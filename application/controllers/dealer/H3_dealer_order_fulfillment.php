<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_order_fulfillment extends Honda_Controller {
	var $folder = "dealer";
	var $page   = "h3_dealer_order_fulfillment";
	var $title  = "Order Fulfillment";

	public function __construct()
	{		
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('h3_dealer_purchase_order_model', 'purchase_order');		
		$this->load->model('h3_dealer_purchase_order_parts_model', 'purchase_order_parts');		
		$this->load->model('h3_dealer_shipping_list_model', 'shipping_list');		
		$this->load->model('h3_dealer_shipping_list_parts_model', 'shipping_list_parts');		
		$this->load->model('dealer_model', 'dealer');		
		$this->load->model('ms_part_model', 'ms_part');		
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "index";

		$this->template($data);	
	}

	public function detail()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = 'Purchase Order';		
		$data['mode']  = 'detail';
		$data['set']   = "form";

		$quantity_terpenuhi = $this->db
		->select('SUM(of.qty_fulfillment) AS qty_order_fulfillment', false)
		->from('tr_h3_dealer_order_fulfillment as of')
		->where('of.po_id = pop.po_id')
		->get_compiled_select();

		$kuantitas_po = $this->db
		->select('SUM(pop.kuantitas) as kuantitas', false)
		->from('tr_h3_dealer_purchase_order_parts as pop')
		->where('pop.po_id = po.po_id')
		->get_compiled_select();

		$data['purchase_order'] = $this->db
		->select('po.po_id')
		->select('date_format(po.tanggal_order, "%d-%m-%Y") as tanggal_order')
		->select('po.id_dealer')
		->select('d.nama_dealer')
		->select('po.id_booking')
		->select('c.id_customer')
		->select('c.nama_customer')
		->select('c.no_hp as kontak_customer')
		->select('prh.nama as nama_pemesan')
		->select('prh.no_hp as kontak_pemesan')
        ->select("IFNULL(({$quantity_terpenuhi}), 0) as qty_terpenuhi")
        ->select("IFNULL(({$kuantitas_po}), 0) - IFNULL(({$quantity_terpenuhi}), 0) as qty_belum_terpenuhi")
        ->select("
        concat(
            format(
            ( IFNULL(({$quantity_terpenuhi}), 0) / IFNULL(({$kuantitas_po}), 0) ) * 100 
            , 0),
            '%'
        )
		as fulfillment_rate")
		->select("IFNULL(({$kuantitas_po}), 0) as qty_order", false)
		->select('po.penyerahan_customer')
        ->from('tr_h3_dealer_purchase_order as po')
        ->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id = po.po_id')
        ->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po.id_booking')
        ->join('tr_h3_dealer_pemesan_request_hotline as prh', 'prh.id = rd.id_data_pemesan', 'left')
		->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer')
		->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
        ->where('po.po_type', 'hlo')
        ->where('po.id_booking !=', null)
		->where('po.po_id', $this->input->get('id'))
		->get()->row_array();

		$data['purchase_order_parts'] = $this->db
		->select('p.nama_part')
		->from('tr_h3_dealer_purchase_order_parts as pop')
		->join('ms_part as p', 'p.id_part = pop.id_part')
		->where('pop.po_id', $this->input->get('id'))
		->get()->result_array();

		$data['history_pesan_customer'] = $this->db
		->select('kch.tipe_kontak, kch.tgl_kontak_customer, (CASE WHEN kch.informasi_pesan="info_kedatangan_part" then "Informasi Kedatangan Part kepada Customer" WHEN kch.informasi_pesan="info_eta_ke_customer" then "Informasi ETA kepada Customer" ELSE "-" END) as informasi_pesan')
		->from('tr_h3_history_kontak_customer_hotline as kch')
		->where('kch.po_id', $this->input->get('id'))
		->get()->result_array();

		$data['penerimaan_parts'] = $this->db
		->select('of.id_part')
		->select('p.nama_part')
		->select('
		case
			when pb.id_penerimaan_barang is not null then pb.id_penerimaan_barang
			when gr.id_good_receipt is not null then gr.id_good_receipt
		end as id_penerimaan_barang
		', false)
		->select('
		case
			when pb.id_penerimaan_barang is not null then date_format(pb.created_at, "%d-%m-%Y %H:%i")
			when gr.id_good_receipt is not null then date_format(gr.tanggal_receipt, "%d-%m-%Y %H:%i")
		end as tanggal_penerimaan_barang
		', false)
		->select('of.qty_fulfillment as qty')
		->from('tr_h3_dealer_order_fulfillment as of')
		->join('tr_h3_dealer_penerimaan_barang as pb', 'of.id_referensi = pb.id_penerimaan_barang', 'left')
		->join('tr_h3_dealer_good_receipt as gr', 'of.id_referensi = gr.id_good_receipt', 'left')
		->join('ms_part as p', 'p.id_part = of.id_part')
		->where('of.po_id', $this->input->get('id'))
		->where('of.qty_fulfillment > 0', null, false)
		->order_by('pb.created_at', 'ASC')
		->get()->result_array();

		$qty_order_fullfillment = $this->db
		->select('IFNULL(
			SUM(of.qty_fulfillment), 0
		) AS qty_order_fulfillment')
		->from('tr_h3_dealer_order_fulfillment as of')
		->where('of.id_part = pop.id_part')
		->where('of.po_id = pop.po_id')
		->get_compiled_select();

		$eta_revisi = $this->db
		->select('hewh.eta')
		->from('tr_h3_md_history_estimasi_waktu_hotline as hewh')
		->where('hewh.po_id = pop.po_id')
		->where('hewh.id_part = pop.id_part')
		->where('hewh.source', 'upload_revisi')
		->order_by('hewh.created_at', 'desc')
		->limit(1)
		->get_compiled_select();

		$parts = $this->db
		->select('pop.id_part')
		->select('p.nama_part')
		->select('pop.kuantitas')
		->select("({$qty_order_fullfillment}) as qty_terpenuhi")
		->select('date_format(pop.eta_tercepat, "%d-%m-%Y") as eta_tercepat')
		->select('date_format(pop.eta_terlama, "%d-%m-%Y") as eta_terlama')
		// ->select("date_format(({$eta_revisi}), '%d-%m-%Y') as eta_revisi", false)
		->select('date_format(pop.eta_revisi, "%d-%m-%Y") as eta_revisi')
		->from('tr_h3_dealer_purchase_order_parts as pop')
		->join('ms_part as p', 'p.id_part = pop.id_part')
		->where('pop.po_id', $this->input->get('id'))
		->get()->result_array();

		$parts = array_map(function($row) use ($data) {
			$id_sales_order = $this->db
			->select('so.nomor_so')
			->from('tr_h3_dealer_sales_order as so')
			->join('tr_h3_dealer_sales_order_parts as sop', 'sop.nomor_so = so.nomor_so')
			->where('so.id_dealer', $data['purchase_order']['id_dealer'])
			->where('so.booking_id_reference', $data['purchase_order']['id_booking'])
			->where('sop.id_part', $row['id_part'])
			->get()->result_array();

			$id_sales_order = array_map(function($data){
				return $data['nomor_so'];
			}, $id_sales_order);

			$row['id_sales_order'] = $id_sales_order;

			return $row;
		}, $parts);

		$data['parts'] = $parts;

		$this->template($data);
	}

	public function send_email_to_customer(){
		$data['dealer'] = $this->db
            ->select('d.nama_dealer')
            ->select('d.alamat')
            ->select('d.no_telp')
            ->select('kelurahan.kelurahan')
            ->select('kecamatan.kecamatan')
            ->select('kabupaten.kabupaten')
            ->select('provinsi.provinsi')
            ->from('ms_dealer as d')
            ->join('ms_kelurahan as kelurahan', 'kelurahan.id_kelurahan = d.id_kelurahan')
            ->join('ms_kecamatan as kecamatan', 'kecamatan.id_kecamatan = kelurahan.id_kecamatan')
            ->join('ms_kabupaten as kabupaten', 'kabupaten.id_kabupaten = kecamatan.id_kabupaten')
            ->join('ms_provinsi as provinsi', 'provinsi.id_provinsi = kabupaten.id_provinsi')
            ->where('d.id_dealer', $this->m_admin->cari_dealer())
			->limit(1)->get()->row();

		$data['purchase_order'] = $this->db
			->select('po.po_id')
			->select('c.id_customer')
			->select('c.email')
			->select('date_format(po.tanggal_order, "%d/%m/%Y") as tanggal_order')
			->from('tr_h3_dealer_purchase_order as po')
			->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id = po.po_id')
			->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po.id_booking')
			->join('tr_h3_dealer_pemesan_request_hotline as prh', 'prh.id = rd.id_data_pemesan', 'left')
			->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer')
			->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
			->where('po.po_id', $this->input->post('id'))
			->limit(1)
			->get()->row();

		$quantity_terpenuhi = $this->db
		->select('IFNULL(
			SUM(of.qty_fulfillment), 0
		) AS qty_order_fulfillment')
		->from('tr_h3_dealer_order_fulfillment as of')
		->where('of.po_id = pop.po_id')
		->where('of.id_part = pop.id_part')
        ->get_compiled_select();
	
		$data['parts'] = $this->db
			->select('pop.id_part')
			->select('p.nama_part')
			->select('pop.kuantitas')
			->select("ifnull(({$quantity_terpenuhi}), 0) as qty_terpenuhi")
			->select('date_format(pop.eta_terlama, "%d/%m/%Y") as eta_terlama')
			->from('tr_h3_dealer_purchase_order_parts as pop')
			->join('ms_part as p', 'p.id_part = pop.id_part')
			->where('pop.po_id', $this->input->post('id'))
			->get()->result();

        $cfg  = $this->db->get('setup_smtp_email')->row();
        $from = $this->db->get_where('ms_email_md', ['email_for' => 'notification'])->row();
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => $cfg->smtp_host,
            'smtp_port' => 465,
            'smtp_user' => $from->email,
            'smtp_pass' => $from->pass,
            'mailtype'  => 'html',
            'charset'   => 'iso-8859-1'
        );

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");  

        $dealerBersangkutan = $this->dealer->getCurrentUserDealer();
		$this->email->from($from->email, "[{$dealerBersangkutan->nama_dealer}]"); 
		
		if($data['purchase_order']->email == '' || $data['purchase_order']->email == null){
			$this->output->set_status_header(400);
			send_json([
				'message' => 'Email user tidak ada.'
			]);
		}

        $this->email->to($data['purchase_order']->email);

		$this->email->subject("Follow up status pemenuhan pesanan atas purchase order nomor {$data['purchase_order']->po_id}"); 
		$this->email->message($this->load->view('dealer/h3_dealer_email_order_fulfillment', $data, true)); 

        if($this->email->send()){
			send_json([
				'message' => 'Email berhasil dikirim.'
			]);
		}else{
			$this->output->set_status_header(400);
			send_json([
				'message' => 'Email gagal dikirim.'
			]);
		}
	}

	public function kirim_pesan_ke_customer(){
		$this->db->trans_start();
		$po_id = $this->input->post('po_id');
		$tipe_kontak = $this->input->post('tipe_kontak');
		$informasi_pesan = $this->input->post('informasi_pesan');
		$tgl_kontak_customer = $this->input->post('tgl_kontak_customer');
		$jam_kontak_customer = $this->input->post('jam_kontak_customer');


		$saveData = array(
			'tipe_kontak' => $tipe_kontak,
			'po_id' => $po_id,
			'informasi_pesan' => $informasi_pesan,
			'tgl_kontak_customer' => $tgl_kontak_customer.' '.$jam_kontak_customer,
			'created_at'    => date('Y-m-d H:i:s'),
			'created_by' => $this->session->userdata('id_user')
			);
		
		$this->db->insert('tr_h3_history_kontak_customer_hotline',$saveData);

        $this->db->trans_complete();
        if ($this->db->trans_status() == true) {
            $this->db->trans_commit();
			$_SESSION['pesan'] 	= "Berhasil Update Status Pengiriman Pesan kepada Customer";
            $_SESSION['tipe'] 	= "success";
			echo "<script>history.go(-1)</script>";
        } else {
            $this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Gagal Update Status Pengiriman Pesan kepada Customer";
            $_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
        }
    
        // echo json_encode($result);
	}
	
	public function penyerahan_customer(){
		$this->db->trans_start();

		$this->purchase_order->update([
			'penyerahan_customer' => 1
		], $this->input->post(['po_id']));

		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json([
				'message' => 'Data berhasil diperbarui',
			]);
		}else{
		  	$this->output->set_status_header(400);
			send_json([
				'message' => 'Data gagal diperbarui.'
			]);
		}
	}
}