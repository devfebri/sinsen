<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_pho extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_pho";
	protected $title  = "AHM FILE .PHO";

	public function __construct()
	{
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" OR $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}

		$this->load->model('H3_md_pho_model', 'pho');
		// $this->load->model('ms_part_model', 'part');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function import()
	{
		$data['mode']    = 'upload';
		$data['set']     = "form";
		$this->template($data);
	}

    public function injects()
	{
        $this->db->trans_begin();
		if ( isset($_POST['import'])) {
			$notFound='';
			$count_notFound=0;
			$success=0;
            $file = $_FILES['file']['tmp_name'];

			$ekstensi  = explode('.', $_FILES['file']['name']);

			if (empty($file)) {
				    $_SESSION['pesan'] 	= "File Tidak Boleh Kosong!";
                    $_SESSION['tipe'] 	= "danger";
                    redirect('h3/h3_md_pho/import');
			} else {
				if ((strtolower(end($ekstensi)) === 'pho' ||strtolower(end($ekstensi)) === 'PHO') && $_FILES["file"]["size"] > 0) {

					$i = 0;
					$handle = fopen($file, "r");
					while (($row = fgetcsv($handle, 2048,';'))) {
                        
						$i++;
						if ($i == 1) continue;
                        $row[1] = str_replace(' ', '', $row[1]);
                        $row[2] = str_replace(' ', '', $row[2]);
                        $id = preg_replace('/[^ \w]+/', '', $row[0]);
						$query = $this->db->select('id_part')
										  ->get_where('ms_part',array('id_part'=>$id));
						$count = $query->num_rows();
						if($count > 0){
							$data = [
								'hoo_flag' => $row[1],
								'hoo_max' => $row[2],
							];
							$this->db->where('id_part',$id);
							$this->db->update('ms_part',$data);
							$success++;
						}else{
							$notFound .= $id . '</br>';
							$count_notFound++;
							if($count_notFound >= 10){ break; }
						}
					}
                    if ($this->db->trans_status() and $count_notFound==0) {
                        $this->db->trans_commit();
                        fclose($handle);
                        $_SESSION['pesan'] 	= "File PHO berhasil diupload.";
                        $_SESSION['tipe'] 	= "success";
                        redirect('h3/h3_md_pho');
                    } else {
                        $this->db->trans_rollback();
                        $_SESSION['pesan'] 	= "File PHO tidak berhasil diupload/Terdapat kode part yang tidak ada di Master Parts : </br> $notFound dll...";
                        $_SESSION['tipe'] 	= "danger";
                        redirect('h3/h3_md_pho');
                    }
				} else {
                    $_SESSION['pesan'] 	= "Format File Tidak Valid!";
                    $_SESSION['tipe'] 	= "danger";
                    redirect('h3/h3_md_pho/import');
				}
			}
        }elseif(isset($_POST['import_v2'])){
			$notFound='';
			$count_notFound=0;
			$success=0;
            $file = $_FILES['file']['tmp_name'];

			$ekstensi  = explode('.', $_FILES['file']['name']);

			if (empty($file)) {
				    $_SESSION['pesan'] 	= "File Tidak Boleh Kosong!";
                    $_SESSION['tipe'] 	= "danger";
                    redirect('h3/h3_md_pho/import');
			} else {
				if ((strtolower(end($ekstensi)) === 'pho' ||strtolower(end($ekstensi)) === 'PHO') && $_FILES["file"]["size"] > 0) {

					$i = 0;
					$handle = fopen($file, "r");
					while (($row = fgetcsv($handle, 2048,';'))) {
                        
						$i++;
						if ($i == 1) continue;
                        $row[1] = str_replace(' ', '', $row[1]);
                        $row[2] = str_replace(' ', '', $row[2]);
                        $id = preg_replace('/[^ \w]+/', '', $row[0]);
						$query = $this->db->select('id_part')
										  ->get_where('ms_part',array('id_part'=>$id));
						$count = $query->num_rows();
						if($count > 0){
							$data = [
								// 'id_part' => $row[1],
								'hoo_flag' => $row[1],
								'hoo_max' => $row[2],
							];
							$this->db->where('id_part',$id);
							$this->db->update('ms_part',$data);
							$success++;
						}else{
							$notFound .= $id . '</br>';
							$count_notFound++;
							// if($count_notFound >= 2){ break; }
						}
					}
                    if ($this->db->trans_status()) {
                        $this->db->trans_commit();
                        fclose($handle);
                        // $_SESSION['pesan'] 	= "File PHO berhasil diupload $success part dan gagal diupdate $count_notFound part";
						if($count_notFound > 0){
							$_SESSION['pesan'] 	= "File PHO berhasil diupload $success part dan gagal diupdate $count_notFound part yaitu </br> $notFound" ;
						}else{
							$_SESSION['pesan'] 	= "File PHO berhasil diupload $success part dan gagal diupdate $count_notFound part" ;
						}
                        $_SESSION['tipe'] 	= "success";
                        redirect('h3/h3_md_pho');
                    } else {
                        $this->db->trans_rollback();
                        $_SESSION['pesan'] 	= "File PHO tidak berhasil diupload/Terdapat kode part yang tidak ada di Master Parts : </br> $notFound dll...";
                        $_SESSION['tipe'] 	= "danger";
                        redirect('h3/h3_md_pho');
                    }
				} else {
                    $_SESSION['pesan'] 	= "Format File Tidak Valid!";
                    $_SESSION['tipe'] 	= "danger";
                    redirect('h3/h3_md_pho/import');
				}
			}
		}
	}

    public function getDataTable()
    {
        $list = $this->pho->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->id_part;
            $row[] = $field->nama_part;
            $row[] = $field->hoo_flag;
            $row[] = $field->hoo_max;

            $data[] = $row;
        }
    
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->pho->count_all(),
            "recordsFiltered" => $this->pho->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
}
