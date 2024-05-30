<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reason_penerimaan_barang extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        // $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            

            // $row['index'] = $this->input->post('start') + $index;

            $data[] = $row;
            // $index++;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_total_data(),
            'data' => $data
        ]);
    }
    
    public function make_query() {
        $this->db
        ->select('pbi.id ,pb.no_penerimaan_barang, ps.packing_sheet_number, pbi.nomor_karton, pbi.id_part, mkcc.nama_claim, reason.qty, mkcc.tipe_claim,reason.keterangan,mkcc.kode_claim')
        ->from('tr_h3_md_ps as ps')
        ->join('tr_h3_md_ps_parts as psp','ps.id=psp.packing_sheet_number_int','left')
        ->join('tr_h3_md_penerimaan_barang_items as pbi','pbi.packing_sheet_number_int=ps.id and pbi.nomor_karton = psp.no_doos and pbi.no_po = psp.no_po','left')
        ->join('tr_h3_md_penerimaan_barang as pb',' pb.id=pbi.no_penerimaan_barang_int')   
        ->join('tr_h3_md_penerimaan_barang_reasons as reason','reason.id_penerimaan_barang_item=pbi.id','left')
        ->join('ms_kategori_claim_c3 as mkcc','mkcc.id=reason.id_claim','left')
        ->where('pbi.nomor_karton', $this->input->post('no_karton'))
        ->where('pbi.no_penerimaan_barang',$this->input->post('no_penerimaan_barang'))
        ->where('pbi.id_part',$this->input->post('id_part'))
        ->where('reason.checked','1')
        ;
    }

    public function make_datatables() {
        $this->make_query();

        // $search = $this->input->post('search')['value'];
        // if ($search != '') {
        //     $this->db->group_start();
        //     $this->db->like('do.id_do_sales_order', $search);
        //     $this->db->group_end();
        // }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            // $this->db->order_by('do.created_at', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function get_filtered_data() {
        $this->make_datatables();
        return $this->db->count_all_results();
    }

    public function get_total_data() {
        $this->make_query();
        return $this->db->count_all_results();
    }
}
