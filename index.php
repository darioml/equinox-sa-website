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
Welcome to eQuinox IzubaBox, $_SESSION[equinox_code_username],<br />
This is the online portal for the standalone project. Here we manage all codes created for our system.<br /><br /><br />
<hr /><span style="font-size: 90%">If you encounter any issues with the system, or for any requests, please contact me directly:
dm1911 (at) imperial.ac.uk</span>

END;


echo $twig->render('core', array('content'=>$content));

