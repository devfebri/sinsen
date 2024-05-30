<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Html extends CI_Controller {

    public function filter_status(){
        echo $this->load->view('/html/filter_status', [], true);
    }

    public function filter_dealer(){
        $data = [];
        $data['dealer'] = $this->db->from('ms_dealer')->get()->result();

        echo $this->load->view('/html/filter_dealer', $data, true);
    }

    public function filter_active(){
        echo $this->load->view('/html/filter_active', [], true);
    }

    public function filter_diskon_oli_reguler(){
        $data = [];
        $data['dealer'] = $this->db->from('ms_dealer')->get()->result();

        $html = '';
        // $html = $this->load->view('/html/filter_dealer', $data, true);
        $html .= $this->load->view('/html/filter_active', [], true);
        echo $html;
    }

    public function filter_status_reference_good_receipt(){
        echo $this->load->view('/html/filter_status_reference_good_receipt', [], true);
    }

    public function tipe_kendaraan_check_part_stock(){
        $data['kategori'] = $this->db->from('ms_kategori')->get()->result();
        echo $this->load->view('/html/filter_tipe_kendaraan_check_part_stock', $data, true);
        echo $this->load->view('/html/filter_tahun_kendaraan_check_part_stock', $data, true);
    }

    public function filter_tipe_po_penerimaan(){
        echo $this->load->view('/html/filter_penerimaan_barang', [], true);
        echo $this->load->view('/html/filter_shipping_date', [], true);
    }

    public function filter_purchase_order(){
        echo $this->load->view('/html/filter_tipe_po', [], true);
        echo $this->load->view('/html/filter_status', [], true);
        echo $this->load->view('/html/filter_purchase_date', [], true);
    }

    public function filter_customer_h23(){
        echo $this->load->view('/html/filter_kabupaten_kota_customer_h23', [], true);
    }

    public function filter_request_document(){
        echo $this->load->view('/html/filter_request_document_date', [], true);
    }

    public function filter_sales_order(){
        echo $this->load->view('/html/filter_status_sales_order', [], true);
        echo $this->load->view('/html/filter_sales_date', [], true);
    }

    public function filter_picking_slip(){
        echo $this->load->view('/html/filter_status_picking_slip', [], true);
        echo $this->load->view('/html/filter_picking_date', [], true);
    }

    public function filter_create_submit_good_receipt(){
        echo $this->load->view('/html/filter_tipe_good_receipt', [], true);
    }

    public function filter_order_fulfillment(){
        echo $this->load->view('/html/filter_status_order_fulfillment', [], true);
        echo $this->load->view('/html/filter_order_fulfillment_date', [], true);
    }

    public function filter_suggested_order(){
        $data['kelompok_part'] = $this->db->from('ms_kelompok_part as kp')->get()->result();
        echo $this->load->view('/html/filter_kelompok_part_suggested_order', $data, true);
        echo $this->load->view('/html/filter_prioritas_order_suggested_order', [], true);
    }

    public function filter_monitoring_stock(){
        echo $this->load->view('/html/filter_nilai_stock_sim', [], true);
        echo $this->load->view('/html/filter_nilai_stock', [], true);
        echo $this->load->view('/html/filter_kelompok_part_monitoring_stock', [
            'kelompok_part' => $this->db->select('kp.kelompok_part')->from('ms_kelompok_part as kp')->order_by('kp.kelompok_part', 'asc')->get()->result(),
        ], true);
        echo $this->load->view('/html/filter_status_monitoring_stock', [], true);
        echo $this->load->view('/html/filter_rank_monitoring_stock', [], true);
    }

    public function filter_record_demand(){
        $parts = $this->db
        ->distinct('id_part')
        ->select('p.id_part')
        ->select('p.nama_part')
        ->from('tr_h3_dealer_record_reasons_and_parts_demand as rd')
        ->join('ms_part as p', 'p.id_part = rd.id_part')
        ->order_by('rd.id_part', 'asc')
        ->get()->result();

        echo $this->load->view('/html/filter_total_lost', [], true);

        echo $this->load->view('/html/filter_part_record_demand', [
            'parts' => $parts,
        ], true);

        echo $this->load->view('/html/filter_date_record_demand', [], true);
        echo $this->load->view('/html/button_report_reason_demand', [], true);
    }

    public function filter_good_receipt_shipping_list(){
        echo $this->load->view('/html/filter_tipe_po', [], true);
        echo $this->load->view('/html/filter_good_receipt_date', [], true);
    }

    public function filter_inbound_form_parts_return(){
        echo $this->load->view('/html/filter_status_inbound_form_parts_return', [], true);
    }

    public function filter_kategori_gudang_outbound_part_transfer(){
        echo $this->load->view('/html/filter_kategori_gudang', [], true);
    }

    public function filter_md_kategori_claim_c3(){
        echo $this->load->view('/html/md/h3/filter_tipe_claim', [], true);
    }
}
