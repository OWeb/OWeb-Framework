<?php
/**
 * @file Allows to launch the oweb console.
 */

// First load OWeb,
require_once __DIR__ . '/OWeb.php';

if (!isset($_SERVER['REMOTE_ADDR']))
    $_SERVER['REMOTE_ADDR'] = "";

$OWeb = new OWeb\Oweb($argv);

$OWeb->init('OWeb\console\module\Extension\Console');