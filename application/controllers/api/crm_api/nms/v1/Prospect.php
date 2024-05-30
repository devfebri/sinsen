<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Prospect extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('sc');
    $this->load->model('m_h1_dealer_prospek', 'm_prospek');
    $this->load->helper('api');
  }

  public function _cari_id_list_appointment($id_dealer)
  {

    //$tgl				= $this->input->post('tgl');
    $th         = date("y");
    $bln         = date("m");
    $tgl         = date("d");
    $tahun = date("Y");

    $tgl            = $this->input->post('tgl');
    $th             = date("y");
    $waktu           = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $get_dealer = $this->db->query("SELECT kode_dealer_md from ms_dealer WHERE id_dealer='$id_dealer'");
    if ($get_dealer->num_rows() > 0) {
      $get_dealer = $get_dealer->row()->kode_dealer_md;
      $panjang = strlen($get_dealer);
    } else {
      $get_dealer = '';
      $panjang = '';
    }

    $pr_num         = $this->db->query("SELECT id_list_appointment FROM tr_prospek WHERE RIGHT(id_list_appointment,$panjang) = '$get_dealer' and left(created_at,4) = '$tahun' ORDER BY id_list_appointment DESC LIMIT 0,1");
    if ($pr_num->num_rows() > 0) {
      $row   = $pr_num->row();
      $pan  = strlen($row->id_list_appointment) - ($panjang + 6);
      $id   = substr($row->id_list_appointment, $pan, 5) + 1;
      if ($id < 10) {
        $kode1 = $th . "0000" . $id;
      } elseif ($id > 9 && $id <= 99) {
        $kode1 = $th . "000" . $id;
      } elseif ($id > 99 && $id <= 999) {
        $kode1 = $th . "00" . $id;
      } elseif ($id > 999) {
        $kode1 = $th . "0" . $id;
      }
      $kode2 = "PR" . $kode1 . "-" . $get_dealer;
    } else {
      $kode2 = "PR" . $th . "00001-" . $get_dealer;
    }

    return $kode2;
  }

  function index()
  {
    $post = json_decode(file_get_contents('php://input'), true);
    // send_json($post);
    $validasi = request_validation();
    if ($validasi['status'] == 0) {
      $response = [
        'status' => 0,
        'message' => $validasi['message'][0],
      ];
      send_json($response);
    }
    $interaksi = $validasi['post']['interaksi'];
    if (isset($validasi['post']['prospek'])) {
      $post      = $validasi['post']['prospek'];
      // Get Data Dealer
      $dealer = $this->db->get_where('ms_dealer', ['kode_dealer_md' => $post['kode_dealer_md']])->row();
      $post['id_dealer'] = $dealer->id_dealer;
      unset($post['kode_dealer_md']);

      // Get ID Prospek
      $sumber_prospek = $post['sumber_prospek'];
      $sp = $this->db->query("SELECT * FROM ms_sumber_prospek WHERE id='$sumber_prospek' OR description='$sumber_prospek'")->row();
      if ($sp==null) {
        $response = ['status' => 0, 'message' => ['Sumber Prospek belum ditentukan !']];
        send_json($response);
      }
      $id_prospek = $this->m_prospek->getIDProspek($sp->id_dms, $dealer->id_dealer);
      $post['id_prospek'] = $id_prospek;
      $post['sumber_prospek'] = $sp->id_dms;

      //Cek Apakah CDB Berdasarkan Field noFramePembelianSebelumnya. Jika CDB Atur Otomatis Untuk Salespeoplenya
      $post['id_karyawan_dealer']=null;
      if ($post['noFramePembelianSebelumnya'] != '') {
        $cek = $this->db->query("SELECT id_karyawan_dealer,id_flp_md 
                            FROM tr_sales_order so
                            JOIN tr_spk spk ON spk.no_spk=so.no_spk
                            JOIN tr_prospek prp ON prp.id_customer=spk.id_customer
                            WHERE so.no_rangka='{$post['noFramePembelianSebelumnya']}' AND so.id_dealer='$dealer->id_dealer'
                            ")->row();
        if ($cek != null) {
          $post['id_karyawan_dealer'] = $cek->id_karyawan_dealer;
          $post['id_flp_md'] = $cek->id_flp_md;
        }
      }

      //Cek Apakah Leads ID Yang Dikirim Sebelumnya Pernah AssignedDealer 
      $cek_lead = $this->db->query("SELECT id_prospek FROM tr_prospek WHERE leads_id='{$post['leads_id']}' ")->row();
      if ($cek_lead != NULL) {
        $deleted_prospek = ['id_prospek'=>$cek_lead->id_prospek];
      }

      // Get Wilayah
      $wil = $this->db->query("SELECT id_kelurahan,kelurahan,kec.id_kecamatan,kec.kecamatan,kab.id_kabupaten, kab.kabupaten,prov.id_provinsi,prov.provinsi,kel.kode_pos FROM ms_kelurahan kel
        JOIN ms_kecamatan kec ON kec.id_kecamatan=kel.id_kecamatan
        JOIN ms_kabupaten kab ON kab.id_kabupaten=kec.id_kabupaten
        JOIN ms_provinsi prov ON prov.id_provinsi=kab.id_provinsi
        WHERE kel.id_kelurahan='{$post['id_kelurahan']}'
        ")->row();
      $post['id_kelurahan'] = $wil == NULL ? NULL : $wil->id_kelurahan;
      $post['id_kecamatan'] = $wil == NULL ? NULL : $wil->id_kecamatan;
      $post['id_kabupaten'] = $wil == NULL ? NULL : $wil->id_kabupaten;
      $post['id_provinsi'] = $wil == NULL ? NULL : $wil->id_provinsi;
      $post['id_list_appointment'] = $this->_cari_id_list_appointment($post['id_dealer']);
    }
    
    $this->db->query("SET foreign_key_checks = 0;");
    $this->db->trans_begin();
    if (isset($deleted_prospek)) {
      $this->db->delete('tr_prospek', ['id_prospek' => $deleted_prospek['id_prospek']]);
      $this->db->delete('tr_prospek_fol_up', ['id_prospek' => $deleted_prospek['id_prospek']]);
      $this->db->delete('tr_prospek_interaksi', ['id_prospek' => $deleted_prospek['id_prospek']]);
    }
    if (isset($post)) {
      $this->db->insert('tr_prospek', $post);
    }
    foreach ($interaksi as $itr) {
      if (!isset($id_prospek)) {
        $id_prospek = $this->db->query("SELECT id_prospek FROM tr_prospek WHERE leads_id='{$itr['leads_id']}'")->row()->id_prospek;
      }
      $itr['id_prospek'] = $id_prospek;
      $this->db->insert('tr_prospek_interaksi', $itr);
    }
    $raw =['post'=>$post];
    log_message('ERROR','cek_proses_api3_crm. raw_data : '.json_encode($raw));
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $response = ['status' => 0, 'message' => ['Telah terjadi kesalahan !']];
    } else {
      $this->db->trans_commit();
      $this->db->query("SET foreign_key_checks = 1");
      $response = [
        'status' => 1,
        'data' => ['id_prospek' => isset($id_prospek) ? $id_prospek : null]
      ];
    }
    send_json($response);
  }
}
