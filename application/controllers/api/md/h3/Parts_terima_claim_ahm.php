<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Parts_terima_claim_ahm extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('H3_md_stock_model', 'stock');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_parts_terima_claim_ahm', [
                'data' => json_encode($row),
                'id_claim' => $row['id_claim'],
                'id_part' => $row['id_part'],
                'no_doos' => $row['no_doos'],
                'no_po' => $row['no_po'],
                'id_kode_claim' => $row['id_kode_claim'],
                'invoice_tidak_ditemukan' => $row['invoice_tidak_ditemukan'] == 1
            ], true);

            $row['index'] = $this->input->post('start') + $index . '.';

            $data[] = $row;
            $index++;
        }

        send_json([
            'draw' => intval($_POST['draw']),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data
        ]);
    }

    public function make_query()
    {
        $qty_terima_claim = $this->db
            ->select('SUM(
            case
                when tcai.barang_checklist = 1 then tcai.ganti_barang
                when tcai.uang_checklist = 1 then tcai.ganti_uang
                when tcai.ditolak_checklist = 1 then tcai.ditolak
            end 
        )')
            ->from('tr_h3_md_terima_claim_ahm_item as tcai')
            ->join('tr_h3_md_terima_claim_ahm as tca', '(tca.id_terima_claim_ahm = tcai.id_terima_claim_ahm)')
            ->where('tcai.id_claim_int = cmi.id_claim_int')
            ->where('tcai.id_part_int = cmi.id_part_int')
            ->where('tcai.id_kode_claim = cmi.id_kode_claim')
            ->group_start()
            ->where('tca.status', 'Open')
            ->or_where('tca.status', 'Processed')
            ->group_end()
            ->get_compiled_select();

        $this->db
            ->select('cmi.id_claim_int')
            ->select('cmi.id_claim')
            ->select('cmi.id_part')
            ->select('cmi.id_part_int')
            ->select('cmi.no_doos')
            ->select('cmi.no_po')
            ->select('cmi.no_po_int')
            ->select('p.nama_part')
            ->select('cmi.qty_part_diclaim')
            ->select("(cmi.qty_part_diclaim - IFNULL(({$qty_terima_claim}), 0)) as sisa_boleh_terima_claim", false)
            ->select('0 as barang_checklist')
            ->select('0 as ganti_barang')
            ->select('0 as uang_checklist')
            ->select('0 as ganti_uang')
            ->select('0 as ditolak_checklist')
            ->select('0 as ditolak')
            ->select('cmi.id_kode_claim')
            ->select('kc.kode_claim')
            ->select('kc.nama_claim')
            ->select('cm.packing_sheet_number')
            ->select('fdo_parts.invoice_number')
            ->select('IFNULL(fdo_parts.price, 0) as nominal_uang')
            ->select('(fdo_parts.id is null) as invoice_tidak_ditemukan', false)
            ->from('tr_h3_md_claim_main_dealer_ke_ahm_item as cmi')
            ->join('tr_h3_md_claim_main_dealer_ke_ahm as cm', 'cm.id = cmi.id_claim_int')
            ->join('ms_part as p', 'p.id_part_int = cmi.id_part_int')
            ->join('ms_kategori_claim_c3 as kc', 'kc.id = cmi.id_kode_claim')
            ->join('tr_h3_md_fdo_parts as fdo_parts', '(fdo_parts.invoice_number_int = cm.invoice_number_int AND fdo_parts.nomor_packing_sheet_int = cm.packing_sheet_number_int AND fdo_parts.id_part_int = cmi.id_part_int)', 'left')
            ->where('cm.status', 'Processed')
            ->having('sisa_boleh_terima_claim > 0')
            ;
    }

    private function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('cmi.id_claim', $search);
            $this->db->or_like('p.id_part', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('invoice_tidak_ditemukan', 'asc');
            $this->db->order_by('cm.created_at', 'desc');
        }
    }

    private function limit()
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
