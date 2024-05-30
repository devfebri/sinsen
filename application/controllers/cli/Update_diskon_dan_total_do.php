<?php

use GO\Scheduler;

class Update_diskon_dan_total_do extends Honda_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('h3_md_do_sales_order_model', 'do_sales_order');
		$this->load->model('h3_md_do_sales_order_parts_model', 'do_sales_order_parts');
    }

    public function index()
    {
        $scheduler = new Scheduler();

        $scheduler->call(function () {
            $this->update_total_do();
        });

        $scheduler->run();
    }

    public function update_total_do(){
        $do_belum_ada_faktur = $this->db
        ->select('do.id_do_sales_order')
        ->from('tr_h3_md_do_sales_order as do')
        ->where('do.sudah_create_faktur', 0)
        ->get()->result_array();

		$this->db->trans_start();
		foreach ($do_belum_ada_faktur as $each) {
			$this->do_sales_order->update_total_do($each['id_do_sales_order']);
		}
		$this->db->trans_complete();

		if($this->db->trans_status()){
			echo 'Berhasil update total DO.';
		}else{
			echo 'Tidak berhasil update total DO.';
		}
	}
}