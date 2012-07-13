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

if ($_SESSION['equinox_code_permission'][0] != 1)
{
	$core->noAccess();
}
if (@$_GET['subpage'] == 'newcust' || @$_GET['subpage'] == 'editcus')
{
	if (!empty($_POST))
	{
		//Simple data verify , set $error if required
		if (!is_numeric($_POST['cus_tel'])) {
			$error['tel'] = 'Not numeric';
		}
		if (!is_numeric($_POST['cus_shop'])) {
			$error['shop'] = 'Not numeric';
		}
		if (!is_numeric($_POST['cus_box'])) {
			$error['box'] = 'Not numeric';
		}
		if (strlen(trim($_POST['cus_name'])) < 5) {//names need to be longer than 5..?
			$error['name'] = 'Needs to be longer than 5 characters';
		}
		
		//check the data is valid for SQL (Such as shop ID)
		
		//If no errors, save!
		if (!isset($error))
		{
			$error['success'] = "Dario";
		}
	}
    $row = (@is_numeric($_GET['cid'])) ? $db->query("SELECT * FROM customers WHERE customerID = '".$db->escape_string(@$_GET['cid'])."' LIMIT 1")->fetch_assoc() : null;
    $content = $twig->render('admin_cusform',array('member' => @$row, 'error' => @$error, 'new' => (($row == null)?true:false)));
}

echo $twig->render('core', array('content'=>@$content));

