<?php defined('BASEPATH') or exit('No direct script access allowed');

class Lang_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function localized()
    {
        $list = $this->db->get("language")->result_array();
        $lang = array();
        foreach ($list as $item) {
            if (array_key_exists($this->get_lang(), $item)) {
                $lang[$item['name']] = $item[$this->get_lang()];
            } else {
                $lang[$item['name']] = $item['tw'];
            }
        }
        return $lang;
    }

    public function get_lang()
    {
        global $RTR;
        if ($RTR->language) {
            return $RTR->language;
        }

        if ($this->session->user_lang && $this->session->user_lang) {
            return $this->session->user_lang;
        }

        $al = self::accept_language();
        foreach ($al as $lang => $q) {
            if (in_array($lang, $this->config->config['langs'])) {
                return $lang;
            }
        }

        return "tw";
    }

    public function set_lang($lang)
    {
        $exist_lang = $this->db->get_where("lang", array("code" => $lang))->row();
        if ($exist_lang != null) {
            $this->session->set_userdata(array(
                "user_lang"    =>    $lang
            ));
            return $lang;
        }
        return null;
    }

    private static function accept_language()
    {
        $prefLocales = array_reduce(
            explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']),
            function ($res, $el) {
                list($l, $q) = array_merge(explode(';q=', $el), [1]);
                $res[$l] = (float) $q;
                return $res;
            },
            []
        );
        arsort($prefLocales);
        return $prefLocales;
    }
}
