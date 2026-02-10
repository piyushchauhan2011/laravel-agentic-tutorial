<x-guest-layout>
    <p class="text-secondary mb-3">
        Thanks for signing up. Verify your email by clicking the link we emailed you. If you didn’t receive it, request another.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success" role="alert">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center gap-2">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary btn-sm">Resend Verification Email</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link btn-sm p-0">Log out</button>
        </form>
    </div>
</x-guest-layout>
