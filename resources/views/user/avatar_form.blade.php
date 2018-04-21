<div class="card">
    <div class="card-header">Change Avatar</div>

    <div class="card-body">
        <form method="POST" action="{{ route('update.avatar') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="avatar" class="col-form-label text-md-right">Choose your avatar</label>
                <input id="avatar" type="file" class="form-control {{ $errors->has('avatar') ? 'is-invalid' : '' }}" name="avatar">
                @include('layouts.form.input_error', ['key' => 'avatar'])
            </div>

            <div class="form-group mb-0">
                <button type="submit" class="btn btn-primary">
                    Upload
                </button>
            </div>
        </form>

        <div class="mt-4">
            <img src="{{ auth()->user()->getAvatar() }}" class="img-thumbnail" width="100" height="100">
        </div>
    </div>
</div>
