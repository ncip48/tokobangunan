@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <section class="ps-section--account">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="ps-section__left">
                        <aside class="ps-widget--account-dashboard">
                            <div class="ps-widget__header"><img
                                    src="{{ $auth->image ? asset('img/pp/' . $auth->image) : 'http://nouthemes.net/html/martfury/img/users/3.jpg' }}"
                                    alt=""
                                    style="height:60px;width:60px;object-fit:cover;flex-basis:71px;max-width:70px">
                                <figure>
                                    <figcaption>Halo, {{ $auth->name }}</figcaption>
                                    <p><a href="#">{{ $auth->email }}</a></p>
                                </figure>
                            </div>
                            <div class="ps-widget__content">
                                <ul>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                    <li class="active"><a href="#"><i class="icon-user"></i> Informasi Akun</a>
                                    </li>
                                    <li><a href="#"><i class="icon-alarm-ringing"></i> Notifikasi</a></li>
                                    <li><a href="#"><i class="icon-papers"></i> Pesanan</a></li>
                                    <li><a href="#"><i class="icon-map-marker"></i> Alamat Saya</a></li>
                                    <li><a href="#"><i class="icon-store"></i> Terakhir Dilihat</a></li>
                                    {{-- <li><a href="#"><i class="icon-heart"></i> Favorite</a></li> --}}
                                    <li><a style="cursor: pointer" aria-current="page"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                                class="icon-power-switch"></i>Logout</a></li>
                                </ul>
                            </div>
                        </aside>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="ps-section__right">
                        <form class="ps-form--account-setting" action="{{ url('/profile/user') }}" method="POST">
                            @csrf
                            @method('patch')
                            <div class="ps-form__header">
                                @if (session()->has('success'))
                                    <div class="alert alert-warning py-3">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <h3> Informasi Akun</h3>
                                @if ($percentage['null'] > 0)
                                    <h5>Lengkapi Profile</h5>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar progress-bar-striped bg-warning progress-bar-animated"
                                            role="progressbar" aria-valuenow="{{ $percentage['null'] }}" aria-valuemin="0"
                                            aria-valuemax="{{ $percentage['base_columns'] }}"
                                            style="width: {{ $percentage['percent'] }}%;font-size: 20px">
                                            {{ $percentage['percent'] }}%</div>
                                    </div>
                                @endif
                            </div>
                            <div class="ps-form__content">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input class="form-control" type="text" placeholder="Masukkan Nama..."
                                        value="{{ $auth->name }}" name="name">
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Password (Kosongkan jika tidak ingin update password)</label>
                                            <input class="form-control" type="text"
                                                placeholder="Masukkan Password baru..." name="password">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Nomor Telpon</label>
                                            <input class="form-control" type="text" placeholder="Masukkan No Telp..."
                                                name="no_telp" value="{{ $auth->no_telp }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input class="form-control" type="text" placeholder="Masukkan Email..."
                                                value="{{ $auth->email }}" name="email">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Tanggal Lahir</label>
                                            <input class="form-control" type="date"
                                                placeholder="Masukkan Tanggal Lahir..." name="dob"
                                                value="{{ $auth->dob }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Jenis Kelamin</label>
                                            <select class="form-control" name="jenis_kelamin">
                                                <option value="">--Pilih Jenis Kelamin--</option>
                                                <option value="0" @if ($auth->jenis_kelamin == '0') selected @endif>
                                                    Laki-Laki</option>
                                                <option value="1" @if ($auth->jenis_kelamin == '1') selected @endif>
                                                    Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 d-flex align-items-center">
                                        <div class="ps-list--link">
                                            <li>
                                                <a style="cursor:pointer;" data-bs-toggle="modal"
                                                    data-bs-target="#staticBackdrop"> Ingin ganti foto
                                                    profile?Klik
                                                    disini</a>
                                            </li>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group submit">
                                <button class="ps-btn">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Upload Foto</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('profile/photo') }}" method="post" id="change-pp-form"
                            enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            <input type="file" name="image" id="image" />
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="ps-btn p-3" data-bs-dismiss="modal"
                            style="font-size: 14px;font-weight:normal">Batalkan</button>
                        <button type="button" class="ps-btn p-3 bg-primary" style="font-size: 14px;font-weight:normal"
                            onclick="event.preventDefault(); document.getElementById('change-pp-form').submit();">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
