<div id='promo_sales_order'  class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Promo</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr class='bg-blue-gradient'>
                            <th>No.</th>
                            <th>ID Promo</th>
                            <th>Nama</th>
                            <th>Tipe Promo</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if='selected_part_promo.length > 0' v-for='(each, index) of selected_part_promo'>
                            <td>{{ index + 1 }}.</td>
                            <td>{{ each.id_promo }}</td>
                            <td>{{ each.nama }}</td>
                            <td>{{ each.tipe_promo }}</td>
                            <td>
                                <button v-if='_.get(parts, "[" + indexPart + "].selected_promo.id_promo") != each.id_promo' class="btn btn-flat btn-xs btn-success" @click.prevent='pilih_promo(index)'><i class="fa fa-check"></i></button>
                                <button v-if='_.get(parts, "[" + indexPart + "].selected_promo.id_promo") == each.id_promo' class="btn btn-flat btn-xs btn-danger" @click.prevent='hapus_promo()'><i class="fa fa-trash-o"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>