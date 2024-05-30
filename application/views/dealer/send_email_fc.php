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
    <li class="">DP Invoice</li>
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
        $('#id_spk').val('<?= $row->id_spk ?>').trigger('change');
    <?php } ?>
  })
</script>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/invoice_pelunasan">
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
            <form  class="form-horizontal" id="form_" action="dealer/invoice_pelunasan/<?= $form ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Invoice DP</label>
                  <div class="col-sm-4">
                     <input type="text" required class="form-control" value="<?= isset($row)?$row->id_inv_pelunasan:'Otomatis Setalah Save' ?>" autocomplete="off" readonly> 
                  </div>
                </div> 
                <div class="form-group">
                   <label for="inputEmail3" class="col-sm-2 control-label">ID SPK</label>
                  <div class="col-sm-4">
                    <select name="id_spk" id="id_spk" onchange="getSPK()" class="form-control select2" <?= $disabled ?> required>
                      <option value="">--choose-</option>
                      <?php foreach ($spk->result() as $rs): 
                        $selected = isset($row)?$rs->no_spk==$row->id_spk?'selected':'':'';
                      ?>
                        <option value="<?= $rs->no_spk ?>" <?= $selected ?> 
                              data-no_spk             = "<?= $rs->no_spk ?>"
                              data-nama_konsumen      = "<?= $rs->nama_konsumen ?>"
                              data-id_sales_people    = "<?= $rs->id_sales_people ?>"
                              data-id_karyawan_dealer = "<?= $rs->id_karyawan_dealer ?>"
                              data-no_ktp             = "<?= $rs->no_ktp ?>"
                              data-no_hp              = "<?= $rs->no_hp ?>"
                              data-tipe_pembayaran    = "<?= $rs->tipe_pembayaran ?>"
                              data-id_tipe_kendaraan  = "<?= $rs->id_tipe_kendaraan ?>"
                              data-tipe_ahm           = "<?= $rs->tipe_ahm ?>"
                              data-id_warna           = "<?= $rs->id_warna ?>"
                              data-warna              = "<?= $rs->warna ?>"
                              data-harga_on_road      = "<?= $rs->harga_on_road-$rs->diskon ?>"
                              data-tanda_jadi         = "<?= $rs->tanda_jadi ?>"
                              data-diskon             = "<?= $rs->diskon ?>"
                        ><?= $rs->no_spk ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                   <label for="inputEmail3" class="col-sm-2 control-label">Sales People ID</label></label>
                  <div class="col-sm-4">
                    <input type="hidden" id="id_karyawan_dealer" name="id_karyawan_dealer">
                    <input type="text" required class="form-control" name="id_sales_people" id="id_sales_people" autocomplete="off" readonly>
                  </div>                                                                         
                </div> 
                <div class="form-group">
                   <label for="inputEmail3" class="col-sm-2 control-label">Nama Pelanggan</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" name="nama_konsumen" id="nama_konsumen"   autocomplete="off" readonly>
                  </div>                                                                            
                </div> 
                <div class="form-group">
                   <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" name="no_ktp" id="no_ktp"  autocomplete="off" readonly>
                  </div>                                                                            
                </div>
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
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon</label>
                  <div class="col-sm-4">
                    <vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" v-model="diskon"  disabled
                          v-bind:minus="false" :empty-value="0" separator="."/>                                       
                  </div>
                </div>
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Harga</label>
                  <div class="col-sm-4">
                    <vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" v-model="total_harga"  disabled
                          v-bind:minus="false" :empty-value="0" separator="."/>                                       
                  </div>
                </div>   
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Sisa Pelunasan</label>
                  <div class="col-sm-4">
                    <vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" v-model="sisa_pelunasan"  disabled
                          v-bind:minus="false" :empty-value="0" separator="."/>                                       
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
        sisa_pelunasan :'',
        diskon : '<?= isset($row)?$row->diskon:''?>',
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
function getSPK() {
  var nama_konsumen     = $("#id_spk").select2().find(":selected").data("nama_konsumen");$('#nama_konsumen').val(nama_konsumen);
  var no_ktp             = $("#id_spk").select2().find(":selected").data("no_ktp");$('#no_ktp').val(no_ktp);
  var id_sales_people             = $("#id_spk").select2().find(":selected").data("id_sales_people");$('#id_sales_people').val(id_sales_people);
  var id_karyawan_dealer             = $("#id_spk").select2().find(":selected").data("id_karyawan_dealer");$('#id_karyawan_dealer').val(id_karyawan_dealer);
  var tipe_pembayaran             = $("#id_spk").select2().find(":selected").data("tipe_pembayaran");$('#tipe_pembayaran').val(tipe_pembayaran);
  var id_tipe_kendaraan = $("#id_spk").select2().find(":selected").data("id_tipe_kendaraan");
  var tipe_ahm          = $("#id_spk").select2().find(":selected").data("tipe_ahm");
  $('#tipe').val(id_tipe_kendaraan+' | '+tipe_ahm);
  var id_warna          = $("#id_spk").select2().find(":selected").data("id_warna");
  var warna             = $("#id_spk").select2().find(":selected").data("warna");
  var harga_on_road             = $("#id_spk").select2().find(":selected").data("harga_on_road");
  var amount_dp             = $("#id_spk").select2().find(":selected").data("tanda_jadi");
  var diskon             = $("#id_spk").select2().find(":selected").data("diskon");
  form_.total_harga = harga_on_road;
  form_.diskon = diskon;
  form_.sisa_pelunasan = harga_on_road - amount_dp;
  
  $('#warna').val(id_warna+' | '+warna);
}

</script>
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
              <th>No Terima Berkas</th>
              <th>Jenis</th>
              <th>Finance Company</th>
              <th>Banyak Berkas</th>
              <th>Email Finance Company</th>
              <th>Aksi</th>
            </tr>
          </thead>    
          <tbody>
            <?php
                $id_dealer = $this->m_admin->cari_dealer(); 
                $bpkp = $this->db->query("SELECT tpbd.*,tr_spk.id_finance_company,finance_company,ms_finance_company.email,COUNT(tr_sales_order.id_sales_order) AS tot_berkas
                  FROM tr_penyerahan_bpkb_detail AS tpbd
                  JOIN tr_sales_order ON tpbd.no_mesin=tr_sales_order.no_mesin
                  JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
                  JOIN ms_finance_company ON tr_spk.id_finance_company=ms_finance_company.id_finance_company
                  WHERE status_nosin='terima' AND tr_spk.id_dealer=$id_dealer AND tr_spk.jenis_beli='Kredit'
                  AND (SELECT count(no_serah_bpkb) FROM tr_send_email_fc WHERE no_serah_bpkb=tpbd.no_serah_bpkb AND id_finance_company=tr_spk.id_finance_company)=0
                  GROUP BY no_serah_bpkb, ms_finance_company.id_finance_company

                ");
                $srut = $this->db->query("SELECT psd.*,tr_spk.id_finance_company,finance_company,ms_finance_company.email FROM tr_penyerahan_srut_detail AS psd
                  JOIN tr_penyerahan_srut AS ps ON ps.no_serah_terima=psd.no_serah_terima
                  JOIN tr_sales_order ON psd.no_mesin=tr_sales_order.no_mesin
                  JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
                  JOIN ms_finance_company ON tr_spk.id_finance_company=ms_finance_company.id_finance_company
                  WHERE ps.id_dealer=$id_dealer
                  AND (SELECT count(no_serah_srut) FROM tr_send_email_fc WHERE no_serah_srut=psd.no_serah_terima AND id_finance_company=tr_spk.id_finance_company)=0
                ");
            ?>
            <?php foreach ($bpkp->result() as $rs): 
               // $tot_berkas = $this->db->query("SELECT COUNT(no_serah_bpkb) AS c FROM tr_penyerahan_bpkb_detail WHERE no_serah_bpkb='$rs->no_serah_bpkb' ")->row()->c;
                $tot_berkas = $rs->tot_berkas;
              ?>
              <tr>
                <td><a href="dealer/send_email_fc/detail?id=<?= $rs->no_serah_bpkb ?>&jn=bpkb"><?= $rs->no_serah_bpkb ?></a></td>
                <td>BPKB</td>
                <td><?= $rs->finance_company?></td>
                <td><?= $tot_berkas ?></td>
                <td><?= $rs->email ?></td>
                <td align="center">
                  <a href="dealer/send_email_fc/send_email?id=<?= $rs->no_serah_bpkb ?>&jn=bpkb&fc=<?= $rs->id_finance_company ?>" onclick="return confirm('Are you sure to send email to this finance company ?')" class="btn btn-primary btn-flat btn-xs" >Send Email</a>
                </td>
              </tr>
            <?php endforeach ?>

            <?php foreach ($srut->result() as $rs): 
              $tot_berkas = $this->db->query("SELECT COUNT(no_serah_terima) AS c FROM tr_penyerahan_srut_detail WHERE no_serah_terima='$rs->no_serah_terima'")->row()->c;
              ?>
              <tr>
                <td><a href="dealer/send_email_fc/detail?id=<?= $rs->no_serah_terima ?>&jn=srut"><?= $rs->no_serah_terima ?></a></td>
                <td>SRUT</td>
                <td><?= $rs->finance_company ?></td>
                <td><?= $tot_berkas ?></td>
                <td><?= $rs->email ?></td>
                <td align="center">
                  <a href="dealer/send_email_fc/send_email?id=<?= $rs->no_serah_terima ?>&jn=srut&fc=<?= $rs->id_finance_company ?>" onclick="return confirm('Are you sure to send email to this finance company ?')" class="btn btn-primary btn-flat btn-xs" >Send Email</a>
                </td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php
    }
    ?>
  </section>
</div>