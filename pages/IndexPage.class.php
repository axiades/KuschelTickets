<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\Utils;
use KuschelTickets\lib\Link;
use KuschelTickets\lib\system\TicketCategory;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\system\Ticket;

class IndexPage extends Page {

    private $tickets = [];

    public function readParameters(Array $parameters) {
        global $config;

        if(!UserUtils::isLoggedIn()) {
            Utils::redirect(Link::get("login"));
            die();
        }

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_tickets WHERE creator = ? ORDER BY ticketID DESC LIMIT 10");
        $stmt->execute([UserUtils::getUserID()]);
        while($row = $stmt->fetch()) {
            array_push($this->tickets, new Ticket($row['ticketID']));
        }
    }

    public function assign() {
        return array(
            "tickets" => $this->tickets
        );
    }


}
?>