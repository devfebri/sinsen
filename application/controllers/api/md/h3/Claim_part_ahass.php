<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Claim_part_ahass extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['customer_diclaim'] = $this->load->view('additional/md/h3/action_open_view_customer_claim_part_ahass', [
                'id_claim_part_ahass' => $row['id_claim_part_ahass'],
                'customer_diclaim' => $row['customer_diclaim'],
            ], true);

            $row['action'] = $this->load->view('additional/md/h3/action_index_claim_part_ahass_datatable', [
                'id_claim_part_ahass' => $row['id_claim_part_ahass']
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
        $customer_diclaim = $this->db
        ->select('COUNT( DISTINCT(cd.id_dealer) ) as count_dealer')
        ->from('tr_h3_md_claim_part_ahass_parts as cpap')
        ->join('tr_h3_md_claim_dealer as cd', 'cd.id_claim_dealer = cpap.id_claim_dealer')
        ->where('cpap.id_claim_part_ahass = cpa.id_claim_part_ahass')
        ->get_compiled_select();

        $this->db
        ->select('date_format(cpa.created_at, "%d-%m-%Y") as created_at')
        ->select('cpa.id_claim_part_ahass')
        ->select('cpa.packing_sheet_number')
        ->select('date_format(ps.packing_sheet_date, "%d/%m/%Y") as packing_sheet_date')
        ->select('fdo.invoice_number')
        ->select('date_format(fdo.invoice_date, "%d/%m/%Y") as invoice_date')
        ->select("IFNULL( ({$customer_diclaim}), 0) as customer_diclaim", false)
        ->select('cpa.status')
        ->from('tr_h3_md_claim_part_ahass as cpa')
        ->join('tr_h3_md_ps as ps', 'ps.id = cpa.packing_sheet_number_int', 'left')
        ->join('tr_h3_md_fdo as fdo', 'fdo.id = ps.invoice_number_int', 'left')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->or_like('cpa.id_claim_part_ahass', $search);
            $this->db->or_like('cpa.packing_sheet_number', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('cpa.created_at', 'desc');
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
        return $this->db->count_all_results();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}
