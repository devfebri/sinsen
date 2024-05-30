<?php

class h3_dealer_sales_order_parts_model extends Honda_Model
{
    protected $table = 'tr_h3_dealer_sales_order_parts';

    public function book_by_sales_order($id_dealer, $id_part, $id_gudang, $id_rak, $sql = false)
    {
        $data = $this->db
            ->select(sprintf('( IFNULL( SUM(sop_%s.kuantitas), 0) - IFNULL( SUM(sop_%s.kuantitas_return), 0) ) as kuantitas', __FUNCTION__, __FUNCTION__), FALSE)
            ->from(sprintf('tr_h3_dealer_sales_order_parts as sop_%s', __FUNCTION__))
            ->join(sprintf('tr_h3_dealer_sales_order as so_%s', __FUNCTION__), sprintf('so_%s.nomor_so = sop_%s.nomor_so', __FUNCTION__, __FUNCTION__))
            ->group_start()
            ->where(sprintf('so_%s.status !=', __FUNCTION__), 'Closed')
            ->where(sprintf('so_%s.status !=', __FUNCTION__), 'Canceled')
            ->group_end()
            ->where(sprintf('sop_%s.id_part', __FUNCTION__), $id_part, !$sql)
            ->where(sprintf('sop_%s.id_gudang', __FUNCTION__), $id_gudang, !$sql)
            ->where(sprintf('sop_%s.id_rak', __FUNCTION__), $id_rak, !$sql)
            ->where(sprintf('so_%s.id_dealer', __FUNCTION__), $id_dealer, !$sql);

        if ($sql) {
            return $this->db->get_compiled_select();
        }

        $data = $this->db->get()->row_array();

        if ($data != null) {
            return $data['kuantitas'];
        }

        return 0;
    }

    public function update_harga($nomor_so)
    {
        $this->load->helper('calculate_discount');
        
        $parts = $this->db
            ->select('sop.*')
            ->select('p.harga_dealer_user as harga_baru')
            ->from(sprintf('%s as sop', $this->table))
            ->join('ms_part as p', 'p.id_part = sop.id_part')
            ->where('sop.nomor_so', $nomor_so)
            ->get()->result_array();

        foreach ($parts as $part) {
            $harga_lama = $part['harga_saat_dibeli'];
            $harga_baru = $part['harga_baru'];
            $diskon_harga_baru = calculate_discount($part['diskon_value'], $part['tipe_diskon'], $part['harga_baru']);
            $harga_baru_setelah_diskon = $harga_baru - $diskon_harga_baru;

            $kuantitas = $part['kuantitas'];
            if ($part['tipe_diskon'] == 'FoC') $kuantitas -= $part['diskon_value'];
            $total_harga_part = $harga_baru_setelah_diskon * ($kuantitas - $part['kuantitas_return']);

            if ($harga_lama != $harga_baru) {
                $this->db
                    ->set('harga_saat_dibeli', $harga_baru)
                    ->set('harga_setelah_diskon', $harga_baru_setelah_diskon)
                    ->set('tot_harga_part', $total_harga_part)
                    ->where('id', $part['id'])
                    ->update($this->table);

                log_message('info', sprintf('Harga part %s pada sales order dealer %s diupdate menjadi %s', $part['id_part'], $part['nomor_so'], $harga_baru));
            }
        }
    }

    public function total_amount_parts($nomor_so)
    {
        $parts = $this->db
            ->select('sop.tot_harga_part')
            ->from(sprintf('%s as sop', $this->table))
            ->where('sop.nomor_so', $nomor_so)
            ->get()->result_array();

        return array_sum(
            array_map(function ($row) {
                return $row['tot_harga_part'];
            }, $parts)
        );
    }
}
