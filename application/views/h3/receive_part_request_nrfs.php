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
.mb-10{
  margin-bottom: 2px;
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
    <li class="">Penerimaan Unit</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content"> 
    <div class="box">
      <div class="box-body" style="min-height: 700px">
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

        <?php } $_SESSION['pesan'] = ''; ?>

<?php if($set=="view"){ ?>
<div style="margin-bottom:10px;margin-top: 40px"><hr></div>
<table id="datatable_server" class="table table-bordered table-hover table-striped">
    <thead>
      <th>Request ID</th>              
      <th>Dokument NRFS ID</th>
      <th>Nomor Shipping List</th>
      <th>Nomor Mesin</th>
      <th>Nomor Rangka</th>
      <th>Kode Tipe Unit</th>
      <th>Sumber NRFS</th>
      <th>Status Request</th>
      <th>Action</th>             
    </thead>
  </table>
<script>
$(document).ready(function(){  
  var dataTable = $('#datatable_server').DataTable({  
     "processing":true, 
     "serverSide":true, 
     "language": {                
          "infoFiltered": "",
      }, 
     "order":[],
     "lengthMenu": [[10, 25, 50,75,100], [10, 25, 50,75,100]],  
     "ajax":{  
          url:"<?php echo site_url('h3/receive_part_request_nrfs/fetch_data'); ?>",  
          type:"POST",
          dataSrc: "data",
          data: function ( d ) {
            // d.start_date = $('#start_date').val();
            // d.end_date = $('#end_date').val();
            return d;
          },
     },  
     "columnDefs":[  
          { "targets":[8],"orderable":false},
          // { "targets":[8],"className":'text-center'}, 
          // { "targets":0,"checkboxes":{'selectRow':true}}
          // { "targets":[2],"className":'text-right'}, 
          // { "targets":[2,4,5], "searchable": false } 
     ],
    //  'select': {
    //    'style': 'multi'
    // },
  });

  });
function cancelPrompt(request_id) {
    var alasan_cancel = prompt("Alasan melakukan cancel untuk Request ID : "+request_id);

    if (alasan_cancel = null || alasan_cancel == "") {
        alert('Anda belum mengisi alasan !')
        return false;
    }
    if (alasan_cancel ==null || alasan_cancel=='') {
      window.location = '<?= base_url("h3/receive_part_request_nrfs/cancel_by_dealer?id=") ?>'+request_id+'&alasan_cancel='+alasan_cancel;
    }
    return false
}
</script>
  <?php } ?>
<?php if ($set=='tracking'): ?>
<link rel="stylesheet" href="assets/css-progress-wizard-master/css/progress-wizard.min.css">  
<table id="datatable_server" class="table table-bordered table-hover table-striped">
    <thead>
      <th>Request ID</th>              
      <th>Dokumen NRFS ID</th>
      <th>No Mesin</th>
      <th>Kode Tipe Unit</th>
      <th>Status</th>             
    </thead>
  </table>
<script>
$(document).ready(function(){  
  var dataTable = $('#datatable_server').DataTable({  
     "processing":true, 
     "serverSide":true, 
     "language": {                
          "infoFiltered": "",
      }, 
     "order":[],
     "lengthMenu": [[10, 25, 50,75,100], [10, 25, 50,75,100]],  
     "ajax":{  
          url:"<?php echo site_url('h3/receive_part_request_nrfs/fetch_tracking'); ?>",  
          type:"POST",
          dataSrc: "data",
          data: function ( d ) {
            // d.start_date = $('#start_date').val();
            // d.end_date = $('#end_date').val();
            return d;
          },
     },  
     "columnDefs":[  
          { "targets":[4],"orderable":false},
          { "targets":[4],"className":'text-center'}, 
          // { "targets":0,"checkboxes":{'selectRow':true}}
          // { "targets":[2],"className":'text-right'}, 
          // { "targets":[2,4,5], "searchable": false } 
     ],
    //  'select': {
    //    'style': 'multi'
    // },
  });

  });
</script>
<?php endif ?>

<?php if ($set=='form'):
$disabled = '';
$readonly = '';
$form     = '';
if ($mode=='insert') {
  $form ='save';
}
if ($mode=='edit') {
  $form = 'save_edit';
}
 ?>
<div class="box-header with-border">
  <div style="border-bottom: 1px solid #f4f4f4;margin-bottom: 10px">
    <a href="h3/receive_part_request_nrfs">
      <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
    </a>
  </div>
</div><!-- /.box-header -->
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script>
  Vue.use(VueNumeric.default);
  $(document).ready(function(){
    <?php if (isset($row)) { ?>
        pilihDokumen(<?= json_encode($row) ?>)
        form_.request_id = '<?= $row['request_id'] ?>'
    <?php } ?>
  })
</script>
<div class="row">
  <div class="col-md-12">
    <form id="form_" class="form-horizontal" action="h3/receive_part_request_nrfs/<?= $form ?>" method="post" enctype="multipart/form-data">
      <div class="box-body">     
        <div class="form-group">
          <label for="inputEmail3" class="col-sm-2 control-label">Dokumen NRFS ID</label>
          <div class="col-sm-4">
           <input type="text" class="form-control" id="dokumen_nrfs_id" name="dokumen_nrfs_id" v-model="dokumen_nrfs_id" readonly>
          </div>
<!--           <div class="col-sm-1">
            <button class="btn btn-primary btn-flat"><i class="fa fa-search"></i></button>
          </div> -->
          <div v-if="dokumen_nrfs_id != ''">
            <label for="inputEmail3" class="col-sm-2 control-label">ID Dealer</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" v-model="id_dealer" id="id_dealer" name="id_dealer" readonly>
            </div>
          </div>
        </div> 
        <div v-if="dokumen_nrfs_id != ''">
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Request ID</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" v-model="request_id" id="request_id" name="request_id" readonly>
            </div>
            <label for="inputEmail3" class="col-sm-2 control-label">No Shipping List</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" v-model="no_shiping_list" id="no_shiping_list" name="no_shiping_list" readonly>
            </div>
          </div> 
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Kode Tipe Unit</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" v-model="type_unit" id="type_unit" name="type_unit" readonly>
            </div>
            <label for="inputEmail3" class="col-sm-2 control-label">Nomor Rangka</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" v-model="no_rangka" id="no_rangka" name="no_rangka" readonly>
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Tipe Unit</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" v-model="deskripsi_unit" id="deskripsi_unit" name="deskripsi_unit" readonly>
            </div>
            <label for="inputEmail3" class="col-sm-2 control-label">Nomor Mesin</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" v-model="no_mesin" id="no_mesin" name="no_mesin" readonly>
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Warna</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" v-model="deskripsi_warna" id="deskripsi_warna" name="deskripsi_warna" readonly>
            </div>
            <label for="inputEmail3" class="col-sm-2 control-label">Sumber NRFS</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" v-model="sumber_rfs_nrfs" id="sumber_rfs_nrfs" name="sumber_rfs_nrfs" readonly>
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-offset-6 col-sm-2 control-label">Status Request</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" v-model="status_request" id="status_request" name="status_request" readonly>
            </div>
          </div> 
        </div>
        <button style="margin-bottom: 20px" class="btn btn-primary btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Detail Part</button>
          <table class="table table-bordered table-striped">
            <thead>
              <th width="30%">Nomor Parts</th>
              <th width="50%">Nama Parts</th>
              <th width="20%">Kuantitas Parts</th>
            </thead>
             <tbody>
              <tr v-for="(dtl, index) of details">
                <td>{{dtl.id_part}}</td>
                <td>{{dtl.nama_part}}</td>
                <td>{{dtl.qty_part}}</td>
              </tr>
            </tbody>
          </table>
          <hr>
            <div v-if="mode!='detail'" class="col-md-12" style="text-align: center;">
              <button type="button" class="btn btn-primary" @click.prevent="form_.saveForm()"><i class="fa fa-save"></i> Save</button>
            </div>
      </div>
    </form>
  </div>
</div>
<script>
   var form_ = new Vue({
      el: '#form_',
      data: {
        mode : '<?= $mode ?>',
        id_dealer:'',
        dokumen_nrfs_id:'',
        request_id:'',
        no_shiping_list:'',
        type_unit:'',
        no_rangka:'',
        no_mesin:'',
        deskripsi_warna:'',
        deskripsi_unit:'',
        sumber_rfs_nrfs:'',
        status_request:'',
        detail:{
          id_part : '',
          nama_part : '',
          qty_part :''
        },
        details : <?= isset($details)?json_encode($details):'[]' ?>,
      },
      methods: {
        getDetail: function() {
          values = {dokumen_nrfs_id:this.dokumen_nrfs_id}
          $.ajax({
            url:"<?php echo site_url('h3/receive_part_request_nrfs/getDetail');?>",
            type:"POST",
            data:values,
            cache:false,
            dataType:'JSON',
            success:function(response){
              form_.details=[];
              for(rsp of response)
              {
                form_.details.push(rsp);
              }
            }
          });
        },
        showModalDokumen : function() {
          // $('#tbl_part').DataTable().ajax.reload();
          $('.modalDokumen').modal('show');
        },
        saveForm:function(){
          if (this.dokumen_nrfs_id=='') {
            alert('Belum ada Dokumen yang dipilih !')
            return false;
          }
          if (this.details.length==0) {
            alert('Belum ada unit yang dipilih !');
            return false;
          }else{
            var val_confirm = confirm('Are you sure to save this data ?');
            if (val_confirm==false) {
              return false;
            }
          }
          var values ={dokumen_nrfs_id:this.dokumen_nrfs_id,request_id:this.request_id};
          // var values ={detail:form_.details};
          // var form = $('#form_').serializeArray();
          // for (field of form) {
          //   values[field.name] = field.value;
          // }
          // values['save_to'] = save_to;
          // values['po_type'] = '';
          // values['po_number'] = '';

          $.ajax({
            beforeSend: function() {
              $('.btnSubmit').attr('disabled',true);
              },
            url:"<?= base_url('h3/receive_part_request_nrfs/'.$form);?>",
            type:"POST",
            data: values,
            cache:false,
            dataType:'JSON',
            success:function(respon){
              if (respon.status=='sukses') {
               window.location = "<?= base_url('h3/receive_part_request_nrfs') ?>";
              }
            },
            error:function(){
              alert("failure");
              $('.btnSubmit').attr('disabled',false);

            },
            statusCode: {
              500: function() { 
                alert('fail');
                $('#submitBtn').attr('disabled',false);

              }
            }
          });
        },
      }
  });
function pilihDokumen(dok)
{
  console.log(dok)
  var doks = dok.dokumen;               
  form_.dokumen_nrfs_id = doks.dokumen_nrfs_id;
  form_.no_shiping_list = doks.no_shiping_list;
  form_.id_dealer       = doks.id_dealer;
  <?php if ($mode=='insert') {?>
    form_.request_id      = dok.request_id;
  <?php } ?>
  form_.no_rangka       = doks.no_rangka;
  form_.type_unit       = doks.type_code;
  form_.no_mesin        = doks.no_mesin;
  form_.deskripsi_warna = doks.deskripsi_warna;
  form_.deskripsi_unit  = doks.deskripsi_unit;
  form_.sumber_rfs_nrfs = doks.sumber_rfs_nrfs;
  form_.status_request  = doks.status_request;
  form_.getDetail();
}
Vue.component('date-picker',{
    template: '<input type="text" v-datepicker class="form-control isi_combo" :value="value" @input="update($event.target.value)">',
    directives: {
        datepicker: {
            inserted (el, binding, vNode) {
                $(el).datepicker({
                    autoclose: true,
                    format: 'yyyy-mm-dd',
                    todayHighlight:false,
                }).on('changeDate',function(e){
                    vNode.context.$emit('input', e.format(0))
                })
            }
        }
    },
    props: ['value'],
    methods: {
        update (v){
            this.$emit('input', v)
        }
    }
})

</script>

<?php endif ?>

</div><!-- /.box-body -->
</div><!-- /.box -->
  </section>
</div>