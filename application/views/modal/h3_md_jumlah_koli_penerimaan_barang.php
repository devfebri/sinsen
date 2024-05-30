<!-- Modal -->
<div id="h3_md_jumlah_koli_penerimaan_barang" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Jumlah Koli</h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed">
                    <tr>
                        <td>No.</td>
                        <td>Koli</td>
                        <td>Keterangan</td>
                        <td width='3%'></td>
                    </tr>
                    <tr v-if='list_jumlah_koli.length > 0' v-for='(each, index) of list_jumlah_koli'>
                        <td>{{ index + 1 }}.</td>
                        <td>
                            <vue-numeric class='form-control' v-model='each.koli' separator='.'></vue-numeric>
                        </td>
                        <td>
                            <input type="text" class="form-control" v-model='each.keterangan'>
                        </td>
                        <td>
                            <button class="btn btn-flat btn-danger btn-sm" @click.prevent='hapus_jumlah_koli(index)'><i class="fa fa-trash-o"></i></button>
                        </td>
                    </tr>
                    <tr v-if='list_jumlah_koli.length < 1'>
                        <td class='text-center' colspan='4'>Tidak ada data</td>
                    </tr>
                </table>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-flat btn-primary btn-sm" @click.prevent='tambah_jumlah_koli'><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>