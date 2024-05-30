<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>" type="text/javascript"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <?= $breadcrumb ?>
  </section>

  <section class="content">
      <div class="box">
        <div class="box-header with-border">
            <div class="container-fluid">
              
              <!-- <form class="form" action="h3/h3_md_autofulfillment/index" method="post"> -->
              <div class="row">
                <div class="col-sm-3">
                    <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                        <label for="" class="control-label">AHASS/Toko <span style="color:red">*</span></label>
                        <div class="input-group">
                          <select class="form-control select2" name="pilih_ahass" id="pilih_ahass">
                            <option selected disabled>Pilih AHASS</option>
                            <?php foreach($dt_dealer as $dealer) : ?>
                            <option value="<?php echo $dealer->id_dealer?>"><?php echo $dealer->kode_dealer_ahm?> - <?php echo $dealer->nama_dealer?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                        <label for="" class="control-label">Jenis PO<span style="color:red">*</span></label>
                          <select class="form-control form-select" aria-label="Default select example" name="jenis_po" id="jenis_po" style="width: 100%">
                            <option value="FIX"> Fix </option>
                            <option value="REG"> Reguler </option>
                          </select>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                        <label for="" class="control-label">Kelompok Produk<span style="color:red">*</span></label>
                          <select class="form-control form-select" aria-label="Default select example" name="kelompok_part_besar" id="kelompok_part_besar" style="width: 100%">
                            <option value="Parts"> HGP </option>
                            <option value="Acc"> HGA </option>
                            <option value="Oil"> Oil </option>
                            <option value="Other"> Other </option>
                          </select>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                        <label for="" class="control-label">Kategori<span style="color:red">*</span></label>
                          <select class="form-control form-select" aria-label="Default select example" name="kategori_sim_part" id="kategori_sim_part" style="width: 100%">
                            <option value=""> ALL </option>
                            <option value="1"> Sim Part </option>
                            <option value="0"> Non Sim Part </option>
                          </select>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                        <label for="" class="control-label">Type Waktu<span style="color:red">*</span></label>
                          <select class="form-control form-select" aria-label="Default select example" name="tipe_waktu" id="tipe_waktu" style="width: 100%">
                            <option value="week"> Mingguan </option>
                            <option value="month"> Bulanan </option>
                          </select>
                        </div>
                    </div>
                    </div>
                </div> 

                <div class="col-sm-3">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="" class="control-label">Periode Mulai</label>
                                <div class="input-group">
                                    <input id="tanggal_start_periode" name="tanggal_start_periode" type="text" class="form-control datepicker" value="<?= date('Y-m-d', time()) ?>">
                                    <div class="input-group-btn">
                                        <button class="btn btn-flat btn-primary" type='button' disabled><i class="fa fa-calendar"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                  $(document).ready(function(){
                    $('#tanggal_start_periode').change(function(e){
                      start_point = $("#tanggal_start_periode").datepicker("getDate");
                      six_weeks_before = new Date(start_point.getTime() - (6*(60*60*24*7*1000)));
                      // start_point_formatted = $.datepicker.formatDate("dd/mm/yy", start_point);
                      // six_weeks_before_formatted = $.datepicker.formatDate("dd/mm/yy", six_weeks_before);
                      // $html = '<span>Perhitungan analisis ranking dimulai di periode ' + start_point_formatted + ' - ' + six_weeks_before_formatted + '</span>';
                      // $('#date_info').html($html);
                    });
                  });
                </script>
               
              </div>

              <div class="row">
                  <div class="col-sm-1">
                    <button type="button" name="process" value="saveData"  id="btn-save_data" class="btn btn-sm btn-primary" onclick="save_data()" disabled> <i class="fa fa-upload"> </i>Suggest Order </button>                                                      
                  </div>
                  <div class="col-sm-1" style="margin-left: 40px;">
                    <button type="submit" name="viewData" value="viewData"  id="btn-view_data" class="btn btn-sm btn-success" onclick="view_data()" disabled> <i class="fa fa-upload"> </i> View Data </button>                                                      
                  </div>
              </div>
            </div>      
        </div>      
      </div><!-- /.box -->

      <div class="box" id="data_autofulfillment_ahass">
          <div id="date_info" class="row text-center h4">
                  <br>
                  <span id="nama_dealer"></span>
          </div>
          <div id='loading-overlay' class="overlay" style="display: none;">
            <i class="fa fa-refresh fa-spin text-light-blue"></i>
          </div>
          <div class="row">
              <div class="col-sm-2" style="margin-left: 10px;">
                <div class="form-group">
                  <label for="" class="control-label">Filter SIM Part</label>
                  <select class="form-control form-select" aria-label="Default select example" name="kategori_part" id="kategori_part" style="width: 100%">
                      <option value="all"> All </option>
                      <option value="sim_part"> SIM PART </option>
                      <option value="non_sim_part"> NON SIM PART </option>
                  </select>
                </div>
              </div>
              <div class="col-sm-2" style="margin-left: 10px;">
                <div class="form-group">
                 <label for="" class="control-label">Filter Rank</label>
                  <select class="form-control form-select" aria-label="Default select example" name="filter_rank" id="filter_rank" style="width: 100%">
                      <option value=""> All </option>
                      <option value="A"> A </option>
                      <option value="B"> B </option>
                      <option value="C"> C </option>
                      <option value="D"> D </option>
                      <option value="E"> E </option>
                      <option value="F"> F </option>
                  </select>    
                </div>
              </div> 
              <div class="col-sm-2" style="margin-left: 10px;">
                <div class="form-group">
                 <label for="" class="control-label">Filter 15 HGP</label>
                  <select class="form-control form-select" aria-label="Default select example" name="filter_kelompok_hgp" id="filter_kelompok_hgp" style="width: 100%">
                      <option value=""> All </option>
                      <option value="tire"> Tire </option>
                      <option value="ahm"> AHM </option>
                      <option value="electrical_parts"> Electrical Parts </option>
                      <option value="drive_belt"> Drive Belt </option>
                      <option value="brake_system"> Brake System </option>
                      <option value="shock_absorber"> Shock Absorber </option>
                      <option value="piston_kit"> Piston Kit </option>
                      <option value="battery"> Battery </option>
                      <option value="disk_clutch"> Disk Clutch </option>
                      <option value="drive_chain_kit"> Drive Chain Kit (HGP) </option>
                      <option value="palstik_part"> Plastik Part </option>
                      <option value="element_cleaner"> Element Cleaner </option>
                      <option value="sparkplug"> SparkPlug </option>
                      <option value="bearing"> Bearing </option>
                      <option value="oil_seal"> Oil Seal </option>
                      <option value="others"> Others </option>
                  </select>    
                </div>
              </div>
              <div class="col-sm-2" style="margin-left: 10px;">
                <div class="form-group">
                 <label for="" class="control-label">Cari Kode Part</label>
                 <input type="text" class="form-control" name="id_part" id="id_part" placeholder="Cari Kode Part">
                </div>
              </div> 
              <div class="col-sm-2">
                <div class="form-group">
                  <br>
                      <button type="button" class="btn btn-primary btn-sm" id="btn-kategori_part"><span class="fa fa-search"></span></button>
                </div>
              </div> 
          </div>
          <div class="box-body">
            <div class="table-responsive">
              <table id="suggested_order" class="table table-bordered table-hover table-condensed">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Part Number</th>
                    <th>Part Deskripsi</th>
                    <th>Rata - rata 6 (W/M) Periode</th>
                    <!-- <th>Akumulasi Qty</th>
                    <th>Akumulasi %</th> -->
                    <th>Rank</th>
                    <th>T-1</th>
                    <th>T-2</th>
                    <th>T-3</th>
                    <th>T-4</th>
                    <th>T-5</th>
                    <th>T-6</th>
                    <th>Stock On Hand</th>
                    <th>SIM Part</th>
                    <th>On Order</th>
                    <th>Stock In Transit</th>
                    <th>Stock Days</th>
                    <th>Suggested Order</th>
                    <th>Adjusted Order</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <br>
            <div class="col-sm-12">
              <a id="create_so" href="h3/h3_md_sales_order/add" style="margin-right: 30px;margin-left: 10px;" class="pull-right btn btn-flat btn-sm btn-primary">Create SO</a>
              <button id="generatePart" onclick="generate_part_auto()" class="pull-right btn btn-flat btn-sm btn-success">Create PO</button>
           </div> 
          </div><!-- /.box-body -->     
      </div>
      <script>
        $(document).ready(function() {

          $('#pilih_ahass').on('select2:select', function (e) {
            $('#btn-save_data').removeAttr('disabled');
            $('#btn-view_data').removeAttr('disabled');
            
          });

          $('#generatePart').hide();
          $('#create_so').hide();
          
          $('#data_autofulfillment_ahass').hide();
          
        
        });

        function drawing_auto_table(){
          suggested_order = $('#suggested_order').DataTable({
              initComplete: function() {
                  $('#suggested_order_length').parent().removeClass('col-sm-6').addClass('col-sm-2');
                  $('#suggested_order_filter').parent().removeClass('col-sm-6').addClass('col-sm-10');
                  axios.get('html/filter_suggested_order')
                      .then(function(res) {
                          $('#suggested_order_filter').prepend(res.data);

                          $('#filter_prioritas_order_suggested_order').change(function() {
                              suggested_order.draw();
                          });
                      });
              },
              processing: true,
              serverSide: true,
              ordering: false,
              bDestroy:true,
              searching: false,
              order: [],
              ajax: {
                  url: "<?= base_url('h3/h3_md_autofulfillment/autofulfillment_table') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    kelompok_part = [];
                    $('.kelompok_part_checkbox:checkbox:checked').each(function(i){
                      kelompok_part[i] = $(this).val();
                    });
                    if(kelompok_part.length > 0){
                      d.kelompok_part = kelompok_part;
                    }
                    d.tanggal_start_periode= $('#tanggal_start_periode').val();
                    d.tipe_waktu = $('#tipe_waktu').val();
                    d.pilih_ahass = $('#pilih_ahass').val();
                    d.kelompok_part_besar = $('#kelompok_part_besar').val();
                    d.jenis_po = $('#jenis_po').val();
                    d.kategori_part = $('#kategori_part').val();
                    d.id_part = $('#id_part').val();
                    d.deskripsi_part = $('#deskripsi_part').val();
                    d.kategori_sim_part =  $('#kategori_sim_part').val();
                    d.filter_rank = $('#filter_rank').val();
                    d.filter_kelompok_hgp = $('#filter_kelompok_hgp').val();
                    // d.kel_part_filter = kel_part_filter.filters;
                  }
              },
              createdRow: function (row, data, index) {
                $('td', row).addClass('align-middle');
              },
              columns: [
                  { data: 'index', width: '3%', orderable: false },
                  { 
                    data: 'id_part', 
                    width: '150px',
                    
                  },
                  { 
                    data: 'nama_part', 
                    width: '200px',
                  },
                  { 
                    data: 'avg_six_weeks',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  // { 
                  //   data: 'akumulasi_qty',
                  //   render: function(data){
                  //     return accounting.formatMoney(data, "", 0, ".", ",");
                  //   }
                  // },
                  // { 
                  //   data: 'akumulasi_persen',
                  //   render: function(data){
                  //     return accounting.formatMoney(data, "", 0, ".", ",");
                  //   }
                  // },
                  { 
                    data: 'rank'
                  },
                  { 
                    data: 'w1',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'w2',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'w3',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'w4',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'w5',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'w6',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'stock',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    },
                    createdCell: function (td, cellData, rowData, row, col) {
                        $(td).addClass('bg-aqua-active');
                    }
                  },
                  { 
                    data: 'sim_part',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'on_order',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    },
                    createdCell: function (td, cellData, rowData, row, col) {
                        $(td).addClass('bg-aqua-active');
                    }
                  },
                  { 
                    data: 'in_transit',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'stock_days',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'suggested_order',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    },
                    createdCell: function (td, cellData, rowData, row, col) {
                        $(td).addClass('bg-aqua-active');
                    }
                  },
                  { data: 'adjust_order' },
              ],
          }).on('preDraw', function () {
            $('#loading-overlay').show();
          })
          .on('draw.dt', function () {
            $('#loading-overlay').hide();
            
            // var tglStartPeriode_bulan = moment($('#tanggal_start_periode').val()).subtract(6,'months');
            // var tglEndPeriode_bulan = tglStartPeriode_bulan.format('YYYY-MM-DD');
            if(suggested_order.row(0).data() != null){
              var data = suggested_order.row(0).data();
              var namaDealer = data.nama_dealer;
              var tgl = data.periode_start;
              if($('#tipe_waktu').val()=='week'){
                var tglStartPeriode = moment(tgl).subtract(6,'weeks');
                var tglEndPeriode = tglStartPeriode.format('YYYY-MM-DD');
              }else{
                var tglStartPeriode = moment(tgl).subtract(6,'months');
                var tglEndPeriode = tglStartPeriode.format('YYYY-MM-DD'); 
              }
            }else{
              var tgl = "-";
              var namaDealer = "";
              var tglEndPeriode = "-";
            }
            $('#nama_dealer').html(namaDealer);
            
            $('#generatePart').show();
            $('#create_so').show();
          });
        }

        function view_data(){
          $('#data_autofulfillment_ahass').show();
          drawing_auto_table();
        }

        $('#btn-kategori_part').click(function(e){
          e.preventDefault();
          drawing_auto_table();
        });
        
        const save_data = () => {
          if($('#pilih_ahass').val()!=null && $('#kelompok_part_besar').val()!=null){
            if(confirm('Yakin data akan di-save?')){
              $.ajax({
                        type: "POST",
                        url: "<?= base_url('h3/h3_md_autofulfillment/save_data') ?>",
                        dataType: "JSON",
                        beforeSend: function(){ 
                          // $('#btn-save_data').prop("disabled", true);
                          $('#btn-save_data').attr('disabled', true);
                          $('#btn-save_data').html('<i class="fa fa-spinner fa-spin">');},
                        // complete: function() { $('#btn-save_data').attr('disabled', true); }, 
                        data: {
                            tanggal_start_periode: $('#tanggal_start_periode').val(),
                            tipe_waktu: $('#tipe_waktu').val(),
                            pilih_ahass: $('#pilih_ahass').val(),
                            kelompok_part_besar: $('#kelompok_part_besar').val(),
                            jenis_po: $('#jenis_po').val(),
                            kategori_sim_part: $('#kategori_sim_part').val(),
                            // kel_part_filter: kel_part_filter.filters,
                        },
                        // cache: false,
                        success: function(Result) {
                            const {
                                status,
                                message,
                                data
                            } = Result;

                            if (status) {
                                alert('Data berhasil disave');
                            } else {
                                alert('Data gagal disave');
                            }
                            
                            $('#btn-save_data').attr('disabled', false);
                            $('#btn-save_data').html('<i class="fa fa-upload"></i> Analisis Suggest Order');
                        },
                        error: function(x, y, z) {
                            alert('Data gagal disave');
                        }
              });
            }
          }else{
            alert("Silahkan isi Nama Dealer dan Kelompok Part");
          }     
        }

        const generate_part_auto = () => {
          if($('#pilih_ahass').val()!=null && $('#kelompok_part_besar').val()!=null){
            if(confirm('Yakin data akan di-save?')){
              $.ajax({
                        type: "POST",
                        url: "<?= base_url('h3/h3_md_autofulfillment/generate_part_auto') ?>",
                        dataType: "JSON",
                        beforeSend: function(){ 
                          $('#btn-save_data').attr('disabled', true);
                          $('#btn-save_data').html('<i class="fa fa-spinner fa-spin">');}, 
                        data: {
                            tanggal_start_periode: $('#tanggal_start_periode').val(),
                            tipe_waktu: $('#tipe_waktu').val(),
                            pilih_ahass: $('#pilih_ahass').val(),
                            kelompok_part_besar: $('#kelompok_part_besar').val(),
                            jenis_po: $('#jenis_po').val(),
                            // kel_part_filter: kel_part_filter.filters,
                        },
                        // cache: false,
                        success: function(Result) {
                            const {
                                status,
                                message,
                                data
                            } = Result;

                            if (status) {
                                alert('Berhasil Generate PO Dealer!');
                            } else {
                                alert('Data gagal disave');
                            }
                            
                            $('#btn-save_data').attr('disabled', false);
                            $('#btn-save_data').html('<i class="fa fa-upload"></i>Generate Parts Autofulfillment');
                        },
                        error: function(x, y, z) {
                            alert('Data gagal disave');
                            $('#btn-save_data').attr('disabled', false);
                            $('#btn-save_data').html('<i class="fa fa-upload"></i>Generate Parts Autofulfillment');
                        }
              });
            }
          }else{
            alert("Silahkan isi Nama Dealer dan Kelompok Part");
          }     
        }

      </script>
  </section>
</div>