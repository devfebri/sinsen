<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_dealer_inbound_form_for_parts_return extends Honda_Controller
{
    protected $folder = "dealer";
    protected $page   = "h3_dealer_inbound_form_for_parts_return";
    protected $title  = "Inbound Form For Parts Return";

    public function __construct()
    {
        parent::__construct();
        //---- cek session -------//
        $name = $this->session->userdata('nama');
        if ($name=="") {
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
        }
        //===== Load Database =====
        $this->load->database();
        $this->load->helper('url');
        //===== Load Model =====
        $this->load->model('m_admin');
        $this->load->library('form_validation');
        $this->load->model('h3_dealer_outbound_form_for_fulfillment_model', 'outbound_form_for_fulfillment');
        $this->load->model('h3_dealer_outbound_form_for_fulfillment_parts_model', 'outbound_form_for_fulfillment_parts');
        $this->load->model('h3_dealer_inbound_form_for_parts_return_model', 'inbound_form_for_parts_return');
        $this->load->model('h3_dealer_inbound_form_for_parts_return_parts_model', 'inbound_form_for_parts_return_parts');
        $this->load->model('h3_dealer_inbound_form_for_parts_return_parts_reason_model', 'inbound_form_for_parts_return_parts_reason');
        $this->load->model('H3_dealer_gudang_h23_model', 'gudang');
        $this->load->model('H3_dealer_lokasi_rak_bin_model', 'rak');
        $this->load->model('Ms_part_model', 'part');
        $this->load->model('notifikasi_model', 'notifikasi');
        $this->load->model('h3_dealer_stock_model', 'stock');
        $this->load->model('event_model', 'event');
        $this->load->model('reasons_model', 'reasons');
        $this->load->model('H3_surat_jalan_outbound_model', 'surat_jalan');
        $this->load->model('H3_dealer_invoice_inbound_model', 'invoice_inbound');
        $this->load->model('H3_dealer_invoice_inbound_items_model', 'invoice_inbound_items');
        $this->load->model('h3_dealer_sales_order_model', 'sales_order');
        $this->load->model('h3_dealer_sales_order_parts_model', 'sales_order_parts');
        $this->load->model('h3_dealer_transaksi_stok_model', 'transaksi_stok');
    }

    public function index()
    {
        $data['set']	= "index";

        $data['inbound_form_for_parts_return'] = $this->inbound_form_for_parts_return->get([
            'id_dealer' => $this->m_admin->cari_dealer(),
        ]);

        $this->template($data);
    }

    public function add()
    {
        $data['kode_md'] = 'E22';
        $data['mode']    = 'insert';
        $data['set']     = "form";
        $data['gudang'] = $this->gudang->all();
        $data['reasons'] = $this->reasons->all();

        $this->template($data);
    }

    public function get_fulfillment_parts(){
        $this->db
        ->select('ofp.id_part')
        ->select('p.nama_part')
        ->select('ofp.id_gudang')
        ->select('ofp.id_rak')
        ->select('ofp.kuantitas as qty_book')
        ->select('ofp.kuantitas as qty_return')
        ->select('s.satuan')
        ->from('tr_h3_dealer_outbound_form_for_fulfillment_parts as ofp')
        ->join('ms_part as p', 'p.id_part = ofp.id_part')
        ->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
        ->where('ofp.id_outbound_form_for_fulfillment', $this->input->get('outbound_fulfillment'))
        ;

        $data = [];
        foreach ($this->db->get()->result_array() as $each) {
            $sub_array = $each;
            $sub_array['reason'] = [
                [
                    'reason' => 'Penjualan',
                    'action' => '1',
                    'qty' => '0',
                    'keterangan' => '',
                    'id_gudang' => '',
                    'id_rak' => ''
                ],
                [
                    'reason' => 'Kerusakan',
                    'action' => '1',
                    'qty' => '0',
                    'keterangan' => '',
                    'id_gudang' => '',
                    'id_rak' => ''
                ],
                [
                    'reason' => 'Kehilangan',
                    'action' => '1',
                    'qty' => '0',
                    'keterangan' => '',
                    'id_gudang' => '',
                    'id_rak' => ''
                ],
                [
                    'reason' => 'Tertukar',
                    'action' => '1',
                    'qty' => '0',
                    'keterangan' => '',
                    'id_gudang' => '',
                    'id_rak' => ''
                ],
                [
                    'reason' => 'Others',
                    'action' => '1',
                    'qty' => '0',
                    'keterangan' => '',
                    'id_gudang' => '',
                    'id_rak' => ''
                ],
            ];
            $data[] = $sub_array;
        }

        send_json($data);
    }

    public function save()
    {
        $this->db->trans_start();
        $inboundFormForPartsReturn = $this->input->post(['id_outbound_form']);
        $inboundFormForPartsReturn = array_merge($inboundFormForPartsReturn, [
            'id_inbound_form_for_parts_return' => $this->inbound_form_for_parts_return->generateID(),
            'id_dealer' => $this->m_admin->cari_dealer(),
        ]);
        
        $this->inbound_form_for_parts_return->insert($inboundFormForPartsReturn);
        
        $inboundFormForPartsReturnParts = $this->getOnly(true, $this->input->post('parts'), [
            'id_inbound_form_for_parts_return' => $inboundFormForPartsReturn['id_inbound_form_for_parts_return']
        ]);

        foreach ($this->input->post('parts') as $part) {
            $reason = count($part['reason']) > 0 ? $part['reason'] : [];
            unset($part['reason']);
            $part['id_inbound_form_for_parts_return'] = $inboundFormForPartsReturn['id_inbound_form_for_parts_return'];
            $this->inbound_form_for_parts_return_parts->insert($part);
            $id_inbound_part = $this->db->insert_id();

            if(count($reason) > 0){
                foreach ($reason as $each_reason) {
                    $each_reason['id_inbound_part'] = $id_inbound_part;
                    $this->inbound_form_for_parts_return_parts_reason->insert($each_reason);
                }
            }
        }

        if(count($this->input->post('invoices'))){
            foreach ($this->input->post('invoices') as $invoice) {
                $invoice_inbound = array_merge($invoice, [
                    'id_inbound_form_for_parts_return' => $inboundFormForPartsReturn['id_inbound_form_for_parts_return']
                ]);
    
                $invoice_parts = $invoice['parts'];
    
                unset($invoice_inbound['parts']);
    
                $this->invoice_inbound->insert($invoice_inbound);
    
                $id_invoice_inbound = $this->db->insert_id();
    
                $invoice_inbound_parts = $this->getOnly([
                    'id_part', 'harga', 'qty', 'diskon_value', 'tipe_diskon'
                ], $invoice_parts, [
                    'id_invoice_inbound' => $id_invoice_inbound
                ]);
    
                $this->invoice_inbound_items->insert_batch($invoice_inbound_parts);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $inbound = $this->inbound_form_for_parts_return->find($inboundFormForPartsReturn['id_inbound_form_for_parts_return'], 'id_inbound_form_for_parts_return');
            send_json($inbound);
        } else {
            $this->output->set_status_header(400);
        }
    }

    public function parts_transfer(){
        $this->db->trans_start();
        $this->inbound_form_for_parts_return->update([
            'status' => 'Closed'
        ], [
            'id_inbound_form_for_parts_return' => $this->input->post('id_inbound_form')
        ]);

        $parts = $this->inbound_form_for_parts_return_parts->get([
            'id_inbound_form_for_parts_return' => $this->input->post('id_inbound_form')
        ]);

        foreach ($parts as $part) {
            if($part->qty_return > 0){
                $this->tambah_atau_buat_stock([
                    'id_part' => $part->id_part,
                    'kuantitas' => $part->qty_return,
                    'id_gudang' => $part->id_gudang,
                    'id_rak' => $part->id_rak,
                ]);
            }
            
            $reasons = $this->inbound_form_for_parts_return_parts_reason->get([
                'id_inbound_part' => $part->id,
            ]);

            foreach ($reasons as $reason) {
                if($reason->reason == 'Kerusakan' and $reason->qty > 0){
                    $this->tambah_atau_buat_stock([
                        'id_part' => $part->id_part,
                        'kuantitas' => $reason->qty,
                        'id_gudang' => $reason->id_gudang,
                        'id_rak' => $reason->id_rak,
                    ]);
                }
            }
        }

        $invoices = $this->invoice_inbound->get([
            'id_inbound_form_for_parts_return' => $this->input->post('id_inbound_form')
        ]);
        foreach ($invoices as $invoice) {
            $invoice_parts = $this->invoice_inbound_items->get([
                'id_invoice_inbound' => $invoice->id
            ]);

            $sales_order_parts = [];
            foreach ($invoice_parts as $part) {
                $id_gudang = $this->gudang_asal_pada_outbound($part);
                $id_rak = $this->rak_asal_pada_outbound($part);

                $sales_order_parts[] = [
                    'nomor_so' => $this->sales_order->generateNomorSO(),
                    'id_part' => $part->id_part,
                    'harga_saat_dibeli' => $part->harga,
                    'kuantitas' => $part->qty,
                    'tipe_diskon' => $part->tipe_diskon,
                    'diskon_value' => $part->diskon_value,
                    'id_gudang' => $id_gudang,
                    'id_rak' => $id_rak,
                ];

                // $this->kurangi_stock([
                //     'id_part' => $part->id_part,
                //     'kuantitas' => $part->qty,
                //     'id_gudang' => $id_gudang,
                //     'id_rak' => $id_rak,
                // ], $this->sales_order->generateNomorSO());
            }

            $sales_order = [
                'nama_pembeli' => 'Customer Event',
                'no_hp_pembeli' => '--',
                'nomor_so' => $this->sales_order->generateNomorSO(),
                'id_dealer' => $this->m_admin->cari_dealer(),
                'tanggal_so' => date('Y-m-d'),
                'created_by' => $this->session->userdata('id_user'),
                'status' => 'Closed',
                'id_inbound_form_for_parts_return' => $this->input->post('id_inbound_form')
            ];

            $this->sales_order->insert($sales_order);
            $this->sales_order_parts->insert_batch($sales_order_parts);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $inbound = $this->inbound_form_for_parts_return->find($this->input->post('id_inbound_form'), 'id_inbound_form_for_parts_return');
            send_json($inbound);
        } else {
            $this->output->set_status_header(400);
        }
    }

    public function kurangi_stock($part, $referensi){
        $transaksi_stock = [
            'id_part' => $part['id_part'],
            'id_gudang' => $part['id_gudang'],
            'id_rak' => $part['id_rak'],
            'tipe_transaksi' => '-',
            'sumber_transaksi' => $this->page,
            'referensi' => $referensi,
            'stok_value' => $part['kuantitas']
        ];
        $this->transaksi_stok->insert($transaksi_stock);

        $this->db->set('stock', "stock - {$part['kuantitas']}", FALSE);
        $this->db->where([
            'id_part' => $part['id_part'],
            'id_gudang' => $part['id_gudang'],
            'id_rak' => $part['id_rak'],
            'id_dealer' => $this->m_admin->cari_dealer(),
        ]);
        $this->db->update('ms_h3_dealer_stock');
    }

    public function tambah_atau_buat_stock($part){
        $transaksi_stock = [
            'id_part' => $part['id_part'],
            'id_gudang' => $part['id_gudang'],
            'id_rak' => $part['id_rak'],
            'tipe_transaksi' => '+',
            'sumber_transaksi' => $this->page,
            'referensi' => $this->input->post('id_inbound_form'),
            'stok_value' => $part['kuantitas']
        ];
        $this->transaksi_stok->insert($transaksi_stock);

        $stock_digudang = $this->stock->get([
            'id_part' => $part['id_part'],
            'id_gudang' => $part['id_gudang'],
            'id_rak' => $part['id_rak'],
            'id_dealer' => $this->m_admin->cari_dealer(),
        ], true);

        if($stock_digudang != null){
            $this->db->set('stock', "stock + {$part['kuantitas']}", FALSE)
            ->where([
                'id_part' => $part['id_part'],
                'id_gudang' => $part['id_gudang'],
                'id_rak' => $part['id_rak'],
                'id_dealer' => $this->m_admin->cari_dealer(),
            ])
            ->update('ms_h3_dealer_stock');
        }else{
            $this->stock->insert([
                'id_part' => $part['id_part'],
                'id_gudang' => $part['id_gudang'],
                'id_rak' => $part['id_rak'],
                'id_dealer' => $this->m_admin->cari_dealer(),
                'stock' => $part['kuantitas']
            ]);
        }
    }

    public function gudang_asal_pada_outbound($part){
        return $this->db
        ->select('ofp.id_gudang')
        ->from('tr_h3_dealer_outbound_form_for_fulfillment_parts as ofp')
        ->where('ofp.id_outbound_form_for_fulfillment', $this->input->post('id_outbound_form'))
        ->where('ofp.id_part', $part->id_part)
        ->limit(1)
        ->get()->row()->id_gudang;
    }

    public function rak_asal_pada_outbound($part){
        return $this->db
        ->select('ofp.id_rak')
        ->from('tr_h3_dealer_outbound_form_for_fulfillment_parts as ofp')
        ->where('ofp.id_outbound_form_for_fulfillment', $this->input->post('id_outbound_form'))
        ->where('ofp.id_part', $part->id_part)
        ->limit(1)
        ->get()->row()->id_rak;
    }

    public function detail(){
        $data['mode']  = 'detail';
        $data['set']   = "form";
        
        $inbound_form_for_parts_return = $this->db
        ->select('i.*')
        ->select('date_format(i.created_at, "%d-%m-%Y") as tanggal_inbound')
        ->select('sj.id_surat_jalan')
        ->select('date_format(sj.created_at, "%d-%m-%Y") as tanggal_surat_jalan')
        ->select('sj.id_outbound_form')
        ->select('date_format(f.created_at, "%d-%m-%Y") as tanggal_outbound')
        ->select('e.nama as nama_event')
        ->select('e.id_event')
        ->from('tr_h3_dealer_inbound_form_for_parts_return as i')
        ->join('tr_h3_dealer_surat_jalan_outbound_form_for_fulfillment as sj', 'sj.id_outbound_form = i.id_outbound_form')
        ->join('tr_h3_dealer_outbound_form_for_fulfillment as f',' f.id_outbound_form_for_fulfillment = i.id_outbound_form')
        ->join('ms_h3_dealer_event_h23 as e', 'e.id_event = f.id_event')
        ->where('i.id_inbound_form_for_parts_return', $this->input->get('id'))
        ->limit(1)
        ->get()->row();

         $this->db
        ->select('ip.*')
        ->select('p.nama_part')
        ->from('tr_h3_dealer_inbound_form_for_parts_return_parts as ip')
        ->join('ms_part as p', 'p.id_part = ip.id_part')
        ->where('ip.id_inbound_form_for_parts_return', $inbound_form_for_parts_return->id_inbound_form_for_parts_return);

        foreach ($this->db->get()->result_array() as $part) {
            $sub_array = $part;
            $sub_array['reason'] = $this->db
            ->from('tr_h3_dealer_inbound_form_for_parts_return_parts_reason as ofpr')
            ->where('ofpr.id_inbound_part', $part['id'])
            ->get()->result_array();

            $data['parts'][] = $sub_array;
        }

        $data['invoices'] = $this->db
        ->select('ii.*')
        ->select('"" as parts')
        ->from('tr_h3_dealer_invoice_inbound as ii')
        ->where('ii.id_inbound_form_for_parts_return', $inbound_form_for_parts_return->id_inbound_form_for_parts_return)
        ->get()->result();

        $data['inbound_form'] = $inbound_form_for_parts_return;

        $this->template($data);
    }

    public function get_invoice_parts(){
        $result = $this->db
        ->select('p.nama_part')
        ->select('iii.*')
        ->from('tr_h3_dealer_invoice_inbound_items as iii')
        ->join('ms_part as p', 'p.id_part = iii.id_part')
        ->where('iii.id_invoice_inbound', $this->input->get('id'))
        ->get()->result();

        send_json([
            'index' => $this->input->get('index'),
            'result' => $result
        ]);
    }

    public function edit(){
        $data['mode']  = 'edit';
        $data['set']   = "form";
        
        $inbound_form_for_parts_return = $this->db
        ->select('i.*')
        ->select('date_format(i.created_at, "%d-%m-%Y") as tanggal_inbound')
        ->select('sj.id_surat_jalan')
        ->select('date_format(sj.created_at, "%d-%m-%Y") as tanggal_surat_jalan')
        ->select('sj.id_outbound_form')
        ->select('date_format(f.created_at, "%d-%m-%Y") as tanggal_outbound')
        ->select('e.nama as nama_event')
        ->select('e.id_event')
        ->from('tr_h3_dealer_inbound_form_for_parts_return as i')
        ->join('tr_h3_dealer_surat_jalan_outbound_form_for_fulfillment as sj', 'sj.id_outbound_form = i.id_outbound_form')
        ->join('tr_h3_dealer_outbound_form_for_fulfillment as f',' f.id_outbound_form_for_fulfillment = i.id_outbound_form')
        ->join('ms_h3_dealer_event_h23 as e', 'e.id_event = f.id_event')
        ->where('i.id_inbound_form_for_parts_return', $this->input->get('id'))
        ->limit(1)
        ->get()->row();

         $this->db
        ->select('ip.*')
        ->select('p.nama_part')
        ->from('tr_h3_dealer_inbound_form_for_parts_return_parts as ip')
        ->join('ms_part as p', 'p.id_part = ip.id_part')
        ->where('ip.id_inbound_form_for_parts_return', $inbound_form_for_parts_return->id_inbound_form_for_parts_return);

        foreach ($this->db->get()->result_array() as $part) {
            $sub_array = $part;
            $sub_array['reason'] = $this->db
            ->from('tr_h3_dealer_inbound_form_for_parts_return_parts_reason as ofpr')
            ->where('ofpr.id_inbound_part', $part['id'])
            ->get()->result_array();

            $data['parts'][] = $sub_array;
        }

        $data['invoices'] = $this->db
        ->select('ii.*')
        ->select('"" as parts')
        ->from('tr_h3_dealer_invoice_inbound as ii')
        ->where('ii.id_inbound_form_for_parts_return', $inbound_form_for_parts_return->id_inbound_form_for_parts_return)
        ->get()->result();

        $data['inbound_form'] = $inbound_form_for_parts_return;

        $this->template($data);
    }

    public function update()
    {
        $this->db->trans_start();
        
        foreach ($this->input->post('parts') as $part) {
            $reason = count($part['reason']) > 0 ? $part['reason'] : [];
            $id_inbound_part = $part['id'];
            unset($part['id']);
            unset($part['reason']);
            $part['id_inbound_form_for_parts_return'] = $this->input->post('id_inbound_form');
            $this->inbound_form_for_parts_return_parts->update($part, [
                'id_part' => $part['id_part'],
                'id_inbound_form_for_parts_return' => $this->input->post('id_inbound_form')
            ]);

            if(count($reason) > 0){
                foreach ($reason as $each_reason) {
                    $id_reason = $each_reason['id'];
                    unset($each_reason['id']);
                    $this->inbound_form_for_parts_return_parts_reason->update($each_reason, [
                        'id_inbound_part' => $id_inbound_part,
                        'id' => $id_reason
                    ]);
                }
            }
        }

        $invoices = $this->invoice_inbound->get([
            'id_inbound_form_for_parts_return' => $this->input->post('id_inbound_form')
        ]);

        // Hapus invoice sebelumnya
        foreach ($invoices as $invoice) {
            $this->invoice_inbound_items->delete($invoice->id, 'id_invoice_inbound');
        }
        $this->invoice_inbound->delete($this->input->post('id_inbound_form'), 'id_inbound_form_for_parts_return');

        // Masukkan invoice yang baru
        if(count($this->input->post('invoices'))){
            foreach ($this->input->post('invoices') as $invoice) {
                $invoice_inbound = array_merge($invoice, [
                    'id_inbound_form_for_parts_return' => $this->input->post('id_inbound_form')
                ]);
    
                $invoice_parts = $invoice['parts'];
    
                unset($invoice_inbound['parts']);
    
                $this->invoice_inbound->insert($invoice_inbound);
    
                $id_invoice_inbound = $this->db->insert_id();
    
                $invoice_inbound_parts = $this->getOnly([
                    'id_part', 'harga', 'qty', 'diskon_value', 'tipe_diskon'
                ], $invoice_parts, [
                    'id_invoice_inbound' => $id_invoice_inbound
                ]);
    
                $this->invoice_inbound_items->insert_batch($invoice_inbound_parts);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $inbound = $this->inbound_form_for_parts_return->find($this->input->post('id_inbound_form'), 'id_inbound_form_for_parts_return');
            send_json($inbound);
        } else {
            $this->output->set_status_header(500);
        }
    }

    public function cetak_report_summary()
	{
		    $this->load->library('mpdf_l');
			$mpdf                           = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in               = 'UTF-8';
            $mpdf->autoLangToFont           = true;
            
            $data['inbound'] = $this->db
            ->select('ofpr.id_inbound_form_for_parts_return as nomor_inbound')
            ->select('date_format(ofpr.created_at, "%d-%m-%Y") as tanggal_transaksi')
            ->select('sj.id_surat_jalan as surat_jalan')
            ->select('e.id_event')
            ->select('ofpp.id_outbound_form_for_fulfillment as nomor_outbound')
            ->from('tr_h3_dealer_inbound_form_for_parts_return as ofpr')
            ->join('tr_h3_dealer_outbound_form_for_fulfillment as ofpp', 'ofpp.id_outbound_form_for_fulfillment = ofpr.id_outbound_form')
            ->join('tr_h3_dealer_surat_jalan_outbound_form_for_fulfillment as sj', 'sj.id_outbound_form = ofpp.id_outbound_form_for_fulfillment')
            ->join('ms_h3_dealer_event_h23 as e', 'e.id_event = ofpp.id_event')
            ->where('ofpr.id_inbound_form_for_parts_return', $this->input->get('id'))
            ->limit(1)
            ->get()->row()
            ;

            $qty_penjualan = $this->db
            ->select('ifprpr.qty')
            ->from('tr_h3_dealer_inbound_form_for_parts_return_parts_reason as ifprpr')
            ->where('ifprpr.id_inbound_part = ifprp.id')
            ->where('ifprpr.reason', 'Penjualan')
            ->get_compiled_select();
            
            $qty_kerusakan = $this->db
            ->select('ifprpr.qty')
            ->from('tr_h3_dealer_inbound_form_for_parts_return_parts_reason as ifprpr')
            ->where('ifprpr.id_inbound_part = ifprp.id')
            ->where('ifprpr.reason', 'Kerusakan')
            ->get_compiled_select();

            $qty_kehilangan = $this->db
            ->select('ifprpr.qty')
            ->from('tr_h3_dealer_inbound_form_for_parts_return_parts_reason as ifprpr')
            ->where('ifprpr.id_inbound_part = ifprp.id')
            ->where('ifprpr.reason', 'Kehilangan')
            ->get_compiled_select();

            $qty_tertukar = $this->db
            ->select('ifprpr.qty')
            ->from('tr_h3_dealer_inbound_form_for_parts_return_parts_reason as ifprpr')
            ->where('ifprpr.id_inbound_part = ifprp.id')
            ->where('ifprpr.reason', 'Tertukar')
            ->get_compiled_select();

            $qty_others = $this->db
            ->select('ifprpr.qty')
            ->from('tr_h3_dealer_inbound_form_for_parts_return_parts_reason as ifprpr')
            ->where('ifprpr.id_inbound_part = ifprp.id')
            ->where('ifprpr.reason', 'Others')
            ->get_compiled_select();

            $data['parts'] = $this->db
            ->select('p.id_part')
            ->select('p.nama_part')
            ->select('ifprp.qty_book')
            ->select("({$qty_penjualan}) as qty_penjualan")
            ->select("({$qty_kerusakan}) as qty_kerusakan")
            ->select("({$qty_kehilangan}) as qty_kehilangan")
            ->select("({$qty_tertukar}) as qty_tertukar")
            ->select("({$qty_others}) as qty_others")
            ->select('ifprp.qty_return')
            ->from('tr_h3_dealer_inbound_form_for_parts_return_parts as ifprp')
            ->join('ms_part as p', 'p.id_part = ifprp.id_part')
            ->where('ifprp.id_inbound_form_for_parts_return', $this->input->get('id'))
            ->get()->result();
        	
        	$html = $this->load->view('dealer/h3_dealer_report_summary_inbound_parts_return', $data, true);
        	// render the view into HTML
	        $mpdf->WriteHTML($html);
	        // write the HTML into the mpdf
	        $output = "1.pdf";
	        $mpdf->Output($output, 'I');
        
    }
    
    public function check_stock(){
        $stock = $this->db
        ->select('SUM(ds.stock) as stock')
        ->from('ms_h3_dealer_stock as ds')
        ->where('ds.id_dealer', 103)
        ->where('ds.id_part = p.id_part', null, false)
        ->get_compiled_select();

        $this->db
        ->select('p.id_part')
        ->select("IFNULL(({$stock}), 0) as stocks")
        ->from('ms_part as p')
        ->where("({$stock}) = 0", null, false)
        ;

        echo $this->db->get_compiled_select(); die();
    }
}
