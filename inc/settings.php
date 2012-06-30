<?php

$setting['pages'] = array	(	"index.php"		=>	array	(		'name'		=>	'Home'
																),
								"customers.php"	=>	array	(	
																 'name'		=>	'Customers'
																),
							 	"admin.php"		=>	array	(		'name'		=>	'Admin',
															  		'subpages'	=>	array(	"newcust" => array('name' => 'Edit/Add Customer'),
																							"test2" => array('name' => 'Edit/Add Shopkeepers'),
																							"test3" => array('name' => 'System Settings'))
																)
							);


$setting['daysCode'] = 28;