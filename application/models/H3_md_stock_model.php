<?php

class H3_md_stock_model extends Honda_Model
{

    protected $table = 'tr_stok_part';

    public function add_stock($id_part, $id_lokasi_rak, $stock){
        $data_stock = $this->db
		->from('tr_stok_part as s')
		->where('s.id_part', $id_part)
		->where('s.id_lokasi_rak', $id_lokasi_rak)
		->limit(1)
		->get()->row();

        $data_part = $this->db
        ->select('p.id_part_int')
        ->from('ms_part as p')
        ->where('p.id_part', $id_part)
        ->get()->row_array();

        if($data_part == null){
            throw new Exception(sprintf('Kode part %s tidak ditemukan', $id_part));
        }

        if($data_stock != null){
			$this->db->set('qty', "qty + {$stock}", FALSE)
			->where('id_part', $id_part)
			->where('id_lokasi_rak', $id_lokasi_rak)
			->update($this->table);

            log_message('debug', sprintf('Menambahkan stock untuk kode part %s pada lokasi rak [%s] dengan kuantitas %s', $id_part, $id_lokasi_rak, $stock));
		}else{
			$this->db->insert($this->table, [
				'qty' => $stock,
				'id_part' => $id_part,
				'id_part_int' => $data_part['id_part_int'],
				'id_lokasi_rak' => $id_lokasi_rak
			]);

            log_message('debug', sprintf('Membuat stock untuk kode part %s pada lokasi rak [%s] dengan kuantitas %s', $id_part, $id_lokasi_rak, $stock));
		}
    }

    public function qty_on_hand($id_part, $lokasi = null, $sql = false)
    {
        $this->db
            ->from('tr_stok_part as sp_qty_onhand');

        if ($sql) {
            $this->db->where("sp_qty_onhand.id_part = {$id_part}");
        } else {
            $this->db->where('sp_qty_onhand.id_part', $id_part);
        }

        if ($lokasi != null) {
            if ($sql) {
                $this->db->where("sp_qty_onhand.id_lokasi_rak = {$lokasi}");
            } else {
                $this->db->where('sp_qty_onhand.id_lokasi_rak', $lokasi);
            }
            $this->db->select('sp_qty_onhand.qty as  qty_on_hand');
        } else {
            $this->db->group_by('sp_qty_onhand.id_part');
            $this->db->select('sum(sp_qty_onhand.qty) as  qty_on_hand');
        }

        if ($sql) {
            return $this->db->get_compiled_select();
        }

        $data = $this->db->get()->row();
        return $data != null ? $data->qty_on_hand : 0;
    }

    public function qty_avs($id_part, $exclude_po = [], $sql = false, $hotline = false)
    {

        if ($sql) {
            $query = "({$this->qty_on_hand($id_part, null,$sql)}) - ( IFNULL(({$this->qty_booking($id_part,$exclude_po,$sql)}), 0) + IFNULL(({$this->qty_claim($id_part,$sql)}), 0)";
            if(!$hotline){
                $query .= " + IFNULL(({$this->qty_keep_hotline($id_part,$sql)}), 0)";
            }
            $query .= " )";
            return "{$query}";
        } else {
            $kuantitas_pemotongan_on_hand = $this->qty_booking($id_part, $exclude_po, $sql) + $this->qty_claim($id_part, $sql);

            if(!$hotline){
                $kuantitas_pemotongan_on_hand += $this->qty_keep_hotline($id_part, $sql);
            }

            $qty_avs = $this->qty_on_hand($id_part, $sql) - ($kuantitas_pemotongan_on_hand);
            return $qty_avs < 0 ? 0 : $qty_avs;
        }
    }

    public function qty_keep_stock($id_part, $sql = false)
    {
        $qty_onhand = $this->qty_on_hand($id_part, null, $sql);
        $keep_stock_default = $this->qty_keep_hotline('p_sq_keep_stock.id_part', true);

        $this->db
            ->from('ms_part as p_sq_keep_stock')
            ->join('ms_kelompok_part as kp_sq_keep_stock', 'kp_sq_keep_stock.id_kelompok_part = p_sq_keep_stock.kelompok_part');

        if ($sql) {
            $this->db->where("p_sq_keep_stock.id_part = {$id_part}", null, false);
            $this->db->select("
                FLOOR(
                    (
                        IFNULL(({$qty_onhand}), 0) * (
                            (kp_sq_keep_stock.keep_stock_dealer + kp_sq_keep_stock.keep_stock_toko + kp_sq_keep_stock.keep_stock_hotline) / 100
                        )
                    )
                    + IFNULL(({$keep_stock_default}), 0)
                )
            ");

            return $this->db->get_compiled_select();
        } else {

            $this->db
                ->select('kp_sq_keep_stock.keep_stock_toko')
                ->select('kp_sq_keep_stock.keep_stock_dealer')
                ->select('kp_sq_keep_stock.keep_stock_hotline')
                ->select("IFNULL(({$qty_onhand}),0) as qty_onhand")
                ->select("IFNULL(({$keep_stock_default}),0) as keep_stock_default")
                ->where('p_sq_keep_stock.id_part', $id_part);

            $data = $this->db->get()->row_array();
            return floor(
                ($data['qty_onhand'] *
                    (($data['keep_stock_dealer'] + $data['keep_stock_toko'] + $data['keep_stock_hotline']) / 100))
                    + $data['keep_stock_default']
            );
        }
    }

    public function qty_keep_hotline($id_part, $sql = false)
    {
        $this->db
            ->select('kpi_sq_keep_stock_default.qty_keep_stock')
            ->from('ms_kelompok_part_item as kpi_sq_keep_stock_default');

        $this->db->group_start();
        $this->db->or_where('kpi_sq_keep_stock_default.id_part', $id_part, !$sql);
        $this->db->group_end();

        if ($sql) {
            return $this->db->get_compiled_select();
        } 

        $data = $this->db->get()->row_array();
        return $data != null ? $data['qty_keep_stock'] : 0;
    }

    public function qty_intransit($id_part, $sql = false)
    {
        $qty_sudah_diterima = $this->qty_diterima('psp.id_part', null, null, true);

        $qty_intransit = $this->db
            ->select("
        IFNULL( 
            SUM(psp.packing_sheet_quantity), 0
        ) -
        IFNULL(
            ({$qty_sudah_diterima}), 0
        ) as qty_intransit", false)
            ->from('tr_h3_md_ps_parts as psp')
            ->join('tr_h3_md_psl_items as psl', 'psl.packing_sheet_number = psp.packing_sheet_number')
            ->join('tr_h3_md_penerimaan_barang_items as pbi', '(pbi.id_part = psp.id_part and pbi.nomor_karton = psp.no_doos and pbi.packing_sheet_number = psp.packing_sheet_number)', 'left');

        if ($sql) {
            $this->db->where("psp.id_part = {$id_part}");

            return $this->db->get_compiled_select();
        } else {
            $this->db->where('psp.id_part', $id_part);
        }

        $qty_intransit = $this->db->get()->row();
        return $qty_intransit != null ? $qty_intransit->qty_intransit : 0;
    }

    public function qty_ps($id_part, $no_po = null, $sql = false)
    {
        $qty_ps = $this->db
            ->select("
        IFNULL( 
            SUM(psp.packing_sheet_quantity), 0
        ) as qty_ps", false)
            ->from('tr_h3_md_ps_parts as psp');

        if ($sql) {
            $this->db->where("psp.id_part = {$id_part}");
            $this->db->where("psp.no_po = {$no_po}");

            return $this->db->get_compiled_select();
        } else {
            $this->db->where('psp.id_part', $id_part);
            $this->db->where('psp.no_po', $no_po);
        }

        $qty_ps = $this->db->get()->row();
        return $qty_ps != null ? $qty_ps->qty_ps : 0;
    }

    public function qty_claim($id_part, $sql = false)
    {
        $qty_claim = $this->db
            ->select("IFNULL( SUM(cmi.qty_part_diclaim), 0) as qty_claim", false)
            // ->select('cmi.id_claim')
            // ->select('cmi.no_doos')
            // ->select('cmi.no_po')
            // ->select('cmi.qty_part_diclaim')
            // ->select("IFNULL(({$qty_terima_claim}), 0) as qty_terima_claim", false)
            ->from('tr_h3_md_claim_main_dealer_ke_ahm_item as cmi')
            ->join('tr_h3_md_claim_main_dealer_ke_ahm as cm', 'cm.id_claim = cmi.id_claim')
            ->join('ms_kategori_claim_c3 as kc', '(kc.id = cmi.id_kode_claim and kc.claim_potong_avs = 1)')
            ->where('cm.status !=', 'Canceled')
            ->where('cmi.sudah_proses_retur_pembelian_claim', 0);

        if ($sql) {
            $this->db->where("cmi.id_part = {$id_part}");
            return $this->db->get_compiled_select();
        } else {
            $this->db->where('cmi.id_part', $id_part);
        }

        $qty_claim = $this->db->get()->row();
        return $qty_claim != null ? $qty_claim->qty_claim : 0;
    }

    public function qty_diterima($id_part, $packing_sheet_number = null, $nomor_karton = null, $sql = false)
    {
        $this->db
            ->select('ifnull(
            sum(psp_sq_qty_diterima.packing_sheet_quantity),
            0
        ) as qty_diterima')
            ->from('tr_h3_md_penerimaan_barang_items as pbi_sq_qty_diterima')
            ->join('tr_h3_md_ps_parts as psp_sq_qty_diterima', '(psp_sq_qty_diterima.id_part = pbi_sq_qty_diterima.id_part and psp_sq_qty_diterima.packing_sheet_number = pbi_sq_qty_diterima.packing_sheet_number and psp_sq_qty_diterima.no_doos = pbi_sq_qty_diterima.nomor_karton)')
            ->join('tr_h3_md_psl_items as psl', 'psl.packing_sheet_number = psp_sq_qty_diterima.packing_sheet_number')
            ->where('pbi_sq_qty_diterima.tersimpan', 1);

        if ($sql) {
            $this->db->where("pbi_sq_qty_diterima.id_part = {$id_part}");
            if ($packing_sheet_number != null) {
                $this->db->where("pbi_sq_qty_diterima.packing_sheet_number = {$packing_sheet_number}");
            }
            if ($nomor_karton != null) {
                $this->db->where("pbi_sq_qty_diterima.nomor_karton = {$nomor_karton}");
            }

            return $this->db->get_compiled_select();
        } else {
            $this->db->where('pbi_sq_qty_diterima.id_part', $id_part);
            if ($packing_sheet_number != null) {
                $this->db->where('pbi_sq_qty_diterima.packing_sheet_number', $packing_sheet_number);
            }
            if ($nomor_karton != null) {
                $this->db->where('pbi_sq_qty_diterima.nomor_karton', $nomor_karton);
            }
        }

        $qty_diterima = $this->db->get()->row();
        return $qty_diterima != null ? $qty_diterima->qty_diterima : 0;
    }

    public function qty_booking($id_part, $exclude_po = [], $sql = false)
    {
        if ($sql) {
            // return "({$this->qty_booking_delivery_order($id_part, true)}) + ({$this->qty_booking_purchase_urgent_hotline($id_part, true)})";
            return "({$this->qty_booking_delivery_order($id_part, true)}) + ({$this->qty_booking_by_po_dealer($id_part,$exclude_po, true)})";
        } else {
            // return $this->qty_booking_delivery_order($id_part) + $this->qty_booking_purchase_urgent_hotline($id_part);
            return $this->qty_booking_delivery_order($id_part) + $this->qty_booking_by_po_dealer($id_part, $exclude_po);
        }
    }

    public function qty_booking_by_po_dealer($id_part, $exclude_po = [], $sql = false)
    {
        $this->db
            ->select('
        IFNULL(
            SUM(IFNULL(ppdd_qty_booking_by_po_dealer.qty_so, 0) + IFNULL(ppdd_qty_booking_by_po_dealer.qty_pemenuhan, 0)),
            0
        ) as kuantitas', false)
            ->from('tr_h3_md_pemenuhan_po_dari_dealer as ppdd_qty_booking_by_po_dealer')
            ->join('tr_h3_dealer_purchase_order as po_qty_booking_by_po_dealer', 'po_qty_booking_by_po_dealer.po_id = ppdd_qty_booking_by_po_dealer.po_id');

        if (count($exclude_po) > 0) {
            $this->db->where_not_in('ppdd_qty_booking_by_po_dealer.po_id', $exclude_po);
        }

        if ($sql) {
            $this->db->where("ppdd_qty_booking_by_po_dealer.id_part = {$id_part}", null, false);

            return $this->db->get_compiled_select();
        } else {
            $this->db->where('ppdd_qty_booking_by_po_dealer.id_part', $id_part);

            $data = $this->db->get()->row_array();

            return $data['kuantitas'];
        }
    }

    public function qty_booking_delivery_order($id_part, $sql = false)
    {
        $delivery_order_ada_faktur = $this->db
            ->select('do.id_do_sales_order')
            ->from('tr_h3_md_packing_sheet as ps')
            ->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
            ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
            ->get_compiled_select();

        $this->db
            ->select('ifnull(sum(dsop.qty_supply), 0) as qty')
            ->from('tr_h3_md_do_sales_order_parts as dsop')
            ->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = dsop.id_do_sales_order')
            // ->where("dso.id_do_sales_order not in ({$delivery_order_ada_faktur})")
            // ->where('dso.status !=', 'Rejected')
            // ->group_by('dsop.id_part')
            ->where_in('dso.status', [
                'On Process', 'Approved', 'Picking List', 'Proses Scan', 'Closed Scan'
            ]);

        if ($sql) {
            $this->db->where("dsop.id_part = {$id_part}");
            return $this->db->get_compiled_select();
        } else {
            $this->db->where('dsop.id_part', $id_part);
            $delivery_order = $this->db->get()->row();

            return $delivery_order != null ? $delivery_order->qty : 0;
        }
    }

    public function qty_booking_purchase_urgent_hotline($id_part, $sql = false)
    {
        $this->db
            ->select('sum(pop.kuantitas) as qty')
            ->from('tr_h3_dealer_purchase_order_parts as pop')
            ->join('tr_h3_dealer_purchase_order as po', 'pop.po_id = po.po_id')
            ->group_start()
            ->where('po.po_type', 'URG')
            ->or_where('po.po_type', 'HLO')
            ->group_end()
            ->group_by('pop.id_part');


        if ($sql) {
            $this->db->where("pop.id_part = '{$id_part}'");
            return $this->db->get_compiled_select();
        } else {
            $this->db->where('pop.id_part', $id_part);
            $data = $this->db->get()->row();

            return $data != null ? $data->qty : 0;
        }
    }

    public function qty_actual_dealer($id_part, $id_dealer, $sql = false)
    {
        $this->db
            ->select('SUM(ds.stock) as qty')
            ->from('ms_h3_dealer_stock as ds');

        if ($sql) {
            $this->db->where("ds.id_part = {$id_part}", null, false);
            $this->db->where("ds.id_dealer = {$id_dealer}", null, false);

            return $this->db->get_compiled_select();
        } else {
            $this->db->where("ds.id_part", $id_part);
            $this->db->where("ds.id_dealer", $id_dealer);
            $data = $this->db->get()->row();

            return $data != null ? $data->qty : 0;
        }
    }
}
