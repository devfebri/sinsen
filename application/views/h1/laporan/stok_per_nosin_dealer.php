<?php 
function bln($a){
  $bulan=$bl=$month=$a;
  switch($bulan)
  {
    case"1":$bulan="Januari"; break;
    case"2":$bulan="Februari"; break;
    case"3":$bulan="Maret"; break;
    case"4":$bulan="April"; break;
    case"5":$bulan="Mei"; break;
    case"6":$bulan="Juni"; break;
    case"7":$bulan="Juli"; break;
    case"8":$bulan="Agustus"; break;
    case"9":$bulan="September"; break;
    case"10":$bulan="Oktober"; break;
    case"11":$bulan="November"; break;
    case"12":$bulan="Desember"; break;
  }
  $bln = $bulan;
  return $bln;
}
?>
<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 25px;
  padding-left: 4px;
  padding-right: 4px;  
}
.vertical-text{
  writing-mode: lr-tb;
  text-orientation: mixed;
}
.rotate {
  -webkit-transform: rotate(-90deg);
  -moz-transform: rotate(-90deg);
}
#mySpan{
  writing-mode: vertical-lr; 
  transform: rotate(180deg);
}
</style>
<base href="<?php echo base_url(); ?>" />
   
    <?php 
    if($set=="view"){
    ?>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Laporan</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <div class="box box-default">
      <div class="box-header with-border">        
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" id="frm" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                              
<!--                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-1 control-label">Start Date</label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control datepicker" name="start_date" value="<?= date('Y-m-d') ?>" id="start_date">
                  </div>  
                  <label for="inputEmail3" class="col-sm-1 control-label">End Date</label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control datepicker" name="end_date" value="<?= date('Y-m-d') ?>" id="end_date">
                  </div>                                     
                </div>  -->            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-1 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer" id="id_dealer">
                      <option value="all">All Dealers</option>
                      <?php 
                      $sql_dealer = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1 ORDER BY ms_dealer.id_dealer ASC");
                      foreach ($sql_dealer->result() as $isi) {
                        echo "<option value='$isi->id_dealer'>$isi->kode_dealer_md - $isi->nama_dealer</option>";
                      }
                       ?>
                    </select>
                  </div>
                  <div class="col-sm-2">
                    <button type="button" onclick="getReport()" name="process" value="edit" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-download"></i> Download .xls</button>                                                      
                  </div>                  
                </div>                
              </div><!-- /.box-body -->              
              <div class="box-footer">                                                              
                <div style="min-height: 600px">                 
                  <iframe style="overflow: auto; border: 0px solid #fff; width: 100%; height: 602px;margin-bottom: -5px;" id="showReport"></iframe>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    
    <?php }elseif ($set=='cetak') {
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Stok_per_no_mesin_dealer.xls");
header("Pragma: no-cache");
header("Expires: 0");
 ?>
    <!DOCTYPE html>
    <html>
    <!-- <html lang="ar"> for arabic only -->
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <title>Cetak</title>
      <style>
        @media print {
          @page {
            sheet-size: 297mm 210mm;
            margin-left: 0.8cm;
            margin-right: 0.8cm;
            margin-bottom: 1cm;
            margin-top: 1cm;
          }
          .text-center{text-align: center;}
          .bold{font-weight: bold;}
          .table {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
           /*border-collapse: separate;*/
          }
          .table-bordered tr td {
            border: 0.01em solid black;
            padding-left: 6px;
            padding-right: 6px;
          }
          body{
            font-family: "Arial";
            font-size: 11pt;
          }
          
        }
      </style>
    </head>
    <body>
	
	<?php
	
	$where_dealer ='';
 	if ($id_dealer!='all') {
            $where_dealer = "Where z.id_dealer = '$id_dealer'";
          }


	$get_pending_ssu = $this->db->query("
	select y.kode_dealer_md, y.nama_dealer, z.created_at as tgl_so, id_sales_order, no_spk, nama_konsumen, x.no_mesin,x.no_rangka, x.tipe_motor, x.warna, k.tahun_produksi as tahun  from (
		select tr_sales_order.id_dealer, id_sales_order, tr_sales_order.no_spk, tr_spk.nama_konsumen, no_mesin, tr_sales_order.created_at , tgl_cetak_invoice
		from tr_sales_order 
		join tr_spk on tr_sales_order.no_spk = tr_spk.no_spk
		where no_mesin in (select no_mesin from tr_scan_barcode where status = 5 ) and tgl_cetak_invoice is null
		UNION
		select a.id_dealer, a.id_sales_order_gc, a.no_spk_gc, c.nama_npwp as nama_konsumen, b.no_mesin, a.created_at, a.tgl_cetak_invoice
		from tr_sales_order_gc a join tr_sales_order_gc_nosin b on a.id_sales_order_gc = b.id_sales_order_gc
		join tr_spk_gc c on c.no_spk_gc = a.id_sales_order_gc
		where b.no_mesin in (select no_mesin from tr_scan_barcode where status = 5) and a.tgl_cetak_invoice is NULL
	)z join tr_scan_barcode x on z.no_mesin = x.no_mesin
	join ms_dealer y on y.id_dealer = z.id_dealer
	join tr_fkb k on x.no_mesin = k.no_mesin_spasi
	order by z.created_at ASC");
		
	if($get_pending_ssu->num_rows()>0){
	?>
	
	<div style="text-align: center;font-size: 13pt"><b>List Unit yang Belum Ter-SSU</b></div>        
	<table border="1">
		<tr>
			<th>Kode Dealer</th>
			<th>Nama Dealer</th>
			<th>Tgl Sales Order</th>
			<th>No Sales Order</th>
			<th>Nama Konsumen</th>
			<th>No Mesin</th>
			<th>No Rangka</th>
			<th>Tipe Motor</th>
			<th>Kode Warna</th>
			<th>Tahun</th>
		</tr>
	<?php
		foreach($get_pending_ssu->result() as $row) {   
	?>   
			<tr>
				<td>'<?php echo $row->kode_dealer_md;?></td>
				<td><?php echo $row->nama_dealer;?></td>
				<td><?php echo $row->tgl_so;?></td>
				<td><?php echo $row->id_sales_order;?></td>
				<td><?php echo $row->nama_konsumen;?></td>
				<td><?php echo $row->no_mesin;?></td>
				<td><?php echo $row->no_rangka;?></td>
				<td><?php echo $row->tipe_motor;?></td>
				<td><?php echo $row->warna;?></td>
				<td><?php echo $row->tahun;?></td>

			</tr>
	<?php
		}  
	?>

	</table>
	<?php
	}
	?>

      <br><br>

      <div style="text-align: center;font-size: 13pt"><b>Stok Per No. Mesin Dealer</b></div>        
      <table border="1">
        <thead>
          <tr>                  
            <th>Kode Dealer</th>                  
            <th>Dealer</th>
            <th>Tipe Motor</th>                  
            <th>Kode Warna</th>                  
            <th>Deskripsi Tipe</th>                  
            <th>No Mesin</th>
            <th>No Rangka</th>              
	          <th>Tahun</th>                                
            <th>Status</th> 
            <th>Status Stok</th>   
            <th>Tanggal DO</th>                                  
            <th>Aging Stok</th>                                  
          </tr>            
        </thead>
        <tbody>            
        <?php        
        $filter_dealer_penerimaan = '';
        $filter_dealer_unfill='';
        $filter_dealer_intransit='';
          if ($id_dealer!='all') {
            $filter_dealer_penerimaan = " AND tr_penerimaan_unit_dealer.id_dealer='$id_dealer'";
            $filter_dealer_unfill = "AND tr_do_po.id_dealer = '$id_dealer'";
            $filter_dealer_intransit = "AND tr_surat_jalan.id_dealer = '$id_dealer'";
          }
        $dt_pu = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.jenis_pu, tr_penerimaan_unit_dealer_detail.status_on_spk, d.tgl_surat, c.tgl_do, b.no_picking_list, c.no_do , tr_scan_barcode.warna, tr_fkb.tahun_produksi, tipe_motor,tipe_ahm,kode_dealer_md,nama_dealer,tr_penerimaan_unit_dealer.id_dealer,tr_scan_barcode.no_mesin,tr_scan_barcode.no_rangka,tr_scan_barcode.tipe,tr_scan_barcode.status,tr_penerimaan_unit_dealer_detail.fifo AS fifo_terima_dealer 
            FROM tr_penerimaan_unit_dealer 
            INNER JOIN tr_penerimaan_unit_dealer_detail ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer 
            INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
            INNER JOIN ms_dealer ON ms_dealer.id_dealer=tr_penerimaan_unit_dealer.id_dealer
            INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
            inner join tr_fkb on tr_fkb.no_mesin_spasi = tr_scan_barcode.no_mesin
            inner join tr_picking_list_view a on a.no_mesin = tr_penerimaan_unit_dealer_detail.no_mesin and tr_penerimaan_unit_dealer_detail.retur =0
            join tr_picking_list b on a.no_picking_list = b.no_picking_list 
            join tr_do_po c on c.no_do = b.no_do 
            join tr_surat_jalan d on d.no_surat_jalan = tr_penerimaan_unit_dealer.no_surat_jalan 
            WHERE tr_penerimaan_unit_dealer.status = 'close' AND tr_penerimaan_unit_dealer_detail.retur = 0 AND tr_scan_barcode.status not in (1,2,3,5) $filter_dealer_penerimaan GROUP BY tr_scan_barcode.no_mesin
            ORDER BY tr_penerimaan_unit_dealer_detail.fifo ASC");
        /*
        $dt_pu2 = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,tr_scan_barcode.warna, tipe_motor,tipe_ahm,kode_dealer_md,nama_dealer,tr_penerimaan_unit_dealer.id_dealer,tr_scan_barcode.no_mesin,tr_scan_barcode.no_rangka,tr_scan_barcode.tipe,tr_scan_barcode.status,tr_penerimaan_unit_dealer_detail.fifo AS fifo_terima_dealer 
                      FROM tr_penerimaan_unit_dealer 
                INNER JOIN tr_penerimaan_unit_dealer_detail ON 
                      tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer 
                INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                INNER JOIN ms_dealer ON ms_dealer.id_dealer=tr_penerimaan_unit_dealer.id_dealer
                INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
                      WHERE tr_penerimaan_unit_dealer.status = 'close' AND tr_penerimaan_unit_dealer_detail.retur = 0 AND tr_scan_barcode.status < 4
                      $filter_dealer_penerimaan
                      GROUP BY tr_scan_barcode.no_mesin
                      ORDER BY tr_penerimaan_unit_dealer_detail.fifo ASC");          */ 
        foreach($dt_pu->result() as $row) {                       
           if ($row->status != 5) {
                if($row->status == 4){
                  $status = "Ready";
                  if ($row->status_on_spk=='booking') {
                    $status = "Soft Booking";
                  }
                   if ($row->status_on_spk=='hard_book') {
                    $status = "Hard Booking";
                  }
                }elseif($row->status == 5){
                  $status = "Booking";
                }elseif($row->status == 6){
                  $status = "Retur to Dealer";
                }elseif($row->status == 7){
                  $status = "Retur to MD";                
                }else{
                  $status = $row->status;
                }

		// $get_info_do = $this->db->query("select a.no_mesin , a.retur , c.id_dealer, c.tgl_do, b.no_picking_list, c.no_do 
		// 			from tr_picking_list_view a 
		// 			join tr_picking_list b on a.no_picking_list = b.no_picking_list 
		// 			join tr_do_po c on c.no_do = b.no_do 
		// 			where no_mesin ='$row->no_mesin' and c.id_dealer = $row->id_dealer
		// ")->row();       
    $tanggal = $row->tgl_do;
    $tgl1 = strtotime($tanggal); 
    $tgl2 = strtotime(date("Y-m-d")); 

    $jarak = $tgl2 - $tgl1;

    $hari = $jarak / 60 / 60 / 24;
    $aging_stok = $hari;  
    // $tgl_surat_jalan = get_data('tr_surat_jalan','no_picking_list',$row->no_picking_list,'tgl_surat');
    
    $tgl_surat_jalan = $row->tgl_surat;
              echo "
              <tr>
                <td>'$row->kode_dealer_md</td>
                <td>$row->nama_dealer</td>
                <td>$row->tipe_motor</td>
                <td>$row->warna</td>
                <td>$row->tipe_ahm</td>
                <td>$row->no_mesin</td>
                <td>$row->no_rangka</td>     
                <td>$row->tahun_produksi</td>                    
                <td>";echo strtoupper($row->jenis_pu);
                echo "</td>                                    
                <td>$status</td> 
                <td>$row->tgl_do</td>  
                <td>$aging_stok</td>    
                <td>$tgl_surat_jalan</td>                              
              </tr>
              ";                      
           }
        }
        
        $cek_unfill = $this->db->query("SELECT ms_tipe_kendaraan.id_tipe_kendaraan, tr_scan_barcode.warna as id_warna, ms_tipe_kendaraan.tipe_ahm, ms_dealer.kode_dealer_md, ms_dealer.nama_dealer, tr_do_po.id_dealer, tr_scan_barcode.status, tr_scan_barcode.no_rangka, tr_do_po.tgl_do, tr_picking_list_view.id_item, tr_picking_list_view.no_mesin,tr_fkb.tahun_produksi
		            FROM tr_do_po INNER JOIN tr_picking_list ON tr_do_po.no_do = tr_picking_list.no_do 
                INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
                INNER JOIN tr_picking_list_view ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list AND tr_do_po_detail.id_item = tr_picking_list_view.id_item
                inner join tr_fkb on tr_fkb.no_mesin_spasi = tr_picking_list_view.no_mesin  
                join tr_scan_barcode on tr_scan_barcode.no_mesin  = tr_picking_list_view.no_mesin 
                join ms_dealer on ms_dealer.id_dealer = tr_do_po.id_dealer
                JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
                left join tr_surat_jalan_detail on tr_surat_jalan_detail.no_mesin  = tr_picking_list_view.no_mesin and tr_picking_list_view.retur =0 -- and tr_surat_jalan_detail.ceklist!='ya'
                WHERE tr_scan_barcode.status !=5 and (tr_surat_jalan_detail.no_surat_jalan is null or tr_surat_jalan_detail.ceklist ='tidak')
    -- WHERE tr_picking_list_view.no_mesin NOT IN (SELECT a.no_mesin FROM tr_scan_barcode a join tr_surat_jalan_detail b on b.no_mesin = a.no_mesin WHERE a.status in (2,3) and b.retur = 0 AND b.ceklist = 'ya')
                $filter_dealer_unfill AND tr_do_po_detail.qty_do > 0 AND tr_do_po.status = 'approved' AND tr_picking_list_view.retur = 0");
                  //WHERE tr_picking_list.no_picking_list NOT IN (SELECT tr_surat_jalan.no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL)
        foreach($cek_unfill->result() as $isi) {     
            // $row = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$isi->no_mesin)->row();
            if(isset($isi->status) AND $isi->status != 4) { 
              // $dl = $this->db->get_where('ms_dealer',['id_dealer'=>$isi->id_dealer])->row();
              // $tp = $this->db->query("SELECT ms_tipe_kendaraan.id_tipe_kendaraan, id_warna, tipe_ahm FROM ms_item
              //       JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=ms_item.id_tipe_kendaraan
              //       WHERE id_item='$isi->id_item'
              //       ")->row();       
		// $get_info_do = $this->db->query("select a.no_mesin , a.retur , c.id_dealer, c.tgl_do, b.no_picking_list, c.no_do 
		// 			from tr_picking_list_view a 
		// 			join tr_picking_list b on a.no_picking_list = b.no_picking_list 
		// 			join tr_do_po c on c.no_do = b.no_do 
		// 			where no_mesin ='$row->no_mesin' and c.id_dealer = $isi->id_dealer
		// ")->row();         
    $tanggal = $isi->tgl_do;
    $tanggal = new DateTime($tanggal); 

    $sekarang = new DateTime();

    $perbedaan = $tanggal->diff($sekarang);
    $aging_stok = $perbedaan->d;   
    $tgl_surat_jalan = '-';         
              $status = "Unfill";
              echo "
              <tr>
                <td>'$isi->kode_dealer_md</td>
                <td>$isi->nama_dealer</td>
                <td>$isi->id_tipe_kendaraan</td>
                <td>$isi->id_warna</td>
                <td>$isi->tipe_ahm</td>
                <td>$isi->no_mesin</td>
                <td>$isi->no_rangka</td>   
                <td>$isi->tahun_produksi</td>                   
                <td>";echo strtoupper($row->tipe);
                echo "</td>                                                      
                <td>$status</td>   
                <td>$isi->tgl_do</td>                 
                <td>$aging_stok</td>    
                <td>$tgl_surat_jalan</td>                         
              </tr>
              ";
           }
        }

        $cek_in = $this->db->query("SELECT tr_surat_jalan.id_dealer, tr_scan_barcode.id_item, tr_scan_barcode.tipe, tr_scan_barcode.no_mesin, tr_scan_barcode.no_rangka, tr_fkb.tahun_produksi  
              FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
              INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
              inner join tr_fkb on tr_fkb.no_mesin_spasi = tr_surat_jalan_detail.no_mesin      
              WHERE tr_surat_jalan_detail.terima is null and  tr_surat_jalan_detail.ceklist != 'tidak' and tr_surat_jalan_detail.retur = 0 
              -- WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan IS NOT NULL AND status = 'close') 
              -- AND tr_surat_jalan_detail.retur = 0 AND tr_surat_jalan_detail.ceklist = 'ya'
              $filter_dealer_intransit");
        foreach($cek_in->result() as $row) {                       
           if ($row->status < 4) {
              $dl = $this->db->get_where('ms_dealer',['id_dealer'=>$row->id_dealer])->row();
              $tp = $this->db->query("SELECT * FROM ms_item
                    JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=ms_item.id_tipe_kendaraan
                    WHERE id_item='$row->id_item'
                    ")->row();
          
		$get_info_do = $this->db->query("select a.no_mesin , a.retur , c.id_dealer, c.tgl_do, b.no_picking_list, c.no_do 
					from tr_picking_list_view a 
					join tr_picking_list b on a.no_picking_list = b.no_picking_list 
					join tr_do_po c on c.no_do = b.no_do 
					where no_mesin ='$row->no_mesin' and c.id_dealer = $row->id_dealer
		")->row();   
    $tanggal = $get_info_do->tgl_do;
    
    $tgl1 = strtotime($tanggal); 
    $tgl2 = strtotime(date("Y-m-d")); 

    $jarak = $tgl2 - $tgl1;

    $hari = $jarak / 60 / 60 / 24;
    $aging_stok = $hari;  
    $tgl_surat_jalan = get_data('tr_surat_jalan','no_picking_list',$get_info_do->no_picking_list,'tgl_surat');

		$status = "Intransit";
          
              echo "
              <tr>
                <td>'$dl->kode_dealer_md</td>
                <td>$dl->nama_dealer</td>
                <td>$tp->id_tipe_kendaraan</td>
                <td>$tp->id_warna</td>
                <td>$tp->tipe_ahm</td>
                <td>$row->no_mesin</td>
                <td>$row->no_rangka</td>                       
                <td>$row->tahun_produksi</td>                   
                <td>";echo strtoupper($row->tipe);
                echo "</td>                                    
                <td>$status</td>      
                <td>$get_info_do->tgl_do</td>                         
                <td>$aging_stok</td> 
                <td>$tgl_surat_jalan</td>                                   
              </tr>
              ";
           }
        }        
        ?>
        </tbody>                  
      </table>
    </body>
  </html>
  <?php } ?>

  </section>
</div>


<script>
    function getReport()
    {
      var value={id_dealer:document.getElementById("id_dealer").value,
                cetak:'cetak',
                }

      if (value.tipe == '') {
        alert('Isi data dengan lengkap ..!');
        return false;
      }else{
        //alert(value.tipe);
        $('.loader').show();
        $('#btnShow').disabled;
        $("#showReport").attr("src",'<?php echo site_url("h1/stok_per_nosin_dealer?") ?>tipe='+value.tipe+'&cetak='+value.cetak+'&start_date='+value.start_date+'&end_date='+value.end_date+'&id_dealer='+value.id_dealer);
        document.getElementById("showReport").onload = function(e){          
        $('.loader').hide();       
        };
      }
    }
</script>