<?php
$collection = Mage::getModel('storepickup/store')->getCollection();
		$allstores = $collection->toArray();
		
        $allstoresnewdata = array();
        if(isset($allstores["items"]))
        {
    		   foreach($allstores["items"] as $key => $val)
    		   { 
    		     $val["storedistancefromuser"] = round(distance($latitude, $longitude, $val["store_latitude"], $val["store_longitude"], "M"), 2);
    		     $allstoresnewdata[] = $val;
    			 } 
		    } 
        
       
        if(isset($allstoresnewdata))
        { 
          foreach($allstoresnewdata as $key => $val)
          {
             if($max_distance_from_store>=$val["storedistancefromuser"])
             {
               $all_store_distance_is_more_than_set_config = "no";
             }
          }
          
        }
    


?>