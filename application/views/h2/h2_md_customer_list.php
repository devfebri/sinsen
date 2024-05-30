<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<!-- <link rel="stylesheet" href="http://demos.codexworld.com/multi-select-dropdown-list-with-checkbox-jquery/jquery.multiselect.css"> -->
<!-- jQuery library -->
  <!-- <script src="http://demos.codexworld.com/multi-select-dropdown-list-with-checkbox-jquery/jquery.multiselect.js"></script> -->

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/js/select2.min.js" type="text/javascript"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet" /> -->
<style>
    .overlay{
        display: none;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 999;
        background: rgba(255,255,255,0.8) url("/examples/images/loader.gif") center no-repeat;
    }
    content-wrapper{
        text-align: center;
    }
    /* Turn off scrollbar when body element has the loading class */
    content-wrapper.loading{
        overflow: hidden;   
    }
    /* Make spinner image visible when body element has the loading class */
    content-wrapper.loading .overlay{
        display: block;
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
    <li class="">H2</li>
    <li class="">3 Axis Analysis</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <?php if($set=="view"){ ?>
  <section class="content">
    <div class="box box-default">
      <div class="box-header with-border"> 
      <?php if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {?>
          <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
          </div>
      <?php } $_SESSION['pesan'] = ''; ?>
      <div class="alert alert-warning alert-dismissable">
            <strong>Perhatian! Mohon Syncronisasi Data agar Data Terupdate</strong>
            <button class="close" data-dismiss="alert">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
          </div>       
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" id="frm" method="POST" action= "h2/H2_md_customer_list/export_excel" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pilih AHASS Tujuan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="pilih_ahass" id="pilih_ahass">
                      <option selected disabled>Pilih AHASS</option>
                      <?php foreach($dt_dealer as $dealer) : ?>
                      <option value="<?php echo $dealer->id_dealer?>"><?php echo $dealer->kode_dealer_ahm?> - <?php echo $dealer->nama_dealer?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>                                  
                </div>                                                              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" name="no_mesin" id="no_mesin" placeholder="Cari Nomor Mesin" disabled>
                  </div>  
                  <label for="inputEmail3" class="col-sm-2 control-label">Active Passive</label>
                  <div class="col-sm-4">
                    <select class="form-control form-select" aria-label="Default select example" name="active_passive" id="active_passive" disabled>
                        <option selected disabled>Active/Passive</option>
                        <option value="active">Active</option>
                        <option value="passive">Passive</option>
                    </select>
                  </div>                                     
                </div>   
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Frekuensi Service</label>
                  <div class="col-sm-4">
                    <select class="form-control form-select" aria-label="Default select example" name="frekuensi_service" id="frekuensi_service" disabled>
                        <option selected disabled>Pilih Frekuensi Service Terakhir</option>
                        <option value="kurang_dr_5"> 0-5 </option>
                        <option value="rentang_6_10"> 6-10 </option>
                        <option value="rentang_11_20">11-20</option>
                        <option value="lebih_dr_21"> >21</option>
                    </select>
                  </div>  
                  <label for="inputEmail3" class="col-sm-2 control-label">Avg Rp/UE</label>
                  <div class="col-sm-4">
                    <select class="form-control form-select" aria-label="Default select example" name="avg_rp_ue" id="avg_rp_ue" disabled>
                        <option selected disabled>Pilih Avg Rp/UE</option>
                        <option value="kurang_dr_9"> 0-100.000 </option>
                        <option value="rentang_1_4"> 100.001-250.000 </option>
                        <option value="rentang_2_5"> 250.001-500.000 </option>
                        <option value="lebih_dr_5"> >500.000</option>
                    </select>
                  </div>                                     
                </div> 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Waktu Service Terakhir (Bulan)</label>
                  <div class="col-sm-4">
                    <select class="form-control form-select" aria-label="Default select example" name="waktu_service_terakhir" id="waktu_service_terakhir" disabled>
                        <option selected disabled>Pilih Waktu Service Terakhir (Bulan)</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                    </select>
                  </div>  
                  <label for="inputEmail3" class="col-sm-2 control-label">Profesi</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" aria-label="Default select example" name="profesi" id="profesi" disabled>
                        <option selected disabled>Pilih Profesi</option>
                        <?php foreach($pekerjaan as $kerja) : ?>
                            <option value="<?php echo $kerja->id_pekerjaan?>"><?php echo $kerja->pekerjaan?></option>
                        <?php endforeach; ?>
                    </select>
                  </div>                                     
                </div> 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Status FU</label>
                  <div class="col-sm-4">
                    <select class="form-control form-select" aria-label="Default select example" name="status_fu" id="status_fu" disabled>
                        <option selected disabled>Pilih Status FU</option>
                        <?php foreach($listFU as $list) : ?>
                            <option value="<?php echo $list->id_kategori_status_komunikasi?>"><?php echo $list->kategori_status_komunikasi?></option>
                        <?php endforeach; ?>
                    </select>
                  </div>  
                  <label for="inputEmail3" class="col-sm-2 control-label">Last FU</label>
                  <div class="col-sm-4">
                    <input id='last_fu' type="text" class="form-control" placeholder="Cari Last FU" disabled>
                    <input id='last_fu_start' name="last_fu_start" type="hidden" readonly>
                    <input id='last_fu_end' name="last_fu_end" type="hidden" readonly>
                    <!-- <div class="form-group">
                      <div class="col-sm-2">
                        <input type="text" class="form-control datepicker" name="tgl1" value="Start Date" id="tanggal1">
                      </div>
                      <div class="col-sm-2" style="margin-left:-10px">
                        <input type="text" class="form-control datepicker" name="tgl2" value="End Date" id="tanggal2">
                      </div>
                    </div> -->
                  </div>  
                                              
                </div> 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Gender</label>
                  <div class="col-sm-4">
                    <select class="form-control form-select" aria-label="Default select example" name="gender" id="gender" disabled>
                        <option selected disabled>Gender</option>
                        <option value="Perempuan">Wanita</option>
                        <option value="Laki-laki">Laki-Laki</option>
                    </select>
                  </div>  
                  <label for="inputEmail3" class="col-sm-2 control-label">KM</label>
                  <div class="col-sm-4">
                    <select class="form-control form-select" aria-label="Default select example" name="km_terakhir" id="km_terakhir" disabled>
                        <option selected disabled>Pilih KM</option>
                        <option value="kurang_dr_999"> 0-999 </option>
                        <option value="antara_1000_1999"> 1000-1999 </option>
                        <option value="antara_2000_3999"> 2000-3999 </option>
                        <option value="antara_4000_7999"> 4000-7999 </option>
                        <option value="antara_8000_9999"> 8000-9999 </option>
                        <option value="antara_10000_11999"> 10000-11999 </option>
                        <option value="antara_12000_15999"> 12000-15999 </option>
                        <option value="antara_16000_23999"> 16000-23999 </option>
                        <option value="lebih_dr_24000"> >24.000 </option>
                    </select>
                  </div>                                     
                </div> 
                <div class="form-group" >
                  <label for="inputEmail3" class="col-sm-2 control-label">M/C Type</label>
                  <div class="col-sm-4" id="filter_mc_type">
                    <div class="input-group">
                      <!-- <input name='mc_type[]' id='mc_type' type="hidden" disabled> -->
                      <!-- <input type="text" id='ekspedisi' class="form-control" readonly> -->
                      <input type="hidden" :value='filter_mc_type.filters' name="filters_id_tipe_kendaraan" id='id_tipe_kendaraan'>
                      <input :value='filters.length + " M/C Type"'  type="text" class="form-control" placeholder="Cari Tipe Kendaraan" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h2_list_fu' disabled id="id_tipe_kendaraan2"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h2_list_fu'); ?>
                  </div>  
                  <label for="inputEmail3" class="col-sm-2 control-label">Ring</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" disabled>
                  </div>                                     
                </div> 
                    
                <div class="form-group">
                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">AHASS Last FU</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control datepicker" name="tgl1" value="<?= date('Y-m-d') ?>" id="tanggal1">
                  </div>  -->
                  
                       <label for="inputEmail3" class="col-sm-2 control-label">AHASS Last FU</label>
                      <div class="col-sm-4">
                        <select class="form-control select2" name="ahass_last_fu" id="ahass_last_fu" disabled>
                            <option selected disabled>AHASS Last FU</option>
                            <?php foreach($dt_dealer as $dealer) : ?>
                                <option value="<?php echo $dealer->id_dealer?>"><?php echo $dealer->kode_dealer_ahm?> - <?php echo $dealer->nama_dealer?></option>
                            <?php endforeach; ?>
                        </select>
                      </div>

               
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun Motor</label>
                  <div class="col-sm-4">
                    <!-- <input type="text" class="form-control" name="tahun_motor"  id="tahun_motor" placeholder="Tahun Motor"> -->
                    <input id='tahun_motor' type="text" name="tahun_motor"  id="tahun_motor" class="form-control" placeholder="Tahun Motor" disabled>
                    <input id='tahun_motor_value' type="hidden">
                  </div>                
                                       
                </div> 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Customer Segment</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" aria-label="Default select example" id="customer_segment" name="customer_segment" disabled>
                        <option selected disabled>Pilih Customer Segment</option>
                    </select>
                  </div>  
                  <label for="inputEmail3" class="col-sm-2 control-label">Last TOJ</label>
                  <div class="col-sm-4" id="filter_toj">
                    <div class="input-group" >
                      <input type="hidden" :value='filter_toj.filters' name="filters_last_toj" id='last_toj'>
                      <input :value='filters.length + " ToJ"'  type="text" class="form-control" placeholder="Cari Last ToJ" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h2_last_toj' disabled id="last_toj2"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h2_last_toj'); ?>
                  </div>  
                </div>           
                
                <div class="form-group">
                  <div class="col-sm-1" style="margin-right: -12px;">
                    <button type="button" id="btn-reset" name="process" value="reset" class="btn btn-sm bg-maroon "><i class="fa fa-history"></i> Reset</button>                                                      
                  </div>   
  		            <div class="col-sm-1" style="margin-right: -12px;">
                    <button type="button" name="process" value="submit2" id="btn-search" onclick="submit_data()" class="btn btn-sm bg-blue " disabled><i class="fa fa-search"></i> Search</button>                                                      
                  </div>
                  <div class="col-sm-1" style="margin-right: 17px;">
                    <button type="submit" name="process" value="export_excel" id="btn-excel" class="btn btn-sm btn-success" disabled><i class="fa fa-download" ></i> Export Excel</button>                                                      
                  </div>
                  <!-- href="dealer/h2_dealer_customer_list/generate_data" -->
                  <div class="col-sm-1" style="margin-right: 24px;">
                    <button type="button" name="process" value="generateData"  id="btn-generate" class="btn btn-sm btn-primary btn_generate" onclick="generate_data()" disabled> <i class="fa fa-upload"> </i> Generate Data </button>                                                      
                  </div>
                  <div class="col-sm-1">
                    <button type="button" id="btn-sync" name="process" value="sync" class="btn btn-sm bg-yellow " onclick="sync_data()"><i class="fa fa-refresh" aria-hidden="true"> </i> Sync Data</button>                                                      
                  </div>
                  <div id="loading" class="col-sm-4">
                    <p> Please Wait... Proses Sinkronisasi</p>
                  </div>
                  <div id="loading_export_excel" class="col-sm-4">
                    <p> Please Wait... Sedang Proses Export Excel</p>
                  </div>
                  <div id="loading_generate_data" class="col-sm-4">
                    <p> Please Wait... Sedang Proses Generate Data</p>
                  </div>
                </div>        
              </div><!-- /.box-body -->              
            </form>
            
            <table class="table table-striped table-bordered table-hover table-condensed" id="list_fu_table" style="width: 100%">
              <thead>
                <tr>
                  <th>#</th>
                  <th>No Mesin</th>
                  <th>Nama</th>
                  <th>Nomor Hp</th>
                  <th>M/C Type</th>
                  <th>Tahun Motor</th>
                  <th>Frekuensi Service</th>
                  <th>KM Terakhir</th>
                  <th>Waktu Service Terakhir (Bulan)</th>
                  <th>Avg Rp/UE</th>
                  <th>Last ToJ</th>
                  <th>Profesi</th>
                  <th>Pending Item</th>
                  <th>Status FU</th>
                  <th>Last FU</th>
                  <th>Customer Segment</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>

          
        </div>
      </div>
    </div><!-- /.box -->
  </section>
  </html>
  <?php }elseif($set=="generate"){?>
    
  <?php }elseif($set=="detail"){?>
    <section class="content">
      <div class="box box-default">
      
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/h2_md_customer_list">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-arrow-left"></i> Kembali </button>
            </a>
          </h3>
          <form class="form-horizontal" id="frm" method="post" action= "" enctype="multipart/form-data">
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <h2 class="box-title">
                    <h4><b>Profil Customer</b></h4>
                    <input type="hidden" name="id_customer" value="<?php echo $getDetailData->row()->id_customer ?>">
                  </h2>
                    <div class="row">
                      <div class="col-sm-6">
                        <table class="table">
                          <tr>
                              <td>Nama</td>
                              <td>:</td>
                              <td><?php echo $getDetailData->row()->nama_customer ?></td>
                          </tr>
                          <tr>
                              <td>Tanggal Lahir</td>
                              <td>:</td>
                              <td><?= $getDetailData->row()->tgl_lahir ?></td>
                          </tr>
                          <tr>
                              <td>Asal Provinsi</td>
                              <td>:</td>
                              <td><?php echo $getDetailData->row()->provinsi ?></td>
                          </tr> 
                          <tr>
                              <td>No.Mesin</td>
                              <td>:</td>
                              <td><?php echo $getDetailData->row()->no_mesin ?></td>
                          </tr> 
                          <tr>
                              <td>No.Rangka</td>
                              <td>:</td>
                              <td><?php echo $getDetailData->row()->no_rangka ?></td>
                          </tr> 
                          <tr>
                              <td>No.Plat</td>
                              <td>:</td>
                              <td><?php echo $getDetailData->row()->no_polisi ?></td>
                          </tr> 
                          <tr>
                              <td>Tahun Motor</td>
                              <td>:</td>
                              <td><?php echo $getDetailData->row()->tahun_produksi ?></td>
                          </tr> 
                        </table>
                      </div>
                      <div class="col-sm-6">
                        <table class="table">
                        <tr>
                              <td>No Hp</td>
                              <td>:</td>
                              <td><?=$getDetailData->row()->hp_pembawa?> <td>
                          </tr> 
                          <tr>
                              <td>Email</td>
                              <td>:</td>
                              <td><?=$getDetailData->row()->email?> <td>
                          </tr> 
                          <tr>
                              <td>Pengguna Motor</td>
                              <td>:</td>
                              <td><?php echo $getDetailData->row()->nama_pengguna  ?></td>
                          </tr>
                          <tr>
                              <td>Tujuan Penggunaan Motor</td>
                              <td>:</td>
                              <td><?=$getDetailData->row()->tujuan_penggunaan_motor?></td>
                          </tr> 
                          <tr>
                              <td>Last ToJ</td>
                              <td>:</td>
                              <td><?php echo $getDetailData->row()->deskripsi ?> ( <?php echo $getDetailData->row()->tgl_servis ?> )</td>
                          </tr> 
                          <tr>
                              <td>Pending Item</td>
                              <td>:</td>
                              <td> - </td>
                          </tr>  
                          <tr>
                              <td>Catatan SA/Mekanik</td>
                              <td>:</td>
                              <td><?= $getDetailData->row()->saran_mekanik ?></td>
                          </tr>
                        </table>
                      </div>
                    </div>
                  
                </div>
              </div>
            </div>
            <!-- <div class=" box-footer">
              <div class="col-sm-12" align="center">
                <button type="submit" id="submitBtn" name="edit" value="edit" class="btn btn-info btn-block btn-flat"><i class="fa fa-save"></i> Edit</button>
              </div> -->
            </div>
          </form>
        </div>
      </div>
    </section>
  <?php } ?>
  </section>
</div>



<!-- <script src="http://demos.codexworld.com/multi-select-dropdown-list-with-checkbox-jquery/jquery.multiselect.js"></script> -->
<script>
    $('#last_fu').daterangepicker({
      opens: 'left',
      autoUpdateInput: false,
      locale: {
          format: 'DD/MM/YYYY'
      }
    }, function(start, end, label) {
      $('#last_fu_start').val(start.format('YYYY-MM-DD'));
      $('#last_fu_end').val(end.format('YYYY-MM-DD'));
      // list_fu_table.draw();
    }).on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    }).on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
      $('#last_fu_start').val('');
      $('#last_fu_end').val('');
      // list_fu_table.draw();
    });

    filter_mc_type = new Vue({
      el: '#filter_mc_type',
      data: {
        filters: []
      },
        watch: {
          filters: function(){
            // list_fu_table.draw();
          }
        }
    });

    $("#h2_list_fu").on('change',"input[type='checkbox']",function(e){
      target = $(e.target);
      id_tipe_kendaraan = target.attr('data-id_tipe_kendaraan');

      if(target.is(':checked')){
        filter_mc_type.filters.push(id_tipe_kendaraan);
      }else{
        index_picker = _.indexOf(filter_mc_type.filters, id_tipe_kendaraan);
        filter_mc_type.filters.splice(index_picker, 1);
      }
        // h2_list_fu_table.draw();
      });

      filter_toj = new Vue({
      el: '#filter_toj',
      data: {
        filters: []
      },
        watch: {
          filters: function(){
            // list_fu_table.draw();
          }
        }
    });

    $("#h2_last_toj").on('change',"input[type='checkbox']",function(e){
      target = $(e.target);
      last_toj = target.attr('data-last_toj');

      if(target.is(':checked')){
        filter_toj.filters.push(last_toj);
      }else{
        index_picker = _.indexOf(filter_toj.filters, last_toj);
        filter_toj.filters.splice(index_picker, 1);
      }
        // h2_list_fu_table.draw();
      });


      // $('#last_toj').multiselect({
      //   columns: 1,
      //   placeholder: 'Pilih Last ToJ',
      //   search: true,
      //   selectAll: true,
      //   // onEventName: function(){
      //   //   alert("coba kuy")
      //   // }
      // });

      function drawing_fu_table(){
      list_fu_table=$('#list_fu_table').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        searching: false,
        scrollX: true,
        bDestroy:true,
        ajax: {
          url: "<?= base_url('h2/H2_md_customer_list/getDataTable') ?>",
          dataSrc: "data",
          type: "POST",
          data: function(d){
            d.no_mesin = $('#no_mesin').val();
            d.active_passive = $('#active_passive').val();
            d.frekuensi_service = $('#frekuensi_service').val();
            d.avg_rp_ue = $('#avg_rp_ue').val();
            d.profesi = $('#profesi').val();
            d.km_terakhir = $('#km_terakhir').val();
            d.tahun_motor = $('#tahun_motor_value').val();
            d.status_fu = $('#status_fu').val();
            d.filter_mc_type = filter_mc_type.filters;
            // d.mc_type = $('#mc_type').val();
            // d.last_toj = $('#last_toj').val();
            d.filter_toj = filter_toj.filters;
            d.gender = $('#gender').val();
            d.waktu_service_terakhir = $('#waktu_service_terakhir').val();
            start_date = $('#last_fu_start').val();
            end_date = $('#last_fu_end').val();
            if ((start_date != undefined && start_date != '') && (end_date != undefined && end_date != '')) {
                d.filter_last_fu = true;
                d.start_date = start_date;
                d.end_date = end_date;
            }
            // d.hasil=filter_mc_type.filters.val();
            // alert(filter_mc_type.filters);
            // $('#id_tipe_kendaraan').val(d.filter_mc_type);
            d.id_tipe_kendaraan =  $('#id_tipe_kendaraan').val();
            d.pilih_ahass =  $('#pilih_ahass').val();
            
            // alert(d.id_tipe_kendaraan);
          }
        },
        columns: [
          { data: 'index', orderable: false, width: '3%' },
          { data: 'no_mesin' },
          { data: 'nama_pembawa', width: '180px' },
          { data: 'no_hp_pembawa' },
          { data: 'id_tipe_kendaraan', width: '180px' },
          { data: 'tahun_motor' },
          { data: 'frekuensi_service', orderable: false },
          { data: 'km_terakhir' },
          { data: 'tgl_servis', width: '100px' },
          { data: 'total_jasa', orderable: false },
          { data: 'deskripsi', width: '100px' },
          { data: 'pekerjaan' },
          { data: 'pending_item', orderable: false },
          { data: 'status_fu', orderable: false},
          { data: 'tgl_fol_up' },
          { data: 'customer_segment', orderable: false },
          { data: 'action', width: '3%', orderable:false }
        ],
      });
    }

    function submit_data() {
        // $('#list_fu_table').DataTable().ajax.reload();
        drawing_fu_table();
    }

    $(document).ready(function(){
      $('#tahun_motor').datepicker({
          autoclose: true,
          format: "yyyy",
          viewMode: "years", 
          minViewMode: "years",
          clearBtn: true,
      }).on('changeDate', function(e){
          $('#tahun_motor_value').val(e.format('yyyy'));
            // list_fu_table.draw();
      });

      $('#tgl_fu').datepicker({
          autoclose: true,
          format: "dd-mm-yyyy",
          clearBtn: true,
      });


      $('#loading').hide();
      $('#loading_export_excel').hide();
      $('#loading_generate_data').hide();
      // $('#last_toj2').hide();
      

    $('#btn-reset').click(function(){
			$('#no_mesin').val('');
      $('#frekuensi_service').val('');
			history.go(0);
		});

    // $('#no_mesin').on('keyup', _.debounce(function(){
    //     list_fu_table.draw();
    //   }, 500));

    // $('#active_passive').on('click', _.debounce(function(){
    //     list_fu_table.draw();
    //   }, 500));

    // $('#status_fu').on('click', _.debounce(function(){
    //     list_fu_table.draw();
    //   }, 500));

    // $('#frekuensi_service').on('click', _.debounce(function(){
    //     list_fu_table.draw();
    //   }, 500));
    // $('#avg_rp_ue').on('click', _.debounce(function(){
    //     list_fu_table.draw();
    //   }, 500));
    // $('#km_terakhir').on('click', _.debounce(function(){
    //     list_fu_table.draw();
    //   }, 500));
    // $('#profesi').on('select2:select', function (e) {
    //     list_fu_table.draw();
    //   });
      
    $('#pilih_ahass').on('select2:select', function (e) {
      $("#no_mesin").removeAttr('disabled');
      $("#frekuensi_service").removeAttr('disabled');
      $("#avg_rp_ue").removeAttr('disabled');
      $("#waktu_service_terakhir").removeAttr('disabled');
      $("#profesi").removeAttr('disabled');
      $("#status_fu").removeAttr('disabled');
      $("#last_fu").removeAttr('disabled');
      $("#gender").removeAttr('disabled');
      $("#km_terakhir").removeAttr('disabled');
      $("#id_tipe_kendaraan2").removeAttr('disabled');
      $("#tahun_motor").removeAttr('disabled');
      $("#active_passive").removeAttr('disabled');
      // $('#last_toj2').show();
      $('#last_toj2').removeAttr('disabled');
      $("#btn-generate").removeAttr('disabled');
      $("#btn-search").removeAttr('disabled');
      $("#btn-excel").removeAttr('disabled');
    });

  //   $('#waktu_service_terakhir').on('click', _.debounce(function(){
  //       list_fu_table.draw();
  //     }, 500));

  //   $('#gender').on('click', _.debounce(function(){
  //       list_fu_table.draw();
  //     }, 500));
    
  });

  // $('#last_toj').change(function() {
  //           // table.ajax.reload();
  //           list_fu_table.draw();
  //       });

        

  const generate_data = () => {
    if(confirm('Yakin data akan di-generate?')){
      $.ajax({
                type: "POST",
                url: "<?= base_url('h2/h2_md_customer_list/save_generate') ?>",
                dataType: "JSON",
                beforeSend: function(){ $('#loading_generate_data').show();},
                complete: function() { $('#loading_generate_data').hide(); }, 
                data: {
                    id_customer: $('#id_customer').val(),
                    no_mesin: $('#no_mesin').val(),
                    active_passive: $('#active_passive').val(),
                    frekuensi_service: $('#frekuensi_service').val(),
                    avg_rp_ue: $('#avg_rp_ue').val(),
                    profesi: $('#profesi').val(),
                    km_terakhir: $('#km_terakhir').val(),
                    tahun_motor: $('#tahun_motor_value').val(),
                    filter_mc_type: filter_mc_type.filters,
                    filter_toj: filter_toj.filters,
                    // d.mc_type = $('#mc_type').val();
                    last_toj:$('#last_toj').val(),
                    waktu_service_terakhir: $('#waktu_service_terakhir').val(),
                    id_tipe_kendaraan:  $('#id_tipe_kendaraan').val(),
                    status_fu:$('#status_fu').val(),
                    last_fu_start:$('#last_fu_start').val(),
                    last_fu_end:$('#last_fu_end').val(),
                    gender:$('#gender').val(),
                    pilih_ahass:$('#pilih_ahass').val(),
                },
                // cache: false,
                success: function(Result) {
                    const {
                        status,
                        message,
                        data
                    } = Result;

                    if (status) {
                        alert('Data berhasil digenerate');
                    } else {
                        alert('Data gagal di generate');
                    }
                },
                error: function(x, y, z) {
                    alert('Data gagal di generate');
                }
      });
    }
            
  }

  const sync_data = () => {
      $.ajax({
                type: "POST",
                url: "<?= base_url('h2/h2_md_customer_list/sync_data') ?>",
                dataType: "JSON",
                beforeSend: function(){ $('#loading').show();},
                complete: function() { $('#loading').hide(); }, 
                data: {},
                // cache: false,
                success: function(Result) {
                    const {
                        status,
                        message,
                        data
                    } = Result;

                    if (status) {
                        alert('Data berhasil disinkronisasi');
                    } else {
                        alert('Data gagal disinkronisasi');
                    }
                },
                error: function(x, y, z) {
                    alert('Data gagal disinkronisasi');
                }
      });
  }
  

</script>     