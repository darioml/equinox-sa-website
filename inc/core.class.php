<?php

/*
Code written and managed by Dario Magliocchetti-Lombi for e.quinox

Copying is under no circumstances allowed, unless prior WRITTEN (not email) consent from author.

COPY 2011-2012, dario-ml
www.dario-ml.com

Page Name:			core.php
Description:		Holds the core variables for most of the script!

*/

error_reporting(-1);

class eQuinox
{
	var $db, $links, $page, $templates;
	
	function __construct($db)
	{
		require('settings.php');
		$this->db = $db;
		$this->page = basename($_SERVER['SCRIPT_NAME']);
		$this->links = $setting['pages'];
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
				@$content .= $twig->render('customer_du_cont', array('search'=>@$_GET['search'], 'customer'=>$row));
			}
			return $twig->render('customer_du_body', array("content"=>$content));
		}
	}
	
	function noAccess()
	{
		global $twig;
		echo $twig->render('core_noaccess');
		exit();
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
					$_SESSION['equinox_code_uid'] = $row['userID'];
					$_SESSION['equinox_code_username'] = $row['username'];
                                        $_SESSION['equinox_code_hash'] = md5(time());
					$_SESSION['equinox_code_auth'] = $row['level'];
                                        
					$_SESSION['equinox_code_shops'] = explode(',', $row['shopID']);
                                        
                                        $this->db->query("update logins set loginhash = '$_SESSION[equinox_code_hash]' where userID = '$row[userID]'");
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
                else
                {
                    $row = $this->db->query("SELECT * FROM logins WHERE userID = '$_SESSION[equinox_code_uid]' LIMIT 1")->fetch_assoc();
                    if ($row['loginhash'] == 'logout')
                    {
                        session_destroy();
                        header("Location: index.php");
                    }
                    elseif ($row['loginhash'] != $_SESSION['equinox_code_hash'])
                    {
                        $_SESSION['equinox_code_uid'] = $row['userID'];
			$_SESSION['equinox_code_username'] = $row['username'];
                        $_SESSION['equinox_code_hash'] = md5(time());
			$_SESSION['equinox_code_auth'] = $row['level'];
                        
			$_SESSION['equinox_code_shops'] = explode(',', $row['shopID']);
                        
                        if ($row['loginhash'] != '0') // if it's not been blanked...
                        {
                            $_SESSION['equinox_code_multiple'] = 1;
                            $_SESSION['equinox_code_hash'] = $row['loginhash'];
                        }
			else
			{
				//To logout other, have an $ignore_logout.
				//Also, allow change password!
				$_SESSION['equinox_code_multiple'] = 0;
				$this->db->query("update logins set loginhash = '$_SESSION[equinox_code_hash]' where userID = '$row[userID]'");	
			}
                    }
                }
	}
}