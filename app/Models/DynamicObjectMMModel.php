<?php

namespace App\Models;

use App\Services\AppService;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Model;
use Orkester\Persistence\Repository;

class DynamicObjectMMModel extends Model
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('dynamicobjectmm')
            ->attribute('idDynamicObjectMM', key: Key::PRIMARY)
            ->attribute('name')
            ->attribute('startFrame', type: Type::INTEGER)
            ->attribute('endFrame', type: Type::INTEGER)
            ->attribute('startTime', type: Type::FLOAT)
            ->attribute('endTime', type: Type::FLOAT)
            ->attribute('status', type: Type::INTEGER)
            ->attribute('origin', type: Type::INTEGER)
            ->attribute('idDocument', type: Type::INTEGER)
            ->attribute('idFrameElement', type: Type::INTEGER)
            ->attribute('idLU', type: Type::INTEGER)
            ->associationOne('document', DocumentModel::class, key: 'idDocument')
            ->associationOne('frameElement', FrameElementModel::class, key: 'idFrameElement')
            ->associationOne('lu', model: LUModel::class, key: 'idLU');
    }


    public static function listByFilter($filter)
    {
        $criteria = self::getCriteria()->select('*')->orderBy('idObjectMM');
        if ($filter->idDocumentMM) {
            $criteria->where("idDocumentMM = {$filter->idDocumentMM}");
        }
        if ($filter->status) {
            $criteria->where("status = {$filter->status}");
        }
        if ($filter->origin) {
            $criteria->where("origin = {$filter->origin}");
        }
        return $criteria;
    }

    public static function getObjectsByDocument($idDocument)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $cmd = <<<SQL

select do.idDynamicObjectMM as idObjectMM,
       do.startFrame,
       do.endFrame,
       do.startTime,
       do.endTime,
       do.status,
       do.origin,
       do.idLU,
       IF(do.idLU,concat(entries_flu.name,'.',lu.name),'') as lu,
       do.idFrameElement,
       fe.idFrame,
       IFNULL(entries_f.name, '')  as frame,
       do.idFrameElement as idFE,
       IFNULL(entries_fe.name, '') as fe,
       color.rgbBg as color
from dynamicobjectmm do
         left join frameelement as fe on do.idFrameElement = fe.idFrameElement
         left join frame as f on fe.idFrame = f.idFrame
         left join entry as entries_f on f.idEntity = entries_f.idEntity
         left join entry as entries_fe on fe.idEntity = entries_fe.idEntity
         left join color on fe.idColor = color.idColor
         left join lu on do.idLU = lu.idLU
         left join frame flu on lu.idFrame = flu.idFrame
         left join entry as entries_flu on flu.idEntity = entries_flu.idEntity
where (do.idDocument = {$idDocument})
and ((entries_f.idLanguage = {$idLanguage}) or (entries_f.idLanguage is null))
and ((entries_fe.idLanguage = {$idLanguage}) or (entries_fe.idLanguage is null))
and ((entries_flu.idLanguage = {$idLanguage}) or (entries_flu.idLanguage is null))
order by do.startTime asc,do.endTime asc

SQL;
        $result = self::query($cmd);
        $oMM = [];
        foreach ($result as $i => $row) {
            $oMM[] = $row->idObjectMM;
        }
        $bboxes = [];
        if (count($result) > 0) {
            $bboxList = DynamicBBoxMMModel::listByObjectsMM($oMM)->all();
            foreach ($bboxList as $bbox) {
                $bboxes[$bbox->idObjectMM][] = $bbox;
            }
        }
        $objects = [];
        foreach ($result as $i => $row) {
            $row->order = $i + 1;
            $row->bboxes = $bboxes[$row->idObjectMM] ?? [];
            $objects[] = $row;
        }
        return $objects;
    }

    public static function updateObject($data)
    {
        debug($data);
        if ($data->idDynamicObjectMM) {
            self::getById($data->idDynamicObjectMM);
        }
        $documentMM = new DocumentMM($data->idDocumentMM);
        self::beginTransaction();
        try {
            $object = [
                'startTime' => $data->startTime,
                'endTime' => $data->endTime,
                'startFrame' => $data->startFrame,
                'endFrame' => $data->endFrame,
                'idDocument' => $documentMM->idDocument,
                'status' => ($data->idFrameElement > 0) ? 1 : 0,
                'origin' => $data->origin ?: '2',
                'idFrameElement' => $data->idFrameElement,
                'idLU' => $data->idLU,
            ];
            self::saveData($object);
            Timeline::addTimeline("dynamicobjectmm", self::getId(), "S");
            if (count($data->frames)) {
                $objectFrameMM = new DynamicBBoxMMModel();
                $objectFrameMM->putFrames(self::idDynamicObjectMM, $data->frames);
            }
            self::commit();
        } catch (\Exception $e) {
            debug($e->getMessage());
            self::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public static function updateObjectData($data)
    {
        if ($data->idObjectMM != -1) {
            self::getById($data->idObjectMM);
        }
        $transaction = self::beginTransaction();
        try {
            $object = (object)[
                'startTime' => $data->startTime,
                'endTime' => $data->endTime,
                'startFrame' => $data->startFrame,
                'endFrame' => $data->endFrame,
                'idDocumentMM' => $data->idDocumentMM,
                'status' => ($data->idFrameElement > 0) ? 1 : 0,
                'origin' => $data->origin ?: '2',
                'idFrameElement' => $data->idFrameElement,
                'idLU' => $data->idLU,
            ];
            mdump(self::getData());
            self::save($object);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public static function delete($id): int
    {
        self::beginTransaction();
        try {
            DynamicBBoxMMModel::getCriteria()
                ->where('idDynamicObjectMM', '=', $id)
                ->delete();
            parent::delete($id);
            self::commit();
            return $id;
        } catch (\Exception $e) {
            self::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public static function deleteObjects($idToDelete)
    {
        $transaction = self::beginTransaction();
        try {
            $objectFrameMM = new DynamicBBoxMMModel();
            $deleteCriteria = $objectFrameMM->getDeleteCriteria();
            $deleteCriteria->where('idDynamicObjectMM', 'IN', $idToDelete);
            $deleteCriteria->delete();
            $deleteCriteria = self::getDeleteCriteria();
            $deleteCriteria->where('idDynamicObjectMM', 'IN', $idToDelete);
            $deleteCriteria->delete();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public static function deleteObjectFrame($idToDelete)
    {
        $transaction = self::beginTransaction();
        try {
            $objectFrameMM = new DynamicBBoxMMModel();
            $deleteCriteria = $objectFrameMM->getDeleteCriteria();
            $deleteCriteria->where('idDynamicBBoxMM', '=', $idToDelete);
            $deleteCriteria->delete();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    /*
    public function putObjects($data) {
        $objectFrameMM = new ObjectFrameMM();
        $idAnnotationSetMM = $data->idAnnotationSetMM;
        $transaction = self::beginTransaction();
        try {
            $selectCriteria = self::getCriteria()->select('idObjectMM')->where("idAnnotationSetMM = {$idAnnotationSetMM}");
            $deleteFrameCriteria = $objectFrameMM->getDeleteCriteria();
            $deleteFrameCriteria->where("idObjectMM", "IN" , $selectCriteria);
            $deleteFrameCriteria->delete();
            $deleteCriteria = self::getDeleteCriteria();
            $deleteCriteria->where("idAnnotationSetMM = {$idAnnotationSetMM}");
            $deleteCriteria->delete();
            foreach($data->objects as $object) {
                self::setPersistent(false);
                $object->idAnnotationSetMM = $data->idAnnotationSetMM;
                mdump($object);
                if ($object->idFrameElement <= 0) {
                    $object->idFrameElement = '';
                    $object->status = 0;
                } else {
                    $object->status = 1;
                }
                self::save($object);
                $objectFrameMM->putFrames(self::idObjectMM, $object->frames);
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }
    */


    /*
    public function save($data = null)
    {
        $transaction = self::beginTransaction();
        try {
            self::setData($data);
            parent::save();
            Timeline::addTimeline("dynamicobjectmm", self::getId(), "S");
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }
    */

    public static function getByIdFlickr30k($idFlickr30k)
    {
        $criteria = self::getCriteria();
        $criteria->where("idFlickr30k", '=', $idFlickr30k);
        self::retrieveFromCriteria($criteria);
    }


}
