<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Parts_diskon_oli_reguler extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $fetch_data = $this->db->get()->result();
        $data = array();
        foreach ($fetch_data as $row) {
            $sub_array = (array) $row;
            $sub_array['action'] = $this->load->view('additional/md/h3/action_parts_diskon_oli_reguler', [
                'data' => json_encode($row),
                'selected' => $row->selected
            ], true);
            $data[] = $sub_array;
        }
        $output = [
            'draw' => intval($_POST["draw"]),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_record_total(),
            'data' => $data
        ];
        send_json($output);
    }
    
    public function make_query() {
        $kelompok_produk_oli = $this->db
        ->select('skp.id_kelompok_part')
        ->from('ms_h3_md_setting_kelompok_produk as skp')
        ->where('skp.produk', 'Oil')
        ->get_compiled_select();

        $part_yang_sudah_didaftar_diskon = $this->db
        ->select('id_part')
        ->from('ms_h3_md_diskon_oli_reguler')
        ->get_compiled_select();

        $this->db
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
        ->select("p.id_part in ({$part_yang_sudah_didaftar_diskon}) as selected")
        ->from('ms_part as p')
        ->where("p.kelompok_part in ({$kelompok_produk_oli})")
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
            $this->db->order_by('p.id_part', 'asc');
        }

        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function get_filtered_data() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function get_record_total() {
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
