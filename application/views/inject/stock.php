<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Inject Stock</title>
   </head>
   <body>
      <link rel="stylesheet" href="<?= base_url('assets/panel/bootstrap/css/bootstrap.min.css') ?>">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="<?= base_url('assets/panel/font-awesome/css/font-awesome.min.css">  ') ?>  
         <!-- Ionicons -->
         <link rel="stylesheet" href="<?= base_url('assets/panel/ionicons.min.css') ?>">
      <!-- Theme style -->
      <link rel="stylesheet" href="<?= base_url('assets/panel/dist/css/AdminLTE.min.css') ?>">
      <link rel="stylesheet" href="<?= base_url('assets/panel/custom.css') ?>">
      <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
      <link rel="stylesheet" href="<?= base_url('assets/panel/dist/css/skins/_all-skins.min.css') ?>">
      <!-- iCheck -->
      <link rel="stylesheet" href="<?= base_url('assets/panel/plugins/iCheck/flat/blue.css') ?>">
      <!-- Morris chart -->
      <link rel="stylesheet" href="<?= base_url('assets/panel/plugins/morris/morris.css') ?>">
      <!-- jvectormap -->
      <link rel="stylesheet" href="<?= base_url('assets/panel/plugins/jvectormap/jquery-jvectormap-1.2.2.css') ?>">
      <!-- Date Picker -->
      <link rel="stylesheet" href="<?= base_url('assets/panel/plugins/datepicker/datepicker3.css') ?>">
      <!-- Daterange picker -->
      <link rel="stylesheet" href="<?= base_url('assets/panel/plugins/daterangepicker/daterangepicker-bs3.css') ?>">
      <!-- bootstrap wysihtml5 - text editor -->
      <link rel="stylesheet" href="<?= base_url('assets/panel/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') ?>">
      <link rel="stylesheet" href="<?= base_url('assets/panel/plugins/select2/select2.min.css') ?>">
      <link rel="stylesheet" href="<?= base_url('assets/panel/plugins/datatables/dataTables.bootstrap.css') ?>">
      <link rel="stylesheet" href="<?= base_url('assets/panel/plugins/iCheck/all.css') ?>">
      <link rel="stylesheet" href="<?= base_url('assets/toastr/toastr.css') ?>">
      <script src="<?= base_url('assets/panel/plugins/jQuery/jquery-2.2.3.min.js') ?>"></script>
      <script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url('assets/panel/lodash.min.js') ?>"></script> 
      <script src="<?= base_url('assets/panel/moment.min.js') ?>"></script> 
      <script src="<?= base_url('assets/panel/daterangepicker.min.js') ?>"></script> 
      <script src="<?= base_url('assets/toastr/toastr.min.js') ?>"></script>
      <link rel="stylesheet" type="text/css" href="<?= base_url('assets/panel/daterangepicker.css') ?>" />
      <script>
         Vue.use(VueNumeric.default);
      </script>
      <base href="<?php echo base_url(); ?>" />
      <body>
         <section class="content">
            <div id="app" class="box" style='border-top: 0; border-bottom: 0;'>
               <div v-if="loading" class="overlay">
                  <i class="fa fa-refresh fa-spin text-light-blue"></i>
               </div>
               <div class="box-body">
                  <div class="row">
                     <div class="col-md-12">
                        <form class="form-horizontal">
                           <div class="box-body">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">File stock</label>
                                    <div class="col-sm-4">
                                    <input type="file" @change='on_file_change()' ref='file' class="form-control" accept=".xlsx">
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <div class="col-sm-6 no-padding">
                                        <button :disabled='file == null' class="btn btn-flat btn-primary" @click.prevent='upload'>Upload</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                     </div>
                  </div>
                  <div v-if='validation_error.length > 0' class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" @click.prevent='validation_error = []' aria-hidden="true">×</button>
                        <h4>
                            <i class="icon fa fa-warning"></i> 
                            Alert!
                        </h4>
                        <ol class="">
                            <li v-for='(each, index) of validation_error'>
                            {{ each.message }}
                            <ul>
                                <li v-for='(error, index) of each.errors'>{{ error }}</li>
                            </ul>
                            </li>
                        </ol>
                    </div>
               </div>
            </div>
            <!-- /.box -->
            <script>
                var app = new Vue({
                    el: '#app',
                    data: {
                        loading: false,
                        validation_error: [],
                        file: null
                    },
                    methods: {
                        upload: function(){
                            post = new FormData();
                            post.append('file', this.file);

                            this.validation_error = [];
                            this.loading = true;
                            axios.post('inject/stock/upload_excel', post, {
                                headers: {
                                'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
                                }
                            })
                            .then(function(res){
                                toastr.success('Stok Berhasil di Inject');
                            })
                            .catch(function(err){
                                data = err.response.data;
                                if(data.error_type == 'validation_error'){
                                    app.validation_error = data.payloads;
                                }else{
                                    toastr.error(err);
                                }
                            })
                            .then(function(){ app.loading = false; app.reset_file(); });
                        },
                        on_file_change: function(){
                            this.file = this.$refs.file.files[0];
                        },
                        reset_file: function(){
                            const input = this.$refs.file;
                            input.type = 'text';
                            input.type = 'file';
                        }
                    }
                });
            </script>
         </section>
   </body>
</html>