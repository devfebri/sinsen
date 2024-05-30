<?php 
function bln(){
  $bulan=$bl=$month=date("m");
  switch($bulan)
  {
    case"1":$bulan="Januari"; break;
    case"2":$bulan="Februari"; break;
    case"3":$bulan="Maret"; break;
    case"4":$bulan="April"; break;
    case"5":$bulan="Mei"; break;
    case"6":$bulan="Juni"; break;
    case"7":$bulan="Juli"; break;
    case"8":$bulan="Agustus"; break;
    case"9":$bulan="September"; break;
    case"10":$bulan="Oktober"; break;
    case"11":$bulan="November"; break;
    case"12":$bulan="Desember"; break;
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
<body onload="auto()">
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
          <a href="dealer/mutasi_stok">
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
            <form id="form_" class="form-horizontal" action="dealer/mutasi_stok/<?= $form ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">    

                <?php 
                $id_dealer = $this->m_admin->cari_dealer();
                $rt = $this->m_admin->getByID("ms_dealer","id_dealer",$id_dealer)->row();
                ?>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <?php if (isset($hdr)){ ?>
                      <input type="hidden" name="id_mutasi" value="<?= $hdr->id_mutasi ?>">
                    <?php }else{ ?>
                      <input type="hidden" id="id_mutasi" name="id_mutasi">
                    <?php } ?>                                       
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Stok Transfer</label>
                  <div class="col-sm-4">
                    <select name="tipe_stok_trf" v-model="tipe_stok_trf" class="form-control" <?= $disabled ?>>
                      <option value="">--choose--</option>
                      <option value="gudang">Gudang</option>
                      <option value="pos">POS</option>
                      <option value="event">Event / Exhibition</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang Asal</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="asal_mutasi" <?= $disabled ?>>
                      <option value="">- choose -</option>
                      <?php 
                      $id_dealer = $this->m_admin->cari_dealer();
                      $dt_gudang = $this->db->query("SELECT * FROM ms_gudang_dealer WHERE id_dealer = '$id_dealer' AND active = 1 ORDER BY gudang ASC");
                      foreach($dt_gudang->result() as $row) {   
                          $selected = isset($hdr->asal_mutasi)?$hdr->asal_mutasi==$row->gudang?'selected':'':''; 
                        echo "<option value='$row->gudang|$row->id_gudang_dealer' $selected>$row->gudang</option>";
                      }
                      ?>
                    </select>
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label"  v-if="tipe_stok_trf=='gudang' || tipe_stok_trf=='pos'">Gudang Tujuan</label>
                  <div class="col-sm-4"  v-if="tipe_stok_trf=='gudang' || tipe_stok_trf=='pos'">
                    <select class="form-control select2" name="tujuan_mutasi" <?= $disabled ?>>
                      <option value="">--choose--</option>
                      <?php 
                      $id_dealer = $this->m_admin->cari_dealer();
                      $dt_gudang = $this->db->query("SELECT * FROM ms_gudang_dealer WHERE id_dealer = '$id_dealer' AND active = 1 ORDER BY gudang ASC");
                      foreach($dt_gudang->result() as $row) {          
                          $selected = isset($hdr->tujuan_mutasi)?$hdr->tujuan_mutasi==$row->gudang?'selected':'':'';
                        echo "<option value='$row->gudang' $selected>$row->gudang</option>";
                      }
                      ?>
                    </select>
                  </div>   
                   <label for="inputEmail3" class="col-sm-2 control-label" v-if="tipe_stok_trf=='event'">Event</label>
                  <div class="col-md-4" v-if="tipe_stok_trf=='event'">
                    <select class="form-control select2" name="id_event"<?= $disabled ?>>
                      <?php if ($event->num_rows()>0): ?>
                        <option value="">--choose--</option>
                        <?php foreach ($event->result() as $ev): 
                          $selected = isset($hdr->id_event)?$hdr->id_event==$ev->id_event?'selected':'':'';
                        ?>
                          <option value="<?= $ev->id_event ?>" <?= $selected ?>><?= $ev->nama_event ?></option>
                        <?php endforeach ?>
                      <?php endif ?>
                    </select>
                  </div>               
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Keterangan" name="keterangan" value="<?= isset($hdr->keterangan)?$hdr->keterangan:'' ?>" <?= $disabled ?>>                     
                  </div>                                                    
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Created By</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" readonly value="<?= $this->session->userdata('nama'); ?>">                    
                  </div>                                                    
                </div>    
                <div class="form-group" v-if="mode=='close'">                 
                  <label for="inputEmail3" class="col-sm-2 control-label">Closed By</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" readonly value="<?= isset($hdr->closed_by)?$hdr->closed_by:$this->session->userdata('nama') ?>">                    
                  </div>                                                    
                </div>                
                <div class="form-group">
                  <table id="myTable" class="table myTable1 order-list table-bordered" border="0">
                    <thead>
                      <tr>
                        <th width="5%">No</th>
                        <th width="15%">No Mesin</th>
                        <th width="15%">No Rangka</th>
                        <!-- <th width="10%">Kode Item</th>       -->
                        <th width="15%">Tipe Kendaraan</th>
                        <th width="10%">Warna</th>      
                        <th v-if="mode=='close'" width="5%">Close</th>
                        <th width="20%" v-if="mode!='close'">KSU</th>      
                        <th width="5%" align="center" v-if="mode=='insert'">Action</th>                      
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(dtl, index) of details">
                        <td>{{index+1}}</td>
                        <td>{{dtl.no_mesin}}
                            <input type="hidden" name="no_mesin[]" v-model="dtl.no_mesin">
                        </td>
                        <td>{{dtl.no_rangka}}</td>
                        <!-- <td>{{dtl.id_item}}</td> -->
                        <td>{{dtl.tipe_ahm}}</td>
                        <td>{{dtl.warna}}</td>
                        <td v-if="mode=='close'" align="center"><input type="checkbox" v-model="dtl.close" :disabled="mode=='detail'"></td>
                        <td  v-if="mode!='close'">
                          <table width="100%">
                            <tr v-for="(ksu, index) of dtl.ksu">
                              <td><button type="button" style="width: 100%;text-align: left;font-size: 10pt;margin-bottom: 4px" class="btn btn-danger btn-xs">{{ksu.ksu}}</button></td>
                              <td>&nbsp;&nbsp;<input type="checkbox" v-model="ksu.cek" :disabled="mode=='detail'"></td>
                            </tr>
                          </table>
                        </td>  
                        <td align="center" v-if="mode=='insert'"> 
                          <button type="button" @click.prevent="delDetails(index)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>            
                        </td>  
                      </tr>
                      <tr>
                        <td colspan="5" align="right"><b>Total</b></td>
                        <td><b>{{totalDetails}} Unit</b></td>
                        <td v-if="mode=='insert'"></td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr v-if="mode=='insert'">
                        <td></td>
                        <td>
                          <input id="no_mesin" readonly type="text" data-toggle="modal" data-target="#Nosinmodal" v-model="detail.no_mesin" class="form-control isi" placeholder="No Mesin">
                        </td>
                        <td>
                          <input type="text" data-toggle="modal" data-target="#Nosinmodal" placeholder="No Rangka" class="form-control isi" v-model="detail.no_rangka" readonly>
                        </td>
                        <!-- <td>
                          <input type="text" data-toggle="modal" data-target="#Nosinmodal" placeholder="Kode Item" class="form-control isi" v-model="detail.id_item" readonly>
                        </td> -->
                        <td>
                          <input type="text"  readonly class="form-control isi" placeholder="Tipe Kendaraan" v-model="detail.tipe_ahm">
                        </td>      
                         <td>
                          <input type="text" id="warna" readonly class="form-control isi" placeholder="Warna" v-model="detail.warna">
                        </td>        
                        <td  v-if="mode!='close'">
                          <table width="100%">
                            <tr v-for="(ksu, index) of detail.ksu">
                              <td><button type="button" style="width: 100%;text-align: left;font-size: 10pt;margin-bottom: 4px" class="btn btn-danger btn-xs">{{ksu.ksu}}</button></td>
                              <td>&nbsp;&nbsp;<input type="checkbox" v-model="ksu.cek"></td>
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

              </div><!-- /.box-body -->
              <div class="box-footer" v-if="mode!='detail'"> 
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10" v-if="mode=='insert'">
                  <button type="button" onclick="submitBtn()" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
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
        details : <?= isset($details)?json_encode($details):'[]' ?>,
      },
      methods: {
        clearDetail: function () {
        this.detail = {no_mesin : '',
                    no_rangka : '',
                    id_item   :'',
                    tipe_ahm  :'',
                    warna     : ''
                  }
        },
        addDetails : function(){
          if (this.details.length > 0) {
            for (detail of this.details) {
              if (detail.no_mesin === this.detail.no_mesin) {
                  alert("No Mesin Sudah Dipilih !");
                  this.clearDetail();
                  return;
              }
            }
          }
          if (this.detail.no_mesin=='') 
          {
            alert('Pilih No. Mesin !');
            return false;
          }
          this.details.push(this.detail);
          this.clearDetail();
        },
  
        delDetails: function(index){
            this.details.splice(index, 1);
        },
      },
      watch:{
        detail:function () {
          // alert('dd');
        }
      },
      computed: {
          totalDetails: function(){
            return this.details.length;
          }
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
function submitBtn() {
  var values = {details:form_.details,};
  var form   = $('#form_').serializeArray();
  for (field of form) {
    values[field.name] = field.value;
  }
  $.ajax({
    beforeSend: function() {
      $('#submitBtn').attr('disabled',true);
    },
    url:'<?= base_url('dealer/mutasi_stok/'.$form) ?>',
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
function cek_nosin(){
  var no_mesin  = $("#no_mesin").val();                         
  $.ajax({
      url: "<?php echo site_url('dealer/mutasi_stok/cek_data')?>",
      type:"POST",
      data:"no_mesin="+no_mesin,
      dataType:'JSON',
      cache:false,
      success:function(rsp){               
        if (rsp.status=='ok') {
          form_.detail = { no_mesin: rsp.no_mesin,
                            no_rangka : rsp.no_rangka,
                            tipe_ahm : rsp.tipe_ahm,
                            warna : rsp.warna,
                            id_item : rsp.id_item,
                            ksu : rsp.ksu
            } 
        } 
        console.log(form_.detail)
          // data=msg.split("|");
          // if(data[0]=="ok"){
          //   form_.detail = { no_mesin: data[1],
          //                   no_rangka : data[2],
          //                   tipe_ahm : data[3],
          //                   warna : data[4],
          //                   id_item : data[5]
          //   }          
          //   // $("#no_mesin").val(data[1]);                
          //   // $("#no_rangka").val(data[2]);                
          //   // $("#tipe_ahm").val(data[3]);
          //   // $("#warna").val(data[4]);                                    
          //   // $("#id_item").val(data[5]);                                    
          // }else{
          //   alert(data[0]);
          // }
      } 
  })
}
</script>
    <?php 
    }elseif($set=="detail"){
    ?>
    

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/mutasi_stok">
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
            <form class="form-horizontal" action="dealer/mutasi_stok/save" method="post" enctype="multipart/form-data">
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
          <a href="dealer/mutasi_stok/add">
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
              <th>No Mutasi</th>     
              <th>Tipe Stok Transfer</th>
              <th>Gudang Asal</th>              
              <th>Gudang Tujuan</th>              
              <th>Qty Mutasi</th>                 
              <th>No Surat Jalan</th>           
              <th>Status</th>     
              <th>Aksi</th>         
            </tr>
          </thead>
          <tbody style="vertical-align: middle;">            
          <?php 
          $no=1; 
          foreach($dt_mutasi->result() as $row) {    
            $cek_print = 0;
            $s = $this->db->query("SELECT count(no_mesin) as qty FROM tr_mutasi_detail WHERE id_mutasi = '$row->id_mutasi'")->row();
            $tipe='';
            if ($row->tipe_stok_trf=='event')$tipe = 'Event';
            if ($row->tipe_stok_trf=='pos')$tipe = 'Pos';
            if ($row->tipe_stok_trf=='gudang')$tipe = 'Gudang';
            echo "
            <tr style='vertical-align:middle'>
              <td>$no</td>
              <td><a href='dealer/mutasi_stok/detail?id=$row->id_mutasi'>$row->id_mutasi</a></td>
              <td>$tipe</td>
              <td>$row->asal_mutasi</td>
              <td>$row->tujuan_mutasi</td>
              <td>$s->qty unit</td>       
              <td>$row->no_sj</td>                            
              <td>$row->status_mutasi</td>"?>                         
              <td>

                <?php 
                  if ($row->status_mutasi=='open'): ?>
                  <a style="margin-bottom: 1px" onclick="return confirm('Apakah anda yakin ?')" href="dealer/mutasi_stok/konfirmasi_transfer?id=<?= $row->id_mutasi ?>" class="btn btn-primary btn-xs">Konfirmasi Transfer</a>
                <?php endif ?>
                <?php if ($row->status_mutasi=='intransit'): ?>
                  <?php if ($row->print_list_ke>0)$cek_print=1 ?>
                  <a style="margin-bottom: 1px" href="dealer/mutasi_stok/print_list_unit_trf?id=<?= $row->id_mutasi ?>" class="btn btn-success btn-xs"><i class="fa fa-print"></i> List Unit Transfer</a>
                  <?php if ($row->tipe_stok_trf=='event'):
                      $cek_inbound = $this->db->get_where('tr_inbound',['no_sj_outbound'=>$row->no_sj,'status'=>'close'])->num_rows();
                      $cek_print = $cek_inbound>0?1:0;
                  ?>
                    <a href="dealer/mutasi_stok/print_sj?id=<?= $row->id_mutasi ?>" class="btn btn-success btn-xs"><i class="fa fa-print"></i> Surat Jalan</a>
                  <?php endif ?>
                   <?php if ($cek_print>0): ?>
                     <a href="dealer/mutasi_stok/close?id=<?= $row->id_mutasi ?>" class="btn btn-warning btn-xs">Close</a>
                   <?php endif ?>
                <?php endif ?>
              </td>
            </tr>
          <?php
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
      url : "<?php echo site_url('dealer/mutasi_stok/cari_id')?>",
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
     xhr.open("POST", "dealer/mutasi_stok/t_data", true); 
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
          url : "<?php echo site_url('dealer/mutasi_stok/save_data')?>",
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
        url : "<?php echo site_url('dealer/mutasi_stok/delete_data')?>",
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