<div id='app' class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">
      <a href="h3/<?= $isi ?>">
        <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
      </a>  
    </h3>
  </div><!-- /.box-header -->
  <div v-if='loading' class="overlay">
    <i class="fa fa-refresh fa-spin text-light-blue"></i>
  </div>
  <div class="box-body">
    <?php $this->load->view('template/session_message.php'); ?>
    <div class="row">
      <div class="col-md-12">
        <form class="form-horizontal">
          <div class="box-body">              
            <div class="row">
              <div class="col-sm-12 bg-blue text-center" style='padding: 5px 0; margin-bottom: 10px;'>
                <span class='text-bold'>PO Expired</span>
              </div>
            </div>
            <div class="form-group">                  
              <div v-if='mode != "insert"'>
                <label class="col-sm-2 control-label">Tanggal PO</label>
                <div class="col-sm-4">                    
                  <input type="text" readonly class="form-control" v-model='purchase.tanggal_po'>
                </div>  
                <label class="col-sm-2 control-label">No PO MD Lama</label>
                <div class="col-sm-4">                    
                  <input type="text" readonly class="form-control" v-model='purchase.id_purchase_order'>
                </div>
              </div>
            </div>
            <div class="form-group">                  
              <div v-if='mode != "insert"'>
                <label class="col-sm-2 control-label">Tipe PO</label>
                <div class="col-sm-4">                    
                  <input type="text" readonly class="form-control" v-model='purchase.jenis_po'>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
                <table id="table" class="table table-condensed table-responsive">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Kode Part</th>
                      <th>Nama Part</th>
                      <th>Kelompok Part</th>
                      <th>Qty Order</th>
                      <th>Qty Penerimaan</th>
                      <th>Qty Back Order</th>
                    </tr>
                  </thead>
                  <tbody>            
                    <tr v-for="(part, index) in parts"> 
                      <td>{{ index + 1 }}.</td>
                      <td>{{ part.id_part }}</td>
                      <td>{{ part.nama_part }}</td>
                      <td>{{ part.kelompok_part }}</td>
                      <td>{{ part.qty_order }}</td>
                      <td>{{ part.qty_penerimaan }}</td>
                      <td>{{ part.qty_back_order }}</td>
                    </tr>
                    <tr v-if="parts.length < 1">
                      <td class="text-center" colspan="5">Tidak ada data</td>
                    </tr>
                  </tbody>                    
                </table>
              </div>
            </div>                                                                                                                            
          </div><!-- /.box-body -->
          <div class="box-footer">
            <button @click.prevent='create_new_po' class="btn btn-flat btn-sm btn-info">Create New PO</button>
          </div><!-- /.box-footer -->
        </form>
      </div>
    </div>
  </div>
</div><!-- /.box -->
<script>
  var app = new Vue({
      el: '#app',
      data: {
        loading: false,
        errors: {},
        mode: '<?= $mode ?>',
        purchase: <?= json_encode($purchase) ?>,
        parts: <?= json_encode($parts) ?>,
      },
      methods: {
        create_new_po: function(){
          this.loading = true;
          this.errors = {};

          axios.get('h3/<?= $isi ?>/create_new_po', {
            params: {
              id_purchase_order: this.purchase.id_purchase_order
            }
          })
          .then(function(res){
            window.location = 'h3/h3_md_purchase_order/detail?id_purchase_order=' + res.data.id_purchase_order;
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              toastr.error(data.message);
              app.errors = data.errors;
            }else if(data.error_type == 'not_created'){
              toastr.warning(data.message);
            }else{
              toastr.error(err);
            }
          })
          .then(function(){ app.loading = false; })
        },
        sub_total: function(part) {
          return (part.qty_order * part.harga);
        },
        error_exist: function(key){
          return _.get(this.errors, key) != null;
        },
        get_error: function(key){
          return _.get(this.errors, key)
        },
      },
      computed: {
        total_amount: function(){
          sub_total_fn = this.sub_total;
          return _.chain(this.parts)
          .sumBy(function(part){
            return sub_total_fn(part);
          })
          .value();
        },
      },
    });
</script>