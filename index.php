<?php
if($_SERVER["HTTPS"] != "on"){ header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
  }
if(!session_id()) {
    session_start();
}
//opcache_reset();
//error_reporting(E_ALL);
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/config.php';require_once __DIR__ . '/inc/db.class.php';
$loader = new Twig_Loader_Filesystem('templates/');
$twig = new Twig_Environment($loader, array());
$db = new Database(); $accessToken = '';

//print_r($_SESSION);
$helper = $fb->getRedirectLoginHelper();
$_SESSION['FBRLH_state']=$_GET['state'];
$permissions = ['email']; // optional
try {
	if (isset($_SESSION['facebook_access_token'])) {
		$accessToken = $_SESSION['facebook_access_token'];
	} else {
   $accessToken = $helper->getAccessToken('https://contratomycombo.com/');   //	$accessToken = $helper->getAccessToken();
	}
} catch(Facebook\Exceptions\FacebookResponseException $e) {
 	echo 'Graph returned an error1: ' . $e->getMessage();
  	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
 	// When validation fails or other local issues
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
  	header("Location: index.php");
 }
if (isset($accessToken)) {
	if (isset($_SESSION['facebook_access_token'])) {
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	} else {
		// getting short-lived access token
		$_SESSION['facebook_access_token'] = (string) $accessToken;
	  	// OAuth 2.0 client handler
		$oAuth2Client = $fb->getOAuth2Client();
		// Exchanges a short-lived access token for a long-lived one
		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
		$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
		// setting default access token to be used in script
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	}
	// redirect the user back to the same page if it has "code" GET variable
	if (isset($_GET['code'])) {
		header('Location: ./');
	}
	// getting basic info about user
	try {
		$profile_request = $fb->get('/me?fields=name,first_name,last_name,email');
		$profile = $profile_request->getGraphNode()->asArray();
    if(!$_SESSION['uid']) {
      $_SESSION['uid'] = $db->getUserid($profile['id']);
    }
    $db->checkuser($profile['id'], $profile['name'], $profile['email']);

	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		echo 'Graph returned an error2: ' . $e->getMessage();session_destroy();
		// redirecting user back to app login page
		header("Location: ./");
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		header("Location: logout.php");
	}
        $id = $db->getUserid($profile['id']);
            list ($clausulas, $totalc)  = $db->getClausulasUid(1,$id);
            list ($ranking, $totalcr)  = $db->getClausulasUidRanking(1,$id);

        if($db->tieneClausulas($id)) { $tienec = 1; } else { $tienec = 0; }
        echo $twig->render('index.html', array("URL"=>URL,"tienec" => $tienec, "clausulas" => $clausulas,"ranking" => $ranking, "totalc" => $totalc, "totalcr" => $totalcr));
  	// Now you can redirect to another page and use the access token from $_SESSION['facebook_access_token']
} else {
	// replace your website URL same as added in the developers.facebook.com/apps e.g. if you used http instead of https and you used non-www version or www version of your website then you must add the same here
	$loginUrl = $helper->getLoginUrl("https://contratomycombo.com/", $permissions);
     echo $twig->render('no-login.html', array("loginurl" => $loginUrl));
}
