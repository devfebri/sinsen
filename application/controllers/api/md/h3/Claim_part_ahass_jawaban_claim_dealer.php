<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Claim_part_ahass_jawaban_claim_dealer extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_claim_part_ahass_jawaban_claim_dealer', [
                'data' => json_encode($row)
            ], true);
            $row['index'] = $this->input->post('start') + $index;
            $data[] = $row;

            $index++;
        }
        
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_total_data(),
            'data' => $data
        ]);
    }
    
    public function make_query() {
        $qty_part_dikirim_ke_md = $this->db
        ->select('SUM(cdp.qty_part_dikirim_ke_md) as qty_part_dikirim_ke_md')
        ->from('tr_h3_md_claim_part_ahass_parts as cpap')
        ->join('tr_h3_md_claim_dealer_parts as cdp', '(cdp.id_claim_dealer = cpap.id_claim_dealer and cdp.id_part = cpap.id_part and cdp.id_kategori_claim_c3 = cpap.id_kategori_claim_c3)')
        ->where('cpap.id_claim_part_ahass = cpa.id_claim_part_ahass')
        ->get_compiled_select();

        $qty_jawaban_claim = $this->db
        ->select('
            SUM(
                case
                    when jcdp.barang_checklist then jcdp.qty_barang
                    when jcdp.uang_checklist then jcdp.qty_uang
                    when jcdp.tolak_checklist then jcdp.qty_tolak
                end
            ) as qty
        ', false)
        ->from('tr_h3_md_claim_part_ahass_parts as cpap')
        ->join('tr_h3_md_jawaban_claim_dealer_parts as jcdp', '(jcdp.id_claim_dealer = cpap.id_claim_dealer and jcdp.id_part = cpap.id_part and jcdp.id_kategori_claim_c3 = cpap.id_kategori_claim_c3 and jcdp.pending = 0)')
        ->where('cpap.id_claim_part_ahass = cpa.id_claim_part_ahass')
        ->get_compiled_select();

        $this->db
        ->select('cpa.id_claim_part_ahass')
        // ->select("IFNULL(({$qty_part_dikirim_ke_md}), 0) as qty_part_dikirim_ke_md")
        // ->select("IFNULL(({$qty_jawaban_claim}), 0) as qty_jawaban_claim")
        ->select('date_format(cpa.created_at, "%d-%m-%Y") as created_at')
        ->from('tr_h3_md_claim_part_ahass as cpa')
        ->where('cpa.status !=', 'Canceled')
        ->where("IFNULL(({$qty_part_dikirim_ke_md}), 0) != IFNULL(({$qty_jawaban_claim}), 0)", null, false)
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('cpa.id_claim_part_ahass', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('cpa.id_claim_part_ahass', 'ASC');
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
        return $this->db->count_all_results();
    }
}
