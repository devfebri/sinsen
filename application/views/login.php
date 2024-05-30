<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0055)http://portal2.ahm.co.id/jx02/ahmipdsh000-pst/login.htm -->
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes">
  <title>S.E.E.D.S - Login User</title>

  <script type="text/javascript" src="<?= base_url('assets/login') ?>/jQuery-2.1.3.min.js"></script>
  <script type="text/javascript" src="<?= base_url('assets/login') ?>/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?= base_url('assets/login') ?>/js.cookie.min.js"></script>
  <!--    <script type="text/javascript" src="resources/js/common.js"></script>-->

  <link href="<?= base_url('assets/login') ?>/common.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<?= base_url('assets/panel/bootstrap/css/bootstrap.min.css') ?>">
  <link href="<?= base_url('assets/login') ?>/bootstrap-theme.min.css" rel="stylesheet" type="text/css">

  <style>
    body {
      background-image: url("<?= base_url('assets/panel/images/bg_new.jpg') ?>");
      font-family: 'Segoe UI_', 'Segoe UI', 'Open Sans', Verdana, Arial, Helvetica, sans-serif;
    }

    @media handheld,
    only screen and (max-width: 480px),
    only screen and (max-device-width: 480px) {
      body {
        background-image: none;
      }

      .login {
        width: 100% !important;
        border: none;
        box-shadow: none;
        margin-top: 0 !important;
        /*margin: 0 !important;*/
        /* overriding */
      }

      .login .login-body {
        width: auto !important;
      }

      .btn-username {
        bottom: 0;
        color: #ccc;
        font-size: 18px;
        height: 18px;
        position: relative !important;
        right: -200px;
        top: -30px !important;
        z-index: 999;
      }

      .btn-password {
        bottom: 0;
        color: #ccc;
        font-size: 18px;
        height: 18px;
        position: relative !important;
        right: -200px;
        top: -30px !important;
        z-index: 999;
      }
    }
  </style>
</head>

<body data-gr-c-s-loaded="true">
  <div class="login">
    <div class="login-head">
      <div class="row" align="center">
        <div class="col-sm-12">
          <img src="<?= base_url('assets/panel/images/logo_seeds2.jpeg') ?>" style="height: 70px;" alt="">
        </div>
      </div>
    </div>
    <div class="login-body">
      <form class="form-horizontal" action="<?= base_url('panel/login') ?>" method="POST" autocomplete="off">
        <?php if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') { ?>
          <div class="form-group large-font" style="margin-bottom: 1px!important">
            <label class="control-label"></label>
            <div id="error-login" style="font-size: 11px!important;margin-bottom: 5px;"><span style="color: #900"><?php echo $_SESSION['pesan'] ?></span></div>
          </div>
        <?php }
        $_SESSION['pesan'] = '';
        ?>
        <div class="form-group large-font" style="margin-bottom: 0 !important">
          <input class="form-control" name="username" id="username" type="text" placeholder="Username" style="padding: 19px !important">
          <span class="glyphicon glyphicon-user btn-username"></span>
        </div>
        <div class="form-group large-font" style="margin-bottom: 0 !important">
          <input class="form-control" name="password" id="password" type="password" placeholder="Password" style="padding: 19px !important">
          <span class="glyphicon glyphicon-lock btn-password"></span>
        </div>
        <div class="form-group large-font" style="margin-bottom: 0 !important">           
          <span id="captImg"><?php echo $captchaImg; ?> </span> 
          <a href="#" class="reload-captcha refreshCaptcha btn btn-info btn-sm" ><i class="glyphicon glyphicon-refresh"></i></a>        
          <input class="form-control" name="kode" id="kode" type="text" placeholder="Masukkan Kode Keamanan" style="padding: 19px !important">
          <span class="glyphicon glyphicon-edit btn-username"></span>
        </div>
        <div class="form-group-button">
          <button type="submit" class="btn btn-login large-button" style="color: #fff"><b>SIGN IN</b></button>
        </div>
      </form>      
    </div>
    <div class="login-footer">
      <div id="helperLinks" class="copy-links" style="padding: 15px;">
        <center>

          <div style="color: #aaa; margin-top: 5px;">Copyright @ <? echo date('Y')?> <img src="<?= base_url('assets/panel/images/sinsen_logo.png') ?>" style="height: 30px;" alt="">
 All right reserved.</div>

        </center>
      </div>
    </div>
  </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- captcha refresh code -->
<script>
$(document).ready(function(){
    $('.refreshCaptcha').on('click', function(){
        $.get('<?php echo base_url().'panel/refresh'; ?>', function(data){
            $('#captImg').html(data);
        });
    });
});
</script>
</body>

</html>