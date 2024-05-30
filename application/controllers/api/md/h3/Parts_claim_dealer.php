<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Parts_claim_dealer extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_parts_claim_dealer', [
                'data' => json_encode($row),
                'id_part' => $row['id_part'],
                'sisa_boleh_diclaim' => $row['sisa_boleh_diclaim']
            ], true);
            $data[] = $row;
        }

        send_json([
            'draw' => intval($_POST['draw']), 
            'recordsFiltered' => $this->get_filtered_data(), 
            'recordsTotal' => $this->get_total_data(), 
            'data' => $data
        ]);
    }
    
    public function make_query() {
        $part_sudah_diclaim = $this->db
        ->select('SUM(cdp.qty_part_diclaim) as qty_part_diclaim')
        ->from('tr_h3_md_claim_dealer as cd')
        ->join('tr_h3_md_claim_dealer_parts as cdp', 'cdp.id_claim_dealer = cd.id_claim_dealer')
        ->where('cd.status !=', 'Rejected')
        ->where('cd.status !=', 'Canceled')
        ->where('cd.id_packing_sheet = ps.id_packing_sheet')
        ->where('cdp.id_part = dop.id_part')
        ->where('cdp.keputusan', 'Terima')
        ->get_compiled_select();

        $this->db
        ->select('dop.id_part')
        ->select('p.nama_part')
        ->select('dop.qty_supply as qty_packing_sheet')
        ->select('0 as qty_part_diclaim')
        ->select('0 as qty_part_dikirim_ke_md')
        ->select("IFNULL(({$part_sudah_diclaim}), 0) as part_sudah_diclaim", false)
        ->select("( dop.qty_supply - IFNULL(({$part_sudah_diclaim}), 0) ) as sisa_boleh_diclaim", false)
        ->select('"" as id_kategori_claim_c3')
        ->select('"" as kode_claim')
        ->select('"" as keterangan')
        ->select('"" as keputusan')
        ->from('tr_h3_md_packing_sheet as ps')
        ->join('tr_h3_md_picking_list as pl', 'ps.id_picking_list = pl.id_picking_list')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
        ->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
        ->join('ms_part as p', 'dop.id_part = p.id_part')
        ->where('ps.id_packing_sheet', $this->input->post('id_packing_sheet'))
        ->where('dop.qty_supply >', 0)
        ->where("( dop.qty_supply - IFNULL(({$part_sudah_diclaim}), 0) ) > 0", null, false)
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('p.id_part', $search);
            $this->db->or_like('p.nama_part', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('p.id_part', 'ASC');
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
