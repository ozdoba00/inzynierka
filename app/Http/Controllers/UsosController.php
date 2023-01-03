<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\UsosProvider;
use App\Models\UsosData;
class UsosController extends Controller
{
    public function index()
    {
        $usosProvider = new UsosProvider;
        $usosProvider->setAuthorizationData('https://usosapps.prz.edu.pl/',  env('USOS_API_KEY'), env('USOS_API_KEY_SECRET'), 'http://localhost:8080/usos-submit');
        $usosProvider->setScopes(array('studies', 'email', 'other_emails', 'personal'));
        $requestTokens = $usosProvider->setRequestToken();

        $usosData = new UsosData();
        $usosData->create([
            'oauth_token' => $requestTokens['oauth_token'],
            'oauth_token_secret' => $requestTokens['oauth_token_secret']
        ]);

        return ['url'=>$usosProvider->getAuthorizeUrl($requestTokens['oauth_token'], $requestTokens['oauth_token_secret'])];
    }

    public function authorization(Request $request)
    {
        try {
            $oauthToken = $request->oauth_token;
            $oauthVerifier = $request->oauth_verifier;
            $usosData = UsosData::where('oauth_token', 'LIKE', '%'.$oauthToken.'%');

            if(!empty($usosData))
            {
                $usosData = $usosData->update([
                    'oauth_verifier' => $oauthVerifier
                ]);
                return ['message'=>'Usos data authorized successfully'];
            }

        } catch (\Throwable $th) {
            return ['error' => $th];
        }
    }
    public function accessToken(Request $request)
    {
        try {

            $usosProvider = new UsosProvider;
            $oauthToken = $request->oauth_token;
            $usosData = UsosData::where('oauth_token', 'LIKE', '%' . $oauthToken . '%')->first();
            $access_token = $usosProvider->getAccessToken('https://usosapps.prz.edu.pl/', env('USOS_API_KEY'), env('USOS_API_KEY_SECRET'), $usosData->oauth_token, $usosData->oauth_token_secret, $usosData->oauth_verifier);
            $usosDataUpdate = UsosData::find($usosData->id);
            $usosDataUpdate = $usosDataUpdate->update([
                'oauth_token' => $access_token['oauth_token'],
                'oauth_token_secret' => $access_token['oauth_token_secret']
            ]);

        } catch (\Throwable $th) {
            return $th;
        }
    }
}
