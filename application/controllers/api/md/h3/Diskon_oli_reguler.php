<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Diskon_oli_reguler extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->make_datatables();
        $this->limit();
        
        $data = [];
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/action_diskon_oli_reguler', [
                'id' => $row['id']
            ], true);

            $ranges = $this->db
            ->select('
                case
                    when dorgr.tipe_diskon = "Rupiah" then concat(
                        "Rp ",
                        format(dorgr.diskon_value, 0, "ID_id")
                    )
                    when dorgr.tipe_diskon = "Persen" then concat(
                        format(dorgr.diskon_value, 0, "ID_id"),
                        " %"
                    )
                end as diskon
            ', false)
            ->from('ms_h3_md_diskon_oli_reguler_general_ranges as dorgr')
            ->join('ms_h3_md_range_dus_oli as rdo', 'rdo.id = dorgr.id_range_dus_oli')
            ->where('dorgr.id_diskon_oli_reguler', $row['id'])
            ->limit(3)
            ->get()->result_array();

            for ($i=0; $i < 3; $i++) { 
                $row['range_' . ($i + 1)] = isset($ranges[$i]) ? $ranges[$i]['diskon'] : '-';
             }

            $data[] = $row;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data
        ]);
    }
    
    public function make_query() {
        $this->db
        ->select('dor.*')
        ->select('p.id_part')
        ->select('p.nama_part')
        ->select('
            concat(
                "Rp ",
                format(p.harga_dealer_user, 0, "ID_id")
            ) as het
        ', false)
        ->select('p.harga_dealer_user')
        ->select('p.kelompok_part')
        ->select('p.status')
        ->select('date_format(dor.created_at, "%d-%m-%Y") as created_at')
        ->join('ms_part as p', 'dor.id_part = p.id_part')
        ->from('ms_h3_md_diskon_oli_reguler as dor')
        ;

        $active = $this->input->post('active');
        if($active != null){
            $this->db->where('dor.active', $active);
        }
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('dor.id_part', $search);
            $this->db->or_like('p.nama_part', $search);
            $this->db->or_like('p.kelompok_part', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('dor.created_at', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal() {
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
