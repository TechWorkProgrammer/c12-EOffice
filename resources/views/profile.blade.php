@extends('layouts.app')

@section('title', 'Profil')
@section('pengguna', '')

@section('content')
    <div class="pagetitle">
        <h1>Ganti Kata Sandi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="{{route('home')}}">Profil</a></li>
            </ol>
        </nav>
    </div>
    <section class="section profile">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body pt-5">
                        <form>
                            <div class="row mb-3">
                                <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Kata Sandi Saat
                                    Ini</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="password" type="password" class="form-control" id="currentPassword">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Kata Sandi
                                    Baru</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="newpassword" type="password" class="form-control" id="newPassword">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Masukkan Ulang Kata
                                    Sandi Baru</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Ganti Kata Sandi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
