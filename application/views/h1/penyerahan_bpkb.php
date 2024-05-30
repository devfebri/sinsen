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
    if($set=="insert"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/penyerahan_bpkb">
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
            <form class="form-horizontal" id="myForm" action="h1/penyerahan_bpkb/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Serah Terima</label>
                  <div class="col-sm-3">
                    <input type="text" name="tgl_serah_terima" placeholder="Tgl Serah Terima" value="<?php echo date("Y-m-d") ?>" id="tanggal" class="form-control">
                  </div>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="id_dealer" name="id_dealer">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_dealer->result() as $isi) {
                        echo "<option value='$isi->id_dealer'>$isi->kode_dealer_md | $isi->nama_dealer</option>";
                      }
                      ?>
                    </select>
                  </div>              
                  <div class="col-sm-1">
                    <button type='button' onclick="generate()" class="btn btn-flat btn-primary btn-sm">Generate</button> 
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Dealer</label>
                  <div class="col-sm-10">
                    <input type="text" name="alamat" id="alamat" placeholder="Alamat Dealer" readonly class="form-control">
                  </div>              
                </div>                

                <div>
                  <span id="tampil_data"></span>
                </div>
                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="button" onclick="resetkan()" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=='detail'){
      $row = $dt_bpkb->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/penyerahan_bpkb">
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
            <form class="form-horizontal" action="h1/penyerahan_bpkb/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Serah Terima</label>
                  <div class="col-sm-3">
                    <input type="text" readonly name="tgl_serah_terima" placeholder="Tgl Serah Terima" value="<?php echo $row->tgl_serah_terima ?>" class="form-control">
                  </div>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-5">
                    <?php  
                    $isi = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();
                    ?>
                    <input type="text" readonly name="nama_dealer" placeholder="Nama Dealer" value="<?php echo $isi->nama_dealer ?>" class="form-control">
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Dealer</label>
                  <div class="col-sm-10">
                    <input type="text" name="alamat" id="alamat" placeholder="Alamat Dealer" value="<?php echo $isi->alamat ?>" readonly class="form-control">
                  </div>              
                </div>                

                <div>
                  <table id="example2" class="table myTable1 table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>No Mesin</th>
                        <th>No Rangka</th>
                        <th>No BPKB</th>
                        <th>Nama Konsumen</th>
                        <th>Tipe</th>
                        <th>Tahun Produksi</th>                    
                        <th>No BASTD</th>        
                        <th>No Bukti</th>
                        <th>Tgl Transfer</th>
                        <th>Status Pembayaran</th>
                      </tr>
                    </thead>
                   
                    <tbody>                    
                      <?php   
                      $no = 1;
                      $dt_b = $this->db->query("SELECT * FROM tr_penyerahan_bpkb_detail WHERE no_serah_bpkb = '$row->no_serah_bpkb'");        
                      foreach($dt_b->result() as $isi) {                             
                        $rt = $this->db->query("SELECT tr_terima_bj.*,tr_pengajuan_bbn_detail.id_tipe_kendaraan,
                            tr_pengajuan_bbn_detail.tahun,tr_pengajuan_bbn.id_dealer,ms_tipe_kendaraan.deskripsi_ahm FROM tr_terima_bj 
                            INNER JOIN tr_pengajuan_bbn_detail ON tr_terima_bj.no_mesin = tr_pengajuan_bbn_detail.no_mesin
                            INNER JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd
                            INNER JOIN ms_tipe_kendaraan ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                            WHERE tr_terima_bj.no_mesin = '$isi->no_mesin'")->row();
                        $nosin_spasi = substr_replace($isi->no_mesin," ", 5, -strlen($isi->no_mesin));
                        $rw = $this->m_admin->getByID("tr_fkb","no_mesin",$nosin_spasi);
                        if($rw->num_rows() > 0){
                          $ry = $rw->row();                          
                          $tahun_produksi = $ry->tahun_produksi;
                        }else{                          
                          $tahun_produksi = "";
                        }

			$cek = $this->db->query("			
					SELECT referensi ,
					GROUP_CONCAT(tr_penerimaan_bank_detail.id_penerimaan_bank SEPARATOR ', ') as id_penerimaan_bank, 
					GROUP_CONCAT(tr_penerimaan_bank.tgl_entry SEPARATOR ', ') as tgl_entry,
					sum(nominal) as nominal, sisa_hutang , c.status_bayar 
					FROM tr_penerimaan_bank_detail 
					INNER JOIN tr_penerimaan_bank ON tr_penerimaan_bank_detail.id_penerimaan_bank = tr_penerimaan_bank.id_penerimaan_bank
					join tr_faktur_stnk c on tr_penerimaan_bank_detail.referensi = c.no_bastd
					WHERE tr_penerimaan_bank_detail.referensi = '$rt->no_bastd'
					GROUP by referensi 
					order by tr_penerimaan_bank.tgl_entry asc
			");

                        $no_bukti = ($cek->num_rows() > 0) ? $cek->row()->id_penerimaan_bank : "" ;
                        $tgl_transfer = ($cek->num_rows() > 0) ? $cek->row()->tgl_entry : "" ;
                        $nominal = ($cek->num_rows() > 0) ? $cek->row()->nominal : 0 ;
                        $sisa_hutang = ($cek->num_rows() > 0) ? $cek->row()->sisa_hutang : 0 ;
                        if($tgl_transfer!=""){
                          //if($nominal == $sisa_hutang AND $nominal > 0){
			  if($cek->row()->status_bayar == 'lunas'){
                            $status = "Lunas";
                          }else{
                            $status = "Sebagian";
                          }
                        }else{
                          $status = "";
                        }
                        echo "
                        <tr>                     
                          <td>$isi->no_mesin</td> 
                          <td>$rt->no_rangka</td> 
                          <td>$rt->no_bpkb</td> 
                          <td>$rt->nama_konsumen</td> 
                          <td>$rt->deskripsi_ahm</td>       
                          <td>$tahun_produksi</td>                                           
                          <td>$rt->no_bastd</td>                                           
                          <td>$no_bukti</td>                                           
                          <td>$tgl_transfer</td>                                           
                          <td>$status</td>                                           
                        </tr>";
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
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/penyerahan_bpkb/add">            
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
        <table id="table_penyerahan_bpkb" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>                                
              <th>No Serah Terima</th>              
              <th>Tgl Serah Terima</th>            
              <th>Nama Dealer</th>
              <th>Alamat Dealer</th>
              <th width="15%">Action</th>      
            </tr>
          </thead>
          <tbody>   
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
function generate2(){    
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
function generate()
{
  $("#tampil_data").show();  
  var value={id_dealer:document.getElementById("id_dealer").value}
  $.ajax({
       beforeSend: function() { $('#loading-status').show(); },
       url:"<?php echo site_url('h1/penyerahan_bpkb/t_bpkb')?>",
       type:"POST",
       data:value,
       cache:false,
       success:function(html){
          $('#loading-status').hide();          
          $('#tampil_data').html(html);
          //document.getElementById("tampil_data").innerHTML = xhr.responseText;
          cek_alamat();          
       },
       statusCode: {
    500: function() {
      $('#loading-status').hide();
      alert("Something Wen't Wrong");
    }
  }
  });
}
function resetkan(){
  $("#counter").text(' ');
  document.getElementById("myForm").reset();
}

  $( document ).ready(function() {
    tabless = $('#table_penyerahan_bpkb').DataTable({
      "scrollX": true,
      "processing": true, 
      "bDestroy": true,
      "serverSide": true, 
      "order": [],
      "ajax": {
        "url": "<?php  echo site_url('h1/penyerahan_bpkb/fetch_data_penyerahan_bpkb_datatables')?>",
          "type": "POST"
      },  
      "columnDefs": [
        {
            "targets": [ 0,5 ],
            "orderable": false, 
        },
      ],
  });
});
</script>