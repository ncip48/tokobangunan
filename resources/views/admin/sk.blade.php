@extends('admin.layouts.main')

@section('title', 'Syarat & Ketentuan')

@section('content')
    <form class="ps-form--account-setting" action="{{ url('admin/syarat-ketentuan') }}" method="POST" autocomplete="off">
        @csrf
        @method('PATCH')
        <div class="ps-form__header">
            @if (session()->has('success'))
                <div class="alert alert-warning py-3">
                    {{ session('success') }}
                </div>
            @endif
            <h3 class="mb-5"> Syarat & Ketentuan</h3>
        </div>
        <div class="ps-form__content">
            <div class="row">
                <div class="col-md-12 mb-3">
                    {{-- <label for="sk" class="form-label">Tentang</label> --}}
                    <textarea type="text" class="ckeditor form-control" name="sk" rows="50">{{ $site->sk }}</textarea>
                    @error('sk')
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


@push('customScript')
    <script>
        $(document).ready(function() {
            $('.ckeditor').ckeditor();
        });
    </script>
@endpush
