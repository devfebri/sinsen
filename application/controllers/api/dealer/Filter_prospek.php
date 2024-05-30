<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Filter_prospek extends CI_Controller
{
  public function __construct(){
    parent::__construct();
    $this->load->model('m_admin');
    $this->db_crm = $this->load->database('db_crm', true);
  }

  public function leads_id()
  {
		$id_dealer = $this->m_admin->cari_dealer();
    $response  = $this->db->query("SELECT leads_id id,leads_id text 
							FROM tr_prospek 
							WHERE id_dealer='$id_dealer' AND leads_id LIKE '%{$this->input->post('searchTerm',true)}%'")->result();
    send_json($response);
  }

  public function tipe_kendaraan()
  {
    $search = '';
    if ((string)$this->input->post('searchTerm')!='') {
      $search = $_POST['searchTerm'];
			$search = "WHERE (id_tipe_kendaraan LIKE '%$search%' OR tipe_ahm LIKE '%$search%') ";
    }
    $response = $this->db->query("SELECT id_tipe_kendaraan id,CONCAT(id_tipe_kendaraan,' - ',tipe_ahm) AS text FROM ms_tipe_kendaraan $search")->result();
    send_json($response);
  }
  public function deskripsiEvent()
  {
    $search = 'WHERE is_event_ve=1';
    if ((string)$this->input->post('searchTerm')!='') {
      $srch = $_POST['searchTerm'];
			$search .= "AND (nama_event LIKE '%$srch%' OR description LIKE '%$srch%') ";
    }
    $response = $this->db->query("SELECT id_event id,nama_event text FROM ms_event $search")->result();
    send_json($response);
  }

  public function hasilStatusFollowUp()
  {
    $search = 'WHERE kodeHasilStatusFollowUp NOT IN(1,2)';
    if ((string)$this->input->post('searchTerm')!='') {
      $srch = $_POST['searchTerm'];
			$search .= "AND deskripsiHasilStatusFollowUp LIKE '%$srch%' ";
    }
    $response = $this->db_crm->query("SELECT kodeHasilStatusFollowUp id,deskripsiHasilStatusFollowUp text FROM ms_hasil_status_follow_up $search")->result();
    send_json($response);
  }

  public function sourceLeads()
  {
    $search = '';
    if ((string)$this->input->post('searchTerm')!='') {
      $srch = $_POST['searchTerm'];
			$search .= "WHERE description LIKE '%$srch%' ";
    }
    $response = $this->db->query("SELECT id_dms id,description text FROM ms_sumber_prospek $search")->result();
    send_json($response);
  }

  public function platformData()
  {
    $search = '';
    if ((string)$this->input->post('searchTerm')!='') {
      $srch = $_POST['searchTerm'];
			$search .= "WHERE platform_data LIKE '%$srch%' ";
    }
    $response = $this->db->query("SELECT id_platform_data id,platform_data text FROM ms_platform_data $search")->result();
    send_json($response);
  }

  public function statusFU()
  {
    $search = '';
    if ((string)$this->input->post('searchTerm')!='') {
      $srch = $_POST['searchTerm'];
			$search .= "WHERE deskripsi_status_fu LIKE '%$srch%' ";
    }
    $response = $this->db_crm->query("SELECT id_status_fu id,deskripsi_status_fu text FROM ms_status_fu $search")->result();
    send_json($response);
  }
  

  public function salesPeople()
  {
    $id_dealer = $this->m_admin->cari_dealer();

    $response= $this->db->query("
    SELECT 0 AS id, '- BELUM ASSIGN -' AS text
    UNION 
        SELECT ms_karyawan_dealer.id_karyawan_dealer as id, CONCAT(ms_karyawan_dealer.id_flp_md, ' - ', ms_karyawan_dealer.nama_lengkap) as text
              FROM ms_karyawan_dealer
              LEFT JOIN ms_dealer ON ms_karyawan_dealer.id_dealer=ms_dealer.id_dealer
              WHERE ms_karyawan_dealer.id_dealer = '$id_dealer' AND id_flp_md <> '' AND ms_karyawan_dealer.id_jabatan IN('JBT-099','JBT-035','JBT-071','JBT-072','JBT-073','JBT-074','JBT-063','JBT-064','JBT-065','JBT-103')  AND ms_karyawan_dealer.active='1' 
              ORDER BY text ASC;
          ")->result();

    send_json($response);
  }


}
