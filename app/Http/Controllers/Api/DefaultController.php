<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\{DB, Input, URL, Validator};
use Illuminate\Http\Request;
use App\Utils\RPC;
use App\Models\{AppVersion, Bank, FalseData, Market, Setting, HistoricalData, Users};
use App\DAO\UploaderDAO;

class DefaultController extends Controller
{
    public function falseData()
    {
        $limit = Input::get('limit', '12');
        $page = Input::get('page', '1');

        $old = date("Y-m-d", strtotime("-1 day"));
        $old_time = strtotime($old);
        $time = strtotime(date("Y-m-d"));

        $yesterday = FalseData::where('time', ">", $old_time)->where("time", "<", $time)->sum('price');
        $today = FalseData::where('time', ">", $time)->sum('price');

        $data = FalseData::orderBy('id', 'DESC')->paginate($limit);

        return $this->success(array(
            "data" => $data->items(),
            "limit" => $limit,
            "page" => $page,
            "yesterday" => $yesterday,
            "today" => $today,
        ));
    }

    public function quotation()
    {
        $result = Market::limit(20)->get();
        return $this->success(
            array(
                "coin_list" => $result
            )
        );
    }

    public function historicalData()
    {
        $day = HistoricalData::where("type", "day")->orderBy('id', 'asc')->get();
        $week = HistoricalData::where("type", "week")->orderBy('id', 'asc')->get();
        $month = HistoricalData::where("type", "month")->orderBy('id', 'asc')->get();

        return $this->success(
            array(
                "day" => $day,
                "week" => $week,
                "month" => $month
            )
        );
    }

    public function quotationInfo()
    {
        $id = Input::get("id");
        if (empty($id)) {
            return $this->error("参数错误");
        }
        //$coin_list = RPC::apihttp("https://api.coinmarketcap.com/v2/ticker/".$id."/");
        $coin_list = Market::find($id);
        //$coin_list = @json_decode($coin_list,true);
        return $this->success($coin_list);
    }

    public function dataGraph()
    {
        $data = Setting::getValueByKey("chart_data");
        if (empty($data)) return $this->error("暂无数据");

        $data = json_decode($data, true);
        return $this->success(
            array(
                "data" => array(
                    $data["time_one"], $data["time_two"], $data["time_three"], $data["time_four"], $data["time_five"], $data["time_six"], $data["time_seven"]
                ),
                "value" => array(
                    $data["price_one"], $data["price_two"], $data["price_three"], $data["price_four"], $data["price_five"], $data["price_six"], $data["price_seven"]
                ),
                "all_data" => $data
            )
        );
    }

    public function index()
    {
        $coin_list = RPC::apihttp("https://api.coinmarketcap.com/v2/ticker?limit=10");
        $coin_list = @json_decode($coin_list, true);
        if (!empty($coin_list["data"])) {
            foreach ($coin_list["data"] as &$d) {
                if ($d["total_supply"] > 10000) {
                    $d["total_supply"] = substr($d["total_supply"], 0, -4) . "万";
                }
            }
        }
        return $this->success(
            array(
                "coin_list" => $coin_list["data"]
            )
        );
    }

    public function upload(Request $request)
    {
        $file = $request->file('file');
        $scene = $request->input('scene', ''); //场景,子文件夹
        if (!$file) {
            return $this->error('文件不存在');
        }
        //文件类型验证
        $validator = Validator::make($request->all(), [
            'file' => 'required|image',
        ], [], [
            'file' => '上传附件',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        $result = UploaderDAO::fileUpload($file, $scene);
        if ($result['state'] != 'SUCCESS') {
            return $this->error($result['state']);
        }
        return $this->success($result['url']);
    }

    public function getNode(\Illuminate\Http\Request $request)
    {
        $user_id = $request->get('user_id', 0);
        $show_message["real_teamnumber"] = Users::find($user_id)->real_teamnumber;
        $show_message["top_upnumber"] = Users::find($user_id)->top_upnumber;
        $account_number = $request->get('account_number', null);
        if (!empty($account_number)) {
            $user_id_search = Users::where('account_number', $account_number)->first();
            if (!empty($user_id_search)) {
                $user_id = $user_id_search->id;
            } else {
                $user_id = 0;
            }
        }
        //if (empty($user_id)){
        $users = Users::where('parent_id', $user_id)->get();
        $results = array();
        foreach ($users as $key => $user) {
            $results[$key]['name'] = $user->account_number;
            $results[$key]['id'] = $user->id;
            $results[$key]['parent_id'] = $user->parent_id;
            // $results[$key]['children'] = array();
            // if (!empty($user->left)){
            //     $push_left = Users::find($user->left);
            //     $arr = array('name'=>$push_left->account_number,'id'=>$push_left->id);
            //     array_push($results[$key]['children'],$arr);
            // }
            // if (!empty($user->center)){
            //     $push_center = Users::find($user->center);
            //     $arr = array('name'=>$push_center->account_number,'id'=>$push_center->id);
            //     array_push($results[$key]['children'],$arr);
            // }
            // if (!empty($user->right)){
            //     $push_right = Users::find($user->right);
            //     $arr = array('name'=>$push_right->account_number,'id'=>$push_right->id);
            //     array_push($results[$key]['children'],$arr);
            // }
        }
        //}
        //else{
        //     $results = array();
        //     $user = Users::find($user_id);
        //     $results['name'] = $user->account_number;
        //     $results['id'] = $user->id;
        //     $results['children'] = array();
        //     $results = array();
        //     if (!empty($user->parent_id)){
        //         $push_parent = Users::find($user->parent_id);
        //         if(!empty($push_parent)){
        //             $arr = array('name'=>$push_parent->account_number,'id'=>$push_parent->id);
        //             array_push($results,$arr);
        //         }

        //     }
        //     if (!empty($user->center)){
        //         $push_center = Users::find($user->center);
        //         if(!empty($push_center)){
        //             $arr = array('name'=>$push_center->account_number,'id'=>$push_center->id);
        //             array_push($results,$arr);
        //         }

        //     }
        //     if (!empty($user->right)){
        //         $push_right = Users::find($user->right);
        //         if(!empty($push_right)){
        //             $arr = array('name'=>$push_right->account_number,'id'=>$push_right->id);
        //             array_push($results,$arr);
        //         }

        //     }
        // }
        $data["show_message"] = $show_message;
        $data["results"] = $results;
        return $this->success($data);
    }

    public function getVersion()
    {
        $version = Setting::getValueByKey('version', '1.0');
        return $this->success($version);
    }

    public function getBanks()
    {
        $result = Bank::all();
        return $this->success($result);
    }

    public function checkUpdate(Request $request)
    {
        $name = $request->input('name', '');
        $version = $request->input('version', '');
        $os = strtolower($request->input('os', 'android') ?? 'android');
        $type = $os == 'android' ? 1 : 2;
        try {
            $app_version = AppVersion::where('type', $type)
                ->orderBy('version_num', 'desc')
                ->firstOrFail();
            if (version_compare($app_version->version_name, $version) > 0) {
                list($main_version) = explode('.', $version);
                list($app_main_version) = explode('.', $app_version->version_name);
                $main_version = intval($main_version);
                $app_main_version = intval($app_main_version);
                if ($app_main_version > $main_version) {
                    $pkg_url = $app_version->pkg_url;
                    $wgt_url = '';
                } else {
                    $pkg_url = '';
                    $wgt_url = $app_version->wgt_url;   
                }
                return [
                    'code' => 0,
                    'msg' => $os . '发现新版本',
                    'data' => [
                        'update' => true,
                        'wgtUrl' => $wgt_url,
                        'pkgUrl' => $pkg_url,
                        'downUrl' => $app_version->down_url,
                    ],
                ];
            } else {
                throw new \Exception('您的App已经是最新版本');
            } 
        } catch (\Throwable $th) {
            return [
                'code' => 0,
                'msg' => $th->getMessage(),
                'data' => [
                    'update' => false,
                    'wgtUrl' => '',
                    'pkgUrl' => '',
                    'downUrl' => $app_version->down_url ?? '',
                ],
            ];
        }
    }

    public function base64ImageUpload(Request $request)
    {
        $base64_image_content = $request->input('base64_file', '');
        $res = self::base64ImageContent($base64_image_content);
        if (!$res) {
            return $this->error('上传失败');
        }
        return $this->success($res);
    }

    public static function base64ImageContent($base64_image_content)
    {
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            $path = '/upload/' . date('Ymd') . '/';
            $new_file  = public_path() . $path;
            if (!file_exists($new_file)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $filename = time() . rand(0, 999999) . ".{$type}";
            $full_file = $new_file . $filename;
            if (file_put_contents($full_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                return $path . $filename;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }    
}
