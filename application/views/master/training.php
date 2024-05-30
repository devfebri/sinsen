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
    <li class="">Master</li>
    <li class="">Master H2</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="form"){
      $form = '';
      $disabled = '';
      $readonly='';
      if ($mode=='insert') {
        $form='save';
      }
      if ($mode=='edit') {
        $form='save_edit';
        $readonly='readonly';
      }
      if ($mode=='detail') {
        $form='';
        $disabled = 'disabled';
      }
    ?>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script>
  Vue.use(VueNumeric.default);
  // $(document).ready(function(){
  //   <?php if (isset($row)) { ?>
  //       pilihAHASS(<?= json_encode($dealer) ?>)

  //   <?php } ?>
  // })
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
          <a href="master/training">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
        <div class="row">
          <div class="col-md-12">
            <form id="form_" class="form-horizontal" method="post" enctype="multipart/form-data">
              <div class="box-body">
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Training</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" name="id_training" id="id_training" autocomplete="off" value="<?= isset($row)?$row->id_training:'' ?>" :readonly="mode=='detail'" <?= $readonly ?>>                     
                  </div>               
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Training</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" name="training" id="training" autocomplete="off" value="<?= isset($row)?$row->training:'' ?>" :readonly="mode=='detail'">                    
                  </div>               
                </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
               <div class="col-sm-12" align="center" v-if="mode=='insert' || mode=='edit'">
                  <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>            
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
<script>
  var form_ = new Vue({
    el: '#form_',
    data: {
      mode : '<?= $mode ?>',
    },   
    methods:{
      
    }
  })

$('#submitBtn').click(function(){
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
  var values = {};
  var form   = $('#form_').serializeArray();
  for (field of form) {
    values[field.name] = field.value;
  }
  if ($('#form_').valid()) // check if form is valid
  {
    
    if (confirm("Apakah anda yakin ?") == true) {
      $.ajax({
        beforeSend: function() {
          $('#submitBtn').attr('disabled',true);
        },
        url:'<?= base_url('master/training/'.$form) ?>',
        type:"POST",
        data: values,
        cache:false,
        dataType:'JSON',
        success:function(response){
          if (response.status=='sukses') {
            window.location = response.link;
          }else{
            alert(response.pesan);
          }
          $('#submitBtn').attr('disabled',false);
        },
        error:function(){
          alert("failure");
          $('#submitBtn').attr('disabled',false);

        },
        statusCode: {
          500: function() { 
            alert('fail');
            $('#submitBtn').attr('disabled',false);

          }
        }
      });
    } else {
      return false;
    }
  }else{
    alert('Silahkan isi field required !')
  }
})
</script>
    <?php
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/training/add">
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
        <table id="example4" class="table table-hover">
          <thead>
            <tr>
              <th>ID Training</th>
              <th>Training</th>
              <th width="12%" align="center">Aksi</th>     
            </tr>
          </thead>
          <tbody>            
          <?php 
         foreach($dt_result->result() as $row) { 
          $btn_edit = "<a href='".base_url('master/training/edit?id=').$row->id_training."' class='btn btn-warning btn-xs'><i class='fa fa-edit'></a>";
          $button = $btn_edit;
          echo "
          <tr>
          <td><a href=".base_url('master/training/detail?id=').$row->id_training.">$row->id_training</a></td>
          <td>$row->training</td>
          <td>$button</td>
          </tr>
          ";
         }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php
    }
    ?>
  </section>
</div>