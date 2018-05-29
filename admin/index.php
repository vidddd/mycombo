<?php
if($_SERVER["HTTPS"] != "on"){ header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
  }
session_start();
// opcache_reset(); error_reporting(E_ALL);
require_once '../vendor/autoload.php';
require_once '../inc/config.php';
$loader = new Twig_Loader_Filesystem('templates/');
$twig = new Twig_Environment($loader, array());
require_once '../inc/db.class.php';
$db = new Database();

if (isset($_POST['username']) and isset($_POST['password'])){
  $username = $_POST['username'];$password = $_POST['password'];

   if($db->existeUserAdmin($username, $password)) {
      $_SESSION['username'] = $username;
      $_SESSION['password'] = $password;
        list ($clausulas, $totalpages) = $db->getClausulas(1,0); $seccion = '1';

         echo $twig->render('index.html', array('clausulas' => $clausulas, 'seccion' => $seccion , 'totalpages' => $totalpages ));

   } else {
       echo $twig->render('login.html', array('mensaje' => 'Usuario o password incorrectos'));
   }

}else{
  if($db->existeUserAdmin($_SESSION['username'], $_SESSION['password'])) {

    if($_POST['cid'] && $_POST['clausula']) {
      $db->updateClausula($_POST['cid'],$_POST['clausula']);
    }
    if($_GET['aprovar']) {
      if($_GET['aprovar'] != '')
        $db->cambiarEstadoClausula($_GET['aprovar'],1);
    }
    if($_GET['denegar']) {
      if($_GET['denegar'] != '')
        $db->cambiarEstadoClausula($_GET['denegar'],2);
    }
    if($_GET['pendiente']) {
      if($_GET['pendiente'] != '')
        $db->cambiarEstadoClausula($_GET['pendiente'],0);
    }
    $clausulas = array();
    if($_GET['page'])
       $page = $_GET['page']; else $page = 0;

    switch ($_GET['show']) {
      case 2:
        list ($clausulas, $totalpages) = $db->getClausulas($page,1); $seccion ='2';
          break;
      case 3:
        list ($clausulas, $totalpages) = $db->getClausulas($page,2); $seccion = '3';
          break;
      default:
        list ($clausulas, $totalpages) = $db->getClausulas($page,0); $seccion = '1';
     }

    echo $twig->render('index.html', array('clausulas' => $clausulas, 'seccion' => $seccion , 'totalpages' => $totalpages ));

  } else {
    echo $twig->render('login.html', array());
  }
}
