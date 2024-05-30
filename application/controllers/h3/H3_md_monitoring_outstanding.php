<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_monitoring_outstanding extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_monitoring_outstanding";
    protected $title  = "Monitoring Outstanding";

	public function __construct()
	{		
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}

		$this->load->model('h3_md_claim_dealer_model', 'claim_dealer');
		$this->load->model('h3_md_claim_dealer_parts_model', 'claim_dealer_parts');
		$this->load->model('h3_md_claim_part_ahass_model', 'claim_part_ahass');
		$this->load->model('h3_md_claim_part_ahass_parts_model', 'claim_part_ahass_parts');
		$this->load->model('H3_md_stock_model', 'stock');
        $this->load->model('H3_md_stock_int_model', 'stock_int');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function history(){
		$data['isi']            = $this->page;
		$data['title']          = "History Monitoring Outstanding";
		$data['set']	        = "history";
		$this->template($data);
	}

	public function getDataHistory()
    {
        $this->make_datatables();
        $this->limit();

        $records = [];
        foreach ($this->db->get()->result_array() as $row) {
            $row['qty_onhand'] = $qty_sudah_diterima = $this->stock_int->qty_diterima($row['id_part_int'], $row['packing_sheet_number_int'], $row['no_doos']);

            $surat_jalan = $this->db->query("SELECT psl.surat_jalan_ahm,date_format(psl.created_at, '%d/%m/%Y') as created_at from tr_h3_md_psl_items psli join tr_h3_md_psl psl on psli.surat_jalan_ahm_int=psl.id where psli.packing_sheet_number_int = ".$row['packing_sheet_number_int'])->row_array();

            if($surat_jalan['surat_jalan_ahm'] != null){
                $row['qty_intransit'] = $row['packing_sheet_quantity'] - $qty_sudah_diterima;
                $row['tanggal_surat_jalan_ahm']=$surat_jalan['created_at'];
                $row['qty_unfill']=0;
            }else{
                $row['qty_intransit'] = 0;  
                $row['tanggal_surat_jalan_ahm']="-"; 
                $row['qty_unfill']=$surat_jalan['packing_sheet_quantity'];
            }

            $records[] = $row;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $records
        ]);
    }

    public function make_query()
    {
        $this->db
        ->select('ifnull(psp.no_po, "-") as id_purchase_order')
        ->select('psp.id_part_int')
        ->select('psp.id_part')
        ->select('p.nama_part')
        ->select('date_format(ps.packing_sheet_date, "%d/%m/%Y") as packing_sheet_date')
        ->select('ps.packing_sheet_number')
        ->select('ps.id as packing_sheet_number_int')
        ->select('psp.no_doos')
        ->select('psp.packing_sheet_quantity')
        // ->select('
        //     case
        //         when psl.created_at is not null then date_format(psl.created_at, "%d/%m/%Y")
        //         else "-"
        //     end as tanggal_surat_jalan_ahm
        // ', false)
        // ->select('"-" as tanggal_surat_jalan_ahm
        // ', false)
        ->select('psp.qty_order as qty_order')
        // ->select('psl.surat_jalan_ahm')
        // ->select('
        // case
        //     when psl.surat_jalan_ahm is null then psp.packing_sheet_quantity
        //     else 0
        // end as qty_unfill
        // ', false)
        // ->select(' 0 as qty_unfill
        // ', false)
        ->from('tr_h3_md_ps as ps')
        ->join('tr_h3_md_ps_parts as psp', 'ps.id = psp.packing_sheet_number_int')
        ->join('tr_h3_md_purchase_order as po', 'po.id = psp.no_po_int', 'left')
        // ->join('ms_part as p', 'p.id_part_int = psp.id_part_int', 'left')
        ->join('ms_part as p', 'p.id_part_int = psp.id_part_int')  
        // ->join('tr_h3_md_penerimaan_barang_items pbi','pbi.nomor_karton_int=psp.no_doos_int and pbi.id_part_int=psp.id_part_int and pbi.packing_sheet_number_int=psp.packing_sheet_number_int')
        // ->join('tr_h3_md_penerimaan_barang pb','pb.id=pbi.no_penerimaan_barang_int')
        // ->where('pb.status','Closed');
        // ->join('tr_h3_md_psl_items as psli', 'psli.packing_sheet_number_int = ps.id', 'left')
        // ->join('tr_h3_md_psl as psl', 'psl.id = psli.surat_jalan_ahm_int', 'left')
        // ->where_not_in('psp.no_doos_int',$penerimaan_barang);
        ->where("psp.no_doos_int in (select nomor_karton_int from tr_h3_md_penerimaan_barang_items pbi join tr_h3_md_penerimaan_barang pb on pbi.no_penerimaan_barang_int=pb.id where pb.status='Closed')");
        $this->db->group_start();
            $this->db->or_where('left(ps.created_at,10) <=', '2023-09-30');
            $this->db->group_end();
    }

    public function make_datatables()
    {
        $this->make_query();

        // if($this->input->post('periode_packing_sheet_filter_start') != null and $this->input->post('periode_packing_sheet_filter_end') != null){
        //     $this->db->group_start();
        //     $this->db->where('ps.packing_sheet_date >=', $this->input->post('periode_packing_sheet_filter_start'));
        //     $this->db->where('ps.packing_sheet_date <=', $this->input->post('periode_packing_sheet_filter_end'));
        //     $this->db->group_end();
        // }

        // if ($this->input->post('part_filter') != null and count($this->input->post('part_filter')) > 0) {
        //     $this->db->where_in('psp.id_part', $this->input->post('part_filter'));
        // }

        // if ($this->input->post('purchase_filter') != null and count($this->input->post('purchase_filter')) > 0) {
        //     $this->db->where_in('psp.no_po', $this->input->post('purchase_filter'));
        // }

        // if ($this->input->post('surat_sl_ahm_filter') != null and count($this->input->post('surat_sl_ahm_filter')) > 0) {
        //     $this->db->where_in('psl.surat_jalan_ahm', $this->input->post('surat_sl_ahm_filter'));
        // }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ps.created_at', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}