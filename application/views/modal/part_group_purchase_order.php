<!-- Modal -->
<div id="part_group_purchase_order" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Part Group</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-sm-4">
                        <div v-for='e in part_group'>
                            <input type="checkbox" id="e.kelompok_part" :value="e.kelompok_part" v-model='checked_part_group'>
                            <label for="e.kelompok_part">{{ e.kelompok_part }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>