<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Model;
use Orkester\Persistence\Repository;

class DynamicBBoxMMModel extends Model
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('dynamicbboxmm')
            ->attribute('idDynamicBBoxMM', key: Key::PRIMARY)
            ->attribute('frameNumber', type: Type::INTEGER)
            ->attribute('frameTime', type: Type::FLOAT)
            ->attribute('x', type: Type::INTEGER)
            ->attribute('y', type: Type::INTEGER)
            ->attribute('width', type: Type::INTEGER)
            ->attribute('height', type: Type::INTEGER)
            ->attribute('blocked', type: Type::INTEGER)
            ->attribute('idDynamicObjectMM', type: Type::INTEGER, key: Key::FOREIGN)
            ->associationOne('dynamicObjectMM', model: DynamicObjectMMModel::class, key: 'idDynamicObjectMM');
    }

    public static function listByObjectMM($idDynamicObjectMM)
    {
        $criteria = self::getCriteria()
            ->select(['idDynamicBBoxMM', 'frameNumber', 'x', 'y', 'width', 'height', 'frameNumber', 'frameTime', 'blocked'])
            ->where("idDynamicObjectMM", "=", $idDynamicObjectMM)
            ->orderBy('frameNumber');
        return $criteria;
    }

    public static function listByObjectsMM(array $idDynamicObjectMM)
    {
        $criteria = self::getCriteria()
            ->select(['idDynamicBBoxMM', 'idDynamicObjectMM as idObjectMM', 'frameNumber', 'x', 'y', 'width', 'height', 'frameNumber', 'frameTime', 'blocked'])
            ->where("idDynamicObjectMM", "IN", $idDynamicObjectMM)
            ->orderBy('idDynamicBBoxMM,frameNumber');
        return $criteria;
    }

    public static function putFrames($idDynamicObjectMM, $frames)
    {
        self::beginTransaction();
        try {
            $deleteCriteria = self::getCriteria();
            $deleteCriteria->where("idDynamicObjectMM = {$idDynamicObjectMM}");
            $deleteCriteria->delete();
            foreach ($frames as $row) {
                $frame = (object)$row;
                self::setPersistent(false);
                $frame->idDynamicObjectMM = $idDynamicObjectMM;
                self::saveData($frame);
                Timeline::addTimeline("dynamicboxmm", self::getId(), "S");
            }
            self::commit();
        } catch (\Exception $e) {
            self::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public static function updateBBox(array $bbox)
    {
        self::beginTransaction();
        try {
            self::setData($bbox);
            parent::save();
            self::commit();
        } catch (\Exception $e) {
            self::rollback();
            throw new \Exception($e->getMessage());
        }
    }


    /*
    public function save($data = null)
    {
        $transaction = self::beginTransaction();
        try {
            self::setData($data);
            parent::save();
            Timeline::addTimeline("objectframemm", self::getId(), "S");
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }
    */


}
