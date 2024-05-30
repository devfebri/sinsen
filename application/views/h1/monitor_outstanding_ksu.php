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
<?php 
if(isset($_GET['k'])){
?>
  <body onload="kirim_data_sj()">
<?php
}elseif(isset($_GET['s'])){
?>
  <body onload="kirim_data_ksu()">
<?php 
}else{
?>
  <body>
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
    <li class="">Pengeluaran</li>
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
          <!--a href="h1/surat_jalan/add">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>No Picking List KSU</th> 
              <th>Tanggal Picking List KSU</th>             
              <th>No DO</th>
              <th>Tgl Do</th>              
              <th>Nama Dealer</th> 
              <th>No Surat Jalan Outstanding KSU</th> 
              <th>Tgl Surat Jalan Outstanding KSU</th> 
              <th>Status</th>
              <th>Action</th>             
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1;
          // $sql = $this->db->query("SELECT DISTINCT(tr_surat_jalan_ksu.no_surat_jalan) AS no_sj,tr_surat_jalan_ksu_pl.no_pl_ksu, 
          //             tr_surat_jalan_ksu_pl.tgl_pl_ksu,tr_surat_jalan_ksu_pl.no_do,tr_do_po.tgl_do,ms_dealer.nama_dealer,tr_surat_jalan_ksu_pl.no_sj_outstanding_ksu,tr_surat_jalan_ksu_pl.no_sj_outstanding_ksu,tr_surat_jalan_ksu_pl.tgl_sj_outstanding_ksu
          //             FROM tr_surat_jalan_ksu_pl 
          //             INNER JOIN tr_surat_jalan_ksu ON tr_surat_jalan_ksu.no_surat_jalan = tr_surat_jalan_ksu_pl.no_surat_jalan                      
          //             INNER JOIN tr_do_po ON tr_surat_jalan_ksu_pl.no_do = tr_do_po.no_do
          //             INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
          //             WHERE tr_surat_jalan_ksu.qty < tr_surat_jalan_ksu.qty_do");

           $sql = $this->db->query("SELECT DISTINCT(tr_surat_jalan_ksu.no_surat_jalan) AS no_sj,tr_surat_jalan_ksu_pl.no_pl_ksu, 
                      tr_surat_jalan_ksu_pl.tgl_pl_ksu,tr_surat_jalan_ksu.no_do,tr_do_po.tgl_do,ms_dealer.nama_dealer,tr_surat_jalan_ksu_pl.no_sj_outstanding_ksu,tr_surat_jalan_ksu_pl.no_sj_outstanding_ksu,tr_surat_jalan_ksu_pl.tgl_sj_outstanding_ksu
                      FROM tr_surat_jalan_ksu_pl 
                      INNER JOIN tr_surat_jalan_ksu ON tr_surat_jalan_ksu.no_surat_jalan = tr_surat_jalan_ksu_pl.no_surat_jalan                      
                      INNER JOIN tr_do_po ON tr_surat_jalan_ksu.no_do = tr_do_po.no_do
                      INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
                      WHERE tr_surat_jalan_ksu.qty < tr_surat_jalan_ksu.qty_do");
          foreach ($sql->result() as $key) {
            $tr = $this->m_admin->getByID("tr_mon_ksu","no_pl_ksu",$key->no_pl_ksu)->row();
            if(isset($tr->no_pl_ksu)){
              $status = $tr->status_mon;
              if($status == 'diterima'){
                $tombol="";
              }elseif ($status == 'close') {
               // $tombol ="<a href=\"monju.id\"  target=\"popup\" class=\"btn bg-maroon btn-xs btn-flat\" onclick=\"window.open('h1/monitor_outstanding_ksu/print_sj?id=$key->no_sj&pl=$key->no_pl_ksu','popup','width=900,height=600,scrollbars=no,resizable=no'); return false;\">SJ Outstanding KSU</a>";
            //    if ($key->no_sj_outstanding_ksu=='' or $key->no_sj_outstanding_ksu==null) {
                   $tombol = "<button class=\"btn bg-maroon btn-xs btn-flat print_sj_ksu\" no_sj=\"$key->no_sj\" pl=\"$key->no_pl_ksu\" >SJ Outstanding KSU</button>
                    <a href=\"h1/monitor_outstanding_ksu/konfirmasi?id=$key->no_sj&pl=$key->no_pl_ksu\" class=\"btn btn-primary btn-xs btn-flat\">Konfirmasi</a>
                    <a onclick=\"return confirm('Are you sure to close this data?')\" href=\"h1/monitor_outstanding_ksu/close?id=$key->no_sj&pl=$key->no_pl_ksu\" class=\"btn btn-danger btn-xs btn-flat\">close</a>";
              //  }
              
              }else{
                $tombol ="<a href=\"h1/monitor_outstanding_ksu/konfirmasi?id=$key->no_sj&pl=$key->no_pl_ksu\" class=\"btn btn-primary btn-xs btn-flat\">Konfirmasi</a>
                    <a onclick=\"return confirm('Are you sure to close this data?')\" href=\"h1/monitor_outstanding_ksu/close?id=$key->no_sj&pl=$key->no_pl_ksu\" class=\"btn btn-danger btn-xs btn-flat\">close</a>";
              }
            }else{
              $status = "";
              $tombol = "";
            }
            echo "
            <tr>
              <td>$no</td>
              <td>
                <a href='h1/monitor_outstanding_ksu/detail?id=$key->no_sj&pl=$key->no_pl_ksu'>
                  $key->no_pl_ksu
                </a>
              </td>
              <td>$key->tgl_pl_ksu</td>
              <td>$key->no_do</td>
              <td>$key->tgl_do</td>
              <td>$key->nama_dealer</td>
              <td>$key->no_sj_outstanding_ksu</td>
              <td>"; 
              if ($key->no_sj_outstanding_ksu!='' or $key->no_sj_outstanding_ksu!=null)  {
                echo $key->tgl_sj_outstanding_ksu;
              }
              echo "</td>
              <td>$status</td>"; ?>
              <td> <?php echo $tombol ?> </td>
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
    }elseif ($set=='detail') {
    ?>

        <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/monitor_outstanding_ksu">            
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
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h1/monitor_outstanding_ksu/save_ksu" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <input type="hidden" name="no_pl_ksu" value="<?php echo $pl ?>">                
                <input type="hidden" name="sj" value="<?php echo $sj ?>">                
                <div class="form-group">
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>                        
                        <th width="5%">No</th>
                        <th>ID KSU</th> 
                        <th>KSU</th>             
                        <th>Qty Kekurangan</th>
                        <th width="2%">Qty Pemenuhan</th>                            
                      </tr>
                    </thead>
                    <tbody>            
                    <?php 
                    $no=1;      
                   
                    foreach ($dt_mo->result() as $key) {                      
                      $cek = $this->db->query("SELECT * FROM tr_mon_ksu_detail WHERE id_ksu = '$key->id_ksu' AND no_pl_ksu = '$pl'");
                      $cek2 = $this->db->query("SELECT * FROM tr_mon_ksu WHERE no_pl_ksu = '$pl'");
                      if($cek->num_rows() > 0){                        
                        $r = $cek->row();
                        $s = $cek2->row();
                        if($s->status_mon == 'close' or $s->status_mon == 'diterima'){
                        	$val = "readonly value='$r->qty_penuh'";
                        }else{
                          $val = "value='$r->qty_penuh'";
                        }                        
                      $status_mon =$s->status_mon;
                  }else{
                        $val = "";
                        $status_mon="";
                      }
                      $isi = $key->qty_do - $key->qty;
                      echo "
                      <tr>
                        <td>$no</td>              
                        <td>$key->id_ksu</td>
                        <td>$key->ksu</td>
                        <td>$isi</td>
                        <td>
                          <input type='text' class='form-control isi' name='qty_penuh[]' $val>
                          <input type='hidden' class='form-control isi' name='qty_do[]' value='$isi'>
                          <input type='hidden' class='form-control isi' name='id_ksu[]' value='$key->id_ksu'>
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
              <div class="box-footer">
            <?php if ($set=='konfirmasi' or $status_mon==""): ?>
            	<div class="col-sm-2">
            </div>
            <div class="col-sm-10">
              <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
              <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
            </div>
            
            <?php endif ?>
          </div><!-- /.box-footer -->

    <?php
    }elseif ($set=='konfirmasi') {
    ?>

        <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/monitor_outstanding_ksu">            
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
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h1/monitor_outstanding_ksu/save_ksu_konfirmasi" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <input type="hidden" name="no_pl_ksu" value="<?php echo $pl ?>">                
                <input type="hidden" name="sj" value="<?php echo $sj ?>">                
                <div class="form-group">
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>                        
                        <th width="5%">No</th>
                        <th>ID KSU</th> 
                        <th>KSU</th>             
                        <th>Qty Kekurangan</th>
                        <th>Qty Pemenuhan</th>                            
                        <th width="2%">Qty Konfirmasi</th>                            
                      </tr>
                    </thead>
                    <tbody>            
                    <?php 
                    $no=1;        
                    foreach ($dt_mo->result() as $key) {       
                      $cek = $this->db->query("SELECT * FROM tr_mon_ksu_detail WHERE id_ksu = '$key->id_ksu' AND no_pl_ksu = '$pl'");
                      if($cek->num_rows() > 0){
                        $r = $cek->row();
                        $val = "value='$r->qty_konfirmasi'";
                      }else{
                        $val = "";
                      }
                      echo "
                      <tr>
                        <td>$no</td>              
                        <td>$key->id_ksu</td>
                        <td>$key->ksu</td>
                        <td>$key->qty_do</td>
                        <td>$key->qty_penuh</td>
                        <td>
                          <input type='text' class='form-control isi' name='qty_konfirmasi[]' $val>
                          <input type='hidden' class='form-control isi' name='qty_do[]' value='$key->qty_do'>
                          <input type='hidden' class='form-control isi' name='qty_penuh[]' value='$key->qty_penuh'>
                          <input type='hidden' class='form-control isi' name='id_ksu[]' value='$key->id_ksu'>
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
              <div class="box-footer">
            <div class="col-sm-2">
            </div>
            <div class="col-sm-10">
              <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
              <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
            </div>
          </div><!-- /.box-footer -->


    <?php      
    }
    ?>
  </section>
</div>

<script type="text/javascript">
  $(document).on("click",".print_sj_ksu",function(){ 
      var no_sj=$(this).attr('no_sj');
      var pl=$(this).attr('pl');
       var h=600;
       var w=800;
       var left = (screen.width/2)-(w/2);
      var top = (screen.height/2)-(h/2);
      var targetWin = window.open ('h1/monitor_outstanding_ksu/print_sj?id='+no_sj+'&pl='+pl, "Cetak Outstanding KSU", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
      location.reload();
        })
</script>