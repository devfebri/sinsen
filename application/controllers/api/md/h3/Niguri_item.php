<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Niguri_item extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        set_time_limit(0);
        $this->load->library('Mcarbon');
    }

    public function index()
    {
        $this->select_for_datatable();
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';

            $tanggal_generate = Mcarbon::parse($row['tanggal_generate']);

            for ($i=0; $i < 6; $i++) { 
                $label_fix_order = 'fix_order_n';
                if($i != 0){
                    $label_fix_order .= '_' . $i;
                }
                $label_fix_order .= '_editable';
                
                $row[$label_fix_order] = $this->db
                ->select('po.id_purchase_order')
                ->from('tr_h3_md_purchase_order as po')
                ->join('tr_h3_md_purchase_order_parts as pop', 'pop.id_purchase_order_int = po.id')
                ->where("SUBSTR(po.bulan, 6, 2) = '{$tanggal_generate->copy()->addMonths($i)->format('m')}'", null, false)
                ->where("SUBSTR(po.tahun, 1, 4) = '{$tanggal_generate->copy()->addMonths($i)->format('Y')}'", null, false)
                ->where('pop.id_part', $row['id_part'])
                ->get()->row_array() == null;

                $row['qty_reguler_editable'] = $this->db
                ->select('po.id_purchase_order')
                ->from('tr_h3_md_purchase_order as po')
                ->join('tr_h3_md_purchase_order_parts as pop', 'pop.id_purchase_order_int = po.id')
                ->where("SUBSTR(po.bulan, 6, 2) = '{$tanggal_generate->copy()->startOfMonth()->format('m')}'", null, false)
                ->where("SUBSTR(po.tahun, 1, 4) = '{$tanggal_generate->copy()->startOfMonth()->format('Y')}'", null, false)
                ->where('pop.id_part', $row['id_part'])
                ->get()->row_array() == null;
            }
            
            $data[] = $row;
            $index++;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data
        ]);
    }

    private function select_for_datatable(){
        $this->db
        ->select('n.id')
        ->select('nh.tanggal_generate')
        ->select('nh.type_niguri')
        ->select('n.id_part')
        ->select('p.nama_part')
        ->select('p.kelompok_part')
        ->select('p.harga_dealer_user as het')
        ->select('p.harga_md_dealer as hpp')
        ->select('n.qty_avs')
        ->select('n.pertama')
        ->select('n.kedua')
        ->select('n.ketiga')
        ->select('n.keempat')
        ->select('n.kelima')
        ->select('n.keenam')
        ->select('n.average')
        ->select('n.s_l')
        ->select('n.qty_intransit')
        ->select('ROUND(n.qty_suggest) AS qty_suggest')
        ->select('ROUND(n.qty_reguler) AS qty_reguler', false)
        ->select('( ROUND(n.qty_reguler) * n.hpp) as amount_qty_reguler', false)
        ->select('n.fix_order_n')
        ->select('(n.fix_order_n * n.hpp) as amount_fix_order_n')
        ->select('n.fix_order_n_1')
        ->select('(n.fix_order_n_1 * n.hpp) as amount_fix_order_n_1')
        ->select('n.fix_order_n_2')
        ->select('(n.fix_order_n_2 * n.hpp) as amount_fix_order_n_2')
        ->select('n.fix_order_n_3')
        ->select('(n.fix_order_n_3 * n.hpp) as amount_fix_order_n_3')
        ->select('n.fix_order_n_4')
        ->select('(n.fix_order_n_4 * n.hpp) as amount_fix_order_n_4')
        ->select('n.fix_order_n_5')
        ->select('(n.fix_order_n_5 * n.hpp) as amount_fix_order_n_5')
        ->select('n.updated_at')
        ;
    }

    private function make_query()
    {
        $this->db
        ->from('tr_h3_md_niguri as n')
        ->join('ms_part as p', 'p.id_part_int = n.id_part_int')
        ->join('tr_h3_md_niguri_header as nh', 'nh.id = n.id_niguri_header')
        ->where('n.id_niguri_header', $this->input->post('id_niguri_header'))
        ;
    }

    private function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('n.id_part', $search);
            $this->db->group_end();
        }

        if($this->input->post('id_kelompok_part_filter') != null && count($this->input->post('id_kelompok_part_filter')) > 0){
            $this->db->where_in('p.kelompok_part', $this->input->post('id_kelompok_part_filter'));
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('n.id_part', 'asc');
        }
    }

    private function limit(){
        if ($this->input->post('length') != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    private function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    private function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }

    public function get_total_fix_order(){
        $this->db->select("SUM( n.{$this->input->post('update_key')} * p.harga_md_dealer ) as amount");
        $this->make_datatables();
        $this->db->where("n.{$this->input->post('update_key')} > 0", null, false);

        send_json($this->db->get()->row_array()['amount']);
    }
}
