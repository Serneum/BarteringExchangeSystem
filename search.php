<?php
/**********************************************************
* Author: Chris Rees
* Date: 11/23/2010
* Purpose: Search through the database
**********************************************************/

include 'dbConnect.php';
include 'chaining.php';
include 'XML.php';

//Global variables
global $longest, $chainList, $chain, $initialHave;
$longest = array();
$chainList = array();
$chain = array();
$initialHave = $_POST[have]; //user input;
$string = $_POST[want]; //user input;
$string_array = explode(' ', $string);

switch($_POST[op])
{
	case "search":
		createSearchXML($string);
	break;

	case "chain":
		if($initialHave == $string)
			echo "Both items are the same. No search will be performed.";
		else
		{
			$uID = mysql_fetch_assoc(mysql_query("SELECT user_id FROM accounts WHERE username = \"$_POST[uName]\""));
			//Get the first user's pair_id
			$row = mysql_fetch_assoc(mysql_query("SELECT pair_id FROM items WHERE have = \"$_POST[have]\" AND want = \"$_POST[want]\" AND user_id = ".$uID[user_id]));
			$chain[] = $row[pair_id];

			//Break apart multi-word strings for easier searching
			for($i = 0; $i < count($string_array); $i++)
				if(mysql_num_rows(mysql_query("SELECT word FROM common WHERE word = \"$string_array[0]\"")) == 0)
					$search_array[] = $string_array[$i];
					
			if(count($search_array) == 0)
				createChainXML($longest = array($row[pair_id]));
			//Recursively create the chain
			else
			{
				createChain($string_array[0], 1);
				
				//Finds the longest chain
				foreach($chainList as $list)
					if(count($list) > count($longest))
						$longest = $list;
				
				if(count($longest) > 0)
				{
					makeTrade();
					createChainXML($longest);
				}
				else
					createChainXML($longest = array($row[pair_id]));
			}
		}
	break;
}

include 'dbClose.php';
?>