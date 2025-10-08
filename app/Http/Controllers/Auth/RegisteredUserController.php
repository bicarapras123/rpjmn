<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Invite;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan halaman register.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Proses pendaftaran user baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'invite_code' => ['required', 'string'],
        ]);
    
        // Cek kode undangan
        $invite = Invite::where('code', $request->invite_code)->first();
    
        if (!$invite) {
            return back()->withErrors(['invite_code' => 'Kode undangan tidak ditemukan.']);
        }
    
        // Jika sudah mencapai batas penggunaan
        if ($invite->used_count >= $invite->max_uses) {
            return back()->withErrors(['invite_code' => 'Kode undangan sudah mencapai batas pemakaian.']);
        }
    
        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        // Tambahkan jumlah penggunaan kode
        $invite->increment('used_count');
    
        // Jika sudah sampai limit, tandai kode sudah digunakan habis
        if ($invite->used_count >= $invite->max_uses) {
            $invite->update(['used' => true]);
        }
    
        event(new Registered($user));
        Auth::login($user);
    
        return redirect(RouteServiceProvider::HOME);
    }
}    