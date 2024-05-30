<?php

class H3_md_create_ap_part_sales_campaign_insentif_cashback_model extends Honda_Model{

	private $tanggal_transaksi;
	private $tanggal_jatuh_tempo;

	public function __construct(){
		parent::__construct();

		$this->tanggal_transaksi = Mcarbon::now();
		$this->tanggal_jatuh_tempo = $this->tanggal_transaksi->copy()->addMonths(1);

		$this->load->library('Mcarbon');
		$this->load->model('H3_md_ap_part_model', 'ap_part');
	}

	public function proses($id_sales_campaign, $rekap = false){
		$this->check_sales_campaign($id_sales_campaign);

		$ap_parts = [];
		if($rekap){
			$ap_parts = $this->rekap($id_sales_campaign);
		}elseif(!$rekap){
			$ap_parts = $this->non_rekap($id_sales_campaign);
		}

		$this->ap_part->insert_batch($ap_parts);

		$this->set_sudah_proses_insentif($id_sales_campaign);
	}

	private function non_rekap($id_sales_campaign){
		return $this->db
		->select('"" as nomor_account')
		->select('perolehan.id_campaign')
		->select('sc.kode_campaign as referensi')
		->select('perolehan.id as id_referensi_table')
		->select('"ms_h3_md_sales_campaign" as referensi_table')
		->select('"perolehan_insentif_cashback_sales_campaign" as jenis_transaksi')
		->select('perolehan.id_dealer')
		->select("'{$this->tanggal_transaksi->format('Y-m-d')}' as tanggal_transaksi", false)
		->select("'{$this->tanggal_jatuh_tempo->format('Y-m-d')}' as tanggal_jatuh_tempo", false)
		->select('"MD" as nama_vendor')
		->select('perolehan.total_bayar')
		->select('perolehan.id as id_perolehan_poin_sales_campaign')
		->from('tr_h3_perolehan_sales_campaign_cashback_tidak_langsung as perolehan')
		->join('ms_h3_md_sales_campaign as sc', 'sc.id = perolehan.id_campaign')
		->where('perolehan.id_campaign', $id_sales_campaign)
		->where('perolehan.total_bayar > ', 0)
		->get()->result_array();
	}

	private function rekap($id_sales_campaign){
		$data = $this->db
		->select('"" as nomor_account')
		->select('perolehan.id_campaign')
		->select('sc.kode_campaign as referensi')
		->select('perolehan.id_campaign as id_referensi_table')
		->select('"ms_h3_md_sales_campaign" as referensi_table')
		->select('"rekap_perolehan_insentif_cashback_sales_campaign" as jenis_transaksi')
		->select("'{$this->tanggal_transaksi->format('Y-m-d')}' as tanggal_transaksi", false)
		->select("'{$this->tanggal_jatuh_tempo->format('Y-m-d')}' as tanggal_jatuh_tempo", false)
		->select('"MD" as nama_vendor')
		->select('SUM(perolehan.total_bayar) as total_bayar')
		->from('tr_h3_perolehan_sales_campaign_cashback_tidak_langsung as perolehan')
		->join('ms_h3_md_sales_campaign as sc', 'sc.id = perolehan.id_campaign')
		->where('perolehan.id_campaign', $id_sales_campaign)
		->where('perolehan.total_bayar > ', 0)
		->get()->row_array();

		if($data['total_bayar'] == null) return [];

		return [$data];
	}

	private function check_sales_campaign($id_sales_campaign){
		$sales_campaign = $this->db
			->from('ms_h3_md_sales_campaign as sc')
			->where('sc.id', $id_sales_campaign)
			->get()->row_array();

		if ($sales_campaign != null AND $sales_campaign['sudah_proses_insentif'] == 1) {
			throw new Exception(sprintf('Sales campaign %s - %s sudah pernah diproses insentif cashback sebelumnya [%s]', $sales_campaign['kode_campaign'], $sales_campaign['nama'], $sales_campaign['id']));
		}
	}

	private function set_sudah_proses_insentif($id_sales_campaign){
		$this->db
		->set('sc.sudah_proses_insentif', 1)
		->where('sc.id', $id_sales_campaign)
		->update('ms_h3_md_sales_campaign as sc');
	}
}
