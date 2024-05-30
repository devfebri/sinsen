<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_update_diskon extends Honda_Controller
{
	public function __construct(){
		parent::__construct();
		$this->load->model('h3_dealer_purchase_order_model', 'purchase_order');
		$this->load->model('h3_md_sales_order_model', 'sales_order');
		$this->load->model('H3_md_do_sales_order_model', 'delivery_order');
		$this->load->model('H3_md_do_revisi_model', 'do_revisi');
	}

	public function index(){
		$this->db->trans_begin();
		try {
			$this->purchase_order->update_diskon();
			$this->sales_order->update_diskon_sales_order();
			$this->delivery_order->update_diskon_belum_create_faktur();
			$this->do_revisi->update_diskon_open_do_revisi();

			$this->db->trans_commit();

			$message = 'Berhasil update diskon';
			if($this->input->is_ajax_request()){
				send_json([
					'message' => $message
				]);
			}else{
				if(isset($_SERVER['HTTP_REFERER'])){
					$this->session->set_userdata('pesan', $message);
					$this->session->set_userdata('tipe', 'success');

					redirect($_SERVER['HTTP_REFERER']);
				}
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			log_message('error', $e);

			if($this->input->is_ajax_request()){
				send_json([
					'message' => $e->getMessage()
				], 422);
			}else{
				if(isset($_SERVER['HTTP_REFERER'])){
					$this->session->set_userdata('pesan', $e->getMessage());
					$this->session->set_userdata('tipe', 'danger');
					redirect($_SERVER['HTTP_REFERER']);
				}
			}
		}
	}
}
