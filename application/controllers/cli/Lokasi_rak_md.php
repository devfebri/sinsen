<?php

class Lokasi_rak_md extends Honda_Controller
{

    public function set_kapasitas_terpakai()
    {
        $this->db
            ->set('kapasitas_terpakai', 0)
            ->update('ms_h3_md_lokasi_rak');

        $this->db
            ->select('sp.id_lokasi_rak')
            ->from('tr_stok_part as sp');

        $lokasi_ada_stock = array_map(function ($row) {
            return $row['id_lokasi_rak'];
        }, $this->db->get()->result_array());

        if (count($lokasi_ada_stock) > 0) {
            $list_lokasi = $this->db
                ->select('id')
                ->from('ms_h3_md_lokasi_rak')
                ->where_in('id', $lokasi_ada_stock)
                ->get()->result_array();

            foreach ($list_lokasi as $lokasi) {
                $kapasitas_terpakai = 0;

                $stock = $this->db
                    ->select('IFNULL(SUM(qty), 0) as stock')
                    ->from('tr_stok_part')
                    ->where('id_lokasi_rak', $lokasi['id'])
                    ->get()->row_array();

                if ($stock != null) {
                    $kapasitas_terpakai = $stock['stock'];
                }

                $this->db
                    ->set('kapasitas_terpakai', $kapasitas_terpakai)
                    ->where('id', $lokasi['id'])
                    ->update('ms_h3_md_lokasi_rak');
            }
        }
    }
}
