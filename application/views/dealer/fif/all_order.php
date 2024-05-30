
<table class="table table-bordered" id="datatable">
  <thead>
    <tr>
      <th>No.</th>
      <th>No SPK</th>
      <th>Nama Konsumen</th>
      <th>Order UUID</th>
      <th>Order Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/sweetalert2/sweetalert2.min.css">
<script type="text/javascript" src="<?php echo base_url() ?>assets/sweetalert2/sweetalert2.min.js"></script>

<script type="text/javascript">
  var dataTable;
  $(document).ready(function(e){
    $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
    {
        return {
            "iStart": oSettings._iDisplayStart,
            "iEnd": oSettings.fnDisplayEnd(),
            "iLength": oSettings._iDisplayLength,
            "iTotal": oSettings.fnRecordsTotal(),
            "iFilteredTotal": oSettings.fnRecordsDisplay(),
            "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
            "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
        };
    };

    var base_url = "<?php echo base_url() ?>"; // You can use full url here but I prefer like this
    dataTable = $('#datatable').DataTable({
       "pageLength" : 10,
       "serverSide": true,
       "ordering": true, // Set true agar bisa di sorting
        "processing": true,
        "language": {
          processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
          searchPlaceholder: "Min. 5 digit untuk cari"
        },

       "order": [[1, "DESC" ]],
       "rowCallback": function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            var index = page * length + (iDisplayIndex + 1);
            $('td:eq(0)', row).html(index);
        },
       "ajax":{
                url :  base_url+'dealer/api_fif/getDataAllOrder',
                type : 'POST'
              },
    }); // End of DataTable

    $(".dataTables_filter input")
    .unbind() // Unbind previous default bindings
    .bind("input", function(e) { // Bind our desired behavior
        // If the length is 3 or more characters, or the user pressed ENTER, search
        if(this.value.length >= 5 || e.keyCode == 13) {
            // Call the API search function
            dataTable.search(this.value).draw();
        }
        // Ensure we clear the search if they backspace far enough
        if(this.value == "") {
          dataTable.search("").draw();
        }
        return;
    });

  }); 


  function ready_to_delivery(url) {
    var base_url = "<?php echo base_url() ?>";
      $.ajax({
          url: url,
          type: 'GET',
          success: function(res) {
              console.log(res);
              $("#alert").html(res);
              dataTable.draw();
          }
      });
    }
</script>


<script>
    function confirmAndUpdate(id,spk) {
    Swal.fire({
      title: "Update Data ?",
      text: "SPK akan dikembalikan ke status booking | SPK dapat diedit",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, update it!"
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "dealer/api_fif/update_history_finco?spk=" + spk + "&id=" + id;
      }
    });

  }
</script>


