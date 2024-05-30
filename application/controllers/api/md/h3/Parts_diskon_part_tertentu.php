
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Parts_diskon_part_tertentu extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_parts_diskon_part_tertentu', [
                'data' => json_encode($row),      
                'disabled' => $row['disabled']
            ], true);
            $data[] = $row;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
        ]);
    }
    
    public function make_query() {
        $this->db
        ->select('p.*')
        ->select('
            case
                when dpt.id is null then 0
                else 1
            end disabled
        ', false)
        ->select('
            concat(
                "Rp ",
                format(p.harga_dealer_user, 0, "ID_id")
            ) as het
        ', false)
        ->from('ms_part as p')
        ->join('ms_h3_md_diskon_part_tertentu as dpt', 'dpt.id_part_int = p.id_part_int', 'left')
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
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

    public function recordsFiltered() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal() {
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
