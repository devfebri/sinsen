<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Part_dealer extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
        $this->load->model('h3_analisis_ranking_model', 'analisis_ranking');
        $this->load->model('h3_dealer_stock_model', 'dealer_stock');
        $this->load->model('h3_md_purchase_order_parts_model', 'purchase_parts');
    }

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = [];
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $analisis_ranking = $this->analisis_ranking->get_analisis_ranking($this->m_admin->cari_dealer(), $row['id_part']);
            $row['rank'] = $analisis_ranking['rank'];
            $row['order_md'] = $this->purchase_parts->qty_on_order_md($this->m_admin->cari_dealer(), $row['id_part']);
            $row['stock'] = $this->dealer_stock->qty_on_hand($this->m_admin->cari_dealer(), $row['id_part']);

            $row['action'] = $this->load->view('additional/action_part_dealer', [
                'id_part' => $row['id_part'],
            ], true);
            $row['index'] = $this->input->post('start') + $index . '.';
            $index++;

            $data[] = $row;
        }

        $output = array(
            'draw' => intval($_POST['draw']), 
            'recordsFiltered' => $this->get_filtered_data(), 
            'recordsTotal' => $this->get_total_data(),
            'data' => $data
        );

        send_json($output);
    }

    public function make_query() {
        $order_md = $this->db
        ->select('sum(pop.kuantitas)')
        ->from('tr_h3_dealer_purchase_order_parts as pop')
        ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = pop.po_id')
        ->where('pop.id_part = p.id_part')
        ->where('po.id_dealer', $this->m_admin->cari_dealer())
        ->where('po.status', 'Processed by MD')
        ->get_compiled_select();

        $stock = $this->db
        ->select('sum(ds.stock)')
        ->from('ms_h3_dealer_stock as ds')
        ->where('ds.id_part = p.id_part')
        ->where('ds.id_dealer', $this->m_admin->cari_dealer())
        ->get_compiled_select();

         
        if($this->config->item('ahm_d_only')){
            $this->db
            ->select('p.id_part')
            ->select('p.nama_part')
            ->select('p.status')
            ->select('IFNULL(dmp.min_stok, 0) as min_stok')
            ->select('IFNULL(dmp.maks_stok, 0) as maks_stok')
            ->from('ms_part as p')
            ->join('ms_h3_dealer_master_part as dmp', "(dmp.id_part = p.id_part and dmp.id_dealer = '{$this->m_admin->cari_dealer()}')", 'left')
            ->where("p.kelompok_part !='FED OIL'");
        }else{
            $this->db
            ->select('p.id_part')
            ->select('p.nama_part')
            ->select('p.status')
            ->select('IFNULL(dmp.min_stok, 0) as min_stok')
            ->select('IFNULL(dmp.maks_stok, 0) as maks_stok')
            ->from('ms_part as p')
            ->join('ms_h3_dealer_master_part as dmp', "(dmp.id_part = p.id_part and dmp.id_dealer = '{$this->m_admin->cari_dealer()}')", 'left')
            // ->join('ms_h3_analisis_ranking as ar', "(ar.id_part = p.id_part and ar.id_dealer = '{$this->m_admin->cari_dealer()}')", 'left')
            // ->where('ar.id_dealer', $this->m_admin->cari_dealer())
            ;
        }
        
        if($this->config->item('ahm_d_only')){
            $this->db->where("p.kelompok_part !='FED OIL'");
        }
    }

    public function make_datatables() {
        $this->make_query();
        
        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('p.nama_part', $search);
            $this->db->or_like('p.id_part', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('p.id_part', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function get_filtered_data() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function get_total_data() {
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}