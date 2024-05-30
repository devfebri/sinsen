<?php 
function bln(){
  $bulan=$bl=$month=date("m");
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
</style>
<base href="<?php echo base_url(); ?>" />
<body onload="auto()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Penerimaan Unit</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="detail"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/laporan_ksu">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
        <div id="row">
          <div class="col-md-12">       
            <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">     
              <div class="box-body">              
                <div class="form-group">
                  <input type="hidden" name="id_penerimaan_unit_dealer" value="<?php echo $id_penerimaan_unit_dealer ?>">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Penerimaan Unit</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" value="<?php echo $id_penerimaan_unit_dealer ?>" readonly placeholder="No Surat Jalan" name="no_surat_jalan">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Penerimaan</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control" value="<?php echo $sj->tgl_penerimaan ?>" readonly placeholder="Tgl Surat Jalan" name="tgl_surat">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $sj->no_surat_jalan ?>" readonly placeholder="No DO" name="no_do">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $sj->tgl_surat_jalan ?>" readonly placeholder="Tgl DO" name="tgl_do">                    
                  </div>
                </div>                
              </div>            
          </div>
        </div>
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>Kode KSU</th>              
              <th>Aksesoris</th>              
              <th>Kode Item</th>
              <th>Qty MD</th>
              <th>Qty Terima</th>              
            </tr>
          </thead>
          <tbody>            
            <?php 
            $no = 1;
            $v_ksu = $this->db->query("SELECT * FROM tr_penerimaan_ksu_dealer INNER JOIN ms_ksu ON tr_penerimaan_ksu_dealer.id_ksu=ms_ksu.id_ksu 
                WHERE tr_penerimaan_ksu_dealer.id_penerimaan_unit_dealer = '$id_penerimaan_unit_dealer'");
            foreach ($v_ksu->result() as $row) {                                          
              echo "
              <tr>
                <td>$no</td>
                <td>$id_penerimaan_unit_dealer</td>                
                <td>$row->ksu</td>                
                <td>$row->id_item</td>                                
                <td>$row->qty_md</td>                                                
                <td>$row->qty_terima</td>                                                
              </tr>
              ";
              $no++;
            }
            ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->    
    </div><!-- /.box -->  
    

    <?php
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!--a href="dealer/monitoring_outstanding_ksu/list_ksu">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-list"></i> List KSU</button>
          </a-->          
          <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  
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
              <th>Tgl Penerimaan</th>
              <th>No Surat Jalan</th>              
              <th>Tgl Surat Jalan</th>              
              <th>QTY Terima</th>              
              <th>Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_ksu->result() as $row) {     
            $s = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer WHERE id_penerimaan_unit_dealer = '$row->id_penerimaan_unit_dealer'")->row();          
            $j = $this->db->query("SELECT SUM(qty_terima) AS jum FROM tr_penerimaan_ksu_dealer WHERE id_penerimaan_unit_dealer = '$row->id_penerimaan_unit_dealer'");
            if($j->num_rows() > 0){
              $k = $j->row();
              $jum = $k->jum;
            }else{
              $jum = "";
            }
            echo "
            <tr>
              <td>$no</td>
              <td>$s->tgl_penerimaan</td>
              <td>$s->no_surat_jalan</td>
              <td>$s->tgl_surat_jalan</td>
              <td>$jum</td>                            
              <td>                                
                <a href='dealer/laporan_ksu/view?id=$s->id_penerimaan_unit_dealer'>
                  <button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-eye'></i> Detail</button>
                </a>
              </td>
            </tr>
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

