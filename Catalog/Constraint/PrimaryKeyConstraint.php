<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog\Constraint;

/**
 * Description of PrimaryKeyConstraint
 *
 * @author A760526
 */
class PrimaryKeyConstraint extends Constraint implements PrimaryKeyConstraintInterface {

    /**
     * Variabile che definisce il tipo di Constraint della classe
     * @var type 
     */
    protected $type = self::CONSTRAINT_PK;

}
