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

    <li class="">User</li>

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

          <a href="master/user_access_level">

            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>

          </a>

        </h3>

        <div class="box-tools pull-center">

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

            <form class="form-horizontal" action="master/user_access_level/save" method="post" enctype="multipart/form-data">

              <div class="box-body">                                                                                                                      

                <div class="form-group">                  

                  <label for="field-1" class="col-sm-2 control-label">User Group</label>                  

                  <div class="col-sm-6">

                    <select class="form-control" required name="id_user_group" id="id_user_group">

                      <option value="">- choose -</option>

                      <?php 

                      foreach ($dt_user_group->result() as $val) {

                        echo "

                        <option value='$val->id_user_group'>$val->user_group</option>;

                        ";

                      }

                      ?>

                    </select>

                  </div>                  

                  <div class="col-sm-2">

                    <button onclick="cek_group()" type="button" class="btn btn-flat btn-primary">Generate</button>

                  </div>

                </div>



                <hr>                

                <div class="form-group">

                  <span id="tampil_group"></span>

                </div>

              </div>                

              <div class="box-footer">

                <div class="col-sm-2">

                </div>

                <div class="col-sm-10">

                  <button type="submit" name="save" value="save" onclick="return confirm('Are you sure to save all these data?')" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>

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

          <!-- <a href="master/user_access_level/add">

            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>

          </a>           -->

          <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  

        </h3>

        <div class="box-tools pull-center">

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

              <th>User Group</th>                            

              <th>Menu</th>                            

              <th>View</th>

              <th>Add</th>                            

              <th>Edit</th>

              <th>Delete</th>

              <th>Print</th>

              <th>Download</th>

              <th>Approval</th>

            </tr>

          </thead>

          <tbody>            

          <?php 

          $no=1; 

          foreach($dt_user_access_level->result() as $row) {

            if($row->can_select=='1') $can_select = "<i class='glyphicon glyphicon-ok'></i>";

              else $can_select = "";

            if($row->can_insert=='1') $can_insert = "<i class='glyphicon glyphicon-ok'></i>";

              else $can_insert = "";

            if($row->can_update=='1') $can_update = "<i class='glyphicon glyphicon-ok'></i>";

              else $can_update = "";

            if($row->can_delete=='1') $can_delete = "<i class='glyphicon glyphicon-ok'></i>";

              else $can_delete = "";

            if($row->can_print=='1') $can_print = "<i class='glyphicon glyphicon-ok'></i>";

              else $can_print = "";

            if($row->can_download=='1') $can_download = "<i class='glyphicon glyphicon-ok'></i>";

              else $can_download = "";

            if($row->can_approval=='1') $can_approval = "<i class='glyphicon glyphicon-ok'></i>";

              else $can_approval = "";

            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";

              else $active = "";                  	 

          echo "          

            <tr>

              <td>$no</td>

              <td>$row->user_group</td>                            

              <td>$row->menu_name</td>

              <td>$can_select</td>

              <td>$can_insert</td>

              <td>$can_update</td>

              <td>$can_delete</td>

              <td>$can_print</td>

              <td>$can_download</td>

              <td>$can_approval</td>              

            </tr>";          

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

function cek_group(){    

  $("#tampil_group").show();

  var id_user_group = document.getElementById("id_user_group").value;   

  var xhr;

  if (window.XMLHttpRequest) { // Mozilla, Safari, ...

    xhr = new XMLHttpRequest();

  }else if (window.ActiveXObject) { // IE 8 and older

    xhr = new ActiveXObject("Microsoft.XMLHTTP");

  } 

   //var data = "birthday1="+birthday1_js;          

    var data = "id_user_group="+id_user_group;                           

     xhr.open("POST", "master/user_access_level/t_group", true); 

     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  

     xhr.send(data);

     xhr.onreadystatechange = display_data;

     function display_data() {

        if (xhr.readyState == 4) {

            if (xhr.status == 200) {       

                document.getElementById("tampil_group").innerHTML = xhr.responseText;

            }else{

                alert('There was a problem with the request.');

            }

        }

    } 

}

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

          url: "<?php echo site_url('master/user_access_level/ajax_bulk_delete')?>",

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