<table id="modalexample2" class="table table-bordered table-hover">

          <thead>

            <tr>
              <th>FIFO</th>  

              <th>No Mesin</th>

              <th>No Rangka</th>                                    

              <th>Tipe Motor</th>                                               

              <th>Warna</th>    

              <th>Tipe</th>        
              <th width="10%"></th>
            </tr>

          </thead>

          <tbody>

          <?php

          $no = 1; 

          $id_dealer = $this->m_admin->cari_dealer();

          $id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');

          $id_warna = $this->input->post('id_warna');

          $dt_nosin = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.no_mesin,tr_scan_barcode.no_rangka,ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,ms_warna.id_warna,ms_warna.warna,tr_scan_barcode.tipe,tr_penerimaan_unit_dealer_detail.fifo

            FROM tr_penerimaan_unit_dealer_detail LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin=tr_scan_barcode.no_mesin 

            LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer

            LEFT JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item

            LEFT JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 

            LEFT JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna WHERE tr_penerimaan_unit_dealer_detail.status_dealer = 'input'

            AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' and tr_scan_barcode.tipe='RFS' 
            AND tr_scan_barcode.status = '4'
            AND tr_penerimaan_unit_dealer_detail.jenis_pu='RFS' AND tr_penerimaan_unit_dealer_detail.retur =0
            AND ms_item.id_tipe_kendaraan='$id_tipe_kendaraan' AND ms_item.id_warna = '$id_warna' AND tr_penerimaan_unit_dealer.status = 'close'
            -- AND tr_penerimaan_unit_dealer_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_sales_order WHERE no_mesin IS NOT NULL UNION SELECT no_mesin FROM tr_sales_order_gc_nosin)

            ORDER BY tr_penerimaan_unit_dealer_detail.fifo ASC");          

          foreach ($dt_nosin->result() as $ve2) {



            echo "

            <tr>
              <td>$ve2->fifo</tipe>
              <td>$ve2->no_mesin</td>

              <td>$ve2->no_rangka</td>            

              <td>$ve2->id_tipe_kendaraan | $ve2->tipe_ahm</td>

              <td>$ve2->id_warna | $ve2->warna</td>

              <td>$ve2->tipe</tipe>

              ";

              ?>                         
              
              <td class="center">

                <button title="Choose" data-dismiss="modal" id_tipe="<?php echo $ve2->id_tipe_kendaraan; ?>" onclick="chooseitem('<?php echo $ve2->no_mesin; ?>','<?php echo $ve2->id_tipe_kendaraan; ?>')" class="btn btn-flat btn-success btn-sm btn_get"><i class="fa fa-check"></i></button>                 

              </td>
            </tr>

            <?php

            $no++;

          }

          ?>

          </tbody>

        </table>