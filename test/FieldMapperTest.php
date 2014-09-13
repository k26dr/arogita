<?php
/**
 * Created by PhpStorm.
 * User: kedar
 * Date: 9/1/14
 * Time: 11:47 AM
 */

echo "<br/>FieldMapperTest<br/>";

require_once("../FieldMapper.php");
require("../database.php");
require("helpers.php");

$mapper = new FieldMapper($capsule);

// getFields
speedTest('$mapper->getFields("patient_data")');
echo "(Cached) "; speedTest('$mapper->getFields("patient_data")');
assert(count($mapper->getFields("lists")) == 28);
assertException('$mapper->getFields("gobble")', 102);

// hasPidField
assert($mapper->hasPidField("lists"));
assert($mapper->hasPidField("patient_data"));
assert(!$mapper->hasPidField("list_options"));
assertException('$mapper->hasPidField("gobble")', 102);

// getFieldNames
assertException('$mapper->getFieldNames("gumdrop")', 102);
assertInArray($mapper->getFieldNames("patient_data"), array("id", 'pid', 'mname', 'street', 'status', 'phone_biz', 'phone_contact'));
assertNotInArray($mapper->getFieldNames("patient_data"), array("gobble", 'mook', 'bumbum'));

// getRequiredFields
assertException('$mapper->getRequiredFields("gummybear")', 102);
assertInArray($mapper->getRequiredFields("patient_data"),
    array('pid', 'city', 'state', 'country_code', 'sex', 'referrer', 'hipaa_voice'));
assertNotInArray($mapper->getRequiredFields("patient_data"),
    array('id', 'DOB', 'financial_review', 'date', 'providerId', 'updated_on'));

// getAutoUpdateField
assert($mapper->getAutoUpdateField('lists') == "modifydate");
assertException('$mapper->getAutoUpdateField("gummy")', 102);

// getTables
$tables = $mapper->getTables();
assert(in_array("patient_data", $tables));
assert(in_array("lists", $tables));
assert(!in_array("madmen", $tables));

// getTablesWithPid
speedTest('$mapper->getTablesWithPid()');
echo "(Cached) "; speedTest('$mapper->getTablesWithPid()');
$pid_tables = $mapper->getTablesWithPid();
assert(in_array("patient_data", $pid_tables));
assert(in_array("lists", $pid_tables));
assert(!in_array("goober", $pid_tables));
assert(!in_array("list_options", $pid_tables));

// getAutoIncrementField
assertException('$mapper->getAutoIncrementField("gobble")', 102);
assert($mapper->getAutoIncrementField("form_camos") == 'id');
assert($mapper->getAutoIncrementField("gprelations") == null);

// getPrimaryKeyField
assert($mapper->getPrimaryKeyField("patient_data") == 'pid');
assert($mapper->getPrimaryKeyField("drugs") == 'drug_id');
assert($mapper->getPrimaryKeyField("amc_misc_data") == null);

echo "Passed FieldMapperTest<br/>";