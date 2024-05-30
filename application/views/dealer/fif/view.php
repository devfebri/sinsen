<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>        
    <li class="">Dealer</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
     <div class="box box-default">
      <div class="box-header with-border">              
        <div class="row">
          <div class="col-md-12">
              <div class="box-body">
                <div class="alert alert-success alert-dismissible">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <p><strong>Catatan :</strong></p>
                  <ol>
                    <li>jika sudah melakukan submit order dan status masih <strong>ON STAGING / NEW ORDER,&nbsp;</strong>ingin melakukan update SPK silahkan meminta ke team FIF untuk cancel order dahulu, kemudian spk yang sudah diupdate dapat di submit ulang.</li>
                    <li>jika status sudah menjadi <strong>APPROVED&nbsp;</strong>dan ada kesalahan di data SPK, lakukan cancel SPK dan buat prospek baru beserta SPK baru, kemudian submit order di menu API FIF</li>
                  </ol>
                </div>
              <ul class="nav nav-tabs">
             <?php 
             $act_1 = '';
             $act_2 = '';
             $act_3 = '';
              if ($_GET) {
                  if ($_GET['page'] == 'push_order') {
                      $act_1 = 'class="active"';
                  } elseif($_GET['page'] == 'all_order') {
                      $act_2 = 'class="active"';
                  } elseif($_GET['page'] == 'all_order_invoice') {
                      $act_3 = 'class="active"';
                  } else {
                      $act_1 = 'class="active"';
                  }
              } else {
                $act_1 = 'class="active"';
              }
               ?>
                  <li <?php echo $act_1 ?>><a href="dealer/api_fif/index?page=push_order">Push Order</a></li>
                  <li <?php echo $act_2 ?>><a href="dealer/api_fif/index?page=all_order">All Order</a></li>
                  <?php if ($this->session->userdata('group') == '61'): ?>
                  <li <?php echo $act_3 ?>><a href="dealer/api_fif/index?page=all_order_invoice">Invoice</a></li>
                  <?php endif ?>
              </ul>
              <br>

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
                <div id="alert"></div>
              <?php 
              if ($_GET) {
                  if ($_GET['page'] == 'push_order') {
                      $this->load->view('dealer/fif/spk_fif');
                  } elseif($_GET['page'] == 'all_order') {
                      $this->load->view('dealer/fif/all_order');
                  } elseif($_GET['page'] == 'all_order_invoice') {
                      $this->load->view('dealer/fif/all_invoice');
                  }
              } else {
                  $this->load->view('dealer/fif/spk_fif');
              }
              ?>
              </div>         
          </div>
        </div>
      </div>
    </div>
  </section>
</div>





