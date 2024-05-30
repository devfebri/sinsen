<style type="text/css">
  .myTable1 {
    margin-bottom: 0px;
  }

  .myt {
    margin-top: 0px;
  }

  .isi {
    height: 30px;
    padding-left: 5px;
    padding-right: 5px;
    margin-right: 0px;
  }

  .isi_combo {
    height: 30px;
    border: 1px solid #ccc;
    padding-left: 1.5px;
  }

  .hide {
    display: none;
  }
</style>
<base href="<?php echo base_url(); ?>" />
<?php if(isset($_GET['id'])){ ?>

<body>
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
        <div class="box box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h1/pembayaran_claim_dealer/add" class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</a>
              <a href="h1/pembayaran_claim_dealer/history" class="btn bg-yellow btn-flat margin"><i class="fa fa-eye"></i> History</a>
              <a href="h1/pembayaran_claim_dealer/dealer" class="btn bg-green btn-flat margin"><i class="fa fa-eye"></i> Dealer</a>
              <a href="h1/rep_sales_program/aksi" class="btn bg-primary btn-flat margin"><i class="fa fa-eye"></i> Contoh Kwitansi</a>
       

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
            <div class="tableFixHead">
              <table id="claim_header" class="table table-bordered table-hover" style="overflow: auto; height: 100px;">
                <thead>
                  <tr valign="top" id="myExemple">
                    <th width="5%" style="position: sticky; top: 0; z-index: 1;">No</th>
                    <th>Tanggal Created</th>
                    <th>No Transaksi Claim</th>
                    <th>Nama Dealer</th>
                    <th>Periode Program</th>
                    <th>Jumlah Unit di Aprove</th>
                    <th>Jumlah Unit Reject</th>
                    <th>Total Kontribusi AHM</th>
                    <th>Total Kontribusi MD</th>
                    <th>Total Kontribusi D</th>
                    <th>Total Reject </th>
                    <th>Total Pembayaran</th>
                    <th>Tanggal Pencairan</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 


                    $no = 1;
                    foreach ($pembayaran_claim_dealer as $row) {?>
                    
                  <tr>
                    <td><?= $nomors=$no++;?></td>
                    <td><?=$row->tgl_transaksi_claim?></td>
                    <td><a
                        href="/h1/pembayaran_claim_dealer/detail_claim?id=<?=$row->id_claim_generate_payment?>"><?=$row->id_claim_generate_payment?></a>
                    </td>
                    <td><?=$row->nama_dealer?> . <?php echo '('.$row->id_dealer.')'?></td>
                    <td><?=$row->priode_program?></td>
                    <td><?=$row->total_approve?></td>
                    <td><?=$row->total_reject?></td>
                    <td><?=number_format($row->total_kontribusi_ahm)?></td>
                    <td><?=number_format($row->total_kontribusi_md)?></td>
                    <td><?=number_format($row->total_kontribusi_d)?></td>
                    <td><?=number_format($row->total_full_reject)?></td>
                    <td><?=number_format($row->total_pembayaran)?></td>
                    <td><?=$row->tgl_pencairan_md_ke_d?></td>
                    <td><?=$row->status?></td>
                    <td>
                      <div class="row">
                        <? if ($row->status=='input'): ?>
                        <a href="h1/pembayaran_claim_dealer/send_notify?id_generate=<?=$row->id_claim_generate_payment?>"
                          class="btn btn-primary btn-xs btn-flat" hidden><i class="fa fa-paper-plane"></i> Send
                          Notify</a>
                        <? elseif ($row->status=='send'): ?>
                        <a href="h1/pembayaran_claim_dealer/claim_approve?id_generate=<?=$row->id_claim_generate_payment?>"
                          class="btn btn-info btn-xs btn-flat"
                          onclick="return confirm('Are you sure to approve this data?')"><i class="fa fa-check"></i>
                          Approve </a>
                       
                        <? endif; ?>
                      </div>
                    </td>
                  </tr>
                  <?php
                    }

                    ?>
                </tbody>
              </table>
            </div>
          </div>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    } ?>

<?php
    if($set=="view_dealer"){
    ?>

    <div class="box box box-default">
      <div class="box-header with-border">
            <?  if ($container=='index' ): ?>
            <a href="dealer/pembayaran_claim/" class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Kembali</a>
            <a href="dealer/pembayaran_claim/history" class="btn bg-yellow btn-flat margin"><i class="fa fa-eye"></i> History</a>
            <? else:  ?>
              <a href="dealer/pembayaran_claim/" class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Kembali</a>
            <? endif; ?>
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
          <div  class="tableFixHead">
          <table id="claim_header">
            <thead>
              <tr  valign="top" id="myExemple">              
                <th width="5%" style="position: sticky; top: 0; z-index: 1;" >No</th>       
                <th>Tanggal Created</th>
                <th>No Transaksi</th>
                <th >Jumlah Unit di Aprove</th>         
                <th >Jumlah Unit Reject</th>     
                <th >Total Kontribusi AHM</th>           
                <th >Total Kontribusi MD</th>           
                <th >Total Kontribusi D</th>  
                <th >Total Pembayaran</th>           
                <th >Tanggal Pencairan</th>
                <th >Status</th>
                <th >Action</th>
              </tr>
            </thead>
       
            <tbody  class="table table-bordered table-hover"  width="100%" style="overflow: auto; height: 100px;">   
            <?php
                    $no = 1;
                    foreach ($pembayaran_claim_dealer as $row) {
                      // $row->status=='input' || 
                      if ($row->status=='approved' || $row->status=='send' ){
                        $kontribusi_ahm = $row->total_kontribusi_ahm;
                        $kontribusi_md = $row->total_kontribusi_md;
                        $kontribusi_d = $row->total_kontribusi_d;
                        $total_bayar = $row->total_pembayaran;
                        $status = $row->status;
                      }else{
                        $kontribusi_ahm = NULL;
                        $kontribusi_md  = NULL;
                        $kontribusi_d   = NULL;
                        $total_bayar    = NULL;
                        $status         = NULL;
                      }
                      ?>
                        <tr>
                        <td><?= $nomors=$no++;?></td>   
                        <td><?= $row->tgl_transaksi_claim?></td>    
                        <td><a href="/dealer/pembayaran_claim/detail_claim?id=<?=$row->id_claim_generate_payment?>"><?=$row->id_claim_generate_payment?></a></td>    
                        <td><?= $row->total_approve?></td>    
                        <td><?=$row->total_reject?></td>    
                        <td><?=number_format($kontribusi_ahm )?></td>    
                        <td><?=number_format($kontribusi_md)?></td>
                        <td><?=number_format($kontribusi_d)?></td>
                        <td><?=number_format($total_bayar)?></td>  
                        <td><?=$row->tgl_pencairan_md_ke_d?>
                      </td> 
                        <td><?php if($row->status=='send'){echo '<span class="label label-primary">Send</span>';}else{ echo '<span class="label label-default">'.$row->status.'</span>';} ?></td>
                       
                        <td>
                        <?  if ($row->status=='input' && $container =='index'): ?>
                          <a href="dealer/pembayaran_claim/claim_approve?id_generate=<?=$row->id_claim_generate_payment?>" class="btn btn-success btn-xs btn-flat"  onclick="return confirm('Are you sure to approve this data?')"><i class="fa fa-check"></i>Confirm</a>
                            <? endif; ?>
                        </td>    
                        
                        </tr>
                    <?php
                    }
                    ?>
            </tbody>
          
          </table>
          </div>
        </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    } ?>



    <?php
    if($set=="history"){
    ?>

    <div class="box box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/pembayaran_claim_dealer/" class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i>
            Kembali</a>
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
        <div class="tableFixHead">
          <table id="claim_header" class="table table-bordered table-hover" style="overflow: auto; height: 100px;">
            <thead>
              <tr valign="top" id="myExemple">
                <th width="5%" style="position: sticky; top: 0; z-index: 1;">No</th>
                <th>Tanggal Created</th>
                <th>No Transaksi Claim</th>
                <th>Nama Dealer</th>
                <th>Periode Program</th>
                <th>Jumlah Unit di Aprove</th>
                <th>Jumlah Unit Reject</th>
                <th>Total Kontribusi AHM</th>
                <th>Total Kontribusi MD</th>
                <th>Total Kontribusi D</th>
                <th>Total Reject </th>
                <th>Jenis Pembayaran</th>
                <th>Total Pembayaran </th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
                    $no = 1;
                    foreach ($pembayaran_claim_dealer as $row) {?>
              <tr>
                <td><?= $nomors=$no++;?></td>
                <td><?=$row->tgl_transaksi_claim?></td>
                <td><a
                    href="/h1/pembayaran_claim_dealer/detail_claim?id=<?=$row->id_claim_generate_payment?>"><?=$row->id_claim_generate_payment?></a>
                </td>
                <td><?=$row->nama_dealer?> . <?php echo '('.$row->id_dealer.')'?></td>
                <td><?=$row->priode_program?></td>
                <td><?=$row->total_approve?></td>
                <td><?=$row->total_reject?></td>
                <td><?=number_format($row->total_kontribusi_ahm)?></td>
                <td><?=number_format($row->total_kontribusi_md)?></td>
                <td><?=number_format($row->total_kontribusi_d)?></td>
                <td><?=number_format($row->total_full_reject)?></td>
                <td><?=number_format($row->total_pembayaran)?></td>
                <td><?=number_format($row->tgl_pencairan_md_ke_d)?></td>
                <td><?=$row->status?></td>
               
              </tr>
              <?php
                    }
                    ?>
            </tbody>
          </table>
        </div>
      </div>
    </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }
    else if($set=="add"){
    ?>
    <div class="onload">

      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h1/pembayaran_claim_dealer" class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View
              Data
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

          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" id='form_sp' action="h1/pembayaran_claim_dealer/add" method="post"
                enctype="multipart/form-data">
                <div class="box-body">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label  class="col-sm-4 control-label">Nama Dealer *</label>
                      <div class="col-sm-8">
                        <select name="id_dealer" class="form-control select2" required>
                          <option value="">- Pilih Nama Dealer-</option>
                          <?php
                            foreach($dealer as $row) { ?>
                          <option <?php if ($row->kode_dealer_ahm == set_value('id_dealer') ) { echo 'selected'; }?>
                            value="<?=$row->kode_dealer_ahm?>"><?=$row->nama_dealer?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label  class="col-sm-4 control-label">No. Sales ID</label>
                      <div class="col-sm-8">
                        <select name="no_sales_id" class="form-control select2 "
                          value="<?php echo set_value('no_sales_id'); ?>" >
                          <option value="">- Pilih sales ID -</option>
                          <?php
                            foreach($sales_program as $row) {  
                              ?>
                          <option <?php if ($row->id_program_md == set_value('no_sales_id') ) { echo 'selected'; }?>
                            value="<?=$row->id_program_md?>">
                            <?php echo $row->id_program_md." | ".$row->judul_kegiatan." | ".$row->jenis_sales_program." ( ".$row->periode_awal."  -  ".$row->periode_akhir.")"  ?>
                          </option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-4 control-label">Type Program *</label>
                      <div class="col-sm-8">
                        <select class="form-control" name="tipe_program" aria-label="Default select example" value="<?php echo set_value('tipe_program'); ?>"  id="select_tipe_program" required>
                          <option value="">- Jenis -</option>
                          <option value="all" <?php if ('all'== set_value('tipe_program') ) { echo 'selected'; }?>>All
                          <option value="scp" <?php if ('scp'== set_value('tipe_program') ) { echo 'selected'; }?>>SCP
                          <option value="dg" <?php  if ('dg'== set_value('tipe_program') ) { echo 'selected'; }?>>DG
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-4 control-label">Priode Program *</label>
                      <div class="col-sm-8">
                        <div class="row">
                          <div class="col-sm-6">
                            <input type="text" name="tanggal_awal" value="<?php echo set_value('tanggal_awal'); ?>"class="form-control" id="tanggal3" autocomplete="off" placeholder="Tanggal Priode Awal" required>
                          </div>
                          <div class="col-sm-6">
                            <input type="text" name="tanggal_akhir" value="<?php echo set_value('tanggal_akhir'); ?>"class="form-control" id="tanggal4" autocomplete="off" placeholder=" Tanggal Priode Akhir" required>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-4 control-label">No Juklak MD</label>
                      <div class="col-sm-8">
                        <select name="no_juklak" class="form-control select2"
                          value="<?php echo set_value('no_juklak'); ?>">
                          <option value="">- No Juklak MD -</option>
                          <?php
                            foreach($juklak as $row) {  
                              ?>
                          <option <?php if ($row->no_juklak_md == set_value('no_juklak') ) { echo 'selected'; }?>
                            value="<?=$row->no_juklak_md?>"><?=$row->no_juklak_md?></option>
                          <?php } ?>
                        </select>

    
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-4 control-label">Nama Bank</label>
                      <div class="col-sm-6">
                        <select class="form-control select2" name="id_bank" value="<?php echo set_value('id_bank'); ?>">
                          <option value="">- Jenis -</option>
                          <?php
                            foreach($bank as $row) {  ?>
                          <option <?php if ($row->id_bank == set_value('id_bank') ) { echo 'selected'; }?>
                            value="<?=$row->id_bank?>"><?=$row->bank?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-4 control-label">Include PPN</label>
                      <div class="col-sm-6">
                        <input type="radio" name="setting_ppn" value="1" id="radio_setting_ppn_yes"
                          <?php if (set_value('setting_ppn')== '1'){echo 'checked';}else{echo '';} ?>> Ya
                      </div>
                      <div class="col-sm-6">
                        <input type="radio" name="setting_ppn" value="0"  id="radio_setting_ppn_no"
                          <?php if (set_value('setting_ppn')== '0'){echo 'checked';}else{echo '';} ?> required>Tidak
                      </div>
                    </div>
                  </div>

                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-8">
                  </div>
                  <div class="col-sm-4">
                    <button type="submit" id="select" name="generate" value="generate" class="btn btn-info btn-flat"><i
                        class="fa fa-save"></i> Generate</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->

      <style>
        .tableFixHead {
          overflow: auto;
        }

        .tableFixHead thead,
        tfoot th {
          position: sticky;
          top: 0;
          z-index: 1;
          box-shadow: 0 2px 4px 0 rgba(0, 0, 0, .2);
        }

        table {
          border-collapse: collapse;
          width: 100%;
        }

        .tableFixHead tfoot {
          position: sticky;
          background-color: #eeeeee;
        }

        tfoot {
          bottom: 0;
          font-weight: bold;
          box-shadow: 0 2px 4px 0 rgba(0, .2, 0, 0);
        }

        .hide-input {
          border-color: inherit;
          -webkit-box-shadow: none;
          box-shadow: none;
          border: none;
          width: 60px;
        }

        .hide-input-sm {
          border-color: inherit;
          -webkit-box-shadow: none;
          box-shadow: none;
          border: none;
          width: 30px;
        }

        .hide-input-lg {
          border-color: inherit;
          -webkit-box-shadow: none;
          box-shadow: none;
          border: none;
          width: 120px;
        }

        .hide-input-md {
          border-color: inherit;
          -webkit-box-shadow: none;
          box-shadow: none;
          border: none;
          width: 100px;
        }

        .hide-input-sm-footer {
          border-color: inherit;
          -webkit-box-shadow: none;
          box-shadow: none;
          border: none;
          background-color: #eeeeee;
          width: 70px;
        }

        .hiden-checkbox,
        input[type=checkbox].hidden {
          display: none;
        }
      </style>


      <?php  if (isset($set_generate)) {?>

      <div class="box hide-box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h1/sales_program/add">
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
          <form action="/h1/pembayaran_claim_dealer/save"  method="post" multi>

          <?php  if (!empty($temp_data)) { ?>
          <div class="tableFixHead">

            <table class="table table-bordered table-claim" id="table-claim" width="100%">
              <thead>
                <tr valign="top" id="myExemple">
                  <th width="5%">No</th>
                  <th>Nama Dealer</th>
                  <th>No Juklak MD</th>
                  <th>Sub kategori program</th>
                  <th>No Sales ID</th>
                  <th>Series Motor</th>
                  <th>Jumlah Unit di Aprove</th>
                  <th>Jumlah Unit Reject</th>
                  <th>Jumlah Gantung</th>
                  <th>Kontribusi AHM</th>
                  <th>Kontribusi MD</th>
                  <th>Kontribusi D</th>
                  <th>Total Kontribusi AHM</th>
                  <th>Total Kontribusi MD</th>
                  <th>Total Kontribusi D</th>
                  <th>Total Reject</th>
                  <th>Total Sisa Stok</th>
                  <th>Total Pembayaran 
                    <?php  if ($tipe_program=='scp' ) {echo 'MD ke D';} else if ($tipe_program =='dg' ) {echo 'D ke MD';} ?>
                  </th>
                  <th>Include PPN </th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="add_row_manual_input">
                <!-- <form action="/h1/pembayaran_claim_dealer/save" name="Form" onsubmit="return validateForm()" method="post" multi> -->
                  <?php if (empty($temp_data)) : ?>
                  <tr>
                    <td colspan="17" align="center"> Data Tidak Ditemukan</td>
                  </tr>
                  <?php elseif (!empty($temp_data)): ?>
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
                  $totalbayar=0;
                  foreach($temp_data as $val) 
                  { 
                    ?>
                    <?php 
                      $program = $val->jenis_sales_program; 
                      $check_program = getPPN(0.1, $val->periode_awal);

                      if($setting_ppn=='0'){
                        if ($program == 'SCP' || $program == 'Segmented'  || $program == 'ROTI'  && $setting_ppn=='0'){
                          $check_program = getPPN(1.1, $val->periode_awal);  
                          $include_ppn = 0;
                          $get_kontribusi_ahm = $val->kontribusi_ahm / $check_program; 
                          $get_kontribusi_md = $val->kontribusi_md / $check_program; 
                          $get_kontribusi_d = $val->kontribusi_dealer / $check_program; 
                        } else if ($program == 'Direct Gift' ){
                          $check_program = getPPN(1.1, $val->periode_awal);  
                          $include_ppn = 0;
                          $get_kontribusi_ahm = $val->kontribusi_ahm / $check_program; 
                          $get_kontribusi_md = $val->kontribusi_md / $check_program; 
                          $get_kontribusi_d = $val->kontribusi_dealer / $check_program; 
                        }
                 
                      }  

                      else{
                        $include_ppn = 1;
                        $get_kontribusi_ahm = $val->kontribusi_ahm;
                        $get_kontribusi_md  = $val->kontribusi_md;
                        $get_kontribusi_d   = $val->kontribusi_dealer;
                        $check_program      = NULL;

                      }
                    ?>
                  <tr>
                    <td>
                      <input type="text" class="hide-input-sm" value=" <?= $nomors=$no++;?>" readonly>
                    </td>
                    <td>
                      <input type="text" name="id_claim[]" value="<?=$val->id_claim?>" readonly hidden>
                      <input type="checkbox" name="nama_dealer[]" value="<?=$val->id_dealer?>" class="hiden-checkbox">
                      <input type="checkbox" name="nama_dealer_detail[]" value="<?=$val->dealer_detail?>" class="hiden-checkbox"><?=$val->nama_dealer?>
                    </td>
                    <td><input type="checkbox" name="no_juklak[]"           value="<?=$val->no_juklak_md?>"         class="hiden-checkbox"readonly><?=$val->no_juklak_md?></td>
                    <td><input type="checkbox" name="jenis_sales_program[]" value="<?=$val->jenis_sales_program?>"  class="hiden-checkbox" readonly><?=$val->jenis_sales_program?></td>
                    <td><input type="checkbox" name="id_program_md[]"       value="<?=$val->id_program_md?>"        class="hiden-checkbox" readonly><?=$val->id_program_md?></td>
                    <td><input type="checkbox" name="series_motor[]"        value="<?=$val->series_motor?>"         class="hiden-checkbox" readonly><?=$val->series_motor?></td>
                    <td><input type="checkbox" name="approve_name[]"        value="<?= $val->status_approved?>"     class="hiden-checkbox approve_name" onchange="totalapprove()"><?= $val->status_approved?></td>
                    <td><input type="checkbox" name="reject_name[]"         value="<?=$val->status_reject?>"        class="hiden-checkbox reject_name" onchange="totalreject()"><?=$val->status_reject?></td>
                    <td><input type="checkbox" name="sisa_stock[]"          value="<?= $stock=$val->status_ajukan?>"class="hiden-checkbox sisa_stock" onchange="sisastockpending()"><?=$val->status_ajukan?></td>
                    <td><input type="checkbox" name="kontribusi_ahm[]"      value="<?=$get_kontribusi_ahm?>"        class="hiden-checkbox kontribusi_ahm" onchange="kontribusi_ahm()"><?=number_format($get_kontribusi_ahm)?></td>
                    <td><input type="checkbox" name="kontribusi_md[]"       value="<?=$get_kontribusi_md?>"         class="hiden-checkbox kontribusi_md" onchange="kontribusi_md()"><?=number_format($get_kontribusi_md)?></td>
                    <td><input type="checkbox" name="kontribusi_dealer[]"   value="<?=$get_kontribusi_d?>"          class="hiden-checkbox kontribusi_dealer" onchange="kontribusi_dealer()"><?=number_format($get_kontribusi_d)?></td>
                    <td><input type="checkbox" name="ful_total_kontribusi_ahm[]"        value="<?=$ful_total_kontribusi_ahm    = $val->status_approved * $get_kontribusi_ahm?>"class="hiden-checkbox ful_total_kontribusi_ahm" onchange="ful_total_kontribusi_ahm()"><?=number_format($ful_total_kontribusi_ahm)?></td>
                    <td><input type="checkbox" name="ful_total_kontribusi_md_name[]"    value="<?=$ful_total_kontribusi_md     = $val->status_approved * $get_kontribusi_md?>"class="hiden-checkbox ful_total_kontribusi_md_name" onchange="ful_total_kontribusi_md_check()"><?=number_format($ful_total_kontribusi_md)?></td>
                    <td><input type="checkbox" name="ful_total_kontribusi_d_name[]"     value="<?=$ful_total_kontribusi_d      = $val->status_approved * $get_kontribusi_d?>"class="hiden-checkbox ful_total_kontribusi_d_name" onchange="ful_total_kontribusi_d_check()" class="hiden-checkbox"><?=number_format($ful_total_kontribusi_d)?></td>
                    <td><input type="checkbox" name="sum_total_reject[]"                value="<?=$sum_total_reject            =(($get_kontribusi_ahm + $get_kontribusi_md + $get_kontribusi_d) *  $val->status_reject)?> "onchange="sum_total_reject_check()" class="hiden-checkbox sum_total_reject"><?=number_format($sum_total_reject)?></td>
                    <td><input type="checkbox" name="total_stock_full[]"                value="<?=$total_stock_full             =(($get_kontribusi_ahm + $get_kontribusi_md + $get_kontribusi_d) *  $stock)?>"onchange="total_stock_full()" class="hiden-checkbox total_stock_full"><?=number_format($total_stock_full)?></td>
                    <td> 
                      <?php  if ($tipe_program=='scp' ) : ?>
                        <input type="checkbox" name="total_pembayaran[]" value="<?=$totalbayar= ($sum_total_reject) + ( $ful_total_kontribusi_ahm + $ful_total_kontribusi_md)?>" onchange="totalbayar()" class="hiden-checkbox totalbayar"><?=number_format($totalbayar)?>
                      <?php  elseif ($tipe_program=='dg' ) : ?>
                        <input type="checkbox" name="total_pembayaran[]" value="<?=$totalbayar= ($sum_total_reject) +  $ful_total_kontribusi_d?>" onchange="totalbayar()" class="hiden-checkbox totalbayar"><?=number_format($totalbayar)?>
                        <? endif; ?>
                    </td>
                      <td> <input type="checkbox" class="hiden-checkbox"  name="jenis_pembayaran[]" value="<?php echo set_value('tipe_program')?>" />  
                      <input type="checkbox" class="hiden-checkbox " value="" name="include_ppn[]"  /> 
                      <?php if( $include_ppn == 1){echo 'Include PPN';}else{echo'Not Include PPN';}?></td>
                    <td><input type="checkbox" class="checkall" name="checkallvalues[]"value="<?=$val->id_program_md?>" /></td>
                  </tr>
                  <?php
                }?>

              <tfoot >
                <tr>
                  <input type="hidden" name="footer_setting_ppn"    value="<?php echo set_value('setting_ppn'); ?>">
                  <input type="hidden" name="footer_id_bank"        value="<?php echo set_value('id_bank'); ?>">
                  <input type="hidden" name="footer_tanggal_awal"   value="<?php echo set_value('tanggal_awal'); ?>">
                  <input type="hidden" name="footer_tanggal_akhir"  value="<?php echo set_value('tanggal_akhir'); ?>">
                  <input type="hidden" name="footer_tipe_program"   value="<?php echo set_value('tipe_program'); ?>">
                  <td><input name="kode_dealer" type="hidden" class="hide-input-sm" value="<?=$val->id_dealer?>"readonly></td>
                  <td><input name="periode_awal" type="hidden" class="hide-input-sm" value="<?=$val->periode_awal?>"readonly></td>
                  <td><input name="periode_akhir" type="hidden" class="hide-input-sm" value="<?=$val->periode_akhir?>"readonly></td>
                  <td colspan="2"></td>
                  <td><b>Total</b></td>
                  <td> <span id="format_footer_total_tot_approve"></span>         <input type="text" name="footer_total_tot_approve"        id="total_tot_approved_footer_check"class="hide-input-sm-footer total_tot_approved_footer" value="" readonly></td>
                  <td> <span id="format_footer_total_tot_reject"></span>          <input type="text" name="footer_total_tot_reject"         id="total_tot_approved_footer_check"class="hide-input-sm-footer" value="" readonly></td>
                  <td> <span id="format_footer_total_sisa_stock_pending"></span>  <input type="text" name="footer_total_sisa_stock_pending" id="total_sisa_stock_footer"class="hide-input-sm-footer" value="" readonly></td>
                  <td> <span id="format_footer_total_tot_ahm"></span>             <input type="text" name="footer_total_tot_ahm"            id="total_tot_ahm_footer"class="hide-input-sm-footer" value="" readonly></td>
                  <td> <span id="format_footer_total_tot_m"></span>                <input type="text" name="footer_total_tot_m"              id="total_tot_m_footer" class="hide-input-sm-footer"value="" readonly></td>
                  <td> <span id="format_footer_total_tot_d"></span>                <input type="text" name="footer_total_tot_d"              id="total_tot_d_footer" class="hide-input-sm-footer"value="" readonly></td>
                  <td> <span id="format_footer_total_ful_tot_ahm"></span>          <input type="text" name="footer_total_ful_tot_ahm"        id="total_full_tot_ahm_footer"class="hide-input-sm-footer" value="" readonly></td>
                  <td> <span id="format_footer_total_ful_tot_md"></span>           <input type="text" name="footer_total_ful_tot_md"         id="total_ful_tot_md_footer"class="hide-input-sm-footer" value="" readonly></td>
                  <td> <span id="format_footer_total_ful_tot_d"></span>            <input type="text" name="footer_total_ful_tot_d"          id="total_ful_tot_d_footer"class="hide-input-sm-footer" value="" readonly></td>
                  <td> <span id="format_footer_total_ful_tot_reject"></span>       <input type="text" name="footer_total_ful_tot_reject"     id="total_full_tot_reject_footer"class="hide-input-sm-footer" value="" readonly></td>
                  <td> <span id="format_footer_total_tot_sisa"></span>             <input type="text" name="footer_total_tot_sisa"           id="total_tot_sisa_footer"class="hide-input-sm-footer" value="" readonly></td>
                  <td> <span id="format_footer_total_tot_pembayaran"></span>       <input type="text" name="footer_total_tot_pembayaran"     id="total_tot_pembayaran_footer"class="hide-input-sm-footer" value="" readonly></td>
                  <td></td>
                  <!-- testing ernesto -->
                  <td class="hide-input-sm-footer">  
                  <button class="btn btn-primary btn-sm btn-flat"  onclick="show_modal_manual()"  type="button" ><i class="fa fa-plus" aria-hidden="true"></i></button>
                </tr>
                <tr>
                </tr>
              </tfoot>
              <? endif; ?>
              <!-- ernesto -->
              </tbody>
            </table>

          </div>
          <?php }?>

          
          <table class="table table-bordered table-claim" id="table-claim" width="100%" >
                <thead>
                <tr valign="top" id="myExemple">
                  <th>Nama Dealer</th>
                  <th>No Juklak MD</th>
                  <th>Sub kategori program</th>
                  <th>No Sales ID</th>
                  <th>Jumlah Unit di Aprove</th>
                  <th>Jumlah Unit Reject</th>
                  <th>Jumlah Gantung </th>
                  <th>Kontribusi AHM</th>
                  <th>Kontribusi MD</th>
                  <th>Kontribusi D</th>
                  <th>Total Kontribusi AHM</th>
                  <th>Total Kontribusi MD</th>
                  <th>Total Kontribusi D</th>
                  <th>Total Reject</th>
                  <th>Total Pembayaran <br> D ke MD</th>
                  <th>PPN</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody  class="tbl-product-body" id="tbl-product-body-manual">
              </tbody>
                <tfoot>
                  <tr>
                    <td colspan="3"></td>
                    <td >Total</td>
                    <td> <input type="text" name="footer_total_tot_approve_manual"    id="total_tot_approve_manual"                       class="hide-input-sm-footer total_tot_approve_manual_footer" value="" readonly></td>
                    <td> <input type="text" name="footer_total_tot_reject_manual"     id="total_tot_reject_manual"                        class="hide-input-sm-footer total_tot_reject_manual_footer" value="" readonly></td>
                    <td> <input type="text" name="footer_total_tot_gantung_manual"    id="total_tot_gantung_manual"                       class="hide-input-sm-footer total_tot_gantung_manual_footer" value="" readonly></td>
                    <td> <input type="text" name="footer_total_tot_ahm_manual"        id="total_tot_ahm_manual"                           class="hide-input-sm-footer total_tot_ahm_manual_footer" value="" readonly></td>
                    <td> <input type="text" name="footer_total_tot_m_manual"          id="total_tot_m_manual"                             class="hide-input-sm-footer total_tot_m_manual_footer" value="" readonly></td>
                    <td> <input type="text" name="footer_total_tot_d_manual"          id="total_tot_d_manual"                             class="hide-input-sm-footer total_tot_d_manual_footer" value="" readonly></td>
                    <td> <input type="text" name="footer_total_ful_tot_ahm_manual"    id="total_full_tot_ahm_manual"                      class="hide-input-sm-footer total_ful_tot_ahm_manual_footer" value="" readonly></td>
                    <td> <input type="text" name="footer_total_ful_tot_md_manual"     id="total_ful_tot_md_manual"                        class="hide-input-sm-footer total_ful_tot_md_manual_footer" value="" readonly></td>
                    <td> <input type="text" name="footer_total_ful_tot_d_manual"      id="total_ful_tot_d_manual"                         class="hide-input-sm-footer total_ful_tot_d_manual_footer" value="" readonly></td>
                    <td> <input type="text" name="footer_total_ful_tot_reject_manual" id="total_full_tot_reject_manual"                   class="hide-input-sm-footer total_ful_tot_reject_footer" readonly></td>
                    <td> <input type="text" name="footer_total_bayar_manual"          id="total_ful_tot_bayar"                            class="hide-input-sm-footer total_ful_tot_bayar_dg_manual_footer" value="" readonly></td>
                  <td></td>
                  <td></td>
                  <!-- <td> <input type="text" name="footer_total_tot_pembayaran" id="total_tot_pembayaran_footer"class="hide-input-sm-footer" value="" readonly></td> -->
                  </tr>
                </tfoot>
                </table>
          <div class="row">
            <br>
            <div class="col-md-2 col-md-offset-5 m-2">
              <button type="submit" class="btn btn-info btn-flat" onclick="return confirm('Are you sure to Generate this data ?')"  onsubmit="return validateForm()" > <i class="fa fa-save"></i>Save</button>
              <button type="button" id="dropdownbutton-hide" class="btn btn-second btn-flat">Cancel</button>
            </div>
          </div>
        </form>

        </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <!-- ernesto -->
    <div class="modal fade" id="ModalManualInput"  role="dialog" aria-labelledby="basicModal"
        aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel">Manual Input Pembayaran Claim Dealer </h4>
            </div>

            <div class="modal-body">
                <div class="form-group">
                  <label for="email">Nama Dealer</label>


                  <select name="id_dealer"  class="form-control select2" id="id_dealer_manual_modal" style="width: 100% !important;padding: 0;"  required>
                          <option value="">- Pilih Nama Dealer-</option>
                          <?php
                            foreach($dealer as $row) { ?>
                          <option <?php if ($row->kode_dealer_ahm == set_value('id_dealer') ) { echo 'selected'; }?>
                            value="<?=$row->kode_dealer_ahm?>"><?=$row->nama_dealer?></option>
                          <?php } ?>
                        </select>
                </div>

                <div class="form-group">
                <label for="email">Nomor Sales ID</label>
                <div class="col-12">
                    <select name="no_sales_id_modal" class="form-control select2"  id="no_sales_id_modal"  value="<?php echo set_value('no_sales_id'); ?>"   style="width: 100% !important;padding: 0;" >
                          <option value="">- Pilih sales ID -</option>
                          <?php
                            foreach($sales_program_modal as $row) {  
                              ?>
                          <option <?php if ($row->id_program_md == set_value('no_sales_id_modal') ) { echo 'selected'; }?>
                            value="<?=$row->id_program_md?>">
                            <?php echo $row->id_program_md." | ".$row->judul_kegiatan." | ".$row->jenis_sales_program." ( ".$row->periode_awal."  -  ".$row->periode_akhir.")"  ?>
                          </option>
                          <?php } ?>
                        </select>
                </div>
   
                </div>

                <div class="form-group">
                <label for="email">Include PPN </label>
                    <div class="col-12">
                        <input type="checkbox"  name="ppn_modal" id="ppn_modal_checkbox_manual" value='1'  >
                    </div>
   
                </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button"  onclick="get_manual()" class="btn btn-primary">Add</button>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
    </div>
    <?php
    }

    else if($set=="detail_claim"){
      ?>


    <div class="box">
      <div class="box-header  with-border">
        <h3 class="box-title">
          <a href="h1/pembayaran_claim_dealer" class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div>

      <div class="box-body">
        <div class="row">
          <form class="form-horizontal">
            <div class="box-body">
              <div class="form-group">
                <label class="col-sm-2 control-label">Tanggal Created</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" value="<?=$header_claim->tgl_transaksi_claim?>" readonly>
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
                <label class="col-sm-2 control-label">Send To Dealer</label>
                <div class="col-sm-3">
                <input type="text" class="form-control" value="<?=$header_claim->send_payment_created?>" readonly>
                </div>
              </div>
            </div><!-- /.box-body -->

          </form>
        </div>
      </div>

      <div class="box hide-box">
        <div class="box-header with-border">
          <h3 class="box-title">

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

          <div class="tableFixHead">

            <table class="table table-bordered table-claim" id="stable-claim" width="100%">
              <thead>
                <tr valign="top" id="myExemple">
                  <th width="5%">No</th>
                  <!-- <th >ID Generate Transaksi Claim</th>  -->
                  <th>Nama Dealer</th>
                  <th>No Juklak MD</th>
                  <th>Program MD</th>
                  <th>Sub Kategori Program</th>
                  <th>Jumlah Unit di Aprove</th>
                  <th >Jumlah Unit Reject</th>        
                  <th>Kontribusi AHM</th>
                  <th>Kontribusi MD</th>
                  <th>Kontribusi D</th>
                  <th>Total Kontribusi AHM</th>
                  <th>Total Kontribusi MD</th>
                  <th>Total Kontribusi D</th>
                  <th>Total Reject</th>
                  <th>Jenis Pembayaran</th>
                  <th>Total Pembayaran MD ke D</th>
                  <th>Total Pembayaran D ke MD</th>
                  <th>Full Total Pembayaran</th>
                  <th>Include PPN</th>
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
                $tot_bayar = 0;
                $tot_reject_full=0;
                $tot_bayar_md = 0;
                $tot_bayar_d = 0;
                $tot_bayar_d_ke_md = 0;
                $tot_bayar_md_ke_d = 0;

          
          if (!empty($detail_claim)) : ?>

                <?php
          foreach ($detail_claim as $row) {?>
                <tr>
                  <td><?= $no++;?></td>
                  <!-- <td><?php //$row->claim_generate_payment_id?></td>    -->
                  <td><?=$row->nama_dealer ?></td>
                  <td><?= empty($row->no_juklak) ? '-' : $row->no_juklak;?></td>
                  <td><?=$row->id_program_md?></td>
                  <td><?=$row->sub_kategori_program?></td>
                  <td><?php $approve +=$row->tot_approve; echo $row->tot_approve ?></td>
                  <td><?php $reject +=$row->tot_reject; echo $row->tot_reject ?></td>    
                  <td><?php $tot_ahm +=$row->kontribusi_ahm; echo number_format($row->kontribusi_ahm)?></td>
                  <td><?php $tot_md +=$row->kontribusi_md; echo number_format($row->kontribusi_md)?></td>
                  <td><?php $tot_d += $row->kontribusi_d; echo number_format($row->kontribusi_d)?></td>
                  <td><?php $tot_ahm_sum +=$row->total_kontribusi_ahm; echo number_format($row->total_kontribusi_ahm)?></td>
                  <td><?php $tot_md_sum +=$row->total_kontribusi_md; echo number_format($row->total_kontribusi_md)?></td>
                  <td><?php $tot_d_sum +=$row->total_kontribusi_d; echo number_format($row->total_kontribusi_d)?></td>
                  <td><?php $tot_reject_full += $row->total_reject_detail; echo number_format($row->total_reject_detail)?></td>
                  <td><?php echo strtoupper($row->jenis_pembayaran);?></td>
                  <td><?php $tot_bayar_md_ke_d += $row->total_pembayaran_md_ke_d ; echo number_format($row->total_pembayaran_md_ke_d )?></td>
                  <td><?php $tot_bayar_d_ke_md += $row->total_pembayaran_d_ke_md ; echo number_format($row->total_pembayaran_d_ke_md )?></td>
                  <td><?php $tot_bayar += $row->total_pembayaran ; echo number_format($row->total_pembayaran )?></td>
                  <td><?php if($row->include_ppn == 1) { echo 'Include PPN';}else if($row->include_ppn == 0){ echo 'Not Include';} ?></td>
                </tr>
                <?php
          }
          ?>
                <?php endif; ?>
              </tbody>
              <tfoot>

                <tr>
                  <td colspan="4"></td>
                  <td><b>Total</b></td>
                  <td><b><?=number_format($approve)?></b></td>
                  <td><b><?=number_format($reject)?></b></td>
                  <td><b><?=number_format($tot_ahm)?></b></td>
                  <td><b><?=number_format($tot_md)?></b></td>
                  <td><b><?=number_format($tot_d)?></b></td>
                  <td><b><?=number_format($tot_ahm_sum)?></b></td>
                  <td><b><?=number_format($tot_md_sum)?></b></td>
                  <td><b><?=number_format($tot_d_sum)?></b></td>
                  <td><b><?=number_format($tot_reject_full)?></b></td>
                  <td></td>
                  <td><b><?=number_format($tot_bayar_md_ke_d)?></b></td>
                  <td><b><?=number_format($tot_bayar_d_ke_md)?></b></td>
                  <td><b><?=number_format($tot_bayar)?></b></td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </div>

          </form>
        </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
      }
   ?>
    </section>
    </div>


    <script>
      $(document).ready(function () {
        var ex2 = $('#claim_header').DataTable({
          "lengthChange": true,
          "ordering": false,
          "info": false,
          "searching": false,
          "paging": false,
          fixedHeader: true,
          "lengthMenu": [
            [10, 25, 50, 75, 100, -1],
            [10, 25, 50, 75, 100, "All"]
          ],
          "autoWidth": true
        });
      });
    </script>



    <script>
      $(".checkall").click(function () {
        $(this).parents('tr').find(':checkbox').prop('checked', this.checked);
        totalapprove();
        totalreject();
        sisastockpending();
        kontribusi_ahm();
        kontribusi_md();
        kontribusi_dealer();
        ful_total_kontribusi_md_check();
        ful_total_kontribusi_ahm();
        ful_total_kontribusi_md_check();
        ful_total_kontribusi_d_check();
        sum_total_reject_check();
        total_stock_full();
        totalbayar();
        include_ppn_check()
        rubah_format_rupiah();

      });

      $(function () {
        function updateSum() {
          var total = 0;
          $(this).parents('tr').find(':checkbox:checked').not(".checkall").each(function (i, n) {
            total += parseInt($(n).val());
          })
        }
        // $(".total_sum").val(total);
        $("input[type=checkbox]").change(updateSum);
        $(".checkall").change(updateSum);
        updateSum();
      });
    </script>

    <script>
             function rubah_format_rupiah(){

              const rupiah = (number)=>{
                return new Intl.NumberFormat("id-ID", {
                  style: "currency",
                  currency: "IDR"
                }).format(number);
              }

             }
    </script>


    <script>

      function totalapprove() {

        var input = document.getElementsByClassName("approve_name");
        var total = 0;
        for (var i = 0; i < input.length; i++) {
          if (input[i].checked) {
            total += parseFloat(input[i].value);
          }
        }
        document.getElementsByName("footer_total_tot_approve")[0].value = total;
      }



      function totalreject() {
        var reject = document.getElementsByClassName("reject_name");
        var totalreject = 0;
        for (var i = 0; i < reject.length; i++) {
          if (reject[i].checked) {
            totalreject += parseFloat(reject[i].value);
          }
        }
        document.getElementsByName("footer_total_tot_reject")[0].value = totalreject;
      }


      function kontribusi_ahm() {
        var kontribusi_ahm = document.getElementsByClassName("kontribusi_ahm");
        var total_kontribusi_ahm = 0;
        for (var i = 0; i < kontribusi_ahm.length; i++) {
          if (kontribusi_ahm[i].checked) {
            total_kontribusi_ahm += parseFloat(kontribusi_ahm[i].value);
          }
        }
        document.getElementsByName("footer_total_tot_ahm")[0].value = total_kontribusi_ahm;
      }


      function kontribusi_md() {
        var kontribusi_md = document.getElementsByClassName("kontribusi_md");
        var total_kontribusi_md = 0;
        for (var i = 0; i < kontribusi_md.length; i++) {
          if (kontribusi_md[i].checked) {
            total_kontribusi_md += parseFloat(kontribusi_md[i].value);
          }
        }
        document.getElementsByName("footer_total_tot_m")[0].value = total_kontribusi_md;
      }

      function kontribusi_dealer() {
        var kontribusi_d = document.getElementsByClassName("kontribusi_dealer");
        var total_kontribusi_d = 0;
        for (var i = 0; i < kontribusi_d.length; i++) {
          if (kontribusi_d[i].checked) {
            total_kontribusi_d += parseFloat(kontribusi_d[i].value);
          }
        }
        document.getElementsByName("footer_total_tot_d")[0].value = total_kontribusi_d;
      }


      function ful_total_kontribusi_ahm() {
        var ful_total_kontribusi_ahm = document.getElementsByClassName("ful_total_kontribusi_ahm");

        var ful_kontribusi_ahm = 0;
        for (var i = 0; i < ful_total_kontribusi_ahm.length; i++) {
          if (ful_total_kontribusi_ahm[i].checked) {
            ful_kontribusi_ahm += parseFloat(ful_total_kontribusi_ahm[i].value);
          }
        }
        document.getElementsByName("footer_total_ful_tot_ahm")[0].value = ful_kontribusi_ahm;
      }

      function ful_total_kontribusi_md_check() {
        var ful_total_kontribusi_md = document.getElementsByClassName("ful_total_kontribusi_md_name");
        var ful_kontribusi_md = 0;
        for (var i = 0; i < ful_total_kontribusi_md.length; i++) {
          if (ful_total_kontribusi_md[i].checked) {
            ful_kontribusi_md += parseFloat(ful_total_kontribusi_md[i].value);
          }
        }
        document.getElementsByName("footer_total_ful_tot_md")[0].value = ful_kontribusi_md;
      }

      function ful_total_kontribusi_d_check() {
        var ful_total_kontribusi_d = document.getElementsByClassName("ful_total_kontribusi_d_name");
        var ful_kontribusi_d = 0;
        for (var i = 0; i < ful_total_kontribusi_d.length; i++) {
          if (ful_total_kontribusi_d[i].checked) {
            ful_kontribusi_d += parseFloat(ful_total_kontribusi_d[i].value);
          }
        }
        document.getElementsByName("footer_total_ful_tot_d")[0].value = ful_kontribusi_d;
      }

      function totalbayar() {
        var totalbayar = document.getElementsByClassName("totalbayar");
        var fulltotalbayar = 0;
        for (var i = 0; i < totalbayar.length; i++) {
          if (totalbayar[i].checked) {
            fulltotalbayar += parseFloat(totalbayar[i].value);
          }
        }
        document.getElementsByName("footer_total_tot_pembayaran")[0].value = fulltotalbayar;
      }

      function sisastockpending() {
        var stock = document.getElementsByClassName("sisa_stock");

        var sisastock = 0;
        for (var i = 0; i < stock.length; i++) {
          if (stock[i].checked) {
            sisastock += parseFloat(stock[i].value);
          }
        }
        document.getElementsByName("footer_total_sisa_stock_pending")[0].value = sisastock;
      }



      function sum_total_reject_check() {
        var sum_total_reject = document.getElementsByClassName("sum_total_reject");
        var total_reject_check = 0;
        for (var i = 0; i < sum_total_reject.length; i++) {
          if (sum_total_reject[i].checked) {
            total_reject_check += parseFloat(sum_total_reject[i].value);
          }
        }
        document.getElementsByName("footer_total_ful_tot_reject")[0].value = total_reject_check;
      }


      function total_stock_full() {
        var total_tot_sisa = document.getElementsByClassName("total_stock_full");
        var tot_sisa = 0;
        for (var i = 0; i < total_tot_sisa.length; i++) {
          if (total_tot_sisa[i].checked) {
            tot_sisa += parseFloat(total_tot_sisa[i].value);
          }
        }
        document.getElementsByName("footer_total_tot_sisa")[0].value = tot_sisa;
      }
 
    </script>

<script>

function get_manual() {
  var dealere = $('#id_dealer_manual_modal option:selected').val();
  var salese  = $('#no_sales_id_modal option:selected').val();

  if ($('#ppn_modal_checkbox_manual').is(":checked"))
    {
      var ppn = 1;
    }else{
      var ppn = 0;
    }

  var count = 0;

  $.ajax({
            type: "GET",
            dataType: 'html',
            url: '<?php echo base_url() . "h1/pembayaran_claim_dealer/get_manual_ajax/" ?>',
            data: {
                'dealer': dealere,  'sales': salese, 'ppn' : ppn
            },
            success: function(data) {
                $('.tbl-product-body').append(data);
                $('#id_dealer_manual_modal').val('');
                $('#no_sales_id_manual_modal').val('');
                $("#ppn_modal_checkbox_manual").prop("checked", false);
                ful_total_manual();
            },
            error: function() {
                alert("did not work");
            }
        });

  $("#ModalManualInput").modal('hide');
  ful_total_manual();

}
</script>

<script>
  
  function ful_total_manual() {
        var approve_name = document.getElementsByClassName("approve_name_manual");
        var reject_name = document.getElementsByClassName("reject_name_manual");
        var gantung_name = document.getElementsByClassName("gantung_name_manual");
        var kontribusi_ahm = document.getElementsByClassName("kontribusi_ahm_manual");
        var kontribusi_md = document.getElementsByClassName("kontribusi_md_manual");
        var kontribusi_dealer = document.getElementsByClassName("kontribusi_dealer_manual");
        var ful_total_kontribusi_ahm = document.getElementsByClassName("ful_total_kontribusi_ahm_name_manual");
        var ful_total_kontribusi_md  = document.getElementsByClassName("ful_total_kontribusi_md_name_manual");
        var ful_total_kontribusi_d   = document.getElementsByClassName("ful_total_kontribusi_d_name_manual");
        var total_reject             = document.getElementsByClassName("ful_total_reject_manual");
        var total_bayar_dg           = document.getElementsByClassName("ful_total_bayar_dg_manual");

        var approve_name_manual = 0;
        var reject_name_manual = 0;
        var gantung_name_manual = 0;
        var kontribusi_ahm_manual = 0;
        var kontribusi_md_manual = 0;
        var kontribusi_d_manual = 0;
        var ful_total_kontribusi_ahm_manual = 0;
        var ful_total_kontribusi_md_manual = 0;
        var ful_total_kontribusi_d_manual = 0;
        var ful_total_reject_manual = 0;
        var ful_total_bayar_dg = 0;

            for (var i = 0; i < approve_name.length; i++) {
              if (approve_name[i].checked) {
                approve_name_manual += parseInt(approve_name[i].value);
                reject_name_manual  += parseFloat(reject_name[i].value);
                gantung_name_manual  += parseFloat(gantung_name[i].value);
                kontribusi_ahm_manual += parseFloat(kontribusi_ahm[i].value);
                kontribusi_md_manual  += parseFloat(kontribusi_md[i].value);
                kontribusi_d_manual   += parseFloat(kontribusi_dealer[i].value);
                ful_total_kontribusi_ahm_manual += parseFloat(ful_total_kontribusi_ahm[i].value);
                ful_total_kontribusi_md_manual  += parseFloat(ful_total_kontribusi_md[i].value);
                ful_total_kontribusi_d_manual   += parseFloat(ful_total_kontribusi_d[i].value);
                ful_total_reject_manual += parseFloat(total_reject[i].value);
                ful_total_bayar_dg      += parseFloat(total_bayar_dg[i].value);
              }
            }
  
        document.getElementsByClassName("total_tot_approve_manual_footer")[0].value  = approve_name_manual;
        document.getElementsByClassName("total_tot_reject_manual_footer")[0].value   = reject_name_manual;
        document.getElementsByClassName("total_tot_gantung_manual_footer")[0].value   = gantung_name_manual;
        document.getElementsByClassName("total_tot_ahm_manual_footer")[0].value      = kontribusi_ahm_manual;
        document.getElementsByClassName("total_tot_m_manual_footer")[0].value        = kontribusi_md_manual;
        document.getElementsByClassName("total_tot_d_manual_footer")[0].value        = kontribusi_d_manual;
        document.getElementsByClassName("total_ful_tot_ahm_manual_footer")[0].value = ful_total_kontribusi_ahm_manual;
        document.getElementsByClassName("total_ful_tot_md_manual_footer")[0].value   = ful_total_kontribusi_md_manual;
        document.getElementsByClassName("total_ful_tot_d_manual_footer")[0].value    = ful_total_kontribusi_d_manual;
        document.getElementsByClassName("total_ful_tot_reject_footer")[0].value      = ful_total_reject_manual;
        document.getElementsByClassName("total_ful_tot_bayar_dg_manual_footer")[0].value = ful_total_bayar_dg;
      }
</script>

<script>
 function show_modal_manual(){
  $('#ModalManualInput').modal('show'); 
  
  var radioValue = $("input[name='setting_ppn']:checked").val();
  if (radioValue == 1 ){
    $('#ppn_modal_checkbox_manual').prop('checked', true);
  }else if (radioValue == 0 ) {
    $('#ppn_modal_checkbox_manual').prop('checked', false);
  }

 }

 function remove(row) {
        $('#'+row).remove();
       ful_total_manual();
    }


</script>




