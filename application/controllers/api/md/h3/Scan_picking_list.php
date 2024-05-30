<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Scan_picking_list extends CI_Controller
{
    public function index()
    {
        $this->benchmark->mark('data_start');
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_scan_picking_list', [
                'data' => $row
            ], true);

            $jumlah_pack_parts = $this->db
            ->select('IFNULL(count(DISTINCT splp_sq.no_dus),0) as jumlah_pack_parts')
            ->from('tr_h3_md_scan_picking_list_parts as splp_sq')
            ->where('splp_sq.id_picking_list_int',$row['id_pl_int'])
            ->where('splp_sq.produk', 'Parts')
            ->get()->row_array();

            $jumlah_pack_tire = $this->db
            ->select('IFNULL(count(DISTINCT splp_sq.no_dus),0) as jumlah_pack_tire')
            ->from('tr_h3_md_scan_picking_list_parts as splp_sq')
            ->where('splp_sq.id_picking_list_int',$row['id_pl_int'])
            ->where('splp_sq.produk', 'Ban')
            ->get()->row_array();
    
            $jumlah_pack_oil = $this->db
            ->select('IFNULL(count(DISTINCT splp_sq.no_dus),0) as jumlah_pack_oil')
            ->from('tr_h3_md_scan_picking_list_parts as splp_sq')
            ->where('splp_sq.id_picking_list_int',$row['id_pl_int'])
            ->where('splp_sq.produk', 'Oil')
            ->get()->row_array();
    

            $row['jumlah_pack_parts'] = $jumlah_pack_parts['jumlah_pack_parts'];
            $row['jumlah_pack_tire'] = $jumlah_pack_tire['jumlah_pack_tire'];
            $row['jumlah_pack_oil'] = $jumlah_pack_oil['jumlah_pack_oil'];
            $row['jumlah_pack_all'] = $jumlah_pack_parts['jumlah_pack_parts'] + $jumlah_pack_tire['jumlah_pack_tire'] + $jumlah_pack_oil['jumlah_pack_oil'];

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

    public function make_query()
    {
        /*
        $jumlah_pack_parts = $this->db
        ->select('count(DISTINCT splp_sq.no_dus)')
        ->from('tr_h3_md_scan_picking_list_parts as splp_sq')
        ->where('splp_sq.id_picking_list_int = pl.id')
        ->where('splp_sq.produk', 'Parts')
        ->get_compiled_select();

        $jumlah_pack_tire = $this->db
        ->select('count(DISTINCT splp_sq.no_dus)')
        ->from('tr_h3_md_scan_picking_list_parts as splp_sq')
        ->where('splp_sq.id_picking_list_int = pl.id')
        ->where('splp_sq.produk', 'Ban')
        ->get_compiled_select();

        $jumlah_pack_oil = $this->db
        ->select('count(DISTINCT splp_sq.no_dus)')
        ->from('tr_h3_md_scan_picking_list_parts as splp_sq')
        ->where('splp_sq.id_picking_list_int = pl.id')
        ->where('splp_sq.produk', 'Oil')
        ->get_compiled_select();

        $jumlah_pack_all = $this->db
        ->select('count(DISTINCT splp_sq.no_dus)')
        ->from('tr_h3_md_scan_picking_list_parts as splp_sq')
        ->where('splp_sq.id_picking_list_int = pl.id')
        ->get_compiled_select();

        */

        $do_revisi_open = $this->db
        ->select('count(dr.id)')
        ->from('tr_h3_md_do_revisi as dr')
        ->where('dr.id_do_sales_order_int = pl.id_ref_int')
        ->where('dr.status', 'Open')
        ->where('dr.source', 'validasi_picking_list')
        ->get_compiled_select();

        

        $this->db
        ->select('pl.id_picking_list')
        ->select('pl.id as id_pl_int')
        ->select('date_format(pl.created_at, "%d-%m-%Y") as tanggal_picking')
        ->select('do.id_do_sales_order')
        ->select('date_format(do.tanggal, "%d-%m-%Y") as tanggal_do')
        ->select('so.id_sales_order')
        ->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_so')
        ->select('so.po_type')
        ->select('so.kategori_po')
        ->select('d.kode_dealer_md')
        ->select('d.nama_dealer')
        ->select('kab.kabupaten')
        // ->select("IFNULL( ({$jumlah_pack_parts}), 0 ) as jumlah_pack_parts")
        // ->select("IFNULL( ({$jumlah_pack_tire}), 0 ) as jumlah_pack_tire")
        // ->select("IFNULL( ({$jumlah_pack_oil}), 0 ) as jumlah_pack_oil")
        // ->select("IFNULL( ({$jumlah_pack_all}), 0 ) as jumlah_pack_all")
        ->select('
            case
                when pl.start_scan is null then "-"
                else date_format(pl.start_scan, "%d/%m/%Y %H:%i")
            end as start_scan
        ', false)
        ->select('
            case
                when pl.end_scan is null then "-"
                else date_format(pl.end_scan, "%d/%m/%Y %H:%i")
            end as end_scan
        ', false)
        ->select('
        case
            when pl.start_scan is null or pl.end_scan is null then "-"
            else timestampdiff(second, pl.start_scan, pl.end_scan) * 100
        end as durasi_scan', false)
        ->select('pl.status')
        ->select('pl.id_ref_int')
        ->select('pl.selesai_scan')
        ->select('pl.ready_for_scan')
        ->from('tr_h3_md_picking_list as pl')
        ->join('ms_karyawan as k', 'k.id_karyawan = pl.id_picker')
        ->join('tr_h3_md_do_sales_order as do', 'do.id = pl.id_ref_int')
        ->join('tr_h3_md_sales_order as so', 'so.id = do.id_sales_order_int')
        ->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
        ->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan', 'left')
        ->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
        ->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
        ->where("IFNULL(({$do_revisi_open}), 0) < 1", null, false)
        ->where('pl.ready_for_scan', 1)
        ->where('pl.status !=', 'Re-check')
        ->where('pl.status !=', 'Canceled')
        ;

        if($this->input->post('history') != null AND $this->input->post('history') == 1){
            $this->db->where('pl.selesai_scan', 1);
        }else{
            $this->db->where('pl.selesai_scan', 0);
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->or_like('pl.id_picking_list', $search);
            $this->db->or_like('pl.id_ref', $search);
            $this->db->or_like('d.nama_dealer', $search);
            $this->db->group_end();
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pl.datetime_proses_scan', 'desc');
        }
    }

    public function limit(){
        if ($this->input->post('length') != - 1) {
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
