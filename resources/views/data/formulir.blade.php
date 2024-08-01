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
                                    <th>Respon</th>
                                    <th>Rating</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Pertanyaan</td>
                                    <td>20</td>
                                    <td>4.9</td>
                                    <td>
                                        <button class="btn btn-success">Detail</button>
                                        <button class="btn btn-secondary">Edit</button>
                                        <button class="btn btn-danger">Hapus</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Unity Pugh</td>
                                    <td>98</td>
                                    <td>4.9</td>
                                    <td>
                                        <button class="btn btn-success">Detail</button>
                                        <button class="btn btn-secondary">Edit</button>
                                        <button class="btn btn-danger">Hapus</button>
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
