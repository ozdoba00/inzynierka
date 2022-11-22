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

        $usosProvider->setAuthorizationData('https://usosapps.prz.edu.pl/',  env('USOS_API_KEY'), env('USOS_API_KEY_SECRET'), 'http://localhost:8000/api/usos-submit');
        $usosProvider->setScopes(array('studies'));
        $usosProvider->setRequestToken();
        $tokens = $usosProvider->getTokens();
        $usosData = new UsosData();
        $usosData->create($tokens);

        return ['url'=>$usosProvider->getAuthorizeUrl()];
    }


    public function authorization()
    {
        $usosData = UsosData::where('oauth_token', 'LIKE', '%'.$_GET['oauth_token'].'%');
        
        $usosData = $usosData->update([
            'oauth_verifier' => $_GET['oauth_verifier']
        ]);

    }
}
