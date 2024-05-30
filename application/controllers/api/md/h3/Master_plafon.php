<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_plafon extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['checkbox'] = $this->load->view('additional/md/h3/action_check_plafon_md', [
                'id' => $row['id'],
                'plafon_id' => $this->session->userdata('plafon_id_marketing') != null ? $this->session->userdata('plafon_id_marketing') : []
            ], true);

            $row['action'] = $this->load->view('additional/md/h3/action_index_h3_md_master_plafon', [
                'id' => $row['id']
            ], true);

            $row['index'] = $this->input->post('start') + $index . '.';
            $index++;
            $data[] = $row;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
        ]);
    }

    public function make_query()
    {
        $this->db
        ->select('plafon.id')
        ->select('d.kode_dealer_md')
        ->select('d.nama_dealer')
        ->select('d.status_dealer')
        ->select('plafon.plafon_awal')
        ->select('plafon.sisa_plafon')
        ->select('
            case
                when plafon.create_by_finance = 1 AND plafon.status = "Approved by Pimpinan" then plafon.nilai_penambahan_plafon_pimpinan
                else plafon.nilai_penambahan_plafon
            end as nilai_penambahan_plafon
        ', false)
        ->select('
        case
            when plafon.create_by_finance = 1 AND plafon.status = "Approved by Pimpinan" then plafon.nilai_penambahan_sementara_pimpinan
            else plafon.nilai_penambahan_sementara
        end as nilai_penambahan_sementara
        ', false)
        ->select('
        case
            when plafon.create_by_finance = 1 AND plafon.status = "Approved by Pimpinan" then plafon.nilai_pengurang_plafon_pimpinan
            else plafon.nilai_pengurang_plafon
        end as nilai_pengurang_plafon
        ', false)
        ->select('plafon.keterangan')
        ->select('plafon.status')
        ->from('ms_h3_md_plafon as plafon')
        ->join('ms_dealer as d', 'd.id_dealer = plafon.id_dealer')
        ;

        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->group_start();
            $this->db->where('plafon.status', 'Approved by Pimpinan');
            $this->db->or_where('left(plafon.created_at,10) <=', '2023-09-08');
            $this->db->group_end();
        }else{
            $this->db->group_start();
            $this->db->where('plafon.status !=', 'Approved by Pimpinan');
            $this->db->where('left(plafon.created_at,10) >', '2023-09-08');
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('d.nama_dealer', $search);
            $this->db->or_like('d.kode_dealer_md', $search);
            $this->db->group_end();
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('plafon.created_at', 'desc');
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
        return $this->db->get()->num_rows();
    }

    public function set_plafon_id(){
        $id = $this->input->get('id');
        $plafon_id_marketing = $this->session->userdata('plafon_id_marketing') != null ? $this->session->userdata('plafon_id_marketing') : [];
        if(!in_array($id, $plafon_id_marketing)){
            $plafon_id_marketing[] = $id;
            $this->session->set_userdata('plafon_id_marketing', $plafon_id_marketing);
        }

        send_json($this->session->userdata('plafon_id_marketing'));
    }

    public function unset_plafon_id(){
        $id = $this->input->get('id');
        $plafon_id_marketing = $this->session->userdata('plafon_id_marketing') != null ? $this->session->userdata('plafon_id_marketing') : [];
        if(count($plafon_id_marketing) > 0){
            foreach($plafon_id_marketing as $index => $plafon_id){
                if($plafon_id == $id){
                    unset($plafon_id_marketing[$index]);
                    break;
                }
            }
        }

        $this->session->set_userdata('plafon_id_marketing', $plafon_id_marketing);

        send_json($this->session->userdata('plafon_id_marketing'));
    }
}
