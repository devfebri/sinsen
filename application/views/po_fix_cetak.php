<html>
       <head>
           <title>PO Fix</title>
           <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
           <link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"> 
           <style>
               .word-table {
                   /* border:1px solid black !important;  */
                   border-collapse: collapse !important;
                   width: 100%;
               }
               .word-table tr th{
                   border:1px solid black !important;  
                   /*padding:-5px 0px; */
               }
               .word-table th{
                   border:1px solid black !important; 
                   background-color:transparent;
                   
               }

               .word-table tr td{
                /*padding:-8px 3px; */
                text-align:center;
                font-family:'Roboto', sans-serif;
                font-size:10px;
                font-weight:normal;
               }

               .word-table2{
                border-collapse: collapse !important;
                   width: 100%;
                   font-size:9px;
                   font-family:'Roboto', sans-serif;
               }
               .word-table2 tr th{
                   border:1px solid black !important;  
                  
               }
               .word-table2 th{
                   border:1px solid black !important; 
                   background-color:transparent;
                   /*padding:-8px 0px; */
                   
               }
               .word-table3{
                /* border-collapse: collapse !important; */
                   width: 100%;
                   font-size:8px;
                   font-family:'Roboto', sans-serif;
               }
               .word-table3 tr th{
                   /* border:1px solid black !important;   */
                  
               }
               .word-table3 th{
                   /* border:1px solid black !important;  */
                   background-color:transparent;
                   /*padding:-8px 0px; */
                   
               }
           </style>
       </head>
       <body>
           <table >
               <tr>
                    <th></th>
                    <th width="1300px" style="text-align:center;border:1px solid black;"><h3 style="font-family: 'Roboto', sans-serif;font-size:12px">FORM ORDER FIX ORDER</h3></th>
                    <th></th>
               </tr>
           </table>
           <br>
      
          
           <table style="margin-left:180px">
               <tr>
                    <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">NO. PO</p></th>
                    <th width="78px;">&nbsp;</th>
                    <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">:</p></th>
                    <th width="200px;" style="text-align:left; font-family: 'Roboto', sans-serif;font-size:10px"><p><?php echo $data['po_id']?></p></th>
                    <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">DEALER</p></th>
                    <th width="80px;">&nbsp;</th>
                    <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">:</p></th>
                    <th width="200px;" style="text-align:left; font-family: 'Roboto', sans-serif;font-size:10px"><p><?=$data['nama_dealer']?></p></th>
               </tr>
           </table>
           <table style="margin-left:180px">
               <tr>
                    <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">TGL PO</p></th>
                    <th width="78px;">&nbsp;</th>
                    <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">:</p></th>
                    <th width="200px;" style="text-align:left; font-family: 'Roboto', sans-serif;font-size:10px"><p><?=strtoupper(tgl_indo($data['tanggal_order']))?></p></th>
                    <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">KODE DEALER</p></th>
                    <th width="48px;">&nbsp;</th>
                    <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">:</p></th>
                    <th width="200px;" style="text-align:left; font-family: 'Roboto', sans-serif;font-size:10px"><p><?=$data['kode_dealer_ahm']?></p></th>
               </tr>
           </table>
           <table style="margin-left:180px">
               <tr>
                    <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">JENIS ORDER</p></th>
                    <th width="48px;">&nbsp;</th>
                    <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">:</p></th>
                    <th width="200px;" style="text-align:left; font-family: 'Roboto', sans-serif;font-size:10px"><p><?=$data['po_type']?></p></th>
                    <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">ALAMAT DEALER</p></th>
                    <th width="35px;">&nbsp;</th>
                    <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">:</p></th>
                    <th width="200px;" style="text-align:left; font-family: 'Roboto', sans-serif;font-size:10px"><p><?=substr($data['alamat'],0,30)?></p></th>
               </tr>
           </table>
           <table style="margin-left:180px">
               <tr>
                    <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">PERIODE</p></th>
                    <th width="70px;">&nbsp;</th>
                    <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">:</p></th>
                    <th width="200px;" style="text-align:left; font-family: 'Roboto', sans-serif;font-size:10px"><p><?php 
                    $bulan = substr($data['tanggal_order'],5,2);
                    if($bulan =="01"){
                        echo "Januari";
                    }elseif($bulan =="02"){
                         echo "Februari";
                    }
                    elseif($bulan =="03"){
                         echo "Maret";
                    }elseif($bulan =="04"){
                         echo "April";
                    }elseif($bulan =="05"){
                         echo "Mei";
                    }elseif($bulan =="06"){
                         echo "Juni";
                    }elseif($bulan =="07"){
                         echo "Juli";
                    }elseif($bulan =="08"){
                         echo "Agustus";
                    }elseif($bulan =="09"){
                         echo "September";
                    }elseif($bulan =="10"){
                         echo "Oktober";
                    }elseif($bulan =="11"){
                         echo "November";
                    }elseif($bulan =="12"){
                         echo "Desember";
                    }
                    ?></p></th>
                    <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">PEMBAYARAN</p></th>
                    <th width="50px;">&nbsp;</th>
                    <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">:</p></th>
                    <th width="200px;" style="text-align:left; font-family: 'Roboto', sans-serif;font-size:10px"><p>CASH</p></th>
               </tr>
           </table>
           <table style="margin-left:180px">
               <tr>
                    <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">PRODUK</p></th>
                    <th width="70px;">&nbsp;</th>
                    <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">:</p></th>
                    <th width="200px;" style="text-align:left; font-family: 'Roboto', sans-serif;font-size:10px"><p><?=strtoupper($data['produk'])?></p></th>
                    <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">SALESMAN</p></th>
                    <th width="67px;">&nbsp;</th>
                    <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">:</p></th>
                    <th width="200px;" style="text-align:left; font-family: 'Roboto', sans-serif;font-size:10px"><p>-</p></th>
               </tr>
           </table>
           <br>
           <table class="word-table">
               <tr>
                <th width="20px;" rowspan="2"><p style="font-family: 'Roboto', sans-serif;font-size:10px">NO</p></th>
                <th width="150px;" rowspan="2"><p style="font-family: 'Roboto', sans-serif;font-size:10px">NOMOR PARTS</p></th>
                <th width="150px;" rowspan="2"><p style="font-family: 'Roboto', sans-serif;font-size:10px">NAMA PARTS</p></th>
                <th width="35px;" rowspan="2"><p style="font-family: 'Roboto', sans-serif;font-size:10px">QTY</p></th>
                <th width="50px;" rowspan="2"><p style="font-family: 'Roboto', sans-serif;font-size:10px">Het</p></th>
                <th width="50px;" rowspan="2"><p style="font-family: 'Roboto', sans-serif;font-size:10px">Discount</p></th>
                <th width="90px;" rowspan="2"><p style="font-family: 'Roboto', sans-serif;font-size:10px">Kel.parts</p></th>
                <th width="80px;" rowspan="2"><p style="font-family: 'Roboto', sans-serif;font-size:10px">Simpart/Non Simpart</p></th>
                <th width="80px;" rowspan="2"><p style="font-family: 'Roboto', sans-serif;font-size:10px">Qty Min. Sim</p></th>
                <th width="50px;" rowspan="2"><p style="font-family: 'Roboto', sans-serif;font-size:10px">Stok AVS</p></th>
                <th width="200px;" colspan="6"><p style="font-family: 'Roboto', sans-serif;font-size:10px">Penjualan Dealer 6 Bulan Sebelumnya</p></th>
                <th width="80px;" rowspan="2"><p style="font-family: 'Roboto', sans-serif;font-size:10px">Average Sales</p></th>
                <th width="50px;" rowspan="2"><p style="font-family: 'Roboto', sans-serif;font-size:10px">Suggest Order</p></th>
                <th width="50px;" rowspan="2"><p style="font-family: 'Roboto', sans-serif;font-size:10px">Adjust Order</p></th>
                <th width="100px;" rowspan="2"><p style="font-family: 'Roboto', sans-serif;font-size:10px">Total Amount</p></th>
               </tr>
               <tr>
                   <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">W-1</p></th>
                   <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">W-2</p></th>
                   <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">W-3</p></th>
                   <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">W-4</p></th>
                   <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">W-5</p></th>
                   <th><p style="font-family: 'Roboto', sans-serif;font-size:10px">W-6</p></th>
                
               </tr>
               <tbody>
             <?php $number=1;  
               $total=0;
               $period_array=array();
               foreach($sparepart as $rows):?>
                <?php $diskonDealer = $this->db->get_where('ms_dealer',array('id_dealer'=>$data['id_dealer']))->row();?>
                <?php $diskonDealertoDecimal= ($diskonDealer->diskon_fixed_order / 100)?>
               <?php $harga_neto = $rows->harga_dealer_user - ($rows->harga_dealer_user * $diskonDealertoDecimal);?>
              
                <tr>
                    <td><p><?php echo $number?></p></td>
                    <td><p style="text-align:left"><?php echo $rows->id_part?></p></td>
                    <td><p style="text-align:left"><?php echo $rows->nama_part?></p></td>
                    <td><p><?php echo $rows->kuantitas?></p></td>
                    <td><p><?php echo number_format($rows->harga_dealer_user,0,',','.')?></p></td>
                    <td><p><?php echo number_format($rows->harga_dealer_user * $diskonDealertoDecimal,0,',','.')?></p></td>
                    <td><p><?php echo $rows->kelompok_part?></p></td>
                    <td><p><?php echo $rows->simpart?></p></td>
                    <td><p><?php echo $rows->minimal_order?></p></td>
                    <td><p><?php echo number_format($rows->stock,0,',','.')?></p></td>
                    <td><p><?php echo $rows->w1?></p></td>
                    <td><p><?php echo $rows->w2?></p></td>
                    <td><p><?php echo $rows->w3?></p></td>
                    <td><p><?php echo $rows->w4?></p></td>
                    <td><p><?php echo $rows->w5?></p></td>
                    <td><p><?php echo $rows->w6?></p></td>
                    <td><p><?php echo $rows->avg_six_weeks?></p></td>
                    <td><p><?php echo $rows->suggested_order?></p></td>
                    <td><p><?php echo $rows->adjusted_order?></p></td>
                    <td><p><?php echo number_format($rows->tot_harga_part,0,',','.')?></p></td>
                </tr>
              <?php $number++;   $period_array[] = intval($rows->tot_harga_part); endforeach;?>
               </tbody>
           </table>
           <br>
           <br>
           <table class="word-table2">
               <tr>
                <th colspan="3" style="text-align:right;" width="610px"><p style="margin-right:20px;">TOTAL</p></th>
                <th width="120px" style="text-align:center;font-size:10px;"><p style="margin-left:10px;">Rp. <?=number_format($total=array_sum($period_array),0,'.','.')?></p></th>
               </tr>
               <tr>
                <th><p>Dibuat Oleh,</p></th>
                <th colspan="2"><p>Diketahui Oleh,</p></th>
                <th><p>Disetujui Oleh,</p></th>
               </tr>
               <tr>
                <th style="padding-top:20px">
                    <br>
                    <br>
                    <p style="margin-bottom:'-100px' !important; font-weight:normal">Nama :</p>
                </th>
                 <th style="padding-top:20px">
                    <br>
                    <br>
                    <p style="margin-bottom:'-100px' !important; font-weight:normal">Nama :</p>
                </th>
              <th style="padding-top:20px">
                    <br>
                    <br>
                    <p style="margin-bottom:'-100px' !important; font-weight:normal">Nama :</p>
                </th>
             <th style="padding-top:20px">
                    <br>
                    <br>
                    <p style="margin-bottom:'-100px' !important; font-weight:normal">Nama :</p>
              </th>
               </tr>
               <tr>
                <th><p>Counter Parts</p></th>
                <th><p>Kepala Bengkel</p></th>
                <th><p>PIC Dealer</p></th>
                <th><p>Pimpinan / Owner</p></th>
               </tr>
           </table>
           <!--<table class="word-table3">-->
           <!--    <tr>-->
           <!--     <th colspan="2" style="text-align:left;font-family:'Roboto',sans-serif;font-size:10px;text-decoration:underline"><p>Catatan :</p></th>-->
           <!--    </tr>-->
           <!--    <tr style="margin-top:-35px">-->
           <!--     <th style="text-align:left;font-family:'Roboto',sans-serif;font-size:9px;font-weight:normal;"><p>1. </p></th>-->
           <!--     <th style="text-align:left;font-family:'Roboto',sans-serif;font-size:9px;font-weight:normal;"><p style="margin-left:-20px"> Barang yang diorder oleh Petugas Parts Dealer / AHASS wajib diketahui oleh Ka. Bengkel / Spv. Spareparts dan disetujui oleh PIC Dealer </p></th>-->
           <!--    </tr>-->
           <!--    <tr style="margin-top:-35px">-->
           <!--     <th style="text-align:left;font-family:'Roboto',sans-serif;font-size:9px;font-weight:normal;"><p>2. </p></th>-->
           <!--     <th style="text-align:left;font-family:'Roboto',sans-serif;font-size:9px;font-weight:normal;"><p style="margin-left:-20px"> Barang yang diorder bersifat Fast Moving dan Slow Moving </p></th>-->
           <!--    </tr>-->
           <!--    <tr style="margin-top:-35px">-->
           <!--     <th style="text-align:left;font-family:'Roboto',sans-serif;font-size:9px;font-weight:normal;"><p>3. </p></th>-->
           <!--     <th style="text-align:left;font-family:'Roboto',sans-serif;font-size:9px;font-weight:normal;"><p style="margin-left:-20px"> Petugas Parts Dealer wajib membuat dan mengirimkan PO (Purchase Order) secara regular Min. 2 kali yaitu setiap tanggal (01 dan 15)</p></th>-->
           <!--    </tr>-->
           <!--    <tr style="margin-top:-35px">-->
           <!--     <th style="text-align:left;font-family:'Roboto',sans-serif;font-size:9px;font-weight:normal;"><p>4. </p></th>-->
           <!--     <th style="text-align:left;font-family:'Roboto',sans-serif;font-size:9px;font-weight:normal;"><p style="margin-left:-20px">Form pemesanan (PO) antara parts dan oli harus dibuat secara terpisah dan form ini dapat diperbanyak dengan cara fotocopy</p></th>-->
           <!--    </tr>-->
           <!--    <tr style="margin-top:-35px">-->
           <!--     <th style="text-align:left;font-family:'Roboto',sans-serif;font-size:9px;font-weight:normal;"><p>5. </p></th>-->
           <!--     <th style="text-align:left;font-family:'Roboto',sans-serif;font-size:9px;font-weight:normal;"><p style="margin-left:-20px">Form order ini berlaku sampai tanggal 03 setiap bulan-nya, apabila tidak terpenuhi sampai tanggal yang ditetapkan maka PO dianggap tidak berlaku (Komunikasi terlebih dahulu ke MAIN DEALER)</p></th>-->
           <!--    </tr>-->
           <!--</table>-->
       </body>
     </html>