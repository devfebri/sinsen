<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Report_penerimaan_ekspedisi extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
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
            'data' => $data,
        ]);
    }
    
    public function make_query()
    {
        $this->db
        ->select('pb.tanggal_penerimaan')
        ->select('pb.no_penerimaan_barang')
        ->select('e.nama_ekspedisi')
        ->select('pb.type_mobil')
        ->select('pb.tgl_surat_jalan_ekspedisi')
        ->select('pb.no_surat_jalan_ekspedisi')
        ->select('pb.no_plat')
        ->select('pb.no_plat')
        ->select('fdo_ps.invoice_number')
        ->select('psli.surat_jalan_ahm')
        ->select('ps.packing_sheet_date')
        ->select('ps.packing_sheet_number')
        ->select('pbi.id_part')
        ->select('p.nama_part')
        ->select('pbi.qty_diterima')
        ->select('(pbi.qty_diterima/IFNULL(p.qty_dus, 1)) as jumlah_koli')
        ->from('tr_h3_md_penerimaan_barang_items as pbi')
        ->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_penerimaan_barang = pbi.no_penerimaan_barang')
        ->join('ms_h3_md_ekspedisi as e', 'e.id = pb.id_vendor')
        ->join('tr_h3_md_fdo_ps as fdo_ps', 'fdo_ps.packing_sheet_number = pbi.packing_sheet_number')
        ->join('tr_h3_md_ps as ps', 'pbi.packing_sheet_number = ps.packing_sheet_number')
        ->join('tr_h3_md_psl_items as psli', 'psli.packing_sheet_number = ps.packing_sheet_number')
        ->join('ms_part as p', 'p.id_part = pbi.id_part')
        ->where('pb.status', 'Closed')
        ->where('pbi.tersimpan', 1)
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('pb.no_penerimaan_barang', $search);
            $this->db->group_end();
        }

        if($this->input->post('id_ekspedisi') != null){
            $this->db->where('pb.id_vendor', $this->input->post('id_ekspedisi'));
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pb.created_at', 'desc');
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
