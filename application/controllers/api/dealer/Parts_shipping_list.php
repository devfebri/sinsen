<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Parts_shipping_list extends CI_Controller
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
            $sub_arr['action'] = $this->load->view('additional/action_parts_shipping_list_datatable', [
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
        $qty_diproses = $this->db
        ->select('sum(pbi.qty)')
        ->from('tr_h3_dealer_penerimaan_barang as pb')
        ->join('tr_h3_dealer_penerimaan_barang_items as pbi', '(pb.id_penerimaan_barang = pbi.id_penerimaan_barang)')
        ->where('pb.id_packing_sheet', $this->input->post('id_packing_sheet'))
        ->where('(splp.id_part = pbi.id_part and splp.no_dus = pbi.no_dus)')
        ->get_compiled_select();

        $id_rak_terakhir = $this->db
        ->select('pbi_for_rak.id_rak')
        ->from('tr_h3_dealer_penerimaan_barang_items as pbi_for_rak')
        ->join('tr_h3_dealer_penerimaan_barang as pb_for_rak', 'pb_for_rak.id_penerimaan_barang = pbi_for_rak.id_penerimaan_barang')
        ->where('(pb_for_rak.id_packing_sheet = ps.id_packing_sheet and pbi_for_rak.id_part = p.id_part)')
        ->order_by('pb_for_rak.created_at', 'DESC')
        ->limit(1)
        ->get_compiled_select();

        $id_gudang_terakhir = $this->db
        ->select('pbi_for_gudang.id_rak')
        ->from('tr_h3_dealer_penerimaan_barang_items as pbi_for_gudang')
        ->join('tr_h3_dealer_penerimaan_barang as pb_for_gudang', 'pb_for_gudang.id_penerimaan_barang = pbi_for_gudang.id_penerimaan_barang')
        ->where('(pb_for_gudang.id_packing_sheet = ps.id_packing_sheet and pbi_for_gudang.id_part = p.id_part)')
        ->order_by('pb_for_gudang.created_at', 'DESC')
        ->limit(1)
        ->get_compiled_select();

        $this->db
        ->select('p.nama_part')
        ->select('p.id_part')
        ->select('splp.qty_scan as qty_ship')
        ->select('splp.no_dus')
        ->select('"Good" as tipe_penerimaan')
        ->select('"" as alasan_bad')
        ->select("({$id_rak_terakhir}) as id_rak")
        ->select("({$id_gudang_terakhir}) as id_gudang")
        ->select("ifnull(({$qty_diproses}), 0) as qty_sudah_terima")
        ->select("splp.qty_scan - ifnull(({$qty_diproses}), 0) as qty_belum_terima")
        ->select("splp.qty_scan - ifnull(({$qty_diproses}), 0) as qty")
        ->from('tr_h3_md_packing_sheet as ps')
        ->join('tr_h3_md_picking_list as pl', 'ps.id_picking_list = pl.id_picking_list')
        ->join('tr_h3_md_picking_list_parts as plp', 'pl.id_picking_list = plp.id_picking_list')
        ->join('tr_h3_md_scan_picking_list_parts as splp', '(splp.id_picking_list = plp.id_picking_list and splp.id_part = plp.id_part)')
        ->join('ms_part as p', 'p.id_part = plp.id_part')
        ->where('ps.id_packing_sheet', $this->input->post('id_packing_sheet'))
        ->where("splp.qty_scan - ifnull(({$qty_diproses}), 0) > 0")
        ;
        // echo $this->db->get_compiled_select(); die();
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('plp.id_part', $search);
            $this->db->or_like('p.nama_part', $search);
            $this->db->or_like('splp.no_dus', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ps.id_packing_sheet', 'ASC');
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