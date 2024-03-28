<?php

namespace App\Http\Controllers\Auth;

use Google\Client;
use Inertia\Inertia;
use Inertia\Response;
use Google\Service\Drive;
use Google\Service\Oauth2;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;
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
    }

}
