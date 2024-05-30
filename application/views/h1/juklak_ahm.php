<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li class=""><i class="fa fa-database"></i> Master Data</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php
    if($set=="view"){
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

        <table id="table" class="table table-hover" width="100%">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>No Juklak AHM</th>          
              <th>Deskripsi Program</th>   
              <th>Segment</th>                     
              <th>Kategori Program</th>      
              <th>Subkategori Program</th>            
              <th>Periode Awal</th>
              <th>Periode Akhir</th>                            
              <th>Unique Customer</th>
              <th>Kuota</th>       
              <th>Tgl Created</th>                            
              <th>Tgl Modified</th>
              <th>Status</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>                      
          </tbody>
        </table>

      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }else if($set=="view_log"){
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
          <div class="table-responsive">	
            <?php // print_r($get_juklak_baru);?>
            <!-- current juklak -->
            <div class="col-md-6">
              <table class="table" width="100%">
                <thead>
                  <tr>          
                    <th width="20%"></th>
                    <th width="40%" colspan="5">Juklak Saat Ini</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>No Juklak</td>
                    <td colspan="5"><?php echo $get_data->juklakNo; ?></td>
                  </tr>
                  <tr>
                    <td>Deskripsi</td>
                    <td colspan="5"><?php echo $get_data->descJuklak; ?></td>
                  </tr>
                  <tr>
                    <td>Segment</td>
                    <td colspan="5"><?php echo $get_data->segment; ?></td>
                  </tr>
                  <tr>
                    <td>Kategori Program</td>
                    <td colspan="5"><?php echo $get_data->programCategory; ?></td>
                  </tr>
                  <tr>
                    <td>Subkategori Program</td>
                    <td colspan="5"><?php echo $get_data->subProgram; ?></td>
                  </tr>
                  <tr>
                    <td>Periode Awal</td>
                    <td colspan="5"><?php echo $get_data->startPeriod; ?></td>
                  </tr>
                  <tr>
                    <td>Periode Akhir</td>
                    <td colspan="5"><?php echo $get_data->endPeriod; ?></td>
                  </tr>
                  <tr>
                    <td>Unique Customer</td>
                    <td colspan="5"><?php echo $get_data->uniqueCustomer; ?></td>
                  </tr>
                  <tr>
                    <td>Quota</td>
                    <td colspan="5"><?php echo $get_data->quota; ?></td>
                  </tr>
                  <tr>
                    <td>Created/ Modified Date</td>
                    <td colspan="5"><?php echo $get_data->modifiedDate; ?></td>
                  </tr>
                  
                  <tr>
                    <th colspan="5">Detail Target</th>
                    <td colspan="5">
                      <tr>
                        <th>Tahun</th>
                        <th>Bulan</th>
                        <th>Target</th>
                        <th colspan="2"></th>         
                      </tr>
                      <?php 
                        if($get_target->num_rows()>0){
                          foreach ($get_target->result() as $row){?>
                          <tr>
                            <td><?php echo $row->year; ?></td>
                            <td><?php echo $row->month; ?></td>
                            <td><?php echo number_format($row->target); ?></td>
                            <td colspan="2"></td>
                          </tr>
                      <?php
                        }
                      }
                      ?>
                    </td>
                  </tr>
                  <tr>
                  <th colspan="5">Detail Tipe</th>
                    <td colspan="5">
                      <tr>
                        <th>Tipe</th>
                        <th>Deskripsi</th>
                        <th>AHM Cont.</th>
                        <th>MD Cont.</th>
                        <th>Dealer Cont.</th>
                      </tr>
                      <?php 
                        if($get_type->num_rows()>0){
                          foreach ($get_type->result() as $row){?>
                          <tr>
                            <td><?php echo $row->type; ?></td>
                            <td><?php echo $row->typeDesc; ?></td>
                            <td><?php echo number_format($row->ahmContribution); ?></td>
                            <td><?php echo number_format($row->mdContribution); ?></td>
                            <td><?php echo number_format($row->dContribution); ?></td>
                          </tr>
                      <?php
                        }
                      }
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <th colspan="5">File Attachment</th>
                    <td colspan="5">
                      <?php 
                        if($get_attachment->num_rows()>0){
                          foreach ($get_attachment->result() as $row){?>
                          <tr>
                            <td colspan="6">
                              <a type="input" href="<?php echo base_url("h1/juklak_ahm/download_file?juklakNo=$row->juklakNo&ver=$row->version&islog=0&name=$row->fileName")?>" target="_blank" class="btn btn-success btn-xs"> <i class="fa fa-download"></i> Ver <?php echo $row->version?> - <?php echo $row->fileName?> </a>
                            </td>
                      <?php
                        }
                      }
                      ?>
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="6">	
                      <a href="h1/juklak_ahm">
                        <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin">Kembali</button>
                      </a>   
                      <?php if($get_data->statusJuklak==0 || ($get_juklak_baru!= false && $get_data->statusJuklak!=2)){ ?>
                        <button type="button" onclick="updateStatus('<?php echo $get_data->juklakNo; ?>','<?php echo $get_data->modifiedDate; ?>')" class="btn btn-warning btn-flat">Update & Notify to Master Program MD</button> 
                      <?php } ?>
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
            <!-- juklak baru -->
            <div class="col-md-6">
              <table class="table" width="100%">
                <thead>
                  <tr>          
                    <th width="40%" colspan="5">Juklak Baru</th>   
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="5"><?php if($get_juklak_baru!=false){ echo $get_juklak_baru[array_search($get_data->juklakNo,$get_juklak_baru)]->juklakNo; }else{ echo '-'; } ?></td>
                  </tr>
                  <tr>
                    <td colspan="5"><?php if($get_juklak_baru!=false){ echo $get_juklak_baru[array_search($get_data->juklakNo,$get_juklak_baru)]->descJuklak; }else{ echo '-'; }?></td>
                  </tr>
                  <tr>
                    <td colspan="5"><?php if($get_juklak_baru!=false){ echo $get_juklak_baru[array_search($get_data->juklakNo,$get_juklak_baru)]->segment; }else{ echo '-'; }?></td>
                  </tr>
                  <tr>
                    <td colspan="5"><?php if($get_juklak_baru!=false){ echo $get_juklak_baru[array_search($get_data->juklakNo,$get_juklak_baru)]->programCategory; }else{ echo '-'; }?></td>
                  </tr>
                  <tr>
                    <td colspan="5"><?php if($get_juklak_baru!=false){ echo $get_juklak_baru[array_search($get_data->juklakNo,$get_juklak_baru)]->subProgram; }else{ echo '-'; }?></td>
                  </tr>
                  <tr>
                    <td colspan="5"><?php if($get_juklak_baru!=false){ echo $get_juklak_baru[array_search($get_data->juklakNo,$get_juklak_baru)]->startPeriod; }else{ echo '-'; }?></td>
                  </tr>
                  <tr>
                    <td colspan="5"><?php if($get_juklak_baru!=false){ echo $get_juklak_baru[array_search($get_data->juklakNo,$get_juklak_baru)]->endPeriod; }else{ echo '-'; }?></td>
                  </tr>
                  <tr>
                    <td colspan="5"><?php if($get_juklak_baru!=false){ if($get_juklak_baru[array_search($get_data->juklakNo,$get_juklak_baru)]->uniqueCustomer!=''){ echo $get_juklak_baru[0]->uniqueCustomer; }else{ echo '0';} }else{ echo '-'; }?></td>
                  </tr>
                  <tr>
                    <td colspan="5"><?php if($get_juklak_baru!=false){ echo $get_juklak_baru[array_search($get_data->juklakNo,$get_juklak_baru)]->quota; }else{ echo '-'; }?></td>
                  </tr>
                  <tr>
                    <td colspan="5"><?php if($get_juklak_baru!=false){ echo $get_juklak_baru[array_search($get_data->juklakNo,$get_juklak_baru)]->modifiedDate; }else{ echo '-'; }?></td>
                  </tr>
                  
                  <tr>
                    <th colspan="6">Detail Target</th>
                      <tr>
                        <th>Tahun</th>
                        <th>Bulan</th>
                        <th>Target</th>    
                        <th colspan="2"></th>              
                      </tr>
                      <?php if($get_juklak_baru!=false){
                        foreach ($get_juklak_baru[array_search($get_data->juklakNo,$get_juklak_baru)]->targets as $target){
                      ?>
                        <tr>
                          <td><?php echo $target->year; ?></td>
                          <td><?php echo $target->month; ?></td>
                          <td><?php echo $target->target; ?></td>
                          <td colspan="2"></td>
                        </tr>
                      <?php 
                        }
                          }else{?>
                          <tr>
                            <td><?php echo '-'; ?></td>
                            <td><?php echo '-'; ?></td>
                            <td><?php echo '-'; ?></td>
                            <td colspan="2"></td>
                          </tr>
                      <?php
                        }
                      ?>
                    </td>
                  </tr>
                  <tr>
                  <th colspan="6">Detail Tipe</th>
                      <tr>
                        <th>Tipe</th>
                        <th>Deskripsi</th>
                        <th>AHM Cont.</th>
                        <th>MD Cont.</th>
                        <th>Dealer Cont.</th>
                      </tr>
                      <?php if($get_juklak_baru!=false){
                        foreach ($get_juklak_baru[array_search($get_data->juklakNo,$get_juklak_baru)]->unit as $unit){
                      ?>
                        <tr>
                          <td><?php echo $unit->type; ?></td>
                          <td><?php echo $unit->typeDesc; ?></td>
                          <td><?php echo number_format($unit->ahmContribution); ?></td>
                          <td><?php echo number_format($unit->mdContribution); ?></td>
                          <td><?php echo number_format($unit->dContribution); ?></td>
                        </tr>
                    <?php 
                        }
                      }else{?>
                      <tr>
                        <td><?php echo '-'; ?></td>
                        <td><?php echo '-'; ?></td>
                        <td><?php echo '-'; ?></td>
                        <td><?php echo '-'; ?></td>
                        <td><?php echo '-'; ?></td>
                      </tr>
                      <?php
                        }
                      ?>
                    </td>
                  </tr>
                    <th colspan="5">File Attachment</th>
                    <?php if($get_juklak_baru!=false){
                      $juklakNo=$get_juklak_baru[array_search($get_data->juklakNo,$get_juklak_baru)]->juklakNo;
                      foreach ($get_juklak_baru[array_search($get_data->juklakNo,$get_juklak_baru)]->attachments as $juklak){
                    ?>
                        <tr>
                          <td colspan="6">
                            <a type="input" href="<?php echo base_url("h1/juklak_ahm/download_file?juklakNo=$juklakNo&ver=$juklak->version&name=$juklak->fileName&islog=1")?>" target="_blank" class="btn btn-info btn-xs"> <i class="fa fa-download"></i> Ver <?php echo $juklak->version; ?> - <?php echo $juklak->fileName; ?> </a>
                          </td>
                        </tr>
                    <?php 
                      }
                    }else{ ?>    
                          <tr>
                            <td colspan="6"><button type="button" disabled class="btn btn-info btn-xs">Download</button></td>
                          </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php
    }
    ?>
  </section>
</div>

<script type="text/javascript">

var table;

$(document).ready(function() {
    //datatables
    
    table = $('#table').DataTable({
	      "scrollX": true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('h1/juklak_ahm/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ 0,5 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
    });
});


function updateStatus(id,tgl){
    var r = confirm("Apakah Anda yakin ingin melanjutkan?");

    if(r){
      var request = $.ajax({
        url: "<?php echo site_url('h1/juklak_ahm/update_status')?>",
        method: "POST",
        data: { id : id, date:tgl },
        dataType: "json"
      });
      
      request.done(function( msg ) {
        alert(msg.msg);
        window.location.href = "<?php echo base_url('h1/juklak_ahm')?>";
      });
      
      request.fail(function( jqXHR, textStatus ) {
        alert( "Request failed: " + textStatus );
      });
    }
  }

function downloadFile(file,ext){
    var request = $.ajax({
      url: "<?php echo site_url('h1/juklak_ahm/download_file')?>",
      method: "POST",
      data: { file : file, ext: ext }
    });
    
    request.done(function( msg ) {
      console.log(msg);
    });
    
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });
}
</script>
