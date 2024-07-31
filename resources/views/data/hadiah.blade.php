@extends('layouts.app')

@section('title', 'Data | Hadiah')
@section('data', '')
@section('data.hadiah', 'active')

@section('content')
    <div class="pagetitle">
        <h1>Data Hadiah</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Data</li>
                <li class="breadcrumb-item active">Hadiah</li>
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
                                <th>Nama Barang</th>
                                <th>Quantitas</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Beras</td>
                                <td>2 Kg</td>
                                <td>2</td>
                                <td>
                                    <button class="btn btn-secondary">Edit</button>
                                    <button class="btn btn-danger">Hapus</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Minyak Goreng</td>
                                <td>2 Liter</td>
                                <td>1</td>
                                <td>
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
