
<!DOCTYPE html>
<html>
  <head>
    <base href="<?php echo base_url(); ?>" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php
    $id = $this->session->userdata("id_karyawan_dealer");
    $dealer = $this->db->query("SELECT ms_dealer.id_dealer,ms_dealer.nama_dealer FROM ms_dealer 
            INNER JOIN ms_karyawan_dealer ON ms_dealer.id_dealer = ms_karyawan_dealer.id_dealer 
            WHERE id_karyawan_dealer = '$id'")->row();
    $dt_setting = $this->db->query("SELECT * FROM ms_setting WHERE id_dealer = '$dealer->id_dealer'");     
    $s = $dt_setting->row(); 


    $fol = $this->m_admin->getById("tabel_setting","id_setting",1)->row();                           
    $uploaddir  = $fol->lokasi_upload;
    $filename   = $uploaddir.'*.*';
    if (count(glob($filename)) > 0) {
        $r = count(glob($filename));
    } else {
        $r = "";
    }

   
    ?>
    <link rel="shortcut icon" href="assets/panel/images/<?php echo $s->favicon ?>">
    <title><?php echo $title; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="assets/panel/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome --> 

    <link rel='stylesheet prefetch' href='assets/validation/daterange/jquery-ui.css'>

    <link rel="stylesheet" href="assets/panel/font-awesome/css/font-awesome.min.css">    
    <!-- Ionicons -->
    <link rel="stylesheet" href="assets/panel/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/panel/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="assets/panel/dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="assets/panel/plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="assets/panel/plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="assets/panel/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <!-- Daterange picker -->
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="assets/panel/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <link rel="stylesheet" href="assets/panel/plugins/select2/select2.min.css">
    <link rel="stylesheet" href="assets/panel/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="assets/panel/plugins/iCheck/all.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="assets/panel/https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="assets/panel/https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php 
    function mata_uang($a){
        return number_format($a, 2, ',', '.');
    }
    ?>
   
  </head>
  <?php 
  $group = $this->session->userdata('group');
  if($group == '1'){ ?>
  <body class="hold-transition skin-blue sidebar-mini">
  <?php }elseif($group == '2'){ ?>
  <body class="hold-transition skin-green sidebar-mini">
  <?php
  }else{
  ?>
  <body class="hold-transition skin-red sidebar-mini">
  <?php } ?>
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <a href="panel/home" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b><?php echo $s->nama_kecil; ?></b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b><?php echo $dealer->nama_dealer ?></b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>

          <div class="navbar-custom-menu">
            
            <ul class="nav navbar-nav">
              <?php 
              $group = $this->session->userdata('group');
              if($group == '1'){
              
              
              // $log_directory = $uploaddir;
              // $results_array = array();

              $files = scandir($uploaddir);
              

              //Output findings              
              ?>
              <li class="dropdown notifications-menu">
                <a href="assets/panel/#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-danger"><?php echo $r ?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have <?php echo $r ?> incoming files</li>
                  <li>
                    
                    <ul class="menu">
                      <?php 
                      foreach($files as $value)
                      {
                        if($value != "." AND $value != ".."){                         
                          echo "
                          <li>
                            <a href='panel/notification'>
                              <i class='fa fa-file text-red'></i>  $value
                            </a>
                          </li> 
                          ";
                        }
                      }
                      ?>                                          
                    </ul>
                  </li>
                  <li class="footer"><a href="panel/notification">View all</a></li>
                </ul>
              </li>
              <?php } ?>
              

             

              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <?php
                  $id = $this->session->userdata("id_user");
                  $r = $this->db->query("SELECT * FROM ms_user WHERE id_user = '$id'")->row();
                  ?>
                  <img src="assets/panel/images/user/<?php echo $r->avatar ?>" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?php echo $this->session->userdata("nama") ?></span>
                </a>
                <ul class="dropdown-menu">                
                  <!-- User image -->
                  <li class="user-header">
                    <img src="assets/panel/images/user/<?php echo $r->avatar ?>" class="img-circle" alt="User Image">
                    <p>
                      <?php 
                      echo $this->session->userdata("nama"); 
                      $sq = $this->m_admin->getByID("ms_user","id_user",$this->session->userdata("id_user"))->row();
                      ?>
                      <small>Registered since <?php echo $sq->input_date ?></small>
                    </p>
                  </li>
                  <!-- Menu Body -->                  
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="panel/profil?id=<?php echo $this->session->userdata('id_user') ?>" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="panel/logout" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              
            </ul>
          </div>
        </nav>
      </header>
      
