<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog\Constraint;

/**
 *
 * @author A760526
 */
interface PrimaryKeyConstraintInterface extends ConstraintInterface {
    /**
     * le constraint vanno associate solo alle colonne, non alle tabelle
     * se una constraint ha bisogno di sapere a quale tabella fa riferimento
     * lo chiede alla colonna
     * 
     * tutte le constraint fanno riferimento ad una o più colonne
     * le foreign key possono essere inoltre di riferimento ad un campo
     * di altra tabella. in questo caso la classe avrà i suoi metodi
     * per puntare alla tabella esterna
     */
}
