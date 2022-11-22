<?php

namespace App\Providers;

class UsosProvider {

 
    public $usos_base_url;
    public $usos_consumer_key;
    public $usos_consumer_secret_key;
    public $callback;
    public $scopes = array();

    public $token_key;
    public $token_secret_key;

    public function setAuthorizationData($url, $consumer_key, $consumer_secret_key, $callback)
    {
        $this->usos_base_url = $url;
        $this->usos_consumer_key = $consumer_key;
        $this->usos_consumer_secret_key = $consumer_secret_key;
        $this->callback = $callback;
        
    }

    public function setScopes(array $scopes)
    {
        $this->scopes = $scopes;
    }
    public function setRequestToken()
    {
        $oauth = new \OAuth($this->usos_consumer_key, $this->usos_consumer_secret_key, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
        $oauth->disableSSLChecks();
        $oauth->enableDebug();
        $req_url = $this->usos_base_url . 'services/oauth/request_token?scopes='.implode('|', $this->scopes);
        $request_token_info = $oauth->getRequestToken($req_url, $this->callback);
        $this->token_key = $request_token_info['oauth_token'];
        $this->token_secret_key = $request_token_info['oauth_token_secret'];
        
        // $oauth->setToken($this->token_key,$this->token_secret_key);
        // $acc_url = $this->usos_base_url.'services/oauth/access_token';
        // $access_token_info = $oauth->getAccessToken($acc_url);
    }

    public function getTokens()
    {
        $tokens = ['oauth_token'=>$this->token_key, 'oauth_token_secret'=>$this->token_secret_key];
        return $tokens;
    }

    public function getAuthorizeUrl()
    {
        $authurl = $this->usos_base_url.'services/oauth/authorize';

        return [$authurl.((strpos($authurl, '?') === false) ? '?' : '&').'oauth_token='.$this->token_key];

    }



}