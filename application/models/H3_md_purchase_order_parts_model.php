<?php

class h3_md_purchase_order_parts_model extends Honda_Model{

    protected $table = 'tr_h3_md_purchase_order_parts';

    public function __construct(){
        parent::__construct();
        $this->load->model('H3_md_stock_model', 'stock');
        $this->load->model('H3_md_history_estimasi_waktu_hotline_model', 'history_estimasi_waktu_hotline_model');
        $this->load->library('Mcarbon');

        $this->load->model('h3_md_purchase_order_model', 'po_md');
    }

    public function insert($data){
        if(!isset($data['id_part_int']) AND isset($data['id_part'])){
            $part = $this->db
            ->select('p.id_part_int')
            ->from('ms_part as p')
            ->where('p.id_part', $data['id_part'])
            ->get()->row_array();

            if($part != null) $data['id_part_int'] = $part['id_part_int'];
        }
        parent::insert($data);
    }
    
    public function qty_on_order_md($id_dealer, $id_part, $sql = false){
        $part_sudah_diproses_di_md = $this->db
        ->select('SUM(opt.qty_bill) as qty_bill', false)
        ->from('tr_h3_dealer_order_parts_tracking as opt')
        ->where('opt.po_id = po.po_id')
        ->where('opt.id_part = pop.id_part')
        ->get_compiled_select();

        $this->db
        ->select("IFNULL( SUM(pop.kuantitas - IFNULL(({$part_sudah_diproses_di_md}), 0)), 0 ) as stock", false)
        ->from('tr_h3_dealer_purchase_order_parts as pop')
        ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = pop.po_id')
        ->where('po.status', 'Processed by MD');

        if($sql){
            $this->db->where("pop.id_part = {$id_part}");
            $this->db->where("po.id_dealer = {$id_dealer}");

            return $this->db->get_compiled_select();
        }else{
            $this->db->where('pop.id_part', $id_part);
            $this->db->where('po.id_dealer', $id_dealer);

            $data = $this->db->get()->row_array();
            return $data != null ? $data['stock'] : 0;
        }
    }

    public function qty_on_order_md2($id_dealer, $id_part_int, $sql = false){
        $part_sudah_diproses_di_md = $this->db
        ->select('SUM(opt.qty_bill) as qty_bill', false)
        ->from('tr_h3_dealer_order_parts_tracking as opt')
        ->where('opt.po_id = po.po_id')
        ->where('opt.id_part_int = pop.id_part_int')
        ->get_compiled_select();

        $this->db
        ->select("IFNULL( SUM(pop.kuantitas - IFNULL(({$part_sudah_diproses_di_md}), 0)), 0 ) as stock", false)
        ->from('tr_h3_dealer_purchase_order_parts as pop')
        ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = pop.po_id')
        ->where('po.status', 'Processed by MD');

        if($sql){
            $this->db->where("pop.id_part_int = {$id_part_int}");
            $this->db->where("po.id_dealer = {$id_dealer}");

            return $this->db->get_compiled_select();
        }else{
            $this->db->where('pop.id_part_int', $id_part_int);
            $this->db->where('po.id_dealer', $id_dealer);

            $data = $this->db->get()->row_array();
            return $data != null ? $data['stock'] : 0;
        }
    }

    public function qty_on_order_md_v3($id_dealer, $id_part_int, $sql = false){
        // $part_sudah_diproses_di_md = $this->db
        // ->select('SUM(opt.qty_bill) as qty_bill', false)
        // ->from('tr_h3_dealer_order_parts_tracking as opt')
        // ->where('opt.po_id = po.po_id')
        // ->where('opt.id_part_int = pop.id_part_int')
        // ->get_compiled_select();

        $part_sudah_diproses_di_md = $this->db
        ->select('SUM(grp.qty) as qty_bill', false)
        ->from('tr_h3_dealer_good_receipt as gr')
        ->join('tr_h3_dealer_good_receipt_parts as grp','gr.id_good_receipt=grp.id_good_receipt')
        ->where('gr.nomor_po = po.po_id')
        // ->where('grp.id_part_int = pop.id_part_int')
        ->where('grp.id_part = pop.id_part')
        ->get_compiled_select();

        $this->db
        ->select("IFNULL( SUM(pop.kuantitas - IFNULL(({$part_sudah_diproses_di_md}), 0)), 0 ) as stock", false)
        ->from('tr_h3_dealer_purchase_order_parts as pop')
        ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = pop.po_id')
        ->where('po.status', 'Processed by MD');

        if($sql){
            $this->db->where("pop.id_part_int = {$id_part_int}");
            $this->db->where("po.id_dealer = {$id_dealer}");

            return $this->db->get_compiled_select();
        }else{
            $this->db->where('pop.id_part_int', $id_part_int);
            $this->db->where('po.id_dealer', $id_dealer);

            $data = $this->db->get()->row_array();
            return $data != null ? $data['stock'] : 0;
        }
    }
    

    public function qty_fix_bulan_lalu($id_part_int, $pesan_untuk_bulan, $sql = false){
        $bulan_lalu = $pesan_untuk_bulan->copy();
        
        $this->db
        ->select(sprintf('IFNULL( SUM(pop_%s.qty_order), 0 ) as qty', __FUNCTION__))
        // ->select('po_fix_bulan_lalu.id_purchase_order')
        // ->select('po_fix_bulan_lalu.tanggal_po')
        // ->select('po_fix_bulan_lalu.jenis_po')
        // ->select('po_fix_bulan_lalu.bulan')
        // ->select('po_fix_bulan_lalu.tahun')
        // ->select('po_fix_bulan_lalu.status')
        // ->select('pop_fix_bulan_lalu.id_part')
        // ->select('pop_fix_bulan_lalu.qty_order')
        ->from(sprintf('tr_h3_md_purchase_order_parts as pop_%s', __FUNCTION__))
        ->join(sprintf('tr_h3_md_purchase_order as po_%s', __FUNCTION__), sprintf('po_%s.id = pop_%s.id_purchase_order_int', __FUNCTION__, __FUNCTION__))
        ->where(sprintf("SUBSTRING(po_%s.tahun, 1, 4) = '%s'", __FUNCTION__, $bulan_lalu->format('Y')), null, false)
        ->where(sprintf("SUBSTRING(po_%s.bulan, 6, 2) = '%s'", __FUNCTION__, $bulan_lalu->format('m')), null, false)
        ->where(sprintf('po_%s.jenis_po', __FUNCTION__), 'FIX')
        ->group_start()
        ->where(sprintf('po_%s.status', __FUNCTION__), 'Closed')
        ->or_where(sprintf('po_%s.status', __FUNCTION__), 'Approved')
        ->group_end()
        ->order_by(sprintf('po_%s.created_at', __FUNCTION__), 'desc');

        $this->db->where(sprintf('pop_%s.id_part_int', __FUNCTION__), $id_part_int, !$sql);

        if($sql){
            return $this->db->get_compiled_select();
        }else{
            $data = $this->db->get()->row_array();
            return $data != null ? $data['qty'] : 0;
        }
    }

    public function qty_bo_ahm($id_part_int, $jenis_po, $bulan_berjalan, $pesan_untuk_bulan, $exclude_po = [], $sql = false){
        $qty_ps = $this->stock->qty_ps(sprintf('pop_%s.id_part', __FUNCTION__), sprintf('po_%s.id_purchase_order', __FUNCTION__), true);

        $this->db
        ->from(sprintf('tr_h3_md_purchase_order_parts as pop_%s', __FUNCTION__))
        ->join(sprintf('tr_h3_md_purchase_order as po_%s', __FUNCTION__), sprintf('(po_%s.id = pop_%s.id_purchase_order_int)', __FUNCTION__, __FUNCTION__))
        ->group_start()
        ->where(sprintf('po_%s.jenis_po', __FUNCTION__), 'REG')
        ->or_where(sprintf('po_%s.jenis_po', __FUNCTION__), 'FIX')
        ->group_end()
        ->group_start()
        ->where(sprintf('po_%s.status', __FUNCTION__), 'Approved')
        ->or_where(sprintf('po_%s.status', __FUNCTION__), 'Closed')
        ->group_end()
        ;

        $this->db->select(sprintf("( SUM(pop_%s.qty_order) - IFNULL((%s), 0) ) as qty", __FUNCTION__, 0), false);

        // $this->db->select('po_bo_ahm.id_purchase_order');
        // $this->db->select('po_bo_ahm.tanggal_po');
        // $this->db->select('po_bo_ahm.bulan');
        // $this->db->select('po_bo_ahm.tahun');
        // $this->db->select('pop_bo_ahm.id_part');
        // $this->db->select('pop_bo_ahm.qty_order');
        // $this->db->select('po_bo_ahm.status');

        $this->db->where(sprintf("SUBSTRING(po_%s.bulan, 6, 2) = '%s'", __FUNCTION__, $bulan_berjalan->format('m')), null, false);
        $this->db->where(sprintf("SUBSTRING(po_%s.tahun, 1, 4) = '%s'", __FUNCTION__, $bulan_berjalan->format('Y')), null, false);
        $this->db->where(sprintf('pop_%s.id_part_int', __FUNCTION__), $id_part_int, !$sql);

        if($sql){
            return $this->db->get_compiled_select();
        }else{
            $data = $this->db->get()->row_array();

            $qty_bo = $data != null ? $data['qty'] - $qty_ps : 0;
            if($qty_bo < 0){
                return 0;
            }else{
                return $qty_bo;
            }
        }
    }

    public function qty_bo_dealer($id_part, $jenis_po, $bulan_berjalan, $pesan_untuk_bulan, $sql = false){
        $bulan_berjalan_sub_query = $bulan_berjalan->copy();

        $this->db
        ->select('SUM(dop_bo_dealer.qty_supply) as qty_supply')
        ->from('tr_h3_md_do_sales_order_parts as dop_bo_dealer')
        ->join('tr_h3_md_do_sales_order as do_bo_dealer', '(do_bo_dealer.id_do_sales_order = dop_bo_dealer.id_do_sales_order)')
        ->where('do_bo_dealer.sudah_create_faktur', 1)
        ->where('dop_bo_dealer.id_part = sop_bo_dealer.id_part')
        ->where('do_bo_dealer.id_sales_order = so_bo_dealer.id_sales_order');
        if($jenis_po == 'FIX'){
            $bulan_berjalan_awal = $bulan_berjalan_sub_query->copy()->startOfMonth();
            $bulan_berjalan_akhir = $bulan_berjalan_sub_query->copy()->endOfMonth();
            $this->db->where("do_bo_dealer.tanggal BETWEEN '{$bulan_berjalan_awal->format('Y-m-d')}' AND '{$bulan_berjalan_akhir->format('Y-m-d')}'", null, false);
        }else if($jenis_po == 'REG'){
            $bulan_berjalan_sub_query->subMonths(1);
            $bulan_berjalan_awal = $bulan_berjalan_sub_query->copy()->startOfMonth();
            $bulan_berjalan_akhir = $bulan_berjalan_sub_query->copy()->endOfMonth();
            $this->db->where("do_bo_dealer.tanggal BETWEEN '{$bulan_berjalan_awal->format('Y-m-d')}' AND '{$bulan_berjalan_akhir->format('Y-m-d')}'", null, false);
        }
        $qty_do = $this->db->get_compiled_select();

        $this->db
        ->select("IFNULL(SUM(sop_bo_dealer.qty_order - IFNULL(({$qty_do}), 0)), 0) as qty")
        // ->select('so_bo_dealer.id_sales_order')
        // ->select('so_bo_dealer.tanggal_order')
        // ->select('sop_bo_dealer.id_part')
        // ->select('sop_bo_dealer.qty_order')
        // ->select("IFNULL(({$qty_do}), 0) as qty")
        ->from('tr_h3_md_sales_order as so_bo_dealer')
        ->join('tr_h3_md_sales_order_parts as sop_bo_dealer', 'sop_bo_dealer.id_sales_order = so_bo_dealer.id_sales_order')
        ->where('so_bo_dealer.status !=', 'Canceled');

        if($jenis_po == 'FIX'){
            $bulan_berjalan_awal = $bulan_berjalan->copy()->startOfMonth();
            $bulan_berjalan_akhir = $bulan_berjalan->copy()->endOfMonth();
            // $this->db->where('so_bo_dealer.po_type', 'FIX');
            $this->db->where("so_bo_dealer.tanggal_order BETWEEN '{$bulan_berjalan_awal->format('Y-m-d')}' AND '{$bulan_berjalan_akhir->format('Y-m-d')}'", null, false);
        }else if($jenis_po == 'REG'){
            $bulan_berjalan->subMonths(1);
            $bulan_berjalan_awal = $bulan_berjalan->copy()->startOfMonth();
            $bulan_berjalan_akhir = $bulan_berjalan->copy()->endOfMonth();
            // $this->db->where('so_bo_dealer.po_type', 'REG');
            $this->db->where("so_bo_dealer.tanggal_order BETWEEN '{$bulan_berjalan_awal->format('Y-m-d')}' AND '{$bulan_berjalan_akhir->format('Y-m-d')}'", null, false);
        }

        if($sql){
            $this->db->where("sop_bo_dealer.id_part = {$id_part}");

            return $this->db->get_compiled_select();
        }else{
            $this->db->where('sop_bo_dealer.id_part', $id_part);

            $data = $this->db->get()->row_array();
            return $data != null ? $data['qty'] : 0;
        }
    }

    public function update_harga($id){
        $data = $this->db
        ->select('pop.id_purchase_order_int')
        ->select('pop.id_purchase_order')
        ->select('pop.id_part')
        ->select('pop.harga')
        ->select('p.harga_md_dealer as harga_terakhir')
        ->from(sprintf('%s as pop', $this->table))
        ->join('ms_part as p', 'p.id_part_int = pop.id_part_int')
        ->where('pop.id', $id)
        ->get()->row_array();

        if($data == null) return;

        $this->db
        ->set('pop.harga', $data['harga_terakhir'])
        ->where('pop.id', $id)
        ->update(sprintf('%s as pop', $this->table));

        log_message('debug', sprintf('Update harga PO MD %s untuk kode part %s [%s] payload %s', $data['id_purchase_order'], $data['id_part'], $id, print_r($data, true)));

        $this->po_md->update_total_amount($data['id_purchase_order_int']);
        
    }
}
