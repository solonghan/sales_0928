<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Flow_record_model extends Base_Model {


    public function set_flow_record($enter, $client_ip, $user_id = FALSE)
    {
        if($this->db->where("create_date like '".date("Y-m-d")."%' AND ip = '".$client_ip."' AND enter='{$enter}'")

                    ->count_all_results($this->flow_record_table) <= 0)
        {
            if ($user_id == FALSE)
                $user_id = 0;

            $this->db->insert($this->flow_record_table, array('user_id' => $user_id, 'ip' => $client_ip, 'enter' => $enter));
        }
    }


    public function &get_statistic($pre30day)
    {
        $get_statistic = $this->db->select("count(id) as value, SUBSTRING_INDEX(`create_date`, ' ', 1) as date")
                                  ->from($this->flow_record_table)
                                  ->where(array("create_date>="=>$pre30day))
                                  ->group_by("SUBSTRING_INDEX(`create_date`, ' ', 1)")
                                  ->get()
                                  ->result_array();
        return $get_statistic;
    }


    public function &get_statistic_independent($pre30day)
    {
        $get_statistic_independent = $this->db->select("count(id) as value, SUBSTRING_INDEX(`create_date`, ' ', 1) as date")
                                              ->from($this->flow_record_table)
                                              ->where(array("create_date>="=>$pre30day, "enter"=>"home"))
                                              ->group_by("SUBSTRING_INDEX(`create_date`, ' ', 1)")
                                              ->get()
                                              ->result_array();
        return $get_statistic_independent;
    }

    // public function set_fcm_record($users, $notification, $result, $data)
    // {
        
    // }
}