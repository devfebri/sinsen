<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring_picking_list extends CI_Controller

{
    public function index()
    {
        $this->benchmark->mark('data_start');
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['id_do_sales_order'] = $this->load->view('additional/action_open_modal_do_sales_order_monitoring_picking_list', [
                'id_do_sales_order' => $row['id_do_sales_order']
            ], true);
            $row['id_sales_order'] = $this->load->view('additional/action_open_modal_sales_order_monitoring_picking_list', [
                'id_sales_order' => $row['id_sales_order']
            ], true);
            $row['action'] = $this->load->view('additional/action_index_monitoring_picking_list_datatable', [
                'id' => $row['id_picking_list']
            ], true);

            $total_item = $this->db
                        ->select('count(plp.id_part_int) as total_item')
                        ->from('tr_h3_md_picking_list_parts as plp')
                        ->where('plp.id_picking_list_int',$row['id_pl_int'])
                        ->get()->row_array();

            $total_pcs = $this->db
                        ->select('sum(plp.qty_supply) as total_pcs')
                        ->from('tr_h3_md_picking_list_parts as plp')
                        ->where('plp.id_picking_list_int',$row['id_pl_int'])
                        ->get()->row_array();


            $row['total_item'] = $total_item['total_item'];
            $row['total_pcs'] = $total_pcs['total_pcs'];
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
        $total_item = $this->db
            ->select('count(
            DISTINCT(plp.id_part)
        )')
            ->from('tr_h3_md_picking_list_parts as plp')
            ->where('plp.id_picking_list_int = pl.id')
            ->get_compiled_select();

        $total_pcs = $this->db
            ->select('sum(plp.qty_supply)')
            ->from('tr_h3_md_picking_list_parts as plp')
            ->where('plp.id_picking_list_int = pl.id')
            ->get_compiled_select();

        */

        $this->db
            ->select('date_format(so.tanggal_order, "%d/%m/%Y") as tanggal_sales')
            ->select('so.id_sales_order')
            ->select('date_format(do.tanggal, "%d/%m/%Y") as tanggal_do')
            ->select('do.id_do_sales_order')
            ->select('date_format(pl.tanggal, "%d/%m/%Y") as tanggal_picking')
            ->select('pl.id_picking_list')
            ->select('d.kode_dealer_md')
            ->select('d.nama_dealer')
            ->select('kab.kabupaten')
            ->select('do.total')
            ->select('
            concat(
                "Rp ",
                format(do.total, 0, "ID_id")
            ) as total_formatted
        ', false)
            ->select('
            case
                when k.nama_lengkap is not null then k.nama_lengkap
                else "-"
            end as nama_picker
        ', false)
            // ->select("({$total_item}) as total_item", false)
            // ->select("({$total_pcs}) as total_pcs", false)
            ->select('pl.status')
            ->select('pl.id_picker')
            ->select('pl.id as id_pl_int')
            ->from('tr_h3_md_picking_list as pl')
            ->join('tr_h3_md_do_sales_order as do', 'do.id = pl.id_ref_int')
            ->join('tr_h3_md_sales_order as so', 'so.id = do.id_sales_order_int')
            ->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
            ->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan')
            ->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan')
            ->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten')
            ->join('ms_karyawan as k', 'k.id_karyawan = pl.id_picker', 'left')
            ->where('pl.status !=', 'Scan');
    }

    public function make_datatables()
    {
        $this->make_query();

        if (count($this->input->post('id_customer')) > 0) {
            $this->db->where_in('so.id_dealer', $this->input->post('id_customer'));
        }

        if (count($this->input->post('id_picking_list')) > 0) {
            $this->db->where_in('pl.id_picking_list', $this->input->post('id_picking_list'));
        }

        if (count($this->input->post('id_do_sales_order')) > 0) {
            $this->db->where_in('do.id_do_sales_order', $this->input->post('id_do_sales_order'));
        }

        if (count($this->input->post('picker')) > 0) {
            $this->db->where_in('pl.id_picker', $this->input->post('picker'));
        }

        if (count($this->input->post('kabupaten')) > 0) {
            $this->db->where_in('kab.id_kabupaten', $this->input->post('kabupaten'));
        }

        if ($this->input->post('periode_sales_filter_start') != null and $this->input->post('periode_sales_filter_end') != null) {
            $this->db->group_start();
            $this->db->where('so.tanggal_order >=', $this->input->post('periode_sales_filter_start'));
            $this->db->where('so.tanggal_order <=', $this->input->post('periode_sales_filter_end'));
            $this->db->group_end();
        }

        if ($this->input->post('periode_picking_list_filter_start') != null and $this->input->post('periode_picking_list_filter_end') != null) {
            $this->db->group_start();
            $this->db->where('pl.tanggal >=', $this->input->post('periode_picking_list_filter_start'));
            $this->db->where('pl.tanggal <=', $this->input->post('periode_picking_list_filter_end'));
            $this->db->group_end();
        }

        if($this->input->post('history') != null AND $this->input->post('history') == 1){
            $this->db->group_start();
                $this->db->where('pl.tanggal <=', '2023-09-30');
                // $this->db->or_where('left(dso.created_at,10) <=', '2023-09-08');
            $this->db->group_end();
        }else{

            $this->db->group_start();
                $this->db->where('pl.tanggal >', '2023-10-01');
                    // $this->db->where('dso.status =', 'On Process');
                    // $this->db->or_where('left(so.created_at,10) <=', '2023-09-08');
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pl.created_at', 'DESC');
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
        $this->benchmark->mark('recordsFiltered_start');
        $this->make_datatables();
        $record = $this->db->count_all_results();
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
