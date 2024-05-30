<?php

function param_get()
{
	$url = parse_url($_SERVER['REQUEST_URI']);
	return $url['query'];
}

function get_detail_order_by_nospk($no_spk){
    $CI =& get_instance();
    $CI->load->model('m_fif');
    $status_order = $CI->m_fif->get_detail_order_by_nospk($no_spk);
    return $status_order;
}

function RemoveSpecialChar($str){
    // Using preg_replace() function 
    // to replace the word 
    $res = preg_replace('/[^a-zA-Z0-9_ -]/s','',$str);
    // Returning the result 
    return $res;
}

function cek_status_order($orderUuid)
{
	$token =  get_token_fif();
	// Status Order EndPoint
	
	$url = "https://restapi.fifgroup.co.id/fifport/order/status/order/".$orderUuid;

	$headers = [
		'Content-Type:application/json',
		'Accept:application/json',
		'Authorization: Bearer '.$token,
	];

	//initialize curl 
	$curl = curl_init(); 
	//set parameters 
	curl_setopt_array($curl, 
		array( 
			CURLOPT_HTTPHEADER => $headers, # HTTP Headers
			//expects a response 
			CURLOPT_RETURNTRANSFER => 1, 
			//get url 
			CURLOPT_URL => $url
		)
	); 
	// Send the request & save response to $resp 
	$resp = curl_exec($curl); 

	// Close request to clear up some resources 
	curl_close($curl); 

	if (json_decode($resp) == 'NULL' || json_decode($resp) ==NULL || json_decode($resp)->error) {
		return json_decode($resp);
	} else {
		return json_decode($resp)->data[0]->order_status;
	}	
}

function get_token_fif()
{

	$headers = [
	'Content-Type: application/x-www-form-urlencoded',
	];


	// echo json_encode($post_raw_json);
	// exit();
	

	# Inisiasi CURL request
	$ch = curl_init();

	# atur CURL Options
	curl_setopt_array($ch, array(
	CURLOPT_URL => 'https://authtoken.fifgroup.co.id:9443/auth/realms/fifgroup/protocol/openid-connect/token', # URL endpoint
	CURLOPT_HTTPHEADER => $headers, # HTTP Headers
	CURLOPT_RETURNTRANSFER => 1, # return hasil curl_exec ke variabel, tidak langsung dicetak
	CURLOPT_FOLLOWLOCATION => 1, # atur flag followlocation untuk mengikuti bila ada url redirect di server penerima tetap difollow
	CURLOPT_CONNECTTIMEOUT => 60, # set connection timeout ke 60 detik, untuk mencegah request gantung saat server mati
	CURLOPT_TIMEOUT => 60, # set timeout ke 120 detik, untuk mencegah request gantung saat server hang
	CURLOPT_POST => 1, # set method request menjadi POST
	CURLOPT_POSTFIELDS => "grant_type=client_credentials&client_id=SinarSentosa&client_secret=622c4be9-092f-4695-b491-4b0d440dca5c", # attached post data dalam bentuk JSON String,
	// CURLOPT_VERBOSE => 1, # mode debug
	// CURLOPT_HEADER => 1, # cetak header
	CURLOPT_SSL_VERIFYPEER => true  
	));

	# eksekusi CURL request dan tampung hasil responsenya ke variabel $resp
	$resp = curl_exec($ch);

	# validasi curl request tidak error
	if (curl_errno($ch) == false) {
		# jika curl berhasil
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($http_code == 200) {
		  # http code === 200 berarti request sukses (harap pastikan server penerima mengirimkan http_code 200 jika berhasil)
			
			$val = json_decode($resp);

			return $val->access_token;
		} else {
		  # selain itu request gagal (contoh: error 404 page not found)
		  // echo 'Error HTTP Code : '.$http_code."\n";
		  
			
			return $resp;
		}
	} else {
		# jika curl error (contoh: request timeout)
		# Daftar kode error : https://curl.haxx.se/libcurl/c/libcurl-errors.html
		// echo "Error while sending request, reason:".curl_error($ch);
	}

	# tutup CURL
	curl_close($ch);
}

function api_fif($token, $method, $url, $post_data=array(), $uploadFile=FALSE)
{
	# atur zona waktu sender server ke Jakarta (WIB / GMT+7)
	date_default_timezone_set("Asia/Jakarta");

	

	if ($uploadFile) {
		$headers = [
			'Content-Type:application/json',
			'Accept:application/json',
			'Authorization: Bearer '.$token,
			'Cache-control: no-cache',
			'Content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW'
		];
	} else {
		$headers = [
			'Content-Type:application/json',
			'Accept:application/json',
			'Authorization: Bearer '.$token,
		];
	}

	# Inisiasi CURL request
	$ch = curl_init();

	# atur CURL Options
	curl_setopt_array($ch, array(
	CURLOPT_URL => $url, # URL endpoint
	CURLOPT_HTTPHEADER => $headers, # HTTP Headers
	CURLOPT_RETURNTRANSFER => 1, # return hasil curl_exec ke variabel, tidak langsung dicetak
	CURLOPT_FOLLOWLOCATION => 1, # atur flag followlocation untuk mengikuti bila ada url redirect di server penerima tetap difollow
	CURLOPT_CONNECTTIMEOUT => 60, # set connection timeout ke 60 detik, untuk mencegah request gantung saat server mati
	CURLOPT_TIMEOUT => 60, # set timeout ke 120 detik, untuk mencegah request gantung saat server hang
	CURLOPT_POST => $method, # set method request menjadi POST
	CURLOPT_POSTFIELDS => json_encode($post_data), # attached post data dalam bentuk JSON String,
	// CURLOPT_VERBOSE => 1, # mode debug
	// CURLOPT_HEADER => 1, # cetak header
	CURLOPT_SSL_VERIFYPEER => true  
	));

	# eksekusi CURL request dan tampung hasil responsenya ke variabel $resp
	$resp = curl_exec($ch);

	# validasi curl request tidak error
	if (curl_errno($ch) == false) {
		# jika curl berhasil
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($http_code == 200) {
		  # http code === 200 berarti request sukses (harap pastikan server penerima mengirimkan http_code 200 jika berhasil)
			return $resp;
		} else {
		  # selain itu request gagal (contoh: error 404 page not found)
		  // echo 'Error HTTP Code : '.$http_code."\n";
			
			return $resp;
		}
	} else {
		# jika curl error (contoh: request timeout)
		# Daftar kode error : https://curl.haxx.se/libcurl/c/libcurl-errors.html
		// echo "Error while sending request, reason:".curl_error($ch);
	}

	# tutup CURL
	curl_close($ch);
}