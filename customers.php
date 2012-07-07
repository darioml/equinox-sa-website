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

if (@$_GET['do'] == "show" && trim(@$_GET['customer']) != "" && is_numeric($_GET['customer'])) //show customer info, if it's numeric
{
	$row = $db->query("SELECT customers.*, count(codes.code) as codesused FROM customers LEFT JOIN codes ON customers.boxID = codes.boxID GROUP BY customerID HAVING customerID = '".$db->escape_string(@$_GET['customer'])."'");
	if ($row->num_rows == 0) //nothing found
	{
		$results_ .= $twig->render('customer_du_none');
	}
	else
	{
		$row = $row->fetch_assoc();
		$row['done'] = @$_GET['done'];
		if ($row['done'] == 'generated')
		{
			$result = $db->query("SELECT * FROM codes WHERE boxID = '$row[boxID]' ORDER BY generated DESC")->fetch_assoc();
			$row['lastcode'] = $result['code'];
		}
		$results_ .= $twig->render('customer_details_table', array('member'=>$row));
	}
}
elseif (@$_GET['do'] == "retrieve" && trim(@$_GET['customer']) != "" && is_numeric($_GET['customer'])) //show customer info, if it's numeric
{
	$row = $db->query("SELECT customers.*, count(codes.code) as codesused FROM customers LEFT JOIN codes ON customers.boxID = codes.boxID GROUP BY customerID HAVING customerID = '".$db->escape_string(@$_GET['customer'])."'");
	if ($row->num_rows == 0) //nothing found
	{
		$results_ .= $twig->render('customer_du_none');
	}
	else
	{
		$row = $row->fetch_assoc();
		if ((@$_POST['postdo'] == 'generate') && is_numeric($_POST['length']))
		{
			$alg = $eQalg->generate($row['boxID'], 4, $row['codesused']);
			$db->query("INSERT INTO codes VALUES ('$row[boxID]', '$alg', '".time()."');");
			header("Location: customers.php?do=show&customer=$row[customerID]&done=generated");
		}
		$results_ .= $twig->render('customer_details_table_retr', array('member'=>$row, 'options' => array(0=>'Permanent',4=>'2 Weeks')));
	}
}
else
{
    include("cussearch.php");
}


echo $twig->render('customers', array('links'=>$links_, 'userbox'=>$userbox_, 'content'=>"customer shit", 'search' => $search_, 'filter' => $filter_, 'results' => $results_));
