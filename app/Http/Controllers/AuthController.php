<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('login');
    }

    /**
     * Register User
     */
    public function register(Request $request)
    {
        // Validate
        $request->validate([
            'name' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'max:255', 'email', 'unique:users'],
            'password' => ['required', 'min:6'],
            'is_admin' => ['required'],
            'role' => ['required'],
        ]);

        // Store input data
        User::create($request->only('name', 'email', 'email_verified_at', 'password', 'is_admin', 'role'));
        return redirect('master-data/user')->with('success', 'User baru berhasil ditambahkan!');
    }

    /**
     * Login User
     */
    public function login(Request $request)
    {

        // Validate
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->is_admin) {
                return redirect()->intended('/dashboard');
            }
            return redirect()->intended('/dashboard');
            // return redirect()->route('dashboard');

        } else {
            return back()->with('error', 'Login failed. Email or password is incorrect.');
        }
    }

    /** 
     * Logout User
     */
    public function logout(Request $request)
    {
        // Periksa apakah user sudah terotentikasi
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        } else {
            // Jika user belum terotentikasi, kemungkinan session sudah invalid
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect('/');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
