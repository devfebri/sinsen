<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0.0 <a href="javascript:void(0);"><i class="fa fa-file-image-o"></i></a>
  </div>
  <?php
  $id_k = $this->session->userdata("id_karyawan_dealer");
  $dealer = $this->db->query("SELECT * FROM ms_dealer 
            INNER JOIN ms_karyawan_dealer ON ms_dealer.id_dealer = ms_karyawan_dealer.id_dealer 
            WHERE id_karyawan_dealer = '$id_k'");
  if ($dealer->num_rows() > 0) {
    $r = $dealer->row();
    $dealer = $r->nama_dealer;
  } else {
    $dealer = "Honda";
  }
  ?>
  <!-- <strong>Copyright &copy; <?php echo date("Y") ?> <a target="_blank" href=""><?php echo $dealer ?></a>.</strong> All rights reserved.-->
  <strong>Copyright &copy; <?php echo date("Y") ?> <img src="<?= base_url('assets/panel/images/sinsen_logo.png') ?>" style="height: 30px;" alt=""> All right reserved.</footer>
</div><!-- ./wrapper -->
<script src="assets/validation/daterange/jquery.min.js"></script>
<script src="assets/validation/daterange/jquery.ui.min.js"></script>
<script type="text/javascript">
  var dateToday = new Date();
  var dates = $("#from, #to").datepicker({
    defaultDate: "+1w",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
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

<script type="text/javascript">
  var dateToday = new Date();
  var dates = $("#from, #to").datepicker({
    defaultDate: "+1w",
    changeMonth: true,
    dateFormat: "yy-mm-dd",
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

<!--<script type="text/javascript">-->
<!--(function (exports) {-->
<!--    function urlsToAbsolute(nodeList) {-->
<!--        if (!nodeList.length) {-->
<!--            return [];-->
<!--        }-->
<!--        var attrName = 'href';-->
<!--        if (nodeList[0].__proto__ === HTMLImageElement.prototype || nodeList[0].__proto__ === HTMLScriptElement.prototype) {-->
<!--            attrName = 'src';-->
<!--        }-->
<!--        nodeList = [].map.call(nodeList, function (el, i) {-->
<!--            var attr = el.getAttribute(attrName);-->
<!--            if (!attr) {-->
<!--                return;-->
<!--            }-->
<!--            var absURL = /^(https?|data):/i.test(attr);-->
<!--            if (absURL) {-->
<!--                return el;-->
<!--            } else {-->
<!--                return el;-->
<!--            }-->
<!--        });-->
<!--        return nodeList;-->
<!--    }-->

<!--    function screenshotPage() {-->
<!--        urlsToAbsolute(document.images);-->
<!--        urlsToAbsolute(document.querySelectorAll("link[rel='stylesheet']"));-->
<!--        var screenshot = document.documentElement.cloneNode(true);-->
<!--        var b = document.createElement('base');-->
<!--        b.href = document.location.protocol + '//' + location.host;-->
<!--        var head = screenshot.querySelector('head');-->
<!--        head.insertBefore(b, head.firstChild);-->
<!--        screenshot.style.pointerEvents = 'none';-->
<!--        screenshot.style.overflow = 'hidden';-->
<!--        screenshot.style.webkitUserSelect = 'none';-->
<!--        screenshot.style.mozUserSelect = 'none';-->
<!--        screenshot.style.msUserSelect = 'none';-->
<!--        screenshot.style.oUserSelect = 'none';-->
<!--        screenshot.style.userSelect = 'none';-->
<!--        screenshot.dataset.scrollX = window.scrollX;-->
<!--        screenshot.dataset.scrollY = window.scrollY;-->
<!--        var script = document.createElement('script');-->
<!--        script.textContent = '(' + addOnPageLoad_.toString() + ')();';-->
<!--        screenshot.querySelector('body').appendChild(script);-->
<!--        var blob = new Blob([screenshot.outerHTML], {-->
           
<!--        });-->
<!--        const file = new File([blob], 'untitled', { type: blob.type })-->
<!--        return file;-->
<!--    }-->

<!--    function addOnPageLoad_() {-->
<!--        window.addEventListener('DOMContentLoaded', function (e) {-->
<!--            var scrollX = document.documentElement.dataset.scrollX || 0;-->
<!--            var scrollY = document.documentElement.dataset.scrollY || 0;-->
<!--            window.scrollTo(scrollX, scrollY);-->
<!--        });-->
<!--    }-->

<!--    function generate() {-->
<!--        window.URL = window.URL || window.webkitURL;-->
<!--        window.open(window.URL.createObjectURL(screenshotPage()));-->
       
<!--    }-->
<!--    exports.screenshotPage = screenshotPage;-->
<!--    exports.generate = generate;-->
<!--})(window);-->
<!--</script>-->

<script src="assets/panel/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="assets/panel/bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="assets/panel/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/panel/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="assets/panel/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/panel/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/panel/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/3.3.2/js/dataTables.fixedColumns.min.js"></script>
<!--<script src="<?php echo base_url(); ?>assets/plugins/datatables/extensions/FixedHeader/js/dataTables.fixedHeader.min.js"></script>-->
<?php /*
<script src="<?php echo base_url(); ?>assets/bootstrap/datatable/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bootstrap/datatable/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bootstrap/datatable/buttons.colVis.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bootstrap/datatable/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bootstrap/datatable/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bootstrap/datatable/vfs_fonts.js"></script>
<!--<script src="<?php echo base_url(); ?>assets/bootstrap/datatable/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets/panel/fixedHeader/js/fixedHeader.dataTables.js"></script>--> */ ?>
<script type="text/javascript" src="assets/fixheader/dataTables.fixedHeader.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<!-- <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script> -->
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  // $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.5 -->
<!-- Morris.js charts -->
<!-- ChartJS 1.0.1 -->
<!--masking-->
<script src="assets/panel/masking/jquery.maskedinput.js"></script>
<script src="assets/validation/jquery.validate.js"></script>
<script>
  jQuery(function($) {
    //$("#no_stnk").mask("99/99/9999",{placeholder:"mm/dd/yyyy"});
    /* for(i=1;i<=500;i++){
       $("#no_plat_"+i+"").mask("BH-9999-aaa", {autoclear: false});  
     } */
  });
</script>
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
<!-- <script src="assets/moment/moment.min.js"></script> -->
<!-- <script src="assets/panel/plugins/daterangepicker/daterangepicker.js"></script> -->
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
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
<!-- <script src="assets/panel/plugins/daterangepicker/daterangepicker.js"></script> -->
<script src="assets/panel/jquery-price-format/jquery.priceformat.js"></script>
<script src="assets/toastr/toastr.min.js"></script>
<!-- Page Script -->
<script>
  $('.dataTables_filter input')
    .unbind() // Unbind previous default bindings
    .bind('input', (delay(function(e) { // Bind our desired behavior
      oTable.search($(this).val()).draw();
      return;
    }, 1000))); // Set delay in milliseconds
  function delay(callback, ms) {
    var timer = 0;
    return function() {
      var context = this,
        args = arguments;
      clearTimeout(timer);
      timer = setTimeout(function() {
        callback.apply(context, args);
      }, ms || 0);
    };
  }
  $(".monthpicker").datepicker({
    format: "yyyy-mm",
    viewMode: "months",
    minViewMode: "months",
    autoclose: true,
  });
  $('.datepicker').datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
  });
  $('.datepicker2').datepicker({
    format: "dd/mm/yyyy",
    autoclose: true,
  });

  function date_dmy(date) {
    return moment(date, 'YYYY-MM-DD').format('DD/MM/YYYY')
  }

  function toNumber(value) {
    // return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    return value.toString().replace(/[^0-9]/gi, '');
  }

  function priceformat() {
    $('.tanpa_rupiah').priceFormat({
      prefix: '',
      thousandsSeparator: '.',
      // clearOnEmpty: true,
      centsLimit: 0,
      limit: 20
    });
  }
  $('.tanpa_rupiah').priceFormat({
    prefix: '',
    thousandsSeparator: '.',
    // clearOnEmpty: true,
    centsLimit: 0,
    limit: 20
  });
  $(function() {
    //Add text editor
    $("#compose-textarea").wysihtml5();
  });
</script>
<script>
  $(function() {
    for (i = 1; i <= 50; i++) {
      $("#tanggal" + i + "").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
      });
    }
    $(".select2").select2();
    $('#tanggal').datepicker({
      format: "yyyy-mm-dd",
      autoclose: true,
    });
    $('#tanggal2').datepicker({
      format: "yyyy-mm-dd",
      autoclose: true,
    });
    $('#tanggal3').datepicker({
      format: "yyyy-mm-dd",
      autoclose: true,
    });
    $('#tanggal4').datepicker({
      format: "yyyy-mm-dd",
      autoclose: true,
    });
    $('#tanggal5').datepicker({
      format: "yyyy-mm-dd",
      autoclose: true,
    });
    $('#tanggal6').datepicker({
      format: "yyyy-mm-dd",
      autoclose: true,
    });
    $('#tanggal7').datepicker({
      format: "yyyy-mm-dd",
      autoclose: true,
    });
    $('#tanggal_today').datepicker({
      format: "yyyy-mm-dd",
      autoclose: true,
      minDate: '2018-12-12',
    });
    $('#format_bulan').datepicker({
      format: "yyyy-mm",
      autoclose: true,
    });
    $('#tanggal_bast').datepicker({
      format: "yyyy-mm-dd",
	disabled: true,
	endDate: new Date()
    });
    $('#tanggal_dob').datepicker({
      format: "yyyy-mm-dd",
	disabled: true,
	endDate: '-17y'
    });
    $('#tanggal_dob2').datepicker({
      format: "yyyy-mm-dd",
	disabled: true,
	endDate: '-17y'
    });
    $('#tanggal_dob3').datepicker({
      format: "yyyy-mm-dd",
	disabled: true,
	endDate: '-17y'
    });
  });
  $('#stok_detail').DataTable({
    order: [
      [2, "asc"]
    ],
    responsive: true,
    dom: 'Bfrtip',
    buttons: [{
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
          columns: ':visible'
        }
      },
      'colvis'
    ]
    // columnDefs: [
    //   { responsivePriority: 1, targets: 0 },
    // { responsivePriority: 2, targets: -2 }
    //]
  });
  $(function() {
    $('#example4').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "info": true,
      "scrollX": true,
      fixedHeader: true,
      "lengthMenu": [
        [10, 25, 50, 75, 100, -1],
        [10, 25, 50, 75, 100, "All"]
      ],
      "autoWidth": true
    });
    var ex2 = $('#example2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      fixedHeader: true,
      "lengthMenu": [
        [10, 25, 50, 75, 100, -1],
        [10, 25, 50, 75, 100, "All"]
      ],
      "autoWidth": true
    });
    $('#example5').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      fixedHeader: true,
      "lengthMenu": [
        [10, 25, 50, 75, 100, -1],
        [10, 25, 50, 75, 100, "All"]
      ],
      "autoWidth": true
    });
    $('#example6').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      fixedHeader: true,
      "lengthMenu": [
        [10, 25, 50, 75, 100, -1],
        [10, 25, 50, 75, 100, "All"]
      ],
      "autoWidth": true
    });
    $('#example7').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      fixedHeader: true,
      "lengthMenu": [
        [10, 25, 50, 75, 100, -1],
        [10, 25, 50, 75, 100, "All"]
      ],
      "autoWidth": true
    });
    $('#example3').DataTable({
      paging: true,
      lengthChange: true,
      searching: true,
      ordering: true,
      info: true,
      fixedHeader: true,
      "lengthMenu": [
        [10, 25, 50, 75, 100, -1],
        [10, 25, 50, 75, 100, "All"]
      ],
      columnDefs: [{
          "targets": [0], //first column
          "orderable": false, //set not orderable
        },
        {
          "targets": [-1], //first column
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
  $(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#example1').DataTable({
      responsive: true,
      dom: 'Bfrtip',
      buttons: [{
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
            columns: ':visible'
          }
        },
        'colvis'
      ]
      // columnDefs: [
      //   { responsivePriority: 1, targets: 0 },
      // { responsivePriority: 2, targets: -2 }
      //]
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#tabel_finance').DataTable({
      dom: 'ftp'
    });
  });
  $(document).ready(function() {
    $('#tabel_finance2').DataTable({
      dom: 'ftp'
    });
  });
  $(document).ready(function() {
    $('#tabel_finance3').DataTable({
      dom: 'ftp'
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#exampleX').DataTable({
      responsive: true,
      paging: false,
      ordering: false,
      info: false,
      dom: 'Brt'
    });
  });
</script>
<script type="text/javascript" src="assets/panel/plugins/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
  tinymce.init({
    selector: "textarea#tinymce",
    menubar: false,
    statusbar: false,
    toolbar: false
  })
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#check-all").click(function() {
      $(".data-check").prop('checked', $(this).prop('checked'));
    });
  });
  $(document).ready(function() {
    $("#check-all2").click(function() {
      $(".data-check2").prop('checked', $(this).prop('checked'));
    });
  });
  $(document).ready(function() {
    $("#check-all3").click(function() {
      $(".data-check3").prop('checked', $(this).prop('checked'));
    });
  });
  $(document).ready(function() {
    $("#check-all4").click(function() {
      $(".data-check4").prop('checked', $(this).prop('checked'));
    });
  });
  $(document).ready(function() {
    $("#check-all5").click(function() {
      $(".data-check5").prop('checked', $(this).prop('checked'));
    });
  });
  $(document).ready(function() {
    $("#check-all6").click(function() {
      $(".data-check6").prop('checked', $(this).prop('checked'));
    });
  });
  $(document).ready(function() {
    $("#check-all7").click(function() {
      $(".data-check7").prop('checked', $(this).prop('checked'));
    });
    $("#check-all8").click(function() {
      $(".data-check8").prop('checked', $(this).prop('checked'));
    });
    $("#check-all9").click(function() {
      $(".data-check9").prop('checked', $(this).prop('checked'));
    });
    $("#check-all10").click(function() {
      $(".data-check10").prop('checked', $(this).prop('checked'));
    });
    $("#check-all11").click(function() {
      $(".data-check11").prop('checked', $(this).prop('checked'));
    });
    $("#check-all12").click(function() {
      $(".data-check12").prop('checked', $(this).prop('checked'));
    });
    $("#check-all13").click(function() {
      $(".data-check13").prop('checked', $(this).prop('checked'));
    });
  });
  $(document).ready(function() {
    $("#check-allx").click(function() {
      $(".data-check").prop('checked', $(this).prop('checked'));
      $(".data-check2").prop('checked', $(this).prop('checked'));
      $(".data-check3").prop('checked', $(this).prop('checked'));
      $(".data-check4").prop('checked', $(this).prop('checked'));
      $(".data-check5").prop('checked', $(this).prop('checked'));
      $(".data-check6").prop('checked', $(this).prop('checked'));
      $(".data-check7").prop('checked', $(this).prop('checked'));
      $(".data-check8").prop('checked', $(this).prop('checked'));
      $(".data-check9").prop('checked', $(this).prop('checked'));
      $(".data-check10").prop('checked', $(this).prop('checked'));
      $(".data-check11").prop('checked', $(this).prop('checked'));
      $(".data-check12").prop('checked', $(this).prop('checked'));
      $(".data-check13").prop('checked', $(this).prop('checked'));
    });
  });
</script>
<script type="text/javascript">
  function check_y(a) {
    var row = a;
    $(".data-check" + row).prop('checked', $(this).prop('checked'));
    $(".data-check2" + row).prop('checked', $(this).prop('checked'));
    $(".data-check3" + row).prop('checked', $(this).prop('checked'));
    $(".data-check4" + row).prop('checked', $(this).prop('checked'));
    $(".data-check5" + row).prop('checked', $(this).prop('checked'));
    $(".data-check6" + row).prop('checked', $(this).prop('checked'));
    $(".data-check7" + row).prop('checked', $(this).prop('checked'));
  }

  function number_only(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if ((charCode >= 48 && charCode <= 57 || charCode == 46 || charCode == 8 || charCode == 127))
      return true;
    return false;
  }

  function nihil(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if ((charCode >= 1 && charCode <= 255))
      return false;
  }

  function back(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if ((charCode === 8))
      return false;
  }
  /* Tanpa Rupiah */
  var tanpa_rupiah = document.getElementById('tanpa-rupiah');
  if (tanpa_rupiah) {
    tanpa_rupiah.addEventListener('keyup', function(e) {
      tanpa_rupiah.value = formatRupiah(this.value);
    });
    tanpa_rupiah.addEventListener('keydown', function(event) {
      limitCharacter(event);
    });
  }
  var tanpa_rupiah2 = document.getElementById('tanpa-rupiah2');
  if (tanpa_rupiah2) {
    tanpa_rupiah2.addEventListener('keyup', function(e) {
      tanpa_rupiah2.value = formatRupiah(this.value);
    });
    tanpa_rupiah2.addEventListener('keydown', function(event) {
      limitCharacter(event);
    });
  }
  var tanpa_rupiah3 = document.getElementById('tanpa-rupiah2');
  if (tanpa_rupiah3) {
    tanpa_rupiah3.addEventListener('keyup', function(e) {
      tanpa_rupiah3.value = formatRupiah(this.value);
    });
    tanpa_rupiah3.addEventListener('keydown', function(event) {
      limitCharacter(event);
    });
  }
  /* Dengan Rupiah */
  var dengan_rupiah = document.getElementById('dengan-rupiah');
  if (dengan_rupiah) {
    dengan_rupiah.addEventListener('keyup', function(e) {
      dengan_rupiah.value = formatRupiah(this.value, 'Rp. ');
    });
    dengan_rupiah.addEventListener('keydown', function(event) {
      limitCharacter(event);
    });
  }

  function convertToRupiah(angka) {
    var rupiah = '';
    var angkarev = angka.toString().split('').reverse().join('');
    for (var i = 0; i < angkarev.length; i++)
      if (i % 3 == 0) rupiah += angkarev.substr(i, 3) + '.';
    return 'Rp. ' + rupiah.split('', rupiah.length - 1).reverse().join('');
  }

  function convertNoRupiah(angka) {
    var rupiah = '';
    var angkarev = angka.toString().split('').reverse().join('');
    for (var i = 0; i < angkarev.length; i++)
      if (i % 3 == 0) rupiah += angkarev.substr(i, 3) + '.';
    return rupiah.split('', rupiah.length - 1).reverse().join('');
  }
  /* Fungsi */
  function formatRupiah(bilangan, prefix) {
    var number_string = bilangan.replace(/[^,\d]/g, '').toString(),
      split = number_string.split(','),
      sisa = split[0].length % 3,
      rupiah = split[0].substr(0, sisa),
      ribuan = split[0].substr(sisa).match(/\d{1,3}/gi);
    if (ribuan) {
      separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
    }
    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
  }

  function limitCharacter(event) {
    key = event.which || event.keyCode;
    if (key != 188 // Comma
      &&
      key != 8 // Backspace
      &&
      key != 17 && key != 86 & key != 67 // Ctrl c, ctrl v
      &&
      (key < 48 || key > 57) // Non digit
      // Dan masih banyak lagi seperti tombol del, panah kiri dan kanan, tombol tab, dll
    ) {
      event.preventDefault();
      return false;
    }
  }
</script>
<script type="text/javascript">
  document.onkeydown = function(e) {
    if (event.keyCode == 123) {
      return false;
    }
    if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
      return false;
    }
    if (e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
      return false;
    }
    if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
      return false;
    }
    if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
      return false;
    }
  }
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#pj thead th').each(function() {
      var title = $(this).text();
      $(this).html('<input style="width:95%" type="text" />');
    });
    var table = $('#pj').DataTable({
      responsive: true,
      dom: 'Bfrtip',
      lengthMenu: [
        [10, 25, 50, -1],
        ['10 rows', '25 rows', '50 rows', 'Show all']
      ],
      buttons: [
        'colvis',
        'pageLength',
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
            columns: ':visible'
          }
        },
      ],
      "footerCallback": function(row, data, start, end, display) {
        var api = this.api(),
          data;
        // Remove the formatting to get integer data for summation
        var intVal = function(i) {
          return typeof i === 'string' ?
            i.replace(/[\$,]/g, '') * 1 :
            typeof i === 'number' ?
            i : 0;
        };
        var numFormat = $.fn.dataTable.render.number('\,', '.', 2, ' ').display;
        var noFormat = $.fn.dataTable.render.number('\,', '.', 2, ' ').display;
        // Total over this page
        var pageTotalton = api
          .column(5, {
            page: 'current'
          })
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);
        var pageTotaljum = api
          .column(7, {
            page: 'current'
          })
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);
        // Update footer
        $(api.column(5).footer()).html(' ' + pageTotalton);
        $(api.column(7).footer()).html(' ' + pageTotaljum);
      }
    });
    table.columns().every(function() {
      var that = this;
      $('input', this.header()).on('keyup change', function() {
        if (that.search() !== this.value) {
          that
            .search(this.value)
            .draw();
        }
      });
    });
  });
</script>
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#carian thead th').each(function() {
      var title = $(this).text();
      $(this).html('<input style="width:95%" type="text" />');
    });
    var table = $('#carian').DataTable({
      responsive: true,
      dom: 'Bfrtip',
      lengthMenu: [
        [10, 25, 50, -1],
        ['10 rows', '25 rows', '50 rows', 'Show all']
      ],
      buttons: [
        'pageLength',
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
            columns: ':visible'
          }
        },
        //   'colvis'
      ],
      "footerCallback": function(row, data, start, end, display) {
        var api = this.api(),
          data;
        // Remove the formatting to get integer data for summation
        var intVal = function(i) {
          return typeof i === 'string' ?
            i.replace(/[\$,]/g, '') * 1 :
            typeof i === 'number' ?
            i : 0;
        };
      }
    });
    table.columns().every(function() {
      var that = this;
      $('input', this.header()).on('keyup change', function() {
        if (that.search() !== this.value) {
          that
            .search(this.value)
            .draw();
        }
      });
    });
  });
</script>
</body>

</html>
<!-- onkeypress="return number_only(event)" -->