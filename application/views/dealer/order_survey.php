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

<body onload="auto()">

<div class="content-wrapper">

<!-- Content Header (Page header) -->

<section class="content-header">

  <h1>

    <?php echo $title; ?>    

  </h1>

  <ol class="breadcrumb">

    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    

    <li class="">Customer</li>

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

          <?php if (isset($set_page)){ ?>

            <?php if ($set_page=='history'){ ?>

              <a href="dealer/order_survey">

            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>

            </a>         

            <?php }else{ ?>

                

          

          <?php } ?>

          <?php }else{ ?>

          <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  

          <a href="dealer/order_survey?set_page=history">

            <button class="btn bg-blue btn-flat margin"><i class="fa fa-check"></i> Cek History</button>

          </a>  

          <a href="dealer/order_survey/gc">

            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn btn-warning btn-flat margin"><i class="fa fa-users"></i> Grup Customer</button>

          </a>                 

        <?php } ?>

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

        <table id="table_order_survey_dealer" class="table table-bordered table-hover">

          <thead>

            <tr>

              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              

              <th width="5%">No</th>

              <th>No Order Surey</th>              

              <th>No SPK</th>              

              <th>Nama Konsumen</th>              

              <th>Leasing</th>

              <th>Alamat</th>              

              <th>Tipe</th>

              <th>Warna</th>

              <th>No.KTP</th>

              <th>Action</th>              

            </tr>

          </thead>

          <tbody>            
          </tbody>

        </table>

      </div><!-- /.box-body -->

    </div><!-- /.box -->



    <?php 

    }elseif($set=="view_gc"){

    ?>

    <div class="box">

      <div class="box-header with-border">

        <h3 class="box-title">

          <?php if (isset($set_page)){ ?>

            <?php if ($set_page=='history'){ ?>

              <a href="dealer/order_survey/gc">

            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>

            </a>         

            <?php }else{ ?>

                

          

          <?php } ?>

          <?php }else{ ?>

          <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  

          <a href="dealer/order_survey/gc?set_page=history">

            <button class="btn bg-blue btn-flat margin"><i class="fa fa-check"></i> Cek History GC</button>

          </a>  

          <a href="dealer/order_survey">

            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn btn-warning btn-flat margin"><i class="fa fa-users"></i> Individu</button>

          </a>                 

        <?php } ?>

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

              <th>No SPK</th>              

              <th>Nama NPWP</th>              

              <th>No NPWP</th>                            

              <th>Alamat</th>              

              <th>Finance Company</th>    

              <th>Status</th>          

              <th>Action</th>              

            </tr>

          </thead>

          <tbody>            

          <?php 

          $no=1; 

          foreach($dt_order_survey->result() as $row) {

            $cek=$this->db->query("SELECT max(no_order_survey_gc) as no_order_survey_gc FROM tr_order_survey_gc WHERE no_spk_gc='$row->no_spk_gc'")->row()->no_order_survey_gc;

            $cek2=$this->db->query("SELECT * FROM tr_hasil_survey_gc WHERE no_spk_gc='$row->no_spk_gc' AND status_approval = 'approved'");

            if (isset($set_page)) {

              if ($cek != $row->no_order_survey_gc OR $cek2->num_rows() > 0) {                  

                

                $leasing = $this->m_admin->getByID("ms_finance_company","id_finance_company",$row->id_finance_company);

                if($leasing->num_rows() > 0){

                  $rd = $leasing->row();

                  $fin = $rd->finance_company;

                }else{

                  $fin = "";

                }



                if($row->status_survey == 'process'){

                  $status = "<span class='label label-primary'>process</span>";                                

                }else{

                  $status = "<span class='label label-danger'>closed</span>";

                }



                echo "

                <tr>

                  <td>$no</td>                  

                  <td>$row->no_spk_gc</td>

                  <td>$row->nama_npwp</td>

                  <td>$row->no_npwp</td>

                  <td>$row->alamat</td>                   

                  <td>$fin</td>                            

                  <td>$status</td>                            

                  <td>                                

                    <a href='dealer/order_survey/cetak_gc?id=$row->no_order_survey_gc' target='_blank'>

                      <button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak Order Survey</button>

                    </a>
                    
                  </td>

                </tr>

                ";

                $no++;

              }

            }else{

              if ($cek == $row->no_order_survey_gc AND $cek2->num_rows() == 0) {                            



                $leasing = $this->m_admin->getByID("ms_finance_company","id_finance_company",$row->id_finance_company);

                if($leasing->num_rows() > 0){

                  $rd = $leasing->row();

                  $fin = $rd->finance_company;

                }else{

                  $fin = "";

                }

                if($row->status_survey == 'process'){

                  $status = "<span class='label label-primary'>process</span>";                                

                }else{

                  $status = "<span class='label label-danger'>closed</span>";

                }

                echo "

                <tr>

                  <td>$no</td>                  

                  <td>$row->no_spk_gc</td>

                  <td>$row->nama_npwp</td>

                  <td>$row->no_npwp</td>

                  <td>$row->alamat</td>                  

                  <td>$fin</td>                            

                  <td>$status</td>                            

                  <td>                                

                    <a href='dealer/order_survey/cetak_gc?id=$row->no_order_survey_gc' target='_blank'>

                      <button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak Order Survey</button>

                    </a>

                  </td>

                </tr>

                ";

                $no++;

              }

            }

          }

          ?>

          </tbody>

        </table>

      </div><!-- /.box-body -->

    </div><!-- /.box -->

<?php

    }elseif($set=="download"){
      $disabled = 'disabled';
    ?>
</script>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/order_survey">
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
            <form  class="form-horizontal" id="form_" action="" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <div class="col-md-12">
                    <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Data Customer</button><br><br>
                  </div>
                </div> 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID SPK</label>
                     <div class="col-sm-4">
                     <input type="text" name="no_spk" required class="form-control" value="<?= isset($row)?$row->no_spk:'' ?>" autocomplete="off" <?= $disabled ?>> 
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">
                     <input type="text" name="nama_konsumen" required class="form-control" value="<?= isset($row)?$row->nama_konsumen:'' ?>" autocomplete="off" <?= $disabled ?>> 
                  </div>
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">
                     <input type="text" name="nama_konsumen" required class="form-control" value="<?= isset($row)?$row->id_tipe_kendaraan.' | '.$row->tipe_ahm:'' ?>" autocomplete="off" <?= $disabled ?>> 
                  </div>
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                     <input type="text"required class="form-control" value="<?= isset($row)?$row->id_warna.' | '.$row->warna:'' ?>" autocomplete="off" <?= $disabled ?>> 
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-12">
                    <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-primary btn-flat btn-sm" disabled>Dokumen Pendukung</button><br><br>
                  </div>
                  <div class="col-md-12">
                    <table class="table table-bordered">
                      <thead>
                        <th>Nama</th>
                        <th>Keterangan</th>
                        <th width="10%">Download</th>
                      </thead>
                      <tr>
                        <td>KTP</td>
                        <td>-</td>
                        <td align="center"><a href="<?= base_url('assets/panel/files/'.$row->file_ktp_2) ?>" download><i class="fa fa-download"></i></a></td>
                      </tr>
                       <tr>
                        <td>KK</td>
                        <td>-</td>
                        <td align="center"><a href="<?= base_url('assets/panel/files/'.$row->file_kk) ?>" download><i class="fa fa-download"></i></a></td>
                      </tr>
                      <?php $dokumen = $this->db->get_where('tr_spk_file',['no_spk'=>$row->no_spk])->result() ?>
                      <?php foreach ($dokumen as $key=>$dk): ?>
                        <tr>
                          <td>Dokumen Pendukung <?= $key+1 ?></td>
                          <td><?= $dk->nama_file ?></td>
                          <td align="center"><a href="<?= base_url('assets/panel/spk_file/'.$dk->file) ?>" download><i class="fa fa-download"></i></a></td>
                        </tr>
                      <?php endforeach ?>
                    </table>
                  </div>
                </div>   
              </div><!-- /.box-body -->
             <div>
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
<?php } ?>
  </section>

  </section>

</div>

<script>
  $( document ).ready(function() {
   tabless = $('#table_order_survey_dealer').DataTable({
	      "scrollX": true,
        "processing": true, 
        "bDestroy": true,
        "serverSide": true, 
        "order": [],
        "ajax": {
          "url": "<?php  echo site_url('dealer/order_survey/fetch_data_spk_datatables')?>",
            "type": "POST"
        },  
              
        "columnDefs": [
        {
            "targets": [ 0,5 ],
            "orderable": false, 
        },
        ],
        });
});
</script>