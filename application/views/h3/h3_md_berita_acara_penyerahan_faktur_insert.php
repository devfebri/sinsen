<div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Wilayah Penagihan</label>
    <div v-bind:class="{ 'has-error': error_exist('id_wilayah_penagihan') }" class="col-sm-4">
        <input disabled type="text" class="form-control" v-model="berita_acara_penyerahan_faktur.nama_wilayah_penagihan" />
        <small v-if="error_exist('id_wilayah_penagihan')" class="form-text text-danger">{{ get_error('id_wilayah_penagihan') }}</small>
    </div>
    <div v-if='mode != "detail" ' class="col-sm-1 no-padding">
        <button class="btn btn-flat btn-primary" type="button" data-toggle="modal" data-target="#h3_md_wilayah_penagihan_berita_acara_penyerahan_faktur"><i class="fa fa-search"></i></button>
    </div>
</div>
<?php $this->load->view('modal/h3_md_wilayah_penagihan_berita_acara_penyerahan_faktur'); ?>
<script>
    function pilih_wilayah_penagihan_berita_acara_penyerahan_faktur(data) {
        form_.berita_acara_penyerahan_faktur.id_wilayah_penagihan = data.id;
        form_.berita_acara_penyerahan_faktur.nama_wilayah_penagihan = data.nama;
    }
</script>
<div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Periode Jatuh Tempo</label>
    <div v-bind:class="{ 'has-error': error_exist('start_date') }" class="col-sm-4">
        <date-picker v-model='berita_acara_penyerahan_faktur.end_date' class='form-control' readonly></date-picker>
        <small v-if="error_exist('start_date')" class="form-text text-danger">{{ get_error('start_date') }}</small>
    </div>
    <div v-if='mode != "detail"' class="col-sm-1 no-padding">
        <button class="btn btn-flat btn-success" @click.prevent="proses_faktur">Proses</button>
    </div>
</div>
<div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Debt Collector</label>
    <div v-bind:class="{ 'has-error': error_exist('id_debt_collector') }" class="col-sm-4">
        <input disabled type="text" class="form-control" v-model="berita_acara_penyerahan_faktur.nama_debt_collector" />
        <small v-if="error_exist('id_debt_collector')" class="form-text text-danger">{{ get_error('id_debt_collector') }}</small>
    </div>
    <div v-if='mode != "detail" ' class="col-sm-1 no-padding">
        <button class="btn btn-flat btn-primary" type="button" data-toggle="modal" data-target="#h3_md_debt_collector_berita_acara_penyerahan_faktur"><i class="fa fa-search"></i></button>
    </div>
</div>
<?php $this->load->view('modal/h3_md_debt_collector_berita_acara_penyerahan_faktur'); ?>
<script>
    function pilih_debt_collector_berita_acara_penyerahan_faktur(data) {
        form_.berita_acara_penyerahan_faktur.id_debt_collector = data.id_karyawan;
        form_.berita_acara_penyerahan_faktur.nama_debt_collector = data.nama_lengkap;
    }
</script>
<div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Diketahui Oleh</label>
    <div v-bind:class="{ 'has-error': error_exist('id_diketahui') }" class="col-sm-4">
        <input disabled type="text" class="form-control" v-model="berita_acara_penyerahan_faktur.nama_diketahui" />
        <small v-if="error_exist('id_diketahui')" class="form-text text-danger">{{ get_error('id_diketahui') }}</small>
    </div>
    <div v-if='mode != "detail" ' class="col-sm-1 no-padding">
        <button class="btn btn-flat btn-primary" type="button" data-toggle="modal" data-target="#h3_md_diketahui_berita_acara_penyerahan_faktur"><i class="fa fa-search"></i></button>
    </div>
</div>
<?php $this->load->view('modal/h3_md_diketahui_berita_acara_penyerahan_faktur'); ?>
<script>
    function pilih_diketahui_berita_acara_penyerahan_faktur(data) {
        form_.berita_acara_penyerahan_faktur.id_diketahui = data.id_karyawan;
        form_.berita_acara_penyerahan_faktur.nama_diketahui = data.nama_lengkap;
    }
</script>
<div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Yang Menerima</label>
    <div v-bind:class="{ 'has-error': error_exist('id_yang_menerima') }" class="col-sm-4">
        <input disabled type="text" class="form-control" v-model="berita_acara_penyerahan_faktur.nama_yang_menerima" />
        <small v-if="error_exist('id_yang_menerima')" class="form-text text-danger">{{ get_error('id_yang_menerima') }}</small>
    </div>
    <div v-if='mode != "detail" ' class="col-sm-1 no-padding">
        <button class="btn btn-flat btn-primary" type="button" data-toggle="modal" data-target="#h3_md_yang_menerima_tanda_terima_faktur"><i class="fa fa-search"></i></button>
    </div>
</div>
<?php $this->load->view('modal/h3_md_yang_menerima_tanda_terima_faktur'); ?>
<script>
    function pilih_yang_menerima_tanda_terima_faktur(data) {
        form_.berita_acara_penyerahan_faktur.id_yang_menerima = data.id_karyawan;
        form_.berita_acara_penyerahan_faktur.nama_yang_menerima = data.nama_lengkap;
    }
</script>
<div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Yang Menagih</label>
    <div class="col-sm-4">
        <input disabled type="text" class="form-control" v-model="berita_acara_penyerahan_faktur.nama_debt_collector" />
    </div>
</div>
<div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Yang Menyerahkan</label>
    <div class="col-sm-4">
        <input disabled type="text" class="form-control" v-model="berita_acara_penyerahan_faktur.nama_yang_menyerahkan" />
    </div>
</div>
<div class="continer-fluid">
    <div class="row">
        <div class="col-sm-12">
            <table v-if="items.length > 0" class="table table-condensed">
                <tr>
                    <td width="3%">No.</td>
                    <td width="20%">Nama Customer</td>
                    <td>No. Invoice</td>
                    <td width="10%">Tanggal Jatuh Tempo</td>
                    <td width="15%" class="text-right">Amount</td>
                    <td>Keterangan</td>
                    <td v-if='mode != "detail"' width="3%">Aksi</td>
                </tr>
                <tr v-for="(item, index) of items">
                    <td>{{ index + 1 }}.</td>
                    <td>{{ print_nama_customer(item) }}</td>
                    <td>{{ item.no_faktur }}</td>
                    <td>{{ item.tgl_jatuh_tempo }}</td>
                    <td class="text-right">
                        <vue-numeric v-model="item.total" read-only separator="." currency="Rp"></vue-numeric>
                    </td>
                    <td>
                        <textarea :disabled='mode == "detail"' cols="30" rows="2" v-model="item.keterangan" class="form-control"></textarea>
                    </td>
                    <td v-if='mode != "detail"'>
                        <input type="checkbox" true-value="1" false-value="0" v-model="item.checked" />
                    </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="text-right">
                        <vue-numeric v-model="total" read-only separator="." currency="Rp"></vue-numeric>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
