<?php

namespace App\Providers;

class UsosProvider {


    public $usos_base_url;
    public $usos_consumer_key;
    public $usos_consumer_secret_key;
    public $callback;
    public $scopes = array();

    public $oauth;
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
        $this->oauth = new \OAuth($this->usos_consumer_key, $this->usos_consumer_secret_key, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
        $this->oauth->disableSSLChecks();
        $this->oauth->enableDebug();
        $req_url = $this->usos_base_url . 'services/oauth/request_token?scopes='.implode('|', $this->scopes);
        $request_token_info = $this->oauth->getRequestToken($req_url, $this->callback);
        
        $this->token_key = $request_token_info['oauth_token'];
        $this->token_secret_key = $request_token_info['oauth_token_secret'];
        return $request_token_info;

    }

    public function getTokens()
    {
        $tokens = ['oauth_token'=>$this->token_key, 'oauth_token_secret'=>$this->token_secret_key];
        return $tokens;
    }

    public function getAuthorizeUrl($requestToken, $requestTokenSecret)
    {
        $authurl = $this->usos_base_url.'services/oauth/authorize';

        return [$authurl.((strpos($authurl, '?') === false) ? '?' : '&').'oauth_token='.$requestToken . '&oauth_token_secret='. $requestTokenSecret . '&oauth_callback_confirmed=true'];

    }


    public function getAccessToken($url, $consumer_key, $consumer_secret_key, $oauthToken, $oauthSecretToken, $oauthVerifier)
    {
        $this->oauth = new \OAuth($consumer_key, $consumer_secret_key, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
        $this->oauth->disableSSLChecks();
        $this->oauth->setToken($oauthToken,$oauthSecretToken);
        $acc_url = $url.'services/oauth/access_token';
        $access_token_info = $this->oauth->getAccessToken($acc_url, '', $oauthVerifier);

        return $access_token_info;
    }



}
