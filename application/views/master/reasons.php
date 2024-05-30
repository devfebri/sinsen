<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Master Data</li>
    <li class="">Reasons</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>

  <section class="content">
    <?php 
    if($set=="form"){
      $form     = '';
      $disabled = '';
      $readonly ='';
      if ($mode=='insert') {
        $form = 'save';
      }
      if ($mode=='edit') {
        $readonly ='readonly';
        $form = 'save_edit';
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
</script>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/reasons">
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
            <form  class="form-horizontal" id="form_" action="master/reasons/<?= $form ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Reasons</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" name="id_reasons" id="id_reasons" value="<?= isset($row)?$row->id_reasons:'Otomatis' ?>" autocomplete="off" <?= $disabled ?> readonly>
                  </div>   
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi</label>
                  <div class="col-sm-6">
                    <input type="text" required class="form-control" name="deskripsi" id="deskripsi" value="<?= isset($row)?$row->deskripsi:'' ?>" autocomplete="off" <?= $disabled ?>>
                  </div>   
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Fungsi</label>
                  <div class="col-sm-6">
                    <select name="fungsi" class="form-control" v-model="fungsi">
                      <option value="">--choose--</option>
                      <option value="Unit Sales">Unit Sales</option>
                      <option value="Stock Out">Stock Out</option>
                      <option value="Stock Opname">Stock Opname</option>
                      <option value="Part Sales">Part Sales</option>
                      <option value="Cancel Prospek">Cancel Prospek</option>
                    </select>
                  </div>   
                </div>                
             <div>
             
              <div class="box-footer" v-if="mode!='detail'">
                <div class="col-sm-12" v-if="mode=='insert'||mode=='edit'" align="center">
                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
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
        fungsi : '<?= isset($row)?$row->fungsi:'' ?>',
        dealer :{
          id_dealer:'',
          nama_dealer:''
        },
        dealers : <?= isset($dealers)?json_encode($dealers):'[]' ?>,
        budget :{
          kategori:'',
          nama:'',
          nominal:''
        },
        budgets : <?= isset($budgets)?json_encode($budgets):'[]' ?>,
        unit :{
          id_tipe_kendaraan:'',
          tipe_ahm:'',
          id_warna:'',
          warna:''
        },
        units : <?= isset($units)?json_encode($units):'[]' ?>,
        karyawan :{
          id_karyawan_dealer:'',
          nama_lengkap:'',
          jabatan:'',
        },
        karyawans : <?= isset($karyawans)?json_encode($karyawans):'[]' ?>,
        part :{
          id_part:'',
          nama_part:'',
          qty_part:'',
        },
        parts : <?= isset($parts)?json_encode($parts):'[]' ?>,
        job :{
          id_jasa_servis:'',
          nama_jasa:'',
        },
        jobs : <?= isset($jobs)?json_encode($jobs):'[]' ?>,
      },
    methods: {
      clearDealers: function () {
      this.dealer = {
              id_dealer:'',
              nama_dealer:''
        }
      },
      addDealers : function(){
        if (this.dealers.length > 0) {
          for (dl of this.dealers) {
            if (dl.id_dealer === this.dealer.id_dealer) {
                alert("Dealer Sudah Dipilih !");
                this.clearDealers();
                return;
            }
          }
        }
        if (this.dealer.id_dealer=='') 
        {
          alert('Pilih Dealer !');
          return false;
        }
        this.dealers.push(this.dealer);
        this.clearDealers();
      },

      delDealers: function(index){
          this.dealers.splice(index, 1);
      },
      getDealer: function(){
        var el   = $('#dealer').find('option:selected'); 
        var id_dealer    = el.attr("id_dealer"); 
        form_.dealer.id_dealer = id_dealer;
      },

      clearBudgets: function () {
        this.budget ={
          kategori:'',
          nama:'',
          nominal:''
        }
      },
      addBudgets : function(){
        if (this.budget.kategori=='') 
        {
          alert('Pilih Kategori !');
          return false;
        }
        this.budgets.push(this.budget);
        this.clearBudgets();
      },

      delBudgets: function(index){
          this.budgets.splice(index, 1);
      },

      clearUnits: function () {
        $('#id_tipe_kendaraan').val('').trigger('change');
        $('#id_warna').val('').trigger('change');
        this.unit ={
          id_tipe_kendaraan:'',
          tipe_ahm:'',
          id_warna:'',
          warna:''
        }
      },
      addUnits : function(){
        var el             = $('#id_warna').find('option:selected'); 
        var warna          = el.attr("warna");
        this.unit.warna    = warna; 
        this.unit.id_warna = $('#id_warna').val(); 
        // if (this.budget.kategori=='') 
        // {
        //   alert('Pilih Kategori !');
        //   return false;
        // }
        this.units.push(this.unit);
        this.clearUnits();
      },

      delUnits: function(index){
          this.units.splice(index, 1);
      },
      getWarna: function() {
          var element   = $('#id_tipe_kendaraan').find('option:selected'); 
          var id_tipe_kendaraan = $('#id_tipe_kendaraan').val();
          if (id_tipe_kendaraan=='' || id_tipe_kendaraan==null) {
            $('#id_warna').html('');
            return false;
          }
          var warnas    = JSON.parse(element.attr("data-warna")); 
          var tipe_ahm = element.attr("data-tipe_unit");
          form_.unit.tipe_ahm = tipe_ahm; 
          form_.unit.id_tipe_kendaraan = $('#id_tipe_kendaraan').val(); 
          $('#id_warna').html('');
            if (warnas.length>0) {
              $('#id_warna').append($('<option>').text('--choose--').attr('value', ''));
            }
          $.each(warnas, function(i, value) {
            $('#id_warna').append($('<option>').text(warnas[i].id_warna+' | '+warnas[i].warna).attr({'value':warnas[i].id_warna,'warna':warnas[i].warna}));

          });
        },

      clearKaryawan: function () {
        $('#karyawan').val('').trigger('change');
        this.karyawan ={
          id_karyawan_dealer:'',
          nama_lengkap:'',
          jabatan:'',
        }
      },
      addKaryawans : function(){
        this.karyawans.push(this.karyawan);
        this.clearKaryawan();
      },

      delKaryawans: function(index){
          this.karyawans.splice(index, 1);
      },
      getJabatan: function () {
        var id_karyawan_dealer = $("#karyawan").select2().find(":selected").data("id_karyawan_dealer");
        var nama_lengkap       = $("#karyawan").select2().find(":selected").data("nama_lengkap");
        var jabatan            = $("#karyawan").select2().find(":selected").data("jabatan");
        this.karyawan={'id_karyawan_dealer':id_karyawan_dealer,
                       'nama_lengkap':nama_lengkap,
                       'jabatan':jabatan,
        }
      },
      showModalPart : function() {
        // $('#tbl_part').DataTable().ajax.reload();
        $('.modalPart').modal('show');
      },
      clearPart: function () {
        this.part ={
          id_part:'',
          nama_part:'',
          qty_part:'',
        }
      },
      addParts : function(){
        this.parts.push(this.part);
        this.clearPart();
      },

      delParts: function(index){
          this.parts.splice(index, 1);
      },

     
      clearJob: function () {
        $('#job').val('').trigger('change');

        this.job ={
          id_jasa_servis:'',
          nama_jasa:''
        }
      },
      addJobs : function(){
        var id_jasa_servis            = $("#job").select2().find(":selected").data("id_jasa_servis");
        this.job.id_jasa_servis = id_jasa_servis; 
        this.job.nama_jasa      = $('#job').val(); 

        this.jobs.push(this.job);
        this.clearJob();
      },
      delJobs: function(index){
          this.jobs.splice(index, 1);
      },
      // getJabatan: function () {
      //   var id_karyawan_dealer = $("#karyawan").select2().find(":selected").data("id_karyawan_dealer");
      //   var nama_lengkap       = $("#karyawan").select2().find(":selected").data("nama_lengkap");
      //   var jabatan            = $("#karyawan").select2().find(":selected").data("jabatan");
      //   this.karyawan={'id_karyawan_dealer':id_karyawan_dealer,
      //                  'nama_lengkap':nama_lengkap,
      //                  'jabatan':jabatan,
      //   }
      // },
    },
  });
  function pilihPart(part)
  {
    form_.part = {id_part:part.id_part,nama_part:part.nama_part}
  }
function submitBtn() {
  var values = {dealers:form_.dealers,
                budgets:form_.budgets,
                units:form_.units,
                parts:form_.parts,
                jobs:form_.jobs,
                karyawans:form_.karyawans,
  };
  var form   = $('#form_').serializeArray();
  for (field of form) {
    values[field.name] = field.value;
  }
  $.ajax({
    beforeSend: function() {
      $('#submitBtn').attr('disabled',true);
    },
    url:'<?= base_url('master/reasons/'.$form) ?>',
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
}
</script>
    <?php
    }elseif($set=="index"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/reasons/add">
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>reasons ID</th>
              <th>Deskripsi</th>
              <th>Fungsi</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reasons->result() as $rs): 
              $status='';$button='';
              $btn_edit ='<a data-toggle=\'tooltip\' title="Edit Data" class=\'btn btn-warning btn-xs btn-flat\' href=\'master/reasons/edit?id='.$rs->id_reasons.'\'><i class=\'fa fa-edit\'></i></a>';
              $btn_delete ='<a onclick="return confirm(\'Are you sure to delete this data ?\')" data-toggle=\'tooltip\' title="Delete Data" class=\'btn btn-danger btn-xs btn-flat\' href=\'master/reasons/delete?id='.$rs->id_reasons.'\'><i class=\'fa fa-trash\'></i></a>';
              $button = $btn_edit.' '.$btn_delete;
              // if ($rs->status=='waiting_approval') {
              //   $status = '<label class="label label-warning">Waiting Approval</label>';
              //   $button = $btn_approve.' '.$btn_reject;
              // }
              // if ($rs->status=='approved') {
              //   $status = '<label class="label label-success">Approved</label>';
              // }
              //  if ($rs->status=='rejected') {
              //   $status = '<label class="label label-danger">Rejected</label>';
              // }
            ?>
              <tr>
                <td><?= $rs->id_reasons ?></td>
                <td><?= $rs->deskripsi ?></td>
                <td><?= $rs->fungsi ?></td>
                <td align="center">
                  <?= $button ?>
                </td>
              </tr>
            <?php endforeach ?>
          </tbody>
          
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
<script>

  function closePrompt(kode_reasons,id_reasons) {

    var alasan_reject = prompt("Alasan melakukan reject untuk Kode reasons : "+kode_reasons);

    if (alasan_reject != null || alasan_reject == "") {

       window.location = '<?= base_url("master/reasons/reject_save?id=") ?>'+id_reasons+'&alasan_reject='+alasan_reject;

        return false;

    }

    return false

  }

</script>
    <?php
    }
    ?>
  </section>
</div>