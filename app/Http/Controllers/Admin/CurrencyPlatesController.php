<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CurrencyPlate;

class CurrencyPlatesController extends Controller
{
    public function index()
    {
        return view('admin.currency.plates');
    }

    public function list(Request $request)
    {
        $limit = $request->get('limit', 10);
        $result = CurrencyPlate::orderBy('sorts', 'asc')
            ->orderBy('id', 'desc')
            ->paginate($limit);
        return $this->layuiData($result);
    }

    public function add(Request $request)
    {
        $id = $request->get('id', 0);
        $currency_plate = CurrencyPlate::findOrNew($id);
        return view('admin.currency.plates_add')->with([
            'result' => $currency_plate,
        ]);
    }

    public function postAdd(Request $request)
    {
        $id = $request->get('id', 0);
        $name = $request->get('name', '');
        $sorts = $request->get('sorts', 0);
        if (empty($name)) {
            return $this->error('板块名称必填');
        }
        if ($id > 0) {
            $has = CurrencyPlate::where('name', $name)->where('id', '<>', $id)->first();
        } else {
            $has = CurrencyPlate::where('name', $name)->first();
        }

        if (!empty($has)) {
            return $this->error('此板块已经添加过了');
        }

        try {
            $data = [
                'name' => $name,
                'sorts' => $sorts,
            ];
            CurrencyPlate::unguarded(function () use ($id, $data) {
                CurrencyPlate::updateOrCreate(['id' => $id], $data);
            });
            return $this->success('操作成功');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }


    public function delete(Request $request)
    {
        $id = $request->get('id', 0);
        $currency_plate = CurrencyPlate::find($id);
        if (empty($currency_plate)) {
            return $this->error('无此板块');
        }
        try {
            $currency_plate->delete();
            return $this->success('删除成功');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }

    //显示状态
    public function showStatus(Request $request)
    {
        $id = $request->get('id', 0);
        if (empty($id)) {
            return $this->error('参数错误');
        }
        try {
            $plate = CurrencyPlate::findOrFail($id);
            $plate->status = 1 - $plate->status;
            $plate->save();
            return $this->success('操作成功');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }
}
