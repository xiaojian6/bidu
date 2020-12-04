<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Request;
use App\DAO\UserDAO;
use App\Models\PrizePool;
use App\Models\Setting;
use App\Models\Users;
use App\Models\UserReal;
use App\Utils\IdCardIdentity;
use App\Events\RealNameEvent;

class UserRealController extends Controller
{
    public function index()
    {
        return view("admin.userReal.index");
    }

    //用户列表
    public function list(Request $request)
    {
        $limit = $request->get('limit', 10);
        $account = $request->get('account', '');

        $list = new UserReal();
        if (!empty($account)) {
            $list = $list->whereHas('user', function ($query) use ($account) {
                $query->where("phone", 'like', '%' . $account . '%')->orwhere('email', 'like', '%' . $account . '%');
            });
        }

        $list = $list->orderBy('id', 'desc')->paginate($limit);
        return response()->json(['code' => 0, 'data' => $list->items(), 'count' => $list->total()]);
    }

    public function detail(Request $request)
    {
        $id = $request->get('id', 0);
        if (empty($id)) {
            return $this->error("参数错误");
        }
        $result = UserReal::find($id);
        return view('admin.userReal.info', ['result' => $result]);
    }

    public function del(Request $request)
    {
        $id = $request->get('id');
        $userreal = UserReal::find($id);
        if (empty($userreal)) {
            $this->error("认证信息未找到");
        }
        try {

            $userreal->delete();
            return $this->success('删除成功');
        } catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }
 
    //状态审核
    public function auth(Request $request)
    {
        $id = $request->get('id', 0);
        $userreal = UserReal::find($id);
        if (empty($userreal)) {
            return $this->error('参数错误');
        }
        if ($userreal->review_status == 1) {
            //查询users表判断是否为第一次实名认证
            $user = Users::find($userreal->user_id);
            $is_realname = $user->is_realname;
            if ($is_realname == 1) {
                //1:未实名认证过  2：实名认证过
                $real_zhitui = Users::where("is_realname", 2)->where("parent_id", $userreal->user_id)->count();//实名认证过的有效直推人数
                //获取下级的总人数
                $member = Users::get()->toArray();
                $real_teamnumber = $this->GetTeamMember($member, $userreal->user_id);//实名认证过的团队人数
                $user->real_teamnumber = $real_teamnumber;
                $user->is_realname = 2;
                $user->save();//自己实名认证获取通证结束

            }
            $userreal->review_status = 2;
        } elseif ($userreal->review_status == 2) {
            $userreal->review_status = 1;
        } else {
            $userreal->review_status = 1;
        }
        try {
            $userreal->save();
            //用户实名事件
            //event(new RealNameEvent($user));
            return $this->success('操作成功');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }

    /*
    public function auth(Request $request)
    {
        $id = $request->get('id', 0);
        $userreal = UserReal::find($id);
        if (empty($userreal)) {
            return $this->error('参数错误');
        }
        $user = Users::find($userreal->user_id);
        if (!$user) {
            return $this->error('用户不存在');
        }
        if ($userreal->review_status == 1) {
            //从未认证到认证
            //查询users表判断是否为第一次实名认证
            $is_realname = $user->is_realname;
            if ($is_realname != 2) {
                //1:未实名认证过  2：实名认证过
                //获取setting值
                $setting = new Setting();
                $real_name_candy = $setting::getValueByKey('real_name_candy', '');
                $zhitui2_number = $setting::getValueByKey('zhitui2_number', '');
                $zhitui2_candy = $setting::getValueByKey('zhitui2_candy', '');
                $zhitui3_number = $setting::getValueByKey('zhitui3_number', '');
                $zhitui3_real_teamnumber = $setting::getValueByKey('zhitui3_real_teamnumber', '');
                $zhitui3_top_upnumber = $setting::getValueByKey('zhitui3_top_upnumber', '');
                $zhitui3_candy = $setting::getValueByKey('zhitui3_candy', '');
                $zhitui4_number = $setting::getValueByKey('zhitui4_number', '');
                $zhitui4_real_teamnumber = $setting::getValueByKey('zhitui4_real_teamnumber', '');
                $zhitui4_top_upnumber = $setting::getValueByKey('zhitui4_top_upnumber', '');
                $zhitui4_candy = $setting::getValueByKey('zhitui4_candy', '');
                $zhitui5_number = $setting::getValueByKey('zhitui5_number', '');
                $zhitui5_real_teamnumber = $setting::getValueByKey('zhitui5_real_teamnumber', '');
                $zhitui5_top_upnumber = $setting::getValueByKey('zhitui5_top_upnumber', '');
                $zhitui5_candy = $setting::getValueByKey('zhitui5_candy', '');
                $zhitui6_number = $setting::getValueByKey('zhitui6_number', '');
                $zhitui6_real_teamnumber = $setting::getValueByKey('zhitui6_real_teamnumber', '');
                $zhitui6_top_upnumber = $setting::getValueByKey('zhitui6_top_upnumber', '');
                $zhitui6_candy = $setting::getValueByKey('zhitui6_candy', '');

                $real_zhitui = Users::where("is_realname", "=", 2)->where("parent_id", "=", $userreal->user_id)->count();//实名认证过的有效直推人数
                //获取下级的总人数
                $member = Users::get()->toArray();
                $real_teamnumber = $this->GetTeamMember($member, $userreal->user_id);//实名认证过的团队人数
                $candy_number = bc_add($user->candy_number, $real_name_candy, 4);
                //$candy_number=$real_name_candy;
                $user->candy_number = $candy_number;
                $user->push_status = 1;
                $top_upnumber = $user->top_upnumber;
                //开始记录日志
                $prize_pool = new PrizePool();
                $prize_pool->scene = PrizePool::CERTIFICATION;//const CERTIFICATION = 1; //实名认证奖励
                $prize_pool->reward_type = PrizePool::REWARD_CANDY;//const REWARD_CANDY = 0; //奖励通证
                $prize_pool->reward_qty = $real_name_candy;
                $prize_pool->from_user_id = $user->id;
                $prize_pool->to_user_id = $user->id;
                $prize_pool->status = 1;
                $prize_pool->memo = $user->account_number . '实名认证通证奖励' . $real_name_candy . '个通证';
                $prize_pool->create_time = time();
                $prize_pool->receive_time = time();
                $prize_pool->save();

                if ($zhitui2_number <= $real_zhitui) {
                    //给自己加通证 +20
                    $user->candy_number = $user->candy_number + $zhitui2_candy;
                    $user->push_status = 2;

                    $prize_pool1 = new PrizePool();
                    $prize_pool1->scene = PrizePool::CERTIFICATION;//const CERTIFICATION = 1; //实名认证奖励
                    $prize_pool1->reward_type = PrizePool::REWARD_CANDY;//const REWARD_CANDY = 0; //奖励通证
                    $prize_pool1->reward_qty = $zhitui2_candy;
                    $prize_pool1->from_user_id = $user->id;
                    $prize_pool1->to_user_id = $user->id;
                    $prize_pool1->status = 1;
                    $prize_pool1->memo = '满足直推' . $zhitui2_number . '人实名送' . $zhitui2_candy . '个通证';
                    $prize_pool1->create_time = time();
                    $prize_pool1->receive_time = time();
                    $prize_pool1->save();

                }
                if ($real_zhitui >= $zhitui3_number && $real_teamnumber >= $zhitui3_real_teamnumber && $top_upnumber >= $zhitui3_top_upnumber) {
                    $user->candy_number = $user->candy_number + $zhitui3_candy;
                    $user->push_status = 3;
                    $prize_pool2 = new PrizePool();
                    $prize_pool2->scene = PrizePool::CERTIFICATION;//const CERTIFICATION = 1; //实名认证奖励
                    $prize_pool2->reward_type = PrizePool::REWARD_CANDY;//const REWARD_CANDY = 0; //奖励通证
                    $prize_pool2->reward_qty = $zhitui3_candy;
                    $prize_pool2->from_user_id = $user->id;
                    $prize_pool2->to_user_id = $user->id;
                    $prize_pool2->status = 1;
                    $prize_pool2->memo = '满足直推' . $zhitui3_number . '人，实名认证团队' . $zhitui3_real_teamnumber . '人充值达' . $zhitui3_top_upnumber . '美金；送' . $zhitui3_candy . '通证';
                    $prize_pool2->create_time = time();
                    $prize_pool2->receive_time = time();
                    $prize_pool2->save();

                }
                if ($real_zhitui >= $zhitui4_number && $real_teamnumber >= $zhitui4_real_teamnumber && $top_upnumber >= $zhitui4_top_upnumber) {
                    $user->candy_number = $user->candy_number + $zhitui4_candy;
                    $user->push_status = 4;

                    $prize_pool3 = new PrizePool();
                    $prize_pool3->scene = PrizePool::CERTIFICATION;//const CERTIFICATION = 1; //实名认证奖励
                    $prize_pool3->reward_type = PrizePool::REWARD_CANDY;//const REWARD_CANDY = 0; //奖励通证
                    $prize_pool3->reward_qty = $zhitui4_candy;
                    $prize_pool3->from_user_id = $user->id;
                    $prize_pool3->to_user_id = $user->id;
                    $prize_pool3->status = 1;
                    $prize_pool3->memo = '满足直推' . $zhitui4_number . '人，实名认证团队' . $zhitui4_real_teamnumber . '人充值达' . $zhitui4_top_upnumber . '美金；送' . $zhitui4_candy . '通证';
                    $prize_pool3->create_time = time();
                    $prize_pool3->receive_time = time();
                    $prize_pool3->save();

                }
                if ($real_zhitui >= $zhitui5_number && $real_teamnumber >= $zhitui5_real_teamnumber && $top_upnumber >= $zhitui5_top_upnumber) {
                    $user->candy_number = $user->candy_number + $zhitui5_candy;
                    $user->push_status = 5;
                    $prize_pool4 = new PrizePool();
                    $prize_pool4->scene = PrizePool::CERTIFICATION;//const CERTIFICATION = 1; //实名认证奖励
                    $prize_pool4->reward_type = PrizePool::REWARD_CANDY;//const REWARD_CANDY = 0; //奖励通证
                    $prize_pool4->reward_qty = $zhitui5_candy;
                    $prize_pool4->from_user_id = $user->id;
                    $prize_pool4->to_user_id = $user->id;
                    $prize_pool4->status = 1;
                    $prize_pool4->memo = '满足直推' . $zhitui5_number . '人，实名认证团队' . $zhitui5_real_teamnumber . '人充值达' . $zhitui5_top_upnumber . '美金；送' . $zhitui5_candy . '通证';
                    $prize_pool4->create_time = time();
                    $prize_pool4->receive_time = time();
                    $prize_pool4->save();
                }
                if ($real_zhitui >= $zhitui6_number && $real_teamnumber >= $zhitui6_real_teamnumber && $top_upnumber >= $zhitui6_top_upnumber) {
                    $user->candy_number = $user->candy_number + $zhitui6_candy;
                    $user->push_status = 6;

                    $prize_pool5 = new PrizePool();
                    $prize_pool5->scene = PrizePool::CERTIFICATION;//const CERTIFICATION = 1; //实名认证奖励
                    $prize_pool5->reward_type = PrizePool::REWARD_CANDY;//const REWARD_CANDY = 0; //奖励通证
                    $prize_pool5->reward_qty = $zhitui6_candy;
                    $prize_pool5->from_user_id = $user->id;
                    $prize_pool5->to_user_id = $user->id;
                    $prize_pool5->status = 1;
                    $prize_pool5->memo = '满足直推' . $zhitui6_number . '人，实名认证团队' . $zhitui6_real_teamnumber . '人充值达' . $zhitui6_top_upnumber . '美金；送' . $zhitui6_candy . '通证';
                    $prize_pool5->create_time = time();
                    $prize_pool5->receive_time = time();
                    $prize_pool5->save();

                }
                $user->real_teamnumber = $real_teamnumber;
                $user->zhitui_real_number = $real_zhitui;
                $user->is_realname = 2;
                $user->save();//自己实名认证获取通证结束
                //判断自己上级的的触发奖励
                UserDAO::addCandyNumber($user);
            }
            $userreal->review_status = 2;
        } else if ($userreal->review_status == 2) {
            $userreal->review_status = 1;
        } else {
            $userreal->review_status = 1;
        }
        try {
            $userreal->save();
            //用户实名事件
            event(new RealNameEvent($user));
            return $this->success('操作成功');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }
     */

    //递归查询用户下级所有人数
    public function GetTeamMember($members, $mid)
    {
        $Teams = array();//最终结果
        $mids = array($mid);//第一次执行时候的用户id
        do {
            $othermids = array();
            $state = false;
            foreach ($mids as $valueone) {
                foreach ($members as $key => $valuetwo) {
                    if ($valuetwo['parent_id'] == $valueone) {
                        //实名认证通过的团队人数
                        $Teams[] = $valuetwo['id'];//找到我的下级立即添加到最终结果中
                        $othermids[] = $valuetwo['id'];//将我的下级id保存起来用来下轮循环他的下级
                        //                        array_splice($members,$key,1);//从所有会员中删除他
                        $state = true;
                    }
                }
            }
            $mids = $othermids;//foreach中找到的我的下级集合,用来下次循环
        } while ($state == true);
        //$Teams=Users::where("parents_path","like","%$mid%")->where("is_realname","=",2)->count();
        $Teams = Users::whereIn("id", $Teams)->where("is_realname", "=", 2)->count();
        return $Teams;
    }
}
