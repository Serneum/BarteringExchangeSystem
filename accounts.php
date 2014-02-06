<?php
/**********************************************************
* Author: Chris Rees
* Date: 11/23/2010
* Purpose: Allows users to add pairs of items to their
* account as well as update account information
**********************************************************/

include 'dbConnect.php';
include 'XML.php';

//Get the username and user_id
$uName = $_POST[uName];
$uID = mysql_fetch_assoc(mysql_query("SELECT user_id FROM accounts WHERE username = \"$uName\"")) or die(mysql_error());

switch($_POST[op])
{
	case "add":
		//Grab the have/want
		$have = $_POST[have];
		$want = $_POST[want];
		//Make sure items aren't empty
		if($have != "" && $want != "" && $have != $want)
		{
			if(mysql_query("INSERT INTO items(user_id, have, want) VALUES(".$uID[user_id].", \"".$have."\", \"".$want."\")"))
				echo "response=T";
			else
				echo "response=nil";
		}
		else
			echo "response=nil";
	break;
	
	case "get":
		createPairXML($uID[user_id]);
	break;
	
	//Implement later
	case "rem":
	break;
	
	//Allows the user to update their email address or password
	case "upd":
		switch($_POST[type])
		{
			case "1":
				$oldPass = mysql_fetch_assoc(mysql_query("SELECT password FROM accounts WHERE user_id = ".$uID[user_id]));
				//Compare old passwords before allowing a change to a new password
				if($oldPass[password] == $_POST[oldPass])
				{
					if(mysql_query("UPDATE accounts SET password = \"".$_POST[newPass]."\" WHERE user_id = ".$uID[user_id]))
						echo "response=T";
					else
						echo "response=nil";
				}
				else
					echo "response=nil";
			break;
			
			case "2":
				if(mysql_query("UPDATE accounts SET email = \"".$_POST[email]."\" WHERE user_id = ".$uID[user_id]))
					echo "response=T";
				else
					echo "response=nil";
			break;
		}
	break;
}

include 'dbClose.php';
?>