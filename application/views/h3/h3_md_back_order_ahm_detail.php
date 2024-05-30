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
        <form class="form-horizontal" action="h3/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
          <div class="box-body">              
            <div class="form-group">                  
              <div v-if='mode != "insert"'>
                <label class="col-sm-2 control-label">Tanggal PO</label>
                <div class="col-sm-4">                    
                  <input type="text" readonly class="form-control" v-model='purchase.tanggal_po'>
                </div>  
                <label class="col-sm-2 control-label">No PO MD</label>
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
                <label class="col-sm-2 control-label">No BO AHM</label>
                <div class="col-sm-4">                    
                  <input type="text" readonly class="form-control" v-model='purchase.no_bo_ahm'>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12 text-center bg-blue" style='padding: 5px; 0'>
                <span class='text-bold'>Detail Parts</span>
              </div>
              <div class="col-sm-12">
                <table id="table" class="table table-condensed table-responsive">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Kode Part</th>
                      <th>Nama Part</th>
                      <th>Kuantitas Order</th>
                      <th>Kuantitas Penerimaan</th>
                      <th>Kuantitas Back Order</th>
                    </tr>
                  </thead>
                  <tbody>            
                    <tr v-for="(row, index) in parts"> 
                      <td>{{ index + 1 }}.</td>
                      <td>{{ row.id_part }}</td>
                      <td>{{ row.nama_part }}</td>
                      <td>{{ row.qty_order }}</td>
                      <td>{{ row.qty_penerimaan }}</td>
                      <td>{{ row.qty_back_order }}</td>
                    </tr>
                  </tbody>                    
                </table>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12 text-center bg-blue" style='padding: 5px; 0'>
                <span class='text-bold'>Detail Penerimaan</span>
              </div>
              <div class="col-sm-12">
                <table id="table" class="table table-condensed table-responsive">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Kode Part</th>
                      <th>Nama Part</th>
                      <th>Tgl SL</th>
                      <th>Tgl Terima</th>
                      <th>Qty SL</th>
                      <th>Qty Terima</th>
                    </tr>
                  </thead>
                  <tbody>            
                    <tr v-for="(row, index) in penerimaan"> 
                      <td>{{ index + 1 }}.</td>
                      <td>{{ row.id_part }}</td>
                      <td>{{ row.nama_part }}</td>
                      <td>{{ row.tanggal_sl }}</td>
                      <td>{{ row.tanggal_terima }}</td>
                      <td>{{ row.qty_sl }}</td>
                      <td>{{ row.qty_terima }}</td>
                    </tr>
                    <tr v-if="penerimaan.length < 1">
                      <td class="text-center" colspan="15">Tidak ada data</td>
                    </tr>
                  </tbody>                    
                </table>
              </div>
            </div>                                                                                                                            
          </div><!-- /.box-body -->
          <div class="box-footer">
            <a v-if='purchase.sudah_back_order == 0' :href="'h3/<?= $isi ?>/po_expired?id_purchase_order=' + purchase.id_purchase_order" class="btn btn-flat btn-sm btn-primary">PO Expired</a>
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
        penerimaan: <?= json_encode($penerimaan) ?>,
      },
      methods: {
        <?= $form ?>: function(){
          this.loading = true;
          this.errors = {};

          post = this.purchase;
          post.total_amount = this.total_amount;
          post.parts = _.chain(this.parts)
          .filter(function(part){
            return part.checked == 1;
          })
          .value();

          axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            window.location = 'h3/<?= $isi ?>/detail?id_purchase_order=' + res.data.id_purchase_order;
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              toastr.error(data.message);
              app.errors = data.errors;
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