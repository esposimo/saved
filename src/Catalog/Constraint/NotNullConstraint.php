<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog\Constraint;

/**
 * Description of NotNullConstraint
 *
 * @author A760526
 */

class NotNullConstraint extends Constraint implements NotNullConstraintInterface {

    protected $type = self::CONSTRAINT_NOT_NULL;

}

