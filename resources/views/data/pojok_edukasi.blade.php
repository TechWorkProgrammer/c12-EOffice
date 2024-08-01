@extends('layouts.app')

@section('title', 'Data | Pojok Edukasi')
@section('data', '')
@section('data.pojok_edukasi', 'active')

@section('content')
    <div class="pagetitle">
        <h1>Pojok Edukasi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Data</li>
                <li class="breadcrumb-item active">Pojok Edukasi</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body pt-3">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Gambar</th>
                                    <th>Dibuat Oleh</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pojokEdukasis as $pojokEdukasi)
                                    <tr>
                                        <td>{{ $pojokEdukasi->name }}</td>
                                        <td>
                                            <img src="{{ $pojokEdukasi->image }}" style="height: 80px">
                                        </td>
                                        <td>{{ $pojokEdukasi->admin->username }}</td>
                                        <td>
                                            <button class="btn btn-primary">Detail</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
