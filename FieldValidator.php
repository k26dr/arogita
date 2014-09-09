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
        $this->screenBannedFields($table, $fields);
    }

    public function validateInsertFields($table, $fields)
    {
        $this->validateTable($table);
        $this->validateFieldsExist($table, $fields);
        $this->validateRequiredFields($table, $fields);
        $this->screenBannedFields($table, $fields);
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

    public function validateSelectPrimariesFields ($table, $patients, $last_sync) {
        $this->validateTable($table);
        if ($this->mapper->getAutoUpdateField($table) == null)
            throw new NoAutoUpdateFieldException($table);
    }

    public function validateSelectFields ($table, $patient_ids, $last_sync) {
        $this->validateTable($table);
        if (!gettype($patient_ids) == 'array')
            throw new BadUnitFieldException('patient_ids');
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
        if (!in_array('pid', array_keys($where)))
            throw new MissingFieldException('pid');
    }

    private function screenBannedFields ($table, $fields) {
        $update_field = $this->mapper->getAutoUpdateField($table);
        $auto_increment = $this->mapper->getAutoIncrementField($table);
        foreach($fields as $field => $value) {
            if ($field == $update_field || $field == $auto_increment)
                throw new InvalidFieldException($field);
        }
    }

} 