<?php
/**
 * Created by PhpStorm.
 * User: kedar
 * Date: 9/1/14
 * Time: 1:07 PM
 */

function assertException($statement, $code) {
    extract($GLOBALS);
    $except = false;
    try { eval($statement . ";"); }
    catch (ArogitaSyncException $e) {
        assert($e->getCode() == $code, "Wrong exception thrown for $statement, expected code $code, found $e->code");
        $except = true;
    }
    assert($except, "Exception $code expected, no exception thrown for $statement");
}

function assertInArray($haystack, $needles) {
    foreach($needles as $needle) {
        assert(in_array($needle, $haystack), "$needle in array");
    }
}

function assertNotInArray ($haystack, $needles) {
    foreach($needles as $needle) {
        assert(!in_array($needle, $haystack), "$needle not in array");
    }
}

function speedTest ($statement) {
    extract($GLOBALS);
    $before = microtime(true);
    eval($statement . ";");
    $after = microtime(true);
    $duration = $after - $before;
    echo "SpeedTest: $statement: $duration (s) <br />";
}