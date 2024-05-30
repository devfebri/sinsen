<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Hutang_do extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_hutang_do_datatable', [
                'id' => $row['id_do_sales_order']
            ], true);
            $data[] = $row;
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
        // $picking_list_yang_berselisih = $this->db
        // ->from('tr_h3_md_scan_picking_list_parts as spl')
        // ->where('spl.qty_picking != spl.qty_scan')
        // ->group_by('spl.id_picking_list')
        // ->get()->result_array();

        // $picking_list_yang_berselisih = $this->db
        // ->select('pl.id_picking_list')
        // ->from('tr_h3_md_picking_list as pl')
        // ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
        // ->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
        // ->join('tr_h3_md_scan_picking_list_parts as splp', 'splp.id_picking_list = pl.id_picking_list')
        // ->where('pl.selesai_scan', 1)
        // ->where('SUM(dop.qty_supply) != SUM(splp.qty_scan)', null, false)
        // ->get()->result_array();

        // $picking_list_yang_berselisih = array_column($picking_list_yang_berselisih, 'id_picking_list');

        // $qty_scan = $this->db
        // ->select('SUM(splp.qty_scan)')
        // ->from('tr_h3_md_scan_picking_list_parts as splp')
        // ->where('splp.id_picking_list = pl.id_picking_list')
        // ->where('splp.id_part_int = dsop.id_part_int')
        // ->get_compiled_select();

        // $this->db
        // ->select('dso.id_do_sales_order')
        // ->select('po.po_id')
        // ->select('po.po_type')
        // ->select('date_format(dso.tanggal, "%d/%m/%Y") as tanggal_do')
        // ->select('date_format(so.batas_waktu, "%d/%m/%Y") as masa_berlaku')
        // ->select('dsop.id_part')
        // ->select('p.nama_part')
        // // ->select('SUM(splp.qty_scan) as qty_scan')
        // ->select('dsop.harga_jual')
        // ->select('dsop.qty_supply as qty_do')
        // ->select('(dsop.qty_supply * dsop.harga_jual) as amount_do')
        // // ->select("IFNULL(({$qty_scan}), 0) as qty_supply")
        // ->select("0 as qty_supply")
        // // ->select("(IFNULL(({$qty_scan}), 0) * dsop.harga_jual) as amount_supply")
        // ->select("0 as amount_supply")
        // // ->select("(dsop.qty_supply - IFNULL(({$qty_scan}), 0)) as qty_sisa_do")
        // ->select("0 as qty_sisa_do")
        // // ->select("((dsop.qty_supply - IFNULL(({$qty_scan}), 0)) * dsop.harga_jual) as amount_sisa_do")
        // ->select("0 as amount_sisa_do")
        // ->select('po.status')
        // ->from('tr_h3_md_do_sales_order as dso')
        // ->join('tr_h3_md_do_sales_order_parts as dsop', 'dsop.id_do_sales_order = dso.id_do_sales_order')
        // ->join('ms_part as p', 'p.id_part_int = dsop.id_part_int')
        // ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
        // ->join('tr_h3_dealer_purchase_order as po', 'so.id_ref = po.po_id')
        // ->join('tr_h3_md_picking_list as pl', 'pl.id_ref = dso.id_do_sales_order')
        // // ->join('tr_h3_md_scan_picking_list_parts as splp', '(splp.id_picking_list = pl.id_picking_list and splp.id_part = dsop.id_part)')
        // ->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
        // ->where('pl.selesai_scan', 1)
        // ->where("(dsop.qty_supply - IFNULL((select SUM(splp.qty_scan) 
        // from tr_h3_md_scan_picking_list_parts as splp where splp.id_picking_list = pl.id_picking_list and splp.id_part = dsop.id_part), 0)) > 0", null, false)

        $this->db
        ->select('dso.id_do_sales_order')
        ->select('po.po_id')
        ->select('po.po_type')
        ->select('date_format(dso.tanggal, "%d/%m/%Y") as tanggal_do')
        ->select('date_format(so.batas_waktu, "%d/%m/%Y") as masa_berlaku')
        ->select('dsop.id_part')
        ->select('p.nama_part')
        // ->select('SUM(splp.qty_scan) as qty_scan')
        ->select('dsop.harga_jual')
        ->select('dsop.qty_supply as qty_do')
        ->select('(dsop.qty_supply * dsop.harga_jual) as amount_do')
        // ->select("IFNULL(({$qty_scan}), 0) as qty_supply")
        // ->select("0 as qty_supply")
        // ->select("(IFNULL(({$qty_scan}), 0) * dsop.harga_jual) as amount_supply")
        // ->select("0 as amount_supply")
        // ->select("(dsop.qty_supply - IFNULL(({$qty_scan}), 0)) as qty_sisa_do")
        // ->select("0 as qty_sisa_do")
        // ->select("((dsop.qty_supply - IFNULL(({$qty_scan}), 0)) * dsop.harga_jual) as amount_sisa_do")
        // ->select("0 as amount_sisa_do")
        ->select('(dsop.qty_supply - COALESCE(splp_sum.total_qty_scan, 0)) as qty_supply')
        ->select("(IFNULL(((dsop.qty_supply - COALESCE(splp_sum.total_qty_scan, 0))), 0) * dsop.harga_jual) as amount_supply")
        ->select("(dsop.qty_supply - IFNULL(((dsop.qty_supply - COALESCE(splp_sum.total_qty_scan, 0))), 0)) as qty_sisa_do")
        ->select("((dsop.qty_supply - IFNULL(((dsop.qty_supply - COALESCE(splp_sum.total_qty_scan, 0))), 0)) * dsop.harga_jual) as amount_sisa_do")
        ->select('po.status')
        ->from('tr_h3_md_do_sales_order as dso')
        ->join('tr_h3_md_do_sales_order_parts as dsop', 'dsop.id_do_sales_order = dso.id_do_sales_order')
        ->join('ms_part as p', 'p.id_part_int = dsop.id_part_int')
        ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
        ->join('tr_h3_dealer_purchase_order as po', 'so.id_ref = po.po_id')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_ref = dso.id_do_sales_order')
        ->join('(SELECT splp.id_picking_list, splp.id_part, SUM(splp.qty_scan) AS total_qty_scan
            FROM tr_h3_md_scan_picking_list_parts splp
            GROUP BY splp.id_picking_list, splp.id_part) as splp_sum','splp_sum.id_picking_list = pl.id_picking_list AND splp_sum.id_part = dsop.id_part','left')
        ->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
        ->where('pl.selesai_scan', 1)
        ;

        // if(count($picking_list_yang_berselisih) > 0){
        //     $this->db->where_in('pl.id_picking_list', $picking_list_yang_berselisih);
        // }else{
        //     $this->db->where('1=0');
        // }
        if($this->input->post('history') != null AND $this->input->post('history') == 1){
            $this->db->group_start();
                $this->db->where('left(dso.created_at,10) <=', '2023-09-30');
                // $this->db->or_where('left(dso.created_at,10) <=', '2023-09-08');
            $this->db->group_end();
        }else{
            $this->db->group_start();
                $this->db->where('left(dso.created_at,10) >', '2023-10-01');
                    // $this->db->where('dso.status =', 'On Process');
                    // $this->db->or_where('left(so.created_at,10) <=', '2023-09-08');
            $this->db->group_end();
        }

    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('dso.id_sales_order', $search);
            $this->db->or_like('dso.id_do_sales_order', $search);
            $this->db->group_end();
        }

        if($this->input->post('no_do_filter') != null){
            $this->db->like('dso.id_do_sales_order', $this->input->post('no_do_filter'));
        }

        if ($this->input->post('filter_customer') != null and count($this->input->post('filter_customer')) > 0) {
            $this->db->where_in('so.id_dealer', $this->input->post('filter_customer'));
        }

        if ($this->input->post('tipe_penjualan_filter') != null and count($this->input->post('tipe_penjualan_filter')) > 0) {
            $this->db->where_in('so.po_type', $this->input->post('tipe_penjualan_filter'));
        }

        if($this->input->post('periode_filter_start') != null and $this->input->post('periode_filter_end') != null){            
            $this->db->group_start();
            $this->db->where('dso.tanggal >=', $this->input->post('periode_filter_start'));
            $this->db->where('dso.tanggal <=', $this->input->post('periode_filter_end'));
            $this->db->group_end();
        }

        $this->db->having('qty_supply >',0);

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('dso.created_at', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}
