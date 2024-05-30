<?php

class h3_md_penerimaan_po_vendor_model extends Honda_Model{

    protected $table = 'tr_h3_md_penerimaan_po_vendor';

    public function __construct(){
        parent::__construct();
    }

    public function insert($data){
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
		$data['status'] = 'Open';

        parent::insert($data);
	}
	
	public function update($data, $condition){
		$data['updated_at'] = date('Y-m-d H:i:s', time());
		$data['updated_by'] = $this->session->userdata('id_user');

		if (isset($data['status']) && $data['status'] == 'Processed') {
			$this->create_invoice_ekspedisi($condition['id_penerimaan_po_vendor']);
		}

        parent::update($data, $condition);
	}
	
	public function create_invoice_ekspedisi($id_penerimaan_po_vendor){
		$this->load->model('H3_md_invoice_ekspedisi_model', 'invoice_ekspedisi');
		$this->load->model('H3_md_invoice_ekspedisi_item_model', 'invoice_ekspedisi_item');

		$no_invoice_ekspedisi = $this->invoice_ekspedisi->generate_id();

		$invoice_ekspedisi = $this->db
		->select("'{$no_invoice_ekspedisi}' as no_invoice_ekspedisi")
		->select('"penerimaan_po_vendor" as tipe_referensi')
		->select('ppv.id_penerimaan_po_vendor as referensi')
		->select('e.ppn as ppn_ekspedisi')
		->select('ppv.total_harga_angkut as dpp')
		->from("{$this->table} as ppv")
		->join('ms_h3_md_ekspedisi as e', 'e.id = ppv.id_ekspedisi')
		->where('ppv.id_penerimaan_po_vendor', $id_penerimaan_po_vendor)
		->get()->row_array();
		$invoice_ekspedisi['tanggal_invoice'] = date('Y-m-d', time());
		$invoice_ekspedisi['ppn'] = floatval($invoice_ekspedisi['dpp']) * ( floatval($invoice_ekspedisi['ppn_ekspedisi']) / 100 );
		$invoice_ekspedisi['grand_total'] = floatval($invoice_ekspedisi['dpp']) + floatval($invoice_ekspedisi['ppn']);

		$items = $this->db
		->select("'{$no_invoice_ekspedisi}' as no_invoice_ekspedisi")
		->select('ppvp.id_part')
		->select('ppvp.qty_order')
		->select('ppvp.qty_diterima')
		->from('tr_h3_md_penerimaan_po_vendor_parts as ppvp')
		->where('ppvp.id_penerimaan_po_vendor', $id_penerimaan_po_vendor)
		->get()->result_array();

		if($invoice_ekspedisi['dpp'] > 0){
			$this->invoice_ekspedisi->insert($invoice_ekspedisi);
			$this->invoice_ekspedisi_item->insert_batch($items);
		}
	}

    public function generateID(){
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('ym');

		$query = $this->db
		->from($this->table)
		->where("LEFT(tanggal, 7)='{$th_bln}'")
		->order_by('created_at', 'DESC')
		->limit(1)
		->get();

		if ($query->num_rows()>0) {
			$row        = $query->row();
			$id_penerimaan_po_vendor = substr($row->id_penerimaan_po_vendor, 0, 5);
			$id_penerimaan_po_vendor = sprintf("%'.05d",$id_penerimaan_po_vendor+1);
			$id   = "{$id_penerimaan_po_vendor}/PPOV/{$bln}/{$th}";
		}else{
			$id   = "00001/PPOV/{$bln}/{$th}";
		}

   		return strtoupper($id);
    }
}