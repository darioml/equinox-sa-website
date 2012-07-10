<?php

/*
Code written and managed by Dario Magliocchetti-Lombi for e.quinox

Copying is under no circumstances allowed, unless prior WRITTEN (not email) consent from author.

COPY 2011-2012, dario-ml
www.dario-ml.com

Page Name:			core.php
Description:		Holds the core variables for most of the script!

*/
session_start();

if (!defined("SAFE_ZONE"))
{
        echo "Fatal Error : Not called from Safe Zone!";
        exit();
}

if (@$_GET['do'] == "logout")
{
	session_destroy();
	header("Location: index.php");
}

include("inc/core.php");
//$core->LoginFunctions();
$test = bindec('000000000');
$_SESSION['equinox_code_permission'] = $test;

$test = decbin($test);
$test = (strlen($test) > 10) ? 0 : $test;
$test = strrev(str_pad($test, 10, '0', STR_PAD_LEFT));

$twig->addGlobal('__permission', $test);

$twig->addGlobal('__cp', array(
    'links' => $twig->render('core_links'),
    'userbox' => $twig->render('core_userblock', array('name' => $_SESSION['equinox_code_username'], 'permission' => $_SESSION['equinox_code_permission'], 'mult' => $_SESSION['equinox_code_multiple']))
));