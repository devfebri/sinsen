<?php

class H3_md_claim_main_dealer_ke_ahm_model extends Honda_Model
{

    protected $table = 'tr_h3_md_claim_main_dealer_ke_ahm';

    public function insert($data)
    {
        $data['status'] = 'Open';
        $data['created_by'] = $this->session->userdata('id_user');
        $data['created_at'] = date('Y-m-d H:i:s', time());
        parent::insert($data);
    }

    public function update($data, $condition)
    {
        $data['updated_by'] = $this->session->userdata('id_user');
        $data['updated_at'] = date('Y-m-d H:i:s', time());
        parent::update($data, $condition);
    }

    public function generateID()
    {
        $th = date('Y');
        $bln = date('m');
        $th_bln = date('Y-m');
        $thbln = date('ym');

        $query = $this->db
            ->from($this->table)
            ->where("LEFT(created_at, 7)='{$th_bln}'")
            ->order_by('created_at', 'desc')
            ->order_by('id_claim', 'desc')
            ->limit(1)
            ->get();

        if ($query->num_rows() > 0) {
            $row  = $query->row();
            $id_claim = substr($row->id_claim, 0, 5);
            $id_claim = sprintf("%'.05d", $id_claim + 1);
            $id = "{$id_claim}/E20/C3/{$this->numberToRomanRepresentation($bln)}/{$th}";
        } else {
            $id = "00001/E20/C3/{$this->numberToRomanRepresentation($bln)}/{$th}";
        }

        return strtoupper($id);
    }

    private function numberToRomanRepresentation($number)
    {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    public function set_int_relation($id)
    {
        $claim_main_dealer = $this->db
            ->select('ps.id as packing_sheet_number_int')
            ->select('ps.invoice_number_int')
            ->select('ps.invoice_number')
            ->from('tr_h3_md_claim_main_dealer_ke_ahm as cmd')
            ->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = cmd.packing_sheet_number')
            ->where('cmd.id', $id)
            ->get()->row_array();

        if ($claim_main_dealer == null) return;

        $this->db
            ->set('packing_sheet_number_int', $claim_main_dealer['packing_sheet_number_int'])
            ->set('invoice_number_int', $claim_main_dealer['invoice_number_int'])
            ->set('invoice_number', $claim_main_dealer['invoice_number'])
            ->where('id', $id)
            ->update($this->table);
    }
}
