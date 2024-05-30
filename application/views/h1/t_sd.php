<table id="example1" class="table table-bordered table-hover">
  <thead>
    <tr>              
      <th>Kode Tipe</th>              
      <th>Stock Days Niguri MD</th>
      <th>Stock MD</th>      
      <th>Intransit AHM</th>      
      <th>Distribusi Dealer</th>        
    </tr>
  </thead>
  <tbody>            
    <?php 
    $no=1;
    $rt = $this->m_admin->getSortCond("ms_item","id_item","ASC");    
    foreach ($rt->result() as $isi) {
      ///----cari stock days-------//
      $cek_n = $this->db->query("SELECT * FROM tr_niguri INNER JOIN tr_niguri_detail ON tr_niguri.id_niguri = tr_niguri_detail.id_niguri
        WHERE tr_niguri.bulan = '$bulan' AND tr_niguri.tahun = '$tahun' AND tr_niguri_detail.id_tipe_kendaraan = '$isi->id_tipe_kendaraan'");
      if($cek_n->num_rows() > 0){
        $d = $cek_n->row();
        $stock_days = $d->d_fix;
      }else{
        $stock_days = 0;
      }

      ///----cari on hand-------//
      $bt = $bulan.$tahun;
      $cek_no = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$isi->id_tipe_kendaraan' 
          AND status = '1' AND (tipe='RFS' OR tipe='NRFS')");      
      if($cek_no->num_rows() > 0){
        $f = $cek_no->row();
        $onhand = $f->jum;        
      }else{
        $onhand = 0;
      }

      $cek_ds = $this->db->query("SELECT COUNT(kode_md) AS jum FROM tr_displan WHERE id_tipe_kendaraan = '$isi->id_tipe_kendaraan'
          AND MID(tr_displan.tanggal,3,6) = '$bt'")->row();          
      $cek_md = ($ahm/100) * $cek_ds->jum + $onhand * ($md/100);            

      ///----cari intransit-------//
      $cek_sl = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_shipping_list WHERE id_modell = '$isi->id_tipe_kendaraan'
          AND tr_shipping_list.no_mesin NOT IN (SELECT no_mesin FROM tr_scan_barcode WHERE no_mesin IS NOT NULL)");      
      if($cek_sl->num_rows() > 0){
        $f = $cek_sl->row();
        $intransit = $f->jum;
      }else{
        $intransit = 0;
      }

      ///----cari distribusi dealer-------//      
      $cek_st = $this->db->query("SELECT COUNT(tr_penerimaan_unit_dealer_detail.no_mesin) AS jum FROM tr_scan_barcode INNER JOIN tr_penerimaan_unit_dealer_detail 
          ON tr_scan_barcode.no_mesin = tr_penerimaan_unit_dealer_detail.no_mesin WHERE tr_scan_barcode.status = 1 AND (tr_scan_barcode.tipe = 'RFS' OR tr_scan_barcode.tipe = 'NRFS')
          AND tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan'");      
      if($cek_st->num_rows() > 0){
        $w = $cek_st->row();
        $stok_d = $w->jum;        
      }else{
        $stok_d = 0;
      }

      $bu = $bulan."-".$tahun;
      $a1 = $bulan - 1;
      $a2 = $bulan + 1;
      if($a1 == "-1"){
        $a1 = "11";
      }elseif($a1 == "0"){
        $a1 = "12";
      }      
      if($a2 == "14"){
        $a2 = "2";
      }elseif($a2 == "13"){
        $a2 = "1";
      }      

      $bu1 = $a1."-".$tahun;
      $bu2 = $a2."-".$tahun;
      $cek_jual = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_sales_order INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin=tr_scan_barcode.no_mesin 
                WHERE tr_scan_barcode.tipe_motor = 'HP1'
                AND (MID(tr_sales_order.tgl_cetak_invoice,6,2) = '$bu' OR 
                MID(tr_sales_order.tgl_cetak_invoice,6,2) = '$bu1' OR 
                MID(tr_sales_order.tgl_cetak_invoice,6,2) = '$bu2')")->row();
      $cek_md = $stock_days * ($cek_jual->jum / 3) - $stok_d;

      $jum = $rt->num_rows();
      echo "
      <tr>
        <td>$isi->id_tipe_kendaraan</td>
        <td>$stock_days</td>
        <td>".round($cek_md)."</td>        
        <td>$intransit</td>
        <td>
          <input type='hidden' name='jum' value='$jum'>          
          <input type='hidden' name='id_tipe_kendaraan_$no' value='$isi->id_tipe_kendaraan'>          
          <input type='hidden' name='stock_days_$no' value='$stock_days'>          
          <input type='hidden' name='stock_md_$no' value='$cek_md'>          
          <input type='hidden' name='intransit_$no' value='$intransit'>                    
          <input type='text' width='10%' class='form-control isi' name='cek_md_$no' value='$cek_md'>
        </td>
      </tr>
      ";
      $no++;
    }
    ?>
  </tbody>
</table>
    <script type="text/javascript">
  $(document).ready(function() {
    $('#example1').DataTable( {
        responsive: true,
        dom: 'Bfrtip',
        buttons: [

            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns:':visible'
                }
            },
            'colvis'
        ]
       // columnDefs: [
         //   { responsivePriority: 1, targets: 0 },
           // { responsivePriority: 2, targets: -2 }
        //]
    } );
} );
</script>