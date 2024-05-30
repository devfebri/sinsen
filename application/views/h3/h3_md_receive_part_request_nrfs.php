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
<body>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?= $breadcrumb ?>
  </section>
  <section class="content">
    <?php if($set == 'form'): ?>
    <?php 
      $form     = '';
      $disabled = '';
      $readonly = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'terima_claim') {
        $form = 'simpan_claim';
      }
      if ($mode == 'detail') {
        $form = 'detail';
        $disabled = 'disabled';
      }
      if ($mode == 'edit') {
        $form = 'update';
      }
    ?>
    <div id="app" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>  
        </h3>
      </div><!-- /.box-header -->
      <div v-if="loading" class="overlay">
        <i class="fa fa-refresh fa-spin text-light-blue"></i>
      </div>
      <div class="box-body">
        <?php $this->load->view('template/session_message.php'); ?>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h3/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No. Dokumen NRFS</label>
                  <div class="col-sm-4">                    
                    <input v-model="part_request.dokumen_nrfs_id" readonly type="text" class="form-control">
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No. Part Request</label>
                  <div class="col-sm-4">                    
                    <input v-model="part_request.request_id" readonly type="text" class="form-control">
                  </div>
                  <label class="col-sm-2 control-label">No. Shipping List</label>
                  <div class="col-sm-4">                    
                    <input v-model="part_request.no_shiping_list" readonly type="text" class="form-control">
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Kode Tipe Unit</label>
                  <div class="col-sm-4">                    
                    <input v-model="part_request.type_code" readonly type="text" class="form-control">
                  </div>
                  <label class="col-sm-2 control-label">No. Rangka</label>
                  <div class="col-sm-4">                    
                    <input v-model="part_request.no_rangka" readonly type="text" class="form-control">
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tipe Unit</label>
                  <div class="col-sm-4">                    
                    <input v-model="part_request.deskripsi_unit" readonly type="text" class="form-control">
                  </div>
                  <label class="col-sm-2 control-label">No. Mesin</label>
                  <div class="col-sm-4">                    
                    <input v-model="part_request.no_mesin" readonly type="text" class="form-control">
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Deskripsi Warna</label>
                  <div class="col-sm-4">                    
                    <input v-model="part_request.deskripsi_warna" readonly type="text" class="form-control">
                  </div>
                  <label class="col-sm-2 control-label">Sumber NRFS</label>
                  <div class="col-sm-4">                    
                    <input v-model="part_request.sumber_rfs_nrfs" readonly type="text" class="form-control">
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">                    
                    <input v-model="part_request.status_request" readonly type="text" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-responsive">
                      <thead>
                        <tr>                                      
                          <th class='align-top' width='3%'>No.</th>              
                          <th class='align-top'>Kode Part</th>              
                          <th class='align-top'>Nama Part</th>              
                          <th class='align-top' width="10%">Qty</th>
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) in parts">
                          <td class="align-middle">{{ index + 1 }}.</td>                       
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>            
                          <td class="align-middle">
                            <vue-numeric class="form-control" :read-only='true' separator="." :empty-value="1" v-model="part.qty_part"/>
                          </td>      
                        </tr>
                        <tr v-if="parts.length < 1">
                          <td class="text-center" colspan="4">Belum ada part</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>                                                                                                                                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-6 text-right">
                  <button v-if='mode == "detail" && part_request.status == "Open"' @click.prevent='approve' type="submit" class="btn btn-sm btn-success btn-flat">Approve</button>                  
                  <button v-if='mode == "detail" && part_request.status == "Open"' @click.prevent='reject' class="btn btn-sm btn-danger btn-flat">Reject</button>                  
                  <div id="reject_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                                  <button @click.prevent='reject' class="btn btn-flat btn-sm btn-primary" data-dismiss="modal">Submit</button>
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
    </div><!-- /.box -->
    <script>
      var app = new Vue({
          el: '#app',
          data: {
            errors: {},
            loading: false,
            mode: '<?= $mode ?>',
            index_part: 0,
            part_request: <?= json_encode($part_request) ?>,
            parts: <?= json_encode($parts) ?>,
          },
          methods: {
            approve: function(){
              this.loading = true;
              post = _.pick(this.part_request, ['request_id']);
              
              axios.post('h3/<?= $isi ?>/approve', Qs.stringify(post))
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?request_id=' + res.data.request_id;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; });
            },
            reject: function(){
              this.loading = true;
              post = _.pick(this.part_request, ['request_id']);
              // post.message = $('#alasan_reject').val();
              
              axios.post('h3/<?= $isi ?>/reject', Qs.stringify(post))
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?request_id=' + res.data.request_id;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; });
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            }
          },
          watch:{
            'claim_dealer.id_packing_sheet': function(){
              datatable_parts_claim_dealer.draw();
            },
            'claim_dealer.id_dealer': function(){
              datatable_packing_sheet_claim_dealer.draw();
            },
            parts: {
              deep: true,
              handler: function(){
                datatable_parts_claim_dealer.draw();
              }
            }
          },
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-4">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">No. Part Request</label>
                    <input id='no_part_request_filter' type="text" class="form-control">
                  </div>
                </div>
              </div>
              <script>
                $(document).ready(function(){
                  $('#no_part_request_filter').on('keyup', _.debounce( function(){
                    receive_part_request_nrfs.draw();
                  }, 500));
                });
              </script>
            </div>
            <div class="col-sm-4">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">No. Dokumen NRFS</label>
                    <input id='no_dokumen_nrfs_filter' type="text" class="form-control">
                  </div>
                </div>
              </div>
              <script>
                $(document).ready(function(){
                  $('#no_dokumen_nrfs_filter').on('keyup', _.debounce( function(){
                    receive_part_request_nrfs.draw();
                  }, 500));
                });
              </script>
            </div>
            <div class="col-sm-4">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">No. Shipping List</label>
                    <input id='no_shipping_list_filter' type="text" class="form-control">
                  </div>
                </div>
              </div>
              <script>
                $(document).ready(function(){
                  $('#no_shipping_list_filter').on('keyup', _.debounce( function(){
                    receive_part_request_nrfs.draw();
                  }, 500));
                });
              </script>
            </div>
          </div>
        </div>
        <table id="receive_part_request_nrfs" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>No. Parts Request</th>              
              <th>No. Dokumen NRFS</th>              
              <th>No. Shipping List</th>              
              <th>No. Mesin</th>              
              <th>No. Rangka</th>              
              <th>Kode Tipe Unit</th>              
              <th>Sumber NRFS</th>              
              <th>Status</th>              
              <th width='3%'>Action</th>              
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          receive_part_request_nrfs = $('#receive_part_request_nrfs').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            scrollX: true,
            ajax: {
                url: "<?= base_url('api/md/h3/receive_part_request_nrfs') ?>",
                dataSrc: "data",
                type: "POST",
                data: function(d){
                  d.no_part_request_filter = $('#no_part_request_filter').val();
                  d.no_dokumen_nrfs_filter = $('#no_dokumen_nrfs_filter').val();
                  d.no_shipping_list_filter = $('#no_shipping_list_filter').val();
                }
            },
            columns: [
                { data: 'index', orderable: false, width: '3%' },
                { 
                  data: 'request_id',
                  render: function(data){
                    if(data != null && data != ''){
                      return data;
                    }
                    return '-';
                  }
                }, 
                { 
                  data: 'dokumen_nrfs_id',
                  render: function(data){
                    if(data != null && data != ''){
                      return data;
                    }
                    return '-';
                  }
                }, 
                { 
                  data: 'no_shiping_list',
                  render: function(data){
                    if(data != null && data != ''){
                      return data;
                    }
                    return '-';
                  }
                }, 
                { data: 'no_mesin' }, 
                { data: 'no_rangka' }, 
                { data: 'type_code' }, 
                { data: 'sumber_rfs_nrfs' }, 
                { data: 'status_request' }, 
                { data: 'action', orderable: false, className: 'text-center' }, 
            ],
          });
        });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>
