<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Lokasi_rak_penerimaan_barang extends CI_Controller
{
    private $id_part;
    private $id_part_int;
    private $selected_packing_sheet_quantity;

    public function __construct()
    {
        parent::__construct();
        ini_set('max_execution_time', '0');

        $this->load->model('H3_md_stock_model', 'stock');
        $this->load->model('H3_md_stock_int_model', 'stock_int');

        $this->load->model('H3_md_lokasi_rak_model', 'lokasi_rak');

        $this->id_part = $this->input->post('selected_id_part');
        $this->id_part_int = $this->input->post('selected_id_part_int');
        $this->selected_packing_sheet_quantity = $this->input->post('selected_packing_sheet_quantity');
        $this->selected_packing_sheet_quantity = $this->selected_packing_sheet_quantity == null ? 0 : $this->selected_packing_sheet_quantity;
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['kapasitas_tersedia_berdasarkan_setting_kode_part_pada_lokasi_rak'] = 0;
            if ($row['part_sudah_disetting_rak'] == 1) {
                $booking_stock = $this->stock_int->qty_on_hand('lrp.id_part_int', 'lrp.id_lokasi_rak', true);

                $setting_kodepart_lokasi_rak = $this->db
                    ->select("IFNULL(lrp.qty_maks, 0) as qty_maks", false)
                    ->select(sprintf("IFNULL((%s), 0) as booking_stock", $booking_stock), false)
                    ->from('ms_h3_md_lokasi_rak_parts as lrp')
                    ->where('lrp.id_lokasi_rak', $row['id'])
                    ->where('lrp.id_part_int', $this->input->post('selected_id_part_int'))
                    ->limit(1)
                    ->get()->row_array();

                if ($setting_kodepart_lokasi_rak != null) {
                    $row['kapasitas_tersedia_berdasarkan_setting_kode_part_pada_lokasi_rak'] = $setting_kodepart_lokasi_rak['qty_maks'] - $setting_kodepart_lokasi_rak['booking_stock'];
                    $row['kapasitas_tersedia_berdasarkan_setting_kode_part_pada_lokasi_rak_qty_maks'] = $setting_kodepart_lokasi_rak['qty_maks'];
                    $row['kapasitas_tersedia_berdasarkan_setting_kode_part_pada_lokasi_rak_book'] = $setting_kodepart_lokasi_rak['booking_stock'];
                }
            }

            $row['action'] = $this->load->view('additional/md/h3/action_lokasi_rak_penerimaan_barang', [
                'data' => json_encode($row),
                'row' => $row,
                'packing_sheet_quantity' => $this->selected_packing_sheet_quantity
            ], true);

            $row['view_stock'] = $this->load->view('additional/md/h3/action_view_stock_lokasi_penerimaan_barang', [
                'id' => $row['id']
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

    public function make_query()
    {
        $kapasitas_booking = $this->db
            ->select('SUM(lrp_sq.qty_maks) as qty_maks', false)
            ->from('ms_h3_md_lokasi_rak_parts as lrp_sq')
            ->where('lrp_sq.id_lokasi_rak = lr.id', null, false)
            ->get_compiled_select();

        $lokasi_setting_kode_part = $this->lokasi_rak->suggest_lokasi_rak_berdasarkan_settingan_master($this->id_part, $this->selected_packing_sheet_quantity, false, null, true);
        $lokasi_master = $this->lokasi_rak->suggest_lokasi_rak_berdasarkan_kapasitas_tersedia($this->id_part, $this->selected_packing_sheet_quantity, false, null, true);
        $list_lokasi_rak = array_merge($lokasi_setting_kode_part, $lokasi_master);

        $this->db
            ->select('lr.id')
            ->select('lr.kode_lokasi_rak')
            ->select('lr.deskripsi')
            ->select("IFNULL(({$kapasitas_booking}), 0) as kapasitas_booking")
            ->select('lr.kapasitas')
            ->select('lr.kapasitas_terpakai')
            ->select("(lr.kapasitas - lr.kapasitas_terpakai) as kapasitas_tersedia")
            ->select('g.kode_gudang')
            ->select('(lrp.id IS NOT NULL)  as part_sudah_disetting_rak', false)
            ->from('ms_h3_md_lokasi_rak as lr')
            ->join('ms_h3_md_lokasi_rak_parts as lrp', sprintf('(lrp.id_lokasi_rak = lr.id AND lrp.id_part_int = "%s")', $this->id_part_int), 'left')
            ->join('ms_h3_md_gudang as g', 'g.id = lr.id_gudang')
            ->where('(lr.kapasitas - lr.kapasitas_terpakai) > 0', null, false)
            ->where('lr.active', 1)
			->where('lr.lokasi_retur', 0)
            ->order_by('part_sudah_disetting_rak', 'desc')
            ->order_by('kapasitas_tersedia', 'asc')
            ;

        // if (count($list_lokasi_rak) > 0) {
        //     $this->db->where_in('lr.id', $list_lokasi_rak);
        // } else {
        //     $this->db->where('1 = 0', null, false);
        // }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('lr.kode_lokasi_rak', $search);
            $this->db->or_like('g.kode_gudang', $search);
            $this->db->group_end();
        }

        
		// $search_lokasi_rak = $this->input->post('search_lokasi_rak');
        // $search_gudang = $this->input->post('search_gudang');

        // if($search_lokasi_rak != ''){
        //     $this->db->group_start();
        //     $this->db->like('lr.kode_lokasi_rak', $search_lokasi_rak);
        //     $this->db->group_end();
        // }

        // if ($search_gudang != '') {
        //     $this->db->group_start();
        //     $this->db->like('g.kode_gudang', $search_gudang);
        //     $this->db->group_end();
        // }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            // $this->db->order_by('lr.kode_lokasi_rak', 'asc');
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
