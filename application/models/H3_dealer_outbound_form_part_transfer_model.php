<?php



class h3_dealer_outbound_form_part_transfer_model extends Honda_Model{

    protected $table = 'tr_h3_dealer_outbound_form_part_transfer';


    public function generateID()
    {
        $th        = date('Y');
        $th_duadigit = date('y');
        $bln       = date('m');
        $th_bln    = date('Y-m');
        $thbln     = date('ym');
        $dealer    = $this->dealer->getCurrentUserDealer();
        $id_dealer = $this->m_admin->cari_dealer();


        $get_data  = $this->db->query("SELECT * FROM $this->table WHERE id_dealer='$id_dealer'
        AND LEFT(created_at,4)='$th' ORDER BY created_at DESC LIMIT 0,1");

        if ($get_data->num_rows() > 0) {
            $row        = $get_data->row();
            $id_outbound_form_part_transfer = substr($row->id_outbound_form_part_transfer, -5);
            $new_kode   = $dealer->kode_dealer_md . '/' . $th_duadigit . '/OFPT-' . sprintf("%'.05d", $id_outbound_form_part_transfer + 1);
            $i = 0;
            while ($i < 1) {
                $cek = $this->db->get_where('tr_h3_dealer_outbound_form_part_transfer', ['id_outbound_form_part_transfer' => $new_kode])->num_rows();
                // $cek = $this->db->query("SELECT id_outbound_form_part_transfer FROM $this->table WHERE id_outbound_form_part_transfer = '$new_kode'")->num_rows();
                if ($cek > 0) {
                    $gen_number    = substr($new_kode, -5);
                    $new_kode = $dealer->kode_dealer_md . '/' . $th_duadigit . '/OFPT-' . sprintf("%'.05d", $gen_number + 1);
                    $i = 0;
                } else {
                    $i++;
                }
            }
        } else {
            $new_kode   = $dealer->kode_dealer_md . '/'.$th_duadigit  . '/OFPT-' . '00001';
        }
        return strtoupper($new_kode);
    }

    public function generateID_old(){
        $th        = date('Y');
        $bln       = date('m');
        $th_bln    = date('Y-m');
        $thbln     = date('ym');
        $dealer    = $this->dealer->getCurrentUserDealer();

        $get_data  = $this->db->query("SELECT * FROM $this->table ORDER BY created_at DESC LIMIT 0,1");

        if ($get_data->num_rows()>0) {
            $row        = $get_data->row();
            $id_outbound_form_part_transfer = substr($row->id_outbound_form_part_transfer, -3);
            $new_kode   = $dealer->kode_dealer_md.'/OFPT-'.sprintf("%'.03d", $id_outbound_form_part_transfer+1);
        } else {
            $new_kode   = $dealer->kode_dealer_md.'/OFPT-'.'001';
        }
        return strtoupper($new_kode);
    }

}
