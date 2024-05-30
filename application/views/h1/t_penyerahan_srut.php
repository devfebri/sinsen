<table id="example2" class="table myTable1 table-bordered table-hover">
  <thead>
    <tr>
      <th>No Mesin</th>
      <th>No Rangka</th>
      <th>No SRUT</th>
      <th>No SRUT dr Pemohon</th>
      <th>Tahun Pembuatan</th>
      <th>Aksi</th>            
    </tr>
  </thead>
 
  <tbody>                    
    <?php   
    // $dt_srut = $this->db->query("SELECT tr_srut.* FROM tr_penerimaan_unit_dealer 
    //   INNER JOIN tr_penerimaan_unit_dealer_detail 
    //     ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
    //     INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
    //     INNER JOIN tr_srut ON tr_scan_barcode.no_mesin = tr_srut.no_mesin
    //     WHERE tr_scan_barcode.status = '4' AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' AND tr_srut.tgl_faktur = '$tgl_faktur'
    //     AND tr_scan_barcode.no_mesin NOT IN (SELECT no_mesin FROM tr_penyerahan_srut_detail WHERE no_mesin IS NOT NULL)");

    // $dt_srut = $this->db->query("SELECT tr_srut.* FROM tr_picking_list_view
    //     INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list=tr_picking_list.no_picking_list
    //     INNER JOIN tr_do_po ON tr_picking_list.no_do=tr_do_po.no_do
    //     INNER JOIN tr_scan_barcode ON tr_picking_list_view.no_mesin = tr_scan_barcode.no_mesin
    //     INNER JOIN tr_srut ON tr_scan_barcode.no_mesin = tr_srut.no_mesin
    //     WHERE tr_do_po.id_dealer = '$id_dealer' AND tr_srut.tgl_faktur = '$tgl_faktur'
    //     AND tr_scan_barcode.no_mesin NOT IN (SELECT no_mesin FROM tr_penyerahan_srut_detail WHERE no_mesin IS NOT NULL)");

    $dt_srut = $this->db->query("SELECT tr_srut.* FROM tr_picking_list_view
        INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list=tr_picking_list.no_picking_list
        INNER JOIN tr_do_po ON tr_picking_list.no_do=tr_do_po.no_do
        INNER JOIN tr_scan_barcode ON tr_picking_list_view.no_mesin = tr_scan_barcode.no_mesin
        INNER JOIN tr_srut ON tr_scan_barcode.no_mesin = tr_srut.no_mesin
        WHERE tr_do_po.id_dealer = '$id_dealer' 
        AND tr_scan_barcode.no_mesin NOT IN (SELECT no_mesin FROM tr_penyerahan_srut_detail WHERE no_mesin IS NOT NULL)");
    $no=1;
    foreach($dt_srut->result() as $isi) {                   
      // $jum = $dt_srut->num_rows();
      // echo "<input type='hidden' name='jum1' value='$jum'>";
      // echo "<input type='hidden' name='no_mesin_$no' value='$isi->no_mesin'>";
      echo "
      <tr>                     
        <td>$isi->no_mesin</td> 
        <td>$isi->no_rangka</td> 
        <td>$isi->no_srut</td> 
        <td>$isi->no_srut_pemohon</td> 
        <td>$isi->tahun_pembuatan</td>                       
        <td><input type=\"checkbox\" name='chk[]' value='$isi->no_mesin' checked></td>                       
      </tr>";
      $no++;
      }
      echo "<input type='hidden' name='no' value='$no'>";

    // $dt_pl = $this->db->query("SELECT tr_srut.* FROM tr_picking_list INNER JOIN tr_picking_list_view ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list
    //     INNER JOIN tr_scan_barcode ON tr_picking_list_view.no_mesin = tr_scan_barcode.no_mesin 
    //     INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
    //     INNER JOIN tr_srut ON tr_scan_barcode.no_mesin = tr_srut.no_mesin
    //     WHERE tr_do_po.id_dealer = '$id_dealer' AND (tr_scan_barcode.status = '3' OR tr_scan_barcode.status = '2')
    //     AND tr_scan_barcode.no_mesin NOT IN (SELECT no_mesin FROM tr_penyerahan_srut_detail WHERE no_mesin IS NOT NULL)");
    // $no1=1;
    // foreach($dt_pl->result() as $isi) {                   
    //   $jum2 = $dt_pl->num_rows();
    //   echo "<input type='hidden' name='jum2' value='$jum2'>";
    //   echo "<input type='hidden' name='no_mesin2_$no1' value='$isi->no_mesin'>";
    //   echo "
    //   <tr>                     
    //     <td>$isi->no_mesin</td> 
    //     <td>$isi->no_rangka</td> 
    //     <td>$isi->no_srut</td> 
    //     <td>$isi->no_srut_pemohon</td> 
    //     <td>$isi->tahun_pembuatan</td>                       
    //   </tr>";
    //   $no1++;
    //   }
    ?>
  </tbody>
</table>     