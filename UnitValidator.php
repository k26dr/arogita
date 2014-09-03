<?php
/**
 * Created by PhpStorm.
 * User: kedar
 * Date: 9/3/14
 * Time: 9:05 AM
 */

class UnitValidator {

    public function validateUnit ($unit) {
        $valid_sync = array('pull', 'push', 'auth');
        if (!isset($unit['sync']))
            throw new BadSyncTypeException("null");
        if ( !in_array($unit['sync'], $valid_sync))
            throw new BadSyncTypeException($unit['sync']);
        $func = "validate" . ucfirst($unit['sync']) . "Unit";
        $func();
    }

    private function validatePullUnit ($unit) {
        $fields = array('patients', 'last_sync');
        $this->validateUnitFields($unit, $fields);
        $this
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