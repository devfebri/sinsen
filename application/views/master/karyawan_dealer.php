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
    <li class="">Dealer</li>    
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="form"){
      $form     = '';
      $readonly = '';
      $disabled = '';
      if ($mode=='insert') {
        $form = 'save';
      }
      if ($mode=='edit') {
        $form = 'save_edit';
      }
      if ($mode=='detail') {
        $disabled='disabled';
      }
    ?>

<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/karyawan_dealer">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
            <form id="form_" method="POST" role="form" enctype="multipart/form-data" action="master/karyawan_dealer/<?= $form ?>" class="form-horizontal form-groups-bordered">
              <div class="box-body">                                                                                                                                                    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">ID Karyawan *</label>            
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="id_karyawan_dealer" placeholder="ID Karyawan" name="id_karyawan_dealer" required value="<?= isset($row)?$row->id_karyawan_dealer:'' ?>" <?= $disabled ?>>
                    <input type="hidden" onkeypress="return number_only(event)" class="form-control" id="id_karyawan_dealer_old" placeholder="ID Karyawan" name="id_karyawan_dealer_old" required value="<?= isset($row)?$row->id_karyawan_dealer:'' ?>" <?= $disabled ?>>
                  </div>
                 </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Honda ID</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="honda_id" placeholder="Honda ID" name="honda_id" value="<?= isset($row)?$row->honda_id:'' ?>" <?= $disabled ?>>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Dealer *</label>           
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer" id="id_dealer" onchange="cari_dealer()" required <?= $disabled ?>>
                      <option value="">- choose -</option>   
                      <?php 
                      foreach($dt_dealer->result() as $val) {
                         $select = isset($row)?$row->id_dealer==$val->id_dealer?'selected':'':'';
                        echo "
                        <option value='$val->id_dealer' $select>$val->kode_dealer_md - $val->nama_dealer</option>;
                        ";
                      }
                      ?>                 
                    </select>
                  </div>
                  <!-- <label for="field-1" class="col-sm-2 control-label">POS Dealer</label>           
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_pos_dealer" id="id_pos_dealer" <?= $disabled ?>>
                      <option value="">- choose -</option>               
                    </select>
                  </div> -->
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Nama Lengkap *</label>            
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="field-1" placeholder="Nama Lengkap" name="nama_lengkap" required value="<?= isset($row)?$row->nama_lengkap:'' ?>" <?= $disabled ?>>
                  </div>                   
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">NIK *</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="field-1" placeholder="NIK" name="nik" required  value="<?= isset($row)?$row->nik:'' ?>" <?= $disabled ?>>
                  </div>
                   <label for="field-1" class="col-sm-2 control-label">ID FLP MD</label>            
                  <div class="col-sm-4">
                    <input type="text"  class="form-control" id="field-1" placeholder="ID FLP MD" name="id_flp_md"  value="<?= isset($row)?$row->id_flp_md:'' ?>" <?= $disabled ?>>
                  </div>
                </div>
                                
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Divisi</label>           
                  <div class="col-sm-4">
                    <select class="form-control" name="id_divisi" <?= $disabled ?>>
                      <option value="">- choose -</option>   
                      <?php 
                      foreach($dt_divisi->result() as $val) {
                        $select = isset($row)?$row->id_divisi==$val->id_divisi?'selected':'':'';
                        echo "
                        <option value='$val->id_divisi' $select>$val->divisi</option>;
                        ";
                      }
                      ?>                 
                    </select>
                  </div>

                  <label for="field-1" class="col-sm-2 control-label">Jabatan *</label>            
                  <div class="col-sm-4">
                    <select class="form-control" required name="id_jabatan" <?= $disabled ?>>
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_jabatan->result() as $val) {
                        $select = isset($row)?$row->id_jabatan==$val->id_jabatan?'selected':'':'';
                        echo "
                        <option value='$val->id_jabatan' $select >$val->jabatan</option>;
                        ";
                      }
                      ?>                      
                    </select>
                  </div>
                </div>    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Tempat/Tgl.Lahir *</label>            
                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="field-1" placeholder="Tempat Lahir" name="tempat_lahir" required value="<?= isset($row)?$row->tempat_lahir:'' ?>" <?= $disabled ?>>
                  </div>                   
                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="tanggal" placeholder="Tgl.Lahir" required name="tgl_lahir"  value="<?= isset($row)?$row->tgl_lahir:'' ?>" <?= $disabled ?>>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Jenis Kelamin *</label>            
                  <div class="col-sm-4">
                    <select class="form-control" required name="jk" <?= $disabled ?>>
                      <option value="">- choose -</option>
                      <option <?= isset($row)?$row->jk=='Laki-laki'?'selected':'':'' ?>>Laki-laki</option>
                      <option <?= isset($row)?$row->jk=='Perempuan'?'selected':'':'' ?>>Perempuan</option>
                    </select>
                  </div>                   
                  <label for="field-1" class="col-sm-2 control-label">Agama *</label>            
                  <div class="col-sm-4">
                    <select class="form-control" required name="id_agama" <?= $disabled ?>>
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_agama->result() as $val) {
                        $select = isset($row)?$row->id_agama==$val->id_agama?'selected':'':'';
                        echo "
                        <option value='$val->id_agama' $select>$val->agama</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">No.Telp</label>            
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="field-1" placeholder="No.Telp" name="no_telp"  value="<?= isset($row)?$row->no_telp:'' ?>" <?= $disabled ?>>
                  </div>
                   <label for="field-1" class="col-sm-2 control-label">No.HP *</label>            
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="field-1" placeholder="No.HP" name="no_hp"  value="<?= isset($row)?$row->no_hp:'' ?>" <?= $disabled ?> required>
                  </div>
                </div>
                <div class="form-group">                  
                   <label for="field-1" class="col-sm-2 control-label">Email</label>            
                  <div class="col-sm-4">
                    <input type="email" class="form-control" id="field-1" placeholder="Email" name="email"  value="<?= isset($row)?$row->email:'' ?>" <?= $disabled ?>>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Alamat *</label>            
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="field-1" placeholder="Alamat lengkap" name="alamat"  value="<?= isset($row)?$row->alamat:'' ?>" <?= $disabled ?> required>                    
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Tgl.Masuk *</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanggal2" placeholder="Tgl.Masuk" name="tgl_masuk"  value="<?= isset($row)?$row->tgl_masuk:'' ?>" <?= $disabled ?> required>
                  </div>                   
                  <label for="field-1" class="col-sm-2 control-label">Tgl.Keluar</label>                              
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanggal3" placeholder="Tgl.Keluar" name="tgl_keluar" value="<?= isset($row)?$row->tgl_keluar:'' ?>" <?= $disabled ?>>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Alasan Keluar</label>            
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Alasan Keluar" name="alasan_keluar"  value="<?= isset($row)?$row->alasan_keluar:'' ?>" <?= $disabled ?>>
                  </div>                                     
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input <?= $disabled ?> type="checkbox" class="form-control flat-red" name="active" value="1" <?= isset($row)?$row->active==1?'checked':'':'checked' ?>>
                      Active
                    </div>
                  </div>                  
                </div>
<br>
<button class="btn btn-primary btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled style="font-size: 12pt">Riwayat Pekerjaan</button>
<div class="form-group" style="padding-top: 43px">
  <div class="col-md-12">
    <table class="table table-bordered">
    <thead>
      <th>Dealer</th>
      <th>Tgl. Masuk</th>
      <th>Tgl. Keluar</th>
      <th v-if="mode=='insert'||mode=='edit'">Action</th>
    </thead>
    <tbody>
      <tr v-for="(rt, index) of riwayats">
        <td>{{rt.kode_dealer_md}} | {{rt.nama_dealer}}</td>
        <td>{{rt.tgl_masuk}}</td>
        <td>{{rt.tgl_keluar}}</td>
        <td align="center" v-if="mode=='insert'||mode=='edit'" style="text-align: center;vertical-align: middle;"> 
          <button type="button" @click.prevent="delRiwayats(index)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>            
        </td>
      </tr>
    </tbody>
    <tfoot v-if="mode=='insert'||mode=='edit'">
      <tr>
        <td>
          <select class="form-control select2" id="get_id_dealer" onchange="setDealer()">
            <option value="">- choose -</option>
            <?php 
            foreach($dt_dealer->result() as $val) { ?>
              echo "
              <option value='<?= $val->id_dealer ?>' 
                data-nama_dealer    = "<?= $val->nama_dealer ?>"
                data-kode_dealer_md = "<?= $val->kode_dealer_md ?>"
              >
                <?= $val->kode_dealer_md ?> | <?= $val->nama_dealer ?></option>
            <?php }
            ?>                      
          </select>
        </td>
        <td>
          <input type="text" class="datepicker form-control" id="tgl_masuk" onchange="setTgl('tgl_masuk')">
        </td>
        <td>
          <input type="text" class="datepicker form-control" id="tgl_keluar" onchange="setTgl('tgl_keluar')">
        </td>
        <td align="center">
          <button  type="button" class="btn btn-primary btn-flat btn-sm"  data-toggle="tooltip" data-placement="top" title="Add" @click.prevent="addRiwayat"><i class="fa fa-plus"></i></button>
        </td>
      </tr>
    </tfoot>
  </table>
  </div>
</div>
<button class="btn btn-primary btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled style="font-size: 12pt;">Riwayat Training</button>
<div class="form-group" style="padding-top: 43px">
  <div class="col-md-12">
    <table class="table table-bordered">
    <thead>
      <th>Training</th>
      <th>Tgl. Training</th>
      <th>No. Sertifikat</th>
      <th>Nilai</th>
      <th>Keterangan</th>
      <th v-if="mode=='insert'||mode=='edit'">Action</th>
    </thead>
    <tbody>
      <tr v-for="(trn, index) of trainings">
        <td>{{trn.training}}</td>
        <td>{{trn.tgl_training}}</td>
        <td>{{trn.no_sertifikat}}</td>
        <td>{{trn.nilai}}</td>
        <td>{{trn.keterangan}}</td>
        <td align="center" v-if="mode=='insert'||mode=='edit'" style="text-align: center;vertical-align: middle;"> 
          <button type="button" @click.prevent="delTrainings(index)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>            
        </td>
      </tr>
    </tbody>
    <tfoot v-if="mode=='insert'||mode=='edit'">
      <tr>
        <td>
          <select class="form-control select2" id="id_training" onchange="setTraining()">
            <option value="">- choose -</option>
            <?php 
            foreach($dt_training->result() as $val) { ?>
              echo "
              <option value='<?= $val->id_training ?>' 
                data-training    = "<?= $val->training ?>"
              >
              <?= $val->training ?></option>
            <?php }
            ?>                      
          </select>
        </td>
        <td>
          <input type="text" class="datepicker form-control" id="tgl_training" onchange="setTglTraining()">
        </td>
        <td>
          <input type="text" v-model="training.no_sertifikat" class="form-control">
        </td>
         <td>
          <input type="text" v-model="training.nilai" class="form-control">
        </td>
         <td>
          <input type="text" v-model="training.keterangan" class="form-control">
        </td>
        <td align="center">
          <button  type="button" class="btn btn-primary btn-flat btn-sm"  data-toggle="tooltip" data-placement="top" title="Add" @click.prevent="addTrainings"><i class="fa fa-plus"></i></button>
        </td>
      </tr>
    </tfoot>
  </table>
  </div>
</div>
              

          <div class="box-footer" v-if="mode=='insert'||mode=='edit'">
            <div class="col-sm-12" align="center">
              <button id="submitBtn" type="button" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
          <!--     <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>      -->           
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
    riwayat :{},
    riwayats : <?= isset($riwayats)?json_encode($riwayats):'[]' ?>,
    training :{},
    trainings : <?= isset($trainings)?json_encode($trainings):'[]' ?>,
  },   
  methods:{
    clearRiwayat : function () {
       this.riwayat ={}
    },
    addRiwayat : function(){
      this.riwayats.push(this.riwayat);
      this.clearRiwayat();
      $('#get_id_dealer').val('').trigger('change'); ;
      $('#tgl_masuk').val('');
      $('#tgl_keluar').val('');
    },
    delRiwayats: function(index){
        this.riwayats.splice(index, 1);
    },
    clearTraining : function () {
       this.training ={}
    },
    addTrainings : function(){
      this.trainings.push(this.training);
      this.clearTraining();
      $('#id_training').val('').trigger('change'); ;
      $('#tgl_training').val('');
    },
    delTrainings: function(index){
        this.trainings.splice(index, 1);
    },
  }
})
function setTgl(el) {
  let tgl = $('#'+el).val();
  form_.riwayat[el]=tgl
  // console.log(form_.riwayat); 
}
function setTglTraining() {
  form_.training.tgl_training=$('#tgl_training').val();
  // console.log(form_.riwayat); 
}
function setDealer() {
 let id_dealer = $('#get_id_dealer').val();
 let nama_dealer = $("#get_id_dealer").select2().find(":selected").data("nama_dealer");
 let kode_dealer_md = $("#get_id_dealer").select2().find(":selected").data("kode_dealer_md");

 form_.riwayat.id_dealer      = id_dealer;
 form_.riwayat.nama_dealer    = nama_dealer;
 form_.riwayat.kode_dealer_md = kode_dealer_md;
}
function setTraining() {
 let id_training = $('#id_training').val(); 
 let training    = $("#id_training").select2().find(":selected").data("training");
 
 form_.training.training     = training;
 form_.training.id_training = id_training;
}

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
  var values = {riwayats:form_.riwayats,trainings:form_.trainings};
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
        url:'<?= base_url('master/karyawan_dealer/'.$form) ?>',
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
          <a href="master/karyawan_dealer/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>  
	  <a href="#" data-toggle="modal" data-target="#importKaryawan" class="btn bg-green btn-flat margin"><i class="fa fa-upload"></i> Import Data</a>         
        
	<!-- Modal -->
          <div id="importKaryawan" class="modal fade" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header alert-success">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Import Data Karyawan</h4>
                </div>
                <div class="modal-body">
                  <p style="text-align: right;">
                    <a href="uploads/template_import_karyawan.xlsx" target="_blank" class="btn btn-info btn-sm">Download Template</a>
                  </p>
                  <form action="master/karyawan_dealer/proses_import" method="POST" enctype="multipart/form-data">

                    <div class="form-group">
                      <label for="field-1" class="col-sm-4 control-label">Upload File</label>            
                      <div class="col-sm-6">
                        <input type="file" class="form-control" id="importFile" name="import_file">
                      </div>                   
                      
                    </div>

                  
                  <br><br>

                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Upload</button>
                  </form>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>

            </div>
          </div>  

	<!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  
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
        <table id="datatable_server" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID Karyawan</th>
              <th>ID FLP</th>             
              <th>NIK</th>
              <th>Nama Lengkap</th>              
              <th>Dealer</th>
              <th>Divisi</th>
              <th>Jabatan</th>
              <th>No.Telp</th>
              <th>Status</th>                                        
              <th width="13%">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
<script>
   $(document).ready(function(){  
      var dataTable = $('#datatable_server').DataTable({  
         "processing":true, 
         "serverSide":true, 
         "scrollX":true,
         "language": {                
              "infoFiltered": "",
              "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
          }, 
         "order":[],
         "lengthMenu": [[10, 25, 50,75,100], [10, 25, 50,75,100]],  
         "ajax":{  
              url:"<?php echo site_url('master/karyawan_dealer/fetch'); ?>",  
              type:"POST",
              dataSrc: "data",
              data: function (d) {
                  return d;
              },
         },  
         "columnDefs":[  
              { "targets":[9],"orderable":false},
              // { "targets":[9],"className":'text-center'}, 
              // // { "targets":[0],"checkboxes":{'selectRow':true}}
              // { "targets":[9,10,11,12,13],"className":'text-right'}, 
         ],
      });
    });
</script>
    <?php
    }
    ?>    
  </section>
</div>



<script type="text/javascript">
function hide_kerja(){
    $("#tampil_kerja").hide();
}
function kirim_data_kerja(){    
  $("#tampil_kerja").show();
  var id_karyawan_dealer = document.getElementById("id_karyawan_dealer").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_karyawan_dealer="+id_karyawan_dealer;                           
     xhr.open("POST", "master/karyawan_dealer/t_kerja", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_kerja").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function simpan_kerja(){
    var id_dealer           = document.getElementById("id_dealer").value;   
    var tgl_masuk           = document.getElementById("tanggal4").value;   
    var tgl_keluar          = document.getElementById("tanggal5").value;   
    var id_karyawan_dealer  = $("#id_karyawan_dealer").val();            
    //alert(id_dealer);
    if (id_dealer == "" || id_karyawan_dealer == "") {    
        alert("Isikan data dengan lengkap...!");
        return false;
    }else{
        $.ajax({
            url : "<?php echo site_url('master/karyawan_dealer/save_kerja')?>",
            type:"POST",
            data:"id_dealer="+id_dealer+"&id_karyawan_dealer="+id_karyawan_dealer+"&tgl_masuk="+tgl_masuk+"&tgl_keluar="+tgl_keluar,
            cache:false,
            success:function(msg){            
                data=msg.split("|");
                if(data[0]=="nihil"){
                    kirim_data_kerja();
                    kosong();                
                }else{
                    alert('Dealer ini sudah ditambahkan');
                    kosong();                      
                }                
            }
        })    
    }
}
function kosong(args){
  $("#id_dealer").val("");
  $("#tanggal4").val("");   
  $("#tanggal5").val("");   
}
function hapus_kerja(a,b){ 
    var id_karyawan_dealer_kerja  = a;   
    var id_kerja   = b;       
    $.ajax({
        url : "<?php echo site_url('master/karyawan_dealer/delete_kerja')?>",
        type:"POST",
        data:"id_karyawan_dealer_kerja="+id_karyawan_dealer_kerja,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_kerja();
            }
        }
    })
}


function hide_training(){
    $("#tampil_training").hide();
}
function kirim_data_training(){    
  $("#tampil_training").show();
  var id_karyawan_dealer = document.getElementById("id_karyawan_dealer").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_karyawan_dealer="+id_karyawan_dealer;                           
     xhr.open("POST", "master/karyawan_dealer/t_training", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_training").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function simpan_training(){
    var training           = document.getElementById("training").value;   
    var tgl_mulai           = document.getElementById("tanggal6").value;   
    var tgl_selesai          = document.getElementById("tanggal7").value;   
    var id_karyawan_dealer  = $("#id_karyawan_dealer").val();            
    //alert(id_dealer);
    if (training == "" || id_karyawan_dealer == "") {    
        alert("Isikan data dengan lengkap...!");
        return false;
    }else{
        $.ajax({
            url : "<?php echo site_url('master/karyawan_dealer/save_training')?>",
            type:"POST",
            data:"training="+training+"&id_karyawan_dealer="+id_karyawan_dealer+"&tgl_mulai="+tgl_mulai+"&tgl_selesai="+tgl_selesai,
            cache:false,
            success:function(msg){            
                data=msg.split("|");
                if(data[0]=="nihil"){
                    kirim_data_training();
                    kosong();                
                }else{
                    alert('Training ini sudah ditambahkan');
                    kosong();                      
                }                
            }
        })    
    }
}
function kosong(args){
  $("#training").val("");
  $("#tanggal6").val("");   
  $("#tanggal7").val("");   
}
function hapus_training(a,b){ 
    var id_karyawan_dealer_training  = a;   
    var id_training   = b;       
    $.ajax({
        url : "<?php echo site_url('master/karyawan_dealer/delete_training')?>",
        type:"POST",
        data:"id_karyawan_dealer_training="+id_karyawan_dealer_training,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_training();
            }
        }
    })
}

function bulk_delete(){
  var list_id = [];
  $(".data-check:checked").each(function() {
    list_id.push(this.value);
  });
  if(list_id.length > 0){
    if(confirm('Are you sure delete this '+list_id.length+' data?'))
      {
        $.ajax({
          type: "POST",
          data: {id:list_id},
          url: "<?php echo site_url('master/karyawan_dealer/ajax_bulk_delete')?>",
          dataType: "JSON",
          success: function(data)
          {
            if(data.status){
              window.location.reload();
            }else{
              alert('Failed.');
            }                  
          },
          error: function (jqXHR, textStatus, errorThrown){
            alert('Error deleting data');
          }
        });
      }
    }else{
      alert('no data selected');
  }
}
function cari_dealer(){
  var id_dealer = $("#id_dealer").val(); 
  $.ajax({
    url : "<?php echo site_url('master/karyawan_dealer/cari_dealer')?>",
    type:"POST",
    data:"id_dealer="+id_dealer,
    cache:false,   
    success:function(msg){            
      $("#id_pos_dealer").html(msg);            
    }
  })  
}
</script>