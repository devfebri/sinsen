<?php 

function mata_uang3($a){

  if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);

    if(is_numeric($a) AND $a != 0 AND $a != ""){

      return number_format($a, 0, ',', '.');

    }else{

      return $a;

    }        

}

function bln($a){

  $bulan=$bl=$month=$a;

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



<base href="<?php echo base_url(); ?>" />

<div class="content-wrapper">

<!-- Content Header (Page header) -->

<section class="content-header">

  <h1>

    <?php echo $title; ?>    

  </h1>

  <ol class="breadcrumb">

    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    

    <li class="">H3</li>

    <li class="">Laporan</li>

    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>

  </ol>

  </section>

  <section class="content">

    

    



    <div class="box box-default">

      <div class="box-header with-border">        

        <div class="row">

          <div class="col-md-12">

            <form class="form-horizontal" action="h1/monitoring_utilisasi/download" id="frm" method="post" enctype="multipart/form-data">

              <div class="box-body">                                                                              

                <div class="form-group">                                    
                  <div class="col-sm-3">
                    <label>Periode Awal</label>
                    <input placeholder="Periode Awal" type="text" autocomplete="off" name="started" id="tanggal1" class="form-control">
                  </div>                  
                  <div class="col-sm-3">
                    <label>Periode Akhir</label>
                    <input placeholder="Periode Akhir" type="text" autocomplete="off" name="ended" id="tanggal2" class="form-control">
                  </div>  
                  <div class="col-sm-3">
                    <label>Kode Part</label>
                    <input placeholder="Kode Part" type="text" autocomplete="off" name="kode_part" id="kode_part" class="form-control">
                  </div>  
                  <div style="margin-top:7px">
                      <br>
                      <button class="btn btn-flat btn-sm btn-primary"  id="search"><span class="fa fa-search"></span> Cari</button>
                      <button class="btn btn-flat btn-sm btn-warning"  id="reset"><span class="fa fa-trash"></span> Reset</button>
                     
                  </div>

                </div>
                </div> 
            <div class="table-responsive" id="table">
            <table id="datatable_server" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Kode Part</th>
                  <th>Gudang</th>
                  <th>Rak</th>
                  <th>Jenis Transaksi</th>
                  <th>Tanggal & Waktu</th>
                  <th>Sumber Transaksi</th>
                  <th>Referensi</th>
                  <th>Stok Awal</th>
                  <th>Stok Value</th>
                  <th>Stok Akhir</th>
                </tr>
              </thead>
            </table>
          </div>
          
          <script>
               $(document).ready(function(){
                  $('#reset').click(function(e){
                       e.preventDefault();
                        $('#tanggal1').val("");  
                        $('#tanggal2').val("");
                        $('#kode_part').val("");
                        dataTable.draw();
                  });
                  $('#search').click(function(e){
                       e.preventDefault();
                        dataTable.draw();
                  });
                });
          </script>
          <script>
  
            $(document).ready(function() {
               dataTable = $('#datatable_server').DataTable({
                "processing": true,
                "serverSide": true,
                "searching":true,
                "language": {
                  "infoFiltered": "",
                  "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
                },
                "order": [],
                "lengthMenu": [
                  [10, 25, 50, 75, 100],
                  [10, 25, 50, 75, 100]
                ],
                "ajax": {
                  url: "<?php echo site_url('dealer/tracking_transaksi_part/fetch_data'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                  d.started = $('#tanggal1').val();
                  d.ended = $('#tanggal2').val();
                  d.kode_part = $('#kode_part').val();
                  },
                },
                "columnDefs": [
                 
                  {
                    "targets": [5],
                    "className": 'text-center'
                  },
                ],
              });
              dataTable.on('draw', function() {
              var info = dataTable.page.info();
              dataTable.column(0, {
                  search: 'applied',
                  order: 'applied',
                  page: 'applied'
              }).nodes().each(function(cell, i) {
                  cell.innerHTML = i + 1 + info.start + ".";
              });
            });
            });
          </script>

              </div><!-- /.box-body -->                           

            </form>

            <!-- <div id="imgContainer"></div> -->

          </div>

        </div>

      </div>

    </div><!-- /.box -->

</section>

</div>
<script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    

    