<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_create_submit_good_receipt extends Honda_Controller {
	var $tables = "tr_h3_dealer_good_receipt";	
	var $folder = "dealer";
	var $page   = "h3_dealer_create_submit_good_receipt";
	var $title  = "Create and Submit Good Receipt";

	public function __construct()
	{		
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('h3_dealer_request_document_model', 'request_document');
		$this->load->model('h3_dealer_purchase_order_model', 'purchase_order');		
		$this->load->model('h3_dealer_sales_order_model', 'sales_order');		
		$this->load->model('h3_dealer_good_receipt_model', 'good_receipt');		
		$this->load->model('h3_dealer_good_receipt_parts_model', 'good_receipt_parts');		
		$this->load->model('h3_dealer_shipping_list_model', 'shipping_list');		
		$this->load->model('h3_dealer_shipping_list_parts_model', 'shipping_list_parts');		
		$this->load->model('customer_model', 'customer');		
		$this->load->model('dealer_model', 'dealer');		
		$this->load->model('ms_part_model', 'ms_part');		
		$this->load->model('h3_dealer_gudang_h23_model', 'gudang_h23');
		$this->load->model('h3_dealer_stock_model', 'stock');
		$this->load->model('h3_dealer_lokasi_rak_bin_model', 'lokasi_rak_bin');
		$this->load->model('h3_dealer_transaksi_stok_model', 'transaksi_stok');
        $this->load->model('H3_dealer_order_fulfillment_model', 'order_fulfillment');
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "index";
		$data['good_receipt'] = $this->good_receipt->get([
			'id_dealer' => $this->m_admin->cari_dealer(),
		]);
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

	public function get_referensi_parts(){
		$id_gudang = $this->db
		->select('ds.id_gudang')
		->from('ms_h3_dealer_stock as ds')
		->where('ds.id_dealer', $this->m_admin->cari_dealer())
		->where('ds.id_part_int = p.id_part_int')
		->order_by('ds.stock', 'desc')
		->limit(1)
		->get_compiled_select();

		$id_rak = $this->db
		->select('ds.id_rak')
		->from('ms_h3_dealer_stock as ds')
		->where('ds.id_dealer', $this->m_admin->cari_dealer())
		->where('ds.id_part_int = p.id_part_int')
		->order_by('ds.stock', 'desc')
		->limit(1)
		->get_compiled_select();

		$part_sudah_diterima = $this->db
		->select('SUM(grp.qty) as qty', false)
		->from('tr_h3_dealer_good_receipt as gr')
		->join('tr_h3_dealer_good_receipt_parts as grp', 'grp.id_good_receipt = gr.id_good_receipt')
		->where('gr.id_reference = nscp.no_nsc', null, false)
		->where('grp.id_part_int = nscp.id_part_int', null, false)
		->get_compiled_select();

		$this->db
		->select('p.id_part_int')
		->select('p.id_part')
		->select('p.nama_part')
		->select('s.satuan')
		->select("({$id_gudang}) as id_gudang")
		->select("({$id_rak}) as id_rak")
		->select('nscp.qty as qty_po')
		->select('nscp.qty')
		->select("(nscp.qty - IFNULL(({$part_sudah_diterima}), 0)) as qty_boleh_terima", false)
		->select("IFNULL(({$part_sudah_diterima}), 0) as part_sudah_diterima", false)
		->select('sop.harga_saat_dibeli as harga')
		->select('sop.harga_setelah_diskon')
		->from('tr_h23_nsc_parts as nscp')
		->join('tr_h23_nsc as nsc', 'nsc.no_nsc = nscp.no_nsc')
		->join('tr_h3_dealer_sales_order_parts as sop', '(sop.nomor_so = nsc.id_referensi AND sop.id_part = nscp.id_part)')
		->join('ms_part as p', 'p.id_part_int = nscp.id_part_int')
		->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
		->where('nscp.no_nsc', $this->input->post('id_referensi'))
		;
		
		send_json(
			$this->db->get()->result_array()
		);
	}

	public function get_referensi_parts_by_po(){
		$id_gudang = $this->db
		->select('ds.id_gudang')
		->from('ms_h3_dealer_stock as ds')
		->where('ds.id_dealer', $this->m_admin->cari_dealer())
		->where('ds.id_part_int = p.id_part_int')
		->order_by('ds.stock', 'desc')
		->limit(1)
		->get_compiled_select();

		$id_rak = $this->db
		->select('ds.id_rak')
		->from('ms_h3_dealer_stock as ds')
		->where('ds.id_dealer', $this->m_admin->cari_dealer())
		->where('ds.id_part_int = p.id_part_int')
		->order_by('ds.stock', 'desc')
		->limit(1)
		->get_compiled_select();

		$part_sudah_diterima = $this->db
		->select('SUM(grp.qty) as qty', false)
		->from('tr_h3_dealer_good_receipt as gr')
		->join('tr_h3_dealer_good_receipt_parts as grp', 'grp.id_good_receipt = gr.id_good_receipt')
		->where('gr.nomor_po = pop.po_id', null, false)
		->where('grp.id_part_int = pop.id_part_int', null, false)
		->get_compiled_select();

		$this->db
		->select('p.id_part_int')
		->select('p.id_part')
		->select('p.nama_part')
		->select('s.satuan')
		->select("({$id_gudang}) as id_gudang")
		->select("({$id_rak}) as id_rak")
		->select('pop.kuantitas as qty_po')
		->select('pop.kuantitas as qty')
		->select("(pop.kuantitas - IFNULL(({$part_sudah_diterima}), 0)) as qty_boleh_terima", false)
		->select("IFNULL(({$part_sudah_diterima}), 0) as part_sudah_diterima", false)
		->select('pop.harga_saat_dibeli as  harga')
		->select('(pop.tot_harga_part/pop.kuantitas) as  harga_setelah_diskon')
		->from('tr_h3_dealer_purchase_order_parts as pop')
		->join('ms_part as p', 'p.id_part_int = pop.id_part_int')
		->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
		->where('pop.po_id', $this->input->post('po_id'))
		->where("(pop.kuantitas - IFNULL(({$part_sudah_diterima}), 0)) > 0", null, false)
		;

		$result = $this->db->get()->result();
		send_json($result);
	}

	private function validasi_qty_yang_diterima($parts, $id_purchase_order){
		$kuantitas_sudah_terpenuhi = $this->db
        ->select('SUM(of.qty_fulfillment) as qty_fulfillment', false)
        ->from('tr_h3_dealer_order_fulfillment as of')
        ->where('of.po_id = po.po_id', null, false)
        ->where('of.id_part = pop.id_part', null, false)
        ->get_compiled_select();

		foreach ($parts as $part) {
			$part_po = $this->db
			->select('pop.id_part')
			->select('pop.kuantitas')
			->select("IFNULL(({$kuantitas_sudah_terpenuhi}), 0) AS kuantitas_sudah_terpenuhi", FALSE)
			->select("(pop.kuantitas - IFNULL(({$kuantitas_sudah_terpenuhi}), 0)) as qty_boleh_terima", false)
			->from('tr_h3_dealer_purchase_order as po')
			->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id = po.po_id')
			->where('pop.id_part', $part['id_part'])
			->where('po.po_id', $id_purchase_order)
			->get()->row_array();

			if($part_po != null){
				if($part['qty'] > $part_po['qty_boleh_terima']){
					$this->output->set_status_header(500);
					send_json([
						'error_type' => 'validasi_qty_pemenuhan_part',
						'message' => "Part {$part['id_part']} hanya boleh menerima dengan kuantitas {$part_po['qty_boleh_terima']}, kuantitas yang diinput {$part['qty']}",
					]);
				}
			}
		}
	}

	public function save(){
		// $this->validate();
		if ($validasi_nomor_po == 1){
			$this->form_validation->set_rules('nomor_po', 'Nomor PO', 'required');

			if (!$this->form_validation->run())
			{
				send_json([
					'error_type' => 'validation_error',
					'message' => 'Data tidak valid',
					'errors' => $this->form_validation->error_array()
				], 422);
			}
			$this->validate();

		}else{
			$this->validate();
		}

		$this->validasi_qty_yang_diterima($this->input->post('parts'), $this->input->post('nomor_po'));

		$this->db->trans_start();
		$master = array_merge($this->input->post(), [
			'id_good_receipt' => $this->good_receipt->generateGoodReceipt(),
			'tanggal_receipt' => date('Y-m-d'),
			'id_dealer' => $this->m_admin->cari_dealer(),
		]);
		
		unset($master['parts']);

		$items = $this->getOnly(true, $this->input->post('parts'), [
			'id_good_receipt' => $this->good_receipt->generateGoodReceipt(),
		]);

		foreach ($items as $each) {
			$transaksi_stock = [
				'id_part' => $each['id_part'],
				'id_gudang' => $each['id_gudang'],
				'id_rak' => $each['id_rak'],
				'tipe_transaksi' => '+',
				'sumber_transaksi' => $this->page,
				'referensi' => $this->good_receipt->generateGoodReceipt(),
				'stok_value' => $each['qty']
			];
			$this->transaksi_stok->insert($transaksi_stock);

            $stockDiGudang = $this->stock->get([
                'id_part' => $each['id_part'],
                'id_gudang' => $each['id_gudang'],
                'id_rak' => $each['id_rak']
            ], true);
            // Cek apakah digudang ada record yang sama.
            if($stockDiGudang != null){
                // Jika ada stock digudang tersebut ditambah.
                $this->stock->update([
                    'stock' => $stockDiGudang->stock + $each['qty'],
                ],[
                    'id_part' => $each['id_part'],
                    'id_gudang' => $each['id_gudang'],
                    'id_rak' => $each['id_rak']
                ]);
            }else{
				$id_part = $each['id_part'];
				$part_int = null;
		  
				$prt = $this->db->get_where('ms_part', ['id_part' => $id_part]);
				if($prt->num_rows()> 0){
				  $part_int = $prt->row()->id_part_int;
				}

                // Jika tidak, maka buat record stock di warehouse.
                $this->stock->insert([
                    'id_part' => $each['id_part'],
                    'id_part_int' => $part_int,
                    'id_gudang' => $each['id_gudang'],
                    'id_rak' => $each['id_rak'],
                    'id_dealer' => $this->m_admin->cari_dealer(),
                    'stock' => $each['qty']
                ]);
			}
			
			$this->order_fulfillment->insert([
				'po_id' => $this->input->post('nomor_po'),
				'id_part' => $each['id_part'],
				'qty_fulfillment' => $each['qty'],
				'id_referensi' => $master['id_good_receipt'],
				'tipe_referensi' => $this->page
			]);

			$this->db
			->set('opt.qty_book', "opt.qty_book + {$each['qty']}", false)
			->set('opt.qty_pick', "opt.qty_pick + {$each['qty']}", false)
			->set('opt.qty_pack', "opt.qty_pack + {$each['qty']}", false)
			->set('opt.qty_bill', "opt.qty_bill + {$each['qty']}", false)
			->set('opt.qty_ship', "opt.qty_ship + {$each['qty']}", false)
			->where('opt.po_id', $this->input->post('nomor_po'))
			->where('opt.id_part', $each['id_part'])
			->update('tr_h3_dealer_order_parts_tracking as opt');
		}

		$this->good_receipt->insert($master);
		$this->good_receipt_parts->insert_batch($items);
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$good_receipt = $this->good_receipt->find($master['id_good_receipt'], 'id_good_receipt');
			send_json($good_receipt);
		}else{
			$this->output->set_status_header(500);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('ref_type', 'Tipe Referensi', 'required');
        $this->form_validation->set_rules('id_reference', 'ID Referensi', 'required');
        // $this->form_validation->set_rules('nomor_po', 'Nomor PO', 'required');

        if (!$this->form_validation->run())
        {
			// $this->output->set_status_header(400);

            // $errors = $this->form_validation->error_array();
            // send_json($errors);
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
        }
	}

	public function detail()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = 'Purchase Order';		
		$data['mode']  = 'detail';
		$data['set']   = "form";
		$data['good_receipt'] = $this->db
		->select('gr.*')
		->from('tr_h3_dealer_good_receipt as gr')
		// ->join('tr_h3_dealer_penerimaan_barang as pb', 'pb.id_packing_sheet = gr.id_reference', 'left')
		->where('gr.id_good_receipt', $this->input->get('id'))
		->where('gr.ref_type !=', 'packing_sheet_shipping_list')
		->get()->row();

		$data['referensi'] = $this->db
		->select('nsc.no_nsc as id_referensi')
        ->select('date_format(nsc.created_at, "%d-%m-%Y") as tanggal')
        ->select('"nsc" as tipe_referensi')
		->from('tr_h23_nsc as nsc')
		->where('nsc.no_nsc', $data['good_receipt']->id_reference)
		->get()->row();

		$kuantitas_part_dari_sales_return = $this->db
		->select('sorp.kuantitas_return')
		->from('tr_h3_dealer_sales_order_return_parts as sorp')
		->join('tr_h3_dealer_sales_order_return as sor', 'sor.id_sales_order_return = sorp.id_sales_order_return')
		->where('(sor.nomor_so = gr.id_reference AND sorp.id_part = grp.id_part)')
		->get_compiled_select();

		$kuantitas_part_dari_invoice = $this->db
		->select('kuantitas')
		->from('tr_h3_dealer_invoice_parts as ip')
		// ->join('tr_h3_dealer_invoice_parts as i', 'i.id_invoice = ip.id_invoice')
		->where('(ip.id_invoice = gr.id_reference AND ip.id_part = grp.id_part)')
		->get_compiled_select();


		$data['good_receipt_parts'] = $this->db
		->select('gr.ref_type')
		->select('grp.*')
		->select('p.nama_part')
		->select('s.satuan')
		->select("
			case 
				when gr.ref_type = 'return_exchange_so' then ({$kuantitas_part_dari_sales_return})
				when gr.ref_type = 'part_sales_work_order' then ({$kuantitas_part_dari_invoice})
			end as kuantitas
		")
		->from('tr_h3_dealer_good_receipt_parts as grp')
		->join('tr_h3_dealer_good_receipt as gr', 'grp.id_good_receipt=gr.id_good_receipt')
		->join('ms_part as p', 'p.id_part = grp.id_part')
		->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
		->where('grp.id_good_receipt', $this->input->get('id'))
		->get()->result()
		;

		$this->template($data);	
	}

	public function hitung_harga_setelah_diskon_dari_shipping_list(){
		$this->db
		->select('grp.id')
		->select('grp.id_good_receipt')
		->select('grp.id_part')
		->select('grp.qty')
		->select('dop.harga_jual as harga')
		->select('dop.harga_setelah_diskon')
		->from('tr_h3_dealer_good_receipt as gr')
		->join('tr_h3_dealer_good_receipt_parts as grp', 'grp.id_good_receipt = gr.id_good_receipt')
		->join('tr_h3_md_packing_sheet as ps', 'gr.id_reference = ps.id_packing_sheet')
		->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
		->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_do_sales_order = do.id_do_sales_order AND dop.id_part = grp.id_part)')
		->where('gr.ref_type', 'packing_sheet_shipping_list');

		foreach ($this->db->get()->result_array() as $row) {
			$this->db
			->set('grp.harga', $row['harga'])
			->set('grp.harga_setelah_diskon', $row['harga_setelah_diskon'])
			->where('grp.id', $row['id'])
			->update('tr_h3_dealer_good_receipt_parts as grp');
		}

		echo 'Berhasil';
	}

	public function hitung_harga_setelah_diskon_dari_so_wo(){
		$this->db
		->select('grp.id')
		->select('grp.id_good_receipt')
		->select('grp.id_part')
		->select('sop.harga_saat_dibeli as harga')
		->select('sop.harga_setelah_diskon')
		->from('tr_h3_dealer_good_receipt as gr')
		->join('tr_h3_dealer_good_receipt_parts as grp', 'grp.id_good_receipt = gr.id_good_receipt')
		->join('tr_h23_nsc as nsc', 'nsc.no_nsc = gr.id_reference')
		->join('tr_h3_dealer_sales_order_parts as sop', '(sop.nomor_so = nsc.id_referensi AND sop.id_part = grp.id_part)')
		->where('gr.ref_type', 'part_sales_work_order')
		->where('gr.nomor_po is null', null, false)
		;

		foreach ($this->db->get()->result_array() as $row) {
			$this->db
			->set('grp.harga', $row['harga'])
			->set('grp.harga_setelah_diskon', $row['harga_setelah_diskon'])
			->where('grp.id', $row['id'])
			->update('tr_h3_dealer_good_receipt_parts as grp');
		}

		echo 'Berhasil';
	}

	public function hitung_harga_setelah_diskon_dari_manual_po(){
		$this->db
		->select('grp.id')
		->select('grp.id_good_receipt')
		->select('grp.id_part')
		->select('pop.harga_saat_dibeli as harga')
		->select('(pop.tot_harga_part/pop.kuantitas) as harga_setelah_diskon')
		->from('tr_h3_dealer_good_receipt as gr')
		->join('tr_h3_dealer_good_receipt_parts as grp', 'grp.id_good_receipt = gr.id_good_receipt')
		->join('tr_h3_dealer_purchase_order as po', 'po.po_id = gr.nomor_po')
		->join('tr_h3_dealer_purchase_order_parts as pop', '(pop.po_id = po.po_id AND pop.id_part = grp.id_part)')
		->where('gr.ref_type', 'part_sales_work_order')
		->where('gr.nomor_po is not null', null, false)
		;

		foreach ($this->db->get()->result_array() as $row) {
			$this->db
			->set('grp.harga', $row['harga'])
			->set('grp.harga_setelah_diskon', $row['harga_setelah_diskon'])
			->where('grp.id', $row['id'])
			->update('tr_h3_dealer_good_receipt_parts as grp');
		}

		echo 'Berhasil';
	}

	public function set_id_part_good_receipt_parts(){
		$this->db
		->select('grp.id')
		->select('p.id_part_int')
		->select('grp.id_part')
		->from('tr_h3_dealer_good_receipt_parts as grp')
		->join('ms_part as p', 'p.id_part = grp.id_part')
		->where('grp.id_part_int is null', null, true);

		foreach ($this->db->get()->result_array() as $row) {
			$this->db
			->set('grp.id_part_int', $row['id_part_int'])
			->where('grp.id', $row['id'])
			->update('tr_h3_dealer_good_receipt_parts as grp');
		}
		echo 'Berhasil';
	}
}