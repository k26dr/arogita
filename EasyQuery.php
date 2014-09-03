<?php
/**
 * Created by PhpStorm.
 * User: kedar
 * Date: 8/26/14
 * Time: 5:27 PM
 */

require_once "exceptions/all.php";

use \Illuminate\Database\Capsule\Manager as Capsule;

class EasyQuery {
    private $capsule;
    private $mapper;
    private $validator;

    public function __construct (Capsule $capsule, FieldMapper $mapper, FieldValidator $validator) {
        $this->capsule = $capsule;
        $this->mapper = $mapper;
        $this->validator = $validator;
    }

    public function upsert ($table, array $fields, array $where = array()) {
        // see if it's valid for insertion
        try {
            $this->validator->validateInsertFields($table, $fields);
            $this->capsule->table($table)->insert($fields);
        } catch (MissingFieldException $e) {
            // continue to update
        } catch (PDOException $e) {
            if (!$e->getCode() == "23000")
                throw $e;
        } // all other exceptions are thrown

        // default where clause
        if (count($where) == 0)
            $where = array('pid', $fields['pid']);

        // see if it's valid for update
        $this->validator->validateUpdateFields($table, $fields, $where);
        if ($this->count($table, $where) != 1)
            throw new BadQueryException($where);

        // update query
        return $this->constructQuery($table, $where)->update($fields);
    }

    // @throws BadQueryException
    // @throws NoTableException
    // @throws InvalidFieldException
    // @throws MissingFieldException
    public function delete ($table, array $where) {
        $this->validator->validateDeleteFields($table, $where);
        return $this->constructQuery($table, $where)->delete();
    }

    // @throws NoTableException
    // @throws InvalidFieldException
    public function count ($table, array $where) {
        $this->validator->validateCountFields($table, $where);
        return $this->constructQuery($table, $where)->count();
    }

    private function constructQuery ($table, $where) {
        $query = $this->capsule->table($table);
        foreach ($where as $field => $value) {
            $query = $query->where($field, '=', $value);
        }
        return $query;
    }

    private function handlePDOException (PDOException $e, $table) {
        if ($e->getCode() == "42S02")
            throw new NoTableException($table);
        if ($e->getCode() == "42S22") {
            throw new InvalidFieldException('unspecified field');
        }
    }
} 