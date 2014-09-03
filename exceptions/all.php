<?php

/**
 * Created by PhpStorm.
 * User: kedar
 * Date: 8/25/14
 * Time: 5:29 PM
 */
abstract class ArogitaAPIException extends Exception
{
    public function __construct($message, $code)
    {
        parent::__construct($message, $this->code);
    }
}

class InvalidFieldException extends ArogitaAPIException
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

class MissingFieldException extends ArogitaAPIException
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

class NoTableException extends ArogitaAPIException
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

class BadQueryException extends ArogitaAPIException
{
    public $where;
    public $code = 103;

    public function __construct($where)
    {
        $this->where = $where;
        $message = "BadQueryException: Where conditions yielded no results";
        parent::__construct($message, $this->code);
    }
}

class BadInputException extends ArogitaAPIException
{
    public $code = 104;

    public function __construct()
    {
        $message = "BadInputException: Request JSON could not be parsed to sync array";
        parent::__construct($message, $this->code);
    }
}

class AuthException extends ArogitaAPIException
{
    public $code = 107;

    public function __construct()
    {
        $message = "AuthException: Could not authenticate client";
        parent::__construct($message, $this->code);
    }
}

class MissingUnitFieldException extends ArogitaAPIException
{
    public $code = 110;
    public $field;
    public $type;

    public function __construct ($type, $field) {
        $this->type = $type;
        $this->field = $field;
        $message = "MissingUnitFieldException: Unit $type missing necessary field $field";
        parent::__construct($message, $this->code);
    }
}

// field is valid but not usable
class BadUnitFieldException extends ArogitaAPIException {
    public $code = 111;
    public $field;

    public function __construct ($field) {
        $this->field = $field;
        $message = "BadUnitFieldException: '$field' contains an ususable value";
        parent::__construct($message, $this->code);
    }
}

class MissingUnitException extends ArogitaAPIException {
    public $code = 112;
    public $type;

    public function __construct ($type) {
        $this->type = $type;
        $message = "MissingUnitException: Missing $type unit";
        parent::__construct($message, $this->code);
    }




}
