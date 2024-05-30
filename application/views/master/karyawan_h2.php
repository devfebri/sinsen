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
  $(document).ready(function(){
    <?php if (isset($row)) { ?>
        pilihAHASS(<?= json_encode($dealer) ?>)

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
          <a href="master/karyawan_h2">
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode AHASS</label>
                  <div class="col-sm-4">
                    <input type="text" readonly  @click.prevent="form_.showModalAHASS" class="form-control" placeholder="Kode AHASS" id="kode_ahass">
                    <input type="hidden" name="id_dealer" id="id_dealer">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama AHASS</label>

                  <div class="col-sm-4">
                    <input type="text" required @click.prevent="form_.showModalAHASS" class="form-control" id="nama_ahass" readonly placeholder="Nama AHASS">                    
                  </div>
                </div>
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Karyawan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" name="id_karyawan" id="id_karyawan" autocomplete="off" value="<?= isset($row)?$row->id_karyawan:'' ?>" :readonly="mode=='detail'" <?= $readonly ?>>                     
                  </div>               
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Honda ID</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" name="honda_id" id="honda_id" autocomplete="off" value="<?= isset($row)?$row->honda_id:'' ?>" :readonly="mode=='detail'">                    
                  </div>               
                </div>
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Lengkap</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" autocomplete="off" value="<?= isset($row)?$row->nama_lengkap:'' ?>" :readonly="mode=='detail'">                    
                  </div>               
                </div>
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Masuk Kerja</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control datepicker" name="tgl_masuk_kerja" id="tgl_masuk_kerja" autocomplete="off" value="<?= isset($row)?$row->tgl_masuk_kerja:'' ?>" :readonly="mode=='detail'">                    
                  </div>               
                </div>
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Resign</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control datepicker" name="tgl_resign" id="tgl_resign" autocomplete="off" value="<?= isset($row)?$row->tgl_resign:'' ?>" :readonly="mode=='detail'">                    
                  </div>               
                </div>
                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Jabatan</label>
                  <div class="col-sm-4">
                    <select name="jabatan" id="jabatan" class="form-control select2" <?= $disabled ?>>
                      <option value="">-choose-</option>
                      <?php $jbtn = $this->db->get('ms_jabatan');
                          foreach ($jbtn->result() as $rs) { 
                            $select = isset($row)?$row->jabatan==$rs->id_jabatan?'selected':'':'';
                          ?>
                          <option value="<?= $rs->id_jabatan ?>" <?= $select ?>><?= $rs->id_jabatan.' | '.$rs->jabatan ?></option>
                        <?php } ?>
                    </select>
                  </div>               
                </div>
                  <button class="btn btn-primary btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Asal Rekruitment</button>
                <br><br><br>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Asal Rekruitment</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" name="asal_rekruitment" id="asal_rekruitment" autocomplete="off" value="<?= isset($row)?$row->asal_rekruitment:'' ?>" :readonly="mode=='detail'">                    
                  </div>  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun Rekruitment</label>
                  <div class="col-sm-4">
                    <input type="number" class="form-control" name="tahun_rekruitment" id="tahun_rekruitment" autocomplete="off" value="<?= isset($row)?$row->tahun_rekruitment:'' ?>" :readonly="mode=='detail'">                    
                  </div>               
                </div>
                <button class="btn btn-primary btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Training Yang Diikuti</button>
                <br><br><br>
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Training Mekanik</label>
                  <div class="col-sm-4">
                    <select <?= $disabled ?> name="train_mekanik" id="train_mekanik" class="form-control select2" v-model="train_mekanik">
                      <option value="">-choose-</option>
                      <option value="untrain">Untrain (Belum Training)</option>
                      <option value="ttl1">TTL-1</option>
                      <option value="ttl2">TTL-2</option>
                      <option value="ttl3">TTL-3</option>
                    </select>
                  </div>               
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Seminar Pemilik AHASS</label>
                  <div class="col-sm-4">
                    <select <?= $disabled ?> name="seminar_pemilik_ahass" id="seminar_pemilik_ahass" class="form-control select2" v-model="seminar_pemilik_ahass">
                      <option value="">-choose-</option>
                      <option value="y">Ya</option>
                      <option value="n">Tidak</option>
                    </select>
                  </div>               
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tranining Kepala Bengkel</label>
                  <div class="col-sm-4">
                    <select <?= $disabled ?> name="train_kepala_bengkel" id="train_kepala_bengkel" class="form-control select2" v-model="train_kepala_bengkel">
                      <option value="">-choose-</option>
                      <option value="y">Ya</option>
                      <option value="n">Tidak</option>
                    </select>
                  </div>               
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tranining Kepala Mekanik</label>
                  <div class="col-sm-4">
                    <select <?= $disabled ?> name="train_kepala_mekanik" id="train_kepala_mekanik" class="form-control select2" v-model="train_kepala_mekanik">
                      <option value="">-choose-</option>
                      <option value="y">Ya</option>
                      <option value="n">Tidak</option>
                    </select>
                  </div>               
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Service Advisor</label>
                  <div class="col-sm-4">
                    <select <?= $disabled ?> name="service_advisor" id="service_advisor" class="form-control select2" v-model="service_advisor">
                      <option value="">-choose-</option>
                      <option value="y">Ya</option>
                      <option value="n">Tidak</option>
                    </select>
                  </div>               
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Training Final Inception</label>
                  <div class="col-sm-4">
                    <select <?= $disabled ?> name="train_final_inspection" id="train_final_inspection" class="form-control select2" v-model="train_final_inspection">
                      <option value="">-choose-</option>
                      <option value="y">Ya</option>
                      <option value="n">Tidak</option>
                    </select>
                  </div>               
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">PAA/Komputerisasi</label>
                  <div class="col-sm-4">
                    <select <?= $disabled ?> name="paa_komputer" id="paa_komputer" class="form-control select2" v-model="paa_komputer">
                      <option value="">-choose-</option>
                      <option value="y">Ya</option>
                      <option value="n">Tidak</option>
                    </select>
                  </div>               
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tranining Claim Processor</label>
                  <div class="col-sm-4">
                    <select <?= $disabled ?> name="train_claim_proces" id="train_claim_proces" class="form-control select2" v-model="train_claim_proces">
                      <option value="">-choose-</option>
                      <option value="y">Ya</option>
                      <option value="n">Tidak</option>
                    </select>
                  </div>               
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Sertifikasi Claim Processor</label>
                  <div class="col-sm-4">
                    <select <?= $disabled ?> name="sertifikasi_claim_proces" id="sertifikasi_claim_proces" class="form-control select2" v-model="sertifikasi_claim_proces">
                      <option value="">-choose-</option>
                      <option value="y">Ya</option>
                      <option value="n">Tidak</option>
                    </select>
                  </div>               
                </div>
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Training Big Bike</label>
                  <div class="col-sm-4">
                    <select <?= $disabled ?> name="train_bigbike" id="train_bigbike" class="form-control select2" v-model="train_bigbike">
                      <option value="">-choose-</option>
                      <option value="y">Ya</option>
                      <option value="n">Tidak</option>
                    </select>
                  </div>               
                </div>
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">
                    <input type="checkbox" name="status" <?= isset($row)?$row->status=='y'?'checked':'':'' ?> <?= $disabled ?>>
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
<div class="modal fade modalAHASS" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Daftar AHASS</h4>
      </div>
      <div class="modal-body">
        <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_ahass" style="width: 100%">
                  <thead>
                  <tr>
                      <th>Kode AHASS</th>
                      <th>Nama AHASS</th>
                      <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>
              <script>
                  function pilihAHASS(ahass)
                  {
                    $('#kode_ahass').val(ahass.kode_dealer_md);
                    $('#nama_ahass').val(ahass.nama_dealer);
                    $('#id_dealer').val(ahass.id_dealer);
                  }
                  $(document).ready(function(){
                      $('#tbl_ahass').DataTable({
                          processing: true,
                          serverSide: true,
                          "language": {                
                                  "infoFiltered": ""
                              },
                          order: [],
                          ajax: {
                              url: "<?= base_url('h2/claim_kpb/fetch_ahass') ?>",
                              dataSrc: "data",
                              data: function ( d ) {
                                    // d.kode_item     = $('#kode_item').val();
                                    return d;
                                },
                              type: "POST"
                          },
                          "columnDefs":[  
                      // { "targets":[4],"orderable":false},
                      { "targets":[2],"className":'text-center'}, 
                      // { "targets":[4], "searchable": false } 
                 ]
                      });
                  });
              </script>
      </div>
    </div>
  </div>
</div>    
<script>
  var form_ = new Vue({
    el: '#form_',
    data: {
      mode : '<?= $mode ?>',
      train_mekanik:'<?= isset($row)?$row->train_mekanik:'' ?>',
      seminar_pemilik_ahass:'<?= isset($row)?$row->seminar_pemilik_ahass:'' ?>',
      train_kepala_bengkel:'<?= isset($row)?$row->train_kepala_bengkel:'' ?>',
      train_kepala_mekanik:'<?= isset($row)?$row->train_kepala_mekanik:'' ?>',
      service_advisor:'<?= isset($row)?$row->service_advisor:'' ?>',
      train_final_inspection:'<?= isset($row)?$row->train_final_inspection:'' ?>',
      paa_komputer:'<?= isset($row)?$row->paa_komputer:'' ?>',
      train_claim_proces:'<?= isset($row)?$row->train_claim_proces:'' ?>',
      sertifikasi_claim_proces:'<?= isset($row)?$row->sertifikasi_claim_proces:'' ?>',
      train_bigbike:'<?= isset($row)?$row->train_bigbike:'' ?>',
      details : <?= isset($details)?json_encode($details):'[]' ?>,
    },   
    methods:{
      totalHarga : function (dtl) {
        return parseInt(this.hargaDiskon(dtl) * dtl.qty);
      },
      hargaDiskon : function (dtl) {
        return parseInt(dtl.harga_material - dtl.diskon);
      },
      showModalAHASS : function() {
          // $('#tbl_part').DataTable().ajax.reload();
        $('.modalAHASS').modal('show');
      },
      showModalPart : function() {
          // $('#tbl_part').DataTable().ajax.reload();
        $('.modalPart').modal('show');
      },
      showModalKelurahan : function() {
          // $('#tbl_part').DataTable().ajax.reload();
        $('.modalKelurahan').modal('show');
      },
      clearDetail : function () {
         this.detail ={id_part:'',
               nama_part:'',
               jumlah:'',
               tipe_penggantian:'',
               harga:'',
               ongkos:'',
               status_part:''
              }
      },
      addDetails : function(){
        this.details.push(this.detail);
        this.clearDetail(); 
      },
      delDetails: function(index){
          this.details.splice(index, 1);
      },
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
        url:'<?= base_url('master/karyawan_h2/'.$form) ?>',
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
          <a href="master/karyawan_h2/add">
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
              <th>ID Karyawan</th>
              <th>Honda ID</th>
              <th>Nama Lengkap</th>
              <th>Tgl Masuk Kerja</th>
              <th>Jabatan</th>
              <th>Status</th>
              <th width="12%" align="center">Aksi</th>     
            </tr>
          </thead>
          <tbody>            
          <?php 
         foreach($dt_result->result() as $row) { 
          $status = '';
          $button = '';
          if ($row->status=='y') {
            $status = "<i class='fa fa-check'></i>";
          }
          $btn_edit = "<a href='".base_url('master/karyawan_h2/edit?id=').$row->id_karyawan."' class='btn btn-warning btn-xs'><i class='fa fa-edit'></a>";
          $button = $btn_edit;
          echo "
          <tr>
          <td><a href=".base_url('master/karyawan_h2/detail?id=').$row->id_karyawan.">$row->id_karyawan</a></td>
          <td>$row->honda_id</td>
          <td>$row->nama_lengkap</td>
          <td>$row->tgl_masuk_kerja</td>
          <td>$row->jabatan</td>
          <td>$status</td>
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