<?php

class H3_md_invoice_ekspedisi_model extends Honda_Model{

    protected $table = 'tr_h3_md_invoice_ekspedisi';

    public function insert($data){
        $data['created_at'] = date('Y-m-d H:i:s', time());
        $data['created_by'] = $this->session->userdata('id_user');

        parent::insert($data);        
    }

    public function update($data, $condition){
        $data['updated_at'] = date('Y-m-d H:i:s', time());
        $data['updated_by'] = $this->session->userdata('id_user');

        if(isset($data['status']) and $data['status'] == 'Processed by Finance'){
            $this->create_ap($condition['id']);
        }

        parent::update($data, $condition);        
    }

    public function create_ap($id){
		$this->load->model('H3_md_ap_part_model', 'ap_part');

        $ap_part = $this->db
        ->select('ie.no_invoice_ekspedisi as referensi')
        ->select('"invoice_ekspedisi" as jenis_transaksi')
        ->select('ie.tanggal_invoice as tanggal_transaksi')
        ->select('DATE_ADD(ie.tanggal_invoice, INTERVAL 30 DAY) as tanggal_jatuh_tempo')
        ->select('e.nama_ekspedisi as nama_vendor')
        ->select('ie.grand_total as total_bayar')
        ->from("{$this->table} as ie")
        ->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_penerimaan_barang = ie.referensi', 'left')
        ->join('tr_h3_md_penerimaan_po_vendor as ppv', 'ppv.id_penerimaan_po_vendor = ie.referensi', 'left')
		->join('ms_h3_md_ekspedisi as e', '(e.id = pb.id_vendor OR e.id = ppv.id_ekspedisi)', 'left')
        ->where('ie.id', $id)
        ->get()->row_array();

        if($ap_part != null){
            $this->ap_part->insert($ap_part);
        }
    }

    public function generate_id(){
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		
        $query = $this->db
        ->select('no_invoice_ekspedisi')
        ->from($this->table)
        ->where("LEFT(created_at, 7)='{$th_bln}'")
        ->order_by('id', 'DESC')
        ->order_by('created_at', 'DESC')
        ->limit(1)
        ->get();

		if ($query->num_rows()>0) {
			$row        = $query->row();
			$no_invoice_ekspedisi = substr($row->no_invoice_ekspedisi, 0, 5);
			$no_invoice_ekspedisi = sprintf("%'.05d",$no_invoice_ekspedisi+1);
			$id   = "{$no_invoice_ekspedisi}/INV-EKS/{$bln}/{$th}";
		}else{
			$id   = "00001/INV-EKS/{$bln}/{$th}";
		}

   		return strtoupper($id);
    }
}
