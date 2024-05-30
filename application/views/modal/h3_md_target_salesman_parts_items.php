<!-- Modal -->
<div id="h3_target_salesman_parts_items" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Item</h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed">
                    <tr>
                        <td width='3%'>No.</td>
                        <td>Kelompok Barang</td>
                        <td>Target</td>
                        <td v-if='mode != "detail"' width='3%'></td>
                    </tr>
                    <tr v-if='items.length > 0' v-for='(item, index) of items'>
                        <td>{{ index + 1 }}.</td>
                        <td>{{ item.id_kelompok_part }}</td>
                        <td>
                            <vue-numeric :disabled='mode == "detail"' class="form-control" v-model='item.target_part_items' currency='Rp' separator='.'></vue-numeric>
                        </td>
                        <td v-if='mode != "detail"'>
                            <button class="btn btn-flat btn-danger btn-sm"><i class="fa fa-trash-o"></i></button>
                        </td>
                    </tr>
                    <tr v-if='items.length < 1'>
                        <td class='text-center' colspan='4'>Tidak ada data</td>
                    </tr>
                </table>
                <div v-if='mode != "detail"' class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-flat btn-primary btn-sm" data-toggle='modal' data-target='#h3_md_kelompok_part_target_salesman_parts' type='button'><i class="fa fa-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#h3_target_salesman_parts_items').on('hide.bs.modal', function(){
            app.target_salesman_parts[app.index_target_salesman_parts].items = app.items;
            app.items = [];
        });
    });
</script>