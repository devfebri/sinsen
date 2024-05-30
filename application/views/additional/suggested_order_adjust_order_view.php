<!-- <input id="adjusted-order-input-<?= $loop ?>" style="width: 100% !important;" type="text" class="form-control form-control-sm"> -->
<div id="adjusted-order-input-<?= $loop ?>">
    <vue-numeric v-model="adjust_order" class="input-compact" style="width: 100% !important;" separator="."></vue-numeric>
</div>
<script>
    $(document).ready(function(){
        adjusted_order_input_<?= $loop ?> = vueForm = new Vue({
          el: '#adjusted-order-input-<?= $loop ?>',
          data: {
            adjust_order: <?= $adjusted_order ?>
          },
          watch: {
              adjust_order: _.debounce(function(val){
                  axios.post('dealer/h3_dealer_suggested_order/adjust_order', Qs.stringify({
                      adjusted_order: this.adjust_order,
                      id_part: '<?= $id_part ?>'
                  }))
                  .then(function(res){
                    toastr.success('Adjusted Order untuk part ' + res.data.id_part + ' telah berhasil di ubah.');
                  })
                  .catch(function(err){
                      toastr.error(err);
                  });
              }, 500),
          }
        });

        
    });
</script>