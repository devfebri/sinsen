<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Parts_penerimaan_barang extends CI_Controller
{
    public function __construct(){
        ini_set('max_execution_time', '0');

        parent::__construct();
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_parts_penerimaan_barang', [
                'data' => json_encode($row),
                'id_part' => $row['id_part'],
                'id_part_int' => $row['id_part_int'],
            ], true);
            $row['index'] = $this->input->post('start') + $index . '.';

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

    public function make_query()
    {
        $this->db
        ->select('psp.id_part_int')
        ->select('psp.id_part')
        ->select('p.nama_part')
        ->from('tr_h3_md_ps_parts as psp')
        ->join('ms_part as p', 'p.id_part_int = psp.id_part_int')
        ->join('tr_h3_md_psl_items as psli', 'psli.packing_sheet_number_int = psp.packing_sheet_number_int')
        ->group_by('psp.id_part')
        ;

        if (count($this->input->post('list_surat_jalan_ahm')) > 0) {
            $this->db->where_in('psli.surat_jalan_ahm_int', $this->input->post('list_surat_jalan_ahm'));
        }else{
            $this->db->where(false);
        }

        if (count($this->input->post('list_packing_sheet_number_int')) > 0) {
            $this->db->where_in('psp.packing_sheet_number_int', $this->input->post('list_packing_sheet_number_int'));
        }

        if (count($this->input->post('list_nomor_karton')) > 0) {
            $this->db->where_in('psp.no_doos', $this->input->post('list_nomor_karton'));
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('psp.id_part', $search);
            $this->db->or_like('p.nama_part', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('psp.id_part', 'asc');
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