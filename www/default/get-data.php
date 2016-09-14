<?php
	// get our parameters for user in the form but also in the curl requests

	$talbots_point_balance = array("client_id" => '01d64c87b96d4c18a1cb22fadcd41335',
		  			"client_secret" => 'bed6c1f720154c8b90fdcec2d2a94537',
		  			"hostname" => 'https://apipprd.biworldwide.com',
		  			"resource" => '/v1/profile/point-balance',
		  			"querystring" => '?username=StoreTest01',
					"grant_type" => "client_credentials");

	$g5alpha_recog_wall = array("client_id" => '000049ee2f5c444097354d0834949970',
		  			"client_secret" => '5261676c150448898439cfdad3d3f3ec',
		  			"hostname" => 'https://apipprd.biworldwide.com',
		  			"resource" => '/v1/recognition/public-wall',
		  			"querystring" => '',
					"grant_type" => "client_credentials");

	$params = $g5alpha_recog_wall;

	// construct this once here so we can use it a couple times later
	$endpoint = $params['hostname'].$params['resource'];
	
	$postData = "";
	foreach($params as $k => $v)
	{
	   $postData .= $k . '='.urlencode($v).'&';
	}
	$postData = rtrim($postData, '&');

	$curl = curl_init($endpoint."/oauth2/token");
	curl_setopt($curl, CURLOPT_HEADER, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_HEADER,'Content-Type: application/x-www-form-urlencoded');
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);

	$json_response = curl_exec($curl);
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	if ($status != 200) {
	  throw new Exception("Error: call to URL $endpoint failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl) . "\n");
	}
	curl_close($curl);

	$response_obj = json_decode($json_response);

    $headers = array( 
        "Authorization: Bearer " . $response_obj->access_token
    ); 

	$curl = curl_init($endpoint.$params['querystring']);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); 
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

	$json_response = curl_exec($curl);

	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	// evaluate for success response
	if ($status != 200) {
		throw new Exception("Error: call to URL $endpoint failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl) . "\n");
	}
	curl_close($curl);

	// echo "<h2>Making API request...</h2>";
	// print_r($json_response);


	header('Content-Type: application/json');
	echo $json_response;
