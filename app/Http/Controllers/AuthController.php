<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditProfileRequest;
use App\Http\Requests\LoginPostRequest;
use App\Http\Resources\UserResource;
use App\Providers\UsosProvider;
use App\Models\UsosData;
use App\Models\UsosDataUser;
use App\Models\FieldOfStudy;
use App\Models\StudyGroup;

use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User
     */
    public function createUser(Request $request)
    {

        try {
            //Validated
    
            $validateUser = Validator::make(
                $request->userData,
                [
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required',
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            
            $email = $request->userData['email'];
            $oauthData = UsosData::where('oauth_verifier', $request->oauthVerifier)->first();
            $usosApi = UsosProvider::setApiAuthorization($oauthData->oauth_token, $oauthData->oauth_token_secret);
            $usosData = new UsosData();
            $userData = $usosData->checkUserApiByEmail($usosApi, $email);
            $userGroups = $usosData->getUserGroups($usosApi);
            if($userData)
            {
                $user = User::create([
                    'email' => $userData['email'],
                    'password' => Hash::make($request->password),
                    'name' => $userData['first_name'],
                    'last_name' => $userData['last_name'],
                    'email_verified_at' => date('Y-m-d H:i:s'),
                    'date_of_birth' => $userData['birth_date'],
                    'gender' => $userData['sex']
                ]);
    
              
                UsosDataUser::create([
                    'user_id' => $user->id,
                    'usos_data_id' => $oauthData->id
                ]);

                if($userData['student_programmes'])
                {
                    foreach ($userData['student_programmes'] as $field) 
                    {
                        $studyField = FieldOfStudy::where('usos_id', $field->id)->first();
                        if(empty($studyField))
                        {
                            $studyField = FieldOfStudy::create([
                                'name' => $field->programme->description->pl,
                                'usos_id' => $field->id
                            ]);
                        }

                        FieldOfStudy::addUserToStudyField($user->id, $studyField->id);
                    }
                }

                if($userGroups)
                {
                    foreach ($userGroups['groups'] as $groups) 
                    {
                        foreach ($groups as  $group) {
                            
                            $studyGroup = StudyGroup::where('usos_id', $group->course_unit_id)->first();
                            if(empty($studyGroup))
                            {
                                $groupClassType = $group->class_type->pl[0];
                                
                                if($group->class_type->pl[0] !== 'W' && $group->class_type->pl[0] !== 'L' && $group->class_type->pl[0] !== 'E' && $group->class_type->pl[0] !== 'P')
                                {
                                    $groupClassType = 'C';
                                }
                             
                                $studyGroup = StudyGroup::create([
                                    'usos_id' => $group->course_unit_id,
                                    'name'=> $group->course_name->pl,
                                    'type' => $groupClassType,
                                    'term' => $group->term_id
                                ]);
                            }

                            StudyGroup::saveUserToGroup($user->id, $studyGroup->id);
                        }
                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => 'User Created Successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ], 200);
            }


            
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            
            $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            
            
            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
            
           
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function profile()
    {
        return new UserResource(User::where('id', Auth::user()->id)->with('studyFields')->first());
    }

    public function checkToken(Request $request)
    {
        $currentToken = $request->currentToken;
        $currentToken = explode('|', $currentToken);

        $tokenId = $currentToken[0];
        $tokenObject = User::getTokenById($tokenId);
        if (!empty($tokenObject)) 
        {
            $currentDate = date('Y-m-d H:i:s', time() - 3600);
            if ($tokenObject->created_at < $currentDate) 
            {
                User::removeToken($tokenId);
                UsosData::removeToken(Auth::user()->id);
                return ['isExpired' => true];
            }
        }
        else
        {
            return ['isExpired' => true];
        }
        

    }


    public function editProfile(EditProfileRequest $request)
    {
        
        try {

            $imgPath = null;
            $user = User::findOrFail(Auth::user()->id);
            
            if(!empty($user->profile_image))
            {
                $imgPath = $user->profile_image;
            }
            if($request->file('avatar') !== null)
            {
                $filenameWithExt = $request->file('avatar')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
    
    
                // Get just ext
                $extension = $request->file('avatar')->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore = $filename.'_'. $user->id . '.'. $extension;
                $imgPath = $request->file('avatar')->storeAs('avatars/',$fileNameToStore);
            }
           
            
            // Upload Image
            $birthDate = Carbon::parse($request->birthDate);
            // return ['baza'=> $user->date_of_birth, 'req'=>$birthDate];
            if(strcmp($birthDate, $user->date_of_birth))
            {
                $birthDate= $birthDate->addDays('1');
            }
            
         
            $user = $user->update([
                'name' => $request->name,
                'last_name' => $request->lastName,
                'gender' => $request->gender,
                'date_of_birth' => $birthDate,
                'profile_image' => $imgPath

            ]);

            return response()->json([
                'success' => true,
                'message' => 'Zadanie zostalo zaktualizowane!',
                'data' => $user
            ], 200);
        } catch (\Throwable $th) {
            return ['error' => $th];
        }
    }
}
