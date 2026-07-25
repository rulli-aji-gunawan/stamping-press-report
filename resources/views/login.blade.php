<x-login-layout>Login Page</x-login-layout>

<main>
    <div class="container">
        <h3>Please login as registered user or admin</h3>

        @if (session('error'))
            <p class="error">{{ session('error') }}</p>
        @endif

        <form id="login-form" action="{{ route('login') }}" method="post">
            @csrf
            <input type="email" name="email" autocomplete="off" placeholder="Email" value="{{ old('email') }}"
                class="{{ $errors->has('email') ? 'input-error' : '' }}">
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror
            <input type="password" name="password" autocomplete="off" placeholder="Password"
                class="{{ $errors->has('password') ? 'input-error' : '' }}">
            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror
            <button type="submit">Login</button>
        </form>
    </div>
</main>
