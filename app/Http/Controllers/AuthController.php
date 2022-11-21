<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditProfileRequest;
use App\Http\Requests\LoginPostRequest;
use App\Http\Resources\UserResource;
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
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required',
                    'last_name' => 'required',
                    'gender' => 'required',
                    'date_of_birth' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
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
    public function loginUser(LoginPostRequest $request)
    {
        try {

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            if ($user->email_verified_at)
            {
                return response()->json([
                    'status' => true,
                    'message' => 'User Logged In Successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken,
                    'email_verified' => $user->email_verified_at
                ], 200);
            }
            else
            {
                return response()->json([
                    'message' => 'User is not verified',
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function profile(Request $request)
    {
        // return $request->user();
        return new UserResource($request->user());
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
