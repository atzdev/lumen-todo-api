<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController
{
	private $request;

	public function __construct(Request $request)
	{
		
		$this->request = $request;
		//dd($this->request->all());
	}

	public function jwt(User $user)
	{
		$payload = [
			'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 3*60*60 // Expiration time 3Hours
		];

		return JWT::encode($payload, env('JWT_SECRET'));
	}

	public function authenticate(Request $request)
	{
		
		$this->validate($request, [
			'email' => 'required|email',
			'password' => 'required'
		]);

		// find the user by email
		//$user = User::where('email', $this->request->input('email'))->first();
		//$user = User::where('email', 'khowell@gmail.com')->first();
		$user = User::where('email', $request->email)->first();
		
		if(!$user) {
			return response()->json([
				'error' => 'Email Does not exist.'
			], 400);
		}
		
		if (Hash::check($this->request->password, $user->password)) {
			return response()->json([
				'token' => $this->jwt($user)
			], 200);
		}

		// Bad Request response
		return response()->json([
			'error' => 'Email or password is wrong'
		], 400);
	}

    
}
