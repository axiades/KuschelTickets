<?php
namespace kt\page;

use kt\system\Page;
use kt\system\KuschelTickets;
use kt\data\ticket\TicketList;
use kt\system\exception\AccessDeniedException;

class ticketsPage extends Page {

    private $tickets = [];

    public function readParameters(Array $parameters) {
        if(KuschelTickets::getUser() == null) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }

        if(!KuschelTickets::getUser()->hasPermission("mod.view.tickets.list")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        $this->tickets = new TicketList();
    }

    public function assign() {
        return array(
            "tickets" => $this->tickets
        );
    }


}
?>