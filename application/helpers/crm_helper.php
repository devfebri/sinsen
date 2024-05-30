<?php

function search_array($array, $search_list)
{

  // Create the result array 
  $result = array();

  // Iterate over each array element 
  foreach ($array as $key => $value) {

    // Iterate over each search condition 
    foreach ($search_list as $k => $v) {

      // If the array element does not meet 
      // the search condition then continue 
      // to the next element 
      if (!isset($value[$k]) || $value[$k] != $v) {

        // Skip two loops 
        continue 2;
      }
    }

    // Append array element's key to the 
    //result array 
    $result[] = $value;
  }

  // Return result  
  return $result;
}

// function tanggal()
// {
//   return gmdate("Y-m-d", time() + 60 * 60 * 7);
// }
function tahun()
{
  return gmdate("Y", time() + 60 * 60 * 7);
}
function tahun_bulan()
{
  return gmdate("Y-m", time() + 60 * 60 * 7);
}

// function waktu()
// {
//   return gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
// }
// function jam()
// {
//   return gmdate("H:i", time() + 60 * 60 * 7);
// }

function _imp($arr)
{
  $expl = explode(',', $arr);
  // $res = '';
  foreach ($expl as $val) {
    $res[] = "'$val'";
  }
  return implode(',', $res);
}

function arr_sql($arr)
{
  foreach ($arr as $val) {
    $res[] = "'$val'";
  }
  if (isset($res)) {
    return implode(',', $res);
  } else {
    return "'-'";
  }
}

// function send_json($arr)
// {
//   echo json_encode($arr);
//   die();
// }

function msg_wrong()
{
  return ['tipe' => 'danger', 'judul' => 'Peringatan', 'pesan' => 'Telah terjadi kesalahan !'];
}
function msg_kombinasi_login_salah()
{
  return 'Kombinasi Email atau Username dengan Password Anda salah. Mohon cek kembali';
}
function msg_not_found()
{
  return  ['icon' => 'error', 'title' => 'Peringatan', 'text' => 'Data tidak ditemukan'];
}
function msg_error($pesan)
{
  return ['tipe' => 'danger', 'judul' => 'Peringatan', 'pesan' => $pesan];
}
function msg_sukses_simpan()
{
  return  ['icon' => 'success', 'title' => 'Informasi', 'text' => 'Data berhasil disimpan'];
}
function msg_sukses($pesan)
{
  return  ['icon' => 'success', 'title' => 'Informasi', 'text' => $pesan];
}
function msg_sukses_update()
{
  return  ['icon' => 'success', 'title' => 'Informasi', 'text' => 'Data berhasil diupdate'];
}

function msg_sukses_upload()
{
  return  ['icon' => 'success', 'title' => 'Success!', 'text' => 'Upload berhasil'];
}

function msg_sukses_hapus()
{
  return  ['tipe' => 'info', 'judul' => 'Informasi', 'pesan' => 'Data berhasil dihapus permanen'];
}

function msg_no_access()
{
  return  ['icon' => 'error', 'title' => 'Peringatan', 'text' => 'Anda tidak memiliki akses'];
}

function random_numbers($digits)
{
  $min = pow(10, $digits - 1);
  $max = pow(10, $digits) - 1;
  return mt_rand($min, $max);
}

function alnum($string)
{
  return preg_replace("/[^a-zA-Z0-9]+/", "", $string);
}


function random_id()
{
  return substr(strtotime(waktu()), -6) . random_numbers(2);
}

// function random_hex($length)
// {
//   $data = 'ABCDEF1234567890';
//   $string = '';
//   for ($i = 0; $i < $length; $i++) {
//     $pos = rand(0, strlen($data) - 1);
//     $string .= $data{
//       $pos};
//   }
//   return $string;
// }

function create_thumbs($params)
{
  $CI = &get_instance();

  $exp_file_name = explode('.', $params['file_name']);
  $file_name_thumb = $exp_file_name[0] . '-small.' . $exp_file_name[1];
  $new_image_small = $params['path'] . '/' . $file_name_thumb;

  // Image resizing config
  $config = array(
    // // Image Large
    // array(
    //   'image_library' => 'GD2',
    //   'source_image'  => './assets/images/' . $file_name,
    //   'maintain_ratio' => FALSE,
    //   'width'         => 700,
    //   'height'        => 467,
    //   'new_image'     => './assets/images/large/' . $file_name
    // ),
    // // image Medium
    // array(
    //   'image_library' => 'GD2',
    //   'source_image'  => './assets/images/' . $file_name,
    //   'maintain_ratio' => FALSE,
    //   'width'         => 600,
    //   'height'        => 400,
    //   'new_image'     => './assets/images/medium/' . $file_name
    // ),
    // Image Small
    array(
      'image_library' => 'GD2',
      'source_image'  => $params['path'] . '/' . $params['file_name'],
      'maintain_ratio' => TRUE,
      // 'create_thumb' => TRUE,
      'width'         => 250,
      // 'height'        => 200,
      // 'thumb_marker' => '_thumb',
      'new_image'     => $new_image_small
    )
  );
  // send_json($config);
  $CI->load->library('image_lib', $config[0]);
  foreach ($config as $item) {
    $CI->image_lib->initialize($item);
    if (!$CI->image_lib->resize()) {
      return false;
    }
    $CI->image_lib->clear();
  }

  return $file_name_thumb;
}


function date2min($hms)
{

  // $fromTime = strtotime($hms);
  // // send_json($fromTime);
  // $getMins = round(abs($fromTime) / 60, 2);

  // $getMins = date('i', strtotime($hms));

  $time = explode(':', $hms);
  $getMins = ($time[0] * 60) + ($time[1]) + ($time[2] / 60);

  return $getMins;
}


function get_slug()
{
  $CI = &get_instance();
  $segment =  $CI->uri->segment(1) . '/' . $CI->uri->segment(2);

  $links = array_keys(links_on_table());
  $links[] = 'fetchData';
  $links[] = 'saveData';
  $links[] = 'saveEdit';
  $links[] = 'insert';
  $links[] = 'saveRoleAkses';
  $links[] = 'saveDataFileToDB';
  if ($CI->uri->segment(3) != NULL) {
    $seg3 = $CI->uri->segment(3);
    if (!in_array($seg3, $links)) {
      $segment .= "/$seg3";
    }
  }

  if ($CI->uri->segment(4) != NULL) {
    $seg4 = $CI->uri->segment(4);
    if (!in_array($seg4, $links)) {
      $segment .= "/$seg4";
    }
  }

  $cek = $CI->db->query("SELECT slug FROM ms_menu WHERE slug='$segment' OR controller='$segment'")->row();
  if ($cek != NULL) {
    return $cek->slug;
  }
}

function get_controller()
{
  $CI = &get_instance();
  $segment =  $CI->uri->segment(1) . '/' . $CI->uri->segment(2);

  $links = array_keys(links_on_table());
  $links[] = 'fetchData';
  $links[] = 'saveData';
  $links[] = 'saveEdit';
  $links[] = 'insert';
  $links[] = 'role_akses';
  if ($CI->uri->segment(3) != NULL) {
    $seg3 = $CI->uri->segment(3);
    if (!in_array($seg3, $links)) {
      $segment .= "/$seg3";
    }
  }

  if ($CI->uri->segment(4) != NULL) {
    $seg4 = $CI->uri->segment(4);
    if (!in_array($seg4, $links)) {
      $segment .= "/$seg4";
    }
  }

  $cek = $CI->db->query("SELECT controller FROM ms_menu WHERE slug='$segment' OR controller='$segment'")->row();
  if ($cek != NULL) {
    return $cek->controller;
  }
}

function all_links()
{
  $CI = &get_instance();
  $cek = $CI->db->query("SELECT link, deskripsi, ikon FROM ms_menu_links ORDER BY order_link ASC")->result();
  $ck = [];
  foreach ($cek as $c) {
    $ck[$c->link] = ['ikon' => $c->ikon, 'deskripsi' => $c->deskripsi];
  }
  return $ck;
}

function links_on_table()
{
  $links = [
    'detail' => [
      'class' => "btn btn-xs btn-info btn-flat",
      'icon' => '<i class="fa fa-eye"></i>',
      'title' => 'Detail',
      'show_title' => 0,
      'tipe' => 'href',
    ],
    'edit' => [
      'class' => "btn btn-warning btn-xs btn-flat",
      'icon' => '<i class="fa fa-edit"></i>',
      'title' => 'Edit',
      'show_title' => 0,
      'tipe' => 'href',
    ],
    'role_akses' => [
      'class' => "btn btn-xs btn-primary btn-flat",
      'icon' => '<i class="fa fa-cogs"></i>',
      'title' => 'Role Akses',
      'show_title' => 0,
      'tipe' => 'href',
    ],
    'history' => [
      'class' => "btn btn-xs btn-primary btn-flat btn_history",
      'icon' => '<i class="fa fa-list"></i>',
      'title' => 'History',
      'show_title' => 0,
      'tipe' => 'button',
      'function' => 'showHistoryLeads'
    ],
    'delete' => [
      'class'      => "btn btn-xs btn-danger btn-flat",
      'icon'       => '<i class = "fa fa-trash"></i>',
      'title'      => 'Hapus',
      'show_title' => 0,
      'tipe'       => 'button',
    ]

  ];
  return $links;
}

function link_on_data_details($params, $id_group, $skip_if = NULL)
{
  $CI = &get_instance();
  $filter = [
    'controller' => get_controller()
  ];
  $links = links_on_table();
  $menu = $CI->dm->getMenus($filter)->row();
  $button = '';
  $explode_links = explode(',', $menu->links_menu);
  $get_links = cekAkasesMenuBySlug($id_group, $menu->slug);
  $explode_links = [];
  foreach ($get_links as $lks) {
    if ($id_group == 1) {
      $explode_links[] = $lks->link;
    } else {
      if ($lks->akses == 1) {
        $explode_links[] = $lks->link;
      }
    }
  }
  foreach ($links as $key => $lk) {
    if (in_array($key,  $explode_links)) {
      $title = $lk['show_title'] == 1 ? $lk['title'] : '';
      $parameter = preg_replace('/\s+/', '', $params['get']);
      $url = site_url("$menu->slug/$key?$parameter");
      if ($lk['tipe'] == 'href') {
        $button .= '<a data-toggle="tooltip" title="' . $lk['title'] . '" href="' . $url . '" class="' . $lk['class'] . '" data-original-title="' . $title . '">' . $lk['icon'] . ' ' . $title . '</a>';
      } else {
        $params_delete = json_encode(['url' => $url]);
        $function = $lk['function'];
        $button .= "<button type=\"button\" class=\"{$lk['class']}\" data-toggle=\"tooltip\" title=\"{$lk['title']}\" onclick='$function(this, $params_delete)'> 
        {$lk['icon']} $title
        </button>";
      }
    }
  }

  // $edit = '<a data-toggle="tooltip" title="Edit" href="tes" class="btn btn-warning btn-xs btn-flat" data-original-title="Edit"><i class="fa fa-edit"></i></a>';
  // $edit .= '<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Tooltip on top">Tooltip on top</button>';
  // $edit .= '<a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button" data-tooltip="tooltip" title="Toggle">cde</a>';
  // $edit .= '<button id="mybtn" type="button" class="btn btn-default" data-toggle="tooltip" data-placement="left" title="Tooltip : left">Tooltip : Left</button>';
  // send_json($button);
  return $button;
}
function link_assign_reassign($leads_id, $id_group, $skip_if = NULL)
{
  $CI = &get_instance();
  $filter = [
    'controller' => get_controller()
  ];
  $links = [
    'assign' => [
      'class' => "btn btn-primary btn-xs btn-flat",
      'icon' => '',
      'title' => 'Assign',
      'onclick' => "showAssign('leads_id')"
    ],
    'reassign' => [
      'class' => "btn btn-success btn-xs btn-flat",
      'icon' => '',
      'title' => 'Reassign',
      'onclick' => "showReAssign('leads_id')"
    ],
  ];
  $menu = $CI->dm->getMenus($filter)->row();
  $button = '';
  $explode_links = explode(',', $menu->links_menu);
  $get_links = cekAkasesMenuBySlug($id_group, $menu->slug);
  $explode_links = [];
  foreach ($get_links as $lks) {
    if ($id_group == 1) {
      $explode_links[] = $lks->link;
    } else {
      if ($lks->akses == 1) {
        $explode_links[] = $lks->link;
      }
    }
  }
  foreach ($links as $key => $lk) {
    if (in_array($key,  $explode_links)) {
      $skip = 0;
      if ($skip_if != NULL) {
        if (isset($skip_if[$key])) {
          $cond_skip = $skip_if[$key];
          foreach ($cond_skip as $ks => $vs) {
            if ($vs[1] == '==') {
              if ($vs[0] == $vs[2]) {
                $skip++;
              }
            } elseif ($vs[1] == '!=') {
              if ($vs[0] != $vs[2]) {
                $skip++;
              }
            }
          }
        }
      }
      if ($skip == 0) {
        $onclick = str_replace('leads_id', $leads_id, $lk['onclick']);
        $button .= "<button type=\"button\" class=\"{$lk['class']}\" onclick=\"$onclick\">
          {$lk['title']}
        </button>";
      }
    }
  }
  return $button;
}


function link_on_data_top($id_group)
{
  $slug = get_slug();
  $button = '';

  $buttons['insert'] = '<a href="' . site_url($slug . '/insert') . '"> <button class="btn bg-blue btn-flat"><i class="fa fa-plus"></i> Add New</button></a>';
  $buttons['upload'] = '<button class="btn btn-info btn-flat" onclick="upload(this)"><i class="fa fa-upload"></i> Upload</button>';
  //Cek Insert
  $links = cekAkasesMenuBySlug($id_group, $slug);
  foreach ($links as $lk) {
    if (isset($buttons[$lk->link]) && $lk->akses == 1) {
      $button .= $buttons[$lk->link];
    }
  }
  return $button;
}

function clear_removed_html($value)
{
  return str_replace('[removed]', '', $value);
}

function convert_datetime($val)
{
  $explode = explode(' ', $val);
  if (count($explode) > 1) {
    $explode[1] = sprintf('%02s', date_parse($explode[1])['month']);
    return $explode[2] . '-' . $explode[1] . '-' . $explode[0] . ' ' . $explode[3];
  } else {
    return $val;
  }
}
function convert_datetime_str($val)
{
  return date("d F Y H:i:s", strtotime($val));
}

function convert_no_hp($val)
{
  if (substr($val, 0, 1) == '+') {
    $val = '0' . substr($val, 3, 30);
  }
  return $val;
}
function convert_no_telp($val)
{
  if (substr($val, 0, 1) == '+') {
    $val = '0' . substr($val, 2, 30);
  }
  return $val;
}


function sql_convert_date($field)
{
  return "DATE_FORMAT($field,'%d %M %Y %H:%i:%s')";
}
function sql_convert_date_dmy($field)
{
  return "DATE_FORMAT($field,'%d/%m/%Y')";
}


function date_iso_8601_to_datetime($date)
{
  return date('Y-m-d H:i:s', strtotime($date));
}

function selisih_2tanggal($start, $end)
{
  $tgl1 = new DateTime(substr($start, 0, 10));
  $tgl2 = new DateTime(substr($end, 0, 10));
  return $tgl2->diff($tgl1);
}
function selisih_detik($start, $end)
{
  $tgl1 = strtotime($start);
  $tgl2 = strtotime($end);
  return $tgl2 - $tgl1;
}

function detik_ke_menit($detik)
{
  return round($detik / 60);
}

function detik_ke_jam($detik)
{
  $menit = detik_ke_menit($detik);
  return round($menit / 60);
}

function detik_ke_hari($detik)
{
  $jam = detik_ke_jam($detik);
  return round($jam / 24);
}

function convert_date($val)
{
  $date = str_replace('/', '-', $val);
  return date('Y-m-d', strtotime($date));
}

function clean_no_hp($no_hp)
{
  $no_hp = preg_replace("/[^0-9]+/", "", $no_hp);
  if (substr($no_hp, 0, 3) == '62') {
    $no_hp = '0' . substr($no_hp, 3, 30);
  } elseif (substr($no_hp, 0, 2) == '62') {
    $no_hp = '0' . substr($no_hp, 2, 30);
  }
  return $no_hp;
}

function set_periode($input)
{
  $periode = explode('-', $input);
  $periodeSet = NULL;
  if (count($periode) > 1) {
    foreach ($periode as $val) {
      $periodeSet[] = convert_date(str_replace(' ', '', $val));
    }
  }
  return $periodeSet;
}

function dMYHIS_en()
{
  return gmdate("d F Y H:i:s", time() + 60 * 60 * 7);
}

function set_errors($error)
{
  $set_errors = [];
  foreach ($error as $key => $val) {
    $err = "<ul>";
    foreach ($val as $v) {
      $err .= "<li>$v</li>";
    }
    $err .= "</ul>";
    $set_errors[] = ['<b>' . $key . '</b>', $err];
  }
  return $set_errors;
}


function cek_error_no_hp($no_hp)
{
  if (strlen($no_hp) < 10) {
    return 'No. HP kurang dari 10 karakter';
  } elseif (strlen($no_hp) > 15) {
    return 'No. HP kurang dari 15 karakter';
  }
}

function cekISO8601Date($dateStr)
{
  if (preg_match('/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/', $dateStr) > 0) {
    return TRUE;
  } else {
    return FALSE;
  }
}

function tanggal_lebih_kecil($awal, $akhir)
{
  if (strtotime($awal) < strtotime($akhir)) {
    return true;
  } else {
    return false;
  }
}

function setStatusProspek($start, $end)
{
  $selisih = selisih_2tanggal($start, $end)->days;
  if ($selisih < 14) {
    $status = 'Hot';
  } elseif ($selisih >= 14 && $selisih <= 28) {
    $status = 'Medium';
  } else {
    $status = 'Low';
  }
  return $status;
}


function validasiAlasanPindahDealer($id_alasan, $lainnya)
{
  $CI = &get_instance();
  $CI->load->model('alasan_reassign_pindah_dealer_model', 'als_m');
  if ((string)$id_alasan == '') {
    $error = 'Alasan Pindah Dealer Kosong';
  } else {
    $fals = ['id_alasan' => $id_alasan];
    $cek_alasan_dealer = $CI->als_m->getAlasanReassign($fals)->row();
    if ($cek_alasan_dealer == null) {
      $error = 'Alasan Pindah Dealer Tidak Ditemukan';
    } else {
      if ($cek_alasan_dealer->id_alasan == 4 && (string)$lainnya == '') {
        $error = 'Keterangan Lainnya Tidak Ke Dealer Sebelumnya Kosong';
      }
    }
  }
  if (isset($error)) {
    return $error;
  }
}


function empty_to_min($val)
{
  return $val == '' ? '-' : $val;
}
