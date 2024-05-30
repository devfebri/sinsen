<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class H2_dealer_customer_list_model extends CI_Model{

    // var $table = 'tr_log_generate_customer_list_fol_up as a';
    var $column_order = array(null, 'c.id_follow_up','c.id_customer','nama_customer','a.tgl_generate','folup_ke', 'c.id_media_kontak_fol_up', 'c.tgl_fol_up', 'c.id_kategori_status_komunikasi', 'c.tgl_next_fol_up', null );
    var $column_search = array('c.id_follow_up','c.id_customer', 'nama_customer');
    var $order = array('b.tgl_assigned' => 'desc');

    public function __construct()
    {
      parent::__construct();
      $this->load->database();
      $this->load->model('m_admin');
    }

    public function getDataPekerjaan()
    {
			$pekerjaan = $this->db->query("SELECT id_pekerjaan, pekerjaan
			FROM ms_pekerjaan")->result();
			return $pekerjaan;
    }

    public function getDataHasilFU()
    {
      $listFU = $this->db_crm->query("SELECT id_kategori_status_komunikasi, kategori_status_komunikasi 
                FROM ms_kategori_status_komunikasi")->result();
      return $listFU;
    }

    public function getDataKendaraan()
    {
      $kendaraan = $this->db->query("SELECT id_tipe_kendaraan, tipe_ahm
                    FROM ms_tipe_kendaraan");
      return $kendaraan;
    }

    public function getMediaKomunikasi()
    {
      $mediaKomunikasi = $this->db_crm->query("SELECT id_media_kontak_fu, media_kontak_fu 
                FROM ms_media_kontak_fu WHERE aktif='1'")->result();
      return $mediaKomunikasi;
    }

    public function getDataDealer()
		{
			$query=$this->db->query("SELECT id_dealer,kode_dealer_ahm,nama_dealer from ms_dealer where id_dealer in('94','2',	'103',	'105',	'46',	'47',	'4',	'1',	'51',	'22',	'107',	'101',	'40',	'80',	'71',	'18',	'97',	'13',	'84',	'43',	'25',	'83',	'41',	'39',	'104',	'106',	'102',	'96',	'85',	'8',	'81',	'44',	'38',	'76',	'30',	'82',	'70',	'77',	'86',	'91',	'58',	'19',	'64',	'54',	'23',	'29',	'11',	'9',	'10',	'74',	'98',	'7',	'6',	'56',	'28',	'69',	'5',	'87',	'89',	'88',	'66',	'90',	'78',	'65', '128')");
			return $query->result();
		}

    public function getDataJasaType()
    {
      $jasaType=$this->db->query("SELECT id_type, deskripsi FROM ms_h2_jasa_type");
      return $jasaType;
    }

    public function getDataKaryawan()
    {
      $id_dealer = $this->m_admin->cari_dealer();
      $pic = $this->db->query("SELECT id_karyawan_dealer,id_flp_md,nama_lengkap FROM ms_karyawan_dealer WHERE id_dealer= '$id_dealer' and active='1'")->result();
      return $pic;
    }

    public function getDataTemplate()
    {
      $id_dealer = $this->m_admin->cari_dealer();
      $template_pesan = $this->db->query("SELECT id_template,pesan,kategori,id_dealer FROM template_pesan_fu_h23 WHERE kategori='WA' AND id_dealer='$id_dealer'");
      return $template_pesan;
    }

    public function getDataTemplateGlobal()
    {
      $id_dealer = $this->m_admin->cari_dealer();
      $template_pesan_global = $this->db->query("SELECT id_template,pesan,kategori,id_dealer FROM template_pesan_fu_h23 WHERE kategori='SMS' AND id_dealer='$id_dealer'");
      return $template_pesan_global;
    }

    public function downloadExcel($id_dealer)
    {

      $filter_active_passive = '';
      $filter_avg_rp_ue = '';
      $filter_km_terakhir='';
      $filter_status_fu='';
      $filter_last_fu2='';
      $filter_gender='';
      $tahun_motor = $this->input->post('tahun_motor');
      $filter_tahun_motor='';
      $mc_type = $this->input->post('filters_id_tipe_kendaraan');
      $splittedNumbers = explode(",", $mc_type);
      $numbers = "'" . implode("', '", $splittedNumbers) ."'";
      $filter_mc_type2 = '';
      $profesi=$this->input->post('profesi');
      $status_fu=$this->input->post('status_fu');
      $filter_profesi='';
      $toj = $this->input->post('filters_last_toj');
      $splittedNumbers_toj = explode(",", $toj);
      $numbers_toj = "'" . implode("', '", $splittedNumbers_toj) ."'";
      $filter_toj2 = '';
      $filter_frekuensi_servis='';
      $filter_waktu_servis_terakhir='';
      $waktu_servis_terakhir=$this->input->post('waktu_service_terakhir');
      $start_date=$this->input->post('last_fu_start');
      $end_date=$this->input->post('last_fu_end');
      $gender=$this->input->post('gender');
      // var_dump($start_date."-".$end_date);
      // die();
      
      if ($this->input->post('active_passive') == 'active') {
        $filter_active_passive= 'and max(f.tgl_servis) >= DATE_SUB(NOW(),INTERVAL 6 MONTH)';
      }elseif($this->input->post('active_passive') == 'passive'){
        $filter_active_passive='and max(f.tgl_servis) <= DATE_SUB(NOW(),INTERVAL 6 MONTH)';
      }

      if ($this->input->post('avg_rp_ue') == 'kurang_dr_9') {
        $filter_avg_rp_ue='and ( select (SUM(y.grand_total) /COUNT(y.id_work_order)) 
        FROM tr_h2_sa_form x
        join tr_h2_wo_dealer y on x.id_sa_form=y.id_sa_form
        where x.id_customer=f.id_customer
        group by x.id_customer ) <= 100000';
      }elseif($this->input->post('avg_rp_ue') == 'rentang_1_4'){
        $filter_avg_rp_ue='and ( select (SUM(y.grand_total) /COUNT(y.id_work_order)) 
        FROM tr_h2_sa_form x
        join tr_h2_wo_dealer y on x.id_sa_form=y.id_sa_form
        where x.id_customer=f.id_customer 
        group by x.id_customer ) >=100001 and ( select (SUM(y.grand_total) /COUNT(y.id_work_order)) 
        FROM tr_h2_sa_form x
        join tr_h2_wo_dealer y on x.id_sa_form=y.id_sa_form
        where x.id_customer=f.id_customer 
        group by x.id_customer ) <=250000';
      }elseif($this->input->post('avg_rp_ue') == 'rentang_2_5'){
        $filter_avg_rp_ue='and ( select (SUM(y.grand_total) /COUNT(y.id_work_order)) 
        FROM tr_h2_sa_form x
        join tr_h2_wo_dealer y on x.id_sa_form=y.id_sa_form
        where x.id_customer=f.id_customer 
        group by x.id_customer ) >=250001 and ( select (SUM(y.grand_total) /COUNT(y.id_work_order)) 
        FROM tr_h2_sa_form x
        join tr_h2_wo_dealer y on x.id_sa_form=y.id_sa_form
        where x.id_customer=f.id_customer 
        group by x.id_customer ) <=500000';
      }elseif($this->input->post('avg_rp_ue') == 'lebih_dr_5'){
        $filter_avg_rp_ue='and ( select (SUM(y.grand_total) /COUNT(y.id_work_order)) 
        FROM tr_h2_sa_form x
        join tr_h2_wo_dealer y on x.id_sa_form=y.id_sa_form
        where x.id_customer=f.id_customer 
        group by x.id_customer ) >=500001';
      }

      if ($this->input->post('km_terakhir') == 'kurang_dr_999') {
        $filter_km_terakhir='and f.km_terakhir <= 999';
      }elseif($this->input->post('km_terakhir') == 'antara_1000_1999'){
          $filter_km_terakhir='and f.km_terakhir >=1000 and f.km_terakhir <=1999';
      }elseif($this->input->post('km_terakhir') == 'antara_2000_3999'){
          $filter_km_terakhir='and f.km_terakhir >=2000 and f.km_terakhir <=3999';
      }elseif($this->input->post('km_terakhir') == 'antara_4000_7999'){
          $filter_km_terakhir='and f.km_terakhir >=4000 and f.km_terakhir <=7999';
      }elseif($this->input->post('km_terakhir') == 'antara_8000_9999'){
          $filter_km_terakhir='and f.km_terakhir >=8000 and f.km_terakhir <=9999';
      }elseif($this->input->post('km_terakhir') == 'antara_10000_11999'){
          $filter_km_terakhir='and f.km_terakhir >=10000 and f.km_terakhir <=11999';
      }elseif($this->input->post('km_terakhir') == 'antara_12000_15999'){
          $filter_km_terakhir='and f.km_terakhir >=12000 and f.km_terakhir <=15999';
      }elseif($this->input->post('km_terakhir') == 'antara_16000_23999'){
          $filter_km_terakhir='and f.km_terakhir >=16000 and f.km_terakhir <=23999';
      }elseif($this->input->post('km_terakhir') == 'lebih_dr_24000'){
          $filter_km_terakhir='and f.km_terakhir >=24000';
      }

      if ($this->input->post('tahun_motor') != NULL) {
        $filter_tahun_motor=" AND b.tahun_produksi = '$tahun_motor'";
      }

      if($this->input->post('id_tipe_kendaraan') != null){
        $filter_mc_type2=" AND a.id_tipe_kendaraan IN ($numbers)";
      }

      if ($this->input->post('profesi') != NULL) {
        $filter_profesi= " AND e.id_pekerjaan = '$profesi'";
      }

      // if($this->input->post('last_toj')!=null and $this->input->post('last_toj')>1){
      //   $thelist = implode("','",$toj);
      //   $thelist = "'".$thelist."'";
      //       // var_dump($thelist);
      //       // die;
      //   $filter_toj=" AND f.id_type IN ($thelist)";
      //       // var_dump($filter_toj);
      //       // die;
      // }

      if($this->input->post('filters_last_toj') != null){
        $filter_toj2=" AND f.id_type IN ($numbers_toj)";
      }

      if ($this->input->post('frekuensi_service') == 'kurang_dr_5') {
              // $this->db->group_by('a.id_customer');
        $filter_frekuensi_servis=" HAVING frekuensi_service <=5 ";
      }elseif($this->input->post('frekuensi_service') == 'rentang_6_10'){
              // $this->db->group_by('a.id_customer');
        $filter_frekuensi_servis=" HAVING frekuensi_service BETWEEN 6 AND 10 ";
      }elseif($this->input->post('frekuensi_service') == 'rentang_11_20'){
              // $this->db->group_by('a.id_customer');
        $filter_frekuensi_servis=" HAVING frekuensi_service BETWEEN 11 AND 20 ";
      }elseif($this->input->post('frekuensi_service') == 'lebih_dr_21'){
              // $this->db->group_by('a.id_customer');
        $filter_frekuensi_servis=" HAVING frekuensi_service >=21 ";
      }
          
      // if ($this->input->post('waktu_service_terakhir') == '1') {
      //   $filter_waktu_servis_terakhir = " AND period_diff(date_format(now(), '%Y%m'), date_format(f.tgl_servis, '%Y%m'))<= '$waktu_servis_terakhir'";
      // }elseif($this->input->post('waktu_service_terakhir') == '2'){
      //   $filter_waktu_servis_terakhir = " AND period_diff(date_format(now(), '%Y%m'), date_format(f.tgl_servis, '%Y%m'))= '$waktu_servis_terakhir'";
      // }elseif($this->input->post('waktu_service_terakhir') == '3'){
      //   $filter_waktu_servis_terakhir = " AND period_diff(date_format(now(), '%Y%m'), date_format(f.tgl_servis, '%Y%m'))= '$waktu_servis_terakhir'";
      // }elseif($this->input->post('waktu_service_terakhir') == '5'){
      //   $filter_waktu_servis_terakhir = " AND period_diff(date_format(now(), '%Y%m'), date_format(f.tgl_servis, '%Y%m'))= '$waktu_servis_terakhir'";
      // }elseif($this->input->post('waktu_service_terakhir') == '6'){
      //   $filter_waktu_servis_terakhir = " AND period_diff(date_format(now(), '%Y%m'), date_format(f.tgl_servis, '%Y%m'))= '$waktu_servis_terakhir'";
      // }elseif($this->input->post('waktu_service_terakhir') == '7'){
      //   $filter_waktu_servis_terakhir = " AND period_diff(date_format(now(), '%Y%m'), date_format(f.tgl_servis, '%Y%m'))= '$waktu_servis_terakhir'";
      // }elseif($this->input->post('waktu_service_terakhir') == '8'){
      //   $filter_waktu_servis_terakhir = " AND period_diff(date_format(now(), '%Y%m'), date_format(f.tgl_servis, '%Y%m'))= '$waktu_servis_terakhir'";
      // }elseif($this->input->post('waktu_service_terakhir') == '9'){
      //   $filter_waktu_servis_terakhir = " AND period_diff(date_format(now(), '%Y%m'), date_format(f.tgl_servis, '%Y%m'))= '$waktu_servis_terakhir'";
      // }elseif($this->input->post('waktu_service_terakhir') == '10'){
      //   $filter_waktu_servis_terakhir = " AND period_diff(date_format(now(), '%Y%m'), date_format(f.tgl_servis, '%Y%m'))= '$waktu_servis_terakhir'";
      // }elseif($this->input->post('waktu_service_terakhir') == '11'){
      //   $filter_waktu_servis_terakhir = " AND period_diff(date_format(now(), '%Y%m'), date_format(f.tgl_servis, '%Y%m'))= '$waktu_servis_terakhir'";
      // }elseif($this->input->post('waktu_service_terakhir') == '12'){
      //   $filter_waktu_servis_terakhir = " AND period_diff(date_format(now(), '%Y%m'), date_format(f.tgl_servis, '%Y%m'))= '$waktu_servis_terakhir'";
      // }

      if ($this->input->post('waktu_service_terakhir') == '1') {
        $filter_waktu_servis_terakhir = " AND period_diff(date_format(now(), '%Y%m'), date_format(f.tgl_servis, '%Y%m'))<= '$waktu_servis_terakhir'";
      }elseif($this->input->post('waktu_service_terakhir') == '12'){
        $filter_waktu_servis_terakhir = " AND period_diff(date_format(now(), '%Y%m'), date_format(f.tgl_servis, '%Y%m'))= '$waktu_servis_terakhir'";
      }elseif($this->input->post('waktu_service_terakhir') != NULL){
        $filter_waktu_servis_terakhir = " AND period_diff(date_format(now(), '%Y%m'), date_format(f.tgl_servis, '%Y%m'))= '$waktu_servis_terakhir'";
      }

      if ($this->input->post('status_fu') != NULL) {
        $filter_status_fu= " AND j.id_kategori_status_komunikasi = '$status_fu'";
      }

      if ($this->input->post('last_fu_start') != NULL && $this->input->post('last_fu_end') != NULL) {
        $filter_last_fu2=" AND j.tgl_fol_up >='$start_date' AND j.tgl_fol_up <='$end_date' ";
      }

      if ($this->input->post('gender') != NULL) {
        $filter_gender= " AND a.jenis_kelamin = '$gender'";
      }

      $downloadExcel = $this->db->query("SELECT a.id_customer, a.no_mesin, d.tipe_ahm, e.pekerjaan,c.no_hp,(CASE WHEN f.tipe_coming like '%milik%' then a.nama_customer else g.nama END) as nama_pembawa, (CASE WHEN g.no_hp != NULL THEN g.no_hp ELSE c.no_hp END)AS no_hp_pembawa, max(f.id_type) as id_type, max(left(i.closed_at,10)) as closed_at,
      (SELECT count(id_sa_form) as frekuensi_service FROM tr_h2_sa_form WHERE id_customer= a.id_customer) as frekuensi_service
                          FROM ms_customer_h23 a
                          JOIN tr_sales_order b ON a.no_mesin = b.no_mesin 
                          JOIN tr_spk c ON b.no_spk=c.no_spk 
                          JOIN ms_tipe_kendaraan d on d.id_tipe_kendaraan=a.id_tipe_kendaraan 
                          JOIN ms_pekerjaan e ON e.id_pekerjaan=c.pekerjaan 
                          left JOIN tr_h2_sa_form f ON a.id_customer=f.id_customer 
                          left JOIN ms_h2_pembawa g ON f.id_pembawa = g.id_pembawa 
                          -- JOIN ms_h2_jasa_type h ON h.id_type=f.id_type 
                          JOIN tr_h2_wo_dealer i ON i.id_sa_form = f.id_sa_form
                          LEFT JOIN tr_h2_fol_up_detail j on j.id_customer=a.id_customer 
                          WHERE f.id_dealer='$id_dealer' $filter_active_passive $filter_avg_rp_ue $filter_km_terakhir $filter_tahun_motor $filter_mc_type2 $filter_profesi $filter_toj2 $filter_waktu_servis_terakhir $filter_status_fu $filter_last_fu2 $filter_gender AND a.id_log_follow_up IS NULL
                          GROUP BY a.id_customer 
                          $filter_frekuensi_servis
                          ORDER BY f.tgl_servis desc");
			  return $downloadExcel;
    }


    public function generateData($id_dealer)
    {
      $toj=$this->input->post('last_toj');
      $status_fu=$this->input->post('status_fu');
      $last_fu_start=$this->input->post('last_fu_start');

      $this->db->select('a.no_rangka');
      $this->db->select('a.no_mesin');
      $this->db->select('a.id_tipe_kendaraan');
      $this->db->select('d.tipe_ahm');
      $this->db->select('a.tgl_pembelian as tgl_pembelian');
      $this->db->select('a.tahun_produksi as tahun_motor');
      $this->db->select('e.pekerjaan');
      $this->db->select('(CASE WHEN f.tipe_coming like "%milik%" then a.nama_customer else g.nama END) as nama_pembawa');
      $this->db->select('(CASE WHEN g.no_hp != NULL THEN g.no_hp ELSE a.no_hp END)AS no_hp_pembawa');
      $this->db->select('f.tgl_servis');
      $this->db->select('h.deskripsi');
      $this->db->select('i.total_jasa');
      $this->db->select('f.km_terakhir');
      $this->db->select('a.id_customer');
      $this->db->select('f.id_type');
      $this->db->select('period_diff(date_format(now(), "%Y%m"), date_format(f.tgl_servis, "%Y%m")) as months');
      // $this->db->select('count(f.id_sa_form) as frekuensi_service');
      $this->db->select('(SELECT count(id_sa_form) FROM tr_h2_sa_form WHERE id_customer = a.id_customer) as frekuensi_service', false);
      $this->db->from('ms_customer_h23 as a');
      $this->db->join('ms_tipe_kendaraan as d', 'd.id_tipe_kendaraan=a.id_tipe_kendaraan');
      $this->db->join('ms_pekerjaan as e', 'e.id_pekerjaan=a.id_pekerjaan','left');
      $this->db->join('tr_h2_sa_form as f', 'a.id_customer=f.id_customer','left');
      $this->db->join('ms_h2_pembawa as g', 'f.id_pembawa = g.id_pembawa', 'left');
      $this->db->join('ms_h2_jasa_type as h', 'h.id_type=f.id_type');
      $this->db->join('tr_h2_wo_dealer as i', 'i.id_sa_form = f.id_sa_form');
      $this->db->join('tr_h2_fol_up_detail as j', 'j.id_customer=a.id_customer', 'left');
      $this->db->where('f.id_dealer',$id_dealer);
      $this->db->where('a.id_log_follow_up is NULL');
      if ($this->input->post('tahun_motor') != NULL) {
        $this->db->where('a.tahun_produksi = ', $this->input->post('tahun_motor'));
      }

      if ($this->input->post('no_mesin')!= NULL) {
        $this->db->like('a.no_mesin', $this->input->post('no_mesin'));
      }

      if ($this->input->post('active_passive') == 'active') {
        $this->db->where('f.tgl_servis >= DATE_SUB(NOW(),INTERVAL 6 MONTH)');
      }elseif($this->input->post('active_passive') == 'passive'){
        $this->db->where('f.tgl_servis <= DATE_SUB(NOW(),INTERVAL 6 MONTH)');
      }

      // if ($this->input->post('avg_rp_ue') == 'kurang_dr_9') {
      //   $this->db->where('i.total_jasa <= 99999');
      // }elseif($this->input->post('avg_rp_ue') == 'rentang_1_4'){
      //     $this->db->where('i.total_jasa >=100000 and i.total_jasa <=249999');
      // }elseif($this->input->post('avg_rp_ue') == 'rentang_2_5'){
      //     $this->db->where('i.total_jasa >=250000 and i.total_jasa <=499999');
      // }elseif($this->input->post('avg_rp_ue') == 'lebih_dr_5'){
      //     $this->db->where('i.total_jasa >=500000');
      // }

      if ($this->input->post('avg_rp_ue') == 'kurang_dr_9') {
        $this->db->where('( select (SUM(y.grand_total) /COUNT(y.id_work_order)) 
        FROM tr_h2_sa_form x
        join tr_h2_wo_dealer y on x.id_sa_form=y.id_sa_form
        where x.id_customer=f.id_customer 
        group by x.id_customer ) <= 100000');
      }elseif($this->input->post('avg_rp_ue') == 'rentang_1_4'){
          $this->db->where('( select (SUM(y.grand_total) /COUNT(y.id_work_order)) 
          FROM tr_h2_sa_form x
          join tr_h2_wo_dealer y on x.id_sa_form=y.id_sa_form
          where x.id_customer=f.id_customer 
          group by x.id_customer ) >=100001 and ( select (SUM(y.grand_total) /COUNT(y.id_work_order)) 
          FROM tr_h2_sa_form x
          join tr_h2_wo_dealer y on x.id_sa_form=y.id_sa_form
          where x.id_customer=f.id_customer
          group by x.id_customer ) <=250000');
      }elseif($this->input->post('avg_rp_ue') == 'rentang_2_5'){
          $this->db->where('( select (SUM(y.grand_total) /COUNT(y.id_work_order)) 
          FROM tr_h2_sa_form x
          join tr_h2_wo_dealer y on x.id_sa_form=y.id_sa_form
          where x.id_customer=f.id_customer
          group by x.id_customer ) >=250001 and ( select (SUM(y.grand_total) /COUNT(y.id_work_order)) 
          FROM tr_h2_sa_form x
          join tr_h2_wo_dealer y on x.id_sa_form=y.id_sa_form
          where x.id_customer=f.id_customer 
          group by x.id_customer ) <=500000');
      }elseif($this->input->post('avg_rp_ue') == 'lebih_dr_5'){
          $this->db->where('( select (SUM(y.grand_total) /COUNT(y.id_work_order)) 
          FROM tr_h2_sa_form x
          join tr_h2_wo_dealer y on x.id_sa_form=y.id_sa_form
          where x.id_customer=f.id_customer 
          group by x.id_customer ) >=500001');
      }  

      if ($this->input->post('km_terakhir') == 'kurang_dr_999') {
        $this->db->where('f.km_terakhir <= 999');
      }elseif($this->input->post('km_terakhir') == 'antara_1000_1999'){
          $this->db->where('f.km_terakhir >=1000 and f.km_terakhir <=1999');
      }elseif($this->input->post('km_terakhir') == 'antara_2000_3999'){
          $this->db->where('f.km_terakhir >=2000 and f.km_terakhir <=3999');
      }elseif($this->input->post('km_terakhir') == 'antara_4000_7999'){
          $this->db->where('f.km_terakhir >=4000 and f.km_terakhir <=7999');
      }elseif($this->input->post('km_terakhir') == 'antara_8000_9999'){
          $this->db->where('f.km_terakhir >=8000 and f.km_terakhir <=9999');
      }elseif($this->input->post('km_terakhir') == 'antara_10000_11999'){
          $this->db->where('f.km_terakhir >=10000 and f.km_terakhir <=11999');
      }elseif($this->input->post('km_terakhir') == 'antara_12000_15999'){
          $this->db->where('f.km_terakhir >=12000 and f.km_terakhir <=15999');
      }elseif($this->input->post('km_terakhir') == 'antara_16000_23999'){
          $this->db->where('f.km_terakhir >=16000 and f.km_terakhir <=23999');
      }elseif($this->input->post('km_terakhir') == 'lebih_dr_24000'){
          $this->db->where('f.km_terakhir >=24000');
      }

      if($this->input->post('filter_mc_type') != null and count($this->input->post('filter_mc_type')) > 0 ){
        $this->db->where_in('a.id_tipe_kendaraan ',$this->input->post('filter_mc_type'));
      }

      if ($this->input->post('profesi') != NULL) {
        $this->db->where('e.id_pekerjaan = ',$this->input->post('profesi'));
      }

      // if($this->input->post('last_toj')!=null and $this->input->post('last_toj')>0){
      //   // $thelist = implode("','",$toj);
      //   // $thelist = "'".$thelist."'";
      //   // $filter_toj=" AND f.id_type IN ($thelist)";
      //   $this->db->where_in('f.id_type',  $this->input->post('last_toj'));
      // }
      
      if($this->input->post('filter_toj') != null and count($this->input->post('filter_toj')) > 0 ){
        $this->db->where_in('f.id_type ',$this->input->post('filter_toj'));
      }

      if ($this->input->post('waktu_service_terakhir') != NULL) {
        $this->db->where('period_diff(date_format(now(), "%Y%m"), date_format(f.tgl_servis, "%Y%m")) = ' , $this->input->post('waktu_service_terakhir'));
      }

      if ($this->input->post('status_fu') != NULL) {
        $this->db->where('j.id_kategori_status_komunikasi =', $this->input->post('status_fu')); 
      }

      if($this->input->post('last_fu_start') != null && $this->input->post('last_fu_end')!= null){
        $this->db->where('j.tgl_fol_up >=', $this->input->post('last_fu_start'));
        $this->db->where('j.tgl_fol_up <=', $this->input->post('last_fu_end')); 
      }

      if ($this->input->post('gender') != NULL) {
        $this->db->where('a.jenis_kelamin = ',$this->input->post('gender'));
      }

      $this->db->group_by('a.id_customer');


      if ($this->input->post('frekuensi_service') == 'kurang_dr_5') {
        $this->db->having('frekuensi_service <=', '5');
      }elseif($this->input->post('frekuensi_service') == 'rentang_6_10'){
          $this->db->having('frekuensi_service BETWEEN 6 AND 10');
      }elseif($this->input->post('frekuensi_service') == 'rentang_11_20'){
          $this->db->having('frekuensi_service BETWEEN 11 AND 20');
      }elseif($this->input->post('frekuensi_service') == 'lebih_dr_21'){
          $this->db->having('frekuensi_service >=', '21');
      }
    
      return $this->db->get()->result();
    }

    public function getDetailData($id_customer)
    {
      
      $getDetailData = $this->db->query("SELECT a.id_customer, a.nama_customer, a.tahun_produksi, (CASE WHEN f.id_pembawa != '' THEN g.nama ELSE a.nama_customer END) as nama_pembawa, (CASE WHEN a.no_hp != '' THEN a.no_hp ELSE g.no_hp END) AS hp_pembawa, h.deskripsi, i.saran_mekanik, m.provinsi, a.no_rangka, a.no_mesin, a.no_polisi, a.tgl_lahir AS tgl_lahir,(CASE WHEN f.tipe_coming like '%milik%' then a.nama_customer else g.nama END) as nama_pengguna, a.tujuan_penggunaan_motor, a.email,DATE_FORMAT(t1.tgl_servis2,'%d-%m-%Y') as tgl_servis, DATE_FORMAT(a.tgl_pembelian,'%d-%m-%Y') as tgl_pembelian
      FROM ms_customer_h23 a
      JOIN ms_tipe_kendaraan d on d.id_tipe_kendaraan=a.id_tipe_kendaraan 
      LEFT JOIN ms_pekerjaan e ON e.id_pekerjaan=e.id_pekerjaan 
      left JOIN tr_h2_sa_form f ON a.id_customer=f.id_customer 
      join (select max(y.tgl_servis) as tgl_servis2, y.id_type ,y.id_customer from ms_customer_h23 x 
      					left join tr_h2_sa_form y on x.id_customer=y.id_customer group by x.id_customer) as t1 on t1.id_customer=f.id_customer and t1.tgl_servis2=f.tgl_servis 
      left JOIN ms_h2_pembawa g ON f.id_pembawa = g.id_pembawa 
      JOIN ms_h2_jasa_type h ON h.id_type=f.id_type 
      JOIN tr_h2_wo_dealer i ON i.id_sa_form = f.id_sa_form
      LEFT JOIN ms_kelurahan j ON j.id_kelurahan = a.id_kelurahan 
      LEFT JOIN ms_kecamatan k ON k.id_kecamatan = j.id_kecamatan 
      LEFT JOIN ms_kabupaten l ON l.id_kabupaten = k.id_kabupaten 
      LEFT JOIN ms_provinsi m ON m.id_provinsi = l.id_provinsi 
      where a.id_customer = '$id_customer'");
      return $getDetailData;
    }

    private function _get_datatables_query()
    { 
      $id_dealer = $this->m_admin->cari_dealer();
      // $db_crm = $this->db_crm       = $this->load->database('db_crm', true);
      $this->db->select('b.id_customer,b.id_follow_up, c.id_kategori_status_komunikasi, count(c.id_follow_up) as folup_ke, a.tgl_generate, c.tgl_fol_up,c.tgl_next_fol_up, d.nama_customer as nama_customer, b.tgl_assigned');
      $this->db->from('tr_log_generate_customer_list_fol_up as a');
      $this->db->join('tr_h2_fol_up_header as b','a.id_generate = b.id_generate');
      $this->db->join('tr_h2_fol_up_detail as c', 'c.id_follow_up=b.id_follow_up');
      $this->db->join('ms_customer_h23 as d', 'd.id_log_follow_up=b.id_generate and d.id_customer = b.id_customer');
      $this->db->where('c.is_done =','0');
      $this->db->where('d.id_log_follow_up !=','');
      $this->db->where('a.assign_dealer =',$id_dealer);
      $this->db->group_by('c.id_follow_up');

        $i = 0;
        foreach ($this->column_search as $item) // looping awal
        {
          if($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
          {
            if($i===0) // looping awal
            {
              $this->db->group_start(); 
              $this->db->like($item, $_POST['search']['value']);
            }
            {
              $this->db->or_like($item, $_POST['search']['value']);
            }
              if(count($this->column_search) - 1 == $i) 
              $this->db->group_end(); 
            }
            $i++;
        }
         
        if(isset($_POST['order'])) 
        {
          $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        if(isset($this->order))
        {
          $order = $this->order;
          $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
      $this->_get_datatables_query();
      if($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
      $query = $this->db->get();
      return $query->result();
    }
 
    function count_filtered()
    {
      $this->_get_datatables_query();
      $query = $this->db->get();
      return $query->num_rows();
    }
 
    public function count_all()
    {
      $id_dealer = $this->m_admin->cari_dealer();
      $this->db->select('c.id_customer,c.id_follow_up, c.id_kategori_status_komunikasi, count(c.id_follow_up) as folup_ke, a.tgl_generate, c.tgl_fol_up,c.tgl_next_fol_up, 
      d.nama_customer as nama_customer');
      $this->db->from('tr_log_generate_customer_list_fol_up as a');
      $this->db->join('tr_h2_fol_up_header as b','a.id_generate = b.id_generate');
      $this->db->join('tr_h2_fol_up_detail as c', 'c.id_follow_up = b.id_follow_up');
      $this->db->join('ms_customer_h23 as d', 'd.id_customer = c.id_customer');
      // $this->db->join('tr_h2_sa_form e', 'e.id_customer=c.id_customer');
      // $this->db->join('ms_h2_pembawa as f', 'f.id_pembawa=e.id_pembawa','left');
      // $this->db->join('tr_h2_manage_booking as i', 'i.id_customer=b.id_customer','left');
      $this->db->where('c.is_done =','0');
      $this->db->where('d.id_log_follow_up !=',NULL);
      $this->db->where('c.id_dealer =',$id_dealer);
      // $this->db->where('d.tgl_booking_service','>=','i.tgl_servis');
      // $this->db->or_where('d.tgl_booking_service is NULL');
      // $this->db->or_where('i.tgl_servis is NULL');
      // $this->db->join($this->db_crm->.'ms_media_kontak_fu as y','y.id_media_kontak_fu=d.id_media_kontak_fol_up');
      $this->db->group_by('c.id_follow_up');
      return $this->db->count_all_results();
    }

    public function getFUData()
    {
      $id_follow_up = $this->input->get('id_follow_up');

      $getFUData = $this->db->query("SELECT o.id_follow_up, p.id_generate, a.nama_customer, a.id_customer, a.tahun_produksi, (CASE WHEN f.id_pembawa != '' THEN g.nama ELSE a.nama_customer END) as nama_pembawa, (CASE WHEN a.no_hp != '' THEN a.no_hp ELSE g.no_hp END) AS hp_pembawa, h.deskripsi, i.saran_mekanik, m.provinsi, a.no_rangka, a.no_mesin, a.no_polisi, (CASE WHEN a.tgl_lahir != NULL THEN TIMESTAMPDIFF(YEAR, a.tgl_lahir , CURDATE()) ELSE '-' END) AS umur, a.email,DATE_FORMAT(f.tgl_servis,'%d-%m-%Y') as tgl_servis, a.tujuan_penggunaan_motor, DATE_FORMAT(a.tgl_pembelian, '%d-%m-%Y') as tgl_pembelian,a.tgl_lahir 
      FROM ms_customer_h23 a
      JOIN ms_tipe_kendaraan d on d.id_tipe_kendaraan=a.id_tipe_kendaraan 
      LEFT JOIN ms_pekerjaan e ON e.id_pekerjaan=a.id_pekerjaan 
      JOIN (SELECT id_customer,max(tgl_servis) as tgl_servis,id_pembawa,id_type,id_sa_form FROM tr_h2_sa_form group by id_customer) as f ON a.id_customer=f.id_customer 
      LEFT JOIN ms_h2_pembawa g ON f.id_pembawa = g.id_pembawa 
      LEFT JOIN ms_h2_jasa_type h ON h.id_type=f.id_type 
      LEFT JOIN tr_h2_wo_dealer i ON i.id_sa_form = f.id_sa_form
      LEFT JOIN ms_kelurahan j ON j.id_kelurahan = a.id_kelurahan 
      LEFT JOIN ms_kecamatan k ON k.id_kecamatan = j.id_kecamatan 
      LEFT JOIN ms_kabupaten l ON l.id_kabupaten = k.id_kabupaten 
      LEFT JOIN ms_provinsi m ON m.id_provinsi = l.id_provinsi
      JOIN tr_h2_fol_up_header n ON n.id_customer=a.id_customer 
      JOIN tr_h2_fol_up_detail o ON o.id_customer=n.id_customer AND o.id_follow_up=n.id_follow_up 
      JOIN tr_log_generate_customer_list_fol_up p on p.id_generate=n.id_generate
      WHERE n.id_follow_up = '$id_follow_up' 
      GROUP BY n.id_follow_up");
      return $getFUData;
    }

    public function historyFollowUp()
    {
      $id_follow_up = $this->input->get('id_follow_up');
      $historyFollowUp = $this->db->query("SELECT a.id_follow_up, a.tgl_fol_up, DATE_FORMAT(a.tgl_booking_service,'%d-%m-%Y') as tgl_booking_service,DATE_FORMAT(a.tgl_actual_service,'%d-%m-%Y') as tgl_actual_service, a.biaya_actual_service, a.tgl_next_fol_up, a.id_kategori_status_komunikasi, a.id_media_kontak_fol_up,(case when a.id_media_kontak_fol_up='1' THEN 'Telepon'
      when a.id_media_kontak_fol_up='2' THEN 'Telepon/WA Call'
      when a.id_media_kontak_fol_up='3' THEN 'WA'
      when a.id_media_kontak_fol_up='4' THEN 'SMS'
      when a.id_media_kontak_fol_up='5' THEN 'Visit'
      when a.id_media_kontak_fol_up='6' THEN 'Facebook'
      when a.id_media_kontak_fol_up='7' THEN 'Instagram'
      when a.id_media_kontak_fol_up='8' THEN 'Telegram'
      when a.id_media_kontak_fol_up='9' THEN 'Twitter'
      when a.id_media_kontak_fol_up='10' THEN 'Email' END) as media_kontak, (case when a.id_kategori_status_komunikasi='1' then 'Unreachable' when a.id_kategori_status_komunikasi='2' then 'Failed'
      when a.id_kategori_status_komunikasi='3' then 'Rejected'
      when a.id_kategori_status_komunikasi='4' then 'Contacted' END) as status_komunikasi,
      (case when a.hasil_fol_up='closed' then 'Selesai' 
      when a.hasil_fol_up in ('cancel','canceled') then 'Batal Service'
      when a.hasil_fol_up in ('open','pending','pause') then 'Sedang dikerjakan'
      else 'Konsumen Tidak Datang' END) as status_fol_up
      FROM tr_h2_fol_up_detail a
      WHERE a.id_follow_up='$id_follow_up'");
      return $historyFollowUp;
    }

    public function input_template($saveData)
    {
      $this->db->insert('template_pesan_fu_h23',$saveData);
    }

    public function update_template($where,$saveData)
    {
      $this->db->where($where);
      $this->db->update('template_pesan_fu_h23',$saveData);
    }

    public function saveFU($where,$saveData)
    {
      $this->db->where($where);
      $this->db->update('tr_h2_fol_up_detail',$saveData);
    }

    public function saveFUCustomer($where2,$save_id_fol_up)
    {
      $this->db->where($where2);
      $this->db->update('ms_customer_h23',$save_id_fol_up);
    }

    public function reportFollowUp($id_dealer, $start_date,$end_date)
    {
      $report = $this->db->query("SELECT d.id_follow_up, d.id_customer, c.no_mesin, (CASE WHEN g.tipe_coming like '%milik%' then c.nama_customer else h.nama END) as nama_customer, i.tipe_ahm, c.tahun_produksi,   
      j.total_jasa, k.pekerjaan, max(d.id_kategori_status_komunikasi) as id_kategori_status_komunikasi, max(d.tgl_fol_up) as tgl_fol_up, max(d.id_media_kontak_fol_up) as id_media_kontak_fol_up, max(d.tgl_next_fol_up) as tgl_next_fol_up, d.tgl_booking_service,g.tgl_servis, count(g.id_sa_form) as frekuensi_servis, period_diff(date_format(now(), '%Y%m'), date_format(tgl_servis, '%Y%m')) as months,  max(d.tgl_actual_service) as tgl_actual_service,max(d.biaya_actual_service) as biaya_actual_service
      -- from tr_log_generate_customer_list_fol_up a
      -- join tr_h2_fol_up_header b on a.id_generate = b.id_generate
      from tr_h2_fol_up_detail d
      join ms_customer_h23 c on c.id_customer = d.id_customer
      -- join tr_h2_fol_up_detail d on b.id_follow_up = d.id_follow_up
      join tr_sales_order n on n.no_mesin=c.no_mesin 
      join tr_spk o on o.no_spk=n.no_spk 
      join tr_h2_sa_form g on g.id_customer=d.id_customer 
      join tr_h2_wo_dealer j on j.id_sa_form=g.id_sa_form 
      left join ms_h2_pembawa h on g.id_pembawa =h.id_pembawa
      join  ms_tipe_kendaraan i on i.id_tipe_kendaraan=c.id_tipe_kendaraan 
      left join ms_pekerjaan k on k.id_pekerjaan=o.pekerjaan
      -- left join ms_karyawan_dealer m on m.id_karyawan_dealer=d.id_karyawan_dealer
      where d.id_dealer='$id_dealer' and left(d.tgl_fol_up,10) >='$start_date' and left(d.tgl_fol_up,10) <='$end_date'
      group by d.id_follow_up");
      return $report;
    }


    public function card_overview_customer_db()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $customer_db = $this->db->query("select count(id_follow_up) as id_follow_up from tr_h2_fol_up_detail thfud where id_dealer='$id_dealer'");
      return $customer_db;
    }

    public function card_overview_belum_fu()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $belum_fu = $this->db->query("select count(id_follow_up) as id_follow_up from tr_h2_fol_up_detail thfud where id_dealer='$id_dealer' and tgl_fol_up IS NULL ");
      return $belum_fu;
    }

    public function card_overview_customer_reminder()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $customer_reminder = $this->db->query("select SUM(CASE WHEN id_kategori_status_komunikasi then 1 else 0 end) as status_komunikasi from tr_h2_fol_up_detail thfud where id_dealer='$id_dealer'");
      return $customer_reminder;
    }

    public function card_overview_contacted()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $contacted = $this->db->query("select SUM(CASE WHEN id_kategori_status_komunikasi=4 then 1 else 0 end) as contacted from tr_h2_fol_up_detail thfud where id_dealer='$id_dealer'");
      return $contacted;
    }

    public function card_overview_booking_service()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $booking_service = $this->db->query("select count(a.tgl_booking_service) as booking_service from tr_h2_fol_up_detail a where a.id_dealer='$id_dealer' and id_kategori_status_komunikasi='4'");
      return $booking_service;
    }

    public function card_overview_actual_service()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $actual_service = $this->db->query("select count(a.hasil_fol_up) as actual_service from tr_h2_fol_up_detail a where is_done='1' and a.hasil_fol_up='closed' and a.id_dealer='$id_dealer'");
      return $actual_service;
    }

    public function channel_effectiveness()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $query = $this->db->query("select (case when a.id_media_kontak_fol_up='1' THEN 'Telepon'
      when a.id_media_kontak_fol_up='2' THEN 'Telepon/WA Call'
      when a.id_media_kontak_fol_up='3' THEN 'WA'
      when a.id_media_kontak_fol_up='4' THEN 'SMS'
      when a.id_media_kontak_fol_up='5' THEN 'Visit'
      when a.id_media_kontak_fol_up='6' THEN 'Facebook'
      when a.id_media_kontak_fol_up='7' THEN 'Instagram'
      when a.id_media_kontak_fol_up='8' THEN 'Telegram'
      when a.id_media_kontak_fol_up='9' THEN 'Twitter'
      when a.id_media_kontak_fol_up='10' THEN 'Email' END) as id_media_kontak_fol_up, count(a.id_media_kontak_fol_up) as hitung_media from tr_h2_fol_up_detail a where a.id_media_kontak_fol_up != 'NULL' and a.id_dealer='$id_dealer' group by a.id_media_kontak_fol_up "); 
      return $query->result();
    }

    public function channelGroupById1()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $query = $this->db->query("select id_media_kontak_fol_up,count(id_media_kontak_fol_up) as customer_reminder, 
      sum(case when id_kategori_status_komunikasi='4' then 1 else 0 end) as contacted,
      sum(case when id_kategori_status_komunikasi='4' and tgl_booking_service != 'NULL' then 1 else 0 end) as booking_service,
      sum(case when id_kategori_status_komunikasi='4' and tgl_booking_service != 'NULL' and tgl_actual_service !='NULL'  then 1 else 0 end) as actual_service from tr_h2_fol_up_detail thfud 
      where id_dealer='$id_dealer' and id_media_kontak_fol_up != 'NULL' and id_media_kontak_fol_up='1'
      group by id_media_kontak_fol_up ");
      return $query;
    }

    public function channelGroupById2()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $query = $this->db->query("select id_media_kontak_fol_up,count(id_media_kontak_fol_up) as customer_reminder, 
      sum(case when id_kategori_status_komunikasi='4' then 1 else 0 end) as contacted,
      sum(case when id_kategori_status_komunikasi='4' and tgl_booking_service != 'NULL' then 1 else 0 end) as booking_service,
      sum(case when id_kategori_status_komunikasi='4' and tgl_booking_service != 'NULL' and tgl_actual_service !='NULL'  then 1 else 0 end) as actual_service from tr_h2_fol_up_detail thfud 
      where id_dealer='$id_dealer' and id_media_kontak_fol_up != 'NULL' and id_media_kontak_fol_up='2'
      group by id_media_kontak_fol_up ");
      return $query;
    }

    public function channelGroupById3()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $query = $this->db->query("select id_media_kontak_fol_up,count(id_media_kontak_fol_up) as customer_reminder, 
      sum(case when id_kategori_status_komunikasi='4' then 1 else 0 end) as contacted,
      sum(case when id_kategori_status_komunikasi='4' and tgl_booking_service != 'NULL' then 1 else 0 end) as booking_service,
      sum(case when id_kategori_status_komunikasi='4' and tgl_booking_service != 'NULL' and tgl_actual_service !='NULL'  then 1 else 0 end) as actual_service from tr_h2_fol_up_detail thfud 
      where id_dealer='$id_dealer' and id_media_kontak_fol_up != 'NULL' and id_media_kontak_fol_up='3'
      group by id_media_kontak_fol_up ");
      return $query;
    }

    public function channelGroupById4()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $query = $this->db->query("select id_media_kontak_fol_up,count(id_media_kontak_fol_up) as customer_reminder, 
      sum(case when id_kategori_status_komunikasi='4' then 1 else 0 end) as contacted,
      sum(case when id_kategori_status_komunikasi='4' and tgl_booking_service != 'NULL' then 1 else 0 end) as booking_service,
      sum(case when id_kategori_status_komunikasi='4' and tgl_booking_service != 'NULL' and tgl_actual_service !='NULL'  then 1 else 0 end) as actual_service from tr_h2_fol_up_detail thfud 
      where id_dealer='$id_dealer' and id_media_kontak_fol_up != 'NULL' and id_media_kontak_fol_up='4'
      group by id_media_kontak_fol_up ");
      return $query;
    }

    public function channelGroupById5()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $query = $this->db->query("select id_media_kontak_fol_up,count(id_media_kontak_fol_up) as customer_reminder, 
      sum(case when id_kategori_status_komunikasi='4' then 1 else 0 end) as contacted,
      sum(case when id_kategori_status_komunikasi='4' and tgl_booking_service != 'NULL' then 1 else 0 end) as booking_service,
      sum(case when id_kategori_status_komunikasi='4' and tgl_booking_service != 'NULL' and tgl_actual_service !='NULL'  then 1 else 0 end) as actual_service from tr_h2_fol_up_detail thfud 
      where id_dealer='$id_dealer' and id_media_kontak_fol_up != 'NULL' and id_media_kontak_fol_up='5'
      group by id_media_kontak_fol_up ");
      return $query;
    }

    public function channelGroupById6()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $query = $this->db->query("select id_media_kontak_fol_up,count(id_media_kontak_fol_up) as customer_reminder, 
      sum(case when id_kategori_status_komunikasi='4' then 1 else 0 end) as contacted,
      sum(case when id_kategori_status_komunikasi='4' and tgl_booking_service != 'NULL' then 1 else 0 end) as booking_service,
      sum(case when id_kategori_status_komunikasi='4' and tgl_booking_service != 'NULL' and tgl_actual_service !='NULL'  then 1 else 0 end) as actual_service from tr_h2_fol_up_detail thfud 
      where id_dealer='$id_dealer' and id_media_kontak_fol_up != 'NULL' and id_media_kontak_fol_up='6'
      group by id_media_kontak_fol_up ");
      return $query;
    }

    public function channelGroupById7()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $query = $this->db->query("select id_media_kontak_fol_up,count(id_media_kontak_fol_up) as customer_reminder, 
      sum(case when id_kategori_status_komunikasi='4' then 1 else 0 end) as contacted,
      sum(case when id_kategori_status_komunikasi='4' and tgl_booking_service != 'NULL' then 1 else 0 end) as booking_service,
      sum(case when id_kategori_status_komunikasi='4' and tgl_booking_service != 'NULL' and tgl_actual_service !='NULL'  then 1 else 0 end) as actual_service from tr_h2_fol_up_detail thfud 
      where id_dealer='$id_dealer' and id_media_kontak_fol_up != 'NULL' and id_media_kontak_fol_up='7'
      group by id_media_kontak_fol_up ");
      return $query;
    }

    public function channelGroupById8()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $query = $this->db->query("select id_media_kontak_fol_up,count(id_media_kontak_fol_up) as customer_reminder, 
      sum(case when id_kategori_status_komunikasi='4' then 1 else 0 end) as contacted,
      sum(case when id_kategori_status_komunikasi='4' and tgl_booking_service != 'NULL' then 1 else 0 end) as booking_service,
      sum(case when id_kategori_status_komunikasi='4' and tgl_booking_service != 'NULL' and tgl_actual_service !='NULL'  then 1 else 0 end) as actual_service from tr_h2_fol_up_detail thfud 
      where id_dealer='$id_dealer' and id_media_kontak_fol_up != 'NULL' and id_media_kontak_fol_up='8'
      group by id_media_kontak_fol_up ");
      return $query;
    }

    public function channelGroupById9()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $query = $this->db->query("select id_media_kontak_fol_up,count(id_media_kontak_fol_up) as customer_reminder, 
      sum(case when id_kategori_status_komunikasi='4' then 1 else 0 end) as contacted,
      sum(case when id_kategori_status_komunikasi='4' and tgl_booking_service != 'NULL' then 1 else 0 end) as booking_service,
      sum(case when id_kategori_status_komunikasi='4' and tgl_booking_service != 'NULL' and tgl_actual_service !='NULL'  then 1 else 0 end) as actual_service from tr_h2_fol_up_detail thfud 
      where id_dealer='$id_dealer' and id_media_kontak_fol_up != 'NULL' and id_media_kontak_fol_up='9'
      group by id_media_kontak_fol_up ");
      return $query;
    }

    public function channelGroupById10()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $query = $this->db->query("select id_media_kontak_fol_up,count(id_media_kontak_fol_up) as customer_reminder, 
      sum(case when id_kategori_status_komunikasi='4' then 1 else 0 end) as contacted,
      sum(case when id_kategori_status_komunikasi='4' and tgl_booking_service != 'NULL' then 1 else 0 end) as booking_service,
      sum(case when id_kategori_status_komunikasi='4' and tgl_booking_service != 'NULL' and tgl_actual_service !='NULL'  then 1 else 0 end) as actual_service from tr_h2_fol_up_detail thfud 
      where id_dealer='$id_dealer' and id_media_kontak_fol_up != 'NULL' and id_media_kontak_fol_up='10'
      group by id_media_kontak_fol_up ");
      return $query;
    }

    public function custFuneling()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $custFuneling = $this->db->query("select 
      SUM(case when id_media_kontak_fol_up ='4' AND id_kategori_status_komunikasi ='4' then 1 else 0 end) as sms, 
      SUM(case when id_kategori_status_komunikasi ='4' AND (id_media_kontak_fol_up='2' or id_media_kontak_fol_up='3') then 1 else 0 end) as wa,
      SUM(case when id_media_kontak_fol_up='1' AND id_kategori_status_komunikasi ='4'then 1 else 0 end) as telepon,
      SUM(case when id_media_kontak_fol_up='10' AND id_kategori_status_komunikasi ='4' then 1 else 0 end) as email,
      SUM(case when id_media_kontak_fol_up ='4' AND (id_kategori_status_komunikasi ='3' or id_kategori_status_komunikasi='1') then 1 else 0 end) as sms_rejected, 
      SUM(case when (id_kategori_status_komunikasi ='3' or id_kategori_status_komunikasi='1') AND (id_media_kontak_fol_up='2' or id_media_kontak_fol_up='3') then 1 else 0 end) as wa_rejected,
      SUM(case when id_media_kontak_fol_up='1' AND (id_kategori_status_komunikasi ='3' or id_kategori_status_komunikasi='1') then 1 else 0 end) as telepon_rejected,
      SUM(case when id_media_kontak_fol_up='10' AND (id_kategori_status_komunikasi ='3' or id_kategori_status_komunikasi='1') then 1 else 0 end) as email_rejected,
      SUM(case when id_media_kontak_fol_up ='4' AND id_kategori_status_komunikasi ='2' then 1 else 0 end) as sms_failed, 
      SUM(case when id_kategori_status_komunikasi ='2' AND (id_media_kontak_fol_up='2' or id_media_kontak_fol_up='3') then 1 else 0 end) as wa_failed,
      SUM(case when id_media_kontak_fol_up='1' AND id_kategori_status_komunikasi ='2'then 1 else 0 end) as telepon_failed,
      SUM(case when id_media_kontak_fol_up='10' AND id_kategori_status_komunikasi ='2' then 1 else 0 end) as email_failed
      from tr_h2_fol_up_detail thfud 
      where id_dealer='$id_dealer'");
      return $custFuneling;
    }

    public function custFunelingBooking()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $booking_service=$this->db->query("select 
      sum(case when id_media_kontak_fol_up='4' then 1 else 0 end) sms,
      sum(case when id_media_kontak_fol_up='2' or id_media_kontak_fol_up='3' then 1 else 0 end) wa,
      sum(case when id_media_kontak_fol_up='1' then 1 else 0 end) telepon,
      sum(case when id_media_kontak_fol_up='10' then 1 else 0 end) email
      from tr_h2_fol_up_detail thfud 
      where id_kategori_status_komunikasi='4' and tgl_booking_service != 'NULL' and id_dealer='$id_dealer'");
      return $booking_service;
    }

    public function custFunelingActual()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $actual_service=$this->db->query("select 
      sum(case when id_media_kontak_fol_up='4' then 1 else 0 end) sms,
      sum(case when id_media_kontak_fol_up='2' or id_media_kontak_fol_up='3' then 1 else 0 end) wa,
      sum(case when id_media_kontak_fol_up='1' then 1 else 0 end) telepon,
      sum(case when id_media_kontak_fol_up='10' then 1 else 0 end) email
      from tr_h2_fol_up_detail thfud 
      where id_kategori_status_komunikasi='4' and id_dealer='$id_dealer' and tgl_actual_service != 'NULL'");
      return $actual_service;
    }

    public function grafikLeaderboard()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $grafikLeaderboard = $this->db->query("select CONCAT('AHASS',' ',b.kode_dealer_md) as kode_dealer_md,
      sum(case when a.id_kategori_status_komunikasi = '4' and a.tgl_actual_service != 'NULL' then 1 else 0 end) as terkontak_actual_service,
      sum(case when a.id_kategori_status_komunikasi = '4' and a.tgl_actual_service is NULL then 1 else 0 end) as terkontak_tidak_service
      from tr_h2_fol_up_detail a
      join ms_dealer b on a.id_dealer=b.id_dealer 
      where a.id_dealer ='$id_dealer'
      group by a.id_dealer");
      return $grafikLeaderboard;
    }

    public function grafikToJWa()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $grafikToJWa = $this->db->query("select e.id_type, count(d.id_jasa) as hitung_toj_wa
      from tr_h2_fol_up_detail a
      join tr_h2_sa_form b on a.id_customer=b.id_customer
      join tr_h2_wo_dealer c on c.id_sa_form=b.id_sa_form
      join tr_h2_wo_dealer_pekerjaan d on c.id_work_order=d.id_work_order 
      join ms_h2_jasa e on e.id_jasa=d.id_jasa 
      where (a.id_media_kontak_fol_up='2' or a.id_media_kontak_fol_up='3') and a.tgl_actual_service=left(c.closed_at,10) and c.status='closed' and a.id_dealer = '$id_dealer'
      group by a.id_follow_up");
      return $grafikToJWa;
    }

    public function grafikToJSMS()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $grafikToJSMS = $this->db->query("select e.id_type, count(d.id_jasa) as hitung_toj_sms
      from tr_h2_fol_up_detail a
      join tr_h2_sa_form b on a.id_customer=b.id_customer
      join tr_h2_wo_dealer c on c.id_sa_form=b.id_sa_form
      join tr_h2_wo_dealer_pekerjaan d on c.id_work_order=d.id_work_order 
      join ms_h2_jasa e on e.id_jasa=d.id_jasa 
      where a.id_media_kontak_fol_up='4' and a.tgl_actual_service=left(c.closed_at,10) and c.status='closed' and a.id_dealer = '$id_dealer'
      group by a.id_follow_up");
      return $grafikToJSMS;
    }

    public function grafikToJTelepon()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $grafikToJTelepon = $this->db->query("select e.id_type, count(d.id_jasa) as hitung_toj_telepon
      from tr_h2_fol_up_detail a
      join tr_h2_sa_form b on a.id_customer=b.id_customer
      join tr_h2_wo_dealer c on c.id_sa_form=b.id_sa_form
      join tr_h2_wo_dealer_pekerjaan d on c.id_work_order=d.id_work_order 
      join ms_h2_jasa e on e.id_jasa=d.id_jasa 
      where a.id_media_kontak_fol_up='1' and a.tgl_actual_service=left(c.closed_at,10) and c.status='closed' and a.id_dealer = '$id_dealer'
      group by a.id_follow_up");
      return $grafikToJTelepon;
    }

    public function grafikToJEmail()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $grafikToJEmail = $this->db->query("select e.id_type, count(d.id_jasa) as hitung_toj_email
      from tr_h2_fol_up_detail a
      join tr_h2_sa_form b on a.id_customer=b.id_customer
      join tr_h2_wo_dealer c on c.id_sa_form=b.id_sa_form
      join tr_h2_wo_dealer_pekerjaan d on c.id_work_order=d.id_work_order 
      join ms_h2_jasa e on e.id_jasa=d.id_jasa 
      where a.id_media_kontak_fol_up='10' and a.tgl_actual_service=left(c.closed_at,10) and c.status='closed' and a.id_dealer = '$id_dealer'
      group by a.id_follow_up");
      return $grafikToJEmail;
    }

    public function grafikActivePassive($tgl1,$tgl2)
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $filter_profesi='';
      $filter_kendaraan='';
      $profesi=$this->input->get('profesi');
      $id_tipe_kendaraan=$this->input->get('id_tipe_kendaraan');
      $splittedNumbers = explode(",", $id_tipe_kendaraan);
      $numbers = "'" . implode("', '", $splittedNumbers) ."'";
      // var_dump($numbers);
      // die();
      if ($this->input->get('profesi') != NULL) {
        $filter_profesi= " AND a.id_pekerjaan = '$profesi'";
      }
      if ($this->input->get('id_tipe_kendaraan') != NULL) {
        $filter_kendaraan= " AND a.id_tipe_kendaraan IN ($numbers)";
      }
      $grafikActivePassive = $this->db->query("select sum(case when t1.tgl_servis2<= DATE_SUB(NOW(),INTERVAL 6 MONTH) then 1 else 0 end) as passive,
      sum(case when t1.tgl_servis2>= DATE_SUB(NOW(),INTERVAL 6 MONTH) then 1 else 0 end) as active, a.tahun_produksi 
     from ms_customer_h23 a 
     left join tr_h2_sa_form b on a.id_customer=b.id_customer 
     left join (select max(y.tgl_servis) as tgl_servis2 ,y.id_customer from ms_customer_h23 x 
               left join tr_h2_sa_form y on x.id_customer=y.id_customer group by x.id_customer) as t1 on t1.id_customer=a.id_customer
     join tr_h2_wo_dealer c on c.id_sa_form=b.id_sa_form 
     where a.tahun_produksi <= NOW() and a.tahun_produksi >= now() - INTERVAL 6 YEAR and c.id_dealer='$id_dealer' and c.status='closed' and b.tgl_servis >= '$tgl1'  and b.tgl_servis <= '$tgl2' $filter_profesi $filter_kendaraan
     group by a.tahun_produksi");
      return $grafikActivePassive;
    }

    public function grafikActivePassiveAfter($tgl3,$tgl4)
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $filter_profesi='';
      $filter_kendaraan='';
      $profesi=$this->input->get('profesi');
      $id_tipe_kendaraan=$this->input->get('id_tipe_kendaraan');
      $splittedNumbers = explode(",", $id_tipe_kendaraan);
      $numbers = "'" . implode("', '", $splittedNumbers) ."'";
      // var_dump($numbers);
      // die();
      if ($this->input->get('profesi') != NULL) {
        $filter_profesi= " AND a.id_pekerjaan = '$profesi'";
      }
      if ($this->input->get('id_tipe_kendaraan') != NULL) {
        $filter_kendaraan= " AND a.id_tipe_kendaraan IN ($numbers)";
      }
      $grafikActivePassiveAfter = $this->db->query("select sum(case when t1.tgl_servis2<= DATE_SUB(NOW(),INTERVAL 6 MONTH) then 1 else 0 end) as passive,
      sum(case when t1.tgl_servis2>= DATE_SUB(NOW(),INTERVAL 6 MONTH) then 1 else 0 end) as active, a.tahun_produksi 
     from ms_customer_h23 a 
     left join tr_h2_sa_form b on a.id_customer=b.id_customer 
     left join (select max(y.tgl_servis) as tgl_servis2 ,y.id_customer from ms_customer_h23 x 
               left join tr_h2_sa_form y on x.id_customer=y.id_customer group by x.id_customer) as t1 on t1.id_customer=a.id_customer
     join tr_h2_wo_dealer c on c.id_sa_form=b.id_sa_form 
     where a.tahun_produksi <= NOW() and a.tahun_produksi >= now() - INTERVAL 6 YEAR and c.id_dealer='$id_dealer' and c.status='closed' and b.tgl_servis >= '$tgl3'  and b.tgl_servis <= '$tgl4' $filter_profesi $filter_kendaraan
     group by a.tahun_produksi");
      return $grafikActivePassiveAfter;
    }

    public function frequencyOfVisit($tgl1,$tgl2)
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $filter_profesi='';
      $filter_kendaraan='';
      $profesi=$this->input->get('profesi');
      $id_tipe_kendaraan=$this->input->get('id_tipe_kendaraan');
      $splittedNumbers = explode(",", $id_tipe_kendaraan);
      $numbers = "'" . implode("', '", $splittedNumbers) ."'";
      // var_dump($numbers);
      // die();
      if ($this->input->get('profesi') != NULL) {
        $filter_profesi= " AND a.id_pekerjaan = '$profesi'";
      }
      if ($this->input->get('id_tipe_kendaraan') != NULL) {
        $filter_kendaraan= " AND a.id_tipe_kendaraan IN ($numbers)";
      }
      $frequencyOfVisit = $this->db->query("select sum(case when t1.tgl_servis2>= DATE_SUB(NOW(),INTERVAL 6 MONTH) then 1 else 0 end) as active, ROUND((sum(case when t1.tgl_servis2>= DATE_SUB(NOW(),INTERVAL 6 MONTH) then 1 else 0 end)/(count(DISTINCT(b.id_customer)))),2) as total, a.tahun_produksi 
      from ms_customer_h23 a 
      left join tr_h2_sa_form b on a.id_customer=b.id_customer
      left join (select max(y.tgl_servis) as tgl_servis2 ,y.id_customer from ms_customer_h23 x 
      					left join tr_h2_sa_form y on x.id_customer=y.id_customer group by x.id_customer) as t1 on t1.id_customer=a.id_customer
      join tr_h2_wo_dealer c on c.id_sa_form=b.id_sa_form 
      where a.tahun_produksi <= NOW() and a.tahun_produksi >= now() - INTERVAL 6 YEAR and c.id_dealer='$id_dealer' and c.status='Closed' 
      and  t1.tgl_servis2>= DATE_SUB(NOW(),INTERVAL 6 MONTH) and t1.tgl_servis2 >= '$tgl1' and t1.tgl_servis2 <= '$tgl2' $filter_profesi $filter_kendaraan
      group by a.tahun_produksi ");
      return $frequencyOfVisit;
    }

    public function frequencyOfVisitAfter($tgl3,$tgl4)
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $filter_profesi='';
      $filter_kendaraan='';
      $profesi=$this->input->get('profesi');
      $id_tipe_kendaraan=$this->input->get('id_tipe_kendaraan');
      $splittedNumbers = explode(",", $id_tipe_kendaraan);
      $numbers = "'" . implode("', '", $splittedNumbers) ."'";
      // var_dump($numbers);
      // die();
      if ($this->input->get('profesi') != NULL) {
        $filter_profesi= " AND a.id_pekerjaan = '$profesi'";
      }
      if ($this->input->get('id_tipe_kendaraan') != NULL) {
        $filter_kendaraan= " AND a.id_tipe_kendaraan IN ($numbers)";
      }
      $frequencyOfVisitAfter = $this->db->query("select sum(case when t1.tgl_servis2>= DATE_SUB(NOW(),INTERVAL 6 MONTH) then 1 else 0 end) as active, ROUND((sum(case when t1.tgl_servis2>= DATE_SUB(NOW(),INTERVAL 6 MONTH) then 1 else 0 end)/(count(DISTINCT(b.id_customer)))),2) as total, a.tahun_produksi 
      from ms_customer_h23 a 
      left join tr_h2_sa_form b on a.id_customer=b.id_customer
      left join (select max(y.tgl_servis) as tgl_servis2 ,y.id_customer from ms_customer_h23 x 
      					left join tr_h2_sa_form y on x.id_customer=y.id_customer group by x.id_customer) as t1 on t1.id_customer=a.id_customer
      join tr_h2_wo_dealer c on c.id_sa_form=b.id_sa_form 
      where a.tahun_produksi <= NOW() and a.tahun_produksi >= now() - INTERVAL 6 YEAR and c.id_dealer='$id_dealer' and c.status='Closed' 
      and  t1.tgl_servis2>= DATE_SUB(NOW(),INTERVAL 6 MONTH) and t1.tgl_servis2 >= '$tgl3' and t1.tgl_servis2 <= '$tgl4' $filter_profesi $filter_kendaraan
      group by a.tahun_produksi ");
      return $frequencyOfVisitAfter;
    }

    public function salesAbility($tgl1,$tgl2)
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $filter_profesi='';
      $filter_kendaraan='';
      $profesi=$this->input->get('profesi');
      $id_tipe_kendaraan=$this->input->get('id_tipe_kendaraan');
      $splittedNumbers = explode(",", $id_tipe_kendaraan);
      $numbers = "'" . implode("', '", $splittedNumbers) ."'";
      // var_dump($numbers);
      // die();
      if ($this->input->get('profesi') != NULL) {
        $filter_profesi= " AND a.id_pekerjaan = '$profesi'";
      }
      if ($this->input->get('id_tipe_kendaraan') != NULL) {
        $filter_kendaraan= " AND a.id_tipe_kendaraan IN ($numbers)";
      }
      $salesAbility = $this->db->query("select sum(case when t1.tgl_servis2>= DATE_SUB(NOW(),INTERVAL 6 MONTH) then 1 else 0 end) as active, a.tahun_produksi
      ,sum(c.total_jasa),ROUND(SUM(c.grand_total)/count(DISTINCT(b.id_customer))) as total
      from ms_customer_h23 a 
      left join tr_h2_sa_form b on a.id_customer=b.id_customer
      left join (select max(y.tgl_servis) as tgl_servis2 ,y.id_customer from ms_customer_h23 x 
      					left join tr_h2_sa_form y on x.id_customer=y.id_customer group by x.id_customer) as t1 on t1.id_customer=a.id_customer
      join tr_h2_wo_dealer c on c.id_sa_form=b.id_sa_form 
      where a.tahun_produksi <= NOW() and a.tahun_produksi >= now() - INTERVAL 6 YEAR and c.id_dealer='$id_dealer' and c.status='Closed' 
      and  t1.tgl_servis2>= DATE_SUB(NOW(),INTERVAL 6 MONTH) and t1.tgl_servis2 >= '$tgl1' and t1.tgl_servis2 <= '$tgl2' $filter_profesi $filter_kendaraan
      group by a.tahun_produksi");
      return $salesAbility;
    }

    public function salesAbilityAfter($tgl3,$tgl4)
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $filter_profesi='';
      $filter_kendaraan='';
      $profesi=$this->input->get('profesi');
      $id_tipe_kendaraan=$this->input->get('id_tipe_kendaraan');
      $splittedNumbers = explode(",", $id_tipe_kendaraan);
      $numbers = "'" . implode("', '", $splittedNumbers) ."'";
      // var_dump($numbers);
      // die();
      if ($this->input->get('profesi') != NULL) {
        $filter_profesi= " AND a.id_pekerjaan = '$profesi'";
      }
      if ($this->input->get('id_tipe_kendaraan') != NULL) {
        $filter_kendaraan= " AND a.id_tipe_kendaraan IN ($numbers)";
      }
      $salesAbilityAfter = $this->db->query("select sum(case when t1.tgl_servis2>= DATE_SUB(NOW(),INTERVAL 6 MONTH) then 1 else 0 end) as active, a.tahun_produksi
      ,sum(c.total_jasa),ROUND(SUM(c.grand_total)/count(DISTINCT(b.id_customer))) as total
      from ms_customer_h23 a 
      left join tr_h2_sa_form b on a.id_customer=b.id_customer
      left join (select max(y.tgl_servis) as tgl_servis2 ,y.id_customer from ms_customer_h23 x 
      					left join tr_h2_sa_form y on x.id_customer=y.id_customer group by x.id_customer) as t1 on t1.id_customer=a.id_customer
      join tr_h2_wo_dealer c on c.id_sa_form=b.id_sa_form 
      where a.tahun_produksi <= NOW() and a.tahun_produksi >= now() - INTERVAL 6 YEAR and c.id_dealer='$id_dealer' and c.status='Closed' 
      and  t1.tgl_servis2>= DATE_SUB(NOW(),INTERVAL 6 MONTH) and t1.tgl_servis2 >= '$tgl3' and t1.tgl_servis2 <= '$tgl4' $filter_profesi $filter_kendaraan
      group by a.tahun_produksi");
      return $salesAbilityAfter;
    }

    function update_data($where,$data,$table2){
      $this->db->where($where);
      $this->db->update($table2,$data);
      // var_dump($this);
      // die();
    }	

    public function actual_service_data()
    {
      $actual_service = $this->db->query("select a.id_follow_up,a.id_customer,b.tgl_servis, a.tgl_booking_service, c.closed_at,c.total_jasa,a.id_kategori_status_komunikasi,c.status
      from tr_h2_fol_up_detail a
      join tr_h2_sa_form b on a.id_customer=b.id_customer 
      and (
        CASE
            WHEN a.tgl_booking_service IS NULL OR a.tgl_booking_service = '0000-00-00'
            THEN b.tgl_servis = (
                SELECT
                    tgl_servis
                FROM
                    tr_h2_sa_form
                WHERE
                    id_customer = a.id_customer
                    AND tgl_servis >= left(a.created_at,10)
                GROUP BY
                    a.id_follow_up
                ORDER BY
                    id_customer,
                    tgl_servis ASC
                LIMIT 1
            )
            ELSE b.tgl_servis = (
                SELECT
                    tgl_servis
                FROM
                    tr_h2_sa_form
                WHERE
                    id_customer = a.id_customer
                    AND tgl_servis >= a.tgl_booking_service
                GROUP BY
                    a.id_follow_up
                ORDER BY
                    id_customer,
                    tgl_servis ASC
                LIMIT 1
            )
        END
      )
      join tr_h2_wo_dealer c on c.id_sa_form=b.id_sa_form 
      where a.id_kategori_status_komunikasi='4'
      GROUP BY a.id_follow_up");
      return $actual_service;
    }

    public function reject_data(){
      $id_dealer=$this->m_admin->cari_dealer();
      $reject_data = $this->db->query("SELECT id_follow_up, COALESCE(SUM(id_kategori_status_komunikasi in (2,3,4)), 0) AS jumlah_status_komunikasi,id_customer
      FROM (
        SELECT t1.id_follow_up, id_kategori_status_komunikasi,id_dealer, t1.id_customer, fol_head.is_done
        FROM tr_h2_fol_up_detail t1
        JOIN tr_h2_fol_up_header fol_head on fol_head.id_follow_up=t1.id_follow_up
        WHERE (
            SELECT COUNT(id_kategori_status_komunikasi)
            FROM tr_h2_fol_up_detail t2
            JOIN tr_h2_fol_up_header fol_head on fol_head.id_follow_up = t2.id_follow_up 
            WHERE t1.id_follow_up = t2.id_follow_up AND t1.created_at <= t2.created_at
            AND id_dealer=$id_dealer AND fol_head.is_done=0
        ) <= 3 AND fol_head.is_done=0
      ) AS recent_data
      WHERE recent_data.id_dealer=$id_dealer
      GROUP BY id_follow_up
      HAVING jumlah_status_komunikasi >= 3;
      ");
      return $reject_data;
    }

    public function getNotification()
    {
      $id_dealer=$this->m_admin->cari_dealer();
      $notification=$this->db->query("SELECT b.id_header_fol_up 
      FROM tr_log_generate_customer_list_fol_up a
      -- LEFT JOIN tr_h2_fol_up_header b on a.id_generate=b.id_generate 
      JOIN tr_h2_fol_up_header b on a.id_generate=b.id_generate
      -- JOIN  tr_h2_fol_up_detail c on c.id_follow_up=b.id_follow_up 
      JOIN tr_h2_fol_up_detail c on c.id_follow_up=b.id_follow_up 
      WHERE a.assign_dealer='$id_dealer' and ((a.from_md='1' and left(a.created_at,10)=CURDATE()) or c.tgl_fol_up is NULL)
      GROUP BY c.id_follow_up");
      return $notification;
    }
    
    // public function insert_import($temp_data)
    // {
    //   // $this->db->where($where);
    //   $insert = $this->db->insert_batch('tr_h2_fol_up_detail', $temp_data);
    //   if($insert){
    //     return true;
    //   }
    // }

  }
?>