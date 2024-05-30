<?php

use GO\Scheduler;

class Update_diskon_dan_total_so extends Honda_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('h3_md_sales_order_model', 'sales_order');
        $this->load->model('h3_md_sales_order_parts_model', 'sales_order_parts');
        $this->load->model('h3_md_diskon_part_tertentu_model', 'diskon_part_tertentu');
		$this->load->model('h3_md_diskon_oli_reguler_model', 'diskon_oli_reguler');
    }

    public function index()
    {
        $scheduler = new Scheduler();

        $scheduler->call(function () {
            $this->update_total_so();
        });

        $scheduler->run();
    }

    public function update_total_so(){
		$sales_orders = $this->sales_order->all();

		$this->db->trans_start();
		foreach ($sales_orders as $sales_order) {
			$this->sales_order->update_total($sales_order->id_sales_order);
		}
		$this->db->trans_complete();

		if($this->db->trans_status()){
			echo 'Berhasil update total SO.';
		}else{
			echo 'Tidak berhasil update total SO.';
		}
	}
}