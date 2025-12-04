@extends('layouts.app')
@section('content')
    <h1>Daftar Post</h1>
    <ul>
    @foreach ($posts as $post)
        <li>
            <strong>{{ $post->title }}</strong>
            (Kategori: {{ $post->category->name ?? 'Tidak Ada' }}) <br>
            <p>{{ $post->content }}</p>
        </li>
    @endforeach
    </ul>
@endsection