<div class="card mb-3">
    <form method="POST" action="{{ $form->url }}" enctype="multipart/form-data">
        @csrf

        <div class="card-body">
            <div class="form-group">
                <label for="title">Title</label>
                <input
                    type="text"
                    class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                    id="title"
                    name="title"
                    value="{{ $form->value('title') }}"
                    autofocus>
                @include('layouts.form.input_error', ['key' => 'title'])
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea
                    class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                    id="description"
                    name="description">
                    {{ $form->value('description') }}
                </textarea>
                @include('layouts.form.input_error', ['key' => 'description'])
            </div>

            <div class="form-group">
                <label for="version">Version</label>
                <input
                    type="text"
                    class="form-control {{ $errors->has('version') ? 'is-invalid' : '' }}"
                    id="version"
                    name="version"
                    value="{{ $form->value('version') }}"
                    placeholder="1.0.0">
                @include('layouts.form.input_error', ['key' => 'version'])
            </div>

            <div class="form-group">
                <label for="audio">Audio</label>
                <input
                    id="audio"
                    type="file"
                    class="form-control {{ $errors->has('audio') ? 'is-invalid' : '' }}"
                    name="audio">
                @include('layouts.form.input_error', ['key' => 'audio'])
            </div>

            <div class="d-flex flex-row justify-content-end">
                <button type="submit" class="btn btn-primary mr-1">{{ $submitBtn }}</button>
                <a href="{{ $refreshUrl }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
