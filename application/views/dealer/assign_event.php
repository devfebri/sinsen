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
          <a href="dealer/assign_event">
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
            <form id="form_" class="form-horizontal" action="dealer/assign_event/<?= $form ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">    
                <div class="form-group">
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Event</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly id="kode_event" name="kode_event" value="<?= $ev->kode_event ?>">                     
                    <input type="hidden" class="form-control" readonly id="id_event" name="id_event" value="<?= $ev->id_event ?>">                     
                    <input type="hidden" class="form-control" readonly id="id_assign" name="id_assign" value="<?= $id_assign ?>">                     
                  </div>                                                    
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">PIC</label>
                  <div class="col-sm-4">
                   <select name="id_karyawan_dealer" class="form-control select2">
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
                  </div>   
                  <div class="col-sm-4">
                    <!-- <input type="text" class="form-control" id="jabatan"> -->
                  </div>
                </div>
                <div class="col-md-12">
                <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Available Promotion</button><br><br>
              </div>
              <div class="col-md-12">
                <table class="table table-bordered">
                  <thead>
                    <th>ID Sales Program</th>
                    <th>Judul</th>
                    <th width="9%" style="text-align: center;" v-if="mode=='insert'">Aksi</th>
                  </thead>
                  <tbody>
                    <tr v-for="(dtl, index) of details">
                      <td>{{dtl.id_program_ahm }}</td>
                      <td>{{dtl.judul_kegiatan}}</td>
                      <td align="center" v-if="mode=='insert'">
                        <button type="button" @click.prevent="delDetails(index)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                      </td>
                    </tr>
                  </tbody>
                  <tfoot v-if="mode=='insert'">
                    <tr>
                      <td style="width: 50%">
                        <select id="sales_program" class="form-control select2" onchange="form_.getSP()">
                          <?php
                            $date = date('Y-m-d');
                            $sp = $this->db->query("SELECT * FROM tr_sales_program 
                              -- WHERE '$date' BETWEEN periode_awal AND periode_akhir 
                              ORDER BY created_at DESC ");
                            if ($sp->num_rows()>0): ?>
                            <option value="">--choose--</option>
                            <?php foreach ($sp->result() as $spp): ?>
                              <option value="<?= $spp->id_sales_program ?>" 
                                data-id_sales_program ="<?= $spp->id_sales_program ?>"
                                data-id_program_ahm   ="<?= $spp->id_program_ahm ?>"
                                data-id_program_md    ="<?= $spp->id_program_md ?>"
                                data-judul_kegiatan="<?= $spp->judul_kegiatan ?>"><?= $spp->id_program_ahm.' | '.$spp->id_program_md ?></option>
                            <?php endforeach ?>
                          <?php endif ?>
                        </select>
                      </td>
                      <td><input type="text" class="form-control isi" disabled v-model="detail.judul_kegiatan"></td>
                      <td align="center">
                        <button type="button" @click.prevent="addDetails()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i></button> 
                      </td>
                    </tr>
                  </tfoot>
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

<script>
   var form_ = new Vue({
      el: '#form_',
      data: {
        mode : '<?= $mode ?>',
        tipe_stok_trf: '<?= isset($hdr)?$hdr->tipe_stok_trf:'' ?>',
        detail:{
          judul_kegiatan : '',
          id_sales_program : '',
          id_program_md :'',
          id_program_ahm:'',
        },
        details : <?= isset($details)?json_encode($details):'[]' ?>,
      },
      methods: {
        clearDetails: function (index) {
          this.detail={
            judul_kegiatan : '',
            id_sales_program : '',
            id_program_md :'',
            id_program_ahm:'',
          }
        },
        addDetails : function(){
          // if (this.details[index].parts.length > 0) {
          //   for (prt of this.details[index].parts) {
          //     if (part.id_part === prt.id_part) {
          //         alert("Part Sudah Dipilih !");
          //         return false;
          //     }
          //   }
          // }
          // if (this.details[index].part.id_part=='' || this.details[index].part.qty_part=='') 
          // {
          //   alert('Isi data dengan lengkap !');
          //   return false;
          // }
          this.details.push(this.detail);
          // console.log(this.details);
          this.clearDetails();
        },
        getSP: function () {
          var id_sales_program = $("#sales_program").select2().find(":selected").data("id_sales_program");
          var judul_kegiatan     = $("#sales_program").select2().find(":selected").data("judul_kegiatan");
          var id_program_ahm     = $("#sales_program").select2().find(":selected").data("id_program_ahm");
          var id_program_md      = $("#sales_program").select2().find(":selected").data("id_program_md");
          this.detail ={ id_sales_program:id_sales_program,
                         judul_kegiatan:judul_kegiatan,
                         id_program_ahm:id_program_ahm,
                         id_program_md:id_program_md,
          }
        },
        delDetails: function(index){
            this.details.splice(index, 1);
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
    url:'<?= base_url('dealer/assign_event/'.$form) ?>',
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
    url:'<?= base_url('dealer/assign_event/getDetail') ?>',
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
          <a href="dealer/assign_event">
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
            <form class="form-horizontal" action="dealer/assign_event/save" method="post" enctype="multipart/form-data">
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
    }elseif($set=="index"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">                
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
              <th>Event ID</th>
              <th>Jenis Event</th>
              <th>Nama Event</th>
              <th>Start Date</th>              
              <th>End Date</th>              
              <th>Aksi</th>         
            </tr>
          </thead>
          <tbody style="vertical-align: middle;">            
          <?php 
          $no=1; 
          foreach($assign->result() as $row) {    
            $button='';
            $btn_assign ='<a data-toggle=\'tooltip\' title="Assign Event" class=\'btn btn-primary btn-xs btn-flat\' href=\'dealer/assign_event/assign?id='.$row->id_event.'\' onclick="return confirm(\'Are You Sure To Assign This Event ?\')">Assign Event</a>';
            $btn_prepare ='<a data-toggle=\'tooltip\' title="Prepare Event" class=\'btn btn-success btn-xs btn-flat\' href=\'dealer/assign_event/prepare?id='.$row->id_event.'&id_assign='.$row->id_assign.'\'>Prepare Event</a>';
            if ($row->id_assign==null) {
              $button = $btn_assign;
            }else{
              if ($row->status=='input') {
                $button = $btn_prepare;
              }
            }
            echo "
            <tr style='vertical-align:middle'>
            <td>";?>
              <a href="<?= base_url('dealer/event_d/detail?id='.$row->id_event) ?>"><?= $row->kode_event ?></a>
             <?php echo " </td>
              <td>$row->jenis_event</td>
              <td>$row->nama_event</td>
              <td>$row->start_date</td>       
              <td>$row->end_date</td>       
            "?>                         
             <td>
              <?= $button ?>
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