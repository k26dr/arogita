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
        date_default_timezone_set('Asia/Kolkata');
    }

    public function upsert ($table, array $fields, array $where = array()) {
        // see if it's valid for insertion
        try {
            $this->validator->validateInsertFields($table, $fields);
            return $this->capsule->table($table)->insert($fields);
        } catch (MissingFieldException $e) {
            // continue to update
        } catch (PDOException $e) {
            if (!$e->getCode() == "23000")  // not a duplicate key
                throw $e;
        } // all other exceptions are thrown

        // default where clause
        if (count($where) == 0 && isset($fields['pid']))
            $where = array('pid' => $fields['pid']);

        // see if it's valid for update
        $this->validator->validateUpdateFields($table, $fields, $where);
        $count = $this->count($table, $where);
        if ($count != 1)
            throw new BadQueryException($where, $count);

        // update query
        return $this->constructQuery($table, $where)->update($fields);
    }

    // @throws BadQueryException
    // @throws NoTableException
    // @throws InvalidFieldException
    // @throws MissingFieldException
    public function delete ($table, array $where) {
        $this->validator->validateDeleteFields($table, $where);
        $count = $this->count($table, $where);
        if ($count > 1)
            throw new BadQueryException($where, $count);
        return $this->constructQuery($table, $where)->delete();
    }

    // @throws NoTableException
    // @throws InvalidFieldException
    public function count ($table, array $where) {
        $this->validator->validateCountFields($table, $where);
        return $this->constructQuery($table, $where)->count();
    }

    public function selectPrimaries ($table, array $patient_ids, $last_sync) {
        $this->validator->validateSelectPrimariesFields($table, $patient_ids, $last_sync);
        $update_field = $this->mapper->getAutoUpdateField($table);
        $primary_field = $this->mapper->getPrimaryKeyField($table);
        $date = new DateTime();
        $date->setTimestamp($last_sync);
        return $this->capsule->table($table)
            ->whereIn('pid', $patient_ids)
            ->where($update_field, '>', $date->format('Y-m-d H:i:s'))
            ->get(array($primary_field));
    }

    public function authenticate ($user, $pass) {
        $count = $this->capsule->table('users')
            ->where('username', '=', $user)
            ->where('password', '=', $pass)
            ->where('authorized', '=', 1)
            ->count();
        if ($count == 1)
            return true;
        return false;
    }

    public function select ($table, $where) {
        $this->validator->validateSelectFields($table, $where);
        return $this->constructQuery($table, $where)->get();
    }

    private function constructQuery ($table, $where) {
        $query = $this->capsule->table($table);
        foreach ($where as $field => $value) {
            $query = $query->where($field, '=', $value);
        }
        return $query;
    }
} 