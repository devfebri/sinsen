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

    <?php if ($set== 'view'){ ?>
      <div class="box box-default">
      <div class="box-header with-border">        
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h1/rep_sales_program/download" id="frm" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                                                                              
                <div class="form-group">                                                      
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Program AHM</label>                  
                  <div class="col-sm-3">
                    <select class="form-control select2" name="id_program_ahm">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_ahm->result() as $isi) {
                        echo "<option value='$isi->id_program_ahm'>$isi->id_program_ahm</option>";
                      }
                      ?>
                    </select>
                  </div>   

                  <label for="inputEmail3" class="col-sm-2 control-label">ID Program MD</label>                  
                  <div class="col-sm-3">
                    <select class="form-control select2" name="id_program_md" id='id_program_md_choose'>
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_md->result() as $isi) {
                        echo "<option value='$isi->id_program_md'>$isi->id_program_md</option>";
                      }
                      ?>
                    </select>
                    <span id="error_program_md" style="color: red;"></span>
                  </div>                                    
                </div>

                <div class="form-group">                                                      
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer</label>                  
                  <div class="col-sm-3">
                    <select class="form-control select2 check_button dealer_choose_set" name="id_dealer"  id='id_dealer_choose'>
                      <option value="">- All Dealer -</option>
                      <?php 
                      foreach ($dt_dealer->result() as $isi) {
                        echo "<option value='$isi->id_dealer'>$isi->kode_dealer_md - $isi->nama_dealer</option>";
                      }
                      ?>
                    </select>
                    <span id="error_dealer" style="color: red;"></span>
                  </div>   

                  <script>
                        $('#setNewValueButton').on('click', function () {
                        // Replace 'new_value' with the value you want to set dynamically
                        var newValue = 'new_value';
                        // Set the select dropdown to the new value
                        $('#id_dealer_choose').val(newValue);
                    });
                    </script>


                  <label for="inputEmail3" class="col-sm-2 control-label">Priode</label>                  
                    <div class="col-sm-2">
                      <!-- <input type="text"  class="form-control check_button" id='periode'  > -->
                      <div class="input-group">
                        <input type="text" class="form-control check_button" id="periode">
                        <span class="input-group-btn">
                          <button class="btn btn-default" type="button" onclick="searchFunction()">
                            <span class="glyphicon glyphicon-search"></span> <!-- Change glyphicon-search to the desired Glyphicon -->
                          </button>
                        </span>
                      </div>
             
                          <input type="hidden" class="form-control start_periode" id='start_periode' name='start_periode'>
                          <input type="hidden" class="form-control end_periode" id='end_periode' name='end_periode'>
                          <script>
                            $(function() {
                              $('#periode').daterangepicker({
                                // opens: 'left',
                                autoUpdateInput: false,
                                locale: {
                                  format: 'DD/MM/YYYY'
                                }
                              }, function(start, end, label) {
                                $('#start_periode').val(start.format('YYYY-MM-DD'));
                                $('#end_periode').val(end.format('YYYY-MM-DD'));
                              }).on('apply.daterangepicker', function(ev, picker) {
                                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                              }).on('cancel.daterangepicker', function(ev, picker) {
                                $(this).val('');
                                $('#start_periode').val('');
                                $('#end_periode').val('');
                              });
                            });
                          </script>

                        <script>
                        function searchFunction() {
                          $('#periode').val('01/03/2023 - 31/03/2023');
                          $('.dealer_choose_set').val('07628');
                          $('.start_periode').val('2023-03-01');
                          $('.end_periode').val('2023-03-31');
                        }
                      </script>
                      <span id="error_priode" style="color: red;"></span>
                    </div>  
                    </div>

                  </div>

                                        
                <div class="form-group">                                                      
                <label for="inputEmail3" class="col-sm-2 control-label">Group Dealer</label>                  
                  <div class="col-sm-3">
                    <select class="form-control select2 check_button" name="dealer_group"  id='id_dealer_group_choose'>
                      <option value="">- All Dealer -</option>
                      <?php 
                      foreach ($dt_group_dealer->result() as $isi) {
                        echo "<option value='$isi->kode_dealer_md'>$isi->nama_dealer</option>";
                      }
                      ?>
                    </select>
                    <span id="error_dealer_group" style="color: red;"></span>
                  </div>  
                  <br><br><br>


                      <div class="col-sm-2">
                    <button type="submit" name="process" value="main" class="btn bg-maroon btn-block btn-flat" onclick="validateAndDownload('main')" ><i class="fa fa-download"></i> Download</button>                                                      
                  </div>

                    <div class="col-sm-2">
                        <button type="submit" name="process" value="scp_dg" class="btn bg-green btn-block btn-flat"  onclick="validateAndDownload('scp_dg')"><i class="fa fa-download"></i> Download Excel MD (SCP/DG)</button>                                                      
                    </div>

                    <div class="col-sm-2">
                       <a href="/h1/rep_sales_program/add" type="submit"  class="btn bg-yellow btn-block btn-flat" "><i class="fa fa-download"></i> Add Report</a>                                                      
                    </div>

                    <div class="col-sm-2">
                       <button type="submit" name="process" value="finance_rp" class="btn bg-blue btn-block btn-flat" "><i class="fa fa-download"></i> Report Finance</a>                                                      
                    </div>
                    
                    <div class="col-sm-2">
                      <button type="submit" name="process" value="d" class="btn bg-red btn-block btn-flat"  onclick="validateAndDownload('d')"><i class="fa fa-download"></i> Download Excel From Dealer</button>                                                      
                    </div>
                  </div>


              </div>                               
            </form>
          </div>
        </div>
      </div>
    </div>
    <? } else if($set== 'monitor'){ ?>
      <div class="box box-default">
      <div class="box-header with-border">        
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h1/rep_sales_program/download" id="frm" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                                                                              
                <div class="form-group">                                                      
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Program AHMsss</label>                  
                  <div class="col-sm-3">
                    <select class="form-control select2" name="id_program_ahm">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_ahm->result() as $isi) {
                        echo "<option value='$isi->id_program_ahm'>$isi->id_program_ahm</option>";
                      }
                      ?>
                    </select>
                  </div>                                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Program MD</label>                  
                  <div class="col-sm-3">
                    <select class="form-control select2" name="id_program_md" id='id_program_md_choose'>
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_md->result() as $isi) {
                        echo "<option value='$isi->id_program_md'>$isi->id_program_md</option>";
                      }
                      ?>
                    </select>
                    <span id="error_program_md" style="color: red;"></span>
                  </div>                                    
                </div>
                <div class="form-group">                                                      
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer</label>                  
                  <div class="col-sm-3">
                    <select class="form-control select2 check_button" name="id_dealer"  id='id_dealer_choose'>
                      <option value="">- All Dealer -</option>
                      <?php 
                      foreach ($dt_dealer->result() as $isi) {
                        echo "<option value='$isi->id_dealer'>$isi->kode_dealer_md - $isi->nama_dealer</option>";
                      }
                      ?>
                    </select>
                    <span id="error_dealer" style="color: red;"></span>
                  </div>   
                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Priodes</label>                  
                    <div class="col-sm-4">
                      <input type="text"  class="form-control check_button" id='periode'  >
                          <input type="hidden" class="form-control" id='start_periode' name='start_periode'>
                          <input type="hidden" class="form-control" id='end_periode' name='end_periode'>
                          <script>
                            $(function() {
                              $('#periode').daterangepicker({
                                // opens: 'left',
                                autoUpdateInput: false,
                                locale: {
                                  format: 'DD/MM/YYYY'
                                }
                              }, function(start, end, label) {
                                $('#start_periode').val(start.format('YYYY-MM-DD'));
                                $('#end_periode').val(end.format('YYYY-MM-DD'));
                              }).on('apply.daterangepicker', function(ev, picker) {
                                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                              }).on('cancel.daterangepicker', function(ev, picker) {
                                $(this).val('');
                                $('#start_periode').val('');
                                $('#end_periode').val('');
                              });
                            });
                          </script>
                      <span id="error_priode" style="color: red;"></span>
                      </div>  
                      
                      </div>
                      <div class="col-sm-2">
                       <button type="submit" name="process" value="main" class="btn bg-maroon btn-block btn-flat" onclick="validateAndDownload('main')"  ><i class="fa fa-download"></i> Download</button>                                                      
                      </div>

                    <div class="col-sm-2">
                        <button type="submit" name="process" value="scp_dg" class="btn bg-green btn-block btn-flat"  onclick="validateAndDownload('scp_dg')"><i class="fa fa-download"></i> Download Excel MD (SCP/DG)</button>                                                      
                    </div>

                    <div class="col-sm-2">
                      <a href="/h1/pembayaran_claim_dealer/" class="btn bg-yellow btn-block btn-flat">Menu Finance</a>                                                  
                    </div>

                    <div class="col-sm-2">
                       <button type="submit" name="process" value="finance_rp" class="btn bg-blue btn-block btn-flat" "><i class="fa fa-download"></i> Report Finance</button>                                                      
                    </div>
                    
                    <div class="col-sm-2">
                      <button type="submit" name="process" value="d" class="btn bg-red btn-block btn-flat"  onclick="validateAndDownload('d')"><i class="fa fa-download"></i> Download Excel From Dealersss</button>                                                      
                    </div>
                  </div>
              </div>                                
            </form>
          </div>
        </div>
      </div>
    </div>
    <? }

else if($set=="add"){?>
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">     
      <a href="h1/rekap_bastd/add">
          <button class="btn bg-maroon btn-flat margin"><i class="fa fa-plus"></i> Add</button>
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
      <table id="table-serverside" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th width="5%">No</th>
            <th>No Rekap</th>
            <th>No Surat</th>  
            <th>Tgl Rekap</th>            
            <th>Jenis Rekap</th>            
            <th>Dealer</th>   
            <th>Periode </th>
            <th>Tgl Jatuh Tempo</th>
            <th>Total Unit</th>              
            <th>Total</th>              
            <th>Action</th>              
          </tr>
        </thead>
        <tbody>  
        </tbody>
      </table>
    </div>
  </div>

 <script>
       $(document).ready(function() {
      $('#table-serverside').DataTable({
          "scrollX": false,
          "processing": true, 
          "serverSide": true, 
          "order": [],

          "ajax": {
              "url": "<?php // echo site_url('h1/rekap_bastd/fetch_bastd')?>",
              "type": "POST"
          },
          "columnDefs": [
          {
              "targets": [ 0,9 ], 
              "orderable": false,
          },
          ],
      });
  });
 </script>

  <?php
  }  else if($set=="insert"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
        <a href="h1/rekap_bastd">
          <button class="btn bg-maroon btn-flat margin"><i class="fa fa-angle-left"></i> Back</button>
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
            <form class="form-horizontal" action="h1/penerimaan_unit/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
     
                <div class="form-group">                
                  <label class="col-sm-2 ">Nama Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2 set-onchange-id_dealer" required name="id_dealer"  id="id_dealer" placeholder="Nama Dealer">
                      <option value="">- choose -</option>
                      <?php                       
                      foreach($dt_dealer->result() as $val) {
                        echo "<option value='$val->id_dealer'>$val->nama_dealer ($val->kode_dealer_md)</option>;";
                      }
                      ?>
                    </select>                 
                  </div>
                </div>

                <div class="form-group">     

                <label class="col-sm-2 ">Group Dealer</label>
                  <div class="col-sm-4">
                  <select class="form-control select2 set-onchange-group_dealer" required name="group_dealer"  id="group_dealer"  placeholder="Group Dealer" >
                      <option value="">- choose -</option>
                      <?php                       
                      foreach($dt_group->result() as $val) {
                        echo "<option value='$val->id_group_dealer'>$val->group_dealer</option>;";
                      }
                      ?>
                    </select>                                     
                  </div>
                  </div>

                <div class="form-group">    
                  <label class="col-sm-2 ">QQ Kuitansi</label>
                  <div class="col-sm-4">
                      <input type="text" autocomplete="off"  name="kwitansi" id="kwitansi" class="form-control" readonly>
                  </div>
                </div>

                <div class="form-group"> 
                  <label class="col-sm-2 ">Periode *</label>
                    <div class="col-sm-4">
                    <input type="text"  class="form-control" id='periode' required>
                        <input type="hidden" class="form-control" id='start_periode' name='start_periode'>
                        <input type="hidden" class="form-control" id='end_periode' name='end_periode'>
                  </div>
                 </div>

                <div class="form-group">                
                  <label class="col-sm-2 ">Tanggal Jatuh Tempo *</label>
                  <div class="col-sm-4">
                  <input type="date" autocomplete="off" required placeholder="Tanggal Jatuh Tempo" name="tgl_jatuh_tempo" value="<?=date('Y-m-d')?>" id="tgl_jatuh_tempo" class="form-control">
                  </div>
                </div>  

                <div class="form-group">                
                  <label class="col-sm-2 ">No Surat *</label>
                  <div class="col-sm-4">
                  <input type="text" autocomplete="off"  name="no_surat" id="no_surat" class="form-control" value=''>
                  </div>
                  <button type="button"class="btn btn-info btn-flat btn-generate-set"><i class="fa fa-gear"></i> Generate</button>
                  <a href="/h1/rekap_bastd/add" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i></a>      
                </div>  
                    
              </div>
       
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }
    ?>

</section>
</div>

<script>
function validateAndDownload(value) {

  if(value === "main"){
    
    var pencarian_program_md = document.getElementById("id_program_md_choose");
    var program_md = pencarian_program_md.value;

    $('#error_priode').text('');
    $('#error_dealer').text('');
    $('#error_dealer_group').text('');

    if (program_md == ''){
      alert('Please select ID Program MD an option ');
        $('#error_program_md').text('Please select an option');
        event.preventDefault();
    }
    

  }else if (value === "scp_dg") {
    $('#error_priode').text('');
    $('#error_dealer').text('');
    $('#error_dealer_group').text('');


    var pencarian_program_md = document.getElementById("id_program_md_choose");
    var program_md = pencarian_program_md.value;

    if (program_md == ''){
        alert('Please select ID Program MD an option ');
        $('#error_program_md').text('Please select an option');
        event.preventDefault();
    }

  } else if (value === "d")  {
    $('#error_program_md').text('');
    var pencarian_periode = document.getElementById("periode");
    var periode = pencarian_periode.value;
    
    var pencarian_dealer = document.getElementById("id_dealer_choose");
    var dealer = pencarian_dealer.value;

    var pencarian_dealer_group = document.getElementById("id_dealer_group_choose");
    var dealer_group = pencarian_dealer_group.value;
  
    if (periode == ''){
        alert('Please select Priode an option ');
        $('#error_priode').text('Please select the periode');
        event.preventDefault();
    }

    if (dealer !== '' || dealer_group !==''){
   
    }else{
      $('#error_dealer').text('Please select an dealer option');
      $('#error_dealer_group').text('Please select an group dealer option');
        event.preventDefault();
    }



    }


}
</script>