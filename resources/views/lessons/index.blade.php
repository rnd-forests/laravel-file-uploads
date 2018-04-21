@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="mb-0"><strong>Lessons Management</strong></h4>
        </div>
    </div>

    @include('lessons.form', ['submitBtn' => 'Add New Lesson', 'refreshUrl' => route('lessons.index')])

    <table class="table bg-white">
        <thead class="thead-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col">Version</th>
                <th scope="col">Audio</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lessons as $lesson)
                <tr>
                    <th scope="row">{{ $lesson->id }}</th>
                    <td>{{ $lesson->title }}</td>
                    <td>{{ $lesson->description }}</td>
                    <td>{{ $lesson->version }}</td>
                    <td>
                        <audio controls>
                            <source src="{{ $lesson->audioPath() }}" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex flex-row justify-content-center">
        {{ $lessons->render() }}
    </div>
</div>
@endsection
