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
    <li class="">Data Claim</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php
    if($set=="view"){
    ?>

    <div class="box box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
            <!-- <a href="dealer/pembayaran_claim/" class="btn bg-primary btn-flat margin"><i class="fa fa-arrow-left"></i> Kembali</a> -->
            <!-- <a href="dealer/pembayaran_claim/" class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Histoy</a> -->
          <h3 class="box-title">
          </h3>
          </a>          
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
          <table id="claim_header">
            <thead>
              <tr  valign="top" id="myExemple">              
                <th width="5%" style="position: sticky; top: 0; z-index: 1;" >No</th>            
                <th >Sales Program </th>
                <th >Kategori Program</th>
                <th >Nama Dealer</th>
                <th>No Juklak MD</th>
                <th >Periode Program</th>      
                <th >Total Pengajuan Claim</th>     
                <th >Jumlah Unit di Aprove</th>         
                <th >Jumlah Unit Reject</th>     
                <th >Status</th>
                <th >Aksi</th>
              </tr>
            </thead>
            <tbody  class="table table-bordered table-hover"  width="100%" style="overflow: auto; height: 100px;">   
            <?php
                    $no = 1;
                    foreach ($pembayaran_claim_dealer as $row) {
                      $jumlah_claim =   $row->total_reject + $row->total_approve ;
                      if ($row->status=='close'){
                        // $kontribusi_ahm = $row->total_kontribusi_ahm;
                        // $kontribusi_md = $row->total_kontribusi_md;
                        // $kontribusi_d = $row->total_kontribusi_d;
                        $status = $row->status;
                        $button = '<span class="label label-default">close</span>';
                      }else{
                        $kontribusi_ahm = NULL;
                        $kontribusi_md  = NULL;
                        $kontribusi_d   = NULL;
                        $status         = NULL;
                        $button = '<span class="label label-default">on Process</span> ';
                      }
                      
                      ?>
                        <tr>
                        <td><?= $nomors=$no++;?></td>   
                        <td> <a href='dealer/monitoring_claim_dealer/detail?id=<?=$row->id_program_md?>'><?=$row->id_program_md?></a> </td>
                        <td><?= strtoupper($row->tipe_program)?></td>   
                        <td><?= $row->nama_dealer?></td>  
                        <td>
                        <?  if ($row->no_juklak_md=='' || $row->no_juklak_md==NULL){ echo "-";}else{echo $row->no_juklak_md;} ?>
                        <td><?php  if (isset($row->periode_awal)) {echo $row->periode_awal." - ". $row->periode_akhir ;} ?> <?php if (isset($row->priode_program)) {echo $row->priode_akhir ;} ?></td>    
                        <td><?=$jumlah_claim?></td>    
                        <td><?=$row->total_approve?></td>    
                        <td><?=$row->total_reject?></td>    
                      
                        <td>
                          <?  
                           $button='';
                          if ($row->approve_from_dealer=='' || $row->approve_from_dealer==NULL): ?>
                            <span class="label label-default">
                              on Process
                            </span>  
                            <?php
                              $button .= '<a data-toggle="tooltip" title="Approve"  onclick="return confirm(\'Are you sure you want to Approve this item?\');"  class="btn btn-primary btn-sm btn-flat" href="dealer/monitoring_claim_dealer/approve_reject_from_dealer?id='.$row->id_program_md.'&&status=approved "><i class="fa fa-check" aria-hidden="true"></i></a>';
                              $button .= '<a data-toggle="tooltip" title="Reject"   onclick="return confirm(\'Are you sure you want to Reject this item?\');"    class="btn btn-danger btn-sm btn-flat"  href="dealer/monitoring_claim_dealer/approve_reject_from_dealer?id='.$row->id_program_md.'&&status=rejected"><i class="fa fa-ban" aria-hidden="true"></i></a>';
                            ?>
                          
                            <? elseif ($row->approve_from_dealer!==NULL):  ?>
                              <span class="label label-success tooltiptext">
                                Approve Claim From Dealer
                            </span>  
                            <? endif; ?>
                        </td>
                        
                        
                        <td>
                          <?=$button?>
                        </td>
                       
                        </tr>
                    <?php
                    }
                    ?>
            </tbody>
          
          </table>
        </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }

    else if($set=="detail_claim"){
      ?>

      <div class="box hide-box">
        <div class="box-header with-border">
          <h3 class="box-title">
          <a href="dealer/pembayaran_claim" class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Kembali</a>        
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

<div class="box-body">
        <div class="row">
          <form class="form-horizontal">
            <div class="box-body">
              <div class="form-group">
                <label class="col-sm-2 control-label">Tanggal Created</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" value="<?=$header_claim->tgl_transaksi_claim?>" readonly>
                </div>
                <label class="col-sm-2 control-label">Nama Dealer</label>
                <div class="col-sm-3">
                  <input type="text" readonly class="form-control" value="<?=$header_claim->nama_dealer?>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Priode Program</label>
                <div class="col-sm-3">
                  <input type="text" readonly class="form-control" value="<?=$header_claim->priode_program?>">
                </div>
                <label class="col-sm-2 control-label">Status</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" value="<?=$header_claim->status?>" readonly>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Nama Bank</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control"
                    value="<?php 	if (!empty($header_claim->id_bank)) {echo $header_claim->bank ;}else {echo 'Tidak dipilih';}?>"
                    readonly>
                </div>
                <label class="col-sm-2 control-label">Include Pajak</label>
                <div class="col-sm-3">
                  <input type="text" readonly class="form-control" value="<?php if ($header_claim->include_ppn=='1'){echo 'Include Pajak';}else if ($header_claim->include_ppn=='0'){echo 'Tidak';}else{echo 'Tidak Dipilih';}?>">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">Tipe Program</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" value="<?=$header_claim->tipe_program ?>" readonly>
                  </div>
                  <label class="col-sm-2 control-label">Send To Dealer</label>
                  <div class="col-sm-3">
                  <input type="text" class="form-control" value="<?=$header_claim->send_payment_created?>" readonly>
                </div>
              </div>
            </div><!-- /.box-body -->

          </form>
        </div>
      </div>

<div  class="tableFixHead">
  <table class="table table-bordered table-claim" id="stable-claim"  width="100%" >
    <thead>
      <tr  valign="top" id="myExemple">              
        <th width="5%" >No</th>            
        <th >Program MD</th>      
        <!-- <th >ID Claim Dealer</th>       -->
        <th >Sub Kategori Program</th>         
        <th >Jumlah Unit Aprove</th>         
        <th >Jumlah Unit Reject</th>          
        <th >Kontribusi AHM</th>           
        <th >Kontribusi MD</th>           
        <th >Kontribusi D</th>      
        <th >Total Kontribusi AHM</th>  
        <th >Total Kontribusi MD</th>  
        <th >Total Kontribusi D</th>   
        <th >Total Pembayaran

        </th>        
        <!-- <th >Kontribusi Other</th>            -->
      </tr>
    </thead>
    <tbody>   

    <?php if (empty($detail_claim)) : ?>
      <tr colspan="4">
          <td> Data Tidak Ditemukan</td>
      </tr>
     <?php endif; ?>
     <?php 
     $no = 1;
     $approve = 0;
     $reject = 0;
     $tot_ahm = 0;
     $tot_md = 0;
     $tot_d = 0;
     $tot_ahm_sum = 0;
     $tot_md_sum = 0;
     $tot_d_sum = 0;
     $tot_d_bayar = 0;
     
     if (!empty($detail_claim)) : ?>

  <?php
          foreach ($detail_claim as $row) {?>
              <tr>
              <td><?= $no++;?></td>    
              <td>a <?=$row->id_program_md?></td>    
              <td><?=$row->sub_kategori_program?></td>    
              <!-- <td><?php //$row->id_claim_dealer?></td>     -->
              <td><?php $approve +=$row->tot_approve; echo $row->tot_approve ?></td>    
              <td><?php $reject +=$row->tot_reject; echo $row->tot_reject ?></td>    
              <td><?php $tot_ahm +=$row->kontribusi_ahm; echo number_format($row->kontribusi_ahm)?></td>    
              <td><?php $tot_md +=$row->kontribusi_md; echo number_format($row->kontribusi_md)?></td>    
              <td><?php $tot_d += $row->kontribusi_d; echo number_format($row->kontribusi_d)?></td>    
              <td><?php $tot_ahm_sum += $row->total_kontribusi_ahm; echo number_format($row->total_kontribusi_ahm)?></td>    
              <td><?php $tot_md_sum += $row->total_kontribusi_md; echo number_format($row->total_kontribusi_md)?></td>    
              <td><?php $tot_d_sum += $row->total_kontribusi_d; echo number_format($row->total_kontribusi_d)?></td>    
              <td></td> 
            </tr>
          <?php
          }
          ?>
            <?php endif; ?>
            </tbody> 
              <tfoot >
                <tr>
                  <td colspan="2"></td>
                  <td><b>Total</b></td>
                  <td><b><?=number_format($approve)?></b></td>
                  <td><b><?=number_format($reject)?></b></td>
                  <td><b><?=number_format($tot_ahm)?></b></td>
                  <td><b><?=number_format($tot_md)?></b></td>
                  <td><b><?=number_format($tot_d)?></b></td>
                  <td><b><?=number_format($tot_ahm_sum)?></b></td>
                  <td><b><?=number_format($tot_md_sum)?></b></td>
                  <td><b><?=number_format($tot_d_sum)?></b></td>
                  <td><b><?=number_format($tot_d_bayar)?></b></td>
                  <td class="hide-input-sm-footer"></td>
                </tr> 
              </tfoot >
            </table>      
            </div>

        </form>
         
          </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
  
      <?php
      }
    elseif($set=="detail"){
    ?>

<div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/sales_program/add">    
          <h3 class="box-title">
            <a href="dealer/monitoring_claim_dealer" class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Kembali</a>
        </h3>
          </a>          
                    
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
        <div style="overflow-x:auto;">
          <table id="example2" class="table table-bordered table-hover" width="100%">
            <thead>
              <tr>              
                <th width="5%">No</th>            
                <th>No SPK</th> 
                <th>No SO</th> 
                <th>No Mesin</th> 
                <!-- <th>AHM Contr.</th> 
                <th>MD Contr.</th> 
                <th>Dealer Contr.</th>  -->
                <!-- <th>Finco Conrt.</th>  -->
                <th>Status Claim</th> 
                <!-- <th>Action</th> -->
              </tr>
            </thead>
            <tbody>            
            <?php
            $no=1; 
            foreach($temp_data as $row) {  ?>
              <tr>
                <td><?= $no++;?></td>                           
                <td><?=$row->no_spk?></td>              
                <td><?=$row->id_sales_order?></td>   
                <td><?=$row->no_mesin_spk?></td>   
                <?php /*
                <td><?=$row->kontribusi_ahm?></td>
                <td><?=$row->kontribusi_md?></td>
                <td><?=$row->kontribusi_dealer?></td>
                <td><?=$row->kontribusi_dealer?></td> */?>
                <td><?=$row->status?></td>
                <!-- <td>
                  <a data-toggle='tooltip' title="Detail" class='btn btn-primary btn-sm btn-flat' href='h1/claim_md_internal_payment/detail?id=<?php // echo $row->id_program_md ?>'><i class='fa fa-eye'></i></a>
                </td> -->
              </tr>
            <?php
           
            }
            ?>
            </tbody>
          </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->


 
      <?php 
      }
   ?>
  </section>
</div>


<script>
$( document ).ready(function() {
var ex2 = $('#claim_header').DataTable({
      "lengthChange": true,
      "ordering": true,
      "info": true,
      "searching": true,
      "paging": true,
      fixedHeader: true,
      "lengthMenu": [
        [10, 25, 50, 75, 100, -1],
        [10, 25, 50, 75, 100, "All"]
      ],
      "autoWidth": true
    });
});
</script>




