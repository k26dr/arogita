<?php

/**
 * Created by PhpStorm.
 * User: kedar
 * Date: 8/26/14
 * Time: 3:38 PM
 */

use \Illuminate\Database\Capsule\Manager as Capsule;

require_once("exceptions/all.php");

class FieldMapper
{
    private $fields = array();
    private $capsule;

    public function __construct(Capsule $capsule)
    {
        $this->capsule = $capsule;
    }

    // @throws  NoTableException
    public function getFields($table)
    {
        if (isset($fields[$table]))
            $columns = $fields[$table];
        else {
            try {
                $columns = $this->capsule->getConnection()->select("show columns from $table");
                $fields[$table] = $columns;
            } catch (PDOException $e) {
                throw new NoTableException($table);
            }
        }
        return $columns;
    }

    // @throws NoTableException
    public function getFieldNames ($table) {
        $columns = $this->getFields($table);
        $names = array();
        foreach($columns as $col) {
            array_push($names, $col['Field']);
        }
        return $names;
    }

    // includes no null fields, excludes auto-increment fields
    // @throws NoTableException
    public function getRequiredFields($table)
    {
        $columns = $this->getFields($table);
        $required_cols = array();
        foreach ($columns as $coldata) {
            if ($coldata['Null'] == "NO" && strpos($coldata['Extra'], 'auto_increment') === false)
                array_push($required_cols, $coldata['Field']);
        }
        return $required_cols;
    }

    // @throws NoTableException
    public function getUniqueFields($table)
    {
        $columns = $this->getFields($table);
        $unique_cols = array();
        $unique_keys = array("PRI", "UNI");
        foreach ($columns as $coldata) {
            if (in_array($coldata['Key'], $unique_keys))
                array_push($unique_cols, $coldata['Field']);
        }
        return $unique_cols;
    }

    // returns boolean
    // @throws NoTableException
    public function hasPidField($table)
    {
        $columns = $this->getFields($table);
        foreach ($columns as $col) {
            if ($col['Field'] == 'pid')
                return true;
        }
        return false;
    }

} 