<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserSetting;
use App\Http\Resources\UserProfileResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    public function index()
    {
        return UserProfileResource::collection(
            UserSetting::where('user_id',Auth::user()->id)->get()
        );
    }

    public function update(Request $request,$id)
    {
        $validation_rules = [
            'name' => 'string|unique:users',
            'email' => 'email|unique:users',
            'user_settings' => 'array',
            'user_settings.*.id' => 'numeric',
            'user_settings.*.name' => 'string',
            'user_settings.*.value' => 'string',
        ];
        $validator = Validator::make($request->all(), $validation_rules );
        if($validator->fails()) {
            return response()->json([
                'message' => 'validation_issue',
                'validate_err'=> $validator->messages(),
            ]);
        }
        foreach($request->user_settings as $item){
            $setting_table_id = $item['id'];
            unset($item['id']);
            $userSettings= UserSetting::where(['id'=>$setting_table_id,'user_id'=>$id])->first();
            if($userSettings){
                $userSettings->update($item);
            }
        }
        $user = User::where('id',$id)->with('user_settings')->first();
        return response()->json([
            'message' => 'successfully updated users data',
            'data'=> $user,
        ]);
       
    }
}
