<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <input type="email" name="email" value="{{ old('email', $request->email) }}" required />
        <input type="password" name="password" required />
        <input type="password" name="password_confirmation" required />
        <button type="submit">Redefinir Senha</button>
    </form>
</x-guest-layout>