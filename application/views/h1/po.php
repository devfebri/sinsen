<?php 
function bln(){
  $bulan=$bl=$month=date("m");
  switch($bulan)
  {
    case"01":$bulan="Januari"; break;
    case"02":$bulan="Februari"; break;
    case"03":$bulan="Maret"; break;
    case"04":$bulan="April"; break;
    case"05":$bulan="Mei"; break;
    case"06":$bulan="Juni"; break;
    case"07":$bulan="Juli"; break;
    case"08":$bulan="Agustus"; break;
    case"09":$bulan="September"; break;
    case"10":$bulan="Oktober"; break;
    case"11":$bulan="November"; break;
    case"12":$bulan="Desember"; break;
  }
  $bln = $bulan;
  return $bln;
}
function bln_1(){
  $bulan=date("m")+1;
  switch($bulan)
  {
    case"01":$bulan="Januari"; break;
    case"02":$bulan="Februari"; break;
    case"03":$bulan="Maret"; break;
    case"04":$bulan="April"; break;
    case"05":$bulan="Mei"; break;
    case"06":$bulan="Juni"; break;
    case"07":$bulan="Juli"; break;
    case"08":$bulan="Agustus"; break;
    case"09":$bulan="September"; break;
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
<?php 
if(isset($_GET['id'])){
?>
<body onload="cek_jenis()">
<?php }else{ ?>
<body onload="auto()">
<?php } ?>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Pembelian</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="insert"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/po">
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
            <form class="form-horizontal" action="h1/po/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                 <!--  <label for="inputEmail3" class="col-sm-2 control-label">No.PO</label>
                  <div class="col-sm-4"> -->
                    <input type="hidden" required class="form-control"  id="id_po" readonly placeholder="No.PO" name="id_po">
                    <input type="hidden" id="tgl" value="<?php echo date("d-M-y") ?>">
                    <input type="hidden" id="mode" value="add">
                  <!-- </div> -->
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis PO</label>
                  <div class="col-sm-4">
                    <input id="jenis_po" name="jenis_po" onchange="cek_jenis()" readonly value="<?php echo $jenis ?>" class="form-control">                    
                  </div>
                </div>                                                                                      
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                  <div class="col-sm-4">
                   
                    <select class="form-control" name="bulan" id="bulan" onchange="cek_bulan()">
                      <option value="<?php echo date("m") ?>"><?php echo bln() ?></option>
                      <option value="01">Januari</option>
                      <option value="02">Februari</option>
                      <option value="03">Maret</option>
                      <option value="04">April</option>
                      <option value="05">Mei</option>
                      <option value="06">Juni</option>
                      <option value="07">Juli</option>
                      <option value="08">Agustus</option>
                      <option value="09">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">November</option>
                      <option value="12">Desember</option>
                    </select> 
                    <!--<select class="form-control" name="bulan" id="bulan" onchange="cek_bulan()">
                      <option value="<?php echo date("m") ?>"><?php echo bln() ?></option>
                      <option value="<?php echo date("m")+1 ?>"><?php echo bln_1() ?></option>
                    </select> -->
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="tahun" id="tahun" onchange="cek_bulan()">
                      <option><?php echo date("Y") ?></option>
                      <?php /*
                      $y = date("Y");
                      for ($i=$y - 10; $i <= $y + 10; $i++) { 
                        echo "<option>$i</option>";
                      }*/
                      ?><option><?php echo date("Y")+1 ?></option>                          
                    </select>
                  </div>
                </div>                                            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Keterangan" name="ket">
                  </div>
                </div>    
                <?php /* ?><div class="form-group">                
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>"> <?php */ ?>
                      <input type="hidden" class="form-control flat-red" name="active" value="1" checked>
                   <?php /* ?>   Active
                    </div>
                  </div>             
                </div> <?php */ ?>

                <hr>                
                <div class="form-group">
                                    
                  
                  <span id="tampil_po"></span>                                                                                  
                  
                  
                </div>                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="button" onClick="cancel_tr()" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    
    

    <?php 
    }elseif($set=="edit"){ 
      $row = $dt_po->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/po">
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
            <form class="form-horizontal" action="h1/po/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_po ?>" />
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.PO</label>
                  <div class="col-sm-4">
                    <input type="text" required value="<?php echo $row->id_po ?>" readonly class="form-control"  id="id_po" placeholder="No.PO" name="id_po">
                    <input type="hidden" id="tgl" value="<?php echo date("d-M-y") ?>">
                    <input type="hidden" id="mode" value="edit">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis PO</label>
                  <div class="col-sm-4">
                    <input id="jenis_po" name="jenis_po" onchange="cek_jenis()" readonly value="<?php echo $row->jenis_po ?>" class="form-control">                    
                  </div>
                </div>                                                                                      
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="bulan" readonly id="bulan">
                      <option value="<?php echo $row->bulan ?>"><?php echo bln($row->bulan) ?></option>                                            
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>
                  <div class="col-sm-4">
                    <select class="form-control" readonly name="tahun" id="tahun">
                      <option value="<?php echo $row->tahun ?>"><?php echo $row->tahun ?></option>                      
                    </select>
                  </div>
                </div>                                            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->ket ?>" class="form-control" id="inputEmail3" placeholder="Keterangan" name="ket">
                  </div>
                </div>    
                <div class="form-group">                
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="form-control flat-red" name="active" value="1" checked>
                      Active
                    </div>
                  </div>             
                </div>

                <hr>                
                <div class="form-group">
                                    
                  
                  <span id="tampil_po"></span>                                                                                  
                  
                  
                </div>                                                 
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Update</button>                
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Reset</button>                                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
<?php 
    }elseif($set=="edit_reg"){ ?>
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
          <a href="h1/po">
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
            <form id="form_" class="form-horizontal" action="h1/po/update_reg" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_po ?>" />
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.PO</label>
                  <div class="col-sm-4">
                    <input type="text" required value="<?php echo $row->id_po ?>" readonly class="form-control"  id="id_po" placeholder="No.PO" name="id_po">
                    <input type="hidden" id="tgl" value="<?php echo date("d-M-y") ?>">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis PO</label>
                  <div class="col-sm-4">
                    <input id="jenis_po" name="jenis_po" readonly value="<?php echo $row->jenis_po ?>" class="form-control">                    
                  </div>
                </div>                                                                                      
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="bulan" readonly id="bulan">
                      <option value="<?php echo $row->bulan ?>"><?php echo $row->bulan ?></option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>
                  <div class="col-sm-4">
                    <select class="form-control" readonly name="tahun" id="tahun">
                      <option value="<?php echo $row->tahun ?>"><?php echo $row->tahun ?></option>
                    </select>
                  </div>
                </div>                                            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->ket ?>" class="form-control" id="inputEmail3" placeholder="Keterangan" name="ket">
                  </div>
                </div>    
                <div class="form-group">                
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="form-control flat-red" name="active" value="1" checked>
                      Active
                    </div>
                  </div>             
                </div>

                <hr>                
                <div class="col-md-12">
                  <table class="table table-bordered">
                  <thead>
                    <th width="7%">ID Item</th>
                    <th width="15%">Tipe</th>
                    <th width="10%">Warna</th>
                    <th width="8%">On Hand</th>
                    <th width="10%">Qty Niguri Fix</th>
                    <th width="10%">Qty PO Fix</th>      
                    <th width="10%">Qty PO T1</th>        
                    <th width="10%">Qty PO T2</th>
                    <th width="9%" style="text-align: center;">Aksi</th>
                  </thead>
                  <tbody>
                    <tr v-for="(dtl, index) of details">
                      <td>{{dtl.id_item}}</td>
                      <td>{{dtl.tipe_ahm}}</td>
                      <td>{{dtl.warna}}</td>
                      <td>
                        <input type="text" class="form-control isi" v-model="dtl.on_hand" readonly>
                      </td>
                      <td>
                        <input type="text" class="form-control isi" v-model="dtl.qty_niguri_fix" readonly>
                      </td>
                       <td>
                        <input type="text" class="form-control isi" v-model="dtl.qty_po_fix">
                      </td>
                      <td>
                        <input type="text" class="form-control isi" v-model="dtl.qty_po_t1">
                      </td>
                      <td>
                        <input type="text" class="form-control isi" v-model="dtl.qty_po_t2">
                      </td>
                     <td align="center">
                        <button type="button" @click.prevent="delDetails(index)" class="btn btn-sm btn-danger btn-flat btn-xs"><i class="fa fa-trash"></i></button>
                      </td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td width="7%">
                        <input id="id_item" readonly type="text" data-toggle="modal" data-target="#modal_item" class="form-control isi" placeholder="ID Item" v-model="detail.id_item">
                      </td>
                      <td width="15%">
                        <input type="text" data-toggle="modal" data-target="#modal_item" placeholder="Tipe" class="form-control isi" readonly v-model="detail.tipe_ahm">
                      </td>
                      <td width="10%">
                        <input type="text" data-toggle="modal" data-target="#modal_item" placeholder="Warna" class="form-control isi" readonly v-model="detail.warna">
                      </td>
                      <td width="10%">
                        <input type="text" class="form-control isi" v-model="detail.on_hand" readonly>
                      </td>
                      <td width="10%">
                        <input type="text" class="form-control isi" v-model="detail.qty_niguri_fix" readonly>
                      </td>
                      <td width="10%">
                        <input type="text" class="form-control isi" v-model="detail.qty_po_fix">
                      </td>
                      <td width="10%">
                        <input type="text" class="form-control isi" v-model="detail.qty_po_t1">
                      </td>
                      <td width="10%">
                        <input type="text" class="form-control isi" v-model="detail.qty_po_t2">
                      </td>
                      <td align="center">
                        <button type="button" @click.prevent="addDetails()" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-plus"></i></button> 
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>                                               
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="button" @click.prevent="submitData" name="process" value="edit" class="btn btn-info btn-flat" id="submitBtn"><i class="fa fa-edit" ></i> Update</button>                
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Reset</button>                                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
<div class="modal fade" id="modal_item">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search Item
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
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1; 
          foreach ($dt_item->result() as $ve2) {
            echo "
            <tr>
              <td>$no</td>
              <td>$ve2->id_item</td>
              <td>$ve2->tipe_ahm</td>
              <td>$ve2->warna</td>";
              ?>
              <td class="center">
                <button title="Choose" onclick='return form_.pilihItem(<?= json_encode($ve2) ?>)' data-dismiss="modal" class="btn btn-flat btn-success btn-xs"><i class="fa fa-check"></i></button>                 
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
<script>
  var form_ = new Vue({
      el: '#form_',
      data: {
        mode : 'edit',
        detail :{
          id_item:'',
          tipe_ahm:'',
          warna:'',
          on_hand:'',
          qty_niguri_fix:'',
          qty_po_fix:'',
          qty_po_t1:'',
          qty_po_t2:'',
        },
        details : <?= isset($details)?json_encode($details):'[]' ?>
      },
    methods: {
      clearDetails: function () {
      this.detail = {
           id_item:'',
          tipe_ahm:'',
          warna:'',
          on_hand:'',
          qty_niguri_fix:'',
          qty_po_fix:'',
          qty_po_t1:'',
          qty_po_t2:'',
        }
      },
      addDetails : function(){
        if (this.details.length > 0) {
          for (dl of this.details) {
            if (dl.id_item === this.detail.id_item) {
                alert("Item Sudah Dipilih !");
                this.clearDetails();
                return;
            }
          }
        }
        if (this.detail.id_item=='') 
        {
          alert('Pilih Item !');
          return false;
        }
        this.details.push(this.detail);
        this.clearDetails();
      },

      delDetails: function(index){
          this.details.splice(index, 1);
      },
      pilihItem: function (item) {
        values = {id_item:item.id_item,
                  bulan:$('#bulan').val(),
                  tahun:$('#tahun').val()
                }
        $.ajax({
          beforeSend: function() {
            // $('#submitBtn').attr('disabled',true);
          },
          url:'<?= base_url('h1/po/cek_item_edit') ?>',
          type:"POST",
          data: values,
          cache:false,
          dataType:'JSON',
          success:function(response){
            form_.detail ={
              id_item:item.id_item,
              tipe_ahm:item.tipe_ahm,
              warna:item.warna,
              on_hand:response.on_hand,
              qty_niguri_fix:response.qty_niguri_fix,
              qty_po_fix:response.qty_po_fix,
              qty_po_t1:response.qty_po_t1,
              qty_po_t2:response.qty_po_t2,
            };
            // console.log(form_.detail)
          },
          error:function(){
            alert("Something Went Wrong");
            // $('#submitBtn').attr('disabled',false);

          },
          statusCode: {
            500: function() { 
              alert('fail, Error 500');
              // $('#submitBtn').attr('disabled',false);

            }
          }
        });
      },
      submitData: function() {
        if (form_.details.length==0) {
          alert('Belum ada Item yang Dipilih !');
          return false;
        }
        var values = {details:form_.details};
        var form   = $('#form_').serializeArray();
        for (field of form) {
          values[field.name] = field.value;
        }
        $.ajax({
          beforeSend: function() {
            $('#submitBtn').attr('disabled',true);
          },
          url:'<?= base_url('h1/po/save_edit') ?>',
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
    },
  });
</script>
    <?php 
    }elseif($set=="detail"){
      $row = $dt_po->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/po">
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
            <form class="form-horizontal" action="h1/po/save_approval" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_po ?>" />
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.PO</label>
                  <div class="col-sm-4">
                    <input type="text" required value="<?php echo $row->id_po ?>" readonly class="form-control"  id="id_po" placeholder="No.PO" name="id_po">
                    <input type="hidden" id="tgl" value="<?php echo date("d-M-y") ?>">
                    <input type="hidden" id="mode" value="detail">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis PO</label>
                  <div class="col-sm-4">
                    <input id="jenis_po" name="jenis_po" onchange="cek_jenis()" readonly value="<?php echo $row->jenis_po ?>" class="form-control">                    
                  </div>
                </div>                                                                                      
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="bulan" readonly id="bulan">
                      <option value="<?php echo $row->bulan ?>"><?php echo bln($row->bulan) ?></option>                                            
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>
                  <div class="col-sm-4">
                    <select class="form-control" readonly name="tahun" id="tahun">
                      <option value="<?php echo $row->tahun ?>"><?php echo $row->tahun ?></option>                      
                    </select>
                  </div>
                </div>                                            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->ket ?>" readonly class="form-control" id="inputEmail3" placeholder="Keterangan" name="ket">
                  </div>
                </div>    
                

                <hr>                
                <div class="form-group">
                                    
                  
                  <span id="tampil_po"></span>                                                                                  
                  
                  
                </div>                                                 
              </div><!-- /.box-body -->
              <?php if($set2=='tombol'){ ?>
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" <?php echo $this->m_admin->set_tombol($id_menu,$group,"approval"); ?> onclick="return confirm('Are you sure to approve this PO?')" name="approval" value="approve" class="btn btn-info btn-flat"><i class="fa fa-check"></i> Approve</button>                
                  <button type="submit" <?php echo $this->m_admin->set_tombol($id_menu,$group,"approval"); ?> onclick="return confirm('Are you sure to reject this PO?')" name="approval" value="reject" class="btn btn-danger btn-flat"><i class="fa fa-close"></i> Reject</button>                
                </div>
              </div><!-- /.box-footer -->
              <?php } ?>
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
          <a href="h1/po/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>          
          
          <!--a href="h1/po/download(20171000004)">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Download</button>
          </a-->          
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
              <th>No.PO</th>              
              <th>Bulan</th>              
              <th>Tahun</th>
              <th>Keterangan</th>
              <th>Jenis PO</th>
              <th>Status</th>
              <!-- <th width="5%">Active</th> -->
              <th width="17%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_po->result() as $row) {       
            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";
            
            $edit = $this->m_admin->set_tombol($id_menu,$group,'edit');
            $delete = $this->m_admin->set_tombol($id_menu,$group,'delete');
            $print = $this->m_admin->set_tombol($id_menu,$group,'print');
            $approval = $this->m_admin->set_tombol($id_menu,$group,'approval');
            $download = $this->m_admin->set_tombol($id_menu,$group,'download');
                
            $tomb='';$status='';
            if($row->jenis_po == 'PO Reguler'){
              $download = "<a $download data-toggle=\"tooltip\" title=\"Download PO\" class=\"btn btn-warning btn-sm btn-flat btn-xs\" href=\"h1/po/download?id=$row->id_po\"><i class=\"fa fa-download\"></i></a>";
              $send ="<a $approval data-toggle=\"tooltip\" title=\"Send PO to AHM\" class=\"btn btn-success btn-sm btn-flat btn-xs\" href=\"h1/po/send?id=$row->id_po\"><i class=\"fa fa-send\"></i></a>";
              $approval_md = "<a $approval data-toggle=\"tooltip\" title=\"Approve Data\" onclick=\"return confirm('Are you sure to approve this data?')\" class=\"btn btn-primary btn-xs btn-flat\" href=\"h1/po/approve_reg?id=$row->id_po\"><i class=\"fa fa-check\"></i></a>";
              $appr_ahm = "<a $approval data-toggle=\"tooltip\" title=\"Approve Data\" onclick=\"return confirm('Are you sure to approved by AHM for this data?')\" class=\"btn btn-primary btn-xs btn-flat\" href=\"h1/po/approve_reg_ahm?id=$row->id_po\"><i class=\"fa fa-check\"></i> AHM</a>";
              $rjct_ahm = "<a $approval data-toggle=\"tooltip\" title=\"Approve Data\" onclick=\"return confirm('Are you sure to rejected by AHM for this data?')\" class=\"btn btn-danger btn-xs btn-flat\" href=\"h1/po/reject_reg_ahm?id=$row->id_po\"><i class=\"fa fa-close\"></i> AHM</a>";
              $edit = "<a $edit data-toggle=\"tooltip\" title=\"Edit Data\" class=\"btn btn-primary btn-sm btn-flat btn-xs\" href=\"h1/po/edit_reg?id=$row->id_po\"><i class=\"fa fa-edit\"></i></a>
                      ";
              if ($row->status=='input') {
                $tomb = $edit.' '.$approval_md;
                $status = "<span class='label label-warning'>$row->status</span>";
              }elseif($row->status=='approved'){
                  if ($row->submitted==1) {
                  $status = "<span class='label label-success'>Submitted</span>";
                    $tomb=$appr_ahm.' '.$rjct_ahm;
                  }else {
                    $tomb=$download.' '.$edit; //.$send . ' '
                    $status = "<span class='label label-primary'>Approved</span>";
                  }
              }elseif ($row->status=='approved_ahm') {
                $status = "<span class='label label-success'>Approved By AHM</span>";
              }elseif ($row->status=='reject_ahm') {
                $status = "<span class='label label-danger'>Rejected By AHM</span>";
                $tomb = $edit.' '.$approval_md;
              }
            }else{
              if($row->status=='input'){
                $status = "<span class='label label-warning'>$row->status</span>";
                $tomb = "<a $delete data-toggle=\"tooltip\" title=\"Delete Data\" onclick=\"return confirm('Are you sure to delete this data?')\" class=\"btn btn-danger btn-xs btn-flat\" href=\"h1/po/delete?id=$row->id_po\"><i class=\"fa fa-trash-o\"></i></a>
                      <a $edit data-toggle=\"tooltip\" title=\"Edit Data\" class=\"btn btn-primary btn-xs btn-flat\" href=\"h1/po/edit?id=$row->id_po\"><i class=\"fa fa-edit\"></i></a>
                      <a $approval data-toggle=\"tooltip\" title=\"Approval Data\" class=\"btn btn-success btn-xs btn-flat\" href=\"h1/po/approval?id=$row->id_po\"><i class=\"fa fa-check\"></i></a>";
              }elseif($row->status=='approved'){
                if($row->submitted==1){
                    $status = "<span class='label label-primary'>Submitted</span>";                
                    $tomb = "<a $download data-toggle=\"tooltip\" title=\"Download PO\" class=\"btn btn-warning btn-xs btn-flat\" href=\"h1/po/download?id=$row->id_po\"><i class=\"fa fa-download\"></i></a>
                          <a $approval data-toggle=\"tooltip\" title=\"Send PO to AHM\" class=\"btn btn-success btn-xs btn-flat\" href=\"h1/po/send?id=$row->id_po\"><i class=\"fa fa-send\"></i></a>";
                }else{
                    $status = "<span class='label label-success'>$row->status</span>";                
                    $tomb = "<a $download data-toggle=\"tooltip\" title=\"Download PO\" class=\"btn btn-warning btn-xs btn-flat\" href=\"h1/po/download?id=$row->id_po\"><i class=\"fa fa-download\"></i></a>
                      <a $edit data-toggle=\"tooltip\" title=\"Edit Data\" class=\"btn btn-primary btn-xs btn-flat\" href=\"h1/po/edit?id=$row->id_po\"><i class=\"fa fa-edit\"></i></a>
                      ";
                    // <a $approval data-toggle=\"tooltip\" title=\"Send PO to AHM\" class=\"btn btn-success btn-xs btn-flat\" href=\"h1/po/send?id=$row->id_po\"><i class=\"fa fa-send\"></i></a>
                }
              }else{
                $status = "<span class='label label-danger'>$row->status</span>";                
                $tomb = "<a $download data-toggle=\"tooltip\" title=\"Download PO\" class=\"btn btn-warning btn-xs btn-flat\" href=\"h1/po/download?id=$row->id_po\"><i class=\"fa fa-download\"></i></a>
                      <a $approval data-toggle=\"tooltip\" title=\"Send PO to AHM\" class=\"btn btn-success btn-xs btn-flat\" href=\"h1/po/send?id=$row->id_po\"><i class=\"fa fa-send\"></i></a>";
              }              
            }
          echo "          
            <tr>
              <td>$no</td>
              <td>
                <a href='h1/po/detail?id=$row->id_po'>
                  $row->id_po
                </a>
              </td>
              <td>$row->bulan</td>
              <td>$row->tahun</td>
              <td>$row->ket</td>
              <td>$row->jenis_po</td>
              <td>$status</td>                                                    
              <td>";
                echo $tomb;
              ?>
                
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


<div class="modal fade" id="Itemmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search Item
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
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1; 
          foreach ($dt_item->result() as $ve2) {
            echo "
            <tr>
              <td>$no</td>
              <td>$ve2->id_item</td>
              <td>$ve2->tipe_ahm</td>
              <td>$ve2->warna</td>";
              ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="chooseitem('<?php echo $ve2->id_item; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
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
<div class="modal fade"  width="850px" id="modal_po_add">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Data PO</h4>
      </div>
      <div class="modal-body">
          <form class="form-horizontal" action="h1/po/edit_po_add" method="post" enctype="multipart/form-data">            
            <input type="hidden" class="form-control" id="id_po_detail" name="id_po_detail">
            <input type="hidden" class="form-control" id="id_po" name="id_po">
            <input type="hidden" class="form-control" id="mode" name="mode">
            <div class="box-body">
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">ID Item</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="id_item" placeholder="ID Item" name="id_item" readonly>
                </div>
                <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="warna" placeholder="Warna" name="warna" readonly>
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="tipe_ahm" placeholder="Tipe Kendaraan" name="tipe_ahm" readonly>
                </div>                
              </div>              
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Qty Order</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="qty_order" placeholder="Qty Order" name="qty_order">
                </div>                
              </div>              

            </div><!-- /.box-body -->
            <div class="box-footer">
              <button type="submit" name="s_process" value="edit" class="btn btn-info">Update</button>
              <a href="adm/mapel">
                <button type="button" data-dismiss="modal" class="btn btn-default pull-right">Cancel</button>                
              </a>
            </div><!-- /.box-footer -->
          </form>
      </div>      
    </div>
  </div>
</div>
<div class="modal fade"  width="850px" id="modal_po_reg">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Data PO</h4>
      </div>
      <div class="modal-body">
          <form class="form-horizontal" action="h1/po/edit_po_reg" method="post" enctype="multipart/form-data">            
            <input type="hidden" class="form-control" id="id_po_detail" name="id_po_detail">
            <input type="hidden" class="form-control" id="id_po" name="id_po">
            <input type="hidden" class="form-control" id="mode" name="mode">
            <div class="box-body">
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">ID Item</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="id_item" placeholder="ID Item" name="id_item" readonly>
                </div>
                <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="warna" placeholder="Warna" name="warna" readonly>
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="tipe_ahm" placeholder="Tipe Kendaraan" name="tipe_ahm" readonly>
                </div>                
              </div>                 
              <div class="form-group">
                <table class="myTable1">
                  <thead>
                    <tr>
                      <th align="center">On Hand</th>
                      <th align="center">Qty Niguri Fix</th>
                      <th align="center">Qty PO Fix</th>
                      <th align="center">Qty PO T1</th>
                      <th align="center">Qty PO T2</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><input type="text" class="form-control isi" name="on_hand" readonly></td>
                      <td><input type="text" class="form-control isi" name="qty_niguri_fix" readonly></td>
                      <td><input type="text" class="form-control isi" name="qty_po_fix"></td>
                      <td><input type="text" class="form-control isi" name="qty_po_t1"></td>
                      <td><input type="text" class="form-control isi" name="qty_po_t2"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div><!-- /.box-body -->
            <div class="box-footer">
              <button type="submit" name="s_process" value="edit" class="btn btn-info">Update</button>
              <a href="adm/mapel">
                <button type="button" data-dismiss="modal" class="btn btn-default pull-right">Cancel</button>                
              </a>
            </div><!-- /.box-footer -->
          </form>
      </div>      
    </div>
  </div>
</div>


<script type="text/javascript">
function cek_jenis(){
  var jenis_po = document.getElementById("jenis_po").value;
  if(jenis_po == 'PO Reguler'){
    kirim_data_po_reg();
  }else if(jenis_po == 'PO Additional'){
    kirim_data_po_add();
  }
}
function cek_bulan(){
  var bulan = document.getElementById("bulan").value;
  var tahun = document.getElementById("tahun").value;
  //$("#jenis_po").val(bulan);
  $.ajax({
      url : "<?php echo site_url('h1/po/cari_jenis')?>",
      type:"POST",
      data:"bulan="+bulan+"&tahun="+tahun,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#jenis_po").val(data[0]);
        cek_jenis();                        
      }        
  })
}
function auto(){
  var po_js=document.getElementById("tgl").value; 
  var status = 1;
  $.ajax({
      url : "<?php echo site_url('h1/po/cari_id')?>",
      type:"POST",
      data:"po="+po_js+"&status="+status,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_po").val(data[0]);
        //kirim_data_po();     
        cek_jenis();                   
      }        
  })
}
function cancel_tr(){
  var id_po_js=document.getElementById("id_po").value; 
  if (confirm("Are you sure to cancel this transaction...?") == true) {
      $.ajax({
        url : "<?php echo site_url('h1/po/cancel_po')?>",
        type:"POST",
        data:"id_po="+id_po_js,   
        cache:false,   
        success: function(msg){ 
          window.location.reload();
        }        
    })
  }else{
    return false;
  }  
}
function chooseitem(id_item){
  document.getElementById("id_item").value = id_item; 
  cek_item();
  $("#Itemmodal").modal("hide");
}
function cek_item(){
  var id_item_js  = $("#id_item").val();                       
  var bulan       = $("#bulan").val();                       
  var tahun       = $("#tahun").val();                       
  $.ajax({
      url: "<?php echo site_url('h1/po/cek_item')?>",
      type:"POST",
      data:"id_item="+id_item_js+"&bulan="+bulan+"&tahun="+tahun,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");
          if(data[0]=="ok"){          
            $("#id_item").val(data[1]);                
            $("#tipe").val(data[2]);                
            $("#warna").val(data[3]);
            $("#on_hand").val(data[4]);                        
            $("#qty_niguri_fix").val(data[5]);                        
            $("#qty_po_fix").val(data[6]);
            $("#qty_po_t1").val(data[7]);
            $("#qty_po_t2").val(data[8]);
          }else{
            alert(data[0]);
          }
      } 
  })
}
function hide_po(){
    $("#tampil_po").hide();
}
function kirim_data_po_reg(){    
  $("#tampil_po").show();
  var id_po = document.getElementById("id_po").value;   
  var mode  = document.getElementById("mode").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_po="+id_po+"&mode="+mode;                           
     xhr.open("POST", "h1/po/t_po_reg", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_po").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function kirim_data_po_add(){    
  $("#tampil_po").show();
  var id_po = document.getElementById("id_po").value;   
  var mode  = document.getElementById("mode").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_po="+id_po+"&mode="+mode;                           
     xhr.open("POST", "h1/po/t_po_add", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_po").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function tes(){
  alert("hello");
}
function simpan_po(){
  var jenis_po = document.getElementById("jenis_po").value;
  if(jenis_po == 'PO Reguler'){
    var id_po               = document.getElementById("id_po").value;   
    var id_item             = document.getElementById("id_item").value;   
    var qty_po_fix          = document.getElementById("qty_po_fix").value;   
    var qty_po_t1           = document.getElementById("qty_po_t1").value;   
    var qty_po_t2           = document.getElementById("qty_po_t2").value;
    var qty_niguri_fix      = document.getElementById("qty_niguri_fix").value;           
    var on_hand             = document.getElementById("on_hand").value; 
    var bulan               = $("#bulan").val();                       
    var tahun               = $("#tahun").val();                                 
    //alert(id_po);
    if (id_po == "" || id_item == "") {    
        alert("Isikan data dengan lengkap...!");
        return false;
    }else{
        $.ajax({
            url : "<?php echo site_url('h1/po/save_po_reg')?>",
            type:"POST",
            data:"id_po="+id_po+"&id_item="+id_item+"&qty_po_fix="+qty_po_fix+"&qty_po_t1="+qty_po_t1+"&qty_po_t2="+qty_po_t2+"&qty_niguri_fix="+qty_niguri_fix+"&on_hand="+on_hand+"&bulan="+bulan+"&tahun="+tahun,
            cache:false,
            success:function(msg){            
                data=msg.split("|");
                if(data[0]=="nihil"){
                    kirim_data_po_reg();
                    kosong();                
                }else if(data[0]=="niguri"){
                    alert("Qty PO Fix item ini melebihi QTY Niguri Fix");
                    $("#qty_po_fix").val("");
                }else if(data[0]=="po_fix"){
                    alert("Qty PO Fix item ini melebihi batas maksimum yang telah ditentukan");
                    $("#qty_po_fix").val("");
                }else if(data[0]=="po_t1"){
                    alert("Qty PO T1 item ini melebihi batas maksimum yang telah ditentukan");
                    $("#qty_po_t1").val("");
                }else{
                    alert(data[0]);
                    kosong();                      
                }                
            }
        })    
    }
  }else if(jenis_po == 'PO Additional'){
    var id_po               = document.getElementById("id_po").value;   
    var id_item             = document.getElementById("id_item").value;   
    var qty_order           = document.getElementById("qty_order").value;           
    //alert(id_po);
    if (id_po == "" || id_item == "") {    
        alert("Isikan data dengan lengkap...!");
        return false;
    }else{
      $.ajax({
          url : "<?php echo site_url('h1/po/save_po_add')?>",
          type:"POST",
          data:"id_po="+id_po+"&id_item="+id_item+"&qty_order="+qty_order,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="nihil"){
                  kirim_data_po_add();
                  kosong();                
              }else{
                  alert('Item ini sudah ditambahkan');
                  kosong();                      
              }                
          }
      })    
    }
  }
}

function kosong(args){
  $("#id_item").val("");
  $("#warna").val("");   
  $("#tipe").val("");   
  $("#qty_po_t1").val("");   
  $("#qty_po_t2").val("");   
  $("#qty_order").val("");   
  $("#qty_po_fix").val("");   
  $("#qty_niguri_fix").val("");   
  $("#on_hand").val("");     
}
function hapus_po(a,b){ 
    var id_po_detail  = a;   
    var id_item   = b;       
    $.ajax({
        url : "<?php echo site_url('h1/po/delete_po')?>",
        type:"POST",
        data:"id_po_detail="+id_po_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              cek_jenis();
            }
        }
    })
}
function edit_po_add(id){    
  //Ajax Load data from ajax
  $.ajax({
      url : "<?php echo site_url('h1/po/cari_po_add')?>",
      type:"POST",
      data:"id="+id,      
      success: function(msg)
      { 
          data=msg.split("|");
          $('[name="id_item"]').val(data[0]);          
          $('[name="tipe_ahm"]').val(data[1]);                    
          $('[name="warna"]').val(data[2]);                    
          $('[name="qty_order"]').val(data[3]);                    
          $('[name="id_po_detail"]').val(data[4]);                                                
          $('[name="id_po"]').val(data[5]);                              
          $('[name="mode"]').val(data[6]);                              
          $('#modal_po_add').modal('show'); // show bootstrap modal when complete loaded
          $('.modal-title').text('Edit Data PO'); // Set title to Bootstrap modal title

      },
      error: function (jqXHR, textStatus, errorThrown)
      {
          alert('Error get data from ajax');
      }
  });
}
function edit_po_reg(id){    
  //Ajax Load data from ajax
  $.ajax({
      url : "<?php echo site_url('h1/po/cari_po_reg')?>",
      type:"POST",
      data:"id="+id,      
      success: function(msg)
      { 
          data=msg.split("|");
          $('[name="id_item"]').val(data[0]);          
          $('[name="tipe_ahm"]').val(data[1]);                    
          $('[name="warna"]').val(data[2]);                    
          $('[name="on_hand"]').val(data[3]);                    
          $('[name="qty_niguri_fix"]').val(data[4]);                    
          $('[name="qty_po_fix"]').val(data[5]);                    
          $('[name="qty_po_t1"]').val(data[6]);                    
          $('[name="qty_po_t2"]').val(data[7]);                    
          $('[name="id_po_detail"]').val(data[8]);                                                
          $('[name="id_po"]').val(data[9]);          
          $('[name="mode"]').val(data[10]);                                                  
          $('#modal_po_reg').modal('show'); // show bootstrap modal when complete loaded
          $('.modal-title').text('Edit Data PO'); // Set title to Bootstrap modal title

      },
      error: function (jqXHR, textStatus, errorThrown)
      {
          alert('Error get data from ajax');
      }
  });
}
</script>