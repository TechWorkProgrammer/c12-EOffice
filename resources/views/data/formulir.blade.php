@extends('layouts.app')

@section('title', 'Data | Pengguna')
@section('data', '')
@section('data.formulir', 'active')

@section('content')
    <div class="pagetitle">
        <h1>Data Formulir</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Data</li>
                <li class="breadcrumb-item active">Formulir</li>
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
                                    <th>Pertanyaan</th>
                                    <th>Responden</th>
                                    <th>Rating</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($questioners as $questioner)
                                    <tr>
                                        <td>{{ $questioner->question }}</td>
                                        <td>{{ $questioner->created_at }}</td>
                                        <td>
                                            <button class="btn btn-success">Detail</button>
                                            <button class="btn btn-secondary">Edit</button>
                                            <button class="btn btn-danger">Hapus</button>
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
