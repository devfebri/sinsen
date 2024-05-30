<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Packing_sheet_penerimaan_barang extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index()
    {
        $records = $this->make_datatables();
        $data = array();
        foreach ($records as $each) {
            $sub_arr = (array) $each;
            $sub_arr['action'] = $this->load->view('additional/md/h3/action_packing_sheet_penerimaan_barang', [
                'data' => json_encode($each)
            ], true);
            $data[] = $sub_arr;
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
        $packing_sheet_yang_sudah_diterima = $this->db
        ->select('packing_sheet_number')
        ->from('tr_h3_md_penerimaan_barang')
        ->get_compiled_select()
        ;

        $this->db
        ->select('ps.*')
        ->select('date_format(ps.packing_sheet_date, "%d-%m-%Y") as packing_sheet_date')
        ->select('date_format(ps.tanggal_po, "%d-%m-%Y") as tanggal_po')
        ->select('psl.surat_jalan_ahm')
        ->from('tr_h3_md_ps as ps')
        ->join('tr_h3_md_psl_items as psl', 'psl.packing_sheet_number = ps.packing_sheet_number')
        // ->where("ps.packing_sheet_number not in ({$packing_sheet_yang_sudah_diterima})")
        // ->where('ps.packing_sheet_number', 'PE2020200100156')
        ;

    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ps.packing_sheet_number', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ps.packing_sheet_date', 'desc');
        }

        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }

    public function recordsFiltered()
    {
        $this->make_query();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}