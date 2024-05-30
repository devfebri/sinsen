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
    <li class="">Entry PO Finance Company</li>
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
    <?php if (isset($row)) { ?>
        $('#id_hasil_survey').val('<?= $row->id_hasil_survey ?>').trigger('change');
    <?php } ?>
  })
</script>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/entry_po_leasing">
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
            <form  class="form-horizontal" id="form_" action="dealer/entry_po_leasing/<?= $form ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Dokumen Pengajuan</label>
                  <div class="col-sm-4">
                   <select name="id_hasil_survey" id="id_hasil_survey" onchange="getHasilSurvey()" class="form-control select2" <?= $disabled ?> required>
                      <option value="">--choose-</option>
                      <?php foreach ($hasil->result() as $rs): 
                        
                        $selected = isset($row)?$rs->no_spk==$row->id_hasil_survey?'selected':'':'';
                      ?>
                        <option value="<?= $rs->id_hasil_survey ?>" <?= $selected ?> 
                             data-no_spk             = "<?= $rs->no_spk ?>"
                             data-nama_konsumen      = "<?= $rs->nama_konsumen ?>"
                             data-no_ktp             = "<?= $rs->no_ktp ?>"
                             data-no_hp              = "<?= $rs->no_hp ?>"
                             data-id_tipe_kendaraan  = "<?= $rs->id_tipe_kendaraan ?>"
                             data-tipe_ahm           = "<?= $rs->tipe_ahm ?>"
                             data-id_warna           = "<?= $rs->id_warna ?>"
                             data-warna              = "<?= $rs->warna ?>"
                             data-id_finance_company = "<?= $rs->id_finance_company ?>"
                             data-finance_company    = "<?= $rs->finance_company ?>"
                        ><?= $rs->no_spk.' | '.$rs->nama_konsumen ?></option>
                      <?php endforeach ?>

                      <?php foreach ($hasil2->result() as $rs): 
                        $selected = isset($row)?$rs->no_spk_gc==$row->id_hasil_survey_gc?'selected':'':'';
                      ?>
                        <option value="<?= $rs->id_hasil_survey_gc ?>" <?= $selected ?> 
                             data-no_spk             = "<?= $rs->no_spk_gc ?>"
                             data-nama_konsumen      = "<?= $rs->nama_npwp ?>"
                             data-no_ktp             = "<?= $rs->no_ktp ?>"
                             data-no_hp              = "<?= $rs->no_hp ?>"
                             data-id_tipe_kendaraan  = "<?= $rs->id_tipe_kendaraan ?>"
                             data-tipe_ahm           = "<?= $rs->tipe_ahm ?>"
                             data-id_warna           = "<?= $rs->id_warna ?>"
                             data-warna              = "<?= $rs->warna ?>"
                             data-id_finance_company = "<?= $rs->id_finance_company ?>"
                             data-finance_company    = "<?= $rs->finance_company ?>"
                        ><?= $rs->id_hasil_survey_gc.' | '.$rs->nama_npwp ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div> 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Finance Company</label>
                  <div class="col-sm-4">                   
                    <input type="text" required class="form-control" disabled id="finco" name="finco">
                    <input type="hidden" required class="form-control" id="no_spk" name="no_spk">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No. PO Finance Company *</label>
                  <div class="col-sm-4">
                     <input type="text" name="po_dari_finco" id="po_no" required class="form-control" value="<?= isset($row)?$row->po_dari_finco:'' ?>" autocomplete="off" <?= $disabled ?>> 
                  </div>
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Pembuatan PO *</label>
                  <div class="col-sm-4">
                     <input type="text" name="tgl_pembuatan_po" id="po_date" required class="form-control datepicker" value="<?= isset($row)?$row->tgl_pembuatan_po:'' ?>" required autocomplete="off" <?= $disabled ?>> 
                  </div>
                </div>  
                <!-- <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Pengiriman PO *</label>
                  <div class="col-sm-4">
                     <input type="text" name="tgl_pengiriman_po" required class="form-control datepicker" value="<?= isset($row)?$row->tgl_pengiriman_po:'' ?>" required autocomplete="off" <?= $disabled ?>> 
                  </div>
                </div> -->
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">                   
                    <input type="text" required class="form-control" disabled id="tipe" name="tipe">
                  </div> 
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" disabled id="warna" name="warna">
                  </div>
                </div>       
              </div><!-- /.box-body -->
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
        total_harga : '',
        amount_dp : '<?= isset($row)?$row->tanda_jadi:''?>',
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
    },
  });
function getHasilSurvey() {

  var id_finance_company  = $("#id_hasil_survey").select2().find(":selected").data("id_finance_company");
  var no_spk             = $("#id_hasil_survey").select2().find(":selected").data("no_spk");$('#no_spk').val(no_spk);
  var values ={id_hasil_survey:$('#id_hasil_survey').val(), id_spk : no_spk , id_finco : id_finance_company}

  $.ajax({
        beforeSend: function() {
          $('#gnrtBtn').attr('disabled',true);
        },
        url:'<?= base_url('dealer/entry_po_leasing/cek_entry_hasil') ?>',
        type:"POST",
        data: values,
        cache:false,
        dataType:'JSON',
        success:function(response){

          if (response.msg=='ada') {
            $('#nama_konsumen').val('');
            $('#no_spk').val('');
            $('#tipe').val('');
            $('#warna').val('');
            $('#finco').val('');
            $('#po_no').val(response.no_po);
            $('#po_date').val(response.po_date);
            alert('Sudah dilakukan entry data untuk ID Dokumen Pengajuan : '+$('#id_hasil_survey').val());
            return false
          }
            var nama_konsumen      = $("#id_hasil_survey").select2().find(":selected").data("nama_konsumen");$('#nama_konsumen').val(nama_konsumen);
            var no_ktp             = $("#id_hasil_survey").select2().find(":selected").data("no_ktp");$('#no_ktp').val(no_ktp);
            var no_spk             = $("#id_hasil_survey").select2().find(":selected").data("no_spk");$('#no_spk').val(no_spk);
            var id_tipe_kendaraan  = $("#id_hasil_survey").select2().find(":selected").data("id_tipe_kendaraan");
            var tipe_ahm           = $("#id_hasil_survey").select2().find(":selected").data("tipe_ahm");
            $('#tipe').val(id_tipe_kendaraan+' | '+tipe_ahm);
            var id_warna           = $("#id_hasil_survey").select2().find(":selected").data("id_warna");
            var warna              = $("#id_hasil_survey").select2().find(":selected").data("warna");
            $('#warna').val(id_warna+' | '+warna);

            var id_finance_company  = $("#id_hasil_survey").select2().find(":selected").data("id_finance_company");
            var po_no  = $("#id_hasil_survey").select2().find(":selected").data("po_no");
            var po_date  = $("#id_hasil_survey").select2().find(":selected").data("po_date");
            var finance_company           = $("#id_hasil_survey").select2().find(":selected").data("finance_company");
            $('#finco').val(id_finance_company+' | '+finance_company);
            $('#po_no').val(response.no_po);
            $('#po_date').val(response.po_date);
        },
        error:function(){
          alert("Error");
          $('#gnrtBtn').attr('disabled',false);

        },
        statusCode: {
          500: function() { 
            alert('Error Code 500');
            $('#gnrtBtn').attr('disabled',false);

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
          <a href="dealer/entry_po_leasing/add">
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
        <table id="datatable_server" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No SPK</th>
              <th>Fincoy</th>
              <th>PO ID</th>
              <th>Tanggal Pembuatan PO</th>
              <th>Tanggal Pengiriman PO</th>
              <th>Nama Customer</th>
              <th>No KTP</th>
              <th>Tgl Create Data</th>
            </tr>
          </thead>          
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
<script>
   $(document).ready(function(){  
      var dataTable = $('#datatable_server').DataTable({  
         "processing":true, 
         "serverSide":true, 
         "language": {                
              "infoFiltered": "",
              "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
          }, 
         "order":[],
         "lengthMenu": [[10, 25, 50,75,100], [10, 25, 50,75,100]],  
         "ajax":{  
              url:"<?php echo site_url('dealer/entry_po_leasing/fetch'); ?>",  
              type:"POST",
              dataSrc: "data",
              data: function (d) {
                  return d;
              },
         },  
         "columnDefs":[  
              // { "targets":[2],"orderable":false},
              // { "targets":[7],"className":'text-center'}, 
              // // { "targets":[0],"checkboxes":{'selectRow':true}}
              // { "targets":[6,7],"className":'text-right'}, 
              // // { "targets":[2,4,5], "searchable": false } 
         ],
      });
    });
</script>
    <?php
    }
    ?>
  </section>
</div>