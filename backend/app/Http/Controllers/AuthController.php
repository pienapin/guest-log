<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'redirectToBPS', 'handleBPSCallback']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['nip']);
        $user = User::where('nip', $credentials['nip'])->first();
        if ($user)
        {
          $token = Auth::login($user);
        } else {
          return response()->json(['error' => 'Unauthorized'], 401);
        }

        // if (!$token = auth()->attempt($credentials)) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 120
        ]);
    }

    public function redirectToBPS()
    {
      // return Socialite::driver('bps')->redirect();
    }

    public function handleBPSCallback()
    {
      // try {
      //   $user = Socialite::driver('bps')->user();
      //   $finduser = User::where('nip', $user->nip)->first();
      //   if ($finduser) {
      //     $auth = Auth::login($finduser);

      //     return response()->json([
      //       'message' => 'User is authenticated',
      //       'data' => $auth
      //     ], 200);
      //   } else {
      //     $newUser = User::updateOrCreate([
      //       'nip' => $user->nip,
      //       'nama' => $user->nama,
      //       'email' => $user->email,
      //       'no_wa' => $user->no_wa
      //     ]);

      //     $auth = Auth::login($newUser);

      //     return response()->json([
      //       'message' => 'User is authenticated',
      //       'data' => $auth
      //     ], 200);
      //   }
      // } catch (Exception $e) {
      //     return response()->json([
      //       'message' => 'Error occured.',
      //       'error' => $e
      //     ]);
      // }
    }
}
