<?php
namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;


class ViewConstraintModel extends Model {

    public static function map(ClassMap $classMap): void
    {
        self::table('view_constraint');
        self::attribute('idConstraintInstance', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('prefix');
        self::attribute('idConstraint', type: Type::INTEGER);
        self::attribute('constraintType');
        self::attribute('idConstrained', type: Type::INTEGER);
        self::attribute('constrainedType');
        self::attribute('idConstrainedBy', type: Type::INTEGER);
        self::attribute('constrainedByType');
        self::associationOne('entityConstraint', model: EntityModel::class, key: 'idConstraint:idEntity');
        self::associationOne('entityConstrained', model: EntityModel::class, key: 'idConstrained:idEntity');
        self::associationOne('entityConstrainedBy', model: EntityModel::class, key: 'idConstrainedBy:idEntity');
        self::associationOne('constrainedFE', model: FrameElementModel::class, key: 'idConstrained:idEntity');
    }

}
