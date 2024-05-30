<div class="modal fade" id="modalInteraksi">
  <div class="modal-dialog" style='width:90%'>
    <div class="modal-content">
      <div class="modal-header bg-red disabled color-palette">
        <button style='color:white' type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" align='center'>Interaksi</h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class='table table-bordered table-condensed table-striped' id="datatables_interaksi" style='width:100%'>
            <thead>
              <th>#</th>
              <th>Leads ID</th>
              <th>ID Interaksi</th>
              <th>Nama</th>
              <th>No. HP</th>
              <th>No. Telp</th>
              <th>Email</th>
              <th>Customer Type</th>
              <th>Event Code Invitation</th>
              <th>Customer Action Date</th>
              <th>CMS Source</th>
              <th>Segmen Motor</th>
              <th>Series Motor</th>
              <th>Kode Unit + Warna Motor</th>
              <th>Deskripsi Event</th>
              <th>Minat Riding Test</th>
              <th>Jadwal Riding Test</th>
              <th>Source Data</th>
              <th>Platform Data</th>
              <th>Provinsi</th>
              <th>Kabupaten</th>
              <th>Kecamatan</th>
              <th>Kelurahan</th>
              <th>Assigned Dealer</th>
              <th>Frame No. Sebelumnya</th>
              <th>Keterangan</th>
              <th>Promo Unit</th>
              <th>Source Ref. ID</th>
              <th>Facebook</th>
              <th>Instagram</th>
              <th>Twitter</th>
            </thead>
          </table>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<script>
  var set_interaksi = [];

  function showModalInteraksi(el, params_id_prospek) {
    values = {
      id_prospek: params_id_prospek
    }
    $.ajax({
      beforeSend: function() {
        set_interaksi = [];
        $(el).html('<i class="fa fa-spinner fa-spin"></i>');
        // $(el).attr('disabled', true);
      },
      enctype: 'multipart/form-data',
      url: '<?= site_url('dealer/prospek_crm/getInteraksi') ?>',
      type: "POST",
      data: values,
      // processData: false,
      // contentType: false,
      // cache: false,
      dataType: 'JSON',
      success: function(response) {
        if (response.status == 1) {
          for (x of response.data) {
            set_interaksi.push(x);
          }
          setInteraksi();
        } else {
          alert(response.pesan)
          $(el).attr('disabled', false);

        }
        $(el).html('View All');
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: '<font color="white">Peringatan</font>',
          html: '<font color="white">Telah terjadi kesalahan !</font>',
          background: '#dd4b39',
          confirmButtonColor: '#cc3422',
          confirmButtonText: 'Tutup',
          iconColor: 'white'
        })
        $(el).html('View All');
        $(el).attr('disabled', false);
      }
    });
    $('#modalInteraksi').modal('show');
  }

  $(document).ready(function() {
    $('#datatables_interaksi').DataTable({
      // "scrollX": true,
      data: set_interaksi
    });
  })

  function setInteraksi() {
    $('#datatables_interaksi').DataTable().destroy();
    $('#datatables_interaksi').DataTable({
      // "scrollX": true,
      data: set_interaksi
    });
  }
</script>