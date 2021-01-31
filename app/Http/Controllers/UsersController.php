<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UsersController extends Controller
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function Users(Request $request){
        $country = $request->input('country');
        $users =User::with('Companies:company_name')
                    ->whereHas('Companies',function ($query) use($country){
                      $query->whereHas('Country',function ($q) use($country){
                            $q->where('country_name',$country);
                      });
        })->get();
        $response['code']=200;
        $response['status']='success';
        $response['data']= $users;
        return response()->json($response,200);

    }

}
