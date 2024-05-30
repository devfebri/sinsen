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
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Master Data</li>
    <li class="">Customer</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="insert"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/niguri">
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
            <form class="form-horizontal" action="h1/niguri/save" method="post" enctype="multipart/form-data">
              <div class="box-body">
                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Niguri</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" autofocus id="inputEmail3" placeholder="ID Niguri" name="id_niguri">
                  </div>
                </div>                                                                                      
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="bulan">
                      <option><?php echo bln() ?></option>
                      <option>Januari</option>
                      <option>Februari</option>
                      <option>Maret</option>
                      <option>April</option>
                      <option>Mei</option>
                      <option>Juni</option>
                      <option>Juli</option>
                      <option>Agustus</option>
                      <option>September</option>
                      <option>Oktober</option>
                      <option>November</option>
                      <option>Desember</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="tahun">
                      <option><?php echo date("Y") ?></option>
                      <?php 
                      $y = date("Y");
                      for ($i=$y - 10; $i <= $y + 10; $i++) { 
                        echo "<option>$i</option>";
                      }
                      ?>                          
                    </select>
                  </div>
                </div>                                            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Keterangan" name="ket">
                  </div>
                </div>                 

                <div class="form-group">                                  
                  <table id="myTable" class="table order-list">
                    <thead>
                      <tr>
                        <td>ID Item</td>
                        <td>Tipe Kendaraan</td>
                        <td>Warna Kendaraan</td>
                        <td>Outlock M</td>
                        <td>Outlock M+1</td>
                        <td>Outlock M+2</td>
                        <td>Stock Days M+2</td>
                        <td>Retail Bulan Lalu</td>
                        <td></td>
                      </tr>
                    </thead>        
                    <tbody>        
                      <tr>
                        <td>
                          <input type="text" class="form-control">
                        </td>
                        <td>
                          <input type="text" class="form-control" name="tipe" disabled>
                        </td>
                        <td>
                          <input type="text" class="form-control" name="warna" disabled>
                        </td>
                        <td>
                          <input type="text" class="form-control" name="outlock_m">
                        </td>
                        <td>
                          <input type="text" class="form-control" name="outlock_m1">
                        </td>
                        <td>
                          <input type="text" class="form-control" name="outlock_m2">
                        </td>
                        <td>
                          <input type="text" class="form-control" name="stock_days_m2">
                        </td>
                        <td>
                          <input type="text" class="form-control" name="retail" disabled>
                        </td>
                        <td>
                          <button type="button" class="btn btn-sm btn-flat btn-primary" id="addrow" value="" /><i class="fa fa-plus"> Add</i></button>                          
                        </td>
                      </tr>
                    </tbody>                        
                  </table>                  
                </div>

              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
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
      $row = $dt_niguri->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/niguri">
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
            <form class="form-horizontal" action="h1/niguri/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_niguri ?>" />
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Niguri</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->id_niguri ?>" required class="form-control" autofocus id="inputEmail3" placeholder="ID Niguri" name="id_niguri">
                  </div>
                </div>                                                                                        
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Niguri</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" id="inputEmail3" value="<?php echo $row->niguri; ?>" placeholder="Niguri" name="niguri">
                  </div>
                </div>          
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label"></label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->active=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="active" value="1">
                      <?php } ?>
                      Active
                    </div>
                  </div>                  
                </div>                                                  
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Update</button>                
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
          <a href="h1/niguri/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>          
          <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  
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
              <th>ID Niguri</th>              
              <th>Bulan</th>              
              <th>Tahun</th>
              <th>Keterangan</th>
              <th width="5%">Active</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_niguri->result() as $row) {       
            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->id_niguri</td>
              <td>$row->bulan</td>
              <td>$row->tahun</td>
              <td>$row->ket</td>
              <td>$active</td>                                          
              <td>";
              ?>
                <a data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="h1/niguri/delete?id=<?php echo $row->id_niguri ?>"><i class="fa fa-trash-o"></i></a>
                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='h1/niguri/edit?id=<?php echo $row->id_niguri ?>'><i class='fa fa-edit'></i></a>
              </td>
            </tr>
          <?php
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

<script src="assets/panel/jQuery.min.js"></script>
<script src="assets/panel/bootstrap2.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    var counter = 0;

    $("#addrow").on("click", function () {
        var newRow = $("<tr>");
        var cols = "";

        cols += '<td><input type="text" class="form-control" name="name[]"/></td>';
        cols += '<td><input type="text" class="form-control" name="mail[]"/></td>';
        cols += '<td><input type="text" class="form-control" name="phone[]"/></td>';


        cols += '<td><input type="button" class="ibtnDel btn btn-sm btn-flat btn-danger" value="Del"></td>';
        newRow.append(cols);
        $("table.order-list").append(newRow);
        counter++;
    });



    $("table.order-list").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
    });


});



function calculateRow(row) {
    var price = +row.find('input[name^="price"]').val();

}

function calculateGrandTotal() {
    var grandTotal = 0;
    $("table.order-list").find('input[name^="price"]').each(function () {
        grandTotal += +$(this).val();
    });
    $("#grandtotal").text(grandTotal.toFixed(2));
}
</script>