<!-- Modal -->
<div id="h3_md_ranges_diskon_oli_reguler" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Ranges</h4>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <td width='3%'>No.</td>
                        <td>Tipe Diskon</td>
                        <td>Diskon Value</td>
                        <td>Kode Range</td>
                        <td>Awal Range</td>
                        <td>Akhir Range</td>
                        <td v-if='mode != "detail"' width='3%'></td>
                    </tr>
                    <tr v-if='ranges.length > 0' v-for='(range, index) of ranges'>
                        <td class='align-middle'>{{ index + 1 }}.</td>
                        <td>
                            <select :disabled='mode == "detail"' v-model='range.tipe_diskon' class="form-control">
                                <option value="">-Pilih-</option>
                                <option value="Rupiah">Rupiah</option>
                                <option value="Persen">Persen</option>
                            </select>
                        </td>
                        <td>
                            <vue-numeric :disabled='mode == "detail"' v-model='range.diskon_value' class="form-control" separator='.' :currency='get_currency(range.tipe_diskon)' :currency-symbol-position='get_currency_position(range.tipe_diskon)'></vue-numeric>
                        </td>
                        <td class='align-middle'>{{ range.kode_range }}</td>
                        <td>
                            <vue-numeric disabled v-model='range.range_start' class="form-control" separator='.'></vue-numeric>
                        </td>
                        <td>
                            <vue-numeric disabled v-model='range.range_end' class="form-control" separator='.'></vue-numeric>
                        </td>
                        <td v-if='mode != "detail"'>
                            <button class="btn btn-flat btn-danger btn-sm" @click.prevent='hapus_range(index)'><i class="fa fa-trash-o"></i></button>
                        </td>
                    </tr>
                    <tr v-if='ranges.length < 1'>
                        <td class="text-center" colspan='7'>Tidak ada data</td>
                    </tr>
                    <tr>
                        <td colspan="6"></td>
                        <td>
                            <button v-if="mode != 'detail'" data-toggle='modal' data-target='#h3_md_range_dus_oli_diskon_oli_reguler' class="btn btn-flat btn-primary btn-sm" type="button"><i class="fa fa-plus"></i></button>
                        </td>
                    </tr>
                </table>
            </div>            
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#h3_md_ranges_diskon_oli_reguler').on('hide.bs.modal', function(){
            app.dealers[app.index_dealer].ranges = app.ranges;
            app.ranges = [];
        });
    });
</script>