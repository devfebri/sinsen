<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_pengeluaran_kas extends Honda_Controller {

	var $folder = "dealer";
	var $page   = "h3_dealer_pengeluaran_kas";
	var $title  = "Pengeluaran Kas (Payment)";

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
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "index";

		$this->template($data);	
	}

	public function add(){
		$data['isi']     = $this->page;		
		$data['title']   = $this->title;		
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$this->template($data);	
	}

	public function detail()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = 'Purchase Order';		
		$data['mode']  = 'detail';
		$data['set']   = "form";

		$quantity_terpenuhi = $this->db
        ->select('sum(pbi.qty_good)')
        ->from('tr_h3_md_sales_order as so')
        ->join('tr_h3_md_do_sales_order as dso', 'dso.id_sales_order = so.id_sales_order', 'left')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_ref = dso.id_do_sales_order', 'left')
        ->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list', 'left')
        ->join('tr_h3_md_surat_pengantar_items as spi', 'spi.id_packing_sheet = ps.id_packing_sheet', 'left')
        ->join('tr_h3_dealer_penerimaan_barang as pb', '(pb.id_packing_sheet = spi.id_packing_sheet and pb.id_surat_pengantar = spi.id_surat_pengantar)', 'left')
        ->join('tr_h3_dealer_penerimaan_barang_items as pbi', 'pb.id_penerimaan_barang = pbi.id_penerimaan_barang', 'left')
        ->where('so.id_ref = po.po_id')
        ->get_compiled_select();

		$data['purchase_order'] = $this->db
		->select('po.po_id')
		->select('date_format(po.tanggal_order, "%d-%m-%Y") as tanggal_order')
		->select('d.nama_dealer')
		->select('po.id_booking')
		->select('c.id_customer')
		->select('c.nama_customer')
		->select('c.no_hp as kontak_customer')
		->select('prh.nama as nama_pemesan')
		->select('prh.no_hp as kontak_pemesan')
        ->select("ifnull(({$quantity_terpenuhi}), 0) as qty_terpenuhi")
        ->select("sum(pop.kuantitas) - ifnull(({$quantity_terpenuhi}), 0) as qty_belum_terpenuhi")
        ->select("
        concat(
            format(
            ( ifnull(({$quantity_terpenuhi}), 0) / sum(pop.kuantitas) ) * 100 
            , 0),
            '%'
        )
		as fulfillment_rate")
        ->select('sum(pop.kuantitas) as qty_order')
        ->from('tr_h3_dealer_purchase_order as po')
        ->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id = po.po_id')
        ->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po.id_booking')
        ->join('tr_h3_dealer_pemesan_request_hotline as prh', 'prh.id = rd.id_data_pemesan', 'left')
		->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer')
		->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
        ->where('po.po_type', 'hlo')
        ->where('po.id_booking !=', null)
		->where('po.po_id', $this->input->get('id'))
		->get()->row();

		$data['purchase_order_parts'] = $this->db
		->select('p.nama_part')
		->from('tr_h3_dealer_purchase_order_parts as pop')
		->join('ms_part as p', 'p.id_part = pop.id_part')
		->where('pop.po_id', $this->input->get('id'))
		->get()->result();

		$data['penerimaan_parts'] = $this->db
		->select('pop.id_part')
		->select('p.nama_part')
		->select('pbi.id_penerimaan_barang')
		->select('date_format(pb.created_at, "%d-%m-%Y %H:%i") as tanggal_penerimaan_barang')
		->select('pbi.qty_good as qty')
		->from('tr_h3_dealer_purchase_order_parts as pop')
		->join('tr_h3_md_sales_order as so', 'so.id_ref = pop.po_id', 'left')
		->join('tr_h3_md_do_sales_order as dso', 'dso.id_sales_order = so.id_sales_order', 'left')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_ref = dso.id_do_sales_order', 'left')
        ->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list', 'left')
        ->join('tr_h3_md_surat_pengantar_items as spi', 'spi.id_packing_sheet = ps.id_packing_sheet', 'left')
        ->join('tr_h3_dealer_penerimaan_barang as pb', '(pb.id_packing_sheet = spi.id_packing_sheet and pb.id_surat_pengantar = spi.id_surat_pengantar)', 'left')
        ->join('tr_h3_dealer_penerimaan_barang_items as pbi', 'pb.id_penerimaan_barang = pbi.id_penerimaan_barang', 'left')
		->join('ms_part as p', 'p.id_part = pop.id_part')
        ->where('(so.id_ref = pop.po_id and pbi.id_part = pop.id_part)')
		->where('pop.po_id', $this->input->get('id'))
		->order_by('pb.created_at', 'ASC')
		->get()->result();

		$kuantitas_part_terpenuhi = $this->db
		->select('sum(pbi.qty_good)')
		->from('tr_h3_dealer_penerimaan_barang as pb')
		->join('tr_h3_dealer_penerimaan_barang_items as pbi', 'pb.id_penerimaan_barang = pbi.id_penerimaan_barang')
		->join('tr_h3_md_packing_sheet as ps', 'pb.id_packing_sheet = ps.id_packing_sheet')
		->join('tr_h3_md_picking_list as pl', 'ps.id_picking_list = pl.id_picking_list')
		->join('tr_h3_md_do_sales_order as dso', 'pl.id_ref = dso.id_do_sales_order')
		->join('tr_h3_md_sales_order as so', 'dso.id_sales_order = so.id_sales_order')
		->where('(so.id_ref = pop.po_id and pop.id_part = pbi.id_part)')
		->get_compiled_select();

		$data['parts'] = $this->db
		->select('pop.id_part')
		->select('p.nama_part')
		->select('pop.kuantitas')
		->select("ifnull(({$kuantitas_part_terpenuhi}), 0) as qty_terpenuhi")
		->select('date_format(pop.eta_terlama, "%d-%m-%Y") as eta_terlama')
		->from('tr_h3_dealer_purchase_order_parts as pop')
		->join('ms_part as p', 'p.id_part = pop.id_part')
		->where('pop.po_id', $this->input->get('id'))
		->get()->result();

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

		$kuantitas_part_terpenuhi = $this->db
			->select('sum(pbi.qty_good)')
			->from('tr_h3_dealer_penerimaan_barang as pb')
			->join('tr_h3_dealer_penerimaan_barang_items as pbi', 'pb.id_penerimaan_barang = pbi.id_penerimaan_barang')
			->join('tr_h3_md_packing_sheet as ps', 'pb.id_packing_sheet = ps.id_packing_sheet')
			->join('tr_h3_md_picking_list as pl', 'ps.id_picking_list = pl.id_picking_list')
			->join('tr_h3_md_do_sales_order as dso', 'pl.id_ref = dso.id_do_sales_order')
			->join('tr_h3_md_sales_order as so', 'dso.id_sales_order = so.id_sales_order')
			->where('(so.id_ref = pop.po_id and pop.id_part = pbi.id_part)')
			->get_compiled_select();
	
		$data['parts'] = $this->db
			->select('pop.id_part')
			->select('p.nama_part')
			->select('pop.kuantitas')
			->select("ifnull(({$kuantitas_part_terpenuhi}), 0) as qty_terpenuhi")
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

        $this->email->to($data['purchase_order']->email);
        // $this->email->to('danielpardamean14@gmail.com');

		$this->email->subject("Follow up status pemenuhan pesanan atas purchase order nomor {$data['purchase_order']->po_id}"); 
		$this->email->message($this->load->view('dealer/h3_dealer_email_order_fulfillment', $data, true)); 

        return $this->email->send();
    }
}