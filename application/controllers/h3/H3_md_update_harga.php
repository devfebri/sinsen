<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_update_harga extends Honda_Controller{

    public function __construct(){
        parent::__construct();

        $this->load->model('H3_md_status_update_harga_model', 'status_update_harga');
    }
    
    public function state(){
        $this->db
        ->select('suh.update_po_dealer')
        ->select('suh.update_so')
        ->select('suh.update_do')
        ->select('suh.update_po_md')
        ->select('suh.update_niguri')
        ->select('suh.update_do_revisi')
        ->limit(1)
        ->from('tr_h3_md_status_update_harga as suh');

        send_json($this->db->get()->row_array());
    }

    public function update_po_dealer(){
        $this->load->model('h3_dealer_purchase_order_model', 'po_dealer');

        $this->db->trans_begin();
        try {
            $this->db
            ->where('lpuh.sudah_proses', 0)
            ->group_start()
            ->where('lpuh.update_het', 1)
            ->or_where('lpuh.update_hpp', 1)
            ->group_end()
            ->from('tr_h3_md_list_parts_update_harga as lpuh');
            
            foreach($this->db->get()->result_array() as $part){
                $this->po_dealer->update_harga($part['id_part_int']);
            }

            $this->status_update_harga->sudah_update_po_dealer();

            $this->db->trans_commit();

            send_json([
                'message' => 'Berhasil update harga purchase order dealer'
            ]);
        } catch (Exception $e) {
            log_message('error', $e);

            $this->db->trans_rollback();

            send_json([
                'message' => 'Tidak berhasil update harga purchase order dealer'
            ], 422);
        }
    }

    public function update_so(){
        $this->load->model('h3_md_sales_order_model', 'sales_order');

        $this->db->trans_begin();
        try {
            $this->db
            ->where('lpuh.sudah_proses', 0)
            ->group_start()
            ->where('lpuh.update_het', 1)
            ->or_where('lpuh.update_hpp', 1)
            ->group_end()
            ->from('tr_h3_md_list_parts_update_harga as lpuh');
            
            foreach($this->db->get()->result_array() as $part){
                $this->sales_order->update_harga($part['id_part_int']);
            }

            $this->status_update_harga->sudah_update_so();

            $this->db->trans_commit();

            send_json([
                'message' => 'Berhasil update harga sales order'
            ]);
        } catch (Exception $e) {
            log_message('error', $e);

            $this->db->trans_rollback();

            send_json([
                'message' => 'Tidak berhasil update harga sales order'
            ], 422);
        }
    }

    public function update_do(){
        $this->load->model('H3_md_do_sales_order_model', 'delivery_order');

        $this->db->trans_begin();
        try {
            $this->db
            ->where('lpuh.sudah_proses', 0)
            ->group_start()
            ->where('lpuh.update_het', 1)
            ->or_where('lpuh.update_hpp', 1)
            ->group_end()
            ->from('tr_h3_md_list_parts_update_harga as lpuh');
            
            foreach($this->db->get()->result_array() as $part){
                $this->delivery_order->update_harga($part['id_part_int']);
            }

            $this->status_update_harga->sudah_update_do();

            $this->db->trans_commit();

            send_json([
                'message' => 'Berhasil update harga delivery order'
            ]);
        } catch (Exception $e) {
            log_message('error', $e);

            $this->db->trans_rollback();

            send_json([
                'message' => 'Tidak berhasil update harga delivery order'
            ], 422);
        }
    }

    public function update_po_md(){
        $this->load->model('h3_md_purchase_order_model', 'purchase_order');

        $this->db->trans_begin();
        try {
            $this->db
            ->where('lpuh.sudah_proses', 0)
            ->group_start()
            ->where('lpuh.update_het', 1)
            ->or_where('lpuh.update_hpp', 1)
            ->group_end()
            ->from('tr_h3_md_list_parts_update_harga as lpuh');
            
            foreach($this->db->get()->result_array() as $part){
                $this->purchase_order->update_harga($part['id_part_int']);
            }

            $this->status_update_harga->sudah_update_po_md();

            $this->db->trans_commit();

            send_json([
                'message' => 'Berhasil update harga purchase order MD'
            ]);
        } catch (Exception $e) {
            log_message('error', $e);

            $this->db->trans_rollback();

            send_json([
                'message' => 'Tidak berhasil update harga purchase order MD'
            ], 422);
        }
    }

    public function update_niguri(){
        $this->load->model('H3_md_niguri_model', 'niguri_item');

        $this->db->trans_begin();
        try {
            $this->db
            ->where('lpuh.sudah_proses', 0)
            ->group_start()
            ->where('lpuh.update_het', 1)
            ->or_where('lpuh.update_hpp', 1)
            ->group_end()
            ->from('tr_h3_md_list_parts_update_harga as lpuh');
            
            foreach($this->db->get()->result_array() as $part){
                $this->niguri_item->update_harga($part['id_part_int']);
            }

            $this->status_update_harga->sudah_update_niguri();

            $this->db->trans_commit();

            send_json([
                'message' => 'Berhasil update harga niguri MD'
            ]);
        } catch (Exception $e) {
            log_message('error', $e);

            $this->db->trans_rollback();

            send_json([
                'message' => 'Tidak berhasil update harga niguri MD'
            ], 422);
        }
    }

    public function update_do_revisi(){
        $this->load->model('H3_md_do_revisi_model', 'do_revisi');

        $this->db->trans_begin();
        try {
            $this->db
            ->where('lpuh.sudah_proses', 0)
            ->group_start()
            ->where('lpuh.update_het', 1)
            ->or_where('lpuh.update_hpp', 1)
            ->group_end()
            ->from('tr_h3_md_list_parts_update_harga as lpuh');
            
            foreach($this->db->get()->result_array() as $part){
                $this->do_revisi->update_harga_open_do_revisi();
            }

            $this->status_update_harga->sudah_update_do_revisi();

            $this->complete_status_update_harga();

            $this->db->trans_commit();

            send_json([
                'message' => 'Berhasil update harga do revisi'
            ]);
        } catch (Exception $e) {
            log_message('error', $e);

            $this->db->trans_rollback();

            send_json([
                'message' => 'Tidak berhasil update harga do revisi'
            ], 422);
        }
    }

    private function complete_status_update_harga(){
        $this->db
        ->set('sudah_proses', 1)
        ->update('tr_h3_md_list_parts_update_harga');

        $this->db->truncate('tr_h3_md_status_update_harga');
    }
}
