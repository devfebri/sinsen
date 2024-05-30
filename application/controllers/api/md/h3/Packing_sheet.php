<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Packing_sheet extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();
        $rows = $this->db->get()->result_array();

        $user_group = $this->db
        ->select('ug.user_group')
        ->from('ms_user as u')
        ->join('ms_user_group as ug', 'ug.id_user_group = u.id_user_group')
        ->where('u.id_user', $this->session->userdata('id_user'))
        ->limit(1)
        ->get()->row_array();

        $data = array();
        $index = 1;
        foreach ($rows as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_packing_sheet_datatable', [
                'id' => $row['id'],
                'sudah_print' => $row['sudah_print'],
                'user_group' => $user_group['user_group'],
                'faktur_printed' => $row['faktur_printed'],
            ], true);
            
            $row['index'] = $this->input->post('start') + $index . '.';

            $data[] = $row;
            $index++;
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
        ->select('ps.id')
        ->select('date_format(pl.tanggal, "%d-%m-%Y") as tanggal_picking')
        ->select('pl.id_picking_list')
        ->select('ps.no_faktur')
        ->select('date_format(ps.tgl_faktur, "%d-%m-%Y %H:%i") as tanggal_faktur')
        ->select('d.nama_dealer')
        ->select('d.alamat')
        ->select('
            case
                when ps.tgl_packing_sheet is not null then date_format(ps.tgl_packing_sheet, "%d-%m-%Y %H:%i")
                else "-"
            end tgl_packing_sheet
        ', false)
        ->select('
            case
                when ps.id_packing_sheet is not null then ps.id_packing_sheet
                else "-"
            end id_packing_sheet
        ', false)
        ->select('(ps.id_packing_sheet IS NOT NULL) as sudah_print', false)
        ->select('ps.faktur_printed')
        ->from('tr_h3_md_packing_sheet as ps')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
        ->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
        ;

        if ($this->input->post('history') == 1) {
            $this->db->group_start();
            $this->db->where('ps.id_packing_sheet IS NOT NULL', null, false);
            $this->db->or_where('left(ps.created_at,10) <=','2023-09-30');
            $this->db->group_end();
        }elseif ($this->input->post('history') == 0) {
            $this->db->group_start();
            $this->db->where('ps.id_packing_sheet IS NULL', null, false);
            $this->db->where('left(ps.created_at,10) >','2023-10-01');
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ps.no_faktur', $search);
            $this->db->or_like('pl.id_picking_list', $search);
            $this->db->or_like('ps.id_packing_sheet', $search);
            $this->db->or_like('d.nama_dealer', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ps.created_at', 'desc');
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
