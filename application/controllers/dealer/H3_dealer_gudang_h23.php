<?php

defined('BASEPATH') or exit('No direct script access allowed');



class h3_dealer_gudang_h23 extends CI_Controller

{

    public $folder = "dealer";

    public $page   = "h3_dealer_gudang_h23";

    public $title  = "Gudang H23";



    public function __construct()

    {

        parent::__construct();

        //---- cek session -------//

        $name = $this->session->userdata('nama');

        if ($name=="") {

            echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";

        }



        //===== Load Database =====

        $this->load->database();

        $this->load->helper('url');

        //===== Load Model =====

        $this->load->model('m_admin');

        $this->load->library('form_validation');

        $this->load->model('h3_dealer_gudang_h23_model', 'gudang_h23');

        $this->load->model('dealer_model', 'dealer');
        $this->load->model('m_admin');

    }

    

    public function index()

    {

        $data['set']	= "index";

        $data['gudang_h23'] = $this->gudang_h23->get([
            'id_dealer' => $this->m_admin->cari_dealer(),
        ]);

        $this->template($data);

    }



    public function add()

    {

        $data['mode']    = 'insert';

        $data['set']     = "form";

        $data['user_dealer'] = $this->dealer->getCurrentUserDealer(); 



        $this->template($data);

    }



    public function save(){

        $gudangData = $this->input->post(['tipe_gudang','deskripsi_gudang','alamat','luas_gudang','kategori']);

        $dataTambahan = [

            'id_gudang' => $this->gudang_h23->generateIdGudang(),

            'id_dealer' => $this->dealer->getCurrentUserDealer()->id_dealer

        ];

        $gudangData = array_merge($gudangData, $dataTambahan);



        $this->db->trans_start();

        $this->gudang_h23->insert($gudangData);

        $this->db->trans_complete();



        if ($this->db->trans_status()) {

			$_SESSION['pesan'] 	= "Data berhasil diperbarui.";

			$_SESSION['tipe'] 	= "info";

			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page/detail?k={$dataTambahan['id_gudang']}'>";

		}else{

			$_SESSION['pesan'] 	= "Data not found !";

			$_SESSION['tipe'] 	= "danger";

			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";

		}

    }



    public function detail()

    {

        $data['mode']  = 'detail';

        $data['set']   = "form";



        $gudang_h23 = $this->gudang_h23->get([
            'id_gudang' => $this->input->get('k'),
            'id_dealer' => $this->m_admin->cari_dealer()
        ], true);

        if (is_object($gudang_h23)) {

            $data['gudang_h23'] = $gudang_h23;

            $data['dealer'] = $this->dealer->find($gudang_h23->id_dealer, 'id_dealer');

            $this->template($data);

        } else {

            $_SESSION['pesan'] 	= "Data not found !";

            $_SESSION['tipe'] 	= "danger";

            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/sa_form'>";

        }

    }



    public function edit()

    {

        $data['set']	= "form";

        $data['mode']  = 'edit';

        $gudang_h23 = $this->gudang_h23->find($this->input->get('k'), 'id_gudang');

        $data['gudang_h23'] = $gudang_h23;

        $data['dealer'] = $this->dealer->find($gudang_h23->id_dealer, 'id_dealer');



        $this->template($data);

    }



    public function update()

    {

        $this->db->trans_start();

        $this->gudang_h23->update($this->input->post(['tipe_gudang','deskripsi_gudang','alamat','luas_gudang','kategori']), $this->input->post(['id_gudang']));

        $this->db->trans_complete();



        if ($this->db->trans_status()) {

            $_SESSION['pesan'] 	= "Data berhasil diperbarui.";

            $_SESSION['tipe'] 	= "info";

            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page/detail?k={$this->input->post('id_gudang')}'>";

        } else {

            $_SESSION['pesan'] 	= "Data not found !";

            $_SESSION['tipe'] 	= "danger";

            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";

        }

    }



    public function delete(){

        $delete = $this->gudang_h23->delete($this->input->get('k'), 'id_gudang');

        if ($delete) {

            $_SESSION['pesan'] 	= "Data berhasil dihapus.";

            $_SESSION['tipe'] 	= "info";

            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";

        } else {

            $_SESSION['pesan'] 	= "Data not found !";

            $_SESSION['tipe'] 	= "danger";

            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";

        }

    }

    

    protected function template($data)

    {

        $name = $this->session->userdata('nama');

        $data['isi']    = $this->page;

        $data['title']	= $this->title;



        if ($name=="") {

            echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";

        } else {

            $this->load->view('template/header', $data);

            $this->load->view('template/aside');

            $this->load->view($this->folder."/".$this->page);

            $this->load->view('template/footer');

        }

    }

}
