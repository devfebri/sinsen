<?php

defined('BASEPATH') or exit('No direct script access allowed');

class h3_dealer_request_document extends Honda_Controller
{
    public $folder = "dealer";
    public $page   = "h3_dealer_request_document";
    public $title  = "Request Document";

    public function __construct()
    {
        parent::__construct();

        //===== Load Database =====
        $this->load->database();
        $this->load->helper('url');
        //===== Load Model =====
        $this->load->model('m_admin');
        $this->load->library('form_validation');
        $this->load->model('customer_model', 'customer');
        $this->load->model('h3_dealer_request_document_model', 'request_document');
        $this->load->model('h3_dealer_request_document_parts_model', 'request_document_parts');
        $this->load->model('h3_dealer_claim_c1_c2_model', 'claim_c1_c2');
        $this->load->model('h3_dealer_non_claim_model', 'non_claim');
        $this->load->model('h3_dealer_pemesan_request_hotline_model', 'pemesan_request_hotline');
        // $this->load->model('dealer_model', 'dealer');
    }

    public function index()
    {
        $data['set']    = "index";
        $data['request_document'] = $this->request_document->get([
            'id_dealer' => $this->m_admin->cari_dealer(),
        ]);
        $this->template($data);
    }

    public function add()
    {
        $data['mode']    = 'insert';
        $data['set']     = "form";

        $this->template($data);
    }

    public function validate()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('id_customer', 'Customer', 'required');
        $this->form_validation->set_rules('no_rangka', 'Nomor Rangka', 'required');
        $this->form_validation->set_rules('kelurahan', 'Kelurahan', 'required');
        $this->form_validation->set_rules('no_mesin', 'Nomor Mesin', 'required');
        $this->form_validation->set_rules('no_hp_customer', 'Nomor Telepon Customer', 'required|numeric');
        // $this->form_validation->set_rules('uang_muka', 'Uang Muka', 'required|greater_than[0]');
        $this->form_validation->set_rules('parts', 'Parts',  array(
            array(
                'check_parts',
                function ($value) {
                    $valid = count($this->input->post('parts')) > 0;

                    if (!$valid) {
                        $this->form_validation->set_message('check_parts', '{field} must be filled at least one.');
                    }

                    return $valid;
                }
            )
        ));

        if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
        }
    }

    public function save()
    {
        $this->validate();

        $requestDocumentData = $this->input->post([
            'id_customer',
            'id_sa_form',
            'penomoran_ulang',
            'tipe_penomoran_ulang',
            'form_warranty_claim_c2_c2',
            'copy_faktur_ahm_claim_c1_c2',
            'gesekan_nomor_framebody_claim_c1_c2',
            'gesekan_nomor_crankcase_claim_c1_c2',
            'copy_ktp_claim_c1_c2',
            'copy_stnk_claim_c1_c2',
            'copy_bpkb_faktur_ahm_non_claim',
            'copy_stnk_non_claim',
            'copy_ktp_non_claim',
            'gesekan_nomor_framebody_non_claim',
            'gesekan_nomor_crankcase_non_claim',
            'potongan_no_rangka_mesin_non_claim',
            'surat_permohonan_penomoran_ulang_dari_kepolisian_non_claim',
            'surat_laporan_forensik_kepolisian_non_claim',
            'vor',
            'job_return_flag',
            'ada_keterangan_tambahan',
            'keterangan_tambahan',
            'uang_muka',
            'masukkan_pemesan',
            'order_to',
            'no_claim_c2',
        ]);

        $requestDocumentData = array_merge($requestDocumentData, [
            'id_booking' => $this->request_document->generateIdBooking(),
            'id_dealer' => $this->m_admin->cari_dealer()
        ]);

        $requestDocumentData = $this->clean_data($requestDocumentData);

        $requestDocumentPartsData = $this->getOnly([
            'id_part', 'harga_saat_dibeli', 'kuantitas', 'eta_terlama', 'eta_revisi','eta_tercepat'
        ], $this->input->post('parts'), [
            'id_booking' => $requestDocumentData['id_booking']
        ]);

        $no_claim_c2 = $this->input->post('no_claim_c2');
        if($no_claim_c2 == ''){
            $this->form_validation->set_rules('uang_muka', 'Uang Muka', 'required|greater_than[0]');
            if (!$this->form_validation->run()) {
                send_json([
                    'error_type' => 'validation_error',
                    'message' => 'Data tidak valid',
                    'errors' => $this->form_validation->error_array()
                ], 422);
            }
        }

        $this->db->trans_start();

        $this->update_customer([
            'no_hp' => $this->input->post('no_hp_customer'), 
            'no_rangka' => $this->input->post('no_rangka'), 
            'no_mesin' => $this->input->post('no_mesin'), 
            'id_kelurahan' => $this->input->post('id_kelurahan')
        ], $this->input->post('id_customer'));

        if ($this->input->post('masukkan_pemesan') == '1') {
            $this->pemesan_request_hotline->insert([
                'id_customer' => $this->input->post('id_customer'),
                'nama' => $this->input->post('nama_pemesan'),
                'no_hp' => $this->input->post('no_hp'),
                'tanggal_pesan' => date('Y-m-d', time())
            ]);
        }

        $requestDocumentData['id_data_pemesan'] = $this->db->insert_id();

        $this->request_document->insert($requestDocumentData);
        $this->request_document_parts->insert_batch($requestDocumentPartsData);

        $this->db->trans_complete();

        $request_document = (array) $this->request_document->find($requestDocumentData['id_booking'], 'id_booking');
        if ($this->db->trans_status() AND $request_document != null) {
            send_json([
                'message' => 'Berhasil membuat request document',
                'payload' => $request_document,
                'redirect_url' => base_url('dealer/h3_dealer_request_document/detail?k=' . $request_document['id_booking'])
            ]);
        } else {
            send_json([
                'message' => 'Tidak berhasil membuat request document'
            ], 422);
        }
    }

    public function detail()
    {
        $data['mode']  = 'detail';
        $data['set']   = "form";

        $data['request_document'] = $this->db
        ->select('rd.status')
        ->select('rd.id_booking')
        ->select('rd.id_customer')
        ->select('c.nama_customer')
        ->select('c.no_identitas')
        ->select('c.no_hp as no_hp_customer')
        ->select('kel.kelurahan')
        ->select('kel.id_kelurahan')
        ->select('kec.kecamatan')
        ->select('kab.kabupaten')
        ->select('prov.provinsi')
        ->select('c.alamat')
        ->select('c.no_polisi')
        ->select('tk.tipe_ahm as tipe_kendaraan')
        ->select('tk.deskripsi_ahm as deskripsi_unit')
        ->select('w.warna as deskripsi_warna')
        ->select('c.no_mesin')
        ->select('c.no_rangka')
        ->select('c.tahun_produksi')
        ->select('rd.id_data_pemesan')
        ->select('rd.masukkan_pemesan')
        ->select('prh.nama as nama_pemesan')
        ->select('prh.no_hp')
        ->select('sa_form.id_sa_form')
        ->select('wo.id_work_order')
        ->select('sa_form.no_buku_claim_c2')
        ->select('sa_form.no_claim_c2')
        ->select('rd.penomoran_ulang')
        ->select('rd.form_warranty_claim_c2_c2')
        ->select('rd.copy_faktur_ahm_claim_c1_c2')
        ->select('rd.gesekan_nomor_framebody_claim_c1_c2')
        ->select('rd.gesekan_nomor_crankcase_claim_c1_c2')
        ->select('rd.copy_ktp_claim_c1_c2')
        ->select('rd.copy_stnk_claim_c1_c2')
        ->select('rd.copy_bpkb_faktur_ahm_non_claim')
        ->select('rd.copy_stnk_non_claim')
        ->select('rd.copy_ktp_non_claim')
        ->select('rd.gesekan_nomor_framebody_non_claim')
        ->select('rd.gesekan_nomor_crankcase_non_claim')
        ->select('rd.potongan_no_rangka_mesin_non_claim')
        ->select('rd.surat_permohonan_penomoran_ulang_dari_kepolisian_non_claim')
        ->select('rd.surat_laporan_forensik_kepolisian_non_claim')
        ->select('rd.tipe_penomoran_ulang')
        ->select('rd.vor')
        ->select('rd.uang_muka')
        ->select('rd.job_return_flag')
        ->select('rd.ada_keterangan_tambahan')
        ->select('rd.keterangan_tambahan')
        ->select('order_to.nama_dealer as nama_dealer_terdekat')
        ->select('rd.order_to')
        ->select('uj.no_inv_uang_jaminan')
        ->select('po.po_id')
        ->from('tr_h3_dealer_request_document as rd')
        ->join('tr_h3_dealer_purchase_order as po', '(po.id_booking = rd.id_booking and po.status != "Canceled" and po.status != "Rejected")', 'left')
        ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer')
        // ->join('ms_kelurahan as kel', 'kel.id_kelurahan = c.id_kelurahan', 'left')
        // ->join('ms_kecamatan as kec', 'kec.id_kecamatan = c.id_kecamatan', 'left')
        // ->join('ms_kabupaten as kab', 'kab.id_kabupaten = c.id_kabupaten', 'left')
        // ->join('ms_provinsi as prov', 'prov.id_provinsi = c.id_provinsi', 'left')
        ->join('ms_kelurahan as kel', 'kel.id_kelurahan = c.id_kelurahan', 'left')
        ->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
        ->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
        ->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi', 'left')
        ->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = c.id_tipe_kendaraan', 'left')
        ->join('ms_warna as w', 'w.id_warna = c.id_warna', 'left')
        ->join('tr_h3_dealer_pemesan_request_hotline as prh', 'prh.id = rd.id_data_pemesan', 'left')
        ->join('tr_h2_sa_form as sa_form', 'sa_form.id_sa_form = rd.id_sa_form', 'left')
        ->join('tr_h2_wo_dealer as wo', 'wo.id_sa_form = sa_form.id_sa_form', 'left')
        ->join('ms_dealer as order_to', 'order_to.id_dealer = rd.order_to', 'left')
        ->join('tr_h2_uang_jaminan as uj', 'uj.id_booking = rd.id_booking', 'left')
        ->where('rd.id_booking', $this->input->get('k'))
        ->get()->row();

        $parts = $this->db
        ->select('trdp.*')
        ->select('p.nama_part')
        ->select('p.import_lokal')
        ->select('p.current')
        ->select('p.hoo_flag')
        ->select('p.hoo_max')
        ->select('(CASE WHEN trdp.alasan_part_revisi_md = "discontinue" THEN "Discontinue" WHEN trdp.alasan_part_revisi_md = "part_set" THEN "Part Set" WHEN trdp.alasan_part_revisi_md = "supersede" THEN "Supersede" WHEN trdp.alasan_part_revisi_md = "lainnya" THEN "Lainnya" else "-" end) as alasan_part_revisi_md')
        ->from('tr_h3_dealer_request_document_parts as trdp')
        ->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = trdp.id_booking')
        ->join('ms_part as p', 'p.id_part = trdp.id_part')
        ->where('trdp.id_booking', $this->input->get('k'))
        ->get()->result_array();

        $parts = array_map(function($row){
            $row['eta_revisi'] = null;
            return $row;
        }, $parts);
        
        $data['parts'] = $parts;

        $this->template($data);
    }

    public function edit()
    {
        $data['set']    = "form";
        $data['mode']  = 'edit';
        $data['request_document'] = $this->db
        ->select('rd.status')
        ->select('rd.id_booking')
        ->select('rd.id_customer')
        ->select('c.nama_customer')
        ->select('c.no_identitas')
        ->select('c.no_hp as no_hp_customer')
        ->select('kel.kelurahan')
        ->select('kel.id_kelurahan')
        ->select('kec.kecamatan')
        ->select('kab.kabupaten')
        ->select('prov.provinsi')
        ->select('c.alamat')
        ->select('c.no_polisi')
        ->select('tk.tipe_ahm as tipe_kendaraan')
        ->select('tk.deskripsi_ahm as deskripsi_unit')
        ->select('w.warna as deskripsi_warna')
        ->select('c.no_mesin')
        ->select('c.no_rangka')
        ->select('c.tahun_produksi')
        ->select('rd.id_data_pemesan')
        ->select('rd.masukkan_pemesan')
        ->select('prh.nama as nama_pemesan')
        ->select('prh.no_hp')
        ->select('sa_form.id_sa_form')
        ->select('wo.id_work_order')
        ->select('sa_form.no_buku_claim_c2')
        ->select('sa_form.no_claim_c2')
        ->select('rd.penomoran_ulang')
        ->select('rd.form_warranty_claim_c2_c2')
        ->select('rd.copy_faktur_ahm_claim_c1_c2')
        ->select('rd.gesekan_nomor_framebody_claim_c1_c2')
        ->select('rd.gesekan_nomor_crankcase_claim_c1_c2')
        ->select('rd.copy_ktp_claim_c1_c2')
        ->select('rd.copy_stnk_claim_c1_c2')
        ->select('rd.copy_bpkb_faktur_ahm_non_claim')
        ->select('rd.copy_stnk_non_claim')
        ->select('rd.copy_ktp_non_claim')
        ->select('rd.gesekan_nomor_framebody_non_claim')
        ->select('rd.gesekan_nomor_crankcase_non_claim')
        ->select('rd.potongan_no_rangka_mesin_non_claim')
        ->select('rd.surat_permohonan_penomoran_ulang_dari_kepolisian_non_claim')
        ->select('rd.surat_laporan_forensik_kepolisian_non_claim')
        ->select('rd.tipe_penomoran_ulang')
        ->select('rd.vor')
        ->select('rd.uang_muka')
        ->select('rd.job_return_flag')
        ->select('rd.ada_keterangan_tambahan')
        ->select('rd.keterangan_tambahan')
        ->select('order_to.nama_dealer as nama_dealer_terdekat')
        ->select('rd.order_to')
        ->select('uj.no_inv_uang_jaminan')
        ->select('po.po_id')
        ->from('tr_h3_dealer_request_document as rd')
        ->join('tr_h3_dealer_purchase_order as po', '(po.id_booking = rd.id_booking and po.status != "Canceled" and po.status != "Rejected")', 'left')
        ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer')
        // ->join('ms_kelurahan as kel', 'kel.id_kelurahan = c.id_kelurahan', 'left')
        // ->join('ms_kecamatan as kec', 'kec.id_kecamatan = c.id_kecamatan', 'left')
        // ->join('ms_kabupaten as kab', 'kab.id_kabupaten = c.id_kabupaten', 'left')
        // ->join('ms_provinsi as prov', 'prov.id_provinsi = c.id_provinsi', 'left')
        ->join('ms_kelurahan as kel', 'kel.id_kelurahan = c.id_kelurahan', 'left')
        ->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
        ->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
        ->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi', 'left')
        ->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = c.id_tipe_kendaraan', 'left')
        ->join('ms_warna as w', 'w.id_warna = c.id_warna', 'left')
        ->join('tr_h3_dealer_pemesan_request_hotline as prh', 'prh.id = rd.id_data_pemesan', 'left')
        ->join('tr_h2_sa_form as sa_form', 'sa_form.id_sa_form = rd.id_sa_form', 'left')
        ->join('tr_h2_wo_dealer as wo', 'wo.id_sa_form = sa_form.id_sa_form', 'left')
        ->join('ms_dealer as order_to', 'order_to.id_dealer = rd.order_to', 'left')
        ->join('tr_h2_uang_jaminan as uj', 'uj.id_booking = rd.id_booking', 'left')
        ->where('rd.id_booking', $this->input->get('k'))
        ->get()->row();

        $eta_revisi = $this->db
		->select('hewh.eta')
		->from('tr_h3_md_history_estimasi_waktu_hotline as hewh')
		->where('hewh.po_id = po.po_id')
		->where('hewh.id_part = trdp.id_part')
		->where('hewh.source', 'upload_revisi')
		->order_by('hewh.created_at', 'desc')
		->limit(1)
		->get_compiled_select();

        $parts = $this->db
        ->select('trdp.*')
        ->select('p.nama_part')
        ->select('p.current')
        ->select('p.import_lokal')
        ->select('p.hoo_flag')
        ->select('p.hoo_max')
		// ->select("({$eta_revisi}) as eta_revisi", false)
        ->from('tr_h3_dealer_request_document_parts as trdp')
        ->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = trdp.id_booking')
        ->join('tr_h3_dealer_purchase_order as po', 'po.id_booking = rd.id_booking', 'left')
        ->join('ms_part as p', 'p.id_part = trdp.id_part')
        ->where('trdp.id_booking', $this->input->get('k'))
        ->get()->result_array();

        $parts = array_map(function($row){
            $row['eta_revisi'] = null;
            return $row;
        }, $parts);

        $data['parts'] = $parts;
        
        $this->template($data);
    }

    public function update()
    {
        $this->validate();

        $requestDocumentData = $this->input->post([
            'id_customer',
            'id_sa_form',
            'penomoran_ulang',
            'tipe_penomoran_ulang',
            'form_warranty_claim_c2_c2',
            'copy_faktur_ahm_claim_c1_c2',
            'gesekan_nomor_framebody_claim_c1_c2',
            'gesekan_nomor_crankcase_claim_c1_c2',
            'copy_ktp_claim_c1_c2',
            'copy_stnk_claim_c1_c2',
            'copy_bpkb_faktur_ahm_non_claim',
            'copy_stnk_non_claim',
            'copy_ktp_non_claim',
            'gesekan_nomor_framebody_non_claim',
            'gesekan_nomor_crankcase_non_claim',
            'potongan_no_rangka_mesin_non_claim',
            'surat_permohonan_penomoran_ulang_dari_kepolisian_non_claim',
            'surat_laporan_forensik_kepolisian_non_claim',
            'vor',
            'job_return_flag',
            'ada_keterangan_tambahan',
            'keterangan_tambahan',
            'uang_muka',
            'masukkan_pemesan',
            'order_to',
            'no_claim_c2',
        ]);

        $requestDocumentData = $this->clean_data($requestDocumentData);

        $requestDocumentPartsData = $this->getOnly([
            'id_part', 'harga_saat_dibeli', 'kuantitas', 'eta_terlama', 'eta_revisi','eta_tercepat'
        ], $this->input->post('parts'), $this->input->post(['id_booking']));

        $no_claim_c2 = $this->input->post('no_claim_c2');
        
        if($no_claim_c2 == ''){
            $this->form_validation->set_rules('uang_muka', 'Uang Muka', 'required|greater_than[0]');
            if (!$this->form_validation->run()) {
                send_json([
                    'error_type' => 'validation_error',
                    'message' => 'Data tidak valid',
                    'errors' => $this->form_validation->error_array()
                ], 422);
            }
        }
        
        $this->db->trans_start();

        $this->update_customer([
            'no_hp' => $this->input->post('no_hp_customer'), 
            'no_rangka' => $this->input->post('no_rangka'), 
            'no_mesin' => $this->input->post('no_mesin'),
            'id_kelurahan' => $this->input->post('id_kelurahan')
        ], $this->input->post('id_customer'));

        if ($this->input->post('masukkan_pemesan') == '1') {
            $pemesan = $this->db
            ->from('tr_h3_dealer_request_document as rd')
            ->where('rd.id_booking', $this->input->post('id_booking'))
            ->get()->row();

            if($pemesan->id_data_pemesan != 0){
                $this->pemesan_request_hotline->update([
                    'nama' => $this->input->post('nama_pemesan'),
                    'no_hp' => $this->input->post('no_hp'),
                    'id_customer' => $this->input->post('id_customer'),
                ], [
                    'id' => $this->input->post('id_data_pemesan')
                ]);
            }else{
                $this->pemesan_request_hotline->insert([
                    'id_customer' => $this->input->post('id_customer'),
                    'nama' => $this->input->post('nama_pemesan'),
                    'no_hp' => $this->input->post('no_hp'),
                    'tanggal_pesan' => date('Y-m-d', time())
                ]);

                $requestDocumentData['id_data_pemesan'] = $this->db->insert_id();
            }
        }

        $this->request_document->update($requestDocumentData, $this->input->post(['id_booking']));
        $this->request_document_parts->update_batch($requestDocumentPartsData, $this->input->post(['id_booking']));

        $this->db->trans_complete();

        $request_document = (array) $this->request_document->get($this->input->post(['id_booking']), true);
        if ($this->db->trans_status() AND $request_document != null) {
            send_json([
                'message' => 'Berhasil memperbarui request document',
                'payload' => $request_document,
                'redirect_url' => base_url('dealer/h3_dealer_request_document/detail?k=' . $request_document['id_booking'])
            ]);
        } else {
            send_json([
                'message' => 'Tidak berhasil memperbarui request document',
            ], 422);
        }
    }

    public function process_dp(){
        // $this->db->trans_start();
        // $this->request_document->update([
        //     'status' => 'Process DP'
        // ], [
        //     'id_booking' => $this->input->get('k'), 
        // ]);
        // $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $this->session->set_flashdata('pesan', 'Request document berhasil diproses DP');
            $this->session->set_flashdata('tipe', 'info');
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page/detail?k={$this->input->get('k')}'>";
        } else {
            $this->session->set_flashdata('pesan', 'Request document tidak berhasil diproses DP');
            $this->session->set_flashdata('tipe', 'info');
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";
        }
    }

    public function cancel(){
        $this->db->trans_start();
        $this->request_document->update([
            'status' => 'Canceled'
        ], [
            'id_booking' => $this->input->get('k'), 
        ]);
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $this->session->set_flashdata('pesan', 'Request document berhasil dibatalkan');
            $this->session->set_flashdata('tipe', 'info');
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page/detail?k={$this->input->get('k')}'>";
        } else {
            $this->session->set_flashdata('pesan', 'Request document tidak berhasil dibatalkan');
            $this->session->set_flashdata('tipe', 'info');
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";
        }
    }

    public function getRequestDocumentParts()
    {
        $query = $this->db
            ->select('trdp.*, mp.nama_part,mp.current,mp.hoo_flag,mp.hoo_max,mp.import_lokal')
            ->select('mp.id_part_int')
            ->select("0 as diskon_value")
            ->select("'' as tipe_diskon")
            ->select("0 as diskon_value_campaign")
            ->select("'' as tipe_diskon_campaign")
            ->select("
            case 
                when etd.id is not null then 
                    case
                        when (rd.penomoran_ulang = '1' AND rd.tipe_penomoran_ulang = 'claim_c1_c2') then date_format(adddate(now(), (etd.ahm_md + etd.proses_md + etd.md_d + etd.rc)), '%Y-%m-%d')
                        when (rd.penomoran_ulang = '1' AND rd.tipe_penomoran_ulang = 'non_claim') then date_format(adddate(now(), (etd.ahm_md + etd.proses_md + etd.md_d + etd.rn)), '%Y-%m-%d')
                        when (mp.import_lokal = 'N' AND mp.current = 'C') then date_format(adddate(now(), (etd.ahm_md + etd.proses_md + etd.md_d + etd.lc)), '%Y-%m-%d')
                        when (mp.import_lokal = 'N' AND mp.current = 'N') then date_format(adddate(now(), (etd.ahm_md + etd.proses_md + etd.md_d + etd.ln)), '%Y-%m-%d')
                        when (mp.import_lokal = 'Y' AND mp.current = 'C') then date_format(adddate(now(), (etd.ahm_md + etd.proses_md + etd.md_d + etd.ic)), '%Y-%m-%d')
                        when (mp.import_lokal = 'Y' AND mp.current = 'N') then date_format(adddate(now(), (etd.ahm_md + etd.proses_md + etd.md_d + etd.in)), '%Y-%m-%d')
                    end
                else 
                    case
                        when (rd.penomoran_ulang = '1' AND rd.tipe_penomoran_ulang = 'claim_c1_c2') then date_format(adddate(now(), (1 + 1 + 1 + 14)), '%Y-%m-%d')
                        when (rd.penomoran_ulang = '1' AND rd.tipe_penomoran_ulang = 'non_claim') then date_format(adddate(now(), (1 + 1 + 1 + 21)), '%Y-%m-%d')
                        when (mp.import_lokal = 'N' AND mp.current = 'C') then date_format(adddate(now(), (3 + 2)), '%Y-%m-%d')
                        when (mp.import_lokal = 'N' AND mp.current = 'N') then date_format(adddate(now(), (3 + 4)), '%Y-%m-%d')
                        when (mp.import_lokal = 'Y' AND mp.current = 'C') then date_format(adddate(now(), (3 + 22)), '%Y-%m-%d')
                        when (mp.import_lokal = 'Y' AND mp.current = 'N') then date_format(adddate(now(), (3 + 44)), '%Y-%m-%d')
                    end
            end as eta_terlama
        ")
            ->select("
        case 
            when etd.id is not null then 
                case
                    when (rd.penomoran_ulang = '1' AND rd.tipe_penomoran_ulang = 'claim_c1_c2') then date_format(adddate(now(), (etd.proses_md + etd.md_d)), '%Y-%m-%d')
                    when (rd.penomoran_ulang = '1' AND rd.tipe_penomoran_ulang = 'non_claim') then date_format(adddate(now(), (etd.proses_md + etd.md_d)), '%Y-%m-%d')
                    when (mp.import_lokal = 'N' AND mp.current = 'C') then date_format(adddate(now(), (etd.proses_md + etd.md_d)), '%Y-%m-%d')
                    when (mp.import_lokal = 'N' AND mp.current = 'N') then date_format(adddate(now(), (etd.proses_md + etd.md_d)), '%Y-%m-%d')
                    when (mp.import_lokal = 'Y' AND mp.current = 'C') then date_format(adddate(now(), (etd.proses_md + etd.md_d)), '%Y-%m-%d')
                    when (mp.import_lokal = 'Y' AND mp.current = 'N') then date_format(adddate(now(), (etd.proses_md + etd.md_d)), '%Y-%m-%d')
                end
            else 
                date_format(adddate(now(), 2), '%Y-%m-%d')
        end as eta_tercepat
        ")
            ->from('tr_h3_dealer_request_document_parts as trdp')
            ->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = trdp.id_booking')
            ->join('ms_part as mp', 'mp.id_part = trdp.id_part')
            ->join('ms_h3_md_estimated_time_delivery_items as etdi', "etdi.id_dealer = {$this->m_admin->cari_dealer()}", 'left')
            ->join('ms_h3_md_estimated_time_delivery as etd', 'etd.id = etdi.id_etd', 'left')
            ->where('trdp.id_booking', $this->input->get('id_booking'))
            ->get()->result()
            ;
        send_json($query);
    }

    public function update_eta_parts()
    {
        $this->load->model('h3_md_etd_model', 'etd');
        $result = [];
        foreach ($this->input->post('parts') as $part) {
            $data = $this->etd->get_estimated_time_delivery($part, $this->input->post('claim'), $this->input->post('tipe_claim'), $this->m_admin->cari_dealer());
            if($data != null){
                $result[] = $data;
            }
        }
        
        send_json($result);
    }

    public function cetak()
	{
		$this->load->library('mpdf_l');
		$mpdf                           = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in               = 'UTF-8';
        $mpdf->autoLangToFont           = true;

        $perkiraan_hari = $this->db
        ->select('rdp.eta_terlama')
        ->from('tr_h3_dealer_request_document_parts as rdp')
        ->where('rdp.id_booking', $this->input->get('k'))
        ->order_by('rdp.eta_terlama', 'asc')
        ->limit(1)
        ->get_compiled_select();
        
        $total_pembayaran = $this->db
        ->select('
            sum( (rdp.kuantitas * rdp.harga_saat_dibeli) )
        ')
        ->from('tr_h3_dealer_request_document_parts as rdp')
        ->where('rdp.id_booking', $this->input->get('k'))
        ->get_compiled_select();

        $data['request_document'] = $this->db
        ->select('rd.*')
        ->select('d.nama_dealer as nama_jaringan')
        ->select('d.alamat as alamat_jaringan')
        ->select('"-" as fax_jaringan')
        ->select('d.no_telp as no_telp_jaringan')
        ->select('rd.id_booking as nomor_order')
        ->select('date_format(rd.created_at, "%d/%m/%Y") as tanggal_order')
        ->select('ifnull(rd.no_buku_khusus_claim_c2, "-") as nomor_claim_c2')
        ->select('c.nama_customer')
        ->select('c.no_hp as no_telp_customer')
        ->select('c.alamat as alamat_customer')
        ->select('c.no_polisi')
        ->select('c.tahun_produksi as tahun_perakitan')
        ->select('c.no_rangka')
        ->select('c.no_mesin')
        ->select('format(rd.uang_muka, 0, "ID_id") as uang_muka_formatted')
        ->select("format(({$total_pembayaran}), 0, 'ID_id') as total_pembayaran")
        ->select("format( ( ({$total_pembayaran}) - rd.uang_muka ), 0, 'ID_id') as sisa_pembayaran")
        ->select("
            case 
                when ({$perkiraan_hari}) is not null then date_format(({$perkiraan_hari}), '%d-%m-%Y')
                else '-'
            end as perkiraan_hari
        ", false)
        ->select('rd.vor')
        ->from('tr_h3_dealer_request_document as rd')
        ->join('ms_dealer as d', 'd.id_dealer = rd.id_dealer')
        ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer')
        ->where('rd.id_booking', $this->input->get('k'))
		->limit(1)
        ->get()->row();

        $data['parts'] = $this->db
        ->select('rdp.*')
        ->select('format(rdp.harga_saat_dibeli, 0, "ID_id") as harga', false)
        ->select('p.nama_part')
        ->select('p.import_lokal')
        ->select('format((rdp.harga_saat_dibeli * rdp.kuantitas), 0, "ID_id") as amount')
        ->from('tr_h3_dealer_request_document_parts as rdp')
        // ->join('ms_part as p', '1=1')
        ->join('ms_part as p', 'p.id_part = rdp.id_part')
        ->where('rdp.id_booking', $this->input->get('k'))
        // ->limit(1)
        ->get()->result_array();

        if($data['request_document']->penomoran_ulang == 0){
            $html = $this->load->view('dealer/h3_dealer_cetak_request_document', $data, true);
        }else{
            $html = $this->load->view('dealer/h3_dealer_cetak_request_document_penomoran_ulang', $data, true);
        }
		
        // render the view into HTML
        $mpdf->WriteHTML($html);
        
        if($data['request_document']->penomoran_ulang == 0){
            for ($i=0; $i < 3; $i++) { 
                $start = ($i * 8) + 13;
                $mpdf->RoundedRect(140 , $start , 3, 3, 'D');
            }
        }
		// write the HTML into the mpdf
		$output = "cetak_request_document.pdf";
		$mpdf->Output($output, 'I');
	}

    public function update_customer($data, $id_customer){
        $this->db
        ->set($data)
        ->where('id_customer', $id_customer)
        ->update('ms_customer_h23');
    }
}
