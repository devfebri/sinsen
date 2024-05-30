<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
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
<?php
  if ($set=="form") {
      $form     = '';
      $disabled = '';
      $readonly ='';
      if ($mode=='insert') {
          $form = 'save';
      }
      if ($mode=='detail') {
          $disabled = 'disabled';
      }
      if ($mode=='edit') {
          $form = 'update';
      } ?>
  <script>
    Vue.use(VueNumeric.default);
  </script>
  <div class="box box-default">
      <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/<?= $isi ?>">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
            </a>
          </h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <table class="table table-condensed">
                <tr class='bg-blue-gradient'>
                  <th colspan='6'><b>Total</b></th>
                </tr>
                <tr class='bg-gray-light'>
                  <th>Part Number</th>
                  <th>Part Deskripsi</th>
                  <th>Stok On Hand</th>
                  <th>Stok Book</th>
                  <th>Stok AVS</th>
                  <th>Stock In Transit</th>
                </tr>
                <tr>
                  <td><?= $part['id_part'] ?></td>
                  <td><?= $part['nama_part'] ?></td>
                  <td><?= $part['stock_on_hand'] ?></td>
                  <td><?= $part['qty_book'] ?></td>
                  <td><?= $part['stock_avs'] ?></td>
                  <td><?= $part['intransit'] ?></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <div class="container-fluid">
          <?php foreach($gudang as $each_gudang): ?>
          <div class="row" style='margin-top: 15px;'>
            <div class="col-sm-12">
              <table class="table table-condensed">
                <tr class='bg-blue-gradient'>
                  <th colspan='8'><b><?= $each_gudang['id_gudang'] ?></b></th>
                </tr>
                <tr class='bg-gray-light'>
                  <th width='3%'>No.</th>
                  <th>Rak Lokasi</th>
                  <th>Stok On Hand</th>
                  <th>Stok Book</th>
                  <th>Stok AVS</th>
                  <th>Stock In Transit from MD</th>
                  <th>Stock In Transit from Part Transfer</th>
                  <th>Stock In Transit from Event</th>
                </tr>
                <?php $index = 1; foreach($each_gudang['rak'] as $each_rak): ?>
                <tr>
                  <td><?= $index ?>.</td>
                  <td><?= $each_rak['id_rak'] ?></td>
                  <td><?= $each_rak['stock_on_hand'] ?></td>
                  <td><?= $each_rak['qty_book'] ?></td>
                  <td><?= $each_rak['stock_avs'] ?></td>
                  <td><?= $each_rak['intransit_md'] ?></td>
                  <td><?= $each_rak['intransit_part_transfer'] ?></td>
                  <td><?= $each_rak['intransit_event'] ?></td>
                </tr>
                <?php $index++; endforeach; ?>
                <?php if(count($each_gudang['rak']) < 1): ?>
                <tr>
                  <td class='text-center' colspan='8'>Tidak ada data</td>
                </tr>
                <?php endif; ?>
              </table>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
    </div>
  </div>
<?php } elseif ($set=="index") { ?>
    <div class="box">
      <div class="box-body">
        <div class="container-fluid no-padding">
          <div class="row">
            <div class="col-sm-2">
              <div class="row">
                <div class="col-sm-12 text-right">
                  <label>Nilai Stock SIM: <span id='nilai_stock_sim' class='text-bold'>Rp 0</span></label>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 text-right">
                  <label>% Item SIM: <span id="persen_item_sim" class="text-bold">0 %</span></label>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 text-right">
                  <label>% Qty SIM: <span id="persen_qty_sim" class="text-bold">0 %</span></label>
                </div>
              </div>
            </div>
            <div class="col-sm-2">
              <label style='margin-right: 10px' class='text-right'>
                  <span>Nilai Stock:</span>
                  <span id='nilai_stock' class='text-bold'>Rp 0</span>
              </label>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label for="" class="control-label">Kelompok Part</label>
                <select  id="filter_kelompok_part_monitoring_stock" class="form-control">
                  <option value="">All</option>
                  <?php foreach($kelompok_parts as $row): ?>
                  <option value="<?= $row['kelompok_part'] ?>"><?= $row['kelompok_part'] ?></option>
                  <?php endforeach; ?>
                </select>
                <script>
                  $('#filter_kelompok_part_monitoring_stock').on('change', function(){
                    monitoring_stock.draw();
                  });
                </script>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label for="" class="control-label">Status</label>
                <select  id="filter_status_monitoring_stock" class="form-control">
                  <option value="">All</option>
                  <option value="A">Active</option>
                  <option value="D">Discontinued</option>
                </select>
                <script>
                  $('#filter_status_monitoring_stock').on('change', function(){
                    monitoring_stock.draw();
                  });
                </script>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label for="" class="control-label">Rank</label>
                <select  id="filter_rank_monitoring_stock" class="form-control">
                  <option value="">All</option>
                  <option value="A">A</option>
                  <option value="B">B</option>
                  <option value="C">C</option>
                  <option value="D">D</option>
                  <option value="E">E</option>
                </select>
                <script>
                  $('#filter_rank_monitoring_stock').on('change', function(){
                    monitoring_stock.draw();
                  });
                </script>
              </div>
            </div>
          </div>
        </div>
        <table id="monitoring_stock" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>Part Number</th>
              <th>Part Deskripsi</th>
              <th>Part Deskripsi Bahasa</th>
              <th>HET</th>
              <th>Rank</th>
              <th>Status</th>
              <th>Stock on Hand</th>
              <th>Qty Book</th>
              <th>Qty AVS</th>
              <th>Min Stok</th>
              <th>Maks Stok</th>
              <th>Stock in Transit</th>
              <th>Qty SIM</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
        $(document).ready(function() {
              monitoring_stock = $('#monitoring_stock').DataTable({
                  processing: true,
                  serverSide: true,
                  pageLength: 5,
                  "lengthMenu": [5,10,15],
                  "language": {           
                    "searchPlaceholder": "Min. 5 digit untuk cari",
                  }, 
                  order: [],
                  scrollX: true,
                  ajax: {
                      url: "<?= base_url('api/dealer/monitoring_stock') ?>",
                      type: "POST",
                      data: function(data) {
                        data.status = $('#filter_status_monitoring_stock').val();
                        data.rank = $('#filter_rank_monitoring_stock').val();
                        data.kelompok_part = $('#filter_kelompok_part_monitoring_stock').val();
                      },
                      dataSrc: function ( json ) {
                        filter = {};
                        filter.status = $('#filter_status_monitoring_stock').val();
                        filter.rank = $('#filter_rank_monitoring_stock').val();
                        filter.kelompok_part = $('#filter_kelompok_part_monitoring_stock').val();
                        
                        axios.post('<?= base_url('api/dealer/monitoring_stock/get_nilai_stock') ?>', Qs.stringify(filter))
                        .then(function(res){
                          $('#nilai_stock').text(
                            accounting.formatMoney(res.data, "Rp ", 0, ".", ",")
                          );
                        })
                        .catch(function(err){
                          toastr.error('Error ketika ingin mengambil nilai stock dealer.')
                        });

                        axios.post('<?= base_url('api/dealer/monitoring_stock/get_nilai_stock_sim_part') ?>', Qs.stringify(filter))
                        .then(function(res){
                          $('#nilai_stock_sim').text(
                            accounting.formatMoney(res.data, "Rp ", 0, ".", ",")
                          );
                        })
                        .catch(function(err){
                          toastr.error('Error ketika ingin mengambil nilai stock SIM Part dealer.')
                        });

                        axios.all([
                          axios.post('<?= base_url('api/dealer/monitoring_stock/get_qty_stock_sim_part') ?>', Qs.stringify(filter)),
                          axios.post('<?= base_url('api/dealer/monitoring_stock/get_qty_stock') ?>', Qs.stringify(filter)),
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
                          axios.post('<?= base_url('api/dealer/monitoring_stock/get_item_stock_sim_part') ?>', Qs.stringify(filter)),
                          axios.post('<?= base_url('api/dealer/monitoring_stock/get_item_stock') ?>', Qs.stringify(filter)),
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


                        return json.data;
                      } 
                  },
                  columns: [
                      { data: 'index', orderable: false, width: '3%' },
                      { data: 'id_part' },
                      { data: 'nama_part' },
                      { data: 'nama_part_bahasa' },
                      { 
                        data: 'harga_dealer_user',
                        render: function(data){
                          return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                        },
                        className: 'text-right'
                      },
                      { 
                        data: 'rank',
                        render: function(data){
                          if(data == null){
                            return '-';
                          }
                          return data;
                        }
                      },
                      { 
                        data: 'status',
                        render: function(data){
                          if(data == null || data == ''){
                            return '-';
                          }
                          return data;
                        }
                      },
                      { 
                        data: 'stock_on_hand', 
                        orderable: false,
                        render: function(data){
                          return accounting.formatMoney(data, "", 0, ".", ",");
                        },
                        className: 'text-right' 
                      },
                      { 
                        data: 'qty_book', 
                        orderable: false,
                        render: function(data, type, row){
                          if(data != null){
                            return '<a onclick="return open_qty_book(\'' + row.id_part + '\')">' + accounting.formatMoney(data, "", 0, ".", ",") + '</a>'
                          }
                          return accounting.formatMoney(data, "", 0, ".", ",");
                        },
                        className: 'text-right' 
                      },
                      { 
                        data: 'qty_avs', 
                        orderable: false,
                        render: function(data){
                          return accounting.formatMoney(data, "", 0, ".", ",");
                        },
                        className: 'text-right' 
                      },
                      { 
                        data: 'min_stok', 
                        orderable: false,
                        render: function(data){
                          return accounting.formatMoney(data, "", 0, ".", ",");
                        },
                        className: 'text-right' 
                      },
                      { 
                        data: 'maks_stok', 
                        orderable: false,
                        render: function(data){
                          return accounting.formatMoney(data, "", 0, ".", ",");
                        },
                        className: 'text-right' 
                      },
                      { 
                        data: 'stock_in_transit', 
                        orderable: false,
                        render: function(data){
                          return accounting.formatMoney(data, "", 0, ".", ",");
                        },
                        className: 'text-right' 
                      },
                      { 
                        data: 'qty_sim_part', 
                        orderable: false,
                        render: function(data){
                          return accounting.formatMoney(data, "", 0, ".", ",");
                        },
                        className: 'text-right' 
                      },
                      { data: 'action', orderable: false, width: '3%', className: 'text-center' },
                  ],
              });

              $(".dataTables_filter input")
                .unbind() // Unbind previous default bindings
                .bind("input", function(e) { // Bind our desired behavior
                    // If the length is 3 or more characters, or the user pressed ENTER, search
                    if(this.value.length >= 5 || e.keyCode == 13) {
                        // Call the API search function
                        monitoring_stock.search(this.value).draw();
                    }
                    // Ensure we clear the search if they backspace far enough
                    if(this.value == "") {
                      monitoring_stock.search("").draw();
                    }
                    return;
                });
            });
        </script>
        <?php $this->load->view('modal/h3_dealer_open_qty_book_stock'); ?>
        <script>
          function open_qty_book(id_part){
            console.log('in');
            $('#id_part_open_qty_book_stock').val(id_part);
            h3_dealer_open_qty_book_stock_datatable.draw();
            $('#h3_dealer_open_qty_book_stock').modal('show');
          }
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
<?php } ?>
  </section>
</div>