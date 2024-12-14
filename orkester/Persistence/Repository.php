<?php

namespace Orkester\Persistence;

use Illuminate\Support\Arr;
use Orkester\Persistence\Criteria\Criteria;
use Orkester\Persistence\Enum\Association;
use Orkester\Persistence\Map\AssociationMap;
use Orkester\Persistence\Map\ClassMap;
use Spatie\LaravelData\Data;

class Repository
{
    protected static array $className;

    protected static function getClassName(): string
    {
        $class = get_called_class();
        if (!isset(static::$className[$class])) {
            $className = str_replace("App\\Repositories\\", "", $class);
            static::$className[$class] = $className;
        }
        return static::$className[$class];
    }

    public static function getClassMap(): ClassMap
    {
        return PersistenceManager::getClassMap(self::getClassName());
    }

    public static function getCriteria(string $databaseName = null): Criteria
    {
        $classMap = PersistenceManager::getClassMap(self::getClassName());
        $connection = PersistenceManager::getConnection($databaseName);
        $criteria = new Criteria($connection);
        return $criteria->setClassMap($classMap);
    }

    public static function getById(int $id): object
    {
        return static::find($id);
    }

    public static function list(object|array|null $filter = null, array $select = ['*'], array|string $order = ''): array
    {
        $criteria = static::getCriteria();
        if (!empty($select)) {
            $criteria->select($select);
        }
        $criteria->filter($filter);
        $criteria->order($order);
        return $criteria->get()->toArray();
    }

    public static function all(array|string $order = ''): array
    {
        return static::list(null,['*'], $order);
    }

    public static function filter(array|null $filters, Criteria|null $criteria = null): Criteria
    {
        $criteria = $criteria ?? static::getCriteria();
        if (!empty($filters)) {
            $filters = is_string($filters[0]) ? [$filters] : $filters;
            foreach ($filters as [$field, $op, $value]) {
                if (!is_null($value)) {
                    $criteria->where($field, $op, $value);
                }
            }
        }
        return $criteria;
    }

    public static function first($conditions, array $select = ['*']): object|null
    {
        $criteria = static::getCriteria()->range(1, 1);
        if (!empty($select)) {
            $criteria->select($select);
        }
        $result = static::filter($conditions, $criteria)->get()->toArray();
        return empty($result) ? null : $result[0];
    }

    public static function find(int $id, array $columns = ['*']): ?object
    {
        $columns ??= static::getClassMap()->getAttributesNames();
        return static::getCriteria()->find($id, $columns);
    }

    public static function getKeyAttributeName(): string
    {
        return static::getClassMap()->keyAttributeName;
    }

    public static function getAssociation(string $associationChain, int $id): array
    {
        return static::getCriteria()
            ->select($associationChain . '.*')
            ->where('id', '=', $id)
            ->get()
            ->toArray();
    }

    public static function deleteAssociation(string $associationChain, int $id)
    {
        return static::getCriteria()
            ->select($associationChain . '.*')
            ->where('id', '=', $id)
            ->delete();
    }

    public static function save(object $model): ?int
    {
        $key = static::getKeyAttributeName();
        if (is_null($model?->$key ?? null)) {
            unset($model->key);
        }
        $fields = (array)static::prepareWrite($model);
        $criteria = self::getCriteria();
        $criteria->upsert([$fields], [$key], array_keys($fields));
        return $model->$key ?? $criteria->getConnection()->getPdo()->lastInsertId();
    }

    protected static function prepareWrite(object $data): object
    {
        $classMap = static::getClassMap();
        $data = get_object_vars($data);
        $validAttributes = array_keys($classMap->insertAttributeMaps);
        return (object)Arr::only($data, $validAttributes);
    }

    public static function insert(object $data): int|string
    {
        $row = static::prepareWrite($data);
        $criteria = static::getCriteria();
        $criteria->insert((array)$row);
        return $criteria->getConnection()->getPdo()->lastInsertId() ?? 0;
    }

    public static function update(object $data): void
    {
        $row = static::prepareWrite($data);
        $key = static::getKeyAttributeName();
        static::getCriteria()
            ->where(static::getKeyAttributeName(), '=', $row->$key)
            ->update((array)$row);
    }

    public static function upsert(object $data, array $uniqueBy, $updateColumns = null): ?int
    {
        $row = static::prepareWrite($data);
        $criteria = static::getCriteria();
        return $criteria->upsert((array)$row, $uniqueBy, $updateColumns);
    }

    public static function delete(int|string $id): int
    {
        return static::getCriteria()
            ->where(static::getKeyAttributeName(), '=', $id)
            ->delete();
    }

    public static function insertUsingCriteria(array $fields, Criteria $usingCriteria): ?int
    {
        $usingCriteria->applyBeforeQueryCallbacks();
        $criteria = static::getCriteria();
        $criteria->insertUsing($fields, $usingCriteria);
        $lastInsertId = $criteria->getConnection()->getPdo()->lastInsertId();
        return $lastInsertId;
    }

    public static function criteriaByFilter(object|null $params, array $select = []): Criteria
    {
        $criteria = static::getCriteria();
        if (!empty($select)) {
            $criteria->select($select);
        }
        if (!is_null($params)) {
            if (!empty($params->pagination->rows)) {
                $page = $params->pagination->page ?? 1;
                $criteria->range($page, $params->pagination->rows);
            }
            if (!empty($params->pagination->sort)) {
                $criteria->orderBy(
                    $params->pagination->sort . ' ' .
                    $params->pagination->order
                );
            }
        }
        return static::filter($params->filter, $criteria);
    }

    public static function exists(array $conditions): bool
    {
        return !is_null(static::filter($conditions));
    }

    protected static function getManyToManyAssociation(string $associationName): AssociationMap
    {
        $classMap = static::getClassMap();
        $association = $classMap->getAssociationMap($associationName);
        if (empty($association)) {
            throw new \InvalidArgumentException("Unknown association: $associationName");
        }
        if (empty($association->associativeTable)) {
            throw new \InvalidArgumentException("append association requires associative table");
        }
        return $association;
    }

    public static function appendManyToMany(string $associationName, mixed $id, array $associatedIds): int
    {
        $association = static::getManyToManyAssociation($associationName);
        $columns = array_map(fn($aId) => [
            $association->toKey => $aId,
            $association->fromKey => $id
        ],
            $associatedIds
        );
        return static::getCriteria()
            ->from($association->associativeTable)
            ->upsert($columns, [$association->toKey, $association->fromKey]);
    }

    public static function deleteManyToMany(string $associationName, mixed $id, ?array $associatedIds): void
    {
        $association = static::getManyToManyAssociation($associationName);
        $criteria = static::getCriteria();
        $criteria
            ->from($association->associativeTable)
            ->where($association->fromKey, '=', $id);
        if (is_array($associatedIds)) {
            $criteria->where($association->toKey, 'IN', $associatedIds);
        }
        $criteria->delete();
    }

    public static function replaceManyToMany(string $associationName, mixed $id, array $associatedIds): void
    {
        PersistenceManager::beginTransaction();
        self::deleteManyToMany($associationName, $id, null);
        self::appendManyToMany($associationName, $id, $associatedIds);
        PersistenceManager::commit();
    }

    public static function getAssociationMap(string $name): ?AssociationMap
    {
        return static::getClassMap()->getAssociationMap($name);
    }

    public static function saveAssociation(object $model, string $associationName, array|object $associated): void
    {
        $keyName = static::getKeyAttributeName();
        $id = $model->$keyName;
        $associationMap = static::getAssociationMap($associationName);
        $cardinality = $associationMap->cardinality;
        if ($cardinality == Association::ONE) {
            $key = $associationMap->fromKey;
            $model->$key = $associated->$key;
        } else {
            $key = $associationMap->toKey;
            $associatedIds = array_map(fn($associatedModel) => $associatedModel->$key, $associated);
            static::replaceManyToMany($associationName, $id, $associatedIds);
        }
    }

    public static function getAssociationById(string $associationChain, int $id, string $toClassName, int|null $idLanguage = null): array
    {
        $toClassMap = PersistenceManager::getClassMap($toClassName);
        $columns = array_values(array_map(fn($x) => $associationChain . '.' . $x, $toClassMap->getAttributesNames()));
        $criteria = static::getCriteria()
            ->select($columns)
            ->where('id', '=', $id);
        if (!is_null($idLanguage)) {
            $criteria->where($associationChain . '.idLanguage', '=', $idLanguage);
        }
        return $criteria
            ->get()
            ->toArray();
    }

    public static function retrieveAssociation(object $model, string $associationName, int|null $idLanguage = null): null|array|object
    {
        $keyName = static::getKeyAttributeName();
        $id = $model->$keyName;
        $associationMap = static::getAssociationMap($associationName);
        $data = static::getAssociationById($associationName, $id, $associationMap->toClassName, $idLanguage);
        $cardinality = $associationMap->cardinality;
        if (count($data) == 0) {
            return (($cardinality == Association::ONE) ? null : []);
        } else {
            if ($cardinality == Association::ONE) {
                return $data[0];
            } else {
//                $associations = [];
//                foreach ($data as $row) {
////                    $association = new $toClassName;
////                    $association->setData($row);
////                    $associations[$association->getId()] = $association;
//                    $associations[] = (object)$row;
//                }
                return $data;
            }
        }
    }

    public static function query(string $cmd, string $database = null): ?array
    {
        $database ??= config('database.default');
        return PersistenceManager::getConnection($database)->select($cmd);
    }

    public static function beginTransaction() {
        PersistenceManager::beginTransaction();
    }

    public static function commit() {
        PersistenceManager::commit();
    }

    public static function rollback() {
        PersistenceManager::rollback();
    }

    /*
    public static function table(string $name)
    {
        static::getClassMap()->table($name);
    }

    public static function attribute(
        string $name,
        string $field = '',
        Type   $type = Type::STRING,
        Key    $key = Key::NONE,
        string $reference = '',
        string $alias = '',
        string $default = null,
        bool   $nullable = true,
        bool   $virtual = false)
    {
        static::getClassMap()->attribute($name, $field, $type, $key, $reference, $alias, $default, $nullable, $virtual);
    }

    public static function associationMany(string $name,
                                           string $model,
                                           string $keys = '',
                                           Join   $join = Join::INNER,
                                           string $associativeTable = '',
                                           string $order = '')
    {
        static::getClassMap()->associationMany($name, $model, $keys, $join, $associativeTable, $order);
    }

    public static function associationOne(
        string $name,
        string $model = '',
        string $key = '',
        string $base = '',
        array  $conditions = [],
        Join   $join = Join::INNER,
    ) {
        static::getClassMap()->associationOne($name, $model, $key, $base, $conditions, $join);
    }
    */
}
