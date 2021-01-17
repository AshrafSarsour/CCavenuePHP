<?php

namespace CCavenue\Payment;

use CCavenue\Security\Crypto;

class Response
{
	public function init($workingKey) {
	    $data = $_POST;
		$encResponse=$data["encResp"];			
		$rcvdString=Crypto::decrypt($encResponse,$workingKey);	
		$order_status="";
		$decryptValues=explode('&', $rcvdString);
		$dataSize=sizeof($decryptValues);
		for($i = 0; $i < $dataSize; $i++) 
		{
			$information=explode('=',$decryptValues[$i]);
			if($i==3)	$order_status=$information[1];
		}
		if($order_status==="Success")
		{
			return true;
		} 
	    return false;
	}
}
