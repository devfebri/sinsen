<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Po_vendor extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $fetch_data = $this->make_datatables();
        $data = array();
        foreach ($fetch_data as $rs) {
            $sub_array = (array)$rs;
            $row = json_encode($rs);
            $link = '<button data-dismiss=\'modal\' onClick=\'return choose_po_vendor(' . $row . ')\' class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>';
            $sub_array['aksi'] = $link;
            $data[] = $sub_array;
        }
        $output = array("draw" => intval($_POST["draw"]), "recordsFiltered" => $this->get_filtered_data(), "data" => $data);
        echo json_encode($output);
    }
    
    public function make_query() {
        $this->db->select('pov.id_po_vendor, date_format(pov.tanggal, "%d-%m-%Y") as tanggal, mv.vendor_name as nama_vendor')
                ->from("tr_h3_md_po_vendor as pov")
                ->join('ms_vendor as mv', 'pov.id_vendor = mv.id_vendor');

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('mv.id_vendor', $search);
            $this->db->or_like('mv.vendor_name', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('mv.id_vendor', 'ASC');
        }
    }

    public function make_datatables() {
        $this->make_query();
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data() {
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
}
