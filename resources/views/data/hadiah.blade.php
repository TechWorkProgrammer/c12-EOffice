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
                                    <th>Poin</th>
                                    <th>Gambar</th>
                                    <th>Peluang</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hadiahs as $hadiah)
                                    <tr>
                                        <td>{{ $hadiah->name }}</td>
                                        <td>{{ $hadiah->point }}</td>
                                        <td>
                                            <img src="{{ $hadiah->image }}" style="height: 80px">
                                        </td>
                                        <td>{{ $hadiah->possibility }}%</td>
                                        <td>
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
