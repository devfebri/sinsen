<?php 
function bln($a){
  $bulan=$bl=$month=$a;
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
<body onload="tampil_detail()">
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
    <li class="">Laporan</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    
    

    <div class="box box-default">
      <div class="box-header with-border">        
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h1/rep_polreg_samsat/download" id="frm" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                                                                              
                <div class="form-group">                                                      
                  <label for="inputEmail3" class="col-sm-3 control-label">Periode Tanggal Mohon Samsat</label>
                  <div class="col-sm-2">
                    <input placeholder="Awal" required type="text" autocomplete="off" name="tgl1" id="tanggal1" class="form-control">
                  </div>                  
                  <div class="col-sm-2">
                    <input placeholder="Akhir" required type="text" autocomplete="off" name="tgl2" id="tanggal2" class="form-control">
                  </div>                  
                  <div class="col-sm-2">
                    <button type="submit" name="process" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-download"></i> Download</button>                                                      
                  </div>                                             
                </div>
              </div><!-- /.box-body -->                                         
            </form>
            <span id="tampil_data"></span>
            <!-- <div id="imgContainer"></div> -->
          </div>
        </div>
      </div>
    </div><!-- /.box -->
</section>
</div>
<script type="text/javascript">
function simpan_polreg(){
  var id_tipe_kendaraan   = $("#id_tipe_kendaraan").val();                         
  $.ajax({
      url: "<?php echo site_url('h1/rep_polreg_samsat/save')?>",
      type:"POST",
      data:"id_tipe_kendaraan="+id_tipe_kendaraan,
      cache:false,
      success:function(data){                
        if(data=='nihil'){
          tampil_detail();                      
        }else{
          alert(data);
        }        
      } 
  })  
}
function hapus_polreg(a){  
  $.ajax({
      url: "<?php echo site_url('h1/rep_polreg_samsat/delete')?>",
      type:"POST",
      data:"id_pol="+a,
      cache:false,
      success:function(data){                
        if(data=='nihil'){
          tampil_detail();                      
        }else{
          alert(data);
        }        
      } 
  })  
}
function tampil_detail()
{
  var value={id:"1"}
  $.ajax({
       beforeSend: function() { $('#loading-status').show(); },
       url:"<?php echo site_url('h1/rep_polreg_samsat/getDetail')?>",
       type:"POST",
       data:value,
       cache:false,
       success:function(html){
          $('#loading-status').hide();          
          $('#tampil_data').html(html);
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