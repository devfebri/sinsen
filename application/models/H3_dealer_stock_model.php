<?php

class h3_dealer_stock_model extends Honda_Model
{
    protected $table = 'ms_h3_dealer_stock';

    public function qty_on_hand($id_dealer, $id_part, $id_gudang = null, $id_rak = null, $sql = false)
    {
        if($this->config->item('ahm_d_only')){
            $this->db
                ->select('IFNULL(SUM(ds_qty_on_hand.stock), 0) as stock')
                ->from('ms_h3_dealer_stock as ds_qty_on_hand')
                ->join('ms_part as p','ds_qty_on_hand.id_part  = p.id_part');
        }else{
            $this->db
                ->select('IFNULL(SUM(ds_qty_on_hand.stock), 0) as stock')
                ->from('ms_h3_dealer_stock as ds_qty_on_hand');
        }

        $this->db->where('ds_qty_on_hand.id_dealer', $id_dealer, !$sql);
        $this->db->where('ds_qty_on_hand.id_part', $id_part, !$sql);

        if ($id_gudang != null) {
            $this->db->where('ds_qty_on_hand.id_gudang', $id_gudang, !$sql);
        }

        if ($id_rak != null) {
            $this->db->where('ds_qty_on_hand.id_rak', $id_rak, !$sql);
        }

        if ($id_gudang == null and $id_rak == null) {
            $this->db->group_by('ds_qty_on_hand.id_part');
        }

        if ($sql) {
            return $this->db->get_compiled_select();
        } else {
            $data = $this->db->get()->row_array();
            return $data != null ? $data['stock'] : 0;
        }
    }

    public function qty_on_hand2($id_dealer, $id_part_int, $id_gudang = null, $id_rak = null, $sql = false)
    {
        $this->db
            ->select('IFNULL(SUM(ds_qty_on_hand.stock), 0) as stock')
            ->from('ms_h3_dealer_stock as ds_qty_on_hand');

        $this->db->where('ds_qty_on_hand.id_dealer', $id_dealer, !$sql);
        $this->db->where('ds_qty_on_hand.id_part_int', $id_part_int, !$sql);

        if ($id_gudang != null) {
            $this->db->where('ds_qty_on_hand.id_gudang', $id_gudang, !$sql);
        }

        if ($id_rak != null) {
            $this->db->where('ds_qty_on_hand.id_rak', $id_rak, !$sql);
        }

        if ($id_gudang == null and $id_rak == null) {
            $this->db->group_by('ds_qty_on_hand.id_part_int');
        }

        if ($sql) {
            return $this->db->get_compiled_select();
        } else {
            $data = $this->db->get()->row_array();
            return $data != null ? $data['stock'] : 0;
        }
    }

    public function qty_sales_order($id_dealer, $id_part, $id_gudang = null, $id_rak = null, $sql = false)
    {
        $this->db
            ->select('( IFNULL( SUM(sop_qty_sales_order.kuantitas - sop_qty_sales_order.kuantitas_return), 0) ) as kuantitas')
            ->from('tr_h3_dealer_sales_order as so_qty_sales_order')
            ->join('tr_h3_dealer_sales_order_parts as sop_qty_sales_order', 'so_qty_sales_order.id = sop_qty_sales_order.nomor_so_int')
            ->where('so_qty_sales_order.status !=', 'Closed')
            ->where('so_qty_sales_order.status !=', 'Canceled')
            ->where('so_qty_sales_order.id_inbound_form_for_parts_return', null);

        if ($sql) {
            $this->db->where("so_qty_sales_order.id_dealer = {$id_dealer}", null, false);
            $this->db->where("sop_qty_sales_order.id_part = {$id_part}", null, false);

            if ($id_gudang != null) {
                $this->db->where("sop_qty_sales_order.id_gudang = {$id_gudang}", null, false);
            }

            if ($id_rak != null) {
                $this->db->where("sop_qty_sales_order.id_rak = {$id_rak}", null, false);
            }

            return $this->db->get_compiled_select();
        } else {
            $this->db->where('so_qty_sales_order.id_dealer', $id_dealer);
            $this->db->where('sop_qty_sales_order.id_part', $id_part);

            if ($id_gudang != null) {
                $this->db->where('sop_qty_sales_order.id_gudang', $id_gudang);
            }

            if ($id_rak != null) {
                $this->db->where('sop_qty_sales_order.id_rak', $id_rak);
            }

            $data = $this->db->get()->row_array();
            return $data != null ? $data['kuantitas'] : 0;
        }
    }

    
    public function qty_sales_order_v2($id_dealer, $id_part, $id_gudang = null, $id_rak = null, $sql = false, $id_part_int)
    {
        $this->db
            ->select('( IFNULL( SUM(sop_qty_sales_order.kuantitas - sop_qty_sales_order.kuantitas_return), 0) ) as kuantitas')
            ->from('tr_h3_dealer_sales_order as so_qty_sales_order')
            ->join('tr_h3_dealer_sales_order_parts as sop_qty_sales_order', 'so_qty_sales_order.id = sop_qty_sales_order.nomor_so_int')
            ->where('so_qty_sales_order.status !=', 'Closed')
            ->where('so_qty_sales_order.status !=', 'Canceled')
            ->where('so_qty_sales_order.id_inbound_form_for_parts_return', null);

        if ($sql) {
            $this->db->where("so_qty_sales_order.id_dealer = {$id_dealer}", null, false);
            $this->db->where("sop_qty_sales_order.id_part_int = {$id_part_int}", null, false);

            if ($id_gudang != null) {
                $this->db->where("sop_qty_sales_order.id_gudang = {$id_gudang}", null, false);
            }

            if ($id_rak != null) {
                $this->db->where("sop_qty_sales_order.id_rak = {$id_rak}", null, false);
            }

            return $this->db->get_compiled_select();
        } else {
            $this->db->where('so_qty_sales_order.id_dealer', $id_dealer);
            $this->db->where('sop_qty_sales_order.id_part_int', $id_part_int);

            if ($id_gudang != null) {
                $this->db->where('sop_qty_sales_order.id_gudang', $id_gudang);
            }

            if ($id_rak != null) {
                $this->db->where('sop_qty_sales_order.id_rak', $id_rak);
            }

            $data = $this->db->get()->row_array();
            return $data != null ? $data['kuantitas'] : 0;
        }
    }

    public function qty_order_fulfillment($id_dealer, $id_part, $id_gudang = null, $id_rak = null, $sql = false)
    {
        $this->db
            ->select('IFNULL( SUM(offp_qty_order_fulfillment.kuantitas), 0) as kuantitas')
            ->from('tr_h3_dealer_outbound_form_for_fulfillment as off_qty_order_fulfillment')
            ->join('tr_h3_dealer_outbound_form_for_fulfillment_parts as offp_qty_order_fulfillment', 'off_qty_order_fulfillment.id_outbound_form_for_fulfillment = offp_qty_order_fulfillment.id_outbound_form_for_fulfillment')
            ->where('off_qty_order_fulfillment.status', 'Open');

        if ($sql) {
            $this->db->where("off_qty_order_fulfillment.id_dealer = {$id_dealer}", null, false);
            $this->db->where("offp_qty_order_fulfillment.id_part = {$id_part}", null, false);

            if ($id_gudang != null) {
                $this->db->where("offp_qty_order_fulfillment.id_gudang = {$id_gudang}", null, false);
            }

            if ($id_rak != null) {
                $this->db->where("offp_qty_order_fulfillment.id_rak = {$id_rak}", null, false);
            }

            return $this->db->get_compiled_select();
        } else {
            $this->db->where('off_qty_order_fulfillment.id_dealer', $id_dealer);
            $this->db->where('offp_qty_order_fulfillment.id_part', $id_part);

            if ($id_gudang != null) {
                $this->db->where('offp_qty_order_fulfillment.id_gudang', $id_gudang);
            }

            if ($id_rak != null) {
                $this->db->where('offp_qty_order_fulfillment.id_rak', $id_rak);
            }

            $data = $this->db->get()->row_array();
            return $data != null ? $data['kuantitas'] : 0;
        }
    }

    public function qty_book_hotline($id_dealer, $id_part, $sql = false)
    {
        $hotline_sudah_dibuatkan_sales_order = $this->db
            ->select('SUM(sop.kuantitas) as kuantitas')
            ->from('tr_h3_dealer_sales_order as so')
            ->join('tr_h3_dealer_sales_order_parts as sop', 'sop.nomor_so = so.nomor_so')
            ->where('so.booking_id_reference = po_qty_book_hotline.id_booking')
            ->where('so.id_dealer', $id_dealer)
            ->where('sop.id_part = of_qty_book_hotline.id_part')
            ->where('so.status !=', 'Canceled')
            ->get_compiled_select();

        $this->db
            ->select("
            IFNULL( 
                SUM(
                    GREATEST(0, of_qty_book_hotline.qty_fulfillment  - IFNULL( ({$hotline_sudah_dibuatkan_sales_order}), 0) )
                )
            , 0 ) 
        as kuantitas", false)
            // ->select('po_qty_book_hotline.po_id')
            // ->select("(
            //     IFNULL( 
            //         of_qty_book_hotline.qty_fulfillment
            //     , 0 ) 
            // ) as qty_fulfillment", false)
            // ->select("(
            //     IFNULL( 
            //         IFNULL( ({$hotline_sudah_dibuatkan_sales_order}), 0)
            //     , 0 ) 
            // ) as hotline_sudah_dibuatkan_sales_order", false)
            // ->select("(
            //     of_qty_book_hotline.qty_fulfillment
            //     -
            //     IFNULL( 
            //         IFNULL( ({$hotline_sudah_dibuatkan_sales_order}), 0)
            //     , 0 ) 
            // ) as kuantitas_sisa", false)
            ->from('tr_h3_dealer_purchase_order as po_qty_book_hotline')
            ->join('tr_h3_dealer_order_fulfillment as of_qty_book_hotline', 'of_qty_book_hotline.po_id = po_qty_book_hotline.po_id')
            ->join('tr_h3_dealer_request_document as rd_qty_book_hotline', 'rd_qty_book_hotline.id_booking = po_qty_book_hotline.id_booking')
            ->join('tr_h2_wo_dealer as wo_qty_book_hotline', 'wo_qty_book_hotline.id_sa_form = rd_qty_book_hotline.id_sa_form', 'left')
            ->where('po_qty_book_hotline.po_rekap', 0)
            ->where('wo_qty_book_hotline.id_work_order_int IS NULL', null, false)
            ->where('po_qty_book_hotline.po_type', 'HLO')
            ->where('of_qty_book_hotline.qty_fulfillment >', 0)
            ->having('kuantitas > 0');

        if ($sql) {
            $this->db->where("po_qty_book_hotline.id_dealer = {$id_dealer}", null, false);
            $this->db->where("of_qty_book_hotline.id_part = {$id_part}", null, false);

            return $this->db->get_compiled_select();
        } else {
            $this->db->where('po_qty_book_hotline.id_dealer', $id_dealer);
            $this->db->where('of_qty_book_hotline.id_part', $id_part);

            $data = $this->db->get()->row_array();
            return $data != null ? $data['kuantitas'] : 0;
        }
    }

    public function qty_sa_form($id_dealer, $id_part, $id_gudang = null, $id_rak = null, $sql = false)
    {
        $this->db
            ->select('SUM(sa_form_parts.qty) as qty')
            ->from('tr_h2_sa_form as sa_form')
            ->join('tr_h2_sa_form_parts as sa_form_parts', 'sa_form_parts.id_sa_form = sa_form.id_sa_form')
            ->where('sa_form.status_form', 'open');

        if ($sql) {
            $this->db->where("sa_form.id_dealer = {$id_dealer}", null, false);
            $this->db->where("sa_form_parts.id_part = {$id_part}", null, false);

            if ($id_gudang != null) {
                $this->db->where("sa_form_parts.id_gudang = {$id_gudang}", null, false);
            }

            if ($id_rak != null) {
                $this->db->where("sa_form_parts.id_rak = {$id_rak}", null, false);
            }


            return $this->db->get_compiled_select();
        } else {
            $this->db->where('sa_form.id_dealer', $id_dealer);
            $this->db->where('sa_form_parts.id_part', $id_part);

            if ($id_gudang != null) {
                $this->db->where("sa_form_parts.id_gudang", $id_gudang);
            }

            if ($id_rak != null) {
                $this->db->where("sa_form_parts.id_rak", $id_rak);
            }

            $data = $this->db->get()->row_array();
            return $data != null ? $data['qty'] : 0;
        }
    }

    public function qty_wo($id_dealer, $id_part, $id_gudang = null, $id_rak = null, $sql = false)
    {
        $this->db
            ->select('SUM(wop.qty) as qty')
            ->from('tr_h2_wo_dealer_parts as wop')
            ->join('tr_h2_wo_dealer as wo', 'wo.id_work_order = wop.id_work_order')
            ->group_start()
            ->where('wo.status !=', 'cancel')
            ->where('wo.status !=', 'canceled')
            ->where('wo.status !=', 'closed')
            ->group_end()
            ->where('wop.nomor_so is null', null, false)
            ->where('wop.pekerjaan_batal', 0);

        if ($sql) {
            $this->db->where("wo.id_dealer = {$id_dealer}", null, false);
            $this->db->where("wop.id_part = {$id_part}", null, false);

            if ($id_gudang != null) {
                $this->db->where("wop.id_gudang = {$id_gudang}", null, false);
            }

            if ($id_rak != null) {
                $this->db->where("wop.id_rak = {$id_rak}", null, false);
            }


            return $this->db->get_compiled_select();
        } else {
            $this->db->where('wo.id_dealer', $id_dealer);
            $this->db->where('wop.id_part', $id_part);

            if ($id_gudang != null) {
                $this->db->where("wop.id_gudang", $id_gudang);
            }

            if ($id_rak != null) {
                $this->db->where("wop.id_rak", $id_rak);
            }

            $data = $this->db->get()->row_array();
            return $data != null ? $data['qty'] : 0;
        }
    }

    public function qty_book($id_dealer, $id_part, $id_gudang = null, $id_rak = null, $sql = false)
    {
        if ($sql) {
            $qty_sales_order = $this->qty_sales_order($id_dealer, $id_part, $id_gudang, $id_rak, $sql);
            $qty_order_fulfillment = $this->qty_order_fulfillment($id_dealer, $id_part, $id_gudang, $id_rak, $sql);
            $qty_book_hotline = $this->qty_book_hotline($id_dealer, $id_part, $sql);
            $qty_sa_form = $this->qty_sa_form($id_dealer, $id_part, $id_gudang, $id_rak, $sql);
            $qty_wo = $this->qty_wo($id_dealer, $id_part, $id_gudang, $id_rak, $sql);

            return "({$qty_sales_order}) + ({$qty_order_fulfillment}) + ({$qty_order_fulfillment}) + IFNULL(({$qty_sa_form}), 0) + IFNULL(({$qty_wo}), 0)";
        } else {
            $qty_sales_order = $this->qty_sales_order($id_dealer, $id_part, $id_gudang, $id_rak, $sql);
            $qty_order_fulfillment = $this->qty_order_fulfillment($id_dealer, $id_part, $id_gudang, $id_rak, $sql);
            $qty_book_hotline = $this->qty_book_hotline($id_dealer, $id_part, $id_gudang, $id_rak, $sql);
            $qty_sa_form = $this->qty_sa_form($id_dealer, $id_part, $id_gudang, $id_rak, $sql);
            $qty_wo = $this->qty_wo($id_dealer, $id_part, $id_gudang, $id_rak, $sql);

            return $qty_sales_order + $qty_order_fulfillment + $qty_book_hotline + $qty_sa_form + $qty_wo;
        }
    }

    public function qty_book_v2($id_dealer, $id_part, $id_gudang = null, $id_rak = null, $sql = false, $id_part_int)
    {
        if ($sql) {
            $qty_sales_order = $this->qty_sales_order_v2($id_dealer, $id_part, $id_gudang, $id_rak, $sql, $id_part_int);
            $qty_order_fulfillment = $this->qty_order_fulfillment($id_dealer, $id_part, $id_gudang, $id_rak, $sql);
            $qty_book_hotline = $this->qty_book_hotline($id_dealer, $id_part, $sql);
            $qty_sa_form = $this->qty_sa_form($id_dealer, $id_part, $id_gudang, $id_rak, $sql);
            $qty_wo = $this->qty_wo($id_dealer, $id_part, $id_gudang, $id_rak, $sql);

            return "({$qty_sales_order}) + ({$qty_order_fulfillment}) + ({$qty_order_fulfillment}) + IFNULL(({$qty_sa_form}), 0) + IFNULL(({$qty_wo}), 0)";
        } else {
            $qty_sales_order = $this->qty_sales_order_v2($id_dealer, $id_part, $id_gudang, $id_rak, $sql, $id_part_int);
            $qty_order_fulfillment = $this->qty_order_fulfillment($id_dealer, $id_part, $id_gudang, $id_rak, $sql);
            $qty_book_hotline = $this->qty_book_hotline($id_dealer, $id_part, $id_gudang, $id_rak, $sql);
            $qty_sa_form = $this->qty_sa_form($id_dealer, $id_part, $id_gudang, $id_rak, $sql);
            $qty_wo = $this->qty_wo($id_dealer, $id_part, $id_gudang, $id_rak, $sql);

            return $qty_sales_order + $qty_order_fulfillment + $qty_book_hotline + $qty_sa_form + $qty_wo;
        }
    }

    public function qty_avs($id_dealer, $id_part, $id_gudang = null, $id_rak = null, $sql = false)
    {
        if ($sql) {
            $qty_onhand = $this->qty_on_hand($id_dealer, $id_part, $id_gudang, $id_rak, $sql);
            $qty_book = $this->qty_book($id_dealer, $id_part, $id_gudang, $id_rak, $sql);

            return "({$qty_onhand}) - ({$qty_book})";
        } else {
            $qty_onhand = $this->qty_on_hand($id_dealer, $id_part, $id_gudang, $id_rak, $sql);
            $qty_book = $this->qty_book($id_dealer, $id_part, $id_gudang, $id_rak, $sql);

            return $qty_onhand - $qty_book;
        }
    }

    public function qty_avs_v2($id_dealer, $id_part, $id_gudang = null, $id_rak = null, $sql = false, $id_part_int)
    {
        if ($sql) {
            $qty_onhand = $this->qty_on_hand($id_dealer, $id_part, $id_gudang, $id_rak, $sql);
            $qty_book = $this->qty_book_v2($id_dealer, $id_part, $id_gudang, $id_rak, $sql,$id_part_int);

            return "({$qty_onhand}) - ({$qty_book})";
        } else {
            $qty_onhand = $this->qty_on_hand($id_dealer, $id_part, $id_gudang, $id_rak, $sql);
            $qty_book = $this->qty_book_v2($id_dealer, $id_part, $id_gudang, $id_rak, $sql, $id_part_int);

            return $qty_onhand - $qty_book;
        }
    }


    public function qty_intransit_md($id_dealer, $id_part, $sql = false)
    {
        // $this->db
        // ->select('IFNULL( SUM(pop_qty_intransit_md.kuantitas), 0) as kuantitas')
        // ->from('tr_h3_dealer_purchase_order_parts as pop_qty_intransit_md')
        // ->join('tr_h3_dealer_purchase_order as po_qty_intransit_md', 'po_qty_intransit_md.po_id = pop_qty_intransit_md.po_id')
        // ->join('tr_h3_md_sales_order as so_qty_intransit_md', 'so_qty_intransit_md.id_ref = po_qty_intransit_md.po_id')
        // ->join('tr_h3_md_do_sales_order as do_qty_intransit_md', 'do_qty_intransit_md.id_sales_order = so_qty_intransit_md.id_sales_order')
        // ->join('tr_h3_md_picking_list as pl_qty_intransit_md', 'pl_qty_intransit_md.id_ref = do_qty_intransit_md.id_do_sales_order')
        // ->join('tr_h3_md_packing_sheet as ps_qty_intransit_md', 'ps_qty_intransit_md.id_picking_list = pl_qty_intransit_md.id_picking_list')
        // ->join('tr_h3_dealer_penerimaan_barang as pb_qty_intransit_md', 'pb_qty_intransit_md.id_packing_sheet = ps_qty_intransit_md.id_packing_sheet', 'left')
        // ->where('pb_qty_intransit_md.id_penerimaan_barang', null)
        // ->where_in('po_qty_intransit_md.status', ['Processed by MD', 'Closed'])
        // ;

        $qty_order_fulfillment = $this->db
            ->select('SUM(of_qty_intransit_md.qty_fulfillment) as qty', false)
            ->from('tr_h3_dealer_order_fulfillment as of_qty_intransit_md')
            ->where('of_qty_intransit_md.po_id = opt_qty_intransit_md.po_id', null, false)
            ->where('of_qty_intransit_md.id_part = opt_qty_intransit_md.id_part', null, false)
            ->get_compiled_select();

        $this->db
            ->select("IFNULL( SUM(opt_qty_intransit_md.qty_bill - IFNULL(({$qty_order_fulfillment}), 0)), 0 ) as kuantitas", false)
            // ->select('opt_qty_intransit_md.po_id')
            // ->select('opt_qty_intransit_md.id_part')
            // ->select('po_qty_intransit_md.status')
            // ->select("IFNULL(({$qty_order_fulfillment}), 0) as fulfillment")
            // ->select('opt_qty_intransit_md.qty_bill')
            ->from('tr_h3_dealer_order_parts_tracking as opt_qty_intransit_md')
            ->join('tr_h3_dealer_purchase_order_parts as pop_qty_intransit_md', '(pop_qty_intransit_md.po_id = opt_qty_intransit_md.po_id and pop_qty_intransit_md.id_part = opt_qty_intransit_md.id_part)')
            ->join('tr_h3_dealer_purchase_order as po_qty_intransit_md', 'po_qty_intransit_md.po_id = opt_qty_intransit_md.po_id')
            ->where('po_qty_intransit_md.po_rekap', 0)
            ->where_in('po_qty_intransit_md.status', ['Processed by MD', 'Closed']);


        if ($sql) {
            $this->db->where("opt_qty_intransit_md.id_part = {$id_part}");
            $this->db->where("po_qty_intransit_md.id_dealer = {$id_dealer}");

            return $this->db->get_compiled_select();
        } else {
            $this->db->where('opt_qty_intransit_md.id_part', $id_part);
            $this->db->where('po_qty_intransit_md.id_dealer', $id_dealer);

            $data = $this->db->get()->row_array();
            return $data != null ? $data['kuantitas'] : 0;
        }
    }

    public function qty_intransit_part_transfer($id_dealer, $id_part, $sql = false)
    {
        $this->db
            ->select('IFNULL( SUM(outbound_parts_qty_intransit_part_transfer.kuantitas), 0) as kuantitas')
            ->from('tr_h3_dealer_outbound_form_part_transfer_parts as outbound_parts_qty_intransit_part_transfer')
            ->join('tr_h3_dealer_outbound_form_part_transfer as outbound_qty_intransit_part_transfer', 'outbound_qty_intransit_part_transfer.id_outbound_form_part_transfer = outbound_parts_qty_intransit_part_transfer.id_outbound_form_part_transfer')
            ->where('outbound_qty_intransit_part_transfer.status', 'In Transit');

        if ($sql) {
            $this->db->where("outbound_parts_qty_intransit_part_transfer.id_part = {$id_part}");
            $this->db->where("outbound_qty_intransit_part_transfer.id_dealer = {$id_dealer}");

            return $this->db->get_compiled_select();
        } else {
            $this->db->where('outbound_parts_qty_intransit_part_transfer.id_part', $id_part);
            $this->db->where('outbound_qty_intransit_part_transfer.id_dealer', $id_dealer);

            $data = $this->db->get()->row_array();
            return $data != null ? $data['kuantitas'] : 0;
        }
    }

    public function qty_intransit_event($id_dealer, $id_part, $sql = false)
    {
        $this->db
            ->select('IFNULL( SUM(fulfillment_parts_qty_intransit_event.kuantitas), 0) as kuantitas')
            ->from('tr_h3_dealer_outbound_form_for_fulfillment_parts as fulfillment_parts_qty_intransit_event')
            ->join('tr_h3_dealer_outbound_form_for_fulfillment as fulfillment_qty_intransit_event', 'fulfillment_qty_intransit_event.id_outbound_form_for_fulfillment = fulfillment_parts_qty_intransit_event.id_outbound_form_for_fulfillment')
            ->join('tr_h3_dealer_inbound_form_for_parts_return as inbound_qty_intransit_event', '(inbound_qty_intransit_event.id_outbound_form = fulfillment_qty_intransit_event.id_outbound_form_for_fulfillment and inbound_qty_intransit_event.status = "Closed")', 'left')
            ->where('fulfillment_qty_intransit_event.status !=', 'Open')
            ->where('inbound_qty_intransit_event.id_inbound_form_for_parts_return', null);

        if ($sql) {
            $this->db->where("fulfillment_parts_qty_intransit_event.id_part = {$id_part}");
            $this->db->where("fulfillment_qty_intransit_event.id_dealer = {$id_dealer}");

            return $this->db->get_compiled_select();
        } else {
            $this->db->where('fulfillment_parts_qty_intransit_event.id_part', $id_part);
            $this->db->where('fulfillment_qty_intransit_event.id_dealer', $id_dealer);

            $data = $this->db->get()->row_array();
            return $data != null ? $data['kuantitas'] : 0;
        }
    }

    public function stock_in_transit($id_dealer, $id_part, $sql = false)
    {
        if ($sql) {
            $qty_intransit_md = $this->qty_intransit_md($id_dealer, $id_part, $sql);
            $qty_intransit_part_transfer = $this->qty_intransit_part_transfer($id_dealer, $id_part, $sql);
            $qty_intransit_event = $this->qty_intransit_event($id_dealer, $id_part, $sql);

            return "({$qty_intransit_md}) + ({$qty_intransit_part_transfer}) + ({$qty_intransit_event})";
        } else {
            $qty_intransit_md = $this->qty_intransit_md($id_dealer, $id_part, $sql);
            $qty_intransit_part_transfer = $this->qty_intransit_part_transfer($id_dealer, $id_part, $sql);
            $qty_intransit_event = $this->qty_intransit_event($id_dealer, $id_part, $sql);

            return $qty_intransit_md + $qty_intransit_part_transfer + $qty_intransit_event;
        }
    }

    public function qty_sim_part($id_dealer, $id_part, $sql = false)
    {
        $this->db
            ->select('spi_qty_sim_part.qty_sim_part as kuantitas')
            ->from('ms_h3_md_jumlah_pit as jp_qty_sim_part')
            ->join('ms_h3_md_sim_part as sp_qty_sim_part', '(sp_qty_sim_part.batas_bawah_jumlah_pit <= jp_qty_sim_part.jumlah_pit and sp_qty_sim_part.batas_atas_jumlah_pit >= jp_qty_sim_part.jumlah_pit)')
            ->join('ms_h3_md_sim_part_item as spi_qty_sim_part', '(spi_qty_sim_part.id_sim_part = sp_qty_sim_part.id_sim_part)')
            ->group_by('spi_qty_sim_part.id_part');

        $this->db->where('spi_qty_sim_part.id_part_int', $id_part, !$sql);
        $this->db->where('jp_qty_sim_part.id_dealer', $id_dealer, !$sql);

        if ($sql) {
            return $this->db->get_compiled_select();
        } else {
            $data = $this->db->get()->row_array();
            return $data != null ? $data['kuantitas'] : 0;
        }
    }
}
