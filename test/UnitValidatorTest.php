<?php
/**
 * Created by PhpStorm.
 * User: kedar
 * Date: 9/4/14
 * Time: 7:11 PM
 */

require_once("../UnitValidator.php");
require_once("helpers.php");

$validator = new UnitValidator();

// validateUnit
$unit = array('sync' => 'garbage_pice', 'user' => 'bash', 'mormon' => 'boss');
assertException('$validator->validateUnit($unit)', 111);

// auth
$unit = array('sync' => 'auth', 'user' => 'magic', 'pass' => 'man');
assertException('$validator->validateUnit($unit)', 110);
$unit = array('sync' => 'auth', 'user' => 'magic', 'pass' => 'man', 'client_id' => 123222);
assert($validator->validateUnit($unit));

// pull
$unit = array('sync' => 'pull', 'patients' => array(24, 26), 'last_sync' => 14111);
assert($validator->validateUnit($unit));
$unit = array('sync' => 'pull', 'patients' => array(24, 26), 'last_sync' => time() * 1000);
assertException('$validator->validateUnit($unit)', 113);
$unit = array('sync' => 'pull', 'patients' => array(), 'last_sync' => time());
assert($validator->validateUnit($unit));
$unit = array('sync' => 'pull', 'last_sync' => time());
assertException('$validator->validateUnit($unit)', 110);

// push upsert
$unit = array('sync' => 'push', 'operation' => 'upsert', 'table' => 'patient_data', 'fields' => array('id' => '24'),
    'where' => array('pid' => 24));
assert($validator->validateUnit($unit));
$unit = array('sync' => 'push', 'operation' => 'upsert', 'fields' => array('id' => '24'), 'where' => array('pid' => 24));
assertException('$validator->validateUnit($unit)', 110);
$unit = array('sync' => 'push', 'operation' => 'upsert', 'table' => 'patient_data', 'where' => array('pid' => 24));
assertException('$validator->validateUnit($unit)', 110);

// push delete
$unit = array('sync' => 'push', 'operation' => 'delete', 'table' => 'patient_data', 'fields' => array('id' => '24'),
    'where' => array('pid' => 24));
assert($validator->validateUnit($unit));
$unit = array('sync' => 'push', 'operation' => 'delete', 'table' => 'patient_data','where' => array('pid' => 24));
assert($validator->validateUnit($unit));
$unit = array('sync' => 'push', 'operation' => 'delete', 'table' => 'patient_data','fields' => array('pid' => '24'));
assertException('$validator->validateUnit($unit)', 110);


echo "Passed all tests";