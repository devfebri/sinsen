
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 1.0.0 <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>#top"><i class="fa fa-arrow-up"></i></a>
        </div>
        <?php 
          $id_k = $this->session->userdata("id_karyawan_dealer");
          $dealer = $this->db->query("SELECT * FROM ms_dealer 
            INNER JOIN ms_karyawan_dealer ON ms_dealer.id_dealer = ms_karyawan_dealer.id_dealer 
            WHERE id_karyawan_dealer = '$id_k'");          
          $r = $dealer->row();
          ?>
        <strong>Copyright &copy; <?php echo date("Y") ?> <a target="_blank" href=""><?php echo $r->nama_dealer ?></a>.</strong> All rights reserved.
      </footer>

      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
          <li><a href="assets/panel/#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
          <li><a href="assets/panel/#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
        
      </aside><!-- /.control-sidefbar -->
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <script src="assets/validation/daterange/jquery.min.js"></script>
      <script src="assets/validation/daterange/jquery.ui.min.js"></script>


    <script type="text/javascript">
    var dateToday = new Date();
    var dates = $("#from, #to").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        dateFormat:"yy-mm-dd",        
        numberOfMonths: 1,
        minDate: dateToday,
        onSelect: function(selectedDate) {
            var option = this.id == "from" ? "minDate" : "maxDate",
                instance = $(this).data("datepicker"),
                date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
            dates.not(this).datepicker("option", option, date);
        }
    });
    </script>

        







    <script src="assets/panel/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="assets/panel/bootstrap/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="assets/panel/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/panel/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="assets/panel/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/panel/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="assets/panel/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datatables/extensions/FixedHeader/js/dataTables.fixedHeader.min.js"></script>

<script src="<?php echo base_url(); ?>assets/bootstrap/datatable/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bootstrap/datatable/buttons.print.min.js"></script>

<script src="<?php echo base_url(); ?>assets/bootstrap/datatable/buttons.colVis.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bootstrap/datatable/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bootstrap/datatable/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bootstrap/datatable/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>assets/bootstrap/datatable/buttons.html5.min.js"></script>
    
    <!-- jQuery UI 1.11.4 -->
    
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.5 -->    
    <!-- Morris.js charts -->  
    <!-- ChartJS 1.0.1 -->
  



    <!-- FastClick -->    
    <script src="assets/raphael/raphael-min.js"></script>

    <script src="assets/panel/plugins/morris/morris.min.js"></script>
    <!-- Sparkline -->
    <script src="assets/panel/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="assets/panel/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="assets/panel/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="assets/panel/plugins/knob/jquery.knob.js"></script>
    <!-- daterangepicker -->
<script src="assets/moment/moment.min.js"></script>
    
    <script src="assets/panel/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="assets/panel/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="assets/panel/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <!-- Slimscroll -->
    <script src="assets/panel/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="assets/panel/plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="assets/panel/dist/js/app.min.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!--script src="assets/panel/dist/js/pages/dashboard.js"></script-->
    <!-- AdminLTE for demo purposes -->
    <script src="assets/panel/dist/js/demo.js"></script>          
    <script src="assets/panel/plugins/iCheck/icheck.min.js"></script>
    <script src="assets/panel/plugins/select2/select2.full.min.js"></script>
    <script src="assets/panel/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    

    <script src="assets/panel/plugins/daterangepicker/daterangepicker.js"></script>
    

    <!-- Page Script -->
    <script>
      $(function () {
        //Add text editor
        $("#compose-textarea").wysihtml5();
      });
    </script>
    <script>
    $(function() {
      $(".select2").select2();
      $('#tanggal').datepicker({
            format:"yyyy-mm-dd"            
        });    
      $('#tanggal2').datepicker({
            format:"yyyy-mm-dd"            
      });
      $('#tanggal3').datepicker({
            format:"yyyy-mm-dd"            
      });
      $('#tanggal4').datepicker({
            format:"yyyy-mm-dd"            
      });
      $('#tanggal5').datepicker({
            format:"yyyy-mm-dd"            
      });
      $('#tanggal6').datepicker({
            format:"yyyy-mm-dd"            
      });
      $('#tanggal7').datepicker({
            format:"yyyy-mm-dd"            
      });
      $('#format_bulan').datepicker({
            format:"yyyy-mm"            
      });      
    });
       

      $(function () {
        $('#example4').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "scrollX":true,
          "autoWidth": true
        });
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,

          "autoWidth": true
        });
        $('#example5').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,

          "autoWidth": true
        });
        $('#example3').DataTable({
          paging: true,
          lengthChange: true,
          searching: true,
          ordering: true,
          info: true,
          columnDefs: [
            { 
                "targets": [ 0 ], //first column
                "orderable": false, //set not orderable
            },
            { 
                "targets": [ -1 ], //first column
                "orderable": false, //set not orderable
            },
          ],            
          autoWidth: true         
        });
      });


     //iCheck for checkbox and radio inputs
      $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
      });
      //Red color scheme for iCheck
      $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
        checkboxClass: 'icheckbox_minimal-red',
        radioClass: 'iradio_minimal-red'
      });
      //Flat red color scheme for iCheck
      $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
      });

      $(document).ready(function(){
          $('[data-toggle="tooltip"]').tooltip(); 
      });

    </script>
    <script type="text/javascript">
  $(document).ready(function() {
    $('#example1').DataTable( {
        responsive: true,
        dom: 'Bfrtip',
        buttons: [

            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns:':visible'
                }
            },
            'colvis'
        ]
       // columnDefs: [
         //   { responsivePriority: 1, targets: 0 },
           // { responsivePriority: 2, targets: -2 }
        //]
    } );
} );
</script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#exampleX').DataTable( {
        responsive: true,
        paging:   false,
        ordering: false,
        info:     false,
        dom: 'Bfrt'               
    });
  });
</script>

    <script type="text/javascript" src="assets/panel/plugins/tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
    tinymce.init({
        selector:"textarea",        
        menubar: false,
        statusbar: false,
        toolbar: false
        })
    </script>
    

    <script type="text/javascript">
    $(document).ready(function() {    
      $("#check-all").click(function () {
          $(".data-check").prop('checked', $(this).prop('checked'));
      });
    });
    $(document).ready(function() {    
      $("#check-all2").click(function () {
          $(".data-check2").prop('checked', $(this).prop('checked'));
      });
    });
    $(document).ready(function() {    
      $("#check-all3").click(function () {
          $(".data-check3").prop('checked', $(this).prop('checked'));
      });
    });
    $(document).ready(function() {    
      $("#check-all4").click(function () {
          $(".data-check4").prop('checked', $(this).prop('checked'));
      });
    });
    $(document).ready(function() {    
      $("#check-all5").click(function () {
          $(".data-check5").prop('checked', $(this).prop('checked'));
      });
    });
    $(document).ready(function() {    
      $("#check-all6").click(function () {
          $(".data-check6").prop('checked', $(this).prop('checked'));
      });
    });
    $(document).ready(function() {    
      $("#check-all7").click(function () {
          $(".data-check7").prop('checked', $(this).prop('checked'));
      });
    });
    $(document).ready(function() {    
      $("#check-allx").click(function () {
          $(".data-check").prop('checked', $(this).prop('checked'));
          $(".data-check2").prop('checked', $(this).prop('checked'));
          $(".data-check3").prop('checked', $(this).prop('checked'));
          $(".data-check4").prop('checked', $(this).prop('checked'));
          $(".data-check5").prop('checked', $(this).prop('checked'));
          $(".data-check6").prop('checked', $(this).prop('checked'));
          $(".data-check7").prop('checked', $(this).prop('checked'));
      });      
    });    
    </script>
    <script type="text/javascript">
    function check_y(a){
      var row = a;
      $(".data-check"+row).prop('checked', $(this).prop('checked'));          
      $(".data-check2"+row).prop('checked', $(this).prop('checked'));          
      $(".data-check3"+row).prop('checked', $(this).prop('checked'));          
      $(".data-check4"+row).prop('checked', $(this).prop('checked'));          
      $(".data-check5"+row).prop('checked', $(this).prop('checked'));          
      $(".data-check6"+row).prop('checked', $(this).prop('checked'));          
      $(".data-check7"+row).prop('checked', $(this).prop('checked'));          
        
    }
    function number_only(evt){
      var charCode = (evt.which) ? evt.which : event.keyCode
      if((charCode >= 48 && charCode <= 57 || charCode == 46 || charCode == 8 || charCode == 127))
        return true;
      return false;
    }
    function nihil(evt){
      var charCode = (evt.which) ? evt.which : event.keyCode
      if((charCode >= 1 && charCode <= 255))
        return false;      
    }


  /* Tanpa Rupiah */
  var tanpa_rupiah = document.getElementById('tanpa-rupiah');
  tanpa_rupiah.addEventListener('keyup', function(e)
  {
    tanpa_rupiah.value = formatRupiah(this.value);
  });

  tanpa_rupiah.addEventListener('keydown', function(event)
  {
    limitCharacter(event);
  });

  var tanpa_rupiah2 = document.getElementById('tanpa-rupiah2');
  tanpa_rupiah2.addEventListener('keyup', function(e)
  {
    tanpa_rupiah2.value = formatRupiah(this.value);
  });  

  tanpa_rupiah2.addEventListener('keydown', function(event)
  {
    limitCharacter(event);
  });

  var tanpa_rupiah3 = document.getElementById('tanpa-rupiah2');
  tanpa_rupiah3.addEventListener('keyup', function(e)
  {
    tanpa_rupiah3.value = formatRupiah(this.value);
  });  

  tanpa_rupiah3.addEventListener('keydown', function(event)
  {
    limitCharacter(event);
  });
  
  /* Dengan Rupiah */
  var dengan_rupiah = document.getElementById('dengan-rupiah');
  dengan_rupiah.addEventListener('keyup', function(e)
  {
    dengan_rupiah.value = formatRupiah(this.value, 'Rp. ');
  });
  
  dengan_rupiah.addEventListener('keydown', function(event)
  {
    limitCharacter(event);
  });
  
  /* Fungsi */
  function formatRupiah(bilangan, prefix)
  {
    var number_string = bilangan.replace(/[^,\d]/g, '').toString(),
      split = number_string.split(','),
      sisa  = split[0].length % 3,
      rupiah  = split[0].substr(0, sisa),
      ribuan  = split[0].substr(sisa).match(/\d{1,3}/gi);
      
    if (ribuan) {
      separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
    }
    
    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
  }
  
  function limitCharacter(event)
  {
    key = event.which || event.keyCode;
    if ( key != 188 // Comma
       && key != 8 // Backspace
       && key != 17 && key != 86 & key != 67 // Ctrl c, ctrl v
       && (key < 48 || key > 57) // Non digit
       // Dan masih banyak lagi seperti tombol del, panah kiri dan kanan, tombol tab, dll
      ) 
    {
      event.preventDefault();
      return false;
    }
  }
</script>
    </script>

  </body>
</html>

