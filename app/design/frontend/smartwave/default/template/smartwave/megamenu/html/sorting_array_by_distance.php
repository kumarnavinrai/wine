<?php
$storedistancesfromuser = array();

  
		if(isset($allstoresnewdata))
		{ 
			foreach($allstoresnewdata as $key => $val)
			{
     			$storedistancesfromuser[] = $val["storedistancefromuser"];
 			}
 			array_multisort($storedistancesfromuser, SORT_ASC, $allstoresnewdata);
		} 	

?>