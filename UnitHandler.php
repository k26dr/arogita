<?php
/**
 * Created by PhpStorm.
 * User: kedar
 * Date: 9/3/14
 * Time: 9:05 AM
 */

class UnitHandler {
    private $validator;
    private $query;

    public function __construct (UnitValidator $validator, EasyQuery $query) {
        $this->validator = $validator;
        $this->query = $query;
    }

    // @returns response unit
    public function execute ($unit) {

    }
} 