<?php

class h3_md_diskon_part_tertentu_model extends Honda_Model
{
    protected $table = 'ms_h3_md_diskon_part_tertentu';

    public function insert($data)
    {
        $data['created_by'] = $this->session->userdata('id_user');

        parent::insert($data);
    }

    public function get_diskon($id_part, $id_dealer, $tipe_po, $produk = '')
    {
        $diskon = [
            'id_part' => $id_part,
            'id_dealer' => $id_dealer,
            'diskon_value' => 0,
            'tipe_diskon' => null,
        ];

        $dealer = $this->db
            ->select('id_dealer')
            ->select('kode_dealer_md')
            ->select('nama_dealer')
            ->select('diskon_fixed_order')
            ->select('diskon_reguler')
            ->select('diskon_urgent')
            ->select('diskon_hotline')
            ->select('tipe_diskon')
            ->from('ms_dealer as d')
            ->where('d.id_dealer', $id_dealer)
            ->limit(1)
            ->get()->row_array();

        if ($dealer == null) {
            log_message('error', sprintf('Dealer tidak ditemukan [%s]', $id_dealer));
            return $diskon;
        }

        $part = $this->db
            ->select('p.id_part_int')
            ->select('p.id_part')
            ->from('ms_part as p')
            ->where('p.id_part', $id_part)
            ->limit(1)
            ->get()->row_array();

        if ($part == null) log_message('error', sprintf('Part tidak ditemukan [%s]', $id_part));

        $diskon_part_tertentu_item = $this->db
            ->select('dpti.*')
            ->from('ms_h3_md_diskon_part_tertentu_items as dpti')
            ->join('ms_h3_md_diskon_part_tertentu as dpt', 'dpt.id = dpti.id_diskon_part_tertentu')
            ->where('dpti.id_dealer', $dealer['id_dealer'])
            ->where('dpt.id_part_int', $part['id_part_int'])
            ->where('dpt.active', 1)
            ->limit(1)
            ->get()->row_array();

        if ($diskon_part_tertentu_item != null) {
            $diskon['tipe_diskon'] = $diskon_part_tertentu_item['tipe_diskon'];

            if ($produk == 'Other') {
                $diskon['diskon_value'] = $diskon_part_tertentu_item['diskon_other'];
            } else if ($tipe_po == 'FIX') {
                $diskon['diskon_value'] = $diskon_part_tertentu_item['diskon_fixed'];
            } else if ($tipe_po == 'REG') {
                $diskon['diskon_value'] = $diskon_part_tertentu_item['diskon_reguler'];
            } else if ($tipe_po == 'URG') {
                $diskon['diskon_value'] = $diskon_part_tertentu_item['diskon_urgent'];
            } else if ($tipe_po == 'HLO') {
                $diskon['diskon_value'] = $diskon_part_tertentu_item['diskon_hotline'];
            }

            log_message('info', sprintf('Diskon part %s untuk dealer [%s] %s ditemukan, tipe diskon : %s; diskon_value : %s;', $part['id_part'], $dealer['kode_dealer_md'], $dealer['nama_dealer'], $diskon['tipe_diskon'], $diskon['diskon_value']));

            return $diskon;
        } else {
            log_message('info', sprintf('Diskon part %s untuk dealer [%s] %s tidak ditemukan, akan dilanjutkan pengecekan diskon part tertentu secara general', $part['id_part'], $dealer['kode_dealer_md'], $dealer['nama_dealer']));
        }

        $diskon_part_tertentu = $this->db
            ->select('dpt.*')
            ->from('ms_h3_md_diskon_part_tertentu as dpt')
            ->where('dpt.id_part_int', $part['id_part_int'])
            ->where('dpt.active', 1)
            ->limit(1)
            ->get()->row_array();

        if ($diskon_part_tertentu != null) {
            $diskon['tipe_diskon'] = $diskon_part_tertentu['tipe_diskon'];

            if ($produk == 'Other') {
                $diskon['diskon_value'] = $diskon_part_tertentu['diskon_other'];
            } else if ($tipe_po == 'FIX') {
                $diskon['diskon_value'] = $diskon_part_tertentu['diskon_fixed'];
            } else if ($tipe_po == 'REG') {
                $diskon['diskon_value'] = $diskon_part_tertentu['diskon_reguler'];
            } else if ($tipe_po == 'URG') {
                $diskon['diskon_value'] = $diskon_part_tertentu['diskon_urgent'];
            } else if ($tipe_po == 'HLO') {
                $diskon['diskon_value'] = $diskon_part_tertentu['diskon_hotline'];
            }

            log_message('info', sprintf('Diskon part %s ditemukan, tipe diskon : %s; diskon_value : %s;', $part['id_part'], $diskon['tipe_diskon'], $diskon['diskon_value']));

            return $diskon;
        } else {
            log_message('info', sprintf('Diskon part %s tidak ditemukan, akan dilanjutkan pengecekan diskon part dari dealer', $part['id_part']));
        }

        $diskon['tipe_diskon'] = $dealer['tipe_diskon'];
        if ($tipe_po == 'FIX') {
            $diskon['diskon_value'] = $dealer['diskon_fixed_order'];
        } else if ($tipe_po == 'REG') {
            $diskon['diskon_value'] = $dealer['diskon_reguler'];
        } else if ($tipe_po == 'URG') {
            $diskon['diskon_value'] = $dealer['diskon_urgent'];
        } else if ($tipe_po == 'HLO') {
            $diskon['diskon_value'] = $dealer['diskon_hotline'];
        }

        log_message('info', sprintf('Diskon part %s untuk dealer [%s] %s berdasarkan dealer ditemukan, tipe diskon : %s; diskon_value : %s;', $part['id_part'], $dealer['kode_dealer_md'], $dealer['nama_dealer'], $diskon['tipe_diskon'], $diskon['diskon_value']));

        return $diskon;
    }
}
