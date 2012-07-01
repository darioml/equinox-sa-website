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