<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sales_order extends CI_Controller
{
    private $id_sales_orders;

    public function __construct(){
        parent::__construct();
        $this->load->library('Mcarbon');
        $this->load->helper('query_execution_time');
        $this->filter_kelompok_part();
    }

    public function index()
    {
        $this->make_datatables();
        // $this->limit();
        
        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $record) {
             /* 09-01-2024 : off sementara , boleh dibuka lagi
            $kuantitas_part = $this->db
                                ->select('sum(sop.qty_pemenuhan) as qty_pemenuhan')
                                ->from('tr_h3_md_sales_order_parts as sop')
                                ->where('sop.id_sales_order',$record['id_sales_order'])
                                ->get()->row_array();

            $nilai_so_to_do = $this->db
                                ->select('ifnull(sum(do_sq.sub_total), 0) as nilai_so_to_do')
                                ->from('tr_h3_md_do_sales_order as do_sq')
                                ->where('do_sq.id_sales_order',$record['id_sales_order'])
                                ->group_start()
                                ->where('do_sq.status !=', 'Rejected')
                                ->where('do_sq.status !=', 'Canceled')
                                ->group_end()
                                ->get()->row_array();
                                
            $jumlah_do = $this->db
                    ->select('count(do_sq.id_do_sales_order) as jumlah_do')
                    ->from('tr_h3_md_do_sales_order as do_sq')
                    ->where('do_sq.id_sales_order',$record['id_sales_order'])
                    ->group_start()
                    ->where('do_sq.status !=', 'Rejected')
                    ->where('do_sq.status !=', 'Canceled')
                    ->group_end()
                    ->get()->row_array();

            $do_proses = $this->db
                        ->select('count(do_sq.id_do_sales_order) as do_proses')
                        ->from('tr_h3_md_do_sales_order as do_sq')
                        ->join('tr_h3_md_picking_list as pl_sq', 'pl_sq.id_ref = do_sq.id_do_sales_order', 'left')
                        ->join('tr_h3_md_packing_sheet as ps_sq', 'ps_sq.id_picking_list = pl_sq.id_picking_list', 'left')
                        ->where('do_sq.id_sales_order',$record['id_sales_order'])
                        ->where('ps_sq.no_faktur', null)
                        ->group_start()
                        ->where('do_sq.status !=', 'Rejected')
                        ->where('do_sq.status !=', 'Canceled')
                        ->group_end()
                        ->get()->row_array();

            $do_close = $this->db
                        ->select('count(do_sq.id_do_sales_order) as do_close')
                        ->from('tr_h3_md_do_sales_order as do_sq')
                        ->join('tr_h3_md_picking_list as pl_sq', 'pl_sq.id_ref = do_sq.id_do_sales_order')
                        ->join('tr_h3_md_packing_sheet as ps_sq', 'ps_sq.id_picking_list = pl_sq.id_picking_list')
                        ->where('do_sq.id_sales_order',$record['id_sales_order'])
                        ->get()->row_array();

            $service_rate = (round(($nilai_so_to_do['nilai_so_to_do']/ $record['total_amount']) * 100)." %");      
           

            $nilai_so_to_do_rupiah = 'Rp ' . number_format($nilai_so_to_do['nilai_so_to_do'], 0, ',', '.') . ' ';

            $record['action'] = $this->load->view('additional/action_index_sales_order_datatable', [
                'id' => $record['id_sales_order']
            ], true);
            $record['kuantitas_part'] = $kuantitas_part['qty_pemenuhan'];
            $record['jumlah_do'] = $jumlah_do['jumlah_do'];
            $record['do_proses'] = $do_proses['do_proses'];
            $record['do_close'] = $do_close['do_close'];
            $record['nilai_so_to_do'] = $nilai_so_to_do_rupiah;
            $record['service_rate'] = $service_rate;
            */
            $record['action'] = $this->load->view('additional/action_index_sales_order_datatable', [
                'id' => $record['id_sales_order']
            ], true);
            $record['kuantitas_part'] = 0;
            $record['jumlah_do'] = 0;
            $record['do_proses'] = 0;
            $record['do_close'] = 0;
            $record['nilai_so_to_do'] = 0;
            $record['service_rate'] = 0;
            $record['index'] = $this->input->post('start') + $index . '.';

            $data[] = $record;
            $index++;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
            'queries' => query_execution_time()
        ]);
    }

    public function filter_kelompok_part(){
         if (count($this->input->post('kelompok_part_filter')) > 0) {
            $this->db
            ->distinct()
            ->select('sop.id_sales_order')
            ->from('tr_h3_md_sales_order_parts as sop')
            ->join('ms_part as p', 'p.id_part_int = sop.id_part_int')
            ->where_in('p.kelompok_part', $this->input->post('kelompok_part_filter'));

            $this->id_sales_orders = $this->db->get()->result_array();

            $this->id_sales_orders = array_map(function($data){
                return $data['id_sales_order'];
            } , $this->id_sales_orders);
        }else{
            $this->id_sales_orders = [];
        }
    }

    public function make_query()
    {
        $this->db
        ->from('tr_h3_md_sales_order as so')
        ->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
        ->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan', 'left')
        ->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
        ->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
        ->join('ms_karyawan as k', 'k.id_karyawan = so.id_salesman', 'left')
        ;

        if($this->session->userdata('group') == 72){
            $this->db->group_start();
                $this->db->where('so.id_salesman', $this->session->userdata('id_karyawan_dealer'));
            $this->db->group_end();
        }

        $startOfMonth = Mcarbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Mcarbon::now()->endOfMonth()->toDateString();
        if($this->input->post('history') != null AND $this->input->post('history') == 1){
            $this->db->group_start();
                $this->db->group_start();
                    $this->db->where('so.status', 'Closed');
                    $this->db->or_where('so.status', 'Canceled');
                    // $this->db->or_where('left(so.created_at,10) <=', '2023-09-08');
                $this->db->group_end();
                $this->db->or_group_start();
                $this->db->where(
                    sprintf('so.tanggal_order not between "%s" AND "%s"', $startOfMonth, $endOfMonth),
                    null,
                    false
                );
                $this->db->or_where('left(so.created_at,10) <=', '2023-09-31');
                $this->db->group_end();
            $this->db->group_end();
        }else{
            // $this->db->group_start();
            //     $this->db->group_start();
            //     $this->db->where('so.status !=', 'Closed');
            //     $this->db->where('so.status !=', 'Canceled');
            //     $this->db->group_end();
            //     $this->db->or_group_start();
            //     $this->db->where(
            //         sprintf('so.tanggal_order between "%s" AND "%s"', $startOfMonth, $endOfMonth),
            //         null,
            //         false
            //     );
            //     $this->db->group_end();
            // $this->db->group_end();

            // $this->db->group_start();
                $this->db->group_start();
                    $this->db->where('so.status !=', 'Closed');
                    $this->db->where('so.status !=', 'Canceled');
                    // $this->db->or_where('left(so.created_at,10) <=', '2023-09-08');
                $this->db->group_end();
                // $this->db->group_start();
                // $this->db->where(
                //     sprintf('so.tanggal_order between "%s" AND "%s"', $startOfMonth, $endOfMonth),
                //     null,
                //     false
                // );
                $this->db->where('left(so.created_at,10) >', '2023-10-01');
                // $this->db->group_end();
            // $this->db->group_end();
            
            // $this->db->or_where('left(sos.created_at,10) >', '2023-09-08');
        }
    }

    public function select_for_datatable(){
        // $kuantitas_part = 0;
        // $nilai_so_to_do = 0;
        // $jumlah_do = 0;
        // $do_proses = 0;
        // $do_close = 0;

        /* 27-12-2022 : off sementara , boleh dibuka lagi
        $kuantitas_part = $this->db
        ->select('sum(sop.qty_pemenuhan)')
        ->from('tr_h3_md_sales_order_parts as sop')
        ->where('sop.id_sales_order_int = so.id')
        ->get_compiled_select();

        $nilai_so_to_do = $this->db
        ->select('ifnull(sum(do_sq.sub_total), 0)')
        ->from('tr_h3_md_do_sales_order as do_sq')
        ->where('do_sq.id_sales_order = so.id_sales_order')
        ->group_start()
        ->where('do_sq.status !=', 'Rejected')
        ->where('do_sq.status !=', 'Canceled')
        ->group_end()
        ->get_compiled_select();

        $jumlah_do = $this->db
        ->select('count(do_sq.id_do_sales_order)')
        ->from('tr_h3_md_do_sales_order as do_sq')
        // ->where('do_sq.id_sales_order = so.id_sales_order')
        ->where('do_sq.id_sales_order_int = so.id')
        ->group_start()
        ->where('do_sq.status !=', 'Rejected')
        ->where('do_sq.status !=', 'Canceled')
        ->group_end()
        ->get_compiled_select();

        $do_proses = $this->db
        ->select('count(do_sq.id_do_sales_order)')
        ->from('tr_h3_md_do_sales_order as do_sq')
        ->join('tr_h3_md_picking_list as pl_sq', 'pl_sq.id_ref = do_sq.id_do_sales_order', 'left')
        ->join('tr_h3_md_packing_sheet as ps_sq', 'ps_sq.id_picking_list = pl_sq.id_picking_list', 'left')
        // ->where('do_sq.id_sales_order = so.id_sales_order')
        ->where('do_sq.id_sales_order_int= so.id')
        ->where('ps_sq.no_faktur', null)
        ->group_start()
        ->where('do_sq.status !=', 'Rejected')
        ->where('do_sq.status !=', 'Canceled')
        ->group_end()
        ->get_compiled_select();

        $do_close = $this->db
        ->select('count(do_sq.id_do_sales_order)')
        ->from('tr_h3_md_do_sales_order as do_sq')
        ->join('tr_h3_md_picking_list as pl_sq', 'pl_sq.id_ref = do_sq.id_do_sales_order')
        // ->join('tr_h3_md_packing_sheet as ps_sq', 'ps_sq.id_picking_list = pl_sq.id_picking_list')
        ->join('tr_h3_md_packing_sheet as ps_sq', 'ps_sq.id_picking_list_int = pl_sq.id')
        // ->where('do_sq.id_sales_order = so.id_sales_order')
        ->where('do_sq.id_sales_order_int = so.id')
        ->get_compiled_select();
        */

        $this->db
        ->select('date_format(so.created_at, "%d-%m-%Y") as created_at')
        ->select('so.id_sales_order')
        ->select('so.total_amount')
        ->select('so.id')
        ->select('
            concat(
                "Rp ",
                format(so.total_amount, 0, "ID_id")
            ) as total_amount_formatted
        ')
        ->select('d.kode_dealer_md as kode_dealer')
        ->select('d.nama_dealer')
        ->select('d.alamat')
        ->select('so.po_type')
        ->select('so.id_ref')
        ->select('so.produk')
        ->select('(CASE WHEN so.created_by_md=1 and so.id_rekap_purchase_order_dealer is null then "MD" else "D" end) as created_by_md')
        ->select('(CASE WHEN so.autofulfillment_md=1 then "Ya" else "-" end) as autofulfillment_md')
        ->select('so.kategori_po')
        // ->select("({$kuantitas_part}) as kuantitas_part")
        // ->select("({$jumlah_do}) as jumlah_do")
        // ->select("({$do_proses}) as do_proses")
        // ->select("({$do_close}) as do_close")
        // ->select("
        // concat(
        //     'Rp ',
        //     format( ({$nilai_so_to_do}), 0, 'ID_id' )
        // ) as nilai_so_to_do")
        // ->select("
        // concat(
        //     format( ( round((({$nilai_so_to_do}) / so.total_amount) * 100) ), 0 ),
        //     ' %'
        // ) as service_rate")
        ->select('kab.kabupaten')
        ->select('k.nama_lengkap as salesman')
        ->select('so.status')
        ;
    }

    public function make_datatables(){
        $this->select_for_datatable();
        $this->make_query();
        $this->filter();
        $this->order();
        $this->limit();
    }

    public function get_sales_order_info($produk){

        /* 27-12-2022 : off sementara , boleh dibuka lagi
        $kuantitas_part = $this->db
        ->select('sum(sop.qty_pemenuhan)')
        ->from('tr_h3_md_sales_order_parts as sop')
        ->where('sop.id_sales_order = so.id_sales_order')
        ->get_compiled_select();

        
        $this->db->select('SUM(IFNULL(so.total_amount, 0)) as amount');
        $this->db->select("SUM(IFNULL(({$kuantitas_part}), 0)) as kuantitas_part");

        $this->make_query();
        $this->filter();
        $this->db->where('so.gimmick', 0);
        $this->db->where('so.produk', $produk);
        $this->db->where('so.status !=', 'Canceled');

        $this->db->where('so.tanggal_order between DATE_FORMAT(NOW(), "%Y-%m-01") and LAST_DAY(NOW())');

        $result = $this->db->get()->row_array();
        
        $data = [];
        $data['amount'] = $result['amount'] != null ? round($result['amount']) : 0;
        $data['kuantitas_part'] = $result['kuantitas_part'] != null ? $result['kuantitas_part'] : 0;
        */
        
        $data = [];
        $data['amount'] = 0;
        $data['kuantitas_part'] = 0;

        send_json($data);
    }

    public function filter(){
        if ($this->input->post('id_customer_filter')) {
            $this->db->where('so.id_dealer', $this->input->post('id_customer_filter'));
        }

        if ($this->input->post('no_so_filter')) {
            $this->db->like('so.id_sales_order', $this->input->post('no_so_filter'));
        }

        if($this->input->post('periode_sales_filter_start') != null and $this->input->post('periode_sales_filter_end') != null){            
            $this->db->group_start();
            $this->db->where('so.tanggal_order >=', $this->input->post('periode_sales_filter_start'));
            $this->db->where('so.tanggal_order <=', $this->input->post('periode_sales_filter_end'));
            $this->db->group_end();
        }

        if (count($this->input->post('tipe_penjualan_filter')) > 0) {
            $this->db->where_in('so.po_type', $this->input->post('tipe_penjualan_filter'));
        }

        if (count($this->input->post('status_filter')) > 0) {
            $this->db->where_in('so.status', $this->input->post('status_filter'));
        }

        if (count($this->input->post('jenis_dealer_filter')) > 0) {
            if(
                in_array(
                    'H123', $this->input->post('jenis_dealer_filter')
                )
            ){
                $this->db->group_start();
                $this->db->where('d.h1', 1);
                $this->db->where('d.h2', 1);
                $this->db->where('d.h3', 1);
                $this->db->group_end();
            }

            if(
                in_array(
                    'H23', $this->input->post('jenis_dealer_filter')
                )
            ){
                $this->db->group_start();
                $this->db->where('d.h1', 0);
                $this->db->where('d.h2', 1);
                $this->db->where('d.h3', 1);
                $this->db->group_end();
            }

            if(
                in_array(
                    'H3', $this->input->post('jenis_dealer_filter')
                )
            ){
                $this->db->group_start();
                $this->db->where('d.h1', 0);
                $this->db->where('d.h2', 0);
                $this->db->where('d.h3', 1);
                $this->db->group_end();
            }
        }

        if (count($this->input->post('kabupaten_filter')) > 0) {
            $this->db->where_in('kab.id_kabupaten', $this->input->post('kabupaten_filter'));
        }

        if (count($this->input->post('salesman_filter')) > 0) {
            $this->db->where_in('so.id_salesman', $this->input->post('salesman_filter'));
        }

        if (count($this->id_sales_orders) > 0) {
            $this->db->where_in('so.id_sales_order', $this->id_sales_orders);
        }

        if ($this->input->post('kategori_sales_filter')) {
            $this->db->where('so.kategori_po', $this->input->post('kategori_sales_filter'));
        }

        if ($this->input->post('tipe_produk_filter')) {
            $this->db->where('so.produk', $this->input->post('tipe_produk_filter'));
        }

        if ($this->input->post('autofulfillment_md_filter')==1) {
            $this->db->where('so.autofulfillment_md',1);
        }

        if ($this->input->post('no_po_filter')) {
            $po = $this->input->post('no_po_filter');
            $po = $this->db->escape_like_str($po);
            $subQuery = "(SELECT id_rekap FROM tr_h3_md_rekap_purchase_order_dealer_item podi WHERE id_referensi LIKE '%$po%')";
            $this->db->group_start();
            $this->db->like('so.id_ref', $this->input->post('no_po_filter'));
            $this->db->or_where("so.id_rekap_purchase_order_dealer IN " . $subQuery, NULL, FALSE);
            $this->db->group_end();
        }
    }

    public function order(){
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by("FIELD(so.status, 'New SO', 'New SO BO', 'Back Order', 'Barang Bagi', 'On Process', 'Closed', 'Canceled')", false);
            $this->db->order_by('so.created_at', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered(){
        $this->db
        ->select('date_format(so.created_at, "%d-%m-%Y") as created_at')
        ->select('so.id_sales_order')
        ->select('so.total_amount')
        ->select('
            concat(
                "Rp ",
                format(so.total_amount, 0, "ID_id")
            ) as total_amount_formatted
        ')
        ->select('d.kode_dealer_md as kode_dealer')
        ->select('d.nama_dealer')
        ->select('d.alamat')
        ->select('so.po_type')
        ->select('so.produk')
        ->select('so.autofulfillment_md')
        ->select('so.kategori_po')
        ->select('kab.kabupaten')
        ->select('k.nama_lengkap as salesman')
        ->select('so.status')
        ;
        $this->make_query();
        $this->filter();
        $this->order();
        
        // $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        // $this->select_for_datatable();
        $this->db->select('so.id_sales_order');
        $this->make_query();
        return $this->db->count_all_results();
    }
}
