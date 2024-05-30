<div id="app" class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">
            <a href="h3/<?= $isi ?>">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
            </a>
        </h3>
    </div>
    <!-- /.box-header -->
    <div v-if="loading" class="overlay">
        <i class="fa fa-refresh fa-spin text-light-blue"></i>
    </div>
    <div class="box-body">
        <?php $this->load->view('template/session_message.php'); ?>
        <div class="row">
            <div class="col-md-12">
                <div v-if='upload_errors.length > 0' class="container-fluid">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-warning"></i> Peringatan, terdapat masalah dalam mengimport data sales order.</h4>
                        <ul>
                          <li v-for='error of upload_errors'>{{ error }}</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                <form class="form-horizontal">
                    <div class="box-body">
                        <div v-bind:class="{ 'has-error': error_exist('file') }" class="form-group">
                            <label class="col-sm-2 control-label">File Template SO</label>
                            <div class="col-sm-4">
                              <input type="file" @change='on_file_change()' ref='file' class="form-control">
                              <small v-if="error_exist('file')" class="form-text text-danger">{{ get_error('file') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-sm-6 no-padding">
                          <button class="btn btn-flat btn-primary btn-sm" @click.prevent='upload'>Upload</button>
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /.box -->
<script>
    app = new Vue({
        el: '#app',
        data: {
          loading: false,
          errors: {},
          upload_errors: [],
          file: null
        },
        methods: {
          upload: function(){
            post = new FormData();
            post.append('file', this.file);

            this.errors = {};
            this.upload_errors = [];
            this.loading = true;
            axios.post('h3/<?= $isi ?>/store_upload', post, {
              headers: {
                'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
              }
            })
            .then(function(res){
              data = res.data;
              if(data.payload != null){
                window.location = 'h3/h3_md_sales_order/detail?id=' + data.payload.id_sales_order;
              }
            })
            .catch(function(err){
              data = err.response.data;
              if(data.error_type == 'validation_error'){
                app.errors = data.errors;
                toastr.error(data.message);
              }else if(data.error_type == 'upload_error'){
                app.upload_errors = data.errors;
                toastr.error(data.message);
              }else{
                toastr.error(err);
              }
            })
            .then(function(){ app.loading = false; });
          },
          on_file_change: function(){
            this.file = this.$refs.file.files[0];
          },
          error_exist: function(key){
            return _.get(this.errors, key) != null;
          },
          get_error: function(key){
            return _.get(this.errors, key)
          }
        }
      });
</script>
