<?php

/**
 * Created by PhpStorm.
 * User: kedar
 * Date: 9/3/14
 * Time: 9:05 AM
 */
class UnitHandler
{
    private $validator;
    private $query;
    private $mapper;

    public function __construct(UnitValidator $validator, EasyQuery $query, FieldMapper $mapper)
    {
        $this->validator = $validator;
        $this->query = $query;
        $this->mapper = $mapper;
    }

    // @returns response unit
    // @ throws ArogitaSyncException
    public function execute($unit)
    {
        $this->validator->validateUnit($unit);
        $func = "execute" . ucfirst($unit['sync']);
        return $this->$func($unit);
    }
    private function executeAuth($unit)
    {
        if (!$this->query->authenticate($unit['user'], $unit['pass']))
            throw new AuthException();
        return true;
    }

    private function executePush($unit)
    {
        if ($unit['operation'] == 'upsert') {
            if (isset($unit['where']))
                return $this->query->upsert($unit['table'], $unit['fields'], $unit['where']);
            else
                return $this->query->upsert($unit['table'], $unit['fields']);
        }
        else if ($unit['operation'] == 'delete')
            return $this->query->delete($unit['table'], $unit['where']);
    }

    private function executePull ($unit)
    {
        $this->validator->validateUnit($unit);
        $tables = $this->mapper->getTablesWithPid();
        $pull_rows = array();
        foreach ($tables as $table) {
            try {
                $rows = $this->query->selectPrimaries($table, $unit['patients'], $unit['last_sync']);
            } catch (NoPrimaryKeyFieldException $e) {
                continue;
            }
            foreach ($rows as $row) {
                $pull_unit = array('table' => $table, 'operation' => 'upsert', 'fields' => array());
                foreach ($row as $field => $value) {
                    if ($value != "" && $value != null && $value != "0000-00-00 00:00:00")
                        $pull_unit['fields'][$field] = $value;
                }
                array_push($pull_rows, $pull_unit);
            }
        }
        return array_merge($unit, array('updates' => $pull_rows));
    }

    public function expandPull ($initial_pull) {
        $expanded_pull = $initial_pull;
        foreach($initial_pull['updates'] as $i => $partial_row) {
            $rows = $this->query->select($partial_row['table'], $partial_row['fields']);
            foreach ($rows[0] as $field => $value) {
                if ($value != "" && $value != null && $value != "0000-00-00 00:00:00")
                    $expanded_pull['updates'][$i]['fields'][$field] = $value;
            }
        }
        return $expanded_pull;
    }
} 