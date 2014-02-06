<?php
/**********************************************************
* Author: Chris Rees
* Date: 11/29/2010
* Purpose: Generate XML to pass back to the flash UI
**********************************************************/

//Generate the XML file for pair results for each user
function createPairXML($uID)
{
	//Select items from the database by the User's ID
	$result = mysql_query("SELECT * FROM items WHERE user_id = ".$uID) or die(mysql_error());
	$num = mysql_num_rows($result);
	if($num != 0) 
	{
		//Create the pairs.xml file
		$_xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n";
		$_xml .= "<results>\r\n";

		while($row = mysql_fetch_array($result)) 
		{
			if($row[in_trade] != 1)
			{
				if($row[pair_id]) 
				{
					//Create file with pair_id, have, and want
					$_xml .= "<result>\r\n";
					$_xml .= "\t<pair_id>".$row[pair_id]."</pair_id>";
					$_xml .= "\t<have>".$row[have]."</have>\r\n";
					$_xml .= "\t<want>".$row[want]."</want>\r\n";
					$_xml .= "</result>\r\n";
				} 
			}
		}
		
		//Close the file
		$_xml .= "</results>";
		echo $_xml;
	} 
	else 
		echo "response=nil";
}

//Generate the XML file for pair results for each user
function createSearchXML($want)
{
	//Select items from the database by what the user wants
	$result = mysql_query("SELECT * FROM items WHERE have = \"".$want."\"");
	$num = mysql_num_rows($result);
	//echo $num;
	if($num != 0) 
	{
		//Create the search.xml file
		$_xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n";
		$_xml .= "<results>\r\n";
		
		while($row = mysql_fetch_array($result)) 
		{
			if($row[in_trade] != 1)
			{
				$uName = mysql_fetch_assoc(mysql_query("SELECT username FROM accounts WHERE user_id = ".$row[user_id].""));
				//Create file with uID, have, and want
				$_xml .= "<result>\r\n";
				$_xml .= "\t<uName>".$uName[username]."</uName>\r\n";
				$_xml .= "\t<have>".$row[have]."</have>\r\n";
				$_xml .= "\t<want>".$row[want]."</want>\r\n";
				$_xml .= "</result>\r\n";
			}
		}
		
		//Close the file
		$_xml .= "</results>";

		echo $_xml;
	} 
	else 
		echo "response=nil";
}

function createChainXML($chain)
{
	//Select items from the database by what the user wants
	$num = count($chain);
	if($num != 0) 
	{
		//Create the chains.xml file
		$_xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n";
		$_xml .= "<results>\r\n";
		
		foreach($chain as $i)
		{
			$result = mysql_query("SELECT * FROM items WHERE pair_id = ".$i);
			if(mysql_num_rows($result) > 0)
			{
				while($row = mysql_fetch_assoc($result))
				{
					$uName = mysql_fetch_assoc(mysql_query("SELECT username FROM accounts WHERE user_id = ".$row[user_id]));
					$_xml .= "<result>\r\n";
					$_xml .= "\t<uName>".$uName[username]."</uName>\r\n";
					$_xml .= "\t<have>".$row[have]."</have>\r\n";
					$_xml .= "\t<want>".$row[want]."</want>\r\n";
					$_xml .= "</result>\r\n";
				}
			}
		}
		
		//Close the file
		$_xml .= "</results>";

		echo $_xml;
	} 
	else 
		echo "response=nil";
}

?>