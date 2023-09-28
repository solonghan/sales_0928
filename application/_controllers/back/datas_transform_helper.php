<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * date string Helpers
 * 
 */

// ------------------------------------------------------------------------

if ( ! function_exists('date_tostring'))
{
    function date_tostring($date)
    {
    	$date = strtotime($date);
        if ((time()-$date)<60*10) {
            // 十分鐘內
            return '剛剛';
        } elseif (((time()-$date)<60*60)&&((time()-$date)>=60*10)) {
            // 十分鐘~1小時
            $s = floor((time()-$date)/60);
            return  $s."分鐘前";
        } elseif (((time()-$date)<60*60*24)&&((time()-$date)>=60*60)) {
            // 1小時～24小時
            $s = floor((time()-$date)/60/60);
            return  $s."小時前";
        } elseif (((time()-$date)<60*60*24*3)&&((time()-$date)>=60*60*24)) {
            // 1天~3天
            $s = floor((time()-$date)/60/60/24);
            return $s."天前";
        } else {
            // 超过3天
            if (date('Y', strtotime($date)) == date('Y')) {
            	// 今年
            	return date("m/d H:i", $date);
            } else {
            	return date("Y/m/d", $date);
            }
        }
    }
}

if ( ! function_exists('privilege_to_str'))
{
    function privilege_to_str($priv)
    {
        switch ($priv) {
            case 'super':
                return '最高權限管理員';
            case 'mgr':
                return '管理員';
        }
    }
}
// ------------------------------------------------------------------------


/* For Sales project - Start */

if ( ! function_exists('login_type_trf_db_name'))
{
    function login_type_trf_db_name($login_type)
    {
        switch ($login_type)
        {
            case 'APPLE':
                return 'apple_id';
            case 'GOOGLE':
                return 'g_id';
            case 'FB':
                return 'fb_id';
            case 'MOBILE':
                return 'mobile_id';
            
            default:
                return FALSE;
        }
    }
}

if ( ! function_exists('is_login_type_exists'))
{
    function is_login_type_exists($login_type)
    {
        switch ($login_type)
        {
            case 'APPLE':
                return TRUE;
            case 'GOOGLE':
                return TRUE;
            case 'FB':
                return TRUE;
            case 'MOBILE':
                return TRUE;

            default:
                return FALSE;
        }
    }
}

if ( ! function_exists('is_os_type_exists'))
{
    function is_os_type_exists($os)
    {
        switch ($os)
        {
            case 'ios':
                return TRUE;
            case 'android':
                return TRUE;
            
            default:
                return FALSE;
        }
    }
}

if ( ! function_exists('login_type_can_search_db'))
{
    function login_type_can_search_db($login_type)
    {
        switch ($login_type)
        {
            case 'mobile_id':
                return TRUE;
            case 'apple_id':
                return TRUE;
            case 'g_id':
                return TRUE;
            case 'fb_id':
                return TRUE;
            
            default:
                return FALSE;
        }
    }
}

if ( ! function_exists('customer_status_tf'))
{
    function customer_status_tf($status)
    {
        switch ($status)
        {
            case 'inform':
                return 1;
            case 'reservation':
                return 2;
            case 'visit':
                return 3;
            case 'propose':
                return 4;
            case 'deal':
                return 5;
            
            default:
                return FALSE;
        }
    }
}

if ( ! function_exists('customer_status_tf_str'))
{
    function customer_status_tf_str($status)
    {
        switch ($status)
        {
            case 1:
                return 'inform';
            case 2:
                return 'reservation';
            case 3:
                return 'visit';
            case 4:
                return 'propose';
            case 5:
                return 'deal';
            
            default:
                return FALSE;
        }
    }
}

if ( ! function_exists('notification_class_tf'))
{
    function notification_class_tf($class)
    {
        switch ($class)
        {
            case 'system':
                return '[ 系統公告 ]';
            
            default:
                return $class;
        }
    }
}

if ( ! function_exists('is_upgra_store_exists'))
{
    function is_upgra_store_exists($store)
    {
        switch ($store)
        {
            case 'full_feature':
                return 'Full_upgrad_store';
            
            default:
                return FALSE;
        }
    }
}

if ( ! function_exists('is_upgra_plan_exists'))
{
    function is_upgra_plan_exists($plan)
    {
        switch ($plan)
        {
            case 'full_1':
                return TRUE;
            case 'full_3':
                return TRUE;
            case 'full_12':
                return TRUE;
            
            default:
                return FALSE;
        }
    }
}

if ( ! function_exists('is_relation_exists'))
{
    function is_relation_exists($relation)
    {
        switch ($relation)
        {
            case 'A':
                return TRUE;
            case 'B':
                return TRUE;
            case 'C':
                return TRUE;
            case 'D':
                return TRUE;
            case 'S':
                return TRUE;
            
            default:
                return FALSE;
        }
    }
}

if ( ! function_exists('status_en_tf_ch'))
{
    function status_en_tf_ch($status)
    {
        switch ($status)
        {
            case 'inform':
                return '告知';
            case 'reservation':
                return '約訪';
            case 'visit':
                return '拜訪';
            case 'propose':
                return '建議';
            case 'deal':
                return '成交';
            
            default:
                return FALSE;
        }
    }
}

if ( ! function_exists('status_ch_tf_en'))
{
    function status_ch_tf_en($status)
    {
        switch ($status)
        {
            case '未開發':
                return 'undeveloped';
            case '告知':
                return 'inform';
            case '約訪':
                return 'reservation';
            case '拜訪':
                return 'visit';
            case '建議':
                return 'propose';
            case '成交':
                return 'deal';
            
            default:
                return FALSE;
        }
    }
}
/* For Sales project - Start */