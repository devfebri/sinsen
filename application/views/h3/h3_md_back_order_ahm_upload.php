<div id="app" class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">
      <a href="h3/<?= $isi ?>">
        <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
      </a>  
    </h3>
  </div><!-- /.box-header -->
  <div v-if="loading" class="overlay">
    <i class="fa fa-refresh fa-spin text-light-blue"></i>
  </div>
  <div class="box-body">
    <div v-if='errors_payload.length > 0' class="alert alert-warning alert-dismissible">
      <button type="button" class="close" @click.prevent='errors_payload = []' aria-hidden="true">×</button>
      <h4>
        <i class="icon fa fa-warning"></i> 
        Perhatian!
      </h4>
      <p>Terdapat Packing Sheet dengan part yang tidak terdaftar di sistem, antara lain:</p>
      <ol class="">
        <li v-for='(each, index) of errors_payload'>
          {{ each.packing_sheet_number }}
          <ul>
            <li v-for='(part, index) of each.parts_tidak_terdaftar'>{{ part }}</li>
          </ul>
        </li>
      </ol>
    </div>
    <div class="row">
      <div class="col-md-12">
        <form class="form-horizontal">
          <div class="box-body">
            <div class="form-group">                  
              <label class="col-sm-2 control-label">File Template BO AHM</label>
              <div class="col-sm-4">                    
                <input type="file" @change='on_file_change()' ref='file' class="form-control" accept=".xls,.xlsx">
              </div>
              <div class="col-sm-2">
                <a href="h3/<?= $isi ?>/download_bo_template" class='btn btn-flat btn-info'>Download Template</a>
              </div>
            </div>
          </div><!-- /.box-body -->
          <div class="box-footer">
            <div class="col-sm-12 no-padding">
              <button :disabled='file == null' class="btn btn-flat btn-sm btn-primary" type="submit" @click.prevent='upload'>Upload</button>
            </div>
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
        error_type: null,
        errors_payload: {},
        success: {},
        file: null
      },
      methods: {
        upload: function(){
          post = new FormData();
          post.append('file', this.file);

          this.errors_payload = {};
          this.loading = true;
          axios.post('h3/<?= $isi ?>/store_upload', post, {
            headers: {
              'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
            }
          })
          .then(function(res){
            window.location = 'h3/<?= $isi ?>';
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'part_tidak_terdaftar'){
              app.error_type = data.error_type;
              app.errors_payload = data.errors_payload;
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