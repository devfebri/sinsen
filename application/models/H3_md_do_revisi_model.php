<?php

class H3_md_do_revisi_model extends Honda_Model
{

    protected $table = 'tr_h3_md_do_revisi';

    public function __construct()
    {
        parent::__construct();

        $this->load->model('H3_md_ms_plafon_model', 'plafon');
        $this->load->model('H3_md_do_revisi_item_model', 'do_revisi_item');
        $this->load->model('H3_md_pemenuhan_po_dari_dealer_model', 'pemenuhan_po_dari_dealer');

        $this->load->library('Mcarbon');
    }

    public function insert($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s', time());
        $data['created_by'] = $this->session->userdata('id_user');
        $data['status'] = 'Open';

        $do_revisi_sudah_pernah_dibuat = $this->db
            ->from(sprintf('%s as dr', $this->table))
            ->where('dr.id_do_sales_order', $data['id_do_sales_order'])
            ->where('dr.source', $data['source'])
            ->where('dr.status', 'Open')
            ->limit(1)
            ->get()->row_array();

        if ($do_revisi_sudah_pernah_dibuat != null) {
            send_json([
                'message' => 'DO revisi sudah pernah dibuatkan sebelumnya',
            ], 403);
        }

        parent::insert($data);
    }

    public function get_data($id)
    {
        $terdapat_revisi_proses_scan = $this->db
            ->select('dr_sq.id')
            ->from('tr_h3_md_do_revisi as dr_sq')
            ->where('dr_sq.source', 'scan_picking_list')
            ->where('dr_sq.id_do_sales_order = dr.id_do_sales_order')
            ->get_compiled_select();

        $do_revisi = $this->db
            ->select('dr.id')
            ->select('date_format(dso.tanggal, "%d-%m-%Y") as tanggal_do')
            ->select('so.id_dealer')
            ->select('dso.id_do_sales_order')
            ->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_so')
            ->select('so.id_sales_order')
            ->select('d.nama_dealer')
            ->select('d.kode_dealer_md as kode_dealer')
            ->select('d.alamat')
            ->select('so.kategori_po')
            ->select('so.produk')
            ->select('date_format(dso.top, "%d/%m/%Y") as top', false)
            ->select('so.po_type')
            ->select('dr.status')
            ->select('dso.diskon_additional')
            ->select('dr.diskon_insentif_revisi')
            ->select('dr.diskon_cashback_revisi')
            ->select('dr.diskon_cashback_otomatis_revisi')
            ->select('so.id_dealer')
            ->select('so.id_salesman')
            ->select('k.nama_lengkap as nama_salesman')
            ->select(
                "
            case
                when dr.source = 'validasi_picking_list' then if(({$terdapat_revisi_proses_scan}), 1, 0)
                else 0
            end as terdapat_revisi_scan",
                false
            )
            ->select('dso.sudah_revisi')
            ->from('tr_h3_md_do_revisi as dr')
            ->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = dr.id_do_sales_order')
            ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
            ->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
            ->join('ms_karyawan as k', 'k.id_karyawan = so.id_salesman', 'left')
            ->where('dr.id', $id)
            ->limit(1)
            ->get()->row_array();

        if ($do_revisi == null) {
            throw new Exception(sprintf('Delivery order revisi [%s] tidak ditemukan', $id));
        }

        $do_revisi['plafon_booking'] = $this->plafon->get_plafon_booking($do_revisi['id_dealer']);
        $do_revisi['plafon'] = $this->plafon->get_plafon($do_revisi['id_dealer']);

        return $do_revisi;
    }

    public function update_harga_open_do_revisi()
    {
        $this->db
            ->select('dr.id')
            ->select('dr.id_do_sales_order')
            ->from('tr_h3_md_do_revisi as dr')
            ->where('dr.status', 'Open');

        foreach ($this->db->get()->result_array() as $row) {
            $this->update_harga($row['id']);
        }
    }

    public function update_harga($id)
    {
        $this->load->helper('rupiah_format');

        $do_revisi = $this->db
            ->from('tr_h3_md_do_revisi as dr')
            ->where('dr.id', $id)
            ->get()->row_array();

        if ($do_revisi == null) {
            log_message('debug', sprintf('DO revisi tidak ditemukan [%s]', $id));
            return;
        }

        $this->db
            ->select('dri.id')
            ->where('dri.id_revisi', $id)
            ->from('tr_h3_md_do_revisi_item as dri');

        foreach ($this->db->get()->result_array() as $row) {
            $this->do_revisi_item->hitung_harga_setelah_diskon($row['id']);
        }

        $this->set_amount($id);
    }

    public function set_amount($id)
    {
        $do_revisi = $this->db
            ->from('tr_h3_md_do_revisi as dr')
            ->where('dr.id', $id)
            ->get()->row_array();

        if ($do_revisi == null) throw new Exception(sprintf('DO revisi tidak ditemukan [%s]', $id));

        $parts = $this->db
            ->select('dop.id_part')
            ->select('(dri.qty_do * dri.harga_setelah_diskon) as amount_do')
            ->select('(dri.qty_revisi * dri.harga_setelah_diskon) as amount_do_revisi')
            ->select('dop.harga_jual')
            ->select('dri.tipe_diskon_satuan_dealer')
            ->select('dri.diskon_satuan_dealer')
            ->select('dri.tipe_diskon_campaign')
            ->select('dri.diskon_campaign')
            ->select('dri.id_campaign_diskon')
            ->select('dri.harga_setelah_diskon')
            ->from('tr_h3_md_do_revisi_item as dri')
            ->join('tr_h3_md_do_sales_order_parts as dop', sprintf('(dop.id_part = dri.id_part and dop.id_do_sales_order = "%s")', $do_revisi['id_do_sales_order']))
            ->where('dri.id_revisi', $do_revisi['id'])
            ->get()->result_array();

        $total_diskon_do = $do_revisi['diskon_insentif'] + ($do_revisi['diskon_cashback'] + $do_revisi['diskon_cashback_otomatis']);
        $amount_do_parts = array_sum(
            array_column($parts, 'amount_do')
        );
        $total_do = $amount_do_parts - $total_diskon_do;

        $this->db
            ->set('dr.sub_total', $amount_do_parts)
            ->set('dr.total', $total_do)
            ->where('dr.id', $id)
            ->update(sprintf('%s as dr', $this->table));

        log_message('debug', sprintf('Update sub total dan total do pada do revisi [%s] subtotal: %s; total: %s;', $id, rupiah_format($amount_do_parts), rupiah_format($total_do)));

        $total_diskon_do_revisi = $do_revisi['diskon_insentif_revisi'] + ($do_revisi['diskon_cashback_revisi'] + $do_revisi['diskon_cashback_otomatis_revisi']);
        $amount_do_revisi_parts = array_sum(
            array_column($parts, 'amount_do_revisi')
        );
        $total_do_revisi = $amount_do_revisi_parts - $total_diskon_do_revisi;

        $this->db
            ->set('dr.sub_total_revisi', $amount_do_revisi_parts)
            ->set('dr.total_revisi', $total_do_revisi)
            ->where('dr.id', $id)
            ->update(sprintf('%s as dr', $this->table));

        log_message('debug', sprintf('Update sub total dan total do revisi pada do revisi [%s] subtotal: %s; total: %s;', $id, rupiah_format($amount_do_revisi_parts), rupiah_format($total_do_revisi)));
    }

    public function update_diskon_open_do_revisi()
    {
        $this->db
            ->select('dr.id')
            ->select('dr.id_do_sales_order')
            ->from('tr_h3_md_do_revisi as dr')
            ->join('tr_h3_md_do_sales_order as do', '(do.id_do_sales_order = dr.id_do_sales_order)')
            ->where('do.sudah_create_faktur', 0);

        foreach ($this->db->get()->result_array() as $row) {
            $this->update_diskon($row['id']);
        }
    }

    public function update_diskon($id)
    {
        $this->load->helper('rupiah_format');
        $this->load->helper('get_diskon_part');
        $this->load->helper('harga_setelah_diskon');

        $do_revisi = $this->db
            ->select('so.id_dealer')
            ->select('so.po_type')
            ->select('so.produk')
            ->select('so.kategori_po')
            ->from('tr_h3_md_do_revisi as dr')
            ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = dr.id_do_sales_order')
            ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
            ->where('dr.id', $id)
            ->get()->row_array();

        if ($do_revisi == null) {
            throw new Exception(sprintf('DO revisi tidak ditemukan [%s]', $id));
        }

        $parts = $this->db
            ->select('dri.id')
            ->select('dri.id_part')
            ->select('dri.qty_revisi as kuantitas')
            ->select('dri.tipe_diskon_satuan_dealer')
            ->select('dri.diskon_satuan_dealer')
            ->select('dri.tipe_diskon_campaign')
            ->select('dri.diskon_campaign')
            ->select('dri.id_campaign_diskon')
            ->from('tr_h3_md_do_revisi_item as dri')
            ->where('dri.id_revisi', $id)
            ->get()->result_array();

        $parts = get_diskon_part($do_revisi['id_dealer'], $do_revisi['po_type'], $do_revisi['produk'], $do_revisi['kategori_po'], $parts);
        $parts = array_map(function ($part) {
            $part['tipe_diskon_satuan_dealer'] = $part['tipe_diskon'];
            $part['diskon_satuan_dealer'] = $part['diskon_value'];

            $part['diskon_campaign'] = $part['diskon_value_campaign'];

            unset($part['tipe_diskon']);
            unset($part['diskon_value']);
            unset($part['diskon_value_campaign']);

            return $part;
        }, $parts);

        foreach ($parts as $part) {
            $this->db
                ->set('dri.tipe_diskon_satuan_dealer', $part['tipe_diskon_satuan_dealer'])
                ->set('dri.diskon_satuan_dealer', $part['diskon_satuan_dealer'])
                ->set('dri.tipe_diskon_campaign', $part['tipe_diskon_campaign'])
                ->set('dri.diskon_campaign', $part['diskon_campaign'])
                ->set('dri.id_campaign_diskon', $part['id_campaign_diskon'])
                ->where('dri.id', $part['id'])
                ->update('tr_h3_md_do_revisi_item as dri');

            log_message('debug', sprintf('Update diskon delivery order revisi item [%s] [payload] %s', $part['id'], print_r($part, true)));

            $this->do_revisi_item->hitung_harga_setelah_diskon($part['id']);
        }

        $this->set_amount($id);
    }

    public function approve($id)
    {
        $this->check_if_approved($id);
        $this->delivery_order_direvisi($id);

        $this->proses_revisi_item_gabungan($id);
        $this->proses_revisi_item($id);

        $this->check_so_for_back_order($id);
        $this->update_gimmick_dan_cashback($id);

        $this->update_diskon_dan_total($id);
    }

    private function check_if_approved($id)
    {
        $do_revisi_approved = $this->do_revisi->get([
            'id' => $id,
            'status' => 'Approved'
        ], true);

        if ($do_revisi_approved != null) {
            throw new Exception('DO Revisi sudah pernah diapprove sebelumnya.');
        }

        $this->update([
            'status' => 'Approved',
            'approved_at' => Mcarbon::now()->toDateTimeString(),
            'approved_by' => $this->session->userdata('id_user')
        ], [
            'id' => $id
        ]);
    }

    private function delivery_order_direvisi($id)
    {
        $this->load->model('H3_md_do_sales_order_model', 'do_sales_order');

        $do_revisi = $this->db
            ->select('dr.id_do_sales_order')
            ->from(sprintf('%s as dr', $this->table))
            ->where('dr.id', $id)
            ->get()->row_array();

        if ($do_revisi == null) throw new Exception(sprintf('DO revisi tidak ditemukan [%s]', $id));

        $this->do_sales_order->set_revisi($do_revisi['id_do_sales_order']);
    }

    private function proses_revisi_item_gabungan($id)
    {
        $this->load->model('H3_md_do_sales_order_model', 'do_sales_order');
        $this->load->model('h3_md_sales_order_model', 'sales_order');

        $do_revisi = (array) $this->find($id);
        $do_sales_order = (array) $this->do_sales_order->find($do_revisi['id_do_sales_order'], 'id_do_sales_order');
        $sales_order = (array) $this->sales_order->find($do_sales_order['id_sales_order'], 'id_sales_order');

        if ($sales_order == null) throw new Exception(sprintf('Sales order dengan nomor %s tidak ditemukan', $sales_order['id_sales_order']));
        $kategori_po = $sales_order['kategori_po'];

        $this->db
            ->select('so.po_type')
            ->select('so.id_ref')
            ->select('so.id_rekap_purchase_order_dealer')
            ->select('pl.id_picking_list')
            ->select('dr.id_do_sales_order')
            ->select('dr.source')
            ->select('dri.id_part')
            ->select('dri.id_tipe_kendaraan')
            ->select('dop.qty_supply as qty_do')
            ->select('dri.qty_revisi')
            ->select('(dop.qty_supply - dri.qty_revisi) as selisih', false)
            ->from('tr_h3_md_do_revisi_item as dri')
            ->join('tr_h3_md_do_revisi as dr', 'dr.id = dri.id_revisi')
            ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = dr.id_do_sales_order');

        if ($kategori_po == 'KPB') {
            $this->db->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_do_sales_order_int = do.id AND dop.id_part = dri.id_part AND dop.id_tipe_kendaraan = dri.id_tipe_kendaraan)');
        } else {
            $this->db->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_do_sales_order_int = do.id AND dop.id_part = dri.id_part)');
        }

        $this->db
            ->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
            ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
            ->where('dri.id_revisi', $id);
        $items = $this->db->get()->result_array();

        foreach ($items as $item) {
            $condition = [
                'id_part' => $item['id_part'],
                'id_do_sales_order' => $item['id_do_sales_order']
            ];

            if ($kategori_po == 'KPB') {
                $condition['id_tipe_kendaraan'] = $item['id_tipe_kendaraan'];
            }

            $this->do_sales_order_parts->update([
                'qty_supply' => $item['qty_revisi']
            ], $condition);

            if ($item['source'] == 'scan_picking_list') {
                if ($kategori_po == 'KPB') {
                    $this->order_parts_tracking->kurang_qty_pick($item['id_ref'], $item['id_part'], $item['selisih'], $item['id_tipe_kendaraan']);
                } else {
                    $this->order_parts_tracking->kurang_qty_pick($item['id_ref'], $item['id_part'], $item['selisih']);
                }
                if ($item['id_rekap_purchase_order_dealer'] != null) {
                    $jumlah_item = $this->db
                        ->select('SUM( pop.kuantitas - ppd.qty_supply) as jumlah_item', false)
                        ->from('tr_h3_dealer_purchase_order_parts as pop')
                        ->join('tr_h3_md_pemenuhan_po_dari_dealer as ppd', '(ppd.po_id = pop.po_id AND ppd.id_part = pop.id_part)')
                        ->where('pop.po_id = po.po_id', null, false)
                        ->get_compiled_select();

                    $this->db
                        ->select('po.po_id')
                        ->select('pop.id_part')
                        ->select('(opt.qty_pick - opt.qty_pack) as selisih')
                        ->select("IFNULL(({$jumlah_item}), 0) as jumlah_item", false)
                        ->from('tr_h3_md_rekap_purchase_order_dealer_item as ri')
                        ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = ri.id_referensi')
                        ->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id = ri.id_referensi')
                        ->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)')
                        ->where('ri.id_rekap', $item['id_rekap_purchase_order_dealer'])
                        ->where('pop.id_part', $item['id_part'])
                        ->where('opt.qty_pick > 0')
                        ->order_by('jumlah_item', 'asc')
                        ->order_by('po.created_at', 'desc');

                    if ($kategori_po == 'KPB') {
                        $this->db->select('pop.id_tipe_kendaraan');
                        $this->db->where('pop.id_tipe_kendaraan', $item['id_tipe_kendaraan']);
                    }

                    $purchase_orders = $this->db->get()->result_array();

                    $supply_untuk_dipecah = $item['selisih'];
                    foreach ($purchase_orders as $purchase_order) {
                        if ($purchase_order['selisih'] <= $supply_untuk_dipecah) {
                            if ($kategori_po == 'KPB') {
                                $this->order_parts_tracking->kurang_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['selisih'], $purchase_order['id_tipe_kendaraan']);
                            } else {
                                $this->order_parts_tracking->kurang_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['selisih']);
                            }
                            $supply_untuk_dipecah -= $purchase_order['selisih'];
                        } else if ($purchase_order['selisih'] >= $supply_untuk_dipecah) {
                            if ($kategori_po == 'KPB') {
                                $this->order_parts_tracking->kurang_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah, $purchase_order['id_tipe_kendaraan']);
                            } else {
                                $this->order_parts_tracking->kurang_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah);
                            }
                            break;
                        }

                        if ($supply_untuk_dipecah == 0) break;
                    }
                }

                $this->scan_picking_list->update([
                    'qty_picking' => $item['qty_revisi'],
                    'qty_do' => $item['qty_revisi'],
                ], [
                    'id_part' => $item['id_part'],
                    'id_picking_list' => $item['id_picking_list']
                ]);
            }

            if ($kategori_po == 'KPB') {
                $this->order_parts_tracking->kurang_qty_book($item['id_ref'], $item['id_part'], $item['selisih'], $item['id_tipe_kendaraan']);
            } else {
                $this->order_parts_tracking->kurang_qty_book($item['id_ref'], $item['id_part'], $item['selisih']);
            }
            if ($item['id_rekap_purchase_order_dealer'] != null) {
                $jumlah_item = $this->db
                    ->select('SUM( pop.kuantitas - ppd.qty_supply) as jumlah_item', false)
                    ->from('tr_h3_dealer_purchase_order_parts as pop')
                    ->join('tr_h3_md_pemenuhan_po_dari_dealer as ppd', '(ppd.po_id = pop.po_id AND ppd.id_part = pop.id_part)')
                    ->where('pop.po_id = po.po_id', null, false)
                    ->get_compiled_select();

                $this->db
                    ->select('po.po_id')
                    ->select('pop.id_part')
                    ->select('(opt.qty_book - opt.qty_pick) as selisih', false)
                    ->select("IFNULL(({$jumlah_item}), 0) as jumlah_item", false)
                    ->from('tr_h3_md_rekap_purchase_order_dealer_item as ri')
                    ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = ri.id_referensi')
                    ->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id = ri.id_referensi')
                    ->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)')
                    ->where('ri.id_rekap', $item['id_rekap_purchase_order_dealer'])
                    ->where('pop.id_part', $item['id_part'])
                    ->where('opt.qty_book > 0')
                    ->order_by('jumlah_item', 'asc')
                    ->order_by('po.created_at', 'desc');

                if ($kategori_po == 'KPB') {
                    $this->db->select('pop.id_tipe_kendaraan');
                    $this->db->where('pop.id_tipe_kendaraan', $item['id_tipe_kendaraan']);
                }

                $purchase_orders = $this->db->get()->result_array();

                $supply_untuk_dipecah = $item['selisih'];
                foreach ($purchase_orders as $purchase_order) {
                    if ($purchase_order['selisih'] <= $supply_untuk_dipecah) {
                        if ($kategori_po == 'KPB') {
                            $this->order_parts_tracking->kurang_qty_book($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['selisih'], $purchase_order['id_tipe_kendaraan']);
                        } else {
                            $this->order_parts_tracking->kurang_qty_book($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['selisih']);
                            $this->pemenuhan_po_dari_dealer->kurangi_qty_do($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['selisih']);
                            $this->pemenuhan_po_dari_dealer->tambah_qty_so($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['selisih']);
                        }
                        $supply_untuk_dipecah -= $purchase_order['selisih'];
                    } else if ($purchase_order['selisih'] >= $supply_untuk_dipecah) {
                        if ($kategori_po == 'KPB') {
                            $this->order_parts_tracking->kurang_qty_book($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah, $purchase_order['id_tipe_kendaraan']);
                        } else {
                            $this->order_parts_tracking->kurang_qty_book($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah);
                            $this->pemenuhan_po_dari_dealer->kurangi_qty_do($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah);
                            $this->pemenuhan_po_dari_dealer->tambah_qty_so($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah);
                        }
                        break;
                    }

                    if ($supply_untuk_dipecah == 0) break;
                }
            }
        }
    }

    private function proses_revisi_item($id)
    {
        $this->load->model('H3_md_do_sales_order_model', 'do_sales_order');
        $this->load->model('h3_md_sales_order_model', 'sales_order');

        $do_revisi = (array) $this->find($id);
        $do_sales_order = (array) $this->do_sales_order->find($do_revisi['id_do_sales_order'], 'id_do_sales_order');
        $sales_order = (array) $this->sales_order->find($do_sales_order['id_sales_order'], 'id_sales_order');

        if ($sales_order == null) throw new Exception(sprintf('Sales order dengan nomor %s tidak ditemukan', $sales_order['id_sales_order']));
        $kategori_po = $sales_order['kategori_po'];

        $this->db
            ->select('so.po_type')
            ->select('so.id_ref')
            ->select('so.id_rekap_purchase_order_dealer')
            ->select('pl.id_picking_list')
            ->select('dr.id_do_sales_order')
            ->select('dr.source')
            ->select('p.id_part')
            ->select('drdi.id_lokasi_rak')
            ->select('drdi.qty_awal as qty_do')
            ->select('drdi.qty_revisi')
            ->select('(drdi.qty_awal - drdi.qty_revisi) as selisih')
            ->from('tr_h3_md_do_revisi_detail_item as drdi')
            ->join('ms_part as p', 'p.id_part_int = drdi.id_part_int')
            ->join('tr_h3_md_do_revisi as dr', 'dr.id = drdi.id_revisi')
            ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = dr.id_do_sales_order')
            ->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
            ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
            ->where('drdi.id_revisi', $id);

        if ($kategori_po == 'KPB') {
            $this->db->select('dri.id_tipe_kendaraan');
        }

        $items = $this->db->get()->result_array();

        foreach ($items as $item) {
            $condition = [
                'id_part' => $item['id_part'],
                'id_lokasi_rak' => $item['id_lokasi_rak'],
                'id_picking_list' => $item['id_picking_list']
            ];

            if ($kategori_po == 'KPB') {
                $condition['id_tipe_kendaraan'] = $item['id_tipe_kendaraan'];
            }

            $this->picking_list_parts->update([
                'qty_supply' => $item['qty_revisi'],
                'qty_disiapkan' => $item['qty_revisi'],
            ], $condition);

            if (($item['po_type'] == 'HLO' || $item['po_type'] == 'URG') and $item['id_ref'] != null and $item['id_ref'] != '') {
                $this->pemenuhan_po_dari_dealer->kurangi_qty_do($item['id_ref'], $item['id_part'], $item['selisih']);
                $this->pemenuhan_po_dari_dealer->tambah_qty_so($item['id_ref'], $item['id_part'], $item['selisih']);
            }
        }
    }

    private function check_so_for_back_order($id)
    {
        $do_revisi = $this->db
            ->select('dr.id_do_sales_order')
            ->from(sprintf('%s as dr', $this->table))
            ->where('dr.id', $id)
            ->get()->row_array();

        if ($do_revisi == null) throw new Exception(sprintf('DO revisi tidak ditemukan [%s]', $id));
        $id_do_sales_order = $do_revisi['id_do_sales_order'];

        $do_sales_order = $this->db
            ->select('do.id_sales_order')
            ->select('so.kategori_po')
            ->from('tr_h3_md_do_sales_order as do')
            ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
            ->where('do.id_do_sales_order', $id_do_sales_order)
            ->get()->row_array();

        if ($do_sales_order == null) return;

        $this->db
            ->select('dop.qty_supply')
            ->from('tr_h3_md_do_sales_order as do')
            ->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
            ->where('do.id_sales_order = sop.id_sales_order')
            ->where('dop.id_part = sop.id_part');

        if ($do_sales_order['kategori_po']) {
            $this->db->where('dop.id_tipe_kendaraan = sop.id_tipe_kendaraan', null, false);
        }

        $qty_supply = $this->db->get_compiled_select();

        $this->db
            ->select('sop.id_part')
            ->select('sop.qty_order')
            ->select("IFNULL(({$qty_supply}), 0) as qty_supply")
            ->from('tr_h3_md_sales_order_parts as sop')
            ->where('sop.id_sales_order', $do_sales_order['id_sales_order']);

        if ($do_sales_order['kategori_po']) {
            $this->db->select('sop.id_tipe_kendaraan');
        }

        $sales_order_parts = $this->db->get()->result_array();

        $selisih = false;
        if (count($sales_order_parts) > 0) {
            foreach ($sales_order_parts as $part) {
                if (intval($part['qty_order']) != intval($part['qty_supply'])) {
                    $selisih = true;
                    break;
                }
            }
        }

        if ($selisih) {
            $this->db
                ->set('so.status', 'Back Order')
                ->set('so.back_order', 1)
                ->where('so.id_sales_order', $do_sales_order['id_sales_order'])
                ->update('tr_h3_md_sales_order as so');
        }
    }

    public function update_gimmick_dan_cashback($id)
    {
        $this->load->model('H3_md_do_sales_order_model', 'do_sales_order');

        $delivery_order_revisi = (array) $this->find($id);

        if ($delivery_order_revisi == null) throw new Exception(sprintf('DO Revisi tidak ditemukan [%s]', $id));

        $this->do_sales_order->update_gimmick_dan_cashback($delivery_order_revisi['id_do_sales_order']);
    }

    public function update_diskon_dan_total($id)
    {
        $this->load->model('H3_md_do_sales_order_model', 'do_sales_order');

        $delivery_order_revisi = (array) $this->find($id);
        $delivery_order = (array) $this->do_sales_order->find($delivery_order_revisi['id_do_sales_order'], 'id_do_sales_order');

        $this->do_sales_order->update_diskon($delivery_order['id']);
        $this->do_sales_order->update_total_do($delivery_order_revisi['id_do_sales_order']);
    }

    public function set_delivery_order_revisi_status($id, $total)
    {
        $this->load->model('H3_md_do_sales_order_model', 'do_sales_order');

        $delivery_order_revisi = (array) $this->find($id);

        if ($delivery_order_revisi == null) throw new Exception(sprintf('DO revisi tidak ditemukan [%s]', $id));

        if ($delivery_order_revisi['total'] < $total) {
            $this->do_sales_order->update([
                'status' => 'Revisi'
            ], [
                'id_do_sales_order' => $delivery_order_revisi['id_do_sales_order']
            ]);
        }
    }
}
