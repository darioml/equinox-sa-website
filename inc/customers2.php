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

require('api.main.php');

$core->startPage(array("customers"));

echo "<div id=\"search\"><form method=\"GET\" action=\"customers.php\"><input name=\"search\" value=\"" . @$_GET['search'] . "\"><input type=\"submit\" value=\"Search Customer Name\"></form></div>";
echo "<div id=\"filter\"><a href=\"customers.php\">All</a> | <a href=\"customers.php?search=^A\">A</a> | <a href=\"customers.php?search=^B\">B</a> | <a href=\"customers.php?search=^C\">C</a> | <a href=\"customers.php?search=^D\">D</a> | <a href=\"customers.php?search=^E\">E</a> | <a href=\"customers.php?search=^F\">F</a> | <a href=\"customers.php?search=^G\">G</a> | <a href=\"customers.php?search=^H\">H</a> | <a href=\"customers.php?search=^I\">I</a> | <a href=\"customers.php?search=^J\">J</a> | <a href=\"customers.php?search=^K\">K</a> | <a href=\"customers.php?search=^L\">L</a> | <a href=\"customers.php?search=^M\">M</a> | <a href=\"customers.php?search=^N\">N</a> | <a href=\"customers.php?search=^O\">O</a> | <a href=\"customers.php?search=^P\">P</a> | <a href=\"customers.php?search=^Q\">Q</a> | <a href=\"customers.php?search=^R\">R</a> | <a href=\"customers.php?search=^S\">S</a> | <a href=\"customers.php?search=^T\">T</a> | <a href=\"customers.php?search=^U\">U</a> | <a href=\"customers.php?search=^V\">V</a> | <a href=\"customers.php?search=^W\">W</a> | <a href=\"customers.php?search=^X\">X</a> | <a href=\"customers.php?search=^Y\">Y</a> | <a href=\"customers.php?search=^Z\">Z</a></div>";

echo "<div id=\"results\">";

if (trim(@$_GET['customer']) != "" && is_numeric($_GET['customer']))
{
	echo "<div style=\"margin-left: 80px; margin-top: 50px;\">";
	$row = $db->query("SELECT * FROM customers WHERE customerID = '".$db->escape_string(@$_GET['customer'])."'")->fetch_assoc();
	echo "Customer Name: <b>$row[name]</b><br />
Mobile Number: <b>$row[telephone]</b><br />
Battery Box ID: <b>$row[boxID]</b>
";
	$result = $db->query("SELECT * FROM codes WHERE boxID = '$row[boxID]' AND retrieved > '0'");
	echo "<div style=\"margin-left: 30px; font-size: 90%\">Codes paid for: <b>".$result->num_rows."</b><br />
Do we want to output current codes?-YES!<br />";
	if (@$_GET['show'] == 'new')
	{
		$newcode = $db->query("SELECT * FROM codes WHERE boxID = $row[boxID] ORDER BY retrieved DESC")->fetch_assoc();
		echo "<div style=\"padding: 10px; color: ffffff; background: #007700; width: 300px; text-align: center\">New code:<br /><b>$newcode[code]</b></div>";
	}
echo "	<form action=\"retrieve.php\" method=\"GET\">
	<input type=\"hidden\" name=\"customer\" value=\"$row[customerID]\">
	<input type=\"submit\" value=\"Retrieve new code\">
</form>";
	echo "</div></div>";
}
elseif (trim(@$_GET['search']) != "")
{
	
	$customer = $db->escape_string($_GET['search']);
	
	if ($customer[0] != '^')	{	$customer = "%" . $customer;		}
	else						{	$customer = substr($customer, 1);	}

	echo $core->displayUsers($db->query("SELECT customerID, name FROM customers WHERE name LIKE '$customer%'"));
}
elseif (trim(@$_GET['customer']) == "")
{
	echo $core->displayUsers($db->query("SELECT customerID, name FROM customers ORDER BY name ASC LIMIT 50"));
}

echo "</div>";

$core->endPage();