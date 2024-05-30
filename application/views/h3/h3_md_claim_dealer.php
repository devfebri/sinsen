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
              <div v-if='part_claim_yang_melebihi_batas.length > 0' class="alert alert-warning" role="alert">
                <strong>Perhatian!</strong> Terdapat kode part yang melebihi sisa kuantitas yang boleh diclaim, antara lain:
                <ul>
                  <li v-for='row of part_claim_yang_melebihi_batas'>
                    Kode Part {{ row.id_part }} dengan kuantitas yang bisa diclaim {{ (row.qty_packing_sheet - row.part_sudah_diclaim) }} dari {{ row.qty_packing_sheet }}
                    <ul>
                      <li v-for='row_groupedPart of row.groupedPart'>Kode Part {{ row_groupedPart.id_part }} dengan kode claim {{ row_groupedPart.kode_claim }} part yang akan diclaim {{ row_groupedPart.qty_part_diclaim }}</li>
                    </ul>
                  </li>
                </ul>
              </div>
              <div v-if='terdapat_part_dengan_qty_claim_nol.length > 0' class="alert alert-warning" role="alert">
                <strong>Perhatian!</strong> Terdapat part dengan kuantitas 0 pada part yang akan diclaim, antara lain: 
                  <span v-for='(id_part, index) of terdapat_part_dengan_qty_claim_nol'>
                    <span>{{ id_part }}</span>
                    <span v-if='(terdapat_part_dengan_qty_claim_nol.length - 1) != index'>, </span>
                  </span>
              </div>
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nama Customer</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_dealer') }" class="col-sm-4">                    
                    <div class="input-group">
                      <input v-model="claim_dealer.nama_dealer" readonly type="text" class="form-control">
                      <div class="input-group-btn">
                        <button v-if='customer_empty || mode == "detail"' :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle="modal" data-target="#h3_md_dealer_claim_dealer"><i class="fa fa-search"></i></button>
                        <button v-if='!customer_empty && mode != "detail"' class="btn btn-flat btn-danger" @click.prevent='reset_customer'><i class="fa fa-trash-o"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('id_dealer')" class="form-text text-danger">{{ get_error('id_dealer') }}</small>                                
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Alamat Customer</label>
                  <div class="col-sm-4">                    
                    <input v-model="claim_dealer.alamat" readonly type="text" class="form-control">
                  </div>                                
                </div>
                <?php $this->load->view('modal/h3_md_dealer_claim_dealer.php'); ?>
                <script>
                  function pilih_dealer_claim_dealer(dealer){
                    app.claim_dealer.id_dealer = dealer.id_dealer;
                    app.claim_dealer.nama_dealer = dealer.nama_dealer;
                    app.claim_dealer.alamat = dealer.alamat;
                  }
                </script>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No Packing Sheet</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_packing_sheet') }" class="col-sm-4">                    
                    <div class="input-group">
                      <input v-model="claim_dealer.id_packing_sheet" readonly type="text" class="form-control">
                      <div class="input-group-btn">
                        <button v-if='packing_sheet_empty || mode == "detail"' :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle="modal" data-target="#modal-packing-sheet-claim-dealer"><i class="fa fa-search"></i></button>
                        <button v-if='!packing_sheet_empty && mode != "detail"' class="btn btn-flat btn-danger" @click.prevent='reset_packing_sheet'><i class="fa fa-trash-o"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('id_packing_sheet')" class="form-text text-danger">{{ get_error('id_packing_sheet') }}</small>                                
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_packing_sheet_claim_dealer_modal') ?>
                <script>
                  function pilih_packing_sheet_claim_dealer(packing_sheet){
                    app.claim_dealer.id_dealer = packing_sheet.id_dealer;
                    app.claim_dealer.nama_dealer = packing_sheet.nama_dealer;
                    app.claim_dealer.alamat = packing_sheet.alamat;
                    app.claim_dealer.id_packing_sheet = packing_sheet.id_packing_sheet;
                    app.claim_dealer.tgl_packing_sheet = packing_sheet.tgl_packing_sheet;
                    app.claim_dealer.tgl_faktur = packing_sheet.tgl_faktur;
                    app.claim_dealer.no_faktur = packing_sheet.no_faktur;
                  }
                </script>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tgl Packing Sheet</label>
                  <div class="col-sm-4">                    
                    <input v-model="claim_dealer.tgl_packing_sheet" readonly type="text" class="form-control">
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tgl Faktur</label>
                  <div class="col-sm-4">                    
                    <input v-model="claim_dealer.tgl_faktur" readonly type="text" class="form-control">
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No Faktur</label>
                  <div class="col-sm-4">                    
                    <input v-model="claim_dealer.no_faktur" readonly type="text" class="form-control">
                  </div>                                
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-responsive">
                      <thead>
                        <tr>                                      
                          <th class='align-top' width='3%'>No.</th>              
                          <th class='align-top'>Part Number</th>              
                          <th class='align-top'>Nama Part</th>              
                          <th class='align-top' width="10%">Qty Packing Sheet</th>
                          <th class='align-top' width="10%">Qty Part Diclaim</th>
                          <th class='align-top' width="10%">Qty Part Dikirim ke MD</th>
                          <th class='align-top'>Kategori Claim C3</th>
                          <th class='align-top'>Keterangan</th>
                          <th class='align-top'>Keputusan</th>
                          <th class='align-top' v-if="mode != 'detail' && mode != 'terima_claim'" width="3%"></th>
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) in parts">
                          <td class="align-middle">{{ index + 1 }}</td>                       
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>            
                          <td class="align-middle">
                            <vue-numeric class="form-control" :read-only='true' separator="." :empty-value="1" v-model="part.qty_packing_sheet"/>
                          </td>      
                          <td class="align-middle">
                            <vue-numeric :read-only='mode == "detail"' class="form-control" separator="." :empty-value="1" :max='part.sisa_boleh_diclaim' v-model="part.qty_part_diclaim">
                          </td>
                          <td class="align-middle">
                            <vue-numeric :read-only='mode == "detail"' class="form-control" separator="." :empty-value="1" v-model="part.qty_part_dikirim_ke_md" :max='part.qty_part_diclaim'/>
                          </td>  
                          <td class="align-middle">
                            <input type="text" readonly class="form-control" @click.prevent='open_kategori_claim_c3(index)' v-model='part.kode_claim'>
                          </td>
                          <td class="align-middle">
                            <input readonly type="text" class="form-control" v-model="part.nama_claim">
                          </td>
                          <td class="align-middle">
                            <select :disabled='mode == "detail"' class="form-control" v-model="part.keputusan">
                              <option value="">-Pilih-</option>
                              <option value="Terima">Terima</option>
                              <option value="Tolak">Tolak</option>
                            </select>
                          </td>
                          <td v-if="mode != 'detail' && mode != 'terima_claim'" class="align-middle">
                            <button class="btn btn-flat btn-danger" v-on:click.prevent="hapus_part(index)">
                              <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                          </td>                              
                        </tr>
                        <tr v-if="parts.length < 1">
                          <td class="text-center" colspan="9">Belum ada part</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                  <div v-if="mode != 'detail'" class="col-sm-12">
                    <button type="button" class="pull-right btn btn-sm btn-flat btn-primary" data-toggle="modal" data-target="#modal-parts-claim-dealer"><i class="fa fa-plus" aria-hidden="true"></i></button>
                  </div>
                </div>                                                                                                                                
                <?php $this->load->view('modal/h3_md_parts_claim_dealer_modal'); ?>
                <script>
                  function pilih_parts_claim_dealer(part){
                    app.parts.push(part);
                  }
                </script>
                <?php $this->load->view('modal/h3_md_kategori_claim_c3_claim_dealer_modal'); ?>
                <script>
                  function pilih_kategori_claim_c3_claim_dealer(data) {
                    app.parts[app.index_part].id_kategori_claim_c3 = data.id;
                    app.parts[app.index_part].kode_claim = data.kode_claim;
                    app.parts[app.index_part].nama_claim = data.nama_claim;
                  }
                </script>
                <hr>
                <div class="form-group">                  
                  <label class="col-sm-3 control-label no-padding">Packing sheet</label>
                  <div class="col-sm-1">                    
                    <input :disabled='mode == "detail"' type="checkbox" v-model="claim_dealer.dokumen_packing_sheet" true-value="1" false-value="0">
                  </div>  
                  <label class="col-sm-3 control-label no-padding">Packing Ticket</label>
                  <div class="col-sm-1">                    
                    <input :disabled='mode == "detail"' type="checkbox" v-model="claim_dealer.dokumen_packing_ticket" true-value="1" false-value="0">
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label class="col-sm-3 control-label no-padding">Foto Bukti (Parts/Kardus/Label/dll)</label>
                  <div class="col-sm-1">                    
                    <input :disabled='mode == "detail"' type="checkbox" v-model="claim_dealer.dokumen_foto_bukti" true-value="1" false-value="0">
                  </div>  
                  <label class="col-sm-3 control-label no-padding">Shipping List</label>
                  <div class="col-sm-1">                    
                    <input :disabled='mode == "detail"' type="checkbox" v-model="claim_dealer.dokumen_shipping_list" true-value="1" false-value="0">
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label class="col-sm-3 control-label no-padding">Nomor Karton</label>
                  <div class="col-sm-1">                    
                    <input :disabled='mode == "detail"' type="checkbox" v-model="claim_dealer.dokumen_nomor_karton" true-value="1" false-value="0">
                  </div>  
                  <label class="col-sm-3 control-label no-padding">Tutup Botol</label>
                  <div class="col-sm-1">                    
                    <input :disabled='mode == "detail"' type="checkbox" v-model="claim_dealer.dokumen_tutup_botol" true-value="1" false-value="0">
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label class="col-sm-3 control-label no-padding">Label Timbangan</label>
                  <div class="col-sm-1">                    
                    <input :disabled='mode == "detail"' type="checkbox" v-model="claim_dealer.dokumen_label_timbangan" true-value="1" false-value="0">
                  </div>  
                  <label class="col-sm-3 control-label no-padding">Label Karton</label>
                  <div class="col-sm-1">                    
                    <input :disabled='mode == "detail"' type="checkbox" v-model="claim_dealer.dokumen_label_karton" true-value="1" false-value="0">
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label class="col-sm-3 control-label no-padding">Lain - lain</label>
                  <div class="col-sm-3">                    
                    <input :disabled='mode == "detail"' class="form-control form-control-sm" type="type" v-model="claim_dealer.dokumen_lain">
                  </div>                                  
                </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-6">
                  <button v-if="mode == 'insert'" :disabled='part_claim_yang_melebihi_batas.length > 0 || terdapat_part_dengan_qty_claim_nol.length > 0' class="btn btn-sm btn-flat btn-primary" @click.prevent="<?= $form ?>">Submit</button>
                  <button v-if="mode == 'edit'" :disabled='part_claim_yang_melebihi_batas.length > 0 || terdapat_part_dengan_qty_claim_nol.length > 0' class="btn btn-sm btn-flat btn-warning" @click.prevent="<?= $form ?>">Update</button>
                  <a v-if='mode == "detail" && (claim_dealer.status == "Open" || claim_dealer.status == "Rejected" || claim_dealer.status == "Recheck")' class="btn btn-sm btn-flat btn-warning" :href="'h3/<?= $isi ?>/edit?id_claim_dealer=' + claim_dealer.id_claim_dealer">Edit</a>
                </div>
                <div class="col-sm-6 text-right">
                  <button v-if='mode == "detail" && claim_dealer.status == "Open"' @click.prevent='approve' type="submit" class="btn btn-sm btn-success btn-flat">Approve</button>                  
                  <button v-if='mode == "detail" && claim_dealer.status == "Open"' data-toggle='modal' data-target='#reject_modal' type="button" class="btn btn-sm btn-danger btn-flat">Reject</button>                  
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
                  <button v-if='mode == "detail" && claim_dealer.status == "Open"' data-toggle='modal' data-target='#recheck_modal' type="button" class="btn btn-sm btn-info btn-flat">Recheck</button>                  
                  <div id="recheck_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">
                                  <span aria-hidden="true">×</span>
                                  </button>
                                  <h4 class="modal-title text-left" id="myModalLabel">Alasan Recheck</h4>
                              </div>
                              <div class="modal-body">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                    <textarea class="form-control" id="alasan_recheck"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-sm-12">
                                    <button @click.prevent='recheck' class="btn btn-flat btn-sm btn-primary" data-dismiss="modal">Submit</button>
                                  </div>
                                </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <!-- <button v-if='claim_dealer.status != "Canceled" && mode == "detail"' class="btn btn-flat btn-danger btn-sm" @click.prevent='cancel'>Cancel</button> -->
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
            <?php if($mode == 'detail' or $mode == 'edit'): ?>
            claim_dealer: <?= json_encode($claim_dealer) ?>,
            parts: <?= json_encode($parts) ?>,
            <?php else: ?>
            claim_dealer: {
              id_dealer: '',
              nama_dealer: '',
              alamat: '',
              id_packing_sheet: '',
              tgl_packing_sheet: '',
              tgl_faktur: '',
              no_faktur: '',
              dokumen_packing_sheet: 0, 
              dokumen_packing_ticket: 0,
              dokumen_foto_bukti: 0,
              dokumen_shipping_list: 0,
              dokumen_nomor_karton: 0,
              dokumen_tutup_botol: 0,
              dokumen_label_timbangan: 0,
              dokumen_label_karton: 0,
              dokumen_lain: null,
            },
            parts: [],
            <?php endif; ?>
          },
          methods: {
            <?= $form ?>: function(){

              keys = [
                'id_dealer', 'id_packing_sheet', 'dokumen_packing_sheet', 'dokumen_packing_ticket',
                'dokumen_foto_bukti', 'dokumen_shipping_list', 'dokumen_nomor_karton', 'dokumen_tutup_botol', 
                'dokumen_label_timbangan', 'dokumen_label_karton', 'dokumen_lain'
              ];

              if(this.mode == 'edit'){
                keys.push('id_claim_dealer');
              }

              post = _.pick(this.claim_dealer, keys);
              post.parts = _.map(this.parts, function(part){
                return _.pick(part, [
                  'id_part','qty_packing_sheet','qty_part_diclaim','qty_part_dikirim_ke_md',
                  'keterangan','keputusan', 'id_kategori_claim_c3'
                ]);
              });

              this.loading = true;
              axios.post('h3/h3_md_claim_dealer/<?= $form ?>', Qs.stringify(post))
              .then(function(res){
                window.location = 'h3/h3_md_claim_dealer/detail?id_claim_dealer=' + res.data.id_claim_dealer;
              })
              .catch(function(err){
                app.errors = err.response.data;
                toastr.error(err);
              })
              .then(function(){ app.loading = false; });
            },
            approve: function(){
              this.loading = true;
              post = _.pick(this.claim_dealer, ['id_claim_dealer']);
              
              axios.post('h3/h3_md_claim_dealer/approve', Qs.stringify(post))
              .then(function(res){
                window.location = 'h3/h3_md_claim_dealer/detail?id_claim_dealer=' + res.data.id_claim_dealer;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; });
            },
            reject: function(){
              this.loading = true;
              post = _.pick(this.claim_dealer, ['id_claim_dealer']);
              post.message = $('#alasan_reject').val();
              
              axios.post('h3/h3_md_claim_dealer/reject', Qs.stringify(post))
              .then(function(res){
                window.location = 'h3/h3_md_claim_dealer/detail?id_claim_dealer=' + res.data.id_claim_dealer;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; });
            },
            recheck: function(){
              this.loading = true;
              post = _.pick(this.claim_dealer, ['id_claim_dealer']);
              post.message = $('#alasan_recheck').val();
              
              axios.post('h3/h3_md_claim_dealer/recheck', Qs.stringify(post))
              .then(function(res){
                window.location = 'h3/h3_md_claim_dealer/detail?id_claim_dealer=' + res.data.id_claim_dealer;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; });
            },
            cancel: function(){
              confirmed = confirm('Apakah anda yakin ingin membatalkan transaksi ini?');

              if(!confirmed) return;

              this.loading = true;
              
              axios.get('h3/h3_md_claim_dealer/cancel', {
                params: {
                  id_claim_dealer: this.claim_dealer.id_claim_dealer
                }
              })
              .then(function(res){
                window.location = 'h3/h3_md_claim_dealer/detail?id_claim_dealer=' + res.data.id_claim_dealer;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; });
            },
            hapus_part: function(index) {
              this.parts.splice(index, 1);
            },
            open_kategori_claim_c3: function(index){
              if(this.mode == 'detail') return;

              this.index_part = index;
              $('#h3_md_kategori_claim_c3_claim_dealer_modal').modal('show');
            },
            reset_customer: function(){
              this.claim_dealer.id_dealer = null;
              this.claim_dealer.nama_dealer = null;
              this.claim_dealer.alamat = null;
              this.reset_packing_sheet();
            },
            reset_packing_sheet: function(){
              this.claim_dealer.id_packing_sheet = null;
              this.claim_dealer.tgl_packing_sheet = null;
              this.claim_dealer.tgl_faktur = null;
              this.claim_dealer.no_faktur = null;
              this.parts = [];
              datatable_parts_claim_dealer.draw();
            },
            qty_max_claim: function(part){
              return parseInt(part.qty_packing_sheet) - parseInt(part.part_sudah_diclaim);
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
          computed: {
            customer_empty: function(){
              return this.claim_dealer.id_dealer == null || this.claim_dealer.id_dealer == '';
            },
            packing_sheet_empty: function(){
              return this.claim_dealer.id_packing_sheet == null || this.claim_dealer.id_packing_sheet == '';
            },
            group_part_by_id_part: function(){
              return _.chain(this.parts)
              .filter(function(part){
                return part.qty_part_diclaim > 0;
              })
              .groupBy(function(part){
                return part.id_part;
              })
              .map(function(groupedPart, index){
                part = {};
                part.id_part = index;
                part.part_sudah_diclaim = groupedPart[0].part_sudah_diclaim
                part.qty_packing_sheet = groupedPart[0].qty_packing_sheet
                part.qty_part_diclaim = _.sumBy(groupedPart, function(row){
                  return row.qty_part_diclaim;
                });
                part.groupedPart = groupedPart;
                return part;
              })
              .value();
            },
            part_claim_yang_melebihi_batas: function(){
              return _.chain(this.group_part_by_id_part)
              .filter(function(part){
                sisa_yang_boleh_diclaim = parseInt(part.qty_packing_sheet) - parseInt(part.part_sudah_diclaim);
                return part.qty_part_diclaim > sisa_yang_boleh_diclaim;
              })
              .value();
            },
            terdapat_part_dengan_qty_claim_nol: function(){
              return _.chain(this.parts)
              .filter(function(part){
                return parseInt(part.qty_part_diclaim) == 0;
              })
              .map(function(part){
                return part.id_part;
              })
              .value();
            },
          }
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-4">
              <div id='filter_customer' class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Nama Customer</label>
                    <div class="input-group">
                      <input :value='filters.length + " Customer"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_filter_claim_dealer_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php $this->load->view('modal/h3_md_dealer_filter_claim_dealer_index'); ?>
              <script>
                filter_customer = new Vue({
                  el: '#filter_customer',
                  data: {
                    filters: []
                  },
                  watch: {
                    filters: function(){
                      claim_dealer.draw();
                    }
                  }
                });

                $("#h3_md_dealer_filter_claim_dealer_index").on('change',"input[type='checkbox']",function(e){
                  target = $(e.target);
                  id_dealer = target.attr('data-id-dealer');

                  if(target.is(':checked')){
                    filter_customer.filters.push(id_dealer);
                  }else{
                    index_dealer = _.indexOf(filter_customer.filters, id_dealer);
                    filter_customer.filters.splice(index_dealer, 1);
                  }

                  h3_md_dealer_filter_claim_dealer_index_datatable.draw();
                });
              </script>
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">No Claim Customer</label>
                    <input id='no_claim_customer_filter' type="text" class="form-control">
                  </div>
                </div>
              </div>
              <script>
                $(document).ready(function(){
                  $('#no_claim_customer_filter').on('keyup', _.debounce( function(){
                    claim_dealer.draw();
                  }, 500));
                });
              </script>
            </div>
            <div class="col-sm-4">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">No Faktur</label>
                    <input id='no_faktur_filter' type="text" class="form-control">
                  </div>
                </div>
              </div>
              <script>
                $(document).ready(function(){
                  $('#no_faktur_filter').on('keyup', _.debounce( function(){
                    claim_dealer.draw();
                  }, 500));
                });
              </script>
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">No Surat Jalan</label>
                    <input id='no_surat_jalan_filter' type="text" class="form-control">
                  </div>
                </div>
              </div>
              <script>
                $(document).ready(function(){
                  $('#no_surat_jalan_filter').on('keyup', _.debounce( function(){
                    claim_dealer.draw();
                  }, 500));
                });
              </script>
            </div>
            <div class="col-sm-4">
              <div class="row">
                <div class="form-group">
                  <label for="" class="control-label">Periode Claim</label>
                  <input id='periode_claim_filter' type="text" class="form-control" readonly>
                  <input id='periode_claim_filter_start' type="hidden" disabled>
                  <input id='periode_claim_filter_end' type="hidden" disabled>
                </div>
              </div>
              <script>
                $('#periode_claim_filter').daterangepicker({
                  opens: 'left',
                  autoUpdateInput: false,
                  locale: {
                    format: 'DD/MM/YYYY'
                  }
                }, function(start, end, label) {
                  $('#periode_claim_filter_start').val(start.format('YYYY-MM-DD'));
                  $('#periode_claim_filter_end').val(end.format('YYYY-MM-DD'));
                  claim_dealer.draw();
                }).on('apply.daterangepicker', function(ev, picker) {
                  $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                }).on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
                  $('#periode_claim_filter_start').val('');
                  $('#periode_claim_filter_end').val('');
                  claim_dealer.draw();
                });
              </script>
              <div id='filter_status' class="row">
                <div class="form-group">
                  <label for="" class="control-label">Status</label>
                  <div class="input-group">
                    <input :value='filters.length + " Status"' type="text" class="form-control" readonly>
                    <div class="input-group-btn">
                      <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_status_filter_claim_dealer_index'><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_status_filter_claim_dealer_index'); ?>
              </div>
              <script>
                filter_status = new Vue({
                  el: '#filter_status',
                  data: {
                    filters: []
                  },
                  watch: {
                    filters: function(){
                      claim_dealer.draw();
                    }
                  }
                });
              </script>
            </div>
          </div>
        </div>
        <table id="claim_dealer" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Tgl Claim Customer</th>              
              <th>No Claim Customer</th>              
              <th>Nama Customer</th>              
              <th>Alamat Customer</th>              
              <th>Tgl Faktur</th>              
              <th>No Faktur</th>              
              <th>Tgl Packing Sheet</th>              
              <th>No Packing Sheet</th>              
              <th>Status</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          claim_dealer = $('#claim_dealer').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            scrollX: true,
            ajax: {
                url: "<?= base_url('api/md/h3/claim_dealer') ?>",
                dataSrc: "data",
                type: "POST",
                data: function(d){
                  d.filter_customer = filter_customer.filters;
                  d.filter_status = filter_status.filters;
                  d.no_claim_customer_filter = $('#no_claim_customer_filter').val();
                  d.no_faktur_filter = $('#no_faktur_filter').val();
                  d.no_surat_jalan_filter = $('#no_surat_jalan_filter').val();
                  d.periode_claim_filter_start = $('#periode_claim_filter_start').val();
                  d.periode_claim_filter_end = $('#periode_claim_filter_end').val();
                }
            },
            columns: [
                { data: null, orderable: false, width: '3%' },
                { data: 'tanggal' }, 
                { data: 'id_claim_dealer' }, 
                { data: 'nama_dealer' }, 
                { data: 'alamat' }, 
                { data: 'tgl_faktur' }, 
                { data: 'no_faktur' }, 
                { data: 'tgl_packing_sheet' }, 
                { data: 'id_packing_sheet' }, 
                { data: 'status' }, 
                { data: 'action', orderable: false, width: '3%', className: 'text-center' }
            ],
          });

          claim_dealer.on('draw.dt', function() {
            var info = claim_dealer.page.info();
              claim_dealer.column(0, {
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
    <?php endif; ?>
  </section>
</div>
