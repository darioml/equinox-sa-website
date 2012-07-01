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

$links_ = $twig->render('core_links');
$userbox_ = $twig->render('core_userblock', array('name' => $_SESSION['equinox_code_username']));

//$test = 0x0000;
//printf("%b", $test);

$content = <<<END
Beta testing<br>
currently working:
<ul>
<li>View customer data
<li>Add new code (ALGORITHM doesn't verify with Ashley's program for now, unsure why but have ideas)
</ul>
<br />todo:
<ul>
<li>Show last 5 codes
<li>Admin panel to edit member permissions (shop owners) and settings (how many codes until shop owner can generate permanent unlock code)
<li>Data section
<li>Show how many days left on current code in member list and member detail view
</ul>

END;

echo $twig->render('core', array('links'=>$links_, 'userbox'=>$userbox_, 'content'=>$content));

