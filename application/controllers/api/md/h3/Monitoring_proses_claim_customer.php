<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring_proses_claim_customer extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = [];
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
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
        ->select('cd.id_claim_dealer')
        ->select('date_format(cd.created_at, "%d/%m/%Y") as tanggal_claim_customer')
        ->select('d.kode_dealer_md')
        ->select('d.nama_dealer')
        ->select('jcdp.id_part')
        ->select('jcdp.id_jawaban_claim_dealer')
        ->select('p.nama_part')
        ->select('cdp.qty_part_diclaim')
        ->select('
			case
				when jcdp.barang_checklist = 1 then jcdp.qty_barang
				when jcdp.uang_checklist = 1 then jcdp.qty_uang
				when jcdp.tolak_checklist = 1 then jcdp.qty_tolak
				else 0
			end as qty_pergantian
		', false)
		->select('
			case
				when jcdp.barang_checklist = 1 then "Ganti Barang"
				when jcdp.uang_checklist = 1 then "Ganti Uang"
				when jcdp.tolak_checklist = 1 then "Ganti Tolak"
				else "-"
			end as tipe_pergantian
		', false)
        ->from('tr_h3_md_jawaban_claim_dealer_parts as jcdp')
        ->join('tr_h3_md_jawaban_claim_dealer as jcd', 'jcd.id_jawaban_claim_dealer = jcdp.id_jawaban_claim_dealer')
        ->join('tr_h3_md_claim_dealer as cd', 'cd.id_claim_dealer = jcdp.id_claim_dealer')
        ->join('tr_h3_md_claim_dealer_parts as cdp', '(cdp.id_claim_dealer = jcdp.id_claim_dealer and cdp.id_part = jcdp.id_part and cdp.id_kategori_claim_c3 = jcdp.id_kategori_claim_c3)')
        ->join('ms_dealer as d', 'd.id_dealer = cd.id_dealer')
        ->join('ms_part as p', 'p.id_part = jcdp.id_part')
        ->wherE('jcdp.pending', 0)
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->or_like('d.kode_dealer_md', $search);
            $this->db->or_like('d.nama_dealer', $search);
            $this->db->or_like('cd.id_claim_dealer', $search);
            $this->db->or_like('cd.id_packing_sheet', $search);
            $this->db->or_like('jcdp.id_jawaban_claim_dealer', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('jcd.created_at', 'desc');
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
        return $this->db->get()->num_rows();
    }
}
