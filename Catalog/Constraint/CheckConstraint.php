<?php
namespace smn\lazyc\dbc\Catalog\Constraint;

/**
 * Description of CheckConstraint
 *
 * @author A760526
 */
class CheckConstraint extends Constraint implements CheckConstraintInterface
{
    protected $type = self::CONSTRAINT_CHECK;
}
