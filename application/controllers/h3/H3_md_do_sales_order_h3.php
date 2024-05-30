<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_do_sales_order_h3 extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_do_sales_order_h3";
	protected $title  = "DO Sales Order (H3)";

	public function __construct()
	{		
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}

		$this->load->model('h3_md_do_sales_order_model', 'do_sales_order');
		$this->load->model('h3_md_do_sales_order_parts_model', 'do_sales_order_parts');
		$this->load->model('h3_md_do_sales_order_cashback_model', 'do_sales_order_cashback');
		$this->load->model('h3_md_do_sales_order_gimmick_model', 'do_sales_order_gimmick');
		$this->load->model('H3_md_do_sales_order_poin_model', 'do_sales_order_poin');
		$this->load->model('h3_md_sales_order_model', 'sales_order');
		$this->load->model('h3_md_sales_order_parts_model', 'sales_order_parts');
		$this->load->model('H3_md_ar_part_model', 'ar_part');
		$this->load->model('h3_md_picking_list_model', 'picking_list');
		$this->load->model('h3_md_picking_list_parts_model', 'picking_list_parts');
		$this->load->model('H3_md_ar_part_model', 'ar_part');
		$this->load->model('H3_md_ms_plafon_model', 'plafon');
		$this->load->model('H3_dealer_order_parts_tracking_model', 'order_parts_tracking');		
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";

		$data['do_sales_order'] = $this->do_sales_order->get_do_sales_order($this->input->get('id'));
		$data['do_sales_order_parts'] = $this->do_sales_order_parts->get_do_sales_order_parts($this->input->get('id'));
		$data['monitoring_piutang'] = $this->ar_part->piutang_dealer($data['do_sales_order']['id_dealer'], $data['do_sales_order']['gimmick'] == 1, $data['do_sales_order']['kategori_po'] == 'KPB');
		$data['do_cashback'] = $this->do_sales_order_cashback->get_cashback_do($this->input->get('id'), true);
		$data['do_gimmick'] = $this->do_sales_order_gimmick->get_gimmick_do($this->input->get('id'), true);
		$data['do_poin'] = $this->do_sales_order_poin->get_poin_do($this->input->get('id'));

		$this->template($data);
	}

	public function edit(){
		$data['mode']    = 'edit';
		$data['set']     = "form";

		$data['do_sales_order'] = $this->do_sales_order->get_do_sales_order($this->input->get('id'));
		$data['do_sales_order_parts'] = $this->do_sales_order_parts->get_do_sales_order_parts($this->input->get('id'));
		$data['monitoring_piutang'] = $this->ar_part->piutang_dealer($data['do_sales_order']['id_dealer'], $data['do_sales_order']['gimmick'] == 1, $data['do_sales_order']['kategori_po'] == 'KPB');
		$data['do_cashback'] = $this->do_sales_order_cashback->get_cashback_do($this->input->get('id'), true);
		$data['do_gimmick'] = $this->do_sales_order_gimmick->get_gimmick_do($this->input->get('id'), true);
		$data['do_poin'] = $this->do_sales_order_poin->get_poin_do($this->input->get('id'), true);
		
		$this->template($data);
	}

	public function update(){
		$this->db->trans_start();
		
		$this->do_sales_order->update($this->input->post([
			'total', 'sub_total', 'total_ppn',
			'diskon_cashback', 'diskon_insentif'
		]), $this->input->post(['id_do_sales_order']));
		
		$parts = $this->getOnly([
			'id_part', 'qty_supply', 'harga_jual', 'harga_beli',
			'tipe_diskon_campaign', 'diskon_campaign',
			'tipe_diskon_satuan_dealer', 'diskon_satuan_dealer'
		], $this->input->post('parts'), $this->input->post(['id_do_sales_order']));
		$this->do_sales_order_parts->update_batch($parts, $this->input->post(['id_do_sales_order']));

		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json(
				$this->do_sales_order->get($this->input->post(['id_do_sales_order']), true)
			);
		}else{
			$this->set_status_header(500);
		}
	}

	public function proses(){
		$this->db->trans_start();
		$this->do_sales_order->update([
			'status' => 'On Process'
		], $this->input->get(['id_do_sales_order']));

		$parts = $this->db
		->select('do.id_do_sales_order')
		->select('so.id_ref')
		->select('so.id_rekap_purchase_order_dealer')
		->select('dop.id_part')
		->select('dop.qty_supply')
		->from('tr_h3_md_do_sales_order as do')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
		->where('do.id_do_sales_order', $this->input->get('id_do_sales_order'))
		->get()->result_array();

		foreach ($parts as $part) {
			$this->order_parts_tracking->tambah_qty_book($part['id_ref'], $part['id_part'], $part['qty_supply']);
			if($part['id_rekap_purchase_order_dealer'] != null){
				$purchase_orders = $this->db
				->select('po.po_id')
				->select('pop.id_part')
				->select('(pop.kuantitas - opt.qty_book) as selisih')
				->from('tr_h3_md_rekap_purchase_order_dealer_item as ri')
				->join('tr_h3_dealer_purchase_order as po', 'po.po_id = ri.id_referensi')
				->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id = ri.id_referensi')
				->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)')
				->where('ri.id_rekap', $part['id_rekap_purchase_order_dealer'])
				->where('pop.id_part', $part['id_part'])
				->order_by('po.created_at', 'desc')
				->get()->result_array();

				$supply_untuk_dipecah = $part['qty_supply'];
				foreach ($purchase_orders as $purchase_order) {
					if($purchase_order['selisih'] <= $supply_untuk_dipecah){
						$this->order_parts_tracking->tambah_qty_book($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['selisih']);
						$supply_untuk_dipecah -= $purchase_order['selisih'];
					}else if($purchase_order['selisih'] >= $supply_untuk_dipecah){
						$this->order_parts_tracking->tambah_qty_book($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah);
						break;
					}

					if($supply_untuk_dipecah == 0) break;
				}
			}
		}
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json(
				$this->do_sales_order->get($this->input->get(['id_do_sales_order']), true)
			);
		}else{
			$this->set_status_header(500);
		}
	}

	public function cancel(){
		$do_sales_order = (array) $this->do_sales_order->find($this->input->get('id_do_sales_order'), 'id_do_sales_order');
		
		$this->db->trans_begin();
		try{
			$this->do_sales_order->cancel($do_sales_order['id']);
			$this->db->trans_commit();

			send_json([
				'redirect_url' => base_url(sprintf('h3/h3_md_do_sales_order_h3/detail?id=%s', $do_sales_order['id_do_sales_order']))
			]);
		}catch(Exception $exception){
			$this->db->trans_rollback();

			send_json([
				'message' => $exception->getMessage()
			], 422);
		}
	}

	public function cetak(){
		$data = [];
		$data['do_sales_order'] = $this->do_sales_order->get_do_sales_order($this->input->get('id'));
		$data['do_sales_order_parts'] = $this->do_sales_order_parts->get_do_sales_order_parts($this->input->get('id'));

		$this->db->set('cetak_ke', 'COALESCE(cetak_ke,0) + 1', FALSE);
		$this->db->where('id_do_sales_order', $this->input->get('id'));
		$this->db->update('tr_h3_md_do_sales_order');

		require_once APPPATH .'third_party/mpdf/mpdf.php';
        // Require composer autoload
        $mpdf = new Mpdf();
        // Write some HTML code:
        $html = $this->load->view('h3/h3_md_cetak_delivery_order', $data, true);
        $mpdf->WriteHTML($html);

        // Output a PDF file directly to the browser
        $mpdf->Output("{$data['do_sales_order']['id_do_sales_order']}.pdf", "I");
	}

	public function verifyPassword() {
		$inputPassword = $this->input->post('password');
		$id = $this->input->post('id'); 

		$correctPassword = $this->db->select('ms.password')
									->from('tr_h3_md_setting_menu_password ms')
									->join('ms_menu mm','mm.id_menu=ms.id_menu')
									->where('mm.menu_link',$this->uri->segment(2))
									->get()
									->row_array();


		if(!empty($correctPassword)){
			$correctPassword['password'] = $correctPassword['password'];
		}else{ 
			$correctPassword['password'] = 'sparepart';
		}
	
		if ($inputPassword == $correctPassword['password']) {
			echo 'success';
		} else {
			echo 'fail';
		}
	}
}