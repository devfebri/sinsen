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
<base href="<?php echo base_url(); ?>" />
<?php if(isset($_GET['id'])){ ?>
  <body >
<?php }else{ ?>
  <body >
<?php } ?>
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
          <a href="h1/klaim_unit_dealer" class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Kembali</a>
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
                <th>Total Gantung</th>      
              </tr>
          </thead>
          <tbody>   
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }else if ($set=="history"){
    ?>


    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <div class="box-tools pull-left">
          <a href="h1/monitoring_claim_md_internal_payment" class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Kembali</a>
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
        <table id="table_claim_md_internal_payment_history" class="table table-bordered table-hover">
          <thead>
          <tr>              
                <th width="5%">No</th>            
                <th>ID Program MD</th> 
                <th>Deskripsi Program</th>             
                <th>Periode Awal</th>
                <th>Periode Akhir</th>      
                <th>Total Approval</th>      
                <th>Total Reject</th>      
                <th>Total Gantung</th>      
              </tr>
          </thead>
          <tbody>   
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php

    }elseif($set=="detail"){
    ?>

<div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
        <a href="h1/monitoring_claim_md_internal_payment" class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Kembali</a>
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
  <table  class="table table-bordered table-hover"  id="tabel-data">
        <thead >
        <trvalign="top" >              
                <th width="5%">No</th>            
                <th>Nama Dealer</th> 
                <th>No Juklak MD</th>             
                <th>Sub kategori program</th>
                <th>No Sales ID</th>      
                <th>Series Motor</th>      
                <th>Jumlah Unit di Aprove</th>         
                <th>Jumlah Unit Reject</th>        
                <th>Jumlah Gantung</th>     
                <th>Status</th>                 
                <th>Aksi</th>                 
              </tr>
            </thead>
            <tbody>   
            <?php  
                 $no=1; 
                 $tot_approve =0;
                 $tot_reject=0;
                 $sisa_stock=0;
        
            foreach($temp_data as $val)
             {?>
            <tr>
              <td><?= $no++;?></td>    
              <td> <a href=""><?=$val->nama_dealer?></a></td>     
                <td><?=$val->no_juklak_md?></td>     
                <td><?=$val->jenis_sales_program?></td>  
                <td><?=$val->id_program_md?></td>     
                <td><?=$val->series_motor?></td>
                <td class="approve_td"><?=$val->status_approved?></td>
                <td class="reject_td"> <?=$val->status_reject?></td>
                <td class="sisa_stock" ><?= $stock=$val->status_ajukan  ?></td>
              </td>
               
                <td>
                  <?php 
                  if ($val->status_validasi== ''){echo 'On Process';} else if ($val->status_validasi== 'close'){echo 'Closed';}else if ($val->status_validasi== 'reject'){echo 'Reject';}
                  
                  if ($val->send_dealer==NULL){ ?>

                 <? }else{ ?>
                    <button class='btn bg-success btn-flat'><i class="fa fa-send" aria-hidden="true"></i></button>
                 <?}


                  ?></td>
                   
                <td> 
                  <form action="h1/klaim_unit_dealer/proses_klaim" method="post">
                    <input type="hidden" name="id_claim"      value="<?=$val->id_claim_sp?>">
                    <input type="hidden" name="sales_program" value="<?=$val->id_program_md?>">
                    <input type="hidden" name="id_dealer" value="<?=$val->id_dealer?>">
                    
                    <button type="submit"   name="process" value="approve"  class='btn bg-blue btn-flat'  onclick="return confirm('Are you sure to approve this data?')" ><i class="fa fa-check"></i></button>
                    <?php 
                    if ($val->send_dealer==NULL){ ?>
                      <button type="submit"   name="process" value="send_dealer" title="Send to Dealer"   onclick="return confirm('Are you sure to send to dealer this data?')" class='btn bg-maroon btn-flat'  href='h1/klaim_unit_dealer/generate_claim_to_md?id_dealer=<?php echo $val->id_dealer ?> && sales_program=<?=$val->id_program_md?> '><i class="fa fa-send" aria-hidden="true"></i></button>
                   <? }
                    ?>
                  </form>


                <!-- <? if ($val->status_validasi== '' &&  !$val->status_ajukan >= 1 ): ?>
                  <a data-toggle='tooltip' title="Close"   onclick="return confirm('Are you sure to approve this data?')" class='btn btn-primary btn-flat' href='h1/klaim_unit_dealer/approve_status_testing?id_claim=<?php echo $val->id_claim_sp ?> && sales_program=<?=$val->id_program_md?> '><i class="fa fa-check" aria-hidden="true"></i></a>
                  <a data-toggle='tooltip' title="Send to Dealer"   onclick="return confirm('Are you sure to send to dealer this data?')" class='btn bg-maroon btn-flat'  href='h1/klaim_unit_dealer/generate_claim_to_md?id_dealer=<?php echo $val->id_dealer ?> && sales_program=<?=$val->id_program_md?> '><i class="fa fa-send" aria-hidden="true"></i></a>
                  <? else: ?>
                <? endif; ?> -->

              </td>
                <?php
                  $tot_approve += $val->status_approved;
                  $tot_reject += $val->status_reject;
                  $sisa_stock += $stock;
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
                <td></td>
                <td></td>
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


  <script>
    $( document ).ready(function() {
        

    tabless = $('#table_claim_md_internal_payment').DataTable({
          "scrollX": true,
          "processing": true, 
          "bDestroy": true,
          "serverSide": true, 
          "order": [],
          "ajax": {
            "url": "<?php  echo site_url('h1/klaim_unit_dealer/fetch_data_claim_md_internal_payment')?>",
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

<script>
    $(document).ready(function(){
        $('#tabel-data').DataTable();
    });
</script>


