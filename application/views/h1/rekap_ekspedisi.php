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
    if($set=="insert"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/rekap_ekspedisi">
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
            <form class="form-horizontal" action="h1/rekap_ekspedisi/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Ekspedisi</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="id_vendor" name="id_vendor" onchange="cek_vendor()">
                      <option value="">- choose -</option>
                      <?php 
                      $sql = $this->m_admin->getSortCond("ms_vendor","vendor_name","ASC");
                      foreach ($sql->result() as $isi) {
                        echo "<option value='$isi->id_vendor'>$isi->id_vendor | $isi->vendor_name</option>";
                      }
                      ?>
                    </select>
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Ekspedisi</label>
                  <div class="col-sm-4">
                    <input type="text" name="vendor_name" id="vendor_name" placeholder="Nama Ekspedisi" readonly class="form-control">                    
                  </div>                  
                </div>  
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Periode Penerimaan Awal</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" autocomplete="off" id="tanggal" placeholder="Periode Penerimaan Awal" class="form-control">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Periode Penerimaan Akhir</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_akhir" autocomplete="off" id="tanggal1" placeholder="Periode Penerimaan Akhir" class="form-control">                    
                  </div>                                    
                </div>  
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>
                  <div class="col-sm-4">
                    <button onclick="generate()" type="button" class="btn btn-flat btn-primary"><i class="fa fa-refresh"></i> Generate</button>
                  </div>                                                    
                </div>                                    
                <div class="form-group">                  
                  <span id="tampil_data"></span>
                </div>                  
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
              </div><!-- /.box-footer -->
              
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="detail"){
      $row = $dt_rekap->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/rekap_ekspedisi">
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
            <form class="form-horizontal" action="h1/rekap_ekspedisi/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Ekspedisi</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" readonly value="<?php echo $row->id_vendor ?>" placeholder="Kode Ekspedisi" readonly class="form-control">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Ekspedisi</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" value="<?php echo $row->vendor_name ?>" placeholder="Nama Ekspedisi" readonly class="form-control">                    
                  </div>                  
                </div>  
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Periode Penerimaan Awal</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" value="<?php echo $row->tgl_awal ?>" readonly placeholder="Periode Penerimaan Awal" class="form-control">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Periode Penerimaan Akhir</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" readonly  value="<?php echo $row->tgl_akhir ?>" placeholder="Periode Penerimaan Akhir" class="form-control">                    
                  </div>                                    
                </div>                
                
                
                
                <table class="table table-bordered table-hovered myTable1" width="100%">
                  <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Part</th>
                        <th>Nama Part</th>
                        <th>No Surat Jalan Ekspedisi</th>
                        <th>Tgl Surat Jalan Ekspedisi</th>
                        <th>Tgl Terima</th>
                        <th>Tgl Checker</th>
                        <th>No Polisi</th>                                
                        <th>No Mesin</th>                        
                        <th>Total</th>       
                    </tr>                  
                </thead>
                <tbody>
                    <?php 
                    $no=1;$h=0;$o=0;$g=0;
                    $sql = $this->db->query("SELECT id_checker, total FROM tr_rekap_ekspedisi_detail WHERE tr_rekap_ekspedisi_detail.id_rekap_ekspedisi = '$id'");    
                    $temp_id = '';
                    $index = 0;
                    foreach ($sql->result() as $isi) {
                        if($temp_id ==''){
                          $temp_id = $isi->id_checker;
                        }else if($temp_id!=$isi->id_checker){
                          $index = 0;
                          $temp_id = $isi->id_checker;
                        }
                        // $ek = $this->m_admin->getByID("tr_checker_detail","id_checker",$isi->id_checker);
                        /*
                        $sql2 = $this->db->query("SELECT * FROM tr_checker 
                          LEFT JOIN tr_checker_detail ON tr_checker.id_checker = tr_checker_detail.id_checker
                          LEFT JOIN ms_part ON tr_checker_detail.id_part = ms_part.id_part
                          WHERE tr_checker.id_checker = '$isi->id_checker'")->row();                                                    
                        $cek = $this->db->query("SELECT *, tr_scan_barcode.tgl_penerimaan AS tgl FROM tr_scan_barcode INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.no_shipping_list = tr_penerimaan_unit_detail.no_shipping_list
                            INNER JOIN tr_penerimaan_unit ON tr_penerimaan_unit.id_penerimaan_unit = tr_penerimaan_unit_detail.id_penerimaan_unit
                            WHERE tr_scan_barcode.no_mesin = '$sql2->no_mesin'")->row();                
                        echo "
                        <tr>                
                            <td>$sql2->id_part</td>
                            <td>$sql2->nama_part</td>
                            <td>$cek->no_surat_jalan</td>
                            <td>$cek->tgl_surat_jalan</td>
                            <td>$cek->tgl</td>
                            <td>$sql2->tgl_checker</td>
                            <td>$sql2->no_polisi</td>                
                            <td>$sql2->no_mesin</td>                            
                            <td align='right'>".mata_uang2($total = $isi->total)."</td>                            
                        </tr>
                        ";
                        */
                        $total = $isi->total;
                        $get_info = $this->db->query("SELECT tr_checker_detail.id_part, nama_part, tgl_checker, tr_checker.no_polisi, tr_checker.no_mesin,no_surat_jalan, tgl_surat_jalan, tr_scan_barcode.tgl_penerimaan AS tgl 
                            FROM tr_checker 
                            Inner join tr_scan_barcode on tr_checker.no_mesin = tr_scan_barcode.no_mesin 
                            INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.no_shipping_list = tr_penerimaan_unit_detail.no_shipping_list
                            INNER JOIN tr_penerimaan_unit ON tr_penerimaan_unit.id_penerimaan_unit = tr_penerimaan_unit_detail.id_penerimaan_unit
                            LEFT JOIN tr_checker_detail ON tr_checker.id_checker = tr_checker_detail.id_checker
                            LEFT JOIN ms_part ON tr_checker_detail.id_part = ms_part.id_part
                            WHERE tr_checker.id_checker = '$isi->id_checker' limit $index,1
                        "); 
                        
                        foreach ($get_info->result() as $info) {
                          echo "
                          <tr>                
                              <td>$no</td>
                              <td>$info->id_part</td>
                              <td>$info->nama_part</td>
                              <td>$info->no_surat_jalan</td>
                              <td>$info->tgl_surat_jalan</td>
                              <td>$info->tgl</td>
                              <td>$info->tgl_checker</td>
                              <td>$info->no_polisi</td>                
                              <td>$info->no_mesin</td>                            
                              <td align='right'>".mata_uang2($total)."</td>                            
                          </tr>
                          ";
                        }

                        $index++;
                        $no++;                        
                        $g = $g + $total;
                    }
                    ?>
                    <tfoot>
                        <tr>
                            <td colspan="9"></td>                            
                            <td align='right'><?php echo mata_uang2($g); ?></td>                            
                        </tr>
                    </tfoot>
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
          <a href="h1/rekap_ekspedisi/add">            
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
              <th>No Rekap</th>                           
              <th>Tgl Rekap</th>              
              <th>Periode</th>              
              <th>Total</th>
              <th>Nama Ekspedisi</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_rekap->result() as $row) {                                         
            $tr = $this->db->query("SELECT SUM(total) as jum FROM tr_rekap_ekspedisi_detail WHERE id_rekap_ekspedisi = '$row->id_rekap_ekspedisi'")->row();
          echo "          
            <tr>
              <td>$no</td>                           
              <td>
                <a href='h1/rekap_ekspedisi/detail?id=$row->id_rekap_ekspedisi'>
                  $row->id_rekap_ekspedisi
                </a>
              </td>              
              <td>$row->tgl_rekap</td>
              <td>$row->tgl_awal s/d $row->tgl_akhir</td>
              <td>".mata_uang2($tr->jum)."</td>                            
              <td>$row->vendor_name</td>                            
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
function cek_vendor(){
  var id_vendor = $("#id_vendor").val();                       
  $.ajax({
      url: "<?php echo site_url('h1/rekap_ekspedisi/cek_vendor')?>",
      type:"POST",
      data:"id_vendor="+id_vendor,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");          
          $("#vendor_name").val(data[0]);          
      } 
  })
}
function generate(){    
  var tanggal1  = document.getElementById("tanggal1").value;   
  var tanggal   = document.getElementById("tanggal").value;   
  var id_vendor = document.getElementById("id_vendor").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_vendor="+id_vendor+"&tanggal="+tanggal+"&tanggal1="+tanggal1;                           
     xhr.open("POST", "h1/rekap_ekspedisi/t_data", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_data").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }   
}
</script>