<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
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
<?php

  if ($set=="form") {
      $form     = '';
      $disabled = '';
      if ($mode=='insert') {
          $form = 'save';
      }
      if ($mode=='detail') {
          $disabled = 'disabled';
          $form = 'detail';
      }
      if ($mode=='edit') {
          $form = 'update';
      } ?>

    <div id="app" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div v-if='loading' class="overlay">
        <i class="text-light-blue fa fa-refresh fa-spin"></i>
      </div>
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Periode</label>
                    <div v-bind:class="{ 'has-error': error_exist('start_date') || error_exist('end_date') }" class="col-sm-4">
                      <input type="text" class="form-control pull-right" id="periode_target_dealer" readonly>
                      <small v-if="error_exist('start_date') || error_exist('end_date')" class="form-text text-danger">{{ get_error('start_date') || get_error('end_date') }}</small>
                    </div>
                    <label class="control-label col-sm-2 col-sm-offset-1">Jenis Target Dealer</label>
                    <div class="col-sm-2">
                      <select :disabled='mode != "insert"' class="form-control" v-model='target_dealer.produk'>
                        <option value="">-Pilih-</option>
                        <option value="Parts">Parts</option>
                        <option value="Oil">Oil</option>
                        <option value="Acc">Accesories</option>
                        <option value="Apparel">Apparel</option>
                        <option value="Tools">Tools</option>
                        <option value="Other">Other</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                      <label class="control-label col-sm-2">Target Dealer Global</label>
                      <div class="col-sm-4">
                        <vue-numeric :disabled='mode == "detail"' class="form-control" v-model='target_dealer.target_global' separator='.' currency='Rp'></vue-numeric>
                      </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_target_sales_out_dealer_modal') ?>
                  <?php $this->load->view('modal/h3_dealer_target_sales_out') ?>
                  <script>
                    function pilih_dealer_target_sales_out(data){
                      data = _.pick(data, ['id_dealer', 'kode_dealer_md', 'nama_dealer', 'alamat', 'kabupaten']);
                        app.target_dealer_detail.push(data);

                        h3_dealer_target_sales_out_datatable.draw();
                    }
                  </script>
                  <div class="box-footer">
                      <div class="col-sm-6 no-padding">
                      <button v-if='mode == "edit"' class ="btn btn-flat btn-sm btn-warning" type='button' @click.prevent='<?= $form ?>'>Perbarui</button>
                      <button v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" type='button' @click.prevent='<?= $form ?>'>Simpan</button>
                      <a v-if='mode == "detail"' :href="'h3/<?= $isi ?>/edit?id=' + target_dealer.id" class="btn btn-flat btn-sm btn-warning">Edit</a>
                    </div>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
  app = new Vue({
      el: '#app',
      data: {
        mode : '<?= $mode ?>',
        index_part: 0,
        loading: false,
        errors: {},
        filter_kabupaten: '',
        filter_id_kabupaten: '',
        filter_nama_customer: '',
        filter_jenis_dealer: [],
        showAlert : false,
        <?php if($mode == 'detail' or $mode == 'edit'): ?>
        target_dealer: <?= json_encode($target_dealer) ?>,
        target_dealer_detail: <?= json_encode($target_dealer_detail) ?>,
        <?php else: ?>
        target_dealer: {
          start_date: '',
          end_date: '',
          produk: '',
          target_global: '',
          active:1,
        },
        target_dealer_detail: [],
        <?php endif; ?>
        dealer_modal_type: '',
        items: [],
      },
      methods:{
        <?= $form ?>: function(){
          this.errors = {};
          this.loading = false;

          post = _.pick(this.target_dealer, [
            'id', 'start_date', 'end_date', 'produk', 'target_global'
          ]);

          post.target_dealer_detail = this.target_dealer_detail;


          axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            // window.location = 'h3/<?= $isi ?>/detail?id=' + res.data.id;
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              app.errors = data.errors;
              toastr.error(data.message);
            }

            app.loading = false;
          });
        },
        open_dealer_target_sales_out: function(dealer_modal_type){
          this.dealer_modal_type = dealer_modal_type;
          $('#h3_dealer_target_sales_out_datatable').modal('show');
        },
        open_target_dealer_detail_items: function(index){
          this.index_target_dealer_detail = index;
          $('#h3_dealer_target_sales_out_datatable').modal('show');
        },
        hapus_target_dealer_details: function(index){
          this.target_dealer_detail.splice(index, 1);
          h3_dealer_target_sales_out_datatable.draw();
        },
        error_exist: function(key){
          return _.get(this.errors, key) != null;
        },
        get_error: function(key){
          return _.get(this.errors, key)
        }
      },
      watch: {
      },
      computed: {
        filtered_target_dealer_details: function(){
          filter_id_kabupaten = this.filter_id_kabupaten;
          filter_nama_customer = this.filter_nama_customer;
          filter_jenis_dealer = this.filter_jenis_dealer;
          
          return _.chain(this.target_dealer)
          .filter(function(data){
            if(filter_id_kabupaten != ''){
              return data.id_kabupaten == filter_id_kabupaten;
            }
            return true;
          })
          .filter(function(data){
            if(filter_nama_customer != ''){
              return data.nama_dealer.toUpperCase().includes(filter_nama_customer.toUpperCase());
            }
            return true;
          })
          .filter(function(data){
            if(filter_jenis_dealer.length > 0){
              for (filter of filter_jenis_dealer) {
                if(filter == 'H123'){
                  return data.h1 == 1 && data.h2 == 1 && data.h3 == 1;
                }else if(filter == 'H23'){
                  return data.h1 == 0 && data.h2 == 1 && data.h3 == 1;
                }else if(filter == 'H3'){
                  return data.h1 == 0 && data.h2 == 0 && data.h3 == 1;
                }
              }
            }
            return true;
          })
          .value();
        },
      },
      mounted: function(){
        config = {
          opens: 'left',
          autoUpdateInput: this.mode == 'detail' || this.mode == 'edit',
          locale: {
            format: 'DD/MM/YYYY'
          }
        };

        if(this.mode == 'detail' || this.mode == 'edit'){
          config.startDate = new Date(this.target_dealer.start_date);
          config.endDate = new Date(this.target_dealer.end_date);
        }

        periode_promo = $('#periode_target_dealer').daterangepicker(config).on('apply.daterangepicker', function(ev, picker) {
          app.target_dealer.start_date = picker.startDate.format('YYYY-MM-DD');
          app.target_dealer.end_date = picker.endDate.format('YYYY-MM-DD');
          $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        }).on('cancel.daterangepicker', function(ev, picker) {
          $(this).val('');
        });
      }
  });
</script>
    <?php
  } elseif ($set=="index") {
      ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
          <?php if($this->input->get('history') != null): ?>
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
          </a>  
          <?php else: ?>
          <a href="h3/<?= $isi ?>?history=true">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
          </a> 
          <?php endif; ?>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="container-fluid no-padding">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="" class="control-label">Produk</label>
                  <select id='filter_produk' class="form-control">
                    <option value="">-</option>
                    <option value="Parts">Parts</option>
                    <option value="Oil">Oil</option>
                    <option value="Acc">Accesories</option>
                    <option value="Apparel">Apparel</option>
                    <option value="Tools">Tools</option>
                    <option value="Other">Other</option>
                  </select>
                </div>
              </div>
            </div>
        </div>
        <table id="master_target_dealer" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>Produk</th>
              <th>Periode awal</th>
              <th>Periode akhir</th>
              <th>Target Dealer Global</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          date_render = function(data){
            return moment(data).format('DD/MM/YYYY');
          }

          rupiah_render = function(data){
            if(data != null) return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
          }

          $(document).ready(function() {
            master_target_dealer = $('#master_target_dealer').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/master_target_dealer') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.filter_produk = $('#filter_produk').val();
                    d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  }
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' },
                    { data: 'produk', orderable: false },
                    { 
                      data: 'start_date',
                      render: date_render
                    },
                    { 
                      data: 'end_date',
                      render: date_render
                    },
                    { 
                      data: 'target_global',
                      render: rupiah_render
                    },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });
          });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php } ?>
  </section>
</div>