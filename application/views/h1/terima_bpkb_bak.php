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
</style>
<?php 
function compareByTimeStamp($time1, $time2) 
{ 
  if (strtotime($time1) < strtotime($time2)) 
    return 1; 
  else if (strtotime($time1) > strtotime($time2)) 
    return -1; 
  else
    return 0; 
} 
?>
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
    <li class="">Faktur STNK</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    
    

    <?php 
    if($set=='detail'){      
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/terima_bpkb">
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
            <form class="form-horizontal" action="h1/terima_bpkb/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">                                 
                <div>
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th width="5%">No</th>                          
                        <th>Nama Dealer</th>
                        <th>Nama Konsumen</th>
                        <th>No Mesin</th>
                        <th>No Polisi</th>  
                        <th>No BPKB</th>                      
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $no=1;                    
                      foreach ($sql->result() as $row) {
                        if ($row->konfirm != 'ya') {
                          $warna='yellow';
                        }else{
                          $warna='';
                        }

                        echo "
                          <tr style='background-color:$warna'>
                            <td>$no</td>
                            <td>$row->nama_dealer</td>
                            <td>$row->nama_konsumen</td>
                            <td>$row->no_mesin</td>
                            <td>$row->no_pol</td>
                            <td>$row->no_bpkb</td>
                          </tr>
                        ";
                        $no++;
                      }
                      ?>
                    </tbody>
                  </table>     
                </div>
                
              </div><!-- /.box-body -->
              
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=='konfirm'){
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/terima_bpkb">
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
            <form class="form-horizontal" action="h1/terima_bpkb/save" method="post" enctype="multipart/form-data">              
              <input type="hidden" name="no_kirim_bpkb" value="<?php echo $no_kirim_bpkb ?>">
              <div class="box-body">                                 
                <div>
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th width="5%">No</th>                          
                         <th>Nama Dealer</th>
                        <th>Nama Konsumen</th>
                        <th>No Mesin</th>
                        <th>No Polisi</th>  
                        <th>No BPKB</th>     
                        <th><input type="checkbox" id="check-all"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $no=1;                    
                      foreach ($sql->result() as $row) {
                        $jum = $sql->num_rows();
                        if($row->konfirm == 'ya'){
                          $cek = 'checked disabled';
                        }else{
                          $cek = '';
                        }
                        echo "
                          <tr>
                            <td>$no</td>
                             <td>$row->nama_dealer</td>
                            <td>$row->nama_konsumen</td>
                            <td>$row->no_mesin</td>
                            <td>$row->no_pol</td>
                            <td>$row->no_bpkb</td>
                            <td>
                              <input type='hidden' name='jum' value='$jum'>
                              <input type='hidden' name='no_mesin_$no' value='$row->no_mesin'>
                              <input class='data-check' type='checkbox' name='cek_bpkb_$no' $cek>
                            </td>
                          </tr>
                        ";
                        $no++;
                      }
                      ?>
                    </tbody>
                  </table>     
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
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!-- <a href="h1/penyerahan_bpkb/add">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>           -->
                    
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
              <th width="5%">No</th>                          
              <th>No Kirim bpkb</th>              
              <th>Tgl Kirim</th>       
              <th>Tgl Mohon Samsat</th>                          
              <th>Jumlah Item</th>   
              <th>Status</th>           
              <th width="15%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; $tgl_awal="";$tgl_akhir="";
          foreach($dt_bpkb->result() as $row) { 
            $jum = $this->db->query("SELECT COUNT(no_bpkb) AS jum FROM tr_kirim_bpkb_detail WHERE no_kirim_bpkb = '$row->no_kirim_bpkb'")->row();                                        
            $cek = $this->m_admin->getByID("tr_kirim_bpkb_detail","no_kirim_bpkb",$row->no_kirim_bpkb);
            $x=0;
            $tomb='';$arr=array();
            foreach ($cek->result() as $isi) {
              if($isi->konfirm != 'ya'){
                $x++;
              }
              if($isi->no_mesin!=""){              
                $tgl_samsat = $this->db->query("SELECT tgl_mohon_samsat FROM tr_pengajuan_bbn_detail WHERE no_mesin = '$isi->no_mesin'")->row()->tgl_mohon_samsat;                                                      
                $arr[]= $tgl_samsat;
              }
            }
            usort($arr, "compareByTimeStamp");             
           

            $tgl_awal = current($arr);
            $tgl_akhir = end($arr);
            if ($x>0) {
              $tomb = "<a href='h1/terima_bpkb/konfirm?id=$row->no_kirim_bpkb' class='btn btn-primary btn-flat btn-xs'>Konfirmasi</a>";
            }else{
              $tomb='';
            }
          echo "          
            <tr>
              <td>$no</td>
              <td>
                <a href='h1/terima_bpkb/detail?id=$row->no_kirim_bpkb'>
                  $row->no_kirim_bpkb
                </a>
              </td>
              <td>$row->tgl_kirim_bpkb</td>                                         
              <td>$tgl_awal s/d $tgl_akhir</td>                                                       
              <td>$jum->jum item</td>              
              <td>$row->status_bpkb</td>              
              <td>$tomb</td>";                                      
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
function generate(){    
  $("#tampil_data").show();
  cek_alamat();
  var id_dealer  = document.getElementById("id_dealer").value;     
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_dealer="+id_dealer;
     xhr.open("POST", "h1/penyerahan_bpkb/t_bpkb", true); 
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
function cek_alamat(){
  var id_dealer = document.getElementById("id_dealer").value; 
  $.ajax({
      url : "<?php echo site_url('h1/penyerahan_bpkb/cari_alamat')?>",
      type:"POST",
      data:"id_dealer="+id_dealer,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#alamat").val(data[0]);        
      }        
  })
}
</script>