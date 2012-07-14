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

$content = <<<END
Beta testing<br>
currently working:
<ul>
<li>View customer data</li>
<li>Grant new codes (Algorithm class working 100%)</li>
<li>Access code data & Verify the code is proper (public members of algorithm class!)</li>
<li>Show last 5 codes</li>
<li>Admin permissions (beta, will not be editable by other users until finished!)
	<ul>
	<li>View admin panel</li>
	<li>View all customers or only those belonging to your shop</li>
	<li>Add new customer</li>
	<li>Edit the customers you can view</li>
	<li>Delete customers</li>
	</ul></li>
</ul>
<br />todo:
<ul>
<li>Admin panel to edit member permissions (shop owners) and settings (how many codes until shop owner can generate permanent unlock code)
<li>Show how many days left on current code in member list and member detail view
<li>Allow for show/hide of deleted members (default to hide!)
<li>Interface for restoring deleted customers
<li>Simplify deletion (no need to verify), only by jq
<li>Colour the background of details red to make clear the user is deleted!
<li>rewrite customer.php to reduce reused code!
</ul>

END;


echo $twig->render('core', array('content'=>$content));

