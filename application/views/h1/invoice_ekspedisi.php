<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 40px;  
  padding-left: 5px;
  padding-right: 5px;  
  margin-right: 0px; 
}
.isi_combo{   
  height: 30px;
  border:1px solid #ccc;
  padding-left:1.5px;
}
</style>
<base href="<?php echo base_url(); ?>" />
<body onload="kirim_data_pl()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Finance</li>
    <li class="">Invoice Terima</li>
    <li class="">Ekspedisi Unit</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$page)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="detail"){
      $row = $dt->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/invoice_ekspedisi">
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
            <form class="form-horizontal" action="h1/invoice_ekspedisi/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Penerimaan</label>
                  <div class="col-sm-3">
                    <input type="text" name="no_mesin" value="<?php echo $row->no_penerimaan ?>" placeholder="No Penerimaan" readonly class="form-control">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-3 control-label">Qty Terima</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" value="<?php echo $row->qty_terima ?>" placeholder="Qty Terima" readonly class="form-control">                    
                  </div>                  
                </div>  
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Penerimaan</label>
                  <div class="col-sm-3">
                    <input type="text" name="no_mesin" value="<?php echo $row->tgl_penerimaan ?>" placeholder="Tgl Penerimaan" readonly class="form-control">                    
                  </div>                                    
                </div>  
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-3">
                    <input type="text" value="<?php echo $row->no_surat_jalan ?>" name="no_mesin" placeholder="No Surat Jalan" readonly class="form-control">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-3 control-label">Ekspedisi</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" value="<?php echo $row->ekspedisi ?>" placeholder="Ekspedisi" readonly class="form-control">                    
                  </div>                  
                </div>  
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat Jalan</label>
                  <div class="col-sm-3">
                    <input type="text" name="no_mesin" placeholder="Tgl Surat Jalan" value="<?php echo $row->tgl_surat_jalan ?>" readonly class="form-control">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-3 control-label">No Ekspedisi</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="No Ekspedisi" value="<?php echo $row->no_polisi ?>" readonly class="form-control">                    
                  </div>                  
                </div>  
                
                
                <table class="table table-bordered table-hovered" id="example2" width="100%">
                  <thead>
                    <tr>
                      <th>No Invoice AHM</th>
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>Tipe</th>
                      <th>Warna</th>
                      <th>Harga Ongkos Angkut</th>                    
                    </tr>                  
                  </thead>
                  <tbody>
                  <?php 
                  foreach ($dt_inv->result() as $isi) {
                    $ongkos = $this->db->query("SELECT * FROM ms_group_ongkos INNER JOIN ms_group_angkut ON ms_group_ongkos.id_group_angkut = ms_group_angkut.id_group_angkut
                        INNER JOIN ms_group_angkut_detail ON ms_group_angkut.id_group_angkut = ms_group_angkut_detail.id_group_angkut
                        WHERE ms_group_angkut_detail.id_tipe_kendaraan = '$isi->tipe_motor' AND ms_group_ongkos.id_vendor = '$row->ekspedisi'");
                    if($ongkos->num_rows() > 0){
                      $yi = $ongkos->row();
                      $biaya = $yi->ongkos_ahm;
                    }else{
                      $biaya = 0;
                    }

                    $cek_sl = $this->m_admin->getByID("tr_invoice","no_sl",$isi->no_shipping_list);
                    if($cek_sl->num_rows() > 0){
                      $no_i = $cek_sl->row();
                      $no_invoice = $no_i->no_faktur;
                    }else{
                      $no_invoice = "";
                    }
                    echo "
                    <tr>
                      <td>$no_invoice</td>
                      <td>$isi->no_mesin</td>
                      <td>$isi->no_rangka</td>
                      <td>$isi->tipe_ahm</td>
                      <td>$isi->warna</td>
                      <td>".mata_uang2($biaya)."</td>
                    </tr>
                    ";
                  }
                  ?>
                  </tbody>
                </table>  

                <br>


                
                
              </div><!-- /.box-body -->              
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
          <!--a href="h1/invoice_ekspedisi/upload">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-upload"></i> Upload .INV</button>
          </a-->          
                    
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>                          
              <th>No Penerimaan</th>              
              <th>Tgl Penerimaan</th>
              <th>Nama Ekspedisi</th>
              <th>Qty Terima</th>
              <th>Total</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_inv->result() as $row) { 
          $ekspedisi = $this->db->get_where('tr_penerimaan_unit', ['id_penerimaan_unit'=>$row->no_penerimaan]);
          $ekspedisi = $ekspedisi->num_rows()>0?$ekspedisi->row()->ekspedisi:'';                                        
          echo "          
            <tr>
              <td>$no</td>                           
              <td>
                <a href='h1/invoice_ekspedisi/detail?id=$row->no_penerimaan'>
                  $row->no_penerimaan
                </a>
              </td>                            
              <td>$row->tgl_penerimaan</td>                            
              <td>$ekspedisi</td>                            
              <td>$row->qty_terima</td>                            
              <td>".mata_uang2($row->total)."</td>                                          
              ";                                      
          $no++;
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
