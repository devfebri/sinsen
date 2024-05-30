<a href="h3/h3_md_laporan_penerimaan_barang/detail?no_penerimaan_barang=<?= $id ?>" class="btn btn-xs btn-flat btn-info">View</a>
<!-- <hr> -->
 <hr style="margin-top: 5px; margin-bottom : 5px;">
<!--<a target='_blank' href="h3/h3_md_laporan_penerimaan_barang/download_excel_by_packing_sheet?no_penerimaan_barang=<?= $id ?>" class="btn btn-xs btn-flat btn-success">Report</a>
<a href="h3/h3_md_laporan_penerimaan_barang/download_excel_format_by_packing_sheet?no_penerimaan_barang=<?= $id ?>" class="btn btn-xs btn-flat btn-warning">Report Versi Excel</a>
<a target='_blank' href="h3/h3_md_laporan_penerimaan_barang/download_excel_by_packing_sheet_with_amount?no_penerimaan_barang=<?= $id ?>" class="btn btn-xs btn-flat btn-success">Report with amount</a> -->

<div class="btn-group">
          <button type="button" class="btn btn-success btn-xs btn-flat">Report</button>
          <button type="button" class="btn btn-flat btn-xs btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
          </button>
          <ul class="dropdown-menu">
            <li><a target='_blank' href="h3/h3_md_laporan_penerimaan_barang/download_excel_by_packing_sheet?no_penerimaan_barang=<?= $id ?>" >Report</a></li>
            <li><a href="h3/h3_md_laporan_penerimaan_barang/download_excel_format_by_packing_sheet?no_penerimaan_barang=<?= $id ?>">Report Versi Excel</a></li>
            <li><a target='_blank' href="h3/h3_md_laporan_penerimaan_barang/download_excel_by_packing_sheet_with_amount?no_penerimaan_barang=<?= $id ?>">Report with amount</a></li>
          </ul>
        </div>