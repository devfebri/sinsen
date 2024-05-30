<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Penjualan Unit</li>
    <li class="">Generate List Unit Dealivery</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>

  <section class="content">
    <?php 
    if($set=="form"){
      $form     = '';
      $disabled = '';
      $readonly ='';
      if ($mode=='received') {
        $form = 'save_received';
      }
      if ($mode=='detail') {
        $disabled = 'disabled';
      }
    ?>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

<script>
  Vue.use(VueNumeric.default);
  $(document).ready(function(){
    <?php if (isset($row)) { ?>
        
    <?php } ?>
  })
</script>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/part_inbound_dealer">
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
            <form  class="form-horizontal" id="form_" action="part_inbound_dealer/<?= $form ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Penerimaan</label>
                  <div class="col-sm-4">                   
                    <input type="text" required class="form-control datepicker" id="tgl_penerimaan" name="tgl_penerimaan" autocomplete="off" value="<?= isset($row)?$row->tgl_penerimaan==null?date('Y-m-d'):$row->tgl_penerimaan:date('Y-m-d') ?>" <?= $disabled ?>>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal DO</label>
                  <div class="col-sm-4">                   
                    <input type="text" required class="form-control" id="tgl_do" name="tgl_do" autocomplete="off" value="<?= isset($row)?$row->tgl_do:'' ?>" readonly>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No DO</label>
                  <div class="col-sm-4">                   
                    <input type="text" required class="form-control" id="no_do_spare_part" name="no_do_spare_part" autocomplete="off" value="<?= isset($row)?$row->no_do_spare_part:'' ?>" readonly>
                  </div>
                </div>
                <button class="btn btn-block btn-primary btn-flat" disabled> DETAIL PARTS</button><br>
                <div class="form-group">
                  <div class="col-md-12">
                    <table class="table table-bordered">
                      <thead>
                        <th>No</th>
                        <th>Part Number</th>
                        <th>Part Name</th>
                        <th>Qty Shipping</th>
                        <th>UoM</th>
                        <th>Quantity Actual</th>
                        <th>UoM</th>
                      </thead>
                     <tbody>
                        <tr v-for="(prt, index) of dt_parts">
                          <td>{{index+1}}</td>
                          <td>{{prt.id_part}}</td>
                          <td>{{prt.nama_part}}</td>
                          <td>{{prt.qty_supply}}</td>
                          <td></td>
                          <td>
                            <vue-numeric style="float: left;width: 100%;text-align: left;"
                          class="form-control isi" v-model="prt.qty_actual" 
                          v-bind:minus="false" :empty-value="0" separator="." <?= $disabled ?>/>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>             
                </div>
              </div><!-- /.box-body -->
                        
              <div class="box-footer" v-if="mode!='detail'">
                <div class="col-sm-12" v-if="mode=='received'" align="center">
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
        total_harga : '',
        dt_parts : <?= isset($dt_parts)?json_encode($dt_parts):'[]' ?>,
      },
    methods: {
     
    },
  });

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
  var values = {dt_parts:form_.dt_parts};
  var form   = $('#form_').serializeArray();
  for (field of form) {
    values[field.name] = field.value;
  }
  if ($('#form_').valid()) // check if form is valid
  {
    // if (values.details.length==0) {
    //   alert('Detail masih kosong !')
    //   return false;
    // }
    if (confirm("Apakah anda yakin ?") == true) {
      $.ajax({
        beforeSend: function() {
          $('#submitBtn').attr('disabled',true);
        },
        url:'<?= base_url('dealer/part_inbound_dealer/'.$form) ?>',
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
    }elseif($set=="index"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
<!--           <a href="part_inbound_dealer/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>  -->                         
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No. DO</th>
              <th>Tanggal DO</th>
              <th>Tgl Penerimaan</th>
              <th>Total Part Shipping</th>
              <th>Total Part Actual</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
                        <?php 
        foreach ($dt->result() as $rs) { 
            $status='<label class="label label-primary">Draft</label>';   
            $button = "<a href=".base_url('dealer/part_inbound_dealer/received?id='.$rs->no_do_spare_part)." class=\"btn btn-primary btn-flat btn-xs\">Received</a>";         
          if ($rs->id_good_receipt_part!=null) {
            $button='';
            $status='<label class="label label-success">Received</label>';            
          }
          $tot_part = $this->db->query("SELECT SUM(qty_supply)AS tot_part FROM tr_create_do_spare_detail WHERE no_do_spare_part='$rs->no_do_spare_part'")->row()->tot_part;
          $tot_part_act = $this->db->query("SELECT IFNULL(SUM(qty_actual),0)AS tot_part FROM tr_penerimaan_part_dealer_detail 
            JOIN tr_penerimaan_part_dealer ON tr_penerimaan_part_dealer.id_good_receipt_part=tr_penerimaan_part_dealer_detail.id_good_receipt_part
            WHERE no_do_spare_part='$rs->no_do_spare_part'")->row()->tot_part;
        ?>
              <tr>
                <td><a href="<?= base_url('dealer/part_inbound_dealer/detail?id='.$rs->no_do_spare_part) ?>"><?= $rs->no_do_spare_part ?></a></td>
                <td><?= $rs->tgl_do ?></td>
                <td><?= $rs->tgl_penerimaan ?></td>
                <td><?= $tot_part ?></td>
                <td><?= $tot_part_act ?></td>
                <td><?= $status ?></td>
                <td><?= $button ?></td>
              </tr>
            <?php }
         ?>
          </tbody>          
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
<script>
   // $(document).ready(function(){  
   //    var dataTable = $('#datatable_server').DataTable({  
   //       "processing":true, 
   //       "serverSide":true, 
   //       "language": {                
   //            "infoFiltered": "",
   //            "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
   //        }, 
   //       "order":[],
   //       "lengthMenu": [[10, 25, 50,75,100], [10, 25, 50,75,100]],  
   //       "ajax":{  
   //            url:"<?php echo site_url('dealer/part_inbound_dealer/fetch'); ?>",  
   //            type:"POST",
   //            dataSrc: "data",
   //            data: function (d) {
   //                return d;
   //            },
   //       },  
   //       "columnDefs":[  
   //            // { "targets":[2],"orderable":false},
   //            { "targets":[2],"className":'text-center'}, 
   //            // // { "targets":[0],"checkboxes":{'selectRow':true}}
   //            // { "targets":[6,7],"className":'text-right'}, 
   //            // // { "targets":[2,4,5], "searchable": false } 
   //       ],
   //    });
   //  });
</script>
    <?php
    }
    ?>
  </section>
</div>