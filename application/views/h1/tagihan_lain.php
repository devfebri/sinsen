<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 30px;  
  padding-left: 5px;
  padding-right: 5px;  
  margin-right: 0px; 
}
.isi_combo{   
  height: 30px;
  border:1px solid #ccc;
  padding-left:1.5px;
}
</style>
<base href="<?php echo base_url(); ?>" />
<body onload="auto()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Finance</li>
    <li class="">Invoice Terima</li>
    <li class="">Inovice Asuransi</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$page)); ?></li>
  </ol>
  </section>
  <section class="content">


    <?php 
    if($set=="insert"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/tagihan_lain">
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
            <form class="form-horizontal" action="h1/tagihan_lain/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <input type="hidden" name="id_tagihan_lain" class="form-control" id="id_tagihan_lain">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Customer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="tipe_customer" name="tipe_customer" onchange="cek_tipe()">
                      <option value="">- choose -</option>
                      <option value="Vendor">Vendor</option>
                      <option value="Dealer">Dealer</option>
                    </select>
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Customer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="kode_customer" name="kode_customer">                      
                    </select>
                  </div>                                    
                </div>  
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_tagih" placeholder="Tanggal" id="tanggal3" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama & Divisi</label>
                  <div class="col-sm-4">
                    <input type="text" name="nama_divisi" placeholder="Nama & Divisi" class="form-control" required  pattern="\S+">                    
                  </div>                                                       
                </div>                                
                <div class="form-group">                  
                  <span id="tampil_data"></span>
                </div>         
                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
              </div><!-- /.box-footer -->
              
              
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="edit"){
    ?>
    <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/underscore.js") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/money.js") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
    <script>
    Vue.use(VueNumeric.default);

    </script>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/tagihan_lain">
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
            <form class="form-horizontal" id="form_tagihan" action="h1/tagihan_lain/save_edit" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <input type="hidden" name="id_tagihan_lain" class="form-control" value="<?= $row->id_tagihan_lain ?>">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Customer</label>
                  <div class="col-sm-4">
                    <script>
                      $(document).ready(function(){
                        cek_tipe('<?= $row->kode_customer ?>');
                      })
                    </script>
                    <select class="form-control select2" id="tipe_customer" name="tipe_customer" onchange="cek_tipe()">
                      <option <?= $row->tipe_customer==''?'selected':'' ?> value="">- choose -</option>
                      <option <?= $row->tipe_customer=='Vendor'?'selected':'' ?> value="Vendor">Vendor</option>
                      <option <?= $row->tipe_customer=='Dealer'?'selected':'' ?> value="Dealer">Dealer</option>
                    </select>
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Customer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="kode_customer" name="kode_customer">                      
                    </select>
                  </div>                                    
                </div>  
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_tagih" placeholder="Tanggal" id="tanggal3" class="form-control" value="<?= $row->tgl_tagih ?>">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama & Divisi</label>
                  <div class="col-sm-4">
                    <input type="text" name="nama_divisi" placeholder="Nama & Divisi" class="form-control" required  pattern="\S+" value="<?= $row->nama_divisi ?>">                    
                  </div>                                                       
                </div>                                
                <div class="form-group">                  
                  <table id="myTable" class="table myTable1 order-list" border="0">
                    <thead>
                      <tr>
                        <th width="12%">No PO</th>
                        <th width="10%">Tgl PO</th>
                        <th width="13%">No Kwitansi</th>
                        <th width="9%">Tgl Kwitansi</th>
                        <th width="15%">No BAST</th>
                        <th width="9%">Tgl BAST</th>
                        <th width="9%">Due Datetime</th>
                        <th width="15%">Harga</th>
                        <th width="5%">Aksi</th>
                      </tr>
                    </thead> 
                    <tbody>
                      <tr v-for="(detail, index) of details">
                        <td><input type="text" class="form-control isi_combo" name="no_po[]" v-model="detail.no_po"></td>               
                        <td><input type="text" class="form-control isi_combo datepicker" name="tgl_po[]" v-model="detail.tgl_po"></td>               
                        <td><input type="text" class="form-control isi_combo" name="no_kwitansi[]" v-model="detail.no_kwitansi"></td>               
                        <td><input type="text" class="form-control isi_combo datepicker" name="tgl_kwitansi[]" v-model="detail.tgl_kwitansi"></td>                    
                        <td><input type="text" class="form-control isi_combo" name="no_bast[]" v-model="detail.no_bast"></td>                    
                        <td><input type="text" class="form-control isi_combo datepicker" name="tgl_bast[]" v-model="detail.tgl_bast"></td>                      
                        <td><input type="text" class="form-control isi_combo datepicker" name="due_datetime[]" v-model="detail.due_datetime"></td>
                        <td><vue-numeric style="float: left;width: 80%;text-align: right;"
                              class="form-control text-rata-kanan isi_combo" v-model="detail.harga"
                              v-bind:minus="false" name="harga[]" :empty-value="0" separator="."/>
                        </td>
                        <td align="center" style="vertical-align: middle;">
                            <button @click.prevent="form_tagihan.delDetail(index)" type="button" class="btn btn-danger btn-sm fa fa-trash-o"></button>
                        </td>                                
                      </tr>
                    </tbody>
                    <tfoot>
                      <td>
                        <input type="text" class="form-control isi_combo" v-model="detail.no_po" placeholder="No PO">
                      </td>
                      <td>
                        <date-picker v-model="detail.tgl_po" placeholder="Tgl PO"></date-picker>
                      </td>
                      <td>
                        <input type="text" class="form-control isi_combo" v-model="detail.no_kwitansi" placeholder="No Kwitansi">
                      </td>
                      <td>
                        <date-picker v-model="detail.tgl_kwitansi" placeholder="Tgl Kwitansi"></date-picker>
                      </td>
                      <td>
                        <input type="text" class="form-control isi_combo" v-model="detail.no_bast" placeholder="No BAST">
                      </td>
                      <td>
                        <date-picker v-model="detail.tgl_bast" placeholder="Tgl BAST"></date-picker>
                      </td>
                      <td>
                        <date-picker v-model="detail.due_datetime" placeholder="Due Datetime"></date-picker>
                      </td>
                      <td>
                        <vue-numeric style="float: left;width: 80%;text-align: right;"
                              class="form-control isi_combo text-rata-kanan" v-model="detail.harga"
                              v-bind:minus="false" separator="." placeholder="Harga" />
                      </td>
                      <td align="center" style="vertical-align: middle;">
                            <button @click.prevent="form_tagihan.addDetail(detail)" type="button" class="btn btn-primary btn-sm fa fa-plus"></button>
                        </td>
                    </tfoot>
                  </table>
                </div>         
                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
<script src="assets/panel/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>

  var form_tagihan = new Vue({
      el: '#form_tagihan',
      data: {
        detail:{
          no_po:'',
          tgl_po:'',
          no_kwitansi:'',
          tgl_kwitansi:'',
          no_bast:'',
          tgl_bast:'',
          due_datetime:'',
          harga:''
        },
        details: <?= isset($details)?json_encode($details):'[]' ?>
      },
      methods: {
        // getItem : function() {
        //   $('#tabel_item').DataTable().ajax.reload();
        //   $('#modalItem').modal('show');
        // },
        clearDetail: function(){
          this.detail={
            no_po:'',
            tgl_po:'',
            no_kwitansi:'',
            tgl_kwitansi:'',
            no_bast:'',
            tgl_bast:'',
            due_datetime:'',
            harga:''
          }
        },
        addDetail : function(detail){
          console.log(detail)
          // if (form_tagihan.details.length > 0) {
          //   for (detail of form_tagihan.details) {
          //       if (detail.kode_item === this.detail.kode_item) {
          //           swal("Barang/Item ini sudah dipilih !");
          //           this.detail = {
          //               kode_item: "",
          //               nama_item: "",
          //               qty_po: null,
          //               qty_retur:null,
          //           };
          //           return;
          //       }
          //   }
          // }

          // if (this.detail.kode_bank === '') {
          //   swal('Silahkan pilih Bank terlebih dahulu !');
          //   return;
          // }

          // if (parseInt(this.detail.qty_retur) == 0 || this.detail.qty_retur=='') {
          //   swal('Qty Retur Tidak boleh lebih kecil dari 1');
          //   return;
          // }
          this.details.push(this.detail);
          this.clearDetail();
            console.log(this.details);
        },
        delDetail: function(index){
            this.details.splice(index, 1);
        },
        // cekQty: function(index){
        //     if (parseInt(this.details[index].qty_retur)>parseInt(this.details[index].qty_po)) {
        //       this.details[index].qty_retur=1;
        //       swal('Qty retur tidak boleh melebihi Qty PO !');
        //       // return;

        //     }
        //     if (parseInt(this.details[index].qty_retur)==0) {
        //       this.details[index].qty_retur=1;
        //       swal('Qty Retur Tidak boleh lebih kecil dari 1');
        //       // return;

        //     }
        //   // if (this.details.qty_retur) {}
        //     // alert(this.details);
        //     // objIndex = this.details.findIndex((obj => obj.id == index));
        //     // console.log("Before update: ", this.details[index].qty_retur);
        //     // console.log(this.details.index.kode_item);
        // }
      },

      computed: {
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
    <?php 
    }elseif($set=="kelengkapan"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/tagihan_lain">
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
            <form class="form-horizontal" action="h1/tagihan_lain/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Faktur Pajak</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="No Faktur Pajak" class="form-control">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Faktur Pajak</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Tgl Faktur Pajak" id="tanggal2" class="form-control">                    
                  </div>                                    
                </div>  
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">No Bukti Potong</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="No Bukti Potong" class="form-control">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Bukti Potong</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Tgl Bukti Potong" id="tanggal3" class="form-control">                    
                  </div>                                    
                </div>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Berkas PO</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Berkas PO" class="form-control">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>
                  <div class="col-sm-4 checkbox">
                    <input type="checkbox" name="no_mesin"> Berkas LPB                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>
                  <div class="col-sm-4 checkbox">
                    <input type="checkbox" name="no_mesin"> Berkas Surat Jalan
                  </div>                                    
                </div>                                                                               
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
              </div><!-- /.box-footer -->
              
              
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
          <a href="h1/tagihan_lain/add">            
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>            
              <th>No Terima Tagihan</th>                           
              <th>Tgl Terima Tagihan</th>              
              <th>Customer</th>                            
              <th>Total Tagihan</th>
              <th>Created By</th>
              <th>Nama & Divisi</th>
              <th>Status</th>              
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_rekap->result() as $row) {                                         
            $jum = $this->db->query("SELECT SUM(harga) AS jum FROM tr_tagihan_lain_detail WHERE id_tagihan_lain = '$row->id_tagihan_lain'")->row();
            if($row->status_tagihan=='input'){
              $status = "<span class='label label-warning'>waiting approval</span>";
              $rt = "";
            }elseif($row->status_tagihan=='approved'){
              $status = "<span class='label label-success'>$row->status_tagihan</span>";
              $rt = "style='display:none;";
            }elseif($row->status_tagihan=='rejected'){
              $status = "<span class='label label-danger'>$row->status_tagihan</span>";
              $rt = "style='display:none;";
            }
            if ($row->created_by==1) {
              $created_by='Admin';
            }else{
              $created_by = $row->nama_lengkap;
            }
            echo "          
            <tr>
              <td>$no</td>                           
              <td>$row->id_tagihan_lain</td>              
              <td>$row->tgl_tagih</td>                            
              <td>$row->kode_customer</td>                                          
              <td>$jum->jum</td>      
              <td>$created_by</td>   
              <td>$row->nama_divisi</td>                                          

              <td>$status</td>                            
              <td>
                <a $rt href='h1/tagihan_lain/edit?id=$row->id_tagihan_lain' class='btn btn-flat btn-warning btn-xs'>Edit</a>
                <a $rt href='h1/tagihan_lain/approve?id=$row->id_tagihan_lain' class='btn btn-flat btn-primary btn-xs'>Approve</a>
                <a $rt href='h1/tagihan_lain/reject?id=$row->id_tagihan_lain' class='btn btn-flat btn-danger btn-xs'>Reject</a>
                <a href='h1/tagihan_lain/kelengkapan?id=$row->id_tagihan_lain' class='btn btn-flat btn-warning btn-xs'>Kelengkapan Dokumen</a>";
             echo" </td>                                          
              ";                                      
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
<script type="text/javascript">
function auto(){
  var po_js = '1';
  $.ajax({
      url : "<?php echo site_url('h1/tagihan_lain/cari_id')?>",
      type:"POST",
      data:"po="+po_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");        
        $("#id_tagihan_lain").val(data[0]);
        kirim_data();             
      }        
  })
}
function kirim_data(){    
  $("#tampil_data").show();  
  var id_tagihan_lain  = document.getElementById("id_tagihan_lain").value;     
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_tagihan_lain="+id_tagihan_lain;
     xhr.open("POST", "h1/tagihan_lain/t_data", true); 
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
function cek_tipe(kode_customer){
  var tipe          = $("#tipe_customer").val();   
  $.ajax({
    url : "<?php echo site_url('h1/tagihan_lain/ambil_tipe')?>",
    type:"POST",
    data:"tipe="+tipe+"&kode_customer="+kode_customer,
    cache:false,   
    success:function(msg){            
      $("#kode_customer").html(msg);            
    }
  })  
}
function hapus_data(a){ 
    var id_tagihan_lain_detail  = a;       
    $.ajax({
        url : "<?php echo site_url('h1/tagihan_lain/delete_data')?>",
        type:"POST",
        data:"id_tagihan_lain_detail="+id_tagihan_lain_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data();
            }else{
              alert("Failed");
            }
        }
    })
}
function kosong(args){
  $("#no_po").val("");
  $("#tgl_po").val("");
  $("#no_kwitansi").val("");
  $("#tgl_kwitansi").val("");
  $("#no_bast").val("");  
  $("#tgl_bast").val("");  
  $("#due_datetime").val("");  
  $("#harga").val("");  
}
function simpan_data(){
  var no_po        = document.getElementById("no_po").value;  
  var tgl_po       = document.getElementById("tgl_po").value;     
  var no_kwitansi  = document.getElementById("no_kwitansi").value;     
  var tgl_kwitansi = document.getElementById("tgl_kwitansi").value;     
  var no_bast      = document.getElementById("no_bast").value;     
  var tgl_bast     = document.getElementById("tgl_bast").value;     
  var due_datetime = document.getElementById("due_datetime").value;     
  var harga        = document.getElementById("harga").value;     
  var id_tagihan_lain  = document.getElementById("id_tagihan_lain").value;     
  //alert(id_po);
  if (no_po == "" || tgl_po == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('h1/tagihan_lain/save_data')?>",
          type:"POST",
          data:"id_tagihan_lain="+id_tagihan_lain+"&no_po="+no_po+"&tgl_po="+tgl_po+"&no_kwitansi="+no_kwitansi+"&tgl_kwitansi="+tgl_kwitansi+"&no_bast="+no_bast+"&due_datetime="+due_datetime+"&harga="+harga+"&tgl_bast="+tgl_bast,
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
</script>