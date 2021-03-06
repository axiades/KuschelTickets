<?php
namespace kt\system;
use kt\system\Link;
use kt\system\UserUtils;
use kt\lib\system\CRSF;
use kt\lib\system\GithubOAuth;

class Oauth {

    public static function getProvider(int $identifier) {
        $providers = ["System", "Google", "GitHub"];
        return $providers[$identifier];
    }

    public static function getGoogle() {
        global $config;

        require(Oauth::getGooglePath());
        $client = new \Google_Client();
        $client->setClientId($config['oauth']['google']['clientid']);
        $client->setClientSecret($config['oauth']['google']['clientsecret']);
        $client->setRedirectUri(Link::get("oauth-1"));
        $client->addScope("email");
        $client->addScope("profile");
        return $client;
    }

    public static function getGooglePath() {
        return "lib/3rdParty/GoogleOauth/vendor/autoload.php";
    }

    public static function getGoogleURL() {
        global $config;

        if($config['oauth']['google']['use']) {
            return Oauth::getGoogle()->createAuthUrl();
        } else {
            return "";
        }
    }

    public static function getGitHub() {
        global $config;

        $client = new GitHubOauth(array(
            "client_id" => $config['oauth']['github']['clientid'],
            "client_secret" => $config['oauth']['github']['clientsecret'],
            "redirect_url" => Link::get("oauth-2")
        ));
        return $client;
    }

    public static function getGitHubURL() {
        global $config;

        if($config['oauth']['github']['use']) {
            return Oauth::getGitHub()->getAuthorizeURL(CRSF::get());
        } else {
            return "";
        }
    }

    public static function getUserName(String $username) {
        $username = str_replace(" ", "", $username);
        $username = preg_replace("/\r|\n/", "", $username);
        if(!UserUtils::exists($username, "username")) {
            return $username;
        } else {
            return Oauth::getUserName($username."1");
        }
    }
}