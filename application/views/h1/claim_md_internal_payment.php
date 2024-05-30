<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 30px;  
  padding-left: 5px;
  padding-right: 5px;  
  margin-right: 0px; 
}
.isi_combo{   
  height: 30px;
  border:1px solid #ccc;
  padding-left:1.5px;
}
.hide{
	display: none;
}
</style>

<body >
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Business Control</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
  
    <?php
    if($set=="view"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <div class="box-tools pull-left">
          <a href="h1/claim_md_internal_payment" class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Refresh</a>
          </div>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php                       
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
        ?>                  
        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>
        </div>
        <?php
        }
            $_SESSION['pesan'] = '';                        
                
        ?>
        <table id="table_claim_md_internal_payment" class="table table-bordered table-hover">
          <thead>
          <tr>              
                <th width="5%">No</th>            
                <th>ID Program MD</th> 
                <th>Deskripsi Program</th>             
                <th>Periode Awal</th>
                <th>Periode Akhir</th>      
                <th>Total Approval</th>      
                <th>Total Reject</th>      
                <th>Total Sisa Stock</th>      
                <th>Total Kontribusi AHM</th>         
                <th>Total Kontribusi MD</th>           
                <th>Total Kontribusi Dealer</th>           
              </tr>
          </thead>
          <tbody>   
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    

<script>
  $( document ).ready(function() {
   tabless = $('#table_claim_md_internal_payment').DataTable({
	      "scrollX": true,
        "processing": true, 
        "bDestroy": true,
        "serverSide": true, 
        "order": [],
        "ajax": {
          "url": "<?php  echo site_url('h1/claim_md_internal_payment/fetch_data_claim_md_internal_payment')?>",
            "type": "POST"
        },  
              
        "columnDefs": [
        {
            "targets": [ 0,5 ],
            "orderable": false, 
        },
        ],
        });
});
</script>


    <?php
    }elseif($set=="detail"){
    ?>

<div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
        <a href="h1/claim_md_internal_payment" class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Kembali</a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php                       
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
        ?>                  
        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>
        </div>
        <?php
        }
            $_SESSION['pesan'] = '';                        
        ?>

      <style>
      .tableFixHead          { overflow: auto; }
      .tableFixHead thead,tfoot th { position: sticky; top: 0; z-index: 1;    box-shadow: 0 2px 4px 0 rgba(0,0,0,.2);}
       table                 { border-collapse: collapse; width: 100%;   }
       .tableFixHead tfoot {position:sticky; background-color:#eeeeee; }
       tfoot {bottom:0;   font-weight: bold;   box-shadow: 0 2px 4px 0 rgba(0,.2,0,0);}
      </style>


<div style="overflow-x:auto;">
  <div  class="tableFixHead" >
  <table  class="table table-bordered table-hover">
        <thead >
        <trvalign="top" >              
                <th width="5%">No</th>            
                <th>Nama Dealer</th> 
                <th>No Juklak MD</th>             
                <th>Sub kategori program</th>
                <th>No sales id</th>      
                <th>Series Motor</th>      
                <th>Jumlah Unit di Aprove</th>         
                <th>Jumlah Unit Reject</th>        
                <th>Sisa Stok</th>     
                <th>Kontribusi AHM</th>           
                <th>Kontribusi MD</th>           
                <th>Kontribusi D</th>           
                <th>Total Kontribusi AHM</th>           
                <th>Total Kontribusi MD</th>           
                <th>Total Kontribusi D</th>           
                <th>Total Reject</th>           
                <th>Total Sisa Stok</th>           
                <th>Total Pembayaran D ke MD</th>           
              </tr>
            </thead>
            <tbody>   
            <?php  
                 $no=1; 
                 $tot_approve =0;
                 $tot_reject=0;
                 $sisa_stock=0;
                 $tot_ahm =0;
                 $tot_m = 0;
                 $tot_d = 0;
                 $full_tot_ahm = 0;
                 $ful_tot_md = 0;
                 $ful_tot_d =0;
                 $full_tot_reject = 0;
                 $tot_sisa = 0;
                 $tot_pembayaran = 0;
                 $ful_total_kontribusi_ahm=0;
                 $ful_total_kontribusi_md=0;
                 $ful_total_kontribusi_d=0;
     
            foreach($temp_data as $val)
             {?>
            <tr>
              <td><?= $no++;?></td>    
              <td><?=$val->nama_dealer?></td>     
                <td><?=$val->no_juklak_md?></td>     
                <td><?=$val->jenis_sales_program?></td>  
                <td><?=$val->id_program_md?></td>     
                <td><?=$val->series_motor?></td>
                <td class="approve_td"><?=$val->status_approved?></td>
                <td class="reject_td"> <?=$val->status_reject?></td>
                <td class="sisa_stock" ><?= $stock=$val->status_ajukan  ?></td>
                <td class="kontribusi_ahm_td"><?=number_format($val->kontribusi_ahm)?></td>     
                <td class="kontribusi_md_td"><?=number_format($val->kontribusi_md)?></td>     
                <td class="kontribusi_d_td"><?=number_format($val->kontribusi_dealer)?></td> 
                <td class="full_kontribusi_ahm_td"><?php echo number_format($ful_total_kontribusi_ahm = $val->status_approved * $val->kontribusi_ahm) ?></td>  
                <td class="full_kontribusi_md_td"> <?php echo number_format($ful_total_kontribusi_md= $val->status_approved * $val->kontribusi_md) ?></td>     
                <td class="full_kontribusi_d_td"> <?php echo number_format($ful_total_kontribusi_d = $val->status_approved * $val->kontribusi_dealer) ?></td> 
                <td class="full_tot_reject_td"><?php echo number_format( $sum_total_reject=(($val->kontribusi_ahm + $val->kontribusi_md + $val->kontribusi_dealer) *  $val->status_reject)) ?></td> 
                <td class="full_tot_sisa_stock_td"><?php echo number_format( $total_stock_full=(($val->kontribusi_ahm + $val->kontribusi_md + $val->kontribusi_dealer) *  $stock)) ?></td>
                <td class="tot_bayar_d_ke_md_td"><?php echo number_format( $totalbayar= (($val->kontribusi_ahm + $val->kontribusi_md + $val->kontribusi_dealer) *  $val->status_reject) + ($val->status_approved * $val->kontribusi_md)) ?></td>
                <?php
                  $tot_approve += $val->status_approved;
                  $tot_reject += $val->status_reject;
                  $sisa_stock += $stock;
                  $tot_ahm += $val->kontribusi_ahm;
                  $tot_m += $val->kontribusi_md;
                  $tot_d += $val->kontribusi_dealer;
                  $full_tot_ahm += $ful_total_kontribusi_ahm;
                  $ful_tot_md += $ful_total_kontribusi_md;
                  $ful_tot_d += $ful_total_kontribusi_d;
                  $full_tot_reject += $sum_total_reject;
                  $tot_sisa += $total_stock_full;
                  $tot_pembayaran += $totalbayar;
                ?>
              </tr>
             <?php
             }
             ?> 
            </tbody>
            <tfoot >

            <trvalign="top" >   
            <td colspan="5"></td>
                <td><b>Total</b></td>
                <td><?php echo $tot_approve; ?></td>
                <td><?php echo $tot_reject; ?></td>
                <td><?php echo $sisa_stock; ?></td>
                <td ><?php echo number_format($tot_ahm);?></td>
                <td ><?php echo number_format($tot_m);?></td>
                <td ><?php echo number_format($tot_d);?></td>
                <td ><?php echo number_format($full_tot_ahm);?></td>
                <td ><?php echo number_format($ful_tot_md);?></td>
                <td ><?php echo number_format($ful_tot_d);?></td>
                <td ><?php echo number_format($full_tot_reject);?></td>
                <td ><?php echo number_format($tot_sisa);?></td>
                <td ><?php echo number_format($tot_pembayaran);?></td>
            </tr>
         </tfoot>

        </table>
        </div>
        </div>
      </div><!-- /.box-body -->
      <div class="box-body">
      </div><!-- /.box -->
    </div><!-- /.box -->


  <?php 
  }

 ?>
  </section>
</div>





