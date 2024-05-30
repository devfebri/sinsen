<?php

// Nama dari class yg disebutkan di file hooks.php
class Db_log
{
    function __construct()
    {
    }

    // Nama dari method yang disebutkan di file hooks.php
    function logQueries()
    {
        $now = Mcarbon::now();
        $CI = & get_instance();

        $CI->load->library('Mcarbon');

        $directory = APPPATH . sprintf('logs/query');
        if(!is_dir($directory)){
            mkdir($directory);
        }
        $filepath = sprintf('%s/%s.log', $directory, $now->toDateString());
        $handle = fopen($filepath, "a+"); // buka file dg mode Read/write
        $times = $CI->db->query_times;

        foreach ($CI->db->queries as $key => $query) { 
            if($query){
                // $sql =  "Tanggal/Waktu : ". date("d-M-Y/H:i:s A") . " => " . trim(preg_replace('/\s+/', ' ', $query)) . " Execution Time:" . $times[$key];
                $sql = sprintf('[%s] : %s', $now->format('Y-m-d H:i:s'), print_r([
                    'router_directory' => $CI->router->directory,
                    'router_class' => $CI->router->class,
                    'router_method' => $CI->router->method,
                    'query' => trim(preg_replace('/\s+/', ' ', $query)),
                    'execution_time' => $times[$key]
                ], true));

                if($times[$key] >= 10){
                    fwrite($handle, $sql . "\n");              // tulis di file log
                }
            }
        } 
        fclose($handle);      // Close the file
    }


}