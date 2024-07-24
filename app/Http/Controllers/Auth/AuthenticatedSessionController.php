<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Jobs\LogUserName;
use Illuminate\View\View;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {

            return view('auth.login');



    }




    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {


     $credentials = $request->only('email', 'password');


        $user = User::where('email', $request->email)->first();

        // if($user->try >= 5  )
        // {

        //     abort(403, 'You have been locked out');
        // }

        // if(!Auth::attempt($credentials)){

        //     $user->update([
        //         'try' => $user->try + 1,
        //         'last_failed_attempt' => now()
        //     ]);
        //     return back()->withErrors([
        //         'email' => 'The password dos not match.',
        //     ]);
        // }


        $request->authenticate();
       // Excel::store(new UsersExport, 'recent_users.xlsx', 'public');
       LogUserName::dispatch();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
