<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\{Address, AccountLog, Users, UserCashInfo, UserReal, UsersWallet};
use Carbon\Carbon;

class GoodUserController extends Controller
{
    public function index()
    {
        return view('admin.good_user.index');
    }

    public function data(){

        $today = Carbon::now()->toDateString();

        $data = array();
        $data['total'] = DB::table('users')->count();
        $data['today'] = DB::table('users')->whereDate('created_at', $today);

        dd($data);
    }

}
