<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Do_sales_order_h3 extends CI_Controller
{
    private $delivery_order_ids;

    public function __construct()
    {
        parent::__construct();
        $this->get_sales_order_by_filter_kelompok_part();
    }

    public function index()
    {
        $this->benchmark->mark('data_start');
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['id_sales_order'] = $this->load->view('additional/md/h3/view_modal_sales_order_on_do_sales_order', [
                'id_sales_order' => $row['id_sales_order']
            ], true);
            $row['action'] = $this->load->view('additional/action_index_do_sales_order_h3_datatable', [
                'id' => $row['id_do_sales_order'],
                'cetakan_ke' => $row['cetak_ke']
            ], true);

            $total_revisi = $this->db
                ->select('dr_sq.total')
                ->from('tr_h3_md_do_revisi as dr_sq')
                ->where('dr_sq.id_do_sales_order_int',$row['id_do_so_int'])
                ->order_by('dr_sq.created_at', 'desc')
                ->limit(1)
                ->where('dr_sq.status', 'Approved')
                ->get()->row_array();

            if($total_revisi['total'] != 0 || $total_revisi['total'] != NULL || $total_revisi['total'] != ''){
                $row['sub_total_do_awal'] = $total_revisi['total'];
                $row['sub_total_do_rev'] = $row['total_do'];
                $row['sisa_nilai_do'] = $total_revisi['total']-$row['total_do'];
            }else{
                $row['sub_total_do_awal'] = $row['total_do'];
                $row['sub_total_do_rev'] = null;
                $row['sisa_nilai_do'] = 0;
            }
            $data[] = $row;
        }
        $this->benchmark->mark('data_end');

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsFiltered_time' => floatval($this->benchmark->elapsed_time('recordsFiltered_start', 'recordsFiltered_end')),
            'recordsTotal' => $this->recordsTotal(),
            'recordsTotal_time' => floatval($this->benchmark->elapsed_time('recordsTotal_start', 'recordsTotal_end')),
            'data' => $data,
            'data_time' => floatval($this->benchmark->elapsed_time('data_start', 'data_end'))
        ]);
    }

    public function select_for_datatable()
    {
        /*
        $total_revisi = $this->db
            ->select('dr_sq.total')
            ->from('tr_h3_md_do_revisi as dr_sq')
            ->where('dr_sq.id_do_sales_order_int = dso.id')
            ->order_by('dr_sq.created_at', 'desc')
            ->limit(1)
            ->where('dr_sq.status', 'Approved')
            ->get_compiled_select();
        
        $qty_parts_sales_order = $this->db
            ->select('sum(sop.qty_pemenuhan)')
            ->from('tr_h3_md_sales_order_parts as sop')
            ->where('sop.id_sales_order_int = so.id')
            ->get_compiled_select();

        $qty_parts_do_sales_order = $this->db
            ->select('sum(dop.qty_supply)')
            ->from('tr_h3_md_do_sales_order_parts as dop')
            ->where('dop.id_do_sales_order_int = dso.id')
            ->get_compiled_select();
        */  
        $this->db
            // ->select("IFNULL( ({$qty_parts_sales_order}), 0) as qty_parts_sales_order")
            // ->select("IFNULL( ({$qty_parts_do_sales_order}), 0) as qty_parts_do_sales_order")
            ->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_so')
            ->select('so.id_sales_order')
            ->select('dso.cetak_ke')
            ->select('date_format(dso.tanggal, "%d-%m-%Y") as tanggal_do')
            ->select('dso.id_do_sales_order')
            ->select('d.kode_dealer_md')
            ->select('d.nama_dealer')
            ->select('kab.kabupaten')
            ->select('so.total_amount as total_so')
            ->select('dso.sub_total as total_do')
            ->select('dso.id as id_do_so_int')
        //     ->select("
        // case
        //     when ({$total_revisi}) is not null then ({$total_revisi})
        //     else dso.sub_total
        // end as sub_total_do_awal
        // ", false)
        //     ->select("
        // case
        //     when ({$total_revisi}) is not null then dso.sub_total
        //     else null
        // end as sub_total_do_rev
        // ", false)
            ->select('round( (dso.sub_total / so.total_amount) * 100 ) as service_rate', false)
        //     ->select("
        // case
        //     when ({$total_revisi}) is null then 0
        //     else ({$total_revisi}) - dso.sub_total
        // end as sisa_nilai_do
        // ", false)
            ->select('dso.status')
            ->select('dso.sudah_revisi')
            ->select('so.produk');
    }

    public function make_query()
    {
        $this->db
            ->from('tr_h3_md_do_sales_order as dso')
            ->join('tr_h3_md_sales_order as so', 'so.id = dso.id_sales_order_int', 'left')
            ->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
            ->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan', 'left')
            ->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
            ->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
            ->join('ms_karyawan as k', 'k.id_karyawan = so.id_salesman', 'left');
            if($this->session->userdata('group') == 72){
                $this->db->group_start();
                    $this->db->where('so.id_salesman', $this->session->userdata('id_karyawan_dealer'));
                $this->db->group_end();
            }
            if($this->input->post('history') != null AND $this->input->post('history') == 1){
                $this->db->group_start();
                    $this->db->where('dso.status !=', 'On Process');
                    $this->db->or_where('left(dso.created_at,10) <=', '2023-09-08');
                $this->db->group_end();
            }else{
    
                $this->db->group_start();
                    $this->db->where('dso.status =', 'On Process');
                    $this->db->where('left(dso.created_at,10) >', '2023-09-08');
                $this->db->group_end();
            }
    }

    public function make_datatables()
    {
        $this->select_for_datatable();
        $this->make_query();
        $this->filter();
        $this->order();
        $this->limit();
    }

    public function get_sales_order_info($produk)
    {
        /* 22/08/2023 off sementara, 
        $this->db->select('dso.id');
        $this->make_query();
        $this->filter();
        $this->db->group_start();
        $this->db->where('dso.status !=', 'Rejected');
        $this->db->where('dso.status !=', 'Canceled');
        $this->db->group_end();
        $this->db->where('so.produk', $produk);
        $list_delivery_order_ids = array_unique(
            array_map(function ($row) {
                return $row['id'];
            }, $this->db->get()->result_array())
        );

        $this->benchmark->mark('qty_parts_sales_order_start');
        $this->db
            ->select('IFNULL(SUM(sop.qty_pemenuhan), 0) as kuantitas', false)
            ->from('tr_h3_md_do_sales_order as do')
            ->join('tr_h3_md_sales_order_parts as sop', 'sop.id_sales_order = do.id_sales_order');
        if (count($list_delivery_order_ids) > 0) {
            $this->db->where_in('do.id', $list_delivery_order_ids);
        } else {
            $this->db->where('1=0', null, false);
        }
        $qty_parts_sales_order = $this->db->get()->row_array();
        $this->benchmark->mark('qty_parts_sales_order_end');

        $this->benchmark->mark('qty_parts_do_sales_order_start');
        $this->db
            ->select('IFNULL(SUM(dop.qty_supply), 0) as kuantitas', false)
            ->from('tr_h3_md_do_sales_order_parts as dop');
        if (count($list_delivery_order_ids) > 0) {
            $this->db->where_in('dop.id_do_sales_order_int', $list_delivery_order_ids);
        } else {
            $this->db->where('1=0', null, false);
        }
        $qty_parts_do_sales_order = $this->db->get()->row_array();
        $this->benchmark->mark('qty_parts_do_sales_order_end');

        $this->benchmark->mark('total_amount_sales_order_start');
        $this->db
            ->select('IFNULL(SUM(so.total_amount), 0) as total', false)
            ->from('tr_h3_md_do_sales_order as do')
            ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order');
        if (count($list_delivery_order_ids) > 0) {
            $this->db->where_in('do.id', $list_delivery_order_ids);
        } else {
            $this->db->where('1=0', null, false);
        }
        $total_amount_sales_order = $this->db->get()->row_array();
        $this->benchmark->mark('total_amount_sales_order_end');

        $this->benchmark->mark('total_amount_delivery_order_start');
        $total_revisi = $this->db
            ->select('dr_sq.sub_total')
            ->from('tr_h3_md_do_revisi as dr_sq')
            ->where('dr_sq.id_do_sales_order = do.id_do_sales_order')
            ->order_by('dr_sq.created_at', 'desc')
            ->limit(1)
            ->where('dr_sq.status', 'Approved')
            ->get_compiled_select();

        $this->db
            ->select("SUM(
            case
                when ({$total_revisi}) is not null then ({$total_revisi})
                else do.sub_total
            end
        ) as total", false)
            ->from('tr_h3_md_do_sales_order as do');
        if (count($list_delivery_order_ids) > 0) {
            $this->db->where_in('do.id', $list_delivery_order_ids);
        } else {
            $this->db->where('1=0', null, false);
        }
        $total_amount_delivery_order = $this->db->get()->row_array();
        $this->benchmark->mark('total_amount_delivery_order_end');

        $data = [];
        $data['qty_parts_sales_order'] = floatval($qty_parts_sales_order['kuantitas']);
        $data['qty_parts_sales_order_time'] = floatval($this->benchmark->elapsed_time('qty_parts_sales_order_start', 'qty_parts_sales_order_end'));
        $data['qty_parts_do_sales_order'] = floatval($qty_parts_do_sales_order['kuantitas']);
        $data['qty_parts_do_sales_order_time'] = floatval($this->benchmark->elapsed_time('qty_parts_do_sales_order_start', 'qty_parts_do_sales_order_end'));
        $data['sub_total_do_awal'] = floatval($total_amount_delivery_order['total']);
        $data['sub_total_do_awal_time'] = floatval($this->benchmark->elapsed_time('total_amount_sales_order_start', 'total_amount_sales_order_end'));
        $data['total_so'] = floatval($total_amount_sales_order['total']);
        $data['total_so_time'] = floatval($this->benchmark->elapsed_time('total_amount_delivery_order_start', 'total_amount_delivery_order_end'));
        
        */
        $data = [];
        $data['qty_parts_sales_order'] = 0;
        $data['qty_parts_sales_order_time'] = 0;
        $data['qty_parts_do_sales_order'] = 0;
        $data['qty_parts_do_sales_order_time'] = 0;
        $data['sub_total_do_awal'] = 0;
        $data['sub_total_do_awal_time'] = 0;
        $data['total_so'] = 0;
        $data['total_so_time'] = 0;
        send_json($data);
    }

    private function get_sales_order_by_filter_kelompok_part()
    {
        $kelompok_part_filter = $this->input->post('kelompok_part_filter');

        $this->db
            ->select('dop.id_do_sales_order_int as id')
            ->from('tr_h3_md_do_sales_order_parts as dop')
            ->join('ms_part as p', 'p.id_part_int = dop.id_part_int');

        if ($kelompok_part_filter != null and count($kelompok_part_filter) > 0) {
            $this->db->where_in('p.kelompok_part', $kelompok_part_filter);
        } else {
            $this->db->where('1=0', null, false);
        }

        $delivery_order_ids = array_map(function ($row) {
            return $row['id'];
        }, $this->db->get()->result_array());

        $delivery_order_ids = array_unique($delivery_order_ids);

        $this->delivery_order_ids = $delivery_order_ids;
    }

    private function filter()
    {
        if ($this->input->post('customer_filter') != null and count($this->input->post('customer_filter'))) {
            $this->db->where_in('so.id_dealer', $this->input->post('customer_filter'));
        }

        if (count($this->input->post('jenis_dealer_filter')) > 0) {
            if (
                in_array(
                    'H123',
                    $this->input->post('jenis_dealer_filter')
                )
            ) {
                $this->db->where('d.h1', 1);
                $this->db->where('d.h2', 1);
                $this->db->where('d.h3', 1);
            }

            if (
                in_array(
                    'H23',
                    $this->input->post('jenis_dealer_filter')
                )
            ) {
                $this->db->where('d.h1', 0);
                $this->db->where('d.h2', 1);
                $this->db->where('d.h3', 1);
            }

            if (
                in_array(
                    'H3',
                    $this->input->post('jenis_dealer_filter')
                )
            ) {
                $this->db->where('d.h1', 0);
                $this->db->where('d.h2', 0);
                $this->db->where('d.h3', 1);
            }
        }

        if (count($this->delivery_order_ids) > 0 and count($this->input->post('kelompok_part_filter')) > 0) {
            $this->db->where_in('dso.id', $this->delivery_order_ids);
        } else if (count($this->delivery_order_ids) == 0 and count($this->input->post('kelompok_part_filter')) > 0) {
            $this->db->where('1=0', null, false);
        }

        if (count($this->input->post('kabupaten_filter')) > 0) {
            $this->db->where_in('kab.id_kabupaten', $this->input->post('kabupaten_filter'));
        }

        if ($this->input->post('id_salesman_filter')) {
            $this->db->where('so.id_salesman', $this->input->post('id_salesman_filter'));
        }

        if ($this->input->post('no_so_filter')) {
            $this->db->like('so.id_sales_order', trim($this->input->post('no_so_filter')));
        }

        if ($this->input->post('no_do_filter')) {
            $this->db->like('dso.id_do_sales_order', trim($this->input->post('no_do_filter')));
        }

        if ($this->input->post('periode_sales_filter_start') != null and $this->input->post('periode_sales_filter_end') != null) {
            $this->db->group_start();
            $this->db->where('so.tanggal_order >=', $this->input->post('periode_sales_filter_start'));
            $this->db->where('so.tanggal_order <=', $this->input->post('periode_sales_filter_end'));
            $this->db->group_end();
        }

        if ($this->input->post('tipe_penjualan_filter')) {
            $this->db->where('so.po_type', $this->input->post('tipe_penjualan_filter'));
        }

        if ($this->input->post('kategori_sales_filter')) {
            $this->db->where('so.kategori_po', $this->input->post('kategori_sales_filter'));
        }

        if ($this->input->post('tipe_produk_filter')) {
            $this->db->where('so.produk', $this->input->post('tipe_produk_filter'));
        }

        if ($this->input->post('status_filter') != null and count($this->input->post('status_filter')) > 0) {
            $this->db->where_in('dso.status', $this->input->post('status_filter'));
        }
    }

    private function order()
    {
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('dso.created_at', 'desc');
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
        $this->benchmark->mark('recordsFiltered_start');
        $this->make_datatables();
        $record = $this->db->get()->num_rows();
        $this->benchmark->mark('recordsFiltered_end');

        return $record;
    }

    public function recordsTotal()
    {
        $this->benchmark->mark('recordsTotal_start');
        $this->make_query();
        $record = $this->db->count_all_results();
        $this->benchmark->mark('recordsTotal_end');

        return $record;
    }
}
