    <?php

class M_dashboard_stok extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    function json() {
        $this->datatables->select('ID,city.Name as namakota,city.Population as populasi,country.Name as namanegara');
        $this->datatables->from('city');
        $this->datatables->join('country', 'city.CountryCode = country.Code');
        return $this->datatables->generate();
    }
}

?>