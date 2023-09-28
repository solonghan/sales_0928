<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Base_Router extends CI_Router
{
    public $language;

    protected function _parse_routes()
    {
        $lang = $this->uri->segments[1];
        // if ($this->uri->segments[2] && $this->uri->segments[2] == "member") {
        //     return parent::_parse_routes();            
        // }
        if (in_array($lang, $this->config->config['langs'])) {
            $this->language = $lang;
            unset($this->uri->segments[1]);
        }
        else if ($this->uri->segments[1] === 'tw') {
            $this->language = 'tw';
            unset($this->uri->segments[1]);
        }
        
        return parent::_parse_routes();
    }
}

