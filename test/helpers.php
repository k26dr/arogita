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
        assert($e->getCode() == $code);
        $except = true;
    }
    assert($except);
}

function assertInArray($haystack, $needles) {
    foreach($needles as $needle) {
        assert(in_array($needle, $haystack));
    }
}

function assertNotInArray ($haystack, $needles) {
    foreach($needles as $needle) {
        assert(!in_array($needle, $haystack));
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