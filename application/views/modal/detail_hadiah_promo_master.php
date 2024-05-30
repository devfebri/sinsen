<!-- Modal -->
<div id="detail_hadiah_promo_master" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Hadiah</h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed">
                    <tr>
                        <td class="align-middle" width='3%'>No.</td>
                        <td class="align-middle">Nama</td>
                        <td class="align-middle">Qty</td>
                        <td class="align-middle" width='3%'></td>
                    </tr>
                    <tr v-if='master.gifts.length > 0' v-for='(gift, index) of master.gifts'>
                        <td class="align-middle" width='3%'>{{ index + 1 }}.</td>
                        <td class="align-middle">{{ gift.nama_hadiah }}</td>
                        <td class="align-middle" width='10%'>{{ gift.qty_hadiah }}</td>
                        <td class="align-middle" width='3%'>
                            <button class="btn btn-sm btn-flat btn-danger" @click.prevent='hapus_gift_master(index)'><i class="fa fa-trash-o"></i></button>
                        </td>
                    </tr>
                    <tr v-if='master.gifts.length < 1'>
                        <td class="align-middle text-center" colspan='4'>Tidak ada data</td>
                    </tr>
                </table>
                <button class="btn btn-flat btn-primary btn-sm" type='button' data-toggle='modal' data-target='#add_hadiah_promo_master'><i class="fa fa-plus"></i></button>
            </div>
        </div>
    </div>
</div>