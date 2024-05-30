
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">DMS Extension</li>
      <li class="">H1</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>

  <section class="content">
    <?php
       if ($set == "sales_force_type") { ?>


       <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
                      <a href="dealer/dms_h1_target_management/setting_target_flp_from_md/?id=<?=$this->input->get('id');?>"><button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button></a>
                      <a href="dealer/dms_h1_target_management/download_target_tipe_kendaraan/?id=<?=$this->input->get('id');?>"><button class="btn bg-blue btn-flat margin"><i class="fa fa-download"></i> Download Excel From MD </button></a>
                      <!-- <a href="dealer/dms_h1_target_management/download_target_tipe_kendaraan_process/?id=<?=$this->input->get('id');?>"><button class="btn bg-blue btn-flat margin"><i class="fa fa-download"></i> Download Excel</button></a> -->
                   
                      <form action="dealer/dms_h1_target_management/setting_sales_force_save" method="post"  >
                 
                    </h3>
                    <div class="box-tools pull-right">
                      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                      <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                  </div>
                  <div class="box-body">

                  <div class="form-group">
                    <label class="col-sm-2 control-label">No Register</label> <div class="col-sm-4">
                      <input type="text" name="no_register_target_sales" value="<?=$this->input->get('id');?>" class="form-control" readonly>
                </div> 

                <div class="form-group">
                    <label class="col-sm-2 control-label">Total Target Deal FLP (suggestion)</label> <div class="col-sm-4">
                      <input type="text" name="no_register_target_sales" value="<?=$jumlah_target_md_max?>" class="form-control" readonly>
                </div> 

                <br><br>
                </div> 
                <hr>
                <div class="table-container">
                  <!-- <table class="table" id="myTable" > -->
                  <table class="table" id="example2" class="target_from_md">
                  <thead>
                       <tr>
                       <th colspan="2"></th>
                       </tr>
                      
                    <tr>
                      <th scope="col">NO</th>
                      <th scope="col">TIPE KENDARAAN</th>
                      <?php 
                          foreach ($flp as $row) { ?>
                                <th scope="col">
                                  <?=$row->nama_lengkap?></th>
                      <?}
                      ?>
                      <th scope="col">TOTAL TARGET TIPE</th>
                
                    </tr>
                  </thead>

                  <tbody>
                    <?php   
                       $no = 1;
                       
                       $sum_array =array();
                       $sum_array_tipe = array();

                       $sum_no =array();
                       $sum_from_md =array();

                       foreach ($flp_tipe as $item) : 
                        $sum_no[]=1;

                       ?>
                      <tr>
                      <td><?=$no++?></td>
                          <td><b>
                          <?= $item['id_tipe_kendaraan'];?></b>
                        </td>
                          <?php
                          $total = 0;
                          $jumlah_baris = array();
                          foreach ($item['sales_data'] as $key => $sales) {
                            $jumlah_baris[]=1;
                              echo "<td class='col$key'>" . $sales['avg_tot'] . "</td>";
                              $tes = $sum_array_tipe[$item['id_tipe_kendaraan']]= floatval($sales['avg_tot']);
                              $sum_array[]= floatval($sales['avg_tot']);
                              $total += floatval($sales['avg_tot']);
                          }
                          ?>
                          <td class='colProcess'>
                          <?= $total;?>
                          </td>
                          <!-- <td class='colFromMD'><?php //  $sum_from_md[] = $item['jumlah_from_md']; 
                              // echo $item['jumlah_from_md']; 
                          ?>
                          </td> -->
                      </tr>
                  <?php endforeach;?>
            </form>
          </tbody>
          <tfoot>
            <tr id="sumRow"></tr>
            <input type="hidden" class="jumlahBariInput" value="<?= $jumlah_baris ?>">
            
          </tfoot>
          </table>
     
          </div><!-- /.box-body -->
          <div class="box-footer text-center">
          </div>
        </div><!-- /.box -->

        <script>
        $(document).ready(function () {
          
          // var colSums = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

          var numColumns = $('#example2 tbody tr:first-child td').length;

// Initialize an array with zeros based on the number of columns
          var colSums = Array.from({ length: numColumns }, () => 0);

          $('#example2 tbody tr').each(function () {
            $(this).find('td').each(function (index) {
              colSums[index] += parseFloat($(this).text()) || 0;
            });
          });

          var sumRow = '';
          for (var i = 0; i < colSums.length; i++) {
            if (i === 0 ) {
              sumRow += '<td>Total</td>';
            }else if ( i === 1) {
              sumRow += '<td></td>';
             } else {
              sumRow += '<td><b>' + colSums[i] + '</b></td>';
            }
          }

          $('#sumRow').html(sumRow);
        });
      </script>

      <?php } 
    else if ($set == "index") { ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <?php if (can_access($isi, 'can_insert')) : ?>
              <a href="<?= $folder . '/' . $isi ?>/add"> <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
              </a>
              <a href="<?= $folder . '/' . $isi ?>/upload"> <button class="btn btn-info btn-flat margin"><i class="fa fa-upload"></i> Upload .csv</button>
              </a>
            <?php endif; ?>
            <a href="<?= $folder . '/' . $isi ?>/history"> <button class="btn btn-primary btn-flat margin"><i class="fa fa-list"></i> History</button>
            </a>

            <a href="<?= $folder . '/' . $isi ?>/setting_target_flp"> <button class="btn btn-warning btn-flat margin"><i class="fa fa-bullseye" aria-hidden="true"></i> Setting Target FLP </button>
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
          <table id="datatable_server" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Tahun</th>
                <th>Bulan</th>
                <th>Kode Dealer</th>
                <th>Honda ID</th>
                <th>Nama Salespeople</th>
                <!-- <th>Kode Tipe</th>
                <th>Tipe</th> -->
                <th>Target Sales</th>
                <th>Active</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              var dataTable = $('#datatable_server').DataTable({
                "processing": true,
                "serverSide": true,
                "scrollX": true,
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
                  url: "<?php echo site_url($folder . '/' . $isi . '/fetch'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                    d.periode = '<?= get_ym() ?>';
                    return d;
                  },
                },
                "columnDefs": [
                  {
                    "targets": [5, 6],
                    "className": 'text-center'
                  },
                ],
              });
            });
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php } elseif ($set == "history") { ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/dms_h1_target_management">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>

            </a>
          </h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="datatable_server" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Tahun</th>
                <th>Bulan</th>
                <th>Kode Dealer</th>
                <th>Honda ID</th>
               <!--  <th>Kode Tipe</th>
                <th>Tipe</th> -->
                <th>Nama</th>
                <th>Target Sales</th>
                <th>Active</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              var dataTable = $('#datatable_server').DataTable({
                "processing": true,
                "serverSide": true,
                "scrollX": true,
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
                  url: "<?php echo site_url($folder . '/' . $isi . '/fetch'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                    d.is_history = true;
                    d.periode_lebih_kecil = '<?= get_ym() ?>';
                    return d;
                  },
                },
                "columnDefs": [
                  // { "targets":[2],"orderable":false},
                  {
                    "targets": [5, 6],
                    "className": 'text-center'
                  },
                  // {f
                  //   "targets": [3],
                  //   "className": 'text-right'
                  // },
                  // // { "targets":[0],"checkboxes":{'selectRow':true}}
                  // { "targets":[4],"className":'text-right'}, 
                  // // { "targets":[2,4,5], "searchable": false } 
                ],
              });
            });
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php } 

else if ($set == "add_sales_force") { ?>
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
      <table id="example4" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th width="20">No</th>
            <th>No. Register</th>
            <th>Bulan</th>
            <!-- <th>Priode Target</th> -->
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php 
           $no = 1;
          foreach ($set_sales_force as $row) { ?>
          <tr>
                <td><?=$no++?></td>
                <!-- <td> <a href="/dealer/dms_h1_target_management/detail_sales_force/?id=<?php //$row->no_register_target_sales?>"><?=$row->no_register_target_sales?></a></td> -->
                <td><?=$row->no_register_target_sales?></td>
                <td><?=$row->priode_target?></td>
                <!-- <td><?php //$row->priode_awal?> - <?php //$row->priode_akhir?></td> -->
                <td><?=$row->status?></td>
                <td class="text-center">
                <a href="/dealer/dms_h1_target_management/setting_target_flp_from_md/?id=<?=$row->no_register_target_sales?>"  class="btn btn-primary btn-flat "><i class="fa fa-pencil" aria-hidden="true"></i></a>
                </td>
          </tr>
          <?}?>
        </tbody>
      </table>  
    
    </div><!-- /.box-body -->
  </div><!-- /.box -->
<?php } elseif ($set == "history") { ?>
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">
        <a href="dealer/dms_h1_target_management">
          <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
        </a>
      </h3>
      <div class="box-tools pull-right">
        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
      </div>
    </div><!-- /.box-header -->
    <div class="box-body">
      <table id="datatable_server" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>Tahun</th>
            <th>Bulan</th>
            <th>Kode Dealer</th>
            <th>Honda ID</th>
           <!--  <th>Kode Tipe</th>
            <th>Tipe</th> -->
            <th>Nama</th>
            <th>Target Sales</th>
            <th>Active</th>
            <th>Aksi</th>
          </tr>
        </thead>
      </table>
      <script>
        $(document).ready(function() {
          var dataTable = $('#datatable_server').DataTable({
            "processing": true,
            "serverSide": true,
            "scrollX": true,
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
              url: "<?php echo site_url($folder . '/' . $isi . '/fetch'); ?>",
              type: "POST",
              dataSrc: "data",
              data: function(d) {
                d.is_history = true;
                d.periode_lebih_kecil = '<?= get_ym() ?>';
                return d;
              },
            },
            "columnDefs": [
              // { "targets":[2],"orderable":false},
              {
                "targets": [5, 6],
                "className": 'text-center'
              },
              // {f
              //   "targets": [3],
              //   "className": 'text-right'
              // },
              // // { "targets":[0],"checkboxes":{'selectRow':true}}
              // { "targets":[4],"className":'text-right'}, 
              // // { "targets":[2,4,5], "searchable": false } 
            ],
          });
        });
      </script>
    </div><!-- /.box-body -->
  </div><!-- /.box -->
<?php } 
    elseif ($set == "detail_sales_force") { 
      if ($mode=='approve'){
        $readonly ="";
        // $approve ='<button  type="submit" class="btn bg-blue btn-flat margin" onclick="return confirm("Approve this data?")" ><i class="fa fa-save"></i> Approve</button>';
        // $approve ='<button  type="submit" class="btn bg-blue btn-flat margin" onclick="return confirm("Approve this data?")" ><i class="fa fa-save"></i> Approve</button>';
      }else{
        $readonly ="readonly";
      }
?>
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
                      <a href="dealer/dms_h1_target_management/setting_target_flp"><button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button></a>
                      <a href="dealer/dms_h1_target_management/tipe_kedaraan_by_md?id=<?=$this->input->get('id'); ?>"><button class="btn bg-yellow btn-flat margin"><i class="fa fa fa-motorcycle"></i> Target By Tipe Motor</button></a>
                       <form action="dealer/dms_h1_target_management/setting_sales_force_save" method="post"  >
                    </h3>
                    <div class="box-tools pull-right">
                      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                      <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                  </div>
                  <div class="box-body">

                  <div class="form-group">
                    <label class="col-sm-2 control-label">No Register</label> <div class="col-sm-4">
                      <input type="text" name="no_register_target_sales" value="<?=$sales_force_header->no_register_target_sales?>" class="form-control" readonly>
                </div> 


                <div class="form-group">
                    <label class="col-sm-2 control-label">Periode Bulan</label> <div class="col-sm-4">
                      <input type="text"  value="<?=$sales_force_header->priode_target?>" disabled="disabled" class="form-control">
                </div> 

                <div class="form-group">
                    <label class="col-sm-2 control-label">Total Target Deal FLP</label> <div class="col-sm-4">
                      <input type="text"  disabled="disabled" value="" class="form-control" id="total_prospek_dealer_total">
                </div> 


                <div class="form-group">
                    <label class="col-sm-2 control-label">Total Target Dealer</label> <div class="col-sm-4">
                      <input type="text" name="revenue_event_target" id='total_target_dealer' disabled="disabled" value="" class="form-control">
                </div> 


                <div class="form-group">
                    <label class="col-sm-2 control-label">Total Target Prospek FLP</label> <div class="col-sm-4">
                      <input type="text" name="revenue_event_target" id='total_prospek_dealer'  disabled="disabled" value="" class="form-control" >
                </div> 

                <div class="form-group">
                    <label class="col-sm-2 control-label"></label> <div class="col-sm-4">
                </div> 
                

                <div class="form-group">       
                  <table class="table">
                  <thead>
                      <tr>
                        <th  colspan="2" scope="col" ></th>
                      </tr>
                    <tr>
                      <th scope="col">No</th>
                      <th scope="col">Nama FLP</th>
                      <th scope="col">M-3</th>
                      <th scope="col">M-2</th>
                      <th scope="col">M-1</th>
                      <!-- <th scope="col">AVG</th> -->
                      <!-- <th scope="col">Percent %</th> -->
                      <!-- <th scope="col">Tipe Kendaraan</th> -->
                      <!-- <th scope="col">From Dealer - Sales(X1)</th> -->
                      <!-- <th scope="col">Sales (X1)</th> -->
                      <th scope="col">Target Deal (Suggestion)</th>
                      <th scope="col">Target Prospect (Suggestion)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php   
                    $array_ssu_1 = array();
                    $array_ssu_2 = array();
                    $array_ssu_3 = array();
                    $array_total_dealer = array();
                    $array_total_percent = array();
                    $no = 1;

                    // $total_target_dealer =  array();
                    $total_target_prospek =  array();

                    foreach ($set_sales_force as $row) { 
                      $array_ssu_1[]        = $row['tot_ssu_m_1'];
                      $array_ssu_2[]        = $row['tot_ssu_m_2'];
                      $array_ssu_3[]        = $row['tot_ssu_m_3'];
                      $array_ssu_avg[]      = $row['avg_tot'];
                      $array_total_dealer[] = $row['sales_force'];

                      // $sales_force   = max( $row['tot_ssu_m_1'], $row['tot_ssu_m_2'],  $row['tot_ssu_m_3']);
                      $sales_force  = $row['tot_ssu_max'];

                      $sales_force_prospek_count =  ($sales_force/ 0.8) * 4;

                      $sales_force_prospek              = $row['avg_tot'] * 4; 
                      $sales_force_target_sales         =  $row['sales_force'];
                      $target_prospek =  $row['target_prospek'] ;
                      $array_total_percent[]            = $row['percent'];
                      ?>
                  <tr>

                    <td><?=$no++?></td>
                    <td><?=$row['nama_lengkap']?> <input type="hidden" name="honda_id[]" value="<?=$row['id_flp_md']?>" ></td>
                    <td><?=$row['tot_ssu_m_3']?></td>
                    <td><?=$row['tot_ssu_m_2']?></td>
                    <td><?=$row['tot_ssu_m_1']?></td>
                    <!-- <td><?php //$row['avg_tot']?></td> -->
                    
                    <!-- <td>
                      <input type="text" name="from_dealer[]" class="form-control numeric-inputs" maxlength="2" value="<?=$row['target_spk']?>" readonly>
                    </td> -->

                    <!-- <td>
                      <input type="text" name="sales1[]"    class="form-control numeric-input" maxlength="2" value="<?php //$sales_force?>" <?=$readonly?>>
                    </td>
                     -->
                    <td>
                      <input type="hidden" name="deal1[]"     class="form-control numeric-input-x1" maxlength="2"  value="<?=$sales_force?>" <?=$readonly?>> <?=$sales_force?>
                    </td>
                    <td>
                      <input type="hidden" name="prospek4[]"  class="form-control numeric-input-x4" maxlength="2"  value="<?=$sales_force_prospek_count ?>" <?=$readonly?>> <?=$sales_force_prospek_count ?>
                    </td>
                  </tr>

                  <?php }?>
                  <!-- Total -->
                  <tr>
                    <td colspan="2"><b>Total</b></td>
                    <td><b><?= array_sum($array_ssu_3); ?></b></td>
                    <td><b><?= array_sum($array_ssu_2); ?></b></td>
                    <td><b><?= array_sum($array_ssu_1); ?></b></td>
                    <!-- <td><b><?php // array_sum($array_ssu_avg); ?></b></td> -->
                    <!-- <td><b><?php// array_sum($array_total_percent); ?> %</b></td> -->
                    <!-- <td></td> -->

                    <!-- <td>
                      <input type="text" name="total_from_dealer[]"  value="<?php // array_sum($array_ssu_avg); ?>" class="form-control " id='from_dealer_total_x1' disabled>
                    </td> -->

                    <!-- <td>
                      <input type="text" name="total_sales1[]"      class="form-control " id='sales_total_x1' disabled>
                    </td> -->

                    <td>
                      <div class='deal_total_x1_class' id="deal_total_x1_class"></div>
                      <!-- <input type="text" name="total_deal1[]"      class="form-control " id='deal_total_x1' disabled> -->
                    </td>

                    <td>
                    <div class='prospek_total_x4_class' id="prospek_total_x4_class"></div>
                      <!-- <input type="text" name="total_prospek4[]"  class="form-control " id='prospek_total_x4' disabled> -->
                    </td>
                  </tr>
            </form>
          </tbody>
          </table>

          </div>
          </div><!-- /.box-body -->
          <div class="box-footer text-center">
          <?=$approve ?>
          </div>
        </div><!-- /.box -->

<script>



$(document).ready(function() {
  updateTotal();
  function checkSameTotal(){
    var sales   = $("#header_set_sales_flp").val();
    var deal    = $("#header_set_deal_flp").val();
    var prospek = $("#header_set_prospek_flp").val();
    var set_sales_total_x1   = $("#sales_total_x1").val();
    var set_deal_total_x1    = $("#deal_total_x1").val();
    var set_prospek_total_x4 = $("#prospek_total_x4").val();

    // if (sales === set_sales_total_x1) {
    //   $("#sales_total_x1").css("background-color", "green");
    // }else{
    //   $("#sales_total_x1").css("background-color", "green");
    // }

    // if (deal === set_deal_total_x1) {
    //   $("#sales_total_x1").css("background-color", "green");
    // }else{
    //   $("#sales_total_x1").css("background-color", "");
    // }

    // if (prospek === set_prospek_total_x4) {
    //   $("#sales_total_x1").css("background-color", "green");
    // }else{
    //   $("#sales_total_x1").css("background-color", "");
    // }

    // if (suspek === set_suspek_total_x4) {
    //   $("#sales_total_x1").css("background-color", "green");
    // }else{
    //   $("#sales_total_x1").css("background-color", "");
    // }

  }


  function updateTotal() {
    let total_sales = 0;
    let total_deal = 0;
    let total_prospek = 0;
    let total_from_dealer = 0;

    $("tr").each(function() {
      
      const numericValueSales = parseFloat($(this).find(".numeric-input").val()) || 0;
      total_sales += numericValueSales;

      const numericValueDeal = parseFloat($(this).find(".numeric-input-x1").val()) || 0;
      total_deal += numericValueDeal;

      const numericValueProspek = parseFloat($(this).find(".numeric-input-x4").val()) || 0;
      total_prospek += numericValueProspek;

      const numericValueDealer = parseFloat($(this).find(".numeric-input-x8").val()) || 0;
      total_from_dealer += numericValueDealer;
    });

    $("#sales_total_x1").val(total_sales);

    $("#total_target_dealer").val(total_deal);
    $("#total_prospek_dealer_total").val(total_deal);
    $("#total_prospek_dealer").val(total_prospek);
    

    $("#deal_total_x1").val(total_deal);
    $("#prospek_total_x4").val(total_prospek);

    // $("#deal_total_x1_class").val(total_prospek);
    // $("#prospek_total_x4_class").val(total_prospek);

    $(".deal_total_x1_class").text(total_deal);
    $(".prospek_total_x4_class").text(total_prospek);
  }


  $(".numeric-input-x1").on("input", function() {
    let total_deal = 0;
    $("tr").each(function() {
      const numericValueDeal = parseFloat($(this).find(".numeric-input-x1").val()) || 0;
      total_deal += numericValueDeal;
    });
    $("#deal_total_x1").val(total_deal);
  });

  
  $(".numeric-input-x4").on("input", function() {
    let total_prospek = 0;
    $("tr").each(function() {
      const numericValueDeal = parseFloat($(this).find(".numeric-input-x4").val()) || 0;
      total_prospek += numericValueDeal;
    });
    $("#prospek_total_x4").val(total_prospek);
  });


  $(".numeric-input-x8").on("input", function() {
    let total_prospek = 0;
    $("tr").each(function() {
      const numericValueDeal = parseFloat($(this).find(".numeric-input-x8").val()) || 0;
      total_prospek += numericValueDeal;
    });
    $("#suspek_total_x4").val(total_prospek);
  });



  $(".numeric-input").on("input", function() {
    const maxLength = parseInt($(this).attr("maxlength"));
    const inputValue = $(this).val();

    const numericValue = inputValue.replace(/[^0-9]/g, '');

    if (numericValue.length > maxLength) {
      $(this).val(numericValue.slice(0, maxLength));
    } else {
      $(this).val(numericValue);
    }

    if (numericValue.length === maxLength) {
      const nextInput = $(this).closest("td").next().find(".numeric-input");
      if (nextInput.length) {
        nextInput.focus();
      }
    }

    $(this).closest("tr").find(".numeric-input-x1").val(numericValue);
    
    const inputMultiplierX4 = 4;
    const resultValueX4 = isNaN(numericValue) ? '' : (parseFloat(numericValue) * inputMultiplierX4);
    $(this).closest("tr").find(".numeric-input-x4").val(resultValueX4);

    const inputMultiplierX8 = 8;
    const resultValueX8 = isNaN(resultValueX4) ? '' : (parseFloat(resultValueX4) * inputMultiplierX8);
    $(this).closest("tr").find(".numeric-input-x8").val(resultValueX8);

    updateTotal();
    checkSameTotal();
  });

  $(".numeric-input").on("keydown", function(event) {
    if (event.key === "Enter") {
      event.preventDefault();
      const nextInput = $(this).closest("td").next().find(".numeric-input");
      if (nextInput.length) {
        nextInput.focus();
      }
    }
  });
});

</script>


      <?php } elseif ($set == 'upload') { ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="<?= 'dealer/' . $isi ?>">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
            </a>
            <a href="<?= $folder . '/' . $isi ?>/downloadTemplate">
              <button class="btn bg-green btn-flat margin">Template Upload</button>
            </a>
          </h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" id='form_' method="post" enctype="multipart/form-data">
                <div class="box-body">
                 <?php if ($this->session->flashdata('html_errors')) { ?>
                  <div class="alert alert-danger alert-dismissable">
                    <strong>Telah terjadi kesalahan :</strong>
                    <?= $this->session->flashdata('html_errors') ?>
                    <strong>Upload Data Gagal !</strong>
                  </div>
                 <?php } ?>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Choose File</label>
                    <div class="col-sm-10">
                      <input type="file" accept=".csv" required class="form-control" autofocus name="userfile">
                    </div>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12" align='center'>
                    <button type="button" id='submitBtn' name="process" class="btn btn-info btn-flat">Start Upload</button>
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
            error: false,
            error_list: ''
          },
        })
        $('#submitBtn').click(function() {
          $('#form_').validate({
            highlight: function(element, errorClass, validClass) {
              var elem = $(element);
              if (elem.hasClass("select2-hidden-accessible")) {
                $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass);
              } else {
                $(element).parents('.form-input').addClass('has-error');
              }
            },
            unhighlight: function(element, errorClass, validClass) {
              var elem = $(element);
              if (elem.hasClass("select2-hidden-accessible")) {
                $("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass);
              } else {
                $(element).parents('.form-input').removeClass('has-error');
              }
            },
            errorPlacement: function(error, element) {
              var elem = $(element);
              if (elem.hasClass("select2-hidden-accessible")) {
                element = $("#select2-" + elem.attr("id") + "-container").parent();
                error.insertAfter(element);
              } else {
                error.insertAfter(element);
              }
            }
          })
          var values = new FormData($('#form_')[0]);
          if ($('#form_').valid()) // check if form is valid
          {
            if (confirm("Apakah anda yakin ?") == true) {
              $.ajax({
                beforeSend: function() {
                  $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                  $('#submitBtn').attr('disabled', true);
                  form_.error = false;
                  form_.error_list = '';
                },
                enctype: 'multipart/form-data',
                url: '<?= base_url($folder . '/dms_h1_target_management/import_db') ?>',
                type: "POST",
                data: values,
                processData: false,
                contentType: false,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  if (response.status == 'sukses') {
                    window.location = response.link;
                  } else {
                    if (response.tipe == 'html') {
                      window.location = response.link;
                      // form_.error = true;
                      // form_.error_list = response.pesan;
                    } else {
                      alert(response.pesan);
                    }
                    $('#submitBtn').attr('disabled', false);
                  }
                  $('#submitBtn').html('Start Upload');
                },
                error: function() {
                  alert("Something Went Wrong !");
                  $('#submitBtn').html('Start Upload');
                  $('#submitBtn').attr('disabled', false);
                }
              });
            } else {
              return false;
            }
          } else {
            alert('Silahkan isi field required !')
          }
        })
      </script>
    <?php } elseif ($set == 'form') {
      $form = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'edit') {
        $form = 'save_edit';
      }
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <link href='assets/select2/css/select2.min.css' rel='stylesheet' type='text/css'>
      <script src="assets/jquery/jquery.min.js"></script>
      <script src='assets/select2/js/select2.min.js'></script>

      <script>
        Vue.use(VueNumeric.default);
        Vue.filter('toCurrency', function(value) {
          return 'Rp. ' + accounting.formatMoney(value, "", 0, ".", ",");
          // return value;
        });

        Vue.filter('cekType', function(value, arg1) {
          if (arg1 == 'persen') {
            return value + ' %';
          } else {
            return 'Rp. ' + accounting.formatMoney(value, "", 0, ".", ",");
          }
        });

        $(document).ready(function() {})
      </script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <?php $history = isset($_GET['h']) ? '/history' : ''; ?>
            <a href="<?= $folder . '/' . $this->uri->segment(2) . $history; ?>"> <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
            <div class="col-sm-12">
              <form class="form-horizontal" id="form_" method="post" enctype="multipart/form-data">

                <div class="box-body">
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Tahun</label>
                      <div class="col-sm-4">
                        <input type="number" name="tahun" readonly class="form-control" required v-model="row.tahun">
                        <input type="hidden" name="id" :readonly="mode=='detail'" class="form-control" required v-model="row.id">
                      </div>
                    </div>
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Bulan</label>
                      <div class="col-sm-4">
                        <select class='form-control' v-model='row.bulan' name='bulan' :disabled="mode=='detail'">
                          <option value=''>-choose-</option>
                          <?php for ($i = 1; $i <= 12; $i++) {  ?>
                            <option value='<?= $i ?>'><?= $i ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Honda ID</label>
                      <div class="col-sm-4">
                        <input type="text" name="honda_id" readonly v-model="row.honda_id" class="form-control" required>
                      </div>
                      <label class="col-sm-2 control-label">Nama Lengkap</label>
                      <div class="col-sm-3">
                        <input type="text" name="nama_lengkap" readonly v-model="row.nama_lengkap" class="form-control" required>
                      </div>
                      <div class="col-sm-1">
                        <button type='button' v-if="mode!='detail'" class="btn btn-flat btn-primary" onclick="showModaKaryawanDealer()"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                  <div class="form-group" v-if="1==0">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">ID Tipe Kendaraan</label>
                      <div class="col-sm-4">
                        <input type="text" name="id_tipe_kendaraan" readonly v-model="row.id_tipe_kendaraan" class="form-control" required>
                      </div>
                      <label class="col-sm-2 control-label">Deskripsi</label>
                      <div class="col-sm-3">
                        <input type="text" name="tipe_ahm" readonly v-model="row.tipe_ahm" class="form-control" required>
                      </div>
                      <div class="col-sm-1">
                        <button type='button' v-if="mode!='detail'" class="btn btn-flat btn-primary" onclick="showModalTipeKendaraan()"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Target Sales (Unit)</label>
                      <div class="col-sm-4">
                        <input type="number" name="target_sales" :readonly="mode=='detail'" class="form-control" v-on:change="onChange(row)" v-model="row.target_sales" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Target SPK (Unit)</label>
                      <div class="col-sm-4">
                        <input type="number" name="target_spk" :readonly="mode=='detail'" class="form-control" v-model="row.target_spk" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Target Prospek (Unit)</label>
                      <div class="col-sm-4">
                        <input type="number" name="target_prospek" :readonly="mode=='detail'" class="form-control" v-model="row.target_prospek" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Kuota Unit Diskon (Unit)</label>
                      <div class="col-sm-4">
                        <input type="number" name="kuota_unit_diskon" :readonly="mode=='detail'" class="form-control" v-model="row.kuota_unit_diskon" required>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">All Target Unit</label>
                      <div class="col-sm-4">
                        <input type="number" name="target" :readonly="mode=='detail'" class="form-control" v-model="row.target" readonly>
                      </div>
                    </div>
                  </div> -->
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Batas Approval Diskon (Rp)</label>
                      <div class="col-sm-4">
                        <input type="number" name="batas_approval_diskon" :readonly="mode=='detail'" class="form-control" v-model="row.batas_approval_diskon" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="field-1" class="col-sm-2 control-label">Active</label>
                    <div class="col-sm-4">
                      <input v-model='active' type="checkbox" name='active' true-value='1' false-value='0' :disabled="mode=='detail'">
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="form-group">
                    <div class="col-sm-12" align="center" v-if="mode=='insert' || mode=='edit'">
                      <button type="button" id="submitBtn" @click.prevent="save_data" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php
        $data['data'] = ['tipe_kendaraan', 'karyawan_dealer'];
        $this->load->view('dealer/h2_api', $data); ?>
        <script>
          function pilihTipeKendaraan(item) {
            form_.row.id_tipe_kendaraan = item.id_tipe_kendaraan;
            form_.row.tipe_ahm = item.tipe_ahm;
          }

          function pilihKaryawanDealer(params) {
            form_.row.honda_id = params.id_flp_md
            form_.row.nama_lengkap = params.nama_lengkap
          }
          var form_ = new Vue({
            el: '#form_',
            data: {
              mode: '<?= $mode ?>',
              active: '<?= isset($row) ? $row->active : '' ?>',
              row: <?= isset($row) ? json_encode($row) : "{id_tipe_kendaraan:'LN0',tahun:" . date('Y') . ",bulan:'',honda_id:'',target:'',id:''}" ?>,
            },
            methods: {
              save_data: function() {
                $('#form_').validate({
                  rules: {
                    'checkbox': {
                      required: true
                    }
                  },
                  highlight: function(input) {
                    $(input).parents('.form-input').addClass('has-error');
                  },
                  unhighlight: function(input) {
                    $(input).parents('.form-input').removeClass('has-error');
                  }
                })
                if ($('#form_').valid()) // check if form is valid
                {
                  let values = {};
                  var form = $('#form_').serializeArray();
                  for (field of form) {
                    values[field.name] = field.value;
                  }
                  if (confirm("Apakah anda yakin ?") == true) {
                    $.ajax({
                      beforeSend: function() {
                        $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                        $('#submitBtn').attr('disabled', true);
                      },
                      url: '<?= base_url($folder . '/' . $isi . '/' . $form) ?>',
                      type: "POST",
                      data: values,
                      cache: false,
                      dataType: 'JSON',
                      success: function(response) {
                        $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                        if (response.status == 'sukses') {
                          window.location = response.link;
                        } else {
                          alert(response.pesan);
                          $('#submitBtn').attr('disabled', false);
                        }
                      },
                      error: function() {
                        alert("Something Went Wrong !");
                        $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                        $('#submitBtn').attr('disabled', false);
                      },
                    });
                  } else {
                    return false;
                  }
                } else {
                  alert('Silahkan isi field required !')
                }
              },
              showDetailTransaksi: function(dtl) {
                console.log(dtl)
              },
              clearDetail: function() {
                this.dtl = {}
              },
              addDetails: function() {
                this.details.push(this.dtl);
                this.clearDetail();
              },
              delDetails: function(index) {
                this.details.splice(index, 1);
              },
              onChange: function(row) {
                // console.log(row.target_spk);
                this.row.target_spk = Math.ceil(row.target_sales*100/75);   
                this.row.target_prospek = Math.ceil(row.target_spk*4);               
                this.row.target = parseInt(this.row.target_prospek) + parseInt(this.row.target_spk) + parseInt(this.row.target_sales) + parseInt(this.row.kuota_unit_diskon);
              }
            },
            watch: {
                row: {
                  deep: true,
                  handler: function() {
                    form = '<?= $form ?>';
                    if(form =='save_edit'){
                      // 
                    }else{
                      this.row.target_spk = Math.ceil(this.row.target_sales*100/75);
                      this.row.target_prospek = Math.ceil(this.row.target_spk*4);    
                      // this.row.target_spk = Math.round(this.row.target_sales*4*0.8);
                      // this.row.target_prospek = Math.round(this.row.target_sales*4);  
                      this.row.target = parseInt(this.row.target_prospek) + parseInt(this.row.target_spk) + parseInt(this.row.target_sales) + parseInt(this.row.kuota_unit_diskon);
                    }
                  }
                }
              
              // row: {
              //   deep: true,
              //   handler: function() {
              //     // this.row.target_spk = Math.round(this.row.target_prospek*0.8);
              //     // this.row.target_sales = Math.round(this.row.target_spk*0.9);
              //     this.row.target_spk = Math.round(this.row.target_prospek*0.8);
              //     this.row.target_prospek = Math.round(this.row.target_sales*4);                  
              //     this.row.target = parseInt(this.row.target_prospek) + parseInt(this.row.target_spk) + parseInt(this.row.target_sales) + parseInt(this.row.kuota_unit_diskon);
              //   }
              // }
            }
          });
        </script>
      <?php } ?>
  </section>
</div>