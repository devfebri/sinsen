<style>
.wel_container {
  position: relative;
  text-align: center;
  color: white;
}

.wel_bottom-left {
  position: absolute;
  bottom: 8px;
  left: 16px;
}

.wel_top-left {
  position: absolute;
  top: 8px;
  left: 16px;
}

.wel_top-right {
  position: absolute;
  top: 8px;
  right: 16px;
}

.wel_bottom-right {
  position: absolute;
  bottom: 8px;
  right: 16px;
}

.wel_centered {
  position: absolute;
  top: 60%;
  left: 50%;
  transform: translate(-50%, -50%);
  color:black;
}

div.wel_scroll {
  //background-color: lightblue;
  height: 35%;
  width: 50%;  
  overflow-y: auto;
  margin-top:8.5%;
}

@font-face {
	font-family: "Champagne"; 
	src:url("assets/fonts/Champagne/font-face/Champagne.eot");
	src: url("assets/fonts/Champagne/font-face/Champagne.eot?#iefix") format("embedded-opentype"), 
	url("assets/fonts/Champagne/font-face/Champagne.woff2") format("woff2"), 
	url("assets/fonts/Champagne/font-face/Champagne.woff") format("woff"), 
	url("assets/fonts/Champagne/font-face/Champagne.ttf") format("truetype"), 
	url("assets/fonts/Champagne/font-face/Champagne.svg#Champagne & Limousines") format("svg"); 
}

.customCL_font {
  font-family: Champagne;
  font-size : 16px;
}

</style>

<div class="content-wrapper">
	<div class="form-group">    
	<div class="row" >
		<!-- <img style="width:100%;height:100%;" src= "assets/panel/images/welcome.jpeg"> -->

	<div class="wel_container">
	  <img src="assets/panel/images/welcome.jpeg" alt="Snow" style="width:100%;">
  		<div class="wel_centered wel_scroll text-left customCL_font">
		<?php
			if($announcement != false){
				foreach($announcement as $row){
					$date=date_create($row->tgl_aktif);
					echo date_format($date,"d M Y").' - <a data-toggle="modal" data-target="#modal-edit'. $row->id .'" data-popup="tooltip"><b>';         
					echo $row->perihal.'</b></a><br>';
?>
<?php
				}
			}		
		?>
		</div>
	</div>
	</div>
     	</div> 

  <section class="">      
</div>

<?php
	if($announcement != false){
		foreach($announcement as $row){
?>
			<div class="row">
				<div id="modal-edit<?=$row->id;?>" class="modal fade">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header bg-warning">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<?php /* <h4 class="modal-title"><label><?php echo $row->perihal; ?></label></h4> */ ?>
								<label class='col-md-12'><?php $date=date_create($row->tgl_aktif); echo date_format($date,"d F Y"); ?></label>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<div class='col-md-12'><?php echo $row->isi; ?></div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			</div>
<?php 
	} 
}
?>


<div class="modal fade" id="mdlCheckLogin" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> PEMBERITAHUAN</h4>
            </div>
            <div class="modal-body">
                <p id="psn_pemberitahuan"></p>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
            </div>
        </div>

    </div>
</div>

<script>

$( document ).ready(function() {
	$.ajax({
        url: '<?php echo base_url() ?>panel/cek_ganti_password',
        type: 'GET',
        dataType: 'JSON',
    })
    .done(function(a) {
        console.log("success");

        if (a.status == '1') {
            $("#psn_pemberitahuan").html(a.pesan);
            $('#mdlCheckLogin').modal({
                backdrop: 'static',
                keyboard: false
            });
        } else if (a.status == '2') {
            $(".close").hide();
            $("#psn_pemberitahuan").html(a.pesan);
            $('#mdlCheckLogin').modal({
                backdrop: 'static',
                keyboard: false
            });
        }

    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
});
</script>

<?php 
	if(isset($is_aging_indent_dealer)){
		if($is_aging_indent_dealer > 0){
	?>
		<script>
			alert ("Mohon untuk dapat segera follow up kembali <?php echo $is_aging_indent_dealer;?> customer indent yang sudah melewati > 60 hari");
		</script>
	<?php	
		}
	}
?>
