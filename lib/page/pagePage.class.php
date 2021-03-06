<?php
namespace kt\page;

use kt\system\page\AbstractPage;
use kt\data\page\PageList;
use kt\system\exception\PageNotFoundException;
use kt\system\exception\AccessDeniedException;
use kt\system\User;
use kt\system\UserUtils;
use kt\system\KuschelTickets;

class pagePage extends AbstractPage {

    private $identifier;
    private $page;

    public function readParameters(Array $parameters) {
        global $templateengine;

        $identifier = null;
        foreach(new PageList() as $page) {
            if(isset($parameters[$page->url])) {
                $identifier = $page->identifier;
                $this->page = $page;
                $groups = $page->groups;
            }
        }

        if($identifier == null) {
            throw new PageNotFoundException("Diese Seite wurde nicht gefunden.");
        } else {
            if($groups !== [] && KuschelTickets::getUser()->userID) {
                if(!in_array((String) KuschelTickets::getUser()->getGroup()->groupID, $groups)) {
                    throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
                }
            } else if($groups !== []) {
                throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
            }
            $this->identifier = $identifier;
        }
    }

    public function assign() {
        KuschelTickets::getTPL()->assign(array(
            "content" => $this->page->getContent(),
            "title" => $this->page->title,
            "type" => $this->page->type
        ));
    }


}
?>
