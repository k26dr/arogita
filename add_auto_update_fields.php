<?php
/**
 * Created by PhpStorm.
 * User: kedar
 * Date: 9/4/14
 * Time: 11:27 AM
 */

require_once("FieldMapper.php");
require_once("database.php");

$mapper = new FieldMapper($capsule);

if($mapper->getAutoUpdateField("audit_master") == null)
    $capsule->table("audit_master")->getConnection()->statement("ALTER TABLE audit_master CHANGE created_time created_time TIMESTAMP NOT NULL DEFAULT 0");

foreach ($mapper->getTablesWithPid() as $table) {
    if ($mapper->getAutoUpdateField($table) == null)
        $capsule->table($table)->getConnection()->statement("ALTER TABLE $table ADD updated_on TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
}

echo "Added necessary columns. Database ready for use";