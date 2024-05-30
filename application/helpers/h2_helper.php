<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function update_customer($customer)
{
  $upd_cust = [
    // 'nama_customer'          => $customer['nama_customer'],
    // 'nama_stnk'              => $customer['nama_stnk'],
    // 'nama_pembawa'        => $customer['nama_pembawa'],
    'email'                  => $customer['email'],
    'no_hp'                  => $customer['no_hp'],
    'alamat'                 => $customer['alamat'],
    'jenis_identitas'        => $customer['jenis_identitas'],
    'no_identitas'           => $customer['no_identitas'],
    'alamat_identitas'       => $customer['alamat_identitas'],
    'jenis_kelamin'          => $customer['jenis_kelamin'],
    'id_kelurahan'           => $customer['id_kelurahan'],
    // 'no_polisi'              => strtoupper($customer['no_polisi']),
    // 'no_mesin'               => isset($customer['no_mesin']) ? $customer['no_mesin'] : null,
    // 'id_tipe_kendaraan'      => isset($customer['id_tipe_kendaraan']) ? $customer['id_tipe_kendaraan'] : null,
    // 'id_warna'               => isset($customer['id_warna']) ? $customer['id_warna'] : null,
    // 'no_rangka'              => $customer['no_rangka'],
    // 'tahun_produksi'         => $customer['tahun_produksi'],
    // 'tgl_pembelian'          => $customer['tgl_pembelian'],
    'id_kelurahan_identitas' => $customer['id_kelurahan_identitas'],
    'jenis_customer_beli'    => $customer['jenis_customer_beli'],
    'id_agama'               => $customer['id_agama'],
    'longitude'              => $customer['longitude'],
    'latitude'               => $customer['latitude'],
    'tgl_lahir'               => $customer['tgl_lahir'] == '' ? NULL : $customer['tgl_lahir'],
    'id_pekerjaan'               => $customer['id_pekerjaan'],
    // 'id_dealer'              => $customer['id_dealer'],
    // 'updated_at'             => $waktu,
    // 'updated_by'             => $login_id,
    'ganti_customer'         => 1
    // 'id_kecamatan' => $this->input->post('id_kecamatan'),
    // 'id_kabupaten'    => $this->input->post('id_kabupaten'),
    // 'id_provinsi'     => $this->input->post('id_provinsi'),
  ];
  return $upd_cust;
}

function insert_customer($customer)
{
  $ins_cust = [
    'nama_customer'     => $customer['nama_customer'],
    'nama_stnk'         => $customer['nama_stnk'],
    // 'nama_pembawa'   => $customer['nama_pembawa'],
    'id_dealer_h1'      => isset($customer['id_dealer_h1']) ? $customer['id_dealer_h1'] : null,
    'email'             => isset($customer['email']) ? $customer['email'] : null,
    'no_hp'             => isset($customer['no_hp']) ? $customer['no_hp'] : null,
    'alamat'            => isset($customer['alamat']) ? $customer['alamat'] : null,
    'jenis_identitas'   => isset($customer['jenis_identitas']) ? $customer['jenis_identitas'] : null,
    'no_identitas'      => isset($customer['no_identitas']) ? $customer['no_identitas'] : null,
    'alamat_identitas'      => isset($customer['alamat_identitas']) ? $customer['alamat_identitas'] : null,
    'id_kelurahan'      => isset($customer['id_kelurahan']) ? $customer['id_kelurahan'] : null,
    'jenis_kelamin'     => isset($customer['jenis_kelamin']) ? $customer['jenis_kelamin'] : null,
    'no_mesin'          => isset($customer['no_mesin']) ? $customer['no_mesin'] : '',
    'no_rangka'         => isset($customer['no_rangka']) ? $customer['no_rangka'] : '',
    'no_polisi'         => isset($customer['no_polisi']) ? strtoupper($customer['no_polisi']) : '',
    'id_tipe_kendaraan' => isset($customer['id_tipe_kendaraan']) ? $customer['id_tipe_kendaraan'] : null,
    'id_warna'          => isset($customer['id_warna']) ? $customer['id_warna'] : null,
    'tahun_produksi'    => isset($customer['tahun_produksi']) ? $customer['tahun_produksi'] : '',
    'tgl_pembelian'    => isset($customer['tgl_pembelian']) ? date_ymd($customer['tgl_pembelian']) : '',
    'id_kelurahan_identitas'    => isset($customer['id_kelurahan_identitas']) ? $customer['id_kelurahan_identitas'] : '',
    'jenis_customer_beli' => isset($customer['jenis_customer_beli']) ? $customer['jenis_customer_beli'] : '',
    'id_agama'       => isset($customer['id_agama']) ? $customer['id_agama']            : '',
    'tgl_lahir'       => isset($customer['tgl_lahir']) ? date_ymd($customer['tgl_lahir'])            : NULL,
    'id_pekerjaan'       => isset($customer['id_pekerjaan']) ? $customer['id_pekerjaan']            : '',
    'longitude'      => isset($customer['longitude']) ? $customer['longitude']          : '',
    // 'latitude'       => isset($customer['latitude']) ? $customer['latitude']            : '',
    // 'created_at'     => $waktu,
    // 'created_by'     => $login_id,
    // 'id_dealer'      => $id_dealer
    // 'id_kecamatan'    => $this->input->post('id_kecamatan'),
    // 'id_kabupaten'    => $this->input->post('id_kabupaten'),
    // 'id_provinsi'     => $this->input->post('id_provinsi'),
  ];

  return $ins_cust;
}

function date_ymd($val)
{
  $date = str_replace('/', '-', $val);
  return date('Y-m-d', strtotime($date));
}
function date_ym($val)
{
  $date = str_replace('/', '-', $val);
  return date('Y-m', strtotime($date));
}
function date_dmy($val, $separator = null)
{
  if ($separator == null) $separator = '/';
  $val_expl = explode(' ', $val);
  if (count($val_expl) > 1) {
    $new_date =  date('d' . $separator . 'm' . $separator . 'Y', strtotime($val_expl[0]));
    $new_date = $new_date . ' ' . $val_expl[1];
  } else {
    $new_date =  date('d' . $separator . 'm' . $separator . 'Y', strtotime($val));
  }
  if ($val == '0000-00-00') {
    return $val;
  } else {
    return $new_date;
  }
}
function date_time_dmyhis($date)
{
  return date('d/m/Y H:i:s', strtotime($date));
}

function kop_surat_dealer($id_dealer)
{
  $CI = &get_instance();
  $dealer = $CI->db->query("SELECT ms_dealer.*,kelurahan,kecamatan,kabupaten,provinsi FROM ms_dealer 
                      LEFT JOIN ms_kelurahan ON ms_kelurahan.id_kelurahan=ms_dealer.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan=ms_kelurahan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten=ms_kecamatan.id_kabupaten
                      LEFT JOIN ms_provinsi ON ms_provinsi.id_provinsi=ms_kabupaten.id_provinsi
                      WHERE id_dealer='$id_dealer'
                      ")->row();

  $html = "<div>" . strtoupper($dealer->nama_dealer) . "</div>";
  $html .= "<div>" . strtoupper($dealer->alamat) . "</div>";
  $html .= "<div>" . ucwords($dealer->kecamatan . " " . $dealer->kabupaten) . "</div>";
  $html .= "<div>" . strtoupper($dealer->provinsi) . "</div>";
  $html .= "<div>" . strtoupper($dealer->no_telp) . "</div>";
  return $html;
}

function kry_login($filter)
{
  $where = "WHERE 1=1 ";
  if (is_array($filter)) {
    if (isset($filter['id_karyawan_dealer'])) {
      $where .= " AND kry.id_karyawan_dealer='{$filter['id_karyawan_dealer']}'";
    }
  } else {
    $where .= " AND ms_user.id_user='$filter'";
  }
  $CI = &get_instance();
  return $CI->db->query("SELECT kry.id_karyawan_dealer,nama_lengkap,kry.id_jabatan,jabatan,id_flp_md
  FROM ms_karyawan_dealer kry
  LEFT JOIN ms_jabatan ON ms_jabatan.id_jabatan=kry.id_jabatan
  LEFT JOIN ms_user ON ms_user.id_karyawan_dealer=kry.id_karyawan_dealer
  $where
  ")->row();
}

function arr_in_sql($arr)
{
  // var_dump($arr);
  // send_json($arr);
  if (is_array($arr)) {
    $arr_exp = $arr;
  } else {
    $arr_exp = explode(',', $arr);
  }
  if (count($arr_exp) > 0) {
    foreach ($arr_exp as $val) {
      $new_arr[] = "'$val'";
    }
    return implode(',', $new_arr);
  } else {
    if (is_array($arr)) {
      return implode(',', $arr);
    } else {
      return "'$arr'";
    }
  }
}

function waktu_full()
{
  return gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
}
function get_ymd()
{
  return gmdate("Y-m-d", time() + 60 * 60 * 7);
}
function get_ym()
{
  return gmdate("Y-m", time() + 60 * 60 * 7);
}
function get_y()
{
  return gmdate("Y", time() + 60 * 60 * 7);
}
function get_m()
{
  return gmdate("m", time() + 60 * 60 * 7);
}
function get_d()
{
  return gmdate("d", time() + 60 * 60 * 7);
}
function jam_menit()
{
  return gmdate("H:i", time() + 60 * 60 * 7);
}
function year()
{
  return gmdate("Y", time() + 60 * 60 * 7);
}
function waktu_dgi_file()
{
  return gmdate("ymdHis", time() + 60 * 60 * 7);
}

function dealer($id_dealer = NULL)
{
  $CI = &get_instance();
  $CI->load->model('m_admin');
  $id_dealer = $id_dealer == NULL ? $CI->m_admin->cari_dealer() : $id_dealer;
  $kode_group_dealer = "SELECT id_group_dealer FROM ms_group_dealer_detail WHERE id_dealer=ms_dealer.id_dealer";
  $dealer = $CI->db->query("SELECT kode_dealer_md,id_dealer,nama_dealer,alamat,h1,h2,h3,no_telp,kel.id_kelurahan,npwp,pkp,email,pimpinan,
  CASE WHEN pkp='Ya' THEN 1 ELSE 0 END AS pkp,kelurahan,kecamatan,kabupaten,provinsi,($kode_group_dealer) AS id_group_dealer,CASE WHEN tampil_ppn_h23 IS NULL THEN 0 ELSE tampil_ppn_h23 END as tampil_ppn_h23,ms_dealer.logo,contact_booking_service
  FROM ms_dealer 
  LEFT JOIN ms_kelurahan kel ON kel.id_kelurahan=ms_dealer.id_kelurahan
  LEFT JOIN ms_kecamatan kec ON kec.id_kecamatan=kel.id_kecamatan
  LEFT JOIN ms_kabupaten kab ON kab.id_kabupaten=kec.id_kabupaten
  LEFT JOIN ms_provinsi prov ON prov.id_provinsi=kab.id_provinsi
  WHERE id_dealer='$id_dealer'");
  if ($dealer->num_rows() > 0) {
    return $dealer->row();
  }
}
function main_dealer()
{
  $CI = &get_instance();
  return $CI->db->query("SELECT kode_md,nama_perusahaan,alamat from setting_md")->row();
}

function user()
{
  $CI = &get_instance();
  $id_user = $CI->session->userdata('id_user');
  return $CI->db->query("SELECT usr.id_user,usr.id_karyawan_dealer,id_flp_md,nama_lengkap,kd.id_jabatan,jabatan
    FROM ms_user AS usr
    LEFT JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=usr.id_karyawan_dealer
    LEFT JOIN ms_jabatan jb ON jb.id_jabatan=kd.id_jabatan
    WHERE usr.id_user='$id_user'
  ")->row();
}

function tanggal()
{
  return gmdate("Y-m-d", time() + 60 * 60 * 7);
}

function jam()
{
  return gmdate("H:i:s", time() + 60 * 60 * 7);
}

function num_to_letters($n)
{
  $n -= 1;
  for ($r = ""; $n >= 0; $n = intval($n / 26) - 1)
    $r = chr($n % 26 + 0x41) . $r;
  return $r;
}
function style_col()
{
  return array(
    'font' => array('bold' => true), // Set font nya jadi bold
    'alignment' => array(
      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
    ),
    'borders' => array(
      'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
      'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
      'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
      'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
    )
  );
}

function style_row()
{
  return array(
    'alignment' => array(
      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
    ),
    'borders' => array(
      'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
      'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
      'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
      'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
    )
  );
}
function border_row()
{
  return ['borders' => array(
    'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
    'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
    'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
    'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
  )];
}

function zenziva_sms($params)
{
  $userkey = 'hre5fo';
  $passkey = '1147eivvtm';
  $telepon = '082282535844';
  $message = 'Hi John Doe, have a nice day.';
  $url = 'https://gsm.zenziva.net/api/sendsms/';
  $curlHandle = curl_init();
  curl_setopt($curlHandle, CURLOPT_URL, $url);
  curl_setopt($curlHandle, CURLOPT_HEADER, 0);
  curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
  curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
  curl_setopt($curlHandle, CURLOPT_POST, 1);
  curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
    'userkey' => $userkey,
    'passkey' => $passkey,
    'nohp' => $params['no_hp'],
    'pesan' => $params['pesan']
  ));
  $results = json_decode(curl_exec($curlHandle), true);
  curl_close($curlHandle);
  return $results;
}

function zenziva_wa($params)
{
  $userkey = 'hre5fo';
  $passkey = '1147eivvtm';
  $url = 'https://gsm.zenziva.net/api/sendWA/';
  $curlHandle = curl_init();
  curl_setopt($curlHandle, CURLOPT_URL, $url);
  curl_setopt($curlHandle, CURLOPT_HEADER, 0);
  curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
  curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
  curl_setopt($curlHandle, CURLOPT_POST, 1);
  curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
    'userkey' => $userkey,
    'passkey' => $passkey,
    'nohp' => $params['no_hp'],
    'pesan' => $params['pesan']
  ));
  $results = json_decode(curl_exec($curlHandle), true);
  curl_close($curlHandle);
  return $results;
}

function after($ini, $inthat)
{
  if (!is_bool(strpos($inthat, $ini)))
    return substr($inthat, strpos($inthat, $ini) + strlen($ini));
};

function generate_pesan($params)
{
  $ref = $params['ref'];
  // send_json($ref);
  $konten = $params['konten'];
  $str = explode(']', $konten);
  foreach ($str as $val) {
    $kata  = after('[', $val);
    if (isset($ref[$kata])) {
      $konten = str_replace("[$kata]", $ref[$kata], $konten);
    }
  }
  return $konten;
}

function set_diskon($diskon, $tipe)
{
  $tipe = strtolower($tipe);
  if ($tipe == 'persen') {
    $diskon = (int) $diskon . ' %';
  } elseif ($tipe == 'percentage') {
    $diskon = (int) $diskon . ' %';
  } elseif ($tipe == 'rupiah') {
    $diskon = 'Rp. ' . mata_uang_rp((int) $diskon);
  }
  return $diskon;
}

function remove_space($var, $replace)
{
  return preg_replace('/\s+/', $replace, $var);
}

function tambah_hari($params)
{
  $tanggal     = new DateTime($params['tanggal']);
  return $tanggal->modify("+{$params['days']} days")->format('Y-m-d');
}

function sql_generate_ym($filter)
{
  $start = $filter['bulan_awal'];
  $end = $filter['bulan_akhir'];
  return "
 (
  SELECT 
   '$start-01' +INTERVAL m MONTH AS m1
  FROM
   (
     SELECT
       @rownum:=@rownum+1 AS m
     FROM
      (SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4) t1,
      (SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4) t2,
      (SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4) t3,
      (SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4) t4,
      (SELECT @rownum:=-1) t0
   ) d1
 ) d2 
WHERE
  m1<='$end-01'
ORDER BY m1";
}

function sql_jabatan_mekanik()
{
  return "'JBT-026','JBT-032','JBT-042','JBT-043','JBT-051'";
}

function sql_generate_tanggal()
{
  $sql = "( SELECT '01' AS tgl UNION ALL  ";
  for ($i = 2; $i <= 30; $i++) {
    $sql .= ' SELECT \'' . sprintf("%'.02d", $i) . '\' UNION ALL ';
  }
  $sql .= " SELECT 31) AS tgl";
  return $sql;
}

// function penyebut($nilai)
// {
//   $nilai = abs($nilai);
//   $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
//   $temp = "";
//   if ($nilai < 12) {
//     $temp = " " . $huruf[$nilai];
//   } else if ($nilai < 20) {
//     $temp = penyebut($nilai - 10) . " belas";
//   } else if ($nilai < 100) {
//     $temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
//   } else if ($nilai < 200) {
//     $temp = " seratus" . penyebut($nilai - 100);
//   } else if ($nilai < 1000) {
//     $temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
//   } else if ($nilai < 2000) {
//     $temp = " seribu" . penyebut($nilai - 1000);
//   } else if ($nilai < 1000000) {
//     $temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
//   } else if ($nilai < 1000000000) {
//     $temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
//   } else if ($nilai < 1000000000000) {
//     $temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
//   } else if ($nilai < 1000000000000000) {
//     $temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
//   }
//   return $temp;
// }

// function terbilang($nilai)
// {
//   if ($nilai < 0) {
//     $hasil = "minus " . trim(penyebut($nilai));
//   } else {
//     $hasil = trim(penyebut($nilai));
//   }
//   return $hasil;
// }

function konversi_bln($bulan)
{
  switch ($bulan) {
    case "1":
      $bulan = "Januari";
      break;
    case "2":
      $bulan = "Februari";
      break;
    case "3":
      $bulan = "Maret";
      break;
    case "4":
      $bulan = "April";
      break;
    case "5":
      $bulan = "Mei";
      break;
    case "6":
      $bulan = "Juni";
      break;
    case "7":
      $bulan = "Juli";
      break;
    case "8":
      $bulan = "Agustus";
      break;
    case "9":
      $bulan = "September";
      break;
    case "10":
      $bulan = "Oktober";
      break;
    case "11":
      $bulan = "November";
      break;
    case "12":
      $bulan = "Desember";
      break;
  }
  $bln = $bulan;
  return $bln;
}

function konversi_detik_ke_jam_menit($detik)
{
  $jumlah_jam = floor($detik / 3600);

  //Untuk menghitung jumlah dalam satuan menit:
  $sisa = $detik % 3600;
  $jumlah_menit = floor($sisa / 60);

  //Untuk menghitung jumlah dalam satuan detik:
  $sisa = $sisa % 60;
  $jumlah_detik = floor($sisa / 1);
  // return sprintf("%'.02d", $jumlah_jam) . ':' . sprintf("%'.02d", $jumlah_menit) . ':' . sprintf("%'.02d", $jumlah_detik);
  return sprintf("%'.02d", $jumlah_jam) . ':' . sprintf("%'.02d", $jumlah_menit);
}

function sql_date_dmy($date)
{
  return "DATE_FORMAT($date,'%d/%m/%Y')";
}

function sql_date_dmyhi($date)
{
  return "DATE_FORMAT($date,'%d-%m-%Y %H:%i')";
}
function converted_datetime($datetime)
{
  //+1 day +1 hour +30 minutes +45 seconds
  return date('Y-m-d H:i:s', strtotime('+3 day', strtotime($datetime)));
}

function subtotal_part($parts, $harga)
{
  // $parts['tipe_diskon'] = 'Value';
  $harga_real = $harga;
  // $pkp = dealer()->pkp;
  // if ($pkp == 1) {
  //   // $harga = $harga / 1.1;
  // }
  if ($parts['tipe_diskon'] == 'Percentage') {
    $diskon = ($parts['diskon_value'] / 100) * $harga;
    $harga_real -= $diskon;
  }
  $kuantitas = $parts['qty'];
  if ($parts['tipe_diskon'] == 'FoC') {
    $kuantitas -= $parts['diskon_value'];
  }

  $potongan_harga = 0;
  if ($parts['tipe_diskon'] == 'Value') {
    $potongan_harga = $parts['diskon_value'];
  }
  return ($kuantitas * $harga_real) - $potongan_harga;
}

function random_hex_color()
{
  return substr(md5(rand()), 0, 6);
}


function iframe_template($data)
{
  // send_json($data);
  $CI = &get_instance();
  $data['iframe'] = true;
  $CI->load->view('template/iframe/header', $data);
  $CI->load->view($data['folder'] . "/" . $data['page']);
  $CI->load->view('template/iframe/footer');
}

function array_only($data, $field, $implode_sql = false, $not_in = [])
{
  $return = [];
  foreach ($data as $dtl) {
    if (count($not_in) > 0) {
      if (!in_array($dtl->$field, $not_in)) {
        $return[] = $dtl->$field;
      }
    } else {
      $return[] = $dtl->$field;
    }
  }
  if ($implode_sql == true) {
    $return = arr_in_sql($return);
  }
  return $return;
}
