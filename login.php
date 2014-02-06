<?php
/**********************************************************
* Author: Chris Rees
* Date: 11/19/2010
* Purpose: Allow a user to log in
**********************************************************/

//Open database connection
include 'dbConnect.php';

switch($_POST[op])
{
	//Allow a user to log in
	case "login":
		$uName = $_POST[uName];
		$pass = $_POST[pass];
		
		if($uName != "" && $pass != "")
		{
			$result = mysql_fetch_assoc(mysql_query("SELECT password FROM accounts WHERE username = \"$uName\""));
		
			//Check if passwords match
			if($result[password] == $pass)
			{
				//Set the user as online
				if(mysql_query("UPDATE accounts SET loggedin = 1, time = ".(time()/60)." WHERE username = \"$uName\""))
					echo "response=T";
				else
					echo "response=nil";
			}
			else
				echo "response=nil";
		}
		else
			echo "response=nil";
	break;

	case "logout":
		$uName = $_POST[uName];
		
		//Set the user as offline
		if(mysql_query("UPDATE accounts SET loggedin = 0, time = 0 WHERE username = \"$uName\""))
			echo "response=T";
		else
			echo "response=nil";
	break;
	
	//Create a new account
	case "create":
		//Grab the variables from the URL
		$uName = $_POST[uName];
		$pass = $_POST[pass];
		$email = $_POST[email];
		
		//Run an INSERT query if fields are not empty
		if($uName != "" && $pass != "" && $email != "")
		{
			if(mysql_query("INSERT INTO accounts (username, password, email) VALUES(\"$uName\",\"$pass\",\"$email\")"))
				echo "response=T";
			else
				echo "response=nil";
		}
		else
			echo "result=nil";
	break;
}

//Close connection to the database
include 'dbClose.php';
?>