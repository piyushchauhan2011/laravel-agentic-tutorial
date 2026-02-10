<section>
    <h2 class="h5">Profile Information</h2>
    <p class="text-secondary mb-3">Update your account's profile information and email address.</p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="form-text mt-2">
                    Your email address is unverified.
                    <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline" type="submit">
                        Click here to re-send verification email.
                    </button>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <div class="text-success small mt-1">A new verification link has been sent.</div>
                @endif
            @endif
        </div>

        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-primary" type="submit">Save</button>
            @if (session('status') === 'profile-updated')
                <span class="text-success small">Saved.</span>
            @endif
        </div>
    </form>
</section>
