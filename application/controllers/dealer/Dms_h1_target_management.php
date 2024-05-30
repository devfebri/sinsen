<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . '/third_party/Spout/Autoloader/autoload.php';
class Dms_h1_target_management extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "dms_h1_target_management";
  var $title  = "Target Management";

  public function __construct()
  {
    parent::__construct();
    //---- cek session -------//		
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    }

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_dms');


    //===== Load Library =====
    $this->load->library('upload');
    $this->load->helper('tgl_indo');
    $this->load->helper('terbilang');
  }
  protected function template($data)
  {
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    } else {
      $data['folder'] = $this->folder;
      $this->load->view('template/header', $data);
      $this->load->view('template/aside');
      $this->load->view($this->folder . "/" . $this->page);
      $this->load->view('template/footer');
    }
  }

  public function index()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['set']   = "index";
    $this->template($data);
  }

  public function setting_target_flp()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['set']   = "add_sales_force";    
    $id_dealer = $this->m_admin->cari_dealer();
    $kode_dealer	=	$this->m_admin->cari_kode_dealer($id_dealer);
    $data['set_sales_force'] = $this->db->query("select sfh.* from tr_target_sales_force_md sfh 
    left join tr_target_sales_force_md_detail sfd on sfd.no_register_target_sales = sfh.no_register_target_sales
    WHERE sfd.id_dealer ='$kode_dealer' and sfh.status = 'approve'
    group by sfh.no_register_target_sales")->result();
    $this->template($data);
  }

  public function history()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'History ' . $this->title;
    $data['set']   = "history";
    $this->template($data);
  }

  public function fetch()
  {
    $fetch_data = $this->make_query();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $status = '';
      $button = '';
      $active = $rs->active == 1 ? '<i class="fa fa-check"></i>' : '';
      $btn_edit = '<a data-toggle="tooltip" title="Edit" style="margin-top:2px; margin-right:1px;"href="' . $this->folder . '/' . $this->page . '/edit?id=' . $rs->id . '" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>';
      $history = isset($_POST['is_history']) ? '&h=y' : '';
      $btn_detail = '<a data-toggle="tooltip" title="Detail" style="margin-top:2px; margin-right:1px;"href="' . $this->folder . '/' . $this->page . '/detail?id=' . $rs->id . $history . '" class="btn btn-info btn-xs btn-flat"><i class="fa fa-eye"></i></a>';
      $btn_delete = '<a onclick=\' return confirm("Apakah Anda yakin ?")\' data-toggle="tooltip" title="Delete" style="margin-top:2px; margin-right:1px;"href="' . $this->folder . '/' . $this->page . '/deleted?id=' . $rs->id . '" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i></a>';
      $button .= $btn_detail;
      if (can_access($this->page, 'can_update')) $button .= $btn_edit;
      if (can_access($this->page, 'can_delete')) {
        if ($rs->tahun == get_y() && $rs->bulan == get_m()) {
          $button .= $btn_delete;
        }
      }
      if (isset($_POST['is_history'])) {
        $button = $btn_detail;
      }
      // $sub_array[] = '<a href="' . $this->folder . '/' . $this->page . '/detail?id=' . $rs->id . '">' . $rs->id . '</a>';
      // $aktif = $rs->aktif == 1 ? '<i class="fa fa-check"></i>' : '';
      $sub_array[] = $rs->tahun;
      $sub_array[] = $rs->bulan;
      $sub_array[] = $rs->kode_dealer_md;
      // $sub_array[] = $rs->nama_dealer;
      $sub_array[] = $rs->honda_id;
      $sub_array[] = $rs->nama_lengkap;
      // $sub_array[] = $rs->id_tipe_kendaraan;
      // $sub_array[] = $rs->tipe_ahm;
      $sub_array[] = $rs->target_sales;
      $sub_array[] = $active;
      $sub_array[] = $button;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST['order'] : '',
      'search' => $this->input->post('search')['value'],
      'order_column' => 'view',
      'deleted' => false,
    ];
    if (isset($_POST['periode'])) {
      $filter['periode_sama_lebih_besar'] = $_POST['periode'];
    }
    if (isset($_POST['periode_lebih_kecil'])) {
      $filter['periode_lebih_kecil'] = $_POST['periode_lebih_kecil'];
    }
    $filter['id_dealer']=dealer()->id_dealer;
    if ($recordsFiltered == true) {
      return $this->m_dms->getH1TargetManagement($filter)->num_rows();
    } else {
      return $this->m_dms->getH1TargetManagement($filter)->result();
    }
  }

  public function download_target_tipe_kendaraan_process()
	{

    $no_register_target_sales = $this->input->get('id');


    $id_dealer     = $this->m_admin->cari_dealer();
    $kode_dealer = $this->m_admin->cari_kode_dealer($id_dealer);


     $data['sales_force_header'] = $sales_force_header = $this->db->query("SELECT sfh.*, sum(sfd.jumlah) as tot
     from tr_target_sales_force_md sfh 
     left join tr_target_sales_force_md_detail sfd on sfd.no_register_target_sales = sfd.no_register_target_sales 
     WHERE sfd.id_dealer = '$kode_dealer' and sfh.no_register_target_sales='$no_register_target_sales'
     group by sfd.id_dealer")->row();
 
     
     $filter = [
       'id_dealer' => $id_dealer,
       'set' => 'approve',
       'bulan' => $sales_force_header->priode_target
     ];

     $data['flp'] = $this->m_dms->getSalesPeopleActive($filter)->result();
     $data['jumlah_target_md'] = $this->m_dms->jumlah_target_from_md($filter=NULL)->row();


    $data['flp_sales'] = $this->db->query("SELECT   
    sfc.id_tipe_kendaraan,
    sfc.jumlah,
    CASE WHEN tku.no_urut IS NULL THEN 99 ELSE tku.no_urut END as no_uruts
    from tr_sales_order so 
    join tr_spk spk on so.no_spk = spk.no_spk 
    join tr_prospek pro on pro.id_customer = spk.id_customer 
    join ms_karyawan_dealer kd on kd.id_karyawan_dealer = pro.id_karyawan_dealer
    left join tr_target_sales_force_md_detail sfc on sfc.id_dealer  = '$kode_dealer'
    LEFT JOIN ms_tipe_kendaraan_urut tku ON sfc.id_tipe_kendaraan = tku.id_tipe_kendaraan
    where  kd.id_dealer='$id_dealer'
    AND kd.active ='1'
    and pro.id_flp_md <> ''
    and sfc.no_register_target_sales = '$no_register_target_sales'
    group by 
    sfc.id_tipe_kendaraan 
    order by kd.nama_lengkap,  no_uruts  asc")->result();


    $tipe_kendaaran_in= "";
    foreach ($data['flp_sales'] as $key => $item) {
      $tipe_kendaaran_in .= "'".$item->id_tipe_kendaraan."',";
    }
    $tipe_kendaaran_in = rtrim($tipe_kendaaran_in, ',');

    $array = [];

    $filter = [
      'id_dealer' => $id_dealer,
      'set' => 'detail',
      'tipe_kendaraan_in' =>$tipe_kendaaran_in, 
    ];

    
    $tipe_kendaraan_total_by_ssu = array();
    foreach ($data['flp'] as $innerKey => $keys) {
      $tipe_kendaraan_total_by_ssu[] = max($keys->tot_ssu_m_1, $keys->tot_ssu_m_2,  $keys->tot_ssu_m_3);
    }

    $tipe_kendaraan_excel = array();
    foreach ($data['flp_sales'] as $key => $item) {
      $tipe_kendaraan_excel[] =  $item->jumlah;
    }


    $set_jumlah_sales_flp = count($data['flp']);


    $data['jumlah_target_md_max']=$set_tipe_kendaraan_total_by_ssu = array_sum($tipe_kendaraan_total_by_ssu);
    $set_tipe_kendaraan_excel        = array_sum($tipe_kendaraan_excel);



    foreach ($data['flp_sales'] as $key => $item) {
        $array[$key]['id_tipe_kendaraan'] = $item->id_tipe_kendaraan;
        $array[$key]['jumlah_from_md']    = $item->jumlah; 

        foreach ($data['flp'] as $innerKey => $keys) {

            $filter = [
                'flp' => $keys->id_flp_md,
                'id_dealer' => $id_dealer,
                'set' => 'detail',
            ];

            $sets = $this->m_dms->getSalesPeopleTipeKendaraan($filter)->row();
            $hasil   = 0;

            // re qty  
            $re_tipe_kendaraan = ($set_tipe_kendaraan_excel/$set_tipe_kendaraan_total_by_ssu) * $item->jumlah ;
            // akhir re qty

            // percent
            $tot_ssu_max_set_ulang = max($keys->tot_ssu_m_1, $keys->tot_ssu_m_2,  $keys->tot_ssu_m_3);
            $flp_percent_100 = ($tot_ssu_max_set_ulang / $set_tipe_kendaraan_total_by_ssu ) * 100;
            // percent

            //Hasil 
            $set_flp_process = (round($re_tipe_kendaraan)/100) * $flp_percent_100;
            //Hasil Akhir

            $hasil = $set_flp_process ;
            $sets  = (object)['avg_tot_pembulatan_keatas' => $hasil ]; 

            $array[$key]['sales_data'][$innerKey] = [
                'flp_id' => $keys->id_flp_md,
                'avg_tot' => number_format(round($sets->avg_tot_pembulatan_keatas), 0),
                'nama' => $keys->nama_lengkap,
            ];
        }
  }

  $data['flp_tipe']= $array;
  $data['flp_tipe_footer']= $array;
  $this->load->view('dealer/laporan/temp_target_sales_from_md_tipe_kendaraan',$data);
  }

  
	public function download_target_tipe_kendaraan()
	{
		$no_register_target_sales = $this->input->get('id');
    $id_dealer = $this->m_admin->cari_dealer();

		$data['flp_sales_tipe_kendaraan'] = $this->db->query("SELECT   
		sfc.id_tipe_kendaraan 
		from  tr_target_sales_force_md_detail sfc 
		where
		sfc.no_register_target_sales = '$no_register_target_sales'
		group by 
		sfc.id_tipe_kendaraan  
		order by sfc.id_tipe_kendaraan asc")->result();
		
		$data['flp_sales_kode_dealer'] = $this->db->query("SELECT   
		sfc.id_dealer, md.nama_dealer 
		from  tr_target_sales_force_md_detail sfc 
		left join ms_dealer md on sfc.id_dealer = md.kode_dealer_md 
		where
    md.id_dealer = '$id_dealer' AND 
		sfc.no_register_target_sales = '$no_register_target_sales'
		group by 
		sfc.id_dealer  
		order by sfc.id_dealer asc")->result();

		$array       = [];
		$array_total = [];


		foreach ($data['flp_sales_kode_dealer'] as $key => $item ){
			$array[$key]['id_dealer'] = $item->id_dealer;
			$array[$key]['nama_dealer'] = $item->nama_dealer;

			foreach ($data['flp_sales_tipe_kendaraan'] as $innerKey => $keys) {

				$filter = [
					'flp'       => $keys->id_flp_md,
					'id_dealer' => $item->id_dealer,
					'no_register_target_sales' => $no_register_target_sales,
					'tipe_kendaraan'           => $keys->id_tipe_kendaraan
				];

				$sets = $this->get_kendaraan_sales($filter);

				$array_total[$keys->id_tipe_kendaraan][] =  $sets->jumlah;
				$array[$key]['sales_data'][$innerKey] = [
					'tipe_kendaraan' => $keys->id_tipe_kendaraan,
					'jumlah' => $sets->jumlah,
				];
			}
		}

		$data['sales_force_detail']= $array;
		$data['sales_force_detail_footer']= $array_total;

		$this->load->view('dealer/laporan/temp_target_sales_from_md_tipe_kendaraan',$data);
	}

  
	public function get_kendaraan_sales($filter){

		$no_register = $filter['no_register_target_sales'];
		$tipe_kendaraan = $filter['tipe_kendaraan'];
		$id_dealer = $filter['id_dealer'];

		$query = $this->db->query("SELECT   
		sfc.id_dealer ,sfc.id_tipe_kendaraan,sfc.jumlah
		from  tr_target_sales_force_md_detail sfc 
		where
		sfc.no_register_target_sales = '$no_register' and sfc.id_tipe_kendaraan ='$tipe_kendaraan' and sfc.id_dealer ='$id_dealer'
		group by 
		sfc.id_dealer  
		order by sfc.id_dealer asc")->row();
		return $query;
	}

  public function add()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['mode']  = 'insert';
    $data['set']   = "form";
    $this->template($data);
  }

  function save()
  {
    $post       = $this->input->post();
    $insert = [
      'tahun'                   => $post['tahun'],
      'bulan'                   => $post['bulan'],
      'honda_id'                => $post['honda_id'],
      // 'target'            => $post['target'],
      'target_prospek'          => $post['target_prospek'],
      'target_spk'              => $post['target_spk'],
      'target_sales'            => $post['target_sales'],
      'kuota_unit_diskon'      => $post['kuota_unit_diskon'],
      'batas_approval_diskon'      => $post['batas_approval_diskon'],
      'id_dealer'               => dealer()->id_dealer,
      'id_tipe_kendaraan'       => isset($post['id_tipe_kendaraan'])?$post['id_tipe_kendaraan']:'LN0',
      'active'                  => $this->input->post('active') == 'on' ? 1 : 0,
      'created_at'              => waktu_full(),
      'created_by'              => user()->id_user,
    ];
    $tgl_isi = strtotime($post['tahun'] . '-' . $post['bulan']);
    $now     = strtotime(get_ym());
    // send_json(($tgl_isi > $now));
    if ($tgl_isi < $now) {
      $rsp = [
        'status' => 'error',
        'pesan' => 'Telah lewat bulan !'
      ];
      send_json($rsp);
    }
    $tes = ['insert' => $insert];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('dms_h1_target_management', $insert);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong !'
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => 'sukses',
        'link' => base_url($this->folder . "/" . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
    }
    send_json($rsp);
  }
  function deleted()
  {
    $get       = $this->input->get();
    $filter = ['id' => $get['id']];
    $cek = $this->m_dms->getH1TargetManagement($filter);
    if ($cek->num_rows() > 0) {
      $this->db->trans_begin();
      $deleted = ['deleted' => 1];
      $this->db->update('dms_h1_target_management', $deleted, ['id' => $get['id']]);

      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        $_SESSION['pesan']   = "Something went wrong";
        $_SESSION['tipe']   = "error";
      } else {
        $this->db->trans_commit();
        $_SESSION['pesan']   = "Data has been deleted successfully";
        $_SESSION['tipe']   = "success";
      }
    }
    echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . '/' . $this->page) . "'>";
  }

  public function upload()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['set']   = "upload";
    $this->template($data);
  }

  
  function import_db()
  {
    $filename = $_FILES["userfile"]["tmp_name"];
    $name     = $_FILES["userfile"]["name"];
    $size     = $_FILES["userfile"]["size"];
    $name_r   = explode('.', $name);

    if ($size > 0 and $name_r[1] == 'csv') {
      
      $file = fopen($filename, "r");
      $is_header_removed = FALSE;
      $id_dealer = dealer()->id_dealer;
      $id_user = user()->id_user;
      $no = 0;
      $err = [];


      while (($rs = fgetcsv($file, 10000, ";")) !== FALSE) {
        $no++;
        if ($no == 1) continue;
        $tgl_isi = strtotime($rs[0] . '-' . $rs[1]);
        $now     = strtotime(get_ym());
        $no_min = $no - 1;


        if ($rs[0]=='') {
          $err[$no_min][] = "Tahun kosong";
        }

        if ($rs[1]=='') {
          $err[$no_min][] = "Bulan kosong";
        }

        if ($tgl_isi < $now) {
          $err[$no_min][] = "Telah lewat bulan";
        }

        $cek_tipe = $this->db->query("SELECT id_tipe_kendaraan FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan='{$rs[3]}'")->num_rows();
        if ($cek_tipe == 0) {
          $err[$no_min][] = "ID tipe kendaraan {$rs[3]} tidak ditemukan";
        }

        if ($rs[2]=='') {
          $err[$no_min][] = "Honda ID kosong";
        }

        $cek_honda_id = $this->db->query("SELECT honda_id FROM ms_karyawan_dealer WHERE (id_flp_md='{$rs[2]}' OR honda_id='{$rs[2]}') AND id_dealer='$id_dealer' AND active=1")->num_rows();
        if ($cek_honda_id == 0) {
          $err[$no_min][] = "Honda ID {$rs[2]} tidak ditemukan";
        }

        if (isset($err[$no_min])) {
          continue;
        }
        
        $ft = [
          'tahun' => $rs[0],
          'bulan' => $rs[1],
          'honda_id' => $rs[2],
          'id_tipe_kendaraan' => $rs[3],
          'id_dealer' => $id_dealer
        ];
        
        $cek_target = $this->m_dms->getH1TargetManagement($ft);
        // send_json($cek_target);
        if ($cek_target->num_rows() == 0) {
          $ins_batch[] = [
            'tahun' => $rs[0],
            'bulan' => $rs[1],
            'honda_id' => $rs[2],
            'id_tipe_kendaraan' => $rs[3],
            'target_prospek' => $rs[4],
            'target_spk' => $rs[5],
            'target_sales' => $rs[6],
            'kuota_unit_diskon' => $rs[7],
            'batas_approval_diskon' => $rs[8],
            'target' => $rs[4] + $rs[5] + $rs[6] + $rs[7] + $rs[8],
            'id_dealer' => $id_dealer,
            'active' => 1,
            'created_at' => waktu_full(),
            'created_by' => $id_user
          ];
        } else {
          $ct = $cek_target->row();
          $upd_batch[] = [
            'id' => $ct->id,
            'tahun' => $rs[0],
            'bulan' => $rs[1],
            'honda_id' => $rs[2],
            'id_tipe_kendaraan' => $rs[3],
            'target_prospek' => $rs[4],
            'target_spk' => $rs[5],
            'target_sales' => $rs[6],
            'kuota_unit_diskon' => $rs[7],
            'target' => $rs[4] + $rs[5] + $rs[6] + $rs[7],
            'id_dealer' => $id_dealer,
            'active' => 1,
            'updated_at' => waktu_full(),
            'updated_by' => $id_user
          ];
        }
      }
      fclose($file);
      // send_json($err);

      if (count($err) > 0) {
        $html_pesan = '<ul>';
        foreach ($err as $key => $er) {
          $html_pesan .= "<li> Line : $key";
          $html_pesan .= "<ol>";
          foreach ($er as $ls) {
            $html_pesan .= "<li> $ls </li>";
          }
          $html_pesan .= "</ol>";
          $html_pesan .= "</li>";
        }
        $html_pesan .= "</ul>";
        $rsp = [
          'status' => 'error',
          // 'pesan'  => $err,
          'tipe'=>'html',
          'link'   => base_url($this->folder . '/' . $this->page.'/upload')
        ];
        $this->session->set_flashdata('html_errors', $html_pesan);
        send_json($rsp);
      } else {
        $tes = [
          'ins' => isset($ins_batch) ? $ins_batch : NULL,
          'upd' => isset($upd_batch) ? $upd_batch : NULL
        ];
        // send_json($tes);
        $this->db->trans_begin();
        if (isset($ins_batch)) {
          $this->db->insert_batch('dms_h1_target_management', $ins_batch);
        }
        if (isset($upd_batch)) {
          $this->db->update_batch('dms_h1_target_management', $upd_batch, 'id');
        }
        if (!$this->db->trans_status()) {
          $this->db->trans_rollback();
          $rsp = [
            'status' => 'error',
            'pesan' => ' Something went wrong !'
          ];
        } else {
          $this->db->trans_commit();
          $c_ins = isset($ins_batch) ? count($ins_batch) : 0;
          $c_upd = isset($upd_batch) ? count($upd_batch) : 0;
          if ($c_ins > 0) {
            $pesan[] = "$c_ins data berhasil ditambah";
          }
          if ($c_upd > 0) {
            $pesan[] = "$c_upd data berhasil diupdate";
          }
          $_SESSION['pesan']   = implode(', ', $pesan);
          $_SESSION['tipe']   = "success";
          $rsp = [
            'status' => 'sukses',
            'link' => base_url($this->folder . '/' . $this->page)
          ];
        }
      }
    } else {
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong !'
      ];
    }
    send_json($rsp);
  }

  public function edit()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Edit ' . $this->title;
    $data['mode']  = 'edit';
    $data['set']   = "form";
    $id    = $this->input->get('id');

    $filter['id'] = $id;
    $result = $this->m_dms->getH1TargetManagement($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . "/" . $this->page) . "'>";
    }
  }

  function save_edit()
  {
    $post      = $this->input->post();

    $id = $post['id'];

    $update = [
      'tahun'             => $post['tahun'],
      'bulan'             => $post['bulan'],
      'honda_id'          => $post['honda_id'],
      // 'target'            => $post['target'],
      'target_prospek'    => $post['target_prospek'],
      'target_sales'      => $post['target_sales'],
      'kuota_unit_diskon'      => $post['kuota_unit_diskon'],
      'batas_approval_diskon'      => $post['batas_approval_diskon'],
      'target_spk'        => $post['target_spk'],
      'id_dealer'         => dealer()->id_dealer,
      'id_tipe_kendaraan'       => isset($post['id_tipe_kendaraan'])?$post['id_tipe_kendaraan']:'LN0',
      'active' => $this->input->post('active') == 'on' ? 1 : 0,
      'updated_at'        => waktu_full(),
      'updated_by'        => user()->id_user,
    ];
    // $tes = ['update' => $update];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('dms_h1_target_management', $update, ['id' => $id]);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong !'
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => 'sukses',
        'link' => base_url($this->folder . '/' . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
    }
    send_json($rsp);
  }

  public function tipe_kedaraan_by_md()
  {

    $no_register_target_sales = $this->input->get('id');

    $data['mode']  = 'approve';
    $data['isi']   = $this->page;
    $data['title'] = 'History ' . $this->title;
    $data['set']   = "sales_force_type";
    $id_dealer     = $this->m_admin->cari_dealer();
    $kode_dealer = $this->m_admin->cari_kode_dealer($id_dealer);

     $data['sales_force_header'] = $sales_force_header = $this->db->query("SELECT sfh.*, sum(sfd.jumlah) as tot
     from tr_target_sales_force_md sfh 
     left join tr_target_sales_force_md_detail sfd on sfd.no_register_target_sales = sfd.no_register_target_sales 
     WHERE sfd.id_dealer = '$kode_dealer' and sfh.no_register_target_sales='$no_register_target_sales'
     group by sfd.id_dealer")->row();
 
     
     $filter = [
       'id_dealer' => $id_dealer,
       'set' => 'approve',
       'bulan' => $sales_force_header->priode_target
     ];

     $data['flp'] = $this->m_dms->getSalesPeopleActive($filter)->result();
     $data['jumlah_target_md'] = $this->m_dms->jumlah_target_from_md($filter=NULL)->row();


    $data['flp_sales'] = $this->db->query("SELECT   
    sfc.id_tipe_kendaraan,
    sfc.jumlah,
    CASE WHEN tku.no_urut IS NULL THEN 99 ELSE tku.no_urut END as no_uruts
    from tr_sales_order so 
    join tr_spk spk on so.no_spk = spk.no_spk 
    join tr_prospek pro on pro.id_customer = spk.id_customer 
    join ms_karyawan_dealer kd on kd.id_karyawan_dealer = pro.id_karyawan_dealer
    left join tr_target_sales_force_md_detail sfc on sfc.id_dealer  = '$kode_dealer'
    LEFT JOIN ms_tipe_kendaraan_urut tku ON sfc.id_tipe_kendaraan = tku.id_tipe_kendaraan
    where  kd.id_dealer='$id_dealer'
    AND kd.active ='1'
    and pro.id_flp_md <> ''
    and sfc.no_register_target_sales = '$no_register_target_sales'
    group by 
    sfc.id_tipe_kendaraan 
    order by kd.nama_lengkap,  no_uruts  asc")->result();


    $tipe_kendaaran_in= "";
    foreach ($data['flp_sales'] as $key => $item) {
      $tipe_kendaaran_in .= "'".$item->id_tipe_kendaraan."',";
    }
    $tipe_kendaaran_in = rtrim($tipe_kendaaran_in, ',');

    $array = [];

    $filter = [
      'id_dealer' => $id_dealer,
      'set' => 'detail',
      'tipe_kendaraan_in' =>$tipe_kendaaran_in, 
    ];
    
    $tipe_kendaraan_total_by_ssu = array();
    foreach ($data['flp'] as $innerKey => $keys) {
      $tipe_kendaraan_total_by_ssu[] = max($keys->tot_ssu_m_1, $keys->tot_ssu_m_2,  $keys->tot_ssu_m_3);
    }

    $tipe_kendaraan_excel = array();
    foreach ($data['flp_sales'] as $key => $item) {
      $tipe_kendaraan_excel[] =  $item->jumlah;
    }

    $data['jumlah_target_md_max']=$set_tipe_kendaraan_total_by_ssu = array_sum($tipe_kendaraan_total_by_ssu);
    $set_tipe_kendaraan_excel        = array_sum($tipe_kendaraan_excel);

    foreach ($data['flp_sales'] as $key => $item) {
        $array[$key]['id_tipe_kendaraan'] = $item->id_tipe_kendaraan;
        $array[$key]['jumlah_from_md']    = $item->jumlah; 

        foreach ($data['flp'] as $innerKey => $keys) {

            $filter = [
                'flp' => $keys->id_flp_md,
                'id_dealer' => $id_dealer,
                'set' => 'detail',
            ];

            $sets = $this->m_dms->getSalesPeopleTipeKendaraan($filter)->row();
            $hasil   = 0;

            // re qty  
            $re_tipe_kendaraan = ($set_tipe_kendaraan_excel/$set_tipe_kendaraan_total_by_ssu) * $item->jumlah ;
            // akhir re qty

            // percent
            $tot_ssu_max_set_ulang = max($keys->tot_ssu_m_1, $keys->tot_ssu_m_2,  $keys->tot_ssu_m_3);
            $flp_percent_100 = ($tot_ssu_max_set_ulang / $set_tipe_kendaraan_total_by_ssu ) * 100;
            // percent

            //Hasil 
            $set_flp_process = ($re_tipe_kendaraan/100) * $flp_percent_100;
            //Hasil Akhir

            $hasil = $set_flp_process ;
            $sets  = (object)['avg_tot_pembulatan_keatas' => $hasil ]; 

            $array[$key]['sales_data'][$innerKey] = [
                'flp_id' => $keys->id_flp_md,
                'avg_tot' => number_format(round($sets->avg_tot_pembulatan_keatas), 0),
                'avg_tot' => number_format($sets->avg_tot_pembulatan_keatas, 0),
                'nama' => $keys->nama_lengkap,
            ];
        }
  }

  $data['flp_tipe']= $array;
  $data['flp_tipe_footer']= $array;



  $this->template($data);
  }


  public function setting_target_flp_from_md()
  {

    $id            = $this->input->get('id');
    $data['mode']  = 'approve';

    $data['isi']   = $this->page;
    $data['title'] = 'History ' . $this->title;
    $data['set']   = "detail_sales_force";

    $get_dealer  = $this->m_admin->cari_dealer();
    $kode_dealer = $this->m_admin->cari_kode_dealer($get_dealer);

    $data['sales_force_header'] = $sales_force_header = $this->db->query("SELECT sfh.*, sum(sfd.jumlah) as tot
		from tr_target_sales_force_md sfh 
		left join tr_target_sales_force_md_detail sfd on sfd.no_register_target_sales = sfd.no_register_target_sales 
		WHERE sfd.id_dealer = '$kode_dealer' and sfh.no_register_target_sales='$id'
		group by sfd.id_dealer")->row();

    
    $filter = [
      'id_dealer' => $get_dealer,
      'set' => 'approve',
      'bulan' => $sales_force_header->priode_target
    ];

    $data['tipe_kendaran'] = $this->db->query("SELECT DISTINCT id_tipe_kendaraan from tr_target_sales_force_md_detail where no_register_target_sales ='$id'")->result();

    $sales_force_get = $this->m_dms->getSalesPeopleActive($filter)->result();

    $data['count_avg'] = count($sales_force_get);

    $avg = array();
    $temp = array();

    foreach( $sales_force_get as $item){

      if ($item->avg_tot == 0) {
        $sales_force = 0; 
      } else {
        $sales_force = floor(($item->avg_tot /  $data['count_avg']) * $sales_force_header->tot) - 1;
      }

      $avg[] = $item->avg_tot;

 

      $tot_ssu_max = max($item->tot_ssu_m_1, $item->tot_ssu_m_2,  $item->tot_ssu_m_3);
      
      $array_set = array(
      'id_flp_md' =>  $item->id_flp_md,
      'nama_lengkap' =>  $item->nama_lengkap,
      'tot_ssu' =>  $item->tot_ssu,
      'tot_ssu_m_1' =>  $item->tot_ssu_m_1,
      'tot_ssu_m_2' =>  $item->tot_ssu_m_2,
      'tot_ssu_m_3' =>  $item->tot_ssu_m_3,
      'tot_ssu_max' =>  $tot_ssu_max,
      'avg_tot' =>  $item->avg_tot,
      'target_prospek' =>   $item->target_spk * 4,
      'target_sales' =>  $item->target_sales,
      'target_spk' =>  $item->target_spk,
      'percent' =>  $item->target_spk,
      'sales_force' => $sales_force,
      // 'percent' => $formatted,
      );
      $temp[] = $array_set;
    }



    $data['set_sales_force'] = $temp;
    $this->template($data);
  }



  public function detail_sales_force()
  {
    $id    = $this->input->get('id');
    $data['mode']  = 'detail';

    $data['isi']   = $this->page;
    $data['title'] = 'History ' . $this->title;
    $data['set']   = "detail_sales_force";
    $id_dealer = $this->m_admin->cari_dealer();
    $kode_dealer	=	$this->m_admin->cari_kode_dealer($id_dealer);
    
    $filter = [
      'id_dealer' => $kode_dealer,
      'set' => 'detail'
    ];

    $data['set_sales_force'] = $this->m_dms->getSalesPeopleActive($filter)->result();
    

    $data['sales_force_header']    = $this->db->query("SELECT sfh.*, sum(sfd.jumlah) as tot
		from tr_target_sales_force_md sfh 
		left join tr_target_sales_force_md_detail sfd on sfd.no_register_target_sales = sfd.no_register_target_sales 
		WHERE sfd.id_dealer = '$kode_dealer' and sfh.no_register_target_sales='$id'
		group by sfd.id_dealer")->row();
    $this->template($data);
  }

  public function detail()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Detail ' . $this->title;
    $data['mode']  = 'detail';
    $data['set']   = "form";
    $id    = $this->input->get('id');

    $filter['id'] = $id;
    $result = $this->m_dms->getH1TargetManagement($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . "/" . $this->page) . "'>";
    }
  }

  function downloadTemplate()
  {
    header('Content-Type: text/csv');
    $file_date = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $file_name = 'DMS - Target Management - ' . $file_date . '.csv';
    header('Content-Disposition: attachment; filename="'.$file_name.'"');
    $fp = fopen('php://output', 'wb');


    $dt = array(
      'Tahun',
      'Bulan',
      'Honda ID',
      'Tipe Kendaraan',
      'Target Prospek',
      'Target SPK',
      'Target Sales',
      'Kuota Unit Diskon',
      'Batas Approval Diskon',
    );
    fputcsv($fp, $dt, ';');
    $tipe_motors =$this->db->query("SELECT tipe_motor from tr_scan_barcode tsb 
    where status BETWEEN 1 AND 4 
    GROUP BY tipe_motor");
    $tanggal = explode('-',tanggal());

    foreach ($tipe_motors->result() as $key=>$tm) {
        $dt = [$tanggal[0],$tanggal[1],'',$tm->tipe_motor,0,0,0,0,0];
        if($key==0)$dt=[$tanggal[0],$tanggal[1],'',$tm->tipe_motor,5,4,3,2,100000];
        fputcsv($fp, $dt, ';');
    }
  }


  function setting_sales_force_save()
  {

    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['set']   = "index";

    $sales    = $this->input->post('sales1');
    $deal     = $this->input->post('deal1');
    $prospek  = $this->input->post('prospek4');
    $honda_id  = $this->input->post('honda_id');
    $register  = $this->input->post('no_register_target_sales');
    $get_dealer = $this->m_admin->cari_dealer();

    $currentMonth = date('m');
    
    if (is_array($sales)) {
        $temp_data = array();
        foreach ($sales as $key => $value) {
            $temp_data[] = (object)array(
                'id_sales_assign'=> null,
                'honda_id'       => isset($honda_id[$key]) ? $honda_id[$key] : null,
                'target_sales'   => isset($sales[$key]) ? intval($sales[$key]) : null,
                'target_deal'    => isset($deal[$key]) ? intval($deal[$key]) : null,
                'target_prospek' => isset($prospek[$key]) ? intval($prospek[$key]) : null,
                'no_register_target_sales' => $register,
                'bulan' => $currentMonth,
                'id_dealer' => $get_dealer
            );
        }
    }


    $this->db->where('honda_id', $honda_id);
    $this->db->where('no_register_target_sales', $register);
    $this->db->from('tr_target_sales_force_md_assign');
    // Execute the query
    $query = $this->db->get();

    if ($query->num_rows() > 0) {

  $this->db->insert_batch('tr_target_sales_force_md_assign', $temp_data);
       
  if ($this->db->trans_status() === FALSE) {
    $this->db->trans_rollback();
    $_SESSION['pesan']   = "Something went wrong";
    $_SESSION['tipe']   = "error";
  } else {
    $this->db->trans_commit();

          $data = array(
            'approve_d_created_at ' => waktu_full(),
            'approve_d_by ' =>  user()->id_user,
          );

        $where = array(
            'no_register_target_sales' =>$register, 
        );

          $this->db->where($where);
          $this->db->update('tr_target_sales_force_md', $data);
    
          $_SESSION['pesan']   = "Data has been Insert successfully";
          $_SESSION['tipe']   = "success";
  }

} else {

  $_SESSION['pesan']   = "Data has been Record";
  $_SESSION['tipe']   = "success";

}

  $this->template($data);




  }



}
