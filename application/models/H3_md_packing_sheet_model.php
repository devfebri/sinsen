<?php

class h3_md_packing_sheet_model extends Honda_Model{

	protected $table = 'tr_h3_md_packing_sheet';
	
	public function __construct(){
		parent::__construct();
		$this->load->model('H3_md_ar_part_model', 'ar_part');
	}

    public function insert($data){
		$picking_list = $this->db->from('tr_h3_md_picking_list as pl')->where('pl.id_picking_list', $data['id_picking_list'])->get()->row_array();

		$data['id_picking_list_int'] = $picking_list['id'];
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
		parent::insert($data);

		$this->create_ar($data['id_picking_list']);

	}

	public function create_ar($id_picking_list){
		$dealer_gimmick = $this->db
		->select('d.nama_dealer')
		->from('ms_dealer as d')
		->where('d.tipe_plafon_h3', 'gimmick')
		->limit(1)
		->get_compiled_select();

		$dealer_kpb = $this->db
		->select('d.nama_dealer')
		->from('ms_dealer as d')
		->where('d.tipe_plafon_h3', 'kpb')
		->limit(1)
		->get_compiled_select();

		$data = $this->db
		->select('
			case
				when so.produk = "Parts" then "1.02.11052.02"
				when so.produk = "Oil" then "1.02.11053.02"
				else "1.02.11052.02"
			end as kode_coa
		', false)
		->select('ps.no_faktur as referensi')
		->select('"faktur_penjualan" as tipe_referensi')
		->select('pl.id_dealer')
		->select(sprintf('
			case 
				when do.gimmick = 1 then (%s)
				when (so.kategori_po = "KPB") then (%s)
				else d.nama_dealer
			end as nama_customer
		', $dealer_gimmick, $dealer_kpb))
		->select('ps.tgl_faktur as tanggal_transaksi')
		->select('ps.tgl_jatuh_tempo as tanggal_jatuh_tempo')
		->select('ROUND(do.total, 0) as total_amount', false)
		->select('do.gimmick')
		->select('(so.kategori_po = "KPB") as kpb', false)
		->select('LOWER(so.produk) as jenis_transaksi')
		->from('tr_h3_md_picking_list as pl')
		->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
		->where('pl.id_picking_list', $id_picking_list)
		->get()->row_array();

		if($data != null){
			$this->ar_part->insert($data);
		}
	}
	
    public function generateSuratJalan($tipe_po, $id_dealer, $gimmick = 0){
		$tahun = date('Y', time());
		$bulan = date('m', time());

		$dealer = $this->db
		->from('ms_dealer as d')
		->where('d.id_dealer', $id_dealer)
		->get()->row();

		$query = $this->db
		->select('ps.id_packing_sheet')
		->select('do.id_do_sales_order')
		->select('so.id_sales_order')
		->select('so.jenis_pembayaran')
		->from('tr_h3_md_packing_sheet as ps')
		->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
        ->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
		->where("LEFT(ps.tgl_packing_sheet, 4)='{$tahun}'")
		// ->where('so.id_dealer', $id_dealer)
		// ->where('so.po_type', $tipe_po)
		->where('ps.id_packing_sheet is not null', null, false)
		->order_by('ps.tgl_packing_sheet', 'desc')
		->limit(1)
		->where('ps.created_at > ', '2021-06-22 13:50:24')
		->get()
		;

		if ($query->num_rows()>0) {
            $row = $query->row();
			$id_packing_sheet = substr($row->id_packing_sheet, 0, 6);
			$id_packing_sheet = sprintf("%'.06d",$id_packing_sheet+1);
			$id = "{$id_packing_sheet}/PS-{$tipe_po}/{$dealer->kode_dealer_md}/{$bulan}/{$tahun}";

			// 22/11/23 Query untuk tidak kedouble, tapi lambat jadi mau ditest dulu
			// $i = 0;
            // while ($i < 1) {
            //     $cek = $this->db->get_where('tr_h3_md_packing_sheet', ['id_packing_sheet' => $id])->num_rows();
            //     if ($cek > 0) {
            //         $gen_number    = substr($id,0,6);
            //         $id = "{$gen_number}/PS-{$tipe_po}/{$dealer->kode_dealer_md}/{$bulan}/{$tahun}";
            //         $i = 0;
            //     } else {
            //         $i++;
            //     }
            // }

		}else{
			$id = "000001/PS-{$tipe_po}/{$dealer->kode_dealer_md}/{$bulan}/{$tahun}";
		}
		
		if($gimmick == 1){
			$id .= '/FGD';
		}
		
   		return strtoupper($id);
    }

	public function generateFaktur($kode_customer)
	{
		$th        = date('Y');
		$bln       = date('m');

		$query = $this->db
		->select('no_faktur')
		->from($this->table)
		->where("LEFT(tgl_faktur, 4)='{$th}'")
		->order_by('id', 'DESC')
		->order_by('no_faktur', 'DESC')
		->where('created_at > ', '2021-05-20 14:58:00')
		->limit(1)
		->get();

		if ($query->num_rows() > 0) {
			$data_terakhir = $query->row_array();
			$nomor_faktur_terakhir = intval(
				explode('/', $data_terakhir['no_faktur'])[0]
			);
			$no_faktur = sprintf("%'.06d", ($nomor_faktur_terakhir + 1));
			$nomor_faktur_berikutnya = "{$no_faktur}/FAK-{$kode_customer}/{$bln}/{$th}";
		} else {
			$nomor_faktur_berikutnya = "000001/FAK-{$kode_customer}/{$bln}/{$th}";
		}
		return strtoupper($nomor_faktur_berikutnya);
	}
	
	public function generateID(){
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('ym');

		$query = $this->db->select('*')
		->from($this->table)
		->where("LEFT(tgl_packing_sheet, 7)='{$th_bln}'")
		->order_by('id', 'DESC')
		->limit(1)
		->get();

		if ($query->num_rows()>0) {
            $row        = $query->row();
			$id_packing_sheet = substr($row->id_packing_sheet, 0, 3);
			$id_packing_sheet = sprintf("%'.03d",$id_packing_sheet+1);
			$id   = "{$id_packing_sheet}/PS/{$bln}/{$th}";
		}else{
			$id   = "001/PS/{$bln}/{$th}";
		}
   		return strtoupper($id);
    }
}
