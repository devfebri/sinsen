<?php

class h3_dealer_customer_h23_model extends Honda_Model
{
    protected $table = 'ms_customer_h23';

    public function __construct()
    {
        $this->load->model('dealer_model', 'dealer');
        $this->load->library('Mcarbon');
    }

    public function insert($data){
        $data['created_at'] = Mcarbon::now()->toDateTimeString();
        $data['created_by'] = $this->session->userdata('id_user');
        parent::insert($data);
    }

    public function generateIdCustomer($direct_sales = false)
    {
        $th        = Mcarbon::now()->format('y');
        $bln       = Mcarbon::now()->format('m');
        $th_bln    = Mcarbon::now()->format('Y-m');
        $thbln     = Mcarbon::now()->format('ym');
        $dealer    = $this->dealer->getCurrentUserDealer();
        
        $this->db
        ->from($this->table)
        ->where("left(created_at, 7) = '{$th_bln}'", null, false)
        ->where('id_dealer', $this->m_admin->cari_dealer())
        ->order_by('created_at', 'desc')
        ->order_by('id_customer', 'desc')
        ->where('is_dealer', 0)
        ;

        if($direct_sales){
            $this->db->where('is_direct_sales', 1);
        }else{
            $this->db->where('is_direct_sales', 0);
        }

        $get_data = $this->db->get();
        
        if ($get_data->num_rows()>0) {
            $row        = $get_data->row();
            if($direct_sales){
                $id_customer = substr($row->id_customer, -2);
                $new_kode   = $dealer->kode_dealer_md.'/'. $th . '/'. $bln. '/' .'CUS/DR' .sprintf("%'.02d", $id_customer+1);
            }else{
                $id_customer = substr($row->id_customer, -4);
                $new_kode   = $dealer->kode_dealer_md.'/'. $th . '/'. $bln. '/' .'CUS/' .sprintf("%'.04d", $id_customer+1);
            }
        } else {
            if($direct_sales){
                $new_kode   = $dealer->kode_dealer_md.'/'. $th . '/'. $bln. '/' .'CUS/DR' .'01';
            }else{
                $new_kode   = $dealer->kode_dealer_md.'/'. $th . '/'. $bln. '/' .'CUS/' .'0001';
            }
        }

        return strtoupper($new_kode);
    }

    public function generateIdCustomerEV()
    {
        $th        = Mcarbon::now()->format('y');
        $bln       = Mcarbon::now()->format('m');
        $th_bln    = Mcarbon::now()->format('Y-m');
        $thbln     = Mcarbon::now()->format('ym');
        $dealer    = $this->dealer->getCurrentUserDealer();
        
        $this->db
        ->from($this->table)
        ->where("left(created_at, 7) = '{$th_bln}'", null, false)
        ->where('id_dealer', $this->m_admin->cari_dealer())
        ->order_by('created_at', 'desc')
        ->order_by('id_customer', 'desc')
        ->where('is_dealer', 0)
        ->where('is_direct_sales', 0)
        ->where('is_ev', 1)
        ;

        $get_data = $this->db->get();
        
        if ($get_data->num_rows()>0) {
            $row        = $get_data->row();
            $id_customer = substr($row->id_customer, -4);
            $new_kode   = $dealer->kode_dealer_md.'/'. $th . '/'. $bln. '/' .'CUS/EV/' .sprintf("%'.04d", $id_customer+1); 
            $i = 0;
            while ($i < 1) {
                $cek = $this->db->get_where('ms_customer_h23', ['id_customer' => $new_kode])->num_rows();
                if ($cek > 0) {
                    $gen_number    = substr($new_kode, -4);
                    $new_kode   = $dealer->kode_dealer_md.'/'. $th . '/'. $bln. '/' .'CUS/EV/' .sprintf("%'.04d", $gen_number+1); 
                    $i = 0;
                } else {
                    $i++;
                }
            }
        } else {
                $new_kode   = $dealer->kode_dealer_md.'/'. $th . '/'. $bln. '/' .'CUS/EV/' .'0001';
        }

        return strtoupper($new_kode);
    }
}
