<x-login-layout>Login Page</x-login-layout>

<main>
    <div class="container">
        <h3>Please login as registered user or admin</h3>
        <form id="login-form" method="post">
            @csrf
            <input type="email" name="email" placeholder="Email">
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror
            <input type="password" name="password" placeholder="Password">
            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror
            <button type="submit">Login</button>
            {{-- <p>or</p>
            <a href="/login-admin" class="login-admin-button">
                <p>Login as admin</p>
            </a> --}}
        </form>
    </div>
</main>
