<?php

session_start();opcache_reset();
error_reporting(E_ALL);
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/db.class.php';
$db = new Database();

if($_SESSION['uid']){
 if(!$db->tieneClausulas($_SESSION['uid'])) {
  if ($_POST['clausula1']) {
       $db->insertaClausula($_SESSION['uid'], $_POST['clausula1']); echo 1;
  }
  if ($_POST['clausula2'] && $_POST['clausula2'] != '') {
       $db->insertaClausula($_SESSION['uid'], $_POST['clausula2']); echo 2;
  }
  if ($_POST['clausula3'] && $_POST['clausula3'] != '') {
       $db->insertaClausula($_SESSION['uid'], $_POST['clausula3']); echo 3;
  }
  if ($_POST['clausula4'] && $_POST['clausula4'] != '') {
       $db->insertaClausula($_SESSION['uid'], $_POST['clausula4']); echo 4;
  }
  if ($_POST['clausula5'] && $_POST['clausula5'] != '') {
       $db->insertaClausula($_SESSION['uid'], $_POST['clausula5']); echo 5;
  }
}
}
?>
