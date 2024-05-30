<?php

class Honda_Controller extends CI_Controller
{
    protected $page;
    protected $title;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('unit_test');
        $this->unit->active(!ENVIRONMENT === 'development');
        $this->output->enable_profiler(!ENVIRONMENT === 'development');
    }

    public function groupArray($arr, $extension = null)
    {
        $index = 0;
        $final = [];
        $keyList = array_keys($arr);
        $item = count($arr[$keyList[0]]);
        for ($i = 0; $i < $item; $i++) {
            foreach ($keyList as $key) {
                $final[$i][$key] = $arr[$key][$i];

                if ($extension !== null) {
                    foreach ($extension as $extensionKey => $extensionValue) {
                        $final[$i][$extensionKey] = $extensionValue;
                    }
                }
            }
        }
        return $final;
    }

    public function getOnly($keys = [], $stacks = [], $inject = []){
        if($stacks == []){
            return [];
        }
		$final = [];
		foreach ($stacks as $each) {
            $subArr = [];
            if($keys === true OR ($keys == [])){
                $subArr = $each;
            }else{
                // foreach ($each as $key => $value) {
                //     if(in_array($key, $keys)){
                //         $subArr[$key] = $value;
                //     }
                // }

                foreach ($keys as $key) {
                    if(isset($each[$key]) and $each[$key] != ''){
                        $subArr[$key] = $each[$key];
                    }else{
                        $subArr[$key] = null;
                    }
                }
            }
			
            $subArr = array_merge($subArr, $inject);
			$final[] = $subArr;
        }
		return $final;
    }

    public function get_in_array($keys = [], $data, $additional = []){
        if(count($keys) > 0){
            $final_data = [];
            foreach ($keys as $key) {
                if(isset($data[$key]) and $data[$key] != ''){
                    $final_data[$key] = $data[$key];
                }else{
                    $final_data[$key] = null;
                }
            }
            return array_merge($final_data, $additional);
        }
        return array_merge($data, $additional);
    }

    protected function template($data)
    {
        $name = $this->session->userdata('nama');
        $data['isi']    = $this->page;
        $data['title']    = $this->title;

        $menu = $this->db
        ->select('m.menu_name')
        ->select('mi.menu_induk')
        ->select('mb.menu_bagian')
        ->from('ms_menu as m')
        ->join('ms_menu_induk as mi', 'm.id_menu_induk = mi.id_menu_induk')
        ->join('ms_menu_bagian as mb', 'm.id_menu_bagian = mb.id_menu_bagian')
        ->limit(1)
        ->where('m.menu_link', $this->page)
        ->get()->row();

        $this->breadcrumbs->push('Dashboard', "/panel/home");
        $this->breadcrumbs->push($menu->menu_bagian, "/{$menu->menu_bagian}");
        $this->breadcrumbs->push($menu->menu_induk, "/{$menu->menu_induk}");
        $this->breadcrumbs->push($menu->menu_name, "/{$menu->menu_name}");

        $data['breadcrumb'] = $this->breadcrumbs->show(); 

        if ($name == "") {
            echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
        } else {
            $this->load->view('template/header', $data);
            $this->load->view('template/aside');
            $this->load->view($this->folder . "/" . $this->page);
            $this->load->view('template/footer');
        }
    }

    public function clean_data($data){
        $result = [];
		foreach ($data as $key => $value) {
			if($data[$key] != '' && $data[$key] != null){
				$result[$key] = $value;
			}
        }
        return $result;
    }

    public function __destruct()
    {
        // if (ENVIRONMENT === 'development') {
        //     echo $this->unit->report();
        // }
    }
}
