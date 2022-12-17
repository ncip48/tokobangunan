@extends('admin.layouts.main')

@section('title', 'Edit Website')

@section('content')
    <form class="ps-form--account-setting" action="{{ url('admin/edit-website') }}" method="POST" autocomplete="off"
        enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="ps-form__header">
            @if (session()->has('success'))
                <div class="alert alert-warning py-3">
                    {{ session('success') }}
                </div>
            @endif
            <h3 class="mb-5"> Edit Website</h3>
        </div>
        <div class="ps-form__content">
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label>Nama Website</label>
                        <input class="form-control" type="text" placeholder="Masukkan Nama Website" name="name"
                            value="{{ $site->name }}">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label>Email Website</label>
                        <input class="form-control" type="email" placeholder="Masukkan Email Website" name="email"
                            value="{{ $site->email }}">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <input class="form-control" type="text" placeholder="Masukkan Deskripsi Website"
                            name="description" value="{{ $site->description }}">
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label>No HP</label>
                        <input class="form-control" type="text" placeholder="Masukkan No HP Website" name="phone"
                            value="{{ $site->phone }}">
                        @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="address" class="form-label">Alamat</label>
                    <textarea type="text" class="form-control" id="address" name="address" rows="5">{{ $site->address }}</textarea>
                    @error('address')
                        <span class="text-danger">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <div class="mb-4">
                        <label for="facebook" class="form-label">Facebook</label>
                        <input type="text" class="form-control" id="facebook" name="facebook"
                            value="{{ $site->facebook }}">
                        @error('facebook')
                            <span class="text-danger">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div>
                        <label for="twitter" class="form-label">Twitter</label>
                        <input type="text" class="form-control" id="twitter" name="twitter"
                            value="{{ $site->twitter }}">
                        @error('twitter')
                            <span class="text-danger">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="about" class="form-label">Tentang</label>
                    <textarea type="text" class="form-control" id="about" name="about" rows="5">{{ $site->about }}</textarea>
                    @error('about')
                        <span class="text-danger">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <div class="mb-3">
                        <label for="instagram" class="form-label">Instagram</label>
                        <input type="text" class="form-control" id="instagram" name="instagram"
                            value="{{ $site->instagram }}">
                        @error('instagram')
                            <span class="text-danger">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mt-4">
                        <label for="whatsapp" class="form-label">Whatsapp</label>
                        <input type="text" class="form-control" id="whatsapp" name="whatsapp"
                            value="{{ $site->whatsapp }}">
                        @error('whatsapp')
                            <span class="text-danger">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="logo" class="form-label">Ubah Logo Utama</label>
                    <input type="file" class="form-control-file" name="logo">
                    <span class="text-danger">* Kosongkan jika tidak ingin mengubah gambar</span>
                    @error('logo')
                        <span class="text-danger">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="logo" class="form-label">Ubah Logo Seller</label>
                    <input type="file" class="form-control-file" name="logo_seller">
                    <span class="text-danger">* Kosongkan jika tidak ingin mengubah gambar</span>
                    @error('logo_seller')
                        <span class="text-danger">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="logo" class="form-label">Ubah Logo Admin</label>
                    <input type="file" class="form-control-file" name="logo_admin">
                    <span class="text-danger">* Kosongkan jika tidak ingin mengubah gambar</span>
                    @error('logo_admin')
                        <span class="text-danger">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="favicon" class="form-label">Ubah Favicon</label>
                    <input type="file" class="form-control-file" id="favicon" name="favicon">
                    <span class="text-danger">* Kosongkan jika tidak ingin mengubah favicon</span>
                    @error('favicon')
                        <span class="text-danger">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group submit mt-3">
            <button class="ps-btn">Simpan</button>
        </div>
    </form>
@endsection
