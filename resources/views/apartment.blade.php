@extends('layouts.main')
@section('title', ' Тестовое задание')
@section('content')
    <div class="content">
        <apartment-component v-bind:apartments="{{ Js::from($apartments) }}"></apartment-component>
    </div>
@endsection
