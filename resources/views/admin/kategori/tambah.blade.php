@extends('admin.layouts.main')

@section('title', 'Tambah Kategori')

@section('content')
    <form class="ps-form--account-setting" action="{{ url('admin/tambah-kategori') }}" method="POST" autocomplete="off"
        enctype="multipart/form-data">
        @csrf
        <div class="ps-form__header">
            @if (session()->has('success'))
                <div class="alert alert-warning py-3">
                    {{ session('success') }}
                </div>
            @endif
            <h3 class="mb-5"> Tambah Kategori</h3>
        </div>
        <div class="ps-form__content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input class="form-control" type="text" placeholder="Masukkan Nama Kategori" name="nama_kategori"
                            value="{{ old('nama_kategori') }}">
                        @error('nama_kategori')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Gambar</label>
                        <input class="form-control-file" type="file" name="image">
                        @error('image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group submit">
            <button class="ps-btn">Simpan</button>
        </div>
    </form>
@endsection
