<?php
/**
 * Created by PhpStorm.
 * User: kedar
 * Date: 9/3/14
 * Time: 9:56 AM
 */

class InputHandler {

    // @throws BadInputException
    public function extractArray ($json) {
        try {
            $units = json_decode($json, true);
        } catch (Exception $e) {
            throw new BadInputException();
        }
        if (gettype($units) != "array")
            throw new BadInputException();
        return $units;
    }

    public function popAuth (&$units) {
        foreach ($units as $i => $unit) {
            if ($unit['sync'] == 'auth') {
                $auth = $unit;
                unset($unit[$i]);
                return $auth;
            }
        }
        throw new MissingUnitException('auth');
    }

    public function popPull (&$units) {
        foreach ($units as $i => $unit) {
            if ($unit['sync'] == 'pull') {
                $pull = $unit;
                unset($unit[$i]);
                return $pull;
            }
        }
        throw new MissingUnitException('pull');
    }
} 