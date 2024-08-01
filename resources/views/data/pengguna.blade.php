@extends('layouts.app')

@section('title', 'Data | Pengguna')
@section('data', '')
@section('data.pengguna', 'active')

@section('content')
    <div class="pagetitle">
        <h1>Data Pengguna</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Data</li>
                <li class="breadcrumb-item active">Pengguna</li>
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
                                    <th>No Telepon</th>
                                    <th>Role</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Alamat</th>
                                    <th>Email</th>
                                    <th>Poin</th>
                                    <th>Kuesioner</th>
                                    <th>Verifikasi</th>
                                    <th>Dibuat Pada</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->phone_number }}</td>
                                        <td>{{ $user->role }}</td>
                                        <td>{{ $user->birthdate }}</td>
                                        <td>{{ $user->address }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->point }}</td>
                                        <td>{{ $user->questioner_submitted }}</td>
                                        <td>{{ $user->is_verified }}</td>
                                        <td>{{ $user->created_at }}</td>
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
