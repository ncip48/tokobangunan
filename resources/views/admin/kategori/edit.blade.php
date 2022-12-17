@extends('admin.layouts.main')

@section('title', 'Edit Kategori')

@section('content')
    <form class="ps-form--account-setting" action="{{ url('admin/kategori') }}" method="POST" autocomplete="off"
        enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="ps-form__header">
            @if (session()->has('success'))
                <div class="alert alert-warning py-3">
                    {{ session('success') }}
                </div>
            @endif
            <h3 class="mb-5"> Edit Kategori</h3>
        </div>
        <div class="ps-form__content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="hidden" name="id" value="{{ $category->id }}">
                        <input class="form-control" type="text" placeholder="Masukkan Nama Kategori" name="nama_kategori"
                            value="{{ $category->nama_kategori }}">
                        @error('nama_kategori')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Gambar</label>
                        <input class="form-control-file" type="file" name="image">
                        <span class="text-danger">* Kosongkan jika tidak ingin mengubah gambar</span>
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
