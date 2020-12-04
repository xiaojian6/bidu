<?php

namespace App\Http\Controllers\Admin;

use App\Models\AppVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppVersionController extends Controller
{
    //
    public function index()
    {
        return view('admin.app_version.index');
    }

    public function lists(Request $request)
    {
        $limit = $request->get('limit', 10);
//        $account_number = $request->get('account_number','');
        $result = new AppVersion();
        $result = $result->orderBy('version_num', 'desc')->paginate($limit);
        return $this->layuiData($result);
    }

    public function add(Request $request)
    {
        $id = $request->get('id', 0);
        if (empty($id)) {
            $result = new AppVersion();
        } else {
            $result = AppVersion::find($id);
        }
        return view('admin.app_version.add')->with('result', $result);
    }

    public function postAdd(Request $request)
    {
        $id = $request->get('id', 0);
        $data = $request->except('id');

        try {
            DB::beginTransaction();
            AppVersion::unguard();
            AppVersion::updateOrCreate(
                ['id' => $id],
                $data
            );
            AppVersion::reguard();
            DB::commit();
            return $this->success('操作成功');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->error($exception->getMessage());
        }
    }

    public function delete(Request $request)
    {
        $id = $request->get('id', 0);
        $acceptor = AppVersion::find($id);
        if (empty($acceptor)) {
            return $this->error('无此记录');
        }
        try {
            $acceptor->delete();
            return $this->success('删除成功');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }
}
