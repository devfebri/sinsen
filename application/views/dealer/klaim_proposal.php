<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Report</li>
    <li class=""><?= $title ?></li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>

  <section class="content">
    <?php 
    if($set=="form"){
      $form     = '';
      $disabled = '';
      $readonly ='';
      if ($mode=='ajukan') {
        $form = 'ajukan';
      }
      if ($mode=='approve') {
        $readonly ='readonly';
        $form     ='approve';
        $disabled = 'disabled';
      }
       if ($mode=='reject') {
        $readonly ='readonly';
        $form     ='reject';
        $disabled = 'disabled';
      }
      if ($mode=='detail') {
        $disabled = 'disabled';
      }
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/klaim_proposal">
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
            <form  class="form-horizontal" id="form_" action="dealer/klaim_proposal/<?= $form ?>" method="post" enctype="multipart/form-data">
              <?php if (isset($row)): ?>
                <input type="hidden" name="id_sales_order" value="<?= $row->id_sales_order ?>">
              <?php endif ?>
              <input type="hidden" name="jenis_program" value="<?= isset($jenis_program)?$jenis_program:'' ?>">
                <input type="hidden" name="id_program_md" value="<?= isset($id_program_md)?$id_program_md:'' ?>">
                <input type="hidden" name="id_claim" value="<?= isset($id_claim)?$id_claim:'' ?>">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="No Mesin" name="no_mesin" value="<?=$row->no_mesin?>" readonly>                                        
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Invoice</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" name="nama_konsumen" value="<?=$row->no_invoice?>">                    
                  </div>
                </div>                
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="" name="nama_konsumen" value="<?=$row->tipe_ahm?>">                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Invoice</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="" name="" value="<?=$row->tgl_cetak_invoice?>">                    
                   </div>                  
                </div>                                  
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Warna" name="nama_konsumen" value="<?=$row->warna?>">                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No BASTK</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="" name="nama_konsumen" value="<?=$row->no_bastk?>">                    
                  </div>                  
                </div> 
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="" name="nama_konsumen" value="<?=$row->nama_konsumen?>">                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl. BASTK</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="" name="nama_konsumen" value="<?=$row->tgl_bastk?>">                    
                  </div>                  
                </div> 
                
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="" name="nama_konsumen" value="<?=$row->no_ktp?>">                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Leasing</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="" name="nama_konsumen" value="<?=$row->finance_company?>">                    
                  </div>                  
                </div> 

                <?php if (strtolower($row->jenis_beli)=='kredit'): ?>
                  <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">No. PO Leasing</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="" name="no_po_leasing" value="<?=$row->no_po_leasing?>">                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl. PO Leasing</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="" name="tgl_po_leasing" value="<?=$row->tgl_po_leasing?>">                    
                  </div>                  
                </div> 
                <?php endif ?>
                <?php if ($mode=='reject'||$mode=='detail'): ?>
                  <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Alasan Reject Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" <?= $mode=='detail'?'disabled':'' ?> name="alasan_reject" value="<?= $row->alasan_reject?>" required>                    
                  </div>                  
                </div> 
                <?php endif ?>
                <div class="form-group">                
                  <div class="col-md-12">
                    <button type="reset" class="btn btn-primary btn-flat btn-block" disabled="">Syarat dan Ketentuan</button>
                  </div><br><br><br>
                  <div class="col-md-offset-1 col-md-10">
                    <table class="table table-bordered table-condensed table-hover">
                      <thead>
                        <th style="width: 4%">No</th>
                        <th style="width: 28%"">Syarat</th>
                        <?php if ($mode!=='approve' && $mode!='reject' && $mode!='detail') { ?>
                        <th style="text-align: left;width: 14%">Upload Dokumen</th>
                        <?php } ?>
                        <th style="text-align: center;width: 8%">Checklist</th>
                        <?php if(1){?>
                        <th style="text-align: center;width: 15%">Alasan Reject</th>
                        <?php } ?>
                        <th style="text-align: center;width: 6%">Download</th>
                        <?php if($mode =='ajukan'){ ?> 
                          <th style="text-align: center;width: 6%"> Aksi </th>
                        <?php } ?>
                      </thead>
                    <tbody>
                      <?php
                      if ($get_syarat->num_rows()>0) {
                        $no=1;
                         foreach ($get_syarat->result() as $key => $rs) { ?>
                           <tr>
                            <td><?=$no?></td>
                             <td><?=$rs->syarat_ketentuan?></td>
                        <?php if ($mode!=='approve' && $mode!=='reject' && $mode!=='detail') { ?>
                            <td style="text-align: center;">
                              <input type="file" name="fileToUpload[]" id="fileToUpload" accept=".png,.jpg,.jpeg,.pdf">
                            </td>
                            <td align="center">
                              <input type="checkbox" name="cek[]" value="<?=$rs->id?>" <?= $rs->checklist_dealer==1?'checked':'' ?>>
                              <input type="hidden" name="id[]" value="<?=$rs->id?>" >
                            </td>
                        <?php }else{ 
                            if($rs->checklist_dealer=='1'){
                          ?>
                          <td align="center"><i class="fa fa-check"></i></td>
                          <?php }else {?>
                          <td align="center">-</td>
                        <?php }} ?>                  
                            <td align="center"><?php if($rs->checklist_reject_md==1){ echo $rs->alasan_reject; }else{ echo '-'; }?></td>
                            <td style="text-align: center;">
                                <?php if($rs->filename !=''){?>
                                <a target="_blank" href="dealer/klaim_proposal/download_file?id=<?php echo $rs->id_cd ?>&id_claim=<?php echo $rs->id_claim ?>" class="btn btn-info btn-xs"><?php echo $rs->filename ?></a>
                                <?php }else{ ?>
                                <!-- <button type="button" class="btn btn-info btn-xs disabled">Download</button> -->
                                <?php }?>
                            </td>
                            <?php if($rs->filename !='' && $mode =='ajukan'){?>
                            <td style="text-align:center"><button type="button" onClick="remove_doc('<?php echo $rs->id_claim?>','<?php echo $rs->id_syarat_ketentuan?>','<?php echo $rs->id_cd?>')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td>
                            <?php }?>
                          </tr>
                        <?php $no++; }
                       } ?>
                    </tbody>
                  </table>          
                  </div>
                </div> 
              </div><!-- /.box-body -->
                        
              <div class="box-footer">
                <div class="col-sm-12" align="center">
                 <?php if ($mode=='ajukan'): ?>
                    <button type="submit" name="submit" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
                 <?php endif ?>
                 <?php if ($mode=='approve'): ?>
                    <button type="submit" name="submit" onclick="return confirm('Apakah anda yakin ?')" value="save" class="btn btn-success btn-flat"><i class="fa fa-check"></i> Approve</button>
                 <?php endif ?>
                  <?php if ($mode=='reject'): ?>
                    <button type="submit" name="submit" onclick="return confirm('Apakah anda yakin ?')" value="save" class="btn btn-danger btn-flat"><i class="fa fa-cross"></i> Reject</button>
                 <?php endif ?>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

<script>
  function remove_doc(id_claim, id_syarat, id_claim_dealer){
    var confirms = confirm("Apakah anda yakin ingin menghapus file ini?");
    if (confirms) {
      var value = {
        id_claim: id_claim,
        id_syarat: id_syarat,
        id_cd: id_claim_dealer
      }

      $.ajax({
        url: "<?php echo site_url('dealer/klaim_proposal/remove_file') ?>",
        type: "POST",
        data: value,
        cache: false,
        success: function(dt_response) {
          alert('File berhasil dihapus.');
          location.reload();
        },
        statusCode: {
          500: function() {
            alert("Something Wen't Wrong");
          }
        }
      });
      
      
      return false;
    }
    return false

    
  }


  var form_ = new Vue({
      el: '#form_',
      data: {
        mode : '<?= $mode ?>',
        total_harga : '',
        units : <?= isset($units)?json_encode($units):'[]' ?>,
      },
    methods: {
      getUnit: function () {
       var tgl_pengiriman = $('#tgl_pengiriman').val();
       if (tgl_pengiriman=='') {
        alert('Silahkan pilih tanggal pengiriman !');
        return false
       }
       values = {tgl_pengiriman:tgl_pengiriman}
       $.ajax({
        beforeSend: function() {
          $('#gnrtBtn').attr('disabled',true);
        },
        url:'<?= base_url('dealer/klaim_proposal/get_unit') ?>',
        type:"POST",
        data: values,
        cache:false,
        dataType:'JSON',
        success:function(response){
          var length = response.length;
          form_.units=[];
          if (response.length==0) {
            alert('Data tidak ditemukan !');
          }
          for (dtl of response) {
              form_.units.push(dtl);
          }
          console.log(form_.units)
          $('#gnrtBtn').attr('disabled',false);
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
      },
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
        <table id="datatable_server" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>Promotion ID</th></th>
              <th>No. SPK</th>
              <th>No. SO</th>
              <th>No. Mesin</th>
              <th>No. Rangka</th>
              <th>Penjualan</th>
              <th>Promotion</th>
              <th>AHM Contr.</th>
              <th>MD Contr.</th>
              <th>Dealer Contr.</th>
              <th>Finco Contr.</th>
              <th>Status Claim</th>
              <th>Alasan Reject</th>
              <th>Aksi</th>
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
         "scrollX":true,
         "language": {                
              "infoFiltered": "",
              "searchPlaceholder": "Min. 5 digit untuk cari",
              "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
          }, 
         "order":[],
         "lengthMenu": [[10, 25, 50,75,100], [10, 25, 50,75,100]],  
         "ajax":{  
              url:"<?php echo site_url('dealer/klaim_proposal/fetch'); ?>",  
              type:"POST",
              dataSrc: "data",
              data: function (d) {
                  return d;
              },
         },  
         "columnDefs":[  
              { "targets":[9],"orderable":false},
              // { "targets":[9],"className":'text-center'}, 
              // // { "targets":[0],"checkboxes":{'selectRow':true}}
              { "targets":[5,6,7,8,9],"className":'text-right'}, 
              // // { "targets":[2,4,5], "searchable": false } 
         ],
      });

      $(".dataTables_filter input")
        .unbind() // Unbind previous default bindings
        .bind("input", function(e) { // Bind our desired behavior
            // If the length is 3 or more characters, or the user pressed ENTER, search
            if(this.value.length >= 5 || e.keyCode == 13) {
                // Call the API search function
                dataTable.search(this.value).draw();
            }
            // Ensure we clear the search if they backspace far enough
            if(this.value == "") {
              dataTable.search("").draw();
            }
            return;
        });

    });
  function rejectPrompt(id_sales_order) {
    var alasan_reject = prompt("Alasan melakukan reject untuk ID Sales Order : "+id_sales_order);
    if (alasan_reject != null || alasan_reject == "") {
       window.location = '<?= base_url("dealer/klaim_proposal/reject?id=") ?>'+id_sales_order+'&ar='+alasan_reject;
        return false;
    }
    return false;
  }
</script>
    <?php
    }
    ?>
  </section>
</div>