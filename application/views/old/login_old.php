<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>User Authentication</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="assets/panel/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/panel/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="assets/panel/plugins/iCheck/square/blue.css">    
    <!--link rel="shortcut icon" href="assets/web/images/<?php echo $r->favicon ?>"-->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>  

  <body background="assets/panel/images/bg.jpg">    
    <div class="login-box">
      <div class="login-logo">
        <a href="panel">
          <font color="white">
            <b>HONDA JAMBI</b>
          </font>
        </a>                
      </div><!-- /.login-logo -->
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
      <div class="login-box-body">
        <p class="login-box-msg" style="color: white;">Sign in to start your session</p>
        <form action="panel/login" autocomplete="off" method="post">
          <div class="form-group has-feedback">
            <input type="text" id="username" name="username" required class="form-control" placeholder="Username">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">            
            <input type="password" id="password" name="password" required class="form-control" placeholder="Password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <span><?php echo $img ?></span>
            <input type="text" name="kode" placeholder="Masukan Kode Keamanan" required="" class="form-control">                                                      
          </div>
          <div class="row">   
            <br>
            <div class="col-xs-6">
              <button type="submit" class="btn btn-block btn-primary btn-block btn-flat"> Sign In</button>              
            </div><!-- /.col -->                      
          </div>
        </form>      
        <br><br>
        <!--button class="btn btn-info btn-flat btn-block" data-toggle="modal" data-target="#forgot_pass">Forgot Your Password ?</button-->          
      </div><!-- /.login-box-body -->     
      
    </div><!-- /.login-box -->
    

      
    <!-- jQuery 2.1.4 -->
    <script src="assets/panel/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="assets/panel/bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="assets/panel/plugins/iCheck/icheck.min.js"></script> 
  </body>
</html>
