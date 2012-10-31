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
	//We don't have access to view the admin panel
	$core->noAccess();
}

if (@$_GET['subpage'] == 'newcust' || @$_GET['subpage'] == 'editcus')
{
	//check permissions!
	if ((($_GET['subpage'] == 'newcust') && ($core->getPermission(2) != 1)) || (($_GET['subpage'] == 'editcus') && ($core->getPermission(3) != 1)))
	{
		$core->noAccess();
	}
	if ($_GET['subpage'] == 'editcus')
	{
		$check = $db->prepare("SELECT shopID, boxID, deleted FROM customers WHERE customerID = ?");
		$check->bind_param('s', $_GET['cid']);
		$check->execute();
		$check->bind_result($shopid, $boxid, $deleted);
		
		if (!$check->fetch())
		{
			$error['general'] = "Undefined customer ID";
		}
		elseif (!$core->ShopPermission($shopid))
		{
			$core->noAccess();
		}
		elseif ($deleted && !$core->getPermission(4))
		{
			$core->noAccess();
		}
			
		$check->close();
	}
	
	if (!empty($_POST))
	{
		//Simple data verify , set $error if required
		if (strlen(trim($_POST['cus_tel'])) < 1) {
			$error['tel'] = 'Must be filled out';
		}elseif (!is_numeric($_POST['cus_tel'])) {
			$error['tel'] = 'Must be a number';
		}

		if (strlen(trim($_POST['cus_paid'])) < 1) {
			$error['paid'] = 'Must be filled out';
		}elseif (!is_numeric($_POST['cus_paid'])) {
			$error['paid'] = 'Must be a number';
		}
		
		if (strlen(trim($_POST['cus_shop'])) < 1) {
			$error['shop'] = 'Must be filled out';
		} elseif (!is_numeric($_POST['cus_shop'])) {
			$error['shop'] = 'Must be a number';
		} elseif ($_POST['cus_shop'] < 1){
			$error['shop'] = 'Cannot be negative';
		} elseif (!$core->ShopPermission($_POST['cus_shop'])) { //Check that the user can add to this shop ID
			$error['shop'] = "You do not own this shop";
		}
		
		if (strlen(trim($_POST['cus_box'])) < 1) {
			$error['box'] = 'Must be filled out';
		} elseif (substr($_POST['cus_box'], 0, 1) != 's' && substr($_POST['cus_box'], 0, 1) != 'l') {
			$error['box'] = 'Invalid boxID (must begin with s or l)';
		}elseif (!is_numeric(substr($_POST['cus_box'], 1))) {
			$error['box'] = 'Must be a number';
		}elseif ($_POST['cus_box'] != $boxid && $_POST['cus_box'] != 0) {
			//Make sure the box is not in use by another customer
			$check = $db->prepare("SELECT customerID, name FROM customers WHERE boxID = ?");
			$check->bind_param('i', $_POST['cus_box']);
			$check->execute();

			$check->bind_result($cid, $cname);
			if ($check->fetch())
			{	
				$error['box'] = "Box in use by $cname [ID: $cid]";
			}
			$check->close();
		}
		
		if (strlen(trim($_POST['cus_name'])) < 5) {//names need to be longer than 5..?
			$error['name'] = 'Needs to be longer than 5 characters';
		}
		
		//check the data is valid for SQL (Such as shop ID)
		
		
		//If no errors, save!
		if (!isset($error))
		{
			//Are we a new customer (unset id)
			if ($_POST['neworedit'] == 'new')
			{

				//New customer
				$test = $db->prepare("INSERT INTO `customers` (`customerID`, `shopID`, `name`, `telephone`, `boxID`, `notes`, `paid`) VALUES (NULL, ?, ?, ?, ?, ?, ?);");
				$test->bind_param('issssd', $_POST['cus_shop'], $_POST['cus_name'], $_POST['cus_tel'], $_POST['cus_box'], $_POST['cus_notes'], $_POST['cus_paid']);
				$test->execute();
				
				header("Location: admin.php?subpage=newcust&done=" . $test->insert_id);
			}
			elseif ($_POST['neworedit'] == 'edit')
			{
				//We're trying to edit:
				$test = $db->prepare("UPDATE `customers` SET `shopID` = ?, `name` = ?, `telephone` = ?, `boxID` = ?, `notes` = ?, paid = ? WHERE `customerID` = ?;");
				$test->bind_param('issssdi', $_POST['cus_shop'], $_POST['cus_name'], $_POST['cus_tel'], $_POST['cus_box'], $_POST['cus_notes'], $_POST['cus_paid'], $_POST['cus_cid']);
				$test->execute();
				$test->close();
				
				header("Location: admin.php?subpage=editcus&cid=" . $_POST['cus_cid'] . "&done=" . date("H:i:s"));
			}
		}
	} // END !empty($_POST)
	//check for success
	if (isset($_GET['done']))
	{
		$error['success'] = ($_GET['subpage'] == 'newcust') ? "Successfully added customer with ID: $_GET[done]" : "Successfully edited customer @ $_GET[done]";
	}
    $row = (@is_numeric($_GET['cid'])) ? $db->query("SELECT * FROM customers WHERE customerID = '".$db->escape_string(@$_GET['cid'])."' LIMIT 1")->fetch_assoc() : null;
    $content = $twig->render('admin_cusform',array('member' => @$row, 'error' => @$error, 'new' => (($row == null)?true:false), 'olddata'=>$_POST));
}
elseif (@$_GET['subpage'] == 'newadm' || @$_GET['subpage'] == 'editadm')
{
	if (!empty($_POST))
	{
		
	} // END !empty($_POST)
	//
	//check for success
	if (isset($_GET['done']))
	{
		$error['success'] = ($_GET['subpage'] == 'newcust') ? "Successfully added customer with ID: $_GET[done]" : "Successfully edited customer @ $_GET[done]";
	}
	
    $row = (@is_numeric($_GET['aid'])) ? $db->query("SELECT * FROM logins WHERE userID = '".$db->escape_string(@$_GET['aid'])."' LIMIT 1")->fetch_assoc() : null;
    $content = $twig->render('admin_admform',array('member' => @$row, 'error' => @$error, 'new' => (($row == null)?true:false), 'olddata'=>$_POST));
}
else
{
	$content = "Show your permissions..<br />You own shopIDs: ";
	$content .= implode(", ", $_SESSION['equinox_code_shops']);
	$content .= ($core->getPermission(1)) ? " (FULL ACCESS)" : null;
}
echo $twig->render('core', array('content'=>@$content));

