<style type="text/css">
.larger-swal-modal {
    /* width: 50%;  */
}
.progress-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 900px;
    margin: 0 auto;
}

.step {
    position: relative;
    width: 30px;
    height: 30px;
    background-color: #ccc;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
}

.active-step {
    background-color: #007bff;
    color: #fff;
}

.active-step-finish {
    background-color:#6bc167;
    color: #fff;
}

.step-line {
    flex-grow: 1;
    height: 4px;
    background-color: #ccc;
    margin-bottom:22px;
}

.step-text {
    text-align: center;
    margin-top: 10px;
}


 .form-group{
  margin :5px;
 }
 .green-cell {
    color: red;
}


.c-pill {
	align-items: center;
	font-family: "Open Sans", Arial, Verdana, sans-serif;
	font-weight: bold;
	font-size: 11px;
	display: inline-block;
	height: 100%;
	white-space: nowrap;
	width: auto;

	position: relative;
	border-radius: 100px;
	line-height: 1;
	overflow: hidden;
	padding: 0px 12px 0px 20px;
	text-overflow: ellipsis;
	line-height: 1.25rem;
	color: #595959;

	word-break: break-word;
}

	&:before {
		border-radius: 50%;
		content: "";
		height: 10px;
		left: 6px;
		margin-top: -5px;
		position: absolute;
		top: 50%;
		width: 10px;
}

.c-pill--success {
	background: #b4eda0;
}
.c-pill--success:before {
	background: #6bc167;
}
.c-pill--warning {
	background: #ffebb6;
}
.c-pill--warning:before {
	background: #ffc400;
}
.c-pill--danger {
	background: #ffd5d1;
}
.c-pill--danger:before {
	background: #ff4436;
}

</style>

<base href="<?php echo base_url(); ?>" />
    <?php 
    if($set=="view"){
    ?>

<body onload="select_kelurahan_combobox();" onload="select_ajax_combobox();">

<div class="content-wrapper">
  <section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>

  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Perubahan Memo</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>

  <section class="content">
  <div class="box box-default">
      <div class="box-header with-border">        
        <div class="row">
          <div class="col-md-12">
            <div class="box-body">   
              <div class="form-group">
                  <label for="inputEmail3" class="col-sm-1 control-label">Pencarian</label>
                  <div class="col-sm-2">
                  <input type="text" name="pencarian" id="no_mesin_pencarian_spk" value="KD11E1435002" class="form-control pencarian_ajax class_no_mesin_pencarian_spk"  placeholder="NO Mesin / NO SPK" >
                  </div>  
                  <div class="col-sm-2">
                     <button class="btn btn-primary" id="resetButton" value="resetButton"  onclick="pencarian()" data-toggle="tooltip" title="Search Nomor Mesin"  > <i class="fa fa-search" aria-hidden="true"></i></button>
                     <button class="btn btn-default"  onclick="location.reload();"  data-toggle="tooltip" title="Reload Page" > <i class="fa fa-refresh" aria-hidden="true"></i></button>
                    <!-- memo history -->
                     <!-- <button class="btn btn-warning"  data-toggle="tooltip" title="Tampilkan Informasi Perubahan Data" data-toggle="modal" data-target="#myModalInfo" >
                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                      </button> -->
                      <!-- <button  class="btn btn-default"  data-toggle="tooltip" title="History Edit" title="Tampilkan Informasi Perubahan Data" onclick="getDataHistoryTable()"><i class="fa fa-history" aria-hidden="true"></i></button> -->
                    </div>  
            </div>   
          </div>
        </div>
      </div>
  </div>
  

  <div class="box box-default">
      <div class="box-header with-border">      
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
              <!-- <button class="btn btn-primary" id="resetButton" value="resetButton" onclick="handleButtonClick(this)" title="Manual Check Data"> <i class="fa fa-window-maximize" aria-hidden="true"></i></button> -->
              <button class="btn btn-warning" id="showallButton" value="showallButton" onclick="handleButtonClick(this)"> <i class="fa fa-window-maximize" aria-hidden="true"></i></button>
              <button class="btn btn-primary" id="alamatButton" value="alamat" onclick="handleButtonClick(this)">Alamat</button>
              <button class="btn btn-primary" id="namaKonsumenButton" value="namaKonsumen" onclick="handleButtonClick(this)">Nama Konsumen</button>
               <button class="btn btn-primary" id="gantiIdentitasButton" value="gantiIdentitas" onclick="handleButtonClick(this)">Ganti Identitas</button>
              <!-- <button class="btn btn-warning" id="gantiPOButton" value="gantiPO" onclick="handleButtonClick(this)">Ganti PO</button>
              <button class="btn btn-primary" id="manualEditButton" value="manualEdit" onclick="handleButtonClick(this)">Manual Edit (Table)</button> -->
            </div>

            <hr>

             <div class="col-md-12 col-sm-12 col-xs-12">

            <hr>
         
        <div class="progress-container">
          
                <div class="col-1 text-center">
                <div class="col-3 step" id="pro_set_active">
                <i class="fa fa-database" aria-hidden="true"  data-toggle="tooltip" title="Prospek"></i>
                </div>
                <p>PRO</p>
            </div>

            <div class="col-1 step-line credit-hide"></div>
            <div class="col-3 text-center credit-hide">
                <div class="step " id="sk_set_active"  data-toggle="tooltip" title="Skema Kredit (kredit)">
                <i class="fa fa-database" aria-hidden="true"></i>
                </div>
                <p >SKEM</p>
            </div>
        
            <div class="col-1 step-line credit-hide"></div>
            <div class="col-3 text-center credit-hide">
                <div class="step " id="os_set_active"  data-toggle="tooltip" title="Order Survey (kredit)">
                <i class="fa fa-database" aria-hidden="true"></i>
                </div>
                <p >OS</p>
            </div>
        
            <div class="col-1 step-line credit-hide"></div>
            <div class="col-3 text-center credit-hide">
                <div class="step " id="hs_set_active"  data-toggle="tooltip" title="Hasil Survey (kredit)">
                <i class="fa fa-database" aria-hidden="true"></i>
                </div>
                <p >HS</p>
            </div>
        
            <div class="col-1 step-line credit-hide"></div>
            <div class="col-3 text-center credit-hide">
                <div class="step " id="ep_set_active"  data-toggle="tooltip" title="Entry Po Dealer (kredit)">
                <i class="fa fa-database" aria-hidden="true"></i>
                </div>
                <p >EPO</p>
            </div>
        
            <div class="col-1 step-line credit-hide"></div>
            <div class="col-3 text-center credit-hide">
                <div class="step " id="dp_set_active"  data-toggle="tooltip" title="Invoice DP (kredit)">
                <i class="fa fa-database" aria-hidden="true"></i>
                </div>
                <p >DP</p>
            </div>

            <div class="col-1 step-line "></div>
            <div class="col-3 text-center ">
                <div class="step " id="indt_set_active" data-toggle="tooltip" title="Indent">
                <i class="fa fa-database" aria-hidden="true"></i>
                </div>
                <p>INDT</p>
            </div>

            <div class="col-1 step-line"></div>
            <div class="col-3 text-center">
                <div class="step" id="cdb_set_active">
                <i class="fa fa-database" aria-hidden="true" data-toggle="tooltip" title="Customer Database"></i>
                </div>
                <p>CDB</p>
            </div>

            
            <div class="col-1 step-line"></div>
            <div class="col-3 text-center">
                <div class="step" id="cdb_kk_set_active" data-toggle="tooltip" title="Customer Database KK">
                <i class="fa fa-database" aria-hidden="true"></i>
                </div>
                <p>CDBK</p>
            </div>

            <div class="col-1 step-line"></div>
            <div class="col-3 text-center">
                <div class="step" id="spk_set_active" data-toggle="tooltip" title="SPK">
                <i class="fa fa-database" aria-hidden="true"></i>
                </div>
                <p>SPK</p>
            </div>
            <div class="col-1 step-line"></div>
            <div class="col-3 text-center">
                <div class="step" id="so_set_active" data-toggle="tooltip" title="Sales Order">
                <i class="fa fa-database" aria-hidden="true"></i>
                </div>
                <p>SO</p>
            </div>

            <div class="col-1 step-line"></div>
            <div class="col-3 text-center">
                <div class="step" id="tjs_set_active"  data-toggle="tooltip" title="Tanda Jadi Sementara">
                <i class="fa fa-database" aria-hidden="true"></i>
                </div>
                <p>TJS</p>
            </div>

            <div class="col-1 step-line"></div>
            <div class="col-3 text-center">
                <div class="step" id="inv_set_active"  data-toggle="tooltip" title="Invoice">
                <i class="fa fa-database" aria-hidden="true"></i>
                </div>
                <p>INV</p>
            </div>

            <div class="col-1 step-line "></div>
            <div class="col-3 text-center">
                <div class="step" id="delv_set_active"  data-toggle="tooltip" title="List Delivery ">
                <i class="fa fa-database" aria-hidden="true"></i>
                </div>
                <p>DELV</p>
            </div>
            <div class="col-1 step-line"></div>
            <div class="col-3 text-center">
                <div class="step" id="delvd_set_active"  data-toggle="tooltip" title="List Delivery Detail">
                <i class="fa fa-database" aria-hidden="true"></i>
                </div>
                <p>DELVD</p>
            </div>
            <div class="col-1 step-line"></div>
            <div class="col-3 text-center">
                <div class="step" id="fact_set_active"  data-toggle="tooltip" title="Faktur STNK ">
                <i class="fa fa-database" aria-hidden="true"></i>
                </div>
                <p>FAKT</p>
            </div>
            <div class="col-1 step-line"></div>
            <div class="col-3 text-center">
                <div class="step" id="bbn_set_active"  data-toggle="tooltip" title="BBN (Bea Balik Nama)">
                <i class="fa fa-database" aria-hidden="true"></i>
                </div>
                <p>BBN</p>
            </div>

            <div class="col-1 step-line "></div>
            <div class="col-3 text-center">
                <div class="step" id="finish_set_active"  data-toggle="tooltip" title="Generete Data Samsat">
                <i class="fa fa-database" aria-hidden="true"></i>
                </div>
                <p>END</p>
            </div>

            <div class="col-3" style="margin-left: 20px;">
                <div class="step"  style="background-color: #ffc400;">
                <i class="fa fa-eye" aria-hidden="true"  data-toggle="tooltip" title="Status Database - Jika berwana biru sudah active"></i>
                </div>
                <p>INFO</p>
            </div>
       </div>
       <hr>
          </div>
      </div>




      <div class="modal fade" id="myModalInfo">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Informasi Perubahan Data</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <p>Informasi Perubahan data ini untuk memudahkan </p>
              <ul>
                <li>Rekaman baru telah ditambahkan ke dalam basis data.</li>
              </ul>
              <p>Manajemen data dengan serius untuk memastikan akurasi dan relevansi.</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
          </div>
        </div>
      </div>




      <div class="row">
      <section class="col-xs-6 col-md-6 col-md-6">
            <div class="text-center temp-value-not_same">
            </div>
        </section>

        <section class="col-xs-4 col-md-4 col-md-4" style="margin-right:0px ;">
            <div class="text-right">
              <!--  memo #1 -->
                      <button class="btn btn-primary button-set-value" id="ajaxButton"  style="height: 50px; width: 220px;"><i class="fa fa-pencil" aria-hidden="true" title="Lakukan Update Value Data"></i>  <b>SUBMIT</b></button>
                  </div>
        </section>
      </div>

      <hr>


        <div class="row">
        <section class="col-xs-6 col-md-6 col-md-6">
            <div class="form-group">
              

            <div class="intial-dealer">
                  <label for="nik_pencarian" class="col-xs-3 control-label">Nama Dealer</label>
                  <div class="col-xs-9">
                  <input type="text"  id="dealer_pencarian_set" name="" value="" class="form-control default-set-value" readonly>
                  </div> 
                </div>


                <div class="intial-prospek">
                  <label for="prospek_pencarian" class="col-xs-3 control-label">ID Prospek</label>
                  <div class="col-xs-9">
                  <input type="text"  id="prospek_pencarian_set" name="" value="" class="form-control default-set-value prospek_pencarian_set_class" readonly>
                  </div> 
                </div>
    

            <div class="intial-jenis_beli">
                  <label for="po_pencarian" class="col-xs-3 control-label">Jenis Beli</label>
                  <div class="col-xs-9">
                  <input type="text"  id="jenis_beli_pencarian_set" value="" class="form-control default-set-value jenis_beli_base" readonly>
                  </div> 
                </div>

               <div class="intial-po">
                  <label for="po_pencarian" class="col-xs-3 control-label">Enty PO</label>
                  <div class="col-xs-9">
                  <input type="text"  id="po_pencarian_set" value="" class="form-control default-set-value" readonly>
                  </div> 
                </div>
   

                <div class="intial-spk">
                  <label for="spk_pencarian" class="col-xs-3 control-label">No SPK</label>
                  <div class="col-xs-9">
                  <input type="text"  id="no_spk_pencarian_set" value="" class="form-control default-set-value spk_pencarian_set_class" readonly>
                  </div> 
                </div>

                <div class="intial-so">
                  <label for="spk_pencarian" class="col-xs-3 control-label">No Sales Order</label>
                  <div class="col-xs-9">
                  <input type="text"  id="no_so_pencarian_set" value="" class="form-control default-set-value" readonly>
                  </div> 
                </div>

                <div class="intial-nik">
                  <label for="nik_pencarian" class="col-xs-3 control-label">NIK</label>
                  <div class="col-xs-9">
                  <input type="text"  id="nik_pencarian_set" value="" class="form-control default-set-value" readonly>
                  </div> 
                </div>
            
                <div class="intial-nama">
                  <label for="nama_pencarian" class="col-xs-3 control-label">Nama Konsumen</label>
                  <div class="col-xs-9">
                  <input type="text"  id="nama_pencarian_set" value="" class="form-control default-set-value" readonly>
                  </div> 
                </div>

                <div class="intial-tempat">
                  <label for="tempat_pencarian" class="col-xs-3 control-label">Tempat Lahir</label>
                  <div class="col-xs-9">
                  <input type="text"  id="tempat_pencarian_set" value="" class="form-control default-set-value" readonly>
                  </div> 
                  </div> 

                  <div class="intial-lahir">         
                  <label for="tanggal_pencarian" class="col-xs-3 control-label">Tanggal Lahir</label>
                  <div class="col-xs-9">
                  <input type="date"  id="tanggal_pencarian_set" value="" class="form-control default-set-value" readonly>
                  </div> 
                  </div> 
            </div> 
            
            <div class="form-group">
                  <div class="intial-jk">  
                  <label for="jk_pencarian" class="col-xs-3 control-label">Jenis Kelamin</label>
                  <div class="col-xs-9">
                  <input type="text"  id="jk_pencarian_set" value="" class="form-control default-set-value" readonly>
                  </div> 
                  </div> 

                  <div class="intial-alamat">  
                  <label for="alamat_pencarian" class="col-xs-3 control-label">Alamat</label>
                  <div class="col-xs-9">
                  <input type="text"  id="alamat_pencarian_set" value="" class="form-control default-set-value" readonly>
                  </div> 
                  </div> 

                  <div class="intial-rt">  
                  <label for="rt_pencarian" class="col-xs-3 control-label">RT </label>
                  <div class="col-xs-9">
                  <input type="text"  id="rt_pencarian_set" value="" class="form-control default-set-value" readonly max="5">
                  </div> 

                  <div class="intial-rw">             
                  <label for="rw_pencarian" class="col-xs-3 control-label">RW</label>
                  <div class="col-xs-9">
                  <input type="text"  id="rw_pencarian_set" value="" class="form-control default-set-value" readonly>
                  </div> 
                  </div> 
            </div> 

            <div class="form-group">
                  <div class="intial-kelurahan"> 
                  <label for="kelurahan_pencarian" class="col-xs-3 control-label">Kelurahan</label>
                  <div class="col-xs-9">
                  <input type="text"  id="kelurahan_pencarian_set" value="" class="form-control default-set-value" readonly>
                  </div> 
                  </div> 

                  <div class="intial-kecamatan"> 
                  <label for="kecamatan_pencarian" class="col-xs-3 control-label">Kecamatan</label>
                  <div class="col-xs-9">
                  <input type="text"  id="kecamatan_pencarian_set" value="" class="form-control default-set-value" readonly>
                  </div> 
                  </div> 

                  <div class="intial-kabupaten"> 
                  <label for="kebupaten_pencarian" class="col-xs-3 control-label">Kabupaten</label>
                  <div class="col-xs-9">
                  <input type="text"  id="kebupaten_pencarian_set" value="" class="form-control default-set-value" readonly>
                  </div> 
                  </div> 
                     
                  <div class="intial-provinsi"> 
                  <label for="provinsi_pencarian" class="col-xs-3 control-label">Provinsi</label>
                  <div class="col-xs-9">
                  <input type="text"  id="provinsi_pencarian_set" value="" class="form-control default-set-value" readonly>
                  </div> 
                  </div> 
            </div> 
            

            <div class="form-group">
                 <div class="intial-agama"> 
                  <label for="agama_pencarian" class="col-xs-3 control-label">Agama</label>
                  <div class="col-xs-9">
                  <input type="text"  id="agama_pencarian_set" value="" class="form-control default-set-value" readonly>
                  </div> 
                  </div> 

                  <div class="intial-perkawinan"> 
                  <label for="perkawinan_pencarian" class="col-xs-3 control-label">Status Perkawinan</label>
                  <div class="col-xs-9">
                  <input type="text"  id="perkawinan_pencarian_set" value="" class="form-control default-set-value" readonly>
                  </div> 
                  </div> 

                  <!-- <div class="intial-kewarganegaraan"> 
                  <label for="kewarganegaraan_pencarian" class="col-xs-3 control-label">Kewarganegaraan</label>
                  <div class="col-xs-9">
                  <input type="text"  id="kewarganegaraan_pencarian_set" value="" class="form-control default-set-value" readonly>
                  </div> 
                  </div>  -->
                  <br>
            </div> 

            
            <div class="form-group">
                 <div class="intial-kk"> 
                  <label for="kk_pencarian" class="col-xs-3 control-label">No KK</label>
                  <div class="col-xs-9">
                  <input type="text"  id="kk_pencarian_set" value="" class="form-control default-set-value" readonly>
                  </div> 
                  </div> 

                 <div class="intial-email"> 
                  <label for="email_pencarian" class="col-xs-3 control-label">Email </label>
                  <div class="col-xs-9">
                  <input type="text"  id="email_pencarian_set" value="" class="form-control default-set-value" readonly>
                  </div> 
                  </div> 
            </div> 
        </section>

        <!-- ahhir initial -->

        <section class="col-xs-6 col-md-6 col-md-6">
              <div class="form-group">
                  <div class="form-leasing">
                    <label for="nik_pencarian" class="col-xs-3 ">PO Leasing</label>
                    <div class="col-xs-8 paper-line-show">
                        <input type="text" id="po_update" class="form-control update-set-value"  name="po_check_update"  >
                              <span class="input-group-btn paper-plane">
                                <button class="btn btn-default" value="nik_button"  type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                                <span class="input-group-btn paper-plane">
                                </span>
                              </span>
                    </div> 
                  </div>

                  <div class="form-email">
                    <label for="email_pencarian" class="col-xs-3 ">Email</label>
                    <div class="col-xs-8 paper-line-show">
                            <input type="text" id="email_update" name="email_check_update" class="form-control update-set-value"  >
                              <span class="input-group-btn paper-plane">
                                <button class="btn btn-default" value="nik_button"  type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                                <span class="input-group-btn paper-plane">
                                </span> 
                              </span>
                    </div> 
                  </div>
                  
                

                 <div class="form-nik">
                    <label for="nik_pencarian" class="col-xs-3 ">NIK</label>
                    <div class="col-xs-8 paper-line-show">
                            <input type="text" id="nik_update" name="nik_check_update" class="form-control update-set-value"  >
                              <span class="input-group-btn paper-plane">
                                <button class="btn btn-default" value=""  name="email_check_update"  type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                                <span class="input-group-btn paper-plane">
                                </span> 
                              </span>
                    </div> 
                  </div>

                  <div class="form-nama">
                  <label for="nama_pencarian" class="col-xs-3 control-label">Nama Konsumen</label>
                        <div class="col-xs-8 paper-line-show">
                                <input type="text"  id="nama_pencarian_set_value"  name="nama_check_update" value="" class="form-control update-set-value" style="text-transform: uppercase;">
                                <span class="input-group-btn paper-plane">
                                </span>
                        </div> 
                  </div>
                    
                  <div class="form-tempat-lahir">
                  <label for="tempat_pencarian" class="col-xs-3 control-label">Tempat Lahir</label>
                                      <div class="col-xs-8 paper-line-show">
                                              <input type="text"  class="form-control update-set-value"  name="tempat_lahir_check_update" id="openModal"  id="tempat_pencarian"  style="text-transform: uppercase;">
                                              <span class="input-group-btn paper-plane">
                                              </span>
                                      </div> 
                  </div>
                                
                  <div class="form-tanggal-lahir">
                  <label for="tanggal_pencarian" class="col-xs-3 control-label">Tanggal Lahir</label>
                      <div class="col-xs-8 paper-line-show">
                              <input type="date" id="tanggal_lahir" value="" class="form-control update-set-value"  name="tanggal_lahir_check_update">
                              <span class="input-group-btn paper-plane">
                                </span>
                      </div> 
                  </div>
              </div> 

              <div class="form-group">
                  <div class="form-jenis-kelamin">
                    <label for="jk_pencarian" class="col-xs-3 control-label">Jenis Kelamin</label>
                    <div class="col-xs-8 paper-line-show">
                            <select id="jk_pencarian" class="form-control update-set-value" name="jenis_kelamin_check_update">
                            <option value="">- choose -</option>
                            <option value="1">Laki-laki</option>
                            <option value="2">Perempuan</option>
                              
                          </select>
                              <span class="input-group-btn paper-plane">
                                </span>
                    </div> 
                  </div>

                    <div class="form-alamat">
                    <label for="alamat_pencarian" class="col-xs-3 control-label">Alamat</label>
                        <div class="col-xs-8 paper-line-show">
                                <input type="text"  id="alamat_pencarian" value="" class="form-control update-set-value"  name="alamat_check_update"  style="text-transform: uppercase;" >
                                <span class="input-group-btn paper-plane">
                                </span>
                        </div> 
                    </div>
                                    
                      <div class="form-rt">
                      <label for="rt_pencarian" class="col-xs-3 control-label">RT </label>
                          <div class="col-xs-8 paper-line-show">
                              <input type="text"  id="rt_pencarian" value="" class="form-control update-set-value"  name="rt_check_update"  style="text-transform: uppercase;" max="4">
                                    <span class="input-group-btn paper-plane">
                                      <button class="btn btn-default" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                                    </span>
                          </div> 
                      </div>
                                
                      <div class="form-rw">
                      <label for="rw_pencarian" class="col-xs-3 control-label">RW</label>
                        <div class="col-xs-8 paper-line-show">
                                <input type="text"  id="rw_pencarian" value="" class="form-control update-set-value" name="rw_check_update"  style="text-transform: uppercase;" max="4">
                                  <span class="input-group-btn paper-plane">
                                    <button class="btn btn-default" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                                  </span>
                        </div> 
                      </div>
              </div> 

              <div class="form-group">
              <div class="form-kelurahan">
              <label for="kelurahan_pencarian" class="col-xs-3 control-label">Kelurahan</label>
                    <div class="col-xs-8 paper-line-show">
                             <input type="hidden" readonly name="kelurahan_check_update" id="id_kelurahan_modal" class="update-set-value">
                            <input required type="text" onpaste="return false" onkeypress="return nihil(event)" name="kelurahan" data-toggle="modal" placeholder="- choose - " data-target="#Kelurahanmodal" class="form-control" id="kelurahan_modal" onchange="take_kec()" autocomplete="off">
                              <span class="input-group-btn paper-plane">
                              </span>
                    </div>
              </div>

                   <div class="form-kecamatan">

                   <label for="kecamatan_pencarian" class="col-xs-3 control-label">Kecamatan</label>
                    <div class="col-xs-8 paper-line-show">
                    <input type="hidden" name="kecamatan_check_update" id="id_kecamatan_modal" class="update-set-value">
                    <input class="form-control"  id="kecamatan_modal" readonly> 
                      <span class="input-group-btn paper-plane">
                        <button class="btn btn-default" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                      </span>
                    </div>
                   </div>

                   <div class="form-kabupaten">
                   <label for="kebupaten_pencarian" class="col-xs-3 control-label">Kabupaten</label>
                    <div class="col-xs-8 paper-line-show">
                    <input type="hidden" name="kabupaten_check_update" id="id_kabupaten_modal" class="update-set-value">
                    <input class="form-control"  id="kabupaten_modal" readonly> 
                      <span class="input-group-btn paper-plane">
                        <button class="btn btn-default" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                      </span>
                    </div>
                   </div>
                    
                    
                  <div class="form-provinsi">
                  <label for="provinsi_pencarian" class="col-xs-3 control-label">Provinsi</label>
                    <div class="col-xs-8 paper-line-show">
                         <input type="hidden" name="provinsi_check_update" id="id_provinsi_modal"  class="update-set-value">
                         <input class="form-control"  id="provinsi_modal" readonly>
                              </input>
                                <span class="input-group-btn paper-plane">
                                <button class="btn btn-default" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                              </span>
                          </div>
                  </div>

                                  
                  <div class="form-kode-pos">
                  <label for="provinsi_pencarian" class="col-xs-3 control-label">Kode Pos</label>
                    <div class="col-xs-8 paper-line-show">
                     <input type="hidden" name="kodepos_check_update" id="id_kodepos_modal" class="update-set-value">
                        <input class="form-control"  id="kodepos_modal" readonly>
                                <span class="input-group-btn paper-plane">
                                <button class="btn btn-default" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                              </span>
                          </div>
                  </div>

               </div> 


              <div class="form-group">
                  <div class="form-agama">
                    <label for="agama_pencarian" class="col-xs-3 control-label">Agama</label>
                    <div class="col-xs-8 paper-line-show">
                    <select  name="agama_check_update"  id="agama_pencarian" class="form-control update-set-value">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_agama->result() as $isi) {
                        echo "<option value='$isi->id_agama'>$isi->agama</option>";
                      }
                      ?>
                    </select>
                                <span class="input-group-btn paper-plane">
                                <button class="btn btn-default" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                              </span>
                    </div>
                  </div>
                  

                  <div class="form-pendidikan">
                  <label for="pendidikan_pencarian" class="col-xs-3 control-label">Pendidikan</label>
                      <div class="col-xs-8 paper-line-show">
                      <select type="text"  name="pendidikan_check_update"  id="pendidikan_pencarian"  class="form-control update-set-value ">
                                  <option value="">- choose -</option>
                                  <?php 
                                  foreach ($dt_pendidikan->result() as $isi) {
                                    echo "<option value='$isi->id'>$isi->pendidikan</option>";
                                  }
                                  ?>
                                  </select>
                                  <span class="input-group-btn paper-plane">
                                  <button class="btn btn-default" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                                </span>
                      </div>
                    </div>

                <div class="form-perkawinan">
                  <label for="perkawinan_pencarian" class="col-xs-3 control-label">Status Pernikahan</label>
                      <div class="col-xs-8 paper-line-show">
                                  
                      <select type="text"  name="status_pernikahan_check_update"  id="pernikahan_pencarian"  class="form-control update-set-value ">
                                  <option value="">- choose -</option>
                                  <?php 
                                  foreach ($dt_pernikahan->result() as $isi) {
                                    echo "<option value='$isi->id_status_pernikahan'>$isi->status_pernikahan</option>";
                                  }
                                  ?>
                                  </select>


                                  <span class="input-group-btn paper-plane">
                                  <button class="btn btn-default" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                                </span>
                      </div>
                    </div>
                    
                    <div class="form-kewarganegaraan">
                    <label for="kewarganegaraan_pencarian" class="col-xs-3 control-label">Kewarganegaraan</label>
                          <div class="col-xs-8 paper-line-show">
                                      <input type="text"  id="perkawinan_pencarian" value="" class="form-control update-set-value">
                                      <span class="input-group-btn paper-plane">
                                      <button class="btn btn-default" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                                    </span>
                          </div>
                    </div>


                  <div class="form-pekerjaan">
                  <label for="perkawinan_pencarian" class="col-xs-3 control-label">Pekerjaan</label>
                    <div class="col-xs-8 paper-line-show">
                                <select type="text" name="pekerjaan_check_update" id="pekerjaan_pencarian" value="" class="form-control update-set-value">
                                  <option value="">- choose -</option>
                                  <?php 
                                  foreach ($dt_pekerjaan->result() as $isi) {
                                    echo "<option value='$isi->id_pekerjaan'>$isi->pekerjaan</option>";
                                  }
                                  ?>
                                  </select>
                              <span class="input-group-btn paper-plane">
                                    <button class="btn btn-default" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                              </span>
                    </div>
                  </div>

                  <div class="form-pengeluaran">
                  <label for="pengeluaran_pencarian" class="col-xs-3 control-label">Pengeluaran Perbulan</label>
                    <div class="col-xs-8 paper-line-show">
                                <select type="text" name="pengeluaran_check_update" id="pengeluaran_pencarian" value="" class="form-control update-set-value">
                                  <option value="">- choose -</option>
                                  <?php 
                                  foreach ($dt_pengeluaran->result() as $isi) {
                                    echo "<option value='$isi->id_pengeluaran_bulan'>$isi->pengeluaran</option>";
                                  }
                                  ?>
                                  </select>
                              <span class="input-group-btn paper-plane">
                                    <button class="btn btn-default" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                              </span>
                    </div>
                  </div>


                  <div class="form-sub-pekerjaan">
                  <label for="perkawinan_pencarian" class="col-xs-3 control-label">Sub Pekerjaan</label>
                    <div class="col-xs-8 paper-line-show">
                                <select type="text"  name="sub_pekerjaan_check_update"  id="sub_pekerjaan" value="" class="form-control update-set-value ">
                                  <option value="">- choose -</option>
                                  <?php 
                                  foreach ($dt_sub_pekerjaan->result() as $isi) {
                                    echo "<option value='$isi->id_sub_pekerjaan'>$isi->sub_pekerjaan</option>";
                                  }
                                  ?>
                                  </select>
                              <span class="input-group-btn paper-plane">
                                    <button class="btn btn-default" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                              </span>
                    </div>
                  </div>


                    
                  <div class="form-pekerjaan-kk">
                  <label for="perkawinan_pencarian" class="col-xs-3 control-label">Pekerjaan KK</label>
                  <div class="col-xs-8 paper-line-show">
                  <select type="text"  name="sub_pekerjaan_kk_check_update"   id="pekerjaan_kk_pencarian" value="" class="form-control update-set-value">
                                  <option value="">- choose -</option>

                                  <?php 
                                  foreach ($dt_pekerjaan_kk->result() as $isi) {
                                    echo "<option value='$isi->id_pekerjaan'>$isi->pekerjaan</option>";
                                  }
                                  ?>
                                  </select>
                              <span class="input-group-btn paper-plane">
                              <button class="btn btn-default" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                            </span>
                  </div>
                  </div>

                    <div class="form-bbn">
                    <label for="bbn_sama_id"  class="col-xs-3" >Data BBN Sama ?</label>
                      <div class="col-xs-8 paper-line-show">
                        <select name="bbn_sama" id="bbn_sama_id" class="form-control ">
                          <option value="yes">Ya</option>
                          <option value="not">Tidak</option>
                        </select>
                      </div> 
                    </div>

                  <div class="select-bbn">
                        <label class="col-xs-3 control-label">NIK</label>
                        <div class="col-xs-8">
                        <input type="text"  id="nik_pencarian" value="" class="form-control update-set-value"  style="text-transform: uppercase;">
                        </div> 

                        <label for="nama_pencarian" class="col-xs-3 control-label">Nama</label>
                        <div class="col-xs-8">
                        <input type="text"  id="nama_pencarian" value="" class="form-control update-set-value"  style="text-transform: uppercase;">
                        </div> 
                        
                        <label for="tempat_pencarian" class="col-xs-3 control-label update-set-value">Tempat Lahir</label>
                        <div class="col-xs-8">
                        <input type="text"  id="tempat_pencarian" value="" class="form-control update-set-value"  style="text-transform: uppercase;">
                        </div> 
                                    
                        <label for="tanggal_pencarian" class="col-sm-3 control-label update-set-value">Tanggal Lahir</label>
                        <div class="col-xs-8">
                        <input type="date"  id="tanggal_pencarian" value="" class="form-control update-set-value">
                        </div> 
                  </div>
                 </div> 
            </section>
      </div>
  </div>

  </section>




    <script>

function getDatabaseFill() {
  var nosin =  $(".class_no_mesin_pencarian_spk").val();
  $(".step").removeClass("active-step");
  $(".step").removeClass("active-step-finish");

    $.ajax({
      url: '<?php echo base_url() . "h1/memo_h1/getDatabaseFill/" ?>',
        type: 'POST',
        dataType: 'html', 
        data: {
                'nosin': nosin,
              },
        success: function(data) {
          const set = JSON.parse(data);
          if (set.pro!== null) $("#pro_set_active").addClass("active-step");
          if (set.dp !== null) $("#dp_set_active").addClass("active-step");
          if (set.sk !== null) $("#sk_set_active").addClass("active-step");
          if (set.ep !== null) $("#ep_set_active").addClass("active-step");

          if (set.os !== null) $("#os_set_active").addClass("active-step");
          if (set.os !== null) {  $('.temp-value-not_same').first().append('<div class="alert alert-danger" style="background-color: transparent; width: 300px;"><strong>Generate</strong> Data Order Kredit.</div>');}
          
          if (set.hs !== null) $("#hs_set_active").addClass("active-step");
          if (set.spk!== null) $("#spk_set_active").addClass("active-step");
          if (set.po !== null) $("#indt_set_active").addClass("active-step");
          if (set.so !== null) $("#so_set_active").addClass("active-step");
          // if (set.cdb !== null) $("#indt_set_active").addClass("active-step");
          if (set.cdb !== null) $("#cdb_set_active").addClass("active-step");
          if (set.cdb_kk !== null) $("#cdb_kk_set_active").addClass("active-step");
          if (set.tjs !== null) $("#tjs_set_active").addClass("active-step");
          if (set.tinv1 !== null) $("#inv_set_active").addClass("active-step");
          if (set.tinv1 !== null) $("#indt_set_active").addClass("active-step");
          if (set.del !== null) $("#delv_set_active").addClass("active-step");
          if (set.delv !== null) $("#delvd_set_active").addClass("active-step");
          if (set.fakt !== null) $("#fact_set_active").addClass("active-step");
          if (set.bbn !== null) $("#bbn_set_active").addClass("active-step");
          if (set.generate !== null) $("#finish_set_active").addClass("active-step-finish");
        
          if (set.generate !== null) {  $('.temp-value-not_same').first().append('<div class="alert alert-danger" style="background-color: transparent; width: 300px;"><strong>Generate</strong> Data Sudah generate ke Samsat.</div>');}
        
        },
        error: function(xhr, textStatus, errorThrown) {
            console.log('Error:', errorThrown);
        }
    });
}

    </script>

<div class="section-history">
  <section class="content " >
    <div class="row">
          <div class="box box-default">
            <div class="box-header with-border">        
              <div class="row">
                <div class="col-md-12">
                  <div class="box-body">   
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Prospek</th>
                          <th class="credit-show">Skema Kredit</th>
                          <th class="credit-show">Order Survey</th>
                          <th class="credit-show">Hasil Survey</th>
                          <th class="credit-show">Entry PO</th>
                          <th class="credit-show">DP</th>
                          <th>Indent</th>
                          <th >CBD</th>
                          <th >CBD KK</th>
                          <th >SPK</th>
                          <th >Sales Order</th>
                          <th >TJS (indent)</th>
                          <th >Invoice Pelunasan</th>
                          <th >Faktur STNK </th>
                          <th >Invoice Pelunasan Recipt</th>
                          <th >Aksi</th>
                        </tr>
                      </thead>
                      <tbody class="history-edit">
                        <tr>
                          <td id="prospek_history"></td>
                          <td class="credit-show"><button class="toggle-button" data-value="skem"><i class="fa fa-pencil" aria-hidden="true"></i></button></td>
                          <td class="credit-show"><button class="toggle-button" data-value="os"><i class="fa fa-pencil" aria-hidden="true"></i></button></td>
                          <td class="credit-show"><button class="toggle-button" data-value="hs"><i class="fa fa-pencil" aria-hidden="true"></i></button></td>
                          <td class="credit-show"><button class="toggle-button" data-value="epo"><i class="fa fa-pencil" aria-hidden="true"></i></button></td>
                          <td class="credit-show"><button class="toggle-button" data-value="dp"><i class="fa fa-pencil" aria-hidden="true"></i></button></td>
                          <td class="credit-show"></td>
                          <td id="cbd_history"></td>
                          <td id="cbd_kk_history"></td>
                          <td id="spk_history"></td>
                          <td id="so_history"></td>
                          <td id="tjs_history"></td>
                          <td id="inv_history"></td>
                          <td id="faktur_history"></td>
                          <td id="inv_recipt"></td>
                          <td id="action_history"></td>
                        </tr>
                      </tbody>
                    </table>
                </div>
              </div>
            </div>
          </div>
        </div>
  </section>
  </div>


<script>
  // testing
  function getDataHistoryTable() {
    $(".history-edit").show();
    $('.section-history').show();
    var nosin =  $(".class_no_mesin_pencarian_spk").val();

    $.ajax({
      url: '<?php echo base_url() . "h1/memo_h1/get_data_count_activity/" ?>',
        type: 'POST',
        dataType: 'html', 
        data: {
                'nosin': nosin,
              },
        success: function(data) {
          const set = JSON.parse(data);
            $("#prospek_history").html(set.prospek !== null ?' <span class="c-pill c-pill--success">Success</span>' : '<span class="red-cell">Not</span>');
            $("#cbd_history").html(set.cdb !== null ? '<span class="c-pill c-pill--success">Success</span>' : '<span class="red-cell">Not</span>');
            $("#cbd_kk_history").html(set.cdb_kk !== null ? '<span class="c-pill c-pill--success">Success</span>' : '<span class="red-cell">Not</span>');
            $("#spk_history").html(set.spk !== null ? '<span class="c-pill c-pill--success">Success</span>' : '<span class="red-cell">Not</span>');
            // $("#so_history").html(set.so !== null ? set.so : '<span class="green-cell">Not</span>');
            $("#so_history").html(set.so !== null ? '<span class="c-pill c-pill--success">Success</span>' : '<span class="red-cell">Not</span>');
            // $("#tjs_history").html(set.spk !== null ? set.spk : '<span class="green-cell">Not</span>');
            $("#inv_history").html(set.inv_1 !== null ? '<span class="c-pill c-pill--success">Success</span>' : '<span class="red-cell">Not</span>');
            $("#faktur_history").html(set.fak_stnk !== null ? set.fak_stnk : '<span class="green-cell">Not</span>');
            $("#inv_recipt").html(set.inv_1 !== null ? '<span class="c-pill c-pill--success">Success</span>' : '<span class="red-cell">Not</span>');
            $("#action_history").html('<button class="btn btn-sm btn-primary"  id="showModalButton" title=" Show History"><i class="fa fa-eye"></i></button>');
            $('#showModalButton').click(function() {
              getDataFromTableDetail();
                 $('#historyModal').modal('show'); // Show a modal dialog
            });
            $(".status_modal_table_prospek").html(set.prospek == null ? set.prospek : '<span class="c-pill c-pill--success">Success</span>');
            $(".status_modal_table_cdb").html(set.cdb_kk == null ? set.cdb_kk : '<span class="c-pill c-pill--success">Success</span>');
            $(".status_modal_table_spk").html(set.spk == null ? set.spk : '<span class="c-pill c-pill--success">Success</span>');
            $(".status_modal_table_so").html(set.so == null ? set.so : '<span class="c-pill c-pill--success">Success</span>');

        },
        error: function(xhr, textStatus, errorThrown) {
            console.log('Error:', errorThrown);
        }
    });
}

function getDataFromTableDetail(){
    $.ajax({
      url: '<?php echo base_url() . "h1/memo_h1/history_memo/" ?>',
      method: 'GET',
      dataType: 'json',
      success: function(data) {
        $('#historyModal tbody').empty();
        data.forEach(function(item, index) {
          $('#historyModal tbody').append(`
            <tr>
              <td>${index + 1}</td>
              <td>${item.created_at}</td>
              <td>${item.table}</td>
              <td>${item.value}</td>
            </tr>
          `);
        });
      },
      error: function(error) {
        console.error('Error fetching data:', error);
      }
    });
}
</script>

<!-- MEMO #3 -->
<div class="modal" tabindex="-1" role="dialog" id="tableModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><b>Update This Data</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Atribut</th>
            </tr>
          </thead>
          <tbody class="value-table">
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" id="buttonValueUpdate" class="btn btn-primary" data-dismiss="modal">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<div class="modal" tabindex="-1" role="dialog" id="historyModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Created</th>
              <th>Table</th>
              <th>Value</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


  <div class="section-intial">
    
  <section class="content">
            <div class="col-sm-3">
                <div class="small-box" style="background-color:#fff; color:white">
                    <div class="box-header">
                        <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style="font-size: 16px;">Table Data</h3>
                    </div>

                    <div class="box-body">
                      <div class="list-group">

                          <a class="list-group-item"  onclick="getMenu('prospek')">
                              <i class="fa fa-comment-o"></i> Prospek
                          </a>

                          
                          <a class="list-group-item"  onclick="getMenu('skem')">
                              <i class="fa fa-search"></i> Skema Kredit
                          </a>

                          <a class="list-group-item"  onclick="getMenu('os')">
                              <i class="fa fa-search"></i> Order Survey
                          </a>

                          <a class="list-group-item"  onclick="getMenu('hs')">
                              <i class="fa fa-search"></i> Hasil Survey
                          </a>

                          <a class="list-group-item"  onclick="getMenu('epo')">
                              <i class="fa fa-search"></i> Entry PO
                          </a>


                          <a class="list-group-item"  onclick="getMenu('cbd')">
                              <i class="fa fa-search"></i> CBD
                          </a>


                          <a class="list-group-item" onclick="getMenu('cbd_kk')">
                              <i class="fa fa-search"></i> CBD KK
                          </a>
                          
                          <a class="list-group-item" onclick="getMenu('spk')">
                              <i class="fa fa-search"></i> SPK
                          </a>

                          <a class="list-group-item" onclick="getMenu('so')">
                              <i class="fa fa-user"></i> Sales Order
                          </a>

                          <a class="list-group-item" onclick="getMenu('indent')">
                              <i class="fa fa-user"></i> TJS (indent)
                          </a>

                          <a class="list-group-item" onclick="getMenu('inv_pelunasan')">
                              <i class="fa fa-user"></i> Invoice Pelunasan 
                          </a>

                          <a class="list-group-item" onclick="getMenu('fak_stnk')">
                              <i class="fa fa-user"></i> Faktur STNK 
                          </a>
                          
                          <a class="list-group-item" onclick="getMenu('inv_pelunasan_recipt')">
                              <i class="fa fa-user"></i> Invoice Pelunasan Recipt
                          </a>
                    
                      </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-9 col-md-9" style="padding:0; margin:0;">
                <div class="small-box" style="background-color:#fff; color:white">
                    <div div class="box-header">
                        <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style="font-size: 16px;">Detail Data</h3>
                    </div>
                    <div class="box-header">
                    <div class="table-container" style="overflow-x: auto;">
                        <table class="table tbl-product-body">
                        </table>
                    </div>
                    </div>
                </div>
            </div>
  </section>
  </div>


</div>


<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
      <table class="table">
      <thead>
        <tr>
          <th><label><input type="checkbox" id="masterCheckbox"></label></th>
          <th>Table</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
        <tbody>
          <tr>
            <td><input name="data[]" type="checkbox" class="checkbox"></td>
            <td>prospek</td>
            <td class="status_modal_table_prospek"></td>
            <td>
              <button class="btn btn-default modal-set" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
            </td>
          </tr>

          <tr>
            <td><input name="data[]" type="checkbox" class="checkbox"></td>
            <td>cdb</td>
            <td class="status_modal_table_cdb"></td>
            <td>
              <button class="btn btn-default  modal-set" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
            </td>
          </tr>

          <tr>
            <td><input name="data[]" type="checkbox" class="checkbox"></td>
            <td>spk</td>
            <td class="status_modal_table_spk"></td>
            <td>
              <button class="btn btn-default  modal-set" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
            </td>
          </tr>

          <tr>
            <td><input name="data[]" type="checkbox" class="checkbox"></td>
            <td>so</td>
            <td class="status_modal_table_so"></td>
            <td>
              <button class="btn btn-default  modal-set" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
      </div>
      <div class="modal-footer text-center">
          <button type="button" class="btn btn-primary" id="saveButton">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="Kelurahanmodal">
        <div class="modal-dialog" role="document" style="width: 50%">
          <div class="modal-content">
            <div class="modal-header">
              Search Kelurahan
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <table id="table" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th>Kelurahan</th>
                    <th>Kecamatan</th>
                    <th>Kabupaten</th>
                    <th width="1%"></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

</body>

<script>

  // MEMO SET MANUAL

   $(".modal-set").click(function() {
    var row = $(this).closest("tr"); 
    var value = row.find("td:eq(1)").text(); 

    var dataToSend = {};

    var nosin =  $("#no_mesin_pencarian_spk").val();
    if (nosin !== '') {
        dataToSend['no_mesin'] = nosin;
    }

    var tempatLahirValue = $('#tempat_pencarian').val();
    if (tempatLahirValue !== '') {
        dataToSend['tempat_pencarian'] = tempatLahirValue;
    }

    var namaValue = $('#nama_pencarian_set_value').val();
    if (namaValue !== '') {
        dataToSend['nama_pencarian'] = namaValue;
    }

    $.ajax({
      url: '<?php echo base_url() . "h1/memo_h1/fetch_process/" ?>',
      method: 'POST',
      data: { value: value,data: dataToSend,  },
      success: function(response) {
        getDataHistoryTable();

        if (response.status === 'success') {
          Swal.fire({
            icon:response.status,
            title:response.status,
            text: response.message,
            });
        } else if (response.status === 'error') {
            alert(response.message);
        }

      },
      error: function(xhr, status, error) {
        Swal.fire({
        icon: 'error',
        title: 'Error sending data',
        text: error,
    });
      }
    });

    // $("#myModal").hide();
    
  });
</script>




<script>
    $("#masterCheckbox").change(function() {
    $(".checkbox").prop("checked", $(this).prop("checked"));
  });

  $(".checkbox").change(function() {
    if ($(".checkbox:checked").length === $(".checkbox").length) {
      $("#masterCheckbox").prop("checked", true);
    } else {
      $("#masterCheckbox").prop("checked", false);
    }
  });
</script>

<script>
  function getMenu(jum) {
    var menu = jum;
    var nosin =  $("#no_mesin_pencarian_spk").val();
        $.ajax({
              type: "POST",
              dataType: 'html',
              url: '<?php echo base_url() . "h1/memo_h1/get_data/" ?>',
              data: {
                  'menu': menu, 'nosin': nosin,
              },
              success: function(data) {
                  $('.tbl-product-body').html(data);
              },
              error: function() {
                  alert("did not work");
              }
          });
}
</script>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
// Main Start
function pencarian() {
    var nosin =  $("#no_mesin_pencarian_spk").val();
    getDatabaseFill();
    checkData_same(nosin);

        $.ajax({
              type: "POST",
              dataType: 'html',
              url: '<?php  echo base_url() . "h1/memo_h1/get_data_first/" ?>',
              data: {
               'nosin': nosin,
              },
              success: function(data) {
                const set = JSON.parse(data);
              $('.tombolKlik').text(set.jenis_beli);

              $('#prospek_pencarian_set').val(set.id_prospek);
              $('#dealer_pencarian_set').val(set.nama_dealer);
              $('#jenis_beli_pencarian_set').val(set.jenis_beli);
              $('#no_spk_pencarian_set').val(set.no_spk);
              $('#po_pencarian_set').val(set.no_po_leasing);
              $('#nik_pencarian_set').val(set.no_ktp);
              $('#nama_pencarian_set').val(set.nama_konsumen);
              $('#tempat_pencarian_set').val(set.tempat_lahir);
              $('#tanggal_pencarian_set').val(set.tgl_lahir);
              $('#jk_pencarian_set').val(set.jk);
              $('#alamat_pencarian_set').val(set.alamat);
              $('#rt_pencarian_set').val(set.rt);
              $('#rw_pencarian_set').val(set.rw);
              $('#no_so_pencarian_set').val(set.id_sales_order);

              set_wilayah(set.id_kelurahan,set.id_kecamatan,set.id_kabupaten,set.id_provinsi);
              set_master(set.id_agama,set.status_pernikahan,set.jenis_wn,set.jk);

              $('#agama_pencarian_set').val(set.id_agama);
              $('#perkawinan_pencarian_set').val(set.status_pernikahan);
              $('#kewarganegaraan_pencarian_set').val(set.jenis_wn);
              Swal.fire({
                icon: 'success',
                title: 'Data Ditemukan',
                text: 'Data berhasil ditemukan',
              });
              $("#no_mesin_pencarian_spk").prop("readonly", true);
              $(".button-set-value").show();
              $(".update-set-value").prop("readonly", false);
              
              set_bash_kredit_cash(set.jenis_beli);
              },
              error: function() {
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'Something went wrong. Data could not be fetched.',
                });
              }
          });
}

function set_bash_kredit_cash(data){
  if (data === 'Kredit') {
    $(".credit-hide").show();
  }else{
    $(".credit-hide").hide();
  } 
}

</script>



<script>
   function set_wilayah(kel_,kec_,kab_,prov_) {

        $.ajax({
              type: "POST",
              dataType: 'html',
              url: '<?php echo base_url() . "h1/memo_h1/wilayah/" ?>',
              data: {
               'kel': kel_, 'kec': kec_, 'kab': kab_, 'prov': prov_,
              },
              success: function(data) {
                const set = JSON.parse(data);
                $('#kelurahan_pencarian_set').val(set.kelurahan);
                $('#kecamatan_pencarian_set').val(set.kecamatan);
                $('#kebupaten_pencarian_set').val(set.kabupaten);
                $('#provinsi_pencarian_set').val(set.provinsi);
              },
              error: function() {
                  alert("did not work");
              }
          });

   }

   function set_master(agama,status_pernikahan,jenis_wn,jk) {
    $.ajax({
          type: "POST",
          dataType: 'html',
          url: '<?php echo base_url() . "h1/memo_h1/master/" ?>',
          data: {
          'agama': agama, 'status_pernikahan': status_pernikahan, 'jenis_wn': jenis_wn, 'jk': jk,
          },
          success: function(data) {
            const set = JSON.parse(data);
             $('#agama_pencarian_set').val(set.agama);
              $('#perkawinan_pencarian_set').val(set.status_pernikahan);
              $('#kewarganegaraan_pencarian_set').val(set.jenis_wn);
            // $('#kelurahan_pencarian_set_change').html(data);
          },
          error: function() {
              alert("did not work");
          }
      });
    }
</script>


<script>
  $('#bbn_sama_id').on('change', function() {
    alert(this.value);
    if (this.value == 'not'){
         $(".select-bbn").show();
    } else if (check == 'yes'){
        $(".select-bbn").hide();
    }
});
</script>

<script>
    function select_kelurahan_combobox() {
      $.ajax({
              type: "POST",
              dataType: 'html',
              url: '<?php echo base_url() . "h1/memo_h1/get_data_kelurahan/" ?>',
              data: {
              //  'nosin': nosin,
              },
              success: function(data) {
                $('#kelurahan_pencarian_set_change').html(data);

              },
              error: function() {
                  alert("did not work");
              }
          });

          $.ajax({
              type: "POST",
              dataType: 'html',
              url: '<?php echo base_url() . "h1/memo_h1/get_data_master/" ?>',
              data: {
              //  'nosin': nosin,
              },
              success: function(data) {
                $('#kelurahan_pencarian_set_change').html(data);

              },
              error: function() {
                  alert("did not work");
              }
          });

    }
</script>



<script>
  $('#kelurahan_pencarian_set_change').on('change', function (e) {
    var optionSelected = $("option:selected", this);
    var kelurahan = this.value;

    $.ajax({
              type: "POST",
              dataType: 'html',
              url: '<?php echo base_url() . "h1/memo_h1/get_data_kecamatan/" ?>',
              data: {
               'kelurahan': kelurahan,
              },
              success: function(data) {
                $('#kelurahan_pencarian_set_change').html(data);

              },
              error: function() {
                  alert("did not work");
              }
          });
});


function select_kecamatan_combobox(){
      $.ajax({
              type: "POST",
              dataType: 'html',
              url: '<?php echo base_url() . "h1/memo_h1/get_data_kecamatan/" ?>',
              data: {
              //  'nosin': nosin,
              },
              success: function(data) {
                $('#kelurahan_pencarian_set_change').html(data);

              },
              error: function() {
                  alert("did not work");
              }
          });
    }

    
    function select_kabupaten_combobox(){
      $.ajax({
              type: "POST",
              dataType: 'html',
              url: '<?php echo base_url() . "h1/memo_h1/get_data_kabupaten/" ?>',
              data: {
              //  'nosin': nosin,
              },
              success: function(data) {
                $('#kelurahan_pencarian_set_change').html(data);

              },
              error: function() {
                  alert("did not work");
              }
          });
    }
</script>

<script>
    function handleButtonClick(button) {
        var buttonText = button.textContent;
        var buttonValue = button.value;

        $('.default-set-value').click(function () {
        $(this).val(""); // Set the value to an empty string
    });
        performAction(buttonValue);
    }

    function performAction(value) {
        switch (value) {
            case "alamat":
                  alamat();
                break;
            case "namaKonsumen":
              namaKonsumen();
                break;
            case "gantiIdentitas":
              gantiIdentitas();
                break;
            case "gantiPO":
              gantiPO();
                break;
            case "manualEdit":
              manualEdit();
                break;
                case "resetButton":
              resetButton();
                break;
                case "showallButton":
                  showallButton();
                break;
                
            default:
                alert("Unknown action: " + value);
        }
    }

    
</script>

<script>

  function resetButton(){
    $(".section-intial").show(); 
    $('.form-group > div[class^="intial-"]').show();
    $('.form-group > div[class^="form-"]').show();
    // $('.ganti-identitas').hide();
    $('.paper-plane').hide();
  }

  function showallButton(){
    // $(".section-intial").show(); 
    $('.form-group > div[class^="intial-"]').show();
    $('.form-group > div[class^="form-"]').show();
    // $('.ganti-identitas').hide();
    $('.paper-plane').hide();
  }

   function alamat() {
    $('.form-group > div[class^="form-"]').not('.form-alamat').hide();
    $(".form-alamat").show(); 
    $(".intial-alamat").show(); 
   
    // $('.ganti-identitas').hide();
    $('.paper-plane').show();
    $('.section-history').hide();
   }

   function namaKonsumen() {
    $('.form-group > div[class^="form-"]').not('.form-nama').hide();
    $(".form-nama").show(); 
    $('.form-group > div[class^="intial-"]').not('.intial-nama,.intial-spk,.intial-so,.intial-jenis_beli').hide();
    $(".section-intial").hide();
    // $('.ganti-identitas').hide();
    // $('.paper-line-show').show();s
    // $(".col-xs-8 paper-line-show").removeClass(".col-xs-8 paper-line-show").addClass("col-xs-9 paper-line-show");
    $('.paper-plane').hide();

   }

   function gantiPO() {
    $('.form-group > div[class^="intial-"]').not('.intial-nama,.intial-spk,.intial-so').hide();
    // $('.form-group > div[class^="form-"]').not('.form-leasing').hide();
    $('#form-leasing').show();
    // $('.ganti-identitas').hide();
    $('.paper-plane').hide();

   }

   function manualEdit() {
    $(".section-intial").show(); 
    $('.form-group > div[class^="intial-"]').show();
    $('.form-group > div[class^="form-"]').hide();
    // $('.ganti-identitas').hide();
    $('.paper-plane').show();
    $('.section-history').hide();
   }

   function gantiIdentitas() {
     $(".section-intial").hide(); 
     // $('.form-group > div[class^="intial-"]').show();
     $('.form-group > div[class^="form-"]').show();
     $('.form-no_po_leasing').hide(); 
     $('.paper-plane').hide();
    //  $('.ganti-identitas').show(); 
  }


</script>



<script>
function openModal() {
  $("#myModal").show();
}

function closeModal() {
  $("#myModal").css("display", "none");
}

</script>

<script>
  $(document).ready(function() {
    namaKonsumen();
        // gantiIdentitas();
  $(".button-set-value").hide();
  $(".select-bbn").hide();
  $('#no_mesin_pencarian_spk').focus();
  $('.section-history').hide();

  $(".sendButton").click(function() {
    var checkboxValue = $(this).closest("tr").find(".checkbox").val();
  });

  $("#myForm").submit(function(event) {
    event.preventDefault(); 
    var formData = $(this).serialize();

    $.ajax({
      beforeSend: function() {
                          $(el).html('<i class="fa fa-spinner fa-spin"></i> Process');
                          $(el).attr('disabled', true);
                        },
            url: '<?php echo base_url() . "h1/memo_h1/get_data/" ?>',
      type: 'POST',
      data: formData,
      success: function(response) {
      },
      error: function() {
      }
    });
  });
    // testing
    $(".update-set-value").prop("readonly", true);
    
    $(".section-intial").hide(); 
    $("#tanggal_pencarian").prop("readonly", true);


// testing update
//  memo #1
    $("#ajaxButton").click(function () {  
      var nosin   =  $(".class_no_mesin_pencarian_spk").val();
      var prospek =  $(".prospek_pencarian_set_class").val();
      var spk     =  $(".spk_pencarian_set_class").val();

      var defaultValue = {};
      var inputData = {};

      $(".update-set-value").each(function () {
          var name = $(this).attr("name");
          var value = $(this).val();
          if (name !== ""  && value !== ""  && name !== null && value !== null  ) {
              inputData[name] = value;
          }
      });

      $(".default-set-value").each(function () {
          var name = $(this).attr("name");
          var value = $(this).val();
          if (name !== ""  && value !== ""  && name !== null && value !== null  ) {
            defaultValue[name] = value;
          }
      });

      $('#tableModal').modal('show');
      createTableRows(inputData);

  });


  // memo #3
$("#buttonValueUpdate").click(function () {

var nosin   =  $(".class_no_mesin_pencarian_spk").val();
var prospek =  $(".prospek_pencarian_set_class").val();
var spk     =  $(".spk_pencarian_set_class").val();

var defaultValue = {};
var inputData = {};

$(".update-set-value").each(function () {
    var name = $(this).attr("name");
    var value = $(this).val();
    if (name !== ""  && value !== ""  && name !== null && value !== null  ) {
        inputData[name] = value;
    }
});


$(".default-set-value").each(function () {
    var name = $(this).attr("name");
    var value = $(this).val();
    if (name !== ""  && value !== ""  && name !== null && value !== null  ) {
      defaultValue[name] = value;
    }
});

function isEmpty(obj) {
    for(var key in obj) {
        if(obj.hasOwnProperty(key))
            return false;
    }
    return true;
}

var hasNullValue = isEmpty(inputData);

if (hasNullValue) {
    Swal.fire({
    title: 'Form Belum Terisi',
    text: 'Mohon isi Form untuk merubah data',
    icon: 'error'
});

return false;
} 


Swal.fire({
        title: 'Apakah kamu yakin?',
        text: 'Apakah kamu ingin memperbarui data ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        customClass: {
        popup: 'larger-swal-modal' 
    }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?php echo base_url() . "h1/memo_h1/update_set_value/" ?>',
                method: "POST", 
                data: { data: inputData, nosin: nosin, prospek: prospek, spk: spk },
                success: function (response) {
                    Swal.fire({
                        title: 'Data Berhasil diubah',
                        text: 'Berhasil',
                        icon: 'success'
                    });
                    pencarian();

                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        title: 'Response Values',
                        text: 'Gagal',
                        icon: 'error'
                    });
                }
            });
        } else {
        }
    });


    $(".history-edit").hide();
      $(".toggle-button").click(function() {
        var dataValue = $(this).data("value");
        alert("Button value: " + dataValue);
      });

});


  $(".openModalBtn").click(function() {
    $("#myModal").modal("show");
  });


  table = $('#table').DataTable({
            "processing": true, 
            "serverSide": true, 
            "order": [], 
            "ajax": {
              "url": "<?php echo site_url('h1/memo_h1/ajax_list') ?>",
              "type": "POST"
            },
            "columnDefs": [{
              "targets": [0], 
              "orderable": false, 
            }, ],
          });
});

function createTableRows(data) {
  var tbody = $('.value-table');
  tbody.empty(); // Clear existing rows
  var count = 1;
  
  for (var key in data) {
    if (data.hasOwnProperty(key)) {
      var row = '<tr><td>' + count + '</td><td>' + key + '</td><td>' + data[key] + '</td></tr>';
      tbody.append(row);
      count++;
    }
  }
}


</script>

<script>
      function checkData_same(nosin) {
      $.ajax({
        url: '<?php echo base_url() . "h1/memo_h1/get_value_same/" ?>',
        type: 'POST',
        data: {
                      'data': nosin,
                      },
        dataType: 'text', 
        success: function(responseData) {
          $('#result').html(responseData);
        },
        error: function(xhr, status, error) {
          $('#result').html('Error: ' + xhr.status);
        }
      });
    }
</script>



<script>
function take_kec(data) {

		var id_kelurahan =data;
		$.ajax({
		  url: "<?php echo site_url('dealer/spk/take_kec') ?>",
		  type: "POST",
		  data: "id_kelurahan=" + id_kelurahan,
		  cache: false,
		  success: function(msg) {
			data = msg.split("|");
      $("#id_kelurahan_modal").val(id_kelurahan);
			$("#id_kecamatan_modal").val(data[0]);
			$("#kecamatan_modal").val(data[1]);
			$("#id_kabupaten_modal").val(data[2]);
			$("#kabupaten_modal").val(data[3]);
			$("#id_provinsi_modal").val(data[4]);
			$("#provinsi_modal").val(data[5]);
			$("#kelurahan_modal").val(data[6]);
			$("#id_kodepos_modal").val(data[7]);
			$("#kodepos_modal").val(data[7]);
		  }
		})
	  }

    function chooseitem(data) {
      take_kec(data);
		$("#Kelurahanmodal").modal("hide");
	  }
</script>

<?php }?>