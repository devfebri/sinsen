<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Check_kekurangan_part extends CI_Controller
{

    public $surat_jalan_ahm;

    public function __construct(){
        parent::__construct();

        ini_set('max_execution_time', '0');

        $this->surat_jalan_ahm = $this->db
        ->select('DISTINCT(pbi.surat_jalan_ahm_int) as surat_jalan_ahm_int')
        ->from('tr_h3_md_penerimaan_barang_items as pbi')
        ->where('pbi.no_surat_jalan_ekspedisi', $this->input->post('no_surat_jalan_ekspedisi'))
		->get_compiled_select();
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = [];
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action_qty_reason_ahm'] = $this->load->view('additional/md/h3/action_view_qty_reason_ahm_penerimaan_barang', [
                'id' => $row['id'],
                'proses_claim_ahm' => $row['proses_claim_ahm']
            ], true);

            $row['action_qty_ekspedisi'] = $this->load->view('additional/md/h3/action_view_qty_reason_ekspedisi_penerimaan_barang', [
                'id' => $row['id'],
                'proses_claim_ekspedisi' => $row['proses_claim_ekspedisi']
            ], true);

            $row['action'] = $this->load->view('additional/md/h3/action_check_kekurangan_part_penerimaan_barang', [
                'id' => $row['id'],
                'tersimpan' => $row['tersimpan'],
            ], true);

            $row['index'] = $this->input->post('start') + $index . '.';

            $data[] = $row;
            $index++;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'data' => $data,
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
        ]);
    }

    public function make_query()
    {
        $this->db
        ->select('pbi.id')
        ->select('pbi.tersimpan')
        ->select('psli.surat_jalan_ahm')
        ->select('date_format(ps.packing_sheet_date, "%d/%m/%Y") as packing_sheet_date')
        ->select('ps.packing_sheet_number')
        ->select('psp.no_doos as nomor_karton')
        ->select('psp.id_part')
        ->select('p.nama_part')
        ->select('psp.packing_sheet_quantity')
        ->select('ifnull(pbi.qty_diterima, 0) as qty_diterima')
        ->select('pbi.qty_selain_claim_ekspedisi as qty_reason_ahm')
        ->select('ifnull(pbi.proses_claim_ahm, 0) as proses_claim_ahm')
        ->select('pbi.qty_claim_ekspedisi as qty_reason_ekspedisi')
        ->select('ifnull(pbi.proses_claim_ekspedisi, 0) as proses_claim_ekspedisi')
        ->from('tr_h3_md_ps as ps')
        ->join('tr_h3_md_ps_parts as psp', 'psp.packing_sheet_number_int = ps.id')
        ->join('tr_h3_md_psl_items as psli', 'psli.packing_sheet_number_int = ps.id')
        ->join('tr_h3_md_penerimaan_barang_items as pbi', '(pbi.id_part_int = psp.id_part_int and pbi.packing_sheet_number_int = psp.packing_sheet_number_int and pbi.nomor_karton_int = psp.no_doos_int and psp.no_po = pbi.no_po)', 'left')
        ->join('ms_part as p', 'p.id_part_int = psp.id_part_int')
		->where("psli.surat_jalan_ahm_int in ({$this->surat_jalan_ahm})")
        ->group_start()
            ->group_start()
            ->where('pbi.tersimpan', 0)
            ->group_end()
            ->or_group_start()
                ->or_group_start()
                    ->where('pbi.qty_selain_claim_ekspedisi > 0', null, false)
                    ->where("pbi.proses_claim_ahm", 0)
                ->group_end()
                ->or_group_start()
                    ->where('pbi.qty_claim_ekspedisi > 0', null, false)
                    ->where("pbi.proses_claim_ekspedisi", 0)
                ->group_end()
            ->group_end()
        ->group_end()
        ->order_by('qty_reason_ahm', 'desc')
        ->order_by('qty_reason_ekspedisi', 'desc')
        ->order_by('psp.no_doos', 'asc')
        ->order_by('psp.id_part', 'asc')
		;
    }

    public function make_datatables(){
        $this->make_query();
        
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by("qty_reason_ahm", 'desc');
            $this->db->order_by("qty_reason_ekspedisi", 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered(){
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }

    public function total_kekurangan_part(){
        $this->make_query();
        echo $this->db->get()->num_rows();
        die;
    }
}
