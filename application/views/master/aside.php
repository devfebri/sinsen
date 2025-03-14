<?php 
$group = $this->session->userdata('group');
$jenis_user = $this->session->userdata('jenis_user');
if($jenis_user == "Main Dealer"){
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
              if($r->avatar != ""){
                echo "<img src='assets/panel/images/user/$r->avatar' width='18px' class='img-circle' alt='User Image'>";                
              }else{
                echo "<img src='assets/panel/images/user/admin-lk.jpg' width='18px' class='img-circle' alt='User Image'>";                                
              }
              ?>
            </div>
            <div class="pull-left info">
              <p>
                <?php echo $this->session->userdata('nama');  ?>
              </p>
              <a href="panel/home"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>  
          <br>      
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="
            <?php 
            if($isi=="home"){
              echo "active";
            } 
            ?>
            "><a href="panel/home"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
            
            <li class="header">REFERENCES</li>  
            
            <!-- Master Data -->
            <li class="
              <?php 
              $l = $this->db->query("SELECT COUNT(id_menu) as ju FROM ms_menu WHERE id_menu_header = '1'")->row();                                                                                   
              $i = $l->ju;
              $sql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk BETWEEN 1 AND '$i' AND id_menu_header = '1'");                                                                     
              foreach ($sql->result() as $m) {
                $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$m->menu_link' AND status = '1'")->row();                                                                           
                if(count($ql) > 0){
                  if($isi==$ql->menu_link){
                    echo "active";
                  }
                } 
              }            
              ?>
              ">
              <a>
                <i class="fa fa-database"></i> <span>Master Data</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">

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
                    AND ms_user_access_level.id_user_group = '$group' AND ms_user_access_level.can_select = '1'");                                                         
                  foreach ($sql->result() as $m) {
                    $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND id_menu_header = '1' AND menu_link = '$m->menu_link' AND status = '1'")->row();                                                                           
                    if($isi==$ql->menu_link){
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
                      $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$me->menu_link'")->row();                                                           
                    ?>                    
                      <li class="
                      <?php 
                      if($isi==$ql->menu_link){
                        echo "active";
                      } 
                      ?>
                      "><a href="master/<?php echo $me->menu_link ?>"><?php echo $me->menu_name ?></a></li>                                      
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
            <!--============================================== MENU H1 ===============================================================-->
            <li class="
              <?php 
              $l = $this->db->query("SELECT MAX(id_menu_induk) as ju FROM ms_menu WHERE id_menu_header = '2'")->row();                                                                                   
              $i = $l->ju;
              $sql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk BETWEEN 16 AND '$i' AND id_menu_header = '2' AND status = '1'");                                                                     
              foreach ($sql->result() as $m) {
                $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$m->menu_link' AND status = '1'")->row();                                                                           
                if($isi==$ql->menu_link){
                  echo "active";
                }else{
                  echo "";
                }
              }            
              ?>
              ">
              <a>
                <i class="fa fa-database"></i> <span>H1</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">

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
                  $sql = $this->db->query("SELECT * FROM ms_menu INNER JOIN ms_user_access_level ON ms_menu.id_menu=ms_user_access_level.id_menu 
                    WHERE ms_menu.id_menu_induk = '$menu->id_menu_induk' AND ms_menu.id_menu_header = '2' AND ms_menu.status = '1'
                    AND ms_user_access_level.id_user_group = '$group' AND ms_user_access_level.can_select = '1'");                                                         
                  foreach ($sql->result() as $m) {
                    $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND id_menu_header = '2' AND menu_link = '$m->menu_link' AND status = '1'")->row();                                                                           
                    if($isi==$ql->menu_link){
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
                      if($cek->num_rows() > 0){ 
                        $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$me->menu_link'")->row();                                                           

                        ?>
                        <li class="
                          <?php 
                          if($isi==$ql->menu_link){
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
                              if($cek2->num_rows() > 0){ ?>
                                <li>
                                  <a><?php echo $df->sub_name ?>
                                    <span class="pull-right-container">
                                      <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                  </a>
                                  <ul class="treeview-menu">
                                    <?php 
                                    foreach ($cek2->result() as $key) {
                                      echo "<li><a href='h1/$key->sub_link'>$key->sub_name</a></li>";
                                    }
                                    ?>                                    
                                  </ul>
                                </li>
                              <?php 
                              }else{
                              ?>
                                <li class="                          
                                <?php
                                if($isi==$df->sub_link){
                                  echo "active";
                                } 
                                ?>
                                "><a href='h1/<?php echo $df->sub_link ?>'><?php echo $df->sub_name ?></a></li>
                              <?php 
                              }                              
                            }
                            ?>                            
                          </ul>
                        </li>
                          <?php  
                      }else{ 
                        $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$me->menu_link'")->row();                                                           
                        ?>                    
                          <li class="
                          <?php 
                          if($isi==$ql->menu_link){
                            echo "active";
                          } 
                          ?>
                          "><a href="h1/<?php echo $me->menu_link ?>"><?php echo $me->menu_name ?></a></li>                                      
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
            if($isi=="setting" or $isi=="notification"){
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
                if($isi=="setting"){
                  echo "active";
                } 
                ?>
                "><a href="panel/setting">Setting</a></li>
                <li class="
                <?php 
                if($isi=="notification"){
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
}elseif($jenis_user == 'Dealer'){
?>

      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">  
          <div class="user-panel">
            <div class="pull-left image">
              <?php               
              $id = $this->session->userdata('id_user'); 
              $r  = $this->db->query("SELECT * FROM ms_user WHERE id_user = '$id'")->row();                                 
              if($r->avatar != ""){
                echo "<img src='assets/panel/images/user/$r->avatar' width='18px' class='img-circle' alt='User Image'>";                
              }else{
                echo "<img src='assets/panel/images/user/admin-lk.jpg' width='18px' class='img-circle' alt='User Image'>";                                
              }
              ?>
            </div>
            <div class="pull-left info">
              <p>
                <?php echo $this->session->userdata('nama');  ?>
              </p>
              <a href="panel/home"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>  
          <br>      
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="
            <?php 
            if($isi=="home"){
              echo "active";
            } 
            ?>
            "><a href="panel/home"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
                        

            <li class="header">MAIN NAVIGATION</li>

            <?php 
            $menu_bagian = $this->db->query("SELECT DISTINCT(ms_menu_induk.menu_induk),ms_menu_induk.id_menu_induk FROM ms_menu INNER JOIN ms_menu_bagian 
                        ON ms_menu.id_menu_bagian=ms_menu_bagian.id_menu_bagian INNER JOIN ms_menu_induk
                        ON ms_menu.id_menu_induk=ms_menu_induk.id_menu_induk INNER JOIN ms_user_access_level
                        ON ms_menu.id_menu = ms_user_access_level.id_menu
                        WHERE ms_menu.status = '1' AND ms_menu.id_menu_header = '7' AND ms_user_access_level.id_user_group = '$group' 
                        ORDER BY ms_menu_induk.id_menu_induk ASC");                                                                       
            foreach ($menu_bagian->result() as $menu) {                
            ?>

              <li class="
              <?php
              $sql = $this->db->query("SELECT * FROM ms_menu INNER JOIN ms_user_access_level ON ms_menu.id_menu=ms_user_access_level.id_menu 
                WHERE ms_menu.id_menu_induk = '$menu->id_menu_induk' AND ms_menu.id_menu_header = '7' AND ms_menu.status = '1' 
                AND ms_user_access_level.id_user_group = '$group' AND ms_user_access_level.can_select = '1' ORDER BY ms_menu.code ASC");                                                         
              foreach ($sql->result() as $m) {
                $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND id_menu_header = '7' AND menu_link = '$m->menu_link' AND status = '1'")->row();                                                                           
                if($isi==$ql->menu_link){
                  echo "active";
                } 
              }               
              ?> 
              treeview">
                <a><i class="fa fa-database"></i> 
                  <span><?php echo $menu->menu_induk ?></span>
                  <i class="fa fa-angle-left pull-right"></i>                  
                </a>
                <ul class="treeview-menu">
                  <?php 
                  foreach ($sql->result() as $me) {                                                            
                    $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$menu->id_menu_induk' AND menu_link = '$me->menu_link'")->row();                                                           
                    ?>
                  <li class="
                  <?php 
                  if($isi==$ql->menu_link){
                    echo "active";
                  } 
                  ?>
                  "><a href="dealer/<?php echo $me->menu_link ?>"><?php echo $me->menu_name ?></a></li>
                  <?php } ?>                  
                </ul>
              </li>
            
            <?php 
            }
            ?>


          <!--li class="header">CONFIGURATION</li>
            <li class="
            <?php 
            if($isi=="setting" or $isi=="notification"){
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
                if($isi=="setting"){
                  echo "active";
                } 
                ?>
                "><a href="panel/setting">Setting</a></li>
                <li class="
                <?php 
                if($isi=="notification"){
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
}elseif($jenis_user == 'Admin' OR $jenis_user == 'Super Admin'){
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
              if($r->avatar != ""){
                echo "<img src='assets/panel/images/user/$r->avatar' width='18px' class='img-circle' alt='User Image'>";                
              }else{
                echo "<img src='assets/panel/images/user/admin-lk.jpg' width='18px' class='img-circle' alt='User Image'>";                                
              }
              ?>
            </div>
            <div class="pull-left info">
              <p>
                <?php echo $this->session->userdata('nama');  ?>
              </p>
              <a href="panel/home"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>  
          <br>      
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="
            <?php 
            if($isi=="home"){
              echo "active";
            } 
            ?>
            "><a href="panel/home"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
            
            <li class="header">REFERENCES</li>  
            
            <!-- Master Data -->
            <li class="
              <?php 
              $l = $this->db->query("SELECT COUNT(id_menu) as ju FROM ms_menu WHERE id_menu_header = '1'")->row();                                                                                   
              $i = $l->ju;
              $sql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk BETWEEN 1 AND '$i' AND id_menu_header = '1'");                                                                     
              foreach ($sql->result() as $m) {
                $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$m->menu_link' AND status = '1'")->row();                                                                           
                if(count($ql) > 0){
                  if($isi==$ql->menu_link){
                    echo "active";
                  }
                } 
              }            
              ?>
              ">
              <a>
                <i class="fa fa-database"></i> <span>Master Data</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">

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
                    if($isi==$ql->menu_link){
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
                      $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$me->menu_link'")->row();                                                           
                    ?>                    
                      <li class="
                      <?php 
                      if($isi==$ql->menu_link){
                        echo "active";
                      } 
                      ?>
                      "><a href="master/<?php echo $me->menu_link ?>"><?php echo $me->menu_name ?></a></li>                                      
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
              $sql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk BETWEEN 16 AND '$i' AND id_menu_header = '2' AND status = '1'");                                                                     
              foreach ($sql->result() as $m) {
                $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$m->menu_link' AND status = '1'")->row();                                                                           
                if($isi==$ql->menu_link){
                  echo "active";
                }else{
                  echo "";
                }
              }            
              ?>
              ">
              <a>
                <i class="fa fa-database"></i> <span>H1</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">

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
                    if($isi==$ql->menu_link){
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
                      if($cek->num_rows() > 0){ 
                        $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$me->menu_link'")->row();                                                           

                        ?>
                        <li class="
                          <?php 
                          if($isi==$ql->menu_link){
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
                              if($cek2->num_rows() > 0){ ?>
                                <li>
                                  <a><?php echo $df->sub_name ?>
                                    <span class="pull-right-container">
                                      <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                  </a>
                                  <ul class="treeview-menu">
                                    <?php 
                                    foreach ($cek2->result() as $key) {
                                      echo "<li><a href='h1/$key->sub_link'>$key->sub_name</a></li>";
                                    }
                                    ?>                                    
                                  </ul>
                                </li>
                              <?php 
                              }else{
                              ?>
                                <li class="                          
                                <?php
                                if($isi==$df->sub_link){
                                  echo "active";
                                } 
                                ?>
                                "><a href='h1/<?php echo $df->sub_link ?>'><?php echo $df->sub_name ?></a></li>
                              <?php 
                              }                              
                            }
                            ?>                            
                          </ul>
                        </li>
                          <?php  
                      }else{ 
                        $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$me->menu_link'")->row();                                                           
                        ?>                    
                          <li class="
                          <?php 
                          if($isi==$ql->menu_link){
                            echo "active";
                          } 
                          ?>
                          "><a href="h1/<?php echo $me->menu_link ?>"><?php echo $me->menu_name ?></a></li>                                      
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
              $sql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk BETWEEN 16 AND '$i' AND id_menu_header = '3' AND status = '1'");                                                                     
              foreach ($sql->result() as $m) {
                $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$m->menu_link' AND status = '1'")->row();                                                                           
                if($isi==$ql->menu_link){
                  echo "active";
                }else{
                  echo "";
                }
              }            
              ?>
              ">
              <a>
                <i class="fa fa-database"></i> <span>H2</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">

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
                    WHERE ms_menu.id_menu_induk = '$menu->id_menu_induk' AND ms_menu.id_menu_header = '3' AND ms_menu.status = '1'");                                                         
                  foreach ($sql->result() as $m) {
                    $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND id_menu_header = '3' AND menu_link = '$m->menu_link' AND status = '1'")->row();                                                                           
                    if($isi==$ql->menu_link){
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
                      if($cek->num_rows() > 0){ 
                        $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$me->menu_link'")->row();                                                           

                        ?>
                        <li class="
                          <?php 
                          if($isi==$ql->menu_link){
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
                              if($cek2->num_rows() > 0){ ?>
                                <li>
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
                              }else{
                              ?>
                                <li class="                          
                                <?php
                                if($isi==$df->sub_link){
                                  echo "active";
                                } 
                                ?>
                                "><a href='h2/<?php echo $df->sub_link ?>'><?php echo $df->sub_name ?></a></li>
                              <?php 
                              }                              
                            }
                            ?>                            
                          </ul>
                        </li>
                          <?php  
                      }else{ 
                        $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$me->menu_link'")->row();                                                           
                        ?>                    
                          <li class="
                          <?php 
                          if($isi==$ql->menu_link){
                            echo "active";
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
                if($isi==$ql->menu_link){
                  echo "active";
                }else{
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
                  $sql = $this->db->query("SELECT * FROM ms_menu WHERE ms_menu.id_menu_induk = '$menu->id_menu_induk' AND ms_menu.id_menu_header = '4' AND ms_menu.status = '1'");                                                         
                  foreach ($sql->result() as $m) {
                    $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND id_menu_header = '4`' AND menu_link = '$m->menu_link' AND status = '1'")->row();                                                                           
                    if($isi==$ql->menu_link){
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
                      if($cek->num_rows() > 0){ 
                        $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$me->menu_link'")->row();                                                           

                        ?>
                        <li class="
                          <?php 
                          if($isi==$ql->menu_link){
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
                              if($cek2->num_rows() > 0){ ?>
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
                              }else{
                              ?>
                                <li class="                          
                                <?php
                                if($isi==$df->sub_link){
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
                      }else{ 
                        $ql = $this->db->query("SELECT * FROM ms_menu WHERE id_menu_induk = '$m->id_menu_induk' AND menu_link = '$me->menu_link'")->row();                                                           
                        ?>                    
                          <li class="
                          <?php 
                          if($isi==$ql->menu_link){
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

            <li class="header">CONFIGURATION</li>


            <li class="
            <?php 
            if($isi=="setting" or $isi=="notification"){
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
                if($isi=="setting"){
                  echo "active";
                } 
                ?>
                "><a href="panel/setting">Setting</a></li>
                <li class="
                <?php 
                if($isi=="notification"){
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