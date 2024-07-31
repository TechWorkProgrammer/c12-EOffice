@extends('layouts.app')

@section('title', 'Beranda')
@section('beranda', '')

@section('content')
    <div class="pagetitle">
        <h1>Blank Page</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route("home")}}">Beranda</a></li>
                <li class="breadcrumb-item">Pages</li>
                <li class="breadcrumb-item active"></li>
            </ol>
        </nav>
    </div>
@endsection
