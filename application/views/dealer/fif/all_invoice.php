
<table class="table table-bordered" id="datatable">
  <thead>
    <tr>
      <th>No.</th>
      <th>No SPK</th>
      <th>Nama Konsumen</th>
      <th>Amount Tranfer</th>
      <th>No Invoice</th>
      <th>Tgl Invoice</th>
      <th>Tgl Cair</th>
      <th>Status invice</th>
      <th>Action</th>
      
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>


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
          searchPlaceholder: "Pencarian..."
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
                url :  base_url+'dealer/api_fif/getDataAllOrderInvoice',
                type : 'POST'
              },
    }); // End of DataTable


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