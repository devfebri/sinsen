<?php

class H3_md_stock_int_model extends Honda_Model
{

    protected $table = 'tr_stok_part';

    public function qty_on_hand($id_part_int, $lokasi = null, $sql = false)
    {
        $this->db
        ->from(sprintf('tr_stok_part as sp_%s', __FUNCTION__));

        $this->db->where(sprintf('sp_%s.id_part_int', __FUNCTION__), $id_part_int, !$sql);

        if ($lokasi != null) {
            $this->db->where(sprintf('sp_%s.id_lokasi_rak', __FUNCTION__), $lokasi, !$sql);
            $this->db->select(sprintf('sp_%s.qty as qty_on_hand', __FUNCTION__));
        } else {
            $this->db->group_by(sprintf('sp_%s.id_part_int', __FUNCTION__));
            $this->db->select(sprintf('SUM(sp_%s.qty) as qty_on_hand', __FUNCTION__));
        }

        if ($sql) {
            return $this->db->get_compiled_select();
        }

        $data = $this->db->get()->row();
        return $data != null ? $data->qty_on_hand : 0;
    }

    public function qty_avs($id_part_int, $exclude_po = [], $sql = false, $hotline = false, $exclude_claim_main_dealer = [])
    {

        if ($sql) {
            $query = "({$this->qty_on_hand($id_part_int, null,$sql)}) - ( IFNULL(({$this->qty_booking($id_part_int,$exclude_po,$sql)}), 0) + IFNULL(({$this->qty_claim($id_part_int, $exclude_claim_main_dealer, $sql)}), 0)";
            if(!$hotline){
                $query .= " + IFNULL(({$this->qty_keep_stock_hotline($id_part_int,$sql)}), 0)";
            }
            $query .= ")";
            return $query;
        } else {
            $kuantitas_pemotongan_on_hand = $this->qty_booking($id_part_int, $exclude_po, $sql) + $this->qty_claim($id_part_int, $exclude_claim_main_dealer, $sql);
            if(!$hotline){
                $kuantitas_pemotongan_on_hand += $this->qty_keep_stock_hotline($id_part_int, $sql);
            }
            $qty_avs = $this->qty_on_hand($id_part_int, $sql) - ($kuantitas_pemotongan_on_hand);
            return $qty_avs < 0 ? 0 : $qty_avs;
        }
    }

    public function qty_keep_stock($id_part_int, $sql = false)
    {
        $qty_onhand = $this->qty_on_hand($id_part_int, null, $sql);
        $keep_stock_default = $this->qty_keep_stock_hotline(sprintf('p_%s.id_part_int', __FUNCTION__), true);

        $this->db
            ->from(sprintf('ms_part as p_%s', __FUNCTION__))
            ->join(sprintf('ms_kelompok_part as kp_%s', __FUNCTION__), sprintf('kp_%s.id_kelompok_part = p_%s.kelompok_part', __FUNCTION__, __FUNCTION__));

        if ($sql) {
            $this->db->where(sprintf('p_%s.id_part_int = %s', __FUNCTION__, $id_part_int), null, false);
            $this->db->select(sprintf(
                'FLOOR(
                    (
                        IFNULL((%s), 0) * (
                            (kp_%s.keep_stock_dealer + kp_%s.keep_stock_toko + kp_%s.keep_stock_hotline) / 100
                        )
                    )
                    + IFNULL((%s), 0)
                )'
            , $qty_onhand, __FUNCTION__, __FUNCTION__, __FUNCTION__, $keep_stock_default));

            return $this->db->get_compiled_select();
        } else {

            $this->db
                ->select(sprintf('kp_%s.keep_stock_toko', __FUNCTION__))
                ->select(sprintf('kp_%s.keep_stock_dealer', __FUNCTION__))
                ->select(sprintf('kp_%s.keep_stock_hotline', __FUNCTION__))
                ->select(sprintf('IFNULL((%s),0) as qty_onhand', $qty_onhand))
                ->select(sprintf('IFNULL((%s),0) as keep_stock_default', $keep_stock_default))
                ->where(sprintf('p_%s.id_part_int', __FUNCTION__), $id_part_int);

            $data = $this->db->get()->row_array();
            return floor(
                ($data['qty_onhand'] *
                    (($data['keep_stock_dealer'] + $data['keep_stock_toko'] + $data['keep_stock_hotline']) / 100))
                    + $data['keep_stock_default']
            );
        }
    }

    public function qty_keep_stock_hotline($id_part_int, $sql = false)
    {
        $this->db
        ->select(sprintf('kpi_%s.qty_keep_stock', __FUNCTION__))
        ->from(sprintf('ms_kelompok_part_item as kpi_%s', __FUNCTION__));

        $this->db->group_start();
        $this->db->where(sprintf('kpi_%s.id_part_int', __FUNCTION__), $id_part_int, $sql);
        $this->db->group_end();

        if($sql){
            return $this->db->get_compiled_select();
        }

        $data = $this->db->get()->row_array();
        return $data != null ? $data['qty_keep_stock'] : 0;
    }

    public function qty_intransit($id_part_int, $sql = false)
    {
        $qty_sudah_diterima = $this->qty_diterima(sprintf('psp_%s.id_part_int', __FUNCTION__), null, null, true);

        $qty_intransit = $this->db
        ->select(sprintf('IFNULL( SUM(psp_%s.packing_sheet_quantity), 0 ) - IFNULL( (%s), 0 ) as qty_intransit', __FUNCTION__, $qty_sudah_diterima), false)
        // ->select(sprintf('IFNULL( SUM(psp_%s.packing_sheet_quantity), 0 ) - IFNULL( (%s), 0 ) as qty_intransit', __FUNCTION__, $this->qty_diterima(sprintf('psp_%s.id_part_int', __FUNCTION__), null, null, true)), false)
        ->from(sprintf('tr_h3_md_ps_parts as psp_%s', __FUNCTION__))
        ->join(sprintf('tr_h3_md_psl_items as psl_%s', __FUNCTION__), sprintf('psl_%s.packing_sheet_number_int = psp_%s.packing_sheet_number_int', __FUNCTION__, __FUNCTION__))
        ->join(sprintf('tr_h3_md_penerimaan_barang_items as pbi_%s', __FUNCTION__), sprintf('(pbi_%s.id_part_int = psp_%s.id_part_int and pbi_%s.nomor_karton_int = psp_%s.no_doos_int and pbi_%s.packing_sheet_number_int = psp_%s.packing_sheet_number_int)', __FUNCTION__, __FUNCTION__, __FUNCTION__, __FUNCTION__, __FUNCTION__, __FUNCTION__), 'left');

        $this->db->where(sprintf('psp_%s.id_part_int', __FUNCTION__), $id_part_int, !$sql);

        if($sql){
            return $this->db->get_compiled_select();
        }

        $qty_intransit = $this->db->get()->row();
        return $qty_intransit != null ? $qty_intransit->qty_intransit : 0;
    }

    public function qty_ps($id_part_int, $no_po = null, $sql = false)
    {
        $qty_ps = $this->db
        ->select(sprintf('IFNULL( SUM(psp_%s.packing_sheet_quantity), 0 ) as qty_ps', __FUNCTION__), false)
        ->from(sprintf('tr_h3_md_ps_parts as psp_%s', __FUNCTION__));

     
        $this->db->where(sprintf('psp_%s.id_part_int', __FUNCTION__), $id_part_int, !$sql);
        if($no_po != null){
            $this->db->where(sprintf('psp_%s.no_po', __FUNCTION__), $no_po, !$sql);
        }

        if($sql){ 
            return $this->db->get_compiled_select();
         }

        $qty_ps = $this->db->get()->row();
        return $qty_ps != null ? $qty_ps->qty_ps : 0;
    }

    public function qty_claim($id_part_int, $exclude_claim_main_dealer = [], $sql = false)
    {
        $kuantitas_terima_claim_ganti_barang_dan_ditolak = $this->db
        ->select(sprintf('SUM(
            case
                when tcai_%s.barang_checklist = 1 then tcai_%s.ganti_barang
                when tcai_%s.ditolak_checklist = 1 then tcai_%s.ditolak
            end 
        )', __FUNCTION__, __FUNCTION__, __FUNCTION__, __FUNCTION__))
        ->from(sprintf('tr_h3_md_terima_claim_ahm_item as tcai_%s', __FUNCTION__))
        ->where(sprintf('tcai_%s.id_claim_int = cmi_%s.id_claim_int', __FUNCTION__, __FUNCTION__), null, false)
        ->where(sprintf('tcai_%s.id_part_int = cmi_%s.id_part_int', __FUNCTION__, __FUNCTION__), null, false)
        ->where(sprintf('tcai_%s.no_doos = cmi_%s.no_doos', __FUNCTION__, __FUNCTION__), null, false)
        ->where(sprintf('tcai_%s.no_po = cmi_%s.no_po', __FUNCTION__, __FUNCTION__), null, false)
        ->where(sprintf('tcai_%s.id_kode_claim = cmi_%s.id_kode_claim', __FUNCTION__, __FUNCTION__), null, false)
        ->group_start()
        ->or_where(sprintf('tcai_%s.barang_checklist', __FUNCTION__), 1)
        ->or_where(sprintf('tcai_%s.ditolak_checklist', __FUNCTION__), 1)
        ->group_end()
        ->get_compiled_select();

        $qty_claim = $this->db
            ->select(sprintf('( IFNULL( SUM(cmi_%s.qty_part_diclaim), 0) - IFNULL((%s), 0) ) as qty_claim', __FUNCTION__, $kuantitas_terima_claim_ganti_barang_dan_ditolak), false)
            // ->select(sprintf('IFNULL((%s), 0)', $kuantitas_terima_claim_ganti_barang_dan_ditolak), false)
            // ->select(sprintf('cmi_%s.id_claim', __FUNCTION__))
            // ->select(sprintf('cmi_%s.no_doos', __FUNCTION__))
            // ->select(sprintf('cmi_%s.no_po', __FUNCTION__))
            // ->select(sprintf('cmi_%s.qty_part_diclaim', __FUNCTION__))
            // ->select("IFNULL(({$qty_terima_claim}), 0) as qty_terima_claim", false)
            ->from(sprintf('tr_h3_md_claim_main_dealer_ke_ahm_item as cmi_%s', __FUNCTION__))
            ->join(sprintf('tr_h3_md_claim_main_dealer_ke_ahm as cm_%s', __FUNCTION__), sprintf('cm_%s.id_claim = cmi_%s.id_claim', __FUNCTION__, __FUNCTION__))
            ->join(sprintf('ms_kategori_claim_c3 as kc_%s', __FUNCTION__), sprintf('(kc_%s.id = cmi_%s.id_kode_claim and kc_%s.claim_potong_avs = 1)', __FUNCTION__, __FUNCTION__, __FUNCTION__))
            ->where(sprintf('cm_%s.status !=', __FUNCTION__), 'Canceled')
            ->where(sprintf('cmi_%s.sudah_proses_retur_pembelian_claim', __FUNCTION__), 0);

        $this->db->where(sprintf('cmi_%s.id_part_int', __FUNCTION__), $id_part_int, !$sql);

        if(count($exclude_claim_main_dealer) > 0){
            $this->db->where_not_in(sprintf('cmi_%s.id_claim_int', __FUNCTION__), $exclude_claim_main_dealer);
        }

        if ($sql) {
            return $this->db->get_compiled_select();
        }

        $qty_claim = $this->db->get()->row();
        return $qty_claim != null ? $qty_claim->qty_claim : 0;
    }

    public function qty_diterima($id_part_int, $packing_sheet_number_int = null, $nomor_karton = null, $sql = false)
    {
        $this->db
            ->select(sprintf('IFNULL(SUM(psp_%s.packing_sheet_quantity), 0) as qty_diterima', __FUNCTION__))
            ->from(sprintf('tr_h3_md_penerimaan_barang_items as pbi_%s', __FUNCTION__))
            ->join(sprintf('tr_h3_md_ps_parts as psp_%s', __FUNCTION__), sprintf('(psp_%s.id_part_int = pbi_%s.id_part_int and psp_%s.packing_sheet_number_int = pbi_%s.packing_sheet_number_int and psp_%s.no_doos_int = pbi_%s.nomor_karton_int)', __FUNCTION__, __FUNCTION__, __FUNCTION__, __FUNCTION__, __FUNCTION__, __FUNCTION__))
            ->join(sprintf('tr_h3_md_psl_items as psl_%s', __FUNCTION__), sprintf('psl_%s.packing_sheet_number_int = psp_%s.packing_sheet_number_int', __FUNCTION__, __FUNCTION__))
            ->where(sprintf('pbi_%s.tersimpan', __FUNCTION__), 1);

        $this->db->where(sprintf('pbi_%s.id_part_int', __FUNCTION__), $id_part_int, !$sql);
        if ($packing_sheet_number_int != null) {
            $this->db->where(sprintf('pbi_%s.packing_sheet_number_int', __FUNCTION__), $packing_sheet_number_int, !$sql);
        }
        if ($nomor_karton != null) {
            $this->db->where(sprintf('pbi_%s.nomor_karton', __FUNCTION__), $nomor_karton, !$sql);
        }

        if($sql){
            return $this->db->get_compiled_select();
        }

        $qty_diterima = $this->db->get()->row();
        return $qty_diterima != null ? $qty_diterima->qty_diterima : 0;
    }

    public function qty_booking($id_part_int, $exclude_po = [], $sql = false)
    {
        if ($sql) {
            return "({$this->qty_booking_delivery_order($id_part_int, $sql)}) + ({$this->qty_booking_by_po_dealer($id_part_int,$exclude_po, $sql)})";
        } else {
            return $this->qty_booking_delivery_order($id_part_int) + $this->qty_booking_by_po_dealer($id_part_int, $exclude_po);
        }
    }

    public function qty_booking_by_po_dealer($id_part_int, $exclude_po = [], $sql = false)
    {
        $this->db
            ->select(sprintf('IFNULL( SUM(IFNULL(ppdd_%s.qty_so, 0) + IFNULL(ppdd_%s.qty_pemenuhan, 0)), 0 ) as kuantitas', __FUNCTION__, __FUNCTION__), false)
            ->from(sprintf('tr_h3_md_pemenuhan_po_dari_dealer as ppdd_%s', __FUNCTION__))
            ->join(sprintf('tr_h3_dealer_purchase_order as po_%s', __FUNCTION__), sprintf('po_%s.id = ppdd_%s.po_id_int', __FUNCTION__, __FUNCTION__));

        if (count($exclude_po) > 0) {
            $this->db->where_not_in(sprintf('ppdd_%s.po_id_int', __FUNCTION__), $exclude_po);
        }

        $this->db->where(sprintf('ppdd_%s.id_part_int', __FUNCTION__), $id_part_int, !$sql);

        if ($sql) {
            return $this->db->get_compiled_select();
        }

        $data = $this->db->get()->row_array();
        return $data['kuantitas'];
    }

    public function qty_booking_delivery_order($id_part_int, $sql = false)
    {
        $delivery_order_ada_faktur = $this->db
            ->select('do.id_do_sales_order')
            ->from('tr_h3_md_packing_sheet as ps')
            ->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
            ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
            ->get_compiled_select();

        $this->db
            ->select(sprintf('IFNULL(SUM(dsop_%s.qty_supply), 0) as qty', __FUNCTION__), false)
            ->from(sprintf('tr_h3_md_do_sales_order_parts as dsop_%s', __FUNCTION__))
            ->join(sprintf('tr_h3_md_do_sales_order as dso_%s', __FUNCTION__), sprintf('dso_%s.id = dsop_%s.id_do_sales_order_int', __FUNCTION__, __FUNCTION__))
            // ->where("dso.id_do_sales_order not in ({$delivery_order_ada_faktur})")
            // ->where('dso.status !=', 'Rejected')
            // ->group_by('dsop.id_part')
            ->where_in(sprintf('dso_%s.status', __FUNCTION__), [
                'On Process', 'Approved', 'Picking List', 'Proses Scan', 'Closed Scan'
            ]);
        
            $this->db->where(sprintf('dsop_%s.id_part_int', __FUNCTION__), $id_part_int, !$sql);

        if ($sql) {
            return $this->db->get_compiled_select();
        }

        $delivery_order = $this->db->get()->row();
        return $delivery_order != null ? $delivery_order->qty : 0;
    }

    public function qty_booking_purchase_urgent_hotline($id_part_int, $sql = false)
    {
        $this->db
            ->select(sprintf('SUM(pop_%s.kuantitas) as qty', __FUNCTION__))
            ->from(sprintf('tr_h3_dealer_purchase_order_parts as pop_%s', __FUNCTION__))
            ->join(sprintf('tr_h3_dealer_purchase_order as po_%s', __FUNCTION__), sprintf('pop_%s.po_id_int = po_%s.id', __FUNCTION__, __FUNCTION__))
            ->group_start()
            ->where(sprintf('po_%s.po_type', __FUNCTION__), 'URG')
            ->or_where(sprintf('po_%s.po_type', __FUNCTION__), 'HLO')
            ->group_end()
            ->group_by(sprintf('pop_%s.id_part_int', __FUNCTION__));

        $this->db->where(sprintf('pop_%s.id_part_int', __FUNCTION__), $id_part_int, !$sql);

        if ($sql) {
            return $this->db->get_compiled_select();
        }

        $data = $this->db->get()->row();
        return $data != null ? $data->qty : 0;
    }

    public function qty_actual_dealer($id_part_int, $id_dealer, $sql = false)
    {
        $this->db
        ->select(sprintf('SUM(ds_%s.stock) as qty', __FUNCTION__), false)
        ->from(sprintf('ms_h3_dealer_stock as ds_%s', __FUNCTION__));

        $this->db->where(sprintf('ds_%s.id_part_int', __FUNCTION__), $id_part_int, !$sql);
        $this->db->where(sprintf('ds_%s.id_dealer', __FUNCTION__), $id_dealer, !$sql);

        if ($sql) {
            return $this->db->get_compiled_select();
        }

        $data = $this->db->get()->row();
        return $data != null ? $data->qty : 0;
    }
}
