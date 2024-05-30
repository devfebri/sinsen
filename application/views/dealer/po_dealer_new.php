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
    <?php if ($this->uri->segment(3)==''||$this->uri->segment(3)=='tracking'){ ?>
    <div class="box">
      <div class="box-body" style="min-height: 700px">
          <div class="nav-tabs-custom" style="margin-bottom: 10px">
            <ul class="nav nav-tabs">
            <?php $tabs[] = ['judul'=>'My Order','link'=>''];
                  $tabs[] = ['judul'=>'Tracking Orders','link'=>'tracking'];
            ?>
            <?php foreach ($tabs as $tab):
              $active = $this->uri->segment(3)==$tab['link']?'active':'';
            ?>
              <li class="<?= $active ?>"><a href="<?= base_url('dealer/po_dealer_new/'.$tab['link']) ?>"><?= $tab['judul'] ?></a></li>
            <?php endforeach ?>
            </ul>
          </div>
         <?php }else{ ?>
       <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/po_dealer_new">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->   
        <?php } ?>
        
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
  <div style="border-bottom: 1px solid #f4f4f4;margin-bottom: 10px">
    <a href="dealer/po_dealer_new/add?type=reg">
      <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add PO Regular</button>
    </a>
    <a href="dealer/po_dealer_new/add?type=add">
      <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add PO Additional</button>
    </a>
  </div>
  <table id="datatable_server" class="table table-bordered table-hover table-striped">
    <thead>
      <th>PO Dealer</th>              
      <th>Periode</th>
      <th>Unit Qty</th>
      <th>PO Type</th>
      <th>Submission Deadline</th>
      <th>Remarks</th>
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
          url:"<?php echo site_url('dealer/po_dealer_new/fetch_order'); ?>",  
          type:"POST",
          dataSrc: "data",
          data: function ( d ) {
            // d.start_date = $('#start_date').val();
            // d.end_date = $('#end_date').val();
            return d;
          },
     },  
     "columnDefs":[  
          { "targets":[4,5,6],"orderable":false},
          // { "targets":[6],"className":'text-center'}, 
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
  <?php } ?>
<?php if ($set=='tracking'): ?>
<link rel="stylesheet" href="assets/css-progress-wizard-master/css/progress-wizard.min.css">  
<table width="60%" style="margin-left: 30px">
  <tr>
    <td width="20%"><select id="period" class="form-control select2" style="width: 95%">
          <option value="">Period</option>
          <?php for ($t = date('Y'); $t >2000 ; $t--) {
             for ($i = 1; $i <= 12; $i++) { ?>
              <option value="<?= $t ?>-<?= $i ?>"><?= medium_bulan($i) ?> <?= $t ?></option>              
          <?php } } ?>
        </select>
    </td>
    <td width="25%">
        <select id="po_type" class="form-control select2" style="width: 95%">
          <option value="">PO Type</option>
          <option value="add">PO Additional</option>
          <option value="reg">PO Reguler</option>
        </select>
    </td>
    <td width="20%">
      <input type="text" id="tgl_order" class="form-control isi datepicker" style="width: 95%" placeholder="PO Date" autocomplete="off">
    </td>
    <td><select id="status" class="form-control select2" style="width: 95%">
          <option value="">Status</option>
          <option value="input">Draft</option>
          <option value="submitted">Submitted</option>
          <option value="processed">Processed</option>
          <option value="closed">Closed</option>
          <option value="returned_po">Returned PO</option>
          <option value="rejected">Rejected</option>
          <option value="Cancelled by Dealer">Cancelled by Dealer</option>

        </select>
    </td>
  </tr>
</table>
<br>
<table id="datatable_server" class="table table-bordered table-hover table-striped">
    <thead>
      <th>PO Dealer</th>              
      <th>Periode</th>
      <th>Unit Qty</th>
      <th>PO Type</th>
      <th>PO Date</th>
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
          url:"<?php echo site_url('dealer/po_dealer_new/fetch_tracking'); ?>",  
          type:"POST",
          dataSrc: "data",
          data: function ( d ) {
            d.status    = $('#status').val();
            d.po_type   = $('#po_type').val();
            d.period    = $('#period').val();
            d.tgl_order = $('#tgl_order').val();
            return d;
          },
     },  
     "columnDefs":[  
          { "targets":[5],"orderable":false},
          { "targets":[5],"className":'text-center'}, 
          // { "targets":0,"checkboxes":{'selectRow':true}}
          // { "targets":[2],"className":'text-right'}, 
          // { "targets":[2,4,5], "searchable": false } 
     ],
    //  'select': {
    //    'style': 'multi'
    // },
  });
  selectColumns = [
        'status',
        'po_type',
        'period'
    ];
  $(selectColumns).each( function (index, value) {
    $('#'+value).change(function() {
        $('#datatable_server').DataTable().ajax.reload();
    })
  });

  inputColumns = [
        'tgl_order'
    ];
  $(inputColumns).each( function (index, value) {
    $('#'+value).change(function() {
        $('#datatable_server').DataTable().ajax.reload();
    })
  });

  });
</script>
<?php endif ?>

<?php if ($set=='form'):
$disabled = '';
$readonly = '';
$form     = '';
$po_type  = isset($row->po_type)?$row->po_type:$po_type;
if ($po_type=='reg') {
  $po_type_full='REGULAR';
}else{
  $po_type_full='ADDITIONAL';
}
if ($mode=='detail') {
  $disabled='disabled';
}
if ($mode=='insert') {
  $form ='save';
}
if ($mode=='edit') {
  $form = 'save_edit';
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
<div class="row">
  <div class="col-md-12">
    <form id="form_" class="form-horizontal" action="dealer/po_dealer_new/<?= $form ?>" method="post" enctype="multipart/form-data">
      <div class="box-body">     
        <div class="col-md-8">
          <div class="box box-primary">
            <div class="box-header with-border">
              <div class="col-md-8">
                <h3 class="box-title"><b><?= $po_type_full ?> PO</b></h3><br>
                <h3 class="box-title"><b><?= $po_number ?></b></h3>
              </div>
              <div class="col-md-4">
                <h3 class="box-title" style="color: #3C8DBC;text-align: right;">
                  <?php 
                      $tgl = date('d');
                      $deadline = $set_md->deadline_po_dealer;
                      if ($tgl>$deadline) {
                        $bulan=date('m')+2;
                        $tahun = date('Y');
                        if ($bulan>12) {
                          $bulan=2;
                          $tahun=$tahun+1;
                        }
                      }else{
                        $bulan = date('m')+1;
                        $tahun=date('Y');
                        if ($bulan>12) {
                          $bulan=1;
                          $tahun=$tahun+1;
                        }
                      }
                      $bulan = isset($row->po_period_m)?$row->po_period_m:$bulan;
                      $tahun = isset($row->po_period_y)?$row->po_period_y:$tahun;
                   ?>
                  <?php if ($po_type=='reg'): ?>
                    <b><?= strtoupper(bulan_pjg($bulan)) ?> <?= $tahun ?></b>
                  <?php endif ?>
                  <?php if ($po_type=='add'): ?>
                    <input type="text" class="form-control datepicker" name="tgl" autocomplete="off" placeholder="Pilih Tanggal PO" required id="tgl" value="<?= isset($row)?$row->tgl:'' ?>" <?= $disabled ?>>
                  <?php endif ?>
                </h3>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="box box-primary">
            <div class="box-header with-border">
                <?php if ($mode!='detail'): ?>
                  <button type="button" @click.prevent="form_.saveForm('input')" class="btn btn-primary btnSubmit"><i class="fa fa-save"></i> SAVE DRAFT</button><br><br>
                <?php endif ?>
                <?php 
                  $status ='input';
                  $btn_tipe='warning';
                  $status_show = 'draft';
                  if (isset($row)) {
                    $status = $row->status;
                    if ($status=='input') {
                      $btn_tipe = 'warning';
                      $status_show='draft';
                    }
                    if ($status=='submitted') {
                      $btn_tipe = 'info';
                      $status_show = 'submitted';
                    }
                    if ($status=='approved') {
                      $btn_tipe = 'primary';
                      $status_show = 'processed';
                    }
                  }
                 ?>
                <button type="button" class="btn btn-<?=$btn_tipe?>" style="width: 100%;text-align: left" disabled><b><?= strtoupper($status_show) ?></b></button>
            </div>
          </div>
        </div>
        <button style="margin-bottom: 20px" class="btn btn-primary btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Detail</button>
      
          <table v-if="po_type=='reg'" class="table table-bordered table-striped">
            <thead>
              <th  width="18%">Tipe Unit</th>
              <th width="18%">Warna</th>
              <th>Current Stock</th>
              <th>Monthly Sales</th>
              <th>PO T1 Last Period</th>
              <th>PO T2 Last Period</th>
              <th>PO Fix (<?= $set_md->po_fix_dealer ?>%)</th>
              <th>PO T1 (<?= $set_md->po_t1_dealer ?>%)</th>
              <th>PO T2 (Free)</th>
              <th>Kuantitas Indent</th>
              <!-- <th width="15%">Total Harga</th> -->
              <th v-if="mode!='detail'">Action</th>
            </thead>
            <tbody>
              <tr v-for="(dtl, index) of details">
                <td>{{dtl.id_tipe_kendaraan}} | {{dtl.tipe_unit}}</td>
                <td>{{dtl.id_warna}} | {{dtl.warna}}</td>
                <td>{{dtl.current_stock}}</td>
                <td>{{dtl.monthly_sale}}</td>
                <td>{{dtl.po_t1_last}}</td>
                <td>{{dtl.po_t2_last}}</td>
                <td><input type="text" @change="form_.cekPoFix(index)" class="form-control isi" v-model="dtl.po_fix" <?= $disabled ?>></td>
                <td><input type="text" @change="form_.cekPoT1(index)" class="form-control isi" v-model="dtl.qty_po_t1" <?= $disabled ?>></td>
                <td><input type="text" class="form-control isi" v-model="dtl.qty_po_t2" <?= $disabled ?>></td>
                <td>{{dtl.qty_indent}}
                  <input type="hidden" v-model="total(dtl)">
                </td>
                <!-- <td><vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" v-model="total(dtl)" 
                          v-bind:minus="false" :empty-value="0" readonly separator="."/> -->
                <!-- </td> -->
                <td v-if="mode!='detail'">
                  <button class="btn btn-flat btn-danger btn-xs" @click.prevent="form_.delDetails(index)"><i class="fa fa-trash"></i></button>
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr v-if="mode!='detail'">
                <td>
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
                <td><select onchange="form_.getDetail()" class="form-control select2" id="id_warna"></select></td>
                <td>{{detail.current_stock}}</td>
                <td>{{detail.monthly_sale}}</td>
                <td>{{detail.po_t1_last}}</td>
                <td>{{detail.po_t2_last}}</td>
                <td><input type="text" onchange="form_.cekPoFix()" class="form-control isi" v-model="detail.po_fix"></td>
                <td><input type="text" onchange="form_.cekPoT1()" class="form-control isi" v-model="detail.qty_po_t1"></td>
                <td><input type="text" class="form-control isi" v-model="detail.qty_po_t2"></td>
                <td>{{detail.qty_indent}}
                  <input type="hidden" v-model="totDetail(detail)">
                </td>
               <!--  <td><vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" v-model="totDetail(detail)" 
                          v-bind:minus="false" :empty-value="0" readonly separator="."/></td> -->
                <td v-if="mode!='detail'">
                  <button type="button" onclick="form_.addDetails()" class="btn btn-primary btn-flat btn-xs">
                    <i class="fa fa-plus"></i>
                  </button>
                </td>
              </tr>
            </tfoot>
          </table>

          <table v-if="po_type=='add'" class="table table-bordered table-striped">
            <thead>
              <th>ID Item</th>
              <th>Tipe</th>
              <th>Warna</th>
              <th width="15%">Qty Order</th>
              <th v-if="mode!='detail'">Action</th>
            </thead>
             <tbody>
              <tr v-for="(dtl, index) of details">
                <td>{{dtl.id_item}}</td>
                <td>{{dtl.tipe_unit}}</td>
                <td>{{dtl.warna}}</td>
                <td><input type="text" class="form-control isi" v-model="dtl.po_fix" <?= $disabled ?>></td>
                <td v-if="mode!='detail'">
                  <button class="btn btn-flat btn-danger btn-xs" @click.prevent="form_.delDetails(index)"><i class="fa fa-trash"></i></button>
                </td>
              </tr>
            </tbody>
            <tfoot v-if="mode!='detail'">
              <tr>
                <td><input type="text" @click.prevent="form_.showModalItem()" class="form-control isi" readonly v-model="detail.id_item"></td>
                <td><input type="text" class="form-control isi" v-model="detail.tipe_ahm" readonly></td>
                <td><input type="text" class="form-control isi" v-model="detail.warna" readonly></td>
                <td><input type="text" class="form-control isi" v-model="detail.po_fix"></td>
                <td v-if="mode!='detail'">
                  <button type="button" onclick="form_.addDetails()" class="btn btn-primary btn-flat btn-xs">
                    <i class="fa fa-plus"></i>
                  </button>
                </td>
              </tr>
            </tfoot>
          </table>
          <hr>
            <div v-if="mode!='detail'" class="col-md-12" style="text-align: center;">
              <button type="button" class="btn btn-primary" @click.prevent="form_.saveForm('submitted')"><i class="fa fa-send"></i> SEND TO MAIN DEALER</button>
            </div>
      </div>
    </form>
  </div>
</div>
<script>

// function cekTglPO() {
// var tgl_po = $('#tgl').val();
//   console.log(tgl_po);
//   var tgl = new Date();
//   va
//   if (tgl_hari_ini<new Date(tgl_po)) {
//     console.log('ss')
//     alert('tanggal tidak boleh lebih kecil dari hari ini !');
//     $('#tgl_po').val('')
//     return false;
//   }
// }
   var form_ = new Vue({
      el: '#form_',
      data: {
        mode : '<?= $mode ?>',
        po_type :'<?= $po_type ?>',
        detail:{
          id_tipe_kendaraan : '',
          tipe_unit : '',
          id_warna :'',
          warna:'',
          current_stock : '',
          monthly_sale : '',
          po_t1_last : '',
          po_t2_last : '',
          po_fix : '',
          qty_po_t1 : '',
          qty_po_t2 : '',
          qty_indent : '',
          harga : '',
          total_harga:'',
          min_po_fix:'',
          max_po_fix:'',
          min_po_t1:'',
          max_po_t1:''
        },
        details : <?= isset($details)?json_encode($details):'[]' ?>,
      },
      methods: {
        total: function (unit) {
            total = unit.harga*(parseInt(unit.po_fix)+parseInt(unit.qty_indent));
            ppn = total * (10/100);
            total = total + ppn;
            return total;
        },
        cekPoFix: function (index=null) {
          if (index==null) {
            var po_fix_dealer = <?= $set_md->po_fix_dealer ?>;
            var max_po_fix = 0;
            var min_po_fix = 0;
            if (this.detail.po_t1_last>0) {
              var max_po_fix = Math.floor(parseInt(this.detail.po_t1_last) + parseFloat(this.detail.po_t1_last * (po_fix_dealer/100)));
              var min_po_fix = Math.ceil(parseInt(this.detail.po_t1_last) - parseFloat(this.detail.po_t1_last * (po_fix_dealer/100)));
            }
            this.detail.min_po_fix = min_po_fix;
            this.detail.max_po_fix = max_po_fix;

            if (parseFloat(max_po_fix)>0) {
              if (parseInt(this.detail.po_fix)>parseFloat(max_po_fix)) {
                alert('PO Fix melebihi batas maksimal!');
                this.detail.po_fix='';
              }
              else if (parseInt(this.detail.po_fix)<parseFloat(min_po_fix)) {
                alert('PO Fix kurang dari batas minimal !');
                this.detail.po_fix='';
              }
            }
          }else{
            var max_po_fix = this.details[index].max_po_fix;
            var min_po_fix = this.details[index].min_po_fix;

            if (parseFloat(max_po_fix)>0) {
              if (parseInt(this.details[index].po_fix)>parseFloat(max_po_fix)) {
                alert('PO Fix melebihi batas maksimal!');
                this.details[index].po_fix='';
              }
              else if (parseInt(this.details[index].po_fix)<parseFloat(min_po_fix)) {
                alert('PO Fix kurang dari batas minimal !');
                this.details[index].po_fix='';
              }
            }
          }
          // console.log(this.detail);
        },
         cekPoT1: function (index=null) {
          if (index==null) {
            var po_t1_dealer = <?= $set_md->po_t1_dealer ?>;
            var max_po_t1 = 0;
            var min_po_t1 = 0;
            if (this.detail.po_t2_last>0) {
              var max_po_t1 = Math.floor(parseInt(this.detail.po_t2_last) + parseFloat(this.detail.po_t2_last * (po_t1_dealer/100)));
              var min_po_t1 = Math.ceil(parseInt(this.detail.po_t2_last) - parseFloat(this.detail.po_t2_last * (po_t1_dealer/100)));
            }
            this.detail.min_po_t1 = min_po_t1;
            this.detail.max_po_t1 = max_po_t1;

            // max > 101.6 << 101
            // min > 90.2 >> 91
            if (parseFloat(max_po_t1)>0) {
              if (parseInt(this.detail.qty_po_t1)>parseFloat(max_po_t1)) {
                alert('PO T1 melebihi batas maksimal!');
                this.detail.qty_po_t1='';
              }
              else if (parseInt(this.detail.qty_po_t1)<parseFloat(min_po_t1)) {
                alert('PO T1 kurang dari batas minimal !');
                this.detail.qty_po_t1='';
              }
            }
          }else{
            var max_po_t1 = this.details[index].max_po_t1;
            var min_po_t1 = this.details[index].min_po_t1;
            if (parseFloat(max_po_t1)>0) {
              if (parseInt(this.details[index].qty_po_t1)>parseFloat(max_po_t1)) {
                alert('PO Fix melebihi batas maksimal!');
                this.details[index].qty_po_t1='';
              }
              else if (parseInt(this.details[index].qty_po_t1)<parseFloat(min_po_t1)) {
                alert('PO Fix kurang dari batas minimal !');
                this.details[index].qty_po_t1='';
              }
            }
          }
        },
        getWarna: function() {
          var element   = $('#id_tipe_kendaraan').find('option:selected'); 
          var id_tipe_kendaraan = $('#id_tipe_kendaraan').val();
          if (id_tipe_kendaraan=='' || id_tipe_kendaraan==null) {
            $('#id_warna').html('');
            return false;
          }
          var warnas    = JSON.parse(element.attr("data-warna")); 
          var tipe_unit = element.attr("data-tipe_unit");
          form_.detail.tipe_unit = tipe_unit; 
          form_.detail.id_tipe_kendaraan = $('#id_tipe_kendaraan').val(); 
          $('#id_warna').html('');
            if (warnas.length>0) {
              $('#id_warna').append($('<option>').text('--choose--').attr('value', ''));
            }
          $.each(warnas, function(i, value) {
            $('#id_warna').append($('<option>').text(warnas[i].id_warna+' | '+warnas[i].warna).attr({'value':warnas[i].id_warna,'warna':warnas[i].warna}));

          });
        },
        getDetail: function() {
          var element           = $('#id_warna').find('option:selected'); 
          var warna             = element.attr("warna");
          form_.detail.warna    = warna; 
          form_.detail.id_warna = $('#id_warna').val(); 

          values = {id_tipe_kendaraan:$('#id_tipe_kendaraan').val(),
                    id_warna:$('#id_warna').val()
                   }
          $.ajax({
            url:"<?php echo site_url('dealer/po_dealer_new/getDetail');?>",
            type:"POST",
            data:values,
            cache:false,
            dataType:'JSON',
            success:function(response){
              form_.detail.current_stock = response.current_stock;
              form_.detail.monthly_sale  = response.monthly_sale;
              form_.detail.po_t1_last    = response.po_t1_last;
              form_.detail.po_t2_last    = response.po_t2_last;
              form_.detail.qty_indent    = response.qty_indent;
              form_.detail.harga         = response.harga;
              // console.log(form_.detail)
            }
          });
        },
        clearDetail: function(){
          $('#id_tipe_kendaraan').val('').trigger('change');
          // $('#id_warna').html('');
          this.detail={
            id_tipe_kendaraan : '',
            tipe_unit : '',
            id_warna :'',
            warna:'',
            current_stock : '',
            monthly_sale : '',
            po_t1_last : '',
            po_t2_last : '',
            po_fix : '',
            qty_po_t1 : '',
            qty_po_t2 : '',
            qty_indent : '',
            harga : '',
            total_harga:'',
            min_po_fix:'',
            max_po_fix:'',
            min_po_t1:'',
            max_po_t1:'',
            id_item:''
          }  
        },
        showModalItem : function() {
          // $('#tbl_part').DataTable().ajax.reload();
          $('.modalItem').modal('show');
        },
        addDetails : function(){
          // console.log(this.detail);
          if (this.detail.id_tipe_kendaraan=='' || this.detail.id_warna==''|| this.detail.po_fix==''|| this.detail.qty_po_t1==''||this.detail.qty_po_t2=='') 
          {
            alert('Isi data dengan lengkap !');
            return false;
          }

          for(dtl of this.details)
          {
            if (this.detail.id_tipe_kendaraan==dtl.id_tipe_kendaraan && this.detail.id_warna==dtl.id_warna) {
              alert('Item sudah dipilih !')
              return false;
            }
          }
          this.details.push(this.detail);
          this.clearDetail();
        },
  
        delDetails: function(index){
            this.details.splice(index, 1);
        },
        saveForm:function(save_to){
          
          if (this.details.length==0) {
            alert('Belum ada unit yang dipilih !');
            return false;
          }else{
            if (this.po_type=='add') {
              var tgl=$('#tgl').val();
              if (tgl=='') {
                alert('Tanggal PO Additional belum dipilih !');
                return false;
              }
            }else{
              var val_confirm = confirm('Are you sure ?');
              if (val_confirm==false) {
                return false;
              }
            }
          }
          var values ={detail:form_.details};
          var form = $('#form_').serializeArray();
          for (field of form) {
            values[field.name] = field.value;
          }
          values['save_to'] = save_to;
          values['po_type'] = '<?= $po_type ?>';
          values['po_number'] = '<?= $po_number ?>';

          $.ajax({
            beforeSend: function() {
              $('.btnSubmit').attr('disabled',true);
              },
            url:"<?= base_url('dealer/po_dealer_new/'.$form);?>",
            type:"POST",
            data: values,
            cache:false,
            dataType:'JSON',
            success:function(respon){
              if (respon.status=='sukses') {
                window.location = "<?= base_url('dealer/po_dealer_new') ?>";
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
        totDetail:function(detail) {
          po_fix     = detail.po_fix==''?0:detail.po_fix;
          qty_indent = detail.qty_indent==''?0:detail.qty_indent;
          total      = detail.harga * (parseInt(po_fix)+parseInt(qty_indent));
          ppn = total *(10/100);
          this.detail.total_harga = total+ppn;
          total = total+ppn;
          return total;
        },
      },
      watch:{
        detail:function () {
          // alert('dd');
        }
      },
      computed: {
        // totDetail:function(detail) {
        //   po_fix     = detail.po_fix==''?0:detail.po_fix;
        //   qty_indent = detail.qty_indent==''?0:detail.qty_indent;
        //   total      = detail.harga * (parseInt(po_fix)+parseInt(qty_indent));
        //   ppn = total *(10/100);
        //   this.detail.total_harga = total+ppn;
        //   return total;
        // },
      },
  });
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
<div class="modal fade modalItem" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Item</h4>
      </div>
      <div class="modal-body">
        <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_ahass" style="width: 100%">
                  <thead>
                  <tr>
                      <th>ID Item</th>
                      <th>Tipe Kendaraan</th>
                      <th>Warna</th>
                      <th>Action</th>
                  </tr>
                  </thead>
              </table>
              <script>
                  function pilihItem(item)
                  {
                    form_.detail={
                      id_tipe_kendaraan:item.id_tipe_kendaraan,
                      id_warna:item.id_warna,
                      warna:item.warna,
                      tipe_ahm:item.tipe_ahm,
                      tipe_unit:item.tipe_ahm,
                      id_item:item.id_item,
                    }
                    console.log(form_.detail)
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
                              url: "<?= base_url('dealer/po_dealer_new/fetch_item') ?>",
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
              </script>
      </div>
    </div>
  </div>
</div>
<?php endif ?>

</div><!-- /.box-body -->
</div><!-- /.box -->
  </section>
</div>