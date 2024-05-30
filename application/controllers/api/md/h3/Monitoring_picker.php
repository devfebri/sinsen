<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring_picker extends CI_Controller
{

    public function index()
    {
        $this->benchmark->mark('data_start');
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $picking_list = $this->db
            ->select('SUM(plp_sq.qty_disiapkan) as qty_disiapkan')
            ->from('tr_h3_md_picking_list_parts as plp_sq')
            ->join('tr_h3_md_picking_list as pl_sq', 'pl_sq.id = plp_sq.id_picking_list_int')
            ->where('pl_sq.id_ref_int = do_sq.id')
            ->where('plp_sq.id_part_int = dop_sq.id_part_int')
            ->get_compiled_select();
    
            $picking_list_kpb = $this->db
            ->select('SUM(plp_sq.qty_disiapkan) as qty_disiapkan')
            ->from('tr_h3_md_picking_list_parts as plp_sq')
            ->join('tr_h3_md_picking_list as pl_sq', 'pl_sq.id = plp_sq.id_picking_list_int')
            ->where('pl_sq.id_ref_int = do_sq.id')
            ->where('plp_sq.id_part_int = dop_sq.id_part_int')
            ->where('plp_sq.id_tipe_kendaraan = dop_sq.id_tipe_kendaraan')
            ->get_compiled_select();
    
            $amount_pl = $this->db
            ->select("
            case
                when so_sq.kategori_po = 'KPB' then SUM(dop_sq.harga_setelah_diskon * IFNULL(({$picking_list_kpb}), 0))
                else SUM(dop_sq.harga_setelah_diskon * IFNULL(({$picking_list}), 0))
            end as amount
            ", false)
            ->from('tr_h3_md_do_sales_order as do_sq')
            ->join('tr_h3_md_do_sales_order_parts dop_sq', 'do_sq.id = dop_sq.id_do_sales_order_int')
            ->join('tr_h3_md_sales_order as so_sq', '(so_sq.id = do_sq.id_sales_order_int)')
            ->where('do_sq.id',$row['do_id'])
            // ->get_compiled_select();
            ->get()->row_array();

            // $test = $amount_pl['amount'] - ($row['diskon_insentif'] + $row['diskon_cashback']);
            $row['amount_pl'] = "Rp " . number_format($amount_pl['amount']+ $row['total_ppn'] - ($row['diskon_insentif'] + $row['diskon_cashback']),0,",",".");
            $row['service_rate'] = ($amount_pl['amount']+ $row['total_ppn'] - ($row['diskon_insentif'] + $row['diskon_cashback']))/$row['total']*100 . " %";

            $row['action'] = $this->load->view('additional/action_index_monitoring_picker_datatable', [
                'id' => $row['id_picking_list'],
                'status' => $row['status'],
                'ready_for_scan' => $row['ready_for_scan'],
            ], true);
            $row['id_picking_list'] = $this->load->view('additional/action_index_picking_list_monitoring_picker_datatable', [
                'id' => $row['id_picking_list'],
            ], true);
            $row['id_do_sales_order'] = $this->load->view('additional/action_open_modal_do_sales_order_monitoring_picker', [
                'id_do_sales_order' => $row['id_do_sales_order'],
            ], true);
            $row['id_sales_order'] = $this->load->view('additional/action_open_modal_sales_order_monitoring_picker', [
                'id_sales_order' => $row['id_sales_order'],
            ], true);

            $row['index'] = $this->input->post('start') . $index . '.';
            $index++;

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
        // $total_item_do = $this->db
        // ->select('count(dop.id_part)')
        // ->from('tr_h3_md_do_sales_order_parts as dop')
        // ->where('dop.id_do_sales_order_int = do.id')
        // ->get_compiled_select();

        // $total_pcs_do = $this->db
        // ->select('sum(dop.qty_supply)')
        // ->from('tr_h3_md_do_sales_order_parts as dop')
        // ->where('dop.id_do_sales_order_int = do.id')
        // ->get_compiled_select();

        // $picking_list = $this->db
        // ->select('SUM(plp_sq.qty_disiapkan) as qty_disiapkan')
        // ->from('tr_h3_md_picking_list_parts as plp_sq')
        // ->join('tr_h3_md_picking_list as pl_sq', 'pl_sq.id = plp_sq.id_picking_list_int')
        // ->where('pl_sq.id_ref_int = do_sq.id')
        // ->where('plp_sq.id_part_int = dop_sq.id_part_int')
        // ->get_compiled_select();

        // $picking_list_kpb = $this->db
        // ->select('SUM(plp_sq.qty_disiapkan) as qty_disiapkan')
        // ->from('tr_h3_md_picking_list_parts as plp_sq')
        // ->join('tr_h3_md_picking_list as pl_sq', 'pl_sq.id = plp_sq.id_picking_list_int')
        // ->where('pl_sq.id_ref_int = do_sq.id')
        // ->where('plp_sq.id_part_int = dop_sq.id_part_int')
        // ->where('plp_sq.id_tipe_kendaraan = dop_sq.id_tipe_kendaraan')
        // ->get_compiled_select();

        // $amount_pl = $this->db
        // ->select("
        // case
        //     when so_sq.kategori_po = 'KPB' then SUM(dop_sq.harga_setelah_diskon * IFNULL(({$picking_list_kpb}), 0))
        //     else SUM(dop_sq.harga_setelah_diskon * IFNULL(({$picking_list}), 0))
        // end as amount
        // ", false)
        // ->from('tr_h3_md_do_sales_order as do_sq')
        // ->join('tr_h3_md_do_sales_order_parts dop_sq', 'do_sq.id = dop_sq.id_do_sales_order_int')
        // ->join('tr_h3_md_sales_order as so_sq', '(so_sq.id = do_sq.id_sales_order_int)')
        // ->where('do_sq.id = do.id')
        // ->get_compiled_select();

        // $total_item_pl = $this->db
        // ->select('count(DISTINCT(plp.id_part))')
        // ->from('tr_h3_md_picking_list_parts as plp')
        // ->where('plp.id_picking_list_int = pl.id')
        // ->get_compiled_select();

        // $total_pcs_pl = $this->db
        // ->select('sum(
        //     ifnull(plp.qty_disiapkan, 0)
        // )')
        // ->from('tr_h3_md_picking_list_parts as plp')
        // ->where('plp.id_picking_list_int = pl.id')
        // ->get_compiled_select();

        $this->db
        ->select('date_format(so.tanggal_order, "%d/%m/%Y") as tanggal_so')
        ->select('so.id_sales_order')
        ->select('date_format(do.tanggal, "%d/%m/%Y") as tanggal_do')
        ->select('do.id_do_sales_order')
        ->select('date_format(pl.tanggal, "%d/%m/%Y") as tanggal_picking')
        ->select('pl.id_picking_list')
        ->select('d.kode_dealer_md')
        ->select('do.diskon_insentif')
        ->select('do.diskon_cashback')
        ->select('do.id as do_id')
        ->select('ifnull(do.total_ppn,0) as total_ppn')
        ->select('do.total')
        ->select('d.nama_dealer')
        ->select('kab.kabupaten')
        ->select('
            concat(
                "Rp ",
                format(do.total, 0, "ID_id")
            ) as amount_do'
        , false)
        // ->select("({$total_item_do}) as total_item_do")
        // ->select("({$total_pcs_do}) as total_pcs_do")
        // ->select("
        // concat(
        //     'Rp ',
        //     format(
        //         (
        //             ({$amount_pl}) -
        //             ( do.diskon_insentif + do.diskon_cashback )
        //         )
        //     , 0, 'ID_id')
        // ) as amount_pl"
        // , false)
        // ->select("({$total_item_pl}) as total_item_pl")
        // ->select("({$total_pcs_pl}) as total_pcs_pl")
        // ->select(" 
        // concat(
        //     format( 
        //         (
        //             (
        //                 ({$amount_pl}) -
        //                 ( do.diskon_insentif + do.diskon_cashback )
        //             ) 
        //             / 
        //             do.total
        //         ) * 100,
        //         1,
        //         'ID_id'
        //     ),
        //     ' %'   
        // )
        // as service_rate")
        // ->select("0 as amount_pl")
        // ->select("0 as service_rate")
        ->select('k.nama_lengkap as nama_picker')
        ->select('pl.status')
        ->select('pl.ready_for_scan')
        ->from('tr_h3_md_picking_list as pl')
        ->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list_int = pl.id', 'left')
        ->join('tr_h3_md_do_sales_order as do', 'do.id = pl.id_ref_int')
        ->join('tr_h3_md_sales_order as so', 'do.id_sales_order_int = so.id')
        ->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
        ->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan', '')
        ->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan')
        ->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten')
        ->join('ms_karyawan as k', 'k.id_karyawan = pl.id_picker', 'left')
        ;

        if($this->input->post('history') != null AND $this->input->post('history') == 1){
            $this->db->group_start();
            $this->db->where('ps.faktur_printed', 1);
            // $this->db->or_where('left(ps.created_at,10) <=', '2023-09-08');
            $this->db->or_where('pl.tanggal <=', '2023-09-30');
            $this->db->group_end();
        }else{
            // $this->db->where('left(ps.created_at,10) >', '2023-09-08');
            $this->db->where('pl.tanggal >', '2023-10-01');
            $this->db->group_start();
            $this->db->where('ps.faktur_printed', 0);
            $this->db->or_where('ps.id', null);
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        if (count($this->input->post('filter_picker')) > 0) {
            $this->db->where_in('pl.id_picker', $this->input->post('filter_picker'));
        }

        if (count($this->input->post('filter_customer')) > 0) {
            $this->db->where_in('so.id_dealer', $this->input->post('filter_customer'));
        }

        if (count($this->input->post('filter_kabupaten')) > 0) {
            $this->db->where_in('kab.id_kabupaten', $this->input->post('filter_kabupaten'));
        }

        if (count($this->input->post('filter_status')) > 0) {
            $this->db->where_in('pl.status', $this->input->post('filter_status'));
        }

        if (count($this->input->post('filter_sales_order')) > 0) {
            $this->db->where_in('so.id_sales_order', $this->input->post('filter_sales_order'));
        }

        if (count($this->input->post('filter_delivery_order')) > 0) {
            $this->db->where_in('do.id_do_sales_order', $this->input->post('filter_delivery_order'));
        }

        if (count($this->input->post('filter_picking_list')) > 0) {
            $this->db->where_in('pl.id_picking_list', $this->input->post('filter_picking_list'));
        }

        if($this->input->post('periode_so_filter_start') != null and $this->input->post('periode_so_filter_end') != null){            
            $this->db->group_start();
            $this->db->where('so.tanggal_order >=', $this->input->post('periode_so_filter_start'));
            $this->db->where('so.tanggal_order <=', $this->input->post('periode_so_filter_end'));
            $this->db->group_end();
        }

        if($this->input->post('periode_do_filter_start') != null and $this->input->post('periode_do_filter_end') != null){            
            $this->db->group_start();
            $this->db->where('do.tanggal >=', $this->input->post('periode_do_filter_start'));
            $this->db->where('do.tanggal <=', $this->input->post('periode_do_filter_end'));
            $this->db->group_end();
        }

        if($this->input->post('periode_picking_list_filter_start') != null and $this->input->post('periode_picking_list_filter_end') != null){            
            $this->db->group_start();
            $this->db->where('pl.tanggal >=', $this->input->post('periode_picking_list_filter_start'));
            $this->db->where('pl.tanggal <=', $this->input->post('periode_picking_list_filter_end'));
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pl.validation_end', 'DESC');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
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
