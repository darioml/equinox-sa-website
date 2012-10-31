<?php

$setting['pages'] = array	(	"index.php"		=>	array	(		'name'		=>	'Home'
																),
								"customers.php"	=>	array	(	
																 'name'		=>	'Customers',
																 'subpages' => array(	"find by id"=>array("name" => "html", "code" => "<div id=\"findid\"><form action=\"customers.php\" method=\"get\"><input type=\"hidden\" name=\"do\" value=\"show\">Show ID:<input type= \"text\" name=\"cid\"><input type=\"submit\" value=\"Find\"></form></div>"))
																),
							 	"admin.php"		=>	array	(		'name'		=>	'Admin',
															  		'subpages'	=>	array(	"newcust" => array('name' => 'Add Customer'),
																							"newadm" => array('name' => 'Add Admin'),
																							"findbid" => array('name' => 'Find box owner'),
																							"tracu" => array('name' => 'Transfer customer'),
																							"sysset" => array('name' => 'System Settings'),
																							"linebreak" => array('name'=>'html', 'code'=>"<br /><br />"),
																							"editcus" => array('name' => "html", 'code' => "<div id=\"findid\"><form action=\"admin.php\" method=\"get\"><input type=\"hidden\" name=\"subpage\" value=\"editcus\">Customer ID:<input type= \"text\" name=\"cid\"><input type=\"submit\" value=\"Edit\"></form></div>"),
																							"editadm" => array('name' => "html", 'code' => "<div id=\"findid\"><form action=\"admin.php\" method=\"get\"><input type=\"hidden\" name=\"subpage\" value=\"editadm\">Admin ID:<input type= \"text\" name=\"aid\"><input type=\"submit\" value=\"Edit\"></form></div>")
																						 )
																)
							);


$setting['daysCode'] = 28;