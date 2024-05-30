<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script> 
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<base href="<?php echo base_url(); ?>" /> 
<body>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?= $breadcrumb ?>
</section>
  <section class="content">
    <div class="box">
      <div class="box-body">
        <div class="container-fluid">
          <form class='form-horizontal'>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Customer</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input id='nama_customer_filter' type="text" class="form-control" disabled>
                      <input id='id_customer_filter' type="hidden" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_dealer_filter_online_stock_dealer_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>       
                <?php $this->load->view('modal/h3_md_dealer_filter_online_stock_dealer_index', [
                  'offline' => false
                ]); ?>         
                <script>
                function pilih_dealer_filter_online_stock_dealer_index(data, type) {
                  if(type == 'add_filter'){
                    $('#nama_customer_filter').val(data.nama_dealer);
                    $('#id_customer_filter').val(data.id_dealer);
                  }else if(type == 'reset_filter'){
                    $('#nama_customer_filter').val('');
                    $('#id_customer_filter').val('');
                  }
                  // online_stock_dealer.draw();
                  h3_md_dealer_filter_online_stock_dealer_index_datatable.draw();
                }
                </script>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Kelompok Part</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input id='id_kelompok_part_filter' type="text" class="form-control" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_kelompok_part_filter_online_stock_dealer_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>       
                <?php $this->load->view('modal/h3_md_kelompok_part_filter_online_stock_dealer_index'); ?>         
                <script>
                function pilih_kelompok_part_filter_online_stock_dealer_index(data, type) {
                  if(type == 'add_filter'){
                    $('#id_kelompok_part_filter').val(data.id_kelompok_part);
                  }else if(type == 'reset_filter'){
                    $('#id_kelompok_part_filter').val('');
                  }
                  // online_stock_dealer.draw();
                  h3_md_kelompok_part_filter_online_stock_dealer_index_datatable.draw();
                }
                </script>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Part Number</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <!-- <input id='nama_part_filter' type="text" class="form-control" disabled>
                      <input id='id_part_filter' type="hidden" disabled> -->
                      <div class="input-group-btn">
                        <!-- <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_part_filter_online_stock_dealer_index'>
                          <i class="fa fa-search"></i>
                        </button> -->
                        <input type="text" class="form-control" id='kode_part_filter'>
                      </div>
                    </div>
                  </div>
                </div>       
                <?php //$this->load->view('modal/h3_md_part_filter_online_stock_dealer_index'); ?>         
                <!-- <script>
                function pilih_part_filter_online_stock_dealer_index(data, type) {
                  if(type == 'add_filter'){
                    $('#nama_part_filter').val(data.id_part + ' - ' + data.nama_part);
                    $('#id_part_filter').val(data.id_part);
                  }else if(type == 'reset_filter'){
                    $('#nama_part_filter').val('');
                    $('#id_part_filter').val('');
                  }
                  online_stock_dealer.draw();
                  h3_md_part_filter_online_stock_dealer_index_datatable.draw();
                }
                </script> -->
              </div>
              <!-- <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">SIM Part</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                    <input id='nama_simpart_filter' type="text" class="form-control" disabled>
                      <input id='id_simpart_filter' type="hidden" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_simpart_filter_online_stock_dealer_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>       
                <?php //$this->load->view('modal/h3_md_simpart_filter_online_stock_dealer_index'); ?>         
                <script>
                function pilih_simpart_filter_online_stock_dealer_index(data, type) {
                  if(type == 'add_filter'){
                    $('#nama_simpart_filter').val(data.id_part + ' - ' + data.nama_part);
                    $('#id_simpart_filter').val(data.id_part);
                  }else if(type == 'reset_filter'){
                    $('#nama_simpart_filter').val('');
                    $('#id_simpart_filter').val('');
                  }
                  online_stock_dealer.draw();
                  h3_md_simpart_filter_online_stock_dealer_index_datatable.draw();
                }
                </script>
              </div> -->
              
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Nama Part</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <div class="input-group-btn">
                      <input type="text" class="form-control" id='nama_part_filter'>
                      </div>
                    </div>
                  </div>
                </div>       
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 no-padding-x">Periode Sales</label>
                  <div class="col-sm-8">
                    <input id='periode_sales_filter' type="text" class="form-control" readonly>
                    <input id='periode_sales_filter_start' type="hidden" disabled>
                    <input id='periode_sales_filter_end' type="hidden" disabled>
                  </div>
                </div>                
                <script>
                  $('#periode_sales_filter').daterangepicker({
                    opens: 'left',
                    autoUpdateInput: false,
                    locale: {
                      format: 'DD/MM/YYYY'
                    }
                  }).on('apply.daterangepicker', function(ev, picker) {
                    $('#periode_sales_filter_start').val(picker.startDate.format('YYYY-MM-DD'));
                    $('#periode_sales_filter_end').val(picker.endDate.format('YYYY-MM-DD'));
                    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                    // online_stock_dealer.draw();
                  }).on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $('#periode_sales_filter_start').val('');
                    $('#periode_sales_filter_end').val('');
                    // online_stock_dealer.draw();
                  });
                </script>
              </div>
              <!-- <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 no-padding-x">Download Laporan Stock Dealer</label>
                  <div class="col-sm-8">
                    <button type="button"  id="downloadButton" onclick="getReport('download')" name="process" value="edit" class="btn bg-blue btn-sm btn-flat"><i class="fa fa-download"></i> Download .xls</button>
                  </div>
                </div>  
              </div> -->
              
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">SIM Part</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <div class="input-group-btn">
                        <select class="form-control form-select" aria-label="Default select example" name="sim_part" id="sim_part" >
                          <option selected disabled>Pilih Filter SIM Parts</option>
                          <option value="all"> ALL </option>
                          <option value="sim"> SIM Parts </option>
                          <option value="non"> Non SIM Parts </option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>       
                <!-- <script>
                function pilih_simpart_filter_online_stock_dealer_index(data, type) {
                  if(type == 'add_filter'){
                    $('#nama_simpart_filter').val(data.id_part + ' - ' + data.nama_part);
                    $('#id_simpart_filter').val(data.id_part);
                  }else if(type == 'reset_filter'){
                    $('#nama_simpart_filter').val('');
                    $('#id_simpart_filter').val('');
                  }
                  // online_stock_dealer.draw();
                  h3_md_simpart_filter_online_stock_dealer_index_datatable.draw();
                }
                </script> -->
              </div>
            </div>
            <br>
            <div class="col-sm-12">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                        <button type="button" class="btn btn-success btn-block btn-sm" id="btn-cari_filter"><span class="fa fa-search"></span>  SEARCH</button>
                  </div> 
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 no-padding-x">Download Laporan Stock Dealer</label>
                  <div class="col-sm-8">
                    <button type="button"  id="downloadButton" onclick="getReport('download')" name="process" value="edit" class="btn bg-blue btn-flat"><i class="fa fa-download"></i> Download .xls</button>
                  </div>
                </div>  
              </div>
            </div>

            <br>

            <div id="check_nilai_sim_part_data">
                <div class="row" >
                    <div class="col-sm-2 ">
                      <label>Nilai Stock SIM: <span id='nilai_stock_sim' class='text-bold'>Rp 0</span></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                      <label>% Item SIM: <span id="persen_item_sim" class="text-bold">0 %</span></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                      <label>% Qty SIM: <span id="persen_qty_sim" class="text-bold">0 %</span></label>
                    </div>
                </div>
              </div>
          </form>
        </div>
        <table id="online_stock_dealer" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Part Number</th>              
              <th>Nama Part</th>              
              <th>HET</th>              
              <th>Tipe Motor</th>              
              <th>Qty On Hand</th>              
              <th>Qty AVS</th>              
              <th>Qty Sales</th>              
              <th>Qty SIM Part</th>              
              <th>Qty Booking</th>              
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          // $(document).ready(function() {
          function online_stock_dealer2(){
            online_stock_dealer = $('#online_stock_dealer').DataTable({
                processing: true,
                serverSide: true,
                "bDestroy":true,
                order: [],
                ajax: {
                    url: "<?= base_url('api/md/h3/online_stock_dealer') ?>",
                    // dataSrc: "data",
                    dataSrc: function ( json ) {
                        filter = {};
                        filter.id_customer_filter = $('#id_customer_filter').val();
                        filter.id_kelompok_part_filter = $('#id_kelompok_part_filter').val();
                        filter.id_part_filter = $('#id_part_filter').val();
                        filter.id_simpart_filter = $('#id_simpart_filter').val();
                        filter.periode_sales_filter_start = $('#periode_sales_filter_start').val();
                        filter.periode_sales_filter_end = $('#periode_sales_filter_end').val();
                        filter.kode_part_filter = $('#kode_part_filter').val();
                        filter.nama_part_filter = $('#nama_part_filter').val();
                        filter.sim_part = $('#sim_part').val();

                        if($('#id_customer_filter').val()!=''){
                          axios.post('<?= base_url('api/md/h3/online_stock_dealer/get_nilai_stock') ?>', Qs.stringify(filter))
                          .then(function(res){
                            $('#nilai_stock').text(
                              accounting.formatMoney(res.data, "Rp ", 0, ".", ",")
                            );
                          })
                          .catch(function(err){
                            toastr.error('Error ketika ingin mengambil nilai stock dealer.')
                          });

                          axios.post('<?= base_url('api/md/h3/online_stock_dealer/get_nilai_stock_sim_part') ?>', Qs.stringify(filter))
                          .then(function(res){
                            $('#nilai_stock_sim').text(
                              accounting.formatMoney(res.data, "Rp ", 0, ".", ",")
                            );
                          })
                          .catch(function(err){
                            toastr.error('Error ketika ingin mengambil nilai stock SIM Part dealer.')
                          });

                          axios.all([
                          axios.post('<?= base_url('api/md/h3/online_stock_dealer/get_qty_stock_sim_part') ?>', Qs.stringify(filter)),
                          axios.post('<?= base_url('api/md/h3/online_stock_dealer/get_qty_stock') ?>', Qs.stringify(filter)),
                          ])
                          .then(function(res){
                            persentase = parseFloat(res[0].data) / parseFloat(res[1].data);
                            $('#persen_qty_sim').text(
                              accounting.formatMoney(persentase, "", 2, ".", ",") + ' %'
                            );
                          })
                          .catch(function(err){
                            toastr.error('Error ketika ingin mengambil nilai persentase kuantitas SIM Part dealer.')
                          });

                          axios.all([
                            axios.post('<?= base_url('api/md/h3/online_stock_dealer/get_item_stock_sim_part') ?>', Qs.stringify(filter)),
                            axios.post('<?= base_url('api/md/h3/online_stock_dealer/get_item_stock') ?>', Qs.stringify(filter)),
                          ])
                          .then(function(res){
                            persentase = parseFloat(res[0].data) / parseFloat(res[1].data);
                            $('#persen_item_sim').text(
                              accounting.formatMoney(persentase, "", 2, ".", ",") + ' %'
                            );
                          })
                          .catch(function(err){
                            toastr.error('Error ketika ingin mengambil nilai persentase kuantitas SIM Part dealer.')
                          });
                        }
                        return json.data;
                    } ,
                    type: "POST",
                    data: function(d){
                      d.id_customer_filter = $('#id_customer_filter').val();
                      d.id_kelompok_part_filter = $('#id_kelompok_part_filter').val();
                      d.id_part_filter = $('#id_part_filter').val();
                      d.id_simpart_filter = $('#id_simpart_filter').val();
                      d.periode_sales_filter_start = $('#periode_sales_filter_start').val();
                      d.periode_sales_filter_end = $('#periode_sales_filter_end').val();
                      d.kode_part_filter = $('#kode_part_filter').val();
                      d.nama_part_filter = $('#nama_part_filter').val();
                      d.sim_part = $('#sim_part').val();
                    }
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' }, 
                    { data: 'id_part' },
                    { data: 'nama_part' },
                    { 
                      data: 'harga_dealer_user',
                      render: function(data){
                        return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                      }
                    },
                    { data: 'tipe_motor', orderable: false, className: 'text-center', width: '5%' },
                    { data: 'qty_onhand', orderable: false },
                    { data: 'qty_avs', orderable: false },
                    { data: 'qty_sales', orderable: false },
                    { data: 'qty_sim_part', orderable: false },
                    { data: 'qty_hotline', orderable: false },
                ],
            });
          }
          // });
          

          function view_qty_hotline(id_part) {
            $('#id_part_for_view_qty_hotline').val(id_part);
            h3_md_view_qty_hotline_online_stock_dealer_datatable.draw();
            $('#h3_md_view_qty_hotline_online_stock_dealer').modal('show');
          }

          function view_tipe_motor(id_part) {
            $('#id_part_for_view_tipe_motor').val(id_part);
            h3_md_view_tipe_motor_online_stock_dealer_datatable.draw();
            $('#h3_md_view_tipe_motor_online_stock_dealer').modal('show');
          }

          function getReport(tipe) {
            var value = {
                        id_dealer: $('#id_customer_filter').val(),
                        id_kelompok_part: $('#id_kelompok_part_filter').val(),
                        sim_part: $('#id_simpart_filter').val(),
              tipe: tipe,
              cetak: 'cetak',
            }
            if(value.id_dealer ==''){
              alert("Pilih Customer!");
              return false;
            }

            if (value.tipe === 'download') {
              let values = JSON.stringify(value);

              $('#downloadButton').prop('disabled', true);
              $('#downloadButton').html('<i class="fa fa-spinner fa-spin"></i> On Process...');

              $.ajax({
                type: 'POST',
                url: '<?php echo site_url("h3/h3_md_online_stock_part_dealer/cetak" ) ?>',
                data: {
                  cetak: value.cetak,
                  params: values,
                  id_dealer: $('#id_customer_filter').val(),
                        id_kelompok_part: $('#id_kelompok_part_filter').val(),
                        sim_part: $('#id_simpart_filter').val(),
                },
                success: function(response) {
                  var blob = new Blob([response]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "Laporan Stock Dealer.xls"; 
                    link.style.display = 'none';

                  document.body.appendChild(link);
                  link.click();

                  // Membersihkan tautan
                  document.body.removeChild(link);
                  $('#downloadButton').prop('disabled', false);
                  $('#downloadButton').html('<i class="fa fa-download"></i> Download .xls');
                },
                error: function() {
                  $('#downloadButton').prop('disabled', false);
                  $('#downloadButton').html('<i class="fa fa-download"></i> Download .xls');

                }
              });
            }
          }

          $(document).ready(function() {
            $('#btn-cari_filter').on('click', function(e){
              online_stock_dealer2();
            });
          });
        </script>
        <?php $this->load->view('modal/h3_md_view_qty_hotline_online_stock_dealer'); ?>
        <?php $this->load->view('modal/h3_md_view_tipe_motor_online_stock_dealer'); ?>
        <input type="hidden" id='id_part_for_view_qty_hotline'>
        <input type="hidden" id='id_part_for_view_tipe_motor'>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </section>
</div>
