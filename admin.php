<?php

/*
Code written and managed by Dario Magliocchetti-Lombi for e.quinox

Copying is under no circumstances allowed, unless prior WRITTEN (not email) consent from author.

COPY 2011-2012, dario-ml
www.dario-ml.com

Page Name:		index.php
Description:		Main page
Created:       		22 December 2011
Last Modified: 		25 December 2011


*/

define("SAFE_ZONE", 1);

require('global.php');

$links_ = $twig->render('core_links');
$userbox_ = $twig->render('core_userblock', array('name' => $_SESSION['equinox_code_username']));

if ($_GET['subpage'] == 'editcus' && is_numeric($_GET['cid']))
{
    $row = $db->query("SELECT * FROM customers WHERE customerID = '".$db->escape_string(@$_GET['cid'])."' LIMIT 1")->fetch_assoc();
    $content .= $twig->render('admin_cusform',array('member' => $row));
}
elseif ($_GET['subpage'] == 'newcust' || $_GET['subpage'] == 'editcus')
{
    $content .= $twig->render('admin_cusform',array('member' => $row, 'new' => true));
}

echo $twig->render('core', array('links'=>$links_, 'userbox'=>$userbox_, 'content'=>$content));

