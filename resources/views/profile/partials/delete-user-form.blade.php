<section>
    <h2 class="h5 text-danger">Delete Account</h2>
    <p class="text-secondary mb-3">Once deleted, all account resources and data are permanently removed.</p>

    <form method="post" action="{{ route('profile.destroy') }}"
        onsubmit="return confirm('Are you sure you want to permanently delete your account?');">
        @csrf
        @method('delete')

        <div class="mb-3">
            <label for="delete_password" class="form-label">Confirm with Password</label>
            <input id="delete_password" name="password" type="password"
                class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                placeholder="Password" />
            @error('password', 'userDeletion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-danger" type="submit">Delete Account</button>
    </form>
</section>
