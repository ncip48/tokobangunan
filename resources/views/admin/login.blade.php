<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@php
    $kategori = \App\Models\Kategori::all();
    $kategori = $kategori->map(function ($cat) {
        $cat['merk'] = \App\Models\Merk::where('id_kategori', $cat->id)->get();
        return $cat;
    });
@endphp

@auth
    @php
        $favorite = \App\Models\Favorite::where('id_user', Auth::user()->id)->count();
        $cart = \App\Models\Keranjang::where('id_user', Auth::user()->id)->sum('qty');
        $carts = \App\Models\Keranjang::select('keranjang.*', 'produk.*', 'toko.nama_toko as nama_toko')
            ->where('keranjang.id_user', Auth::user()->id)
            ->join('produk', 'keranjang.id_produk', 'produk.id')
            ->join('toko', 'produk.id_toko', 'toko.id')
            ->get();
        $cartNum = \App\Models\Keranjang::where('keranjang.id_user', Auth::user()->id)
            ->join('produk', 'keranjang.id_produk', 'produk.id')
            ->get();
        $cartNum = $cartNum->map(function ($crt) {
            $produk = \App\Models\Produk::where('id', $crt->id_produk)->first();
            $crt['total'] = $produk->harga_produk * $crt->qty;
            return $crt;
        });
    @endphp
@endauth

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - Login</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700&amp;amp;subset=latin-ext"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/demo.css') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/nouislider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/lightgallery.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/fontawesome-stars.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/autopart.css') }}">

    <script src="{{ mix('js/app.js') }}" defer></script>
</head>

<body>
    <div class="ps-page--my-account">
        <div class="ps-my-account">
            <div class="container">
                <form class="ps-form--account ps-tab-root pt-5 pb-5" method="POST"
                    action="{{ route('admin-login-action') }}">
                    @csrf
                    <ul class="ps-tab-list">
                        <li class="active"><a href="#sign-in">Login Admin</a></li>
                    </ul>
                    <div class="ps-tabs">
                        <div class="ps-tab active" id="sign-in">
                            <div class="ps-form__content">
                                <h5>Log In Your Account</h5>
                                <div class="form-group">
                                    <input id="email" type="email" placeholder="Email Address"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group form-forgot">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password" placeholder="Password"><a
                                        href="">Lupa?</a>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <input type="hidden" name="url" value="{{ URL::previous() }}">
                                <div class="form-group">
                                    <div class="ps-checkbox">
                                        <input class="form-control" type="checkbox" id="remember-me"
                                            name="remember-me" />
                                        <label for="remember-me">Rememeber me</label>
                                    </div>
                                </div>
                                <div class="form-group submtit">
                                    <button type="submit" class="ps-btn ps-btn--fullwidth">Login</button>
                                </div>
                            </div>
                            <div class="ps-form__footer">
                                <p>Connect with:</p>
                                <ul class="ps-list--social">
                                    <li><a class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                                    <li><a class="google" href="#"><i class="fa fa-google-plus"></i></a></li>
                                    <li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
                                    <li><a class="instagram" href="#"><i class="fa fa-instagram"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="ps-tab" id="register">
                            <div class="ps-form__content">
                                <h5>Register An Account</h5>
                                <div class="form-group">
                                    <input class="form-control" type="text" placeholder="Username or email address">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="text" placeholder="Password">
                                </div>
                                <div class="form-group submtit">
                                    <button class="ps-btn ps-btn--fullwidth">Login</button>
                                </div>
                            </div>
                            <div class="ps-form__footer">
                                <p>Connect with:</p>
                                <ul class="ps-list--social">
                                    <li><a class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                                    <li><a class="google" href="#"><i class="fa fa-google-plus"></i></a></li>
                                    <li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
                                    <li><a class="instagram" href="#"><i class="fa fa-instagram"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
    {{-- <script src="{{ asset('/js/popper.min.js') }}"></script> --}}
    <script src="{{ asset('/js/nouislider.min.js') }}"></script>
    <script src="{{ asset('/js/owl.carousel.min.js') }}"></script>
    {{-- <script src="{{ asset('/js/bootstrap.min.js') }}"></script> --}}
    <script src="{{ asset('/js/slick.min.js') }}"></script>
    <script src="{{ asset('/js/slick.min.js') }}"></script>
    <script src="{{ asset('/js/jquery.barrating.min.js') }}"></script>
    <script src="{{ asset('/js/lightgallery-all.min.js') }}"></script>
    <script src="{{ asset('/js/sticky-sidebar.min.js') }}"></script>
    <script src="{{ asset('/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/js/main.js') }}"></script>
    <script src="{{ asset('js/cryptojs-aes.min.js') }}"></script>
    <script src="{{ asset('js/cryptojs-aes-format.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#btn-search").click(function() {
                var query = $("#query-search").val();
                if (!query) {
                    $("#box-search").stop().animate({
                        opacity: 0
                    }, 1);
                    return
                }
                let html = '';
                $.ajax({
                    url: "{{ route('api.search') }}",
                    method: "POST",
                    data: {
                        search: query
                    },
                    success: function(data) {
                        // console.log(data)
                        if (data.success) {
                            let toko = data.stores;
                            let produk = data.products;
                            if (toko.length == 0 && produk.length == 0) {
                                html +=
                                    '<div class="ps-cart-item"><p>Tidak ada hasil</p></div>';
                            }
                            if (toko.length > 0) {
                                html +=
                                    '<div class="ps-block--shop-features"><div class="ps-block__header justify-content-start align-items-center"><i class="mb-3 fa fa-shopping-basket" aria-hidden="true"></i>';
                                html +=
                                    '<h5 class="mb-3 ml-2">Toko </h5></div><div class="ps-block__content" ><ul class = "ps-list--link" >';
                                toko.forEach(function(item) {
                                    html += '<li><a href="/toko/' + item.prefix + '">' +
                                        item.nama_toko + '</a></li>';
                                });
                                html += '</ul></div></div>';
                            }
                            if (produk.length > 0) {
                                html +=
                                    '<div class="ps-block--shop-features"><div class="ps-block__header justify-content-start align-items-center"><i class="mb-3 fa fa-shopping-bag" aria-hidden="true"></i>';
                                html +=
                                    '<h5 class="mb-3 ml-2">Produk </h5></div><div class="ps-block__content" ><ul class = "ps-list--link" >';
                                produk.forEach(function(item) {
                                    html += '<li><a href="/produk/' + item.prefix +
                                        '">' +
                                        item.nama_produk + '</a></li>';
                                });
                                html += '</ul></div></div>';
                            }
                            $("#box-search").html(html);
                        }
                        // $("#box-search").html(data);
                        $("#box-search").stop().animate({
                            opacity: 1
                        }, 1);
                    }
                });
            });
        });

        const dropdown_menu = document.querySelector('#box-search');
        const dropdown_li = document.querySelector('#btn-search');

        dropdown_li.addEventListener('click', () => {
            document.addEventListener('mouseup', function(e) {
                if (!dropdown_menu.contains(e.target)) {
                    dropdown_menu.style.opacity = 0;
                }
            });

        });
    </script>

    @stack('customScript')
</body>

</html>
