<div v-if='faktur_belum_lunas_tidak_dikembalikan.length > 0' class="alert alert-warning" role="alert">
  <strong>Perhatian!</strong> Terdapat faktur yang belum lunas tetapi tidak dikembalikan, antara lain:
  <ul>
      <li v-for='no_faktur in faktur_belum_lunas_tidak_dikembalikan'>{{ no_faktur }}</li>
  </ul>
</div>
<div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">No BAP</label>
    <div class="col-sm-4">
        <input disabled type="text" class="form-control" v-model="berita_acara_penyerahan_faktur.no_bap" />
    </div>
    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal BAP</label>
    <div class="col-sm-4">
        <input disabled type="text" class="form-control" v-model="berita_acara_penyerahan_faktur.created_at" />
    </div>
</div>
<div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Kode Wilayah Penagihan</label>
    <div v-bind:class="{ 'has-error': error_exist('id_wilayah_penagihan') }" class="col-sm-4">
        <input disabled type="text" class="form-control" v-model="berita_acara_penyerahan_faktur.kode_wilayah_penagihan" />
        <small v-if="error_exist('id_wilayah_penagihan')" class="form-text text-danger">{{ get_error('id_wilayah_penagihan') }}</small>
    </div>
    <label for="inputEmail3" class="col-sm-2 control-label">Wilayah Penagihan</label>
    <div class="col-sm-4">
        <input disabled type="text" class="form-control" v-model="berita_acara_penyerahan_faktur.nama_wilayah_penagihan" />
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
    <label for="inputEmail3" class="col-sm-2 control-label">Debt Collector</label>
    <div v-bind:class="{ 'has-error': error_exist('id_debt_collector') }" class="col-sm-4">
        <input disabled type="text" class="form-control" v-model="berita_acara_penyerahan_faktur.nama_debt_collector" />
        <small v-if="error_exist('id_debt_collector')" class="form-text text-danger">{{ get_error('id_debt_collector') }}</small>
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
                    <td width='10%' class='text-right'>Cash</td>
                    <td>BG</td>
                    <td>Transfer</td>
                    <td v-if='mode != "detail" && berita_acara_penyerahan_faktur.dikembalikan == 0' class='text-center'>Status (Dikembalikan?)</td>
                    <td v-if='mode == "pengembalian" && berita_acara_penyerahan_faktur.dikembalikan == 1' class='text-center'>Status</td>
                </tr>
                <tr v-for="(item, index) of items">
                    <td>{{ index + 1 }}.</td>
                    <td>{{ print_nama_customer(item) }}</td>
                    <td>{{ item.no_faktur }}</td>
                    <td>{{ item.tgl_jatuh_tempo }}</td>
                    <td class="text-right">
                        <vue-numeric v-model="item.total" read-only separator="." currency="Rp"></vue-numeric>
                    </td>
                    <td class="text-right">
                        <vue-numeric :read-only='berita_acara_penyerahan_faktur.dikembalikan == 1' class="form-control" v-model='item.cash' separator='.' currency='Rp' read-only></vue-numeric>
                    </td>
                    <td class="text-right">
                        <vue-numeric v-if='item.no_bg != ""' :read-only='berita_acara_penyerahan_faktur.dikembalikan == 1' class="form-control" v-model='item.amount_bg' separator='.' currency='Rp' read-only></vue-numeric>
                    </td>
                    <td class="text-right">
                        <vue-numeric :read-only='berita_acara_penyerahan_faktur.dikembalikan == 1' class="form-control" v-model='item.transfer' separator='.' currency='Rp' read-only></vue-numeric>
                    </td>
                    <td v-if='mode != "detail" && berita_acara_penyerahan_faktur.dikembalikan == 0' class='text-center'>
                        <input type="checkbox" true-value="1" false-value="0" v-model="item.dikembalikan" />
                    </td>
                    <td v-if='mode == "pengembalian" && berita_acara_penyerahan_faktur.dikembalikan == 1' class='text-center'>
                        <span v-if='item.dikembalikan == 1'>Dikembalikan</span>
                        <span v-if='item.dikembalikan == 0'>Tidak dikembalikan</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="text-right">
                        <vue-numeric v-model="total" read-only separator="." currency="Rp"></vue-numeric>
                    </td>
                    <td class="text-right">
                        <vue-numeric v-model="total_cash" read-only separator="." currency="Rp"></vue-numeric>
                    </td>
                    <td class="text-right">
                        <vue-numeric v-model="total_amount_bg" read-only separator="." currency="Rp"></vue-numeric>
                    </td>
                    <td class="text-right">
                        <vue-numeric v-model="total_transfer" read-only separator="." currency="Rp"></vue-numeric>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
