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
  Vue.component('v-select', VueSelect.VueSelect);
</script>
    <div id="form_" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div v-if='loading' class="overlay">
        <i class="text-light-blue fa fa-refresh fa-spin"></i>
      </div>
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form  class="form-horizontal" action="dealer/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                <h4>
                  <b>Masukkan data <?= $title ?></b>
                </h4>
                <div v-if='mode == "detail" || mode == "edit"' class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Outbound Form for Part Transfer</label>
                  <div class="col-sm-4">
                    <input readonly v-model='outbound_form.id_outbound_form_part_transfer' type="text" class="form-control">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Request</label>
                  <div class="col-sm-4">
                    <input readonly v-model='outbound_form.created_at' type="text" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">
                    <select class="form-control" v-model='tipe' :disabled='mode == "detail"'>
                      <option value="">-choose-</option>
                      <option value="Between Warehouse">Between Warehouse</option>
                      <option value="POS">POS</option>
                    </select>
                  </div>
                  <div v-if='mode == "detail" || mode == "edit"'>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal In Transit</label>
                    <div class="col-sm-4">
                      <input readonly v-model='outbound_form.tanggal_in_transit' type="text" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alasan Transfer</label>
                  <div class="col-sm-4">
                    <textarea :disabled='mode == "detail"' class="form-control" v-model='alasan'></textarea>
                  </div>
                  <div v-if='mode == "detail" || mode == "edit"'>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Closed</label>
                    <div class="col-sm-4">
                      <input readonly v-model='outbound_form.tanggal_closed' type="text" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang Asal</label>
                  <div class="col-sm-4">
                    <input readonly v-model='gudang.id_gudang' type="text" class="form-control" data-toggle='modal' data-target='#gudang_outbound_form' placeholder='Pilih gudang'>
                  </div>
                </div>
                <div v-if='mode == "detail"' class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">
                    <input readonly v-model='outbound_form.status' type="text" class="form-control">
                  </div>
                </div>
                <div v-if='mode == "detail" && outbound_form.keterangan != null' class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-4">
                    <input readonly v-model='outbound_form.keterangan' type="text" class="form-control">
                  </div>
                </div>
                <?php $this->load->view('modal/gudang_outbound_form') ?>
                <script>
                  function pilih_gudang_outbound_form(data) {
                    form_.gudang = data;
                  }
                </script>
                <table class="table">
                  <tr>
                    <td width='3%'>No.</td>
                    <td>Nomor Parts</td>
                    <td>Deskripsi Parts</td>
                    <td width="10%">Qty Asal</td>
                    <td width="10%">Qty Transfer</td>
                    <td>Gudang Asal</td>
                    <td>Rak Asal</td>
                    <td>Gudang Tujuan</td>
                    <td>Rak Tujuan</td>
                    <td v-if="mode!='detail'">Aksi</td>
                  </tr>
                    <tr v-if="parts.length > 0" v-for="(part, index) of parts">
                      <td class="align-middle">{{ index + 1 }}.</td>
                      <td class="align-middle">
                        {{ part.id_part }}
                      </td>
                      <td class="align-middle">{{ part.nama_part }}</td>
                      <td class="align-middle">
                        <vue-numeric :read-only="true" class="form-control form-control-sm" thousand-separator="." v-model="part.qty_asal" :empty-value="1"/>
                      </td>
                      <td class="align-middle">
                        <vue-numeric :read-only="mode=='detail'" class="form-control form-control-sm" thousand-separator="." v-model="part.kuantitas" :empty-value="1" :max='part.qty_asal'/>
                      </td>
                      <td class="align-middle">
                        <input disabled class="form-control" type="text" v-model="part.id_gudang">
                      </td>
                      <td class="align-middle">
                        <input disabled class="form-control" type="text" v-model="part.id_rak">
                      </td>
                      <!-- <td class="align-middle">
                        <input readonly v-model='part.id_gudang_tujuan' @click.prevent='change_index(index)' type="text" class="form-control">
                      </td>
                      <td class="align-middle">
                        <input readonly v-model='part.id_rak_tujuan' @click.prevent='change_index(index)' type="text" class="form-control">
                      </td> -->
                      <td v-if="tipe =='Between Warehouse' ||tipe ==''" class="align-middle">
                        <input readonly v-model='part.id_gudang_tujuan' @click.prevent='change_index(index)' type="text" class="form-control">
                      </td>
                      <td v-else="tipe =='POS'" class="align-middle">
                        <input readonly v-model='part.id_gudang_tujuan' @click.prevent='change_index2(index)' type="text" class="form-control">
                      </td>
                      <td v-if="tipe =='Between Warehouse' ||tipe ==''" class="align-middle">
                        <input readonly v-model='part.id_rak_tujuan' @click.prevent='change_index(index)' type="text" class="form-control">
                      </td>
                      <td v-else-if="tipe =='POS'" class="align-middle">
                        <input readonly v-model='part.id_rak_tujuan' @click.prevent='change_index2(index)' type="text" class="form-control">
                      </td>
                      <td v-if="mode!='detail'" class="align-middle">
                        <button class="btn btn-sm btn-flat btn-danger" v-on:click.prevent="hapusPart(index)"><i class="fa fa-trash-o"></i></button>
                      </td>
                    </tr>
                    <tr v-if="parts.length < 1">
                      <td colspan="8" class="text-center text-muted">Belum ada part</td>
                    </tr>
              </table>
              <button v-if="mode!='detail'" style="margin-top:15px" type="button" class="mt-3 pull-right btn btn-flat btn-info btn-sm" data-toggle="modal" data-target="#parts_outbound_form">Tambah Part</button>
              <?php $this->load->view('modal/parts_outbound_form') ?>
              <script>
                function parts_outbound_form(data) {
                  parts = form_.parts;
                  length = parts.length;
                  if(length > 0){
                    data.id_gudang_tujuan = parts[length-1].id_gudang_tujuan;
                    data.id_rak_tujuan = parts[length-1].id_rak_tujuan;
                  }
                  form_.parts.push(data);
                }
              </script>
              <br>
                <br>
              <div class="box-footer">
              <div class="alert alert-warning alert-dismissable" v-if='mode=="insert"'>
                    <strong>Perhatian!</strong>
                    <p>Dicek kembali qty booking pada part yang akan dimutasi</p>
                  </div>
                <div class="col-sm-6 no-padding">
                  <button v-if="mode=='insert'" @click.prevent='<?= $form ?>' type="button" class="btn btn-sm btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button v-if="mode=='edit'" @click.prevent='<?= $form ?>' type="submit" class="btn btn-sm btn-warning btn-flat"><i class="fa fa-save"></i> Update</button>
                  <a v-if='mode == "detail" && (outbound_form.status == "Open" || outbound_form.status == "Rejected") && auth.can_update' :href="'dealer/h3_dealer_outbound_form_part_transfer/edit?k=' + outbound_form.id_outbound_form_part_transfer" class="btn btn-flat btn-sm btn-warning">Edit</a>
                  <a v-if='mode == "detail" && auth.can_print' :href="'dealer/h3_dealer_outbound_form_part_transfer/report?k=' + outbound_form.id_outbound_form_part_transfer" class="btn btn-flat btn-sm btn-primary">Report</a>
                </div>
                <div class="col-sm-6 no-padding text-right">
                  <button v-if='mode == "detail" && outbound_form.status == "Rejected" && auth.can_reopen' @click.prevent='reopen' class="btn btn-flat btn-sm btn-success">Re-Open</button>
                  <button v-if='mode == "detail" && outbound_form.status == "Open" && auth.can_reject' type='button' data-toggle='modal' data-target='#reject_modal' class="btn btn-danger btn-sm btn-flat">Reject</button>
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
                  <button v-if='mode == "detail" && outbound_form.status == "Open" && auth.can_transit' @click.prevent='transit' class="btn btn-flat btn-sm btn-info">Transit</button>
                  <button v-if='mode == "detail" && outbound_form.status == "In Transit" && auth.can_close' @click.prevent='close' class="btn btn-flat btn-sm btn-info">Close</button>
                </div>
              </div><!-- /.box-footer -->
              <?php $this->load->view('modal/rak_outbound_form') ?>
              <?php $this->load->view('modal/rak_outbound_form_pos') ?>
              <script>
                function rak_outbound_form(data) {
                  form_.parts[form_.index_part].id_gudang_tujuan = data.id_gudang;
                  form_.parts[form_.index_part].id_rak_tujuan = data.id_rak;
                }
              </script>
              <script>
                function rak_outbound_form_pos(data) {
                  form_.parts[form_.index_part].id_gudang_tujuan = data.id_gudang;
                  form_.parts[form_.index_part].id_rak_tujuan = data.id_rak;
                }
              </script>
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
  var form_ = new Vue({
      el: '#form_',
      data: {
        auth: <?= json_encode(get_user('h3_dealer_outbound_form_part_transfer')) ?>, 
        mode : '<?= $mode ?>',
        index_part: 0,
        loading: false,
        <?php if ($mode == 'detail' or $mode == 'edit'): ?>
        outbound_form: <?= json_encode($outbound_form_part_transfer) ?>,
        parts: <?= json_encode($outbound_form_part_transfer_parts) ?>,
        gudang: <?= json_encode($gudang) ?>,
        tipe: '<?= $outbound_form_part_transfer->tipe ?>',
        alasan: '<?= $outbound_form_part_transfer->alasan ?>',
        <?php else: ?>
        parts: [],
        gudang: {},
        tipe: '',
        alasan: '',
        <?php endif; ?>
      },
      methods:{
        <?= $form ?>: function(){
          post = {};
          if(this.mode == 'edit'){
            post.id_outbound_form_part_transfer = this.outbound_form.id_outbound_form_part_transfer;
          }
          post.tipe = this.tipe;
          post.id_gudang = this.gudang.id_gudang;
          post.alasan = this.alasan;
          post.parts = _.map(this.parts, function(p){
            return _.pick(p, ['id_part', 'qty_asal', 'id_rak', 'id_gudang', 'kuantitas', 'id_rak_tujuan', 'id_gudang_tujuan']);
          });

          axios.post('dealer/h3_dealer_outbound_form_part_transfer/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            console.log(res);
            window.location = 'dealer/h3_dealer_outbound_form_part_transfer/detail?k=' + res.data.id_outbound_form_part_transfer;
          })
          .catch(function(err){
            toastr.error(err);
          });
        },
        reopen: function(){
          this.loading = true;
          axios.get('dealer/h3_dealer_outbound_form_part_transfer/reopen', {
            params : {
              id_outbound_form_part_transfer: this.outbound_form.id_outbound_form_part_transfer
            }
          })
          .then(function(res){
            window.location = 'dealer/h3_dealer_outbound_form_part_transfer/detail?k=' + res.data.id_outbound_form_part_transfer;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; });
        },
        reject: function(){
          post = {};
          post.id_outbound_form_part_transfer = this.outbound_form.id_outbound_form_part_transfer;
          post.keterangan = $('#alasan_reject').val();

          this.loading = true;
          axios.post('dealer/h3_dealer_outbound_form_part_transfer/reject', Qs.stringify(post))
          .then(function(res){
            window.location = 'dealer/h3_dealer_outbound_form_part_transfer/detail?k=' + res.data.id_outbound_form_part_transfer;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; })
        },
        transit: function(){
          this.loading = true;
          axios.get('dealer/h3_dealer_outbound_form_part_transfer/transit', {
            params : {
              k: this.outbound_form.id_outbound_form_part_transfer
            }
          })
          .then(function(res){
            window.location = 'dealer/h3_dealer_outbound_form_part_transfer/detail?k=' + res.data.id_outbound_form_part_transfer;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; });
        },
        close: function(){
          this.loading = true;
          axios.get('dealer/h3_dealer_outbound_form_part_transfer/close', {
            params : {
              k: this.outbound_form.id_outbound_form_part_transfer,
              tipe: this.tipe
            }
          })
          .then(function(res){
            window.location = 'dealer/h3_dealer_outbound_form_part_transfer/detail?k=' + res.data.id_outbound_form_part_transfer;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; });
        },
        change_index: function(index){
          this.index_part = index;
          rak_outbound_form_datatable.draw();
          $('#rak_outbound_form').modal('show');
        },
        change_index2: function(index){
          this.index_part = index;
          rak_outbound_form_pos_datatable.draw();
          $('#rak_outbound_form_pos').modal('show');
        },
        hapusPart: function(index){
          this.parts.splice(index, 1);
        }
      },
      watch: {
        gudang: {
          deep: true,
          handler: function(){
            parts_outbound_form_datatable.draw();
          }
        }
      }
  });
</script>

    <?php

  } elseif ($set=="index") {

      ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <?php if(can_access('h3_dealer_outbound_form_part_transfer', 'can_insert')): ?>
          <a href="dealer/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
          <?php endif; ?>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="example1" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Tipe</th>
              <th>Status</th>
              <th>Tanggal</th>
            </tr>
          </thead>
          <tbody>
          <?php if (count($outbound_form_part_transfer) > 0): ?>
            <?php foreach ($outbound_form_part_transfer as $e): ?>
              <tr>
                <td><a href="dealer/<?= $isi ?>/detail?k=<?= $e->id_outbound_form_part_transfer ?>"><?= $e->id_outbound_form_part_transfer ?></a></td>
                <td><?= $e->tipe ?></td>
                <td><?= $e->status ?></td>
                <td><?= date('d-m-Y', strtotime($e->created_at)) ?></td>
              </tr>
            <?php endforeach ?>
          <?php endif; ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php
  }
    ?>
  </section>
</div>