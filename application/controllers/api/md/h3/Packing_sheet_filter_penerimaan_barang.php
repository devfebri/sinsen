<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Packing_sheet_filter_penerimaan_barang extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['action'] = $this->load->view('additional/md/h3/action_packing_sheet_penerimaan_barang', [
                'data' => json_encode($row),
                'packing_sheet_number_int' => $row['packing_sheet_number_int'],
                'packing_sheet_number' => $row['packing_sheet_number'],
                'surat_jalan_ahm' => $row['surat_jalan_ahm'],
                'surat_jalan_ahm_int' => $row['surat_jalan_ahm_int'],
            ], true);
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
        $nomor_karton_diterima = $this->db
        ->select('COUNT(pbi.nomor_karton)')
        ->from('tr_h3_md_penerimaan_barang_items as pbi')
        ->where('pbi.surat_jalan_ahm_int = psli.surat_jalan_ahm_int')
        ->where('pbi.tersimpan', 1)
        ->get_compiled_select();

        $packing_sheet_ada_di_faktur = $this->db
        ->select('DISTINCT(fdo_ps.packing_sheet_number_int) as packing_sheet_number_int')
        ->from('tr_h3_md_fdo_ps as fdo_ps')
        ->join('tr_h3_md_psl_items as psli', 'psli.packing_sheet_number_int = fdo_ps.packing_sheet_number_int')
        ->get_compiled_select();

        $this->db
        ->select('psli.surat_jalan_ahm_int')
        ->select('psli.surat_jalan_ahm')
        ->select('psli.packing_sheet_number_int')
        ->select('psp.packing_sheet_number')
        ->select('date_format(ps.packing_sheet_date, "%d/%m/%Y") as packing_sheet_date')
        ->from('tr_h3_md_psl_items as psli')
        ->join('tr_h3_md_ps_parts as psp', 'psp.packing_sheet_number = psli.packing_sheet_number')
        ->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = psp.packing_sheet_number')
        ->group_start()
        ->where_in('psli.surat_jalan_ahm_int', $this->input->post('surat_jalan_ahm_int'))
        ->where("ifnull(({$nomor_karton_diterima}), 0) != ps.jumlah_karton", null, false) // 90% waktu proses query disebabkan karena query ini
        ->where("psli.packing_sheet_number_int IN (({$packing_sheet_ada_di_faktur}))", null, false)
        ->group_end()
        ->group_by('psp.packing_sheet_number_int');
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('psp.no_doos', $search);
            $this->db->or_like('psp.packing_sheet_number', $search);
            $this->db->or_like('psli.surat_jalan_ahm', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('psp.no_doos', 'asc');
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