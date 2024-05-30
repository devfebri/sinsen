<?php

class h3_dealer_request_document_model extends Honda_Model
{
    protected $table = 'tr_h3_dealer_request_document';

    public function __construct()
    {
        $this->load->model('dealer_model', 'dealer');
        $this->load->model('h3_dealer_request_document_parts_model', 'request_document_part');
    }

    public function insert($data)
    {
        $data['created_by'] = $this->session->userdata('id_user');
        parent::insert($data);
        $id = $this->db->insert_id();

        $this->set_int_relation($id);

        return $this->db
            ->from($this->table)
            ->where('id', $id)
            ->limit(1)
            ->get()->row_array();
    }

    public function set_int_relation($id)
    {
        $data = $this->db
            ->from($this->table)
            ->where('id', $id)
            ->limit(1)
            ->get()->row_array();
        if ($data == null) throw new Exception(sprintf('Request document tidak ditemukan [%s]', $id));

        $customer = $this->db
            ->select('id_customer_int as id')
            ->from('ms_customer_h23')
            ->where('id_customer', $data['id_customer'])
            ->limit(1)
            ->get()->row_array();
        if ($customer == null) throw new Exception(sprintf('Customer %s tidak ditemukan', $data['id_customer']));

        $updated = $this->db
            ->set('id_customer_int', $customer['id'])
            ->where('id', $id)
            ->update($this->table);

        if($updated) log_message('info', sprintf('Set relation untuk request document %s', $data['id_booking']));

        return $updated;
    }

    public function update($data, $condition)
    {
        $data['updated_at'] = date('Y-m-d H:i:s', time());
        $data['updated_by'] = $this->session->userdata('id_user');
        parent::update($data, $condition);
    }

    public function generateIdBooking()
    {
        $th        = date('Y');
        $bln       = date('m');
        $th_bln    = date('Y-m');
        $thbln     = date('ym');
        $dealer    = $this->dealer->getCurrentUserDealer();

        $get_data = $this->db
            ->from($this->table)
            ->where('id_dealer', $this->m_admin->cari_dealer())
            ->order_by('id', 'desc')
            ->limit(1)
            ->get();

        // ->order_by('created_at', 'desc')

        if ($get_data->num_rows() > 0) {
            $row        = $get_data->row();
            
            // generate id booking baru ketika id booking lama sudah mencapai limit 999
            if(strlen($row->id_booking) > 17 || substr($row->id_booking, -3) == '999'){
                $get_data = $this->db
                    ->from($this->table)
                    ->where('id_dealer', $this->m_admin->cari_dealer())
                    ->where("left(created_at,7) = '$th_bln'")
                    ->order_by('id', 'desc')
                    ->limit(1)
                    ->get();
                    
                    // ->order_by('created_at', 'desc')
        
                if ($get_data->num_rows() > 0) {
                    $row        = $get_data->row();
                    $id_booking = substr($row->id_booking, -3);
                    
                    if($id_booking == '999'){
                        $new_id = '001';
                    }else{
                        $new_id =sprintf("%'.03d", $id_booking + 1);
                    }
                    
                    $id = $dealer->kode_dealer_md.'/'.$thbln.'/BOK-'.$new_id;
                    $new_kode = $id;
                } else {
                    $new_kode   = $dealer->kode_dealer_md .'/'.$thbln. '/BOK-' . '001';
                }
            }else{
                $id_booking = substr($row->id_booking, -3);
                $new_kode   = $dealer->kode_dealer_md . '/BOK-' . sprintf("%'.03d", $id_booking + 1);
            }
        } else {
            $new_kode   = $dealer->kode_dealer_md . '/BOK-' . '001';
        }

        /*
        // if($dealer->kode_dealer_md=='00888'){
            $get_data = $this->db
                ->from($this->table)
                ->where('id_dealer', $this->m_admin->cari_dealer())
                ->where("left(created_at,7) = '$th_bln'")
                ->order_by('created_at', 'desc')
                ->limit(1)
                ->get();
    
            if ($get_data->num_rows() > 0) {
                $row        = $get_data->row();
                $id_booking = substr($row->id_booking, -3);
                
                if($id_booking == '999'){
                    $new_id = '001';
                }else{
                    $new_id =sprintf("%'.03d", $id_booking + 1);
                }
                
                $id = $dealer->kode_dealer_md.'/'.$thbln.'/BOK-'.$new_id;
                $new_kode = $id;
            } else {
                $new_kode   = $dealer->kode_dealer_md .'/'.$thbln. '/BOK-' . '001';
            }
        }
        */
        
        return strtoupper($new_kode);
    }
}
