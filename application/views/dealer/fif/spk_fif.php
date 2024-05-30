<table class="table table-bordered" id="datatable">
  <thead>
    <tr>
      <th>No.</th>
      <th>No SPK</th>
      <th>Nama Konsumen</th>
      <th>Tanggal</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<button data-id="1" data-name="apa">Clik</button>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

<script type="text/javascript">
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

    var base_url = "<?php echo base_url() ?>"; 

    dataTable = $('#datatable').DataTable({
       "pageLength" : 10,
       "serverSide": true,
       "ordering": true,
        "processing": true,
        "language": {
          processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
          searchPlaceholder: "Min. 5 digit untuk cari"
        },
       "order": [[1, "desc" ]],
       "rowCallback": function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            var index = page * length + (iDisplayIndex + 1);
            $('td:eq(0)', row).html(index);
        },
       "ajax":{
                url :  base_url+'dealer/api_fif/getData',
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

</script>

<script>
function myFunction() {
  document.getElementById("demo").innerHTML = "Hello World";
}
</script>
