<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_dealer_sales_order extends Honda_Controller
{
    public $tables = "tr_h3_dealer_sales_order";
    public $folder = "dealer";
    public $page   = "h3_dealer_sales_order";
    public $title  = "Sales Order";

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
        $this->load->library('form_validation');
        $this->load->model('h3_dealer_purchase_order_model', 'purchase_order');
        $this->load->model('h3_dealer_customer_h23_model', 'customer_h23');
        $this->load->model('h3_dealer_sales_order_model', 'sales_order');
        $this->load->model('h3_dealer_sales_order_parts_model', 'sales_order_parts');
        $this->load->model('h3_dealer_picking_slip_model', 'picking_slip');
        $this->load->model('customer_model', 'customer');
        $this->load->model('ms_part_model', 'ms_part');
        $this->load->model('h2_dealer_work_order_parts_model', 'work_order_parts');
        $this->load->model('h3_dealer_invoice_model', 'invoice');
        $this->load->model('h3_dealer_invoice_parts_model', 'invoice_parts');
        $this->load->model('h3_dealer_stock_model', 'dealer_stock');
    }

    public function index()
    {
        $data['set']    = "index";
        $data['sales_order'] = $this->sales_order->get([
            'id_dealer' => $this->m_admin->cari_dealer()
        ]);
        $this->template($data);
    }

    public function add()
    {
        $data['kode_md'] = 'E20';
        $data['mode']    = 'insert';
        $data['set']     = "form";
        $data['satuan'] = $this->db->from('ms_satuan')->get()->result();

        $this->template($data);
    }

    public function get_part_promo()
    {
        $data = $this->promo_query($this->input->post('id_part'), $this->input->post('kelompok_part'));
        send_json($data);
    }

    public function promo_query($id_part, $kelompok_part)
    {
        $now = date('Y-m-d', time());

        $this->db
            ->select('prm.*')
            ->select('date_format(prm.start_date, "%d/%m/%Y") as start_date')
            ->select('date_format(prm.end_date, "%d/%m/%Y") as end_date')
            ->select('prmi.tipe_disc')
            ->select('prmi.disc_value')
            ->from('ms_h3_promo_dealer as prm')
            ->join('ms_h3_promo_dealer_items as prmi', 'prm.id_promo = prmi.id_promo')
            ->group_start()
            ->where('prmi.id_part', $id_part)
            ->or_where('prmi.kelompok_part', $kelompok_part)
            ->group_end()
            ->where("'{$now}' between prm.start_date and prm.end_date", null, false)
            ->group_by('prm.id_promo')
            ->order_by('prm.created_at', 'desc');

        $data = [];
        $index = 0;
        foreach ($this->db->get()->result_array() as $each) {
            $sub_array = $each;

            $hadiah_master = $this->db
                ->from('ms_h3_promo_dealer_hadiah as h')
                ->where('h.id_promo', $sub_array['id_promo'])
                ->where('h.id_items', null)
                ->get()->result_array();

            $sub_array['gifts'] = count($hadiah_master) > 0 ? $hadiah_master : [];

            $this->db
                ->from('ms_h3_promo_dealer_items as prmi')
                ->where('prmi.id_promo', $each['id_promo'])
                ->order_by('prmi.qty', 'desc');

            $promo_items = [];
            foreach ($this->db->get()->result_array() as $promo_item) {
                $sub_array_item = $promo_item;
                $hadiah_item  = $hadiah_master = $this->db
                    ->from('ms_h3_promo_dealer_hadiah as h')
                    ->where('h.id_promo', $sub_array_item['id_promo'])
                    ->where('h.id_items', $sub_array_item['id'])
                    ->get()->result_array();

                $sub_array_item['gifts'] = count($hadiah_item) > 0 ? $hadiah_item : [];
                $promo_items[] = $sub_array_item;
            }

            $sub_array['promo_items'] = $promo_items;

            $data[$index] = $sub_array;
            $index++;
        }

        return $data;
    }

    public function get_purchase_parts()
    {
        $id_gudang = $this->db
            ->select('ds.id_gudang')
            ->from('ms_h3_dealer_stock as ds')
            ->group_start()
            ->where('ds.id_part = pop.id_part')
            ->where('ds.id_dealer', $this->m_admin->cari_dealer())
            ->group_end()
            ->order_by('ds.stock', 'desc')
            ->limit(1)
            ->get_compiled_select();

        $id_rak = $this->db
            ->select('ds.id_rak')
            ->from('ms_h3_dealer_stock as ds')
            ->group_start()
            ->where('ds.id_part = pop.id_part')
            ->where('ds.id_dealer', $this->m_admin->cari_dealer())
            ->group_end()
            ->order_by('ds.stock', 'desc')
            ->limit(1)
            ->get_compiled_select();

        $stock = $this->db
            ->select('ds.stock')
            ->from('ms_h3_dealer_stock as ds')
            ->group_start()
            ->where('ds.id_part = pop.id_part')
            ->where('ds.id_dealer', $this->m_admin->cari_dealer())
            ->group_end()
            ->order_by('ds.stock', 'desc')
            ->limit(1)
            ->get_compiled_select();

        $this->db
            ->select('pop.kuantitas')
            ->select('pop.harga_saat_dibeli')
            ->select('p.nama_part')
            ->select('p.id_part_int')
            ->select('p.id_part')
            ->select('p.kelompok_part')
            ->select("({$id_gudang}) as id_gudang")
            ->select("({$id_rak}) as id_rak")
            ->select("({$stock}) as stock")
            ->select('s.satuan')
            ->select('"" as tipe_diskon')
            ->from('tr_h3_dealer_purchase_order_parts as pop')
            ->join('ms_part as p', 'p.id_part = pop.id_part')
            ->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
            ->where('pop.po_id', $this->input->get('id'));

        $data = $this->db->get()->result_array();

        $parts = [];
        foreach ($data as $each) {
            $subArray = $each;
            $subArray['promo'] = $this->promo_query($each['id_part'], $each['kelompok_part']);
            $subArray['selected_promo'] = [];

            $parts[] = $subArray;
        }
        $data = $parts;

        send_json($data);
    }

    public function get_purchase_parts_test()
    {
        $this->db
            ->select('po.id_dealer')
            ->select('pop.kuantitas')
            ->select('pop.harga_saat_dibeli')
            ->select('p.nama_part')
            ->select('p.id_part_int')
            ->select('p.id_part')
            ->select('p.kelompok_part')
            ->select('s.satuan')
            ->select('"" as tipe_diskon')
            ->from('tr_h3_dealer_purchase_order_parts as pop')
            ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = pop.po_id')
            ->join('ms_part as p', 'p.id_part = pop.id_part')
            ->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
            ->where('pop.po_id', $this->input->get('id'));

        $parts = [];
        foreach ($this->db->get()->result_array() as $row) {

            $stock = $this->db
                ->select('ds.stock')
                ->select('ds.id_rak')
                ->select('ds.id_gudang')
                ->from('ms_h3_dealer_stock as ds')
                ->group_start()
                ->where('ds.id_part', $row['id_part'])
                ->where('ds.id_dealer', $this->m_admin->cari_dealer())
                ->group_end()
                ->order_by('ds.stock', 'desc')
                ->limit(1)
                ->get()->row_array();

            if ($stock != null) {
                $row['id_gudang'] = $stock['id_gudang'];
                $row['id_rak'] = $stock['id_rak'];
                $row['stock'] = $stock['stock'];
            } else {
                $row['id_gudang'] = null;
                $row['id_rak'] = null;
                $row['stock'] = 0;
            }

            $row['promo'] = $this->promo_query($row['id_part'], $row['kelompok_part']);
            $row['selected_promo'] = [];


            if ($row['kuantitas'] <= $row['stock']) {
                $parts[] = $row;
            }
        }

        send_json($parts);
    }

    public function get_parts_by_booking()
    {
        $kuantitas_sudah_dibuatkan_so = $this->db
            ->select('SUM(sop.kuantitas-sop.kuantitas_return) as kuantitas')
            ->from('tr_h3_dealer_sales_order as so')
            ->join('tr_h3_dealer_sales_order_parts as sop', 'sop.nomor_so = so.nomor_so')
            ->where('sop.id_part = rdp.id_part', null, false)
            ->where('so.id_dealer = rd.id_dealer', null, false)
            ->where('so.booking_id_reference = rd.id_booking', null, false)
            ->where('so.status !=', 'Canceled')
            ->where('so.status !=', 'Rejected')
            ->get_compiled_select();

        $this->db
            ->select('rdp.kuantitas')
            // ->select("IFNULL(({$kuantitas_sudah_dibuatkan_so}), 0) as kuantitas_sudah_dibuatkan_so", false)
            ->select("(rdp.kuantitas - IFNULL(({$kuantitas_sudah_dibuatkan_so}), 0)) as kuantitas_boleh_dibuatkan_so", false)
            ->select('rdp.harga_saat_dibeli')
            ->select('p.id_part_int')
            ->select('p.id_part')
            ->select('p.nama_part')
            ->select('p.kelompok_part')
            ->select('s.satuan')
            ->select('"" as tipe_diskon')
            ->select('"" as diskon_value')
            ->from('tr_h3_dealer_request_document as rd')
            ->join('tr_h3_dealer_request_document_parts as rdp', 'rd.id_booking = rdp.id_booking')
            ->join('ms_part as p', 'p.id_part = rdp.id_part')
            ->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
            ->where('rd.id_booking', $this->input->get('id'))
            ->having('kuantitas_boleh_dibuatkan_so >', 0);

        $parts = [];
        foreach ($this->db->get()->result_array() as $part) {
            $qty_avs = $this->dealer_stock->qty_avs($this->m_admin->cari_dealer(), 'ds.id_part', 'ds.id_gudang', 'ds.id_rak', [$this->input->get('id')], true);
            $stock = $this->db
                ->select('ds.*')
                ->select("IFNULL(({$qty_avs}), 0) as qty_avs", false)
                ->from('ms_h3_dealer_stock as ds')
                ->where('ds.id_part', $part['id_part'])
                ->where('ds.freeze', 0)
                ->where('ds.id_dealer', $this->m_admin->cari_dealer())
                ->order_by('qty_avs', 'desc')
                ->having('qty_avs >', 0)
                ->limit(1)
                ->get()->row_array();

            if ($stock != null) {
                $part['stock'] = $stock['qty_avs'];
                $part['id_gudang'] = $stock['id_gudang'];
                $part['id_rak'] = $stock['id_rak'];
            } else {
                $part['stock'] = 0;
                $part['id_gudang'] = null;
                $part['id_rak'] = null;
            }

            // Check Promo
            $part['promo'] = $this->promo_query($part['id_part'], $part['kelompok_part']);
            if (count($part['promo']) == 1) {
                $part['selected_promo'] = $part['promo'][0];
            } else {
                $part['selected_promo'] = [];
            }

            if ($part['stock'] > 0) {
                $parts[] = $part;
            }
        }
        $data = $parts;

        send_json($data);
    }

    public function get_booking_reference()
    {
        $this->db
            ->select('rd.*')
            ->select('c.id_customer_int')
            ->select('c.nama_customer')
            ->select('c.alamat')
            ->select('c.no_hp')
            ->select('c.no_mesin')
            ->select('c.no_rangka')
            ->from('tr_h3_dealer_purchase_order as po')
            ->join('tr_h3_dealer_request_document as rd', 'po.id_booking = rd.id_booking')
            ->join('ms_customer_h23 as c', 'c.id_customer=rd.id_customer')
            ->where('po.po_id', $this->input->get('id'));

        send_json($this->db->get()->row());
    }

    public function get_customer()
    {
        $this->db
            // ->select('c.*')
            ->select('d.nama_dealer')
            ->select('d.no_telp')
            ->select('d.alamat')
            ->from('tr_h3_dealer_purchase_order as po')
            ->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
            ->join('tr_h3_dealer_request_document as rd', 'po.id_booking = rd.id_booking')
            ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer')
            ->where('po.po_id', $this->input->get('id'));
        send_json($this->db->get()->row());
    }

    public function validate()
    {
        $this->form_validation->set_error_delimiters('', '');
        // $this->form_validation->set_rules('kategori_po', 'Kategori Purchase Order', 'required');
        $this->form_validation->set_rules('nama_pembeli', 'Nama Pembeli', 'required');
        $this->form_validation->set_rules('no_hp_pembeli', 'Nomor HP Pembeli', 'required');
        $this->form_validation->set_rules('alamat_pembeli', 'Alamat Pembeli', 'required');

        if ($this->input->post('pembelian_dari_dealer_lain') == '1') {
            // $this->form_validation->set_rules('id_customer', 'Customer', 'required');
            $this->form_validation->set_rules('booking_id_reference', 'Booking Reference', 'required');
        }
        
        //Pengecekan apakah pembeli EV tersebut terdaftar sebagai motor EV 
        $id_customer = $this->input->post('id_customer');
        $cek_customer_ev = $this->db->query("SELECT is_ev from ms_customer_h23 where id_customer ='$id_customer'")->row_array();

        if($cek_customer_ev['is_ev'] != '1' || $cek_customer_ev['is_ev'] == null || $cek_customer_ev['is_ev'] == 0){
            $parts = $this->input->post('parts');
            foreach($parts as $part){
                //Cek kelompok part
                $cek_kel_part = $this->db->query("SELECT kelompok_part from ms_part where id_part_int ='".$part['id_part_int'] ."'")->row_array();
                if($cek_kel_part['kelompok_part']=='EVBT' ||$cek_kel_part['kelompok_part']=='EVCH' ){
                    send_json([
                        'error_type' => 'validation_error',
                        'message' => 'Tidak dapat diproses karena bukan Customer EV'
                    ], 422);
                }
               
            }
        }

        // if (!$this->form_validation->run()) {
        //     $this->output->set_status_header(400);
        //     send_json([
        //         'error_type' => 'validation_error',
        //         'message' => 'Data tidak valid',
        //         'errors' => $this->form_validation->error_array()
        //     ]);
        // }

        if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
    }

    public function save()
    {
        $this->validate();
        $this->db->trans_start();

        $salesOrderData = $this->input->post([
            'id_customer', 'id_customer_int', 'id_work_order', 'uang_muka',
            'booking_id_reference', 'pembelian_dari_dealer_lain', 'id_dealer_pembeli', 'nama_pembeli',
            'no_hp_pembeli', 'alamat_pembeli', 'total_tanpa_ppn', 'disc_so',
        ]);
        $salesOrderData = $this->clean_data($salesOrderData);

        if (isset($salesOrderData['booking_id_reference']) and $salesOrderData['booking_id_reference'] != '' and isset($salesOrderData['pembelian_dari_dealer_lain']) and $salesOrderData['pembelian_dari_dealer_lain'] == 1) {
            $this->purchase_order->update([
                'status' => 'Processed',
                'proses_at' => date('Y-m-d H:i:s', time()),
                'proses_by' => $this->session->userdata('id_user')
            ], [
                'id_booking' => $salesOrderData['booking_id_reference']
            ]);
        }

        if (!isset($salesOrderData['id_customer']) or (!isset($salesOrderData['pembelian_dari_dealer_lain']) and $salesOrderData['pembelian_dari_dealer_lain'] == 1)) {
            $customer_data = [
                'id_customer' => $this->customer_h23->generateIdCustomer(true),
                'nama_customer' => $salesOrderData['nama_pembeli'],
                'no_hp' => $salesOrderData['no_hp_pembeli'],
                'alamat' => $salesOrderData['alamat_pembeli'],
                'is_direct_sales' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('id_user'),
                'id_dealer' => $this->m_admin->cari_dealer()
            ];

            $salesOrderData['id_customer'] = $customer_data['id_customer'];

            if ($salesOrderData['pembelian_dari_dealer_lain'] == 1) {
                $dealer = $this->db
                    ->from('ms_dealer')
                    ->where('id_dealer', $salesOrderData['id_dealer_pembeli'])
                    ->get()->row();

                $this->db
                    ->set('po.status', 'Processed')
                    ->where('po.id_booking', $this->input->post('booking_id_reference'))
                    ->update('tr_h3_dealer_purchase_order as po');

                $customer_data['id_customer'] = $dealer->kode_dealer_md;
                $customer_data['is_dealer'] = 1;
            }

            $customer = $this->db
                ->from('ms_customer_h23 as c')
                ->where('c.no_hp', $salesOrderData['no_hp_pembeli'])
                ->get()->row();

            if ($customer == null) {
                $this->customer_h23->insert($customer_data);
                $salesOrderData['id_customer_int'] = $this->db->insert_id();
            } else {
                $salesOrderData['id_customer'] = $customer->id_customer;
                $salesOrderData['id_customer_int'] = $customer->id_customer_int;
            }
        }

        $salesOrderData = array_merge($salesOrderData, [
            'nomor_so' => $this->sales_order->generateNomorSO(),
            'id_dealer' => $this->m_admin->cari_dealer(),
            'tanggal_so' => date('Y-m-d', time()),
        ]);

        if ($this->input->post('source') != null and $this->input->post('source') == 'wo') {
            $salesOrderData['status'] = 'Processing';
        }

        $nomor_so_int = $this->sales_order->insert($salesOrderData);

        $salesOrderPartsData = $this->getOnly([
            'kuantitas', 'harga_saat_dibeli', 'id_part', 'id_part_int', 'id_gudang',
            'id_rak', 'diskon_value', 'tipe_diskon', 'id_promo', 'nomor_so', 'harga_setelah_diskon'
        ], $this->input->post('parts'), [
            'nomor_so' => $salesOrderData['nomor_so'],
            'nomor_so_int' => $nomor_so_int
        ]);

        $parts = [];
        foreach ($salesOrderPartsData as $part) {
            if ($part['tipe_diskon'] == 'Percentage') {
                $part['disc_percentage'] = $part['diskon_value'];
                $part['disc_amount'] = null;
                $part['disc_foc'] = null;
            } else if ($part['tipe_diskon'] == 'Value') {
                $part['disc_amount'] = $part['diskon_value'];
                $part['disc_percentage'] = null;
                $part['disc_foc'] = null;
            } else if ($part['tipe_diskon'] == 'FoC') {
                $part['disc_foc'] = $part['diskon_value'];
                $part['disc_percentage'] = null;
                $part['disc_amount'] = null;
            }

            $this->sales_order_parts->insert($part);
            $parts[] = $part;

            $kelompok_part = $this->db->select('kelompok_part')
            ->from('ms_part')
            ->where('id_part_int', $part['id_part_int'])
            ->get()->row_array();

            $qty_harus_dipenuhi = $part['kuantitas'];
            if($kelompok_part['kelompok_part']=='EVBT' ||$kelompok_part['kelompok_part']=='EVCH'){
                // Type ACC 
				if($kelompok_part['kelompok_part'] == 'EVBT'){
					$type_acc = 'B';
				}elseif($kelompok_part['kelompok_part'] == 'EVCH'){
					$type_acc = 'C';
				}

                $stocks = $this->db->select('ts.serial_number')
									   ->select('ts.fifo_dealer')
									   ->select('1 as qty')
									   ->from('tr_h3_serial_ev_tracking as ts')
									   ->where('ts.accStatus',4)
									   ->where('ts.type_accesories',$type_acc)
									   ->where('id_lokasi_rak_dealer',$part['id_rak'])
									   ->where('id_part_int',$part['id_part_int'])
									   ->where('id_gudang_dealer',$part['id_gudang'])
									   ->group_start()
										->where('ts.no_so_wo_booking',null)
										->or_where('ts.no_so_wo_booking',0)
										->or_where('ts.no_so_wo_booking','')
									   ->group_end()
									   ->order_by('ts.fifo_dealer','ASC')
									   ->get()->result_array();

                foreach ($stocks as $stock) {
                    if($qty_harus_dipenuhi == 0) break;
                    $serial_number = $stock['serial_number'];
                    if($stock['qty'] <=$qty_harus_dipenuhi){
                        $qty_harus_dipenuhi -= $stock['qty'];
                    }else{
                        $qty_harus_dipenuhi -= $qty_harus_dipenuhi;
                    }

                    // Update Status id_do_sales_order di tr_h3_serial_ev_tracking 
                    $customer = $this->db->select('no_mesin')
                            ->select('no_rangka')
                            ->from('ms_customer_h23')
                            ->where('id_customer', $salesOrderData['id_customer'])
                            ->get()->row_array();
                
                    $this->db->set('no_so_wo_booking',  $salesOrderData['nomor_so'])
                        ->set('id_customer', $salesOrderData['id_customer'])
                        ->set('nama_customer', $salesOrderData['nama_pembeli'])
                        ->set('no_hp', $salesOrderData['no_hp_pembeli'])
                        ->set('no_mesin', $customer['no_mesin'])
                        ->set('no_rangka', $customer['no_rangka'])
                        ->where('id_part_int', $part['id_part_int'])	
                        ->where('serial_number', $serial_number)
                        ->update('tr_h3_serial_ev_tracking');

                    $data_so_ev = array(
                            'nomor_so' => $salesOrderData['nomor_so'],
                            'nomor_so_int' => $nomor_so_int,
                            'id_part' => $part['id_part'],
                            'id_part_int' => $part['id_part_int'],
                            'serial_number' => $serial_number,
                            'is_return' => 0
                    );
                    
                    $this->db->insert('tr_h3_dealer_sales_order_serial_ev', $data_so_ev);    
                }		
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            send_json($this->sales_order->find($salesOrderData['nomor_so'], 'nomor_so'));
        } else {
            $this->output->set_status_header(500);
        }
    }

    public function save_2()
    {
        $this->validate();
        $this->db->trans_start();

        $salesOrderData = $this->input->post([
            'id_customer', 'id_customer_int', 'id_work_order', 'uang_muka',
            'booking_id_reference', 'pembelian_dari_dealer_lain', 'id_dealer_pembeli', 'nama_pembeli',
            'no_hp_pembeli', 'alamat_pembeli', 'total_tanpa_ppn', 'disc_so',
        ]);
        $salesOrderData = $this->clean_data($salesOrderData);

        if (isset($salesOrderData['booking_id_reference']) and $salesOrderData['booking_id_reference'] != '' and isset($salesOrderData['pembelian_dari_dealer_lain']) and $salesOrderData['pembelian_dari_dealer_lain'] == 1) {
            $this->purchase_order->update([
                'status' => 'Processed',
                'proses_at' => date('Y-m-d H:i:s', time()),
                'proses_by' => $this->session->userdata('id_user')
            ], [
                'id_booking' => $salesOrderData['booking_id_reference']
            ]);
        }

        if (!isset($salesOrderData['id_customer']) or (!isset($salesOrderData['pembelian_dari_dealer_lain']) and $salesOrderData['pembelian_dari_dealer_lain'] == 1)) {
            $customer_data = [
                'id_customer' => $this->customer_h23->generateIdCustomer(true),
                'nama_customer' => $salesOrderData['nama_pembeli'],
                'no_hp' => $salesOrderData['no_hp_pembeli'],
                'alamat' => $salesOrderData['alamat_pembeli'],
                'is_direct_sales' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('id_user'),
                'id_dealer' => $this->m_admin->cari_dealer()
            ];

            $salesOrderData['id_customer'] = $customer_data['id_customer'];

            if ($salesOrderData['pembelian_dari_dealer_lain'] == 1) {
                $dealer = $this->db
                    ->from('ms_dealer')
                    ->where('id_dealer', $salesOrderData['id_dealer_pembeli'])
                    ->get()->row();

                $this->db
                    ->set('po.status', 'Processed')
                    ->where('po.id_booking', $this->input->post('booking_id_reference'))
                    ->update('tr_h3_dealer_purchase_order as po');

                $customer_data['id_customer'] = $dealer->kode_dealer_md;
                $customer_data['is_dealer'] = 1;
            }

            $customer = $this->db
                ->from('ms_customer_h23 as c')
                ->where('c.no_hp', $salesOrderData['no_hp_pembeli'])
                ->get()->row();

            if ($customer == null) {
                $this->customer_h23->insert($customer_data);
                $salesOrderData['id_customer_int'] = $this->db->insert_id();
            } else {
                $salesOrderData['id_customer'] = $customer->id_customer;
                $salesOrderData['id_customer_int'] = $customer->id_customer_int;
            }
        }

        $salesOrderData = array_merge($salesOrderData, [
            'nomor_so' => $this->sales_order->generateNomorSO(),
            'id_dealer' => $this->m_admin->cari_dealer(),
            'tanggal_so' => date('Y-m-d', time()),
        ]);

        if ($this->input->post('source') != null and $this->input->post('source') == 'wo') {
            $salesOrderData['status'] = 'Processing';
        }

        $nomor_so_int = $this->sales_order->insert($salesOrderData);

        $salesOrderPartsData = $this->getOnly([
            'kuantitas', 'harga_saat_dibeli', 'id_part', 'id_part_int', 'id_gudang',
            'id_rak', 'diskon_value', 'tipe_diskon', 'id_promo', 'nomor_so', 'harga_setelah_diskon'
        ], $this->input->post('parts'), [
            'nomor_so' => $salesOrderData['nomor_so'],
            'nomor_so_int' => $nomor_so_int
        ]);

        $parts = [];
        foreach ($salesOrderPartsData as $part) {
            if ($part['tipe_diskon'] == 'Percentage') {
                $part['disc_percentage'] = $part['diskon_value'];
                $part['disc_amount'] = null;
                $part['disc_foc'] = null;
            } else if ($part['tipe_diskon'] == 'Value') {
                $part['disc_amount'] = $part['diskon_value'];
                $part['disc_percentage'] = null;
                $part['disc_foc'] = null;
            } else if ($part['tipe_diskon'] == 'FoC') {
                $part['disc_foc'] = $part['diskon_value'];
                $part['disc_percentage'] = null;
                $part['disc_amount'] = null;
            }

            $this->sales_order_parts->insert($part);
            $parts[] = $part;
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            send_json($this->sales_order->find($salesOrderData['nomor_so'], 'nomor_so'));
        } else {
            $this->output->set_status_header(500);
        }
    }

    public function detail()
    {
        $data['mode']  = 'detail';
        $data['set']   = "form";
        $data['sales_order'] = $this->db
            ->select('so.*')
            ->select('date_format(so.tanggal_so, "%d-%m-%Y") as tanggal_so')
            ->select('po.po_id')
            ->select('picking_slip.nomor_ps')
            ->select('picking_slip.id as picking_slip_id')
            ->select('
            case
                when so.pembelian_dari_dealer_lain = 1 then 0
                when so.id_work_order is null then 1
                else wodw.stats = "end"
            end as wo_end
        ', false)
            ->from('tr_h3_dealer_sales_order as so')
            ->join('tr_h2_wo_dealer_waktu as wodw', 'wodw.id_work_order = so.id_work_order', 'left')
            ->join('tr_h3_dealer_picking_slip as picking_slip', 'picking_slip.nomor_so = so.nomor_so', 'left')
            ->join('ms_customer_h23 as c', 'c.id_customer = so.id_customer', 'left')
            ->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = so.booking_id_reference', 'left')
            ->join('tr_h3_dealer_purchase_order as po', '(po.id_booking = rd.id_booking and po.po_type = "HLO")', 'left')
            ->where('so.nomor_so', $this->input->get('k'))
            ->limit(1)
            ->get()->row();

        $data['parts'] = $this->db
            ->select('sop.*')
            ->select('p.nama_part')
            ->select('p.kelompok_part')
            ->select('s.satuan')
            ->from('tr_h3_dealer_sales_order_parts as sop')
            ->join('ms_part as p', 'p.id_part_int = sop.id_part_int')
            ->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
            ->where('sop.nomor_so', $this->input->get('k'))
            ->get()->result_array();


        $parts = [];
        foreach ($data['parts'] as $each) {
            $subArray = $each;
            $subArray['promo'] = $this->promo_query($each['id_part'], $each['kelompok_part']);

            $selected_promo = $this->db->from('ms_h3_promo_dealer as pd')->where('pd.id_promo', $each['id_promo'])->get()->row_array();
            if ($selected_promo != null) {
                $hadiah_master = $this->db
                    ->from('ms_h3_promo_dealer_hadiah as h')
                    ->where('h.id_promo', $selected_promo['id_promo'])
                    ->where('h.id_items', null)
                    ->get()->result_array();

                $selected_promo['gifts'] = count($hadiah_master) > 0 ? $hadiah_master : [];

                $selected_promo['promo_items'] = $this->db->from('ms_h3_promo_dealer_items as pdi')->where('pdi.id_promo', $each['id_promo'])->get()->result_array();

                $this->db
                    ->from('ms_h3_promo_dealer_items as prmi')
                    ->where('prmi.id_promo', $selected_promo['id_promo'])
                    ->order_by('prmi.qty', 'desc');

                $promo_items = [];
                foreach ($this->db->get()->result_array() as $promo_item) {
                    $sub_array_item = $promo_item;
                    $hadiah_item  = $hadiah_master = $this->db
                        ->from('ms_h3_promo_dealer_hadiah as h')
                        ->where('h.id_promo', $sub_array_item['id_promo'])
                        ->where('h.id_items', $sub_array_item['id'])
                        ->get()->result_array();

                    $sub_array_item['gifts'] = count($hadiah_item) > 0 ? $hadiah_item : [];
                    $promo_items[] = $sub_array_item;
                }

                $selected_promo['promo_items'] = $promo_items;

                $subArray['selected_promo'] = $selected_promo;
            } else {
                $subArray['selected_promo'] = [];
            }

            $parts[] = $subArray;
        }
        $data['parts'] = $parts;

        $this->template($data);
    }

    public function proses()
    {
        $this->db->trans_start();

        $id = $this->input->get('id');

        $sales_order = (array) $this->sales_order->find($id);

        $this->sales_order->update([
            'status' => 'Processing'
        ], [
            'id' => $id
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $_SESSION['pesan']     = "Sales Order Akan diproses.";
            $_SESSION['tipe']     = "info";
            echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/$this->page/detail?k={$sales_order['nomor_so']}'>";
        } else {
            $_SESSION['pesan']     = "Data not found !";
            $_SESSION['tipe']     = "danger";
            echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/$this->page'>";
        }
    }

    public function cancel()
    {
        $this->db->trans_start();

        $this->sales_order->update([
            'status' => 'Canceled'
        ], [
            'nomor_so' => $this->input->get('k')
        ]);

        $cek = $this->db->query("SELECT COUNT(no_so_wo_booking) AS no_so_wo_booking FROM tr_h3_serial_ev_tracking WHERE no_so_wo_booking = '".$this->input->get('k')."'", array($this->input->get('k')))->row_array();
    
        if ($cek['no_so_wo_booking'] > 0) {

            $this->db->set('no_so_wo_booking', null);
            $this->db->set('id_customer', null);
            $this->db->set('nama_customer', null);
            $this->db->set('no_hp', null);
            $this->db->set('no_mesin', null);
            $this->db->set('no_rangka', null);
            $this->db->where('no_so_wo_booking',$this->input->get('k'));
            $this->db->update('tr_h3_serial_ev_tracking');

            $this->db->where('nomor_so', $this->input->get('k'));
            $this->db->delete('tr_h3_dealer_sales_order_serial_ev');
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $_SESSION['pesan']     = "Sales Order telah dicancel.";
            $_SESSION['tipe']     = "info";
            echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/$this->page/detail?k={$this->input->get('k')}'>";
        } else {
            $_SESSION['pesan']     = "Data not found !";
            $_SESSION['tipe']     = "danger";
            echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/$this->page'>";
        }
    }

    public function edit()
    {
        $data['set']    = "form";
        $data['mode']  = 'edit';

        $data['sales_order'] = $this->db
            ->select('so.*')
            ->select('date_format(so.tanggal_so, "%d-%m-%Y") as tanggal_so')
            ->select('po.po_id')
            ->select('
            case
                when so.pembelian_dari_dealer_lain = 1 then 0
                when so.id_work_order is null then 0
                else wodw.stats = "end"
            end as wo_end
        ', false)
            ->from('tr_h3_dealer_sales_order as so')
            ->join('tr_h2_wo_dealer_waktu as wodw', 'wodw.id_work_order = so.id_work_order', 'left')
            ->join('ms_customer_h23 as c', 'c.id_customer = so.id_customer', 'left')
            ->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = so.booking_id_reference', 'left')
            ->join('tr_h3_dealer_purchase_order as po', '(po.id_booking = rd.id_booking and po.po_type = "HLO")', 'left')
            ->where('so.nomor_so', $this->input->get('k'))
            ->limit(1)
            ->get()->row();

        $data['parts'] = $this->db
            ->select('sop.*')
            ->select('p.nama_part')
            ->select('p.kelompok_part')
            ->select('s.satuan')
            ->from('tr_h3_dealer_sales_order_parts as sop')
            ->join('ms_part as p', 'p.id_part_int = sop.id_part_int')
            ->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
            ->where('sop.nomor_so', $this->input->get('k'))
            ->get()->result_array();

        $parts = [];
        foreach ($data['parts'] as $each) {
            $subArray = $each;
            $subArray['promo'] = $this->promo_query($each['id_part'], $each['kelompok_part']);

            $selected_promo = $this->db->from('ms_h3_promo_dealer as pd')->where('pd.id_promo', $each['id_promo'])->get()->row_array();
            if ($selected_promo != null) {
                $hadiah_master = $this->db
                    ->from('ms_h3_promo_dealer_hadiah as h')
                    ->where('h.id_promo', $selected_promo['id_promo'])
                    ->where('h.id_items', null)
                    ->get()->result_array();

                $selected_promo['gifts'] = count($hadiah_master) > 0 ? $hadiah_master : [];

                $selected_promo['promo_items'] = $this->db->from('ms_h3_promo_dealer_items as pdi')->where('pdi.id_promo', $each['id_promo'])->get()->result_array();

                $this->db
                    ->from('ms_h3_promo_dealer_items as prmi')
                    ->where('prmi.id_promo', $selected_promo['id_promo'])
                    ->order_by('prmi.qty', 'desc');

                $promo_items = [];
                foreach ($this->db->get()->result_array() as $promo_item) {
                    $sub_array_item = $promo_item;
                    $hadiah_item  = $hadiah_master = $this->db
                        ->from('ms_h3_promo_dealer_hadiah as h')
                        ->where('h.id_promo', $sub_array_item['id_promo'])
                        ->where('h.id_items', $sub_array_item['id'])
                        ->get()->result_array();

                    $sub_array_item['gifts'] = count($hadiah_item) > 0 ? $hadiah_item : [];
                    $promo_items[] = $sub_array_item;
                }

                $selected_promo['promo_items'] = $promo_items;

                $subArray['selected_promo'] = $selected_promo;
            } else {
                $subArray['selected_promo'] = [];
            }

            $parts[] = $subArray;
        }
        $data['parts'] = $parts;

        $this->template($data);
    }

    public function update()
    {
        $salesOrderData = $this->input->post([
            'id_customer',
            'id_work_order',
            'uang_muka',
            'booking_id_reference',
            'pembelian_dari_dealer_lain',
            'id_dealer_pembeli',
            'total_tanpa_ppn',
            'disc_so',
        ]);

        $salesOrderPartsData = $this->getOnly(true, $this->input->post('parts'), $this->input->post(['nomor_so']));
        $parts = [];
        foreach ($salesOrderPartsData as $part) {
            $part['nomor_so_int'] = $this->input->post('nomor_so_int');
            if ($part['tipe_diskon'] == 'Percentage') {
                $part['disc_percentage'] = $part['diskon_value'];
                $part['disc_amount'] = null;
                $part['disc_foc'] = null;
            } else if ($part['tipe_diskon'] == 'Value') {
                $part['disc_amount'] = $part['diskon_value'];
                $part['disc_percentage'] = null;
                $part['disc_foc'] = null;
            } else if ($part['tipe_diskon'] == 'FoC') {
                $part['disc_foc'] = $part['diskon_value'];
                $part['disc_percentage'] = null;
                $part['disc_amount'] = null;
            }

            if ($part['tipe_diskon'] == '') {
                $part['tipe_diskon'] = null;
            }

            $parts[] = $part;

            //Cek apakah EV atau Tidak untuk update SO Booking
            $kelompok_part = $this->db->select('kelompok_part')
            ->from('ms_part')
            ->where('id_part_int', $part['id_part_int'])
            ->get()->row_array();

            if($kelompok_part['kelompok_part']=='EVBT' ||$kelompok_part['kelompok_part']=='EVCH'){
                $this->db->set('no_so_wo_booking',  $this->input->post(['nomor_so']))
                    ->where('id_part_int', $part['id_part_int'])	
                    ->where('serial_number', $part['serial_number'])
                    ->update('tr_h3_serial_ev_tracking');
            }
        }

        $this->db->trans_start();
        $this->sales_order->update($salesOrderData, $this->input->post(['nomor_so']));
        $this->sales_order_parts->update_batch($parts, $this->input->post(['nomor_so']));
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            send_json($this->sales_order->get($this->input->post(['nomor_so']), true));
        } else {
            $this->output->set_status_header(500);
        }
    }

    public function generate_dummy_so()
    {
        $this->db->trans_start();
        $backWeek = rand(1, 6) * 7;
        $tanggal_so = new DateTime(date('Y-m-d'));
        // $tanggal_so->modify("-9 day");
        $tanggal_so->modify("-{$backWeek} day");
        $salesOrderData = [
            // 'id_customer' => $this->db->from('ms_customer_h23')->order_by('RAND()')->limit(1)->get()->row()->id_customer,
            'nama_pembeli' => 'Dummy Pembeli',
            'no_hp_pembeli' => '08080808080808',
            'uang_muka' => rand(500000, 1000000),
            'nomor_so' => $this->sales_order->generateNomorSO($tanggal_so),
            'id_dealer' => $this->m_admin->cari_dealer(),
            'tanggal_so' => $tanggal_so->format('Y-m-d'),
            'created_by' => $this->session->userdata('id_user'),
        ];

        $satuan = $this->db->select('id_satuan')->from('ms_satuan')->order_by('RAND()')->limit(1)->get_compiled_select();
        $dummy_part = $this->db
            ->select("mp.id_part, mp.harga_dealer_user as harga_saat_dibeli, ds.id_rak, 'Percentage' as tipe_diskon, FLOOR(RAND()*(10-1)+1) as diskon_value")
            ->select("FLOOR(RAND()*(10-1)+1) as kuantitas")
            // ->select('100 as kuantitas')
            ->select("({$satuan}) as id_satuan")
            ->from('ms_h3_dealer_stock as ds')
            ->join('ms_part as mp', 'mp.id_part = ds.id_part')
            // ->where('ds.id_part', 'Z613018M')
            ->order_by('RAND()')
            // ->limit(1)
            ->get()->result();

        $salesOrderPartsData = [];
        foreach ($dummy_part as $each) {
            $salesOrderPartsData[] = array_merge((array) $each, [
                'nomor_so' => $salesOrderData['nomor_so']
            ]);
        }

        $this->sales_order->insert($salesOrderData);
        $this->sales_order_parts->insert_batch($salesOrderPartsData);
        $this->picking_slip->insert([
            'nomor_ps' => $this->picking_slip->generateNomorPickingSlip(),
            'nomor_so' => $salesOrderData['nomor_so'],
            'tanggal_ps' => date('Y-m-d'),
            'id_dealer' => $this->m_admin->cari_dealer(),
        ]);
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            die('Berhasil');
        } else {
            die('Gagal');
        }
    }

    public function hitung_harga_setelah_diskon()
    {
        $this->db
            ->from('tr_h3_dealer_sales_order_parts as sop');

        foreach ($this->db->get()->result_array() as $row) {
            $harga_setelah_diskon = $row['harga_saat_dibeli'];

            if ($row['tipe_diskon'] == 'Percentage') {
                $potongan_harga = (intval($row['diskon_value']) / 100) * floatval($row['harga_saat_dibeli']);
                $harga_setelah_diskon -= $potongan_harga;
            } elseif ($row['tipe_diskon'] == 'Value') {
                $harga_setelah_diskon -= intval($row['diskon_value']);
            }

            $this->db
                ->set('sop.harga_setelah_diskon', $harga_setelah_diskon)
                ->where('sop.id', $row['id'])
                ->update('tr_h3_dealer_sales_order_parts as sop');
        }
    }

    public function update_harga()
    {
        $nomor_so = $this->input->get('nomor_so');

        $this->load->model('h3_dealer_sales_order_model', 'sales_order');
        $this->sales_order->update_harga($nomor_so);

        redirect(
            base_url(sprintf('dealer/h3_dealer_sales_order/detail?k=%s', $nomor_so))
        );
    }
}
