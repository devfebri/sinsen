<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Parts_claim_main_dealer_ke_ahm extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('H3_md_stock_model', 'stock');
        $this->load->model('H3_md_stock_int_model', 'stock_int');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['qty_onhand'] = $this->stock_int->qty_on_hand($row['id_part_int']);
            $row['qty_intransit'] = $this->stock_int->qty_intransit($row['id_part_int']);
            $row['qty_booking'] = $this->stock_int->qty_booking($row['id_part_int']);
            $row['qty_avs'] = $this->stock_int->qty_avs($row['id_part_int']);

            $row['action'] = $this->load->view('additional/md/h3/action_parts_claim_main_dealer_ke_ahm', [
                'data' => json_encode($row),
                'id_part' => $row['id_part'],
                'id_part_int' => $row['id_part_int'],
                'no_doos' => $row['no_doos'],
            ], true);

            $data[] = $row;
            $index++;
        }

        send_json([
            'draw' => intval($_POST['draw']),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_total_data(),
            'data' => $data
        ]);
    }

    public function make_query()
    {
        $qty_claim_main_dealer = $this->db
            ->select('SUM(cmdi.qty_part_diclaim) as qty_part_diclaim', false)
            ->from('tr_h3_md_claim_main_dealer_ke_ahm as cmd')
            ->join('tr_h3_md_claim_main_dealer_ke_ahm_item as cmdi', 'cmd.id = cmdi.id_claim_int')
            ->where('cmdi.id_part_int = pbi.id_part_int', null, false)
            ->where('cmdi.no_doos_int = pbi.nomor_karton_int', null, false)
            ->where('cmdi.no_po = pbi.no_po', null, false)
            ->where('cmd.status !=', 'Canceled')
            ->get_compiled_select();

        $this->db
            ->select('pbi.no_penerimaan_barang')
            ->select('pbi.packing_sheet_number_int')
            ->select('pbi.packing_sheet_number')
            ->select('pbi.id_part_int')
            ->select('pbi.id_part')
            ->select('pbi.nomor_karton as no_doos')
            ->select('pbi.nomor_karton_int as no_doos_int')
            ->select('p.nama_part')
            ->select('pbi.no_po')
            ->select('pbi.qty_packing_sheet as packing_sheet_quantity')
            ->select("(pbi.qty_packing_sheet - IFNULL(({$qty_claim_main_dealer}), 0)) as qty_part_yang_boleh_claim", false)
            ->select('1 as qty_part_diclaim')
            ->select('1 as qty_part_dikirim_ke_ahm')
            ->select('"" as id_kode_claim')
            ->select('"" as nama_claim')
            ->select('"" as id_lokasi_rak')
            ->select('"" as lokasi')
            ->select('"" as keterangan')
            ->from('tr_h3_md_penerimaan_barang_items as pbi')
            ->join('ms_part as p', 'p.id_part_int = pbi.id_part_int')
            ->join('tr_h3_md_psl_items as psli', 'psli.packing_sheet_number_int = pbi.packing_sheet_number_int')
            ->where('pbi.tersimpan', 1);

        if ($this->input->post('packing_sheet_number_int') != null) $this->db->where('pbi.packing_sheet_number_int', $this->input->post('packing_sheet_number_int'));
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('pbi.id_part', $search);
            $this->db->or_like('p.nama_part', $search);
            $this->db->or_like('pbi.nomor_karton', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pbi.id_part', 'asc');
        }
    }

    public function limit()
    {
        if ($_POST["length"] != -1) $this->db->limit($_POST['length'], $_POST['start']);
    }

    public function get_filtered_data()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function get_total_data()
    {
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
