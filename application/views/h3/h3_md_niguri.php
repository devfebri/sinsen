<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script src="<?= base_url("assets/panel/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
<script src="<?= base_url("assets/vue/vuejs-paginate.js") ?>" type="text/javascript"></script>
<script>
  Vue.use(VueNumeric.default);
  Vue.component('paginate', VuejsPaginate);
  $(document).ready(function(){
    $.fn.DataTable.Api.register( 'processing()', function ( show ) {
        return this.iterator( 'table', function ( ctx ) {
          ctx.oApi._fnProcessingDisplay( ctx, show );
        });
    });
  });
</script>
<base href="<?php echo base_url(); ?>" /> 
<body>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
  <section class="content-header">
    <h1><?= $title; ?></h1>
    <?= $breadcrumb ?>
  </section>
  <section class="content">
    <?php if($set == 'form'): ?>
      <div id='app' class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h3/<?= $isi ?>">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
            </a>  
           
          </h3>
        </div><!-- /.box-header -->
        <div v-if='loading' class="overlay">
          <i class="fa fa-refresh fa-spin text-light-blue"></i>
        </div>
        <div class="box-body">
          <?php $this->load->view('template/session_message.php'); ?>
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal">
                <div class="box-body">
                  <div class="form-group">                  
                    <label class="col-sm-2 control-label">Periode Niguri</label>
                    <div class="col-sm-4">                    
                      <input type="text" readonly class="form-control" :value='moment(header.tanggal_generate).format("MM/YYYY")'>
                    </div>
                    <label class="col-sm-2 control-label">Tipe Niguri</label>
                    <div class="col-sm-4">                    
                      <input type="text" readonly class="form-control" :value='header.type_niguri'>
                    </div>
                  </div>
                  <div class="form-group">                  
                    <label class="col-sm-2 control-label">Dibuat pada tanggal</label>
                    <div class="col-sm-4">                    
                      <input type="text" readonly class="form-control" :value='moment(header.created_at).format("DD/MM/YYYY HH:mm:ss")'>
                    </div>
                    <label class="col-sm-2 control-label">Diperbarui pada tanggal</label>
                    <div class="col-sm-4">                    
                      <input v-if='header.updated_at == null' type="text" readonly class="form-control" value='-'>
                      <input v-if='header.updated_at != null' type="text" readonly class="form-control" :value='moment(header.updated_at).format("DD/MM/YYYY HH:mm:ss")'>
                    </div>
                  </div>
                  <div class="container-fluid no-padding">
                    <div class="row">
                        <div class="col-sm-12 text-left">
                          <button :disabled='loading' v-if='header.periode_sama == 1' id='btn_perbarui_niguri' class="btn btn-flat btn-sm btn-success">Perbarui Niguri</button>
                        </div>
                      </div>
                  </div>
                  <script>
                    $(document).ready(function(){
                      $('#btn_perbarui_niguri').on('click', function(e){
                        e.preventDefault();
                        niguri.processing(true);
                        axios.get('<?= base_url('h3/H3_md_niguri/perbarui_niguri') ?>', {
                          params: {
                            id_niguri_header: app.header.id
                          }
                        })
                        .then(function(res){
                          data = res.data;
                          toastr.success('Niguri berhasil diperbarui');
                          app.header.updated_at = data.payload.updated_at;
                        })
                        .catch(function(err){
                          toastr.error('Niguri tidak berhasil diperbarui.');
                        })
                        .then(function(){ 
                          niguri.processing(false);
                          niguri.draw();
                        });
                      });
                    });
                  </script>
                  <div class="container-fluid" style='margin-top: 20px;'>
                    <div class="row">
                      <div class="col-sm-3">
                        <div class="form-group" id='kelompok_part_filter'>
                          <label for="" class="control-label">Kelompok Part</label>
                          <div class="input-group">
                            <input type="text" class="form-control" readonly :value='filters_kelompok_part.length + " Kelompok Part"'>
                            <div class="input-group-btn">
                              <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_kelompok_part_filter_niguri'><i class="fa fa-search"></i></button>
                            </div>
                          </div>
                        </div>
                        <?php $this->load->view('modal/h3_md_kelompok_part_filter_niguri'); ?>
                      </div>
                    </div>
                  </div>
                  <div class="container-fluid no-padding" style='margin-top: 20px;'>
                    <table id="niguri" class="table table-bordered table-hover" style="width:100%">
                      <thead>
                        <tr>
                          <th>No.</th>              
                          <th>Kode Part</th>              
                          <th>Nama Part</th>              
                          <th>Kelompok Part</th>              
                          <th>HET</th>              
                          <th>HPP</th>            
                          <?php $time_tanggal_generate = strtotime($header['tanggal_generate']); ?>
                          <?php for ($index = 6; $index >= 1 ; $index--): ?>
                          <?php $carbon = Mcarbon::parse($header['tanggal_generate'])->subMonths($index); ?>
                          <th><?= lang('month_' . $carbon->format('n')) ?> <?= $carbon->format('Y') ?></th>              
                          <?php endfor; ?>
                          <th>Average</th>              
                          <th>S/L</th>              
                          <th>Qty Suggest</th>              
                          <?php $carbon = Mcarbon::parse($header['tanggal_generate']); ?>
                          <th>Fix Order <?= lang('month_' . $carbon->format('n')) ?> <?= $carbon->format('Y') ?></th>              
                          <th>Amount Order <?= lang('month_' . $carbon->format('n')) ?> <?= $carbon->format('Y') ?></th>
                          <th>Qty Intransit</th>              
                          <th>Qty AVS</th>
                          <?php if($header['type_niguri'] == 'REG'): ?>
                          <th>Qty Reguler</th>
                          <th>Amount Reguler</th>
                          <?php endif; ?>
                          <?php $jumlah_fix_order = $header['type_niguri'] == 'REG' ? 1 : 5; ?>
                          <?php for ($index = 1; $index <= $jumlah_fix_order ; $index++): ?>
                          <?php $carbon = Mcarbon::parse($header['tanggal_generate'])->addMonths($index); ?>
                          <th>Fix Order <?= lang('month_' . $carbon->format('n')) ?> <?= $carbon->format('Y') ?></th>
                          <th>Amount Fix Order <?= lang('month_' . $carbon->format('n')) ?> <?= $carbon->format('Y') ?></th>
                          <?php endfor; ?>
                        </tr>
                      </thead>
                      <tbody>    
                      </tbody>
                      <tfoot>
                        <tr>
                          <th colspan='15' style="text-align:right">Total:</th>
                          <th></th>
                          <th>Rp 0</th>

                          <th></th>
                          <th></th>

                          <?php if($header['type_niguri'] == 'REG'): ?>
                          <th></th>
                          <th>Rp 0</th>
                          <?php endif; ?>
                          
                          <th></th>
                          <th>Rp 0</th>

                          <?php if($header['type_niguri'] == 'FIX'): ?>
                            <?php for ($index = 2; $index <= 5 ; $index ++): ?>
                            <th></th>
                            <th>Rp 0</th>
                            <?php endfor; ?>
                          <?php endif; ?>
                          
                        </tr>
                      </tfoot>
                    </table>
                    <link rel="stylesheet" href="<?= base_url('assets/panel/fixedColumns.bootstrap.css') ?>">
                    <link rel="stylesheet" href="<?= base_url('assets/panel/fixedColumns.dataTables.min.css') ?>">
                    <script>
                      update_fix_order = _.debounce(function (params) {
                        toastr.options.preventDuplicates = true;
                        niguri.processing(true);
                        axios.get('<?= base_url('api/md/h3/niguri_item/update_fix_order') ?>', {
                          params: params
                        })
                        .then(function(res){
                          toastr.success(res.data.message);
                          niguri.processing(false);
                          niguri.draw(false); 
                        })
                        .catch(function(err){
                          data = err.response.data;
                          if(data.error_type == 'validation_error'){
                            toastr.error(data.errors.fix_order_value);
                          }else{
                            toastr.error(data.message);
                          }
                        })
                        .then(function(){ 
                          niguri.processing(false);
                        });
                      }, 500);

                      $(document).ready(function() {
                        niguri = $('#niguri').DataTable({
                            footerCallback: function ( row, data, start, end, display ) {
                              var api = this.api(), data;

                              axios.post('<?= base_url('api/md/h3/niguri_item/get_total_fix_order') ?>', Qs.stringify({
                                update_key: 'fix_order_n',
                                id_niguri_header: app.header.id,
                                id_kelompok_part_filter: app.filters_kelompok_part,
                              }))
                              .then(function(res){
                                $( api.column( 16 ).footer() ).html(accounting.formatMoney(res.data, 'Rp ', 0, ".", ","));
                              });

                              <?php if($header['type_niguri'] == 'FIX'): ?>
                              axios.post('<?= base_url('api/md/h3/niguri_item/get_total_fix_order') ?>', Qs.stringify({
                                update_key: 'fix_order_n_1',
                                id_niguri_header: app.header.id,
                                id_kelompok_part_filter: app.filters_kelompok_part,
                              }))
                              .then(function(res){
                                $( api.column( 20 ).footer() ).html(accounting.formatMoney(res.data, 'Rp ', 0, ".", ","));
                              });

                              axios.post('<?= base_url('api/md/h3/niguri_item/get_total_fix_order') ?>', Qs.stringify({
                                update_key: 'fix_order_n_2',
                                id_niguri_header: app.header.id,
                                id_kelompok_part_filter: app.filters_kelompok_part,
                              }))
                              .then(function(res){
                                $( api.column( 22 ).footer() ).html(accounting.formatMoney(res.data, 'Rp ', 0, ".", ","));
                              });

                              axios.post('<?= base_url('api/md/h3/niguri_item/get_total_fix_order') ?>', Qs.stringify({
                                update_key: 'fix_order_n_3',
                                id_niguri_header: app.header.id,
                                id_kelompok_part_filter: app.filters_kelompok_part,
                              }))
                              .then(function(res){
                                $( api.column( 24 ).footer() ).html(accounting.formatMoney(res.data, 'Rp ', 0, ".", ","));
                              });

                              axios.post('<?= base_url('api/md/h3/niguri_item/get_total_fix_order') ?>', Qs.stringify({
                                update_key: 'fix_order_n_4',
                                id_niguri_header: app.header.id,
                                id_kelompok_part_filter: app.filters_kelompok_part,
                              }))
                              .then(function(res){
                                $( api.column( 26 ).footer() ).html(accounting.formatMoney(res.data, 'Rp ', 0, ".", ","));
                              });

                              axios.post('<?= base_url('api/md/h3/niguri_item/get_total_fix_order') ?>', Qs.stringify({
                                update_key: 'fix_order_n_5',
                                id_niguri_header: app.header.id,
                                id_kelompok_part_filter: app.filters_kelompok_part,
                              }))
                              .then(function(res){
                                $( api.column( 28 ).footer() ).html(accounting.formatMoney(res.data, 'Rp ', 0, ".", ","));
                              });
                              <?php endif; ?>

                              <?php if($header['type_niguri'] == 'REG'): ?>
                              axios.post('<?= base_url('api/md/h3/niguri_item/get_total_fix_order') ?>', Qs.stringify({
                                update_key: 'qty_reguler',
                                id_niguri_header: app.header.id,
                                id_kelompok_part_filter: app.filters_kelompok_part,
                              }))
                              .then(function(res){
                                $( api.column( 20 ).footer() ).html(accounting.formatMoney(res.data, 'Rp ', 0, ".", ","));
                              });
                              <?php endif; ?>
                            },
                            processing: true,
                            serverSide: true,
                            order: [],
                            scrollX: true,
                            scrollCollapse: true,
                            fixedColumns: {
                              leftColumns: 6
                            },
                            ajax: {
                              url: "<?= base_url('api/md/h3/niguri_item') ?>",
                              dataSrc: "data",
                              type: "POST",
                              data: function(d){
                                d.id_kelompok_part_filter = app.filters_kelompok_part;
                                d.id_niguri_header = app.header.id;
                              }
                            },
                            drawCallback: function(){
                              $(".update_fix_order").on("keyup", function(e){
                                  $row = $(this).parents("tr");
                                  row_data = niguri.row($row).data();
                                  input_value = $(this).val();
                                  update_key = $(this).data('update-key');

                                  if(update_key == 'fix_order_n'){
                                    row_data.fix_order_n = input_value;
                                  }else if(update_key == 'fix_order_n_1'){
                                    row_data.fix_order_n_1 = input_value;
                                  }else if(update_key == 'fix_order_n_2'){
                                    row_data.fix_order_n_2 = input_value;
                                  }else if(update_key == 'fix_order_n_3'){
                                    row_data.fix_order_n_3 = input_value;
                                  }else if(update_key == 'fix_order_n_4'){
                                    row_data.fix_order_n_4 = input_value;
                                  }else if(update_key == 'fix_order_n_5'){
                                    row_data.fix_order_n_5 = input_value;
                                  }else if(update_key == 'qty_reguler'){
                                    row_data.qty_reguler = input_value;
                                  }
                              });
                            },
                            createdRow: function (row, data, index) {
                              $('td', row).addClass('align-middle');
                            },
                            columns: [
                                { data: 'index', orderable: false, width: '20px' },
                                { data: 'id_part' },
                                { data: 'nama_part', width: '200px' },
                                { data: 'kelompok_part' },
                                { 
                                  data: 'het',
                                  render: function(data){
                                    return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                                  },
                                  width: '100px',
                                  className: 'text-right'
                                },
                                { 
                                  data: 'hpp',
                                  render: function(data){
                                    return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                                  },
                                  width: '100px',
                                  className: 'text-right'
                                },
                                { 
                                  data: 'keenam',
                                  render: function(data){
                                    return accounting.formatMoney(data, '', 0, ".", ",");
                                  },
                                  className: 'text-right'
                                },
                                { 
                                  data: 'kelima',
                                  render: function(data){
                                    return accounting.formatMoney(data, '', 0, ".", ",");
                                  },
                                  className: 'text-right'
                                },
                                { 
                                  data: 'keempat',
                                  render: function(data){
                                    return accounting.formatMoney(data, '', 0, ".", ",");
                                  },
                                  className: 'text-right'
                                },
                                { 
                                  data: 'ketiga',
                                  render: function(data){
                                    return accounting.formatMoney(data, '', 0, ".", ",");
                                  },
                                  className: 'text-right'
                                },
                                { 
                                  data: 'kedua',
                                  render: function(data){
                                    return accounting.formatMoney(data, '', 0, ".", ",");
                                  },
                                  className: 'text-right'
                                },
                                { 
                                  data: 'pertama',
                                  render: function(data){
                                    return accounting.formatMoney(data, '', 0, ".", ",");
                                  },
                                  className: 'text-right'
                                },
                                { 
                                  data: 'average',
                                  render: function(data){
                                    return accounting.formatMoney(data, '', 1, ".", ",");
                                  },
                                  className: 'text-right'
                                },
                                { 
                                  data: 's_l',
                                  render: function(data){
                                    return accounting.formatMoney(data, '', 1, ".", ",");
                                  },
                                  className: 'text-right'
                                },
                                { 
                                  data: 'qty_suggest',
                                  render: function(data){
                                    return accounting.formatMoney(data, '', 0, ".", ",");
                                  },
                                  className: 'text-right',
                                  createdCell: function (td, cellData, rowData, row, col) {
                                    $(td).addClass('bg-aqua-active');
                                  }
                                },
                                { 
                                  data: 'fix_order_n',
                                  render: function(data, type, row){
                                    <?php if($header['type_niguri'] == 'FIX'): ?>
                                    if(app.edit_mode && row.fix_order_n){
                                        return '<input style="width: 80px;" type="text" data-update-key="fix_order_n" class="update_fix_order" value="'+ data +'">';
                                    }
                                    <?php endif; ?>
                                    return data;
                                  },
                                },
                                { 
                                  data: 'amount_fix_order_n',
                                  render: function(data){
                                    return accounting.formatMoney(data, 'Rp ', 0, ".", ",");
                                  },
                                  width: '100px'
                                },
                                { 
                                  data: 'qty_intransit',
                                  render: function(data){
                                    return accounting.formatMoney(data, '', 0, ".", ",");
                                  },
                                  className: 'text-right'
                                },
                                { 
                                  data: 'qty_avs',
                                  render: function(data){
                                    return accounting.formatMoney(data, '', 0, ".", ",");
                                  },
                                  className: 'text-right',
                                  createdCell: function (td, cellData, rowData, row, col) {
                                    $(td).addClass('bg-aqua-active');
                                  }
                                },
                                <?php if($header['type_niguri'] == 'REG'): ?>
                                { 
                                  data: 'qty_reguler',
                                  render: function(data, type, row){
                                    if(app.edit_mode && row.qty_reguler_editable){
                                      return '<input style="width: 80px;" type="text" data-update-key="qty_reguler" class="update_fix_order" value="'+ data +'">';
                                    }
                                    return data;
                                  },
                                },
                                { 
                                  data: 'amount_qty_reguler',
                                  render: function(data){
                                    return accounting.formatMoney(data, 'Rp ', 0, ".", ",");
                                  },
                                  width: '100px'
                                },
                                <?php endif; ?>
                                { 
                                  data: 'fix_order_n_1',
                                  render: function(data, type, row){
                                    <?php if($header['type_niguri'] == 'FIX'): ?>
                                    if(app.edit_mode && row.fix_order_n_1_editable){
                                      return '<input style="width: 80px;" type="text" data-update-key="fix_order_n_1" class="update_fix_order" value="'+ data +'">';
                                    }
                                    <?php endif; ?>
                                    return data;
                                  },
                                },
                                { 
                                  data: 'amount_fix_order_n_1',
                                  render: function(data){
                                    return accounting.formatMoney(data, 'Rp ', 0, ".", ",");
                                  },
                                  width: '100px'
                                },
                                <?php if($header['type_niguri'] == 'FIX'): ?>
                                { 
                                  data: 'fix_order_n_2',
                                  render: function(data, type, row){
                                    if(app.edit_mode && row.fix_order_n_2_editable){
                                      return '<input style="width: 80px; color:black;" type="text" data-update-key="fix_order_n_2" class="update_fix_order" value="'+ data +'">';
                                    }
                                    return data;
                                  },
                                  createdCell: function (td, cellData, rowData, row, col) {
                                    $(td).addClass('bg-aqua-active');
                                  }
                                },
                                { 
                                  data: 'amount_fix_order_n_2',
                                  render: function(data){
                                    return accounting.formatMoney(data, 'Rp ', 0, ".", ",");
                                  },
                                  width: '100px'
                                },
                                { 
                                  data: 'fix_order_n_3',
                                  render: function(data, type, row){
                                    if(app.edit_mode && row.fix_order_n_3_editable){
                                      return '<input style="width: 80px;" type="text" data-update-key="fix_order_n_3" class="update_fix_order" value="'+ data +'">';
                                    }
                                    return data;
                                  },
                                },
                                { 
                                  data: 'amount_fix_order_n_3',
                                  render: function(data){
                                    return accounting.formatMoney(data, 'Rp ', 0, ".", ",");
                                  },
                                  width: '100px'
                                },
                                { 
                                  data: 'fix_order_n_4',
                                  render: function(data, type, row){
                                    if(app.edit_mode && row.fix_order_n_4_editable){
                                      return '<input style="width: 80px;" type="text" data-update-key="fix_order_n_4" class="update_fix_order" value="'+ data +'">';
                                    }
                                    return data;
                                  },
                                },
                                { 
                                  data: 'amount_fix_order_n_4',
                                  render: function(data){
                                    return accounting.formatMoney(data, 'Rp ', 0, ".", ",");
                                  },
                                  width: '100px'
                                },
                                { 
                                  data: 'fix_order_n_5',
                                  render: function(data, type, row){
                                    if(app.edit_mode && row.fix_order_n_5_editable){
                                      return '<input style="width: 80px;" type="text" data-update-key="fix_order_n_5" class="update_fix_order" value="'+ data +'">';
                                    }
                                    return data;
                                  },
                                },
                                { 
                                  data: 'amount_fix_order_n_5',
                                  render: function(data){
                                    return accounting.formatMoney(data, 'Rp ', 0, ".", ",");
                                  },
                                  width: '100px'
                                },
                                <?php endif; ?>
                            ],
                        });

                        function set_fix_order(id_part, update_key, value){
                          axios.post('h3/h3_md_niguri/set_fix_order', Qs.stringify({
                            id: id,
                            update_key: update_key,
                            value: value
                          }))
                          .then(function(res){
                            niguri.draw(false);
                          })
                          .catch(function(err){
                            toastr.error(err);
                          });
                        }

                        $('#niguri').on('keyup', 'input', _.debounce(function(e) {
                          index_row = niguri.cell( $(this).parent() ).index().row;
                          id = niguri.row(index_row).data().id;
                          updateKey = $(this).data('update-key');

                          set_fix_order(id, updateKey, $(this).val());
                        }, 800));
                      });
                      
                    </script>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-4"></div>
                  <div class="col-sm-4"></div>
                  <div class="col-sm-4 no-padding text-right">
                      <a v-if='header.status == "Processed"' :href="'h3/h3_md_niguri/cetak?id=' + header.id" class="btn btn-flat btn-sm btn-info">Download</a>
                      <button v-if='header.status != "Processed" && !edit_mode' class="btn btn-flat btn-sm btn-info" @click.prevent='proses'>Proses</button>
                      <button v-if='header.status == "Processed" && !edit_mode' class="btn btn-flat btn-sm btn-danger" @click.prevent='close'>Close</button>
                      <button v-if='!edit_mode && header.status == "Open"' class="btn btn-flat btn-sm btn-warning" @click.prevent='set_edit_mode'>Edit</button>
                      <button v-if='edit_mode && header.status == "Open"' class="btn btn-flat btn-sm btn-warning" @click.prevent='update'>Update</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      <script>
        var app = new Vue({
            el: '#app',
            mounted: function(){
              $(document).ready(function(){
                $("#h3_md_kelompok_part_filter_niguri").on('change',"input[type='checkbox']",function(e){
                  target = $(e.target);
                  id_kelompok_part = target.attr('data-id-kelompok-part');

                  if(target.is(':checked')){
                    app.filters_kelompok_part.push(id_kelompok_part);
                  }else{
                    index_kelompok_part = _.indexOf(app.filters_kelompok_part, id_kelompok_part);
                    app.filters_kelompok_part.splice(index_kelompok_part, 1);
                  }
                  h3_md_kelompok_part_filter_niguri_datatable.draw();
                });
              });
            },
            data: {
              loading: false,
              errors: {},
              mode: '<?= $mode ?>',
              edit_mode: false,
              filters_kelompok_part: [],
              header: <?= json_encode($header) ?>,
            },
            methods: {
              set_edit_mode: function(){
                this.edit_mode = true;
              },
              update: function(){
                this.loading = true;
                post = {};
                post.id_niguri_header = app.header.id;
                post.type_niguri = app.header.type_niguri;
                post.parts = _.chain(niguri.rows().data())
                .map(function(data){
                  return _.pick(data, [
                    'id', 'id_part', 'fix_order_n', 'fix_order_n_1', 'fix_order_n_2',
                    'fix_order_n_3', 'fix_order_n_4', 'fix_order_n_5', 'qty_reguler'
                  ]);
                })
                .value();

                axios.post('h3/h3_md_niguri/update_fix_order', Qs.stringify(post))
                .then(function(res){
                  toastr.success('Berhasil memperbarui data.');
                  app.edit_mode = false;
                })
                .catch(function(err){
                  data = err.response.data;
                  if(data.error_type == 'validation_error'){
                    if(data.parts_errors.length){
                      part_errors = data.parts_errors[0];
                      first_error = part_errors.errors[0]
                      toastr.error('Kode Part ' + part_errors.id_part + ' : ' + first_error);
                    }
                  }else{
                    toastr.error(data.message);
                  }
                })
                .then(function(){
                  app.loading = false;
                });
              },
              proses: function(){
                params = _.pick(this.header, [
                  'id'
                ]);

                axios.get('h3/h3_md_niguri/proses', {
                  params: params
                })
                .then(function(res){
                  window.location = 'h3/h3_md_niguri/detail?id=' + res.data.id;
                })
                .catch(function(err){
                  toastr.error(err);
                });
              },
              close: function(){
                params = _.pick(this.header, [
                  'id'
                ]);

                axios.get('h3/h3_md_niguri/close', {
                  params: params
                })
                .then(function(res){
                  window.location = 'h3/h3_md_niguri/detail?id=' + res.data.id;
                })
                .catch(function(err){
                  toastr.error(err);
                });
              }
            },
            watch: {
              filters_kelompok_part: function(){
                niguri.draw();
              },
              edit_mode: function(){
                niguri.draw(false);
              }
            }
          });
      </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-body">
      <?php if($this->input->get('history') != null): ?>
              <a href="h3/<?= $isi ?>">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
              </a>  
              <?php else: ?>
              <a href="h3/<?= $isi ?>?history=true">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
              </a> 
      <?php endif; ?>
      <hr>
        <div class="container-fluid no-padding">
          <div class="row" style='margin-bottom: 10px;'>
            <div class="col-sm-4">
              <div class="col-sm-4 no-padding">
                <select id='type_niguri' class="form-control">
                  <option value="FIX">FIX</option>
                  <option value="REG">REGULER</option>
                </select>
              </div>
              <div class="col-sm-8">
                <button id='btn-generate-niguri' class="btn btn-flat btn-success">Generate Niguri Periode Saat Ini</button>
              </div>
            </div>
          </div>
          <script>
            $(document).ready(function(){
              $('#btn-generate-niguri').on('click', function(e){
                e.preventDefault();

                type_niguri = $('#type_niguri').val();

                niguri.processing(true);
                $('#btn-generate-niguri').attr('disabled', true);
                axios.get('h3/h3_md_niguri/generate_niguri?type_niguri=' + type_niguri)
                .then(function(res){
                  data = res.data;
                  response_type = data.response_type;
                  payload = data.payload;

                  if(response_type == 'niguri_already_exists'){
                    window.location = 'h3/h3_md_niguri/detail?id=' + payload.id;
                  }else if(response_type == 'niguri_created'){
                    window.location = 'h3/h3_md_niguri/detail?id=' + payload.id;
                  }else if(response_type == 'niguri_not_created'){
                    window.location = 'h3/h3_md_niguri';
                  }
                })
                .catch(function(err){
                  toastr.error(err);
                })
                .then(function(){
                  niguri.processing(false);
                  $('#btn-generate-niguri').attr('disabled', false);
                });
              });
            })
          </script>
          <table id="niguri" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>No.</th>              
                <th>Periode Niguri</th>              
                <th>Tipe</th>              
                <th>Status</th>              
                <th>Dibuat pada tanggal</th>         
                <th>Diperbarui pada tanggal</th>         
                <th>Action</th>         
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
        <script>
        $(document).ready(function() {
              niguri = $('#niguri').DataTable({
                  processing: true,
                  serverSide: true,
                  order: [],
                  scrollX: true,
                  ajax: {
                    url: "<?= base_url('api/md/h3/niguri') ?>",
                    dataSrc: "data",
                    type: "POST",
                    data: function(d){
                    d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  }
                  },
                  columns: [
                      { data: 'index', orderable: false, width: '20px' },
                      { data: 'tanggal_generate' },
                      { data: 'type_niguri' },
                      { data: 'status' },
                      { data: 'created_at' },
                      { 
                        data: 'updated_at',
                        render: function(data){
                          if(data != null) return data;
                          return '-';
                        }
                      },
                      { data: 'action', orderable: false, width: '2%', className: 'text-center' },
                  ],
              });
            });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>
