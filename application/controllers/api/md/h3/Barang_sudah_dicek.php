<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Barang_sudah_dicek extends CI_Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', '0');

        parent::__construct();
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';

            $row['reason'] = $this->load->view('additional/action_view_reason_penerimaan_barang', [
                'data' => json_encode($row),
                'nomor_karton' => $row['nomor_karton'],
                'no_penerimaan_barang' => $row['no_penerimaan_barang'],
                'id_part' => $row['id_part'],
            ], true);
            
            $data[] = $row;
            $index++;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            // 'data' => $this->db->get()->result_array(),
            'data' => $data,
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
        ]);
    }

    public function make_query()
    {
        $this->db
            ->select('pbi.surat_jalan_ahm')
            ->select('date_format(ps.packing_sheet_date, "%d/%m/%Y") as packing_sheet_date')
            ->select('ps.packing_sheet_number')
            ->select('pbi.nomor_karton')
            ->select('pbi.id_part')
            ->select('p.nama_part')
            ->select('pbi.no_penerimaan_barang')
            ->select('psp.packing_sheet_quantity')
            ->select('pbi.qty_diterima')
            ->select('
            case
                when pbi.qty_diterima > psp.packing_sheet_quantity then 
                    concat("-", (pbi.qty_diterima - psp.packing_sheet_quantity))
                when pbi.qty_diterima < psp.packing_sheet_quantity then 
                    concat("-", (psp.packing_sheet_quantity - pbi.qty_diterima))
                else (pbi.qty_diterima - psp.packing_sheet_quantity)
            end as qty_selisih
        ', false)
            ->select('fdo.invoice_number')
            ->select('fdo.invoice_date')
            ->select('rak.kode_lokasi_rak')
            ->from('tr_h3_md_penerimaan_barang_items as pbi')
            ->join('tr_h3_md_ps_parts as psp', '(psp.id_part_int = pbi.id_part_int and psp.packing_sheet_number_int = pbi.packing_sheet_number_int and pbi.nomor_karton = psp.no_doos and psp.no_po = pbi.no_po)')
            ->join('tr_h3_md_ps as ps', 'ps.id = pbi.packing_sheet_number_int')
            ->join('tr_h3_md_fdo as fdo', 'fdo.id = ps.invoice_number_int', 'left')
            ->join('ms_part as p', 'p.id_part_int = pbi.id_part_int')
            ->join('ms_h3_md_lokasi_rak as rak','pbi.id_lokasi_rak = rak.id')
            ->where('pbi.no_surat_jalan_ekspedisi', $this->input->post('no_surat_jalan_ekspedisi'))
            ->where('pbi.tersimpan', 1)
            ->order_by('pbi.created_at', 'desc');
    }

    public function make_datatables()
    {
        $this->make_query();

        $filter_surat_jalan_ahm = $this->input->post('filter_surat_jalan_ahm');
        $filter_packing_sheet_number = $this->input->post('filter_packing_sheet_number');
        $filter_nomor_karton = $this->input->post('filter_nomor_karton');
        $filter_id_part = $this->input->post('filter_id_part');

        if ($filter_surat_jalan_ahm != null or $filter_packing_sheet_number != null or $filter_nomor_karton != null or $filter_id_part != null) {
            $this->db->group_start();
            if ($filter_surat_jalan_ahm != null) $this->db->like('pbi.surat_jalan_ahm', $filter_surat_jalan_ahm);
            if ($filter_packing_sheet_number != null) $this->db->like('pbi.packing_sheet_number', $filter_packing_sheet_number);
            if ($filter_nomor_karton != null) $this->db->like('pbi.nomor_karton', $filter_nomor_karton);
            if ($filter_id_part != null) $this->db->like('pbi.id_part', $filter_id_part);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ps.packing_sheet_number', 'asc');
        }
    }

    public function limit()
    {
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal()
    {
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
