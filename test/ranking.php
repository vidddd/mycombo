<?php
session_start();opcache_reset();
error_reporting(E_ALL);
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/db.class.php';
$db = new Database();





 ?>
