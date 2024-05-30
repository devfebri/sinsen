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
<body onload="auto()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">SRUT</li>
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
              <th>No SRUT</th>              
              <th>No Mesin</th>            
              <th>No SRUT dr Pemohon</th>
              <th>Thn Pembuatan</th>
              <th>Tgl Terima MD</th>              
              <th>Tgl Terima Dealer</th>
              <th>Tgl Terima Konsumen</th>
              <th>Tgl Terima Leasing</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1;
          $tr_monitor = $this->db->query("SELECT * FROM tr_srut ORDER BY no_srut,no_mesin ASC"); 
          foreach($tr_monitor->result() as $row) {                 
            $cek = $this->db->query("SELECT tr_penyerahan_srut.tgl_faktur FROM tr_penyerahan_srut INNER JOIN tr_penyerahan_srut_detail
              ON tr_penyerahan_srut_detail.no_serah_terima = tr_penyerahan_srut.no_serah_terima WHERE tr_penyerahan_srut_detail.no_mesin = '$row->no_mesin'");
            if($cek->num_rows() > 0){
              $j = $cek->row();
              $tgl_md = $j->tgl_faktur;
            }else{
              $tgl_md = "";
            }

            $cek2 = $this->db->query("SELECT * FROM tr_terima_srut INNER JOIN tr_terima_srut_detail
              ON tr_terima_srut.no_serah_terima = tr_terima_srut_detail.no_serah_terima WHERE tr_terima_srut_detail.no_mesin = '$row->no_mesin'");
            if($cek2->num_rows() > 0){
              $j = $cek2->row();
              $tgl_d = $j->tgl_terima;
            }else{
              $tgl_d = "";
            }

            $cek_t = $this->m_admin->getByID("tr_srut","no_mesin",$row->no_mesin);
            if($cek_t->num_rows() > 0){
              $d = $cek_t->row();
              $tgl_s = $d->tgl_upload;
            }else{
              $tgl_s = "";
            }

          echo "          
            <tr>
              <td>$no</td>
              <td>$row->no_srut</td>
              <td>$row->no_mesin</td>                                         
              <td>$row->no_srut_pemohon</td>
              <td>$row->tahun_pembuatan</td>                            
              <td>$tgl_s</td>                            
              <td>$tgl_d</td>                            
              <td></td>                            
              <td></td>                            
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
function auto(){
  var id = 1;
  $.ajax({
      url : "<?php echo site_url('h1/penyerahan_srut/cari_id')?>",
      type:"POST",
      data:"id="+id,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#no_serah_terima").val(data[0]);                
      }        
  })
}
function generate(){    
  $("#tampil_penyerahan_srut").show();
  var tgl_faktur  = document.getElementById("tanggal2").value;   
  var id_dealer   = document.getElementById("id_dealer").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "tgl_faktur="+tgl_faktur+"&id_dealer="+id_dealer;                           
     xhr.open("POST", "h1/penyerahan_srut/t_penyerahan_srut", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_penyerahan_srut").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }   
}
</script>