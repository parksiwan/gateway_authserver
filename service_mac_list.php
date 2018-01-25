<?php
include("autoloader.php");

/* require the mac as the parameter */
if(isset($_GET['mac']) && strval($_GET['mac'])) 
{
	//$number_of_posts = isset($_GET['num']) ? intval($_GET['num']) : 10; //10 is the default
	$mac_address = strval($_GET['mac']); //no default

	/* connect to the db */
    $service = new Service();
    
	/* grab the posts from the db */
	$account_id = $service->findMacAddress($mac_address);
	$mac_array = array();
	$mac_array["mac_address"] = $mac_address;
	$mac_array["account_id"] = $account_id;
	
	/* output in necessary format */
	header('Content-type: application/json');
	echo json_encode(array('macs'=>$mac_array));
}
?>