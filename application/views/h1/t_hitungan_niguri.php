<script type="text/javascript">
  // Change the selector if needed
var $table = $('table.scroll'),
    $bodyCells = $table.find('tbody tr:first').children(),
    colWidth;

// Adjust the width of thead cells when window resizes
$(window).resize(function() {
    // Get the tbody columns width array
    colWidth = $bodyCells.map(function() {
        return $(this).width();
    }).get();
    
    // Set the width of thead columns
    $table.find('thead tr').children().each(function(i, v) {
        $(v).width(colWidth[i]);
    });    
}).resize(); // Trigger resize handler
</script>
<style type="text/css">
table.scroll {
    width: 100%; /* Optional */
}

table.scroll tbody,
table.scroll thead { display: block; }



table.scroll tbody {
    height: 450px;
    overflow-y: auto;
    overflow-x: hidden;
}

tbody { border-top: 0px solid black; }
</style>
<table id="myTable" class="table myTable1 order-list mytable_niguri scroll" border="0">
  <thead>
    <tr  width="90%">
      <th width="5%">No</th>
      <th width="8.2%">ID Item</th>
      <th width="20.7%">Tipe</th>
      <th width="14.9%">Warna</th>                        
      <th width="15%"><div align="right">Jenis</a></th>
      <th width="7.3%">&nbsp;&nbsp;M-1 <font color="red">[ <?php echo $lm1 ?> ]</font></th>
      <th width="7.4%">&nbsp;&nbsp;&nbsp;M <font color="red">[ <?php echo $lm ?> ]</font></th>
      <th width="7%">&nbsp;Fix <font color="blue">[ <?php echo $lfix ?> ]</font></th>
      <th width="7%">&nbsp;&nbsp;T1 <font color="red">[ <?php echo $lt1 ?> ]</font></th>
      <th width="9%">&nbsp;&nbsp;T2 <font color="red">[ <?php echo $lt2 ?> ]</font></th>     
      <th></th>
    </tr>
  </thead> 
  <tbody>
    <?php 
    $no=1;
    $item = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item 
        INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
        INNER JOIN ms_warna ON ms_warna.id_warna = ms_item.id_warna WHERE ms_tipe_kendaraan.active = 1 AND (ms_item.bundling = '' OR  ms_item.bundling IS NULL) 
        ");
    //ORDER BY id_item ASC LIMIT 0,1000");
      
    // note: *izin edit, michael, 1 agsts 2019 13.57; penambahan where and ms_item.active = 1  
    $item = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item 
        INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
        INNER JOIN ms_warna ON ms_warna.id_warna = ms_item.id_warna WHERE ms_item.active = 1 AND (ms_item.bundling = '' OR  ms_item.bundling IS NULL) ORDER BY id_item ASC
        ");
    //end note    
        
    $a=0;
    foreach ($item->result() as $isi) {
    	$jumlah = $item->num_rows();
	    $tgl 			= date("dmY");
			$id_item 	= $isi->id_item;
			$bul 			= $dbulan;
			$tahun 		= $dtahun;
			$bulan 		= $bul - 1;		
			$bulan2 	= $bul - 2;			
			
			if($bulan == "-1"){
	    	$bln = "11";
	    	$th = $tahun-1;
		  }elseif($bulan == "0"){
		    $bln = "12";
		    $th = $tahun-1;
		  }else{
		  	$bln = $bulan;
		  	$th = $tahun;
		  }

		  if($bulan2 == "-1"){
	    	$bln2 = "11";
	    	$th2 = $tahun-1;
		  }elseif($bulan2 == "0"){
		    $bln2 = "12";
		    $th2 = $tahun-1;
		  }else{
		  	$bln2 = $bulan2;
		  	$th2 = $tahun;
		  }

		  $isi_bln 		= $bln;		
		  $isi_bln2 	= $bln2;		
		  $isi_bln3 	= $bul;		

		  $isi_bl_1 	= sprintf("%'.02d",$bln);		
			$isi_bl_2 	= sprintf("%'.02d",$bln2);		

		  $r_m     		= $th."-".$isi_bl_1;
		  $r_m1     	= $th."-".$isi_bl_2;
		  $bln_thn  	= $isi_bln.$th;	
		  $bln_thn_ds = $isi_bl_1.$th;	
		  $bln_thn_sl = $isi_bl_2.$th2;	
		  
			$sql = $this->db->query("SELECT * FROM ms_item INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
				INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna 
				WHERE ms_item.id_item = '$id_item'");
			if($sql->num_rows() > 0){
				$dt_ve = $sql->row();
				$id_tipe_kendaraan = $dt_ve->id_tipe_kendaraan;
				$id_warna = $dt_ve->id_warna;
				
				$cari_m1 = $this->db->query("SELECT count(no_mesin) as jum FROM tr_shipping_list WHERE id_modell = '$id_tipe_kendaraan' 
							AND id_warna = '$id_warna' AND RIGHT(tgl_sl,6) = '$bln_thn_sl'");			
				if($cari_m1->num_rows() > 0){
					$ty = $cari_m1->row();
					$a_m1 = $ty->jum;
				}else{
					$a_m1 = 0;
				}			

				$cari_m2 = $this->db->query("SELECT SUM(qty_plan) as jum FROM tr_displan WHERE id_tipe_kendaraan = '$id_tipe_kendaraan' 
							AND id_warna = '$id_warna' AND RIGHT(tanggal,6) = '$bln_thn_ds'")->row();			
				if(isset($cari_m2->jum)){
					//$op = $cari_m2->row();
					$a_m2 = $cari_m2->jum;
				}else{
					$a_m2 = 0;
				}			

				$cari_fix1 = $this->db->query("SELECT * FROM tr_niguri_detail INNER JOIN tr_niguri
					ON tr_niguri_detail.id_niguri = tr_niguri.id_niguri 
					WHERE tr_niguri_detail.id_item = '$id_item' AND tr_niguri.bulan = '$isi_bln'
					AND tr_niguri.tahun = '$tahun'");
				if($cari_fix1->num_rows() > 0){
					$rr = $cari_fix1->row();
					$data_fix_1 = $rr->a_t1;
					$data_t1 = $rr->a_t2;
					$data_t2 = 0;
					$datb_fix_1 = $rr->b_t1;
					$datb_t1 = $rr->b_t2;
					$datb_t2 = 0;
          $data_rm1 = $rr->b_fix;
				}else{
					$data_fix_1 = 0;
					$data_t1 = 0;
					$data_t2 = 0;
					$datb_fix_1 = 0;
					$datb_t1 = 0;
					$datb_t2 = 0;
          $data_rm1=0;
				}						
				
				// $cek_retail = $this->db->query("SELECT count(tr_sales_order.no_mesin) as jum FROM tr_sales_order INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
				// 		WHERE LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$r_m' AND tr_sales_order.status_so = 'so_invoice'
				// 		AND tr_scan_barcode.id_item = '$id_item'");
				// if($cek_retail->num_rows() > 0){
				// 	$ty = $cek_retail->row();
				// 	$data_rm1 = $ty->jum;
				// }else{
				// 	$data_rm1 = 0;
				// }

				$cek_retail2 = $this->db->query("SELECT count(tr_sales_order.no_mesin) as jum FROM tr_sales_order INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
						WHERE LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$r_m1' AND tr_sales_order.status_so = 'so_invoice'
						AND tr_scan_barcode.id_item = '$id_item'");
				if($cek_retail2->num_rows() > 0){
					$t = $cek_retail2->row();
					$data_rm = $t->jum;
				}else{
					$data_rm = 0;
				}
			}

      $item_cek = $this->db->query("SELECT * FROM tr_niguri_detail INNER JOIN tr_niguri ON tr_niguri.id_niguri = tr_niguri_detail.id_niguri
         WHERE tr_niguri_detail.id_item = '$isi->id_item' AND tr_niguri.bulan = '$isi_bln'
          AND tr_niguri.tahun = '$tahun'");
      if($item_cek->num_rows() > 0){
        $cek_item = "readonly";
      }else{
        $cek_item = "";
      }
			//echo "ok"."|".$dt_ve->id_item."|".$dt_ve->tipe_ahm."|".$dt_ve->warna."|".$a_m1."|".$a_m2."|".$data_fix_1."|".$data_t1."|".$data_t2."|".$data_rm1."|".$data_rm."|".$datb_fix_1."|".$datb_t1."|".$datb_t2;
      	echo "
        <tr>
          <td width='5%'>$no</td>
          <td width='8%'>$isi->id_item</td>
          <td width='20%'>$isi->tipe_ahm</td>
          <td width='14%'>$isi->warna</td>                          
          <td width='15%' align='right'>
            AHM Dist to MD
            <input type='hidden' value='$isi->id_item' name='id_item_$no' id='id_item_$no'>
            <input type='hidden' value='$jumlah' name='jumlah'>
          </td>
          <td width=\"7%\">
            <input type=\"text\" value='$a_m1' id=\"a_m1_$no\" readonly onkeypress=\"return number_only(event)\" class=\"form-control isi a_m1\" placeholder=\"M-1\" name=\"a_m1_$no\">
          </td>
          <td width=\"7%\">
            <input type=\"text\" id=\"a_m_$no\" value='$a_m2'  readonly  onkeypress=\"return number_only(event)\" class=\"form-control isi a_m\" placeholder=\"M\" name=\"a_m_$no\">
          </td>
          <td width=\"7%\">
            <input type=\"text\" value='$data_fix_1' id=\"a_fix_$no\" onchange=\"cek_niguri($no,'a_fix')\" onkeypress=\"return number_only(event)\" class=\"form-control isi a_fix\" placeholder=\"Fix\" name=\"a_fix_$no\">
          </td>
          <td width=\"7%\">
            <input type=\"text\" value='$data_t1' id=\"a_t1_$no\" onchange=\"cek_niguri($no,'a_t1')\" onkeypress=\"return number_only(event)\" class=\"form-control isi a_t1\" placeholder=\"T1\" name=\"a_t1_$no\">
          </td>
          <td width=\"7%\">
            <input type=\"text\" value='$data_t2' id=\"a_t2_$no\" onchange=\"cek_niguri($no,'a_t2')\" onkeypress=\"return number_only(event)\" class=\"form-control isi a_t2\" placeholder=\"T2\" name=\"a_t2_$no\">                          
          </td>          
        </tr>
        <tr>                            
          <td colspan='5' width='15%' align='right'>
            Retail Sales
          </td>
          <td width='7%'>
            <input type=\"text\" value='$data_rm' id=\"b_m1_$no\" readonly onkeypress=\"return number_only(event)\" class=\"form-control isi b_m1\" placeholder=\"M-1\" name=\"b_m1_$no\">
          </td>
          <td width='7%'>
            <input type=\"text\"  value='$data_rm1' id=\"b_m_$no\" onchange=\"cek_niguri($no,'b_m')\" onkeypress=\"return number_only(event)\" class=\"form-control isi b_m\" placeholder=\"M\" name=\"b_m_$no\">
          </td>
          <td width='7%'>
            <input type=\"text\" value='$datb_fix_1' id=\"b_fix_$no\" onchange=\"cek_niguri($no,'b_fix')\" onkeypress=\"return number_only(event)\" class=\"form-control isi b_fix\" placeholder=\"Fix\" name=\"b_fix_$no\">
          </td>
          <td width='7%'>
            <input type=\"text\" value='$datb_t1' id=\"b_t1_$no\" onchange=\"cek_niguri($no,'b_t1')\" onkeypress=\"return number_only(event)\" class=\"form-control isi b_t1\" placeholder=\"T1\" name=\"b_t1_$no\">
          </td>
          <td width='7%'>
            <input type=\"text\" value='$datb_t2' id=\"b_t2_$no\" onchange=\"cek_niguri($no,'b_t2')\" onkeypress=\"return number_only(event)\" class=\"form-control isi b_t2\" placeholder=\"T2\" name=\"b_t2_$no\">                          
          </td>
        </tr>
        <tr>                            
          <td colspan='5' width='15%' align='right'>
            Total Stock
          </td>
          <td width=\"7%\">
            <input type=\"text\" readonly id=\"c_m1_$no\" onkeypress=\"return number_only(event)\" class=\"form-control isi c_m1\" placeholder=\"M-1\" name=\"c_m1_$no\">
          </td>
          <td width=\"7%\">
            <input type=\"text\" readonly id=\"c_m_$no\" onkeypress=\"return number_only(event)\" class=\"form-control isi c_m\" placeholder=\"M\" name=\"c_m_$no\">
          </td>
          <td width=\"7%\">
            <input type=\"text\" readonly id=\"c_fix_$no\" onkeypress=\"return number_only(event)\" class=\"form-control isi c_fix\" placeholder=\"Fix\" name=\"c_fix_$no\">
          </td>
          <td width=\"7%\">
            <input type=\"text\" readonly id=\"c_t1_$no\" onkeypress=\"return number_only(event)\" class=\"form-control isi c_t1\" placeholder=\"T1\" name=\"c_t1_$no\">
          </td>
          <td width=\"7%\">
            <input type=\"text\" readonly id=\"c_t2_$no\" onkeypress=\"return number_only(event)\" class=\"form-control isi c_t2\" placeholder=\"T2\" name=\"c_t2_$no\">                          
          </td>
        </tr>
        <tr>                            
          <td colspan='5' width='15%' align='right'>
            Total Stock Days
          </td>
          <td width=\"7%\">
            <input type=\"text\" readonly id=\"d_m1_$no\" onkeypress=\"return number_only(event)\" class=\"form-control isi d_m1\" placeholder=\"M-1\" name=\"d_m1_$no\">
          </td>
          <td width=\"7%\">
            <input type=\"text\" readonly id=\"d_m_$no\" onkeypress=\"return number_only(event)\" class=\"form-control isi d_m\" placeholder=\"M\" name=\"d_m_$no\">
          </td>
          <td width=\"7%\">
            <input type=\"text\" readonly id=\"d_fix_$no\" onkeypress=\"return number_only(event)\" class=\"form-control isi d_fix\" placeholder=\"Fix\" name=\"d_fix_$no\">
          </td>
          <td width=\"7%\">
            <input type=\"text\" readonly id=\"d_t1_$no\" onkeypress=\"return number_only(event)\" class=\"form-control isi d_t1\" placeholder=\"T1\" name=\"d_t1_$no\">
          </td>
          <td width=\"7%\">
            <input type=\"text\" readonly id=\"d_t2_$no\" onkeypress=\"return number_only(event)\" class=\"form-control isi d_t2\" placeholder=\"T2\" name=\"d_t2_$no\">                          
          </td>
        </tr>
      ";
      $no++;
    }
    ?>
  </tbody>
</table> 

<table id="myTable1" class="table myTable1 table-bordered table-hover">
  <tr>     
      <td rowspan='4' width='10%' colspan='3'></td>
      <td rowspan='4' width='20%'></td>
      <td rowspan='4' width='14%' align='right'><b>Total</td>

      <td width='17.8%' align='right'><b>AHM Dist to MD</td>
      <td width='7%'><b><span id="a_m1_tot"></span></td>
      <td width='7%'><b><span id="a_m_tot"></span></td>
      <td width='7%''><b><span id="a_fix_tot"></span></td>
      <td width='7%'><b><span id="a_t1_tot"></span></td>
      <td width='9%'><b><span id="a_t2_tot"></span></td>
      <td>
        
      </td>
    </tr>
    <tr>
      <td width='17.8%' align='right'><b>Retail Sales</td>
      <td width='7%'><b><span id="b_m1_tot"></span></td>
      <td width='7%'><b><span id="b_m_tot"></span></td>
      <td width='7%''><b><span id="b_fix_tot"></span></td>
      <td width='7%'><b><span id="b_t1_tot"></span></td>
      <td width='9%'><b><span id="b_t2_tot"></span></td>
      <td></td>
    </tr>
    <tr>
      <td width='17.8%' align='right'><b>Total Stock</td>
     <td width='7%'><b><span id="c_m1_tot"></span></td>
      <td width='7%'><b><span id="c_m_tot"></span></td>
      <td width='7%''><b><span id="c_fix_tot"></span></td>
      <td width='7%'><b><span id="c_t1_tot"></span></td>
      <td width='9%'><b><span id="c_t2_tot"></span></td>
      <td></td>
    </tr>
    <tr>
      <td width='17.8%' align='right'><b>Total Stock Days</td>
      <td width='7%'><b><span id="d_m1_tot"></span></td>
      <td width='7%'><b><span id="d_m_tot"></span></td>
      <td width='7%''><b><span id="d_fix_tot"></span></td>
      <td width='7%'><b><span id="d_t1_tot"></span></td>
      <td width='9%'><b><span id="d_t2_tot"></span></td>
      <td></td>
    </tr>       
</table>
