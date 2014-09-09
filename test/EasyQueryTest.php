<?php
/**
 * Created by PhpStorm.
 * User: kedar
 * Date: 9/1/14
 * Time: 3:17 PM
 */

require_once("../database.php");
require_once("../EasyQuery.php");
require_once("helpers.php");
require_once("../FieldMapper.php");
require_once("../FieldValidator.php");

$mapper = new FieldMapper($capsule);
$validator = new FieldValidator($mapper);
$query = new EasyQuery($capsule, $mapper, $validator);

// count
$count = $query->count("patient_data", array('pid' => 24));
assert(gettype($count) == 'integer');
assert($count == 1);
assertException('$query->count("gobble", array())', 102); // bad table
assertException('$query->count("patient_data", array("gobble" => 304))', 100); // bad field
assert($query->count("patient_data", array('pid' => 24, 'language' => 'Tamil')) == 1);

// selectPrimaries
$since = strtotime('2 December 2013');
assertException('$query->selectPrimaries("gobble", array(), $since)', 102);
assert(count($query->selectPrimaries('lists', array(24), 10)) == 3);
assert(count($query->selectPrimaries('lists', array(24, 25, 26, 65), $since)) == 10);
assert(count($query->selectPrimaries('lists', array(24, 25, 26, 65, 171, 185), $since)) == 10);
assert(count($query->selectPrimaries('lists', array(24, 25, 26, 65, 2, 7), $since)) == 10);
assert(count($query->selectPrimaries('lists', array(24, 25, 26, 65, 35), $since)) == 11);
assert(count($query->selectPrimaries('lists', array(24, 25, 26, 65, 35), time() + 1000)) == 0);
$rows = $query->selectPrimaries('lists', array(24), 10);
assert(count($rows) == 3);
foreach ($rows as $row) {
    assert(isset($row['id']));
    assert(count($row) == 1);
    assert(!isset($row['pid']));
}

// select
$rows = $query->select("patient_data", array('pid' => 24));
assert(count($rows) == 1);
assert($rows[0]['pid'] == 24);
assert(count($rows[0]) > 20);
assertException('$query->select("gobble", array())', 102);

// upsert
assertException('$query->upsert("gobble", array(), array())', 102);
assertException('$query->upsert("lists", array(), array())', 101);
assertException('$query->upsert("lists", array("pid" => 24, "gobble" => "gook"), array())', 100);
assertException('$query->upsert("patient_data", array("id" => 24, "mname" => "Gyan", "pid" => 24))', 100);
assertException('$query->upsert("patient_data", array("updated_on" => "2013-10-04", "mname" => "Gyan", "pid" => 24))', 100);
assertException('$query->upsert("patient_data", array("mname" => "Gyan"))', 101);

// delete
assertException('$query->delete("gobble", array())', 102);
assertException('$query->delete("lists", array())', 101);
assertException('$query->delete("lists", array("pid" => 26))', 103);

// upsert/selectPrimaries/select patient_data
$before = time() - 1;
assert(count($query->selectPrimaries("patient_data", array(24), $before)) == 0);
$date = new DateTime();
$before_string = $date->setTimestamp($before)->format('Y-m-d H:i:s');
$query->upsert('patient_data', array('pid' => 24, 'financial_review' => $before_string));
$rows = $query->select("patient_data", array('pid' => 24));
assert(count($rows) == 1);
assert($rows[0]['pid'] == 24);
assert($rows[0]['financial_review'] == $before_string);
$after = time() + 1;
$rows = $query->selectPrimaries("patient_data", array(24), $after);
assert(count($rows) == 0);
$rows = $query->selectPrimaries("patient_data", array(24), $before);
assert(count($rows) == 1);

// upsert/count/delete form_camos
$pid = 1000;
while ($query->count('form_camos', array('pid' => $pid)) != 0) { // get an unused pid
    $pid++;
}
$row = array('pid' => $pid, 'user' => 'mother', 'groupname' => 'mygroup');
$query->upsert("form_camos", $row); // without where
assert($query->count('form_camos', array('pid' => $pid)) == 1);
$query->delete('form_camos', array('pid' => $pid));
assert($query->count('form_camos', array('pid' => $pid)) == 0);
$query->upsert("form_camos", $row, array('gooble gooble' => 'glock')); // with where, no difference
assert($query->count('form_camos', array('pid' => $pid)) == 1);
$query->delete('form_camos', array('pid' => $pid));
assert($query->count('form_camos', array('pid' => $pid)) == 0);

echo "Passed all tests";
