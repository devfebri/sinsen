<style type="text/css">
  .myTable1 {
    margin-bottom: 0px;
  }

  .myt {
    margin-top: 0px;
  }

  .isi {
    height: 40px;
    padding-left: 5px;
    padding-right: 5px;
    margin-right: 0px;
  }

  .isi_combo {
    height: 30px;
    border: 1px solid #ccc;
    padding-left: 1.5px;
  }

  .center-table {
    text-align: center;
    align-items: center;
  }
</style>

<base href="<?php echo base_url(); ?>" />

<body>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $title; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
        <li class="">H1</li>
        <li class="">Business Control</li>
        <li class="active">Email Juklak Dealer CC</li>
      </ol>
    </section>
    <section class="content">

      <?php
    if($set=="view"){
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
          <a href="h1/email_juklak_dealer/">
              <button class="btn bg-maroon  btn-sm btn-flat margin"><i class="fa fa-history"></i> Kembali</button>
            </a>
          <button class="btn btn-primary btn-sm btn-flat" id="select" data-toggle="modal" data-target="#modaladd"><i
                  class="fa fa-plus" aria-hidden="true"></i> Tambah <Datag></Datag></button>
          </h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
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
          <table id="example4" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="5%">No</th>
                <th>Email CC</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php 
          $no=1; 
          foreach($dt_mail->result() as $row) {                                         
            ?>
              <tr>
                <td><?= $no?></td>
                <td><?= $row->email_cc?></a></td>
                <td>
                <?php if (empty($row->active)) {?>
                     <i class="fa fa-ban" aria-hidden="true"></i>
                      <?php }else{ ?>
                  <i class="fa fa-check" aria-hidden="true"></i>
                  <?php
                } 
                   ?>
              </td>
                <td class="center-table">
                  <button class="btn btn-primary btn-sm btn-flat" data-toggle="modal" data-target="#Modaledit"
                    id="select" data-email="<?=$row->email_cc?>" data-id="<?=$row->id?>" data-active="<?=$row->active?>"
                  ><i class="fa fa-edit" aria-hidden="true"></i></button>

                  <a data-toggle='tooltip' title="Delete Data"
                    onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat"
                    href="h1/email_juklak_dealer_cc/delete?id=<?php echo $row->id ?>"><i
                      class="fa fa-trash-o"></i></a>
                </td>
                <?php                                  
          $no++;
          }
          ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->

      <div class="modal fade" id="Modaledit" tabindex="-1" role="dialog" aria-labelledby="basicModal"
        aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel">Ubah Data </h4>
            </div>
            <div class="modal-body">
              <form action="h1/email_juklak_dealer_cc/edit"  method="post">
                <div class="form-group">
                  <label for="email">Email</label>
                    <input type="hidden" class="form-control" value="" name="id_email" id="email_id">
                    <input type="text" class="form-control" value="" autofocus="" name="email_juklak" id="email"
                      required>
                </div>

                <div class="checkbox">
                  <label><input type="checkbox" name="status_active" class="active"> Status</label>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>


      <div class="modal fade" id="modaladd" tabindex="-1" role="dialog" aria-labelledby="basicModal"
        aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel">Tambah Data </h4>
            </div>
            <div class="modal-body">
              <form action="h1/email_juklak_dealer_cc/add"  method="post" >
                <div class="form-group">
                  <label for="email">Email</label>

                      <input type="email" class="form-control" value="" autofocus="" required="" placeholder="Email"
                        name="email_juklak_cc">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php
    }
    ?>



  </section>
  </div>

  <script>
    $(document).ready(function () {
      $(document).on('click', '#select', function () {
        var item_id = $(this).data('id');
        var email = $(this).data('email');
        var dealer = $(this).data('dealer');
        var active = $(this).data('active');
        if (active == 1) {
          $('.active').prop('checked', true);
        }
        $('#email_id').val(item_id);
        $('#email').val(email);
        $('#dealer').val(dealer);
        $('#modal-item').modal('hide');
      });
    });
  </script>