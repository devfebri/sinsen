<div id="form_" class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">
            <a href="h3/<?= $isi ?>">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
            </a>
        </h3>
    </div>
    <!-- /.box-header -->
    <div v-if="loading" class="overlay">
        <i class="text-light-blue fa fa-refresh fa-spin"></i>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" action="dealer/<?= $isi ?>/simpan_penambahan" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Ekspedisi</label>
                            <div v-bind:class="{ 'has-error': error_exist('id_vendor') }" class="col-sm-4">
                                <input :readonly='mode == "detail"' v-model="ongkos_angkut_part.nama_vendor" type="text" class="form-control" readonly />
                                <small v-if="error_exist('id_vendor')" class="form-text text-danger">{{ get_error('id_vendor') }}</small>
                            </div>
                            <div class="col-sm-1 no-padding">
                                <button v-if='mode != "detail"' class="btn btn-flat btn-primary" type="button" data-toggle="modal" data-target="#h3_md_ekspedisi_ongkos_angkut_part"><i class="fa fa-search"></i></button>
                            </div>
                            <?php $this->load->view('modal/h3_md_ekspedisi_ongkos_angkut_part') ?>
                            <script>
                                function pilih_ekspedisi_ongkos_angkut_part(data) {
                                    form_.ongkos_angkut_part.id_vendor = data.id;
                                    form_.ongkos_angkut_part.nama_vendor = data.nama_ekspedisi;
                                }
                            </script>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kategori</label>
                            <div v-bind:class="{ 'has-error': error_exist('kategori') }" class="col-sm-4">
                                <select v-model='ongkos_angkut_part.kategori' class="form-control">
                                  <option value="">-Pilih-</option>
                                  <option value="Parts">Parts</option>
                                  <option value="Oil">Oil</option>
                                </select>
                                <small v-if="error_exist('kategori')" class="form-text text-danger">{{ get_error('kategori') }}</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Jenis</label>
                            <div v-bind:class="{ 'has-error': error_exist('jenis') }" class="col-sm-4">
                                <select v-model='ongkos_angkut_part.jenis' class="form-control">
                                  <option value="">-Pilih-</option>
                                  <option value="Berat">Berat</option>
                                  <option value="Volume">Volume</option>
                                  <option value="Truk">Truk</option>
                                  <option value="Udara">Udara</option>
                                </select>
                                <small v-if="error_exist('jenis')" class="form-text text-danger">{{ get_error('jenis') }}</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Type Mobil</label>
                            <div v-bind:class="{ 'has-error': error_exist('type_mobil') }" class="col-sm-4">
                                <input readonly type="text" class="form-control" v-model='ongkos_angkut_part.type_mobil'>
                                <small v-if="error_exist('type_mobil')" class="form-text text-danger">{{ get_error('type_mobil') }}</small>
                            </div>
                            <div class="col-sm-1 no-padding">
                                <button v-if='mode != "detail"' class="btn btn-flat btn-primary" type="button" data-toggle="modal" data-target="#h3_md_ekspedisi_item_ongkos_angkut_part"><i class="fa fa-search"></i></button>
                            </div>
                            <?php $this->load->view('modal/h3_md_ekspedisi_item_ongkos_angkut_part') ?>
                            <script>
                                function pilih_ekspedisi_item_ongkos_angkut_part(data) {
                                    form_.ongkos_angkut_part.type_mobil = data.type_mobil;
                                    form_.ongkos_angkut_part.kapasitas = data.kapasitas;
                                    form_.ongkos_angkut_part.kategori = data.produk_angkatan;
                                }
                            </script>
                            <label for="inputEmail3" class="col-sm-2 control-label">Kapasitas</label>
                            <div v-bind:class="{ 'has-error': error_exist('kapasitas') }" class="col-sm-3">
                                <vue-numeric disabled class="form-control" separator='.' currency='Ton' v-model='ongkos_angkut_part.kapasitas' currency-symbol-position='suffix'></vue-numeric>
                                <small v-if="error_exist('kapasitas')" class="form-text text-danger">{{ get_error('kapasitas') }}</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Per Satuan</label>
                            <div v-bind:class="{ 'has-error': error_exist('per_satuan') }" class="col-sm-4">
                                <vue-numeric class="form-control" v-model='ongkos_angkut_part.per_satuan' separator='.' precision='2' decimal-separator='.' currency-symbol-position='suffix' :currency='get_currency'></vue-numeric>
                                <small v-if="error_exist('per_satuan')" class="form-text text-danger">{{ get_error('per_satuan') }}</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Harga Sebelumnya</label>
                            <div class="col-sm-4">
                                <vue-numeric readonly class="form-control" v-model='ongkos_angkut_part.harga_sebelumnya' separator='.' currency='Rp'></vue-numeric>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Harga Baru</label>
                            <div v-bind:class="{ 'has-error': error_exist('harga') }" class="col-sm-4">
                                <vue-numeric class="form-control" v-model='ongkos_angkut_part.harga' separator='.' currency='Rp'></vue-numeric>
                                <small v-if="error_exist('harga')" class="form-text text-danger">{{ get_error('harga') }}</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Dimulai Tanggal</label>
                            <div v-bind:class="{ 'has-error': error_exist('start_date') }" class="col-sm-4">
                                <input id='dimulai_tanggal_picker' type="text" class="form-control" readonly/>
                                <small v-if="error_exist('start_date')" class="form-text text-danger">{{ get_error('start_date') }}</small>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="col-sm-6 no-padding">
                                <button class="btn btn-flat btn-sm btn-primary" type="button" @click.prevent="simpan_penambahan">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    var form_ = new Vue({
        el: "#form_",
        data: {
            kosong: "",
            mode: "<?= $mode ?>",
            index_part: 0,
            loading: false,
            errors: {},
            ongkos_angkut_part: {
                id_vendor: "",
                nama_vendor: "",
                kategori: "",
                jenis: "",
                type_mobil: "",
                kapasitas: "",
                per_satuan: "",
                kategori: "",
                harga: 0,
                harga_sebelumnya: "",
                start_date: "",
            },
        },
        methods: {
            simpan_penambahan: function () {
                this.errors = {};
                this.loading = true;

                post = _.pick(this.ongkos_angkut_part, [
                  'id_vendor', 'type_mobil', 'kapasitas', 'per_satuan', 'harga', 'start_date', 'kategori', 'jenis'
                ])
                axios.post("h3/<?= $isi ?>/simpan_penambahan", Qs.stringify(post))
                .then(function (res) {
                    window.location = "h3/<?= $isi ?>/detail?id_vendor=" + res.data.id_vendor;
                })
                .catch(function (err) {
                    data = err.response.data;
                    if (data.error_type == "validation_error") {
                        form_.errors = data.errors;
                        toastr.error(data.message);
                    }
                })
                .then(function () {
                    form_.loading = false;
                });
            },
            ambil_harga_sebelumnya: function(){
                if(this.ongkos_angkut_part.jenis == '' || this.ongkos_angkut_part.id_vendor == '' || this.ongkos_angkut_part.type_mobil == '') return;

                this.loading = true;
                axios.get('h3/h3_md_ms_ongkos_angkut_part/ambil_harga_sebelumnya', {
                    params: {
                        id_vendor: this.ongkos_angkut_part.id_vendor,
                        jenis: this.ongkos_angkut_part.jenis,
                        type_mobil: this.ongkos_angkut_part.type_mobil,
                    }
                })
                .then(function(res){
                    form_.ongkos_angkut_part.harga_sebelumnya = res.data;
                })
                .catch(function(err){
                    toastr.error(err);
                })
                .then(function(){ form_.loading = false; })
            },
            open_periode: function (index) {
                this.index_periode = index;
            },
            get_currency: function(){
                if(this.ongkos_angkut_part.jenis == 'Truk'){
                    return 'Truk';
                }else if(this.ongkos_angkut_part.jenis == 'Volume'){
                    return 'M3';
                }else if(this.ongkos_angkut_part.jenis == 'Berat'){
                    return 'Kg';
                }else if(this.ongkos_angkut_part.jenis == 'Udara'){
                    return 'Kg';
                }
                return '';
            },
            error_exist: function (key) {
                return _.get(this.errors, key) != null;
            },
            get_error: function (key) {
                return _.get(this.errors, key);
            },
        },
        watch: {
            'ongkos_angkut_part.id_vendor': function(n, o){
                this.ambil_harga_sebelumnya();
                h3_md_ekspedisi_item_ongkos_angkut_part_datatable.draw();
            },
            'ongkos_angkut_part.jenis': function(n, o){
                this.ambil_harga_sebelumnya();
            },
            'ongkos_angkut_part.type_mobil': function(n, o){
                this.ambil_harga_sebelumnya();
            }
        },
        mounted: function(){
          $(document).ready(function(){
            $('#dimulai_tanggal_picker').datepicker({
              format: 'dd/mm/yyyy'
            })
            .on('changeDate', function(e){
              form_.ongkos_angkut_part.start_date = e.format('yyyy-mm-dd');
            });
          });
        }
    });
</script>
