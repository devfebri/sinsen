<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
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
            <form class="form-horizontal">
              <div class="box-body">
                <div v-if='parts_dengan_qty_avs_tidak_terpenuhi.length > 0' class="alert alert-warning" role="alert">
                  <strong>Perhatian!</strong>
                  <ol>
                    <li v-for='part in parts_dengan_qty_avs_tidak_terpenuhi'>Kuantitas claim pada kode part {{ part.id_part }} sebesar {{ part.qty_part_diclaim }} tidak mencukupi kuantitas AVS yang hanya sebesar {{ part.qty_avs }} 
                    </li>
                  </ol>
                </div>
                <div v-if='mode != "insert"' class="form-group">                  
                  <label class="col-sm-2 control-label">No. Claim</label>
                  <div class="col-sm-4">                    
                    <input v-model="claim_main_dealer.id_claim" readonly type="text" class="form-control">
                  </div>
                  <label class="col-sm-2 control-label">Tanggal Claim</label>
                  <div class="col-sm-4">                    
                    <input v-model="claim_main_dealer.created_at" readonly type="text" class="form-control">
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No. Packing Sheet</label>
                  <div v-bind:class="{ 'has-error': error_exist('packing_sheet_number') }" class="col-sm-4">                    
                    <div class="input-group"> 
                      <input v-model="claim_main_dealer.packing_sheet_number" readonly type="text" class="form-control">
                      <div class="input-group-btn">
                        <button :disabled='mode == "detail"' v-if='packing_sheet_empty || mode == "detail"' class="btn-flat btn-primary btn" type='button' data-toggle="modal" data-target="#h3_md_packing_sheet_claim_main_dealer_ke_ahm"><i class="fa fa-search"></i></button>
                        <button v-if='!packing_sheet_empty && mode != "detail"' class="btn-flat btn-danger btn" @click.prevent='reset_packing_sheet'><i class="fa fa-trash-o"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('packing_sheet_number')" class="form-text text-danger">{{ get_error('packing_sheet_number') }}</small>                                
                  </div>                    
                  <?php $this->load->view('modal/h3_md_packing_sheet_claim_main_dealer_ke_ahm.php'); ?>
                  <script>
                    function pilih_packing_sheet_main_dealer_ke_ahm(data){
                      app.claim_main_dealer.packing_sheet_number_int = data.id;
                      app.claim_main_dealer.packing_sheet_number = data.packing_sheet_number;
                      app.claim_main_dealer.invoice_number = data.invoice_number;
                      app.claim_main_dealer.invoice_number_int = data.invoice_number_int;
                    }
                  </script>
                  <label class="col-sm-2 control-label">No. Faktur AHM</label>
                  <div class="col-sm-4">                    
                    <input v-model="claim_main_dealer.invoice_number" readonly type="text" class="form-control">
                  </div> 
                </div>
                <div v-if='mode != "insert"' class="form-group">                  
                  <label class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">                    
                    <input v-model="claim_main_dealer.status" readonly type="text" class="form-control">
                  </div>
                </div>
                <div class="container-fluid bg-primary" style='padding: 8px; margin-bottom: 10px;'>
                  <div class="row">
                    <div class="col-sm-12 text-center">
                      <span class='text-bold'>Dokumen Pendukung yang Wajib Disertakan</span>
                    </div>
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-3 control-label no-padding">Packing sheet</label>
                  <div class="col-sm-1">                    
                    <input :disabled='mode == "detail"' type="checkbox" v-model="claim_main_dealer.packing_sheet" true-value="1" false-value="0">
                  </div>  
                  <label class="col-sm-3 control-label no-padding">Packing Ticket</label>
                  <div class="col-sm-1">                    
                    <input :disabled='mode == "detail"' type="checkbox" v-model="claim_main_dealer.packing_ticket" true-value="1" false-value="0">
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label class="col-sm-3 control-label no-padding">Foto Bukti (Parts/Kardus/Label/dll)</label>
                  <div class="col-sm-1">                    
                    <input :disabled='mode == "detail"' type="checkbox" v-model="claim_main_dealer.foto_bukti" true-value="1" false-value="0">
                  </div>  
                  <label class="col-sm-3 control-label no-padding">Shipping List</label>
                  <div class="col-sm-1">                    
                    <input :disabled='mode == "detail"' type="checkbox" v-model="claim_main_dealer.shipping_list" true-value="1" false-value="0">
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label class="col-sm-3 control-label no-padding">Nomor Karton</label>
                  <div class="col-sm-1">                    
                    <input :disabled='mode == "detail"' type="checkbox" v-model="claim_main_dealer.nomor_karton" true-value="1" false-value="0">
                  </div>  
                  <label class="col-sm-3 control-label no-padding">Tutup Botol</label>
                  <div class="col-sm-1">                    
                    <input :disabled='mode == "detail"' type="checkbox" v-model="claim_main_dealer.tutup_botol" true-value="1" false-value="0">
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label class="col-sm-3 control-label no-padding">Label Timbangan</label>
                  <div class="col-sm-1">                    
                    <input :disabled='mode == "detail"' type="checkbox" v-model="claim_main_dealer.label_timbangan" true-value="1" false-value="0">
                  </div>  
                  <label class="col-sm-3 control-label no-padding">Label Karton</label>
                  <div class="col-sm-1">                    
                    <input :disabled='mode == "detail"' type="checkbox" v-model="claim_main_dealer.label_karton" true-value="1" false-value="0">
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label class="col-sm-3 control-label">Lain - lain</label>
                  <div class="col-sm-3">                    
                    <input :disabled='mode == "detail"' class="form-control form-control-sm" type="type" v-model="claim_main_dealer.lain_lain">
                  </div>                                  
                </div>
                <div class="container-fluid bg-primary" style='padding: 8px; margin-bottom: 10px;'>
                  <div class="row">
                    <div class="col-sm-12 text-center">
                      <span class='text-bold'>Part Claim</span>
                    </div>
                  </div>
                </div>
                <div v-if='parts_yang_melebihi_qty_boleh_diclaim.length > 0' class="alert alert-warning" role="alert">
                  <strong>Perhatian!</strong> Terdapat part yang melebihi kuantitas yang boleh diclaim.
                  <ol>
                    <li v-for='part in parts_yang_melebihi_qty_boleh_diclaim'>Kode part {{ part.id_part }} dengan nomor karton {{ part.no_doos }} hanya dibolehkan claim dengan kuantitas {{ part.qty_part_yang_boleh_claim }}
                      <ul>
                        <li v-for='group in part.grouped'>Kode part {{ group.id_part }} dengan nomor karton {{ group.no_doos }} pada nomor urut {{ group.no_urut }} dengan kuantitas claim {{ group.qty_part_diclaim }}</li>
                      </ul>
                    </li>
                  </ol>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-responsive">
                      <thead>
                        <tr>                                      
                          <th class='align-top' width='3%'>No.</th>              
                          <th class='align-top'>No. Karton</th>              
                          <th class='align-top' width='10%'>No. Part</th>              
                          <th class='align-top' width='15%'>Part Deskripsi</th>              
                          <th class='align-top' width="10%">Qty Part PS</th>
                          <th class='align-top' width="10%">Qty Part Diclaim</th>
                          <th class='align-top' width="10%">Qty Part Dikirim ke AHM</th>
                          <th class='align-top'>Kode Claim</th>
                          <th class='align-top'>Rak Lokasi</th>
                          <th class='align-top'>Qty Available</th>
                          <th class='align-top'>Keterangan</th>
                          <th class='align-top' v-if="mode != 'detail'" width="3%"></th>
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) in parts">
                          <td class="align-middle">{{ index + 1 }}.</td>                       
                          <td class="align-middle">{{ part.no_doos }}</td>                       
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>            
                          <td class="align-middle">
                            <vue-numeric class="form-control" :read-only='true' separator="." :empty-value="1" v-model="part.packing_sheet_quantity"/>
                          </td>      
                          <td class="align-middle">
                            <vue-numeric :read-only='mode == "detail"' class="form-control" separator="." :empty-value="1" v-model="part.qty_part_diclaim" v-on:keyup.native="qty_part_diclaim_handler">
                          </td>  
                          <td class="align-middle">
                            <vue-numeric :read-only='mode == "detail"' class="form-control" separator="." :empty-value="1" v-model="part.qty_part_dikirim_ke_ahm" :max='part.qty_part_diclaim'/>
                          </td>  
                          <td class="align-middle">
                            <input type="text" readonly class="form-control" v-model='part.nama_claim' @click.prevent='open_kode_claim_datatable(index)'>
                          </td>
                          <td>
                            <input readonly type="text" class="form-control" v-model='part.lokasi' @click.prevent='open_lokasi_rak_datatable(index)'>
                          </td>
                          <td class='align-middle'>
                            <vue-numeric read-only class="form-control" separator="." v-model="part.qty_avs"/>
                          </td>
                          <td class="align-middle">
                            <input :disabled='mode == "detail"' type="text" class="form-control" v-model="part.keterangan">
                          </td>
                          <td v-if="mode != 'detail'" class="align-middle">
                            <button class="btn btn-flat btn-danger" v-on:click.prevent="hapus_part(index)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                          </td>                              
                        </tr>
                        <tr v-if="parts.length < 1">
                          <td class="text-center" colspan="12">Belum ada part</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                  <div v-if="mode != 'detail'" class="col-sm-12">
                    <button type="button" class="pull-right btn btn-flat btn-primary" data-toggle="modal" data-target="#h3_md_parts_claim_main_dealer_ke_ahm"><i class="fa fa-plus" aria-hidden="true"></i></button>
                  </div>
                </div>                                                                                                                                
                <?php $this->load->view('modal/h3_md_parts_claim_main_dealer_ke_ahm'); ?>
                <script>
                  function pilih_parts_claim_main_dealer_ke_ahm(data){
                    app.parts.push(data);
                    h3_md_parts_claim_main_dealer_ke_ahm_datatable.draw();
                    h3_md_packing_sheet_claim_main_dealer_ke_ahm_datatable.draw();
                  }
                </script>
                <?php $this->load->view('modal/h3_md_kode_claim_claim_main_dealer_ke_ahm'); ?>
                <script>
                  function pilih_kode_claim_claim_main_dealer_ke_ahm(data){
                    app.parts[app.index_part].nama_claim = data.nama_claim;
                    app.parts[app.index_part].id_kode_claim = data.id;
                  }
                </script>
                <?php $this->load->view('modal/h3_md_lokasi_claim_main_dealer_ke_ahm'); ?>
                <script>
                  function pilih_lokasi_rak_claim_main_dealer_ke_ahm(data){
                    app.parts[app.index_part].id_lokasi_rak = data.id;
                    app.parts[app.index_part].lokasi = data.kode_lokasi_rak;
                  }
                </script>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-6 no-padding">
                  <button v-if="mode == 'insert'" :disabled='parts_yang_melebihi_qty_boleh_diclaim.length > 0 || parts_dengan_qty_avs_tidak_terpenuhi.length > 0' class="btn btn-sm btn-flat btn-primary" @click.prevent="<?= $form ?>">Submit</button>
                  <button v-if="mode == 'edit'" :disabled='parts_yang_melebihi_qty_boleh_diclaim.length > 0 || parts_dengan_qty_avs_tidak_terpenuhi.length > 0' class="btn btn-sm btn-flat btn-warning" @click.prevent="<?= $form ?>">Update</button>
                  <a v-if='mode == "detail" && claim_main_dealer.status == "Open"' class="btn btn-sm btn-flat btn-warning" :href="'h3/<?= $isi ?>/edit?id_claim=' + claim_main_dealer.id_claim">Edit</a>
                </div>
                <div class="col-sm-6 no-padding text-right">
                  <button v-if='mode == "detail" && claim_main_dealer.status == "Open"' :disabled='parts_dengan_qty_avs_tidak_terpenuhi.length > 0' @click.prevent='proses' type="submit" class="btn btn-sm btn-info btn-flat">Proses</button>                  
                  <button v-if='mode == "detail" && claim_main_dealer.status == "Open"' :disabled='parts_dengan_qty_avs_tidak_terpenuhi.length > 0' @click.prevent='cancel' class="btn btn-sm btn-danger btn-flat">Cancel</button>                  
                  <a v-if='mode == "detail" && claim_main_dealer.status == "Processed"' class="btn btn-sm btn-flat btn-info" :href="'h3/<?= $isi ?>/cetak?id_claim=' + claim_main_dealer.id_claim">Cetak</a>
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
            <?php if($mode == 'detail' or $mode == 'edit'): ?>
            claim_main_dealer: <?= json_encode($claim_main_dealer) ?>,
            parts: <?= json_encode($parts) ?>,
            <?php else: ?>
            claim_main_dealer: {
              packing_sheet_number_int: '',
              packing_sheet_number: '',
              invoice_number: '',
              packing_sheet: 0,
              packing_ticket: 0,
              foto_bukti: 0,
              shipping_list: 0,
              nomor_karton: 0,
              tutup_botol: 0,
              label_timbangan: 0,
              label_karton: 0,
              lain_lain: '',
            },
            parts: [],
            <?php endif; ?>
            index_part: 0,
          },
          methods: {
            <?= $form ?>: function(){
              post = _.pick(this.claim_main_dealer, [
                'id_claim', 'packing_sheet_number', 'packing_sheet_number_int', 'invoice_number', 'invoice_number_int', 'packing_sheet', 'packing_ticket',
                'foto_bukti', 'shipping_list', 'nomor_karton', 'tutup_botol', 
                'label_timbangan', 'label_karton', 'lain_lain'
              ]);

              post.parts = _.map(this.parts, function(part){
                return _.pick(part, [
                  'id_part_int', 'id_part', 'no_doos', 'qty_part_diclaim','qty_part_dikirim_ke_ahm',
                  'id_kode_claim','id_lokasi_rak', 'keterangan', 'qty_avs', 'no_po', 'no_doos_int'
                ]);
              });

              this.loading = true;
              axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?id_claim=' + res.data.id_claim;
              })
              .catch(function(err){
                app.errors = err.response.data;
                toastr.error(err);
              })
              .then(function(){ app.loading = false; });
            },
            proses: function(){
              this.loading = true;
              post = _.pick(this.claim_main_dealer, ['id_claim']);
              
              axios.post('h3/<?= $isi ?>/proses', Qs.stringify(post))
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?id_claim=' + res.data.id_claim;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; });
            },
            cancel: function(){
              this.loading = true;
              post = _.pick(this.claim_main_dealer, ['id_claim']);
              
              axios.post('h3/<?= $isi ?>/cancel', Qs.stringify(post))
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?id_claim=' + res.data.id_claim;
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
            hapus_part: function(index) {
              this.parts.splice(index, 1);
              h3_md_parts_claim_main_dealer_ke_ahm_datatable.draw();
              h3_md_packing_sheet_claim_main_dealer_ke_ahm_datatable.draw();
            },
            open_kode_claim_datatable: function(index){
              if(this.mode == 'detail') return;
              this.index_part = index;
              $('#h3_md_kode_claim_claim_main_dealer_ke_ahm').modal('show');
              h3_md_kode_claim_claim_main_dealer_ke_ahm_datatable.draw();
            },
            open_lokasi_rak_datatable: function(index){
              if(this.mode == 'detail') return;
              this.index_part = index;
              $('#h3_md_lokasi_claim_main_dealer_ke_ahm').modal('show');
              h3_md_lokasi_claim_main_dealer_ke_ahm_datatable.draw();
            },
            reset_packing_sheet: function(){
              this.claim_main_dealer.packing_sheet_number = '';
              this.claim_main_dealer.invoice_number = '';
            },
            qty_part_diclaim_handler: _.debounce(function($event){
              h3_md_packing_sheet_claim_main_dealer_ke_ahm_datatable.draw();
            }, 500),
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            }
          },
          computed: {
            packing_sheet_empty: function(){
              return this.claim_main_dealer.packing_sheet_number == null || this.claim_main_dealer.packing_sheet_number == '';
            },
            parts_yang_melebihi_qty_boleh_diclaim: function(){
              return _.chain(this.parts)
              .map(function(part, index){
                part.no_urut = index + 1;
                return part;
              })
              .groupBy(function(part){
                return part.id_part + part.no_doos;
              })
              .map(function(grouped, index){
                return {
                  id_part: grouped[0].id_part,
                  no_doos: grouped[0].no_doos,
                  qty_part_yang_boleh_claim: grouped[0].qty_part_yang_boleh_claim,
                  qty_part_diclaim: _.sumBy(grouped, function(group){
                    return parseInt(group.qty_part_diclaim);
                  }),
                  grouped: grouped
                }
              })
              .filter(function(part){
                return parseInt(part.qty_part_diclaim) > parseInt(part.qty_part_yang_boleh_claim);
              })
              .value();
            },
            parts_dengan_qty_avs_tidak_terpenuhi: function(){
              data = _.chain(this.parts)
              .groupBy(function(part){
                return part.id_part_int;
              })
              .map(function(group, index){
                return {
                  id_part_int: group[0].id_part_int,
                  id_part: group[0].id_part,
                  qty_avs: group[0].qty_avs,
                  qty_part_diclaim: _.chain(group)
                  .sumBy(function(row){
                    return parseInt(row.qty_part_diclaim);
                  })
                  .value()
                }
              })
              .value();

              return _.chain(data)
              .filter(function(row){
                return row.qty_part_diclaim > row.qty_avs;
              })
              .value();

              return data;
            }
          },
          watch:{
            'claim_main_dealer.packing_sheet_number': function(){
              h3_md_parts_claim_main_dealer_ke_ahm_datatable.draw();
            },
          },
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
        <table id="claim_main_dealer_ke_ahm" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Tgl Claim MD ke AHM</th>              
              <th>No Claim MD ke AHM</th>              
              <th>Packing Sheet Number</th>              
              <th>Invoice Number</th>              
              <th>Status</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          claim_main_dealer_ke_ahm = $('#claim_main_dealer_ke_ahm').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/claim_main_dealer_ke_ahm') ?>",
                dataSrc: "data",
                type: "POST"
            },
            columns: [
                { data: 'index', orderable: false, width: '3%' },
                { data: 'created_at' }, 
                { data: 'id_claim' }, 
                { data: 'packing_sheet_number' }, 
                { 
                  data: 'invoice_number',
                  render: function(data){
                    if(data != null && data != '') return data;
                    return '-';
                  }
                }, 
                { data: 'status' }, 
                { data: 'action', orderable: false, width: '3%', className: 'text-center' }
            ],
          });
        });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>
