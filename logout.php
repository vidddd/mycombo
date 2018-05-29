<?php
session_start();
session_unset();
    $_SESSION['FBID'] = NULL;
    $_SESSION['FULLNAME'] = NULL;
    $_SESSION['EMAIL'] =  NULL;
    $_SESSION['facebook_access_token'] = NULL;
header("Location: index.php");        // you can enter home page here ( Eg : header("Location: " ."https://www.krizna.com/home.php");
?>
