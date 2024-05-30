
<div class="content-wrapper">

<!-- Content Header (Page header) -->

<section class="content-header">

  <h1>

    <?php echo $title; ?>    

  </h1>

  <ol class="breadcrumb">

    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    

    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>

  </ol>

  </section>

  <section class="content">

    <div class="box box-default">

      <div class="box-header with-border">

        <h3 class="box-title">Your Account</h3>

        <div class="box-tools pull-right">

          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>

        </div>

      </div><!-- /.box-header -->

      <div class="box-body">

        <?php                       

        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    

        ?>                  

        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable" >

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
        
          <?php                       

        if (isset($_SESSION['warn']) && $_SESSION['warn'] <> '') {                    

        ?>                  

        <div class="alert alert-<?php echo $_SESSION['type'] ?> alert-dismissable" style="font-weight:normal;">

           <?php echo $_SESSION['warn'] ?>

            <button class="close" data-dismiss="alert">

                <span aria-hidden="true">&times;</span>

                <span class="sr-only">close</span>  

            </button>

        </div>

        <?php

        }

            $_SESSION['warn'] = '';                        

                

        ?>

        <div class="row">

          <div class="col-md-12">

            <form class="form-horizontal" action="panel/update_profil" method="post" enctype="multipart/form-data">

              <input name="id" type="hidden" value="<?php echo $this->session->userdata("id_user") ?>">
              <input name="sess" type="hidden" value="<?php echo $this->session->userdata("session_id") ?>">

              <div class="box-body">

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Nama</label>

                  <div class="col-sm-10">

                    <input type="text" class="form-control" id="nama" readonly value="<?php echo $this->session->userdata('nama'); ?>" name="nama">

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Username</label>

                  <div class="col-sm-10">

                    <?php 

                    $level = $this->session->userdata("level");

                    if($level=='administrator'){

                    ?>

                      <input type="text" class="form-control" id="username" value="<?php echo $this->session->userdata('username'); ?>" name="username">

                    <?php

                    }else{

                    ?>

                      <input type="text" readonly class="form-control" id="username" value="<?php echo $this->session->userdata('username'); ?>" name="username">

                    <?php } ?>

                  </div>

                </div>

                <?php

                $id_user = $this->session->userdata("id_user");

                $ambil = $this->db->query("SELECT * FROM ms_user INNER JOIN ms_user_group ON ms_user.id_user_group = ms_user_group.id_user_group 

                  WHERE id_user = '$id_user'")->row();

                ?>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">User Group</label>

                  <div class="col-sm-10">

                    <input type="text" class="form-control" id="inputEmail3" readonly value="<?php echo $ambil->user_group; ?>" name="level">

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>

                  <div class="col-sm-10">
                  <?php $dealer = $this->m_admin->getByID('ms_dealer','id_dealer',$this->m_admin->cari_dealer());
                  if ($dealer->num_rows()>0) {
                    $dealer= $dealer->row()->nama_dealer;
                  }else{
                    $dealer='';
                  }
                  ?>
                    <input type="text" class="form-control" id="inputEmail3" readonly value="<?php echo $dealer ?>" name="level">

                  </div>

                </div>

                <div class="form-group">
                   
                  <label for="inputPassword3" class="col-sm-2 control-label">Password</label>

                  <div class="col-sm-6">
                    <input type="password" class="form-control" id="inputPassword3" placeholder="Kosongkan bila tidak dirubah" name="password" onkeyup="handleKeyUp()">
                   
                  </div>
                   <div class="col-sm-4">
                    <a class="btn btn-default bg-maroon btn-flat" type="button" onclick="generated();"><i class="fa fa-cogs"></i> Generate</a>
                    <a class="btn btn-success btn-flat" type="button" onclick="showing();" id="btnShow"><i class="fa fa-eye"></i> Show Password </a>
                  </div>

                </div>   
                 <div class="form-group">

                  <label for="inputPassword3" class="col-sm-2 control-label"></label>

                  <div class="col-sm-10">

                  <p id="textValidation" style="color:#DD4B39;font-weight:bold"></p>
                     
                  </div>

                </div>
             

                <div class="form-group">

                  <label for="inputPassword3" class="col-sm-2 control-label">Avatar</label>

                  <div class="col-sm-10">

                    <input type="file" class="form-control" name="avatar" placeholder="Kosongkan bila tidak dirubah">

                  </div>

                </div>  

              </div><!-- /.box-body -->

              <div class="box-footer">

                <button type="submit" class="btn btn-info" id="myBtn">Save</button>

                <button type="button" class="btn btn-default pull-right" onClick="resetValidasi();">Reset</button>                

              </div><!-- /.box-footer -->

            </form>

          </div>

        </div>

      </div>

    </div><!-- /.box -->

  </section>

</div>
<script>
    function generate(Length){
         var result           = '';
         var besar            = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
         var kecil            = 'abcdefghijklmnopqrstuvwxyz';
         var angka            = '0123456789';
         var simbol           = "[|\\/~^:,;?!&%$@*+]";
         var besarLength = besar.length;
         var kecilLength = kecil.length;
         var angkaLength = angka.length;
         var simbolLength = besar.length;
            for ( var i = 0; i < 1; i++ ) {
                result += besar.charAt(Math.floor(Math.random() * besarLength));
            }
            for ( var i = 0; i < 1; i++ ) {
                result += angka.charAt(Math.floor(Math.random() * angkaLength));
            }
            for ( var i = 0; i < 1; i++ ) {
                result += kecil.charAt(Math.floor(Math.random() * kecilLength));
            }
            for ( var i = 0; i < 1; i++ ) {
                result += simbol.charAt(Math.floor(Math.random() * simbolLength));
            }
             for ( var i = 0; i < 1; i++ ) {
                result += besar.charAt(Math.floor(Math.random() * besarLength));
            }
             for ( var i = 0; i < 1; i++ ) {
                result += kecil.charAt(Math.floor(Math.random() * kecilLength));
            }
            for ( var i = 0; i < 1; i++ ) {
                result += angka.charAt(Math.floor(Math.random() * angkaLength));
            }
             for ( var i = 0; i < 1; i++ ) {
                result += simbol.charAt(Math.floor(Math.random() * simbolLength));
            }
          
         return result;
    }
   
    function generated(){
      document.getElementById("inputPassword3").value=generate(10);
      valid();
    }
    
    function valid(){
      
        var passLength = document.getElementById("inputPassword3").value;
        var textValidation = document.getElementById("textValidation");
        var namaUser = document.getElementById("nama").value;
        if(document.getElementById('inputPassword3').value.length < 8 ){
            document.getElementById("myBtn").disabled = true; 
            toastr.error(`Hai ${namaUser}, <br>Password kamu belum memenuhi kriteria, silahkan klik tombol <b>Generate</b> kembali ya`);
        }else if(document.getElementById('inputPassword3').value.length >= 8 ){
              document.getElementById("myBtn").disabled = false; 
              toastr.success(`Hai ${namaUser}, Password kamu memenuhi kriteria`);
        }
    }
    
    function resetValidasi(){
             document.getElementById("inputPassword3").value='';
             validationText ="";
             textValidation.style.color = "#DD4B39";
             textValidation.innerHTML  = validationText;
    }
    
    function showing(){
         var x = document.getElementById("inputPassword3");
         var z = document.getElementById("btnShow");
         var caption ="";
            if (x.type === "password") {
                 x.type = "text";
                 z.style.backgroundColor="#222B34";
                 caption ="<i class='fa fa-eye-slash'></i> Hide Password";
                 z.innerHTML = caption;
            } else {
                 x.type = "password";
                   z.style.backgroundColor="#008D4C";
                 caption ="<i class='fa fa-eye'></i> Show Password";
                 z.innerHTML = caption;
            }
    }
    
    
    function handleKeyUp(){
       
          var validationText="";
          var myInput = document.getElementById("inputPassword3");
          var myUsername = document.getElementById("username");
          var myInputVal = document.getElementById("inputPassword3").value.toUpperCase();
          var myUsernameVal = document.getElementById("username").value.toUpperCase();
          var textValidation = document.getElementById("textValidation");
          var namaUser = document.getElementById("nama").value;
          var isValid=false;
          
        //   validate password tidak boleh mengandung username !
          if(myInputVal.includes(myUsernameVal)){
             toastr.warning(`Hai ${namaUser}, Password kamu tidak boleh mengandung kata yang sama dengan username`);
              document.getElementById("myBtn").disabled = true; 
          }
          
          
          // Validate lowercase letters
          var lowerCaseLetters = /[a-z]/g;
          if(myInput.value.match(lowerCaseLetters)) {  
              
          } else {
              
            validationText +="Harus memiliki minimal 1 huruf kecil !<br>";
            textValidation.style.color = "#DD4B39";
            textValidation.innerHTML  =validationText;
            document.getElementById("myBtn").disabled = true; 
            
          }
          
          // Validate capital letters
          var upperCaseLetters = /[A-Z]/g;
          if(myInput.value.match(upperCaseLetters)) {  
           
          } else {
              
            validationText +="Harus memiliki minimal 1 huruf besar !<br>";
            textValidation.style.color = "#DD4B39";
            textValidation.innerHTML  =validationText;
            document.getElementById("myBtn").disabled = true; 
            
          }
          
          // Validate numbers
          var numbers = /[0-9]/g;
          if(myInput.value.match(numbers)) {  
              
            } else {
            
            validationText +="Harus memiliki minimal 1 angka !<br>";
            textValidation.style.color = "#DD4B39";
            textValidation.innerHTML  =validationText;
            document.getElementById("myBtn").disabled = true; 
            
          }
          
           // Validate symbols
          var simbol = "[|\\/~^:,;?!&%$@*+]";
          if(myInput.value.match(simbol)) { 
              
          } else {
              
            validationText +="Harus memiliki minimal 1 simbol !<br>";
            textValidation.style.color = "#DD4B39";
            textValidation.innerHTML  =validationText;
            document.getElementById("myBtn").disabled = true; 
            
          }
         
          // Validate length
          if(myInput.value.length < 8) {
            
            validationText +="Jumlah karakter minimal 8 digit !<br>";
            textValidation.style.color = "#DD4B39";
            textValidation.innerHTML  = validationText;
            document.getElementById("myBtn").disabled = true; 
          
          } else if(myInput.value.length >= 8 && myInput.value.match(simbol) && myInput.value.match(numbers) && myInput.value.match(lowerCaseLetters) && myInput.value.match(upperCaseLetters)) {
             
           
            if(myInputVal.includes(myUsernameVal)){
             toastr.warning(`Hai ${namaUser}, Password kamu tidak boleh mengandung kata yang sama dengan username`);
              document.getElementById("myBtn").disabled = true; 
            }else{
             validationText =`Hai ${namaUser}, Password kamu memenuhi kriteria.`;
             textValidation.style.color = "green";
             textValidation.innerHTML  = validationText;
             
            }
             
             isValid=true;
             if(isValid==true){
               document.getElementById("myBtn").disabled = false; 
             }else{
               document.getElementById("myBtn").disabled = true; 
             }
          }
          
          if(myInput.value.length == 0){
              
             document.getElementById("myBtn").disabled = false;
             validationText ="";
             textValidation.style.color = "#DD4B39";
             textValidation.innerHTML  = validationText;
          }
        
    }
</script>