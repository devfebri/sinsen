<label id='filter_kelompok_part_suggested_order' style="margin-right: 5px;">Kelompok Part:
    <input readonly type="text" class="form-control" v-model='checked_part_group' data-toggle='modal' data-target='#part_group_suggested_order'>
    <!-- Modal -->
    <div id="part_group_suggested_order" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
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
                            <div v-for='e in kelompok_part'>
                                <input class='kelompok_part_checkbox' type="checkbox" id="e.kelompok_part" :value="e.kelompok_part" v-model='checked_part_group'>
                                <label for="e.kelompok_part">{{ e.kelompok_part }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</label>
<script>
    // $(document).ready(function(){
    //     $('#part_group_suggested_order').modal().on('hide.bs.modal', function(e){
    //         console.log(e);
    //     });
    // });

    filter_kelompok_part_suggested_order = new Vue({
        el: '#filter_kelompok_part_suggested_order',
        data: {
            kelompok_part: <?= json_encode($kelompok_part) ?>,
            checked_part_group: [],
        },
        methods:{
            suggested_order_draw_handler: _.debounce(function(){ suggested_order.draw(); }, 500),
        },
        watch: {
            checked_part_group: function(){
                this.suggested_order_draw_handler();
            },
        },
    });
</script>