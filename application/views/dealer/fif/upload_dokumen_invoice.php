
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


     <div class="box box-default">

      <div class="box-header with-border">              

        <div class="row">

          <div class="col-md-12">


              <div class="box-body">

              <a href="dealer/api_fif/index?page=all_order" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
              <?php if (isset($_GET['judul'])): ?>
              
              <h3><?php echo $_GET['judul'] ?></h3>

              <?php else: ?>

              <h3>Upload Dokumen Invoice</h3>

              <?php endif ?>
              <br><br>

              <form action="" method="POST" enctype="multipart/form-data">
                <div class="fom-group">
                  <label>Foto Serah Terima Kendaraan </label>
                  <input type="file" name="foto_serah_terima" class="form-control" required>
                </div>
                <br>
                <div class="fom-group">
                  <label>Foto Berita Acara Serah Terima Kendaraan (BASTK)</label>
                  <input type="file" name="bast" class="form-control" required>
                </div>
                <br>

                <div class="fom-group">
                  <label>Foto No.Rangka (Cek Fisik)</label>
                  <input type="file" name="no_rangka" class="form-control" required>
                </div>
                <br>

                <div class="fom-group">
                  <label>Foto No.Mesin (Cek Fisik)</label>
                  <input type="file" name="no_mesin" class="form-control" required>
                </div>
                <br>

                <div class="fom-group">
                  <label>Foto KTP Penerima Unit</label>
                  <input type="file" name="ktp_penerima_unit" class="form-control" required>
                </div>
                <br>

                <div class="fom-group">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
              </div><!-- /.box-body -->              
          </div>

        </div>

      </div>

    </div><!-- /.box -->

    

  </section>

</div>





