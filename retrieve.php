<?php

define('SAFE_ZONE', '1');

require("connection.php");

if (!empty($_POST) && is_numeric($_POST['cid']))
{
	$cid = $_POST['cid'];
	$result = $db->query("SELECT * FROM customers WHERE customerID = '$cid'");
	if ($result->num_rows == 0)
	{
		echo "invalid";
		exit();
	}
	$rowc = $result->fetch_assoc();
	
	$db->query("UPDATE codes SET retrieved = '".time()."' WHERE boxID = '$rowc[boxID]' AND retrieved = '0' LIMIT 1");
	
	header("Location: index.php?customer=$cid&show=new");
	
	
	
	exit();
}

if (!is_numeric(@$_GET['customer']))
{
	echo "invalid";
	exit();
}
$cid = $_GET['customer'];
$result = $db->query("SELECT * FROM customers WHERE customerID = '$cid'");
if ($result->num_rows == 0)
{
	echo "invalid";
	exit();
}
$rowc = $result->fetch_assoc();

echo "Are you sure you want to retrieve a new code for <b><u>$rowc[name]</b></u>? <br /><br />
<span style=\"color: #990000\">Please note that this is irreversible. Make sure you have collected the fee from the user before issuing them a new code.</span></br>
<table><tr><td><form action=\"retrieve.php\" method=\"POST\"><input type=\"hidden\" name=\"cid\" value=\"$rowc[customerID]\"><input type=\"submit\" value=\"Yes!\"></form></td><td><form action=\"index.php\" method=\"GET\"><input type=\"hidden\" name=\"customer\" value=\"$rowc[customerID]\"><input type=\"submit\" value=\"No\"></form></td></tr></table>";