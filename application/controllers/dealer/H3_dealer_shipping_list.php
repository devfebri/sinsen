<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_dealer_shipping_list extends Honda_Controller
{
    public $folder = "dealer";
    public $page   = "h3_dealer_shipping_list";
    public $title  = "Shipping List";

    public function __construct()
    {
        parent::__construct();
        //---- cek session -------//
        $name = $this->session->userdata('nama');
        if ($name == "") {
            echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
        }

        //===== Load Database =====
        $this->load->database();
        $this->load->helper('url');
        //===== Load Model =====
        $this->load->model('m_admin');
        $this->load->model('customer_model', 'customer');
        $this->load->model('h3_dealer_request_document_model', 'request_document');
        $this->load->model('h3_dealer_good_receipt_model', 'good_receipt');
        $this->load->model('h3_dealer_good_receipt_parts_model', 'good_receipt_parts');
        $this->load->model('h3_dealer_purchase_order_model', 'purchase_order');
        $this->load->model('h3_dealer_purchase_order_parts_model', 'purchase_order_parts');
        $this->load->model('h3_dealer_shipping_list_model', 'shipping_list');
        $this->load->model('h3_dealer_shipping_list_parts_model', 'shipping_list_parts');
        $this->load->model('h3_dealer_lokasi_rak_bin_model', 'lokasi_rak_bin');
        $this->load->model('h3_dealer_penerimaan_barang_model', 'penerimaan_barang');
        $this->load->model('h3_dealer_penerimaan_barang_items_model', 'penerimaan_barang_items');
        $this->load->model('h3_dealer_purchase_return_model', 'purchase_return');
        $this->load->model('h3_dealer_purchase_return_parts_model', 'purchase_return_parts');
        $this->load->model('dealer_model', 'dealer');
        $this->load->model('ms_part_model', 'ms_part');
        $this->load->model('h3_dealer_gudang_h23_model', 'gudang');
        $this->load->model('h3_dealer_stock_model', 'stock');
        $this->load->model('notifikasi_model', 'notifikasi');
        $this->load->model('h3_dealer_transaksi_stok_model', 'transaksi_stok');
        $this->load->model('H3_dealer_order_fulfillment_model', 'order_fulfillment');
    }

    public function index()
    {
        $data['isi']    = $this->page;
        $data['title']    = $this->title;
        $data['set']    = "index";
        $data['penerimaan_barang'] = $this->db
            ->select('pb.*')
            ->select('date_format(pb.created_at, "%d-%m-%Y") as created_at')
            ->from('tr_h3_dealer_penerimaan_barang as pb')
            ->where('pb.id_dealer', $this->m_admin->cari_dealer())
            ->get()->result();
        $this->template($data);
    }

    public function add()
    {
        $data['kode_md'] = 'E22';
        $data['isi']     = $this->page;
        $data['title']   = $this->title;
        $data['mode']    = 'insert';
        $data['set']     = "form";
        $this->template($data);
    }

    public function get_surat_pengantar()
    {
        $this->db
            ->select('sp.*')
            ->select('date_format(sp.tanggal, "%d-%m-%Y") as tanggal')
            ->from('tr_h3_md_surat_pengantar_items as spi')
            ->join('tr_h3_md_surat_pengantar as sp', 'spi.id_surat_pengantar = sp.id_surat_pengantar')
            ->where('spi.id_packing_sheet', $this->input->post('id_packing_sheet'));

        $result = $this->db->get()->row();
        send_json($result);
    }

    public function save()
    {
        $parts_good = array_map(
            function ($part) {
                return [
                    'id_part_int' => $part['id_part_int'],
                    'id_part' => $part['id_part'],
                    'no_dus' => $part['no_dus'],
                    'id_gudang' => $part['id_gudang_good'],
                    'id_rak' => $part['id_rak_good'],
                    'qty' => $part['qty_good'],
                    'harga' => $part['harga'],
                    'harga_setelah_diskon' => $part['harga_setelah_diskon'],
                    'serial_number' => $part['serial_number'],
                ];
            },
            array_filter($this->input->post('parts'), function ($part) {
                return $part['qty_good'] > 0;
            }, ARRAY_FILTER_USE_BOTH)
        );

        $parts_bad = array_map(
            function ($part) {
                return [
                    'id_part' => $part['id_part'],
                    'no_dus' => $part['no_dus'],
                    'id_gudang' => $part['id_gudang_bad'],
                    'id_rak' => $part['id_rak_bad'],
                    'qty' => $part['qty_bad'],
                ];
            },
            array_filter($this->input->post('parts'), function ($part) {
                return $part['qty_bad'] > 0;
            }, ARRAY_FILTER_USE_BOTH)
        );

        $penerimaan_barang = array_merge($this->input->post([
            'id_surat_pengantar', 'id_packing_sheet', 'nomor_po'
        ]), [
            'id_penerimaan_barang' => $this->penerimaan_barang->generateID(),
            'tanggal' => date('Y-m-d'),
        ]);

        $penerimaan_barang_items = $this->getOnly([
            'id_claim_bad', 'id_claim_tidak_terima', 'id_gudang_bad', 'id_gudang_good',
            'id_part_int','id_part', 'id_penerimaan_barang', 'id_rak_bad', 'id_rak_good', 'no_dus',
            'qty_bad', 'qty_good', 'qty_tidak_terima', 'qty_ship', 'id_rak_int', 'id_gudang_int','serial_number'
        ], $this->input->post('parts'), [
            'id_penerimaan_barang' => $penerimaan_barang['id_penerimaan_barang'],
        ]);

        $good_receipt_data = [
            'id_reference' => $this->input->post('id_packing_sheet'),
            'ref_type' => 'packing_sheet_shipping_list',
            'id_good_receipt' => $this->good_receipt->generateGoodReceipt(),
            'tanggal_receipt' => date('Y-m-d'),
            'id_dealer' => $this->m_admin->cari_dealer(),
        ];
        $good_receipt_parts_data = $this->getOnly(true, $parts_good, [
            'id_good_receipt' => $good_receipt_data['id_good_receipt'],
        ]);

        $this->db->trans_begin();
        $this->penerimaan_barang->insert($penerimaan_barang);
        $this->penerimaan_barang_items->insert_batch($penerimaan_barang_items);

        if (count($good_receipt_parts_data) > 0) {
            $this->good_receipt->insert($good_receipt_data);
            $this->good_receipt_parts->insert_batch($good_receipt_parts_data);
        }

        foreach ($parts_good as $part) {
            $this->update_or_insert_stock($part, $penerimaan_barang['id_penerimaan_barang']);
        }

        foreach ($parts_bad as $part) {
            $this->update_or_insert_stock($part, $penerimaan_barang['id_penerimaan_barang']);
        }

        $purchase_order = $this->db
            ->select('po.po_id')
            ->select('po.po_type')
            ->select('po.id_booking')
            ->select('ps.id_packing_sheet')
            ->select('pl.id_picking_list')
            ->select('so.id_rekap_purchase_order_dealer')
            ->select('c.nama_customer')
            ->from('tr_h3_md_packing_sheet as ps')
            ->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
            ->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = pl.id_ref')
            ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
            ->join('tr_h3_dealer_purchase_order as po', 'so.id_ref = po.po_id')
            ->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po.id_booking', 'left')
            ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer', 'left')
            ->where('ps.id_packing_sheet', $this->input->post('id_packing_sheet'))
            ->limit(1)
            ->get()->row_array();

        $good_receipt_parts_data = (array) $this->db
            ->select('gdp.*, mp.nama_part')
            ->from('tr_h3_dealer_good_receipt_parts as gdp')
            ->join('tr_h3_dealer_good_receipt as gd', 'gd.id_good_receipt = gdp.id_good_receipt')
            ->join('ms_part as mp', 'mp.id_part = gdp.id_part')
            ->where('gdp.id_good_receipt', $good_receipt_data['id_good_receipt'])
            ->where('gd.id_reference', $purchase_order['id_packing_sheet'])
            ->get()->result();

        if ($purchase_order['id_rekap_purchase_order_dealer'] != null) {
            $parts = $this->db
                ->select('grp.id_part')
                ->select('grp.qty as qty_pemenuhan')
                ->from('tr_h3_dealer_good_receipt_parts as grp')
                ->where('grp.id_good_receipt', $good_receipt_data['id_good_receipt'])
                ->get()->result_array();

            foreach ($parts as $part) {
                $qty_order_fullfillment = $this->db
                    ->select('SUM(of.qty_fulfillment) AS qty_order_fulfillment')
                    ->from('tr_h3_dealer_order_fulfillment as of')
                    ->where('of.id_part = pop.id_part')
                    ->where('of.po_id = pop.po_id')
                    ->get_compiled_select();

                $po_yang_harus_dibagi = $this->db
                    ->select('pop.po_id')
                    ->select('pop.id_part')
                    ->select("(rpp.kuantitas - IFNULL( ({$qty_order_fullfillment}), 0)) as kuantitas", false)
                    ->from('tr_h3_dealer_purchase_order_parts as pop')
                    ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = pop.po_id')
                    ->join('tr_h3_md_rekap_purchase_order_dealer_parts as rpp', '(rpp.id_part = pop.id_part and rpp.po_id = pop.po_id)')
                    ->where('pop.id_part', $part['id_part'])
                    ->where('rpp.id_rekap', $purchase_order['id_rekap_purchase_order_dealer'])
                    ->order_by('po.proses_at', 'asc')
                    ->having('kuantitas > 0')
                    ->get()->result_array();

                $qty_pemenuhan = $part['qty_pemenuhan'];
                foreach ($po_yang_harus_dibagi as $row) {
                    if ($qty_pemenuhan == 0) break;

                    if ($row['kuantitas'] <= $qty_pemenuhan) {
                        $this->order_fulfillment->insert([
                            'po_id' => $row['po_id'],
                            'id_part' => $row['id_part'],
                            'qty_fulfillment' => $row['kuantitas'],
                            'id_referensi' => $penerimaan_barang['id_penerimaan_barang'],
                            'tipe_referensi' => $this->page
                        ]);
                        $qty_pemenuhan -= $row['kuantitas'];
                    } else if ($row['kuantitas'] > $qty_pemenuhan) {
                        $this->order_fulfillment->insert([
                            'po_id' => $row['po_id'],
                            'id_part' => $row['id_part'],
                            'qty_fulfillment' => $qty_pemenuhan,
                            'id_referensi' => $penerimaan_barang['id_penerimaan_barang'],
                            'tipe_referensi' => $this->page
                        ]);
                        $qty_pemenuhan -= $qty_pemenuhan;
                    }
                }
            }
        }

        foreach ($this->input->post('parts') as $part) {
            $this->order_fulfillment->insert([
                'po_id' => $this->input->post('nomor_po'),
                'id_part' => $part['id_part'],
                'qty_fulfillment' => $part['qty_ship'],
                'id_referensi' => $penerimaan_barang['id_penerimaan_barang'],
                'tipe_referensi' => $this->page
            ]);

            $this->db
            ->set('opt.qty_ship', "opt.qty_ship + {$part['qty_good']}", false)
            ->where('opt.po_id', $this->input->post('nomor_po'))
            ->where('opt.id_part', $part['id_part'])
            ->update('tr_h3_dealer_order_parts_tracking as opt');
        }

        $this->notify_finance_dms($purchase_order, $good_receipt_data['id_good_receipt']);

        if ($purchase_order['po_type'] == 'HLO' and $purchase_order['id_rekap_purchase_order_dealer'] == null) {
            $this->notify_front_desk_for_hotline_order_fulfillment($purchase_order, $good_receipt_parts_data, $good_receipt_data['id_good_receipt']);
        }

        $email_payload = $this->emailToPICFinance($purchase_order);

        if ($this->db->trans_status()) {
            $this->db->trans_commit();

            $shipping_list = (array) $this->penerimaan_barang->find($penerimaan_barang['id_penerimaan_barang'], 'id_penerimaan_barang');

            if ($shipping_list != null) {
                send_json([
                    'redirect_url' => base_url(sprintf('dealer/h3_dealer_shipping_list/detail?id=%s', $shipping_list['id_penerimaan_barang']))
                ]);
            }
        } else {
            $this->db->trans_rollback();
            if (!$email_payload['email_sent']) {
                send_json([
                    'error_type' => 'email_error',
                    'message' => $email_payload['additional_message']
                ], 422);
            }
        }
    }

    // Notify Finance in DMS.
    public function notify_finance_dms($purchase_order, $id_good_receipt)
    {
        $pesan = "Telah terjadi Good Receipt {$id_good_receipt} untuk Shipping List {$purchase_order['id_packing_sheet']}";

        $this->notifikasi->insert([
            'id_notif_kat' => $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'notify_finance_good_receipt')->get()->row()->id_notif_kat,
            'judul' => 'Good Receipt Purchase Order',
            'pesan' => $pesan,
            'link' => "dealer/h3_dealer_good_receipt/detail?id={$id_good_receipt}",
            'id_dealer' => $this->m_admin->cari_dealer(),
            'show_popup' => true,
        ]);
    }
    // Notify Front Desk for Hotline Order Fulfillment.
    public function notify_front_desk_for_hotline_order_fulfillment($purchase_order, $good_receipt_parts_data, $id_good_receipt)
    {
        $pesan = "Parts ";
        $firstPart = $good_receipt_parts_data[0];

        $pesan .= "[{$firstPart->id_part} - {$firstPart->nama_part}]";
        $jumlahItem = count($good_receipt_parts_data);
        if ($jumlahItem > 1) {
            $sisaItem = $jumlahItem - 1;
            $pesan .= " dan {$sisaItem} lainnya";
        }
        $pesan .= " atas pesanan {$purchase_order['nama_customer']} untuk PO nomor {$purchase_order['po_id']} telah diterima melalui Goods Receipt atas Shipping List Nomor {$purchase_order['id_packing_sheet']}.";

        $menu_kategori = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'notify_front_desk_hotline_fulfillment')->get()->row();
        $this->notifikasi->insert([
            'id_notif_kat' => $menu_kategori->id_notif_kat,
            'judul' => 'Penerimaan Purchase Order Hotline',
            'pesan' => $pesan,
            'link' => "{$menu_kategori->link}/detail?id={$purchase_order['po_id']}",
            'id_dealer' => $this->m_admin->cari_dealer(),
            'show_popup' => true,
        ]);
    }

    public function update_or_insert_stock($part, $referensi)
    {
        $transaksi_stock = [
            'id_part' => $part['id_part'],
            'id_gudang' => $part['id_gudang'],
            'id_rak' => $part['id_rak'],
            'tipe_transaksi' => '+',
            'sumber_transaksi' => $this->page,
            'referensi' => $referensi,
            'stok_value' => $part['qty'],
            'serial_number' => $part['serial_number'],
        ];
        $this->transaksi_stok->insert($transaksi_stock);

        //id_gudang dan id_rak int
        $gudang = $this->db->select('id as id_gudang_int')
                    ->from('ms_gudang_h23')
                    ->where('id_gudang', $part['id_gudang'])
                    ->get()->row_array();

        $rak = $this->db->select('id as id_rak_int')
                    ->from('ms_lokasi_rak_bin')
                    ->where('id_rak', $part['id_rak'])
                    ->get()->row_array();

        $stock_didalam_gudang = $this->stock->get([
            'id_part' => $part['id_part'],
            // 'id_part_int' => $part['id_part_int'],
            'id_gudang' => $part['id_gudang'],
            // 'id_gudang_int' => $gudang['id_gudang_int'],
            'id_rak' => $part['id_rak'],
            // 'id_rak_int' => $rak['id_rak_int'],
            'id_dealer' => $this->m_admin->cari_dealer(),
        ], true);

        // Cek apakah digudang ada record yang sama.
        if ($stock_didalam_gudang != null) {
            // Jika ada stock digudang tersebut ditambah.
            $this->stock->update([
                'stock' => $stock_didalam_gudang->stock + $part['qty'],
            ], [
                'id_part' => $part['id_part'],
                'id_part_int' => $part['id_part_int'],
                'id_gudang' => $part['id_gudang'],
                'id_rak' => $part['id_rak'],
                'id_dealer' => $this->m_admin->cari_dealer(),
            ]);
        } else {
            // Jika tidak, maka buat record stock di warehouse.
            $this->stock->insert([
                'id_part' => $part['id_part'],
                'id_part_int' => $part['id_part_int'],
                'id_gudang' => $part['id_gudang'],
                'id_rak' => $part['id_rak'],
                'id_gudang_int' => $gudang['id_gudang_int'],
                'id_rak_int' => $rak['id_rak_int'],
                'id_dealer' => $this->m_admin->cari_dealer(),
                'stock' => $part['qty']
            ]);
        }

        //Cek apakah EV atau Tidak 
        $kelompok_part = $this->db->select('kelompok_part')
				->from('ms_part')
				->where('id_part', $part['id_part'])
				->get()->row_array();

        
        if($kelompok_part['kelompok_part']=='EVBT' ||$kelompok_part['kelompok_part']=='EVCH'){
            // int penerimaan dealer 
            $id_penerimaan_barang_int = $this->db->select('id')
                ->select('created_at')
                ->select('created_by')
				->from('tr_h3_dealer_penerimaan_barang')
				->where('id_penerimaan_barang', $referensi)
				->get()->row_array();

            $this->db
				->set('id_penerimaan_dealer', $referensi)
				->set('id_gudang_dealer', $part['id_gudang'])
				->set('id_lokasi_rak_dealer', $part['id_rak'])
				->set('id_gudang_dealer_int', $gudang['id_gudang_int'])
				->set('id_lokasi_rak_dealer_int', $rak['id_rak_int'])
				->set('created_at_penerimaan_dealer', $id_penerimaan_barang_int['created_at'])
				->set('created_by_penerimaan_dealer', $id_penerimaan_barang_int['created_by'])
				->set('accStatus', 4)
                ->set('fifo_dealer', $this->generate_fifo())
				->where('id_part_int', $part['id_part_int'])	
				->where('serial_number', $part['serial_number'])
				->update('tr_h3_serial_ev_tracking');

            //Data Untuk insert status ev

            $accType ='';
			if($kelompok_part['kelompok_part']=='EVBT'){
				$accType ='B';
			}elseif($kelompok_part['kelompok_part']=='EVCH'){
				$accType ='C';
			}

            $status_ev = $this->db->select('mdReceiveDate')
                ->select('mdSLDate')
                ->select('mdSLNo')
                ->select('dealerCode')
                ->select('accStatus_2_processed_at')
                ->select('accStatus_2_processed_by_user')
                ->select('accStatus_3_processed_at')
                ->select('accStatus_3_processed_by_user')
                ->from('tr_status_ev_acc')
                ->where('serialNo', $part['serial_number'])
                ->where('accStatus', 3)
                ->limit(1)
                ->get()->row_array();

            //Insert tr_status_ev_acc
            $data_ev = array(
                'serialNo' =>  $part['serial_number'],
                'accType' => $accType,
                'accStatus' => 4,
                'mdReceiveDate' =>  $status_ev['mdReceiveDate'],
                'mdSLDate' => $status_ev['mdSLDate'],
                'mdSLNo' =>  $status_ev['mdSLNo'],
                'dealerCode' => $status_ev['dealerCode'],
                'dealerReceiveDate' => $id_penerimaan_barang_int['created_at'],
                'accStatus_2_processed_at' =>  $status_ev['accStatus_2_processed_at'],
                'accStatus_2_processed_by_user' =>  $status_ev['accStatus_2_processed_by_user'],
                'accStatus_3_processed_at' =>  $status_ev['accStatus_3_processed_at'],
                'accStatus_3_processed_by_user' => $status_ev['accStatus_3_processed_by_user'],
                'accStatus_4_processed_at' =>  $id_penerimaan_barang_int['created_at'],
                'accStatus_4_processed_by_user' => $id_penerimaan_barang_int['created_by'],
                'api_from' =>2,
                'last_updated' => date('Y-m-d H:i:s', time())
            );
            
            $this->db->insert('tr_status_ev_acc', $data_ev);

            //Insert data di table ev_log_send_api_3
			$data_ev_to_ahm = array(
				'serialNo' =>  $part['serial_number'],
				'accStatus' => 4,
				'created_at' =>  $id_penerimaan_barang_int['created_at'],
				'status_scan' => 1, 
			);
			
			$this->db->insert('ev_log_send_api_3', $data_ev_to_ahm);
        }
    }

    public function generate_fifo(){
		$bulan_short = date('m');
		$tahun_short = date('y');
		$tahun = date('Y');
        $tahun_dan_bulan    = date('Y-m');
        $id_dealer    = $this->m_admin->cari_dealer();
        
		$get_data  = $this->db->query("SELECT fifo_dealer FROM tr_h3_serial_ev_tracking WHERE LEFT(created_at_penerimaan_dealer,4)='$tahun' and id_dealer = $id_dealer ORDER BY created_at_penerimaan_dealer DESC LIMIT 0,1");

        if ($get_data->num_rows() > 0) {
            $row        = $get_data->row();
            $id_outbound_form_part_transfer = substr($row->fifo, -5);
            $new_kode   = $tahun . sprintf("%'.05d", $id_outbound_form_part_transfer + 1);
            $i = 0;
            while ($i < 1) {
                $cek = $this->db->get_where('tr_h3_serial_ev_tracking', ['fifo_dealer' => $new_kode])->num_rows();
                if ($cek > 0) {
                    $gen_number    = substr($new_kode, -5);
                    $new_kode = $tahun . sprintf("%'.05d", $gen_number + 1);
                    $i = 0;
                } else {
                    $i++;
                }
            }
        } else {
            $new_kode   = $tahun. '00001';
        }

        return strtoupper($new_kode);
	}

    public function detail()
    {
        $data['isi']   = $this->page;
        $data['title'] = 'Purchase Order';
        $data['mode']  = 'detail';
        $data['set']   = "form";

        $data['shipping_list'] = $this->db
            ->select('pb.*')
            ->select('pb.tanggal as tanggal')
            ->select('sp.tanggal as tanggal_surat_pengantar')
            ->select('ps.tgl_packing_sheet as tanggal_packing_sheet')
            ->select('po.po_id as nomor_po')
            ->select('po.tanggal_order as tanggal_po')
            ->select('ps.tgl_faktur as tanggal_faktur')
            ->select('ps.no_faktur as nomor_faktur')
            ->from('tr_h3_dealer_penerimaan_barang as pb')
            ->join('tr_h3_md_surat_pengantar as sp', 'sp.id_surat_pengantar = pb.id_surat_pengantar')
            ->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = pb.id_packing_sheet')
            ->join('tr_h3_md_picking_list as pl', 'ps.id_picking_list = pl.id_picking_list', 'left')
            ->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = pl.id_ref', 'left')
            ->join('tr_h3_md_sales_order as so', 'dso.id_sales_order = so.id_sales_order', 'left')
            ->join('tr_h3_dealer_purchase_order as po', 'so.id_ref = po.po_id', 'left')
            ->where('pb.id_penerimaan_barang', $this->input->get('id'))
            ->limit(1)
            ->get()->row_array();

        $qty_ship = $this->db
            ->select('SUM(plp.qty_disiapkan)')
            ->from('tr_h3_md_picking_list_parts as plp')
            ->where('plp.id_part = pbi.id_part')
            ->where('plp.id_picking_list = ps.id_picking_list')
            ->get_compiled_select();

        //Check apakah Packing Sheet ada lebih dari 1
        $check_ps = $this->db->select('count(id_packing_sheet) as id_packing_sheet')
                             ->from('tr_h3_md_packing_sheet')
                             ->where('id_packing_sheet',  $data['shipping_list']['id_packing_sheet'])
                             ->get()->row_array();

        if($check_ps['id_packing_sheet'] > 1){
            $data['penerimaan_barang_items'] = $this->db
                ->select('pbi.*')
                ->select('p.nama_part')
                ->select('c_bad.kode_claim as kode_claim_bad')
                ->select('c_bad.nama_claim as nama_claim_bad')
                ->select('c_tidak_terima.kode_claim as kode_claim_tidak_terima')
                ->select('c_tidak_terima.nama_claim as nama_claim_tidak_terima')
                ->from('tr_h3_dealer_penerimaan_barang_items as pbi')
                ->join('tr_h3_dealer_penerimaan_barang as pb', 'pb.id_penerimaan_barang = pbi.id_penerimaan_barang')
                ->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = pb.id_packing_sheet')
                ->join('ms_kategori_claim_c3 as c_bad', 'c_bad.id = pbi.id_claim_bad', 'left')
                ->join('ms_kategori_claim_c3 as c_tidak_terima', 'c_tidak_terima.id = pbi.id_claim_tidak_terima', 'left')
                ->join('ms_part as p', 'p.id_part = pbi.id_part')
                ->where('pbi.id_penerimaan_barang', $this->input->get('id'))
                ->group_by('pb.id_packing_sheet')
                ->group_by('pbi.id_part')
                ->get()->result();
        }else{
            $data['penerimaan_barang_items'] = $this->db
                ->select('pbi.*')
                ->select('p.nama_part')
                ->select('c_bad.kode_claim as kode_claim_bad')
                ->select('c_bad.nama_claim as nama_claim_bad')
                ->select('c_tidak_terima.kode_claim as kode_claim_tidak_terima')
                ->select('c_tidak_terima.nama_claim as nama_claim_tidak_terima')
                ->from('tr_h3_dealer_penerimaan_barang_items as pbi')
                ->join('tr_h3_dealer_penerimaan_barang as pb', 'pb.id_penerimaan_barang = pbi.id_penerimaan_barang')
                ->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = pb.id_packing_sheet')
                ->join('ms_kategori_claim_c3 as c_bad', 'c_bad.id = pbi.id_claim_bad', 'left')
                ->join('ms_kategori_claim_c3 as c_tidak_terima', 'c_tidak_terima.id = pbi.id_claim_tidak_terima', 'left')
                ->join('ms_part as p', 'p.id_part = pbi.id_part')
                ->where('pbi.id_penerimaan_barang', $this->input->get('id'))
                ->get()->result();
        }

        $this->template($data);
    }

    public function emailToPICFinance($purchase_order)
    {
        $data['purchase_order'] = $purchase_order;

        $part_bad = $this->db
            ->select('p.id_part')
            ->select('p.nama_part')
            ->select('s.satuan')
            ->select('pbi.qty_bad as qty')
            ->select('pop.harga_saat_dibeli')
            ->select('"Bad" as reason')
            ->from('tr_h3_dealer_penerimaan_barang as pb')
            ->join('tr_h3_dealer_penerimaan_barang_items as pbi', 'pb.id_penerimaan_barang = pbi.id_penerimaan_barang')
            ->join('tr_h3_md_packing_sheet as ps', 'pb.id_packing_sheet = ps.id_packing_sheet')
            ->join('tr_h3_md_picking_list as pl', 'ps.id_picking_list = pl.id_picking_list')
            ->join('tr_h3_md_do_sales_order as dso', 'pl.id_ref = dso.id_do_sales_order')
            ->join('tr_h3_md_sales_order as so', 'dso.id_sales_order = so.id_sales_order')
            ->join('tr_h3_dealer_purchase_order_parts as pop', '(so.id_ref = pop.po_id and pbi.id_part = pop.id_part)')
            ->join('ms_part as p', 'p.id_part = pbi.id_part')
            ->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
            ->where('pbi.qty_bad > 0')
            ->where('pb.id_packing_sheet', $purchase_order['id_packing_sheet'])
            ->get_compiled_select();

        $part_tidak_terima = $this->db
            ->select('p.id_part')
            ->select('p.nama_part')
            ->select('s.satuan')
            ->select('pbi.qty_tidak_terima as qty')
            ->select('pop.harga_saat_dibeli')
            ->select('"Not Received" as reason')
            ->from('tr_h3_dealer_penerimaan_barang as pb')
            ->join('tr_h3_dealer_penerimaan_barang_items as pbi', 'pb.id_penerimaan_barang = pbi.id_penerimaan_barang')
            ->join('tr_h3_md_packing_sheet as ps', 'pb.id_packing_sheet = ps.id_packing_sheet')
            ->join('tr_h3_md_picking_list as pl', 'ps.id_picking_list = pl.id_picking_list')
            ->join('tr_h3_md_do_sales_order as dso', 'pl.id_ref = dso.id_do_sales_order')
            ->join('tr_h3_md_sales_order as so', 'dso.id_sales_order = so.id_sales_order')
            ->join('tr_h3_dealer_purchase_order_parts as pop', '(so.id_ref = pop.po_id and pbi.id_part = pop.id_part)')
            ->join('ms_part as p', 'p.id_part = pbi.id_part')
            ->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
            ->where('pbi.qty_tidak_terima > 0')
            ->where('pb.id_packing_sheet', $purchase_order['id_packing_sheet'])
            ->get_compiled_select();

        $data['parts'] = $this->db->query("{$part_bad} union {$part_tidak_terima}")->result();

        $data['selisih'] = count($data['parts']) > 0;

        $cfg  = $this->db->get('setup_smtp_email')->row();
        $from = $this->db->get_where('ms_email_md', ['email_for' => 'notification'])->row();
        $config = array(
            'protocol' => 'mail',
            'smtp_host' => $cfg->smtp_host,
            'smtp_port' => 587,
            'smtp_user' => $from->email,
            'smtp_pass' => $from->pass,
            'mailtype'  => 'html',
            'charset'   => 'iso-8859-1'
        );

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");

        $dealerBersangkutan = $this->dealer->getCurrentUserDealer();
        $this->email->from($from->email, "[{$dealerBersangkutan->nama_dealer}]");

        $email_pic_finance = $this->db
            ->select('*')
            ->from('ms_user_group as ug')
            ->join('ms_user as u', 'u.id_user_group = ug.id_user_group')
            ->join('ms_karyawan_dealer as kd', 'kd.id_karyawan_dealer = u.id_karyawan_dealer')
            ->where('ug.code', 'pic_finance')
            ->where('kd.email !=', '')
            ->where('kd.id_dealer', $dealerBersangkutan->id_dealer)
            ->limit(1)
            ->get()->row();

        if ($email_pic_finance != null) {
            $email_pic_finance = $email_pic_finance->email;
        } else {
            $message = "[{$this->page}] Tidak terdapat PIC finance di dealer {$dealerBersangkutan->kode_dealer_md} - {$dealerBersangkutan->nama_dealer} ({$dealerBersangkutan->id_dealer}) ketika melakukan penerimaan barang untuk no. PO {$purchase_order['po_id']} dengan no. packing sheet {$purchase_order['id_packing_sheet']}";
            log_message('info', $message);
            return [
                'email_sent' => false,
                'additional_message' => $message
            ];
        }

        $this->email->to($email_pic_finance);

        $email_branch_manager = $this->db
            ->select('kd.email')
            ->from('ms_user_group as ug')
            ->join('ms_user as u', 'u.id_user_group = ug.id_user_group')
            ->join('ms_karyawan_dealer as kd', 'kd.id_karyawan_dealer = u.id_karyawan_dealer')
            ->where('ug.code', 'pic_dealer')
            ->where('kd.id_dealer', $dealerBersangkutan->id_dealer)
            ->get()
            ->row();

        if ($email_branch_manager != null) {
            $email_branch_manager = $email_branch_manager->email;
        } else {
            $message = "[{$this->page}] Tidak terdapat Branch Manager di dealer {$dealerBersangkutan->kode_dealer_md} - {$dealerBersangkutan->nama_dealer} ({$dealerBersangkutan->id_dealer}) ketika melakukan penerimaan barang untuk no. PO {$purchase_order['po_id']} dengan no. packing sheet {$purchase_order['id_packing_sheet']}";
            log_message('info', $message);
            return [
                'email_sent' => false,
                'additional_message' => $message
            ];
        }

        $this->email->cc($email_branch_manager);
        $this->email->subject("Notifikasi Penerimaan Parts dengan Shipping List no {$purchase_order['id_packing_sheet']}");
        $this->email->message($this->load->view('dealer/h3_dealer_email_shipping_list_finance', $data, true));

        if ($this->email->send()) {
            $message = "[{$this->page}] Berhasil mengirim penerimaan barang untuk no. PO {$purchase_order['po_id']} dengan no. packing sheet {$purchase_order['id_packing_sheet']} di dealer {$dealerBersangkutan->kode_dealer_md} - {$dealerBersangkutan->nama_dealer} ({$dealerBersangkutan->id_dealer}) kepada PIC finance dengan email {$email_pic_finance}";
            log_message('info', $message);
            return [
                'email_sent' => true,
                'additional_message' => $message
            ];
        } else {
            $message = "[{$this->page}] Gagal mengirim email penerimaan barang untuk no. PO {$purchase_order['po_id']} dengan no. packing sheet {$purchase_order['id_packing_sheet']} di dealer {$dealerBersangkutan->kode_dealer_md} - {$dealerBersangkutan->nama_dealer} ({$dealerBersangkutan->id_dealer}) kepada PIC finance dengan email {$email_pic_finance}";
            log_message('info', $message);
            return [
                'email_sent' => false,
                'additional_message' => 'Terdapat error pada konfigurasi email pada server.',
                'email_debug_message' => $this->email->print_debugger(),
            ];
        }
    }

    public function get_parts_by_packing_sheet()
    {
         //Cek apakah PENERIMAAN KPB atau tidak 
         $tipe_po = $this->db->select('so.kategori_po ')
                ->from('tr_h3_md_packing_sheet ps')
                ->join('tr_h3_md_picking_list pl','ps.id_picking_list_int=pl.id ')
                ->join('tr_h3_md_do_sales_order do','do.id = pl.id_ref_int')
                ->join('tr_h3_md_sales_order so','so.id = do.id_sales_order_int')
                ->where('ps.id_packing_sheet',$this->input->get('id_packing_sheet'))
                ->get()->row_array();

        if($tipe_po['kategori_po'] == 'KPB'){
            $this->db
                ->select('p.id_part_int')
                ->select('spl.id_part')
                ->select('SUM(spl.qty_scan) as qty_ship')
                ->select('SUM(spl.qty_scan) as qty_good')
                ->select('0 as qty_bad')
                ->select('0 as qty_tidak_terima')
                ->select('spl.no_dus')
                ->select('p.nama_part')
                ->select('"" as id_gudang_good')
                ->select('"" as id_rak_good')
                ->select('"" as id_gudang_bad')
                ->select('"" as id_rak_bad')
                ->select('"" as id_claim_bad')
                ->select('"" as kode_claim_bad')
                ->select('"" as nama_claim_bad')
                ->select('"" as id_claim_tidak_terima')
                ->select('"" as kode_claim_tidak_terima')
                ->select('"" as nama_claim_tidak_terima')
                ->select('dop.harga_jual as harga')
                ->select('dop.harga_setelah_diskon')
                ->select('spl.serial_number')
                ->from('tr_h3_md_packing_sheet as ps')
                ->join('tr_h3_md_picking_list as pl', 'ps.id_picking_list = pl.id_picking_list')
                ->join('tr_h3_md_scan_picking_list_parts as spl', 'ps.id_picking_list = spl.id_picking_list')
                ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
                ->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_do_sales_order = do.id_do_sales_order and dop.id_part = spl.id_part and dop.id_tipe_kendaraan=spl.id_tipe_kendaraan)')
                ->join('ms_part as p', 'p.id_part = spl.id_part')
                ->where('ps.id_packing_sheet', $this->input->get('id_packing_sheet'))
                ->group_by('spl.no_dus')
                ->group_by('spl.id_part')
                ->group_by('spl.serial_number')
                ->order_by('spl.no_dus');
        }else{
            $this->db
                ->select('p.id_part_int')
                ->select('spl.id_part')
                ->select('SUM(spl.qty_scan) as qty_ship')
                ->select('SUM(spl.qty_scan) as qty_good')
                ->select('0 as qty_bad')
                ->select('0 as qty_tidak_terima')
                ->select('spl.no_dus')
                ->select('p.nama_part')
                ->select('"" as id_gudang_good')
                ->select('"" as id_rak_good')
                ->select('"" as id_gudang_bad')
                ->select('"" as id_rak_bad')
                ->select('"" as id_claim_bad')
                ->select('"" as kode_claim_bad')
                ->select('"" as nama_claim_bad')
                ->select('"" as id_claim_tidak_terima')
                ->select('"" as kode_claim_tidak_terima')
                ->select('"" as nama_claim_tidak_terima')
                ->select('dop.harga_jual as harga')
                ->select('dop.harga_setelah_diskon')
                ->select('spl.serial_number')
                ->from('tr_h3_md_packing_sheet as ps')
                ->join('tr_h3_md_picking_list as pl', 'ps.id_picking_list = pl.id_picking_list')
                ->join('tr_h3_md_scan_picking_list_parts as spl', 'ps.id_picking_list = spl.id_picking_list')
                ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
                ->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_do_sales_order = do.id_do_sales_order and dop.id_part = spl.id_part)')
                ->join('ms_part as p', 'p.id_part = spl.id_part')
                ->where('ps.id_packing_sheet', $this->input->get('id_packing_sheet'))
                ->group_by('spl.no_dus')
                ->group_by('spl.id_part')
                ->group_by('spl.serial_number')
                ->order_by('spl.no_dus');
        }
        

        send_json($this->db->get()->result_array());
    }

    public function update_qty_ship()
    {
        $qty_ship = $this->db
            ->select('SUM(splp.qty_scan) as qty_scan', false)
            ->from('tr_h3_md_scan_picking_list_parts as splp')
            ->where('splp.id_picking_list = ps.id_picking_list', null, false)
            ->where('splp.id_part = pbi.id_part', null, false)
            ->where('splp.no_dus = pbi.no_dus', null, false)
            ->get_compiled_select();

        $data = $this->db
            ->select('pbi.*')
            ->select("IFNULL(({$qty_ship}), 0) as qty_ship")
            ->from('tr_h3_dealer_penerimaan_barang_items as pbi')
            ->join('tr_h3_dealer_penerimaan_barang as pb', 'pb.id_penerimaan_barang = pbi.id_penerimaan_barang')
            ->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = pb.id_packing_sheet')
            ->where('pbi.id_penerimaan_barang', 'PB/00888/2101/0003')
            ->get()->result_array();

        foreach ($data as $row) {
            $this->db
                ->set('pbi.qty_ship', $row['qty_ship'])
                ->where('pbi.id', $row['id'])
                ->update('tr_h3_dealer_penerimaan_barang_items as pbi');
        }
    }

    public function print_pdf(){
        $this->load->library('mpdf_l');
      $mpdf                           = $this->mpdf_l->load();
      $mpdf->allow_charset_conversion = false;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = false;

        
        $title  = 'PENERIMAAN-PARTS';
        $id = $_GET['id'];
        $data['data']=$this->db->query("SELECT pb.id_penerimaan_barang,DATE_FORMAT(pb.tanggal,'%d-%m-%Y') as tgl_penerimaan,pb.nomor_po, pb.id_surat_pengantar,pb.id_packing_sheet, ps.no_faktur 
            from tr_h3_dealer_penerimaan_barang pb 
            join tr_h3_md_packing_sheet ps on ps.id_packing_sheet=pb.id_packing_sheet 
            where pb.id_penerimaan_barang='$id'")->row_array();

        $data['sparepart']=$this->db->query("SELECT 
            pbi.id_part, mp.nama_part, pbi.qty_ship, pbi.qty_good, pbi.id_gudang_good, pbi.id_rak_good, 
            pbi.qty_bad, pbi.id_gudang_bad, pbi.id_rak_bad, pbi.qty_tidak_terima, no_dus, pbi.serial_number, (CASE WHEN mp.kelompok_part = 'EVBT' then 'Battery' WHEN mp.kelompok_part = 'EVCH' then 'Charger' else '' end) as type_acc, (CASE WHEN pbi.serial_number is not null THEN 1 ELSE pop.kuantitas END) as qty_po
            -- ,pop.kuantitas as qty_po
            from tr_h3_dealer_penerimaan_barang pb 
            join tr_h3_dealer_penerimaan_barang_items pbi on pbi.id_penerimaan_barang=pb.id_penerimaan_barang 
            join ms_part mp on mp.id_part_int=pbi.id_part_int 
            join tr_h3_dealer_purchase_order_parts pop on pop.id_part_int=mp.id_part_int and pop.po_id=pb.nomor_po 
            where pb.id_penerimaan_barang= '$id'")->result();

        $data['sparepart_bad']=$this->db->query("SELECT 
            pbi.id_part, mp.nama_part, pbi.qty_bad, pbi.id_gudang_bad, pbi.id_rak_bad, claim.nama_claim
            from tr_h3_dealer_penerimaan_barang_items pbi
            join ms_part mp on mp.id_part_int=pbi.id_part_int 
            join ms_kategori_claim_c3 claim on claim.id=pbi.id_claim_bad
            where pbi.id_penerimaan_barang= '$id'");

        $data['sparepart_tidak_diterima']=$this->db->query("SELECT 
            pbi.id_part, mp.nama_part, pbi.qty_tidak_terima, claim.nama_claim
            from tr_h3_dealer_penerimaan_barang_items pbi
            join ms_part mp on mp.id_part_int=pbi.id_part_int 
            join ms_kategori_claim_c3 claim on claim.id=pbi.id_claim_tidak_terima
            where pbi.id_penerimaan_barang= '$id'");


        $html = $this->load->view('dealer/h3_dealer_shipping_list_cetak', $data, true);
        $mpdf->WriteHTML($html);
        $output = $title . '.pdf';
        $mpdf->Output("$output", 'I');
        
        
        echo json_encode([
            "status"=>200,
            "message"=>"success"
            ]);
        
    }
}
