<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="https://unpkg.com/vue-select@latest"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">H2</li>
      <li class="">Sevice Management</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>

  <section class="content">
      <div class="box">
        <div class="box-body">
          <table id="example1" class="table table-bordered table-hover table-condensed">
            <thead>
              <tr>
                <th>Search Field</th>
                <th>Search Result</th>
                <th>Sisa Stok</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Alasan</th>
              </tr>
            </thead>
            <tbody>
            <?php if (count($reason_demand) > 0): ?>
              <?php foreach ($reason_demand as $e): ?>
                  <td><?= $e->search_field ?></td>
                  <td><?= $e->search_result ?></td>
                  <td><?= $e->sisa_stock ?></td>
                  <td><?= $e->harga_satuan ?></td>
                  <td><?= $e->qty ?></td>
                  <td><?= $e->note_field ?></td>
                </tr>
              <?php endforeach ?>
            <?php endif; ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
  </section>
</div>