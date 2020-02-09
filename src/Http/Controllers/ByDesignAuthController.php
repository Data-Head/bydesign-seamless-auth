<?php


namespace DataHead\ByDesignSeamlessAuth\Http\Controllers;


use App\Http\Controllers\Controller;
use App\User;
use http\Env\Response;
use Illuminate\Support\Str;

class ByDesignAuthController extends Controller
{
    private $authToken;
    private $baseURL;

    public function __construct()
    {
        $this->authToken = base64_encode(config('bydesign-seamless-auth.username') . ':' . config('bydesign-seamless-auth.password'));
        $this->baseURL = config('bydesign-seamless-auth.base_url');
    }

    public function checkGUID($guid) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$this->baseURL/Authentication/Seamless/Verify/ForRep/$guid",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: Basic $this->authToken"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            // If there was an error with CURL, return to failed URL
            return \Response::redirectTo(config('bydesign-seamless-auth.failed_redirect_url'));
        } else {
            $response = json_decode($response);

            // If no access token came back, return to failed URL
            if(!isset($response->access_token))
                return \Response::redirectTo(config('bydesign-seamless-auth.failed_redirect_url'));

            $repInfo = $this->getUserInfo($response->userName);

            // Return failed redirect URL if cant obtain rep info
            if($repInfo == null)
                return \Response::redirectTo(config('bydesign-seamless-auth.failed_redirect_url'));

            $user = User::where('rep_id', $repInfo->userName)->first();

            if($user != null) {
                \Auth::login($user);
            } else {
                $newUser = new User();

                if(isset($repInfo->FirstName) && isset($repInfo->LastName))
                    $newUser->name = $repInfo->FirstName . ' ' . $repInfo->LastName;
                else if(isset($repInfo->Company))
                    $newUser->name = $repInfo->Company;
                else
                    $newUser->name = Str::random(16);

                $newUser->email = isset($repInfo->Email) ? $repInfo->Email : $repInfo->FirstName . $repInfo->LastName . '@example.com';
                $newUser->password = \Hash::make(Str::random(12) . $newUser->email . Str::random(12));
                $newUser->rep_id = $repInfo->RepDID;
                $newUser->save();
                \Auth::login($newUser);
            }

            return \Response::redirectTo(config('bydesign-seamless-auth.success_redirect_url'));
        }
    }

    private function getUserInfo($userName) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$this->baseURL/user/rep/$userName/info",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: Basic $this->authToken"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return null;
        } else {
            $response = json_decode($response);
            if(!isset($response->RepID))
                return null;

            return $response;
        }
    }
}
