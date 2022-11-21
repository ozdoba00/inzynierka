<?php

use Laravel\Socialite\Two\AbstractProvider;

class usosServiceProvider extends AbstractProvider {

    public function getCognitoUrl()
    {
        return config('services.usos.base_uri') . '/oauth';
    }
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getCognitoUrl() . '/authorize', $state);
    }
 
    protected function getTokenUrl()
    {
        // TODO: Implement getTokenUrl() method.
    }
 
    protected function getUserByToken($token)
    {
        // TODO: Implement getUserByToken() method.
    }
 
    protected function mapUserToObject(array $user)
    {
        // TODO: Implement mapUserToObject() method.
    }

}