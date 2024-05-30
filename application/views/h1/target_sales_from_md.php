
<body >
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Sales Force</li>
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
           <button class="btn bg-blue btn-flat margin"  onclick="showModalUploadSalesForce()"><i class="fa fa-plus"></i> Tambah</button>
           <a href="/h1/target_sales_from_md/setting_urut_tipe_kendaraan"  class="btn bg-maroon btn-flat margin"  ><i class="fa fa-gear"></i> Setting Urut Tipe Kendaraan</a>
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
              <th width="5%">No</th>                          
              <th width="12%">No Register</th>
              <th>Bulan</th>
              <th>Status</th>                 
              <th>Aksi</th>                 
            </tr>
          </thead>
          <tbody>            
          <?php 

          $no=1; 
          foreach($sales_force->result() as $row){ ?>        
            <tr>
              <td><?=$no++?></td>
              <td>   <a href="/h1/target_sales_from_md/detail_dealer?id=<?=$row->no_register_target_sales?>" ><?= $row->no_register_target_sales?> </a></td>
              <td><?= $row->priode_target?></td>
              <td><?= $row->status?></td>
              <td>
              <a href="/h1/target_sales_from_md/delete?id=<?=$row->no_register_target_sales?>" onclick="return confirm('Are you sure you want to delete this data?');" class="btn btn-danger btn-flat"><i class='fa fa-trash'></i> </a>
              </td>
          </tr>
              <?}?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->


    <div class="modal fade" id="modalUploadSalesForce">
            <div class="modal-dialog" style='width:40%'>
              <div class="modal-content">
                <div class="modal-header bg-red disabled color-palette">
                  <button style='color:white' type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" align='center'>Upload Sales Force Unit</h4>
                </div>
                <div class="modal-body">
                  <form id="form_upload_to_sales_force" class='form-horizontal'>
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Month:</label>
                      <div class="form-input">
                        <div class="col-sm-5">
                        <div class='input-group date'>
                              <input type='text' class="form-control" id='datepicker' name= "month" placeholder="MM"/ required readonly>
                              <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                              </span>
                           </div>
                        </div>
                      </div>
                    </div>

                    <script type="text/javascript">
                      $(function () {
                          $('#datepicker').datepicker({			    
                              format: 'm',
                              minViewMode: 'months',
                              maxViewMode: 'months',
                              startView: 'months'
                          });
                      });
                    </script>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Pilih File (.csv)</label>
                      <div class="form-input">
                        <div class="col-sm-7">
                        <input type="file" accept=".csv" name="file_upload" required />
                        </div>
                      </div>
                    </div>


                  </form>
                </div>
                <div class="modal-footer">
                  <div class="row">
                    <div class="col-sm-12 col-md-12" align='center'>
                        <a href="<?= base_url('./downloads/target_sales_force_md/template_sfc.csv') ?>" class="btn btn-success btn-flat margin">Template Upload</a>
                        <button type="button" class="btn btn-info btn-flat" onclick="buttonUploadToSalesForce()"><i class='fa fa-upload'></i> Upload</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
                      
          <link rel="stylesheet" href="<?php echo base_url() ?>assets/sweetalert2/sweetalert2.min.css">
          <script type="text/javascript" src="<?php echo base_url() ?>assets/sweetalert2/sweetalert2.min.js"></script>
                  
          
          <script>
              function buttonUploadToSalesForce() {
                  var inputValue = $('#datepicker').val();
                  if (inputValue === null || inputValue.trim() === '') {
                      return false; // Prevent form submission
                      Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please enter a valid value Month',
                        });
                      e.preventDefault(); // Prevent form submission
                  }


                  var fileInput = $('input[name="file_upload"]');
                    if (fileInput.get(0).files.length === 0) {
                      Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please select a CSV file',
                      });
                      e.preventDefault(); // Prevent form submission
                      }
                      
                      var values = new FormData($('#form_upload_to_sales_force')[0]);
                      values.append('inputValue', inputValue);
                      
                      $.ajax({
                        enctype: 'multipart/form-data',
                        url: "<?php   echo site_url('h1/target_sales_from_md/upload_sales_force')?>",
                        type: "POST",
                        data: values,
                        processData: false,
                        contentType: false,
                        cache: false,
                        dataType: 'JSON',
                        success: function(response) {
                          const errorTableHTML = generateErrorTableWithLists(response.data);

                          Swal.fire({
                              title: "Upload Status",
                              text: response.pesan, 
                              icon: (response.status === 1) ? "error" : "success",
                              html: errorTableHTML,
                              confirmButtonText: "OK"
                          }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload(); // Reload the page when OK is clicked
                            }});
                          ;

                          function generateErrorTableWithLists(errors) {
                            if (errors && typeof errors === 'object') {
                            var containerHtml = "<div><table border ='1' class='table table-bordered'><thead><tr class='text-center'><th>Error Messages</th></tr></thead><tbody>";
                            for (var key in errors) {
                              containerHtml += "<tr><td><ul>";
                              errors[key].forEach(function (error) {
                                containerHtml += "<li>" + error + "</li>";
                              });
                              containerHtml += "</ul></td></tr>";
                            }
                            containerHtml += "</tbody></table></div>";
                            return containerHtml;
                          } 

                          }
                          },
                           error: function() {
                          $('#modalUploadSalesForce').modal('hide');  
                           }
                        });
             
                }
              </script>
 <?php 

} else if($set=="detail_dealer"){      

?>

<style>
.table-container {
  width: 100%;
  overflow-x: auto;
}

table {
  width: auto; /* Allow the table to expand horizontally */
  border-collapse: collapse;
  white-space: nowrap; /* Prevent wrapping of table cells */
}

th, td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
  white-space: nowrap;
}
.sticky-col {
  position: -webkit-sticky;
  position: sticky;
  background-color: white;
}

.footer-first-col {
  width: 80px;
  min-width: 10px;
  max-width: 10px;
  left: 0px;
}

.end-col {
  left: 90%;
  width: 100px;
  background-color: white;
  position: absolute;
  /* position: static;   */
  /* bottom: 8px; */
  /* right: 16px; */
}


.first-col {
  background-color: white;
}

.second-col {
  width: 100%;
  left: 100px;
}

.jumlah-tipe-kendaraan{
  width: 70px;
}

.scroll-data{
  position: static;
}

.table-container thead {
  position: sticky;
  top: 0;
  background-color: #fff; /* Adjust background color as needed */
  z-index: 2; /* Ensure the sticky header is above other elements */
}

.sticky-footer {
    position: sticky;
    bottom: 0;
    z-index: 2;
    background-color: #fff; /* Adjust background color as needed */
  }

</style>
<div class="box">
<div class="box-header with-border">
  <h3 class="box-title">
    <a href="/h1/target_sales_from_md/">
     <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
     </a>
     <a href="/h1/target_sales_from_md/download_target_tipe_kendaraan?id=<?=$this->input->get('id', TRUE); ?>">
     <button class="btn bg-blue btn-flat margin"><i class="fa fa-download"></i> Download Excel</button>
     </a>
  </h3>
  <div class="box-tools pull-right">
    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
  </div>
</div>
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
              <div class="box-body">              
                <input type="hidden" id="tgl" value="<?php echo date("d-M-y") ?>">
                <form action="h1/target_sales_from_md/updated_target_tipe" method="post">
                  
                
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">No Register</label>
                  <div class="col-sm-4">
                  <input type="text" name="no_register" class="form-control" value="<?=$sales_force_target->no_register_target_sales?>" readonly>                     
                  <input type="hidden" id="status_target" class="form-control status_target" value="<?=$sales_force_target->status?>" readonly>                     
                  </div>
                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control"  value="<?=$sales_force_target->priode_target?>"  readonly>                                        
                    </div>
                  </div>
                </div>
                <hr>

              <div class="table-container" style="height:500px;width:100%; overflow:auto;">

              <table  id="exampl" class="table table-bordered table-container">
              <thead>
                <tr>       
                  <div class="row">
                      <th  class="sticky-col first-col">No</th>                          
                      <th  class="sticky-col first-col">Kode Dealer</th>                          
                      <th  class="sticky-col second-col">Dealer</th>  
                    <?php 
                          foreach ($flp_sales_tipe_kendaraan as $row) 
                          { ?>
                          <th scope="scroll-data">
                          <?=$row->id_tipe_kendaraan?></th>
                      <?}
                      ?>   

                    <th  class="sticky-col first-col">Total Jumlah</th>        
                   <div>
                  </div>
                </tr>
              </thead>
              <tbody>            
       
              <?php   

                      $temp = array();
                      $array_total = array();
                       $no = 1;
                       foreach ($sales_force_detail as $set => $item) : ?>
                      <tr>
                        <td > <?=$no++?></td>
                          <td  class="sticky-col first-col">
                          <?= $item['id_dealer'];?>
                        </td>

                        <td  class="sticky-col second-col">
                          <?= $item['nama_dealer'];?>
                        </td>
                        
                          <?php

                          $total = 0;
                          foreach ($item['sales_data'] as $key => $sales) 
                          {  
                          ?>
                          <td style="width:140px"> 
                            <input type="hidden" name="sales[tipe_kendaraan][]"  value="<?= $sales['tipe_kendaraan']?>" placeholder="Tipe Kendaraan" />
                            <input type="hidden" name="sales[kode_dealer][]"     value="<?= $item['id_dealer']?>"       placeholder="Kode Dealer" />
                            <input type="text"   name="sales[jumlah][]"          value="<?= $sales['jumlah']?>"         placeholder="Jumlah" class="form-control jumlah-tipe-kendaraan set_sum<?=$key?>"  readonly />
                          <?
                         $total += floatval($sales['jumlah']);
                          }
                          ?>
                          <td align="center">
                              <? $array_total[]= $total ?>
                             <b> <?= $total;?></b>
                        </td>
                      </tr>
                  <?php endforeach;?>
                <tfoot  class="sticky-footer">
                  <tr>
                    <td colspan="3"  class="sticky-col footer-first-col">Total</td>
                    <?php 

                  foreach ($sales_force_detail_footer as $key => $sales) 
                  { ?>
                      <td><b><?=array_sum($sales);?></b></td>
                    <?}?>
                    <td><b><?= array_sum($array_total);?></b></td>
                  </tr>
                  </tfoot>

              </tbody>
              </table>
              </div>
              <div class="form-group  text-center">      
                <button  type="submit" name="submit_button" value="approve" class="btn btn-primary btn-flat margin approve-button"><i class="fa fa-save" aria-hidden="true"></i> Approve </button>
                <button  type="submit" name="submit_button" value="save" class="btn bg-maroon btn-flat margin save-button"  onclick="confirm('Save this Data')"><i class="fa fa-save" aria-hidden="true"></i> Save </button>
                <button class="btn btn-default btn-flat margin edit-button"><i class="fa fa-edit" aria-hidden="true"></i> Edit</button>
                   <?php if($sales_force_target->status == 'draft') {?>
                    <?}elseif ($sales_force_target->status == 'approve'){?> 
                    <?}else{?> 
                    <?}?>
                  </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
</div><!-- /.box-body -->
</div><!-- /.box -->

<script>
  $(document).ready(function() {


    totalUnitMD = 0;
            $('.sum-total-each').each(function() {
                var index = $(this).closest('td').index(); 
                var totalUnit = parseFloat($('input[name="total_unit[]"]').eq(index).val().replace(/\./g, '').replace(',', '.')); 
                if (!isNaN(totalUnit)) {
                  totalUnitMD += totalUnit;
                }
            });
            $('.total-unit-set').val( totalUnitMD);


    var statusValue = $('#status_target').val().toLowerCase();
      if (statusValue !== 'draft') {
        $('.approve-button').hide();
        $('.save-button').hide();
        $('.edit-button').hide();
      }
        function calculateTotalSum() {
            let totalSum = 0;
            $('.jumlah-tipe-kendaraan').each(function() {
                totalSum += parseFloat($(this).val()) || 0;
            });
            $('#totalSum').val(totalSum);
        }
        $('.jumlah-tipe-kendaraan').on('input', function() {
            calculateTotalSum();
        });
        calculateTotalSum();
    $('#detailButton').click(function(event) {
        var dealerId = $(this).data('dealer-id');
        var postData = {
            dealerId: dealerId
        };
        $.ajax({
            type: 'POST',  
            url: "<?php echo site_url('dealer/target_sales_from_md/fetch_detail'); ?>",
            data: postData,
            success: function(response) {
                $('#result').html(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });


    });

    $('.edit-button').click(function(){
      event.preventDefault();
      var table = $(this).closest('table');
      var rowsToEdit = $('tr:not(:first)', table);
      $('.jumlah-tipe-kendaraan').on('input', function() {
          this.value = this.value.replace(/[^0-9]/g, ''); // Remove any non-numeric characters
          if (this.value.length > 3) {
            this.value = this.value.slice(0, 3);
        }
      });
      $('.jumlah-tipe-kendaraan').removeAttr('readonly');

    });


});

</script>
  <?} else if($set=="setting_urut_tipe_kendaraan"){ ?>

<div class="box">
<div class="box-header with-border">
  <h3 class="box-title">
    <a href="/h1/target_sales_from_md/">
     <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
     </a>
  </h3>

  <div class="box-tools pull-right">
    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
  </div>

</div>

<div class="box-body">
<style>
    ul {
      list-style-type: none;
      padding: 0;
      margin: 0;
    }

    .li-table {
      display: flex;
      justify-content: space-between;
      align-items: flex-start; /* Add this line to align items to the left */
      padding: 8px 16px;
      border-bottom: 1px solid #ddd;
    }

    .span-bold{
      font-weight: bold;
      align-items: center;
    }
    
    .span-bold-no{
      align-items: left;
      /* width: 1px; */
    }

    .span-body{
      align-items: left;
      width: 200px;
    }

    .span-body-no{
      align-items: left;
      width: 1px;
    }

    .hidden-input {
      border: none;
        background: none;
    }

    .disable-checkbox{
      display:block;
    }

  </style>

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

<form action="h1/target_sales_from_md/update_urut_target_tipe_kendaraan" method="post">      
     <button  type="submit" class="btn bg-primary btn-flat margin" onclick="return confirm('Apakah Anda yakin ingin mengedit item ini?');" ><i class="fa fa-gear"></i> Setting</button>
        <div class="row">
          <div class="col-md-12">
              <div class="box-body">    
                  <li class="li-table">
                    <span class="span-bold">No</span>
                    <span class="span-bold">ID Tipe Kendaraan</span>
                    <span class="span-bold">Tipe AHM</span>
                    <span class="span-bold">Active</span>
                  </li>

                  <ul id="image-list1" class="sortable-list">
                  <?php 
                  $no=1;
                  foreach ($tipe_kendaraan as $set => $item) : ?>
                    <li class="li-table" >
                    <span class="span-body" > <input class="hidden-input urut-static" type="text"  value="<?= $no++ ?>"> </span>
                    <span class="span-body"> <input class="hidden-input" type="text" name="tipe_kendaraan[]"  id="tipe_kendaraan" value="<?= $item->id_tipe_kendaraan; ?>"></span>
                    <span class="span-body"> <input class="hidden-input"  type="text" name="tipe_ahm[]"       id="tipe_ahm" value="<?= $item->tipe_ahm; ?>"></span>
                    <span class="span-body disable-checkbox"> <input class="hidden-input"  type="checkbox" id="active" value="<?= $item->active; ?>" <?= $item->active == 1 ? 'checked' : ''; ?>></span>
                  </li>
                  <?php endforeach; ?>
                </ul>
      
                </form>    
          </div>
        </div>
      </div>
    </div><!-- /.box -->
</div><!-- /.box-body -->
</div><!-- /.box -->

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script>
  $(document).ready(function() {
  var header = $(".li-table:first-child");

  $("#image-list1").sortable({
    items: 'li:not(:first-child)',
    axis: 'y', // Enable vertical sorting only
    update: function(event, ui) {
      updateNumbers();
    }
  });

  function updateNumbers() {
    $(".li-table:gt(0)").each(function(index) {
      var number = index + 1;
      $(this).find('.urut-static input').val(number);
    });
  }

  $("#image-list1").on("scroll", function() {
    var scrollTop = $(this).scrollTop();
    if (scrollTop > 0) {
      header.addClass("urut-static");
    } else {
      header.removeClass("urut-static");
    }
  });
});

</script>
<script>
  $(document).ready(function() {
    $("#image-list1").on("scroll", function() {
      var scrollTop = $(this).scrollTop();
      $(".urut-static").css("margin-top", scrollTop);
    });
  });
</script>
<script>
    $('.sortable-list').sortable({
  connectWith: '.sortable-list',
  update: function(event, ui) {
    var changedList = this.id;
    var order = $(this).sortable('toArray');
    var positions = order.join(';');

    console.log({
      id: changedList,
      positions: positions
    });
  }
});
  </script>
  <?}?>
  </section>
</div>


<script>
    function showModalUploadSalesForce() {
    $('#modalUploadSalesForce').modal('show');
     }
     
</script>

