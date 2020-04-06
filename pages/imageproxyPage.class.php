<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\Utils;

class imageproxyPage extends Page {

    private $tickets = [];

    public function readParameters(Array $parameters) {
        global $config;
        if(isset($parameters['url']) && !empty($parameters['url'])) {
            $url = Utils::fromASCI($parameters['url']);
            if(preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) {
                $imginfo = getimagesize($url);
                header("Content-type:".$imginfo['mime']);
                readfile($url);
            }
        }

        die();
    }

    public function assign() {
       return array();
    }


}
?>