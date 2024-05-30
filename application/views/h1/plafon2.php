<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Finance</li>
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
          <a href="h1/plafon">
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
            <form class="form-horizontal" action="h1/plafon/save" method="post" enctype="multipart/form-data">
              <div class="box-body">                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer" onchange="showTotal()" id="id_dealer">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_dealer->result() as $val) {
                        echo "
                        <option value='$val->id_dealer' data-plafon = '$val->plafon_maks' data-plafon_akhir = '$val->plafon' >$val->kode_dealer_md | $val->nama_dealer</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Plafon Awal</label>

                  <div class="col-sm-4">
                    <input type="text" class="form-control" disabled id="total_plafon_awal" placeholder="" name="total_plafon_awal" id="total_plafon_awal">
                  </div>
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No. Surat Pengajuan</label>
                  <div class="col-sm-4">
                   <input type="text" class="form-control" id="no_surat_pengajuan" placeholder="No. Surat Pengajuan" name="no_surat_pengajuan">
                  
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Plafon Akhir</label>

                  <div class="col-sm-4">
                    <input type="text" class="form-control" disabled id="total_plafon_akhir" placeholder="" name="total_plafon_akhir" id="total_plafon_akhir">
                  </div>
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan </label>
                  <div class="col-sm-6">
                   <input type="text" class="form-control" id="keterangan" placeholder="Keterangan" name="keterangan">
                  
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Foto (Maks 1 MB)</label>
                  <div class="col-sm-6">
                   <input type="file" class="form-control" id="foto" placeholder="Foto" name="foto"></div>
                </div> 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pengajuan Plafon</label>
                  <div class="col-sm-2">
                    <select class="form-control" name="op">
                      <option value="+">Penambahan</option>
                      <option value="-">Pengurangan</option>
                    </select>
                  </div> 
                  <div class="col-sm-8">
                    <input type="text" onkeypress="return number_only(event)" class="form-control" required id="tanpa-rupiah" placeholder="Pengajuan Plafon" name="plafon">
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
      $row = $dt_plafon->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/plafon">
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
            <form class="form-horizontal" action="h1/plafon/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_plafon ?>" />
              <div class="box-body">                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer" disabled>
                      <option value="<?php echo $row->id_dealer ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();                                 
                        if(isset($dt_cust)){
                          echo "$dt_cust->kode_dealer_md | $dt_cust->nama_dealer";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_dealer = $this->m_admin->kondisi("ms_dealer","id_dealer != ".$row->id_dealer);                                                
                      foreach($dt_dealer->result() as $val) {
                        echo "
                        <option value='$val->id_dealer'>$val->kode_dealer_md | $val->nama_dealer</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Plafon Awal</label>

                  <div class="col-sm-4">
                    <input type="text" class="form-control" disabled id="total_plafon_awal" placeholder="" name="total_plafon_awal" value="<?php echo $dt_cust->plafon_maks ?>">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No. Surat Pengajuan</label>
                  <div class="col-sm-4">
                   <input type="text" class="form-control" id="no_surat_pengajuan" placeholder="No. Surat Pengajuan" name="no_surat_pengajuan" value="<?php echo $row->no_surat_pengajuan ?>">
                  
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Plafon Akhir</label>

                  <div class="col-sm-4">
                    <input type="text" class="form-control" disabled id="total_plafon_akhir" placeholder="" value="<?php echo $dt_cust->plafon ?>" name="total_plafon_akhir">
                  </div>
                </div> 
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-6">
                   <input type="text" class="form-control" id="keterangan" placeholder="Keterangan" name="keterangan" value="<?php echo $row->keterangan ?>">
                  
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Foto</label>
                  <div class="col-sm-6">
                   <input type="file" class="form-control" id="foto" placeholder="Foto" name="foto"></div>
                </div>   
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pengajuan Plafon</label>
                  <div class="col-sm-2">
                    <select class="form-control" name="op">
                      <?php if($row->op1 == '+'){ ?>
                      <option value="+">Penambahan</option>
                      <option value="-">Pengurangan</option>
                      <?php }elseif($row->op1 == '-'){ ?>
                      <option value="-">Pengurangan</option>
                      <option value="+">Penambahan</option>
                      <?php } ?>
                    </select>
                  </div> 
                  <div class="col-sm-8">
                    <input type="text" value="<?php echo mata_uang2($row->plafon)  ?>" onkeypress="return number_only(event)" class="form-control" required id="tanpa-rupiah" placeholder="Pengajuan Plafon" name="plafon">
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
    }elseif($set=="approve1"){
      $row = $dt_plafon->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/plafon">
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
            <form class="form-horizontal" action="h1/plafon/update_approve1" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_plafon ?>" />
              <input type="hidden" name="status" value="waiting 1" />
              <div class="box-body">                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer" disabled>
                      <option value="<?php echo $row->id_dealer ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();                                 
                        if(isset($dt_cust)){
                          echo "$dt_cust->kode_dealer_md | $dt_cust->nama_dealer";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_dealer = $this->m_admin->kondisi("ms_dealer","id_dealer != ".$row->id_dealer);                                                
                      foreach($dt_dealer->result() as $val) {
                        echo "
                        <option value='$val->id_dealer'>$val->kode_dealer_md | $val->nama_dealer</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pengajuan Plafon (Marketing)</label>
                  <div class="col-sm-2">
                    <select class="form-control" name="op">
                      <?php if($row->op1 == '+'){ ?>
                      <option value="+">Penambahan</option>
                      <option value="-">Pengurangan</option>
                      <?php }elseif($row->op1 == '-'){ ?>
                      <option value="-">Pengurangan</option>
                      <option value="+">Penambahan</option>
                      <?php } ?>
                    </select>
                  </div> 
                  <div class="col-sm-8">
                    <input type="text" value="<?php echo mata_uang2($row->plafon)  ?>" readonly onkeypress="return number_only(event)" class="form-control" required id="tanpa-rupiah" placeholder="Pengajuan Plafon">
                  </div>
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Plafon yg Disetujui</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo mata_uang2($row->plafon)  ?>" onkeypress="return number_only(event)" class="form-control" required id="tanpa-rupiah" placeholder="Plafon yg Disetujui" name="plafon1">
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
    }elseif($set=="approve2"){
      $row = $dt_plafon->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/plafon">
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
            <form class="form-horizontal" action="h1/plafon/update_approve1" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_plafon ?>" />
              <input type="hidden" name="id_dealer" value="<?php echo $row->id_dealer ?>" />
              <input type="hidden" name="status" value="waiting 2" />
              <div class="box-body">                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer" disabled>
                      <option value="<?php echo $row->id_dealer ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();                                 
                        if(isset($dt_cust)){
                          echo "$dt_cust->kode_dealer_md | $dt_cust->nama_dealer";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_dealer = $this->m_admin->kondisi("ms_dealer","id_dealer != ".$row->id_dealer);                                                
                      foreach($dt_dealer->result() as $val) {
                        echo "
                        <option value='$val->id_dealer'>$val->kode_dealer_md | $val->nama_dealer</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pengajuan Plafon (Marketing)</label>
                  <div class="col-sm-2">
                    <select class="form-control" name="op">
                      <?php if($row->op2 == '+'){ ?>
                      <option value="+">Penambahan</option>
                      <option value="-">Pengurangan</option>
                      <?php }elseif($row->op2 == '-'){ ?>
                      <option value="-">Pengurangan</option>
                      <option value="+">Penambahan</option>
                      <?php } ?>
                    </select>
                  </div> 
                  <div class="col-sm-8">
                    <input type="text" value="<?php echo mata_uang2($row->plafon)  ?>" readonly onkeypress="return number_only(event)" class="form-control" required id="tanpa-rupiah" placeholder="Pengajuan Plafon (Marketing)">
                  </div>
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pengajuan Plafon (Finance)</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo mata_uang2($row->plafon1)  ?>" readonly onkeypress="return number_only(event)" class="form-control" required id="tanpa-rupiah" placeholder="Pengajuan Plafon (Finance)">
                  </div>
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Plafon yg Disetujui</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo mata_uang2($row->plafon1)  ?>" onkeypress="return number_only(event)" class="form-control" required id="tanpa-rupiah" placeholder="Plafon yg Disetujui" name="plafon2">
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
          <a href="h1/plafon/add">
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
              <th>ID Dealer</th>                            
              <th>Nama Dealer</th>
              <th>Plafon</th>               
              <th>Status</th> 
              <th>Tanggal</th>            
              <th width="15%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_plafon->result() as $row) {       
            $edit = $this->m_admin->set_tombol($id_menu,$group,'edit');
            $delete = $this->m_admin->set_tombol($id_menu,$group,'delete');
            $approval = $this->m_admin->set_tombol($id_menu,$group,'approval');
            if($row->status=='input'){
              $op = $row->op1;
              $plafon = mata_uang2($row->plafon);
              $status = "<span class='label label-danger'>$row->status</span>";
              $rd =  "<a $delete data-toggle=\"tooltip\" title=\"Delete Data\" onclick=\"return confirm('Are you sure to delete this data?')\" class=\"btn btn-danger btn-sm btn-flat\" href=\"h1/plafon/delete?id=$row->id_plafon\"><i class=\"fa fa-trash-o\"></i></a>
                      <a $edit data-toggle=\"tooltip\" title=\"Edit Data\" class=\"btn btn-primary btn-sm btn-flat\" href=\"h1/plafon/edit?id=$row->id_plafon\"><i class=\"fa fa-edit\"></i></a>";
              $rs = "<a $approval data-toggle=\"tooltip\" title=\"Appove Data\" class=\"btn btn-success btn-sm btn-flat\" href=\"h1/plafon/approve?id=$row->id_plafon\"><i class=\"fa fa-check\"></i></a>";              
            }elseif($row->status=='waiting 2' OR $row->status=='waiting 1'){
              $op = $row->op2;
              $plafon = mata_uang2($row->plafon1);
              $status = "<span class='label label-warning'>$row->status</span>";
              $rd = "";
              $rs = "<a $approval data-toggle=\"tooltip\" title=\"Appove Data\" class=\"btn btn-success btn-sm btn-flat\" href=\"h1/plafon/approve?id=$row->id_plafon\"><i class=\"fa fa-check\"></i></a>";
            }elseif($row->status=='approved'){
              $op = $row->op3;
              $plafon = mata_uang2($row->plafon2);
              $status = "<span class='label label-success'>$row->status</span>";
              $rd = "";
              $rs = "";
            }   
          echo "          
            <tr>
              <td>$no</td>              
              <td>$row->kode_dealer_md</td>                            
              <td>$row->nama_dealer</td>                            
              <td>(".$op.") ".$plafon."</td>                            
              <td>$status</td>                            
              <td>$row->tgl</td>                            
              <td>";
              ?>                                
                <?php echo $rd ?>
                <?php echo $rs ?>
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
  function showTotal()
{
  var total_plafon_awal = $("#id_dealer").select2().find(":selected").data("plafon");
  var total_plafon_akhir = $("#id_dealer").select2().find(":selected").data("plafon_akhir");
  $('#total_plafon_awal').val(total_plafon_awal);
  $('#total_plafon_akhir').val(total_plafon_akhir);
}
</script>