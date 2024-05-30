<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_s3nd3m41l extends CI_Model
{

   public function __construct()
   {
      parent::__construct();
      $this->load->database();
      $this->load->model('m_admin');
   }

   function getFinco($filter = null)
   {
      $where = "WHERE ms_finance_company.active=1 ";
      if ($filter != null) {
         if (isset($filter['is_order_rep_sales_finco'])) {
            $filt = $filter['is_order_rep_sales_finco'];
            if ($filt == 1) {
               $where .= "AND order_rep_sales_finco IS NOT NULL";
            } elseif ($filt == 0) {
               $where .= "AND order_rep_sales_finco IS NULL";
            }
         }
      }
      $getFinco = $this->db->query("SELECT id_finance_company,finance_company FROM     
                  ms_finance_company
                  $where
                  ORDER BY order_rep_sales_finco ASC
               ");
      return $getFinco;
   }
   function getDealer($filter = null)
   {
      $where = "WHERE active=1 ";
      if ($filter != null) {
         if (isset($filter['h1'])) {
            $where .= " AND h1=1";
         }
      }
      return $this->db->query("SELECT id_dealer,kode_dealer_md,nama_dealer FROM ms_dealer $where ORDER BY nama_dealer ASC 
      --  LIMIT 10
       ");
   }
   function getSalesDealerByFinco($params)
   {
      $filt_dealer = ['h1' => 1];
      $periode = 'tanggal';
      $tanggal = $params['tanggal'];
      $dealers = $this->getDealer($filt_dealer);
      $filter_f = ['is_order_rep_sales_finco' => 1];
      $fincos  = $this->getFinco($filter_f);

      $filter_ = ['is_order_rep_sales_finco' => 0];
      $fincos_oth  = $this->getFinco($filter_);

      foreach ($fincos->result() as $fc) {
         $tot_finc[$fc->id_finance_company] = 0;
      }
      $tot_finc['other'] = 0;
      $tot_finc['cash']  = 0;
      $tot_finc['total'] = 0;
      foreach ($dealers->result() as $k_d => $dl) {
         $subArrUnit[] = $dl->nama_dealer;
         $tot_sales = 0;

         //Kredit With Finco
         foreach ($fincos->result() as $fnc) {
            $sales = $this->m_admin->get_penjualan_inv('tanggal', $tanggal, null, $dl->id_dealer, null, null, $fnc->id_finance_company);
            $subArrUnit[] = $sales;
            $tot_sales += $sales;
            $tot_finc[$fnc->id_finance_company] += $sales;
         }

         //Kredit With Other Finco
         $sales = 0;
         foreach ($fincos_oth->result() as $fc) {
            $sales += $this->m_admin->get_penjualan_inv('tanggal', $tanggal, null, $dl->id_dealer, null, null, $fc->id_finance_company);
         }
         $subArrUnit[] = $sales;
         $tot_sales += $sales;
         $tot_finc['other'] += $sales;

         //Cash
         $sales = $this->m_admin->get_penjualan_inv('tanggal', $tanggal, null, $dl->id_dealer, null, null, null, null, 'cash');
         $subArrUnit[] = $sales;

         $tot_sales += $sales;
         $tot_finc['cash'] += $sales;

         //Total
         $subArrUnit[] = $tot_sales;
         $tot_finc['total'] += $tot_sales;

         //Persen
         //Kredit With Finco
         $tot_persen = 0;
         foreach ($subArrUnit as $key => $su) {
            if ($key > 0) {
               $persen = @($su / $tot_sales);
               $tot_persen += $persen;

               $persen = number_format($persen * 100, 2);
               $subArrPersen[] = $persen;
            }
         }

         //Set Result Per Dealer
         $result[] = ['unit' => $subArrUnit, 'persen' => $subArrPersen];

         //Reset Sub
         $subArrUnit = array();
         $subArrPersen = array();
      }
      $tot_persen = array();
      foreach ($tot_finc as $key => $fc) {
         $persen = @($fc / $tot_finc['total']);
         $persen = number_format($persen * 100, 2);
         $tot_persen[] = $persen;
      }
      return ['result' => $result, 'finco' => $fincos->result(), 'total' => $tot_finc, 'tot_persen' => $tot_persen];
   }
}
