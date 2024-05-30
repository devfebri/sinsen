<?php

class Request_document_dealer extends Honda_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('h3_dealer_request_document_model', 'request_document');
        $this->load->model('h3_dealer_request_document_parts_model', 'request_document_parts');
    }

    public function set_int_relation()
    {
        $this->db
            ->select('id')
            ->from('tr_h3_dealer_request_document')
            ->group_start()
            ->where('id_customer_int IS NULL', null, false)
            ->group_end();

        foreach ($this->db->get()->result_array() as $row) {
            try {
                $this->request_document->set_int_relation($row['id']);
            } catch (Exception $e) {
                log_message('error', $e);
            }
        }

        $this->db
            ->select('id')
            ->from('tr_h3_dealer_request_document_parts')
            ->group_start()
            ->or_where('id_booking_int IS NULL', null, false)
            ->or_where('id_part_int IS NULL', null, false)
            ->group_end();

        foreach ($this->db->get()->result_array() as $row) {
            try {
                $this->request_document_parts->set_int_relation($row['id']);
            } catch (Exception $e) {
                log_message('error', $e);
            }
        }
    }
}
