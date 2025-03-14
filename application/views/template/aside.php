<?php
$group = $this->session->userdata('group');
$jenis_user = $this->session->userdata('jenis_user');
if ($jenis_user == "Main Dealer") {
?>

  <?php
  $li = $this->uri->segment(2);
  ?>


  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <?php
          $id = $this->session->userdata('id_user');
          $r  = $this->db->query("SELECT * FROM ms_user WHERE id_user = '$id'")->row();
          if ($r->avatar != "") {
            echo "<img src='assets/panel/images/user/$r->avatar' width='18px' class='img-circle' alt='User Image'>";
          } else {
            echo "<img src='assets/panel/images/user/admin-lk.jpg' width='18px' class='img-circle' alt='User Image'>";
          }
          ?>
        </div>
        <div class="pull-left info">
          <p>
            <?php echo $this->session->userdata('nama');  ?>
          </p>
          <a href="javascript:void(0);"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <br>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
	<li class="
            <?php
            if ($isi == "welcome") {
              echo "active";
            }
            ?>
	  ">
	<a href="panel/welcome"><i class="fa fa-home"></i> <span>Home</span></a></li> 

        <li class="
            <?php
            if ($isi == "home") {
              echo "active";
            }
            ?>
            "><a href="panel/home"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>

        <li class="header">REFERENCES</li>
         <?php if($this->session->userdata('id_user')=="468" or $this->session->userdata('id_user')=="488" ){?>
        <li class="">
          <a>
            <i class="fa fa-database"></i> <span>Dashboard</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu" style="white-space: normal;">
            <li class="" style="white-space: normal;"><a href="panel/home">Dashboard H1</a></li>
            <li class="" style="white-space: normal;"><a href="panel/home_h23">Dashboard H23</a></li>
          </ul>
        </li>
        <?php }?>
        <!-- Master Data -->
        <li class="
              <?php
              $l = $this->db->query("SELECT COUNT(id_menu) as ju FROM ms_menu WHERE id_menu_header = '1'")->row();
              $i = $l->ju;
              $sql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk BETWEEN 1 AND '$i' AND id_menu_header = '1'");
              foreach ($sql->result() as $m) {
                $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$m->menu_link' AND status = '1'")->row();
                if (count($ql) > 0) {
                  if ($isi == $ql->menu_link) {
                    echo "active";
                  }
                }
              }
              ?>
              " style="white-space: normal;">
          <a>
            <i class="fa fa-database"></i> <span>Master Data</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu" style="white-space: normal;">

            <?php
            $menu_bagian = $this->db->query("SELECT DISTINCT(ms_menu_induk.menu_induk),ms_menu_induk.id_menu_induk FROM ms_menu INNER JOIN ms_menu_bagian 
                          ON ms_menu.id_menu_bagian=ms_menu_bagian.id_menu_bagian INNER JOIN ms_menu_induk
                          ON ms_menu.id_menu_induk=ms_menu_induk.id_menu_induk INNER JOIN ms_user_access_level
                          ON ms_menu.id_menu = ms_user_access_level.id_menu
                          WHERE ms_menu.status = '1' AND ms_menu.id_menu_header = '1' AND ms_user_access_level.id_user_group = '$group' 
                          AND ms_user_access_level.can_select = '1' ORDER BY ms_menu.id_menu ASC");
            foreach ($menu_bagian->result() as $menu) {
            ?>
              <!-- Demography -->
              <li class="                
                  <?php
                  $sql = $this->db->query("SELECT * FROM ms_menu INNER JOIN ms_user_access_level ON ms_menu.id_menu=ms_user_access_level.id_menu 
                    WHERE ms_menu.id_menu_induk = '$menu->id_menu_induk' AND ms_menu.id_menu_header = '1' AND ms_menu.status = '1'
                    AND ms_user_access_level.id_user_group = '$group' AND ms_user_access_level.can_select = '1' ORDER BY ms_menu.id_menu ASC");
                  foreach ($sql->result() as $m) {
                    $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND id_menu_header = '1' AND menu_link = '$m->menu_link' AND status = '1'")->row();
                    if ($isi == $ql->menu_link) {
                      echo "active";
                    }
                  }
                  ?>
                  treeview" style="white-space: normal;">
                <a>
                  <span><?php echo $menu->menu_induk ?></span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu" style="white-space: normal;">
                  <?php
                  foreach ($sql->result() as $me) {
                    $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$me->menu_link'")->row();
                  ?>
                    <li class="
                      <?php
                      if ($isi == $ql->menu_link) {
                        echo "active";
                      }
                      ?>
                      " style="white-space: normal;"><a href="master/<?php echo $me->menu_link ?>"><?php echo $me->menu_name ?></a></li>
                  <?php
                  }
                  ?>
                </ul>
              </li>

            <?php
            }
            ?>
          </ul>
        </li>

        <li class="header">MAIN NAVIGATION</li>
        <?php
        $main_menu = ['H1' => 'H1', 'H2' => 'H2', 'H3' => 'H3'];
        $id_menu_header = ['H1' => 2, 'H2' => 3, 'H3' => 4];
        foreach ($main_menu as $key => $val) { ?>
          <li class="treeview" id="<?= $key ?>" style="white-space: normal;">
            <a><i class="fa fa-database"></i>
              <span><?= $val ?></span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu" style="white-space: normal;">
              <?php
              $menu_bagian = $this->db->query("SELECT DISTINCT(ms_menu_induk.menu_induk),ms_menu_induk.id_menu_induk FROM ms_menu 
              INNER JOIN ms_menu_bagian ON ms_menu.id_menu_bagian=ms_menu_bagian.id_menu_bagian 
              INNER JOIN ms_menu_induk ON ms_menu.id_menu_induk=ms_menu_induk.id_menu_induk
              INNER JOIN ms_user_access_level ON ms_menu.id_menu = ms_user_access_level.id_menu
              WHERE ms_menu.status = '1' AND ms_menu.id_menu_header = '{$id_menu_header[$val]}'
              AND ms_user_access_level.id_user_group = '$group' AND menu_level=1 
              AND (SELECT SUM(ms_menu.id_menu) FROM ms_menu 
                  INNER JOIN ms_user_access_level ON ms_menu.id_menu=ms_user_access_level.id_menu 
                  WHERE ms_menu.id_menu_induk = ms_menu_induk.id_menu_induk AND ms_menu.id_menu_header = '{$id_menu_header[$val]}' AND ms_menu.status = '1' AND menu_level=1
                  AND ms_user_access_level.id_user_group = '$group' AND ms_user_access_level.can_select = '1' )>0
              ORDER BY ms_menu_induk.urutan ASC
              ");
              $tot_menu[] = ['key' => $key, 'tot' => $menu_bagian->num_rows()];
              foreach ($menu_bagian->result() as $menu) {
              ?>
                <li class="treeview" style="white-space: normal;">
                  <a>
                    <span><?php echo $menu->menu_induk ?></span>
                    <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu" style="white-space: normal;">
                    <?php
                    $sql = $this->db->query("SELECT * FROM ms_menu 
                  INNER 
                  JOIN ms_user_access_level ON ms_menu.id_menu=ms_user_access_level.id_menu 
                    WHERE ms_menu.id_menu_induk = '$menu->id_menu_induk' 
                    AND ms_menu.id_menu_header = '{$id_menu_header[$val]}' AND ms_menu.status = '1' AND menu_level=1
                    AND ms_user_access_level.id_user_group = '$group' AND ms_user_access_level.can_select = '1' ORDER BY ms_menu.code ASC");
                    foreach ($sql->result() as $me) {
                      $cek_sub1 = $this->db->query("SELECT id_menu_sub,id_menu,sub_name,sub_link FROM ms_menu_sub WHERE id_menu='$me->id_menu'");
                      if ($cek_sub1->num_rows() == 0) {
                    ?>
                        <li style="white-space: normal;">
                          <a href="<?= strtolower($val) ?>/<?php echo $me->menu_link ?>"><?= $me->menu_name ?>
                          </a>
                        </li>
                      <?php } else { // If Sub1 
                      ?>
                        <li class="treeview" style="white-space: normal;">
                          <a>
                            <span><?php echo $me->menu_name ?></span>
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                          </a>
                          <ul class="treeview-menu" style="white-space: normal;">
                            <?php
                            foreach ($cek_sub1->result() as $sub1) {
                              $cek_sub2 = $this->db->query("SELECT id_menu_sub_2,id_menu_sub,sub_name,sub_link FROM ms_menu_sub_2 WHERE id_menu_sub='$sub1->id_menu_sub'");
                              if ($cek_sub2->num_rows() == 0) { ?>
                                <li style="white-space: normal;">
                                  <a href="<?= strtolower($val) ?>/<?php echo $sub1->sub_link ?>"><?= $sub1->sub_name ?>
                                  </a>
                                </li>
                              <?php } else { // If Sub 2 
                              ?>
                                <li class="treeview" style="white-space: normal;">
                                  <a>
                                    <span><?php echo $sub1->sub_name ?></span>
                                    <span class="pull-right-container">
                                      <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                  </a>
                                  <ul class="treeview-menu" style="white-space: normal;">
                                    <?php
                                    foreach ($cek_sub2->result() as $sub2) { ?>
                                      <li style="white-space: normal;">
                                        <a href="<?= strtolower($val) ?>/<?php echo $sub2->sub_link ?>"><?= $sub2->sub_name ?>
                                        </a>
                                      </li>
                                    <?php } //Foreach Sub 2
                                    ?>
                                  </ul>
                                </li>
                            <?php } //Else If Sub 2
                            } //Foreach Sub 1
                            ?>
                          </ul>
                        </li>
                      <?php } //ekse if sub1 
                      ?>
                    <?php } ?>
                  </ul>
                </li>
              <?php
              }
              ?>
            </ul>
          </li>
        <?php } ?>


        <li class="header">CONFIGURATION</li>


        <li class="
            <?php
            if ($isi == "setting" or $isi == "notification") {
              echo "active";
            }
            ?>
            ">
          <a><i class="fa fa-gear"></i>
            <span> General</span><i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="
                <?php
                if ($isi == "setting") {
                  echo "active";
                }
                ?>
                "><a href="panel/setting">Setting</a></li>
            <li class="
                <?php
                if ($isi == "notification") {
                  echo "active";
                }
                ?>
                "><a href="panel/notification">Notification</a></li>
          </ul>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

<?php
} elseif ($jenis_user == 'Dealer') {
  $id_dealer = $this->m_admin->cari_dealer();
?>
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <?php
          $id = $this->session->userdata('id_user');
          $r  = $this->db->query("SELECT * FROM ms_user WHERE id_user = '$id'")->row();
          if ($r->avatar != "") {
            echo "<img src='assets/panel/images/user/$r->avatar' width='18px' class='img-circle' alt='User Image'>";
          } else {
            echo "<img src='assets/panel/images/user/admin-lk.jpg' width='18px' class='img-circle' alt='User Image'>";
          }
          ?>
        </div>
        <div class="pull-left info">
          <p>
            <?php echo $this->session->userdata('nama');  ?>
          </p>
          <a href="javascript:void(0);" ><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <br>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
	<li class="
            <?php
            if ($isi == "welcome") {
              echo "active";
            }
            ?>
	  ">
	<a href="panel/welcome"><i class="fa fa-home"></i> <span>Home</span></a></li> 
        <li class="
            <?php
            if ($isi == "home") {
              echo "active";
            }
            ?>
            ">
          <?php $uri_2 = $this->uri->segment(2);
          $aktif_menu = $this->db->query("SELECT menu_bagian FROM ms_menu 
            JOIN ms_menu_bagian ON ms_menu_bagian.id_menu_bagian=ms_menu.id_menu_bagian
            WHERE menu_link='$uri_2'");
          if ($aktif_menu->num_rows() > 0) {
            $aktif_menu = $aktif_menu->row()->menu_bagian;
          } else {
            $aktif_menu = null;
          }
          ?>
          <a href="panel/home"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
	<li><a id="click_qas" ><i class="fa fa-home"></i> <span>QAS</span></a></li> 
        <li class="header">MAIN NAVIGATION</li>
        <?php $main_menu = ['H1' => 'H1', 'H2' => 'H2', 'H3' => 'H3', 'FH1_Dealer' => 'Finance H1', 'FH23' => 'Finance H23', 'CRM' => 'CRM', 'DGI' => 'Dealer Group Integration', 'DMS' => 'DMS Extension'];
        foreach ($main_menu as $key => $val) { ?>
          <li class="treeview" id="<?= $key ?>" style="white-space: normal;">
            <a><i class="fa fa-database"></i>
              <span><?= $val ?></span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <?php
              $filter_header = " header='$key'";
              if ($key == 'H1') {
                $filter_header = " header IS NULL";
              }
              $menu_bagian = $this->db->query("SELECT DISTINCT(ms_menu_induk.menu_induk),ms_menu_induk.id_menu_induk FROM ms_menu 
              INNER JOIN ms_menu_bagian ON ms_menu.id_menu_bagian=ms_menu_bagian.id_menu_bagian 
              INNER JOIN ms_menu_induk ON ms_menu.id_menu_induk=ms_menu_induk.id_menu_induk AND $filter_header 
              INNER JOIN ms_user_access_level ON ms_menu.id_menu = ms_user_access_level.id_menu
              WHERE ms_menu.status = '1' AND ms_menu.id_menu_header = '7' 
              AND ms_user_access_level.id_user_group = '$group' AND menu_level=1 
              AND (SELECT SUM(ms_menu.id_menu) FROM ms_menu 
                  INNER JOIN ms_user_access_level ON ms_menu.id_menu=ms_user_access_level.id_menu 
                  WHERE ms_menu.id_menu_induk = ms_menu_induk.id_menu_induk AND ms_menu.id_menu_header = '7' AND ms_menu.status = '1' AND menu_level=1
                  AND ms_user_access_level.id_user_group = '$group' AND ms_user_access_level.can_select = '1' )>0
              ORDER BY ms_menu_induk.urutan ASC
              ");
              $tot_menu[] = ['key' => $key, 'tot' => $menu_bagian->num_rows()];
              foreach ($menu_bagian->result() as $menu) {
              ?>
                <li class="treeview" style="white-space: normal;">
                  <a>
                    <span><?php echo $menu->menu_induk ?></span>
                    <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu" style="white-space: normal;">
                    <?php
                    $sql = $this->db->query("SELECT * FROM ms_menu 
                  INNER 
                  JOIN ms_user_access_level ON ms_menu.id_menu=ms_user_access_level.id_menu 
                    WHERE ms_menu.id_menu_induk = '$menu->id_menu_induk' 
                    AND ms_menu.id_menu_header = '7' AND ms_menu.status = '1' AND menu_level=1
                    AND ms_user_access_level.id_user_group = '$group' AND ms_user_access_level.can_select = '1' ORDER BY ms_menu.code ASC");
                    foreach ($sql->result() as $me) { 
                      /*$list_id_dealer = [70,110,119,120,121,1,3,4,46,47];
                      if ($me->menu_link=='prospek_crm' && !in_array($id_dealer,$list_id_dealer)) {
                        continue;
                      }*/
                      ?>
                      <li><a href="dealer/<?php echo $me->menu_link ?>"><?php echo $me->menu_name ?>
                          <?php if ($me->is_sub_menu == 1) : ?>
                            <?php $menulv2 = $this->db->query("SELECT * FROM ms_menu 
                            JOIN ms_user_access_level uac ON uac.id_menu=ms_menu.id_menu
                            WHERE parent_menu=$me->id_menu AND uac.can_select='1' AND uac.id_user_group='$group'  ORDER BY code ASC") ?>
                            <?php if ($menulv2->num_rows() > 0) : ?>
                              <i class="fa fa-angle-left pull-right"></i>
                            <?php endif ?>
                          <?php endif ?>
                        </a>
                        <?php if ($me->is_sub_menu == 1) : ?>
                          <ul class="treeview-menu" style="white-space: normal;">
                            <?php foreach ($menulv2->result() as $rs) : ?>
                              <li><a href="dealer/<?php echo $rs->menu_link ?>"><?= $rs->menu_name ?></a></li>
                            <?php endforeach ?>
                          </ul>
                        <?php endif ?>
                      </li>
                    <?php } ?>
                  </ul>
                </li>
              <?php
              }
              ?>
            </ul>
          </li>
        <?php } ?>
        <!--li class="header">CONFIGURATION</li>
            <li class="
            <?php
            if ($isi == "setting" or $isi == "notification") {
              echo "active";
            }
            ?>
            ">
              <a href="#"><i class="fa fa-gear"></i> General
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="
                <?php
                if ($isi == "setting") {
                  echo "active";
                }
                ?>
                "><a href="panel/setting">Setting</a></li>
                <li class="
                <?php
                if ($isi == "notification") {
                  echo "active";
                }
                ?>
                "><a href="panel/notification">Notification</a></li>
              </ul>
            </li-->
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>


<?php
} elseif ($jenis_user == 'Admin' or $jenis_user == 'Super Admin') {
?>
  <?php
  $li = $this->uri->segment(2);
  ?>


  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <?php
          $id = $this->session->userdata('id_user');
          $r  = $this->db->query("SELECT * FROM ms_user WHERE id_user = '$id'")->row();
          if ($r->avatar != "") {
            echo "<img src='assets/panel/images/user/$r->avatar' width='18px' class='img-circle' alt='User Image'>";
          } else {
            echo "<img src='assets/panel/images/user/admin-lk.jpg' width='18px' class='img-circle' alt='User Image'>";
          }
          ?>
        </div>
        <div class="pull-left info">
          <p>
            <?php echo $this->session->userdata('nama');  ?>
          </p>
          <a href="javascript:void(0);" ><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <br>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
	<li class="
            <?php
            if ($isi == "welcome") {
              echo "active";
            }
            ?>
	  ">
	<a href="panel/welcome"><i class="fa fa-home"></i> <span>Home</span></a></li> 
        <li class="
            <?php
            if ($isi == "home") {
              echo "active";
            }
            ?>
            "><a href="panel/home"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>

        <li class="header">REFERENCES</li>
        <?php if($this->session->userdata('id_user')=="468"){?>
        <li class="">
          <a>
            <i class="fa fa-database"></i> <span>Dashboard</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu" style="white-space: normal;">
            <li class="" style="white-space: normal;"><a href="panel/home">Dashboard H1</a></li>
            <li class="" style="white-space: normal;"><a href="panel/home_h23">Dashboard H23</a></li>
          </ul>
        </li>
        <?php }?>
       
        <!-- Master Data -->
        <li class="
              <?php
              $l = $this->db->query("SELECT COUNT(id_menu) as ju FROM ms_menu WHERE id_menu_header = '1'")->row();
              $i = $l->ju;
              $sql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk BETWEEN 1 AND '$i' AND id_menu_header = '1'");
              foreach ($sql->result() as $m) {
                $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$m->menu_link' AND status = '1'")->row();
                if (count($ql) > 0) {
                  if ($isi == $ql->menu_link) {
                    echo "active";
                  }
                }
              }
              ?>
              " style="white-space: normal;">
          <a>
            <i class="fa fa-database"></i> <span>Master Data</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu" style="white-space: normal;">

            <?php
            $menu_bagian = $this->db->query("SELECT DISTINCT(ms_menu_induk.menu_induk),ms_menu_induk.id_menu_induk FROM ms_menu INNER JOIN ms_menu_bagian 
                          ON ms_menu.id_menu_bagian=ms_menu_bagian.id_menu_bagian INNER JOIN ms_menu_induk
                          ON ms_menu.id_menu_induk=ms_menu_induk.id_menu_induk 
                          WHERE ms_menu.status = '1' AND ms_menu.id_menu_header = '1' ORDER BY ms_menu.id_menu ASC");
            foreach ($menu_bagian->result() as $menu) {
            ?>
              <!-- Demography -->
              <li class="                
                  <?php
                  $sql = $this->db->query("SELECT * FROM ms_menu 
                    WHERE ms_menu.id_menu_induk = '$menu->id_menu_induk' AND ms_menu.id_menu_header = '1' AND ms_menu.status = '1'");
                  foreach ($sql->result() as $m) {
                    $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND id_menu_header = '1' AND menu_link = '$m->menu_link' AND status = '1'")->row();
                    if ($isi == $ql->menu_link) {
                      echo "active";
                    }
                  }
                  ?>
                  treeview" style="white-space: normal;">
                <a>
                  <span><?php echo $menu->menu_induk ?></span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu" style="white-space: normal;">
                  <?php
                  foreach ($sql->result() as $me) {
                    $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$me->menu_link'")->row();
                  ?>
                    <li class="
                      <?php
                      if ($isi == $ql->menu_link) {
                        echo "active";
                      }
                      ?>
                      " style="white-space: normal;"><a href="master/<?php echo $me->menu_link ?>"><?php echo $me->menu_name ?></a></li>
                  <?php
                  }
                  ?>
                </ul>
              </li>

            <?php
            }
            ?>
          </ul>
        </li>


        <li class="header">MAIN NAVIGATION</li>

        <li class="
              <?php
              $l = $this->db->query("SELECT MAX(id_menu_induk) as ju FROM ms_menu WHERE id_menu_header = '2'")->row();
              $i = $l->ju;
              $sql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk BETWEEN 16 AND '$i' AND id_menu_header = '2' AND status = '1' ORDER BY ms_menu.code ASC");
              foreach ($sql->result() as $m) {
                $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$m->menu_link' AND status = '1'")->row();
                if ($isi == $ql->menu_link) {
                  echo "active";
                } else {
                  echo "";
                }
              }
              ?>
              " style="white-space: normal;">
          <a>
            <i class="fa fa-database"></i> <span>H1</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu" style="white-space: normal;">

            <?php
            $menu_bagian = $this->db->query("SELECT DISTINCT(ms_menu_induk.menu_induk),ms_menu_induk.id_menu_induk FROM ms_menu INNER JOIN ms_menu_bagian 
                          ON ms_menu.id_menu_bagian=ms_menu_bagian.id_menu_bagian INNER JOIN ms_menu_induk
                          ON ms_menu.id_menu_induk=ms_menu_induk.id_menu_induk 
                          WHERE ms_menu.status = '1' AND id_menu_header = '2' ORDER BY ms_menu_induk.id_menu_induk,ms_menu.code ASC");
            foreach ($menu_bagian->result() as $menu) {
            ?>
              <!-- Demography -->
              <li class="                
                  <?php
                  $sql = $this->db->query("SELECT * FROM ms_menu 
                    WHERE ms_menu.id_menu_induk = '$menu->id_menu_induk' AND ms_menu.id_menu_header = '2' AND ms_menu.status = '1'");
                  foreach ($sql->result() as $m) {
                    $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND id_menu_header = '2' AND menu_link = '$m->menu_link' AND status = '1'")->row();
                    if ($isi == $ql->menu_link) {
                      echo "active";
                    }
                  }
                  ?>
                  treeview" style="white-space: normal;">
                <a>
                  <span><?php echo $menu->menu_induk ?></span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu" style="white-space: normal;">
                  <?php
                  foreach ($sql->result() as $me) {

                    $cek = $this->db->query("SELECT * FROM ms_menu_sub WHERE id_menu = '$me->id_menu'");
                    if ($cek->num_rows() > 0) {
                      $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$me->menu_link'")->row();
                  ?>
                      <li class="
                          <?php
                          if ($isi == $ql->menu_link) {
                            echo "active";
                          }
                          ?>
                          " style="white-space: normal;">
                        <a><?php echo $me->menu_name ?>
                          <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                          </span>
                        </a>
                        <ul class="treeview-menu" style="white-space: normal;">
                          <?php
                          foreach ($cek->result() as $df) {
                            $cek2 = $this->db->query("SELECT * FROM ms_menu_sub_2 WHERE id_menu_sub = '$df->id_menu_sub'");
                            if ($cek2->num_rows() > 0) { ?>
                              <li style="white-space: normal;">
                                <a><?php echo $df->sub_name ?>
                                  <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                  </span>
                                </a>
                                <ul class="treeview-menu" style="white-space: normal;">
                                  <?php
                                  foreach ($cek2->result() as $key) {
                                    echo "<li><a href='h1/$key->sub_link'>$key->sub_name</a></li>";
                                  }
                                  ?>
                                </ul>
                              </li>
                            <?php
                            } else {
                            ?>
                              <li class="                          
                                <?php
                                if ($isi == $df->sub_link) {
                                  echo "active";
                                }
                                ?>
                                " style="white-space: normal;"><a href='h1/<?php echo $df->sub_link ?>'><?php echo $df->sub_name ?></a></li>
                          <?php
                            }
                          }
                          ?>
                        </ul>
                      </li>
                    <?php
                    } else {
                      $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$me->menu_link'")->row();
                    ?>
                      <li class="
                          <?php
                          if ($isi == $ql->menu_link) {
                            echo "active";
                          }
                          ?>
                          " style="white-space: normal;"><a href="h1/<?php echo $me->menu_link ?>"><?php echo $me->menu_name ?></a></li>
                  <?php
                    }
                  }
                  ?>
                </ul>
              </li>

            <?php
            }
            ?>
          </ul>
        </li>






        <li class="
              <?php
              $l = $this->db->query("SELECT MAX(id_menu_induk) as ju FROM ms_menu WHERE id_menu_header = '3'")->row();
              $i = $l->ju;
              $sql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk BETWEEN 16 AND '$i' AND id_menu_header = '3' AND status = '1' ORDER BY code ASC");
              foreach ($sql->result() as $m) {
                $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$m->menu_link' AND status = '1' ORDER BY code ASC");
                if ($ql->num_rows() > 0) {
                  $ql = $ql->row();
                  if ($isi == $ql->menu_link) {
                    echo "active";
                  } else {
                    echo "";
                  }
                }
              }
              ?>
              " style="white-space: normal;">
          <a>
            <i class="fa fa-database"></i> <span>H2</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu" style="white-space: normal;">

            <?php
            $menu_bagian = $this->db->query("SELECT DISTINCT(ms_menu_induk.menu_induk),ms_menu_induk.id_menu_induk FROM ms_menu INNER JOIN ms_menu_bagian 
                          ON ms_menu.id_menu_bagian=ms_menu_bagian.id_menu_bagian INNER JOIN ms_menu_induk
                          ON ms_menu.id_menu_induk=ms_menu_induk.id_menu_induk 
                          WHERE ms_menu.status = '1' AND id_menu_header = '3' ORDER BY ms_menu_induk.id_menu_induk,ms_menu.code ASC");
            foreach ($menu_bagian->result() as $menu) {
            ?>
              <!-- Demography -->
              <li class="                
                  <?php
                  $sql = $this->db->query("SELECT * FROM ms_menu 
                    WHERE ms_menu.id_menu_induk = '$menu->id_menu_induk' AND ms_menu.id_menu_header = '3' AND ms_menu.status = '1' ORDER BY code ASC");
                  foreach ($sql->result() as $m) {
                    $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND id_menu_header = '3' AND menu_link = '$m->menu_link' AND status = '1' ORDER BY code ASC");
                    if ($ql->num_rows() > 0) {
                      $ql = $ql->row();
                      if ($isi == $ql->menu_link) {
                        echo "active";
                      }
                    }
                  }
                  ?>
                  treeview" style="white-space: normal;">
                <a>
                  <span><?php echo $menu->menu_induk ?></span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu" style="white-space: normal;">
                  <?php
                  foreach ($sql->result() as $me) {

                    $cek = $this->db->query("SELECT * FROM ms_menu_sub WHERE id_menu = '$me->id_menu'");
                    if ($cek->num_rows() > 0) {
                      $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$me->menu_link' ORDER BY code ASC")->row();

                  ?>
                      <li class="
                          <?php
                          if ($isi == $ql->menu_link) {
                            echo "active";
                          }
                          ?>
                          " style="white-space: normal;">
                        <a><?php echo $me->menu_name ?>
                          <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                          </span>
                        </a>
                        <ul class="treeview-menu" style="white-space: normal;">
                          <?php
                          foreach ($cek->result() as $df) {
                            $cek2 = $this->db->query("SELECT * FROM ms_menu_sub_2 WHERE id_menu_sub = '$df->id_menu_sub'");
                            if ($cek2->num_rows() > 0) { ?>
                              <li style="white-space: normal;">
                                <a><?php echo $df->sub_name ?>
                                  <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                  </span>
                                </a>
                                <ul class="treeview-menu">
                                  <?php
                                  foreach ($cek2->result() as $key) {
                                    echo "<li><a href='h2/$key->sub_link'>$key->sub_name</a></li>";
                                  }
                                  ?>
                                </ul>
                              </li>
                            <?php
                            } else {
                            ?>
                              <li class="                          
                                <?php
                                if ($isi == $df->sub_link) {
                                  echo "active";
                                }
                                ?>
                                " style="white-space: normal;"><a href='h2/<?php echo $df->sub_link ?>'><?php echo $df->sub_name ?></a></li>
                          <?php
                            }
                          }
                          ?>
                        </ul>
                      </li>
                    <?php
                    } else {
                      $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$me->menu_link' ORDER BY code ASC");
                    ?>
                      <li class="
                          <?php
                          if ($ql->num_rows() > 0) {
                            $ql = $ql->row();
                            if ($isi == $ql->menu_link) {
                              echo "active";
                            }
                          }
                          ?>
                          "><a href="h2/<?php echo $me->menu_link ?>"><?php echo $me->menu_name ?></a></li>
                  <?php
                    }
                  }
                  ?>
                </ul>
              </li>

            <?php
            }
            ?>
          </ul>
        </li>

        <!--============================================== MENU H3 ===============================================================-->
        <li class="
              <?php
              $l = $this->db->query("SELECT MAX(id_menu_induk) as ju FROM ms_menu WHERE id_menu_header = '4'")->row();
              $i = $l->ju;
              $sql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk BETWEEN 100 AND '$i' AND id_menu_header = '4' AND status = '1'");
              foreach ($sql->result() as $m) {
                $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$m->menu_link' AND status = '1'")->row();
                if ($isi == $ql->menu_link) {
                  echo "active";
                } else {
                  echo "";
                }
              }
              ?>
              ">
          <a>
            <i class="fa fa-database"></i> <span>H3</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">

            <?php
            $menu_bagian = $this->db->query("SELECT DISTINCT(ms_menu_induk.menu_induk),ms_menu_induk.id_menu_induk FROM ms_menu INNER JOIN ms_menu_bagian 
                          ON ms_menu.id_menu_bagian=ms_menu_bagian.id_menu_bagian INNER JOIN ms_menu_induk
                          ON ms_menu.id_menu_induk=ms_menu_induk.id_menu_induk 
                          WHERE ms_menu.status = '1' AND id_menu_header = '4' ORDER BY ms_menu_induk.id_menu_induk,ms_menu.code ASC");
            foreach ($menu_bagian->result() as $menu) {
            ?>
              <!-- Demography -->
              <li class="                
                  <?php
                  $sql = $this->db->query("SELECT * FROM ms_menu WHERE ms_menu.id_menu_induk = '$menu->id_menu_induk' AND ms_menu.id_menu_header = '4' AND ms_menu.status = '1' ORDER BY ms_menu.code ASC ");
                  foreach ($sql->result() as $m) {
                    $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND id_menu_header = '4' AND menu_link = '$m->menu_link' AND status = '1'")->row();
                    if ($isi == $ql->menu_link) {
                      echo "active";
                    }
                  }
                  ?>
                  treeview">
                <a>
                  <span><?php echo $menu->menu_induk ?></span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <?php
                  foreach ($sql->result() as $me) {

                    $cek = $this->db->query("SELECT * FROM ms_menu_sub WHERE id_menu = '$me->id_menu'");
                    if ($cek->num_rows() > 0) {
                      $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$me->menu_link'")->row();

                  ?>
                      <li class="
                          <?php
                          if ($isi == $ql->menu_link) {
                            echo "active";
                          }
                          ?>
                          ">
                        <a><?php echo $me->menu_name ?>
                          <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                          </span>
                        </a>
                        <ul class="treeview-menu">
                          <?php
                          foreach ($cek->result() as $df) {
                            $cek2 = $this->db->query("SELECT * FROM ms_menu_sub_2 WHERE id_menu_sub = '$df->id_menu_sub'");
                            if ($cek2->num_rows() > 0) { ?>
                              <li>
                                <a><?php echo $df->sub_name ?>
                                  <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                  </span>
                                </a>
                                <ul class="treeview-menu">
                                  <?php
                                  foreach ($cek2->result() as $key) {
                                    echo "<li><a href='h3/$key->sub_link'>$key->sub_name</a></li>";
                                  }
                                  ?>
                                </ul>
                              </li>
                            <?php
                            } else {
                            ?>
                              <li class="                          
                                <?php
                                if ($isi == $df->sub_link) {
                                  echo "active";
                                }
                                ?>
                                "><a href='h3/<?php echo $df->sub_link ?>'><?php echo $df->sub_name ?></a></li>
                          <?php
                            }
                          }
                          ?>
                        </ul>
                      </li>
                    <?php
                    } else {
                      $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$me->menu_link'")->row();
                    ?>
                      <li class="
                          <?php
                          if ($isi == $ql->menu_link) {
                            echo "active";
                          }
                          ?>
                          "><a href="h3/<?php echo $me->menu_link ?>"><?php echo $me->menu_name ?></a></li>
                  <?php
                    }
                  }
                  ?>
                </ul>
              </li>

            <?php
            }
            ?>
          </ul>
        </li>
        <li class="
              <?php
              $l = $this->db->query("SELECT MAX(id_menu_induk) as ju FROM ms_menu WHERE id_menu_header = '4'")->row();
              $i = $l->ju;
              $sql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_header = '5' AND status = '1'");
              foreach ($sql->result() as $m) {
                $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$m->menu_link' AND status = '1'")->row();
                if ($isi == $ql->menu_link) {
                  echo "active";
                } else {
                  echo "";
                }
              }
              ?>
              ">
          <a>
            <i class="fa fa-database"></i> <span>HC3</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">

            <?php
            $menu_bagian = $this->db->query("SELECT DISTINCT(ms_menu_induk.menu_induk),ms_menu_induk.id_menu_induk FROM ms_menu INNER JOIN ms_menu_bagian 
                          ON ms_menu.id_menu_bagian=ms_menu_bagian.id_menu_bagian INNER JOIN ms_menu_induk
                          ON ms_menu.id_menu_induk=ms_menu_induk.id_menu_induk 
                          WHERE ms_menu.status = '1' AND id_menu_header = '5' ORDER BY ms_menu_induk.id_menu_induk,ms_menu.code ASC");
            foreach ($menu_bagian->result() as $menu) {
            ?>
              <!-- Demography -->
              <li class="                
                  <?php
                  $sql = $this->db->query("SELECT * FROM ms_menu WHERE ms_menu.id_menu_induk = '$menu->id_menu_induk' AND ms_menu.id_menu_header = '5' AND ms_menu.status = '1' ORDER BY ms_menu.code ASC ");
                  foreach ($sql->result() as $m) {
                    $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND id_menu_header = '5' AND menu_link = '$m->menu_link' AND status = '1'")->row();
                    if ($isi == $ql->menu_link) {
                      echo "active";
                    }
                  }
                  ?>
                  treeview">
                <a>
                  <span><?php echo $menu->menu_induk ?></span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <?php
                  foreach ($sql->result() as $me) {

                    $cek = $this->db->query("SELECT * FROM ms_menu_sub WHERE id_menu = '$me->id_menu'");
                    if ($cek->num_rows() > 0) {
                      $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$me->menu_link'")->row();

                  ?>
                      <li class="
                          <?php
                          if ($isi == $ql->menu_link) {
                            echo "active";
                          }
                          ?>
                          ">
                        <a><?php echo $me->menu_name ?>
                          <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                          </span>
                        </a>
                        <ul class="treeview-menu">
                          <?php
                          foreach ($cek->result() as $df) {
                            $cek2 = $this->db->query("SELECT * FROM ms_menu_sub_2 WHERE id_menu_sub = '$df->id_menu_sub'");
                            if ($cek2->num_rows() > 0) { ?>
                              <li>
                                <a><?php echo $df->sub_name ?>
                                  <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                  </span>
                                </a>
                                <ul class="treeview-menu">
                                  <?php
                                  foreach ($cek2->result() as $key) {
                                    echo "<li><a href='hc3/$key->sub_link'>$key->sub_name</a></li>";
                                  }
                                  ?>
                                </ul>
                              </li>
                            <?php
                            } else {
                            ?>
                              <li class="                          
                                <?php
                                if ($isi == $df->sub_link) {
                                  echo "active";
                                }
                                ?>
                                "><a href='hc3/<?php echo $df->sub_link ?>'><?php echo $df->sub_name ?></a></li>
                          <?php
                            }
                          }
                          ?>
                        </ul>
                      </li>
                    <?php
                    } else {
                      $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$me->menu_link'")->row();
                    ?>
                      <li class="
                          <?php
                          if ($isi == $ql->menu_link) {
                            echo "active";
                          }
                          ?>
                          "><a href="hc3/<?php echo $me->menu_link ?>"><?php echo $me->menu_name ?></a></li>
                  <?php
                    }
                  }
                  ?>
                </ul>
              </li>

            <?php
            }
            ?>
          </ul>
        </li>

        <li class="header">CONFIGURATION</li>


        <li class="
            <?php
            if ($isi == "setting" or $isi == "notification") {
              echo "active";
            }
            ?>
            ">
          <a><i class="fa fa-gear"></i>
            <span> General</span><i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="
                <?php
                if ($isi == "setting") {
                  echo "active";
                }
                ?>
                "><a href="panel/setting">Setting</a></li>
            <li class="
                <?php
                if ($isi == "notification") {
                  echo "active";
                }
                ?>
                "><a href="panel/notification">Notification</a></li>
          </ul>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
<?php
}
?>

<script>
  $(document).ready(function() {
    let active_link = '<?= $this->uri->segment(1); ?>/<?= $this->uri->segment(2); ?>';
    $("a[href='" + active_link + "']").parents('li').addClass('active');
    let tot_menu = <?= json_encode(isset($tot_menu) ? $tot_menu : []) ?>;
    for (tm of tot_menu) {
      if (tm.tot === 0) {
        $('#' + tm.key).hide();
      }
    }
  })

	$("#click_qas").on("click", function(){
		window.open('https://audit.sinarsentosaprimatama.com/jx07/ahmipdsh004-pst/login.htm', '_blank').focus();
	});
</script>