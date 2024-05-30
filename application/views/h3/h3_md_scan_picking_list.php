<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/humanize-duration.js") ?>" type="text/javascript"></script>
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
      if ($mode == 'scan') {
        $form = 'save';
      }
      if ($mode == 'terima_claim') {
        $form = 'simpan_claim';
      }
      if ($mode == 'detail') {
        $form = 'create';
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
              <div v-if='!sesuai_dengan_pembagian_paket_bundling' class="alert alert-warning" role="alert">
                <strong>Perhatian!</strong> Kuantitas Supply tidak sesuai dengan pembagian paket bundling.
              </div>
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No Picking List</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="picking_list.id_picking_list">
                  </div>     
                  <label class="col-sm-2 control-label">No DO</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="picking_list.id_do_sales_order">
                  </div>                            
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tgl Picking List</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="picking_list.tanggal_picking">
                  </div>                           
                  <label class="col-sm-2 control-label">Tgl Picking List</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="picking_list.tanggal_do">
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nama Picker</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="picking_list.nama_picker">
                  </div>  
                  <label class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="picking_list.nama_dealer">
                  </div>                           
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Kategori PO</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="picking_list.kategori_po">
                  </div>
                  <label class="col-sm-2 control-label">Alamat Customer</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="picking_list.alamat">
                  </div>                             
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">EV/Non EV</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="picking_list.is_ev">
                  </div>
                  <label class=" col-sm-2 control-label">Tipe PO</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="picking_list.po_type">
                  </div>                             
                </div>
                <?php if($mode != 'detail'): ?>
                <hr>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nomor Dus</label>
                  <div class="col-sm-4">
                    <input v-model="nomor_dus" type="text" class="form-control" readonly>
                  </div>    
                  <div class="col-sm-2">
                    <select v-model='produk' class="form-control">
                      <option value="">-Pilih-</option>
                      <option value="Parts">Parts</option>
                      <option value="Ban">Ban</option>
                      <option value="Oil">Oil</option>
                    </select>
                  </div>
                  <div class="col-sm-4 no-padding">
                    <button :disabled='produk == ""' class="btn btn-flat btn-success" @click.prevent='generate_no_dus'>Generate No. DUS</button>
                    <button class="btn btn-flat btn-primary" onclick='app.set_focus_to_body()' type='button' data-toggle='modal' data-target='#h3_md_nomor_dus_scan_picking_list'>Pilih No. DUS</button>
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_nomor_dus_scan_picking_list') ?>
                <script>
                function pilih_nomor_dus_scan_picking_list(data){
                  app.nomor_dus = data.no_dus;
                  app.set_focus_to_body();
                }
                </script>
                <div v-show='nomor_dus != "" && produk != ""' class="form-group">
                  <label class="col-sm-2 control-label">Scan</label>
                  <div class="col-sm-4">
                    <div class="input-group">
                      <input readonly v-model='scan_part.id_part' type="text" class="form-control">
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle="modal" data-target="#h3_md_scan_picking_list_parts"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div> 
                </div>
                <?php $this->load->view('modal/h3_md_scan_picking_list_parts') ?>
                <script>
                function pilih_action_scan_picking_list_parts(part){
                    keys = ['id_part_int', 'id_part', 'nama_part', 'qty_do', 'qty_picking', 'qty_scan', 'qty_belum_scan', 'id_lokasi_rak','serial_number'];
                    if(app.kategori_kpb){
                      keys.push('id_tipe_kendaraan');
                    }
                    if(app.ev){
                      keys.push('is_ev');
                    }
                    app.scan_part = _.pick(part, keys);
                    app.scan_part.no_dus = app.nomor_dus;
                    app.scan_part.produk = app.produk;
                    // app.nomor_dus = '';
                }
                </script>
                <div class="form-group">
                  <div  v-if='scan_part.id_part != ""' class="col-sm-12">
                    <table id="table" class="table table-condensed table-responsive">
                      <thead>
                        <tr>                                      
                          <th width="10%">No Dus</th>              
                          <th>Produk</th>
                          <th>Kode Part</th>              
                          <th>Nama Part</th>              
                          <th v-if='kategori_kpb'>Tipe Kendaraan</th>         
                          <th>Serial Number</th>              
                          <th width="10%">Qty DO</th>              
                          <th width="10%">Qty Picking</th>              
                          <th width="10%">Qty Scan</th>              
                          <th width="5%"></th>              
                        </tr>
                      </thead>
                      <tbody>            
                        <tr> 
                          <td class="align-middle">{{ scan_part.no_dus || '-' }}</td>                       
                          <td class="align-middle">{{ scan_part.produk || '-' }}</td>                       
                          <td class="align-middle">{{ scan_part.id_part || '-' }}</td>                       
                          <td class="align-middle">{{ scan_part.nama_part || '-' }}</td>                       
                          <td v-if='kategori_kpb' class="align-middle">{{ scan_part.id_tipe_kendaraan || '-' }}</td>             
                          <td class="align-middle">{{ scan_part.serial_number || '-' }}</td>                     
                          <td class="align-middle">{{ scan_part.qty_do || '-' }}</td>                       
                          <td class="align-middle">{{ scan_part.qty_picking || '-' }}</td>                       
                          <td class="align-middle">
                            <vue-numeric class="form-control form-control-sm" v-model="scan_part.qty_scan" :max='scan_part.qty_belum_scan'/>
                          </td>         
                          <td class="align-middle">
                            <button :disabled="scan_part.qty_scan < 1" @click.prevent="add_scan_part()" type="button" class="btn btn-flat btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                          </td>              
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>
                <?php endif; ?>  
                <hr>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-condensed table-responsive">
                      <thead>
                        <tr>                                      
                          <th width='3%'>No.</th>              
                          <th>Kode Part</th>              
                          <th>Nama Part</th>            
                          <th>Serial Number</th>            
                          <th>Produk</th>              
                          <th width="10%">Qty DO</th>              
                          <th width="10%">Qty Picking List</th>              
                          <th v-if='tipe_oli_reguler' width="10%">Total Dus</th>              
                          <th width="10%">Total Scan</th>              
                          <th width="10%">Qty Scan</th>              
                          <th>No Dus</th>              
                          <th v-if="mode != 'detail'" width="3%"></th>              
                        </tr>
                      </thead>
                      <tbody>
                        <template v-for='(row, indexGroup) of grouped_scanned_parts'>
                          <tr v-for='(group, index) of row.group'>
                            <template v-if='index == 0'>
                            <td :rowspan='row.group.length'>{{ indexGroup + 1 }}</td>
                            <td :rowspan='row.group.length'>{{ row.id_part }}</td>
                            <td :rowspan='row.group.length'>{{ row.nama_part }}</td>
                            <td :rowspan='row.group.length'>{{ row.serial_number }}</td>
                            <td :rowspan='row.group.length'>{{ row.produk }}</td>
                            <td :rowspan='row.group.length'>{{ row.qty_do }}</td>
                            <td :rowspan='row.group.length'>{{ row.qty_picking }}</td>
                            <td :rowspan='row.group.length'>{{ row.qty_scan }}</td>
                            <td :rowspan='row.group.length' v-if='tipe_oli_reguler'>
                              <vue-numeric v-model='row.total_dus' precision='2' read-only></vue-numeric>
                            </td>
                            </template>
                            <td>{{ group.qty_scan }}</td>
                            <td>{{ group.no_dus }}</td>
                            <td v-if="mode != 'detail'" class="align-middle">
                              <button @click.prevent="remove_scan_parts(group.id)" type="button" class="btn btn-flat btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                            </td> 
                          </tr>
                        </template>            
                        <tr v-if="scanned_parts.length < 1">
                          <td class="text-center" colspan="7">Tidak ada data</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-sm-6" style='font-size: 15px;'>
                      <div class="row">
                        <div class="col-sm-4">
                          <span class='text-bold'>Total Dus Parts</span>
                        </div>
                        <div class="col-sm-8">
                          : <vue-numeric read-only separator='.' v-model='total_dus_parts' currency='Koli' currency-symbol-position='suffix'></vue-numeric>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-6" style='font-size: 15px;'>
                      <div class="row">
                        <div class="col-sm-4">
                          <span class='text-bold'>Total Dus Oil</span>
                        </div>
                        <div class="col-sm-8">
                          : <vue-numeric read-only separator='.' v-model='total_dus_oil' currency='Koli' currency-symbol-position='suffix'></vue-numeric>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-6" style='font-size: 15px;'>
                      <div class="row">
                        <div class="col-sm-4">
                          <span class='text-bold'>Total Dus Ban</span>
                        </div>
                        <div class="col-sm-8">
                          : <vue-numeric read-only separator='.' v-model='total_dus_ban' currency='Koli' currency-symbol-position='suffix'></vue-numeric>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>                                                                                                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-12 no-padding">
                  <!-- <a  onclick='return confirm("Apakah anda yakin ingin menyelesaikan proses scan picking list")' :href="'h3/<?= $isi ?>/selesai_scan?id_picking_list=' + picking_list.id_picking_list" class="btn btn-flat btn-sm btn-warning">Selesai Scan</a> -->
                  <button v-if="picking_list.selesai_scan == 0" :disabled='!sesuai_dengan_pembagian_paket_bundling || this.scanned_parts.length < 1' class="btn btn-flat btn-sm btn-warning" @click.prevent='open_parts_belum_scan'>Scan Selesai</button>
                </div>
              </div><!-- /.box-footer -->
              <?php $this->load->view('modal/h3_md_belum_terscan_scan_picking_list'); ?>
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <script>
      app = new Vue({
          el: '#app',
          data: {
            loading: false,
            nomor_dus: '',
            produk: '',
            using_scanner: false,
            mode: '<?= $mode ?>',
            picking_list: <?= json_encode($picking_list) ?>,
            scan_part: {
              id_part_int: null,
              id_part: '',
              nama_part: '',
              serial_number: '',
              qty_do: '',
              qty_picking: '',
              qty_scan: '',
              qty_belum_scan: '',
            },
            scanned_parts: <?= json_encode($scanned_parts) ?>,
            parts_belum_scan: [],
            paket_bundling: [],
          },
          mounted: function(){
            if(this.kategori_bundling){
              this.get_paket_bundling();
            }

            id_part = '';
            $(document).keypress(function (e) {
                if (e.keyCode == 13) {
                    if(app.nomor_dus == '') {
                      toastr.warning('Belum pilih/generate nomor dus.');
                      return;
                    }
                    
                    // app.add_part_by_scanner('082322MAK0LN9');
                    app.add_part_by_scanner(id_part);
                    id_part = '';
                } else {
                    id_part += e.key;
                }
            });
          },
          methods: {
            open_parts_belum_scan: function(){
              axios.get('h3/h3_md_scan_picking_list/parts_belum_scan', {
                params: {
                  id_picking_list: this.picking_list.id_picking_list
                }
              })
              .then(function(res){
                app.parts_belum_scan = res.data;
                if(app.parts_belum_scan.length > 0){
                  $('#h3_md_belum_terscan_scan_picking_list').modal('show');
                }else{
                  app.selesai_scan();
                }
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; });
            },
            selesai_scan: function(){
              confirmed = confirm('Apakah anda yakin ingin menyelesaikan proses scan picking list?');

              if(!confirmed) return;

              this.loading = true;
              axios.get('h3/h3_md_scan_picking_list/selesai_scan', {
                params: {
                  id_picking_list: this.picking_list.id_picking_list
                }
              })
              .then(function(res){
                data = res.data;

                if(data.redirect_url != null) window.location = data.redirect_url;
              })
              .catch(function(err){
                data = err.response.data;
                toastr.error(data.message);
                app.loading = false;
              });
            },
            set_focus_to_body: function(){
              body = document.getElementsByTagName("BODY")[0];
              body.focus();
            },
            add_scan_part: function(){
              this.loading = true;
              keys = ['id_part_int', 'id_part', 'qty_do', 'qty_picking', 'qty_scan', 'no_dus', 'id_lokasi_rak', 'produk','serial_number'];
              if(app.kategori_kpb){
                keys.push('id_tipe_kendaraan');
              }
              post = _.pick(this.scan_part, keys);
              post.id_picking_list = this.picking_list.id_picking_list;
              post.id_picking_list_int = this.picking_list.id;
              axios.post('h3/h3_md_scan_picking_list/add_scan_part', Qs.stringify(post))
              .then(function(res){
                toastr.success(res.data.message);
                app.scanned_parts.push(res.data.payload);
                app.reset_scan_part();
                app.get_paket_bundling();
                h3_md_scan_picking_list_parts_datatable.draw();
                h3_md_nomor_dus_scan_picking_list_datatable.draw();
              })
              .catch(function(err){
                toastr.error(err.response.data.message);
              })
              .then(function(){ app.loading = false; })
            },
            remove_scan_parts: function(id){
              part = _.find(this.scanned_parts, function(part){
                return part.id == id;
              });
              index = _.findIndex(this.scanned_parts, function(part){
                return part.id == id;
              });

              if(typeof part == 'undefined') return;

              confirmed = confirm('Apakah anda yakin ingin menghapus item kode part ' + part.id_part + ' dan no. karton ' + part.no_dus + '?');

              if(!confirmed) return;

              this.loading = true;
              axios.get('h3/h3_md_scan_picking_list/remove_scan_parts', {
                params: {
                  id: part.id
                }
              })
              .then(function(res){
                toastr.success(res.data.message);
                app.scanned_parts.splice(index, 1);
                app.get_paket_bundling();
                h3_md_scan_picking_list_parts_datatable.draw();
                h3_md_nomor_dus_scan_picking_list_datatable.draw();
              })
              .catch(function(err){
                toastr.error(err.response.data.message);
              })
              .then(function(){
                app.loading = false;
              });
            },
            generate_no_dus: function(){
              this.loading = true;
              axios.get('h3/h3_md_scan_picking_list/generate_no_dus', {
                params: {
                  id_picking_list: this.picking_list.id_picking_list,
                  produk: this.produk
                }
              })
              .then(function(res){
                app.nomor_dus = res.data.no_dus;
                app.set_focus_to_body();
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; })
            },
            add_part_by_scanner: function(id_part){
              this.loading = true;
              axios.get('h3/h3_md_scan_picking_list/add_part_by_scanner', {
                params: {
                  id_part: id_part,
                  id_picking_list: this.picking_list.id_picking_list
                }
              })
              .then(function(res){
                if(res.data != null){
                  app.scan_part = _.pick(res.data, ['id_part', 'nama_part', 'qty_do', 'qty_picking', 'qty_scan', 'qty_belum_scan', 'id_lokasi_rak','serial_number']);
                  app.scan_part.no_dus = app.nomor_dus;
                  app.scan_part.produk = app.produk;
                  // app.nomor_dus = '';
                  // app.add_scan_part();
                }else{
                  toastr.warning('Part (' + id_part + ') yang di scan tidak terdapat di picking list ini.');
                }
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; })
            },
            reset_scan_part: function(){
              this.scan_part = {
                id_part: '',
                nama_part: '',
                serial_number: '',
                qty_do: '',
                qty_picking: '',
                qty_scan: '',
                qty_belum_scan: '',
              };
            },
            get_paket_bundling: function(id_paket_bundling, id_picking_list){
              this.loading = true;
              axios.get('h3/h3_md_scan_picking_list/get_paket_bundling', {
                params: {
                  id_paket_bundling: this.picking_list.id_paket_bundling,
                  id_picking_list: this.picking_list.id_picking_list,
                }
              })
              .then(function(res){
                app.paket_bundling = res.data;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){
                app.loading = false;
              })
            },
          },
          computed: {
            kategori_kpb: function(){
              return this.picking_list.kategori_po == 'KPB';
            }, 
            ev: function(){
              return this.picking_list.is_ev == 'EV';
            },
            kategori_bundling: function(){
              return this.picking_list.kategori_po == 'Bundling H1';
            },
            total_dus: function(){
              return _.chain(this.scanned_parts)
              .uniqBy(function(data){
                return data.no_dus;
              })
              .map(function(data){
                return data.no_dus;
              })
              .value().length;
            },
            total_dus_parts: function(){
              return _.chain(this.scanned_parts)
              .filter(function(data){
                return data.produk == 'Parts';
              })
              .uniqBy(function(data){
                return data.no_dus;
              })
              .map(function(data){
                return data.no_dus;
              })
              .value().length;
            },
            total_dus_oil: function(){
              return _.chain(this.scanned_parts)
              .filter(function(data){
                return data.produk == 'Oil';
              })
              .uniqBy(function(data){
                return data.no_dus;
              })
              .map(function(data){
                return data.no_dus;
              })
              .value().length;
            },
            total_dus_ban: function(){
              return _.chain(this.scanned_parts)
              .filter(function(data){
                return data.produk == 'Ban';
              })
              .uniqBy(function(data){
                return data.no_dus;
              })
              .map(function(data){
                return data.no_dus;
              })
              .value().length;
            },
            tipe_oli_reguler: function(){
              return this.picking_list.produk == 'Oli' && this.picking_list.kategori_po == 'Non SIM Part';
            },
            sesuai_dengan_pembagian_paket_bundling: function(){
              if(this.paket_bundling.length < 1) return [];

              kelipatan = this.paket_bundling[0].kelipatan;
              
              mempunyai_kelipatan_sama = _.every(this.paket_bundling, ['kelipatan', kelipatan]);

              return mempunyai_kelipatan_sama;
            },
            grouped_scanned_parts: function(){
              kategori_kpb = this.kategori_kpb;
              return _.chain(this.scanned_parts)
              .groupBy(function(part){
                groupFilter = part.id_part + part.id_lokasi_rak + part.serial_number;
                // if(kategori_kpb){
                //   groupFilter += part.id_tipe_kendaraan;
                // }
                return groupFilter;
              })
              .map(function(grouped, index){
                list = {
                  id_part: grouped[0].id_part,
                  nama_part: grouped[0].nama_part,
                  qty_do: grouped[0].qty_do,
                  qty_picking: grouped[0].qty_picking,
                  id_lokasi_rak: grouped[0].id_lokasi_rak,
                  serial_number: grouped[0].serial_number,
                  qty_scan: _.chain(grouped)
                    .sumBy(function(part){
                      return parseInt(part.qty_scan);
                    })
                    .value(),
                  produk: grouped[0].produk,
                  id_tipe_kendaraan: grouped[0].id_tipe_kendaraan,
                  total_dus: _.chain(grouped)
                  .uniqBy(function(row){
                    return row.no_dus;
                  })
                  .value().length,
                  group: _.chain(grouped)
                    .orderBy(['id_part', 'no_dus'], ['asc', 'asc'])
                    .map(function(part){
                      return _.pick(part, ['id', 'qty_scan', 'no_dus']);
                    })
                    .value()
                };

                if(kategori_kpb){
                  list.qty_do = _.chain(grouped)
                    .sumBy(function(row){
                      return parseInt(row.qty_do);
                    })
                    .value();

                  list.qty_picking = _.chain(grouped)
                    .sumBy(function(row){
                      return parseInt(row.qty_picking);
                    })
                    .value();
                }

                return list;
              })
              .value();
            },
          },
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header">
        <?php if($this->input->get('history') != null): ?>
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
          </a>  
          <?php else: ?>
          <a href="h3/<?= $isi ?>?history=true">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
          </a> 
        <?php endif; ?>
      </div>
      <div class="box-body">
        <table id="scan_picking_list" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>Tgl Picking List</th>              
              <th>No Picking List</th>              
              <th>Tgl DO</th>              
              <th>No. DO</th>              
              <th>Tgl SO</th>              
              <th>No. SO</th>   
              <th>Tipe PO</th>              
              <th>Kategori PO</th>              
              <th>Kode Customer</th>              
              <th>Nama Customer</th>              
              <th>Kabupaten</th>              
              <th>Jumlah Pack Parts</th>              
              <th>Jumlah Pack Tire</th>              
              <th>Jumlah Pack Oli</th>              
              <th>Total Pack</th>              
              <th>Start Scan</th>              
              <th>End Scan</th>              
              <th>Durasi Scan</th>              
              <th>Status</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
          $(document).ready(function() {
            scan_picking_list = $('#scan_picking_list').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                scrollX: true,
                ajax: {
                  url: "<?= base_url('api/md/h3/scan_picking_list') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  }
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: null, orderable: false, width: '3%' },
                    { data: 'tanggal_picking', width: '70px' },
                    { data: 'id_picking_list', width: '200px' },
                    { data: 'tanggal_do', width: '70px' },
                    { data: 'id_do_sales_order', width: '200px' },
                    { data: 'tanggal_so', width: '70px' },
                    { data: 'id_sales_order', width: '200px' },
                    { data: 'po_type' },
                    { data: 'kategori_po' },
                    { data: 'kode_dealer_md' },
                    { data: 'nama_dealer', width: '200px' },
                    { data: 'kabupaten' },
                    { data: 'jumlah_pack_parts' },
                    { data: 'jumlah_pack_tire' },
                    { data: 'jumlah_pack_oil' },
                    { data: 'jumlah_pack_all' },
                    { data: 'start_scan', width: '100px' },
                    { data: 'end_scan', width: '100px' },
                    { 
                      data: 'durasi_scan',
                      render: function(data){
                        if(data != '-'){
                          return humanizeDuration(data, { language: "id", round: true });
                        }
                        return data;
                      }
                    },
                    { data: 'status' },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });

            scan_picking_list.on('draw.dt', function() {
              var info = scan_picking_list.page.info();
              scan_picking_list.column(0, {
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
