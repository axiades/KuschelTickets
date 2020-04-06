<?php
use KuschelTickets\lib\system\CRSF;

$errors = array(
    "token" => false,
    "sure" => false
);
$success = false;

if(isset($parameters['submit'])) {
    if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
        if(CRSF::validate($parameters['CRSF'])) {
            if(isset($parameters['sure'])) {
                if(isset($parameters['notifications'])) {
                    $stmt = $config['db']->prepare("DELETE FROM kuscheltickets".KT_N."_notifications WHERE done = 1");
                    $stmt->execute();
                    $success = "Die gewählten Einträge wurden erfolgreich gelöscht.";
                }
                if(isset($parameters['tickets'])) {
                    $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_tickets WHERE NOT state = 1");
                    $stmt->execute();
                    while($row = $stmt->fetch()) {
                        $statement = $config['db']->prepare("DELETE FROM kuscheltickets".KT_N."_ticket_answers WHERE ticketID = ?");
                        $statement->execute([$row['ticketID']]);
                    }
                    $stmt = $config['db']->prepare("DELETE FROM kuscheltickets".KT_N."_tickets WHERE NOT state = 1");
                    $stmt->execute();
                    $success = "Die gewählten Einträge wurden erfolgreich gelöscht.";
                }
                if(isset($parameters['banned'])) {
                    $stmt = $config['db']->prepare("DELETE FROM kuscheltickets".KT_N."_accounts WHERE banned = 1");
                    $stmt->execute();
                    $success = "Die gewählten Einträge wurden erfolgreich gelöscht.";
                }
                if(isset($parameters['errorlogs'])) {
                    $errorfiles = glob("./data/logs/*.txt");
                    foreach($errorfiles as $file) {
                        unlink($file);
                    }
                    $success = "Die gewählten Einträge wurden erfolgreich gelöscht.";
                }
                if(isset($parameters['templatescompiled'])) {
                    $errorfiles = glob("./data/templates_compiled/*.php");
                    foreach($errorfiles as $file) {
                        unlink($file);
                    }
                    $success = "Die gewählten Einträge wurden erfolgreich gelöscht.";
                }
            } else {
                $errors['sure'] = "Bitte bestätige die Löschung der Daten.";
            }
        } else {
            $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
        }
    } else {
        $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
    }
}



$stmt = $config['db']->query('SHOW TABLE STATUS');
$dbsize = $stmt->fetch(PDO::FETCH_ASSOC)["Data_length"];
$dbsize = round($dbsize/(1024 * 1024), 2);

$stmt = $config['db']->prepare("SELECT COUNT(*) AS readnotifications FROM kuscheltickets".KT_N."_notifications WHERE done = 1");
$stmt->execute();
$row = $stmt->fetch();
$readnotifications = $row['readnotifications'];

$stmt = $config['db']->prepare("SELECT COUNT(*) AS closetickets FROM kuscheltickets".KT_N."_tickets WHERE NOT state = 1");
$stmt->execute();
$row = $stmt->fetch();
$closetickets = $row['closetickets'];

$stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_tickets WHERE NOT state = 1");
$stmt->execute();
$closeanswers = 0;
while($row = $stmt->fetch()) {
    $stmt = $config['db']->prepare("SELECT COUNT(*) AS closeanswers FROM kuscheltickets".KT_N."_ticket_answers WHERE ticketID = ?");
    $stmt->execute([$row['ticketID']]);
    $r = $stmt->fetch();
    $closeanswers = $closetickets + $r['closeanswers'];
}

$stmt = $config['db']->prepare("SELECT COUNT(*) AS bannedusers FROM kuscheltickets".KT_N."_accounts WHERE banned = 1");
$stmt->execute();
$row = $stmt->fetch();
$bannedusers = $row['bannedusers'];

$errorlogs = glob("./data/logs/*.txt");
$errorlogs = count($errorlogs);

$templatescompiled = glob("./data/templates_compiled/*.php");
$templatescompiled = count($templatescompiled);

$site = array(
    "dbsize" => $dbsize,
    "errors" => $errors,
    "success" => $success,
    "readnotifications" => $readnotifications,
    "closetickets" => $closetickets,
    "closeanswers" => $closeanswers,
    "bannedusers" => $bannedusers,
    "errorlogs" => $errorlogs,
    "templatescompiled" => $templatescompiled
);
