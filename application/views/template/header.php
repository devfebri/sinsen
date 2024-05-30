<!DOCTYPE html>
<html>

<head>
  <base href="<?php echo base_url(); ?>" />
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <?php
  // if((time() - $_SESSION['last_timestamp']) > 900) { // 60 * 2
  //   echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel/logout'>";      
  //   //echo "Sesi habis";
  // }

  // $cek = $this->db->query("SELECT * FROM ms_user WHERE status = 'online'");
  // foreach ($cek->result() as $isi) {
  //   $tgl1 = strtotime($isi->last_login_date);
  //   $tgl2 = strtotime(gmdate("Y-m-d h:i:s", time()+60*60*7));    
  //   $diff_secs = abs($tgl1 - $tgl2);
  //   $base_year = min(date("Y"), date("Y"));
  //   $diff   = mktime(0, 0, $diff_secs, 1, 1, $base_year);
  //   $hasil  = array( "years" => date("Y", $diff) - $base_year, "months_total" => (date("Y", $diff) - $base_year) * 12 + date("n", $diff) - 1, "months" => date("n", $diff) - 1, "days_total" => floor($diff_secs / (3600 * 24)), "days" => date("j", $diff) - 1, "hours_total" => floor($diff_secs / 3600), "hours" => date("G", $diff), "minutes_total" => floor($diff_secs / 60), "minutes" => (int) date("i", $diff), "seconds_total" => $diff_secs, "seconds" => (int) date("s", $diff) );
  //   if($hasil['minutes_total'] > 1440){
  //     $sq = $this->m_admin->getByID("ms_user","id_user",$isi->id_user)->row();   
  //     $tgl1 = $sq->last_login_date;
  //     $tgl2 = gmdate("Y-m-d h:i:s", time()+60*60*7);    
  //     $waktu = $this->m_admin->cari_waktu($tgl1,$tgl2);
  //     $dt['last_login_duration'] = $waktu['minutes'];
  //     $dt['status'] = "offline";
  //     $this->m_admin->update("ms_user",$dt,'id_user',$isi->id_user);
  //   }
  // }


  $jenis_user = $this->session->userdata("jenis_user");
  if ($jenis_user != "Admin" and $jenis_user != 'Super Admin') {
    $this->m_admin->cek_akses();
  }


  $id = $this->session->userdata("id_karyawan_dealer");
  $dealer = $this->db->query("SELECT * FROM ms_dealer 
            INNER JOIN ms_karyawan_dealer ON ms_dealer.id_dealer = ms_karyawan_dealer.id_dealer 
            WHERE id_karyawan_dealer = '$id'");
  //$dt_setting = $this->db->query("SELECT * FROM ms_setting WHERE id_dealer = '$dealer->id_dealer'");     
  if ($dealer->num_rows() > 0 && $jenis_user == "Dealer") {
    $s = $dealer->row();
    $fav = $s->favicon;
    $nama_dealer = $s->nama_dealer;
    $nama_kecil = $s->nama_kecil;
    $_SESSION['setting'] = "done";
  } else {
    $fav = "";
    $nama_dealer = "Honda";
    $nama_kecil = "H";
    $_SESSION['setting'] = "none";
  }



  $fol = $this->m_admin->getById("tabel_setting", "id_setting", 1)->row();
  $uploaddir  = $fol->lokasi_upload;
  $filename   = $uploaddir . '*.*';
  if (count(glob($filename)) > 0) {
    $r = count(glob($filename));
  } else {
    $r = "";
  }


  $r = $this->m_admin->cari_pos_dealer();


  ?>
  <?php if ($fav != '') { ?>
    <link rel="shortcut icon" href="assets/panel/images/<?php echo $fav ?>">
  <?php } ?>
  <title><?php echo $title; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="assets/panel/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->

  <link rel="stylesheet" href="assets/panel/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="assets/panel/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/panel/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="assets/panel/custom.css">
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
  <link rel="stylesheet" href="assets/panel/plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="assets/panel/plugins/daterangepicker/daterangepicker-bs3.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="assets/panel/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <link rel="stylesheet" href="assets/panel/plugins/select2/select2.min.css">
  <link rel="stylesheet" href="assets/panel/plugins/datatables/dataTables.bootstrap.css">
  <link rel="stylesheet" href="assets/panel/plugins/iCheck/all.css">
  <link rel="stylesheet" href="assets/toastr/toastr.css">
  <?php /*<link rel="stylesheet" href="assets/panel/fixedHeader/css/fixedHeader.dataTables.scss"> */ ?>
  <link rel="stylesheet" type="text/css" href="assets/fixheader/fixedHeader.dataTables.min.css">
  <script src="assets/panel/plugins/jQuery/jquery-2.2.3.min.js"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
        <script src="assets/panel/https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="assets/panel/https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  <?php
  function mata_uang($a)
  {
    if (is_numeric($a) and $a != 0 and $a != "") {
      return number_format($a, 2, ',', '.');
    } else {
      return $a;
    }
  }
  function mata_uang2($a)
  {
    if (is_numeric($a) and $a != 0 and $a != "") {
      return number_format($a, 0, ',', '.');
    } else {
      return $a;
    }
  }
  ?>

  <style type="text/css">
    <?php for ($i = 2; $i <= 5; $i++) { ?>#example<?php echo $i ?>tbody tr:nth-child(even) {
      background: #c5f3cc
    }

    #example<?php echo $i ?>tbody tr:nth-child(odd) {
      background: #FFF
    }

    #example<?php echo $i ?>thead tr th {
      background: #135f3f;
      color: white;
    }

    <?php } ?>.input-inline {
      display: inline;
      height: 30px;
      vertical-align: middle
    }
  </style>
    

</head>

<body oncontextmenu="return true;" class="hold-transition skin-red-light sidebar-mini">
  <style type="text/css">
    #loading-status {
      position: fixed;
      top: 50%;
      left: 50%;
      margin: -50px 0px 0px -50px;

      -moz-border-radius: 5px;
      -webkit-border-radius: 5px;
      z-index: 3000;
      display: none;
    }


    .disabled-select {
      background-color: #d5d5d5;
      opacity: 0.5;
      border-radius: 3px;
      cursor: not-allowed;
      position: absolute;
      top: 0;
      bottom: 0;
      right: 0;
      left: 0;
    }

    select[readonly].select2-hidden-accessible+.select2-container {
      pointer-events: none;
      touch-action: none;
    }

    select[readonly].select2-hidden-accessible+.select2-container .select2-selection {
      background: #eee;
      box-shadow: none;
    }

    select[readonly].select2-hidden-accessible+.select2-container .select2-selection__arrow,
    select[readonly].select2-hidden-accessible+.select2-container .select2-selection__clear {
      display: none;
    }

    .isi2 {
      height: 30px;
      padding-left: 4px;
      padding-right: 4px;
    }
  </style>
  <div id="loading-status">
    <table>
      <tr>
        <td><img src='<?php echo base_url("assets/panel/images/ajax-load.gif") ?>' class="img-responsive" style="width: 30%" /></td>
      </tr>
    </table>
  </div>
  <div id="top"></a>
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <a href="panel/home" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <!-- <span class="logo-mini"><b><?php echo $nama_kecil; ?></b></span> -->
          <span class="logo-mini"><img src="assets/panel/images/logo.png" width="90%"></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b><?php echo $nama_dealer ?></b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <?php

              // $log_directory = $uploaddir;
              // $results_array = array();

              //$files = scandir($uploaddir);


              //Output findings              
              ?>
              <li class="dropdown notifications-menu">
                <a href="assets/panel/#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-default jml_notif"></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header" style="font-weight: bold">Anda Mempunyai <span class="jml_notif"></span> Notifikasi Belum Dibaca</li>
                  <li>
                    <ul class="menu" id="container_notif">

                    </ul>
                  </li>
                  <li class="footer"><a style="background-color: #efefef" href="panel/all_notif">View all</a></li>
                </ul>
              </li>
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <?php
                  $id = $this->session->userdata("id_user");
                  $r = $this->db->query("SELECT * FROM ms_user WHERE id_user = '$id'")->row();

                  if ($r->avatar != "") {
                    echo "<img src='assets/panel/images/user/$r->avatar' class='user-image' alt='User Image'>";
                  } else {
                    echo "<img src='assets/panel/images/user/admin-lk.jpg' class='user-image' alt='User Image'>";
                  }
                  ?>

                  <span class="hidden-xs"><?php echo $this->session->userdata("nama") ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <?php
                    if ($r->avatar != "") {
                      echo "<img src='assets/panel/images/user/$r->avatar' class='img-circle' alt='User Image'>";
                    } else {
                      echo "<img src='assets/panel/images/user/admin-lk.jpg' class='img-circle' alt='User Image'>";
                    }
                    ?>
                    <!-- <img src="assets/panel/images/user/<?php echo $r->avatar ?>" class="img-circle" alt="User Image"> -->
                    <p>
                      <?php
                      echo $this->session->userdata("nama");
                      $sq = $this->m_admin->getByID("ms_user", "id_user", $this->session->userdata("id_user"))->row();
                      //echo $this->m_admin->cari_dealer();
                      ?>
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
              <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <script>
        $(document).ready(function() {
          //get_notif();
          //setTimeout(get_notif, 50000);
          //setInterval(get_notif, 50000); // The interval set to 20 seconds

        })

        // $(".btn_notif").click(function(event){
        function get(link) {
          var url = $(link).attr('href');
          id_notifikasi = $(link).attr('id_notifikasi');

          var values = {
            id_notifikasi: id_notifikasi,
            status: 'dibaca'
          };
          // console.log(values);
          $.ajax({
            url: "<?php echo site_url('panel/upd_notif_status'); ?>",
            type: "POST",
            cache: false,
            data: values,
            dataType: 'JSON',
            success: function(response) {
              if (response == 'sukses') {
                window.location = url
              }
            }
          });
        };

        function get_notif() {
          $.ajax({
            url: "<?php echo site_url('panel/get_notif'); ?>",
            cache: false,
            type: "POST",
            dataType: 'JSON',
            success: function(response) {
              showNotif(response);
            },
            complete: function() {
              // Schedule the next request when the current one's complete
              // setInterval(get_notif, 50000); // The interval set to 5 seconds
            }
          });
        }
        var popup_showed = [];

        function isInArray(value, array) {
          return array.indexOf(value) > -1;
        }

        function showNotif(response) {
          $("#container_notif").html('');
          $('.jml_notif').text(response.tot_notif);
          var html = '';
          for (rsp of response.data) {
            html += "<li><button style=' text-decoration:none;padding:0px;padding-left:15px;border-bottom:1px solid #red' onclick='get(this)' class='btn btn-link  btn_notif' id_notifikasi='" + rsp.id_notifikasi + "' href='" + rsp.link + "'>" + rsp.judul + "</button></li>";
            if (rsp.popup == 1) {
              if (isInArray(rsp.id_notifikasi, popup_showed)) {

              } else {
                toastr_success(rsp.pesan);
                popup_showed.push(rsp.id_notifikasi);
              }
            }
          }
          // console.log(popup_showed);
          $("#container_notif").append(html);
        }

        function toastr_success(pesan) {
          toastr.options = {
            "closeButton": true,
            "newestOnTop": true,
            "progressBar": true,
            "showDuration": 0,
            "hideDuration": 0,
            "timeOut": 10000,
            "extendedTimeOut": 10000,
          }
          toastr.success(pesan);
        }

        function toastr_error(pesan) {
          toastr.options = {
            "closeButton": true,
            "newestOnTop": true,
            "progressBar": true,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": 6000,
            "extendedTimeOut": 10000,
          }
          toastr.error(pesan);
        }

        function toastr_warning(pesan) {
          toastr.options = {
            "closeButton": true,
            "newestOnTop": true,
            "progressBar": true,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": 6000,
            "extendedTimeOut": 10000,
          }
          toastr.warning(pesan);
        }
      </script>