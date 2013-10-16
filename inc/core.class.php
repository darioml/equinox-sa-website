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
	var $db, $links, $page, $templates, $settings;
	
	function __construct($db)
	{
		require('settings.php');
		
		$this->db = $db;
		$this->page = basename($_SERVER['SCRIPT_NAME']);
		$this->links = $setting['pages'];

		$settings = array (
				'small2day' => 250,
				'small5day' => 550,
				'small7day' => 800,
				'small14day' => 1400,
				'small21day' => 2250,
				'small28day' => 2750,
				'small56day' => 5500,
				'smalltotal' => 100000,
				'big2day' => 300,
				'big5day' => 1250,
				'big7day' => 1750,
				'big14day' => 3500,
				'big21day' => 5250,
				'big28day' => 6750,
				'big56day' => 13500,
				'bigtotal' => 170000		
			);

		foreach ($settings as $key=>$value)
		{
			@$this->settings->$key = $value;
		}
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
				$total = (substr($row['boxID'],0,1) == 's') ? $this->settings->smalltotal : $this->settings->bigtotal;
				$row['percent'] = round(($row['paid'] / $total) * 100,1);
				@$content .= $twig->render('customer_du_cont', array('search'=>@$_GET['search'], 'customer'=>$row));
			}
			return $twig->render('customer_du_body', array("content"=>$content));
		}
	}
	
	function ginput($index, $escape=true)
	{
		return ($escape) ? htmlspecialchars(@$_GET[$index]) : @$_GET[$index];
	}
	
	function getPermission($index = -1)
	{
		return (isset($_SESSION['equinox_code_permission'][$index])) ? (bool)$_SESSION['equinox_code_permission'][$index] : false;
	}
	
	function ShopPermission($shopID, $ownerArray = null)
	{
		$ownerArray = ($ownerArray == null) ? @$_SESSION['equinox_code_shops'] : $ownerArray;
		return ($this->getPermission(1)) ? true : in_array($shopID, $ownerArray);
	}
	
	function noAccess($reason = "")
	{
		global $twig;
		echo $twig->render('core_noaccess', array('reason' => $reason));
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