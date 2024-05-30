<style type="text/css">
  .isi {
     height: 25px;
    padding-left: 4px;
    padding-right: 4px;
  }
</style>
<?php 

function bln($b){
  $bulan=$bl=$month=$b;
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
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Master Data</li>
    <li class="">Kelompok Harga</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="insert"){
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/proyeksi_distribusi_md">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
            <form class="form-horizontal" action="dealer/proyeksi_distribusi_md/save" method="post" enctype="multipart/form-data">
              <div class="box-body">                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                 <?php $dealer= $this->db->query("SELECT * FROM ms_dealer ORDER BY nama_dealer ASC") ?>
                    <div class="col-sm-4"> 
                        <select class="form-control select2" name="id_dealer" id="id_dealer">
                          <?php if ($dealer->num_rows() >0) { ?>
                            <option value="">- choose -</option>
                            <?php foreach ($dealer->result() as $rs): ?>
                                <option value="<?=$rs->id_dealer?>"><?=$rs->nama_dealer?></option>
                            <?php endforeach ?>
                         <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-1">
                      <button class="btn btn-primary" onclick="generate()" type="button">Generate</button>
                    </div>  

                </div><hr>
                <div class="form-group">
                  <div class="col-sm-12">
                    <div id="showGenerate"></div>
                    <!-- <button class="btn btn-primary btn-sm" disabled style="letter-spacing: 0.7px;width:100%;text-align: left;font-size: 15px;"><b>Tipe Motor</b></button> -->
                    
                  </div>
                </div>
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="edit"){
      $row = $dt_stok_ditahan->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/stok_ditahan">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
            <form class="form-horizontal" action="dealer/proyeksi_distribusi_md/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_stok_ditahan ?>" />
              <div class="box-body">    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Kelompok Harga</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control"  value="<?php echo $row->id_stok_ditahan ?>" required id="inputEmail3" placeholder="ID Kelompok Harga" name="id_stok_ditahan">
                  </div>
                </div>                                                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelompok Harga</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" id="inputEmail3" value="<?php echo $row->stok_ditahan; ?>" placeholder="Kelompok Harga" name="stok_ditahan">
                  </div>
                </div>                 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Target Market</label>
                  <div class="col-sm-10">
                    <select name="target_market" class="form-control">
                      <option value="<?php echo $row->target_market ?>"><?php echo $row->target_market ?></option>
                      <option>Customer Umum</option>
                      <option>Instansi</option>
                      <option>Dealer Umum</option>
                      <option>Dealer Khusus</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label"></label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->active=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="active" value="1">
                      <?php } ?>
                      Active
                    </div>
                  </div>                  
                </div>                                           
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Update</button>                
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php
    }elseif($set=="view"){
    ?>

    <div class="box">
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>Bulan PO</th>   
              <th>Tahun</th>
              <th>Total Qty Order</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt->result() as $row) {  ?>
            <?php $qty_order=$this->db->query("SELECT sum(qty_order) as qty FROM tr_analisis_proyeksi_order_detail WHERE id_analisis='$row->id_analisis'")->row()->qty;
              if ($row->status == 'input') {
                $status = 'Waiting Approval';
                $label = 'info';
              }elseif($row->status=='approved'){
                $status = $row->status;
                $label='success';
              }elseif($row->status=='rejected'){
                $status = $row->status;
                $label='danger';
              }
            ?>
              <tr>
                <td><?=$no?></td>
                <td><a href="dealer/proyeksi_distribusi_md/detail?id=<?=$row->id_analisis?>"><?=bln($row->bulan)?></a></td>
                <td><?=$row->tahun?></td>
                <td><?=$qty_order?></td>
                <td><span class="label label-<?=$label?>"><?=$status?></span></td>
              </tr>
          <?php
          $no++;
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php 
     } elseif($set=="detail"){
      $row=$dt_analisis->row();
    ?>
    <!-- <body onload="getDetail(<?=$row->id_analisis?>)"> -->
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/proyeksi_distribusi_md">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
            <form class="form-horizontal" action="dealer/proyeksi_distribusi_md/approval" method="post" enctype="multipart/form-data">
              <div class="box-body">                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                 <?php $dealer= $this->db->query("SELECT * FROM ms_dealer ORDER BY nama_dealer ASC") ?>
                    <div class="col-sm-4"> 
                        <input type="text" name="" id="id_dealer" value="<?=$row->nama_dealer?>" class="form-control" readonly>
                    </div> 

                </div><hr>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table class="table table-condensed table-hover table-bordered" >
                      <!-- <thead>
                        <th width="5%">No.</th>
                        <th>Tipe</th>
                        <th></th>
                        <th width=9%" style="text-align: center">M-1 <span style="color: red">[<?=date('m')-1?>]</span></th>
                        <th width=9%" style="text-align: center">M <span style="color: red">[<?=date('m')?>]</span></th>
                      </thead> -->
                      <tbody>
                        <?php $no=1; foreach ($dt_detail->result() as $key => $rs): ?>
                        
                              <input type="hidden" name="tipe_<?=$key?>" value="<?=$rs->id_tipe_kendaraan?>">
                              <input type="hidden" name="stok_distribusi_<?=$key?>" value="<?=$rs->stok_distribusi?>">
                              <input type="hidden" name="jenis_moving_<?=$key?>" value="<?=$rs->jenis_moving?>">
                              <tr>
                                <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?=$rs->id_tipe_kendaraan?> | <?=$rs->tipe_ahm?></b></td>
                                <td colspan="2" style="text-align: right;margin-right: 20px;"><b>Stok Distribusi</b></td>
                                <td><b><?=$rs->stok_distribusi ?> %</b></td>
                              </tr>
                              <tr>
                                <td width="5%">No.</td>
                                <td>Tipe</td>
                                <td></td>
                                <td width=9%" style="text-align: center">M-1 <span style="color: red">[<?=$row->bulan-1?>]</span></td>
                                <td width=9%" style="text-align: center">M <span style="color: red">[<?=$row->bulan?>]</span></td>
                              </tr>
                              <tr>
                                <td rowspan="11" style="vertical-align: middle;text-align: center;"><?=$no?></td>
                                <td rowspan="11" style="vertical-align: middle;"><?=$rs->id_tipe_kendaraan?> | <?=$rs->tipe_ahm?></td>
                                <td style="text-align: right;">Stok Awal MD</td>
                                <td style="text-align: center;"><input type="text" name="stok_awal_md_<?=$key?>" readonly class="form-control isi" value="<?=$rs->st_awal_md?>"></td>
                                <td style="text-align: center;"><input type="text" name="stok_md_<?=$key?>" readonly class="form-control isi" value="<?=$rs->stok_md?>"></td>
                              </tr>
                              <tr>
                                <td  style="text-align: right;">Displan AHM</td>
                                 <td style="text-align: center;"><input type="text" name="displan_ahm_awal_<?=$key?>" readonly class="form-control isi" value="<?=$rs->displan_ahm_awal?>"></td>
                                <td style="text-align: center;"><input type="text" name="displan_ahm_<?=$key?>" readonly class="form-control isi" value="<?=$rs->displan_ahm?>"> </td>
                              </tr>
                              <tr>
                                <td style="text-align: right;">Penjualan Dealer <?=$row->nama_dealer?></td>
                                 <td style="text-align: center;"><input type="text" name="penjualan_dealer_m1_<?=$key?>" readonly class="form-control isi" value="<?=$rs->penjualan_dealer_m1?>"></td>
                                <td style="text-align: center;"><input type="text" name="penjualan_dealer_m_<?=$key?>" readonly class="form-control isi" value="<?=$rs->penjualan_dealer_m?>"></td>
                              </tr>
                              <tr>
                                <td style="text-align: right;">Penjualan All Dealer</td>
                                 <td style="text-align: center;"><input type="text" name="penjualan_all_dealer_m1_<?=$key?>" readonly class="form-control isi" value="<?=$rs->penjualan_all_dealer_m1?>"></td>
                                <td style="text-align: center;"><input type="text" name="penjualan_all_dealer_m_<?=$key?>" readonly class="form-control isi" value="<?=$rs->penjualan_all_dealer_m?>"></td>
                              </tr>
                              <tr>
                                <td colspan="3"></td>
                              </tr>
                               <tr>
                                <td  style="text-align: right;">Distribusi Ke Dealer</td>
                                 <td style="text-align: center;"><input type="text" name="dist_ke_dealer_m1_<?=$key?>" readonly class="form-control isi" value="<?=$rs->dist_ke_dealer_m1?>"></td>
                                <td style="text-align: center;"><input type="text" name="dist_ke_dealer_m_<?=$key?>" readonly class="form-control isi" value="<?=$rs->dist_ke_dealer_m?>"></td>
                              </tr>
                               <tr>
                                <td  style="text-align: right;">+/- Distribusi</td>
                                 <td style="text-align: center;"><input type="text" name="" readonly class="form-control isi"  value="-" readonly></td>
                                <td style="text-align: center;"><input type="text" name="distribusi_<?=$key?>" readonly class="form-control isi" value="<?=floor($rs->distribusi)?>"></td>
                              </tr>
                              <tr>
                                <td  style="text-align: right;">Stok Ditahan</td>
                                 <td style="text-align: center;"><input type="text" name="" readonly class="form-control isi"  value="-" readonly></td>
                                <td style="text-align: center;"><input type="text" name="stok_ditahan_<?=$key?>" readonly class="form-control isi" value="<?=floor($rs->stok_ditahan)?>"></td>
                              </tr>
                              <tr>
                                <td colspan="3"></td>
                              </tr>
                              <tr>
                                <td  style="text-align: right;">Saran Distribusi</td>
                                 <td style="text-align: center;"><input type="text" name="" readonly class="form-control isi"  value="-" readonly></td>
                                <td style="text-align: center;"><input type="text" name="suggest_distribusi_<?=$key?>" readonly class="form-control isi" value="<?=floor($rs->suggest_distribusi)?>"></td>
                              </tr>
                              <tr>
                                <td  style="text-align: right;">Qty Order</td>
                                 <td style="text-align: center;"><input type="text" name="" class="form-control isi" value="-" readonly></td>
                                <td style="text-align: center;"><input type="text" name="qty_order_<?=$key?>" class="form-control isi" value="<?=floor($rs->qty_order)?>" readonly></td>
                              </tr>
                              <tr>
                                <td colspan="5" style="background: #e0dddd;min-height: 1px"></td>
                              </tr>
                          <?php $no++; endforeach ?>
                      </tbody>
                    </table>
                    <!-- <button class="btn btn-primary btn-sm" disabled style="letter-spacing: 0.7px;width:100%;text-align: left;font-size: 15px;"><b>Tipe Motor</b></button> -->
                    
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
  <?php } elseif($set=="approval"){
      $row=$dt_analisis->row();
    ?>
    <!-- <body onload="getDetail(<?=$row->id_analisis?>)"> -->
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/proyeksi_distribusi_md">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
            <form class="form-horizontal" action="dealer/proyeksi_distribusi_md/approval" method="post" enctype="multipart/form-data">
              <div class="box-body">                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                 <?php $dealer= $this->db->query("SELECT * FROM ms_dealer ORDER BY nama_dealer ASC") ?>
                    <div class="col-sm-4"> 
                        <input type="text" name="" id="id_dealer" value="<?=$row->nama_dealer?>" class="form-control" readonly>
                        <input type="hidden" name="id_analisis" id="id_analisis" value="<?=$row->id_analisis?>" class="form-control" readonly>
                    </div> 

                </div><hr>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table class="table table-condensed table-hover table-bordered" >
                      <!-- <thead>
                        <th width="5%">No.</th>
                        <th>Tipe</th>
                        <th></th>
                        <th width=9%" style="text-align: center">M-1 <span style="color: red">[<?=date('m')-1?>]</span></th>
                        <th width=9%" style="text-align: center">M <span style="color: red">[<?=date('m')?>]</span></th>
                      </thead> -->
                      <tbody>
                        <?php $no=1; foreach ($dt_detail->result() as $key => $rs): ?>
                        
                              <input type="hidden" name="tipe_<?=$key?>" value="<?=$rs->id_tipe_kendaraan?>">
                              <input type="hidden" name="stok_distribusi_<?=$key?>" value="<?=$rs->stok_distribusi?>">
                              <input type="hidden" name="jenis_moving_<?=$key?>" value="<?=$rs->jenis_moving?>">
                              <tr>
                                <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?=$rs->id_tipe_kendaraan?> | <?=$rs->tipe_ahm?></b></td>
                                <td colspan="2" style="text-align: right;margin-right: 20px;"><b>Stok Distribusi</b></td>
                                <td><b><?=$rs->stok_distribusi ?> %</b></td>
                              </tr>
                              <tr>
                                <td width="5%">No.</td>
                                <td>Tipe</td>
                                <td></td>
                                <td width=9%" style="text-align: center">M-1 <span style="color: red">[<?=$row->bulan-1?>]</span></td>
                                <td width=9%" style="text-align: center">M <span style="color: red">[<?=$row->bulan?>]</span></td>
                              </tr>
                              <tr>
                                <td rowspan="11" style="vertical-align: middle;text-align: center;"><?=$no?></td>
                                <td rowspan="11" style="vertical-align: middle;"><?=$rs->id_tipe_kendaraan?> | <?=$rs->tipe_ahm?></td>
                                <td style="text-align: right;">Stok Awal MD</td>
                                <td style="text-align: center;"><input type="text" name="stok_awal_md_<?=$key?>" readonly class="form-control isi" value="<?=$rs->st_awal_md?>"></td>
                                <td style="text-align: center;"><input type="text" name="stok_md_<?=$key?>" readonly class="form-control isi" value="<?=$rs->stok_md?>"></td>
                              </tr>
                              <tr>
                                <td  style="text-align: right;">Displan AHM</td>
                                 <td style="text-align: center;"><input type="text" name="displan_ahm_awal_<?=$key?>" readonly class="form-control isi" value="<?=$rs->displan_ahm_awal?>"></td>
                                <td style="text-align: center;"><input type="text" name="displan_ahm_<?=$key?>" readonly class="form-control isi" value="<?=$rs->displan_ahm?>"> </td>
                              </tr>
                              <tr>
                                <td style="text-align: right;">Penjualan Dealer <?=$row->nama_dealer?></td>
                                 <td style="text-align: center;"><input type="text" name="penjualan_dealer_m1_<?=$key?>" readonly class="form-control isi" value="<?=$rs->penjualan_dealer_m1?>"></td>
                                <td style="text-align: center;"><input type="text" name="penjualan_dealer_m_<?=$key?>" readonly class="form-control isi" value="<?=$rs->penjualan_dealer_m?>"></td>
                              </tr>
                              <tr>
                                <td style="text-align: right;">Penjualan All Dealer</td>
                                 <td style="text-align: center;"><input type="text" name="penjualan_all_dealer_m1_<?=$key?>" readonly class="form-control isi" value="<?=$rs->penjualan_all_dealer_m1?>"></td>
                                <td style="text-align: center;"><input type="text" name="penjualan_all_dealer_m_<?=$key?>" readonly class="form-control isi" value="<?=$rs->penjualan_all_dealer_m?>"></td>
                              </tr>
                              <tr>
                                <td colspan="3"></td>
                              </tr>
                               <tr>
                                <td  style="text-align: right;">Distribusi Ke Dealer</td>
                                 <td style="text-align: center;"><input type="text" name="dist_ke_dealer_m1_<?=$key?>" readonly class="form-control isi" value="<?=$rs->dist_ke_dealer_m1?>"></td>
                                <td style="text-align: center;"><input type="text" name="dist_ke_dealer_m_<?=$key?>" readonly class="form-control isi" value="<?=$rs->dist_ke_dealer_m?>"></td>
                              </tr>
                               <tr>
                                <td  style="text-align: right;">+/- Distribusi</td>
                                 <td style="text-align: center;"><input type="text" name="" readonly class="form-control isi"  value="-" readonly></td>
                                <td style="text-align: center;"><input type="text" name="distribusi_<?=$key?>" readonly class="form-control isi" value="<?=floor($rs->distribusi)?>"></td>
                              </tr>
                              <tr>
                                <td  style="text-align: right;">Stok Ditahan</td>
                                 <td style="text-align: center;"><input type="text" name="" readonly class="form-control isi"  value="-" readonly></td>
                                <td style="text-align: center;"><input type="text" name="stok_ditahan_<?=$key?>" readonly class="form-control isi" value="<?=floor($rs->stok_ditahan)?>"></td>
                              </tr>
                              <tr>
                                <td colspan="3"></td>
                              </tr>
                              <tr>
                                <td  style="text-align: right;">Saran Distribusi</td>
                                 <td style="text-align: center;"><input type="text" name="" readonly class="form-control isi"  value="-" readonly></td>
                                <td style="text-align: center;"><input type="text" name="suggest_distribusi_<?=$key?>" readonly class="form-control isi" value="<?=floor($rs->suggest_distribusi)?>"></td>
                              </tr>
                              <tr>
                                <td  style="text-align: right;">Qty Order</td>
                                 <td style="text-align: center;"><input type="text" name="" class="form-control isi" value="-" readonly></td>
                                <td style="text-align: center;"><input type="text" name="qty_order_<?=$key?>" class="form-control isi" value="<?=floor($rs->qty_order)?>" readonly></td>
                              </tr>
                              <tr>
                                <td colspan="5" style="background: #e0dddd;min-height: 1px"></td>
                              </tr>
                          <?php $no++; endforeach ?>
                      </tbody>
                    </table>
                    <!-- <button class="btn btn-primary btn-sm" disabled style="letter-spacing: 0.7px;width:100%;text-align: left;font-size: 15px;"><b>Tipe Motor</b></button> -->
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                        <div class="col-sm-6"> 
                            <input type="text" name="keterangan" id="keterangan" class="form-control">
                        </div> 

                    </div>
               <div class="box-footer">
                <div class="col-sm-5">
                </div>
                <div class="col-sm-7">
                  <button type="submit" name="submit" value="approved" class="btn btn-primary btn-flat">Approve</button>
                  <button type="submit" class="btn btn-danger btn-flat" name="submit" value="rejected" >Reject</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
  <?php } ?>
  </section>
</div>

<script type="text/javascript">
function bulk_delete(){
  var list_id = [];
  $(".data-check:checked").each(function() {
    list_id.push(this.value);
  });
  if(list_id.length > 0){
    if(confirm('Are you sure delete this '+list_id.length+' data?'))
      {
        $.ajax({
          type: "POST",
          data: {id:list_id},
          url: "<?php echo site_url('dealer/proyeksi_distribusi_md/ajax_bulk_delete')?>",
          dataType: "JSON",
          success: function(data)
          {
            if(data.status){
              window.location.reload();
            }else{
              alert('Failed.');
            }                  
          },
          error: function (jqXHR, textStatus, errorThrown){
            alert('Error deleting data');
          }
        });
      }
    }else{
      alert('no data selected');
  }
}

 function generate()
{
  var value={id_dealer:$('#id_dealer').val()}
 if (value.id_dealer=='') {
  alert('Silahkan lengkapi data...!');
 }else{
   $.ajax({
       beforeSend: function() { $('#loading-status').show(); },
       url:"<?php echo site_url('dealer/proyeksi_distribusi_md/generate')?>",
       type:"POST",
       data:value,
       cache:false,
       success:function(html){
        
        if (html=='error') {
          alert('Silahkan lengkapi data master stok ditahan..!') ; 
        }else{
          $('#showGenerate').html(html);
        }
        $('#loading-status').hide();
       },
       statusCode: {
    500: function() {
      $('#loading-status').hide();
      alert("Something Wen't Wrong");
    }
  }
  });
 }
}


</script>