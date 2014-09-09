<?php
/**
 * Created by PhpStorm.
 * User: kedar
 * Date: 9/3/14
 * Time: 9:05 AM
 */

require_once("exceptions/all.php");

class UnitValidator {

    public function validateUnit ($unit) {
        $valid_sync = array('pull', 'push', 'auth');
        if (!isset($unit['sync']))
            throw new MissingUnitFieldException('sync');
        if ( !in_array($unit['sync'], $valid_sync))
            throw new BadUnitFieldException('sync');
        $func = "validate" . ucfirst($unit['sync']) . "Unit";
        return $this->$func($unit);
    }

    private function validatePullUnit ($unit) {
        $fields = array('patients', 'last_sync');
        $this->validateUnitFields($unit, $fields);
        if (gettype($unit['patients']) != 'array' || count($unit['patients']) == 0)
            throw new BadUnitFieldException('patients');
        if (time() * 200 < $unit['last_sync'])
            throw new LastSyncValueException();
        return true;
    }

    private function validateAuthUnit ($unit) {
        $fields = array('user', 'pass', 'client_id');
        $this->validateUnitFields($unit, $fields);
        return true;
    }

    private function validatePushUnit ($unit) {
        $fields = array('table', 'operation');
        $this->validateUnitFields($unit, $fields);
        $valid_operations = array('delete', 'upsert');
        if (!in_array($unit['operation'], $valid_operations))
            throw new BadUnitFieldException('operation');
        if ($unit['operation'] == 'delete' && !isset($unit['where']))
            throw new MissingUnitFieldException('where');
        if ($unit['operation'] == 'upsert' && !isset($unit['fields']))
            throw new MissingUnitFieldException('fields');
        return true;
    }

    private function validateUnitFields (array $unit, array $fields) {
        $keys = array_keys($unit);
        foreach($fields as $field) {
            if (!in_array($field, $keys))
                throw new MissingUnitFieldException($field);
        }
        return true;
    }
} 