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
$core->LoginFunctions();

if (@$_SESSION['equinox_code_shops'] == array(0=>0) && @$_SESSION['equinox_code_permission'][1] != 1)
{
	//Not allowed to view customers!
	unset($core->links["customers.php"]);
}
if (@$_SESSION['equinox_code_permission'][0] != 1)
{
	unset($core->links["admin.php"]);
}
$twig->addGlobal('__corelinks', $core->links);

$test = bindec('11');

$test = decbin($test);
$test = strrev($test);

$_SESSION['equinox_code_permission'] = $test;
$twig->addGlobal('__permission', $test);

$twig->addGlobal('__cp', array(
    'links' => $twig->render('core_links'),
    'userbox' => $twig->render('core_userblock', array('name' => $_SESSION['equinox_code_username'], 'permission' => $_SESSION['equinox_code_permission'], 'mult' => @$_SESSION['equinox_code_multiple']))
));