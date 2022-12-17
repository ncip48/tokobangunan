@extends('admin.layouts.main')

@section('title', 'Kategori')

@section('content')
    <div class="ps-shopping-product">
        <div class="ps-form--account-setting">
            <div class="ps-form__header">
                @if (session()->has('success'))
                    <div class="alert alert-warning py-3">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="d-flex flex-row align-items-center mb-3 justify-content-between">
                    <div class="d-flex flex-row align-items-center mb-3">
                        <h3 class="mb-5"> Kategori</h3>
                        <a href="{{ url('admin/tambah-kategori') }}" class="ml-4 badge badge-pill px-4 py-2 mb-5"
                            style="background-color: #dd2400;color:white;font-size:12px"> +
                            Tambah</a>
                    </div>
                    <form action="{{ url('admin/kategori') }}" method="GET" class="ml-4 mb-5" autocomplete="off">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari Kategori" name="search"
                                value="{{ request()->get('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-danger" style="background-color:#dd2400" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Gambar</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $category->nama_kategori }}</td>
                                <td><a style="cursor: pointer" data-image="{{ url('img/' . $category->image) }}"
                                        id="pop-image-{{ $loop->iteration }}">{{ $category->prefix }}</a>
                                </td>
                                <td class="text-right">
                                    <form id="form-hapus-kategori-admin-{{ $category->id }}"
                                        action="{{ url('admin/kategori/' . Crypt::encrypt($category->id)) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @method('DELETE')
                                    <a href="{{ url('admin/kategori/' . Crypt::encrypt($category->id)) }}"
                                        class="btn btn-sm btn-success btn-rounded">Edit</a>
                                    <a onclick="event.preventDefault(); document.getElementById('form-hapus-kategori-admin-{{ $category->id }}').submit();"
                                        class="btn btn-sm btn-danger btn-rounded">Hapus</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada kategori</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="ps-pagination">
            {{ $categories->links() }}
        </div>
    </div>

    <!-- Creates the bootstrap modal where the image will appear -->
    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="" id="imagepreview" style="width: 100%; height: 300px;object-fit:contain;">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('customScript')
    <script>
        $(document).ready(function() {
            let length = "{{ $categories->count() }}"
            for (let i = 1; i <= length; i++) {
                $('#pop-image-' + i).on("click", function() {
                    console.log('hhh')
                    $('#imagepreview').attr('src', $(this).attr(
                        'data-image'
                    )); // here asign the image to the modal when the user click the enlarge link
                    $('#imagemodal').modal(
                        'show'
                    ); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
                });
            }
        });
    </script>
@endpush
