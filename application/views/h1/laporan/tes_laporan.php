<body>
      <div style="text-align: center;font-size: 13pt"><b>Laporan Stock Unit</b></div>
      
      <hr>      
      <?php 
      $sql_dealer = $this->db->query("SELECT * FROM ms_dealer LIMIT 0,3");
      foreach ($sql_dealer->result() as $isi) {
            $sql_stok = $this->db->query("SELECT ms_tipe_kendaraan.tipe_ahm,tr_scan_barcode.* FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_penerimaan_unit_dealer
                ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer
                INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = tr_scan_barcode.tipe_motor
                WHERE tr_penerimaan_unit_dealer.id_dealer = '$isi->id_dealer' AND (tr_scan_barcode.status = 4 OR tr_scan_barcode.status = 5)");                        
            if($sql_stok->num_rows() > 0){
            echo "Dealer : $isi->kode_dealer_md - $isi->nama_dealer"; ?>
            <table class='table table-bordered' style='font-size: 9pt' width='100%'>
              <tr>
                <td class='bold text-center' width='5%'>No</td>
                <td class='bold text-center' width='40%'>Tipe Motor</td>
                <td class='bold text-center' width='20%'>Tgl Dist (Terakhir)</td>
                <td class='bold text-center' width='20%'>Tgl Jual (Terakhir)</td>
                <td class='bold text-center' width='15%'>Stock O.H</td>          
            </tr>
            <?php 
            $no=1;
            foreach ($sql_stok->result() as $amb) {
              $cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
                LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
                WHERE tr_scan_barcode.id_item = '$amb->id_item' AND tr_penerimaan_unit_dealer.id_dealer = '$isi->id_dealer' 
                AND tr_scan_barcode.status = '4'")->row();                
              $cek_ssu = $this->db->query("SELECT * FROM tr_sales_order INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
               WHERE tr_sales_order.id_dealer = '$isi->id_dealer' AND tr_scan_barcode.tipe_motor = '$amb->tipe_motor' 
               ORDER BY tgl_create_ssu DESC LIMIT 0,1");
              if($cek_ssu->num_rows() > 0){
                $tgl_create_ssu = $cek_ssu->row()->tgl_create_ssu;
                if(isset($tgl_create_ssu)){
                  $tgl_jual = date("d F Y", strtotime($tgl_create_ssu));    
                }else{
                  $tgl_jual = "";
                }
              }else{
                $tgl_jual = "";
              }

              $cek_sj = $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
                INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin 
                WHERE tr_surat_jalan.id_dealer = '$isi->id_dealer' AND tr_scan_barcode.tipe_motor = '$amb->tipe_motor' 
                ORDER BY tgl_surat DESC LIMIT 0,1");
              if($cek_sj->num_rows() > 0){
                $tgl_surat1 = $cek_sj->row()->tgl_surat;
                if(isset($tgl_surat1)){
                  $tgl_surat = date("d F Y", strtotime($tgl_surat1));    
                }else{
                  $tgl_surat = "";
                }
              }else{
                $tgl_surat = "";
              }
              echo "
              <tr>
                <td>$no</td>
                <td>$amb->tipe_ahm</td>
                <td>$tgl_surat</td>
                <td>$tgl_jual</td>
                <td>$cek_qty->jum</td>            
              </tr>";              
              $no++;
            }
          }
          ?>
          </table> <br>
      <?php
      }
      ?>
    </body>