<?php
/**
 * Created by PhpStorm.
 * User: kedar
 * Date: 9/4/14
 * Time: 9:45 PM
 */

require_once("../UnitHandler.php");
require_once("../UnitValidator.php");
require_once("../EasyQuery.php");
require_once("../database.php");
require_once("../FieldMapper.php");
require_once("../FieldValidator.php");

$mapper = new FieldMapper($capsule);
$field_validator = new FieldValidator($mapper);
$query = new EasyQuery($capsule, $mapper, $field_validator);
$unit_validator = new UnitValidator();
$handler = new UnitHandler($unit_validator, $query, $mapper);

// auth

// upsert patient_data
$unit = array('sync' => 'push', 'operation' => 'upsert', 'table' => 'patient_data', 'fields' =>
    array('pid' => '24', 'mname' => 'test'));
assert($handler->execute($unit));
$rows = $capsule->table('patient_data')->where('pid', '=', '24')->get();
assert($rows[0]['mname'] == 'test');
$unit = array('sync' => 'push', 'operation' => 'upsert', 'table' => 'patient_data', 'fields' =>
    array('pid' => '24', 'mname' => 'revert'));
assert($handler->execute($unit));
$rows = $capsule->table('patient_data')->where('pid', '=', '24')->get();
assert($rows[0]['mname'] == 'revert');

// upsert/delete form_camos
$pid = 1000;
while ($query->count('form_camos', array('pid' => $pid)) != 0) { // get an unused pid
    $pid++;
}
$unit = array('sync' => 'push', 'operation' => 'upsert', 'table' => 'form_camos', 'fields' =>
    array('pid' => $pid, 'user' => 'mother', 'groupname' => 'mygroup'));
assert($handler->execute($unit));
assert($query->count('form_camos', array('pid' => $pid)) == 1);
$unit = array('sync' => 'push', 'operation' => 'delete', 'table' => 'form_camos', 'where' => array('pid' => $pid));
assert($handler->execute($unit));
assert($query->count('form_camos', array('pid' => $pid)) == 0);

// initialPull
$unit = array('sync' => 'pull', 'last_sync' => 0, 'patients' => array(24, 25, 26));
$pull = $handler->initialPull($unit);
assert(isset($pull['updates']));
foreach ($pull['updates'] as $row) {
    assert(count($row['fields']) == 1);
}

// expandedPull
$expanded = $handler->expandPull($pull);
assert(isset($pull['updates']));
assert(count($expanded['updates'] == $pull['updates']));
foreach($expanded['updates'] as $unit) {
    assert(count($unit['fields']) > 1);
}



echo "Passed all tests";