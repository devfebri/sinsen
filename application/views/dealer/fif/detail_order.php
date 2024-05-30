
<base href="<?php echo base_url(); ?>" />

<div class="content-wrapper">

<!-- Content Header (Page header) -->

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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">


     <div class="box box-default">

      <div class="box-header with-border">              

        <div class="row">

          <div class="col-md-12">


              <div class="box-body">

              <a href="dealer/api_fif/index?page=all_order" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
              <br><br>


              <?php 
              $rw = json_decode($hasil);
               ?>
              <table class="table">
                <tr>
                  <td>cust nik</td>
                  <td><?php echo $rw->cust_nik ?></td>
                </tr>
                <tr>
                  <td>cust name</td>
                  <td><?php echo $rw->cust_name ?></td>
                </tr>
                <tr>
                  <td>birth_place</td>
                  <td><?php echo $rw->birth_place ?></td>
                </tr>
                <tr>
                  <td>birth_date</td>
                  <td><?php echo $rw->birth_date ?></td>
                </tr>
                <tr>
                  <td>cust_mother</td>
                  <td><?php echo $rw->cust_mother ?></td>
                </tr>
                <tr>
                  <td>addr_address</td>
                  <td><?php echo $rw->addr_address ?></td>
                </tr>
                <tr>
                  <td>addr_rt</td>
                  <td><?php echo $rw->addr_rt ?></td>
                </tr>
                <tr>
                  <td>addr_rw</td>
                  <td><?php echo $rw->addr_rw ?></td>
                </tr>
                <tr>
                  <td>addr_kel_code</td>
                  <td><?php echo $rw->addr_kel_code ?></td>
                </tr>
                <tr>
                  <td>dom_address</td>
                  <td><?php echo $rw->dom_address ?></td>
                </tr>
                <tr>
                  <td>dom_rt</td>
                  <td><?php echo $rw->dom_rt ?></td>
                </tr>
                <tr>
                  <td>dom_rw</td>
                  <td><?php echo $rw->dom_rw ?></td>
                </tr>
                <tr>
                  <td>dom_kel_code</td>
                  <td><?php echo $rw->dom_kel_code ?></td>
                </tr>
                <tr>
                  <td>cust_mobile_phone1</td>
                  <td><?php echo $rw->cust_mobile_phone1 ?></td>
                </tr>
                <tr>
                  <td>cust_mobile_phone2</td>
                  <td><?php echo $rw->cust_mobile_phone2 ?></td>
                </tr>
                <tr>
                  <td>emer_mob_phone1</td>
                  <td><?php echo $rw->emer_mob_phone1 ?></td>
                </tr>
                <tr>
                  <td>cust_salary</td>
                  <td><?php echo $rw->cust_salary ?></td>
                </tr>
                <tr>
                  <td>prospect_no</td>
                  <td><?php echo $rw->prospect_no ?></td>
                </tr>

              </table>

              </div><!-- /.box-body -->              

              

          </div>



        </div>

      </div>

    </div><!-- /.box -->

    

  </section>

</div>





