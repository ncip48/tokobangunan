<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Merk;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function loginAction()
    {
        $credentials = $this->validate(request(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Email tidak valid',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password kurang dari 8 karakter',
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->intended('admin');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin-login');
    }

    public function index()
    {
        return view('admin.dashboard');
    }

    public function kategori(Request $request)
    {
        $categories = Kategori::when($request->search, function ($query) use ($request) {
            $query->where('nama_kategori', 'like', "%{$request->search}%");
        })->paginate(10);
        return view('admin.kategori.index', compact('categories'));
    }

    public function tambahKategori()
    {
        return view('admin.kategori.tambah');
    }

    public function tambahKategoriAction(Request $request)
    {
        $this->validate($request, [
            'nama_kategori' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'nama_kategori.required' => 'Nama kategori tidak boleh kosong',
            'image.required' => 'Gambar tidak boleh kosong',
            'image.image' => 'File yang diupload bukan gambar',
            'image.mimes' => 'Gambar harus berformat jpeg, png, jpg, gif, svg',
            'image.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $image = $request->file('image');
        $new_name = rand() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('img'), $new_name);

        $prefix = strtolower(str_replace(' ', '-', $request->nama_kategori));

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'prefix' => $prefix,
            'image' => $new_name,
        ]);

        return redirect()->route('admin-kategori')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function editKategori($id)
    {
        $id = Crypt::decrypt($id);
        $category = Kategori::find($id);
        return view('admin.kategori.edit', compact('category'));
    }

    public function editKategoriAction(Request $request)
    {
        $id = $request->id;
        $this->validate($request, [
            'nama_kategori' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'nama_kategori.required' => 'Nama kategori tidak boleh kosong',
            'image.image' => 'File yang diupload bukan gambar',
            'image.mimes' => 'Gambar harus berformat jpeg, png, jpg, gif, svg',
            'image.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $kategori = Kategori::find($id);

        $image = $request->file('image');
        if ($image) {
            File::delete(public_path('img/' . $kategori->image));
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img'), $new_name);
            $kategori->image = $new_name;
        }

        $prefix = strtolower(str_replace(' ', '-', $request->nama_kategori));

        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->prefix = $prefix;
        $kategori->save();

        return redirect()->route('admin-kategori')->with('success', 'Kategori berhasil diubah');
    }

    public function hapusKategori($id)
    {
        $id = Crypt::decrypt($id);
        Kategori::destroy($id);

        return redirect()->route('admin-kategori')->with('success', 'Kategori berhasil dihapus');
    }

    public function merk(Request $request)
    {
        $merks = Merk::when($request->search, function ($query) use ($request) {
            $query->where('merk.nama_merk', 'like', "%{$request->search}%");
        })
            ->join('kategori', 'kategori.id', '=', 'merk.id_kategori')
            ->select('merk.*', 'kategori.nama_kategori')
            ->paginate(10);
        return view('admin.merk.index', compact('merks'));
    }

    public function tambahMerk()
    {
        $categories = Kategori::all();
        return view('admin.merk.tambah', compact('categories'));
    }

    public function tambahMerkAction(Request $request)
    {
        $this->validate($request, [
            'nama_merk' => 'required',
            'id_kategori' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'nama_merk.required' => 'Nama merk tidak boleh kosong',
            'id_kategori.required' => 'Kategori tidak boleh kosong',
            'image.required' => 'Gambar tidak boleh kosong',
            'image.image' => 'File yang diupload bukan gambar',
            'image.mimes' => 'Gambar harus berformat jpeg, png, jpg, gif, svg',
            'image.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $image = $request->file('image');
        $new_name = rand() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('img/merk/'), $new_name);

        $prefix = strtolower(str_replace(' ', '-', $request->nama_merk));

        Merk::create([
            'nama_merk' => $request->nama_merk,
            'id_kategori' => $request->id_kategori,
            'prefix' => $prefix,
            'image' => $new_name,
        ]);

        return redirect()->route('admin-merk')->with('success', 'Merk berhasil ditambahkan');
    }

    public function editMerk($id)
    {
        $id = Crypt::decrypt($id);
        $merk = Merk::find($id);
        $categories = Kategori::all();
        return view('admin.merk.edit', compact('merk', 'categories'));
    }

    public function editMerkAction(Request $request)
    {
        $id = $request->id;
        $this->validate($request, [
            'nama_merk' => 'required',
            'id_kategori' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'nama_merk.required' => 'Nama merk tidak boleh kosong',
            'id_kategori.required' => 'Kategori tidak boleh kosong',
            'image.image' => 'File yang diupload bukan gambar',
            'image.mimes' => 'Gambar harus berformat jpeg, png, jpg, gif, svg',
            'image.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $merk = Merk::find($id);

        $image = $request->file('image');
        if ($image) {
            File::delete(public_path('img/merk/' . $merk->image));
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img/merk/'), $new_name);
            $merk->image = $new_name;
        }

        $prefix = strtolower(str_replace(' ', '-', $request->nama_merk));

        $merk->nama_merk = $request->nama_merk;
        $merk->id_kategori = $request->id_kategori;
        $merk->prefix = $prefix;
        $merk->save();

        return redirect()->route('admin-merk')->with('success', 'Merk berhasil diubah');
    }

    public function hapusMerk($id)
    {
        $id = Crypt::decrypt($id);
        Merk::destroy($id);

        return redirect()->route('admin-merk')->with('success', 'Merk berhasil dihapus');
    }

    public function editWebsite()
    {
        $site = Site::first();
        return view('admin.website', compact('site'));
    }

    public function editWebsiteAction(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_seller' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_admin' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'facebook' => 'required',
            'instagram' => 'required',
            'twitter' => 'required',
            'description' => 'required',
            'about' => 'required',
        ], [
            'name.required' => 'Nama tidak boleh kosong',
            'email.required' => 'Email tidak boleh kosong',
            'phone.required' => 'Nomor telepon tidak boleh kosong',
            'address.required' => 'Alamat tidak boleh kosong',
            'logo.image' => 'File yang diupload bukan gambar',
            'logo.mimes' => 'Gambar harus berformat jpeg, png, jpg, gif, svg',
            'logo.max' => 'Ukuran gambar maksimal 2MB',
            'logo_seller.image' => 'File yang diupload bukan gambar',
            'logo_seller.mimes' => 'Gambar harus berformat jpeg, png, jpg, gif, svg',
            'logo_seller.max' => 'Ukuran gambar maksimal 2MB',
            'logo_admin.image' => 'File yang diupload bukan gambar',
            'logo_admin.mimes' => 'Gambar harus berformat jpeg, png, jpg, gif, svg',
            'logo_admin.max' => 'Ukuran gambar maksimal 2MB',
            'favicon.image' => 'File yang diupload bukan gambar',
            'favicon.mimes' => 'Gambar harus berformat jpeg, png, jpg, gif, svg',
            'favicon.max' => 'Ukuran gambar maksimal 2MB',
            'facebook.required' => 'Link Facebook tidak boleh kosong',
            'instagram.required' => 'Link Instagram tidak boleh kosong',
            'twitter.required' => 'Link Twitter tidak boleh kosong',
            'description.required' => 'Deskripsi tidak boleh kosong',
            'about.required' => 'Tentang tidak boleh kosong',
        ]);

        $site = Site::first();

        $logo = $request->file('logo');
        if ($logo) {
            File::delete(public_path('img/' . $site->logo));
            $new_name = rand() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('img/'), $new_name);
            $site->logo = $new_name;
        }

        $logo_seller = $request->file('logo_seller');
        if ($logo_seller) {
            File::delete(public_path('img/' . $site->logo_seller));
            $new_name = rand() . '.' . $logo_seller->getClientOriginalExtension();
            $logo_seller->move(public_path('img/'), $new_name);
            $site->logo_seller = $new_name;
        }

        $logo_admin = $request->file('logo_admin');
        if ($logo_admin) {
            File::delete(public_path('img/' . $site->logo_admin));
            $new_name = rand() . '.' . $logo_admin->getClientOriginalExtension();
            $logo_admin->move(public_path('img/'), $new_name);
            $site->logo_admin = $new_name;
        }

        $favicon = $request->file('favicon');
        if ($favicon) {
            File::delete(public_path('img/' . $site->favicon));
            $new_name = rand() . '.' . $favicon->getClientOriginalExtension();
            $favicon->move(public_path('img/'), $new_name);
            $site->favicon = $new_name;
        }

        $site->name = $request->name;
        $site->address = $request->address;
        $site->phone = $request->phone;
        $site->email = $request->email;
        $site->description = $request->description;
        $site->facebook = $request->facebook;
        $site->instagram = $request->instagram;
        $site->twitter = $request->twitter;
        $site->whatsapp = $request->whatsapp;
        $site->about = $request->about;
        $site->save();

        return redirect()->back()->with('success', 'Website berhasil diubah');
    }
}
