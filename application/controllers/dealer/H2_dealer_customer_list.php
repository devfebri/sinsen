<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H2_dealer_customer_list extends CI_Controller
{

  var $folder =   "dealer";
  var $page   =   "h2_dealer_customer_list";
  var $title  =   "List Follow Up Reminder";
  var $jenis_user    = '';
  var $is_md      = false;
  var $jenis_user_bagian    = '';
  var $is_h1      = false;

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    $this->db_crm       = $this->load->database('db_crm', true);
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('H2_dealer_customer_list_model');
    //===== Load Library =====	
    $this->load->library('form_validation');
    //---- cek session -------//		
    $name = $this->session->userdata('nama');
    $auth = $this->m_admin->user_auth($this->page, "select");
    $sess = $this->m_admin->sess_auth();
    if ($name == "" or $auth == 'false') {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
    } elseif ($sess == 'false') {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
    }
    ini_set('display_errors', 0);

    $this->jenis_user   = $this->db->get_where('ms_user',['jenis_user'=>$_SESSION['group']])->row()->code;
		if ($this->jenis_user=='Main Dealer') {
			$this->is_md = true;
		}
    $this->jenis_user_bagian  = $this->db->get_where('ms_user',['jenis_user_bagian'=>$_SESSION['group']])->row()->code;
		if ($this->jenis_user_bagian=='h1') {
			$this->is_h1 = true;
		}
  }

  protected function template($data)
  {
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    } else {
      $data['id_menu'] = $this->m_admin->getMenu($this->page);
      $data['group']   = $this->session->userdata("group");
      $this->load->view('template/header', $data);
      $this->load->view('template/aside');
      $this->load->view($this->folder . "/" . $this->page);
      $this->load->view('template/footer');
    }
  }

  public function index()
  {
     
      
        $data['isi']       = $this->page;
        $data['title']     = $this->title;
        $data['set']       = "view";
        
        $data['pekerjaan'] = $this->H2_dealer_customer_list_model->getDataPekerjaan();
        $data['listFU']    = $this->H2_dealer_customer_list_model->getDataHasilFU();
        $data['kendaraan'] = $this->H2_dealer_customer_list_model->getDataKendaraan();
        $data['dt_dealer'] = $this->H2_dealer_customer_list_model->getDataDealer();
        $data['jasaType']  = $jasaType =$this->H2_dealer_customer_list_model->getDataJasaType();      
        $this->template($data);
  }

  public function getDataTable()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $status_komunikasi = '';
            if($row['id_kategori_status_komunikasi']=='1'){
                $status_komunikasi = 'Unreachable';
            }elseif($row['id_kategori_status_komunikasi']=='2'){
                $status_komunikasi = 'Failed';
            }elseif($row['id_kategori_status_komunikasi']=='3'){
                $status_komunikasi = 'Rejected';
            }elseif($row['id_kategori_status_komunikasi']=='4'){
                $status_komunikasi = 'Contacted';
            }

            $deskripsi_toj = $this->db->query("SELECT deskripsi FROM ms_h2_jasa_type WHERE id_type= '$row[id_type]'")->row_array();
            $km_terakhir = $this->db->query("SELECT max(km_terakhir) as km_terakhir FROM tr_h2_sa_form WHERE id_customer= '$row[id_customer]' and tgl_servis='$row[tgl_servis]'")->row_array();
            $total_jasa = $this->db->query("SELECT ROUND(SUM(b.grand_total)/COUNT(b.id_work_order),2) as total_jasa FROM tr_h2_sa_form a JOIN tr_h2_wo_dealer b on a.id_sa_form=b.id_sa_form WHERE a.id_customer='$row[id_customer]'")->row_array();

            $row['index'] = $index++;
            $row['no_mesin'] = $row['no_mesin'];
            $row['nama_pembawa'] = strtoupper($row['nama_pembawa']);
            $row['no_hp_pembawa'] = $row['no_hp_pembawa'];
            $row['id_tipe_kendaraan'] = $row['tipe_ahm'];
            $row['tahun_motor'] = $row['tahun_motor'];
            $row['frekuensi_service'] = $row['frekuensi_service'];
            $row['km_terakhir'] = $km_terakhir['km_terakhir'];
            $row['tgl_servis'] = $row['months'];
            $row['total_jasa'] = $total_jasa['total_jasa'];
            $row['deskripsi'] = $deskripsi_toj['deskripsi'];
            if($this->session->userdata('jenis_user_bagian')=='h23'){
                $row['pekerjaan'] = $row['pekerjaan_h23'];
            }else{
                $row['pekerjaan'] = $row['pekerjaan'];
            }
            $row['pending_item'] = '-';
            $row['status_fu'] = $status_komunikasi;
            $row['tgl_fol_up'] = $row['tgl_fol_up'];
            $row['customer_segment'] = '-';
            $row['action'] = '<a id="'.$row['id_customer'].'" href="dealer/h2_dealer_customer_list/edit_data?id_customer='.$row['id_customer'].'" type="button" class="btn btn-primary btn-xs" target="_blank">Edit</a>';

            $data[] = $row;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data
        ]);
  }

  public function make_query()
    {
        $this->db
        ->select('a.no_rangka')
        ->select('a.no_mesin')
        ->select('a.id_tipe_kendaraan')
        ->select('d.tipe_ahm')
        ->select('a.tgl_pembelian as tgl_pembelian')
        ->select('a.tahun_produksi as tahun_motor')
        ->select('e.pekerjaan')
        ->select('(CASE WHEN f.tipe_coming like "%milik%" then a.nama_customer else g.nama END) as nama_pembawa')
        ->select('(CASE WHEN a.id_pekerjaan != NULL THEN e.pekerjaan else e.pekerjaan END)AS pekerjaan_h23')
        ->select('(CASE WHEN g.no_hp != NULL THEN g.no_hp else a.no_hp END)AS no_hp_pembawa')
        ->select('max(f.tgl_servis) as tgl_servis')
        ->select('a.id_customer')
        ->select('max(f.id_type) as id_type')
        ->select('period_diff(date_format(now(), "%Y%m"), date_format(max(f.tgl_servis), "%Y%m")) as months')
        ->select('max(j.tgl_fol_up) as tgl_fol_up')
        ->select('max(j.id_kategori_status_komunikasi) as id_kategori_status_komunikasi')
        ->select('(SELECT count(id_sa_form) FROM tr_h2_sa_form WHERE id_customer = a.id_customer) as frekuensi_service', false)
        ->from('ms_customer_h23 as a')
        ->join('ms_tipe_kendaraan as d', 'd.id_tipe_kendaraan=a.id_tipe_kendaraan')
        ->join('ms_pekerjaan as e', 'a.id_pekerjaan=e.id_pekerjaan', 'left')
        ->join('tr_h2_sa_form as f', 'a.id_customer=f.id_customer')
        ->join('ms_h2_pembawa as g', 'f.id_pembawa = g.id_pembawa', 'left')
        ->join('ms_h2_jasa_type as h', 'h.id_type=f.id_type')
        ->join('tr_h2_wo_dealer as i', 'i.id_sa_form = f.id_sa_form')
        ->join('tr_h2_fol_up_detail as j', 'j.id_customer=a.id_customer', 'left')
        ->where('f.id_dealer = ',$this->m_admin->cari_dealer())
        ->where('a.id_log_follow_up IS NULL')
        ->group_by('a.id_customer')
        ;
  }

  public function make_datatables()
  {
        $this->make_query();

        if ($this->input->post('no_mesin') != null) {
            $this->db->like('a.no_mesin', $this->input->post('no_mesin'));
        }
        if ($this->input->post('active_passive') == 'active') {
            $this->db->where('f.tgl_servis >= DATE_SUB(NOW(),INTERVAL 6 MONTH)');
        }elseif($this->input->post('active_passive') == 'passive'){
            $this->db->where('f.tgl_servis <= DATE_SUB(NOW(),INTERVAL 6 MONTH)');
        }

        
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

        if ($this->input->post('tahun_motor') != NULL) {
            $this->db->where('a.tahun_produksi =', $this->input->post('tahun_motor'));
        }

        if($this->input->post('filter_mc_type') != NULL and count($this->input->post('filter_mc_type')) > 0){
            $this->db->where_in('a.id_tipe_kendaraan', $this->input->post('filter_mc_type'));
        }
        if ($this->input->post('profesi') != NULL) {
            $this->db->where('e.id_pekerjaan =',$this->input->post('profesi'));
        }
        // if ($this->input->post('last_toj') != NULL and count($this->input->post('last_toj')) > 0) {
        //     $this->db->where_in('f.id_type',$this->input->post('last_toj'));
        // }
        if ($this->input->post('filter_toj') != NULL and count($this->input->post('filter_toj')) > 0) {
            $this->db->where_in('f.id_type',$this->input->post('filter_toj'));
        }

        if ($this->input->post('frekuensi_service') == 'kurang_dr_5') {
            // $this->db->group_by('a.id_customer');
            $this->db->having('frekuensi_service <=', '5');
        }elseif($this->input->post('frekuensi_service') == 'rentang_6_10'){
            // $this->db->group_by('a.id_customer');
            $this->db->having('frekuensi_service BETWEEN 6 AND 10');
        }elseif($this->input->post('frekuensi_service') == 'rentang_11_20'){
            // $this->db->group_by('a.id_customer');
            $this->db->having('frekuensi_service BETWEEN 11 AND 20');
        }elseif($this->input->post('frekuensi_service') == 'lebih_dr_21'){
            // $this->db->group_by('a.id_customer');
            $this->db->having('frekuensi_service >=', '21');
        }
        
      
        // if ($this->input->post('waktu_service_terakhir') == '1') {
        //     $this->db->where('period_diff(date_format(now(), "%Y%m"), date_format(f.tgl_servis, "%Y%m"))<=',$this->input->post('waktu_service_terakhir'));
        // }elseif($this->input->post('waktu_service_terakhir') == '2'){
        //     $this->db->where('period_diff(date_format(now(), "%Y%m"), date_format(f.tgl_servis, "%Y%m"))=',$this->input->post('waktu_service_terakhir'));
        // }elseif($this->input->post('waktu_service_terakhir') == '3'){
        //     $this->db->where('period_diff(date_format(now(), "%Y%m"), date_format(f.tgl_servis, "%Y%m"))=',$this->input->post('waktu_service_terakhir'));
        // }elseif($this->input->post('waktu_service_terakhir') == '5'){
        //     $this->db->where('period_diff(date_format(now(), "%Y%m"), date_format(f.tgl_servis, "%Y%m"))=',$this->input->post('waktu_service_terakhir'));
        // }elseif($this->input->post('waktu_service_terakhir') == '6'){
        //     $this->db->where('period_diff(date_format(now(), "%Y%m"), date_format(f.tgl_servis, "%Y%m"))=',$this->input->post('waktu_service_terakhir'));
        // }elseif($this->input->post('waktu_service_terakhir') == '7'){
        //     $this->db->where('period_diff(date_format(now(), "%Y%m"), date_format(f.tgl_servis, "%Y%m"))=',$this->input->post('waktu_service_terakhir'));
        // }elseif($this->input->post('waktu_service_terakhir') == '8'){
        //     $this->db->where('period_diff(date_format(now(), "%Y%m"), date_format(f.tgl_servis, "%Y%m"))=',$this->input->post('waktu_service_terakhir'));
        // }elseif($this->input->post('waktu_service_terakhir') == '9'){
        //     $this->db->where('period_diff(date_format(now(), "%Y%m"), date_format(f.tgl_servis, "%Y%m"))=',$this->input->post('waktu_service_terakhir'));
        // }elseif($this->input->post('waktu_service_terakhir') == '10'){
        //     $this->db->where('period_diff(date_format(now(), "%Y%m"), date_format(f.tgl_servis, "%Y%m"))=',$this->input->post('waktu_service_terakhir'));
        // }elseif($this->input->post('waktu_service_terakhir') == '11'){
        //     $this->db->where('period_diff(date_format(now(), "%Y%m"), date_format(f.tgl_servis, "%Y%m"))=',$this->input->post('waktu_service_terakhir'));
        // }elseif($this->input->post('waktu_service_terakhir') == '12'){
        //     $this->db->where('period_diff(date_format(now(), "%Y%m"), date_format(f.tgl_servis, "%Y%m"))=',$this->input->post('waktu_service_terakhir'));
        // }

        if ($this->input->post('waktu_service_terakhir') == '1') {
            $this->db->where('period_diff(date_format(now(), "%Y%m"), date_format(f.tgl_servis, "%Y%m"))<=',$this->input->post('waktu_service_terakhir'));
        }elseif($this->input->post('waktu_service_terakhir') == '12'){
            $this->db->where('period_diff(date_format(now(), "%Y%m"), date_format(f.tgl_servis, "%Y%m"))=',$this->input->post('waktu_service_terakhir'));
        }elseif($this->input->post('waktu_service_terakhir') != NULL){
            $this->db->where('period_diff(date_format(now(), "%Y%m"), date_format(f.tgl_servis, "%Y%m"))=',$this->input->post('waktu_service_terakhir'));
        }



        if ($this->input->post('status_fu') != NULL) {
            $this->db->where('j.id_kategori_status_komunikasi =', $this->input->post('status_fu'));
        }

        if($this->input->post('filter_last_fu') != null){
            $this->db->where('j.tgl_fol_up >=', $this->input->post('start_date'));
            $this->db->where('j.tgl_fol_up <=', $this->input->post('end_date')); 
        }
        

        if ($this->input->post('gender') != NULL) {
            $this->db->where('a.jenis_kelamin =', $this->input->post('gender'));
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('f.tgl_servis', 'desc');
        }
  }

  public function limit()
  {
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
  }

  public function recordsFiltered()
  {
        $this->make_datatables();
        return $this->db->get()->num_rows();
  }

  public function recordsTotal()
  {
        $this->make_query();
        return $this->db->count_all_results();
  }
    
  public function export_excel()
  {
      $data['id_dealer'] = $id_dealer = $this->m_admin->cari_dealer();
      $data['mc_type']=$mc_type= $this->input->post('filter_mc_type');
      $data['start_date']=$this->input->post('last_fu_start');
      $data['end_date']=$this->input->post('last_fu_end');
      if($_POST['process']=='export_excel'){
        $data['downloadExcel']= $this->H2_dealer_customer_list_model->downloadExcel($id_dealer);
        $this->load->view("dealer/laporan/temp_h2_dealer_customer_list_excel",$data);
      }  
  }
    

  public function generate_data()
  {
      $data['set']	= "generate";
		  $this->template($data);	
  }

  public function get_id_follow_up()
	{
		$th       = date('Y');
		$bln      = date('m');
		$th_bln   = date('Y-m-d');
		$th_kecil = date('y');
        $tbt = date('ymd');
        $hari = date('d');
		$id_dealer = $this->m_admin->cari_dealer();
		// $id_sumber='E20';
		// if ($id_dealer!=null) {
			$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
			$id_sumber = $dealer->kode_dealer_md;
		// }
		$get_data  = $this->db->query("SELECT * FROM tr_h2_fol_up_detail
			WHERE created_at='$th_bln' AND id_dealer='$id_dealer' 
			ORDER BY created_at DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$row      = $get_data->row();
				$id_follow_up = substr($row->id_follow_up, -5);
				$new_kode = 'FolUp/'.$id_sumber.'/'.$tbt.'/'.sprintf("%'.05d",$id_follow_up+1);
				$i=0;
				while ($i<1) {
					$cek = $this->db->get_where('tr_h2_fol_up_detail',['id_follow_up'=>$new_kode])->num_rows();
				    if ($cek>0) {
						$neww     = substr($new_kode, -5);
						$new_kode = 'FolUp/'.$id_sumber.'/'.$tbt.'/'.sprintf("%'.05d",$neww+1);
						$i        = 0;
				    }else{
				    	$i++;
				    }
				}
	   		}else{
				$new_kode = 'FolUp/'.$id_sumber.'/'.$tbt.'/'.'00001';
	   		}
   		return strtoupper($new_kode);
	}	

  public function get_id_folup()
  {
    $id_dealer = $this->m_admin->cari_dealer();
    
	$sumber = $this->db->query("SELECT id_dealer,kode_dealer_md FROM ms_dealer md WHERE id_dealer='$id_dealer'")->row();
    $id_sumber = $sumber->kode_dealer_md;
    $query = "SELECT CONCAT('FOLUP/','$id_sumber','/',DATE_FORMAT(CURRENT_DATE(), '%y%m%d'),'/',lpad(COUNT(*)+1,4,'0'))  AS newid FROM tr_h2_fol_up_detail WHERE DATE(created_at) = CURRENT_DATE() AND id_dealer='$id_dealer'";
	$data = $this->db->query($query)->row();
	return $data->newid;
  }

  public function save_generate()
  {
    $data['id_dealer'] = $id_dealer = $this->m_admin->cari_dealer();
    $this->db->trans_begin();
    $data = $this->H2_dealer_customer_list_model->generateData($id_dealer);
    $datatotal = count($data);
   
    $data_insert = [
        'tgl_generate' => date('Y-m-d H:i:s'),
        'assign_dealer' => $this->m_admin->cari_dealer(),
        'jumlah_generate' => $datatotal,
        'active'        => 1,
        'created_at'    => date('Y-m-d H:i:s'),
        'created_by'    => $this->session->userdata('id_user'),
        'from_md'    => 0,
        'is_excel'    => 0,//is_excel untuk apakah upload data dari excel atau tidak 
    ];
    $this->db->insert('tr_log_generate_customer_list_fol_up', $data_insert);
    $id_header = $this->db->insert_id();

    foreach ($data as $key => $value) {
        $data_detail = [
            // 'id_follow_up' => $id_follow_up,
            'id_generate' => $id_header,
            'id_customer' => $value->id_customer,
            'tgl_assigned' => date('Y-m-d H:i:s'),
            'active'        => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'created_by'    => $this->session->userdata('id_user'),
        ];
        $this->db->set('id_follow_up', $this->get_id_folup());
        $this->db->insert('tr_h2_fol_up_header', $data_detail);

        $data_detail_fu = [
            // 'id_follow_up' => $id_follow_up,
            // 'id_follow_up_int' => $id_header2,
            'id_customer' => $value->id_customer,
            'id_dealer' => $this->m_admin->cari_dealer(),
            'created_at'    => date('Y-m-d H:i:s'),
            'created_by'    => $this->session->userdata('id_user'),
        ];
        $this->db->set('id_follow_up', $this->get_id_folup());
        $this->db->insert('tr_h2_fol_up_detail', $data_detail_fu);

        $save_id_fol_up = array('id_log_follow_up' =>$id_header);
        $this->db->where('id_customer',$value->id_customer);
        $this->db->update('ms_customer_h23',$save_id_fol_up);
    }

    if ($this->db->trans_status() == true) {
        $this->db->trans_commit();
        $result = [
            'status' => true,
            'message' => 'success',
            'data' => []
        ];
    } else {
        $this->db->trans_rollback();
        $result = [
            'status' => false,
            'message' => 'failed',
            'data' => []
        ];
    }

    echo json_encode($result);
  }

  public function sync_data()
  {
    $this->db->trans_begin();
    $data = $this->H2_dealer_customer_list_model->actual_service_data();
    foreach($data->result() as $row){
        $customer_h23 = $this->db->query("SELECT id_customer FROM ms_customer_h23 WHERE id_customer='$row->id_customer'");

        $data_cust = array(
            'id_log_follow_up' => NULL);

        $this->db->where('id_customer',$row->id_customer);
        $this->db->update('ms_customer_h23',$data_cust);

        if($row->status=='closed')
        {
            $data_actual = array(
                'tgl_actual_service' => $row->closed_at,
                'biaya_actual_service' => $row->total_jasa,
                'hasil_fol_up'=> $row->status,
                'is_done'=>1);
        }else{
            $data_actual = array(
                'tgl_actual_service' => $row->closed_at,
                'biaya_actual_service' => 0,
                'hasil_fol_up'=> $row->status,
                'is_done'=>1);
        }
        $this->db->where('id_follow_up',$row->id_follow_up);
        $this->db->where('id_kategori_status_komunikasi',$row->id_kategori_status_komunikasi);
        $this->db->update('tr_h2_fol_up_detail',$data_actual);

        $is_done = array(
            'is_done' => 1);
            
        $this->db->where('id_follow_up',$row->id_follow_up);
        $this->db->update('tr_h2_fol_up_header',$is_done);
        
    }

     //Cek untuk customer follow up yang telah 3x berturut2 status Failed, Unreachable, atau Reject
     $data_reject = $this->H2_dealer_customer_list_model->reject_data();
     foreach($data_reject->result as $row){
         $data_cust = array(
            'id_log_follow_up' => NULL);
 
         $this->db->where('id_customer',$row->id_customer);
         $this->db->update('ms_customer_h23',$data_cust);
 
         $is_done = array(
             'is_done' => 1);
             
         $this->db->where('id_follow_up',$row->id_follow_up);
         $this->db->update('tr_h2_fol_up_header',$is_done);
     }
 

    if ($this->db->trans_status() == true) {
        $this->db->trans_commit();
        $result = [
            'status' => true,
            'message' => 'success',
            'data' => []
        ];
    } else {
        $this->db->trans_rollback();
        $result = [
            'status' => true,
            'message' => 'success',
            'data' => []
        ];
    }

    echo json_encode($result);
  }

  public function edit_data($id_customer)
  {
    $data['isi']            = $this->page;
    $data['title']          = $this->title;
    $data['set']	          = "detail";
    $data['id_dealer']      = $id_dealer = $this->m_admin->cari_dealer();
    $data['id_customer']    = $id_customer = $this->input->get('id_customer');
    $data['pic']            = $this->H2_dealer_customer_list_model->getDataKaryawan();
    $data['mediaKomunikasi']= $this->H2_dealer_customer_list_model->getMediaKomunikasi();
    
    $data['getDetailData']  = $this->H2_dealer_customer_list_model->getDetailData($id_customer);
		$this->template($data);	
  }

  public function update_data()
  {
    $id_customer = $this->input->post('id_customer');
	$tujuan_penggunaan_motor = $this->input->post('tujuan_penggunaan_motor');
	$tgl_lahir = $this->input->post('tgl_lahir');
    $no_hp = $this->input->post('no_hp');
    $email = $this->input->post('email');
 
    $this->form_validation->set_rules('no_hp','No.Hp','required|min_length[9]|max_length[13]|numeric');
    $this->form_validation->set_rules('email','Email','required');
    if ($this->form_validation->run()==true){
        $this->db->trans_begin();
        $this->db->set('tujuan_penggunaan_motor', $tujuan_penggunaan_motor);
        $this->db->set('tgl_lahir', $tgl_lahir);
        $this->db->set('no_hp', $no_hp);
        $this->db->set('email', $email);
        $this->db->where('id_customer', $id_customer);
        $this->db->update('ms_customer_h23');
        
        if ($this->db->trans_status() == true) {
            $this->db->trans_commit();
            $result = [
                'status' => true,
                'message' => 'success',
                'data' => []
            ];
        } else {
            $this->db->trans_rollback();
            $result = [
                'status' => false,
                'message' => 'failed',
                'data' => []
            ];
        }
    }else{
        $this->db->trans_rollback();
        $result = [
            'status' => false,
            'message' => 'failed',
            'data' => []
        ];
    }
    echo json_encode($result);
  }
}
