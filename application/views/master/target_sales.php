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

          <a href="master/target_sales">

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

            <form class="form-horizontal" action="master/target_sales/save" method="post" enctype="multipart/form-data">

              <div class="box-body">                

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">ID Target Sales</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" autofocus required id="id_target_sales" placeholder="ID Target Sales" name="id_target_sales">

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">ID Dealer</label>

                  <div class="col-sm-4">

                    <select class="form-control select2" name="id_dealer">

                      <option value="">- choose -</option>

                      <?php 

                      foreach($dt_dealer->result() as $val) {

                        echo "

                        <option value='$val->id_dealer'>$val->nama_dealer</option>;

                        ";

                      }

                      ?>

                    </select>

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>

                  <div class="col-sm-4">

                    <select name="tahun" class="form-control">

                      <option value="">- choose -</option>

                      <?php 

                      $year = date("Y") + 10;

                      for ($i=2005; $i <= $year ; $i++) { 

                        echo "

                        <option>$i</option>

                        ";

                      }

                      ?>

                    </select>

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

                <hr>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">ID Tipe Kendaraan</label>

                  <div class="col-sm-4">

                    <select class="form-control select2" id="id_tipe_kendaraan">

                      <option value="">- choose -</option>

                      <?php 

                      foreach($dt_tipe->result() as $val) {

                        echo "

                        <option value='$val->id_tipe_kendaraan'>$val->tipe_ahm</option>;

                        ";

                      }

                      ?>

                    </select>

                  </div>                  

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Januari</label>

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control"  placeholder="Januari" id="jan">

                  </div>

                  <label for="field-1" class="col-sm-2 control-label">Februari</label>            

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="feb" placeholder="Februari">                                        

                  </div>

                  <label for="field-1" class="col-sm-2 control-label">Maret</label>            

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="mar" placeholder="Maret">                                        

                  </div>                                   

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">April</label>

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control"  placeholder="April" id="apr">

                  </div>

                  <label for="field-1" class="col-sm-2 control-label">Mei</label>            

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="mei" placeholder="Mei">                                        

                  </div>

                  <label for="field-1" class="col-sm-2 control-label">Juni</label>            

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="jun" placeholder="Juni">                                        

                  </div>                                   

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Juli</label>

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control"  placeholder="Juli" id="jul">

                  </div>

                  <label for="field-1" class="col-sm-2 control-label">Agustus</label>            

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="agus" placeholder="Agustus">                                        

                  </div>

                  <label for="field-1" class="col-sm-2 control-label">September</label>            

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="sept" placeholder="September">                                        

                  </div>                                   

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Oktober</label>

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control"  placeholder="Oktober" id="okt">

                  </div>

                  <label for="field-1" class="col-sm-2 control-label">November</label>            

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="nov" placeholder="November">                                        

                  </div>

                  <label for="field-1" class="col-sm-2 control-label">Desember</label>            

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="des" placeholder="Desember">                                        

                  </div>                                   

                </div>                

                <div class="form-group">                          

                  <div class="col-sm-2">

                  </div>  

                  <div class="col-sm-8">

                   <button type="button" onClick="simpan_target()" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>

                   <button type="button" onClick="kirim_data_target()" class="btn btn-warning btn-flat"><i class="fa fa-eye"></i> Show</button>

                   <button type="button" onClick="hide_target()" class="btn btn-warning btn-flat"><i class="fa fa-eye-slash"></i> Hide</button>

                  </div> 

                </div>

                <div class="form-group">                  

                  <div class="col-sm-10">

                    <div id="tampil_target"></div>

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

      $row = $dt_target_sales->row(); 

    ?>



    <div class="box box-default">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="master/target_sales">

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

            <form class="form-horizontal" action="master/target_sales/update" method="post" enctype="multipart/form-data">

              <input type="hidden" name="id" value="<?php echo $row->id_target_sales ?>" />

              <div class="box-body">                              

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">ID Target Sales</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" disabled value="<?php echo $row->id_target_sales ?>" autofocus required id="id_target_sales" placeholder="ID Target Sales" name="id_target_sales">

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">ID Dealer</label>

                  <div class="col-sm-4">

                    <select class="form-control select2" name="id_dealer">

                      <option value="<?php echo $row->id_dealer ?>">

                        <?php 

                        $dt_cust    = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();                                 

                        if(isset($dt_cust)){

                          echo $dt_cust->nama_dealer;

                        }else{

                          echo "- choose -";

                        }

                        ?>

                      </option>

                      <?php 

                      $dt_dealer = $this->m_admin->kondisiCond("ms_dealer","id_dealer != ".$row->id_dealer);                                                

                      foreach($dt_dealer->result() as $val) {

                        echo "

                        <option value='$val->id_dealer'>$val->nama_dealer</option>;

                        ";

                      }

                      ?>

                    </select>

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>

                  <div class="col-sm-4">

                    <select name="tahun" class="form-control">

                      <option value="<?php echo $row->tahun ?>"><?php echo $row->tahun ?></option>

                      <?php 

                      $year = date("Y") + 10;

                      for ($i=2005; $i <= $year ; $i++) { 

                        echo "

                        <option>$i</option>

                        ";

                      }

                      ?>

                    </select>

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

                <hr>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">ID Tipe Kendaraan</label>

                  <div class="col-sm-4">

                    <select class="form-control select2" id="id_tipe_kendaraan">

                      <option value="">- choose -</option>

                      <?php 

                      foreach($dt_tipe->result() as $val) {

                        echo "

                        <option value='$val->id_tipe_kendaraan'>$val->tipe_ahm</option>;

                        ";

                      }

                      ?>

                    </select>

                  </div>                  

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Januari</label>

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control"  placeholder="Januari" id="jan">

                  </div>

                  <label for="field-1" class="col-sm-2 control-label">Februari</label>            

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="feb" placeholder="Februari">                                        

                  </div>

                  <label for="field-1" class="col-sm-2 control-label">Maret</label>            

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="mar" placeholder="Maret">                                        

                  </div>                                   

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">April</label>

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control"  placeholder="April" id="apr">

                  </div>

                  <label for="field-1" class="col-sm-2 control-label">Mei</label>            

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="mei" placeholder="Mei">                                        

                  </div>

                  <label for="field-1" class="col-sm-2 control-label">Juni</label>            

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="jun" placeholder="Juni">                                        

                  </div>                                   

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Juli</label>

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control"  placeholder="Juli" id="jul">

                  </div>

                  <label for="field-1" class="col-sm-2 control-label">Agustus</label>            

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="agus" placeholder="Agustus">                                        

                  </div>

                  <label for="field-1" class="col-sm-2 control-label">September</label>            

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="sept" placeholder="September">                                        

                  </div>                                   

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Oktober</label>

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control"  placeholder="Oktober" id="okt">

                  </div>

                  <label for="field-1" class="col-sm-2 control-label">November</label>            

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="nov" placeholder="November">                                        

                  </div>

                  <label for="field-1" class="col-sm-2 control-label">Desember</label>            

                  <div class="col-sm-2">

                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="des" placeholder="Desember">                                        

                  </div>                                   

                </div>                

                <div class="form-group">                          

                  <div class="col-sm-2">

                  </div>  

                  <div class="col-sm-8">

                   <button type="button" onClick="simpan_target()" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>

                   <button type="button" onClick="kirim_data_target()" class="btn btn-warning btn-flat"><i class="fa fa-eye"></i> Show</button>

                   <button type="button" onClick="hide_target()" class="btn btn-warning btn-flat"><i class="fa fa-eye-slash"></i> Hide</button>

                  </div> 

                </div>

                <div class="form-group">                  

                  <div class="col-sm-10">

                    <div id="tampil_target"></div>

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

          <a href="master/target_sales/add">

            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>

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

              <th>ID Target Sales</th>                            

              <th>Dealer</th>                            

              <th>Tahun</th>                                          

              <th>Tipe Kendaraan</th>

              <th width="5%">Active</th>                            

              <th width="10%">Action</th>

            </tr>

          </thead>

          <tbody>            

          <?php 

          $no=1; 

          foreach($dt_target_sales->result() as $row) {          

            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";

                else $active = "";            

            $sql = $this->db->query("SELECT * FROM ms_target_sales_detail INNER JOIN ms_tipe_kendaraan 

                ON ms_target_sales_detail.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan WHERE id_target_sales = '$row->id_target_sales'");

            $isi = 1;

          echo "          

            <tr>

              <td>$no</td>              

              <td>$row->id_target_sales</td>                            

              <td>$row->nama_dealer</td>                            

              <td>$row->tahun</td>                                          

              <td>";

              foreach ($sql->result() as $k) {

                echo "$isi. $k->tipe_ahm <br>";

                $isi++;

              }

              echo "

              </td>                            

              <td>$active</td>                            

              <td>";

              ?>

                <a data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/target_sales/delete?id=<?php echo $row->id_target_sales ?>"><i class="fa fa-trash-o"></i></a>

                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/target_sales/edit?id=<?php echo $row->id_target_sales ?>'><i class='fa fa-edit'></i></a>

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

function hide_target(){

    $("#tampil_target").hide();

}

function kirim_data_target(){    

  $("#tampil_target").show();

  var id_target_sales = document.getElementById("id_target_sales").value;   

  var xhr;

  if (window.XMLHttpRequest) { // Mozilla, Safari, ...

    xhr = new XMLHttpRequest();

  }else if (window.ActiveXObject) { // IE 8 and older

    xhr = new ActiveXObject("Microsoft.XMLHTTP");

  } 

   //var data = "birthday1="+birthday1_js;          

    var data = "id_target_sales="+id_target_sales;                           

     xhr.open("POST", "master/target_sales/t_target", true); 

     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  

     xhr.send(data);

     xhr.onreadystatechange = display_data;

     function display_data() {

        if (xhr.readyState == 4) {

            if (xhr.status == 200) {       

                document.getElementById("tampil_target").innerHTML = xhr.responseText;

            }else{

                alert('There was a problem with the request.');

            }

        }

    } 

}

function simpan_target(){

    var id_target_sales    = document.getElementById("id_target_sales").value;   

    var id_tipe_kendaraan  = document.getElementById("id_tipe_kendaraan").value;       

    var jan       = document.getElementById("jan").value;       

    var feb       = document.getElementById("feb").value;       

    var mar       = document.getElementById("mar").value;       

    var apr       = document.getElementById("apr").value;       

    var mei       = document.getElementById("mei").value;       

    var jun       = document.getElementById("jun").value;       

    var jul       = document.getElementById("jul").value;       

    var agus      = document.getElementById("agus").value;       

    var sept      = document.getElementById("sept").value;       

    var okt       = document.getElementById("okt").value;       

    var nov       = document.getElementById("nov").value;       

    var des       = document.getElementById("des").value;       

    //alert(nov);

    if (id_target_sales=="" || id_tipe_kendaraan=="") {    

        alert("Isikan data dengan lengkap...!");

        return false;

    }else{

        $.ajax({

            url : "<?php echo site_url('master/target_sales/save_target')?>",

            type:"POST",

            data:"id_target_sales="+id_target_sales+"&id_tipe_kendaraan="+id_tipe_kendaraan+"&jan="+jan+"&feb="+feb+"&mar="+mar+"&apr="+apr+"&mei="+mei+"&jun="+jun+"&jul="+jul+"&agus="+agus+"&sept="+sept+"&okt="+okt+"&nov="+nov+"&des="+des,

            cache:false,

            success:function(msg){            

                data=msg.split("|");

                if(data[0]=="nihil"){

                    kirim_data_target();

                    kosong();                

                }else{

                    alert('Tipe Kendaraan ini sudah ditambahkan');

                    kosong();                      

                }                

            }

        })    

    }

}

function kosong(args){

  $("#id_tipe_kendaraan").val("");     

  $("#jan").val("");     

  $("#feb").val("");     

  $("#mar").val("");     

  $("#apr").val("");     

  $("#mei").val("");     

  $("#jun").val("");     

  $("#jul").val("");     

  $("#agus").val("");     

  $("#sept").val("");     

  $("#okt").val("");     

  $("#nov").val("");     

  $("#des").val("");     

}

function hapus_target(a,b){ 

    var id_target_sales_detail  = a;   

    var id_target_sales   = b;       

    $.ajax({

        url : "<?php echo site_url('master/target_sales/delete_target')?>",

        type:"POST",

        data:"id_target_sales_detail="+id_target_sales_detail,

        cache:false,

        success:function(msg){            

            data=msg.split("|");

            if(data[0]=="nihil"){

              kirim_data_target();

            }

        }

    })

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

          url: "<?php echo site_url('master/target_sales/ajax_bulk_delete')?>",

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