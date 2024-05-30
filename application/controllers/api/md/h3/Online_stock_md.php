<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Online_stock_md extends CI_Controller {

    public $table = "tr_stok_part";

    public function __construct() {
        parent::__construct();
        $this->load->model('H3_md_stock_model', 'stock');
        $this->load->model('H3_md_stock_int_model', 'stock_int');
    }

    public function index() {
        send_json([
            'data' => $this->getData(), 
            'draw' => intval($this->input->post('draw')), 
            'recordsFiltered' => $this->get_filtered_data(), 
            'recordsTotal' => $this->count_all(),
        ]);
    }

    public function getData($limit = true){
        $this->make_datatables();
        $this->limit();
        $this->order();

        $data = [];
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['qty_onhand'] = $this->stock_int->qty_on_hand($row['id_part_int']);
            $row['qty_diterima'] = $this->stock_int->qty_diterima($row['id_part_int']);
            $row['qty_intransit'] = $this->stock_int->qty_intransit($row['id_part_int']);
            $row['qty_booking'] = $this->stock_int->qty_booking($row['id_part_int']);
            $row['qty_avs'] = $this->stock_int->qty_avs($row['id_part_int']);
            $row['qty_claim'] = $this->stock_int->qty_claim($row['id_part_int']);
            $row['qty_keep_stock'] = $this->stock_int->qty_keep_stock_hotline($row['id_part_int']);
            $row['rak_lokasi'] = $this->load->view('additional/md/h3/open_view_modal_rak_lokasi_online_stock_md', [
                'id_part' => $row['id_part']
            ], true);
            $row['tipe_motor'] = $this->load->view('additional/md/h3/open_view_modal_tipe_motor_online_stock_md', [
                'id_part' => $row['id_part']
            ], true);
            $row['serial_number'] = $this->load->view('additional/md/h3/open_view_modal_serial_number_online_stock_md', [
                'id_part' => $row['id_part']
            ], true);

            // PO Hotline untuk qty PO
            $hotline_sudah_dipenuhi = $this->db
            ->select('SUM(dop.qty_supply) as kuantitas', false)
            ->from('tr_h3_md_sales_order as so')
            ->join('tr_h3_md_do_sales_order as do', 'do.id_sales_order = so.id_sales_order')
            ->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_sales_order')
            ->where('so.id_ref = po.referensi_po_hotline', null, false)
            ->where('dop.id_part = pop.id_part', null, false)
            ->where('do.sudah_create_faktur', 1)
            ->where('so.created_at > pb.created_at', null, false)
            ->get_compiled_select();

            $qty_po_hotline = $this->db
		    ->select('"HLO" as jenis_po')
            ->select("IFNULL( (SUM(pbi.qty_diterima - IFNULL(({$hotline_sudah_dipenuhi}), 0)) ), 0 ) as sisa_belum_terpenuhi", false)
            // ->select('pbi.no_penerimaan_barang')
            // ->select('pbi.qty_diterima')
            // ->select('pbi.no_po')
            ->from('tr_h3_md_penerimaan_barang_items as pbi')
            ->join('tr_h3_md_penerimaan_barang as pb', '(pb.no_penerimaan_barang = pbi.no_penerimaan_barang)')
            ->join('tr_h3_md_purchase_order_parts as pop', '(pop.id_purchase_order = pbi.no_po AND pop.id_part = pbi.id_part)')
            ->join('tr_h3_md_purchase_order as po', '(po.id_purchase_order = pop.id_purchase_order)')
            ->where('po.jenis_po', 'HTL')
            ->where('pbi.id_part', $row['id_part'])
            ->where('pbi.tersimpan', 1)
            ->get_compiled_select();

            // PO Urgent untuk qty PO
            $urgent_sudah_dipenuhi = $this->db
            ->select('SUM(dop.qty_supply) as kuantitas', false)
            ->from('tr_h3_md_sales_order as so')
            ->join('tr_h3_md_do_sales_order as do', 'do.id_sales_order = so.id_sales_order')
            ->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
            ->where('so.id_ref = pop.referensi', null, false)
            ->where('dop.id_part = pop.id_part', null, false)
            ->where('do.sudah_create_faktur', 1)
            ->where('so.created_at > pb.created_at', null, false)
            ->get_compiled_select();

            $qty_po_urgent = $this->db
            ->select('"URG" as jenis_po')
            ->select("IFNULL( (SUM(pbi.qty_diterima - IFNULL(({$urgent_sudah_dipenuhi}), 0))), 0 ) as sisa_belum_terpenuhi", false)
            // ->select('pbi.no_penerimaan_barang')
            // ->select('pb.created_at')
            // ->select('pbi.qty_diterima')
            // ->select('pbi.no_po')
            // ->select('pop.referensi')
            // ->select("IFNULL(({$urgent_sudah_dipenuhi}), 0) as urgent_sudah_dipenuhi", false)
            ->from('tr_h3_md_penerimaan_barang_items as pbi')
            ->join('tr_h3_md_penerimaan_barang as pb', '(pb.no_penerimaan_barang = pbi.no_penerimaan_barang)')
            ->join('tr_h3_md_purchase_order_parts as pop', '(pop.id_purchase_order = pbi.no_po AND pop.id_part = pbi.id_part)')
            ->join('tr_h3_md_purchase_order as po', '(po.id_purchase_order = pop.id_purchase_order)')
            ->where('po.jenis_po', 'URG')
            ->where('pbi.id_part', $row['id_part'])
            ->where('pbi.tersimpan', 1)
            ->get_compiled_select();

            $this->db
            ->select('SUM(qty_po.sisa_belum_terpenuhi) as kuantitas', false)
            ->from("
                (
                    ({$qty_po_hotline})
                    UNION
                    ({$qty_po_urgent})
                ) as qty_po
            ");

            if(count($this->input->post('filter_tipe_so')) > 0){
                $this->db->where_in('qty_po.jenis_po', $this->input->post('filter_tipe_so'));
            }

            $row['qty_po'] = $this->db->get()->row_array()['kuantitas'];

            $data[] = $row;
            $index++;
        }
        return $data;
    }
    
    public function make_query() {
        // $qty_on_hand_sq = $this->stock->qty_on_hand('p.id_part', null, true);

		$qty_on_hand_sq = $this->stock_int->qty_on_hand('p.id_part_int', null, true);
        $this->db
        ->select('p.id_part_int')
        ->select('p.id_part')
        ->select('p.nama_part')
        ->select('concat(
            "Rp ", 
            format(p.harga_dealer_user, 0, "id_ID")
        ) as het', false)
        ->select('p.harga_dealer_user')
        ->select('concat(
            "Rp ", 
            format(p.harga_md_dealer, 0, "id_ID")
        ) as hpp', false)
        ->select('p.status')
        ->select('0 as qty_claim')
        ->select('0 as qty_po')
        ->select('
            case
                when p.kelompok_vendor = "AHM" then
                    case
                        when skp.produk is null then "-"
                        else skp.produk
                    end
                else "Other"
            end as produk
        ', false)
        ->from('ms_part as p')
        ->join('ms_kelompok_part as kp', 'kp.id_kelompok_part = p.kelompok_part', 'left')
        ->join('ms_h3_md_setting_kelompok_produk as skp', 'skp.id_kelompok_part = kp.id_kelompok_part', 'left')
        ->where("({$qty_on_hand_sq}) > 0", null, false)
        ;
    }

    public function make_datatables() {
        $this->make_query();
        $this->filter();
    }

    public function amount($produk){
        $this->db
        ->select('SUM((sp.qty * p.harga_md_dealer)) as amount', false)
        ->from('ms_h3_md_setting_kelompok_produk as skp')
        ->join('ms_kelompok_part as kp', 'kp.id = skp.id_kelompok_part_int')
        ->join('ms_part as p', 'p.kelompok_part_int = kp.id')
        ->join('tr_stok_part as sp', 'sp.id_part_int = p.id_part_int')
        ->where('skp.produk', $produk);

        $this->filter();

        $data = $this->db->get()->row_array();

        if($data != null){
            send_json([
                'amount' => floatval($data['amount']),
            ]);
        }

        send_json([
            'amount' => 0
        ]);
    }

    public function filter(){
        if($this->input->post('kode_part_filter') != null){
            $this->db->like('p.id_part', trim($this->input->post('kode_part_filter')));
        }

        if($this->input->post('nama_part_filter') != null){
            $this->db->like('p.nama_part', trim($this->input->post('nama_part_filter')));
        }

        if(count($this->input->post('filter_produk')) > 0){
            $this->db->where_in('skp.produk', $this->input->post('filter_produk'));
        }

        if(count($this->input->post('filter_kelompok_part')) > 0){
            $this->db->where_in('kp.id_kelompok_part', $this->input->post('filter_kelompok_part'));
        }

        if(count($this->input->post('filter_kategori')) > 0){
            if(in_array('SIM Part', $this->input->post('filter_kategori'))){
                $this->db->where('p.sim_part', 1);
            }elseif(in_array('Non SIM Part', $this->input->post('filter_kategori'))){
                $this->db->where('p.sim_part', 0);
            }
        }

        if (count($this->input->post('filter_status')) > 0) {
            $this->db->where_in('p.status', $this->input->post('filter_status'));
        }

        if (count($this->input->post('filter_rank')) > 0) {
            $this->db->where_in('p.rank', $this->input->post('filter_rank'));
        }
    }

    public function order(){
        if (isset($_POST["order"])) {
            $this->db->order_by($_POST['columns'][$_POST['order']['0']['column']]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('p.id_part', 'asc');
        }
    }

    public function limit(){
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function get_filtered_data() {
        $this->make_datatables();
        return $this->db->count_all_results();
    }

    public function count_all(){
        $this->make_query();
        return $this->db->count_all_results();
    }

    public function get_amount(){
        $data = $this->getData();

        $result = [];

        $parts = array_filter($data, function($each){
            return $each['qty_onhand'] > 0 and $each['parts'] == 'Parts';
        });
        $amount_parts = array_map(function($each){
            return $each['qty_onhand'] * $each['harga_dealer_user'];
        }, $data);
        $result['amount_parts'] = array_sum($amount_parts);

        return $result;
    }
}
