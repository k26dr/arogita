<?php
/**
 * Created by PhpStorm.
 * User: kedar
 * Date: 8/25/14
 * Time: 2:49 PM
 */

//header('Content-Type: application/json');

require_once "database.php"; // brings in eloquent builder $capsule
require_once "config/db_fields.php";
require_once "exceptions/all.php";

function parseInput ($json) {
    try {
        $units = json_decode($json);
    } catch (Exception $e) {
        throw new BadInputException();
    }
    if (gettype($units) != "Array")
        throw new BadInputException();

    $auth = false;
    $pull = false;
    foreach ($units as $unit) {
        if ()
    }

    return $units;
}

$mapper = new FieldMapper($capsule);
$json = file_get_contents('php://input');
$units = parseInput($json);


