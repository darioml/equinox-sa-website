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
<li>View customer data
<li>Grant new codes (Algorithm class working 100%)
<li>Access code data & Verify the code is proper (public members of algorithm class!)
<li>Show last 5 codes
<li>Admin permissions (beta, will not be editable by other users until finished!)
</ul>
<br />todo:
<ul>
<li>Admin panel to edit member permissions (shop owners) and settings (how many codes until shop owner can generate permanent unlock code)
<li>Show how many days left on current code in member list and member detail view
</ul>

END;


echo $twig->render('core', array('content'=>$content));

