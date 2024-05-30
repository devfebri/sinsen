<div id="form_" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div v-if='loading' class="overlay">
        <i class="text-light-blue fa fa-refresh fa-spin"></i>
      </div>
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form  class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Ekspedisi</label>
                    <div class="col-sm-4">
                      <input :readonly='mode == "detail"' v-model='ongkos_angkut_part.nama_vendor' type="text" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="container-fluid">
                      <div class="row">
                        <div class="col-sm-12">
                          <table class="table table-condensed">
                            <tr>
                              <td width='3%'>No.</td>
                              <td>Jenis</td>
                              <td>Type Mobil</td>
                              <td>Per Satuan</td>
                              <td>Harga</td>
                              <td>Kategori</td>
                              <td>Dimulai Tanggal</td>
                              <td width='3%'></td>
                            </tr>
                            <tr v-if='items.length > 0' v-for='(each, index) of items'>
                              <td>{{ index + 1 }}.</td>
                              <td class='align-middle'>{{ each.jenis }}</td>
                              <td class='align-middle'>{{ each.type_mobil }}</td>
                              <td class='align-middle'>
                                <vue-numeric read-only class="form-control" v-model='each.per_satuan' separator='.' precision='2' decimal-separator='.'></vue-numeric>
                              </td>
                              <td class='align-middle'>
                                <vue-numeric read-only class="form-control" v-model='each.harga' separator='.' currency='Rp'></vue-numeric>
                              </td>
                              <td>{{ each.kategori }}</td>
                              <td>{{ each.start_date }}</td>
                              <td>
                                <button class="btn btn-flat btn-sm btn-danger" @click.prevent='hapus_ongkos_angkut_part(index)'><i class="fa fa-trash-o"></i></button>
                              </td>
                            </tr>
                          </table>
                        </div>
                      </div>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
  var form_ = new Vue({
      el: '#form_',
      data: {
        mode : '<?= $mode ?>',
        index_part: 0,
        loading: false,
        errors: {},
        ongkos_angkut_part: <?= json_encode($ongkos_angkut_part) ?>,
        items: <?= json_encode($items) ?>
      },
      methods: {
        hapus_ongkos_angkut_part: function(index){
          item = this.items[index];
          this.loading = true;
          axios.get('h3/h3_md_ms_ongkos_angkut_part/hapus_ongkos_angkut_part', {
            params: {
              id: item.id
            }
          })
          .then(function(res){
            form_.items.splice(index, 1);
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){
            form_.loading = false;
          })
        }
      }
  });
</script>