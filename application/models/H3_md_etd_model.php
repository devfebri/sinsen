<?php

class h3_md_etd_model extends Honda_Model{

    // protected $table = 'ms_h3_md_estimated_time_delivery';
    var $table = 'ms_h3_md_estimated_time_delivery';
    var $column_order = array(null, 'nama_dealer',null); //field yang ada di table user
    var $column_search = array('nama_dealer'); //field yang diizin untuk pencarian 
    var $order = array('id' => 'asc'); // default order 

    public function get_estimated_time_delivery($part, $claim, $tipe_claim, $id_dealer){
        $this->load->library('Mcarbon');

        $part = $this->db
        ->from('ms_part as p')
        ->where('p.id_part', $part)
        ->get()->row_array();

        $etd = $this->db
        ->select('etd.*')
        ->from('ms_h3_md_estimated_time_delivery_items as etdi')
        ->join('ms_h3_md_estimated_time_delivery as etd', 'etd.id = etdi.id_etd')
        ->where('etdi.id_dealer', $id_dealer)
        ->limit(1)
        ->get()->row_array();

        $eta_terlama = Mcarbon::now();
        $eta_tercepat = Mcarbon::now();
        if($etd != null){
            if($claim == 1 AND $tipe_claim == 'renumbering_claim'){
                $eta_terlama->addDays(
                    // ($etd['ahm_md'] + $etd['proses_md'] + $etd['md_d'] + $etd['rc'])
                    ($etd['ahm_md'] + $etd['max_md_d'] + $etd['proses_md'] + $etd['rc'])
                );
            }else if($claim == 1 AND $tipe_claim == 'renumbering_non_claim'){
                $eta_terlama->addDays(
                    // ($etd['ahm_md'] + $etd['proses_md'] + $etd['md_d'] + $etd['rn'])
                    ($etd['ahm_md'] + $etd['max_md_d'] + $etd['proses_md'] + $etd['rn'])
                );
            }else if($part['import_lokal'] == 'N' AND $part['current'] == 'C'){
                $eta_terlama->addDays(
                    // ($etd['ahm_md'] + $etd['proses_md'] + $etd['md_d'] + $etd['lc'])
                    ($etd['ahm_md'] + $etd['proses_md']+ $etd['max_md_d'] + $etd['lc'])
                );
            }else if($part['import_lokal'] == 'N' AND $part['current'] == 'N'){
                $eta_terlama->addDays(
                    // ($etd['ahm_md'] + $etd['proses_md'] + $etd['md_d'] + $etd['ln'])
                    ($etd['ahm_md'] + $etd['proses_md'] + $etd['max_md_d'] + $etd['ln'])
                );
            }else if($part['import_lokal'] == 'Y' AND $part['current'] == 'C'){
                $eta_terlama->addDays(
                    // ($etd['ahm_md'] + $etd['proses_md'] + $etd['md_d'] + $etd['ic'])
                    ($etd['ahm_md'] + $etd['proses_md'] + $etd['max_md_d'] + $etd['ic'])
                );
            }else if($part['import_lokal'] == 'Y' AND $part['current'] == 'N'){
                $eta_terlama->addDays(
                    // ($etd['ahm_md'] + $etd['proses_md'] + $etd['md_d'] + $etd['in'])
                    ($etd['ahm_md'] + $etd['proses_md'] + $etd['max_md_d'] + $etd['in'])
                );
            }

            $eta_tercepat->addDays(
                // ($etd['proses_md'] + $etd['md_d'])
                ($etd['proses_md'] + $etd['min_md_d'])
            );
        }else{
            if($claim == 1 AND $tipe_claim == 'renumbering_claim'){
                $eta_terlama->addDays(17);
            }else if($claim == 1 AND $tipe_claim == 'renumbering_non_claim'){
                $eta_terlama->addDays(24);
            }else if($part['import_lokal'] == 'N' AND $part['current'] == 'C'){
                $eta_terlama->addDays(5);
            }else if($part['import_lokal'] == 'N' AND $part['current'] == 'N'){
                $eta_terlama->addDays(7);
            }else if($part['import_lokal'] == 'Y' AND $part['current'] == 'C'){
                $eta_terlama->addDays(25);
            }else if($part['import_lokal'] == 'Y' AND $part['current'] == 'N'){
                $eta_terlama->addDays(47);
            }

            $eta_tercepat->addDays(2);
        }

        return [
            'id_part' => $part['id_part'],
            'eta_terlama' => $eta_terlama->format('Y-m-d'),
            'eta_tercepat' => $eta_tercepat->format('Y-m-d'),
        ];
    }

    public function tabel_eta()
    {
        $id = $this->input->get('id');
        $tabel_eta = $this->db->query("
        select b.id_dealer, a.ahm_md, a.proses_md, a.md_d, a.min_md_d, a.max_md_d, a.proses_d,c.kode_dealer_md 
        from ms_h3_md_estimated_time_delivery a
        left join ms_h3_md_estimated_time_delivery_items b on a.id=b.id_etd 
        join ms_dealer c on c.id_dealer=b.id_dealer 
        where a.id='$id'");
        return $tabel_eta;
    }

    private function _get_datatables_query()
    {
         
        $this->db->select('a.id,c.nama_dealer, b.id_dealer, a.ahm_md, a.proses_md,a.md_d,a.min_md_d, a.max_md_d, a.proses_d, c.kode_dealer_md')
                 ->from('ms_h3_md_estimated_time_delivery as a')
                 ->join('ms_h3_md_estimated_time_delivery_items as b', 'a.id=b.id_etd','left')
                 ->join('ms_dealer as c', 'c.id_dealer=b.id_dealer');
                //  ->where('a.id', $e->id)
                //  ->get()->result();
 
        $i = 0;
     
        foreach ($this->column_search as $item) // looping awal
        {
            if($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {
                 
                if($i===0) // looping awal
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) 
                    $this->db->group_end(); 
            }
            $i++;
        }
         
        if(isset($_POST['order'])) 
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }

}
