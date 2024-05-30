<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring_jatuh_tempo_ahm extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            // $row['action'] = $this->load->view('additional/action_monitoring_bg_piutang_belum_cair', [
            //     'id_penerimaan_pembayaran' => $row['id_penerimaan_pembayaran']
            // ], true);

            $data[] = $row;
            $index++;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
            'post' => $_POST
        ]);
    }

    public function get_total(){
        $this->make_datatables();
        $result = $this->db->get()->result_array();
        
        $data = [
            'total_dpp' => 0,
            'total_ppn' => 0,
            'grand_total' => 0,
        ];

        $data['total_dpp'] = array_map(function($row){
            if($row['top_dpp_filtered'] == 1){
                return floatval($row['total_dpp']);
            }
            return 0;
        }, $result);
        $data['total_dpp'] = array_sum($data['total_dpp']);

        $data['total_ppn'] = array_map(function($row){
            if($row['top_ppn_filtered'] == 1){
                return floatval($row['total_ppn']);
            }
            return 0;
        }, $result);
        $data['total_ppn'] = array_sum($data['total_ppn']);

        $data['grand_total'] = $data['total_dpp'] + $data['total_ppn'];

        send_json($data);
    }

    public function make_query()
    {
        $this->db
        ->select('fdo.invoice_number')
        ->select('fdo.invoice_date')
        ->select('fdo.dpp_due_date')
        ->select('fdo.ppn_due_date')
        ->select('fdo.total_dpp')
        ->select('fdo.total_ppn')
        ->select('cg.kode_giro')
        ->select('vp.total_amount')
        ->from('tr_h3_md_fdo as fdo')
        ->join('tr_h3_md_voucher_pengeluaran_items as vpi', '(vpi.id_referensi = fdo.invoice_number)', 'left')
        ->join('tr_h3_md_voucher_pengeluaran as vp', '(vp.id_voucher_pengeluaran = vpi.id_voucher_pengeluaran AND vp.via_bayar = "Giro")', 'left')
        ->join('ms_cek_giro as cg', 'cg.id_cek_giro = vp.id_giro', 'left')
        ;

        if($this->input->post('periode_filter_start') != null AND $this->input->post('periode_filter_end') != null){
            $this->db->select("fdo.dpp_due_date between '{$this->input->post('periode_filter_start')}' AND '{$this->input->post('periode_filter_end')}' as top_dpp_filtered", null, false);
            $this->db->select("fdo.ppn_due_date between '{$this->input->post('periode_filter_start')}' AND '{$this->input->post('periode_filter_end')}' as top_ppn_filtered", null, false);
        }else{
            $this->db->select('0 as top_dpp_filtered');
            $this->db->select('0 as top_ppn_filtered');
        }

        if($this->input->post('history') != null AND $this->input->post('history') == 1){
            $this->db->group_start();
                $this->db->where('left(fdo.created_at,10) <=', '2023-09-30');
                // $this->db->or_where('left(dso.created_at,10) <=', '2023-09-08');
            $this->db->group_end();
        }else{
            $this->db->group_start();
                $this->db->where('left(fdo.created_at,10) >', '2023-10-01');
                    // $this->db->where('dso.status =', 'On Process');
                    // $this->db->or_where('left(so.created_at,10) <=', '2023-09-08');
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('fdo.invoice_number', $search);
            $this->db->group_end();
        }

        if($this->input->post('periode_filter_start') != null AND $this->input->post('periode_filter_end') != null){
            $this->db->group_start();
                $this->db->group_start();
                $this->db->where("fdo.dpp_due_date between '{$this->input->post('periode_filter_start')}' AND '{$this->input->post('periode_filter_end')}'", null, false);
                $this->db->group_end();

                $this->db->or_group_start();
                $this->db->where("fdo.ppn_due_date between '{$this->input->post('periode_filter_start')}' AND '{$this->input->post('periode_filter_end')}'", null, false);
                $this->db->group_end();
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('fdo.created_at', 'desc');
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
        return $this->db->count_all_results();
    }
}
