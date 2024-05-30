<?php 
function bln(){
  $bulan=$bl=$month=date("m");
  switch($bulan)
  {
    case"1":$bulan  = "Januari"; break;
    case"2":$bulan  = "Februari"; break;
    case"3":$bulan  = "Maret"; break;
    case"4":$bulan  = "April"; break;
    case"5":$bulan  = "Mei"; break;
    case"6":$bulan  = "Juni"; break;
    case"7":$bulan  = "Juli"; break;
    case"8":$bulan  = "Agustus"; break;
    case"9":$bulan  = "September"; break;
    case"10":$bulan = "Oktober"; break;
    case"11":$bulan = "November"; break;
    case"12":$bulan = "Desember"; break;
  }
  $bln = $bulan;
  return $bln;
}
?>
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
    <li class="">Penerimaan Unit</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    $form='save';
    if($set=="form"){
      $disabled='';
      if ($mode=='detail') {
        $disabled='disabled';
      }
      if ($mode=='close') {
        $disabled='disabled';
        $form = 'save_close';
      }
    ?>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script>
  Vue.use(VueNumeric.default);
  $(document).ready(function(){
    <?php if (isset($row)) { ?>
        // geTglSJ('<?= $row->no_sj_outbound ?>');
    <?php } ?>
  })
</script>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/inbound">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
            <form id="form_" class="form-horizontal" action="dealer/inbound/<?= $form ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <select name="no_sj_outbound" id="no_sj_outbound" class="form-control select2"  onchange="geTglSJ()" <?= $disabled ?>>
                      <?php if ($sj->num_rows()>0): ?>
                        <option value="">--choose--</option>
                        <?php foreach ($sj->result() as $rs): 
                            $selected = isset($row->no_sj_outbound)?$row->no_sj_outbound==$rs->no_sj?'selected':'':'';
                        ?>
                          <option 
                            data-tgl_sj="<?= $rs->tgl_sj ?>"
                            data-gudang_asal="<?= $rs->asal_mutasi ?>"
                            data-kode_event="<?= $rs->kode_event ?>"
                            data-nama_event="<?= $rs->nama_event ?>"
                            value="<?= $rs->no_sj ?>" <?= $selected ?>><?= $rs->no_sj ?>
                          </option>
                        <?php endforeach ?>
                      <?php endif ?>
                    </select>
                  </div>
                </div>
                <?php if (isset($row)): ?>
                      <input type="hidden" value="<?= $row->no_sj_outbound ?>" name="no_sj_outbound" reaodnly>
                    <?php endif ?>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat jalan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly id="tgl_sj" name="tgl_sj" value="<?= isset($row->tgl_sj)?$row->tgl_sj:'' ?>">                     
                  </div>                                                    
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Event ID</label>
                  <div class="col-sm-4">
                    <?php if (isset($row)) {
                      $event = $this->db->get_where('ms_event',['id_event'=>$row->id_event]);
                    } ?>
                    <input type="text" class="form-control" readonly id="event" value="<?= isset($row->id_event)?$event->row()->kode_event:'' ?>">                 
                  </div>                                                    
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang Asal</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly id="gudang_asal" name="gudang_asal"  value="<?= isset($row->gudang_asal)?$row->gudang_asal:'' ?>">                     
                  </div>                                                    
                </div>
                <div class="form-group">
                   <label for="inputEmail3" class="col-sm-2 control-label">Gudang Tujuan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="gudang_tujuan" <?= $disabled?>>
                      <option value="">--choose--</option>
                      <?php 
                      $id_dealer = $this->m_admin->cari_dealer();
                      $dt_gudang = $this->db->query("SELECT * FROM ms_gudang_dealer WHERE id_dealer = '$id_dealer' AND active = 1 ORDER BY gudang ASC");
                      foreach($dt_gudang->result() as $rs) {          
                            $selected = isset($row->gudang_tujuan)?$row->gudang_tujuan==$rs->gudang?'selected':'':'';

                        echo "<option value='$rs->gudang' $selected>$rs->gudang</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                          
                <div class="form-group">
                  <table id="myTable" class="table myTable1 order-list table-bordered" border="0">
                    <thead>
                      <tr>
                        <th>No Mesin</th>
                        <th>No Rangka</th>
                        <th>Tipe Kendaraan</th>
                        <th>Warna</th>      
                        <th width="20%">Terima KSU</th>      
                        <th>Status</th>
                        <th width="15%">Alasan</th>
                        <th width="25%">Part</th>                      
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(dtl, index) of details">
                        <td>{{dtl.no_mesin}}
                            <input type="hidden" name="no_mesin[]" v-model="dtl.no_mesin">
                        </td>
                        <td>{{dtl.no_rangka}}</td>
                        <!-- <td>{{dtl.id_item}}</td> -->
                        <td>{{dtl.tipe_ahm}}</td>
                        <td>{{dtl.warna}}</td>
                        <td>
                          <table width="100%">
                            <tr v-for="(ksu, index) of dtl.ksu">
                              <td><button type="button" style="width: 100%;text-align: left;font-size: 10pt;margin-bottom: 4px" class="btn btn-danger btn-xs">{{ksu.ksu}}</button></td>
                              <td>&nbsp;&nbsp;<input type="checkbox" v-model="ksu.cek" :disabled="mode=='detail'||mode=='close'"></td>
                            </tr>
                          </table>
                        </td>
                        <td>
                          <select v-model="dtl.status" @change.prevent="clearParts(index)" :disabled="mode=='detail'||mode=='close'">
                            <option value="">--choose--</option>
                            <option value="rfs">RFS</option>
                            <option value="nrfs">NRFS</option>
                          </select>
                        </td>  
                         <td>
                            <input type="text" class="form-control isi" v-model="dtl.alasan" :disabled="mode=='detail'||mode=='close'">
                         </td>
                        <td>
                          <table class="table" style="margin-bottom: 0px" v-if="dtl.status=='nrfs'">
                            <tr>
                              <td>Need Parts</td>
                              <td colspan="2" style="width: 100%"><select v-model="dtl.need_parts"> 
                                <option value="">--choose--</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                              </select></td>
                            </tr>
                            <tr>
                              <td><b>Nomor Parts</b></td>
                              <td colspan="2"><b>Kuantitas Part</b></td>
                            </tr>
                            <tr v-if="mode=='insert'">
                              <td><input type="text" @click.prevent="form_.showModalPart(index)" class="form-control isi" readonly v-model="dtl.part.id_part"></td>
                              <td width="24%"><input type="text" class="form-control isi" v-model="dtl.part.qty_part"></td>
                              <td><button type="button" @click.prevent="addPart(index, dtl.part)" class="btn btn-flat btn-primary btn-xs"><i class="fa fa-plus"></i></button></td>
                            </tr>
                            <tr  v-for="(prt, indx) of dtl.parts">
                              <td>{{prt.id_part}}</td>
                              <td><input type="text" class="form-control isi" v-model="prt.qty_part" :readonly="mode=='detail' ||mode=='close'"></td>
                              <td  v-if="mode=='insert'"><button type="button" @click.prevent="delParts(index, indx)" class="btn btn-flat btn-danger btn-xs"><i class="fa fa-trash"></i></button></td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div> 

              </div><!-- /.box-body -->
              <div class="box-footer" v-if="mode!='detail'"> 
                <div class="col-sm-12" v-if="mode=='insert'" align="center">
                  <button type="button" onclick="submitBtn()" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                </div>
              </div><!-- /.box-footer -->
              <div class="box-footer" v-if="mode=='close'">
                <div class="col-sm-12" align="center">
                  <button type="button" onclick="submitBtn()" name="save" value="save" class="btn btn-info btn-flat">Close</button>             
                </div>
              </div>
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
        <h4 class="modal-title" id="myModalLabel">AHASS</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="no_mesin_part">
        <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_part" style="width: 100%">
                  <thead>
                  <tr>
                      <th>ID Part</th>
                      <th>Nama Part</th>
                      <th>Kel. Vendor</th>
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
        tipe_stok_trf: '<?= isset($hdr)?$hdr->tipe_stok_trf:'' ?>',
        detail:{
          no_mesin : '',
          no_rangka : '',
          id_item :'',
          tipe_ahm:'',
          warna : '',
          ksu : [],
        },
        index_detail_part:'',
        details : <?= isset($details)?json_encode($details):'[]' ?>,
      },
      methods: {
        clearPart: function (index) {
        this.details[index].part = {id_part : '',
                    qty_part : ''
                  }
        },
        clearParts: function (index) {
        this.details[index].parts=[];
        },
        addPart : function(index, part){
          if (this.details[index].parts.length > 0) {
            for (prt of this.details[index].parts) {
              if (part.id_part === prt.id_part) {
                  alert("Part Sudah Dipilih !");
                  return false;
              }
            }
          }
          if (this.details[index].part.id_part=='' || this.details[index].part.qty_part=='') 
          {
            alert('Isi data dengan lengkap !');
            return false;
          }
          this.details[index].parts.push(part);
          // console.log(this.details);
          this.clearPart(index);
        },
  
        delParts: function(index_dtl,index_prt){
            this.details[index_dtl].parts.splice(index_prt, 1);
        },
        showModalPart: function(index) {
          $('.modalPart').modal('show');
          this.index_detail_part = index;
          console.log(this.index_detail_part);
        }
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
 function pilihPart(part)
  {
    index = form_.index_detail_part;
    form_.details[index].part.id_part = part.id_part;
  }

function submitBtn() {
  var values = {details:form_.details};
  var form   = $('#form_').serializeArray();
  for (field of form) {
    values[field.name] = field.value;
  }
  $.ajax({
    beforeSend: function() {
      $('#submitBtn').attr('disabled',true);
    },
    url:'<?= base_url('dealer/inbound/'.$form) ?>',
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

function geTglSJ() {
  $('#event').val('');
  var tgl_sj      = $("#no_sj_outbound").select2().find(":selected").data("tgl_sj");
  var gudang_asal = $("#no_sj_outbound").select2().find(":selected").data("gudang_asal");
  var kode_event  = $("#no_sj_outbound").select2().find(":selected").data("kode_event");
  var nama_event  = $("#no_sj_outbound").select2().find(":selected").data("nama_event");
  $('#tgl_sj').val(tgl_sj);
  $('#gudang_asal').val(gudang_asal);
  $('#event').val(kode_event+' | '+nama_event);
  getDetail();
}

function getDetail() {
  values = {no_sj:$('#no_sj_outbound').val()}
  $.ajax({
    url:'<?= base_url('dealer/inbound/getDetail') ?>',
    type:"POST",
    data: values,
    cache:false,
    dataType:'JSON',
    success:function(response){
      console.log(response);
      form_.details=[];
      for (dtl of response) {
          form_.details.push(dtl);
      }
    }
  }); 
}

</script>
    <?php 
    }elseif($set=="detail"){
    ?>
    

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/inbound">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
            <form class="form-horizontal" action="dealer/inbound/save" method="post" enctype="multipart/form-data">
              <div class="box-body">    

                <?php 
                $id_dealer = $this->m_admin->cari_dealer();
                $rt = $this->m_admin->getByID("ms_dealer","id_dealer",$id_dealer)->row();
                $row2 = $dt_isi->row();
                ?>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <input type="hidden" id="id_mutasi" name="id_mutasi">                                        
                    <input type="text" required class="form-control" placeholder="Dealer" readonly value="<?php echo $rt->nama_dealer ?>" name="nama_konsumen">                                        
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Dealer</label>
                  <div class="col-sm-10">
                    <input type="text" readonly value="<?php echo $rt->alamat ?>"  class="form-control" placeholder="Alamat Dealer" name="nama_konsumen">                                        
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Asal Mutasi</label>
                  <div class="col-sm-4">
                    <input readonly value="<?php echo $row2->asal_mutasi ?>" type="text" required class="form-control" placeholder="Asal Mutasi" name="alasan">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tujuan Mutasi</label>
                  <div class="col-sm-4">
                    <input readonly value="<?php echo $row2->tujuan_mutasi ?>" type="text" required class="form-control" placeholder="Tujuan Mutasi" name="alasan">                                        
                  </div>                  
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Alasan Mutasi</label>
                  <div class="col-sm-10">
                    <input readonly value="<?php echo $row2->alasan ?>" type="text" required class="form-control" placeholder="Alasan Mutasi" name="alasan">                    
                  </div>                                                    
                </div>
                
                
                <div class="form-group">
                                    
                  
                  <table id="myTable" class="table myTable1 order-list" border="0">
                    <thead>
                      <tr>
                        <th width="15%">No Mesin</th>
                        <th width="15%">No Rangka</th>
                        <th width="10%">Kode Item</th>      
                        <th width="15%">Tipe Kendaraan</th>
                        <th width="10%">Warna</th>                                                      
                      </tr>
                    </thead> 
                  </table>
                  <table id="example2" class="table myTable1 table-bordered table-hover">
                    <?php   
                    foreach($dt_data->result() as $row) {           
                      echo "   
                      <tr>                    
                        <td width='15%'>$row->no_mesin</td>
                        <td width='15%'>$row->no_rangka</td>
                        <td width='10%'>$row->id_item</td>
                        <td width='15%'>$row->tipe_ahm</td>      
                        <td width='10%'>$row->warna</td>
                      </tr>";                    
                      }
                    ?>  
                  </table>
                  
                  
                </div> 

              </div><!-- /.box-body -->              
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
   
    <?php
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/inbound/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>          
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>No Surat Jalan</th>     
              <th>Tgl Surat Jalan</th>     
              <th>Event ID</th>
              <th>Gudang Asal</th>              
              <th>Gudang Tujuan</th>   
              <th>Status</th>
              <th>Aksi</th>         
            </tr>
          </thead>
          <tbody style="vertical-align: middle;">            
          <?php 
          $no=1; 
          foreach($dt_inbound->result() as $row) {    
            $status = '';
            $button = '';
            $btn_close = '<a href="dealer/inbound/close?id='.$row->no_sj_outbound.'" class="btn btn-warning btn-flat btn-xs">Close</a>';
            if ($row->status=='input') {
              $status = '<label class="label label-primary">Input</label>';
              $button = $btn_close;
            }
             if ($row->status=='close') {
              $status = '<label class="label label-warning">Close</label>';
            }
            echo "
            <tr style='vertical-align:middle'>
              <td>$no</td>
              <td><a href='dealer/inbound/detail?id=$row->no_sj_outbound'>$row->no_sj_outbound</a></td>
              <td>$row->tgl_sj</td>
              <td>$row->kode_event</td>
              <td>$row->gudang_asal</td>       
              <td>$row->gudang_tujuan</td>  
              <td>$status</td>  
              <td>$button</td>  
            </tr>";
          $no++;
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



<div class="modal fade" id="Nosinmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search No Mesin
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>ID Item</th>
              <th>Tipe Kendaraan</th>                                    
              <th>Warna</th>                                               
              <th>No Mesin</th>
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1; 
          $id_dealer = $this->m_admin->cari_dealer();
          $dt_item = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,tr_scan_barcode.id_item,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_penerimaan_unit_dealer_detail
                LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
                WHERE tr_penerimaan_unit_dealer.id_dealer = '$row->id_dealer' AND tr_scan_barcode.tipe = 'RFS' 
                AND tr_scan_barcode.status = '4'");
          // $dt_item = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,tr_scan_barcode.id_item,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin=tr_scan_barcode.no_mesin
          //   INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
          //   INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
          //   INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
          //   INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
          //   WHERE tr_penerimaan_unit_dealer.id_dealer = '$id_dealer'
          //   ");
          foreach ($dt_item->result() as $ve2) {
            echo "
            <tr>
              <td>$no</td>
              <td>$ve2->id_item</td>
              <td>$ve2->tipe_ahm</td>
              <td>$ve2->warna</td>
              <td>$ve2->no_mesin</td>";
              ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="chooseitem('<?php echo $ve2->no_mesin; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
              </td>           
            </tr>
            <?php
            $no++;
          }
          ?>
          </tbody>
        </table>
      </div>      
    </div>
  </div>
</div>

<script type="text/javascript">
function auto(){  
  var tgl = "1";
  $.ajax({
      url : "<?php echo site_url('dealer/inbound/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_mutasi").val(data[0]);        
        // kirim_data();      
      }        
  })
}
function chooseitem(no_mesin){
  document.getElementById("no_mesin").value = no_mesin; 
  cek_nosin();
  $("#Nosinmodal").modal("hide");
}

function hide_po(){
    $("#tampil_po").hide();
}
function kirim_data(){    
  $("#tampil_data").show();  
  var id_mutasi = document.getElementById("id_mutasi").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_mutasi="+id_mutasi;
     xhr.open("POST", "dealer/inbound/t_data", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_data").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }   
}
function simpan_data(){
  var no_mesin    = document.getElementById("no_mesin").value;  
  var id_mutasi   = document.getElementById("id_mutasi").value;     
  //alert(id_po);
  if (id_mutasi == "" || no_mesin == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('dealer/inbound/save_data')?>",
          type:"POST",
          data:"id_mutasi="+id_mutasi+"&no_mesin="+no_mesin,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="nihil"){
                  kirim_data();
                  kosong();                              
              }else{
                  alert(data[0]);
                  kosong();                      
              }                
          }
      })    
  }

}

function kosong(args){
  $("#no_mesin").val("");  
}
function hapus_data(a,b){ 
    var id_mutasi  = a;   
    var id_item   = b;       
    $.ajax({
        url : "<?php echo site_url('dealer/inbound/delete_data')?>",
        type:"POST",
        data:"id_mutasi_detail="+id_mutasi_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data();
            }
        }
    })
}
</script>