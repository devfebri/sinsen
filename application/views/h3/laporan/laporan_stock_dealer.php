<?php 
$no = date('d/m/y_Hi');
// header("Content-type: application/octet-stream");
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan Stock Dealer.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
  <html>

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Cetak</title>
    <style>
      @media print {
        @page {
          sheet-size: 330mm 210mm;
          margin-left: 0.8cm;
          margin-right: 0.8cm;
          margin-bottom: 1cm;
          margin-top: 1cm;
        }

        .text-center {
          text-align: center;
        }

        .bold {
          font-weight: bold;
        }

        .table {
          width: 100%;
          max-width: 100%;
          border-collapse: collapse;
          /*border-collapse: separate;*/
        }

        .table-bordered tr td {
          border: 0.01em solid black;
          padding-left: 5px;
          padding-right: 3px;
        }

        body {
          font-family: "Arial";
          font-size: 10pt;
        }
      }
    </style>
  </head>

  <body>
    <table>
      <tr>
        
      </tr>
    </table>
    <div style="text-align: center;font-size: 13pt"><b>Laporan Stock Dealer </b></div>
    <div style="text-align: center;font-size: 13pt"><b><?= $nama_dealer->nama_dealer ?></b></div>
    <div style="text-align: right;font-size: 11pt">Dicetak  : <?=$_SESSION['nama']?> - <?=tgl_indo(date('Y-m-d H:i:s'))?> <?=date('H:i:s')?></div>
    <hr>
  
    <table class="table table-bordered" border=1>
        <tr>
            <th>No</th>
            <th>Part Number</th>
            <th>Description</th>
            <th>Rak</th>
            <th>Qty</th>
            
            <th>Harga Beli</th>
            <th>Jumlah</th>
            <th>Harga Jual</th>
            <th>Jumlah</th>
            <th>Kel. Produk</th>
            <th>Rank</th>
            <th>Status</th>
        </tr>
        <?php 
        $ci =& get_instance();
        $ci->load->model('h3_dealer_stock_model');
     
        $no=1;
        $sum_beli=array();
        $disk=0;
        $sum_jual=array();
        foreach($details as $rows){
            $disk_tertentu = $this->db->query("select count(a.diskon_reguler) as ds,b.tipe_diskon,a.diskon_reguler from ms_h3_md_diskon_part_tertentu_items a 
            join ms_h3_md_diskon_part_tertentu b on b.id=a.id_diskon_part_tertentu where b.id_part='$rows->id_part' and a.id_dealer ='$rows->id_dealer'");
            $diskonitem = $this->db->query("SELECT count(diskon_reguler) as dsk, diskon_reguler,tipe_diskon FROM ms_h3_md_diskon_part_tertentu where id_part='$rows->id_part'")->row();
            $diskon_dealer = $this->db->query("SELECT diskon_reguler,tipe_diskon FROM ms_dealer where id_dealer='$rows->id_dealer'")->row()->diskon_reguler;
            $diskon_dealer2 = $this->db->query("SELECT diskon_reguler,tipe_diskon FROM ms_dealer where id_dealer='$rows->id_dealer'")->row()->tipe_diskon;
            // $qty_booking = $ci->h3_dealer_stock_model->qty_book($dealer,$rows->id_part,$rows->id_gudang,$rows->id_rak,$sql = false);
           
            if($disk_tertentu->row()->ds == 0){
                $disk =  $diskonitem->diskon_reguler;
            }
            
            if($disk_tertentu->row()->ds == 0 and  $diskonitem->dsk == 0){
                $disk = $diskon_dealer;
            }
            
            if($disk_tertentu->row()->ds != 0){
                $disk = $disk_tertentu->row()->diskon_reguler;
            }
            
            if($diskonitem->dsk == 1){
                $disk = $diskonitem->diskon_reguler;
            }
            
            
            
            // $hetKurangDiskon = ($diskonitem->tipe_diskon == 'Persen' || $diskon_dealer2 =='Persen' ) ? (($rows->harga_dealer_user * $disk) / 100) :  $disk ;
           if($rows->kelompok_vendor =="FED.OIL"){
                $hetKurangDiskon = $disk;
           }else{
               
               if($diskonitem->tipe_diskon == "Persen" && $rows->kelompok_vendor !="FED.OIL" ){
                   $hetKurangDiskon = (($rows->harga_dealer_user * $disk) / 100);
               }else{
                   $hetKurangDiskon = $disk;
               }
               
                if($disk_tertentu->row()->tipe_diskon == "Persen" && $rows->kelompok_vendor !="FED.OIL" ){
                   $hetKurangDiskon = (($rows->harga_dealer_user * $disk) / 100);
               }else{
                   $hetKurangDiskon = $disk;
               }
                if($diskon_dealer2 == "Persen" && $rows->kelompok_vendor !="FED.OIL" ){
                   $hetKurangDiskon = (($rows->harga_dealer_user * $disk) / 100);
               }else{
                   $hetKurangDiskon = $disk;
               }
           }
           
           
          
            
            $het =$rows->harga_dealer_user - $hetKurangDiskon; 
            $harga =($het * $rows->stock);
            $sum_beli[] = intval($harga);
            $sum_jual[] = intval($rows->jumlah_jual);
        ?>
        <tr>
           <td style="text-align:center"><?=$no++?></td> 
           <td style="text-align:left">&nbsp;<?=$rows->id_part?></td> 
           <td><?=$rows->nama_part?></td> 
           <td><?=$rows->id_rak?></td> 
           <td style="text-align:center"><?=$rows->stock?></td> 
        
           <td style="text-align:right"><?=number_format($het,0,',','.')?></td> 
           <td style="text-align:right"><?=number_format($harga,0,',','.')?></td> 
           <td style="text-align:right"><?=number_format($rows->harga_dealer_user,0,',','.')?></td> 
           <td style="text-align:right"><?=number_format($rows->jumlah_jual,0,',','.')?></td> 
           <td><?=$rows->kelompok_part?></td> 
           <td style="text-align:center"><?=$rows->rank?></td> 
           <td style="text-align:center"><?=$rows->status?></td> 
        </tr>
        
        <?php } ?>
        <tr>
            <th colspan="6" style="text-align:right;">Total :</th>
            <th><?=number_format(array_sum($sum_beli),0,',','.')?></th>
            <th></th>
            <th style="text-align:right"><?=number_format(array_sum($sum_jual),0,',','.')?></th>
            <th colspan="4"></th>
        </tr>
    </table>
  </body>
   
  </html>
