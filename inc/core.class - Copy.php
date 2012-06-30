<?php

/*
Code written and managed by Dario Magliocchetti-Lombi for e.quinox

Copying is under no circumstances allowed, unless prior WRITTEN (not email) consent from author.

COPY 2011-2012, dario-ml
www.dario-ml.com

Page Name:			core.php
Description:		Holds the core variables for most of the script!

*/

class eQuinox
{
	var $db, $links, $page;
	
	function __construct($db)
	{
		require('settings.php');
		$this->db = $db;
		$this->page = basename($_SERVER['SCRIPT_NAME']);
		$this->links = $setting['pages'];
	}
	
	function startPage($stylesheet = array(), $logincheck = 1)
	{
		echo "<!doctype html>\r\n".
		"<html>\r\n".
		"<head>\r\n".
		"   <meta charset=\"utf-8\">\r\n".
		"   <meta name=\"viewport\" content=\"width=device-width\">\r\n".
		"   <link rel=\"stylesheet\" href=\"style.css\">";
		foreach ($stylesheet as $css)
		{
			echo "\r\n  <link rel=\"stylesheet\" href=\"{$css}.css\">";
		}
		echo "\r\n</head>\r\n".
		"<body>\r\n".
		"<div id=\"main\">\r\n\r\n".
		"<div id=\"head\">\r\n";
		
		echo (isset($_SESSION['equinox_code_username'])) ? "  <div id=\"loggedinas\">Logged in as $_SESSION[equinox_code_username] (<a href=\"?do=logout\">Logout</a>)</div>\r\n" : "  <br />\r\n";
		
		echo "  <div id=\"logo\"><img src=\"http://e.quinox.org/include/image/equinox_small.jpg\" border=\"0\" /></div>\r\n";
		if (isset($_SESSION['equinox_code_auth']))
		{
			echo "  <div id=\"links\">\r\n".
			"    <div id=\"links_one\">\r\n";
			foreach ($this->links as $link=>$data)
			{
				echo "      <a href=\"$link\"";
				echo ($link == $this->page) ? " style=\"color: #FFFF99;\"" : "";
				echo ">$data[name]</a>\r\n";
			}
			echo "    </div>\r\n".
			"    <div id=\"links_two\">\r\n";
			if (isset($this->links[$this->page]['subpages']))
			{
				echo "      <a href=\"$this->page\">Main</a>\r\n";
				foreach ($this->links[$this->page]['subpages'] as $link=>$data)
				{
					echo "      <a href=\"?spage=$link\">$data[name]</a>\r\n";
				}
			}
			echo "    </div>\r\n".
			"  </div>\r\n";
		}
		echo "</div>".
		"<br style=\"clear: both;\" />\r\n\r\n".
		"<div id=\"content\">\r\n";
		if ($logincheck == 1){	$this->LoginFunctions(); }
	}
	
	function endPage()
	{
		echo "\r\n</div>\r\n\r\n".
		"</div>\r\n".

		"</body>\r\n".
		"</html>\r\n";
	}
	
	function displayUsers($result)
	{
		global $twig;
		
		if ($result->num_rows == 0)
		{
			return $twig->render('customer_du_none');
		}
		else 
		{
			while ($row = $result->fetch_assoc())
			{
				@$content .= $twig->render('customer_du_cont', array('search'=>@$_GET['search'], 'cID'=>$row['customerID'], 'name'=>$row['name'], 'days'=>"not done"));
			}
			return $twig->render('customer_du_body', array("content"=>$content));
		}
	}
	
	function LoginFunctions()
	{
		global $twig;
		if (!isset($_SESSION['equinox_code_auth']))
		{
			if (!empty($_POST) && isset($_POST['username']) && isset($_POST['password']))
			{
				$username = $this->db->escape_string($_POST['username']);
				$password = hash('sha256', $this->db->escape_string($_POST['password']));
				
				$result = $this->db->query("SELECT * FROM logins WHERE username = '$username' and password = '$password'");
				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();
					$_SESSION['equinox_code_auth'] = $row['level'];
					$_SESSION['equinox_code_username'] = $row['username'];
					header("Location: index.php");
				}
				else
				{
					$error = "Invalid login details";
				}
			}
			
			echo $twig->render('login', array('error'=>@$error) );
			
			exit();
		}
	}
}