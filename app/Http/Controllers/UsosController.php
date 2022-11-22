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

        $usosProvider->setAuthorizationData('https://usosapps.prz.edu.pl/',  env('USOS_API_KEY'), env('USOS_API_KEY_SECRET'), 'http://localhost:8081/usos-submit');
        $usosProvider->setScopes(array('studies'));
        $usosProvider->setRequestToken();
        $tokens = $usosProvider->getTokens();
        $usosData = new UsosData();
        $usosData->create($tokens);

        return ['url'=>$usosProvider->getAuthorizeUrl()];
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



                // return ['message'=>'Usos data authorized successfully'];
            }


        } catch (\Throwable $th) {
            return ['error' => $th];
        }



    }
}
