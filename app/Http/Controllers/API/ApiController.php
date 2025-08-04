<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, Password};
use Illuminate\Validation\{Rules, ValidationException};
use Illuminate\Support\Str;
use Laravel\Passport\RefreshTokenRepository;

class ApiController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Return the new user with a 201 Created status
        return new UserResource($user)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Authenticate a user and return access and refresh tokens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Generate access and refresh tokens for the authenticated user
        $user = $request->user();
        $tokenResult = $user->createToken('auth_token', ['*']);
        $accessToken = $tokenResult?->accessToken;
        $expires_at = $tokenResult?->token?->expires_at;
        //$refreshToken = $user->createToken('refresh_token', ['*'])->refreshToken;

        return response()->json([
            'access_token' => $accessToken,
            //'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
            'expires_at' => $expires_at,
        ]);
    }

    /**
     * Logout the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // Revoke the user's access token
        //$request->user()->currentAccessToken()->delete();
        // Get the access token used for the request
        $accessToken = $request->user()->token();

        // Revoke access token
        $accessToken->revoke();

        // Revoke associated refresh tokens
        //app(RefreshTokenRepository::class)->revokeRefreshTokensByAccessTokenId($accessToken->id);

        // Return a success response
        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Get the authenticated user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        return new UserResource($user);
    }

    /**
     * Get the authenticated user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_profile(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            //'password' => 'required|string|min:8|confirmed',
        ]); 

        $user->update($request->all());
        UserResource::withoutWrapping();
        return response()->json([
            "message" => "Profile updated successfully.",
            "data" => new UserResource($user)
        ],200);
    }

    /**
     * Update the user's password.
     */
    public function update_password(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Password updated successfully.'
        ],200);
    }

    public function forgot_password(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        Password::sendResetLink(
            $request->only('email')
        );

        //return back()->with('status', __('A reset link will be sent if the account exists.'));
        return response()->json([
            'message' => 'A reset link will be sent if the account exists.'
        ],200);
    }

    public function change_password(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status == Password::PasswordReset) {
            return response()->json([
                'message' => $status,
            ],200);
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }
}
