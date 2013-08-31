<?php

/*
Code written and managed by Dario Magliocchetti-Lombi for e.quinox

Copying is under no circumstances allowed, unless prior WRITTEN (not email) consent from author.

COPY 2011-2012, dario-ml
www.dario-ml.com

Page Name:			core.php
Description:		Holds the core variables for most of the script!

*/
date_default_timezone_set('Europe/London');

if (!defined("SAFE_ZONE"))
{
        echo "Fatal Error : Not called from Safe Zone!";
        exit();
}

include 'Twig/Autoloader.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, array('debug' => true));
$twig->addExtension(new Twig_Extension_Debug());

$db = new mysqli("localhost", "user", "password", "database");

include('core.class.php');
$core = new eQuinox($db);

$twig->addGlobal('__currpage', $core->page);
