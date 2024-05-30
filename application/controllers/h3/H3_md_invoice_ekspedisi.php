<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_invoice_ekspedisi extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_invoice_ekspedisi";
	protected $title  = "Invoice Ekspedisi";

	public function __construct()
	{
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_invoice_ekspedisi_model', 'invoice_ekspedisi');
		//===== Load Library =====
		$this->load->library('upload');
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
	}

	public function index()
	{
		$data['set']	= "index";
		$this->template($data);
	}

	public function detail()
	{
		$data['mode'] = 'detail';
		$data['set'] = "form";

		$data['invoice_ekspedisi'] = $this->db
			->select('ie.id')
			->select('ie.referensi')
			->select('ie.ppn_ekspedisi')
			->select('
            case
                when ie.tipe_referensi = "penerimaan_barang" then date_format(pb.created_at, "%d-%m-%Y") 
                when ie.tipe_referensi = "penerimaan_po_vendor" then date_format(ppv.tanggal, "%d-%m-%Y") 
            end as tanggal_penerimaan
        ', false)
			->select('
            case
                when ie.tipe_referensi = "penerimaan_barang" then pb.no_surat_jalan_ekspedisi
                when ie.tipe_referensi = "penerimaan_po_vendor" then ppv.surat_jalan_ekspedisi
            end as no_surat_jalan_ekspedisi
		', false)
			->select('
            case
                when ie.tipe_referensi = "penerimaan_barang" then date_format(pb.tgl_surat_jalan_ekspedisi, "%d-%m-%Y")
                when ie.tipe_referensi = "penerimaan_po_vendor" then date_format(ppv.tanggal, "%d-%m-%Y")
            end as tgl_surat_jalan_ekspedisi
        ', false)
			->select('e.nama_ekspedisi')
			->select('
            case
                when ie.tipe_referensi = "penerimaan_barang" then pb.nama_driver
                when ie.tipe_referensi = "penerimaan_po_vendor" then ppv.nama_driver
            end as nama_driver
		', false)
			->select('
            case
                when ie.tipe_referensi = "penerimaan_barang" then pb.no_plat
                when ie.tipe_referensi = "penerimaan_po_vendor" then ppv.no_plat
            end as no_plat
		', false)
			->select('
            case
                when ie.tipe_referensi = "penerimaan_barang" then pb.jenis_ongkos_angkut_part
                when ie.tipe_referensi = "penerimaan_po_vendor" then ppv.jenis_ongkos_angkut_part
            end as jenis_ongkos_angkut_part
		', false)
			->select('
            case
                when ie.tipe_referensi = "penerimaan_barang" then pb.harga_ongkos_angkut_part
                when ie.tipe_referensi = "penerimaan_po_vendor" then ppv.harga_ongkos_angkut_part
            end as harga_ongkos_angkut_part
		', false)
			->select('
            case
                when ie.tipe_referensi = "penerimaan_barang" then pb.per_satuan_ongkos_angkut_part
                when ie.tipe_referensi = "penerimaan_po_vendor" then ppv.per_satuan_ongkos_angkut_part
            end as per_satuan_ongkos_angkut_part
		', false)
			->select('
            case
                when ie.tipe_referensi = "penerimaan_barang" then pb.berat_truk
                when ie.tipe_referensi = "penerimaan_po_vendor" then ppv.berat_truk
            end as berat_truk
        ', false)
			->select('ie.diskon')
			->select('ie.potongan_tagihan')
			->select('ie.status')
			->from('tr_h3_md_invoice_ekspedisi as ie')
			->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_penerimaan_barang = ie.referensi', 'left')
			->join('tr_h3_md_penerimaan_po_vendor as ppv', 'ppv.id_penerimaan_po_vendor = ie.referensi', 'left')
			->join('ms_h3_md_ekspedisi as e', '(e.id = pb.id_vendor OR e.id = ppv.id_ekspedisi)', 'left')
			->where('ie.id', $this->input->get('id'))
			->get()->row();

		$data['items'] = $this->db
			->select('iei.id_part')
			->select('p.nama_part')
			->select('iei.qty_order')
			->select('iei.qty_diterima')
			->from('tr_h3_md_invoice_ekspedisi as ie')
			->join('tr_h3_md_invoice_ekspedisi_item as iei', 'iei.no_invoice_ekspedisi = ie.no_invoice_ekspedisi')
			->join('ms_part as p', 'p.id_part = iei.id_part')
			->where('ie.id', $this->input->get('id'))
			->get()->result_array();

		$this->template($data);
	}

	public function edit()
	{
		$data['mode'] = 'edit';
		$data['set'] = "form";

		$data['invoice_ekspedisi'] = $this->db
			->select('ie.id')
			->select('ie.referensi')
			->select('ie.ppn_ekspedisi')
			->select('
            case
                when ie.tipe_referensi = "penerimaan_barang" then date_format(pb.created_at, "%d-%m-%Y") 
                when ie.tipe_referensi = "penerimaan_po_vendor" then date_format(ppv.tanggal, "%d-%m-%Y") 
            end as tanggal_penerimaan
        ', false)
			->select('
            case
                when ie.tipe_referensi = "penerimaan_barang" then pb.no_surat_jalan_ekspedisi
                when ie.tipe_referensi = "penerimaan_po_vendor" then ppv.surat_jalan_ekspedisi
            end as no_surat_jalan_ekspedisi
		', false)
			->select('
            case
                when ie.tipe_referensi = "penerimaan_barang" then date_format(pb.tgl_surat_jalan_ekspedisi, "%d-%m-%Y")
                when ie.tipe_referensi = "penerimaan_po_vendor" then date_format(ppv.tanggal, "%d-%m-%Y")
            end as tgl_surat_jalan_ekspedisi
        ', false)
			->select('e.nama_ekspedisi')
			->select('
            case
                when ie.tipe_referensi = "penerimaan_barang" then pb.nama_driver
                when ie.tipe_referensi = "penerimaan_po_vendor" then ppv.nama_driver
            end as nama_driver
		', false)
			->select('
            case
                when ie.tipe_referensi = "penerimaan_barang" then pb.no_plat
                when ie.tipe_referensi = "penerimaan_po_vendor" then ppv.no_plat
            end as no_plat
		', false)
			->select('
            case
                when ie.tipe_referensi = "penerimaan_barang" then pb.jenis_ongkos_angkut_part
                when ie.tipe_referensi = "penerimaan_po_vendor" then ppv.jenis_ongkos_angkut_part
            end as jenis_ongkos_angkut_part
		', false)
			->select('
            case
                when ie.tipe_referensi = "penerimaan_barang" then pb.harga_ongkos_angkut_part
                when ie.tipe_referensi = "penerimaan_po_vendor" then ppv.harga_ongkos_angkut_part
            end as harga_ongkos_angkut_part
		', false)
			->select('
            case
                when ie.tipe_referensi = "penerimaan_barang" then pb.per_satuan_ongkos_angkut_part
                when ie.tipe_referensi = "penerimaan_po_vendor" then ppv.per_satuan_ongkos_angkut_part
            end as per_satuan_ongkos_angkut_part
		', false)
			->select('
            case
                when ie.tipe_referensi = "penerimaan_barang" then pb.berat_truk
                when ie.tipe_referensi = "penerimaan_po_vendor" then ppv.berat_truk
            end as berat_truk
        ', false)
			->select('ie.diskon')
			->select('ie.potongan_tagihan')
			->select('ie.status')
			->from('tr_h3_md_invoice_ekspedisi as ie')
			->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_penerimaan_barang = ie.referensi', 'left')
			->join('tr_h3_md_penerimaan_po_vendor as ppv', 'ppv.id_penerimaan_po_vendor = ie.referensi', 'left')
			->join('ms_h3_md_ekspedisi as e', '(e.id = pb.id_vendor OR e.id = ppv.id_ekspedisi)', 'left')
			->where('ie.id', $this->input->get('id'))
			->get()->row();

		$data['items'] = $this->db
			->select('iei.id_part')
			->select('p.nama_part')
			->select('iei.qty_order')
			->select('iei.qty_diterima')
			->from('tr_h3_md_invoice_ekspedisi as ie')
			->join('tr_h3_md_invoice_ekspedisi_item as iei', 'iei.no_invoice_ekspedisi = ie.no_invoice_ekspedisi')
			->join('ms_part as p', 'p.id_part = iei.id_part')
			->where('ie.id', $this->input->get('id'))
			->get()->result_array();

		$this->template($data);
	}

	public function update()
	{
		$this->db->trans_start();
		$data = $this->input->post(['dpp', 'ppn', 'grand_total', 'diskon', 'potongan_tagihan']);
		$this->invoice_ekspedisi->update($data, $this->input->post(['id']));

		$invoice_ekspedisi = (array) $this->invoice_ekspedisi->find($this->input->post('id'));
		$this->db
			->set('pb.berat_truk', $this->input->post('berat_truk'))
			->where('pb.no_penerimaan_barang', $invoice_ekspedisi['referensi'])
			->update('tr_h3_md_penerimaan_barang as pb');
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$invoice_ekspedisi = $this->invoice_ekspedisi->find($this->input->post('id'));
			send_json($invoice_ekspedisi);
		} else {
			$this->output->set_status_header(500);
		}
	}

	public function proses()
	{
		$this->db->trans_start();
		$data = [
			'status' => 'Processed by Finance',
			'processed_at' => date('Y-m-d H:i:s', time()),
			'processed_by' => $this->session->userdata('id_user')
		];
		$this->invoice_ekspedisi->update($data, $this->input->get(['id']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$invoice_ekspedisi = $this->invoice_ekspedisi->find($this->input->get('id'));
			send_json($invoice_ekspedisi);
		} else {
			$this->output->set_status_header(500);
		}
	}

	public function reject()
	{
		$this->db->trans_start();
		$data = [
			'status' => 'Rejected by Finance',
			'rejected_at' => date('Y-m-d H:i:s', time()),
			'rejected_by' => $this->session->userdata('id_user'),
			'alasan_reject' => $this->input->get('alasan_reject')
		];
		$this->invoice_ekspedisi->update($data, $this->input->get(['id']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$invoice_ekspedisi = $this->invoice_ekspedisi->find($this->input->get('id'));
			send_json($invoice_ekspedisi);
		} else {
			$this->output->set_status_header(500);
		}
	}

	public function validate()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_dealer', 'Dealer', 'required');
		$this->form_validation->set_rules('id_wilayah_penagihan', 'Wilayah Penagihan', 'required');
		$this->form_validation->set_rules('start_date', 'Periode Faktur', 'required');
		$this->form_validation->set_rules('id_yang_menyerahkan', 'Yang Menyerahkan', 'required');
		$this->form_validation->set_rules('id_yang_menerima', 'Yang Menerima', 'required');
		$this->form_validation->set_rules('id_yang_menyetujui', 'Disetujui Oleh', 'required');
		$this->form_validation->set_rules('id_bank', 'Bank', 'required');

		if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
	}
}
