<?php

defined('BASEPATH') or exit('No direct script access allowed');

class h3_dealer_customer_h23 extends Honda_Controller
{
    public $folder = "dealer";
    public $page   = "h3_dealer_customer_h23";
    public $title  = "Customer H23";

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
        $this->load->model('h3_dealer_customer_h23_model', 'customer_h23');
        $this->load->model('kelurahan_model', 'kelurahan');
        $this->load->model('provinsi_model', 'provinsi');
        $this->load->model('kabupaten_model', 'kabupaten');
        $this->load->model('kecamatan_model', 'kecamatan');
        $this->load->model('kelurahan_model', 'kelurahan');
        $this->load->model('tipe_kendaraan_model', 'tipe_kendaraan');
        $this->load->model('warna_model', 'warna');
        $this->load->model('dealer_model', 'dealer');
    }

    public function index()
    {
        $data['set']	= "index";
        $data['customer_h23'] = $this->customer_h23->get([
            'id_dealer' => $this->m_admin->cari_dealer()
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

    public function save()
    {
        $this->validate();

        $data = $this->input->post([
            'nama_customer','no_identitas','jenis_identitas','no_hp',
            'alamat','id_provinsi','id_kabupaten','id_kecamatan','id_kelurahan',
            'id_tipe_kendaraan','id_warna','no_mesin','no_rangka','tahun_produksi',
            'no_polisi','no_spk', 'email','nama_stnk', 'tgl_pembelian'
        ]);

        $tahun_produksi = substr($data['tahun_produksi'], 0,4);

        //Cek tipe kendaraan
        $cek_tipe_kendaraan = $this->db->select('id_kategori')
                                       ->from('ms_tipe_kendaraan')
                                       ->where('id_tipe_kendaraan',$data['id_tipe_kendaraan'])
                                       ->get()->row_array(); 

        $is_ev = NULL;                               
        if($cek_tipe_kendaraan['id_kategori'] =='EV'){
            $is_ev = 1;
        }

        if($is_ev==1){
            $id_customer = $this->customer_h23->generateIdCustomerEV();
        }else{
            $id_customer = $this->customer_h23->generateIdCustomer();
        }

        $data = array_merge($data, [
            // 'id_customer' => $this->customer_h23->generateIdCustomer(),
            'id_customer' => $id_customer,
            'created_by' => $this->session->userdata('id_user'),
            'created_at' => date('Y-m-d H:i:s'),
            'id_dealer' => $this->m_admin->cari_dealer(),
            'tahun_produksi' => $tahun_produksi,
            'is_ev' => $is_ev
        ]);

        $this->db->trans_start();
        $this->customer_h23->insert($data);
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $result = $this->customer_h23->find($data['id_customer'], 'id_customer');
            send_json([
                'message' => 'Berhasil membuat customer',
                'payload' => $result,
                'redirect_url' => base_url(sprintf('dealer/h3_dealer_customer_h23/detail?k=%s', $data['id_customer']))
            ]);
        } else {
            send_json([
                'message' => 'Tidak berhasil membuat customer'
            ], 422);
        }
    }

    public function detail(){
        $data = [];
        $data['mode']  = 'detail';
        $data['set']   = "form";

        $data['customer_h23'] = $this->db
        ->select('c.*')
        ->select('kelurahan.id_kelurahan')
        ->select('kelurahan.kelurahan')
        ->select('kecamatan.id_kecamatan')
        ->select('kecamatan.kecamatan')
        ->select('kabupaten.id_kabupaten')
        ->select('kabupaten.kabupaten')
        ->select('provinsi.id_provinsi')
        ->select('provinsi.provinsi')
        ->select('w.id_warna')
        ->select('w.warna')
        ->from('ms_customer_h23 as c')
        ->join('ms_kelurahan as kelurahan', 'c.id_kelurahan = kelurahan.id_kelurahan','left')
        ->join('ms_kecamatan as kecamatan', 'kelurahan.id_kecamatan = kecamatan.id_kecamatan','left')
        ->join('ms_kabupaten as kabupaten', 'kecamatan.id_kabupaten = kabupaten.id_kabupaten','left')
        ->join('ms_provinsi as provinsi', 'kabupaten.id_provinsi = provinsi.id_provinsi','left')
        ->join('ms_warna as w', 'c.id_warna = w.id_warna', 'left')
        ->where('c.id_customer', $this->input->get('k'))
        ->limit(1)
        ->get()->row_array();

        $this->template($data);
    }

    public function edit()
    {
        $data['set']	= "form";
        $data['mode']  = 'edit';

        $data['customer_h23'] = $this->db
        ->select('c.*')
        ->select('kelurahan.id_kelurahan')
        ->select('kelurahan.kelurahan')
        ->select('kecamatan.id_kecamatan')
        ->select('kecamatan.kecamatan')
        ->select('kabupaten.id_kabupaten')
        ->select('kabupaten.kabupaten')
        ->select('provinsi.id_provinsi')
        ->select('provinsi.provinsi')
        ->select('w.id_warna')
        ->select('w.warna')
        ->from('ms_customer_h23 as c')
        ->join('ms_kelurahan as kelurahan', 'c.id_kelurahan = kelurahan.id_kelurahan','left')
        ->join('ms_kecamatan as kecamatan', 'kelurahan.id_kecamatan = kecamatan.id_kecamatan','left')
        ->join('ms_kabupaten as kabupaten', 'kecamatan.id_kabupaten = kabupaten.id_kabupaten','left')
        ->join('ms_provinsi as provinsi', 'kabupaten.id_provinsi = provinsi.id_provinsi','left')
        ->join('ms_warna as w', 'c.id_warna = w.id_warna', 'left')
        ->where('c.id_customer', $this->input->get('k'))
        ->limit(1)
        ->get()->row_array();

        $this->template($data);
    }

    public function update()
    {
        $this->validate();
        $customerData = $this->input->post([
            'nama_customer','no_identitas','jenis_identitas',
            'no_hp','alamat','id_provinsi',
            'id_kabupaten','id_kecamatan','id_kelurahan',
            'id_tipe_kendaraan','id_warna','no_mesin',
            'no_rangka','tahun_produksi','no_polisi','no_spk', 'email', 'tgl_pembelian','nama_stnk',
        ]);

        $tahun_produksi = substr($customerData['tahun_produksi'], 0,4);

        $customerData = array_merge($customerData, [
            'tahun_produksi' => $tahun_produksi
        ]);

        $this->db->trans_start();
        $this->customer_h23->update($customerData, $this->input->post(['id_customer']));
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $result = $this->customer_h23->get($this->input->post(['id_customer']), true);
            send_json([
                'message' => 'Berhasil memperbarui customer',
                'payload' => $result,
                'redirect_url' => base_url(sprintf('dealer/h3_dealer_customer_h23/detail?k=%s', $this->input->post('id_customer')))
            ]);
        } else {
            send_json([
                'message' => 'Tidak berhasil memperbarui customer'
            ], 422);
        }
    }

    public function delete()
    {
        $delete = $this->customer_h23->delete($this->input->get('k'), 'id_customer');
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

    public function validate(){
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('nama_customer', 'Nama Customer', 'required');
        $this->form_validation->set_rules('no_identitas', 'Nomor Identitas', 'required');
        $this->form_validation->set_rules('jenis_identitas', 'Jenis Identitas', 'required');
        $this->form_validation->set_rules('no_hp', 'Nomor Handphone', 'required|min_length[10]|max_length[15]');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('id_kelurahan', 'Kelurahan', 'required');
        $this->form_validation->set_rules('id_tipe_kendaraan', 'Tipe Kendaraan', 'required');
        $this->form_validation->set_rules('id_warna', 'Warna', 'required');
        $this->form_validation->set_rules('tgl_pembelian', 'Tgl Pembelian Motor', 'required');

        $array_rules_no_mesin = [ 'required','exact_length[12]'];
        // $array_rules_no_rangka = [ 'required' ,'exact_length[14]','callback_tidak_boleh_mh1'];
        $array_rules_no_rangka = [ 'required','min_length[17]'];

        if($this->input->post('jenis_identitas') == 'ktp'){
            $this->form_validation->set_rules('no_identitas', 'Nomor Identitas', 'required|min_length[16]|max_length[16]');
        }

        if($this->input->post('jenis_identitas') == 'sim'){
            $this->form_validation->set_rules('no_identitas', 'Nomor Identitas', 'required|min_length[12]|max_length[12]');
        }
        
        if($this->input->post('jenis_identitas') == 'kitap'){
            $this->form_validation->set_rules('no_identitas', 'Nomor Identitas', 'required|min_length[12]|max_length[16]');
        }
        
        if($this->uri->segment(3) == 'update'){
            $customer = $this->db
            ->select('c.no_mesin')
            ->select('c.no_rangka')
            ->from('ms_customer_h23 as c')
            ->where('c.id_customer', $this->input->post('id_customer'))
            ->get()->row_array();

            if($customer != null){
                if($customer['no_mesin'] != $this->input->post('no_mesin')){
                    $array_rules_no_mesin[] = 'is_unique[ms_customer_h23.no_mesin]';
                }

                if($customer['no_rangka'] != $this->input->post('no_rangka')){
                    $array_rules_no_rangka[] = 'is_unique[ms_customer_h23.no_rangka]';
                }
            }
        }

        if($this->uri->segment(3) == 'save'){
            //Cek apakah terdapat no mesin dan no rangka yang sama terdaftar 
            $customer_nosin = $this->db
                ->select('c.no_mesin')
                ->select('c.no_rangka')
                ->from('ms_customer_h23 as c')
                ->where('c.no_mesin', $this->input->post('no_mesin'))
                ->get()->row_array();

            if(isset($customer_nosin)){
                    $array_rules_no_mesin[] = 'is_unique[ms_customer_h23.no_mesin]';
            }

            $customer_norang = $this->db
                ->select('c.no_mesin')
                ->select('c.no_rangka')
                ->from('ms_customer_h23 as c')
                ->where('c.no_rangka', $this->input->post('no_rangka'))
                ->get()->row_array();

            if(isset($customer_norang)){
                    $array_rules_no_rangka[] = 'is_unique[ms_customer_h23.no_rangka]';
            }
        }

        $this->form_validation->set_rules('no_mesin', 'Nomor Mesin', $array_rules_no_mesin, [
            'is_unique' => 'No. mesin sudah terdaftar.'
        ]);
        $this->form_validation->set_rules('no_rangka', 'Nomor Rangka', $array_rules_no_rangka, [
            'is_unique' => 'No. rangka sudah terdaftar.'
        ]);
        // $this->form_validation->set_rules('tahun_produksi', 'Tahun Produksi', 'required|numeric|min_length[4]|max_length[4]');
        $this->form_validation->set_rules('tahun_produksi', 'Tahun Produksi', 'required');
        $this->form_validation->set_rules('no_polisi', 'Nomor Polisi', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');

        if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
    }

    public function tidak_boleh_mh1($str) {
        $str = strtoupper($str);
        if (strpos($str, 'MH1') === 0) { // Mengecek apakah 'MH1' adalah awalan
            $this->form_validation->set_message('tidak_boleh_mh1', '{field} tidak boleh berawalan MH1');
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
