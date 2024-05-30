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

    <li class="">H23</li>

    <li class="">Laporan</li>

    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>

  </ol>

  </section>

  <section class="content">

    

    



    <div class="box box-default">

      <div class="box-header with-border">        

        <div class="row">

          <div class="col-md-12">

            
               
            <div class="table-responsive" id="table">
            <table id="datatable_server" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>ID WO</th>
                  <th>Tanggal Create</th>
                  <th>Status</th>
                  <th>Referensi</th>
                  <th>ID Jasa</th>
                  <th>Deskripsi</th>
                  <th>ID Part</th>
                  <th>Qty</th>
                  <th>Nomor SO</th>
                  
                </tr>
              </thead>
            </table>
          </div>
          <script>
                    $(document).ready(function() {
                        datatable_server = $('#datatable_server').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('dealer/list_part_booking/fetch') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                  return d;
                                },
                                type: "POST"
                            },
                            columns: [
                                { data: 'index', orderable: false, width: '3%' }, 
                                { data: 'id_work_order' }, 
                                { data: 'tanggal' }, 
                                { data: 'status' }, 
                                { data: 'referensi' }, 
                                { data: 'id_jasa' }, 
                                { data: 'deskripsi' }, 
                                { data: 'id_part' }, 
                                { data: 'qty' }, 
                                { data: 'nomor_so' }, 
                               
                            ],
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




    

    