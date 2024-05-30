<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Part_purchase_order extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        $this->load->model('m_admin');
        $this->load->model('H3_md_ms_sim_part_model', 'sim_part');
        $this->load->model('h3_dealer_stock_model', 'dealer_stock');
        $this->load->model('h3_md_purchase_order_parts_model', 'purchase_parts');
        $this->load->model('h3_analisis_ranking_model', 'analisis_ranking');
        $this->load->model('h3_dealer_master_part_model', 'dealer_master_part');
    }

    public function index()
    {
        $this->make_datatables(); 
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['sim_part'] = $this->sim_part->qty_sim_part($this->m_admin->cari_dealer(), $row['id_part_int']);
            $row['stock'] = $this->dealer_stock->qty_on_hand($this->m_admin->cari_dealer(), $row['id_part']);
            $row['order_md'] = $this->purchase_parts->qty_on_order_md($this->m_admin->cari_dealer(), $row['id_part']);
            $row['qty_in_transit'] = $this->dealer_stock->qty_intransit_md($this->m_admin->cari_dealer(), $row['id_part']);

            $row = array_merge($row, $this->analisis_ranking->get_analisis_ranking($this->m_admin->cari_dealer(), $row['id_part']));
            $row = array_merge($row, $this->dealer_master_part->get_dealer_master_part($this->m_admin->cari_dealer(), $row['id_part']));

            // $row['action'] = $this->load->view('additional/action_part_purchase_order_datatable', [
            //     'data' => json_encode($row),
            //     'id_part' => $row['id_part']
            // ], true);
            if($this->input->post('po_type')=='HLO'){
                $row['action'] = $this->load->view('additional/action_part_purchase_order_datatable_hlo', [
                    'data' => json_encode($row),
                    'id_part' => $row['id_part'],
                    'hoo_max' => $row['hoo_max'],
                    'import_lokal' => $row['import_lokal'],
                    'current' => $row['current']
                ], true);
                // $row['action'] = 'HLO';
            }else{
                $row['action'] = $this->load->view('additional/action_part_purchase_order_datatable', [
                    'data' => json_encode($row),
                    'id_part' => $row['id_part']
                ], true);
                // $row['action'] = 'Tidak HLO';
            }
            $data[] = $row;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data
        ]);
    }

    public function make_query()
    {
        $this->db
        ->select('p.id_part_int')
        ->select('p.id_part')
        ->select('p.kelompok_vendor')
        ->select('p.nama_part')
        ->select('concat("Rp ", format(p.harga_dealer_user, 0, "id_ID")) as harga_dealer_user')
        ->select('p.harga_dealer_user as harga_saat_dibeli')
        ->select('1 as kuantitas')
        ->select("0 as diskon_value")
        ->select("'' as tipe_diskon")
        ->select("0 as diskon_value_campaign")
        ->select("'' as tipe_diskon_campaign")
        ->select('p.import_lokal')
        ->select('p.current')
        ->select('p.hoo_flag')
        ->select('p.hoo_max')
        // ->select('ROUND(IFNULL(ar.avg_six_weeks, 0), 0) AS avg_six_weeks')
		// ->select('IFNULL(ar.w1, 0) AS w1')
		// ->select('IFNULL(ar.w2, 0) AS w2')
		// ->select('IFNULL(ar.w3, 0) AS w3')
		// ->select('IFNULL(ar.w4, 0) AS w4')
		// ->select('IFNULL(ar.w5, 0) AS w5')
		// ->select('IFNULL(ar.w6, 0) AS w6')
		// ->select('IFNULL(ar.stock_days, 0) AS stock_days')
		// ->select('ar.rank')
        ->from('ms_part as p')
        ->join('ms_h3_md_setting_kelompok_produk as skp', "(skp.id_kelompok_part_int = p.kelompok_part_int and skp.produk = '{$this->input->post('produk')}')")
        // ->join('ms_h3_analisis_ranking as ar', "ar.id_dealer = {$this->m_admin->cari_dealer()} and ar.id_part = p.id_part", 'left')
        ;

        if($this->input->post('kategori_po') != null and $this->input->post('kategori_po') == 'SIM Part'){
            $this->db->where('p.sim_part', 1);
        }else{
            $this->db->where('p.sim_part', 0);
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        // $search = trim($this->input->post('search') ['value']);
        // if ($search != '') {
        //     $this->db->group_start();
        //     $this->db->like('p.id_part', $search);
        //     $this->db->or_like('p.nama_part', $search);
        //     $this->db->group_end();
        // }

        
        $cari_kode_part = $this->input->post('cari_kode_part');
        $cari_nama_part = $this->input->post('cari_nama_part');

        if($cari_kode_part != ''){
            $this->db->like('p.id_part', $cari_kode_part);
        }

        if($cari_nama_part != ''){
            $this->db->like('p.nama_part', $cari_nama_part);
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('p.id_part', 'asc');
        }
    }

    public function limit(){
        if ($this->input->post('length') != - 1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        return $this->db->from('ms_part')->count_all_results();
    }
}