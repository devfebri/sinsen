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
              <th>Program MD</th>          
              <th>Deskripsi Program</th>   
              <th>Segment</th>                     
              <th>Kategori Program</th>      
              <th>Subkategori Program</th>            
              <th>Periode Awal</th>
              <th>Periode Akhir</th>           
              <th>Tgl Maks. PO</th>
              <th>Tgl Maks. BASTK</th>    
              <th>Quota</th>                    
              <th>Unique Customer</th>                       
              <th>KK Validation</th>                       
              <th>Created Date</th>                       
              <th>Modified Date</th>                    
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
    }else if($set=="view_detail"){
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
              
              $unik ='-'; 
              $kk ='-';
              
              if($get_data->unique_customer == 1){
                $unik ='Ya';
              }
              if($get_data->kk_validation == 1){
                $kk = 'Ya';
              }
          ?>
          <div class="table-responsive">	
            <table class="table" width="100%">
              <thead>
                <tr>          
                  <th width="20%"></th>
                  <th width="40%" colspan="5"></th>  
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Program MD</td>
                  <td colspan="5"><?php echo $get_data->id_program_md; ?></td>
                </tr>
                <tr>
                  <td>Deskripsi Program MD</td>
                  <td colspan="5"><?php echo $get_data->judul_kegiatan; ?></td>
                </tr>
                <tr>
                  <td>Segment</td>
                  <td colspan="5"><?php echo $get_data->segment; ?></td>
                </tr>
                <tr>
                  <td>Kategori Program</td>
                  <td colspan="5"><?php echo $get_data->kategori_program; ?></td>
                </tr>
                <tr>
                  <td>Subkategori Program</td>
                  <td colspan="5"><?php echo $get_data->sub_kategori_program; ?></td>
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
                  <td>Tanggal Maks. PO</td>
                  <td colspan="5"><?php echo $get_data->tanggal_maks_po; ?></td>
                </tr>
                <tr>
                  <td>Tanggal Maks. BASTK</td>
                  <td colspan="5"><?php echo $get_data->tanggal_maks_bastk; ?></td>
                </tr>

                <tr>
                  <td>Quota</td>
                  <td colspan="5"><?php echo $get_data->kuota_program; ?></td>
                </tr>
                <tr>
                  <td>Target Program</td>
                  <td colspan="5"><?php echo $get_data->target_penjualan; ?></td>
                </tr>
                <tr>
                  <td>Unique Customer</td>
                  <td colspan="5"><?php echo $unik; ?></td>
                </tr>
                <tr>
                  <td>KK Validation</td>
                  <td colspan="5"><?php echo $kk; ?></td>
                </tr>
                <tr>
                  <td>Created Date</td>
                  <td colspan="5"><?php echo date_format(date_create($get_data->created_at),"Y-m-d"); ?></td>
                </tr>
                <tr>
                  <td>Modified Date</td>
                  <td colspan="5"><?php echo date_format(date_create($get_data->updated_at),"Y-m-d"); ?></td>
                </tr>
                <tr>              
                  <th>Kode Tipe</th>       
                  <th>Warna</th>
                  <th>Tahun Produksi</th>
                  <th>Kontribusi</th>
                  <th>Cash</th>
                  <th>Kredit</th>
                </tr>
                <?php 
                  if($get_type->num_rows()>0){
                    foreach ($get_type->result() as $row){
                ?>
                    <tr>
                      <td rowspan="7">
                          <?php $kendaraan = $this->db->query("SELECT id_tipe_kendaraan, tipe_ahm FROM ms_tipe_kendaraan where id_tipe_kendaraan = '$row->id_tipe_kendaraan' order by id_tipe_kendaraan ASC");
                          if ($kendaraan->num_rows() > 0) {  ?>
                            <?php foreach ($kendaraan->result() as $rs):
                              ?>
                              <?php echo  $rs->id_tipe_kendaraan ?> | <?php echo  $rs->tipe_ahm ?>
                            <?php endforeach ?>
                          <?php } ?>
                      </td>
                      <td rowspan="7">
                        <?php 
                            $warna = explode(',', $row->id_warna);
                            foreach ($warna as $wrn => $iwarna){
                              $dt_warna = $this->db->query("SELECT id_warna, warna FROM ms_warna where id_warna ='$iwarna' order by id_warna ASC")->row();
                              echo $dt_warna->id_warna .' - '. $dt_warna->warna.'<br><br>';
                            } 
                        ?>
                      </td>
                      <td rowspan="7"><?php echo $row->tahun_produksi;?></td>
                      <td><b>AHM</b></td><td><?php echo number_format($row->ahm_cash)?></td><td><?php echo number_format($row->ahm_kredit)?></td>
                    </tr>
                    <tr>
                      <td><b>MD</b></td><td><?php echo number_format($row->md_cash)?></td><td><?php echo number_format($row->md_kredit)?></td>
                    </tr>
                    <tr>
                      <td><b>Dealer</b></td><td><?php echo number_format($row->dealer_cash)?></td><td><?php echo number_format($row->dealer_kredit)?></td>
                    </tr>
                    <tr>
                      <td><b>Finco</b></td><td><?php echo number_format($row->other_cash)?></td><td><?php echo number_format($row->other_kredit)?></td>
                    </tr> 
                    <tr>
                      <td><b>Add. MD</b></td><td><?php echo number_format($row->add_md_cash)?></td><td><?php echo number_format($row->add_md_kredit)?></td>
                    </tr>
                    <tr>
                      <td><b>Add. Dealer</b></td><td><?php echo number_format($row->add_dealer_cash)?></td><td><?php echo number_format($row->add_dealer_kredit)?></td>
                    </tr>
                    <tr>
                      <td><b>Total</b></td><td><?php echo number_format(($row->ahm_cash+$row->md_cash+$row->dealer_cash+$row->other_cash+$row->add_md_cash+$row->add_dealer_cash)); ?></td><td><?php echo number_format($row->ahm_kredit+$row->md_kredit+$row->dealer_kredit+$row->other_kredit+$row->add_md_kredit+$row->add_dealer_kredit)?></td>
                    </tr>
                  <?php
                  } 
                }?>

                <tr>
                <th colspan="6">Syarat dan Kelengkapan Dokumen</th>
                    <?php 
                      $i=0;
                      if($get_syarat->num_rows()>0){
                        foreach ($get_syarat->result() as $row){
                          $i++;  
                        ?>
                        <tr>
                        <td colspan="6">
                          <?php echo $i.'. '.$row->syarat_ketentuan;?>
                        </td>                     
                        </tr>
                    <?php
                      }
                    }
                    ?>
                  </td>
                </tr>
                
                <tr>
                <th colspan="6">File Attachment</th>
                  <tr>
                    <td colspan="6">
                      <a type="input" href="<?php echo base_url("dealer/juklak/download_file?id_program_md=$get_data->id_program_md")?>" target="_blank" class="btn btn-success btn-xs"> <i class="fa fa-download"></i> <?php echo $get_data->file_name?></a>
                    </td>                     
                  </tr>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="6">	
                    <a href="dealer/juklak">
                      <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin">Kembali</button>
                    </a>   
                  </td>
                </tr>
              </tfoot>
            </table>
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
            "url": "<?php echo site_url('dealer/juklak/ajax_list')?>",
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

function downloadFile(file,ext){
    var request = $.ajax({
      url: "<?php echo site_url('dealer/juklak/download_file')?>",
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
