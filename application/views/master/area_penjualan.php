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

    <li class="">Dealer</li>

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

          <a href="master/area_penjualan">

            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>

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

            <form class="form-horizontal" action="master/area_penjualan/save" method="post" enctype="multipart/form-data">

              <div class="box-body">

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Code</label>

                  <div class="col-sm-4">

                    <input type="text" required class="form-control" autofocus id="inputEmail3" placeholder="Code" name="code">

                  </div>

                </div>                                                                                      

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Area Penjualan</label>

                  <div class="col-sm-10">

                    <input type="text" class="form-control" required id="inputEmail3" placeholder="Area Penjualan" name="area_penjualan">

                  </div>

                </div>                 

                <div class="form-group">

                  <label for="field-1" class="col-sm-2 control-label">Status</label>            

                  <div class="col-sm-2">

                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">

                      <input type="checkbox" class="flat-red" name="active" value="1" checked>

                      Active

                    </div>

                  </div>                  

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

      $row = $dt_area_penjualan->row(); 

    ?>



    <div class="box box-default">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="master/area_penjualan">

            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>

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

            <form class="form-horizontal" action="master/area_penjualan/update" method="post" enctype="multipart/form-data">

              <input type="hidden" name="id" value="<?php echo $row->id_area_penjualan ?>" />

              <div class="box-body">

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Code</label>

                  <div class="col-sm-4">

                    <input type="text" required autofocus class="form-control" id="inputEmail3" value="<?php echo $row->code; ?>" placeholder="Code" name="code">

                  </div>

                </div>                                                                          

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Area Penjualan</label>

                  <div class="col-sm-10">

                    <input type="text" required class="form-control" id="inputEmail3" value="<?php echo $row->area_penjualan; ?>" placeholder="Area Penjualan" name="area_penjualan">

                  </div>

                </div>               

                <div class="form-group">

                  <label for="field-1" class="col-sm-2 control-label">Status</label>            

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

          <a href="master/area_penjualan/add">

            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>

          </a>          

          <button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button>                  

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

              <th width="1%"><input type="checkbox" id="check-all"></th>              

              <th width="5%">No</th>

              <th width="10%">Code</th>

              <th>Area Penjualan</th>              

              <th width="10%">Active</th>

              <th width="10%">Action</th>

            </tr>

          </thead>

          <tbody>            

          <?php 

          $no=1; 

          foreach($dt_area_penjualan->result() as $row) {

          if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";

                else $active = "";       

          echo "          

            <tr>

              <td><input type='checkbox' class='data-check' value='$row->id_area_penjualan'></td>

              <td>$no</td>

              <td>$row->code</td>              

              <td>$row->area_penjualan</td>              

              <td>$active</td>              

              <td>";

              ?>

                <a data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/area_penjualan/delete?id=<?php echo $row->id_area_penjualan ?>"><i class="fa fa-trash-o"></i></a>

                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/area_penjualan/edit?id=<?php echo $row->id_area_penjualan ?>'><i class='fa fa-edit'></i></a>

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



<script type="text/javascript">

function bulk_delete(){

  var list_id = [];

  $(".data-check:checked").each(function() {

    list_id.push(this.value);

  });

  if(list_id.length > 0){

    if(confirm('Are you sure delete this '+list_id.length+' data?'))

      {

        $.ajax({

          type: "POST",

          data: {id:list_id},

          url: "<?php echo site_url('master/area_penjualan/ajax_bulk_delete')?>",

          dataType: "JSON",

          success: function(data)

          {

            if(data.status){

              window.location.reload();

            }else{

              alert('Failed.');

            }                  

          },

          error: function (jqXHR, textStatus, errorThrown){

            alert('Error deleting data');

          }

        });

      }

    }else{

      alert('no data selected');

  }

}

</script>