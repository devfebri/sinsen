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
    <li class="">Diskon</li>
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
        // $readonly ='readonly';
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
  $(document).ready(function(){
  })
</script>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/diskon">
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
            <form  class="form-horizontal" id="form_" action="dealer/diskon/<?= $form ?>" method="post" enctype="multipart/form-data">
              <?php if (isset($row)): ?>
                <input type="hidden" id="id_diskon" name="id_diskon" value="<?= $row->id_diskon ?>">
              <?php endif ?>
              <div class="box-body">
                <div class="form-group">
                   <!-- <label for="inputEmail3" class="col-sm-2 control-label">Kode Event</label> -->
                  <div class="col-sm-4">
                    <input type="hidden" required class="form-control" placeholder="Otomatis" name="id_diskon" id="id_diskon" readonly value="<?= isset($row)?$row->id_diskon:'' ?>">
                  </div>
                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Diskon</label>
                  <div class="col-sm-4">
                     <input type="text" required class="form-control" value="<?= isset($row)?$row->id_diskon:'Otomatis Setalah Save' ?>" autocomplete="off" readonly> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jatah Approval</label>
                  <div class="col-sm-4">
                    <select name="jatah_approval" class="form-control" v-model="jatah_approval">
                      <option value="">--choose--</option>
                      <option value="Y">Ada</option>
                      <option value="T">Tidak</option>
                    </select>
                  </div>                                                                       
                </div> 
                <div class="form-group">
                   <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control datepicker" name="start_date" id="start_date" value="<?= isset($row)?$row->start_date:'' ?>" autocomplete="off" <?= $disabled ?>>
                  </div>
                  <div v-if="jatah_approval=='Y'">
                    <label for="inputEmail3" class="col-sm-2 control-label">Banyak Jatah</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" name="byk_jatah" v-model="byk_jatah" autocomplete="off">
                    </div>   
                  </div>
                  <div v-if="jatah_approval=='T'">
                    <label for="inputEmail3" class="col-sm-2 control-label">Banyak Jatah</label>
                    <div class="col-sm-4">
                      <select name="byk_jatah" class="form-control" v-model="byk_jatah">
                        <option value="">--choose--</option>
                        <option value="Tidak Ada Diskon">Tidak Ada Diskon</option>
                        <option value="Diskon Unlimited">Diskon Unlimited</option>
                      </select>
                    </div> 
                  </div>
                </div>
                <div class="form-group">
                   <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control datepicker" name="end_date" id="end_date" value="<?= isset($row)?$row->end_date:'' ?>" autocomplete="off" <?= $disabled ?>>
                  </div>
                 <label for="inputEmail3" class="col-sm-2 control-label">Tipe Diskon</label>
                  <div class="col-sm-4">
                    <select name="tipe_diskon" class="form-control" v-model="tipe_diskon">
                      <option value="">--choose--</option>
                      <option value="Ammount">Ammount</option>
                      <option value="Percentage">Percentage</option>
                    </select>
                  </div>                                                                          
                </div> 
                <div class="form-group">
                   <label for="inputEmail3" class="col-md-offset-6 col-sm-2 control-label">Value</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" name="value" id="value" value="<?= isset($row)?$row->value:'' ?>" autocomplete="off" <?= $disabled ?>>
                  </div>                                                                            
                </div>             
              </div><!-- /.box-body -->
             <div>
             
              <div class="col-md-12">
                <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Detail Kendaraan</button><br><br>
              </div>
              <div class="col-md-12">
                <table class="table table-bordered">
                  <thead>
                    <th>Tipe</th>
                    <th>Warna</th>
                    <th width="9%" style="text-align: center;" v-if="mode=='insert'||mode=='edit'">Aksi</th>
                  </thead>
                  <tbody>
                    <tr v-for="(unt, index) of units">
                      <td>{{unt.id_tipe_kendaraan}} | {{unt.tipe_ahm}}</td>
                      <td>{{unt.id_warna}} | {{unt.warna}}</td>
                      <td align="center" v-if="mode=='insert'||mode=='edit'">
                        <button type="button" @click.prevent="delUnits(index)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                      </td>
                    </tr>
                  </tbody>
                  <tfoot v-if="mode=='insert' || mode=='edit'">
                    <tr>
                      <td style="width: 50%">
                        <select id="id_tipe_kendaraan" class="form-control select2" onchange="form_.getWarna()">
                          <?php if ($tipe_unit->num_rows()>0): ?>
                            <option value="">--choose--</option>
                            <?php foreach ($tipe_unit->result() as $tu):
                                $warna = $this->db->query("SELECT ms_warna.id_warna,ms_warna.warna from ms_item 
                                          inner join ms_warna on ms_item.id_warna =ms_warna.id_warna
                                          WHERE id_tipe_kendaraan='$tu->id_tipe_kendaraan'
                                          GROUP BY ms_item.id_warna
                                          ORDER BY ms_warna.warna ASC")->result()
                            ?>
                              <option value="<?= $tu->id_tipe_kendaraan ?>" data-tipe_unit="<?= $tu->tipe_ahm ?>" data-warna='<?= json_encode($warna) ?>'><?= $tu->id_tipe_kendaraan.' | '.$tu->tipe_ahm ?></option>
                            <?php endforeach ?>
                          <?php endif ?>
                        </select>
                      </td>
                      <td><select class="form-control select2" id="id_warna" style="width: 100%"></select></td>
                      <td align="center">
                        <button type="button" @click.prevent="addUnits()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i></button> 
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <div class="col-md-12">
                <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Assignment People</button><br><br>
              </div>
              <div class="col-md-12">
                <table class="table table-bordered">
                  <thead>
                    <th>Sales People</th>
                    <th>Jabatan</th>
                    <th width="9%" style="text-align: center;" v-if="mode=='insert'||mode=='edit'">Aksi</th>
                  </thead>
                  <tbody>
                    <tr v-for="(dt, index) of karyawans">
                      <td>{{dt.nama_lengkap}}</td>
                      <td>{{dt.jabatan}}</td>
                      <td align="center" v-if="mode=='insert'|| mode=='edit'">
                        <button type="button" @click.prevent="delKaryawans(index)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                      </td>
                    </tr>
                  </tbody>
                  <tfoot v-if="mode=='insert' || mode=='edit'">
                    <tr>
                      <td style="width: 50%">
                        <select id="karyawan" class="form-control select2" onchange="form_.getJabatan()">
                          <?php
                            $karyawan = $this->db->query("SELECT * FROM ms_karyawan_dealer LEFT JOIN ms_jabatan ON ms_jabatan.id_jabatan=ms_karyawan_dealer.id_jabatan");
                           if ($karyawan->num_rows()>0): ?>
                            <option value="">--choose--</option>
                            <?php foreach ($karyawan->result() as $tu): ?>
                              <option value="<?= $tu->nama_lengkap ?>" 
                                  data-id_karyawan_dealer="<?= $tu->id_karyawan_dealer ?>" 
                                  data-nama_lengkap='<?= $tu->nama_lengkap ?>'
                                  data-jabatan="<?= $tu->jabatan ?>"
                              >
                                  <?= $tu->id_karyawan_dealer.' | '.$tu->nama_lengkap.' | '.$tu->jabatan ?>
                              </option>
                            <?php endforeach ?>
                          <?php endif ?>
                        </select>
                      </td>
                      <td><input type="text" v-model="karyawan.jabatan" class="form-control" disabled></td>
                      <td align="center">
                        <button type="button" @click.prevent="addKaryawans()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i></button> 
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
                            
              <div class="box-footer" v-if="mode!='detail'">
                <div class="col-sm-12" v-if="mode=='insert'||mode=='edit'" align="center">
                  <button type="button" id="submitBtn" onclick="funcSubmit()" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
<div class="modal fade modalPart" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Part</h4>
      </div>
      <div class="modal-body">
        <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_part" style="width: 100%">
                  <thead>
                  <tr>
                      <th>ID Part</th>
                      <th>Nama Part</th>
                      <th>Kelompok Vendor</th>
                      <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>
              <script>
                  $(document).ready(function(){
                      $('#tbl_part').DataTable({
                          processing: true,
                          serverSide: true,
                          "language": {                
                                  "infoFiltered": ""
                              },
                          order: [],
                          ajax: {
                              url: "<?= base_url('master/kpb/fetch_part') ?>",
                              dataSrc: "data",
                              data: function ( d ) {
                                    // d.kode_item     = $('#kode_item').val();
                                    return d;
                                },
                              type: "POST"
                          },
                          "columnDefs":[  
                      // { "targets":[4],"orderable":false},
                      { "targets":[3],"className":'text-center'}, 
                      // { "targets":[4], "searchable": false } 
                 ]
                      });
                  });
                  // function loads()
                  // {
                  //   alert('d');
                  //     $('#tabel_harga_sebelumnya').DataTable().ajax.reload();
                  // }
              </script>
      </div>
    </div>
  </div>
</div>
<script>
function funcSubmit(){
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
  if ($('#form_').valid()) // check if form is valid
  {
    $.ajax({
      beforeSend: function() {
        $('#submitBtn').attr('disabled',true);
      },
      url:'<?= base_url('dealer/diskon/'.$form) ?>',
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
   }else{
    alert('Silahkan isi field required !')
  }
}
  var form_ = new Vue({
      el: '#form_',
      data: {
        mode : '<?= $mode ?>',
        jatah_approval : '<?= isset($row)?$row->jatah_approval:'' ?>',
        tipe_diskon : '<?= isset($row)?$row->tipe_diskon:'' ?>',
        byk_jatah : '<?= isset($row)?$row->byk_jatah:'' ?>',
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

</script>
    <?php
    }elseif($set=="index"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/diskon/add">
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
              <th>ID Diskon</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Tipe Diskon</th>
              <th>Value</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($event->result() as $rs): 
              $status='';$button='';
              $btn_edit ='<a data-toggle=\'tooltip\' title="Edit Data" class=\'btn btn-warning btn-xs btn-flat\' href=\'dealer/diskon/edit?id='.$rs->id_diskon.'\'><i class=\'fa fa-edit\'></i></a>';
              // $btn_view ='<a data-toggle=\'tooltip\' title="View Detail" class=\'btn btn-primary btn-xs btn-flat\' href=\'dealer/diskon/edit?id='.$rs->id_diskon.'\'><i class=\'fa fa-eye\'></i></a>';
              $button = $btn_edit;
              // if ($rs->status=='waiting_approval') {
              //   $status = '<label class="label label-warning">Waiting Approval</label>';
              //   // $button = $btn_edit.' '.$btn_approve.' '.$btn_reject;
              // }
              // if ($rs->status=='approved') {
              //   $status = '<label class="label label-success">Approved</label>';
              // }
              //  if ($rs->status=='rejected') {
              //   $status = '<label class="label label-danger">Rejected</label>';
              // }
            ?>
              <tr>
                <td><a href="<?= base_url('dealer/diskon/detail?id='.$rs->id_diskon) ?>"><?= $rs->id_diskon ?></a></td>
                <td><?= $rs->start_date ?></td>
                <td><?= $rs->end_date ?></td>
                <td><?= $rs->tipe_diskon ?></td>
                <td align="right"><?= $rs->value ?></td>
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

  function closePrompt(kode_event,id_event) {

    var alasan_reject = prompt("Alasan melakukan reject untuk Kode Event : "+kode_event);

    if (alasan_reject != null || alasan_reject == "") {

       window.location = '<?= base_url("dealer/diskon/reject_save?id=") ?>'+id_event+'&alasan_reject='+alasan_reject;

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