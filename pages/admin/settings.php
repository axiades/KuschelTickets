<?php
use KuschelTickets\lib\Utils;
use KuschelTickets\lib\system\CRSF;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$validrecaptchauasecase = ['login', 'registration', 'passwordreset', 'addticket', 'ticketanswer', 'accountmanagement', 'notificationsettings', 'editortemplates'];

$errors = array(
    "pagetitle" => false,
    "cookie" => false,
    "adminmail" => false,
    "statecolorclosed" => false,
    "stateopencolor" => false,
    "statedonecolor" => false,
    "databasedatabase" => false,
    "databasehost" => false,
    "databaseport" => false,
    "databaseport" => false,
    "databasepassword" => false,
    "smtphost" => false,
    "smtpauth" => false,
    "smtpusername" => false,
    "smtppassword" => false,
    "smtpport" => false,
    "smtpfrom" => false,
    "recaptchause" => false,
    "recaptchaversion" => false,
    "recaptchapublic" => false,
    "recaptchaprivate" => false,
    "recaptchacases" => false,
    "token" => false,
    "favicon" => false
);

$success = false;
$colors = ['red','orange','yellow','olive','green','teal','blue','violet','purple','pink','brown','grey','black'];


if(isset($parameters['submit'])) {
    if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
        if(CRSF::validate($parameters['CRSF'])) {
            if(isset($parameters['pagetitle']) && !empty($parameters['pagetitle'])) {
                if(isset($parameters['cookie']) && !empty($parameters['cookie'])) {
                    if(isset($parameters['adminmail']) && !empty($parameters['adminmail'])) {
                        if(filter_var($parameters['adminmail'], FILTER_VALIDATE_EMAIL)) {
                            if(isset($parameters['statecolorclosed']) && !empty($parameters['statecolorclosed'])) {
                                if(in_array($parameters['statecolorclosed'], $colors)) {
                                    if(isset($parameters['stateopencolor']) && !empty($parameters['stateopencolor'])) {
                                        if(in_array($parameters['stateopencolor'], $colors)) {
                                            if(isset($parameters['statedonecolor']) && !empty($parameters['statedonecolor'])) {
                                                if(in_array($parameters['statedonecolor'], $colors)) {
                                                    if(isset($parameters['databasedatabase']) && !empty($parameters['databasedatabase'])) {
                                                        if(isset($parameters['databasehost']) && !empty($parameters['databasehost'])) {
                                                            if(isset($parameters['databaseport']) && !empty($parameters['databaseport'])) {
                                                                if(is_numeric($parameters['databaseport'])) {
                                                                    if(isset($parameters['databaseuser']) && !empty($parameters['databaseuser'])) {
                                                                        if(isset($parameters['databasepassword']) && !empty($parameters['databasepassword'])) {
                                                                            $databasepw = $parameters['databasepassword'];
                                                                        } else {
                                                                            $databasepw = "";
                                                                        }
                                                                        $pdoerror = "";
                                                                        try{
                                                                            $pdotry = new PDO("mysql:host=".$parameters['databasehost'].":".$parameters['databaseport'].";dbname=".$parameters['databasedatabase'], $parameters['databaseuser'], $parameters['databasepassword']);
                                                                            $pdotry->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                                        } catch (PDOException $e){
                                                                            $pdoerror = $e->getMessage();
                                                                        }
                                                                        if($pdoerror == "") {
                                                                            if(isset($parameters['smtphost']) && !empty($parameters['smtphost'])) {
                                                                                if(isset($parameters['smtpport']) && !empty($parameters['smtpport'])) {
                                                                                    if(is_numeric($parameters['smtpport'])) {
                                                                                        $smtpauth = false;
                                                                                        if(isset($parameters['smtpauth'])) {
                                                                                            if(isset($parameters['smtpusername']) && !empty($parameters['smtpusername'])) {
                                                                                                if(isset($parameters['smtppassword']) && !empty($parameters['smtppassword'])) {
                                                                                                    $smtpauth = true;
                                                                                                } else {
                                                                                                    $errors['smtppassword'] = "Bitte gib ein Passwort für die Anmeldung auf dem SMTP Server an.";
                                                                                                }
                                                                                            } else {   
                                                                                                $errors['smtpusername'] = "Bitte gib einen Benutzernamen für die Anmeldung auf dem SMTP Server an.";
                                                                                            }
                                                                                        }
                                                                                        if(isset($parameters['smtpfrom']) && !empty($parameters['smtpfrom'])) {
                                                                                            if(filter_var($parameters['smtpfrom'], FILTER_VALIDATE_EMAIL)) {
                                                                                                $mailexception = "";
                                                                                                if($parameters['smtphost'] !== $config['mail']['host'] || $parameters['smtpport'] !== $config['mail']['port'] || $parameters['smtpauth'] !== $config['mail']['auth'] || $parameters['smtpusername'] !== $config['mail']['username'] || $parameters['smtppassword'] !== $config['mail']['password'] || $parameters['smtpform'] !== $config['mail']['from']) {
                                                                                                    require("lib/PHPMailer/Exception.php");
                                                                                                    require("lib/PHPMailer/PHPMailer.php");
                                                                                                    require("lib/PHPMailer/SMTP.php");
                                                                                                    $mail = new PHPMailer();
                                                                                                    try {                    
                                                                                                        $mail->isSMTP(); 
                                                                                                        $mail->CharSet = "UTF-8";                                          
                                                                                                        $mail->Host = $parameters['smtphost'];                    
                                                                                                        $mail->SMTPAuth = $smtpauth;   
                                                                                                        if($smtpauth) {                           
                                                                                                            $mail->Username = $parameters['smtpusername'];                    
                                                                                                            $mail->Password = $parameters['smtppassword'];   
                                                                                                        }                            
                                                                                                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
                                                                                                        $mail->Port = $parameters['smtpport'];                                 
                                                                                                        $mail->setFrom($parameters['smtpfrom'], "KuschelTickets - SMTP Test");
                                                                                                        $mail->addAddress($parameters['adminmail'], "KuschelTickets Administrator");  
                                                                                                        $mail->isHTML(true);                               
                                                                                                        $mail->Subject = "SMTP Server Test";
                                                                                                        $mail->Body = "<div style='font-family: Arial, sans-serif'>Dies ist nur der Test deines SMTP Servers, du kannst diese E-Mail ignorieren.</div>";
                                                                                                        $mail->send();
                                                                                                    } catch (Exception $e) {
                                                                                                        $mailexception = $e;
                                                                                                    }
                                                                                                }
                                                                                                if($mailexception == "") {
                                                                                                    $userecaptcha = false;
                                                                                                    $validrecaptcha = false;
                                                                                                    if(isset($parameters['recaptchause'])) {
                                                                                                        if(isset($parameters['recaptchaversion']) && !empty($parameters['recaptchaversion'])) {
                                                                                                            if(in_array($parameters['recaptchaversion'], ["2", "3"])) {
                                                                                                                if(isset($parameters['recaptchapublic']) && !empty($parameters['recaptchapublic'])) {
                                                                                                                    if(isset($parameters['recaptchaprivate']) && !empty($parameters['recaptchaprivate'])) {
                                                                                                                        if(isset($parameters['recaptchacases']) && !empty($parameters['recaptchacases'])) {
                                                                                                                            $recaptchacases = explode(",", $parameters['recaptchacases']);
                                                                                                                            if(count($recaptchacases) > 0) {
                                                                                                                                $allcaseesgood = true;
                                                                                                                                foreach($recaptchacases as $usecase) {
                                                                                                                                    if(!in_array($usecase, $validrecaptchauasecase)) {
                                                                                                                                        $errors['recaptchacases'] = "Bitte wähle valide verwendungen für reCaptcha";
                                                                                                                                        $allcaseesgood = false;
                                                                                                                                    }
                                                                                                                                }
                                                                                                                                if($allcaseesgood) {
                                                                                                                                    $validrecaptcha = true;
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                $errors['recaptchacases'] = "Bitte wähle mindestens eine Verwendungsstelle für reCaptcha";
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            $errors['recaptchacases'] = "Bitte wähle mindestens eine Verwendungsstelle für reCaptcha";
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        $errors['recaptchaprivate'] = "Bitte gib einen geheimen reCaptcha Schlüssel an.";
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    $errors['recaptchapublic'] = "Bitte gib einen öffentlichen reCaptcha Schlüssel an.";
                                                                                                                }
                                                                                                            } else {
                                                                                                                $errors['recaptchaversion'] = "Bitte wähle eine valide reCaptcha Version";
                                                                                                            }
                                                                                                        } else {  
                                                                                                            $errors['recaptchaversion'] = "Bitte wähle die reCaptcha Version";
                                                                                                        }
                                                                                                    }
                                                                                                    $validfavicon = false;
                                                                                                    $faviconextension = "";
                                                                                                    if(!empty($_FILES['favicon']['name'])) {
                                                                                                        $check = getimagesize($_FILES['favicon']['tmp_name']);
                                                                                                        if($check !== false) {
                                                                                                            $extensions = ['png', 'jpg', 'jpeg', 'ico'];
                                                                                                            $extension = strtolower(pathinfo($_FILES['favicon']['name'], PATHINFO_EXTENSION));
                                                                                                            if(in_array($extension, $extensions)) {
                                                                                                                if(file_exists("./data/favicon.".$config['faviconextension'])) {
                                                                                                                    unlink("./data/favicon.".$config['faviconextension']);
                                                                                                                }
                                                                                                                move_uploaded_file($_FILES['favicon']['tmp_name'], "./data/favicon.".$extension);
                                                                                                                $faviconextension = $extension;
                                                                                                                $faviconmime = $check['mime'];
                                                                                                                $validfavicon = true;
                                                                                                            } else {
                                                                                                                $extensionserror = "";
                                                                                                                for($i = 0; $i < count($extensions); $i++) {
                                                                                                                    if($i == 0) {
                                                                                                                        $extensionserror = ".".$extensions[$i];
                                                                                                                    } else {
                                                                                                                        $extensionserror = $extensionserror.", .".$extensions[$i];
                                                                                                                    }
                                                                                                                }
                                                                                                                $errors['favicon'] = "Dein Favicon muss ein Bild sein. Es sind nur ".$extensionserror;
                                                                                                            }
                                                                                                        } else {
                                                                                                            $errors['favicon'] = "Dein Favicon muss ein Bild sein.";
                                                                                                        }
                                                                                                    } else {
                                                                                                        $validfavicon = true;
                                                                                                        $faviconextension = $config['faviconextension'];
                                                                                                        $faviconmime = $config['faviconmime'];
                                                                                                    }
                                                                                                    if($validrecaptcha && $validfavicon) {
                                                                                                        // SAVE THE CONFIG FILE
                                                                                                        $recaptchacases = explode(",", $parameters['recaptchacases']);
                                                                                                        $count = count($recaptchacases);
                                                                                                        $recaptchacases_ready = "";
                                                                                                        for($i = 0; $i < $count; $i++) {
                                                                                                            if(isset($recaptchacases[$i + 1])) {
                                                                                                                $recaptchacases_ready = $recaptchacases_ready."'".$recaptchacases[$i]."',";
                                                                                                            } else {
                                                                                                                $recaptchacases_ready = $recaptchacases_ready."'".$recaptchacases[$i]."'";
                                                                                                            }
                                                                                                        }
                                                                                                        if(isset($parameters['smtpauth'])) {
                                                                                                            $smtpauth = ($parameters['smtpauth'] == "on") ? "true" : "false";
                                                                                                        } else {
                                                                                                            $smtpauth = "false";
                                                                                                        }
                                                                                                        if(isset($parameters['recaptchause'])) {
                                                                                                            $recaptchause = ($parameters['recaptchause'] == "on") ? "true" : "false";
                                                                                                        } else {
                                                                                                            $recaptchause = "false";
                                                                                                        }
                                                                                                        
                                                                                                        if(isset($parameters['seourls'])) {
                                                                                                            $seourls = ($parameters['seourls'] == "on") ? "true" : "false";
                                                                                                        } else {
                                                                                                            $seourls = "false";
                                                                                                        }

                                                                                                        if(isset($parameters['externalURLFavicons'])) {
                                                                                                            $externalURLFavicons = ($parameters['externalURLFavicons'] == "on") ? "true" : "false";
                                                                                                        } else {
                                                                                                            $externalURLFavicons = "false";
                                                                                                        }

                                                                                                        if(isset($parameters['emailnotifications'])) {
                                                                                                            $emailnotifications = ($parameters['emailnotifications'] == "on") ? "true" : "false";
                                                                                                        } else {
                                                                                                            $emailnotifications = "false";
                                                                                                        }

                                                                                                        if(isset($parameters['useDesktopNotification'])) {
                                                                                                            $useDesktopNotification = ($parameters['useDesktopNotification'] == "on") ? "true" : "false";
                                                                                                        } else {
                                                                                                            $useDesktopNotification = "false";
                                                                                                        }

                                                                                                        if(isset($parameters['externalURLTitle'])) {
                                                                                                            $externalURLTitle = ($parameters['externalURLTitle'] == "on") ? "true" : "false";
                                                                                                        } else {
                                                                                                            $externalURLTitle = "false";
                                                                                                        }

                                                                                                        if(isset($parameters['proxyAllImages'])) {
                                                                                                            $proxyAllImages = ($parameters['proxyAllImages'] == "on") ? "true" : "false";
                                                                                                        } else {
                                                                                                            $proxyAllImages = "false";
                                                                                                        }

                                                                                                        if(isset($parameters['externalURLWarning'])) {
                                                                                                            $externalURLWarning = ($parameters['externalURLWarning'] == "on") ? "true" : "false";
                                                                                                        } else {
                                                                                                            $externalURLWarning = "false";
                                                                                                        }

                                                                                                        $htcode = PHP_EOL.
                                                                                                        '# KuschelTickets SEO Rewrite'.PHP_EOL.
                                                                                                        '<IfModule mod_rewrite.c>'.PHP_EOL.
                                                                                                        'RewriteEngine on'.PHP_EOL.
                                                                                                        'RewriteCond %{SCRIPT_FILENAME} !-d'.PHP_EOL.
                                                                                                        'RewriteCond %{SCRIPT_FILENAME} !-f'.PHP_EOL.
                                                                                                        'RewriteRule ^(.*)$ index.php?$1 [L,QSA]'.PHP_EOL.
                                                                                                        '</IfModule>'.PHP_EOL.
                                                                                                        ''.PHP_EOL;
                                                                                                        if($seourls == "true") {
                                                                                                            $htaccess = fopen(".htaccess", "w");
                                                                                                            $content = file_get_contents(".htaccess", true);
                                                                                                            if(strpos($content, $htcode) === false) {
                                                                                                                fwrite($htaccess, $content.$htcode);
                                                                                                            }
                                                                                                            fclose($htaccess);
                                                                                                        } else {
                                                                                                            $htaccess = fopen(".htaccess", "w");
                                                                                                            $content = file_get_contents(".htaccess", true);
                                                                                                            if(strpos($content, $htcode) !== false) {
                                                                                                                $content = str_replace($htcode, "", $content);
                                                                                                                fwrite($htaccess, $content);
                                                                                                            }
                                                                                                            fclose($htaccess);
                                                                                                        }
                                                                                                        $file = fopen("config.php", "w");
                                                                                                        fwrite($file, '<?php' . PHP_EOL . '  /*' . PHP_EOL . '      Automatisch erstellte Config-Datei    ' . PHP_EOL . '      Erstellt am ' . date('d.m.Y  H:i:s') . '    ' . PHP_EOL . '  */'.PHP_EOL.
                                                                                                        '$database = array('.PHP_EOL.
                                                                                                        '    "database" => "'.$parameters['databasedatabase'].'",'.PHP_EOL.
                                                                                                        '    "host" => "'.$parameters['databasehost'].'",'.PHP_EOL.
                                                                                                        '    "port" => '.$parameters['databaseport'].','.PHP_EOL.
                                                                                                        '    "user" => "'.$parameters['databaseuser'].'",'.PHP_EOL.
                                                                                                        '    "password" => "'.$parameters['databasepassword'].'"'.PHP_EOL.
                                                                                                        ');'.PHP_EOL.
                                                                                                        ''.PHP_EOL.
                                                                                                        '$config = array('.PHP_EOL.
                                                                                                        '    "version" => "'.$config['version'].'",'.PHP_EOL.
                                                                                                        '    "pagetitle" => "'.$parameters['pagetitle'].'",'.PHP_EOL.
                                                                                                        '    "databaseaccess" => $database,'.PHP_EOL.
                                                                                                        '    "db" => new PDO("mysql:host=".$database["host"].":".$database["port"].";dbname=".$database["database"], $database["user"], $database["password"]),'.PHP_EOL.
                                                                                                        '    "debugging" => array('.PHP_EOL.
                                                                                                        '        "templates" => false,'.PHP_EOL.
                                                                                                        '        "php" => false, // stellst du dies auf true, kann es zu Anzeigefehlern kommen'.PHP_EOL.
                                                                                                        '        "database" => false'.PHP_EOL.
                                                                                                        '    ),'.PHP_EOL.
                                                                                                        '    "cookie" => "'.$parameters['cookie'].'",'.PHP_EOL.
                                                                                                        '    "seourls" => '.$seourls.','.PHP_EOL.
                                                                                                        '    "faviconextension" => "'.$faviconextension.'",'.PHP_EOL.
                                                                                                        '    "externalURLTitle" => '.$externalURLTitle.','.PHP_EOL.
                                                                                                        '    "faviconmime" => "'.$faviconmime.'",'.PHP_EOL.
                                                                                                        '    "proxyAllImages" => '.$proxyAllImages.','.PHP_EOL.
                                                                                                        '    "externalURLFavicons" => '.$externalURLFavicons.','.PHP_EOL.
                                                                                                        '    "externalURLWarning" => '.$externalURLWarning.','.PHP_EOL.
                                                                                                        '    "useDesktopNotification" => '.$useDesktopNotification.','.PHP_EOL.
                                                                                                        '    "emailnotifications" => '.$emailnotifications.','.PHP_EOL.
                                                                                                        '    "adminmail" => "'.$parameters['adminmail'].'",'.PHP_EOL.
                                                                                                        '    "mail" => array('.PHP_EOL.
                                                                                                        '        "host" => "'.$parameters['smtphost'].'",'.PHP_EOL.
                                                                                                        '        "auth" => '.$smtpauth.','.PHP_EOL.
                                                                                                        '        "port" => '.$parameters['smtpport'].','.PHP_EOL.
                                                                                                        '        "username" => "'.$parameters['smtpusername'].'",'.PHP_EOL.
                                                                                                        '        "password" => "'.$parameters['smtppassword'].'",'.PHP_EOL.
                                                                                                        '        "from" => "'.$parameters['smtpfrom'].'"'.PHP_EOL.
                                                                                                        '    ),'.PHP_EOL.
                                                                                                        '    "state_colors" => array('.PHP_EOL.
                                                                                                        '        "closed" => "'.$parameters['statecolorclosed'].'",'.PHP_EOL.
                                                                                                        '        "open" => "'.$parameters['stateopencolor'].'",'.PHP_EOL.
                                                                                                        '        "done" => "'.$parameters['statedonecolor'].'"'.PHP_EOL.
                                                                                                        '    ),'.PHP_EOL.
                                                                                                        '    "recaptcha" => array('.PHP_EOL.
                                                                                                        '        "public" => "'.$parameters['recaptchapublic'].'",'.PHP_EOL.
                                                                                                        '        "secret" => "'.$parameters['recaptchaprivate'].'",'.PHP_EOL.
                                                                                                        '        "use" => '.$recaptchause.','.PHP_EOL.
                                                                                                        '        "version" => '.$parameters['recaptchaversion'].','.PHP_EOL.
                                                                                                        '        "cases" => ['.$recaptchacases_ready.']'.PHP_EOL.
                                                                                                        '    )'.PHP_EOL.
                                                                                                        ');'.PHP_EOL.
                                                                                                        ''.PHP_EOL.
                                                                                                        'define("KT_N", "'.KT_N.'");'.PHP_EOL.
                                                                                                        '');
                                                                                                        fclose($file);
                                                                                                        $success = "Deine Einstellungen wurden erfolgreich gespeichert und eine neue Konifurationsdatei wurde erstellt.";
                                                                                                    }
                                                                                                } else {
                                                                                                    $errrors['smtphost'] = "Es ist ein Fehler bei der Verbindung zu deinem SMTP Server aufgetreten: ".$mailexception;
                                                                                                }
                                                                                            } else {
                                                                                                $errors['smtpform'] = "Bitte gib einen validen Absender für deinen SMTP Server an.";
                                                                                            }
                                                                                        } else {
                                                                                            $errors['smtpform'] = "Bitte gib einen Absender für deinen SMTP Server an.";
                                                                                        }
                                                                                    } else {
                                                                                        $errors['smtpport'] = "Der Port des SMTP Servers muss eine Zahl sein.";
                                                                                    }
                                                                                } else {
                                                                                    $errrors['smtpport'] = "Bitte gib einen Port für den SMTP Server an.";
                                                                                }
                                                                            } else {
                                                                                $errrors['smtphost'] = "Bitte gib einen Host für den SMTP Server an.";
                                                                            }
                                                                        } else {
                                                                            $errors['databasedatabase'] = "Es ist ein Fehler bei der Datenbankverbindung aufgetreten: ".$pdoerror;
                                                                        }
                                                                    } else {
                                                                        $errors['databaseuser'] = "Bitte gib einen Datenbankbenutzer an.";
                                                                    }
                                                                } else {
                                                                    $errors['databaseport'] = "Der Datenbankport muss eine Zahl sein.";
                                                                }
                                                            } else {
                                                                $errors['databaseport'] = "Bitte gib einen Datenbankport an.";
                                                            }
                                                        } else {
                                                            $errors['databasehost'] = "Bitte gib einen Datenbankhost an.";
                                                        }
                                                    } else {
                                                        $errors['databasedatabase'] = "Bitte gib einen Datenbanknamen an.";
                                                    }
                                                } else {
                                                    $errors['statedonecolor'] = "Bitte wähle eine valide Farbe sollte ein Ticket als erledigt markiert sein.";
                                                }
                                            } else {
                                                $errors['statedonecolor'] = "Bitte wähle eine Farbe sollte ein Ticket als erledigt markiert sein.";
                                            }
                                        } else {
                                            $errors['stateopencolor'] = "Bitte wähle eine valide Farbe sollte ein Ticket offen sein.";
                                        }
                                    } else {
                                        $errors['stateopencolor'] = "Bitte wähle eine Farbe sollte ein Ticket offen sein.";
                                    }
                                } else {
                                    $errors['statecolorclosed'] = "Bitte wähle eine valide Farbe sollte ein Ticket geschlossen sein.";
                                }
                            } else {
                                $errors['statecolorclosed'] = "Bitte wähle eine Farbe sollte ein Ticket geschlossen sein.";
                            }
                        } else {
                            $errors['adminmail'] = "Bitte gib eine valide Admin E-Mail Adresse an.";
                        }
                    } else {
                        $errors['adminmail'] = "Bitte gib eine Admin E-Mail Adresse an.";
                    }
                } else {
                    $errors['cookie'] = "Bitte gib einen Cookie Namen an.";
                }
            } else {
                $errors['pagetitle'] = "Bitte gib einen Seitentitel an.";
            }
        } else {
            $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
        }
    } else {
        $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
    }
}




$colors = [
    array(
        "name" => "rot",
        "value" => "red"
    ),
    array(
        "name" => "orange",
        "value" => "orange"
    ),
    array(
        "name" => "gelb",
        "value" => "yellow"
    ),
    array(
        "name" => "hellgrün",
        "value" => "olive"
    ),
    array(
        "name" => "grün",
        "value" => "green"
    ),
    array(
        "name" => "cyan",
        "value" => "teal"
    ),
    array(
        "name" => "blau",
        "value" => "blue"
    ),
    array(
        "name" => "violett",
        "value" => "violet"
    ),
    array(
        "name" => "lila",
        "value" => "purple"
    ),
    array(
        "name" => "pink",
        "value" => "pink"
    ),
    array(
        "name" => "braun",
        "value" => "brown"
    ),
    array(
        "name" => "grau",
        "value" => "grey"
    ),
    array(
        "name" => "schwarz",
        "value" => "black"
    )
];

// require again because we made changes and want to display them
require("config.php");
$site = array(
    "errors" => $errors,
    "colors" => $colors,
    "success" => $success,
    "config" => $config
);