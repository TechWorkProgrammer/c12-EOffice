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
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Un Pugh</td>
                                <td>9958</td>
                                <td>
                                    <button class="btn btn-primary">Detail</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Unity Pugh</td>
                                <td>98</td>
                                <td>
                                    <button class="btn btn-primary">Detail</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
