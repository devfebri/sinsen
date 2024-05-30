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

    if($set=="insert"){

    ?>



    <div class="box box-default">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="master/ppn">

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

            <form class="form-horizontal" action="master/ppn/save" method="post" enctype="multipart/form-data">

              <div class="box-body">           

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Mulai *</label>

                  <div class="col-sm-4">

                    <input type="text" id="tanggal" required class="form-control" name="start_date" placeholder="Tgl Mulai" autocomplete="off">

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Selesai *</label>

		              <div class="col-sm-4">

                    <input type="text" id="tanggal2" required class="form-control" name="end_date" placeholder="Tgl Selesai" autocomplete="off">

                  </div>

                </div>

		            <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Persentase PPN *</label>

                  <div class="col-sm-4">

		                <input id="persen_ppn" required name="persen_ppn" placeholder="Cth: 11.75">

                  </div>

                </div>  

                                        

              </div><!-- /.box-body -->

              <div class="box-footer">

                <div class="col-sm-2">

                </div>

                <div class="col-sm-10">

                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>

                </div>

              </div><!-- /.box-footer -->

            </form>

          </div>

        </div>

      </div>

    </div><!-- /.box -->



    <?php 

    }elseif($set=="edit"){

      $row = $get_data; 

    ?>



    <div class="box box-default">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="master/ppn">

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

            <form class="form-horizontal" action="master/ppn/update" method="post" enctype="multipart/form-data">

              <div class="box-body">    

		            <input type="hidden" name="id" value="<?php echo $row->id_ppn ?>" />       

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Mulai *</label>

                  <div class="col-sm-4">

                    <input type="text" id="tanggal" required class="form-control" name="start_date" value="<?php echo $row->start_date?>" placeholder="Tgl Mulai" autocomplete="off">

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Selesai *</label>

                  <div class="col-sm-4">

                    <input type="text" id="tanggal2" required class="form-control" name="end_date" value="<?php echo $row->end_date?>" placeholder="Tgl Selesai" autocomplete="off">

                  </div>

                </div>



                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Persentase PPN *</label>

                  <div class="col-sm-4">

                    <input id="persen_ppn" required name="persen_ppn" value="<?php echo $row->persen_ppn?>" placeholder="Cth: 11.75">

                  </div>

                </div>                          

              </div><!-- /.box-body -->

              <div class="box-footer">

                <div class="col-sm-2">

                </div>

                <div class="col-sm-10">

                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>

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
	<?php /*
          <a href="master/ppn/add">

            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>

          </a>   
*/ ?>       

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

        <table id="table" class="table table-bordered table-hover">

          <thead>

            <tr>

              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              

              <th width="5%">No</th>

              <th>Tgl Mulai</th>          

              <th>Tgl Selesai</th>           

              <th>Persentase PPN</th>
            </tr>

          </thead>

          <tbody>                      

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



var table;



$(document).ready(function() {

    //datatables

    table = $('#table').DataTable({



        "processing": true, //Feature control the processing indicator.

        "serverSide": true, //Feature control DataTables' server-side processing mode.

        "order": [], //Initial no order.



        // Load data for the table's content from an Ajax source

        "ajax": {

            "url": "<?php echo site_url('master/ppn/ajax_list')?>",

            "type": "POST"

        },



        //Set column definition initialisation properties.

        "columnDefs": [

        {

            "targets": [ 0,2 ], //first column / numbering column

            "orderable": false, //set not orderable

        },

        ],

    });

});



</script>
