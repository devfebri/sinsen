<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mokita
{

  private $CI;
  // private $base_url = "https://api-sinsen.modakita.com"; //dev
  private $base_url = "https://api-admin.sinarsentosaprimatama.com"; //prod
  private $token;
  public function __construct()
  {
    $this->CI = &get_instance();
    $this->token = $this->get_token();
    // send_json($this->token);
  }

  function curl($url, $array_post, $request_from, $headers = [])
  {
    $starttime = microtime(true);
    $ch = curl_init();
    $headers[] = 'Content-Type:application/json';
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($array_post));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //Send the request
    $response = curl_exec($ch);
    //Close request
    $http_status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $diff = microtime(true) - $starttime;
    $insert_logs = [
      'request_from'    => $request_from,
      'endpoint'        => $url,
      'request_data'    => json_encode($array_post),
      'response_data'   => $response,
      'http_status'     => $http_status,
      'request_time'    => $diff
    ];
    $this->CI->db->insert("mokita_customer_apps_logs", $insert_logs);
    if ($response === FALSE) {
      // log_message('error', 'Mokita Send Error : ' . curl_error($ch) . 'http status :' . $http_status);
    } else {
      $raw = [
        'url' => $url,
        'request' => $array_post,
        'response' => json_decode($response),
      ];
      // log_message('error', 'Mokita - Sukses. Raw Data : ' . json_encode($raw));
      return $response;
    }
    // curl_close($ch);
    $info = curl_getinfo($ch);
    // log_message('error', $info);
  }

  public function get_token($username = 'sinsen@mokita', $password = 'Sinsen@Mokita2022!')
  {
    $url = $this->base_url . '/api/v1/ddms/token';
    $array_post = [
      'username' => $username,
      'password' => $password,
    ];
    return json_decode($this->curl($url, $array_post, 'dms'), true)['data'];
  }

  public function booking_checkin($array_post)
  {
    $url = $this->base_url . '/api/v1/ddms/booking/setstatus';
    $headers[] = 'Authorization: Bearer ' . $this->token;
    return $this->curl($url, $array_post, 'dms', $headers);
  }

  public function booking_checkout($array_post)
  {
    $url = $this->base_url . '/api/v1/ddms/booking/checkout';
    $headers[] = 'Authorization: Bearer ' . $this->token;
    return $this->curl($url, $array_post, 'dms', $headers);
  }

  public function service_process($array_post)
  {
    $url = $this->base_url . '/api/v1/ddms/booking/information-detail';
    $headers[] = 'Authorization: Bearer ' . $this->token;
    return $this->curl($url, $array_post, 'dms', $headers);
  }
  public function payment($array_post)
  {
    $url = $this->base_url . '/api/v1/ddms/booking/pay';
    $headers[] = 'Authorization: Bearer ' . $this->token;
    return $this->curl($url, $array_post, 'dms', $headers);
  }

  public function h2_external($array_post)
  {
    $url = $this->base_url . '/api/v1/ddms/booking/external';
    $headers[] = 'Authorization: Bearer ' . $this->token;
    return $this->curl($url, $array_post, 'dms', $headers);
  }

  public function h2_sparepart_status($array_post)
  {
    $url = $this->base_url . '/api/v1/ddms/booking/sparepart/status';
    $headers[] = 'Authorization: Bearer ' . $this->token;
    return $this->curl($url, $array_post, 'dms', $headers);
  }

  function h2_kpb_non_booking($array_post)
  {
    $url = $this->base_url . '/api/v1/ddms/kpb/claim-request';
    $headers[] = 'Authorization: Bearer ' . $this->token;
    return $this->curl($url, $array_post, 'dms', $headers);
  }

  function h1_update_status_trade_in($array_post)
  {
    $url = $this->base_url . '/api/v1/ddms/leads/tradein-update';
    $headers[] = 'Authorization: Bearer ' . $this->token;
    return $this->curl($url, $array_post, 'dms', $headers);
  }

  function h1_update_status_test_ride($array_post)
  {
    $url = $this->base_url . '/api/v1/ddms/leads/testride-update';
    $headers[] = 'Authorization: Bearer ' . $this->token;
    return $this->curl($url, $array_post, 'dms', $headers);
  }

  function h1_credit_approval_indent_delivery_stnk_bpkb($array_post)
  {
    $url = $this->base_url . '/api/v1/ddms/motorcycle/order/status/online';
    $headers[] = 'Authorization: Bearer ' . $this->token;
    return $this->curl($url, $array_post, 'dms', $headers);
  }

  function h1_credit_approval_indent_delivery_stnk_bpkb_offline($array_post)
  {
    $url = $this->base_url . '/api/v1/ddms/motorcycle/order/status/offline';
    $headers[] = 'Authorization: Bearer ' . $this->token;
    return $this->curl($url, $array_post, 'dms', $headers);
  }

  function h1_final_process($array_post)
  {
    $url = $this->base_url . '/api/v1/ddms/leads/final';
    $headers[] = 'Authorization: Bearer ' . $this->token;
    return $this->curl($url, $array_post, 'dms', $headers);
  }
}
