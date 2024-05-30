<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_penjualan_part extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $data[] = $row;
            $index++;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
            'input' => $this->input->post(),
        ]);
    }

    public function make_query()
    {
        $this->db
			->select('nsc.no_nsc')
			->select('c.nama_customer')
			->select('c.no_polisi')
			->select('
				case
					when jasa.id_type = "ASS1" then "KPB 1"
					when (jasa.id_type = "C1" OR jasa.id_type = "C2" OR jasa.id_type = "C3") then "Claim"
					else null
				end as keterangan
			', false)
			->select('sop.id_part')
			->select('p.nama_part')
			->select('
				case
					when sop.id_promo is null then sop.tipe_diskon
					else ""
				end as tipe_diskon
			', false)
			->select('
				case
					when sop.id_promo is null then ifnull(sop.diskon_value, 0)
					else 0
				end as diskon_value
			', false)
			->select('
				case
					when sop.id_promo is not null then sop.tipe_diskon
					else ""
				end as tipe_diskon_promo
			', false)
			->select('
				case
					when sop.id_promo is not null then ifnull(sop.diskon_value, 0)
					else 0
				end as diskon_value_promo
			', false)
			->select('(sop.kuantitas - sop.kuantitas_return) as qty')
			->select('sop.harga_saat_dibeli')
			->select('
				case
					when (jasa.id_type != "ASS1" AND jasa.id_type != "C1" AND jasa.id_type != "C2" AND jasa.id_type != "C3") then nsc.tot_nsc
					else 0
				end as total_nsc
			')
			->select('
				case
					when (jasa.id_type != "ASS1" AND jasa.id_type != "C1" AND jasa.id_type != "C2" AND jasa.id_type != "C3") then wo.total_jasa
					else 0
				end as total_njb
			')
			->select('
				case
					when (jasa.id_type = "ASS1" OR jasa.id_type = "C1" OR jasa.id_type = "C2" OR jasa.id_type = "C3") then nsc.tot_nsc
					else 0
				end as total_nsc_khusus
			')
			->select('
				case
					when (jasa.id_type = "ASS1" OR jasa.id_type = "C1" OR jasa.id_type = "C2" OR jasa.id_type = "C3") then wo.total_jasa
					else 0
				end as total_njb_khusus
			')
			->from('tr_h3_dealer_sales_order as so')
			->join('tr_h3_dealer_sales_order_parts as sop', 'so.nomor_so = sop.nomor_so')
			->join('ms_part as p', 'p.id_part_int = sop.id_part_int', 'left')
			->join('ms_customer_h23 as c', 'c.id_customer_int = so.id_customer_int', 'left')
			->join('tr_h23_nsc as nsc', 'nsc.id_referensi = so.nomor_so')
			->join('tr_h2_wo_dealer as wo', 'wo.id_work_order = so.id_work_order', 'left')
			->join('tr_h2_wo_dealer_parts as wop', '(wop.id_work_order = wo.id_work_order and wop.id_part = sop.id_part)', 'left')
			->join('ms_h2_jasa as jasa', 'jasa.id_jasa = wop.id_jasa', 'left')
            ->where('so.id_dealer', $this->m_admin->cari_dealer())
            ->where("so.tanggal_so between '{$this->input->post('periode_filter_start')}' AND '{$this->input->post('periode_filter_end')}'", null, false)
            ;
    }

    public function make_datatables()
    {
        $this->make_query();
      
        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            // $this->db->like('so.nomor_so', $search);
            // $this->db->or_like('so.id_work_order', $search);
            // $this->db->or_like('wo.no_nsc', $search);
            // $this->db->or_like('c.no_polisi', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('nsc.created_at', 'DESC');
            $this->db->order_by('sop.id_part', 'asc');
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

    public function get_total_nsc(){
        $this->make_datatables();

        $data = $this->db->get()->result_array();

        $total = array_sum(
            array_map(function($row){
                return floatval($row['total_nsc']) + floatval($row['total_njb']);
            }, $data)
        );

        send_json([
            'total' => $total
        ]);
    }

    public function get_total_harga(){
        $this->make_datatables();
        $data = $this->db->get()->result_array();

        $total = array_sum(
            array_map(function($row){
                return floatval($row['total_nsc_khusus']) + floatval($row['total_njb_khusus']);
            }, $data)
        );

        send_json([
            'total' => $total
        ]);
    }
}
