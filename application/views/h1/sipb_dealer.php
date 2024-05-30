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
<body>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Pembelian Unit</li>
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
          <a href="h1/sipb_dealer">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
            <form class="form-horizontal" action="h1/sipb_dealer/save" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="no_sj" name="no_sj">
                      <option value="">-- choose --</option>
                      <?php 
                      $sql = $this->db->query("SELECT * FROM tr_surat_jalan where no_surat_jalan not in(SELECT no_surat_jalan from tr_sipb_dealer)");
                      foreach ($sql->result() as $row) {
                        echo "
                        <option value='$row->no_surat_jalan'>$row->no_surat_jalan</option>
                        ";
                      }
                      ?>
                    </select>
                  </div>                
                  <div class="col-sm-4">
                    <button type="button" onclick="kirim_data_sipb()" class="btn btn-primary btn-flat btn-sm">Generate</button>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat Jalan</label>                  
                  <div class="col-sm-4">
                    <input type="text" id="tgl_sj" name="tgl_sj" class="form-control" placeholder="Tgl Surat Jalan" readonly>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat Izin</label>                  
                  <div class="col-sm-4">
                    <input type="text" required name="tgl_sipb_dealer" class="form-control" placeholder="Tgl Surat Izin" id="tanggal">
                  </div>                                
                </div>                                                                                                      
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>                  
                  <div class="col-sm-4">
                    <input type="text" id="nama_dealer" name="nama_dealer" class="form-control" placeholder="Nama Dealer" readonly>
                    <input type="hidden" id="id_dealer" name="id_dealer">
                  </div>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">No Polisi</label>                  
                  <div class="col-sm-4">
                    <input type="text" id="no_polisi" name="no_polisi" class="form-control" placeholder="No Polisi" >
                  </div>                                
                </div>                                                                                                      
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Dealer</label>                  
                  <div class="col-sm-4">
                    <input type="text" id="alamat_dealer" name="alamat_dealer" class="form-control" placeholder="Alamat Dealer" readonly>
                  </div>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Supir</label>                  
                  <div class="col-sm-4">
                    <input type="text" id="nama_supir" name="nama_supir" class="form-control" placeholder="Nama Supir" >
                  </div>                                
                </div>                                 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Warehouse Head</label>                  
                  <div class="col-sm-4">
                    <select class="form-control select2" name="warehouse_head">
                      <option value="">-- choose --</option>
                      <?php 
                      $sql2 = $this->m_admin->getSortCond("ms_karyawan","nama_lengkap","ASC");
                      foreach ($sql2->result() as $row) {
                        echo "
                        <option value='$row->id_karyawan_dealer'>$row->nama_lengkap ($row->nik)</option>
                        ";
                      }
                      ?>
                    </select>
                  </div>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Security</label>                  
                  <div class="col-sm-4">
                    <select class="form-control select2" name="security">
                      <option value="">-- choose --</option>
                      <?php 
                      $sql2 = $this->m_admin->getSortCond("ms_karyawan","nama_lengkap","ASC");
                      foreach ($sql2->result() as $row) {
                        echo "
                        <option value='$row->id_karyawan_dealer'>$row->nama_lengkap ($row->nik)</option>
                        ";
                      }
                      ?>
                    </select>
                  </div>                                
                </div>                               
                <hr>                
                                    
                  
                <span id="tampil_sipb"></span>                                                                                  
                  
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save this data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i>Reset</button>                                  
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
          <a href="h1/sipb_dealer/add">
            <button class="btn btn-primary btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>
              <th>No Surat Jalan</th>              
              <th>Tgl Surat Jalan</th>              
              <th>Nama Dealer</th>
              <th>No Polisi</th>              
              <th>Aksi</th>              
            </tr>
          </thead>
          <tbody>            
            <?php 
            $no=1;
            foreach ($dt_sipb->result() as $isi) {
              $s = $this->db->query("SELECT * FROM tr_surat_jalan
                  INNER JOIN ms_dealer on tr_surat_jalan.id_dealer = ms_dealer.id_dealer 
                  WHERE tr_surat_jalan.no_surat_jalan = '$isi->no_surat_jalan'")->row();
              echo "
              <tr>
                <td>$no</td>
                <td>
                  
                    $s->no_surat_jalan
                  
                </td>
                <td>$s->tgl_surat</td>
                <td>$s->nama_dealer</td>
                <td>$isi->no_polisi</td>
                <td>
                  <a href='h1/sipb_dealer/cetak?id=$isi->id_sipb_dealer'>
                    <button class='btn btn-primary btn-flat btn-sm' type='button'><i class='fa fa-print'></i> Cetak</button>
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
<script type="text/javascript">
function kirim_data_sipb(){    
  $("#tampil_sipb").show();
  cari_lain();
  var no_sj = document.getElementById("no_sj").value;    
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "no_pl="+birthday1_js;          
    var data = "no_sj="+no_sj;
     xhr.open("POST", "h1/sipb_dealer/t_sipb", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_sipb").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function cari_lain(){
  var no_sj  = $("#no_sj").val();                         
  $.ajax({
      url: "<?php echo site_url('h1/sipb_dealer/cari_lain')?>",
      type:"POST",
      data:"no_sj="+no_sj,
      cache:false,
      success:function(msg){                
          data=msg.split("|");
          if(data[0]=="ok"){          
            $("#tgl_sj").val(data[1]);                
            $("#nama_dealer").val(data[3]);                            
            $("#alamat_dealer").val(data[4]);                            
            $("#id_dealer").val(data[2]);                            
                                     
          }else{
            alert(data[0]);
          }
      } 
  })
}
</script>

