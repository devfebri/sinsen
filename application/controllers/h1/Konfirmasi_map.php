<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konfirmasi_map extends CI_Controller {

    var $tables =   "tr_kirim_biro";	
		var $folder =   "h1";
		var $page		=		"konfirmasi_map";
    var $pk     =   "id_konfirmasi_map";
    var $title  =   "Konfirmasi Penerimaan Map Fisik";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');	
		$this->load->model('m_konfirmasi_map_datatables');			
		//===== Load Library =====
		$this->load->library('upload');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}


	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}else{						
			$data['id_menu'] = $this->m_admin->getMenu($this->page);
			$data['group'] 	= $this->session->userdata("group");
			$this->load->view('template/header',$data);
			$this->load->view('template/aside');			
			$this->load->view($this->folder."/".$this->page);		
			$this->load->view('template/footer');
		}
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "view_new";
		//$data['dt_biro']	= $this->m_admin->getAll($this->tables);
		$this->template($data);			
	}	


	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}		
	public function konfirmasi()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;				
		$id = $this->input->get('id');		
		$a 	= $this->input->get('a');		
		$data['dt_biro']= $this->m_admin->getByID("tr_kirim_biro","no_tanda_terima",$a);
		$data['dt_map']	= $this->db->query("SELECT tr_pengajuan_bbn_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_pengajuan_bbn_detail INNER JOIN ms_tipe_kendaraan 
				ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				INNER JOIN ms_warna ON tr_pengajuan_bbn_detail.id_warna = ms_warna.id_warna
				WHERE tr_pengajuan_bbn_detail.id_generate='$id'");											
		$data['set']		= "konfirmasi";		
		$data['id_generate'] = $id; 		
		$this->template($data);			
	}	
	public function save_old()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');				
		//$no_bastd 	= $this->cari_id();
		$id_generate2 					= $this->input->post("id_generate2");
		$da['id_generate'] 			= $this->input->post("id_generate2");
		$da['no_tanda_terima'] 	= $this->input->post("no_tanda_terima");
		$da['tgl_terima'] 			= $this->input->post("tgl_terima");
		$da['keterangan'] 			= $this->input->post("keterangan");
		$da['status_map'] 			= "input";		
		$da['created_at'] 			= $waktu;		
		$da['created_by'] 			= $login_id;		
		
		$no_mesin 							= $this->input->post("no_mesin");
		if(is_array($no_mesin)){
			foreach($no_mesin AS $key => $val){
				$nosin 								= $_POST['no_mesin'][$key];
				$gen 									= $_POST['id_generate'][$key];
				$data['no_mesin'] 		= $nosin;
				$data['id_generate'] 	= $gen;								
				if(isset($_POST['konfirmasi'][$key])){
					$data["konfirmasi"] = "ya";									
				}else{
					$data["konfirmasi"] = "tidak";									
				}				
				
				$cek = $this->db->query("SELECT * FROM tr_konfirmasi_map_detail WHERE no_mesin = '$nosin'");
				if($cek->num_rows() > 0){						
					$this->m_admin->update("tr_konfirmasi_map_detail",$data,"no_mesin",$nosin);								
				}else{
					$this->m_admin->insert("tr_konfirmasi_map_detail",$data);								
				}	
										
			}
		}	
		
		$ce = $this->db->query("SELECT * FROM tr_konfirmasi_map WHERE id_generate = '$id_generate2'");
		if($ce->num_rows() > 0){						
			$this->m_admin->update("tr_konfirmasi_map",$da,"id_generate",$id_generate2);								
		}else{
			$this->m_admin->insert("tr_konfirmasi_map",$da);								
		}
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/konfirmasi_map'>";
	}
	public function save_oold()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');				
		//$no_bastd 	= $this->cari_id();
		$id_generate2 					= $this->input->post("id_generate2");
		$da['id_generate'] 			= $this->input->post("id_generate2");
		$da['no_tanda_terima'] 	= $this->input->post("no_tanda_terima");
		$da['tgl_terima'] 			= $this->input->post("tgl_terima");
		$da['keterangan'] 			= $this->input->post("keterangan");
		$da['status_map'] 			= "input";		
		$da['created_at'] 			= $waktu;		
		$da['created_by'] 			= $login_id;		
		
		$jum 							= $this->input->post("jum");
		$jum2 							= $this->input->post("jum2");
		$jum_a = $jum + $jum2;
		for ($i=1; $i <= $jum_a; $i++) { 
			$nosin 								= $_POST["no_mesin_".$i];			
			$data['no_mesin'] 		= $nosin;
			$data['id_generate'] 	= $_POST["id_generate_".$i];
			if(isset($_POST["konfirmasi_".$i])){
				$data["konfirmasi"] = "ya";									
			}else{
				$data["konfirmasi"] = "tidak";									
			}				
			
			$cek = $this->db->query("SELECT * FROM tr_konfirmasi_map_detail WHERE no_mesin = '$nosin'");
			if($cek->num_rows() > 0){						
				$this->m_admin->update("tr_konfirmasi_map_detail",$data,"no_mesin",$nosin);								
			}else{
				$this->m_admin->insert("tr_konfirmasi_map_detail",$data);								
			}	
		}
		
		
		$ce = $this->db->query("SELECT * FROM tr_konfirmasi_map WHERE id_generate = '$id_generate2'");
		if($ce->num_rows() > 0){						
			$this->m_admin->update("tr_konfirmasi_map",$da,"id_generate",$id_generate2);								
		}else{
			$this->m_admin->insert("tr_konfirmasi_map",$da);								
		}
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/konfirmasi_map'>";
	}

	public function save()
	{				
		$waktu 			= gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');				
		//$no_bastd 	= $this->cari_id();
		$id_generate2 					= $this->input->post("id_generate2");
		$da['id_generate'] 			= $this->input->post("id_generate2");
		$da['no_tanda_terima'] 	= $this->input->post("no_tanda_terima");
		$da['tgl_terima'] 			= $this->input->post("tgl_terima");
		$da['keterangan'] 			= $this->input->post("keterangan");
		$da['status_map'] 			= "input";		
		$da['created_at'] 			= $waktu;		
		$da['created_by'] 			= $login_id;		
		
		$jum 							= $this->input->post("jum");
		$jum2 							= $this->input->post("jum2");
		$jum_a = $jum + $jum2;
		$da_insert_bj = array();
		$da_konfirm = array();
		$da_update = array();

		for ($i=1; $i <= $jum_a; $i++) { 	
			$nosin 			= $_POST["no_mesin_".$i];
			$data['no_mesin']   = $nosin;			
			$data['id_generate'] 	= $_POST["id_generate_".$i];
			if(isset($_POST["konfirmasi_".$i])){
				$data["konfirmasi"] = "ya";
			}else{
				$data["konfirmasi"] = "tidak";									
			}				

			$cek = $this->db->query("SELECT no_mesin, konfirmasi FROM tr_konfirmasi_map_detail WHERE no_mesin = '$nosin'");
			if($cek->num_rows() > 0){
				if($cek->row()->konfirmasi == 'tidak'){	
					unset($da['created_by']);
					unset($da['created_at']);	
					$da['updated_at'] 	= $waktu;		
					$da['updated_by'] 	= $login_id;	
					$da_update[] = $data;					
					//$this->m_admin->update("tr_konfirmasi_map_detail",$data,"no_mesin",$nosin);	
					//echo $nosin.' update map detail <br>';	
				}						
			}else{
				$da_konfirm[] = $data;
				// $this->m_admin->insert("tr_konfirmasi_map_detail",$data);		
				// echo $nosin.' insert map detail <br>';						
				
				//*insert tbl tr_terima_bj dgn kolom no_bastd, nama_konsumen, no_mesin, no_rangka, id_tipe_kendaraan, id_warna, status_bj, created_at, created_by
					
				$get_bastd = $this->db->query("SELECT no_mesin, nama_konsumen, no_rangka, no_bastd, id_tipe_kendaraan, id_warna FROM tr_pengajuan_bbn_detail WHERE no_mesin = '$nosin'")->row();
				$da_stok['no_bastd'] 			= $get_bastd->no_bastd;	
				$da_stok['no_mesin']			= $nosin;
				$da_stok['notice_pajak']		= 0;
				$da_stok['no_rangka'] 			= $get_bastd->no_rangka;	
				$da_stok['nama_konsumen'] 		= $get_bastd->nama_konsumen;	
				$da_stok['id_tipe_kendaraan'] 		= $get_bastd->id_tipe_kendaraan;	
				$da_stok['id_warna'] 			= $get_bastd->id_warna;	
				$da_stok['status_bj'] 			= 'input';	
				$da_stok['created_at'] 			= $waktu;		
				$da_stok['created_by'] 			= $login_id;	
				$da_insert_bj[] = $da_stok;
				//$this->m_admin->insert("tr_terima_bj",$da_stok);
			}	
		}

		$this->db->trans_begin();

		if (count($da_insert_bj)>0) {
			$this->db->insert_batch('tr_terima_bj',$da_insert_bj);
		}

		if (count($da_konfirm)>0){
			$this->db->insert_batch('tr_konfirmasi_map_detail',$da_konfirm);
		}

		if (count($da_update)>0) {
			$this->db->update_batch('tr_konfirmasi_map_detail',$da_update,'no_mesin');
		}

		$ce = $this->db->query("SELECT id_generate, created_at FROM tr_konfirmasi_map WHERE id_generate = '$id_generate2'");
		if($ce->num_rows() > 0){						
			$this->m_admin->update("tr_konfirmasi_map",$da,"id_generate",$id_generate2);							
		}else{
			$this->m_admin->insert("tr_konfirmasi_map",$da);								
		}

		if ($this->db->trans_status() === FALSE){
      			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something went wrong !";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
      		}else{
        		$this->db->trans_commit();
			$_SESSION['pesan'] 	= "Data has been save successfully";
			$_SESSION['tipe'] 	= "success";		
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/konfirmasi_map'>";
		}

	}



	
	public function fetch_data_konfirmasi_map()
	{

		$list = $this->m_konfirmasi_map_datatables->get_datatables();


		$data = array();
		$no = $_POST['start'];
        foreach($list as $row) {  

			$check_generate = $this->db->query("
			select no_mesin, konfirmasi
			from tr_konfirmasi_map_detail
			where id_generate ='$row->id_generate'
			");

			$x=0;
			if($check_generate->num_rows()>0){
				foreach ($check_generate->result() as $isi) {
					if($isi->konfirmasi != 'ya'){
					  $x++;
					}	
				}
			}else
			{
				$x++;
			}

			if ($x>0) {
				$tomb = "<a href='h1/konfirmasi_map/konfirmasi?id=$row->id_generate&a=$row->no_tanda_terima' class='btn btn-primary btn-flat btn-xs'><i class='fa fa-check'> Konfirmasi Penerimaan</i></a>";
			  }else{
				$tomb='';
			  }

			$no++;
			$rows = array();
			$rows[] = $no;
			$rows[] = $row->tgl_mohon_samsat;
			$rows[] = $row->id_generate;
			$rows[] = $row->no_tanda_terima;
			$rows[] = $row->tgl_terima;
			$rows[] = $row->jumlah;
			$rows[] = $tomb;
			$data[] = $rows;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal"    => $this->m_konfirmasi_map_datatables->count_all(),
			"recordsFiltered" => $this->m_konfirmasi_map_datatables->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}


    public function getAllData()
    {
        $search = $_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
	$limit = $_POST['length']; // Ambil data limit per page
	$start = $_POST['start']; // Ambil data start
	/*
	$order_index = $_POST['order'][0]['column']; // Untuk mengambil index yg menjadi acuan untuk sorting
	$order_field = $_POST['columns'][$order_index]['data']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
	$order_ascdesc = $_POST['order'][0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"
	*/
        $id_menu = $this->m_admin->getMenu($this->page);

        $cari = '';
	if ($search != '') {
		$cari = " 
			and (a.tgl_mohon_samsat LIKE '%$search%' 
				OR a.id_generate LIKE '%$search%'
				OR b.no_tanda_terima LIKE '%$search%')
		";
	}

        $list_data = $this->db->query("			
		select a.tgl_mohon_samsat, a.id_generate ,no_tanda_terima , tgl_terima , count(no_mesin) as jumlah
		from tr_pengajuan_bbn_detail a 
		join tr_kirim_biro b on a.id_generate = b.id_generate 
		where a.id_generate is not NULL and no_tanda_terima !='' and 1=1 $cari
		group by tgl_mohon_samsat , a.id_generate ,no_tanda_terima , tgl_terima 
		order by tgl_mohon_samsat DESC 
		LIMIT $start,$limit
        ");


        $data = array();

        foreach($list_data->result() as $row)
        {
	    $no_kirim_plat = "<a href='h1/konfirmasi_map/konfirmasi?id=$row->id_generate&a=$row->no_tanda_terima' class='btn btn-primary btn-flat btn-xs'><i class='fa fa-check'> Konfirmasi Penerimaan</i></a>";
	    $cek = $this->db->query("
		select no_mesin , konfirmasi
		from tr_konfirmasi_map_detail
		where id_generate ='$row->id_generate' 
            ");
		
            $x=0;
            $tomb=''; $current =''; $end ='';
	    if($cek->num_rows()>0){
             foreach ($cek->result() as $isi) {
               if($isi->konfirmasi != 'ya'){
                 $x++;
               }		
	     }
	    }else{
	    	$x++;
   	    }

            if ($x>0) {
              $tomb = "<a href='h1/konfirmasi_map/konfirmasi?id=$row->id_generate&a=$row->no_tanda_terima' class='btn btn-primary btn-flat btn-xs'><i class='fa fa-check'> Konfirmasi Penerimaan</i></a>";
            }else{
              $tomb='';
            }
	      
            $data[]= array(
            	'',
                $row->tgl_mohon_samsat,
                $row->id_generate,
                $row->no_tanda_terima,
                $row->tgl_terima,
		$row->jumlah,
		$tomb
            );     
        }
        $get_total = $this->db->query("
		
		select a.tgl_mohon_samsat, a.id_generate ,no_tanda_terima , tgl_terima		
		from tr_pengajuan_bbn_detail a 
		join tr_kirim_biro b on a.id_generate = b.id_generate 
		where a.id_generate is not NULL and b.no_tanda_terima !='' $cari
		order by a.tgl_mohon_samsat DESC 
	");

        $total_data = $get_total->num_rows();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total_data,
            "recordsFiltered" => $total_data,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }


    public function getAllDatas()
    {
 
		$search = $_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
		$limit = $_POST['length']; // Ambil data limit per page
		$start = $_POST['start']; // Ambil data start

        $cari = '';
		if ($search != '') {
			$cari = " 
				and (
					bbn.tgl_mohon_samsat LIKE '%$search%' 
					OR	bbn.id_generate LIKE '%$search%'
					)
			";
		}

        $list_data = $this->db->query("		
		SELECT
		bbn.tgl_mohon_samsat,
		bbn.id_generate,
		COUNT(1) AS jumlah,
		(SELECT tkb.no_tanda_terima FROM tr_kirim_biro tkb WHERE tkb.id_generate = bbn.id_generate) AS no_tanda_terima,
		(SELECT tkb.tgl_terima  FROM tr_kirim_biro tkb WHERE tkb.id_generate = bbn.id_generate) AS tgl_terima
		FROM
			tr_pengajuan_bbn_detail bbn 
		WHERE bbn.id_generate IS NOT NULL $cari
		GROUP BY
			bbn.id_generate
		ORDER BY
			bbn.tgl_mohon_samsat DESC
		LIMIT $start,$limit
        ");


        $data = array();

        foreach($list_data->result() as $row)
        {


		$cek = $this->db->query("
				select no_mesin, konfirmasi
				from tr_konfirmasi_map_detail
				where id_generate ='$row->id_generate' ");
		
            $x=0;
            $tomb=''; $current =''; $end ='';
	    if($cek->num_rows()>0){
             foreach ($cek->result() as $isi) {
               if($isi->konfirmasi != 'ya'){
                 $x++;
               }		
	     }
	    }else{
	    	$x++;
   	    }

            if ($x>0) {
              $tomb = "<a href='h1/konfirmasi_map/konfirmasi?id=$row->id_generate&a=$row->no_tanda_terima' class='btn btn-primary btn-flat btn-xs'><i class='fa fa-check'> Konfirmasi Penerimaan</i></a>";
            }else{
              $tomb='';
            }
	      
            $data[]= array(
            	'',
                $row->tgl_mohon_samsat,
                $row->id_generate,
                $row->no_tanda_terima,
                $row->tgl_terima,
				$row->jumlah,
				$tomb
            );     
        }


        $get_total = $this->db->query("
		SELECT
		bbn.tgl_mohon_samsat,
		bbn.id_generate,
		COUNT(1) AS jumlah,
		(SELECT tkb.no_tanda_terima FROM tr_kirim_biro tkb WHERE tkb.id_generate = bbn.id_generate) AS no_tanda_terima,
		(SELECT tkb.tgl_terima  FROM tr_kirim_biro tkb WHERE tkb.id_generate = bbn.id_generate) AS tgl_terima
		FROM
			tr_pengajuan_bbn_detail bbn 
		WHERE bbn.id_generate IS NOT NULL $cari
		GROUP BY
			bbn.id_generate
		ORDER BY
			bbn.tgl_mohon_samsat DESC
	");

        $total_data = $get_total->num_rows();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total_data,
            "recordsFiltered" => $total_data,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }


}