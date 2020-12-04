<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Channels\Sms;
use App\Models\SmsProject;

class SmsProjectController extends Controller
{
    //
    public function index()
    {
        return view('admin.sms_project.index');
    }

    public function lists(Request $request)
    {
        $limit = $request->get('limit', 10);
        $list = SmsProject::orderBy('id', 'desc')->paginate($limit);
        $data = $list->getCollection();
        $data->transform(function ($item, $key) {
            $item->append('scene_name');
            $item->append('region_name');
            return $item;
        });
        $list->setCollection($data);
        return response()->json(['code' => 0, 'data' => $list->items(), 'count' => $list->total()]);
    }

    public function send_test()
    {
        $id = request()->get('id', 0);
        if (!$id) abort(405, '非法参数');
        $res = SmsProject::where('id', $id)->first();
        return view('admin.sms_project.send_test', ['res' => $res]);
    }

    public function send_sms()
    {
        $mobile = request('mobile', '');
        $id = request('id', '');
        $code = request('code', '');
        if (!$mobile or !$id or !$code) abort(405, '非法参数');
        $res = SmsProject::where('id', $id)->first();
        $params = [
            'code' => $code
        ];
        $params_json = json_encode($params);
        $sms_engine = new Sms();
        $send_result = $sms_engine->xsend($mobile, $res->project, 86, $params_json);
        if ($send_result === true) {
            return $this->success('发送成功！');
        } else {
            return $this->error($send_result);
        }
    }

    public function add()
    {
        $region_list = SmsProject::enumRegion();
        return view('admin.sms_project.add')->with('region_list', $region_list);
    }

    public function postAdd(Request $request)
    {
        $id = $request->get('id', '');
        $project = $request->get('project', '');
        $scene = $request->get('scene', '');
        $country_code = $request->get('country_code', '');
        $contents = $request->input('contents', '');
        $is_default = $request->input('is_default', 0);
        $status = $request->input('status', 0);
        is_null($contents) && $contents = '';
        $validator = Validator::make($request->all(), [
            'project' => 'required|string|min:1',
            'scene' => 'required|string|min:1',
            'country_code' => 'required|integer|min:1',
            'contents' => 'nullable|string|min:1',
            'is_default' => 'required|integer|in:0,1',
            'status' => 'required|integer|in:0,1',
        ], [], [
            'project' => '短信模版',
            'scene' => '短信场景',
            'country_code' => '区域代码',
            'contents' => '短信内容',
            'is_default' => '是否默认',
            'status' => '状态',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }

        $sms_project = empty($id) ? new SmsProject() : SmsProject::find($id);

        try {
            SmsProject::unguard();
            $result = $sms_project->fill([
                'project' => $project,
                'scene' => $scene,
                'country_code' => $country_code,
                'contents' => $contents,
                'is_default' => $is_default,
                'status' => $status,
            ])->save();
            if (!$result) {
                throw new \Exception('保存失败');
            }
            return $this->success('保存成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        } finally {
            SmsProject::reguard();
        }
    }

    public function edit()
    {
        $id = request()->get('id', null);
        if (empty($id)) {
            return $this->error('参数错误');
        }
        $result = SmsProject::find($id);
        if (empty($result)) {
            return $this->error('无此数据');
        }
        $region_list = SmsProject::enumRegion();
        return view('admin.sms_project.add', [
                'result' => $result,
                'region_list' => $region_list,
            ]);
    }

    public function del(Request $request)
    {
        $id = $request->get('id', '');
        if (empty($id)) {
            return $this->error('参数错误');
        }
        $result = SmsProject::find($id);
        try {
            $result->delete();
            return $this->success('删除成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
