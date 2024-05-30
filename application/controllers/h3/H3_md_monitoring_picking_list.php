<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_monitoring_picking_list extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_monitoring_picking_list";
    protected $title  = "Monitoring Picking List";

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

		$this->load->model('h3_md_so_other_model', 'so_other');
		$this->load->model('h3_md_so_other_parts_model', 'so_other_parts');
		$this->load->model('h3_md_do_other_model', 'do_other');
		$this->load->model('h3_md_do_other_parts_model', 'do_other_parts');
		$this->load->model('h3_md_picking_list_model', 'picking_list');
		$this->load->model('h3_md_picking_list_parts_model', 'picking_list_parts');
		$this->load->model('part_model', 'master_part');
		$this->load->model('dealer_model', 'dealer');
		$this->load->model('karyawan_md_model', 'karyawan_md');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$data['picking_list'] = $this->picking_list->all();
		$this->template($data);
	}

	public function portal_tugaskan_picker(){
		$data['mode']    = 'tugaskan_picker';
		$data['set']     = "form";
		
		$this->template($data);
	}

	public function get_picking_list(){
		$total_item = $this->db
        ->select('count(plp.id_part)')
        ->from('tr_h3_md_picking_list_parts as plp')
        ->where('plp.id_picking_list = pl.id_picking_list')
        ->get_compiled_select();

        $total_pcs = $this->db
        ->select('sum(plp.qty_supply)')
        ->from('tr_h3_md_picking_list_parts as plp')
        ->where('plp.id_picking_list = pl.id_picking_list')
		->get_compiled_select();
		
		$this->db
		->select('date_format(so.tanggal_order, "%d/%m/%Y") as tanggal_sales')
        ->select('so.id_sales_order')
        ->select('date_format(do.tanggal, "%d/%m/%Y") as tanggal_do')
        ->select('do.id_do_sales_order')
        ->select('date_format(pl.tanggal, "%d/%m/%Y") as tanggal_picking')
        ->select('pl.id_picking_list')
        ->select('d.kode_dealer_md')
        ->select('d.nama_dealer')
        ->select('kab.kabupaten')
        ->select('do.total')
		->select('
			case	
				when pl.id_picker is not null or pl.id_picker != "" then 1
				else 0
			end as checked
		', false)
		->select('pl.id_picker')
		->select('
		case
			when pl.id_picker is not null or pl.id_picker != "" then k.nama_lengkap
			else "-"
		end as nama_picker', false)
		->select('date_format(pl.tanggal, "%d/%m/%Y") as tanggal')
		->select('pl.status')
		->select("({$total_item}) as total_item")
        ->select("({$total_pcs}) as total_pcs")
		->from('tr_h3_md_picking_list as pl')
		->join('tr_h3_md_do_sales_order as do', 'do.id = pl.id_ref_int')
		->join('tr_h3_md_sales_order as so', 'so.id = do.id_sales_order_int')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
        ->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan')
        ->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan')
        ->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten')
		->join('ms_karyawan as k', 'k.id_karyawan = pl.id_picker', 'left')
		->order_by('pl.created_at', 'desc');

		if($this->input->post('id_picker') != ''){
			$this->db
			->group_start()
			->where('pl.id_picker', null)
			->or_where('pl.id_picker', $this->input->post('id_picker'))
			->group_end()
			;
		}

		$this->db->where('pl.status', $this->input->post('filters_status'));

		if (count($this->input->post('filters_customer')) > 0) {
            $this->db->where_in('so.id_dealer', $this->input->post('filters_customer'));
        }

		if (count($this->input->post('filters_picking_list')) > 0) {
            $this->db->where_in('pl.id_picking_list', $this->input->post('filters_picking_list'));
        }

        if (count($this->input->post('filters_no_do')) > 0) {
            $this->db->where_in('do.id_do_sales_order', $this->input->post('filters_no_do'));
		}
		
		if (count($this->input->post('filters_kabupaten')) > 0) {
            $this->db->where_in('kab.id_kabupaten', $this->input->post('filters_kabupaten'));
		}
		
		if($this->input->post('periode_sales_filter_start') != null and $this->input->post('periode_sales_filter_end') != null){            
            $this->db->group_start();
            $this->db->where('so.tanggal_order >=', $this->input->post('periode_sales_filter_start'));
            $this->db->where('so.tanggal_order <=', $this->input->post('periode_sales_filter_end'));
            $this->db->group_end();
		}
		
		if($this->input->post('periode_do_filter_start') != null and $this->input->post('periode_do_filter_end') != null){            
            $this->db->group_start();
            $this->db->where('do.tanggal >=', $this->input->post('periode_do_filter_start'));
            $this->db->where('do.tanggal <=', $this->input->post('periode_do_filter_end'));
            $this->db->group_end();
		}

        if($this->input->post('periode_picking_list_filter_start') != null and $this->input->post('periode_picking_list_filter_end') != null){            
            $this->db->group_start();
            $this->db->where('pl.tanggal >=', $this->input->post('periode_picking_list_filter_start'));
            $this->db->where('pl.tanggal <=', $this->input->post('periode_picking_list_filter_end'));
            $this->db->group_end();
		}
		
		if (count($this->input->post('filters_jenis_dealer')) > 0) {
            if(
                in_array(
                    'H123', $this->input->post('filters_jenis_dealer')
                )
            ){
                $this->db->where('d.h1', 1);
                $this->db->where('d.h2', 1);
                $this->db->where('d.h3', 1);
            }

            if(
                in_array(
                    'H23', $this->input->post('filters_jenis_dealer')
                )
            ){
                $this->db->where('d.h2', 1);
                $this->db->where('d.h3', 1);
            }

            if(
                in_array(
                    'H3', $this->input->post('filters_jenis_dealer')
                )
            ){
                $this->db->where('d.h3', 1);
            }
		}
		
		send_json($this->db->get()->result());
	}

	public function set_picker()
	{
		$id_picker = $this->input->post('id_picker');

		foreach ($this->input->post('picking_list') as $picking_list) {
			$checked = $picking_list['checked'];
			$picking_list = $this->db
				->from('tr_h3_md_picking_list')
				->where('id_picking_list', $picking_list['id_picking_list'])
				->limit(1)
				->get()->row_array();

			if ($picking_list['id_picker'] != null) {
				log_message('info', sprintf('Picking list %s telah ditugaskan ke picker %s', $picking_list['id_picking_list'], $picking_list['id_picker']));
			} else {
				log_message('info', sprintf('Picking list %s belum ada picker', $picking_list['id_picking_list']));
			}

			if ($checked == "1") {
				$this->picking_list->update(['id_picker' => $id_picker], [
					'id' => $picking_list['id']
				]);
				log_message('info', sprintf('Picker %s ditugaskan untuk picking list dengan nomor %s', $id_picker, $picking_list['id_picking_list']));
			} else {
				$this->db
					->set('id_picker', null)
					->where('id', $picking_list['id'])
					->where('status != ', 'Closed PL')
					->update('tr_h3_md_picking_list');

				log_message('info', sprintf('Picker untuk picking list dengan nomor %s dihapus', $picking_list['id_picking_list']));
			}
		}
	}
}