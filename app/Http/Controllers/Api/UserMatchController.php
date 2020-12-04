<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\UserMatch;

class UserMatchController extends Controller
{
    public function lists(Request $request)
    {
        $user_id = Users::getUserId();
        $limit = 50;
        $user_matches = UserMatch::where('user_id', $user_id)
            ->paginate($limit);
        return $this->success($user_matches);
    }

    public function add(Request $request)
    {
        $user_id = Users::getUserId();
        $currency_match_id = $request->input('id', 0);
        if ($currency_match_id <= 0) {
            return $this->error('交易对ID错误');
        }
        try {
            $user_match = UserMatch::where('user_id', $user_id)
                ->where('currency_match_id', $currency_match_id)
                ->first();
            if ($user_match) {
                return $this->error('交易对已添加过自选,请不要重复添加');
            }
            UserMatch::unguard();
            $user_match = UserMatch::create([
                'user_id' => $user_id,
                'currency_match_id' => $currency_match_id,
            ]);
            if (!isset($user_match->id)) {
                throw new \Exception('添加自选失败');
            }
            return $this->success('添加自选成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        } finally {
            UserMatch::reguard();
        }
    }

    public function del(Request $request)
    {
        $user_id = Users::getUserId();
        $id = $request->input('id', 0);
        $user_match = UserMatch::where('user_id', $user_id)
            ->where('currency_match_id', $id)
            ->first();
        if (!$user_match) {
            return $this->error('错误:指定自选交易对不存在');
        }
        $result = $user_match->delete();
        return $result > 0 ? $this->success('删除自选交易对成功') : $this->error('删除自选交易对失败');
    }
}
