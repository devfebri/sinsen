<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="https://unpkg.com/vue-select@latest"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />

<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?php echo $breadcrumb ?>
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
          $form = 'detail';
      }
      if ($mode=='edit') {
          $form = 'update';
      } ?>
<script>
  Vue.use(VueNumeric.default);
</script>
    <div id="form_" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="<?= base_url("dealer/{$isi}") ?>">
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
              <form  class="form-horizontal">
                <div class="box-body">
                <h4>
                  <b>Masukkan data <?= $title ?></b>
                </h4>
                <div class="form-group">
                  <div v-if='mode == "detail" || mode == "edit"'>
                    <label class="col-sm-2 control-label">ID Event</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" v-model="event.id_event" :readonly='mode == "detail" || mode == "edit"'>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">PIC Event</label>
                  <div v-bind:class="{ 'has-error': errors.pic != null }" class="col-sm-4">
                    <input type="text" class="form-control" v-model="event.nama_pic" readonly data-toggle='modal' :data-target='mode != "detail" ? "#pic_event" : ""'>
                    <small v-if='errors.pic != null' class="form-text text-danger">{{ errors.pic }}</small>
                  </div>
                  <?php $this->load->view('modal/pic_event') ?>
                  <script>
                    function pilih_pic_event(data){
                      console.log(data);
                      form_.event.pic = data.id_karyawan_dealer;
                      form_.event.nama_pic = data.nama_lengkap;
                    }
                  </script>
                  <label class="col-sm-2 control-label">Nama Event</label>
                  <div v-bind:class="{ 'has-error': errors.nama != null }" class="col-sm-4">
                    <input type="text" class="form-control" v-model="event.nama" :readonly='mode == "detail"'>
                    <small v-if='errors.nama != null' class="form-text text-danger">{{ errors.nama }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Lokasi Event</label>
                  <div v-bind:class="{ 'has-error': errors.lokasi_event != null }" class="col-sm-4">
                    <input type="text" class="form-control" v-model="event.lokasi_event" :readonly='mode == "detail"'>
                    <small v-if='errors.lokasi_event != null' class="form-text text-danger">{{ errors.lokasi_event }}</small>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Event</label>
                  <div v-bind:class="{ 'has-error': errors.deskripsi != null }" class="col-sm-4">
                    <input type="text" class="form-control" v-model="event.deskripsi" :readonly='mode == "detail"'>
                    <small v-if='errors.deskripsi != null' class="form-text text-danger">{{ errors.deskripsi }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Tipe Event</label>
                  <div v-bind:class="{ 'has-error': errors.tipe != null }" class="col-sm-4">
                    <select v-model='event.tipe' class='form-control' :disabled='mode == "detail"'>
                      <option value="">-Choose-</option>
                      <option value="Pameran">Pameran</option>
                      <option value="Showroom Event">Showroom Event</option>
                      <option value="Roadshow">Roadshow</option>
                    </select>
                    <small v-if='errors.tipe != null' class="form-text text-danger">{{ errors.tipe }}</small>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Periode Event</label>
                  <div v-bind:class="{ 'has-error': errors.start_date != null || errors.end_date != null }" class="col-sm-4">
                    <input type="text" id='periode_event' class="form-control" :disabled='mode == "detail"'>
                    <small v-if='errors.start_date != null || errors.end_date != null' class="form-text text-danger">{{ errors.start_date || errors.end_date }}</small>
                  </div>
                </div>
                <div v-if='mode == "detail"' class="form-group">
                  <label class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" v-model="event.status" readonly>
                  </div>
                </div>
                <table class="table table-striped table-condensed">
                  <tr>
                    <td width='3%'>No.</td>
                    <td width='15%'>Nomor Parts</td>
                    <td width='15%'>Deskripsi Parts</td>
                    <td width="8%">Stock AVS</td>
                    <td width='10%'>Gudang Asal</td>
                    <td width='10%'>Rak Asal</td>
                    <td width="8%">Qty Asal</td>
                    <td width="10%">AVS</td>
                    <td width="8%">Qty Transfer</td>
                    <td width="10%">UOM</td>
                    <td v-if='mode != "detail"' width='3%'></td>
                  </tr>
                    <tr v-if="parts.length > 0" v-for="(part, index) of parts">
                      <td class="align-middle">{{ index + 1 }}.</td>
                      <td class="align-middle">{{ part.id_part }}</td>
                      <td class="align-middle">{{ part.nama_part }}</td>
                      <td class="align-middle">
                        <vue-numeric :read-only="true" class="form-control" thousand-separator="." v-model="part.stock_avs" :empty-value="1"/>
                      </td>
                      <td class="align-middle">{{ part.id_gudang }}</td>
                      <td class="align-middle">{{ part.id_rak }}</td>
                      <td class="align-middle">
                        <vue-numeric :read-only="true" class="form-control" thousand-separator="." v-model="part.stock" :empty-value="1"/>
                      </td>
                      <td class="align-middle">
                        <vue-numeric read-only class="form-control" thousand-separator="." v-model="part.stock_avs" :empty-value="1"/>
                      </td>
                      <td class="align-middle">
                        <vue-numeric :max='part.stock_avs' :read-only="mode == 'detail'" class="form-control" thousand-separator="." v-model="part.kuantitas" :empty-value="1"/>
                      </td>
                      <td class="align-middle">{{ part.satuan }}</td>
                      <td v-if='mode != "detail"' class="align-middle">
                        <button @click.prevent='hapus_part(index)' class="btn btn-flat btn-sm btn-danger"><i class="fa fa-trash-o"></i></button>
                      </td>
                    </tr>
                    <tr v-if="parts.length < 1">
                      <td colspan="8" class="text-center text-muted">Belum ada part</td>
                    </tr>
              </table>
              <div class="row">
                  <div class="col-sm-12 text-right">
                    <button v-if='mode != "detail"' class="btn btn-flat btn-sm btn-primary margin" data-toggle='modal' data-target='#parts_event' type='button'><i class="fa fa-plus"></i></button>
                  </div>
              </div>
              <?php $this->load->view('modal/parts_event') ?>
              <script>
                function pilih_parts_event(data) {
                  form_.parts.push(data);
                  parts_event_datatable.draw();
                }
              </script>
              <div class="box-footer">
                <div class="col-sm-6 no-padding">
                  <button v-if="mode=='insert'" @click.prevent='<?= $form ?>' type="submit" class="btn btn-sm btn-info btn-flat"><i class="fa fa-save"></i> Simpan</button>
                  <button v-if="mode=='edit'" @click.prevent='<?= $form ?>' type="submit" class="btn btn-sm btn-warning btn-flat"><i class="fa fa-save"></i> Update</button>
                  <a v-if='mode == "detail"' :href="'dealer/h3_dealer_event_h23/edit?id_event=' + event.id_event" class="btn btn-flat btn-sm btn-warning">Edit</a>
                </div>
                <div class="col-sm-6 text-right no-padding">
                  <button v-if='mode == "detail" && event.status == "Open"' @click.prevent='approve' class="btn btn-flat btn-sm btn-success" type='button'>Approve</button>
                  <button v-if='mode == "detail" && event.status == "Open"' data-toggle='modal' data-target='#reject_modal' class="btn btn-flat btn-sm btn-danger" type='button'>Reject</button>
                  <!-- Modal -->
                  <div id="reject_modal" class="modal fade modalcustomer" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">×</span>
                                  </button>
                                  <h4 class="modal-title text-left" id="myModalLabel">Alasan Reject</h4>
                              </div>
                              <div class="modal-body">
                                <div class="form-group">
                                  <div class="col-sm-12">
                                    <textarea class="form-control" id="alasan_reject"></textarea>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-sm-12">
                                    <button @click.prevent='reject' class="btn btn-flat btn-sm btn-primary">Submit</button>
                                  </div>
                                </div>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
form_ = new Vue({
    el: '#form_',
    data: {
      kosong :'',
      mode : '<?= $mode ?>',
      loading: false,
      errors: {},
      <?php if ($mode == 'detail' or $mode == 'edit'): ?>
      event: <?= json_encode($event) ?>,
      parts: <?= json_encode($parts) ?>,
      <?php else: ?>
      event: {
        pic: '',
        nama_pic: '',
        nama: '',
        deskripsi: '',
        lokasi_event: '',
        tipe: '',
        start_date: '',
        end_date: '',
      },
      parts: [],
      <?php endif; ?>
    },
    methods: {
      <?= $form ?>: function(){
        post = {};
        if(this.mode == 'edit'){
          post.id_event = this.event.id_event;
        }
        post.pic = this.event.pic;
        post.nama = this.event.nama;
        post.deskripsi = this.event.deskripsi;
        post.lokasi_event = this.event.lokasi_event;
        post.tipe = this.event.tipe;
        post.start_date = this.event.start_date;
        post.end_date = this.event.end_date;
        post.parts = _.map(this.parts, function(p){
          return _.pick(p, ['id_part', 'kuantitas', 'stock', 'id_rak', 'id_gudang', 'stock_avs', 'stock']);
        });

        this.loading = true;
        axios.post('dealer/h3_dealer_event_h23/<?= $form ?>', Qs.stringify(post))
        .then(function(res){
          window.location = 'dealer/h3_dealer_event_h23/detail?id_event=' + res.data.id_event;
        })
        .catch(function(err){
          form_.errors = err.response.data;
          toastr.error(err);
        })
        .then(function(){ form_.loading = false; });
      },
      approve: function(){
        this.loading = true;
        axios.get('dealer/h3_dealer_event_h23/approve', {
          params: {
            id_event: this.event.id_event
          }
        })
        .then(function(res){
          window.location = 'dealer/h3_dealer_event_h23/detail?id_event=' + res.data.id_event;
        })
        .catch(function(err){
          toastr.error(err);
        })
        .then(function(){ form_.loading = false; })
      },
      reject: function(){
        this.loading = true;
        axios.get('dealer/h3_dealer_event_h23/reject', {
          params: {
            id_event: this.event.id_event,
            alasan_reject: $('#alasan_reject').val()
          }
        })
        .then(function(res){
          window.location = 'dealer/h3_dealer_event_h23/detail?id_event=' + res.data.id_event;
        })
        .catch(function(err){
          toastr.error(err);
        })
        .then(function(){ form_.loading = false; })
      },
      hapus_part: function(index){
        this.parts.splice(index, 1);
        parts_event_datatable.draw();
      }
    },
    mounted: function(){
      // promo_date = $(this.$el.children[1].firstChild.firstChild.firstChild.firstChild.children[4].children[3].firstChild).daterangepicker({
      config = {
        opens: 'left',
        autoUpdateInput: this.mode == 'detail' || this.mode == 'edit',
        locale: {
          format: 'DD/MM/YYYY'
        }
      };

      if(this.mode == 'detail' || this.mode == 'edit'){
        config.startDate = new Date(this.event.start_date);
        config.endDate = new Date(this.event.end_date);
      }

      promo_date = $('#periode_event').daterangepicker(config).on('apply.daterangepicker', function(ev, picker) {
        form_.event.start_date = picker.startDate.format('YYYY-MM-DD');
        form_.event.end_date = picker.endDate.format('YYYY-MM-DD');
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
      }).on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
      });
    }
});
</script>
<?php } elseif ($set=="index") { ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href='<?= base_url("dealer/{$isi}/add") ?>'>
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="event_h23" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>ID Event</th>
              <th>Nama Event</th>
              <th>Deskripsi</th>
              <th>Lokasi</th>
              <th>PIC</th>
              <th>Tanggal Mulai</th>
              <th>Tanggal Selesai</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            event_h23 = $('#event_h23').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "<?= base_url('api/dealer/event_h23') ?>",
                    dataSrc: "data",
                    type: "POST"
                },
                columns: [
                    { data: null, orderable: false, width: '3%' },
                    { data: 'id_event' },
                    { data: 'nama' },
                    { data: 'deskripsi' },
                    { data: 'lokasi_event' },
                    { data: 'pic' },
                    { data: 'start_date' },
                    { data: 'end_date' },
                    { data: 'action', orderable: false, width: '3%', className: 'text-center' },
                ],
            });

            event_h23.on('draw.dt', function() {
                var info = event_h23.page.info();
                event_h23.column(0, {
                    search: 'applied',
                    order: 'applied',
                    page: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1 + info.start + ".";
                });
            });
          });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php } ?>
  </section>
</div>