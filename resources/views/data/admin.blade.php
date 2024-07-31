@extends('layouts.app')

@section('title', 'Data | Admin')
@section('data', '')
@section('data.admin', 'active')

@section('content')
    <div class="pagetitle">
        <h1>Data Admin</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Data</li>
                <li class="breadcrumb-item active">Admin</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body pt-3">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Username</th>
                                <th>Terakhir Login</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Un Pugh</td>
                                <td>9958</td>
                            </tr>
                            <tr>
                                <td>Unity Pugh</td>
                                <td>98</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
