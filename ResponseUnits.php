<?php
/**
 * Created by PhpStorm.
 * User: kedar
 * Date: 9/5/14
 * Time: 10:59 AM
 */

class ResponseUnits {

    public function success ($sync) {
        return array('sync' => $sync, 'message' => 'ok', 'code' => 200);
    }

    public function error (array $unit, Exception $e) {
        if ($e instanceof ArogitaSyncException)
            return array(
                'error' => 'ArogitaAPIException',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'request' => $unit
            );
        return array(
            'error' => 'Unknown Exception',
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'request' => $unit
        );
    }
} 