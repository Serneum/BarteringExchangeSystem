<?php
/**********************************************************
* Author: Chris Rees
* Date: 11/19/2010
* Purpose: Connects to the database
**********************************************************/

include 'config.php';

//Open a connection to the database
$con = mysql_connect($DB_HOST, $DB_USER, $DB_PASS);

if (!$con)
	die('Could not connect: ' . mysql_error());

//Choose a specific database to use for all of the information/calls
mysql_select_db('BarteringSystem');
?>
