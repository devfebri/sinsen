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
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>
  <section class="content">
    <?php
    if ($set == "insert") {
    ?>

      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="master/user_group">
              <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
              <form class="form-horizontal" action="master/user_group/save" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Code</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" autofocus id="inputEmail3" placeholder="Code" name="code">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">User Group</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" required id="inputEmail3" placeholder="User Group" name="user_group">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Jenis User</label>
                    <div class="col-sm-4">
                      <select class="form-control" name="jenis_user">
                        <option value="Main Dealer">Main Dealer</option>
                        <option value="Dealer">Dealer</option>
                      </select>
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
    } elseif ($set == "edit") {
      $row = $dt_user_group->row();
    ?>

      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="master/user_group">
              <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
              <form class="form-horizontal" action="master/user_group/update" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $row->id_user_group ?>" />
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Code</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" value="<?php echo $row->code ?>" autofocus id="inputEmail3" placeholder="Code" name="code">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">User Group</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" value="<?php echo $row->user_group ?>" required id="inputEmail3" placeholder="User Group" name="user_group">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Jenis User</label>
                    <div class="col-sm-4">
                      <select class="form-control" name="jenis_user">
                        <option><?php echo $row->jenis_user ?></option>
                        <option value="Main Dealer">Main Dealer</option>
                        <option value="Dealer">Dealer</option>
                      </select>
                    </div>
                  </div>
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
    } elseif ($set == "set_menu") {
      $row = $dt_user_group->row();
    ?>

      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="master/user_group">
              <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
              <form class="form-horizontal" action="master/user_group/save_access" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="field-1" class="col-sm-2 control-label">User Group</label>
                    <div class="col-sm-6">
                      <input type="text" readonly name="user_group" value="<?php echo $row->user_group ?>" class="form-control">
                      <input type="hidden" name="id_user_group" value="<?php echo $row->id_user_group ?>">
                    </div>
                  </div>

                  <hr>
                  <div class="form-group">
                    <table id="exampleX" class="table table-bordered">
                      <thead>
                        <tr>
                          <th colspan="18" bgcolor="maroon">
                            <input type='checkbox' id="check-allx">
                            <font color="white">Check All Menus</font>
                          </th>
                        </tr>
                        <tr>
                          <th>No</th>
                          <th width="15%" style="margin:1;padding-left:5px;">Menu</th>
                          <th width="15%" style="margin:1;padding-left:5px;;">Sub Menu</th>
                          <th width="15%" style="margin:1;padding-left:5px;">Sistem</th>
                          <th style="margin:1;padding:0;width:7%">
                            <div align="center">View <br><input type='checkbox' id="check-all"> </div>
                          </th>
                          <th style="margin:1;padding:0;width:7%">
                            <div align="center">Add <br><input type='checkbox' id="check-all2"> </div>
                          </th>
                          <th style="margin:1;padding:0;width:7%">
                            <div align="center">Edit <br><input type='checkbox' id="check-all3"> </div>
                          </th>
                          <th style="margin:1;padding:0;width:7%">
                            <div align="center">Delete <br><input type='checkbox' id="check-all4"> </div>
                          </th>
                          <th style="margin:1;padding:0;width:7%">
                            <div align="center">Print <br><input type='checkbox' id="check-all5"> </div>
                          </th>
                          <th style="margin:1;padding:0;width:7%">
                            <div align="center">Download <br><input type='checkbox' id="check-all6"> </div>
                          </th>
                          <th style="margin:1;padding:0;width:7%">
                            <div align="center">Approval <br><input type='checkbox' id="check-all7"> </div>
                          </th>
                          <th style="margin:1;padding:0;width:7%">
                            <div align="center">Reject <br><input type='checkbox' id="check-all8"> </div>
                          </th>
                          <th style="margin:1;padding:0;width:7%">
                            <div align="center">Submit <br><input type='checkbox' id="check-all9"> </div>
                          </th>
                          <th style="margin:1;padding:0;width:7%">
                            <div align="center">Cancel <br><input type='checkbox' id="check-all10"> </div>
                          </th>
                          <th style="margin:1;padding:0;width:7%">
                            <div align="center">Close <br><input type='checkbox' id="check-all11"> </div>
                          </th>
                          <th style="margin:1;padding:0;width:7%">
                            <div align="center">Reopen <br><input type='checkbox' id="check-all12"> </div>
                          </th>
                          <th style="margin:1;padding:0;width:7%">
                            <div align="center">Transit <br><input type='checkbox' id="check-all13"> </div>
                          </th>
                        </tr>

                      </thead>
                      <?php
                      $no = 1;
                      if ($row->jenis_user == 'Dealer') {
                        $sql = $this->db->query("SELECT ms_menu.*,ms_menu_header.menu_header,ms_menu_induk.menu_induk FROM ms_menu
                            INNER JOIN ms_menu_header ON ms_menu.id_menu_header = ms_menu_header.id_menu_header
                            INNER JOIN ms_menu_induk ON ms_menu.id_menu_induk = ms_menu_induk.id_menu_induk 
                            WHERE ms_menu.status = '1' AND ms_menu.id_menu_header = '7' ORDER BY ms_menu.id_menu ASC");
                      } else {
                        $sql = $this->db->query("SELECT ms_menu.*,ms_menu_header.menu_header,ms_menu_induk.menu_induk FROM ms_menu
                            INNER JOIN ms_menu_header ON ms_menu.id_menu_header = ms_menu_header.id_menu_header
                            INNER JOIN ms_menu_induk ON ms_menu.id_menu_induk = ms_menu_induk.id_menu_induk 
                            WHERE (ms_menu.status = '1' AND ms_menu.id_menu_header <> '7') OR ms_menu.urutan_menu = '99' ORDER BY ms_menu.id_menu ASC");
                      }

                      foreach ($sql->result() as $menu) {
                        if ($menu->menu_level == 1) {
                          $menu_level = "Main Dealer";
                        } elseif ($menu->menu_level == 2) {
                          $menu_level = "Dealer";
                        }
                        $cek = $this->db->query("SELECT * FROM ms_user_access_level WHERE id_user_group = '$row->id_user_group' AND id_menu = '$menu->id_menu'")->row();
                        if (isset($cek)) {
                          if ($cek->can_select == '1') $st1 = "checked";
                          else $st1 = "";
                          if ($cek->can_insert == '1') $st2 = "checked";
                          else $st2 = "";
                          if ($cek->can_update == '1') $st3 = "checked";
                          else $st3 = "";
                          if ($cek->can_delete == '1') $st4 = "checked";
                          else $st4 = "";
                          if ($cek->can_approval == '1') $st5 = "checked";
                          else $st5 = "";
                          if ($cek->can_download == '1') $st6 = "checked";
                          else $st6 = "";
                          if ($cek->can_print == '1') $st7 = "checked";
                          else $st7 = "";
                          if ($cek->can_reject == '1') $st8 = "checked";
                          else $st8 = "";
                          if ($cek->can_submit == '1') $st9 = "checked";
                          else $st9 = "";
                          if ($cek->can_cancel == '1') $st10 = "checked";
                          else $st10 = "";
                          if ($cek->can_close == '1') $st11 = "checked";
                          else $st11 = "";
                          if ($cek->can_reopen == '1') $st12 = "checked";
                          else $st12 = "";
                          if ($cek->can_transit == '1') $st13 = "checked";
                          else $st13 = "";
                        } else {
                          $st1 = "";
                          $st2 = "";
                          $st3 = "";
                          $st4 = "";
                          $st5 = "";
                          $st6 = "";
                          $st7 = "";
                          $st8 = "";
                          $st9 = "";
                          $st10 = "";
                          $st11 = "";
                          $st12 = "";
                          $st13 = "";
                        }
                      ?>
                        <tr>
                          <td><?php echo $no ?></td>
                          <td><?php echo $menu->menu_induk ?></td>
                          <td>
                            <input type="hidden" name="id_menu_<?php echo $no ?>" value="<?php echo $menu->id_menu ?>">
                            <?php echo $menu->menu_name ?>
                          </td>
                          <td><?php echo $menu->menu_header ?></td>
                          <td>
                            <div align="center">
                              <input type="checkbox" name="can_select_<?php echo $no ?>" class="data-check" <?php echo $st1 ?>>
                            </div>
                          </td>
                          <td>
                            <div align="center">
                              <input type="checkbox" name="can_insert_<?php echo $no ?>" class="data-check2" <?php echo $st2 ?>>
                            </div>
                          </td>
                          <td>
                            <div align="center">
                              <input type="checkbox" name="can_update_<?php echo $no ?>" class="data-check3" <?php echo $st3 ?>>
                            </div>
                          </td>
                          <td>
                            <div align="center">
                              <input type="checkbox" name="can_delete_<?php echo $no ?>" class="data-check4" <?php echo $st4 ?>>
                            </div>
                          </td>
                          <td>
                            <div align="center">
                              <input type="checkbox" name="can_print_<?php echo $no ?>" class="data-check5" <?php echo $st7 ?>>
                            </div>
                          </td>
                          <td>
                            <div align="center">
                              <input type="checkbox" name="can_download_<?php echo $no ?>" class="data-check6" <?php echo $st6 ?>>
                            </div>
                          </td>
                          <td>
                            <div align="center">
                              <input type="checkbox" name="can_approval_<?php echo $no ?>" class="data-check7" <?php echo $st5 ?>>
                            </div>
                          </td>
                          <td>
                            <div align="center">
                              <input type="checkbox" name="can_reject_<?php echo $no ?>" class="data-check8" <?php echo $st8 ?>>
                            </div>
                          </td>
                          <td>
                            <div align="center">
                              <input type="checkbox" name="can_submit_<?php echo $no ?>" class="data-check9" <?php echo $st9 ?>>
                            </div>
                          </td>
                          <td>
                            <div align="center">
                              <input type="checkbox" name="can_cancel_<?php echo $no ?>" class="data-check10" <?php echo $st10 ?>>
                            </div>
                          </td>
                          <td>
                            <div align="center">
                              <input type="checkbox" name="can_close_<?php echo $no ?>" class="data-check11" <?php echo $st11 ?>>
                            </div>
                          </td>
                          <td>
                            <div align="center">
                              <input type="checkbox" name="can_reopen_<?php echo $no ?>" class="data-check12" <?php echo $st12 ?>>
                            </div>
                          </td>
                          <td>
                            <div align="center">
                              <input type="checkbox" name="can_transit_<?php echo $no ?>" class="data-check13" <?php echo $st13 ?>>
                            </div>
                          </td>
                        </tr>
                        <?php
                        if ($menu->is_sub_menu) {
                          $get_chld_1 = $this->db->query("SELECT * FROM ms_menu WHERE parent_menu=$menu->id_menu AND status=1 ORDER BY id_menu ASC")
                        ?>
                          <?php foreach ($get_chld_1->result() as $chld1) :  $no++;
                            $cek = $this->db->query("SELECT * FROM ms_user_access_level WHERE id_user_group = '$row->id_user_group' AND id_menu = '$chld1->id_menu'")->row();
                            if (isset($cek)) {
                              if ($cek->can_select == '1') $st1 = "checked";
                              else $st1 = "";
                              if ($cek->can_insert == '1') $st2 = "checked";
                              else $st2 = "";
                              if ($cek->can_update == '1') $st3 = "checked";
                              else $st3 = "";
                              if ($cek->can_delete == '1') $st4 = "checked";
                              else $st4 = "";
                              if ($cek->can_approval == '1') $st5 = "checked";
                              else $st5 = "";
                              if ($cek->can_download == '1') $st6 = "checked";
                              else $st6 = "";
                              if ($cek->can_print == '1') $st7 = "checked";
                              else $st7 = "";
                              if ($cek->can_reject == '1') $st8 = "checked";
                              else $st8 = "";
                              if ($cek->can_submit == '1') $st9 = "checked";
                              else $st9 = "";
                              if ($cek->can_cancel == '1') $st10 = "checked";
                              else $st10 = "";
                              if ($cek->can_close == '1') $st11 = "checked";
                              else $st11 = "";
                              if ($cek->can_reopen == '1') $st12 = "checked";
                              else $st12 = "";
                              if ($cek->can_transit == '1') $st13 = "checked";
                              else $st13 = "";
                            } else {
                              $st1 = "";
                              $st2 = "";
                              $st3 = "";
                              $st4 = "";
                              $st5 = "";
                              $st6 = "";
                              $st7 = "";
                              $st8 = "";
                              $st9 = "";
                              $st10 = "";
                              $st11 = "";
                              $st12 = "";
                              $st13 = "";
                            }
                          ?>
                            <tr>
                              <td><?= $no ?></td>
                              <td><?= $menu->menu_name ?></td>
                              <td><?= $chld1->menu_name ?></td>
                              <td>Dealer</td>
                              <td>
                                <input type="hidden" name="id_menu_<?php echo $no ?>" value="<?php echo $chld1->id_menu ?>">
                                <div align="center">
                                  <input type="checkbox" name="can_select_<?php echo $no ?>" class="data-check" <?php echo $st1 ?>>
                                </div>
                              </td>
                              <td>
                                <div align="center">
                                  <input type="checkbox" name="can_insert_<?php echo $no ?>" class="data-check2" <?php echo $st2 ?>>
                                </div>
                              </td>
                              <td>
                                <div align="center">
                                  <input type="checkbox" name="can_update_<?php echo $no ?>" class="data-check3" <?php echo $st3 ?>>
                                </div>
                              </td>
                              <td>
                                <div align="center">
                                  <input type="checkbox" name="can_delete_<?php echo $no ?>" class="data-check4" <?php echo $st4 ?>>
                                </div>
                              </td>
                              <td>
                                <div align="center">
                                  <input type="checkbox" name="can_print_<?php echo $no ?>" class="data-check5" <?php echo $st7 ?>>
                                </div>
                              </td>
                              <td>
                                <div align="center">
                                  <input type="checkbox" name="can_download_<?php echo $no ?>" class="data-check6" <?php echo $st6 ?>>
                                </div>
                              </td>
                              <td>
                                <div align="center">
                                  <input type="checkbox" name="can_approval_<?php echo $no ?>" class="data-check7" <?php echo $st5 ?>>
                                </div>
                              </td>
                              <td>
                                <div align="center">
                                  <input type="checkbox" name="can_reject_<?php echo $no ?>" class="data-check8" <?php echo $st8 ?>>
                                </div>
                              </td>
                              <td>
                                <div align="center">
                                  <input type="checkbox" name="can_submit_<?php echo $no ?>" class="data-check9" <?php echo $st9 ?>>
                                </div>
                              </td>
                              <td>
                                <div align="center">
                                  <input type="checkbox" name="can_cancel_<?php echo $no ?>" class="data-check10" <?php echo $st10 ?>>
                                </div>
                              </td>
                              <td>
                                <div align="center">
                                  <input type="checkbox" name="can_close_<?php echo $no ?>" class="data-check11" <?php echo $st11 ?>>
                                </div>
                              </td>
                              <td>
                                <div align="center">
                                  <input type="checkbox" name="can_reopen_<?php echo $no ?>" class="data-check12" <?php echo $st12 ?>>
                                </div>
                              </td>
                              <td>
                                <div align="center">
                                  <input type="checkbox" name="can_transit_<?php echo $no ?>" class="data-check13" <?php echo $st13 ?>>
                                </div>
                              </td>
                            </tr>
                          <?php endforeach ?>
                      <?php }

                        $no++;
                      } ?>

                      </tbody>
                    </table>
                  </div>
                </div>
                <input type="hidden" name="jum_data" value="<?php echo $no - 1 ?>">
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
    } elseif ($set == "view") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="master/user_group/add">
              <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
                <th width="5%">No</th>
                <th>User Group</th>
                <th>Jenis User</th>
                <th width="13%">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              foreach ($dt_user_group->result() as $row) {
                echo "          
            <tr>
              <td>$no</td>
              <td>$row->user_group</td>                            
              <td>$row->jenis_user</td>                            
              <td>";
              ?>
                <a <?php echo $this->m_admin->set_tombol($id_menu, $group, "delete"); ?> data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/user_group/delete?id=<?php echo $row->id_user_group ?>"><i class="fa fa-trash-o"></i></a>
                <a <?php echo $this->m_admin->set_tombol($id_menu, $group, "edit"); ?> data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/user_group/edit?id=<?php echo $row->id_user_group ?>'><i class='fa fa-edit'></i></a>
                <a data-toggle='tooltip' title="User Access Level" class='btn btn-info btn-sm btn-flat' href='master/user_group/access_level?id=<?php echo $row->id_user_group ?>'><i class='fa fa-share'></i></a>
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
  function bulk_delete() {
    var list_id = [];
    $(".data-check:checked").each(function() {
      list_id.push(this.value);
    });
    if (list_id.length > 0) {
      if (confirm('Are you sure delete this ' + list_id.length + ' data?')) {
        $.ajax({
          type: "POST",
          data: {
            id: list_id
          },
          url: "<?php echo site_url('master/user_group/ajax_bulk_delete') ?>",
          dataType: "JSON",
          success: function(data) {
            if (data.status) {
              window.location.reload();
            } else {
              alert('Failed.');
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            alert('Error deleting data');
          }
        });
      }
    } else {
      alert('no data selected');
    }
  }
</script>