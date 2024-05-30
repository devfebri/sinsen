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
<body onload="mulai()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Biro Jasa</li>
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
          <a href="h1/proses_bbn">
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
            <form class="form-horizontal" action="h1/proses_bbn/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-3 control-label">Tgl Mohon Samsat (Awal)</label>                  
                  <div class="col-sm-4">
                    <input autocomplete='off' type="text" id="tanggal" name="tgl_awal" placeholder="Tgl Mohon Samsat (Awal)" class="form-control">
                  </div>                                                
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-3 control-label">Tgl Mohon Samsat (Akhir)</label>                  
                  <div class="col-sm-4">
                    <input autocomplete='off' type="text" id="tanggal1" name="tgl_akhir" placeholder="Tgl Mohon Samsat (Akhir)" class="form-control">
                  </div>                              
                  <div class="col-sm-2">
                    <button type="button" onclick="generate()" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Generate</button>
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
    }elseif($set=="view"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/proses_bbn/add">            
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>                          
              <th>No Invoice</th>
              <th>Tgl Invoice</th>
              <th>Tgl Mohon Samsat</th>
              <th>Jumlah Unit</th>
              <th>Amount</th>                 
              <!-- <th width="15%">Action</th>               -->
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_bbn->result() as $row){                                         
            // $item = $this->db->query("SELECT COUNT(no_mesin) as jum FROM tr_pengajuan_bbn_detail WHERE id_generate = '$row->id_generate'")->row();
            // if(isset($row->no_tanda_terima)){
            $print = $this->m_admin->set_tombol($id_menu,$group,'print');
            // $tom = "<a href='h1/proses_bbn/cetak?id=$row->no_invoice_bbn' $print class='btn btn-primary btn-flat btn-xs'><i class='fa fa-print'> Print</i></a>";
            $tom='';
            // }else{
            //   $tom = "";
            // } 
            $no_mesin_awal = $this->db->query("SELECT no_mesin FROM tr_proses_bbn_detail WHERE no_invoice_bbn = '$row->no_invoice_bbn' ORDER BY id_proses_bbn_detail ASC LIMIT 0,1");
            $nosin_awal = ($no_mesin_awal->num_rows() > 0) ? $no_mesin_awal->row()->no_mesin : "" ;
            if($nosin_awal!=""){
              $tgl_awal = $this->db->query("SELECT tgl_mohon_samsat FROM tr_pengajuan_bbn_detail WHERE no_mesin = '$nosin_awal'")->row()->tgl_mohon_samsat;            
            }else{
              $tgl_akhir = "";
            }

            $no_mesin_akhir = $this->db->query("SELECT no_mesin FROM tr_proses_bbn_detail WHERE no_invoice_bbn = '$row->no_invoice_bbn' ORDER BY id_proses_bbn_detail DESC LIMIT 0,1");
            $nosin_akhir = ($no_mesin_akhir->num_rows() > 0) ? $no_mesin_akhir->row()->no_mesin : "" ;
            if($nosin_akhir!=""){
              $tgl_akhir = $this->db->query("SELECT tgl_mohon_samsat FROM tr_pengajuan_bbn_detail WHERE no_mesin = '$nosin_akhir'")->row()->tgl_mohon_samsat;
            }else{
              $tgl_akhir = "";
            }
            echo "          
            <tr>
              <td>$no</td>
              <td>
                <a href='h1/proses_bbn/detail?id=$row->no_invoice_bbn'>
                  $row->no_invoice_bbn
                </a>
              </td>                           
              <td>$row->tgl_invoice</td>                           
              <td>$tgl_awal s/d  $tgl_akhir</td>                           
              <td>$row->jumlah_unit</td>                           
              <td align='right'>".mata_uang2($row->amount)."</td>";                                 
              // <td>";
              // echo $tom;
              // echo "
              // </td>";                                      
          $no++;
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php
    }elseif($set=="detail"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/proses_bbn">            
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
              <th width="5%">No</th>                          
              <th>No Mesin</th>
              <th>Notice Pajak</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_bbn->result() as $row){                                                   
            echo "          
            <tr>
              <td>$no</td>
              <td>$row->no_mesin</td>                           
              <td align='right'>".mata_uang2($row->notice_pajak)."</td>";                                         
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
function mulai(){
  for (var i = 1; i <= 1000; i++) {   
    $("#notice_pajak_"+i+"").hide();    
  }
}
function cek_form(){ 
  for (var i = 1; i <= 1000; i++) {
    if (document.getElementById("cek_notice_"+i).checked == true){
      $("#notice_pajak_"+i).show();
  //    $("#notice_pajak_"+i).val('');
      $("#notice_pajak_"+i).focus();
    }else{
      $("#notice_pajak_"+i).hide();
    }    
  }  
}
function generate2(){    
  $("#tampil_data").show();
  var start_date  = document.getElementById("tanggal").value;   
  var end_date    = document.getElementById("tanggal1").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "start_date="+start_date+"&end_date="+end_date;                           
     xhr.open("POST", "h1/proses_bbn/t_bbn", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_data").innerHTML = xhr.responseText;
                mulai();
                getDatatables();
            }else{
                alert('There was a problem with the request.');
            }
        }
    }   
}
 function cekSelisih(a)
  {
    var notice_pajak = $('#notice_pajak_'+a).val();
    var biaya_biro = $('#biaya_biro_'+a).val();
    var selisih   = biaya_biro - notice_pajak;
    $('#selisih_'+a).text(selisih);
  }
  function getDatatables()
  {
    $('#exampleX').DataTable({
          "paging": false,
          "scrollX":true
        });
        
  }
function generate()
{
  $("#tampil_data").show();  
  var value={start_date:document.getElementById("tanggal").value,
            end_date:document.getElementById("tanggal1").value}
  $.ajax({
       beforeSend: function() { $('#loading-status').show(); },
       url:"<?php echo site_url('h1/proses_bbn/t_bbn')?>",
       type:"POST",
       data:value,
       cache:false,
       success:function(html){
          $('#loading-status').hide();          
          $('#tampil_data').html(html);
          //document.getElementById("tampil_data").innerHTML = xhr.responseText;
          mulai();
          getDatatables();
       },
       statusCode: {
    500: function() {
      $('#loading-status').hide();
      alert("Something Wen't Wrong");
    }
  }
  });
}
</script>
