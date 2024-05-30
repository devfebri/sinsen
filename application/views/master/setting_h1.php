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

    <li class="">Setting H1</li>

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

          <a href="master/setting_h1">

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

            <form class="form-horizontal" action="master/setting_h1/save" method="post" enctype="multipart/form-data">

              <div class="box-body">                                                                                                                                                    

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Presentase T1</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" required autofocus id="inputEmail3" placeholder="Presentase T1" name="presentase_t1">

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Presentase T2</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" required id="inputEmail3" placeholder="Presentase T2" name="presentase_t2">

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya ADM STNK</label>

                  <div class="col-sm-4">

                    <input type="text" onkeypress="return number_only(event)" class="form-control"  id="inputEmail3" placeholder="Biaya ADM STNK" name="biaya_stnk">

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya ADM BPKB</label>

                  <div class="col-sm-4">

                    <input type="text" onkeypress="return number_only(event)" class="form-control"  id="inputEmail3" placeholder="Biaya ADM BPKB" name="biaya_bpkb">

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya ADM Plat</label>

                  <div class="col-sm-4">

                    <input type="text" onkeypress="return number_only(event)" class="form-control"  id="inputEmail3" placeholder="Biaya ADM Plat" name="biaya_plat">

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya ADM Penjualan</label>

                  <div class="col-sm-4">

                    <input type="text" onkeypress="return number_only(event)" class="form-control"  id="inputEmail3" placeholder="Biaya ADM Penjualan" name="biaya_penjualan">

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

      $row = $dt_setting_h1->row(); 

    ?>



    <div class="box box-default">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="master/setting_h1">

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

            <form class="form-horizontal" action="master/setting_h1/update" method="post" enctype="multipart/form-data">

              <input type="hidden" name="id" value="<?php echo $row->id_setting_h1 ?>" />

              <div class="box-body">    

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Presentase T1</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" value="<?php echo $row->presentase_t1 ?>" required autofocus id="inputEmail3" placeholder="Presentase T1" name="presentase_t1">

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Presentase T2</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" value="<?php echo $row->presentase_t2 ?>" required id="inputEmail3" placeholder="Presentase T2" name="presentase_t2">

                  </div>

                </div>
                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Maksimum Stock Days Dealer</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" value="<?php echo $row->maks_stock_days ?>" required  id="inputEmail3" placeholder="Maksimum Stock Days Dealer" name="maks_stock_days">

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Jumlah Hari Pemenuhan Indent</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" value="<?php echo $row->jml_hari_indent ?>" required id="inputEmail3" placeholder="Jumlah Hari Pemenuhan Indent" name="jml_hari_indent">

                  </div>

                </div>
                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Masa Kadaluarsa Aki (bulan)</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" value="<?php echo $row->masa_aki ?>" required  id="inputEmail3" placeholder="Masa Kadaluarsa Aki (bulan)" name="masa_aki">

                  </div>                  

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya ADM STNK</label>

                  <div class="col-sm-4">

                    <input type="text" value="<?php echo $row->biaya_stnk ?>" onkeypress="return number_only(event)" class="form-control"  id="inputEmail3" placeholder="Biaya ADM STNK" name="biaya_stnk">

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya ADM BPKB</label>

                  <div class="col-sm-4">

                    <input type="text" value="<?php echo $row->biaya_bpkb ?>" onkeypress="return number_only(event)" class="form-control"  id="inputEmail3" placeholder="Biaya ADM BPKB" name="biaya_bpkb">

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya ADM Plat</label>

                  <div class="col-sm-4">

                    <input type="text" value="<?php echo $row->biaya_plat ?>" onkeypress="return number_only(event)" class="form-control"  id="inputEmail3" placeholder="Biaya ADM Plat" name="biaya_plat">

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya ADM Penjualan</label>

                  <div class="col-sm-4">

                    <input type="text" value="<?php echo $row->biaya_penjualan ?>" onkeypress="return number_only(event)" class="form-control"  id="inputEmail3" placeholder="Biaya ADM Penjualan" name="biaya_penjualan">

                  </div>

                </div>
                <hr>
                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">PO Fix Dealer</label>

                  <div class="col-sm-4">

                    <input type="text" value="<?php echo $row->po_fix_dealer ?>" onkeypress="return number_only(event)" class="form-control"  id="inputEmail3" placeholder="PO Fix Dealer" name="po_fix_dealer">

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">PO T1 Dealer</label>

                  <div class="col-sm-4">

                    <input type="text" value="<?php echo $row->po_t1_dealer ?>" onkeypress="return number_only(event)" class="form-control"  id="inputEmail3" placeholder="PO T1 Dealer" name="po_t1_dealer">

                  </div>

                </div>  
                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Deadline PO Dealer</label>

                  <div class="col-sm-4">

                    <input type="text" value="<?php echo $row->deadline_po_dealer ?>"  class="form-control"  id="inputEmail3" placeholder="Deadline PO Dealer" name="deadline_po_dealer">

                  </div>

                </div>     
                <hr>
                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Reminder SPK</label>
                  <div class="col-sm-4">

                    <input type="text" value="<?php echo $row->reminder_spk ?>" onkeypress="return number_only(event)" class="form-control"  id="inputEmail3" name="reminder_spk">

                  </div>

                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Reminder Service</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->reminder_service ?>" onkeypress="return number_only(event)" class="form-control"  id="inputEmail3" name="reminder_service">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Reminder Sales Follow UP</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->reminder_sales_follow_up ?>" onkeypress="return number_only(event)" class="form-control"  id="inputEmail3" name="reminder_sales_follow_up">
                  </div>
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Maks Uncontactable Sales Follow UP</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->maks_uncontactable_sales_fol_up ?>" onkeypress="return number_only(event)" class="form-control"  id="inputEmail3" name="maks_uncontactable_sales_fol_up">
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

          <!--a href="master/setting_h1/add">

            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>

          </a-->          

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

              <th>Presentase T1</th>              

              <th>Presentase T2</th>              

              <th>Biaya ADM STNK</th>

              <th>Biaya ADM BPKB</th>

              <th>Biaya ADM Plat</th>

              <th>Biaya ADM Penjualan</th>
              <th>Masa Kadaluarsa Aki</th>
              <th>Lama Pemenuhan Indent</th>
              <th>Maks Stock Days</th>


              <th width="5%">Action</th>

            </tr>

          </thead>

          <tbody>            

          <?php 

          $no=1; 

          foreach($dt_setting_h1->result() as $row) { 

            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";

                else $active = "";                  

          echo "          

            <tr>

              <td>$no</td>

              <td>$row->presentase_t1</td>                            

              <td>$row->presentase_t2</td>                            

              <td>".mata_uang2($row->biaya_stnk)."</td>                            

              <td>".mata_uang2($row->biaya_bpkb)."</td>                            

              <td>".mata_uang2($row->biaya_plat)."</td>                            

              <td>".mata_uang2($row->biaya_penjualan)."</td>                            
              <td>$row->masa_aki Bulan</td>
              <td>$row->jml_hari_indent Hari</td>
              <td>$row->maks_stock_days</td>

              <td>";

              ?>

                

                <a <?php echo $this->m_admin->set_tombol($id_menu,$group,"edit"); ?> data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/setting_h1/edit?id=<?php echo $row->id_setting_h1 ?>'><i class='fa fa-edit'></i></a>

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

          url: "<?php echo site_url('master/setting_h1/ajax_bulk_delete')?>",

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