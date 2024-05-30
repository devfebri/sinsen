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
<base href="<?php echo base_url(); ?>" />
<body onload="take_kec()">
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
    if($set=="list"){      
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">          
          <a href="h1/tagihan_ubah_nama" class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</a>                    
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
              <th>No Tagihan</th>
              <th>Tgl Tagihan</th>
              <th>Nama Dealer</th>                            
              <th>Jumlah Unit</th>
              <th>Total Tagihan</th>              
              <th width="5%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_tagihan->result() as $row) {
          
          $jumlah = $this->m_admin->getByID("tr_tagihan_ubah_nama_detail","id_tagihan_ubah_nama",$row->id_tagihan_ubah_nama)->num_rows();
          $total = $this->db->query("SELECT SUM(biaya_denda) AS total FROM tr_tagihan_ubah_nama_detail WHERE id_tagihan_ubah_nama='$row->id_tagihan_ubah_nama'")->row()->total;
          $tombol = "<a class='btn btn-flat btn-xs btn-warning'>Cetak</a>";                                        
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->id_tagihan_ubah_nama</td> 
              <td>$row->tgl_tagih</td>                           
              <td>$row->nama_dealer</td>                           
              <td>$jumlah</td>                           
              <td>$total</td>                                       
              <td>";
              echo $tombol."</td>";                                      
          $no++;
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    

    <?php     
    }elseif($set=='add'){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/tagihan_ubah_nama">
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
            <form class="form-horizontal" action="h1/tagihan_ubah_nama/save_all" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Tagih</label>
                  <div class="col-sm-2">
                    <input type="text" name="tgl_tagih" autocomplete="off" placeholder="Tgl Tagih" value="<?php echo date("Y-m-d") ?>" id="tanggal" class="form-control">
                  </div> 

                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required name="id_dealer" id="id_dealer">
                      <option value="">- choose -</option>                      
                      <?php 
                      $biro = $this->db->query("SELECT * FROM ms_dealer");
                      foreach ($biro->result() as $row) {
                        echo "<option value='$row->id_dealer'>$row->kode_dealer_md | $row->nama_dealer</option>";
                      }
                      ?>
                    </select>
                  </div>  
                  <div class="col-sm-2">
                    <button class="btn btn-primary btn-sm btn-flat" type="button" onclick="generateDetail()">Generate</button>                    
                  </div>                              
                </div>          
                <div id="showGenerateDetail"></div>
                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>                  
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
          <a href="h1/tagihan_ubah_nama/add" class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</a>          
          <a href="h1/tagihan_ubah_nama/list_tagihan" class="btn bg-green btn-flat margin"><i class="fa fa-list"></i> List Tagihan</a>                  
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
              <th>Dealer</th>
              <th>No Mesin</th>
              <th>Nama Konsumen</th>                            
              <th>Alamat</th>
              <th>No KTP</th>
              <th>No HP</th>
              <th>Status</th>
              <th width="20%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_tagihan->result() as $row) {
        
            $cek = $this->m_admin->getByID("tr_tagihan_ubah_nama_detail","no_bastd",$row->no_bastd);
            if($cek->num_rows() > 0){
              $rt = $cek->row();
              if($rt->status=='checked'){
                $tombol = "";
              }else{                
                $tombol = "<button type='button' class='btn btn-primary btn-flat btn-xs' data-toggle='modal' data-target='.modal_detail' no_mesin='$row->no_mesin' onclick=\"detail_popup('$row->no_mesin')\"> Approve</button>
                      <a onclick=\"return confirm('Are you sure to reject this data?')\" href='h1/tagihan_ubah_nama/reject?id=$row->no_mesin' class='btn btn-danger btn-flat btn-xs'>Reject</a>";
              }
              if($rt->status == 'approved'){
                $status = "<span class='label label-primary'>$rt->status</span>";
                $tombol ='';
              }elseif($rt->status == 'checked'){
                $status = "<span class='label label-success'>$rt->status</span>";
              }else{
                $status = "<span class='label label-danger'>$rt->status</span>";
              }
            }else{
              $tombol = "<button type='button' class='btn btn-primary btn-flat btn-xs' data-toggle='modal' data-target='.modal_detail' no_mesin='$row->no_mesin' onclick=\"detail_popup('$row->no_mesin')\"> Approve</button>
                      <a onclick=\"return confirm('Are you sure to reject this data?')\" href='h1/tagihan_ubah_nama/reject?id=$row->no_mesin' class='btn btn-danger btn-flat btn-xs'>Reject</a>";
              $status = "<span class='label label-primary'>Waiting Approval</span>";
            }
          
                                                     
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->nama_dealer</td> 
              <td>$row->no_mesin</td>                           
              <td>$row->nama_konsumen</td>                           
              <td>$row->alamat</td>                           
              <td>$row->no_ktp</td>                           
              <td>$row->no_hp</td>                                         
              <td>$status</td>                            
              <td>";
              echo $tombol."</td>";                                      
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
<div class="modal fade" id="Approvemodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Approval
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
          <form class="form-horizontal" action="h1/tagihan_ubah_nama/approve" method="post" enctype="multipart/form-data">              
            <div class="box-body">                       
              <div class="form-group">                                    
                <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                <div class="col-sm-4">
                  <input type="text" name="no_mesin" placeholder="No Mesin" id="no_mesin" class="form-control">
                </div> 
              </div>               
              <div class="form-group">                                    
                <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                <div class="col-sm-4">
                  <input type="text" name="nama_konsumen" placeholder="Nama Konsumen" id="nama_konsumen" class="form-control">
                </div> 
              </div>               
              <div class="form-group">                                    
                <label for="inputEmail3" class="col-sm-2 control-label">Biaya Denda</label>
                <div class="col-sm-4">
                  <input type="text" name="biaya_denda" placeholder="Biaya Denda" id="biaya_denda" class="form-control">
                </div> 
              </div>               
              <div class="form-group">                                    
                <label for="inputEmail3" class="col-sm-2 control-label"></label>
                <div class="col-sm-4">
                  <button class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Simpan</button>
                </div> 
              </div>               
            </div>
          </form>
      </div>      
    </div>
  </div>
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
    </div>
  </div>
</div>

<script type="text/javascript">
function detail_popup(no_mesin)
{
  $.ajax({
       url:"<?php echo site_url('h1/tagihan_ubah_nama/detail_popup');?>",
       type:"POST",
       data:"no_mesin="+no_mesin,
       cache:false,
       success:function(html){
          $("#show_detail").html(html);
       }
  });
}
function generateDetail(){    
  $("#showGenerateDetail").show();
  var id_dealer = document.getElementById("id_dealer").value;     
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_dealer="+id_dealer;                           
     xhr.open("POST", "h1/tagihan_ubah_nama/t_detail", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("showGenerateDetail").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }   
}
</script>