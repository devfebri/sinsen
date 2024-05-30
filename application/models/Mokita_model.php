<?php



class Mokita_model extends CI_Model
{

  function cek_sales_order($filter)
  {
    if (count($filter) > 0) {
      $this->db->select("prospek.leads_id,spk.no_hp,so.tgl_pengiriman,so.no_mesin,spk.no_mesin_spk,prospek.input_from,spk.no_spk");
      $this->db->join("tr_spk spk", "spk.no_spk=so.no_spk");
      $this->db->join("tr_prospek prospek", "prospek.id_customer=spk.id_customer");

      if (isset($filter['sinsengo'])) {
        $this->db->where("prospek.input_from", "sinsengo");
      }

      if (isset($filter['no_mesin'])) {
        $this->db->where("so.no_mesin", $filter['no_mesin']);
      }

      if (isset($filter['no_spk'])) {
        $this->db->where("spk.no_spk", $filter['no_spk']);
      }
      $this->db->limit(1);
      $get_leads = $this->db->get("tr_sales_order so")->row();

      if ($get_leads != null) {
        return $get_leads;
      }
    }
  }

  function last_tracking($no_spk)
  {
    $this->db->where("SpkNumber", $no_spk);
    $this->db->order_by('id', 'DESC');
    $this->db->limit("1");
    $last_status_ce_apps = $this->db->get('mokita_batch_3_h1_notifikasi_tracking_offline')->row();
    if ($last_status_ce_apps != null) {
      return $last_status_ce_apps;
    }
  }

  function set_tracking($no_spk, $arrayPost)
  {
    $arrayPost['last_updated']    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $arrayPost['SpkNumber']       = $no_spk;
    unset($arrayPost['CustomerPhoneNumber']);
    unset($arrayPost['EstimatedDeliveryDate']);
    unset($arrayPost['EngineNumber']);
    $this->db->insert("mokita_batch_3_h1_notifikasi_tracking_offline", $arrayPost);
  }
}
