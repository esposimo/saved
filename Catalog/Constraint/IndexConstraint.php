<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog\Constraint;

/**
 * Description of IndexConstraint
 *
 * @author A760526
 */
class IndexConstraint extends Constraint implements IndexConstraintInterface {

    protected $type = self::CONSTRAINT_INDEX;

}
