<?php
/**
 * Created by PhpStorm.
 * User: kedar
 * Date: 9/1/14
 * Time: 7:00 PM
 */
require_once("FieldMapper.php");
require_once("exceptions/all.php");

class FieldValidator
{
    private $mapper;

    public function __construct(FieldMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function validateUpdateFields($table, array $fields, array $where)
    {
        $this->validateTable($table);
        $this->validateWhere($where);
        $this->validateFieldsExist($table, $fields);
    }

    public function validateInsertFields($table, $fields)
    {
        $this->validateTable($table);
        $this->validateFieldsExist($table, $fields);
        $this->validateRequiredFields($table, $fields);
    }

    public function validateDeleteFields($table, $where)
    {
        $this->validateTable($table);
        $this->validateWhere($where);
    }

    public function validateCountFields ($table, $where) {
        $this->validateTable($table);
        $this->validateFieldsExist($table, $where);
    }


    private function validateTable($table)
    {
        if (!$this->mapper->hasPidField($table))
            throw new NoTableException($table);
    }

    private function validateFieldsExist($table, $fields)
    {
        $valid_fields = $this->mapper->getFieldNames($table);
        foreach ($fields as $field => $value) {
            if (!in_array($field, $valid_fields))
                throw new InvalidFieldException($field);
        }
    }

    private function validateRequiredFields($table, $fields)
    {
        $required_fields = $this->mapper->getRequiredFields($table);
        foreach ($required_fields as $field => $value) {
            if (!in_array($field, $fields))
                throw new MissingFieldException($field);
        }
    }

    private function validateWhere($where)
    {
        if (!in_array('pid', $where))
            throw new MissingFieldException('pid');
    }

    public function validateInput ($json) {
        try {
            $units = json_decode($json);
        } catch (Exception $e) {
            throw new BadInputException();
        }
        if (gettype($units) != "Array")
            throw new BadInputException();

        foreach ($units as $unit) {
            $valid_sync = array('pull', 'push', 'auth');
            if (!isset($unit['sync']))
                throw new BadSyncTypeException("null");
            if ( !in_array($unit['sync'], $valid_sync))
                throw new BadSyncTypeException($unit['sync']);
            $func = "validate" . ucfirst($unit['sync']) . "Unit";
            $func();
        }
    }

    private function validatePullUnit ($unit) {
        $fields = array('patients', 'last_sync');
        $this->validateUnitFields($unit, $fields);
    }

    private function validateAuthUnit ($unit) {
        $fields = array('user', 'pass', 'client_id');
        $this->validateUnitFields($unit, $fields);
    }

    private function validatePushUnit ($unit) {
        if (!isset)
    }

    private function validateUnitFields ($unit, $fields) {
        foreach($fields as $field) {
            if (!in_array($field, $unit))
                throw new MissingUnitFieldException('pull', $field);
        }
    }

} 