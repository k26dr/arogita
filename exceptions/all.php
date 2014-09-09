<?php

/**
 * Created by PhpStorm.
 * User: kedar
 * Date: 8/25/14
 * Time: 5:29 PM
 */
abstract class ArogitaSyncException extends Exception
{
    public $code;

    public function __construct($message, $code)
    {
        parent::__construct($message, $this->code);
    }
}

class InvalidFieldException extends ArogitaSyncException
{
    public $field;
    public $code = 100;

    public function __construct($field)
    {
        $this->field = $field;
        $message = "InvalidFieldException: '$field' is not a valid field";
        parent::__construct($message, $this->code);
    }
}

class MissingFieldException extends ArogitaSyncException
{
    public $field;
    public $code = 101;

    public function __construct($field)
    {
        $this->field = $field;
        $message = "MissingFieldException: Expected field '$field' was not found'";
        parent::__construct($message, $this->code);
    }
}

class NoTableException extends ArogitaSyncException
{
    public $table;
    public $code = 102;

    public function __construct($table)
    {
        $this->table = $table;
        $message = "NoTableException: Table '$table' does not exist in database";
        parent::__construct($message, $this->code);
    }

}

class BadQueryException extends ArogitaSyncException
{
    public $where;
    public $code = 103;

    public function __construct($where)
    {
        $this->where = $where;
        $message = "BadQueryException: Where condition yielded more than one result";
        parent::__construct($message, $this->code);
    }
}

class BadInputException extends ArogitaSyncException
{
    public $code = 104;

    public function __construct()
    {
        $message = "BadInputException: Request JSON could not be parsed to sync array";
        parent::__construct($message, $this->code);
    }
}

class AuthException extends ArogitaSyncException
{
    public $code = 107;

    public function __construct()
    {
        $message = "AuthException: Could not authenticate client";
        parent::__construct($message, $this->code);
    }
}

class MissingUnitFieldException extends ArogitaSyncException
{
    public $code = 110;
    public $field;

    public function __construct($field)
    {
        $this->field = $field;
        $message = "MissingUnitFieldException: Required field '$field' is missing";
        parent::__construct($message, $this->code);
    }
}

// field is valid but not usable
class BadUnitFieldException extends ArogitaSyncException
{
    public $code = 111;
    public $field;

    public function __construct($field)
    {
        $this->field = $field;
        $message = "BadUnitFieldException: '$field' contains an ususable value";
        parent::__construct($message, $this->code);
    }
}

class MissingUnitException extends ArogitaSyncException
{
    public $code = 112;
    public $type;

    public function __construct($type)
    {
        $this->type = $type;
        $message = "MissingUnitException: Missing '$type' unit";
        parent::__construct($message, $this->code);
    }
}

class NoAutoUpdateFieldException extends ArogitaSyncException
{
    public $code = 112;
    public $table;

    public function __construct($table)
    {
        $this->table = $table;
        $message = "NoAutoUpdateFieldException: No auto-update field found in '$table'. Make sure to run the
            add_auto_update_fields.php script on your OpenEMR database before using the sync API";
        parent::__construct($message, $this->code);
    }
}

class LastSyncValueException extends ArogitaSyncException {
    public $code = 113;

    public function __construct () {
        $message = "LasySyncValueException: last_sync value should be in seconds since the Unix epoch, not milliseconds";
        parent::__construct($message, $this->code);
    }
}