<?php
/**********************************************************
* Author: Chris Rees
* Date: 11/24/2010
* Purpose: Checks if a user is still online
**********************************************************/

include 'dbConnect.php';

//Get the current system time and divide by 60 to get minutes
$currTime = time()/60;
$uName = $_POST[uName];

//Grab the time of the last action from the user
$result = mysql_fetch_assoc(mysql_query("SELECT time FROM accounts WHERE username = \"$uName\""));
//If the current time - last action time >= 15, log the user out
if($currTime - $result[time] >= 15)
{
	mysql_query("UPDATE accounts SET loggedin = 0, time = 0 WHERE username = \"$uName\"");
	echo "response=nil";
}
else
{
	mysql_query("UPDATE accounts SET time = ".(time()/60)." WHERE username = \"$uName\"");
	echo "response=T";
}

include 'dbClose.php';
?>