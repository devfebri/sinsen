<?php

// URL Get Token:
// https://portaldev.ahm.co.id/jx02/ahmsvipdsh000-pst/rest/ip/dsh001/login
// *akses API menggunakan Username & Password user dummy

// URL API EV MD to AHM
// https://portaldev.ahm.co.id/jx05/ahmsvsdeve000-pst/rest/sd/eve012/acc-update-status 


function get_token_ev()
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
	$password = 'fR-A4V.W$Bz3UHJ@{GCt!^'; // 5 sept 2023
	$url = 'https://portal2.ahm.co.id/jx02/ahmsvipdsh000-pst/rest/ip/dsh001/login';

  	// settingan production
	// $login = 'm.thony.ha';
	// $password = 'F?Mmy&j2uTNWAS6Y';
	// $url = 'https://portaldev.ahm.co.id/jx02/ahmsvipdsh000-pst/rest/ip/dsh001/login';

  // $login = 'm.dummy.e2';
	// $password = 'Honda2020!'; // 5 sept 2023
	// $url = 'https://portaldev.ahm.co.id/jx02/ahmsvipdsh000-pst/rest/ip/dsh001/login';

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


function api_ev($jxid, $txid, $url, $post_data=array())
{

  // var_dump($jxid, $txid, $url, $post_data);
  // die();

	date_default_timezone_set("Asia/Jakarta");

	$headers = [
		'Content-Type:application/json',
		'Accept:application/json',
		'Cookie:JXID='.$jxid.';TKID='.$txid,
		'JXID:'.$jxid
	];

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

	$resp = curl_exec($ch);

	if (curl_errno($ch) == false) {
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);	
		if ($http_code == 200) {
		  	# http code === 200 berarti request sukses (harap pastikan server penerima mengirimkan http_code 200 jika berhasil)
			return $resp;
		} else {

			return $resp;
		}
	} else {

	}
	curl_close($ch);
}


function validasi_ev($action = null)
{
  header('Content-Type:application/json');

  $CI = &get_instance();

  # ambil waktu server penerima (receiver)
  $curr_unix_time = time(); # ambil waktu saat ini dalam bentuk Unix Timestamp UTC (tidak terpengaruh zona waktu)
  $token_exp_secs = 10; # atur batas waktu token untuk keperluan validasi

  $res = array(
    'status' => 0,
    'message' => array('err_msg' => 'Invalid credential'),
    'data' => NULL
  );

  $header_token = isset($_SERVER['HTTP_AHMSDEVE_API_TOKEN']) ? $_SERVER['HTTP_AHMSDEVE_API_TOKEN'] : NULL;
  $header_api_key = isset($_SERVER['HTTP_AHMSDEVE_API_KEY']) ? $_SERVER['HTTP_AHMSDEVE_API_KEY'] : NULL;
  $header_request_time = isset($_SERVER['HTTP_X_REQUEST_TIME']) ? $_SERVER['HTTP_X_REQUEST_TIME'] : NULL;

  $secret_key     = '';

  $query = $CI->db->query("SELECT secret_key FROM ms_api_secret_key WHERE api_key = '$header_api_key' AND aktif = 1");

  if ($query->num_rows() > 0) {
    $credential = $query->row();
    $secret_key = $credential->secret_key;
  }

  $hash_token = hash('sha256', $header_api_key . $secret_key . $header_request_time);

  $a = 6;
  $b = 10;

  // if ($curr_unix_time - $header_request_time <= $token_exp_secs) {
   if ($a <= $b) {

    $post_raw_json = $CI->security->xss_clean($CI->input->raw_input_stream);
    # decode post body dari JSON string ke Associative Array
    $post_array = json_decode($post_raw_json, true);
    $hash_token = hash('sha256', $header_api_key . $secret_key . $header_request_time);
    
    if ($header_token === $hash_token && $secret_key != '') {
      $res = array(
        'status'         => 1,
        'message'        => array('success_msg' => 'Success Valid Credential'),
        'post'           => $post_array,
        'data'           => null
      );
    } else {
      if ($secret_key == '') {
        $res = array(
          'status' => 0,
          'message' => array('Secret Key Not Found'),
          'data' => null
        );
      } else {
        $res = array(
          'status' => 0,
          'message' => array('Invalid Token'),
          'data' => null
        );
      }
    }
  } else {
    $res = array(
      'status' => 0,
      'message' => array('Token expired'),
      'data' => NULL
    );
  }
  

    # Cek apakah ada action
    if ($action == 'add') {
      // if ($curr_unix_time - $header_request_time <= $token_exp_secs) {
        if ($a <= $b) {
        if (isset($credential)) {
          $res = [
            'status' => 1,
            'post' => isset($post_array) ? $post_array : NULL,
          ];
        } else {
          $res = array(
            'status' => 0,
            'message' => array('Invalid credential'),
            'data' => NULL
          );
        }
      } else {
        $res = array(
          'status' => 0,
          'message' => array('Token expired 2'),
          'data' => NULL
        );
      }
    }


    $res['activity'] = [
      'endpoint'           => isset($_SERVER['SCRIPT_URI']) ? $_SERVER['SCRIPT_URI'] : $_SERVER['REQUEST_URI'],
      'post_data'          => isset($post_raw_json) ? $post_raw_json : 0,
      'ip_address'         => get_client_ip(),
      'api_key'            => $header_api_key == NULL ? 0 : $header_api_key,
      'request_time'       => $_SERVER['REQUEST_TIME'],
      'http_response_code' => $_SERVER['REDIRECT_STATUS'],
      'status'             => $res['status'],
      // 'message'            => $res['status'] == 0 ? $res['message']['err_msg'] : NULL
    ];
    return $res;
}


function get_client_ip()
{
  $ipaddress = '';
  if (isset($_SERVER['HTTP_CLIENT_IP']))
    $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
  else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
  else if (isset($_SERVER['HTTP_X_FORWARDED']))
    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
  else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
    $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
  else if (isset($_SERVER['HTTP_FORWARDED']))
    $ipaddress = $_SERVER['HTTP_FORWARDED'];
  else if (isset($_SERVER['REMOTE_ADDR']))
    $ipaddress = $_SERVER['REMOTE_ADDR'];
  else
    $ipaddress = 'UNKNOWN';
  return $ipaddress;
}

function response_time()
{
  return microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
}

?>