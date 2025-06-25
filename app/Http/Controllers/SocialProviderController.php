<?php

namespace App\Http\Controllers;

use App\Models\SocialProvider;
use App\Models\User;
use App\Models\SocialAccount;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class SocialProviderController extends Controller
{
    const DRIVERS = ['facebook', 'x', 'linkedin-openid', 'google', 'github'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $providers = SocialProvider::all();
        return Inertia::render('SocialProviders', [
            'providers' => $providers,
            'drivers' => self::DRIVERS
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $drivers = self::DRIVERS;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'driver' => ['required', 'in:facebook,x,linkedin-openid,google,github'],
            'client_id' => ['required', 'string'],
            'client_secret' => ['required', 'string'],
            'redirect_uri' => ['required', 'string'],
        ]);

        $provider = SocialProvider::create($validated);
        return redirect()->back()->with('success', "$provider?->driver created successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(SocialProvider $socialProvider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SocialProvider $socialProvider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SocialProvider $provider)
    {
        $validated = $request->validate([
            'driver' => ['required', 'in:facebook,x,linkedin-openid,google,github'],
            'client_id' => ['required', 'string'],
            'client_secret' => ['required', 'string'],
            'redirect_uri' => ['required', 'string'],
        ]);

        $provider->update($validated);

        return redirect()->back()->with('success', "$provider?->driver updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocialProvider $provider)
    {
        $provider->delete();
        return redirect()->back()->with('success', "$provider?->driver deleted successfully.");
    }

    public function redirect(SocialProvider $provider)
    {
        $this->configureProvider($provider);

        /*if (in_array($provider, self::DRIVERS)) {
            return to_route('login')->withErrors(['provider' => 'Invalid social provider']);
        }*/


        try {
            return Socialite::driver($provider->driver)->redirect();
        } catch (Exception $e) {
            return to_route('login')->with('error', 'Something went wrong with ' . ucfirst($provider->driver) . ' authentication');
        }
    }

    public function callback(SocialProvider $provider)
    {
        $this->configureProvider($provider);

        /*if (!in_array($provider->name, self::DRIVERS)) {
            return to_route('login')->with('error', 'Invalid social provider');
        }*/


        try {
            $socialUser = Socialite::driver($provider->driver)->user();

            $social_account = SocialAccount::where('provider_id', $provider->id)
                ->where('provider_user_id', $socialUser->getId())
                ->first();

            if ($social_account) {
                // Login existing linked account
                Auth::login($social_account->user);
                return redirect()->intended(route('dashboard', absolute: false));
            }

            // Check if a user exists with this email
            $user = User::where('email', $socialUser->getEmail())->first();
            if ($user) {
                // User exists but no social link — warn or prevent auto-linking
                if (Auth::check()) {
                    // User is logged in and wants to link provider
                    $user->social_accounts()->create([
                        'provider_id' => $provider->id,
                        'provider_user_id' => $socialUser->getId(),
                    ]);
                    return redirect()->route('profile.edit')->with('success', 'Account linked.');
                } else {
                    // Warn: account with email exists but not linked
                    return redirect()->route('login')->with(
                        'info',
                        'An account with this email exists. Please login with email and password first, then link your ' . $provider->driver . ' account from your profile.',
                    );
                }
            } else {
                // New user, create and link
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'password' => null //Hash::make(Str::random(16)), // random password
                ]);

                $user->socialAccounts()->create([
                    'provider_id' => $provider->id,
                    'provider__user_id' => $socialUser->getId(),
                ]);

                Auth::login($user);
                return redirect()->intended(to_route('dashboard'));
            }
        } catch (Exception $e) {
            return to_route('login')->with('error', 'Something went wrong with ' . ucfirst($provider->driver) . ' authentication');
        }
    }
    
    public function unlinkProvider(Request $request, SocialProvider $provider)
    {
        //$this->configureProvider($provider);

        if (!Auth::check()) {
            return to_route('login');
        }

        $user = Auth::user();

        if ($user?->social_accounts?->count() <= 1 && is_null($user?->password)) {
            return redirect()->back()->with('error', 'You must set a password before unlinking your social account');
        }

        $social_account = SocialAccount::where('user_id', $user->id)->where('provider_id', $provider->id)->first();

        $social_account->delete();

        return redirect()->back()->with('success', ucfirst($provider->driver) . ' account unlinked successfully.');
    }

    private function configureProvider(SocialProvider $provider)
    {
        config(["services.$provider->driver" => [
            'client_id' => $provider->client_id,
            'client_secret' => $provider->client_secret,
            'redirect' => $provider->redirect_uri
        ]]);
    }
}
