$item = $this->db->query("SELECT * FROM ms_item LEFT JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                  WHERE ms_item.active = '1'");
                  //WHERE ms_item.active = '1' LIMIT 0,10");
               foreach ($item->result() as $isi) {
                $cek_ready = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND status = '1' AND tipe='RFS'")->row();
                $cek_booking = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND status = '2'")->row();
                $cek_pl = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND status = '3'")->row();
                $cek_nrfs = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND tipe = 'NRFS' AND status < 4")->row();
                $cek_pinjaman = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND tipe = 'PINJAMAN' AND status < 4")->row();
                $cek_sl = $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list 
                                  WHERE no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_scan_barcode) 
                                  AND id_modell = '$isi->id_tipe_kendaraan' AND id_warna = '$isi->id_warna'")->row();

                $cek_sl1 = $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list INNER JOIN ms_item ON tr_shipping_list.id_modell = ms_item.id_tipe_kendaraan AND tr_shipping_list.id_warna=ms_item.id_warna
                  WHERE tr_shipping_list.id_modell = '$isi->id_tipe_kendaraan' AND tr_shipping_list.id_warna = '$isi->id_warna'
                  AND ms_item.bundling <> 'ya'")->row();
                $cek_sl2 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode INNER JOIN ms_item ON tr_scan_barcode.tipe_motor = ms_item.id_tipe_kendaraan AND tr_scan_barcode.warna = ms_item.id_warna 
                  WHERE tipe_motor = '$isi->id_tipe_kendaraan' AND warna = '$isi->id_warna'
                  AND ms_item.bundling <> 'ya'")->row();      
                $cek_in1 = $this->db->query("SELECT SUM(tr_sipb.jumlah) AS jum FROM tr_sipb INNER JOIN ms_item ON ms_item.id_tipe_kendaraan = tr_sipb.id_tipe_kendaraan AND ms_item.id_warna = tr_sipb.id_warna 
                  WHERE tr_sipb.id_tipe_kendaraan = '$isi->id_tipe_kendaraan' AND tr_sipb.id_warna = '$isi->id_warna'
                  AND ms_item.bundling <> 'ya'")->row();                
                $cek_in2 = $this->db->query("SELECT COUNT(tr_shipping_list.no_mesin) AS jum FROM tr_shipping_list INNER JOIN ms_item ON tr_shipping_list.id_modell = ms_item.id_tipe_kendaraan AND tr_shipping_list.id_warna=ms_item.id_warna
                  WHERE tr_shipping_list.id_modell = '$isi->id_tipe_kendaraan' AND tr_shipping_list.id_warna = '$isi->id_warna'
                  AND ms_item.bundling <> 'ya'")->row();
                $cek_item = $this->db->query("SELECT * FROM ms_item WHERE id_item = '$isi->id_item'")->row();
                $sipb = 0;
                $total = $cek_ready->jum + $cek_booking->jum + $cek_pl->jum + $cek_nrfs->jum  + $cek_pinjaman->jum;
                if($cek_in1->jum - $cek_in2->jum > 0 AND $cek_item->bundling != 'ya'){
                  $rr = $cek_in1->jum - $cek_in2->jum;
                }else{
                  $rr = 0;
                }

                if($cek_sl1->jum - $cek_sl2->jum > 0 AND $cek_item->bundling != 'ya'){
                  $r2 = $cek_sl1->jum - $cek_sl2->jum;
                }else{
                  $r2 = 0;
                }             
                $stok_md = $cek_ready->jum + $cek_booking->jum + $cek_pl->jum + $cek_nrfs->jum + $cek_pinjaman->jum;

                $cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
                    LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                    LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                    LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                    LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                    LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
                    WHERE tr_scan_barcode.id_item = '$isi->id_item' AND tr_scan_barcode.status = '4'")->row();                   
                $cek_unfill = $this->db->query("SELECT COUNT(tr_do_po_detail.id_item) AS jum FROM tr_do_po 
                        LEFT JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
                        LEFT JOIN tr_picking_list ON tr_picking_list.no_do = tr_do_po.no_do
                        WHERE tr_picking_list.no_picking_list NOT IN (SELECT no_picking_list FROM tr_surat_jalan)                          
                        AND tr_do_po_detail.id_item = '$isi->id_item'")->row();
                $cek_in = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
                        WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer)
                        AND tr_surat_jalan_detail.id_item = '$isi->id_item'")->row();
                $total_stock = $r2 + $stok_md + $cek_unfill->jum + $cek_in->jum + $cek_qty->jum;
                $stock_market = $stok_md + $cek_unfill->jum + $cek_in->jum + $cek_qty->jum;

                $cek_sales = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                        WHERE tr_scan_barcode.id_item = '$isi->id_item'")->row();
                if($cek_sales->jum != 0){
                  $stock_days = ceil(($stok_md / $cek_sales->jum) * 30);
                }else{
                  $stock_days = ceil(($stok_md) * 30);
                }      

                if($total_stock != 0){                                
                 echo "
                 <tr>
                    <td>$isi->id_item</td>
                    <td>$isi->deskripsi_ahm</td>
                    <td>$rr</td>
                    <td>$r2</td>
                    <td>$stok_md</td>
                    <td>$cek_unfill->jum</td>
                    <td>$cek_in->jum</td>
                    <td>$cek_qty->jum</td>
                    <td>$total_stock</td>
                    <td>$stock_market</td>
                    <td>$cek_sales->jum</td>
                    <td>$stock_days</td>
                 </tr>
                 ";
                  }
                }