<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{

    private function complete_percentage($model, $table_name, $resource)
    {
        $pos_info =  DB::select(DB::raw("SHOW COLUMNS FROM " . $table_name . " WHERE FIELD != 'image'"));
        $base_columns = count($pos_info) - 3;
        $not_null = -3;
        foreach ($pos_info as $col) {
            $not_null += app('App\\' . $model)::selectRaw('SUM(CASE WHEN ' . $col->Field . ' IS NOT NULL THEN 1 ELSE 0 END) AS not_null')->where('id', '=', $resource->id)->first()->not_null;
        }

        $percent = round(($not_null / $base_columns) * 100);
        $null = $base_columns - $not_null;
        return [
            'percent' => $percent,
            'null' => $null,
            'base_columns' => $base_columns
        ];
    }

    public function index()
    {
        $auth = Auth::user();
        $percentage = $this->complete_percentage('Models\\User', 'users', $auth);
        return view('profile.index', compact('auth', 'percentage'));
    }

    public function updateUser(Request $request)
    {
        $auth = Auth::user();
        $user = User::find($auth->id);
        if (!$request->password) {
            unset($request['password']);
        } else {
            $request['password'] = bcrypt($request['password']);
        }
        $user->update($request->all());
        return redirect()->back()->with('success', 'Berhasil mengubah profile');
    }

    public function updatePhotoProfile(Request $request)
    {
        $auth = Auth::user();
        $user = User::find($auth->id);
        //create upload function
        $image = $request->file('image');
        if ($image) {
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/img/pp/');
            $image->move($destinationPath, $name);
            $msg = 'Berhasil mengubah foto profile';
        } else {
            File::delete(public_path('/img/pp/' . $user->image));
            $name = '';
            $msg = 'Berhasil menghapus foto profile';
        }
        $user->image = $name;
        $user->save();
        return redirect()->back()->with('success', $msg);
    }
}
