<?php
/**
 * Created by PhpStorm.
 * User: kedar
 * Date: 8/25/14
 * Time: 2:49 PM
 */

header('Content-Type: application/json');

require_once "database.php"; // brings in eloquent builder $capsule
require_once "config/db_fields.php";
require_once "exceptions/all.php";
require_once "FieldMapper.php";
require_once "UnitHandler.php";
require_once "InputHandler.php";
require_once "UnitValidator.php";
require_once "FieldValidator.php";
require_once "EasyQuery.php";
require_once "ResponseUnits.php";

//////////////////////////////////////////////////////////////////////////////////////////
// these injections should happen in an IoC container when the API is expanded,
// but this will do for now. All the classes are designed to be easily integrated into a
// Direct Injection framework

$mapper = new FieldMapper($capsule);
$unit_validator = new UnitValidator();
$field_validator = new FieldValidator($mapper);
$query = new EasyQuery($capsule, $mapper, $field_validator);
$input_handler = new InputHandler();
$unit_handler = new UnitHandler($unit_validator, $query, $mapper);
$response_units = new ResponseUnits();
////////////////////////////////////////////////////////////////////////

$json = file_get_contents('php://input');
$units = $input_handler->extractArray($json);
$response = array('pull' => array(), 'errors' => array());

// auth
try {
    $unit_handler->execute($input_handler->popAuth($units));
} catch (Exception $e) {
    array_push($response['errors'], $response_units->error($unit, $e));
    echo json_encode($response); exit();
}

// initial pull
try {
    $pull_unit = $input_handler->popPull($units);
    $initial_pull = $unit_handler->execute($pull_unit);
} catch (Exception $e) {
    if (isset($pull_unit))
        array_push($response['errors'], $response_units->error($pull_unit, $e));
}

// push
foreach($units as $unit) {
    try{
        $unit_handler->execute($unit);
    } catch (Exception $e) {
        array_push($response['errors'], $response_units->error($unit, $e));
    }
}

// expand pull
if (isset($initial_pull))
    $response['pull'] = $unit_handler->expandPull($initial_pull);

echo json_encode($response);

