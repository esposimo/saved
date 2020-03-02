<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog\Constraint;
use smn\lazyc\dbc\Catalog\Constraint\Constraint;
use smn\lazyc\dbc\Catalog\Constraint\UniqueConstraintInterface;

/**
 * Description of UniqueConstraint
 *
 * @author A760526
 */
class UniqueConstraint extends Constraint implements UniqueConstraintInterface {

    protected $type = self::CONSTRAINT_UNIQUE;

}
