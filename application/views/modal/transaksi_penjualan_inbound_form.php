<!-- Modal -->
<div id="transaksi_penjualan_inbound_form" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Transaksi Penjualan</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="control-label">Nomor Invoice</label>
                            <input v-model='invoice.nomor_invoice' type="text" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label">Tanggal Invoice</label>
                            <input id='tanggal_invoice' type="text" class="form-control datepicker">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <table class="table table-condensed table-striped">
                                <tr class='bg-blue-gradient'>
                                    <td width='3%'>No.</td>
                                    <td width='15%'>Part</td>
                                    <td width='15%'>Deskripsi</td>
                                    <td width='10%'>Qty</td>
                                    <td width='20%'>HET</td>
                                    <td>Diskon</td>
                                    <td width='10%'>Value</td>
                                    <td width='3%'></td>
                                </tr>
                                <tr v-if='invoice_parts.length > 0' v-for='(each, index) of invoice_parts'>
                                    <td class='align-middle'>{{ index + 1 }}.</td>
                                    <td class='align-middle'>{{ each.id_part }}</td>
                                    <td class='align-middle'>{{ each.nama_part }}</td>
                                    <td class='align-middle'>
                                        <vue-numeric v-model='each.qty' separator='.' class='form-control'></vue-numeric>
                                    </td>
                                    <td class='align-middle'>
                                        <vue-numeric v-model='each.harga' separator='.' currency='Rp' class='form-control'></vue-numeric>
                                    </td>
                                    <td class='align-middle'>
                                        <select class="form-control" v-model='each.tipe_diskon'>
                                            <option value="Percentage">Percentage</option>
                                            <option value="FoC">FoC</option>
                                            <option value="Value">Value</option>
                                        </select>
                                    </td>
                                    <td class='align-middle'>
                                        <vue-numeric v-model='each.diskon_value' separator='.' class='form-control'></vue-numeric>
                                    </td>
                                    <td class='align-middle'>
                                        <button @click.prevent='hapus_invoice_part(index)' class="btn btn-flat btn-sm btn-danger" type='button'><i class="fa fa-trash-o"></i></button>
                                    </td>
                                </tr>
                                <tr v-if='invoice_parts.length < 1'>
                                    <td colspan='7' class='align-middle text-center'>Tidak ada data</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-6">
                            <button @click.prevent='simpan_invoice' class="btn btn-flat btn-sm btn-success" type='button'>Simpan Invoice</button>
                        </div>
                        <div class="col-sm-6 text-right">
                            <button class="btn btn-flat btn-sm btn-primary" type='button' data-toggle='modal' data-target='#parts_transaksi_penjualan_inbound_form'><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>