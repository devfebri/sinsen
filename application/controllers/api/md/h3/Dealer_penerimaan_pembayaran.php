<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dealer_penerimaan_pembayaran extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_dealer_penerimaan_pembayaran', [
                'data' => json_encode($row),
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
    
    public function make_query() {
        if($this->input->post('id_debt_collector') != null){
            $dealer_yang_ditangani_debt_collector = $this->db
            ->select('DISTINCT(ar.id_dealer) as id_dealer')
            ->from('tr_h3_md_berita_acara_penyerahan_faktur as bap')
            ->join('tr_h3_md_berita_acara_penyerahan_faktur_item as bapi', 'bapi.no_bap = bap.no_bap')
            ->join('tr_h3_md_ar_part as ar', 'ar.referensi = bapi.no_faktur')
            ->where('bap.id_debt_collector', $this->input->post('id_debt_collector'))
            ->get_compiled_select();

            $this->db->where("d.id_dealer IN (({$dealer_yang_ditangani_debt_collector}))", null, false);
        }

        $this->db
        ->select('d.id_dealer')
        ->select('d.kode_dealer_md')
        ->select('d.nama_dealer')
        ->select('d.alamat')
        ->from('ms_dealer as d')
        ->join('ms_group_dealer_detail as gdd', 'gdd.id_dealer = d.id_dealer', 'left')
        ->join('ms_group_dealer as gd', 'gd.id_group_dealer = gdd.id_group_dealer', 'left')
        ;

        if ($this->input->post('id_group_dealer') != null) {
            $this->db->where('gd.id_group_dealer', $this->input->post('id_group_dealer'));
        }

        
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('d.kode_dealer_md', $search);
            $this->db->or_like('d.nama_dealer', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('d.nama_dealer', 'asc');
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
        return $this->db->count_all_results();
    }
}
