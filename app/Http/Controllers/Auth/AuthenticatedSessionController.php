<?php

namespace App\Http\Controllers\Auth;

use Google\Client;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Google\Service\Drive;
use Google\Service\Oauth2;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Service;
use Illuminate\Support\Facades\Redirect;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        /** @var \App\Models\User */
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            return Redirect::intended(RouteServiceProvider::ADMIN);
        }

        return Redirect::intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function oauth(Request $request) {
        $client = new Client();
        $client->setAuthConfig(storage_path("app/private/google_cred.json"));
        $client->setAccessType("offline");

        $client->addScope(Drive::DRIVE);
        $client->addScope(Oauth2::USERINFO_EMAIL);
        $client->addScope(Oauth2::USERINFO_PROFILE);

        $client->setRedirectUri(route('oauth.callback'));

        $auth_url = $client->createAuthUrl();
        header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
        exit;
    }

    public function callback(Request $request) {
        $request->validate([
            "code" => "required",
            "scope" => "required",
        ]);
        
        $client = new Client();
        $client->setAuthConfig(storage_path("app/private/google_cred.json"));
        $client->setAccessType("offline");

        $client->addScope(Drive::DRIVE);
        $client->addScope(Oauth2::USERINFO_EMAIL);
        $client->addScope(Oauth2::USERINFO_PROFILE);

        $client->setRedirectUri(route('oauth.callback'));
        $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));

        $service = new Oauth2($client);
        $data = $service->userinfo->get();

        $serviceFn = function ($user) use($token) {
            $provider = "google";

            $service = Service::where('user_id', $user->id)->where('provider', $provider)->first();
            if (! $service) {
                $service = new Service;
                $service->user_id = $user->id;
                $service->provider = $provider;
                $service->payload = json_encode($token);
                $service->save();
            }
        };

        $oAuthUser = User::where('social_type', 'google')
            ->where('social_id', $data->id)
            ->first();

        if ($oAuthUser) {
            $serviceFn($oAuthUser);
            Auth::login($oAuthUser);
            return Redirect::to(RouteServiceProvider::HOME);
        }

        $user = User::where('email', $data->email)->first();
        if ($user) {
            $user->update([
                'social_id' => $data->id,
                'social_type' => 'google'
            ]);

            $serviceFn($user);
            Auth::login($user);
            return Redirect::to(RouteServiceProvider::HOME);
        }

        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'email_verified_at' => now(),
            'password' => Hash::make(Str::random(32)),
            'social_id' => $data->id,
            'social_type' => 'google'
        ]);

        event(new Registered($user));

        $serviceFn($user);
        Auth::login($user);

        return Redirect::to(RouteServiceProvider::HOME);
    }

}
