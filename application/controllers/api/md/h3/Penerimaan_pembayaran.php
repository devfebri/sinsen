<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Penerimaan_pembayaran extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->library('Mcarbon');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_h3_md_penerimaan_pembayaran', [
                'id_penerimaan_pembayaran' => $row['id_penerimaan_pembayaran']
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
        ->select('pb.id_penerimaan_pembayaran')
        ->select('pb.tanggal_proses')
        ->select('ifnull(d.nama_dealer, "-") as nama_dealer')
        ->select('ifnull(gd.group_dealer, "-") as group_dealer')
        ->select('date_format(pb.tanggal_bap, "%d-%m-%Y") as tanggal_bap')
        ->select('
        case
            when pb.tanggal_bap is not null then date_format(pb.tanggal_bap, "%d-%m-%Y")
            else "-"
        end as tanggal_bap', false)
        ->select('pb.jenis_pembayaran')
        ->select('
            concat(
                "Rp ",
                format(pb.total_pembayaran, 0, "ID_id")
            ) as total_pembayaran
        ')
        ->from('tr_h3_md_penerimaan_pembayaran as pb')
        ->join('ms_dealer as d', 'd.id_dealer = pb.id_dealer', 'left')
        ->join('ms_group_dealer as gd', 'gd.id_group_dealer = pb.id_group_dealer', 'left')
        ->join('ms_karyawan as debt_collector', 'debt_collector.id_karyawan = pb.id_debt_collector', 'left')
        ;

        if($this->input->post('history') != null AND $this->input->post('history') == 1){
            $this->db->group_start();
                $this->db->where('left(pb.created_at,10) <=', '2023-09-19');
                // $this->db->or_where('left(dso.created_at,10) <=', '2023-09-08');
            $this->db->group_end();
        }else{
            $this->db->group_start();
                $this->db->where('left(pb.created_at,10) >', '2023-09-19');
                    // $this->db->where('dso.status =', 'On Process');
                    // $this->db->or_where('left(so.created_at,10) <=', '2023-09-08');
            $this->db->group_end();
        }

        if ($this->input->post('id_group_dealer_filter')) {
            $this->db->where('pb.id_group_dealer', $this->input->post('id_group_dealer_filter'));
        }

        if ($this->input->post('id_customer_filter')) {
            $this->db->where('pb.id_dealer', $this->input->post('id_customer_filter'));
        }

        if ($this->input->post('no_penerimaan_pembayaran_filter')) {
            $this->db->like('pb.id_penerimaan_pembayaran', $this->input->post('no_penerimaan_pembayaran_filter'));
        }

        if($this->input->post('tanggal_bap_filter_start') != null and $this->input->post('tanggal_bap_filter_end') != null){            
            $this->db->group_start();
            $this->db->where("pb.tanggal_bap between '{$this->input->post('tanggal_bap_filter_start')}' AND '{$this->input->post('tanggal_bap_filter_end')}'", null, false);
            $this->db->group_end();
        }

        if($this->input->post('tanggal_penerimaan_filter_start') != null and $this->input->post('tanggal_penerimaan_filter_end') != null){            
            $tanggal_penerimaan_filter_start = Mcarbon::parse($this->input->post('tanggal_penerimaan_filter_start'))->startOfDay()->toDateTimeString();
            $tanggal_penerimaan_filter_end = Mcarbon::parse($this->input->post('tanggal_penerimaan_filter_end'))->endOfDay()->toDateTimeString();
            $this->db->group_start();
            $this->db->where("pb.created_at between '{$tanggal_penerimaan_filter_start}' AND '{$tanggal_penerimaan_filter_end}'", null, false);
            $this->db->group_end();
        }

        if ($this->input->post('id_debt_collector_filter')) {
            $this->db->like('pb.id_debt_collector', $this->input->post('id_debt_collector_filter'));
        }

        if ($this->input->post('jenis_pembayaran_filter')) {
            $this->db->where('pb.jenis_pembayaran', $this->input->post('jenis_pembayaran_filter'));
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        // $search = $this->input->post('search') ['value'];
        // if ($search != '') {
        //     $this->db->like('pb.id_pe', $search);
        //     $this->db->or_like('d.nama_dealer', $search);
        //     $this->db->or_like('wp.nama', $search);
        // }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pb.created_at', 'desc');
        }
    }

    public function limit(){
        if ($this->input->post('length') != - 1) {
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
