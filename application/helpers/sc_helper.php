<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function get_id_jabatan($id_kry)
{
  $id_jabatan = get_data('ms_karyawan_dealer','id_karyawan_dealer',$id_kry,'id_jabatan');
  return $id_jabatan;
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
function get_user_agent()
{
  return $_SERVER['HTTP_USER_AGENT'];
}

function  pseudo_request($params)
{
  if (isset($params['Authorization'])) {
    $base64 = str_replace("Basic ", '', $params['Authorization']);
    $base64_value = base64_decode($base64);
    // send_json($base64_value);
    $base64_arr = explode(':', $base64_value);
    $result = [
      'username' => isset($base64_arr[0]) ? $base64_arr[0] : NULL,
      'password' => isset($base64_arr[1]) ? $base64_arr[1] : NULL,
      'base64' => $base64
    ];
    return $result;
  }
}
function token_bearer($params)
{
  if (isset($params['Authorization'])) {
    $base64 = str_replace("Bearer ", '', $params['Authorization']);
    return ['token' => $base64];
  }
}

function set_token()
{
  $CI = &get_instance();

  $salt = base_convert(bin2hex($CI->security->get_random_bytes(16)), 16, 36);
  // $salt = $CI->security->get_random_bytes(16);
  $token = base64_encode($salt);
  return $token;
}

function res_token_not_found()
{
  return [
    'status' => 0,
    'message' => ["The token is invalid!"],
    'data' => null
  ];
}
function res_invalid_email()
{
  return [
    'status' => 0,
    'message' => ["Your email is invalid"],
    'data' => null
  ];
}
function res_failed_send_email()
{
  return [
    'status' => 0,
    'message' => ["Failed to send email"],
    'data' => null
  ];
}
function broadcast_message($to)
{
  return true;
}

function middleWareAPI($filter = null)
{
  $CI = &get_instance();
  $header = $CI->input->request_headers();
  // send_json($_SERVER);
  $token = token_bearer($header);
  $filter['token'] = $token['token'];
  // send_json($filter);
  $CI->load->model('m_sc_auth', 'm_auth');
  $res_ = $CI->m_auth->validasiToken($filter);
  if (!isset($filter['skip_user'])) {
    if ($res_ != false) {
      if ($res_->row()->id_user == null) {
        // $CI->output->set_status_header(401);
        $CI->output->set_header('HTTP/1.1 401 Unauthorized');
        $result = [
          'status' => 0,
          'message' => ["The token is invalid!"],
          'data' => null
        ];
        send_json($result);
      }
    }
  }
  if ($res_ == false) {
    $filter_logout['token']=$token['token'];
    $res_ = $CI->m_auth->logout($filter_logout);
    if ($res_ == true) {
      $result =
        [
          'status' => 1,
          'message' => ['You have been logout!']
        ];
    } else {
      $result =
        [
          'status' => 1,
          'message' => ['You have been logout!']
        ];
      // $result = res_token_not_found();
    }
    // $CI->output->set_status_header(401);
    // $result = res_token_not_found();
    // send_json($result); // Dimatikan untuk tes MONJU

    //Statis Untuk MONJU, Selain monju pada kondisi ini harusnya return false
    // $res = new stdClass();
    // $res->id_user = 126;
    // $res->username = 'userPSB';
    // $res->id_dealer = 21;
    // return $res;
  } else {
    $res_ = $res_->row();
    //Reset other Token
    $CI->m_auth->cekLastTokenUser($res_->id_user, $res_->token);
    return $res_;
  }
}
function sc_user($filter)
{
  $CI = &get_instance();
  $where = "WHERE 1=1 ";
  if (isset($filter['id_user'])) {
    $where .= " AND usr.id_user='{$filter['id_user']}'";
  }
  if (isset($filter['username'])) {
    $where .= " AND usr.username_sc='{$filter['username']}'";
  }
  return $CI->db->query("SELECT 
      usr.id_user,
      usr.username,
      usr.username_sc,
      usr.id_karyawan_dealer,
      kd.id_karyawan_dealer_int,
      id_flp_md,
      nama_lengkap,
      kd.id_jabatan,
      jabatan,
      CASE 
        WHEN kd.honda_id iS NULL OR kd.honda_id='' THEN kd.id_flp_md
        ELSE kd.honda_id
      END honda_id,
      kd.image
    FROM ms_user AS usr
    LEFT JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=usr.id_karyawan_dealer
    LEFT JOIN ms_jabatan jb ON jb.id_jabatan=kd.id_jabatan
    $where
  ");
}

function msg_sc_error($msg)
{
  return [
    'status' => 0,
    'message' => $msg,
    // 'data' => NULL
  ];
}

function msg_sc_success($data = 1, $msg = NULL)
{
  if ($msg == NULL) {
    $msg = ['success'];
  }
  // send_json($data);
  $result = [
    'status' => 1,
    'message' => $msg,
    'data' => $data
  ];
  if (!is_object($data)) {
    if ($data == 1) {
      unset($result['data']);
    }
  }
  // send_json($result);
  // if ($data == NULL) {
  //   unset($result['data']);
  // }
  return $result;
}

function  cek_mandatory($field, $form)
{
  // send_json($form);
  foreach ($field as $key => $value) {
    if (isset($form[$key])) {
      if ($form[$key] == '') {
        if ($value == 'required') {
          $msg[] = "$key required";
        }
      }
    } else {
      if ($value == 'required') {
        $msg[] = "$key required";
      }
    }
  }
  // send_json($msg);
  if (isset($msg)) {
    send_json(msg_sc_error($msg));
  }
}

function cek_referensi($data, $id)
{
  if ($data->num_rows() == 0) {
    $data = $data->row();
    $msg = [$id . ' not found'];
    send_json(msg_sc_error($msg));
  }
}

function delete_file_by_url($url)
{
  $base = str_replace('https', 'http', base_url());
  $url = str_replace('https', 'http', $url);
  $len = strlen($base);
  $new_path = substr($url, $len, strlen($url) - $len);
  // send_json($new_path);
  if (file_exists($new_path)) {
    if (unlink($new_path)) {
      return true;
    }
  } else {
    if (file_exists($new_path)) {
      if (unlink($url)) {
        return true;
      }
    }
  }
  // send_json(['url' => $url, 'path' => $new_path]);
}

function info_sisa_hari()
{
  $tanggal = tanggal();
  $selisih = selisihWaktu(tgl_terakhir_bulan($tanggal), $tanggal);
  return mediumdate_indo($tanggal, ' ') . ', ' . $selisih . ' HARI MENUJU AKHIR BULAN';
}

function tgl_terakhir_bulan($tanggal)
{
  return date("Y-m-t", strtotime($tanggal));
}

function bulan_kemarin($tanggal)
{
  $tanggal = date_create($tanggal);
  // return $tanggal;
  date_add($tanggal, date_interval_create_from_date_string('-1 months'));
  return date_format($tanggal, 'Y-m');
}
function default_img_adm_lk()
{
  return base_url('assets/panel/images/admin-lk.jpg');
}

function default_img_adm_pr()
{
  return base_url('assets/panel/images/admin-pr.png');
}


function image_karyawan($image, $jk = NULL)
{
  if ($image == NULL or $image == '') {
    // send_json($image);
    $img =  default_img_adm_lk();
    if (strtolower($jk) == 'laki-laki' || strtolower($jk) == 'pria' || strtolower($jk) == 'l') {
      return default_img_adm_lk();
    } else {
      $img =  default_img_adm_pr();
    }
  } else {
    $img =  base_url($image);
  }
  // send_json($img);
  return $img;
}

function array_sort_by_column(&$arr, $col, $dir = SORT_DESC)
{
  $sort_col = array();
  foreach ($arr as $key => $row) {
    $sort_col[$key] = $row[$col];
    // send_json($sort_col);
  }
  array_multisort($sort_col, $dir, $arr);
}

function ordinal($number)
{
  $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
  if ((($number % 100) >= 11) && (($number % 100) <= 13))
    return $number . 'th';
  else
    return $number . $ends[$number % 10];
}

function info_rank($params)
{
  if ($params['rank'] > $params['rank_sebelumnya']) {
    $selisih = $params['rank'] - $params['rank_sebelumnya'];
    return "Naik $selisih Peringkat dari bulan lalu";
  } elseif ($params['rank'] < $params['rank_sebelumnya']) {
    $selisih = $params['rank_sebelumnya'] - $params['rank'];
    return "Turun $selisih Peringkat dari bulan lalu";
  } else {
    return "Sama dengan peringkat bulan lalu";
  }
}
function color_status_queue($status)
{
  $status = strtolower($status);
  $color = '#ba000d';
  if ($status == 'waiting') {
    $color = '#4ba3c7';
  } elseif ($status == 'cancel') {
    $color = '#870000';
  } elseif ($status == 'closed') {
    $color = '#6abf69';
  }
  return $color;
}
function color_status_monitor($status)
{
  $status = strtolower($status);
  $color = '#ba000d';
  if ($status == 'antrian') {
    $color = '#4ba3c7';
  } elseif ($status == 'diservis') {
    $color = '#870000';
  } elseif ($status == 'dipanggil') {
    $color = '#6abf69';
  } elseif ($status == 'menunggu_masuk_pit') {
    $color = '#6abf69';
  } elseif ($status == 'selesai') {
    $color = '#6abf69';
  }
  return $color;
}
function status_pkb_apps($params)
{
  $CI = &get_instance();
  $status = strtolower($params['status']);
  $st = $CI->db->get_where('sc_ms_service_management_status', ['id' => 1, 'status_for' => 'pkb'])->row();
  $color = $st->status_color;
  $status_n = $st->status;
  if ($status == 'open') {
    if ($params['last_stats'] == 'start' || $params['last_stats'] == 'resume') {
      $st = $CI->db->get_where('sc_ms_service_management_status', ['id' => 2, 'status_for' => 'pkb'])->row();
      $color = $st->status_color;
      $status_n = $st->status;
    } elseif ($params['last_stats'] == 'end') {
      $st = $CI->db->get_where('sc_ms_service_management_status', ['id' => 3, 'status_for' => 'pkb'])->row();
      $color = $st->status_color;
      $status_n = $st->status;
    }
  } elseif ($status == 'pause') {
    if ($params['last_stats'] == 'end') {
      $st = $CI->db->get_where('sc_ms_service_management_status', ['id' => 3, 'status_for' => 'pkb'])->row();
      $color = $st->status_color;
      $status_n = $st->status;
    }
  } elseif ($status == 'closed') {
    $st = $CI->db->get_where('sc_ms_service_management_status', ['id' => 3, 'status_for' => 'pkb'])->row();
    $color = $st->status_color;
    $status_n = $st->status;
  }
  $result = ['status' => $status_n, 'status_color' => $color];
  return $result;
}
function status_pkb_work($params)
{
  $CI = &get_instance();
  $status = strtolower($params['status']);
  $st = $CI->db->get_where('sc_ms_service_management_status', ['id' => 4, 'status_for' => 'pkb_work'])->row();
  $color = $st->status_color;
  $status_n = $st->status;
  if ($status == 'open') {
    if ($params['last_stats'] == 'start' || $params['last_stats'] == 'resume') {
      $st = $CI->db->get_where('sc_ms_service_management_status', ['id' => 5, 'status_for' => 'pkb_work'])->row();
      $color = $st->status_color;
      $status_n = $st->status;
    }
  } elseif ($status == 'pause') {
    $st = $CI->db->get_where('sc_ms_service_management_status', ['id' => 6, 'status_for' => 'pkb_work'])->row();
    $color = $st->status_color;
    $status_n = $st->status;
  } elseif ($status == 'closed') {
    $st = $CI->db->get_where('sc_ms_service_management_status', ['id' => 7, 'status_for' => 'pkb_work'])->row();
    $color = $st->status_color;
    $status_n = $st->status;
  }
  $result = ['status' => $status_n, 'status_color' => $color];
  return $result;
}

function status_pkb_on_pit($params)
{
  $status_wo = strtolower($params['status_wo']);
  $start_at = $params['start_at'];
  $vehicle_offroad = $params['vehicle_offroad'];
  $new_status = 'Belum Dimulai';
  if ($status_wo == 'open' && $start_at != NULL) {
    $new_status = 'Pengerjaan';
    if ($params['last_stats'] == 'end') {
      $new_status = 'Selesai';
    }
  } elseif ($status_wo == 'pause') {
    $new_status = 'Ditahan';
    if ($vehicle_offroad == 1) {
      $new_status = 'Ditunda';
    }
  } elseif ($status_wo == 'closed') {
    $new_status = 'Selesai';
  }
  return $new_status;
}
// function custom_number_format($n, $precision = 3)
// {
//   if ($n < 1000000) {
//     // Anything less than a million
//     $n_format = number_format($n);
//   } else if ($n < 1000000000) {
//     // Anything less than a billion
//     $n_format = number_format($n / 1000000, $precision) . 'M';
//   } else {
//     // At least a billion
//     $n_format = number_format($n / 1000000000, $precision) . 'B';
//   }

//   return $n_format;
// }

function custom_number_format($n, $precision = 1)
{
  if ($n < 900) {
    // 0 - 900
    $n_format = number_format($n, $precision);
    $suffix = '';
  } else if ($n < 900000) {
    // 0.9k-850k
    $n_format = number_format($n / 1000, $precision);
    $suffix = 'K';
  } else if ($n < 900000000) {
    // 0.9m-850m
    $n_format = number_format($n / 1000000, $precision);
    $suffix = 'M';
  } else if ($n < 900000000000) {
    // 0.9b-850b
    $n_format = number_format($n / 1000000000, $precision);
    $suffix = 'B';
  } else {
    // 0.9t+
    $n_format = number_format($n / 1000000000000, $precision);
    $suffix = 'T';
  }
  // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
  // Intentionally does not affect partials, eg "1.50" -> "1.50"
  if ($precision > 0) {
    $dotzero = '.' . str_repeat('0', $precision);
    $n_format = str_replace($dotzero, '', $n_format);
  }
  return $n_format . $suffix;
}


function send_fcm($params)
{

  $CI = &get_instance();
  $account = $CI->db->get_where('sc_ms_firebase_account', ['active' => 1, 'for' => $params['for']])->row();
  // send_json($account);
  define('API_ACCESS_KEY', $account->api_access_key);
  $regid = $params['regid']; //Array

  $msg = array(
    'title'     => $params['judul'],
    'message'     => $params['pesan'],
    'content_available' => true,
    'priority' => 'high',
  );
  $fields = array(
    'registration_ids'  => $regid,
    'data'      => $msg
  );
  $headers = array(
    'Authorization: key=' . API_ACCESS_KEY,
    'Content-Type: application/json'
  );

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
  $chresult = curl_exec($ch);

  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if (curl_errno($ch)) {
    return false; //probably you want to return false
  }
  if ($httpCode != 200) {
    return false; //probably you want to return false
  }
  curl_close($ch);
  return $chresult;
}

function get_only_one($arrays, $only)
{
  $new_arr = [];
  foreach ($arrays as $val) {
    $new_arr[] = $val[$only];
  }
  if ($new_arr > 0) {
    return $new_arr;
  }
}


function tanggal_kemarin($tanggal)
{
  //Ambil Bulan Kemarin
  $ym = substr($tanggal, 0, 7);
  $bulan_kemarin =  bulan_kemarin($ym);
  $last_bulan_ini = last_date_month($tanggal);
  $tgl_bulan_kemarin = date('Y-m-d', strtotime($bulan_kemarin . '-' . substr($tanggal, 8, 2)));
  // if (get_tgl($last_bulan_ini) == get_tgl($tanggal)) {
  // 	return last_date_month($bulan_kemarin);
  // }
  if (get_tgl($tgl_bulan_kemarin) == get_tgl($tanggal)) {
    return $tgl_bulan_kemarin;
  }
  if (get_tgl($tgl_bulan_kemarin) < get_tgl($tanggal)) {
    return last_date_month($bulan_kemarin);
  }
}
function get_tgl($tanggal)
{
  return substr($tanggal, 8, 2);
}
function last_date_month($tanggal)
{
  $tanggal = substr($tanggal, 0, 7) . '-01';
  $date = new DateTime($tanggal);
  $date->modify('last day of this month');
  return $date->format('Y-m-d');
}


function set_title($jk)
{
  $resp = '';
  if (strtolower($jk) == 'laki-laki' || strtolower($jk) == 'l') {
    $resp = 'Tuan';
  } elseif (strtolower($jk) == 'perempuan' || strtolower($jk) == 'p') {
    $resp = 'Nyonya';
  }
  return $resp;
}


function format_size($size)
{
  $mod = 1024;
  $units = explode(' ', 'B KB MB GB TB PB');
  for ($i = 0; $size > $mod; $i++) {
    $size /= $mod;
  }
  return round($size, 2) . ' ' . $units[$i];
}
