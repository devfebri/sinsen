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

    <li class="">Hari Libur</li>

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

          <a href="master/hari_libur">

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

            <form class="form-horizontal" action="master/hari_libur/save" method="post" enctype="multipart/form-data">

              <div class="box-body">                                                                                                                                    

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Libur</label>

                  <div class="col-sm-2">

                    <input type="text" class="form-control" id="tanggal" placeholder="Tanggal Libur" name="tgl_libur">

                  </div>                  

                </div>    

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan Libur</label>

                  <div class="col-sm-10">

                    <input type="text" class="form-control" required id="inputEmail3" placeholder="Keterangan Libur" name="keterangan">

                  </div>

                </div>                 

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label"></label>                  

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

      $row = $dt_hari_libur->row(); 

    ?>



    <div class="box box-default">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="master/hari_libur">

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

            <form class="form-horizontal" action="master/hari_libur/update" method="post" enctype="multipart/form-data">

              <input type="hidden" name="id" value="<?php echo $row->id_hari_libur ?>" />

              <div class="box-body">                                                                                                    

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Libur</label>

                  <div class="col-sm-2">

                    <input type="text" class="form-control" value="<?php echo $row->tgl_libur ?>" id="tanggal" placeholder="Tanggal Libur" name="tgl_libur">

                  </div>                  

                </div>    

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan Libur</label>

                  <div class="col-sm-10">

                    <input type="text" class="form-control" required value="<?php echo $row->keterangan ?>" id="inputEmail3" placeholder="Keterangan Libur" name="keterangan">

                  </div>

                </div>                                 

                <div class="form-group">                

                  <label for="inputEmail3" class="col-sm-2 control-label"></label>                  

                  <div class="col-sm-3">

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

          <a href="master/hari_libur/add">

            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>

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

        <table id="example2" class="table table-bordered table-hover">

          <thead>

            <tr>

              <th width="5%">No</th>

              <th>Tgl</th>

              <th>Keterangan Libur</th>              

              <th width="5%">Active</th>             

              <th width="10%">Action</th>

            </tr>

          </thead>

          <tbody>            

          <?php 

          $no=1; 

          foreach($dt_hari_libur->result() as $row) {                   

            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";

                else $active = "";

          echo "          

            <tr>

              <td>$no</td>

              <td>$row->tgl_libur</td>              

              <td>$row->keterangan</td>              

              <td>$active</td>              

              <td>";

              ?>

                <a data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/hari_libur/delete?id=<?php echo $row->id_hari_libur ?>"><i class="fa fa-trash-o"></i></a>

                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/hari_libur/edit?id=<?php echo $row->id_hari_libur ?>'><i class='fa fa-edit'></i></a>

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

          url: "<?php echo site_url('master/hari_libur/ajax_bulk_delete')?>",

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