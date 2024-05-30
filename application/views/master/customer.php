

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

    <li class="">Vendor</li>

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

          <a href="master/customer">

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

            <form class="form-horizontal" action="master/customer/save" method="post" enctype="multipart/form-data">

              <div class="box-body">                                                                                                    

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">ID Customer</label>

                  <div class="col-sm-4">

                    <input type="number" class="form-control" autofocus required id="inputEmail3" placeholder="ID Customer" name="id_customer">

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">No.KTP</label>

                  <div class="col-sm-4">

                    <input type="number" class="form-control"  required id="inputEmail3" placeholder="No.KTP" name="no_ktp">

                  </div>

                </div>                                                                          

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Lengkap</label>

                  <div class="col-sm-10">

                    <input type="text" class="form-control" required id="inputEmail3" placeholder="Nama Lengkap" name="nama">

                  </div>

                </div>                 

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>

                  <div class="col-sm-4">

                    <select class="form-control" name="id_pekerjaan">

                      <option value="">- choose -</option>

                      <?php 

                      foreach($dt_pekerjaan->result() as $val) {

                        echo "

                        <option value='$val->id_pekerjaan'>$val->pekerjaan</option>;

                        ";

                      }

                      ?>

                    </select>

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Pendidikan</label>

                  <div class="col-sm-4">

                    <select class="form-control" name="id_pendidikan">

                      <option value="">- choose -</option>

                      <?php 

                      foreach($dt_pendidikan->result() as $val) {

                        echo "

                        <option value='$val->id_pendidikan'>$val->pendidikan</option>;

                        ";

                      }

                      ?>

                    </select>

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Hobi</label>

                  <div class="col-sm-4">

                    <select class="form-control" name="id_hobi">

                      <option value="">- choose -</option>

                      <?php 

                      foreach($dt_hobi->result() as $val) {

                        echo "

                        <option value='$val->id_hobi'>$val->hobi</option>;

                        ";

                      }

                      ?>

                    </select>

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Pengeluaran 1 Bulan</label>

                  <div class="col-sm-4">

                    <select class="form-control" name="id_pengeluaran_bulan">

                      <option value="">- choose -</option>

                      <?php 

                      foreach($dt_pengeluaran_bulan->result() as $val) {

                        $nominal = mata_uang($val->pengeluaran);

                        echo "

                        <option value='$val->id_pengeluaran_bulan'>$nominal</option>;

                        ";

                      }

                      ?>

                    </select>

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Sebelumnya</label>

                  <div class="col-sm-4">

                    <select class="form-control" name="id_jenis_sebelumnya">

                      <option value="">- choose -</option>

                      <?php 

                      foreach($dt_jenis_sebelumnya->result() as $val) {

                        echo "

                        <option value='$val->id_jenis_sebelumnya'>$val->jenis_sebelumnya</option>;

                        ";

                      }

                      ?>

                    </select>

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Merk Sebelumnya</label>

                  <div class="col-sm-4">

                    <select class="form-control" name="id_merk_sebelumnya">

                      <option value="">- choose -</option>

                      <?php 

                      foreach($dt_merk_sebelumnya->result() as $val) {

                        echo "

                        <option value='$val->id_merk_sebelumnya'>$val->merk_sebelumnya</option>;

                        ";

                      }

                      ?>

                    </select>

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Digunakan Untuk</label>

                  <div class="col-sm-4">

                    <select class="form-control" name="id_digunakan">

                      <option value="">- choose -</option>

                      <?php 

                      foreach($dt_digunakan->result() as $val) {

                        echo "

                        <option value='$val->id_digunakan'>$val->digunakan</option>;

                        ";

                      }

                      ?>

                    </select>

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Sumber Media</label>

                  <div class="col-sm-4">

                    <select class="form-control" name="id_sumber_media">

                      <option value="">- choose -</option>

                      <?php 

                      foreach($dt_sumber_media->result() as $val) {

                        echo "

                        <option value='$val->id_sumber_media'>$val->sumber_media</option>;

                        ";

                      }

                      ?>

                    </select>

                  </div>

                </div>    

                

                <div class="form-group">

                  <label for="field-1" class="col-sm-2 control-label"></label>            

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

      $row = $dt_customer->row(); 

    ?>



    <div class="box box-default">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="master/customer">

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

            <form class="form-horizontal" action="master/customer/update" method="post" enctype="multipart/form-data">

              <input type="hidden" name="id" value="<?php echo $row->id_customer ?>" />

              <div class="box-body">                                                                                                    

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">ID Customer</label>

                  <div class="col-sm-4">

                    <input type="number" value="<?php echo $row->id_customer ?>" class="form-control" autofocus required id="inputEmail3" placeholder="ID Customer" name="id_customer">

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">No.KTP</label>

                  <div class="col-sm-4">

                    <input type="number" class="form-control" value="<?php echo $row->no_ktp ?>"  required id="inputEmail3" placeholder="No.KTP" name="no_ktp">

                  </div>

                </div>                                                                          

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Lengkap</label>

                  <div class="col-sm-10">

                    <input type="text" class="form-control" value="<?php echo $row->nama ?>" required id="inputEmail3" placeholder="Nama Lengkap" name="nama">

                  </div>

                </div>                 

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>

                  <div class="col-sm-4">

                    <select class="form-control" name="id_pekerjaan">

                      <option value="<?php echo $row->id_pekerjaan ?>">

                        <?php 

                        $dt_cust    = $this->m_admin->getByID("ms_pekerjaan","id_pekerjaan",$row->id_pekerjaan)->row();                                 

                        if(isset($dt_cust)){

                          echo $dt_cust->pekerjaan;

                        }else{

                          echo "- choose -";

                        }

                        ?>

                      </option>

                      <?php 

                      $dt_pekerjaan = $this->m_admin->kondisiCond("ms_pekerjaan","id_pekerjaan != ".$row->id_pekerjaan);                                                

                      foreach($dt_pekerjaan->result() as $val) {

                        echo "

                        <option value='$val->id_pekerjaan'>$val->pekerjaan</option>;

                        ";

                      }

                      ?>

                    </select>

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Pendidikan</label>

                  <div class="col-sm-4">

                    <select class="form-control" name="id_pendidikan">

                      <option value="<?php echo $row->id_pendidikan ?>">

                        <?php 

                        $dt_cust    = $this->m_admin->getByID("ms_pendidikan","id_pendidikan",$row->id_pendidikan)->row();                                 

                        if(isset($dt_cust)){

                          echo $dt_cust->pendidikan;

                        }else{

                          echo "- choose -";

                        }

                        ?>

                      </option>

                      <?php 

                      $dt_pendidikan = $this->m_admin->kondisiCond("ms_pendidikan","id_pendidikan != ".$row->id_pendidikan);                                                

                      foreach($dt_pendidikan->result() as $val) {

                        echo "

                        <option value='$val->id_pendidikan'>$val->pendidikan</option>;

                        ";

                      }

                      ?>

                    </select>

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Hobi</label>

                  <div class="col-sm-4">

                    <select class="form-control" name="id_hobi">

                      <option value="<?php echo $row->id_hobi ?>">

                        <?php 

                        $dt_cust    = $this->m_admin->getByID("ms_hobi","id_hobi",$row->id_hobi)->row();                                 

                        if(isset($dt_cust)){

                          echo $dt_cust->hobi;

                        }else{

                          echo "- choose -";

                        }

                        ?>

                      </option>

                      <?php 

                      $dt_hobi = $this->m_admin->kondisiCond("ms_hobi","id_hobi != ".$row->id_hobi);                                                

                      foreach($dt_hobi->result() as $val) {

                        echo "

                        <option value='$val->id_hobi'>$val->hobi</option>;

                        ";

                      }

                      ?>

                    </select>

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Pengeluaran 1 Bulan</label>

                  <div class="col-sm-4">

                    <select class="form-control" name="id_pengeluaran_bulan">

                      <option value="<?php echo $row->id_pengeluaran_bulan ?>">

                        <?php 

                        $dt_cust    = $this->m_admin->getByID("ms_pengeluaran_bulan","id_pengeluaran_bulan",$row->id_pengeluaran_bulan)->row();                                 

                        if(isset($dt_cust)){

                          echo mata_uang($dt_cust->pengeluaran);

                        }else{

                          echo "- choose -";

                        }

                        ?>

                      </option>

                      <?php 

                      $dt_pengeluaran_bulan = $this->m_admin->kondisiCond("ms_pengeluaran_bulan","id_pengeluaran_bulan != ".$row->id_pengeluaran_bulan);                                                

                      foreach($dt_pengeluaran_bulan->result() as $val) {

                        $nominal = mata_uang($val->pengeluaran);

                        echo "

                        <option value='$val->id_pengeluaran_bulan'>$nominal</option>;

                        ";

                      }

                      ?>

                    </select>

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Sebelumnya</label>

                  <div class="col-sm-4">

                    <select class="form-control" name="id_jenis_sebelumnya">

                      <option value="<?php echo $row->id_jenis_sebelumnya ?>">

                        <?php 

                        $dt_cust    = $this->m_admin->getByID("ms_jenis_sebelumnya","id_jenis_sebelumnya",$row->id_jenis_sebelumnya)->row();                                 

                        if(isset($dt_cust)){

                          echo $dt_cust->jenis_sebelumnya;

                        }else{

                          echo "- choose -";

                        }

                        ?>

                      </option>

                      <?php 

                      $dt_jenis_sebelumnya = $this->m_admin->kondisiCond("ms_jenis_sebelumnya","id_jenis_sebelumnya != ".$row->id_jenis_sebelumnya);                                                

                      foreach($dt_jenis_sebelumnya->result() as $val) {

                        echo "

                        <option value='$val->id_jenis_sebelumnya'>$val->jenis_sebelumnya</option>;

                        ";

                      }

                      ?>

                    </select>

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Merk Sebelumnya</label>

                  <div class="col-sm-4">

                    <select class="form-control" name="id_merk_sebelumnya">

                      <option value="<?php echo $row->id_merk_sebelumnya ?>">

                        <?php 

                        $dt_cust    = $this->m_admin->getByID("ms_merk_sebelumnya","id_merk_sebelumnya",$row->id_merk_sebelumnya)->row();                                 

                        if(isset($dt_cust)){

                          echo $dt_cust->merk_sebelumnya;

                        }else{

                          echo "- choose -";

                        }

                        ?>

                      </option>

                      <?php 

                      $dt_merk_sebelumnya = $this->m_admin->kondisiCond("ms_merk_sebelumnya","id_merk_sebelumnya != ".$row->id_merk_sebelumnya);                                                

                      foreach($dt_merk_sebelumnya->result() as $val) {

                        echo "

                        <option value='$val->id_merk_sebelumnya'>$val->merk_sebelumnya</option>;

                        ";

                      }

                      ?>

                    </select>

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Digunakan Untuk</label>

                  <div class="col-sm-4">

                    <select class="form-control" name="id_digunakan">

                      <option value="<?php echo $row->id_digunakan ?>">

                        <?php 

                        $dt_cust    = $this->m_admin->getByID("ms_digunakan","id_digunakan",$row->id_digunakan)->row();                                 

                        if(isset($dt_cust)){

                          echo $dt_cust->digunakan;

                        }else{

                          echo "- choose -";

                        }

                        ?>

                      </option>

                      <?php 

                      $dt_digunakan = $this->m_admin->kondisiCond("ms_digunakan","id_digunakan != ".$row->id_digunakan);                                                

                      foreach($dt_digunakan->result() as $val) {

                        echo "

                        <option value='$val->id_digunakan'>$val->digunakan</option>;

                        ";

                      }

                      ?>

                    </select>

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Sumber Media</label>

                  <div class="col-sm-4">

                    <select class="form-control" name="id_sumber_media">

                      <option value="<?php echo $row->id_sumber_media ?>">

                        <?php 

                        $dt_cust    = $this->m_admin->getByID("ms_sumber_media","id_sumber_media",$row->id_sumber_media)->row();                                 

                        if(isset($dt_cust)){

                          echo $dt_cust->sumber_media;

                        }else{

                          echo "- choose -";

                        }

                        ?>

                      </option>

                      <?php 

                      $dt_sumber_media = $this->m_admin->kondisiCond("ms_sumber_media","id_sumber_media != ".$row->id_sumber_media);                                                

                      foreach($dt_sumber_media->result() as $val) {

                        echo "

                        <option value='$val->id_sumber_media'>$val->sumber_media</option>;

                        ";

                      }

                      ?>

                    </select>

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

          <a href="master/customer/add">

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

              <th>ID Customer</th>

              <th>Nama</th>

              <th>No.KTP</th>

              <th>Pekerjaan</th>

              <th>Pengeluaran/Bulan</th>

              <th>Pendidikan</th>

              <th>Hobi</th>

              <th>Kendaraan Sebelumnya</th>              

              <th>Active</th>           

              <th width="10%">Action</th>

            </tr>

          </thead>

          <tbody>            

          <?php 

          $no=1; 

          foreach($dt_customer->result() as $row) {       

            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";

                else $active = "";

            $nominal = mata_uang($row->pengeluaran);

          echo "          

            <tr>

              <td>$no</td>

              <td>$row->id_customer</td>              

              <td>$row->nama</td>              

              <td>$row->no_ktp</td>

              <td>$row->pekerjaan</td>              

              <td>$nominal</td>

              <td>$row->pendidikan</td>

              <td>$row->hobi</td>

              <td>$row->merk_sebelumnya</td>

              <td>$active</td>

              <td>";

              ?>

                <a data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/customer/delete?id=<?php echo $row->id_customer ?>"><i class="fa fa-trash-o"></i></a>

                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/customer/edit?id=<?php echo $row->id_customer ?>'><i class='fa fa-edit'></i></a>

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

          url: "<?php echo site_url('master/customer/ajax_bulk_delete')?>",

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