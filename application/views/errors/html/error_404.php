<!--Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$ci = new CI_Controller();
$ci =& get_instance();
$ci->load->helper('url');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<base href="<?php echo base_url(); ?>" />	
<title>Page Not Found</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Smart Error Page Responsive, Login Form Web Template, Flat Pricing Tables, Flat Drop-Downs, Sign-Up Web Templates, Flat Web Templates, Login Sign-up Responsive Web Template, Smartphone Compatible Web Template, Free Web Designs for Nokia, Samsung, LG, Sony Ericsson, Motorola Web Design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- font files -->
<link href="//fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900" rel="stylesheet">
<link href="//fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
<!-- /font files -->
<!-- css files -->
<link href="assets/error/css/style.css" rel="stylesheet" type="text/css" media="all" />
<style type="text/css">
.buttonstyle 
{ 
background: white; 
background-position: 0px -401px; 
border: solid 1px #000000; 
color: #000000;
height: 30px;
margin-top: -1px;
padding-bottom: 2px;
}
.buttonstyle:hover {background: black;background-position: 0px -501px;color: #ffffff; }
</style>
<!-- /css files -->
<!-- js files -->

<!-- /js files -->
<body>
<div class="container demo-2">
	<div class="content">
        <div id="large-header" class="large-header">
			<h1 class="header-w3ls"></h1>
            <canvas id="demo-canvas"></canvas>
			<img src="assets/error/images/owl.gif" alt="flashy" class="w3l">
            <h2 class="main-title">404</span></h2>
			<p class="w3-agileits2" style="margin-left:-10px;">Maaf, kami tidak menemukan halaman yang anda cari.</p>
			<div class='button -dark center'>
			 	<p class="w3-agileits1" style="margin-left:14px;"><button onclick="back()" class="buttonstyle"><i class="fa fa-chevron-left">Kembali</i></button></p>
			</div>
        </div>
	</div>
</div>	
<!-- js files -->
<script src="assets/error/js/rAF.js"></script>
<script src="assets/error/js/demo-2.js"></script>
<!-- /js files -->
</body>
</html>

<script type="text/javascript">
function back() {
    window.history.back();
}
</script>