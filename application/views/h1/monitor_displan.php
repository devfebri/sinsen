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

<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Pembelian</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    
    <?php 
    if($set=="upload"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/monitor_displan">
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
            <form class="form-horizontal" action="h1/monitor_displan/import_db" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Choose File</label>
                  <div class="col-sm-10">
                    <input type="file" accept=".UDDS" required class="form-control" autofocus name="userfile" >                    
                  </div>                  
                </div>                                                                                                      
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to import this data?')" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Start Upload</button>                                  
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
          <!--a href="h1/po/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a-->          
          
          <a href="h1/monitor_displan/upload">
            <button class="btn bg-primary btn-flat margin"><i class="fa fa-upload"></i> Upload</button>
          </a>          
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
        <form class="form-horizontal" action="h1/monitor_displan/filter" method="post" enctype="multipart/form-data">          
          <div class="box-body">
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
              <div class="col-sm-4">                
                <select class="form-control" name="bulan" id="bulan">
                  <option value="">- choose -</option>
                  <option value="01">Januari</option>
                  <option value="02">Februari</option>
                  <option value="03">Maret</option>
                  <option value="04">April</option>
                  <option value="05">Mei</option>
                  <option value="06">Juni</option>
                  <option value="07">Juli</option>
                  <option value="08">Agustus</option>
                  <option value="09">September</option>
                  <option value="10">Oktober</option>
                  <option value="11">November</option>
                  <option value="12">Desember</option>
                </select>
              </div>
              <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>
              <div class="col-sm-4">
                <select class="form-control" name="tahun" id="tahun">
                  <option value="">- choose -</option>
                  <?php 
                  $y = date("Y");
                  for ($i=$y-5; $i <= $y+5; $i++) { 
                    echo "<option>$i</option>";
                  }
                  ?>
                </select>
              </div>
            </div>                                                                                      
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
              <div class="col-sm-4">                
                <select class="form-control select2" name="id_tipe_kendaraan" id="id_tipe_kendaraan">
                  <option value="">- choose -</option>
                  <?php 
                  $dt_tipe = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");
                  foreach($dt_tipe->result() as $val) {
                    echo "
                    <option value='$val->id_tipe_kendaraan'>$val->id_tipe_kendaraan | $val->tipe_ahm</option>;
                    ";
                  }
                  ?>                      
                </select>
              </div>              
            </div>
            <div class="form-group">
              <div class="col-sm-2"></div>
              <div class="col-sm-4">
                <button type="button" onclick="kirim_data()" class="btn bg-maroon btn-flat"><i class="fa fa-filter"></i> Generate</button>
                <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Reset</button>
              </div>
            </div>
          </div>
        </form>
                
        <hr>

        <span id="tampil_data"></span>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php 
    }elseif($set=='filter'){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/monitor_displan">
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>Tipe Kendaraan</th>              
              <th>Warna</th>              
              <th>Tanggal</th>              
              <th>Jenis PO</th>
              <th>Qty Plan</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_displan->result() as $row) {
          $bulan = substr($row->tanggal, 2,2);
          $tahun = substr($row->tanggal, 4,4);
          $tgl = substr($row->tanggal, 0,2);
          $tanggal = $tgl."-".$bulan."-".$tahun;

          if($row->jenis_po == 'F'){
            $jenis = "PO Reguler";
          }else{
            $jenis = "PO Additional";
          }

          if(!is_null($row->warna)){
            $warna = "<td>$row->warna</td>";
          }else{
            $warna = "<td bgcolor='red'>$row->id_warna</td>";
          }

          if(!is_null($row->tipe_ahm)){
            $tipe = "<td>$row->tipe_ahm</td>";
          }else{
            $tipe = "<td bgcolor='red'>$row->id_tipe_kendaraan</td>";
          }
          echo "          
            <tr>
              <td>$no</td>
              $tipe
              $warna              
              <td>$tanggal</td>              
              <td>$jenis</td>
              <td>$row->qty_plan</td>
            </tr>";                        
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

<div class="modal fade modal_detail">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title">Detail</h4>
      </div>
      <div class="modal-body" id="show_detail">
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
function kirim_data(){    
  $("#tampil_data").show();
  var id_tipe_kendaraan = document.getElementById("id_tipe_kendaraan").value;   
  var bulan     = document.getElementById("bulan").value; 
  var tahun      = document.getElementById("tahun").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_tipe_kendaraan="+id_tipe_kendaraan+"&bulan="+bulan+"&tahun="+tahun;
     xhr.open("POST", "h1/monitor_displan/tampil_data", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                //alert(id_indent);
                document.getElementById("tampil_data").innerHTML = xhr.responseText;                                
            }else{
                alert('There was a problem with the request.');
            }
        }
    }     
}

</script>

<script type="text/javascript">
function detail_popup(tipe,warna,bulan,tahun,jenis)
{    
  $.ajax({
       url:"<?php echo site_url('h1/monitor_displan/detail_popup');?>",
       type:"POST",
       data:"tipe="+tipe+"&bulan="+bulan+"&tahun="+tahun+"&warna="+warna+"&jenis="+jenis,
       cache:false,
       success:function(html){
          $("#show_detail").html(html);
       }
  });
}
</script>