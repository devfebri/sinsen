<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_dealer_lokasi_rak_bin extends Honda_Controller
{
    public $folder = "dealer";
    public $page   = "h3_dealer_lokasi_rak_bin";
    public $title  = "Lokasi Rak Bin H23";

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
        $this->load->library('form_validation');
        $this->load->model('h3_dealer_lokasi_rak_bin_model', 'lokasi_rak_bin');
        $this->load->model('h3_dealer_gudang_h23_model', 'gudang_h23');
    }

    public function index()
    {
        $data['set']    = "index";
        $data['lokasi_rak_bin'] = $this->lokasi_rak_bin->get([
            'id_dealer' => $this->m_admin->cari_dealer(),
        ]);
        $this->template($data);
    }

    public function add()
    {
        $data['mode']    = 'insert';
        $data['set']     = "form";
        $data['gudang_h23'] = $this->gudang_h23->get(['id_dealer' => $this->m_admin->cari_dealer()]);

        $this->template($data);
    }

    public function save()
    {
        $this->validate();

        $data = array_merge($this->input->post(['id_rak', 'deskripsi_rak', 'unit', 'id_gudang']), [
            'id_dealer' => $this->m_admin->cari_dealer(),
        ]);

        $this->db->trans_start();

        $this->lokasi_rak_bin->insert($data);
        $id = $this->db->insert_id();

        $this->db->trans_complete();

        $lokasi_rak_bin = (array) $this->lokasi_rak_bin->find($id);

        if ($this->db->trans_status() and $lokasi_rak_bin != null) {
            $message = 'Data berhasil disimpan';
            $this->session->userdata('pesan', $message);
            $this->session->userdata('tipe', 'info');

            send_json([
                'message' => $message,
                'payload' => $lokasi_rak_bin,
                'redirect_url' => base_url("dealer/{$this->page}/detail?k={$lokasi_rak_bin['id']}")
            ]);
        } else {
            send_json([
                'message' => 'Data tidak berhasil disimpan'
            ], 422);
        }
    }

    public function detail()
    {
        $data['mode']  = 'detail';
        $data['set']   = "form";
        $data['gudang_h23'] = $this->gudang_h23->get([
            'id_dealer' => $this->m_admin->cari_dealer()
        ]);

        $lokasi_rak_bin = (array) $this->lokasi_rak_bin->find($this->input->get('k'), 'id');

        if ($lokasi_rak_bin == null) {
            $this->session->userdata('pesan', 'Rak tidak ditemukan');
            $this->session->userdata('tipe', 'danger');
            redirect(
                base_url("dealer/{$this->page}")
            );
        }

        $data['lokasi_rak_bin'] = $lokasi_rak_bin;
        $this->template($data);
    }

    public function edit()
    {
        $data['set']    = "form";
        $data['mode']  = 'edit';
        $data['gudang_h23'] = $this->gudang_h23->get([
            'id_dealer' => $this->m_admin->cari_dealer()
        ]);

        $lokasi_rak_bin = (array) $this->lokasi_rak_bin->find($this->input->get('k'), 'id');

        if ($lokasi_rak_bin == null) {
            $this->session->userdata('pesan', 'Rak tidak ditemukan');
            $this->session->userdata('tipe', 'danger');
            redirect(
                base_url("dealer/{$this->page}")
            );
        }

        $data['lokasi_rak_bin'] = $lokasi_rak_bin;
        $this->template($data);
    }

    public function update()
    {
        $this->validate();

        $this->db->trans_start();
        $data = $this->input->post(['deskripsi_rak', 'unit', 'id_gudang']);
        $this->lokasi_rak_bin->update($data, $this->input->post(['id']));
        $this->db->trans_complete();

        $lokasi_rak_bin = (array) $this->lokasi_rak_bin->find($this->input->post('id'));
        if ($this->db->trans_status() and $lokasi_rak_bin != null) {
            $message = 'Data berhasil diperbarui.';
            $this->session->userdata('pesan', $message);
            $this->session->userdata('tipe', 'info');

            send_json([
                'message' => $message,
                'payload' => $lokasi_rak_bin,
                'redirect_url' => base_url("dealer/{$this->page}/detail?k={$lokasi_rak_bin['id']}")
            ]);
        } else {
            send_json([
                'message' => 'Data tidak berhasil diperbarui'
            ], 422);
        }
    }

    public function delete()
    {
        $delete = $this->lokasi_rak_bin->delete($this->input->get('k'), 'id');
        if ($delete) {
            $this->session->userdata('pesan', 'Data berhasil dihapus.');
            $this->session->userdata('tipe', 'info');
        } else {
            $this->session->userdata('pesan', 'Data not found !');
            $this->session->userdata('tipe', 'danger');
        }

        redirect(
            base_url("dealer/{$this->page}")
        );
    }

    public function get()
    {
        $rak = $this->lokasi_rak_bin->get($this->input->get(['id_gudang']));
        send_json($rak);
    }

    public function cetak_barcode()
    {
        $dealer =   $this->db->query("select a.id_dealer,a.nama_dealer,b.id_karyawan_dealer,c.id_user from ms_dealer a 
        join ms_karyawan_dealer b on a.id_dealer=b.id_dealer join ms_user c on c.id_karyawan_dealer=b.id_karyawan_dealer where c.id_user='{$_SESSION['id_user']}'")->row();

        $data['rak'] = $this->db->query("SELECT * FROM ms_lokasi_rak_bin where id_dealer='$dealer->id_dealer'")->result();
        $data['dealer'] = $dealer->id_dealer;
        $data['nama_dealer'] = $dealer->nama_dealer;
        $this->load->library('pdf');
        $mpdf                           = $this->pdf->load();
        $mpdf->allow_charset_conversion = false;  // Set by default to TRUE
        $mpdf->charset_in               = 'UTF-8';
        $mpdf->autoLangToFont           = true;

        $html = $this->load->view('dealer/cetak_barcode_rak', $data, true);

        $mpdf->WriteHTML($html);
        $output = $this->page . '.pdf';
        $mpdf->Output("$output", 'I');
    }

    public function cetak_barcode_id()
    {
        $dealer =  $this->db->query("select a.id_dealer,b.id_karyawan_dealer,c.id_user from ms_dealer a 
        join ms_karyawan_dealer b on a.id_dealer=b.id_dealer join ms_user c on c.id_karyawan_dealer=b.id_karyawan_dealer where c.id_user='{$_SESSION['id_user']}'")->row()->id_dealer;
    }

    public function validate()
    {
        $this->form_validation->set_error_delimiters('', '');
        if (!$this->uri->segment(3) == 'update') {
            $this->form_validation->set_rules('id_rak', 'Kode Rak', 'required|is_unique[ms_lokasi_rak_bin.id_rak]');
        }
        $this->form_validation->set_rules('deskripsi_rak', 'Deskripsi Rak', 'required');
        $this->form_validation->set_rules('unit', 'Unit', 'required|numeric');
        $this->form_validation->set_rules('id_gudang', 'Gudang', 'required');

        if (!$this->form_validation->run()) {
            send_json([
                'error_type' => 'validation_error',
                'message' => 'Data tidak valid',
                'errors' => $this->form_validation->error_array()
            ], 422);
        }
    }
}
