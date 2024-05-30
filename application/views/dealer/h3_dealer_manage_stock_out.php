<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="https://unpkg.com/vue-select@latest"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>
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
    <div id="form_" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form  class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="box-body">
                <h4>
                  <b>Masukkan data <?= $title ?></b>
                </h4>
                <div v-if='mode == "detail"' class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Manage Stock Out</label>
                  <div class="col-sm-4">
                    <input v-model='manage_stock_out.id_manage_stock_out' disabled type="text" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alasan</label>
                  <div class="col-sm-4">
                    <textarea v-model='manage_stock_out.alasan' class="form-control"></textarea>
                  </div>
                </div>
                <div v-if='mode == "detail"' class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">
                    <input v-model='manage_stock_out.status' disabled type="text" class="form-control">
                  </div>
                </div>
                <table class="table">
                  <tr>
                    <td>Nomor Parts</td>
                    <td>Deskripsi Parts</td>
                    <td width='15%'>Qty Stock Out</td>
                    <td>Gudang</td>
                    <td>Rak</td>
                    <td>Satuan</td>
                    <td>Harga Beli</td>
                    <td width='3%' v-if="mode!='detail'"></td>
                  </tr>
                    <tr v-if="parts.length > 0" v-for="(part, index) of parts">
                      <td class="align-middle">{{ part.id_part }}</td>
                      <td class="align-middle">{{ part.nama_part }}</td>
                      <td class='align-middle'>
                        <vue-numeric :read-only="mode=='detail'" class="form-control form-control-sm" thousand-separator="." v-model="part.kuantitas" :empty-value="1"/>
                      </td>
                      <td class='align-middle'>{{ part.id_gudang }}</td>
                      <td class='align-middle'>{{ part.id_rak }}</td>
                      <td class='align-middle'>{{ part.satuan }}</td>
                      <td class='align-middle'>{{ part.harga_beli }}</td>
                      <td v-if="mode!='detail'" class="align-middle">
                        <button class="btn btn-sm btn-flat btn-danger" v-on:click.prevent="hapus_part(index)"><i class="fa fa-trash-o"></i></button>
                      </td>
                    </tr>
                    <tr v-if="parts.length < 1">
                      <td colspan="8" class="text-center text-muted">Belum ada part</td>
                    </tr>
              </table>
              <button v-if="mode!='detail'" type="button" class="margin pull-right btn btn-flat btn-primary btn-sm" data-toggle="modal" data-target="#parts_manage_stock_out"><i class="fa fa-plus"></i></button>
              <?php $this->load->view('modal/parts_manage_stock_out') ?>
              <script>
                function pilih_parts_manage_stock_out(data){
                  form_.parts.push(data);
                }
              </script>
              <div class="box-footer">
                <div class="col-sm-6 no-padding">
                  <button v-if="mode=='insert'" @click.prevent='<?= $form ?>' type="submit" class="btn btn-info btn-flat btn-sm"><i class="fa fa-save"></i> Save All</button>
                  <button v-if="mode=='edit'" @click.prevent='<?= $form ?>' type="submit" class="btn btn-warning btn-flat btn-sm"><i class="fa fa-save"></i> Update</button>
                  <a v-if='auth.can_update && mode == "detail"' :href="'dealer/h3_dealer_manage_stock_out/edit?id_manage_stock_out=' + manage_stock_out.id_manage_stock_out" class="btn btn-flat btn-sm btn-warning">Edit</a>
                </div>
                <div class="col-sm-6 no-padding text-right">
                  <a v-if='auth.can_approval && manage_stock_out.status == "Open"' :href="'dealer/h3_dealer_manage_stock_out/approve?id_manage_stock_out=' + manage_stock_out.id_manage_stock_out" class="btn btn-flat btn-sm btn-success">Approve</a>
                  <button v-if='auth.can_reject && manage_stock_out.status == "Open"' data-toggle='modal' data-target='#reject_modal' class='btn btn-flat btn-sm btn-danger' type='button'>Reject</button>
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
                  <a v-if='auth.can_close && manage_stock_out.status == "Approved"' :href="'dealer/h3_dealer_manage_stock_out/close?id_manage_stock_out=' + manage_stock_out.id_manage_stock_out" class="btn btn-flat btn-sm btn-success">Close</a>
                  <a v-if='auth.can_cancel && manage_stock_out.status == "Rejected"' :href="'dealer/h3_dealer_manage_stock_out/cancel?id_manage_stock_out=' + manage_stock_out.id_manage_stock_out" class="btn btn-flat btn-sm btn-danger">Canceled</a>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
  var form_ = new Vue({
      el: '#form_',
      data: {
        auth: <?= json_encode(get_user('h3_dealer_manage_stock_out')) ?>, 
        mode : '<?= $mode ?>',
        <?php if ($mode == 'detail' or $mode == 'edit'): ?>
        manage_stock_out: <?= json_encode($manage_stock_out) ?>,
        parts: <?= json_encode($parts) ?>,
        <?php else: ?>
        manage_stock_out: {},
        parts: [],
        <?php endif; ?>
      },
      methods:{
        <?= $form ?>: function(){
          post = {};
          if(this.mode == 'edit'){
            post.id_manage_stock_out = this.manage_stock_out.id_manage_stock_out;
          }
          post.parts = _.map(this.parts, function(p){
            return _.pick(p, ['id_part', 'kuantitas', 'id_rak', 'id_gudang']);
          });

          axios.post('dealer/h3_dealer_manage_stock_out/<?= $form ?>', Qs.stringify(post))
          .then(function(r){
            if(_.isObject(r.data)){
              window.location = 'dealer/h3_dealer_manage_stock_out/detail?id_manage_stock_out=' + r.data.id_manage_stock_out;
            }
            console.log(r.data);
          })
          .catch(function(e){
            toastr.error(e);
          })
        },
        reject: function(){
          post = {};
          post.id_manage_stock_out = this.manage_stock_out.id_manage_stock_out;
          post.alasan_reject = $('#alasan_reject').val();

          axios.post('dealer/h3_dealer_manage_stock_out/reject', Qs.stringify(post))
          .then(function(r){
            // window.location = 'dealer/h3_dealer_manage_stock_out/detail?id_manage_stock_out=' + r.data.id_manage_stock_out;
          })
          .catch(function(e){
            toastr.error(e);
          });
        },
        hapus_part: function(index){
          this.parts.splice(index, 1);
        }
      },
  });
</script>
    <?php
  } elseif ($set=="index") {
      ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
        <?php if(can_access('h3_dealer_manage_stock_out', 'can_insert')): ?>
          <a href="dealer/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
        <?php endif; ?>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="manage_stock_out" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID Manage Stock Out</th>
              <th>Tanggal</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            manage_stock_out = $('#manage_stock_out').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                order: [],
                ajax: {
                    url: "<?= base_url('api/dealer/manage_stock_out') ?>",
                    dataSrc: "data",
                    type: "POST"
                },
                columns: [
                    { data: 'id_manage_stock_out' },
                    { data: 'tanggal' },
                    { data: 'status' },
                    { data: 'action' },
                ],
            });
          });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php
  }
    ?>
  </section>
</div>