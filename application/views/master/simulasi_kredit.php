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
    <li class="">Simulasi Kredit</li>
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
<style>
  .isi{
  height: 25px;
  padding-left: 4px;
  padding-right: 4px;  
}
</style>

<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

<script>
  Vue.use(VueNumeric.default);
  $(document).ready(function(){
    <?php if (isset($row)) { ?>
        $('#id_tipe_kendaraan').val('<?= $row->id_tipe_kendaraan ?>').trigger('change');
    <?php } ?>
  })
</script>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/simulasi_kredit">
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
            <form  class="form-horizontal" id="form_" action="master/simulasi_kredit/<?= $form ?>" method="post" enctype="multipart/form-data">
              <?php if (isset($row)): ?>
                <input type="hidden" id="id_simulasi" name="id_simulasi" value="<?= $row->id_simulasi ?>">
              <?php endif ?>
              <div class="box-body">
                <div class="form-group">
                   <label for="inputEmail3" class="col-sm-2 control-label">Tipe Motor</label>
                  <div class="col-sm-4">
                    <select name="id_tipe_kendaraan" id="id_tipe_kendaraan" class="form-control select2" :disabled="mode=='detail'" required>
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
                  </div>
                </div> 
                <div class="form-group">
                   <label for="inputEmail3" class="col-sm-2 control-label">Harga Unit</label>
                  <div class="col-md-4">
                    <vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" v-model="harga_unit"
                          v-bind:minus="false" required :empty-value="0" separator="." :disabled="mode=='detail'"/>                                       
                  </div>
                </div>           
              </div><!-- /.box-body -->
             <div>
              <div class="col-md-12">
                <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Detail</button><br><br>
              </div>
              <div class="col-md-12">
                <table class="table table-bordered">
                  <thead>
                    <th>Uang Muka / DP</th>
                    <th>Voucher</th>
                    <th>Cukup Bayar</th>
                    <th style="text-align:center;">Tenor & Angsuran</th>
                    <th width="9%" style="text-align: center;" v-if="mode=='insert' || mode=='edit'">Aksi</th>
                  </thead>
                  <tbody>
                    <tr v-for="(dtl, index) of details">
                      <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" v-model="dtl.uang_muka"
                          v-bind:minus="false" :empty-value="0" separator="." :disabled="mode=='detail'"/>  
                      </td>
                      <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" v-model="dtl.voucher"
                          v-bind:minus="false" :empty-value="0" separator="." :disabled="mode=='detail'"/>  
                      </td>
                      <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" v-model="dtl.cukup_bayar"
                          v-bind:minus="false" :empty-value="0" separator="." :disabled="mode=='detail'"/>  
                      </td>
                      <td>
                        <table class="table" style="margin-bottom: 0px;margin-top: 0px">
                           <tr>
                             <td>Tenor</td>
                             <td>Angsuran</td>
                             <td>Angsuran Bundling</td>
                             <!-- <td>Aksi</td> -->
                           </tr>
                          <!--  <tr v-if="mode=='insert' || mode=='edit'">
                              <td width="24%"><input type="text" class="form-control isi" v-model="tenorAngsuran_.tenor"></td>
                              <td>
                                <vue-numeric style="float: left;width: 100%;text-align: right;"
                                  class="form-control text-rata-kanan isi" v-model="tenorAngsuran_.angsuran"
                                  v-bind:minus="false" :empty-value="0" separator="."/>  
                              </td>
                              <td><button type="button" @click.prevent="addTenorAngsuran(index)" class="btn btn-flat btn-primary btn-xs"><i class="fa fa-plus"></i></button></td>
                            </tr> -->
                            <tr  v-for="(ta, indx) of dtl.tenorAngsuran">
                              <td><input type="text" class="form-control isi" v-model="ta.tenor" :readonly="mode=='detail'"></td>
                              <td>
                                <vue-numeric style="float: left;width: 100%;text-align: right;"
                                  class="form-control text-rata-kanan isi" v-model="ta.angsuran"
                                  v-bind:minus="false" :empty-value="0" separator="." :disabled="mode=='detail'"/>  
                              </td>
                              <td>
                                <vue-numeric style="float: left;width: 100%;text-align: right;"
                                  class="form-control text-rata-kanan isi" v-model="ta.angsuran_bundling"
                                  v-bind:minus="false" :empty-value="0" separator="." :disabled="mode=='detail'"/>  
                              </td>
                              <!-- <td  v-if="mode=='insert' || mode=='edit'"><button type="button" @click.prevent="delParts(index_tenor,index)" class="btn btn-flat btn-danger btn-xs"><i class="fa fa-trash"></i></button></td> -->
                            </tr>
                         </table>
                      </td>
                      <td align="center" v-if="mode=='insert' || mode=='edit'">
                        <button type="button" @click.prevent="delDetails(index)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                      </td>
                    </tr>
                  </tbody>
                  <tfoot v-if="mode=='insert' || mode=='edit'">
                    <tr style="vertical-align: middle;">
                      <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" v-model="detail.uang_muka"
                          v-bind:minus="false" :empty-value="0" separator="."/>  
                      </td>
                       <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" v-model="detail.voucher"
                          v-bind:minus="false" :empty-value="0" separator="."/>  
                      </td>
                       <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" v-model="detail.cukup_bayar"
                          v-bind:minus="false" :empty-value="0" separator="."/>  
                      </td>
                      <td>
                        <table class="table" style="margin-bottom: 0px;margin-top: 0px">
                           <tr>
                             <td>Tenor</td>
                             <td>Angsuran</td>
                             <td>Angsuran Bundling</td>
                             <td>Aksi</td>
                           </tr>
                           <tr v-if="mode=='insert' || mode=='edit'">
                              <td width="24%"><input type="text" class="form-control isi" v-model="tenorAngsuran_.tenor"></td>
                              <td>
                                <vue-numeric style="float: left;width: 100%;text-align: right;"
                                  class="form-control text-rata-kanan isi" v-model="tenorAngsuran_.angsuran"
                                  v-bind:minus="false" :empty-value="0" separator="."/>  
                              </td>
                              <td>
                                <vue-numeric style="float: left;width: 100%;text-align: right;"
                                  class="form-control text-rata-kanan isi" v-model="tenorAngsuran_.angsuran_bundling"
                                  v-bind:minus="false" :empty-value="0" separator="."/>  
                              </td>
                              <td><button type="button" @click.prevent="addTenorAngsuran()" class="btn btn-flat btn-primary btn-xs"><i class="fa fa-plus"></i></button></td>
                            </tr>
                            <tr  v-for="(ta, indx) of detail.tenorAngsuran">
                              <td><input type="text" class="form-control isi" v-model="ta.tenor" :readonly="mode=='detail'"></td>
                              <td>
                                <vue-numeric style="float: left;width: 100%;text-align: right;"
                                  class="form-control text-rata-kanan isi" v-model="ta.angsuran"
                                  v-bind:minus="false" :empty-value="0" separator="."/>  
                              </td>
                              <td>
                                <vue-numeric style="float: left;width: 100%;text-align: right;"
                                  class="form-control text-rata-kanan isi" v-model="ta.angsuran_bundling"
                                  v-bind:minus="false" :empty-value="0" separator="."/>  
                              </td>
                              <td  v-if="mode=='insert' || mode=='edit'"><button type="button" @click.prevent="delTenorAngsuran(indx)" class="btn btn-flat btn-danger btn-xs"><i class="fa fa-trash"></i></button></td>
                            </tr>
                         </table>
                      </td>
                      <td align="center">
                        <button type="button" @click.prevent="addDetails()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i></button> 
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              
              <div class="box-footer" v-if="mode!='detail'">
                <div class="col-sm-12" v-if="mode=='insert' || mode=='edit'" align="center">
                  <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                </div>
              </div><!-- /.box-footer -->
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
        harga_unit : <?= isset($row)?$row->harga_unit:0 ?>,
        detail :{
          uang_muka:'',
          voucher:'',
          cukup_bayar:'',
          tenorAngsuran:[]
        },
        tenorAngsuran_:{tenor:'',angsuran:'',angsuran_bundling:''},
        details : <?= isset($details)?json_encode($details):'[]' ?>,
      },
    methods: {
      clearDetails: function () {
        this.detail ={
          uang_muka:'',
          voucher:'',
          cukup_bayar:'',
          tenorAngsuran:[]
        }
      },
      addDetails : function(){
        if (this.detail.uang_muka=='' || this.detail.cukup_bayar=='' || this.detail.tenorAngsuran.length==0) 
        {
          alert('Isi data dengan lengkap !');
          return false;
        }
        this.details.push(this.detail);
        console.log(this.details);
        this.clearDetails();
      },

      delDetails: function(index){
          this.details.splice(index, 1);
      },
      getDealer: function(){
        var el   = $('#dealer').find('option:selected'); 
        var id_dealer    = el.attr("id_dealer"); 
        form_.dealer.id_dealer = id_dealer;
      },

      clearTenorAngsuran : function () {
        this.tenorAngsuran_={tenor:'',angsuran:'',angsuran_bundling:''}
      },
      addTenorAngsuran : function(index=null){
        if (this.tenorAngsuran_.tenor=='') 
        {
          alert('Isi data dengan lengkap !');
          return false;
        }
        if (index==null) {
          this.detail.tenorAngsuran.push(this.tenorAngsuran_);
        }else{
          this.details[index].tenorAngsuran.push(this.tenorAngsuran_);
        }
        this.clearTenorAngsuran();
      },
      delTenorAngsuran: function(index){
          this.detail.tenorAngsuran.splice(index, 1);
      },
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
  var values = {details:form_.details,harga_unit:form_.harga_unit};
  var form   = $('#form_').serializeArray();
  for (field of form) {
    values[field.name] = field.value;
  }
  if (form_.details.length==0) {
    alert('Detail belum ditentukan !');
    return false;
  }
  if ($('#form_').valid()) // check if form is valid
  {
    $.ajax({
      beforeSend: function() {
        $('#submitBtn').attr('disabled',true);
      },
      url:'<?= base_url('master/simulasi_kredit/'.$form) ?>',
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
})
</script>
    <?php
    }elseif($set=="index"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/simulasi_kredit/add">
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
              <th>Tipe Motor</th>
              <th>Harga Unit</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($event->result() as $rs): 
              $status='';$button='';
              $btn_edit ='<a data-toggle=\'tooltip\' title="Edit Data" class=\'btn btn-warning btn-xs btn-flat\' href=\'master/simulasi_kredit/edit?id='.$rs->id_simulasi.'\'><i class=\'fa fa-edit\'></i></a>';
              // $btn_approve ='<a data-toggle=\'tooltip\' title="Approval" class=\'btn btn-success btn-xs btn-flat\' href=\'master/simulasi_kredit/approval_save?id='.$rs->id_event.'\' onclick="return confirm(\'Are You Sure To Approve This Data ?\')" >Approved</a>';
               $btn_delete ='<a data-toggle=\'tooltip\' title="Delete" class=\'btn btn-danger btn-xs btn-flat\' href=\'master/simulasi_kredit/delete?id='.$rs->id_simulasi.'\' onclick="return confirm(\'Are you sure to delete this data ?\')" ><i class=\'fa fa-trash\'></i></a>';
               $button = $btn_delete.' '.$btn_edit;
              // $btn_reject = '<button class="btn btn-danger btn-xs btn-flat" onclick="return closePrompt(\''.$rs->kode_event.'\','.$rs->id_event.')"><i class=\'fa fa-close\'></i> Rejected</button>';
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
                <td><a href="<?= base_url('master/simulasi_kredit/detail?id='.$rs->id_simulasi) ?>"><?= $rs->id_tipe_kendaraan.' | '.$rs->tipe_ahm ?></a></td>
                <td align="right"><?= mata_uang_rp($rs->harga_unit) ?></td>
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

       window.location = '<?= base_url("master/simulasi_kredit/reject_save?id=") ?>'+id_event+'&alasan_reject='+alasan_reject;

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