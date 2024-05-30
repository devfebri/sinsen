<?php

function get_token_astra()
{
	$headers = array(
		'Content-Type:application/json'
	);

	# Inisiasi CURL request
	$ch = curl_init();
	
	// // settingan testing
	// $login = 'm.dummy.e2';
	// $password = 'Honda2020!';
	// $url = 'https://portaldev.ahm.co.id/jx02/ahmsvipdsh000-pst/rest/ip/dsh001/login';

	// settingan production
	$login = 'm.thony.ha';
	// $password = 'F?Mmy&j2uTNWAS6Y'; // 5 sept 2023
	$password = 'fR-A4V.W$Bz3UHJ@{GCt!^'; // 16 Nov 2023

	$url = 'https://portal2.ahm.co.id/jx02/ahmsvipdsh000-pst/rest/ip/dsh001/login';

	$cookies = Array();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_TIMEOUT,60);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,60);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_HEADER , true );
	curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
	
	# eksekusi CURL request dan tampung hasil responsenya ke variabel $resp
	$resp = curl_exec($ch);

	# validasi curl request tidak error
	if (curl_errno($ch) == false) {
		# jika curl berhasil
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);		
		$headerSize = curl_getinfo( $ch , CURLINFO_HEADER_SIZE );
		// $headerStr = substr( $resp , 0 , $headerSize );
		$bodyStr = substr( $resp , $headerSize );

		// // convert headers to array
		// $headers = headersToArray( $headerStr );
		// // print_r($headerStr);

		preg_match_all('/Set-Cookie:(?<cookie>\s{0,}.*)$/im', $resp, $cookies);
		// print_r($cookies['cookie'][1]); // show harvested cookies

		// basic parsing of cookie strings (just an example)
		// $cookieParts = array();
		// preg_match_all('/Set-Cookie:\s{0,}(?P<name>[^=]*)=(?P<value>[^;]*).*?expires=(?P<expires>[^;]*).*?path=(?P<path>[^;]*).*?domain=(?P<domain>[^\s;]*).*?$/im', $resp, $cookieParts);
		// print_r($cookieParts);
		
		if ($http_code == 200) {
		  # http code === 200 berarti request sukses (harap pastikan server penerima mengirimkan http_code 200 jika berhasil)
			// $val = json_decode($resp);
		 	$val = json_decode($bodyStr);	 
			$jxid = substr(explode(';',$cookies['cookie'][0])[0],6);
			$tkid = substr(explode(';',$cookies['cookie'][1])[0],6);
			$status = $val->status;

			$data_array = array();
			$data_array['status'] = $status;
			$data_array['jxid'] = $jxid;
			$data_array['txid'] = $tkid;

			// var_dump($val->status);die;
			return $data_array;
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


function api_auto_claim($jxid, $txid, $url, $post_data=array())
{
	# atur zona waktu sender server ke Jakarta (WIB / GMT+7)
	date_default_timezone_set("Asia/Jakarta");

	$headers = [
		'Content-Type:application/json',
		'Accept:application/json',
		'Cookie:JXID='.$jxid.';TKID='.$txid,
		'JXID:'.$jxid
	];

	// print_r($headers); echo '<br><br>';
	// echo $url;die;

	# Inisiasi CURL request
	$ch = curl_init();

	# atur CURL Options
	curl_setopt_array($ch, array(
		CURLOPT_URL => $url, # URL endpoint
		CURLOPT_HTTPHEADER => $headers, # HTTP Headers
		// CURLOPT_HTTPHEADER => array('Accept:application/json',"Cookie: JXID=".$jxid),
		CURLOPT_RETURNTRANSFER => 1, # return hasil curl_exec ke variabel, tidak langsung dicetak
		// CURLOPT_FOLLOWLOCATION => 1, # atur flag followlocation untuk mengikuti bila ada url redirect di server penerima tetap difollow
		CURLOPT_CONNECTTIMEOUT => 60, # set connection timeout ke 60 detik, untuk mencegah request gantung saat server mati
		CURLOPT_TIMEOUT => 60, # set timeout ke 120 detik, untuk mencegah request gantung saat server hang
		CURLOPT_POST => 1, # set method request menjadi POST
		CURLOPT_POSTFIELDS => json_encode($post_data), # attached post data dalam bentuk JSON String,
		// CURLOPT_VERBOSE => 1, # mode debug
		// CURLOPT_HEADER => 1, # cetak header
		CURLOPT_SSL_VERIFYPEER => true  
	));

	# eksekusi CURL request dan tampung hasil responsenya ke variabel $resp
	$resp = curl_exec($ch);
	// echo $resp->status;


	# validasi curl request tidak error
	if (curl_errno($ch) == false) {
		# jika curl berhasil
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);	
		// $result = json_decode($resp,true);

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