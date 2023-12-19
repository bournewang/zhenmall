<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends \Laravel\Nova\Http\Controllers\LoginController
{
    public function authLogin(Request $request)
    {
        $request->request->add(['mobile' => $request->mobile]);

        return $this->login($request);
    }
    
    public function username()
    {
        return 'mobile';
    }
}