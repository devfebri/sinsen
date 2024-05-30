    <script src="assets/panel/plugins/datatables/jquery.dataTables.min.js"></script>
<body>
<table id="exampleX" class="table myTable1 table-bordered table-hover">
  <thead>
    <tr>      
      <th>Tgl Mohon Samsat</th>    
      <th>Nama Dealer</th>          
      <th>No Mesin</th>              
      <th>No Rangka</th>
      <th>Nama Konsumen</th>
      <th>Tipe</th>
      <th>Warna</th>
      <th>Tahun Produksi</th>
      <th>Harga BBN</th>
      <th>Notice Pajak</th>
      <th>Selisih</th>
      <!-- <th>Action</th>       -->
    </tr>
  </thead>
 
  <tbody>                    
    <?php   
    $no = 1;
    foreach($dt_bbn->result() as $isi) {       
      $cek = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail WHERE no_mesin = '$isi->no_mesin'");        
      if($cek->num_rows() > 0){      
        $r = $cek->row();
        $biaya_biro = $r->biaya_bbn_md_bj;
      }else{
        $biaya_biro = 0;
      }
      $jum = $dt_bbn->num_rows();
      $getdealer = $this->db->query("SELECT id_dealer FROM tr_sales_order WHERE no_mesin ='$isi->no_mesin'");
      $getdealer_2 = $this->db->query("SELECT id_dealer FROM tr_sales_order_gc INNER JOIn tr_sales_order_gc_nosin ON 
                        tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc 
                        WHERE tr_sales_order_gc_nosin.no_mesin ='$isi->no_mesin'");    
      $getdealer_3 = $this->db->query("SELECT id_dealer FROM tr_bantuan_bbn_luar WHERE no_mesin ='$isi->no_mesin'");  
      if($getdealer->num_rows()>0) {
        $getdealer = $getdealer->row()->id_dealer;
        $dealer = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer='$getdealer'");
      }elseif($getdealer_2->num_rows()>0) {
        $getdealer = $getdealer_2->row()->id_dealer;
        $dealer = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer='$getdealer'");
      }elseif($getdealer_3->num_rows()>0) {
        $getdealer = $getdealer_3->row()->id_dealer;
        $dealer = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer='$getdealer'");
      }

                   
      $dealer = isset($dealer)?$dealer->row()->nama_dealer:'';
      echo "
      <tr id='tr_detail_$no'>             
        <td>$isi->tgl_samsat</td> 
         <td>$dealer</td> 
        <td>$isi->no_mesin</td> 
        <td>$isi->no_rangka</td> 
        <td>$isi->nama_konsumen</td> 
        <td>$isi->tipe_ahm</td> 
        <td>$isi->warna</td> 
        <td>$isi->tahun</td> 
        <td>".number_format($biaya_biro, 0, ',', '.')."</td>         
        <td align='center'>          
          <input type='hidden' value='$jum' name='jum' id='jum'>
          <input type='hidden' value='$isi->no_mesin' name='no_mesin_$no'>
          <input type='hidden' value='$biaya_biro' id='biaya_biro_$no'>
          <input type='checkbox' id='cek_notice_$no' name='cek_notice_$no'  onchange='cek_form()'><br>
          <input type=\"text\" autocomplete=\"off\" onkeypress=\"return number_only(event)\" onkeyup=\"cekSelisih($no)\" class=\"form-control isi\" name=\"notice_pajak_$no\" id=\"notice_pajak_$no\" minlength=\"7\">
        </td>      
        <td>
          <span id='selisih_$no'></span>
        </td>
        
      </tr>";
      $no++;
      }
    ?>
  </tbody>
</table>     
<!-- 
<td align='center'>
          <input type='checkbox' onclick='cek_form()' id='cek_form_$no' name='check_$no'>
        </td>   -->            
<script type="text/javascript">
  $(document).ready(function() {
    $('#example4').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "scrollX":true,
          fixedHeader:true,
          "lengthMenu": [[10, 25, 50,75,100, -1], [10, 25, 50,75,100, "All"]],
          "autoWidth": true
        });
        var ex2 = $('#example2').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          fixedHeader:true,
          "lengthMenu": [[10, 25, 50,75,100, -1], [10, 25, 50,75,100, "All"]],
          "autoWidth": true
        });
        $('#example5').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          fixedHeader:true,
          "lengthMenu": [[10, 25, 50,75,100, -1], [10, 25, 50,75,100, "All"]],
          "autoWidth": true
        });
        $('#example3').DataTable({
          paging: true,
          lengthChange: true,
          searching: true,
          ordering: true,
          info: true,
          fixedHeader:true,
          "lengthMenu": [[10, 25, 50,75,100, -1], [10, 25, 50,75,100, "All"]],
          
          columnDefs: [
            { 
                "targets": [ 0 ], //first column
                "orderable": false, //set not orderable
            },
            { 
                "targets": [ -1 ], //first column
                "orderable": false, //set not orderable
            },
          ],            
          autoWidth: true         
        });
  })
</script>