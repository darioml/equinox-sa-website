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

include("inc/algorithm.class.php");
$eQalg = new eQuinoxAlgo();

$links_ = $twig->render('core_links');
$userbox_ = $twig->render('core_userblock', array('name' => $_SESSION['equinox_code_username']));
$search_ = $twig->render('customers_search', array('query' => @$_GET['search']));
$filter_ = $twig->render('customers_filter');

$results_ = "";

$hidedel = ($core->getPermission(4)) ? null : "deleted = 0 AND ";

if (@$_GET['do'] == "show" && is_numeric(@$_GET['cid'])) //show customer info, if it's numeric
{
	$row = $db->query("SELECT customers.*, count(codes.code) as codesused FROM customers LEFT JOIN codes ON customers.boxID = codes.boxID GROUP BY customerID HAVING {$hidedel}customerID = '".$db->escape_string(@$_GET['cid'])."'");
		
	if ($row->num_rows == 0) //nothing found
	{
		$results_ .= $twig->render('customer_du_none');
	}
	else
	{
		$row = $row->fetch_assoc();
		
		if (!in_array($row['shopID'], $_SESSION['equinox_code_shops']) && $_SESSION['equinox_code_permission'][1] != 1)
		{
			$core->noAccess();
		}
		if ($row['boxID'] != 0)
		{
			//Fetch last 5 codes:
			$result = $db->query("SELECT * FROM codes WHERE boxID = '$row[boxID]' ORDER BY generated DESC LIMIT 5");
			$codes = array();
			while ($code = $result->fetch_assoc())
			{
				$dura = $eQalg->GetUnlockDays($code['code']);

				$tdleft = (($code['generated'] + $eQalg->times[$dura]['time']) - time()) / (24*60*60);
				$codes[] = array(
								"code"		=> str_pad($code['code'], 10, '0', STR_PAD_LEFT),
								"made"		=> date("j. M Y", $code['generated']),
								"count"		=> $eQalg->GetUnlockNumber($code['code']),
								"total"		=> $eQalg->times[$dura]['name'],
								"tdtotal"	=> $eQalg->times[$dura]['time']/(24*60*60),
								"tdleft"	=> ($tdleft < 0) ? 0 : round($tdleft),
								"type"		=> ($dura == 0) ? 1 : (($tdleft < -2) ? -1 : 0)
											);

			}
		}
		
		$row['done'] = @$_GET['done'];
		
		//Add the extra links for easy admin stuff
		$delete = ($core->getPermission(4)) ? "<a href=\"?do=delete&cid={$row['customerID']}\">Delete</a>" : null;
		$twig->addGlobal('__extralinks', "<div id=\"extralinks\"><p>User Options</p><a href=\"?do=retrieve&cid={$row['customerID']}\">New Box unlock Code</a><a href=\"admin.php?subpage=editcus&cid={$row['customerID']}\">Edit Customer</a>{$delete}</div>");
		
		$results_ .= $twig->render('customer_details_table', array('member'=>$row, 'o_shopID'=>$_SESSION['equinox_code_shops'], 'codes' => @$codes));
	}
}
elseif (@$_GET['do'] == "retrieve" && is_numeric($_GET['cid'])) //show customer info, if it's numeric
{
	$row = $db->query("SELECT customers.*, count(codes.code) as codesused FROM customers LEFT JOIN codes ON customers.boxID = codes.boxID GROUP BY customerID HAVING {$hidedel}customerID = '".$db->escape_string(@$_GET['cid'])."'");
	
	if ($row->num_rows == 0) //nothing found
	{
		$results_ .= $twig->render('customer_du_none');
	}
	else
	{
		$row = $row->fetch_assoc();
		
		if (!in_array($row['shopID'], $_SESSION['equinox_code_shops']) && $_SESSION['equinox_code_permission'][1] != 1)
		{
			$core->noAccess();
		}
		
		if ($row['boxID'] == 0)
		{
			$core->noAccess("Cannot add a code to user with no box ID");
		}
	
		if ((@$_POST['postdo'] == 'generate') && is_numeric($_POST['length']) && ($_POST['length'] >= 0 && $_POST['length'] <= 7))
		{
			$alg = $eQalg->generate($row['boxID'], $row['codesused']+1, $_POST['length']);
			$db->query("INSERT INTO codes VALUES ('$row[boxID]', '$alg', '".time()."');");
			header("Location: customers.php?do=show&cid=$row[customerID]&done=generated");
		}
		
		//Add the extra links for easy admin stuff
		$twig->addGlobal('__extralinks', "<div id=\"extralinks\"><p>User Options</p><a href=\"?do=show&cid={$row['customerID']}\">Show details</a><a href=\"admin.php?subpage=editcus&cid={$row['customerID']}\">Edit Customer</a></div>");
		
		$results_ .= $twig->render('customer_details_table_retr', array('member'=>$row, 'times' => $eQalg->times));
	}
}
elseif (@$_GET['do'] == 'delete' && is_numeric($_GET['cid']) && $core->getPermission(4))
{
	$row = $db->query("SELECT customers.*, count(codes.code) as codesused FROM customers LEFT JOIN codes ON customers.boxID = codes.boxID GROUP BY customerID HAVING {$hidedel}customerID = '".$db->escape_string(@$_GET['cid'])."'");
	
	if ($row->num_rows == 0) //nothing found
	{
		$results_ .= $twig->render('customer_du_none');
	}
	else
	{
		$row = $row->fetch_assoc();
		if ($core->shopPermission($row['shopID']) === true)
		{
			$qu = $db->prepare("UPDATE customers SET deleted = '1' WHERE customerID = ?");
			$qu->bind_param('i', $_GET['cid']);
			$qu->execute();
			header("Location: customers.php");
		}
		else
		{
			$core->noAccess();
		}
	}
}
else // search
{
    if (@$_SESSION['equinox_code_permission'][1] != 1)
	{
		//We don't have global view permissions...
		//clean the session:
		if ($_SESSION['equinox_code_shops'] == array(0=>0))
		{
			echo $core->noAccess();
		}
		foreach($_SESSION['equinox_code_shops'] as $key=>$value)
		{
			if (!is_numeric($value))
			{
				unset($_SESSION['equinox_code_shops'][$key]);
			}
		}

		$sql = "(customers.shopID = '" . implode("' OR customers.shopID = '", $_SESSION['equinox_code_shops']) . "')";
	}

	if (trim(@$_GET['search']) != "") //is there a search present?
	{
		if (isset($sql))	{		$sql .= " AND ";	}

		$customer = $db->escape_string($_GET['search']);

		//search stuff
		if ($customer[0] != '^')	{	$customer = "%" . $customer;		}
		else						{	$customer = substr($customer, 1);	}

		$results_ .= $core->displayUsers($db->query("SELECT customers.*, count(codes.code) as codesused FROM customers LEFT JOIN codes ON customers.boxID = codes.boxID GROUP BY name HAVING {$hidedel}" . @$sql . " customers.name LIKE '$customer%' LIMIT 50"));
	}
	else //DEFAULT
	{
		$page = (@is_numeric($_GET['page'])) ? ($_GET['page'] * 50) : 0;
		if (isset($sql))	{		$sql;	} else { $sql="true"; }
		$results_ .= $core->displayUsers($db->query("SELECT customers.*, count(codes.code) as codesused FROM customers LEFT JOIN codes ON customers.boxID = codes.boxID GROUP BY name HAVING {$hidedel}" . @$sql . " LIMIT $page,50"));
	}
}


echo $twig->render('customers', array('links'=>$links_, 'userbox'=>$userbox_, 'search' => $search_, 'filter' => $filter_, 'results' => $results_));
