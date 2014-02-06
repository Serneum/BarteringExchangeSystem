<?php
/**********************************************************
* Author: Chris Rees
* Date: 11/23/2010
* Purpose: Chains users/items together for trading
**********************************************************/

$longest;
$chainList;
$chain;
$initialHave;

function createChain($goal, $length)
{
	//Create a reference to the variables declared in the file
	global $chainList, $chain, $initialHave;
	
	//Base cases
	if($length > 10)
		return false;
	if($goal == $initialHave)
		return true;

	//Create the query to grab the next part of the chain
	$result = mysql_query("SELECT pair_id, have, want FROM items WHERE have = \"$goal\" AND in_trade = 0 ORDER BY pair_id") or die(mysql_error());

	//Check for the chain to be able to be completed
	for($i = 0; $i < mysql_num_rows($result); $i++)
	{
		$row = mysql_fetch_assoc($result);
		$chain[$length] = $row[pair_id];
		//If the chain completes, add it to the chainList
		if(createChain($row[want], $length + 1))
			foreach($chain as $id)
				$chainList[] = $chain;
		unset($chain[$length]);
	}
	return false;
}

function makeTrade()
{
	global $longest;
	//Dynamically generate MySQL code to move chained items into the trades table
	$query = "INSERT INTO trades(user_count, users) VALUES(".count($longest).", \"";
	$userList = "";
	
	//Generate the VALUES (IDs)
	for($i = 0; $i < count($longest); $i++)
	{
		$result = mysql_fetch_assoc(mysql_query("SELECT user_id FROM items WHERE pair_id = ".$longest[$i]));
		if($i < count($longest) - 1)
			$userList .= $result[user_id]."|";
		else
			$userList .= $result[user_id];
	}
	$query .= $userList."\")";
	//Run the query
	mysql_query($query) or die(mysql_error());
	
	update($userList);
}

function update($userList)
{
	global $longest;
	$query = "SELECT trade_id FROM trades WHERE users = \"".$userList."\"";
	$result = mysql_fetch_assoc(mysql_query($query));
	//Dynamically generate MySQL code to change the moved items to be marked as in a trade
	$query = "UPDATE items SET in_trade = 1, trade_id = ".$result[trade_id]." WHERE pair_id = ";
	for($i = 0; $i < count($longest); $i++)
		if($i < count($longest) - 1)
			$query .= $longest[$i]." OR pair_id = ";
		else
			$query .= $longest[$i]." ";
	
	//Run the query
	mysql_query($query) or die(mysql_error());
}
?>