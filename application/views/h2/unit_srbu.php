  <style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 25px;
  padding-left: 4px;
  padding-right: 4px;  
}
</style>
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H2</li>
    <li class="">SRBU</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
<?php 
if($set=="form"):
  $form ='';
  $readonly='';
  $disabled = '';
  if ($mode=='insert') {
    $form = 'save';
  }
?>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script>
  Vue.use(VueNumeric.default);
  $(document).ready(function(){
    <?php if (isset($row)) { ?>
        // pilihAHASS(<?= json_encode($dealer) ?>)
    <?php } ?>
  })
  Vue.filter('toCurrency', function (value) {
      // console.log("type value ke currency filter" ,  value, typeof value, typeof value !== "number");
      if (typeof value !== "number") {
          return value;
      }
      return accounting.formatMoney(value, "", 0, ".", ",");
      return value;
  });
</script>
<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">
      <a href="h2/unit_srbu">
        <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
      </a>
    </h3>
    <div class="box-tools pull-right">
      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
    </div>
  </div><!-- /.box-header -->
  <div class="box-body">
    <div class="row">
      <div class="col-md-12">
        <form id="form_" class="form-horizontal" enctype="multipart/form-data">
          <div class="form-group">
            <label class="col-sm-2 control-label">No. Mesin</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" @click.prevent="" name="no_mesin" id="no_mesin">
            </div>
          </div>
          <div class="box-footer">
            <div class="col-sm-12" style="text-align: center;">
              <button type="button" @click.prevent="saveFunc" id="submitBtn" class="btn btn-primary btn-flat">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  var form_ = new Vue({
    el: '#form_',
    data: {
      mode : '<?= $mode ?>',
      data :'',
    },
    methods:{
      saveFunc : function () {
      $('#form_').validate({
          rules: {
              'checkbox': {
                  required: true
              }
          },
          highlight: function (input) {
              $(input).parents('.form-group').addClass('has-error');
          },
          unhighlight: function (input) {
              $(input).parents('.form-group').removeClass('has-error');
          }
      })
      if ($('#form_').valid()) // check if form is valid
      {
        let values = {};
        let form   = $('#form_').serializeArray();
        for (field of form) {
          values[field.name] = field.value;
        }
          $.ajax({
            beforeSend: function() {
              $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
              $('#submitBtn').attr('disabled',true);
            },
            url:'<?= base_url('h2/unit_srbu/'.$form) ?>',
            type:"POST",
            data: values,
            cache:false,
            dataType:'JSON',
            success:function(response){
              $('#submitBtn').html('Save');
              if (response.status=='sukses') {
                window.location = response.link;
              }else{
                alert(response.pesan);
                $('#submitBtn').attr('disabled',false);
              }
            },
            error:function(){
              alert("failure");
              $('#submitBtn').html('Save');
              $('#submitBtn').attr('disabled',false);
            },
            statusCode: {
              500: function() { 
                alert('fail');
                $('#submitBtn').html('Save');
                $('#submitBtn').attr('disabled',false);
              }
            }
          });
        }else{
          alert('Silahkan isi field required !')
        }
      },
    }
  })
</script>
<?php endif ?>
<?php
if($set=="view"):
?>
<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title">
      <a href="h2/unit_srbu/add">
        <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
      </a>                      
    </h3>
    <div class="box-tools pull-right">
      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
    </div>
  </div><!-- /.box-header -->
  <div class="box-body">
    <?php                       
    if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
    ?>                  
    <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
        <strong><?php echo $_SESSION['pesan'] ?></strong>
        <button class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>  
        </button>
    </div>
    <?php
    }
        $_SESSION['pesan'] = '';                        
            
    ?>
    <table id="tbl_srbu" class="table table-hover table-bordered table-stripped table-condensed">
      <thead>
        <tr>
          <th>No. Mesin</th>
          <th>No. Rangka</th>
          <th>Tipe Kendaraan</th>
          <th>Warna</th>
          <th width="8%" align="center">Aksi</th>     
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div><!-- /.box-body -->
</div><!-- /.box -->
<script>
  $(document).ready(function(){
      $('#tbl_srbu').DataTable({
          processing: true,
          serverSide: true,
          "language": {                
                  "infoFiltered": ""
              },
          order: [],
          ajax: {
              url: "<?= base_url('h2/unit_srbu/fetch') ?>",
              dataSrc: "data",
              data: function ( d ) {
                d.id_menu        = '<?= $id_menu ?>';
                d.group          = '<?= $group ?>';
                return d;
                },
              type: "POST"
          },
          "columnDefs":[  
            { "targets":[4],"orderable":false},
            { "targets":[4],"className":'text-center'}, 
            { "targets":[4], "searchable": false } 
          ]
      });
  });
// function loads()
// {
//     $('#tbl_srbu').DataTable().ajax.reload();
// }
</script>
  <?php endif ?>
  </section>
</div>