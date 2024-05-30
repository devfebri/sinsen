<!-- Modal -->
<div id="h3_md_modal_view_picking_list" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style='width: 80%;'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Picking List</h4>
            </div>
            <div class="modal-body">
                <div id="h3_md_modal_view_picking_list_vue" class="box box-default" style='border-top: none;'>
                    <div v-if="loading" class="overlay">
                        <i class="fa fa-refresh fa-spin text-light-blue"></i>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">No Picking List</label>
                                            <div class="col-sm-4">
                                                <input v-model="picking.id_picking_list" readonly type="text" class="form-control" />
                                            </div>
                                            <label class="col-sm-2 control-label">Nama Picker</label>
                                            <div class="col-sm-4">
                                                <input v-model="picking.nama_picker" readonly type="text" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Tanggal Picking List</label>
                                            <div class="col-sm-4">
                                                <input v-model="picking.tanggal_picking" readonly type="text" class="form-control" />
                                            </div>
                                            <label class="col-sm-2 control-label">Nama Customer</label>
                                            <div class="col-sm-4">
                                                <input v-model="picking.nama_dealer" readonly type="text" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Jenis PO</label>
                                            <div class="col-sm-4">
                                                <input v-model="picking.po_type" readonly type="text" class="form-control" />
                                            </div>
                                            <label class="col-sm-2 control-label">Alamat Customer</label>
                                            <div class="col-sm-4">
                                                <input v-model="picking.alamat" readonly type="text" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">No SO</label>
                                            <div class="col-sm-4">
                                                <input v-model="picking.id_sales_order" readonly type="text" class="form-control" />
                                            </div>
                                            <label class="col-sm-2 control-label">Tanggal SO</label>
                                            <div class="col-sm-4">
                                                <input v-model="picking.tanggal_so" readonly type="text" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Nomor DO</label>
                                            <div class="col-sm-4">
                                                <input v-model="picking.id_do_sales_order" readonly type="text" class="form-control" />
                                            </div>
                                            <label class="col-sm-2 control-label">Tanggal DO</label>
                                            <div class="col-sm-4">
                                                <input v-model="picking.tanggal_do" readonly type="text" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Start Pick</label>
                                            <div class="col-sm-4">
                                                <input v-model="picking.start_pick" readonly type="text" class="form-control" />
                                            </div>
                                            <label class="col-sm-2 control-label">End Pick</label>
                                            <div class="col-sm-4">
                                                <input v-model="picking.end_pick" readonly type="text" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Status</label>
                                            <div class="col-sm-4">
                                                <input v-model="picking.status" readonly type="text" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <table id="table" class="table table-condensed table-responsive">
                                                    <thead>
                                                        <tr class="bg-blue-gradient">
                                                            <th width="3%">No.</th>
                                                            <th>Kode Part</th>
                                                            <th>Nama Part</th>
                                                            <th v-if='kategori_kpb'>Tipe Kendaraan</th>
                                                            <th>Serial Number</th>
                                                            <th>Kode Lokasi</th>
                                                            <th>Qty DO</th>
                                                            <th>Qty AVS</th>
                                                            <th>Qty Picking</th>
                                                            <th>Qty Disiapkan</th>
                                                            <th v-if='picking.status == "Re-Check"' class='text-center'>Status</th>
                                                            <th v-if='picking.ready_for_scan != 1 && picking.status == "Closed PL" && !no_action' width='10%'>Recheck All <input type="checkbox" v-model='recheck_all' true-value='1' false-value='0'></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(part, index) in parts">
                                                            <td class="align-middle">{{ index + 1 }}.</td>
                                                            <td class="align-middle">{{ part.id_part }}</td>
                                                            <td class="align-middle">{{ part.nama_part }}</td>
                                                            <td v-if='kategori_kpb' class="align-middle">{{ part.id_tipe_kendaraan }}</td>
                                                            <td class="align-middle" v-if="part.serial_number != null"> <b>{{ part.serial_number }}</b></td>
                                                            <td class="align-middle" v-else>{{ part.serial_number }}</td>
                                                            <td class="align-middle">{{ part.kode_lokasi_rak }}</td>
                                                            <td class="align-middle">
                                                                <vue-numeric :read-only="true" class="form-control" separator="." v-model="part.qty_do"></vue-numeric>
                                                            </td>
                                                            <td class="align-middle">
                                                                <vue-numeric :read-only="true" class="form-control" separator="." v-model="part.qty_avs"></vue-numeric>
                                                            </td>
                                                            <td class="align-middle">
                                                                <vue-numeric :read-only="true" class="form-control" separator="." v-model="part.qty_picking"></vue-numeric>
                                                            </td>
                                                            <td class="align-middle">
                                                                <vue-numeric :read-only="true" class="form-control" separator="." v-model="part.qty_disiapkan"></vue-numeric>
                                                            </td>
                                                            <td v-if='picking.status == "Re-Check" && part.recheck == 1' class="align-middle text-center">
                                                                Re-Check
                                                            </td>
                                                            <td v-if='allow_to_show_recheck_action' class="align-middle text-center">
                                                                <input type="checkbox" v-model='part.recheck' true-value='1' false-value='0'>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                    <div class="box-footer">
                                        <div class="col-sm-12 no-padding">
                                            <button v-if='allow_to_show_recheck_action' class="btn-flat btn-sm btn-success btn" :disabled='parts_yang_akan_direcheck.length < 1' @click.prevent='recheck_picking'>Re-check</button>
                                        </div>
                                    </div><!-- /.box-footer -->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
</div>
<script>
    h3_md_modal_view_picking_list_vue = new Vue({
          el: '#h3_md_modal_view_picking_list_vue',
          data: {
            loading: false,
            no_action: false,
            picking: {
                id_picking_list: '',
                tanggal_picking: '',
                kategori_po: '',
                po_type: '',
                id_sales_order: '',
                tanggal_so: '',
                tanggal_do: '',
                nama_picker: '',
                nama_dealer: '',
                alamat: '',
                id_do_sales_order: ''
            },
            parts: [],
            recheck_all: 0,
          },
          methods: {
            get_view_picking_list_data: function(){
                this.loading = true;
                axios.get('h3/h3_md_modal_view_picking_list/get_view_picking_list_data', {
                    params: {
                        id_picking_list: this.picking.id_picking_list
                    }
                })
                .then(function(res){
                    h3_md_modal_view_picking_list_vue.picking = res.data.picking;
                    h3_md_modal_view_picking_list_vue.parts = res.data.parts;
                })
                .catch(function(err){
                    toastr.error(err);
                })
                .then(function(){ h3_md_modal_view_picking_list_vue.loading = false; });
            },
            recheck_item: function(index){
              this.loading = true;
              post = {
                id_picking_list: this.picking.id_picking_list,
                id_part: this.parts[index].id_part,
                index: index
              };
              axios.post('h3/h3_md_modal_view_picking_list/recheck_item', Qs.stringify(post))
              .then(function (response) {
                data = response.data;
                h3_md_modal_view_picking_list_vue.parts.splice(data.index, 1, data.part);
                h3_md_modal_view_picking_list_vue.get_view_picking_list_data();
              })
              .catch(function (error) {
                toastr.error(error);
              })
              .then(function(){
                h3_md_modal_view_picking_list_vue.loading = false;
              });
            },
            recheck_picking: function(){
                this.loading = true;
                post = _.pick(this.picking, ['id_picking_list']);
                post.parts = _.chain(this.parts_yang_akan_direcheck)
                .map(function(data){
                    return _.pick(data, ['id_part', 'id_lokasi_rak', 'qty_disiapkan', 'recheck'])
                })
                .value();

                axios.post('h3/h3_md_modal_view_picking_list/recheck_picking', Qs.stringify(post))
                .then(function (response) {
                    h3_md_modal_view_picking_list_vue.get_view_picking_list_data();
                })
                .catch(function (error) {
                    data = error.response.data;
                    toastr.error(data.message);
                })
                .then(function(){
                    h3_md_modal_view_picking_list_vue.loading = false;
                });
            },
          },
          watch: {
              recheck_all: function(val){
                  this.parts = _.chain(this.parts)
                  .map(function(data){
                      data.recheck = val;
                      return data;
                  })
                  .value();
              }
          },
          computed: {
              allow_to_show_recheck_action: function(){
                  return this.picking.ready_for_scan != 1 && this.picking.status == "Closed PL" && !this.no_action;
              },
              kategori_kpb: function(){
                  return this.picking.kategori_po == 'KPB';
              },
              parts_yang_akan_direcheck: function(){
                return _.chain(this.parts)
                .filter(function(data){
                    return data.recheck == 1;
                })
                .value();
              }
          }
        });
</script>