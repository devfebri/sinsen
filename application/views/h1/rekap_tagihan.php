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
          <a href="h1/rekap_tagihan">
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
            <form class="form-horizontal" action="h1/rekap_tagihan/save" method="post" enctype="multipart/form-data">              
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
          <a href="h1/rekap_tagihan">
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
            <form class="form-horizontal" action="h1/rekap_tagihan/save" method="post" enctype="multipart/form-data">              
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
                    <input type="text" name="no_mesin" readonly value="<?php echo $row->tgl_akhir ?>" placeholder="Periode Penerimaan Akhir" class="form-control">                    
                  </div>                                    
                </div>                
                
                
                
                <table class="table table-bordered table-hovered" id="example4" width="100%">
                  <thead>
                    <tr>
                      <!-- <th>No Invoice</th>
                      <th>Tgl Invoice</th> -->
                      <th>No Penerimaan</th>
                      <th>Tgl Penerimaan</th>
                      <th>No Polisi</th>                    
                      <th>No SJ</th>
                      <th>Qty</th>
                      <th>Total</th>
                    </tr>                  
                  </thead>
                  <tbody>
                  <?php 
                  $sql = $this->db->query("SELECT * FROM tr_rekap_tagihan_detail INNER JOIN tr_penerimaan_unit ON tr_rekap_tagihan_detail.id_penerimaan_unit=tr_penerimaan_unit.id_penerimaan_unit
                    LEFT JOIN tr_invoice_penerimaan ON tr_invoice_penerimaan.no_penerimaan = tr_penerimaan_unit.id_penerimaan_unit
                    WHERE tr_rekap_tagihan_detail.id_rekap_tagihan = '$row->id_rekap_tagihan'");
                  foreach ($sql->result() as $isi) {
                    echo "
                    <tr>
                      <td>$isi->no_penerimaan</td>
                      <td>$isi->tgl_penerimaan</td>
                      <td>$isi->no_polisi</td>
                      <td>$isi->no_surat_jalan</td>
                      <td>$isi->qty_terima</td>
                      <td>".mata_uang2($isi->total)."</td>                      
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
  }elseif($set == 'penerimaan_unit'){
      $row = $dt_rfs->row();
    ?>

    <div class="box">
      <div class="box-header with-border">
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body form-horizontal">
          <div  class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Penerimaan</label>
          <div class="col-sm-3">
            <?php 
            if(isset($row->tgl_penerimaan)){
              $tg = $row->tgl_penerimaan;
            }else{
              $tg = "";
            }
            ?>
            <input type="text" class="form-control" disabled value="<?php echo $tg ?>" id="tanggal" placeholder="Tanggal penerimaan" id="tgl_penerimaan">                    
          </div>  
           <label for="inputEmail3" class="col-sm-2 control-label">Ekspedisi</label>
            <div class="col-sm-3">
              <?php 
              if(isset($row->tgl_penerimaan)){
                $tg = $row->tgl_penerimaan;
              }else{
                $tg = "";
              }
              ?>
              <?php $pu = $this->db->query("SELECT vendor_name,no_polisi,nama_driver,ekspedisi FROM tr_penerimaan_unit JOIN ms_vendor ON tr_penerimaan_unit.ekspedisi=ms_vendor.id_vendor WHERE id_penerimaan_unit='$id_penerimaan_unit'")->row(); ?>
              <input type="text" class="form-control" disabled value="<?php echo $pu->vendor_name ?>">            
            </div> 
          </div>
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Sopir</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" disabled value="<?php echo $pu->nama_driver ?>">            
              </div>
              <label for="inputEmail3" class="col-sm-2 control-label">No. Polisi</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" disabled value="<?php echo $pu->no_polisi ?>">            
              </div>   
          </div>                                                                 
      </div>
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
        <button class="btn-block btn-primary" disabled> RFS</button>
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>
              <th>No Mesin</th>              
              <th>No Rangka</th>
              <th>No SL</th>
              <!-- <th>Nama Ekspeisi</th>       -->
              <th>Kategori</th>
              <th>Tipe</th>  
              <th>Warna</th>        
              <th>Kode Item</th>    
              <th>Lokasi</th>
              <th>FIFO</th>              
            </tr>
          </thead>
          <tbody>            
            <?php 
            $no = 1;
            foreach ($dt_rfs->result() as $row) {
              $ugm = $this->db->get_where('ms_ugm',['id_tipe_kendaraan'=>$row->tipe_motor]);
              $ugm = $ugm->num_rows()>0?$ugm->row()->kategori:'';      
              echo "
              <tr>
                <td>$no</td>
                <td>$row->no_mesin</td>
                <td>$row->no_rangka</td>
                <td>$row->no_shipping_list</td>
                <td>$ugm</td>
                <td>$row->deskripsi_ahm</td>
                <td>$row->warna</td>
                <td>$row->id_item</td>
                <td>$row->lokasi</td>
                <td>$row->fifo</td>
              </tr>
              ";
              $no++;
            }
            ?>
          </tbody>
        </table>


        <button class="btn-block btn-warning" disabled> NRFS</button>
        <table id="example3" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>
              <th>No Mesin</th>              
              <th>No Rangka</th>
              <th>No SL</th>
              <!-- <th>Nama Ekspeisi</th>       -->
              <th>Kategori</th>
              <th>Tipe</th>  
              <th>Warna</th>        
              <th>Kode Item</th>    
              <th>Lokasi</th>
              <th>FIFO</th>              
            </tr>
          </thead>
          <tbody>            
            <?php 
            $no = 1;
            foreach ($dt_nrfs->result() as $row) {     
              $ugm = $this->db->get_where('ms_ugm',['id_tipe_kendaraan'=>$row->tipe_motor]);
              $ugm = $ugm->num_rows()>0?$ugm->row()->kategori:'';   
              echo "
              <tr>
                <td>$no</td>
                <td>$row->no_mesin</td>
                <td>$row->no_rangka</td>
                <td>$row->no_shipping_list</td>
                <td>$ugm</td>
                <td>$row->deskripsi_ahm</td>
                <td>$row->warna</td>
                <td>$row->id_item</td>
                <td>$row->lokasi</td>
                <td>$row->fifo</td>
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
          <a href="h1/rekap_tagihan/add">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
        <!-- table serverside -->
 
         <table id="table_rekap_tagihan" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>            
              <th>No Rekap</th>                           
              <th>Tgl Rekap</th>              
              <th>Ekspedisi</th>              
              <th>Periode</th>
              <th>Total Amount</th>
            </tr>
          </thead>
          <tbody>            
          </tbody>
        </table>

     

        <?/* 
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>            
              <th>No Rekap</th>                           
              <th>Tgl Rekap</th>              
              <th>Ekspedisi</th>              
              <th>Periode</th>
              <th>Total Amount</th>
              <!-- <th width="5%">Action</th>               -->
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_rekap->result() as $row) {                                         
            $r = $this->m_admin->getByID("tr_rekap_tagihan_detail","id_rekap_tagihan",$row->id_rekap_tagihan);
            $jum=0;
            foreach ($r->result() as $isi) {            
              // $qty = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode INNER JOIN tr_penerimaan_unit_detail 
              //   ON tr_scan_barcode.no_shipping_list=tr_penerimaan_unit_detail.no_shipping_list
              //   INNER JOIN tr_penerimaan_unit ON tr_penerimaan_unit_detail.id_penerimaan_unit = tr_penerimaan_unit.id_penerimaan_unit
              //   WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$isi->id_penerimaan_unit'")->row();
              // $jum = $jum + $qty->jum;

              $qty = $this->db->query("SELECT SUM(total) as jum 
              FROM tr_rekap_tagihan_detail 
              INNER JOIN tr_penerimaan_unit ON tr_rekap_tagihan_detail.id_penerimaan_unit=tr_penerimaan_unit.id_penerimaan_unit
              LEFT JOIN tr_invoice_penerimaan ON tr_invoice_penerimaan.no_penerimaan = tr_penerimaan_unit.id_penerimaan_unit
              WHERE tr_rekap_tagihan_detail.id_rekap_tagihan = '$row->id_rekap_tagihan'")->row();            
              // $jum = $jum + $qty->jum;
              $jum = $qty->jum;
            }
          echo "          
            <tr>
              <td>$no</td>                           
              <td>
                <a href='h1/rekap_tagihan/detail?id=$row->id_rekap_tagihan'>
                  $row->id_rekap_tagihan
                </a>
              </td>              
              <td>$row->tgl_rekap</td>
              <td>$row->vendor_name</td>                            
              <td>$row->tgl_awal s/d $row->tgl_akhir</td>                            
              <td>".mata_uang2($jum)."</td>                            
              ";                                      
          $no++;
          }
          ?>
              <!-- <td>
                  <a href='h1/rekap_tagihan/delete?id=$row->id_rekap_tagihan'>
                    <button class='btn btn-flat btn-xs btn-danger'><i class='fa fa-trash-o'></i> Delete</button>
                  </a>                                    
              </td> -->                                          
          </tbody>
        </table>

        */?>
        
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
      url: "<?php echo site_url('h1/rekap_tagihan/cek_vendor')?>",
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
     xhr.open("POST", "h1/rekap_tagihan/t_data", true); 
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


<script>
  $( document ).ready(function() {
   tabless = $('#table_rekap_tagihan').DataTable({
	      "scrollX": true,
        "processing": true, 
        "bDestroy": true,
        "serverSide": true, 
        "order": [],
        "ajax": {
          "url": "<?php  echo site_url('h1/rekap_tagihan/fetch_data_rekap_tagihan')?>",
            "type": "POST"
        },  
              

        });
});
</script>