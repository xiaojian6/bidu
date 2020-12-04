<?php
namespace App\DAO;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\{Users, PrizePool, Setting};
use App\Events\RealNameEvent;

class UserDAO
{
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
                    if ($valuetwo['parent_id'] == $valueone && $valuetwo['is_realname'] == 2) //实名认证通过的团队人数
                    {
                        $Teams[] = $valuetwo['id'];//找到我的下级立即添加到最终结果中
                        $othermids[] = $valuetwo['id'];//将我的下级id保存起来用来下轮循环他的下级
                        //                        array_splice($members,$key,1);//从所有会员中删除他
                        $state = true;
                    }
                }
            }
            $mids = $othermids;//foreach中找到的我的下级集合,用来下次循环
        } while ($state == true);
        $Teams = Users::whereIn("id", $Teams)->where("is_realname", "=", 2)->count();

        return $Teams;
    }

    /**
     * 查询用户的指定代数的上级(根据parents_path信息)
     *
     * @param App\Users $user 用户模型实例
     * @param integer $qty 要取的上级代数,不传或传null则取全部
     * @return array 返回包含上级id的数组
     */
    public static function getParentsPathDesc($user, $qty = null)
    {
        $path = $user->parents_path;
        if ($path == null || empty($path)) {
            return [];
        }
        $parents = explode(',', $path);
        $parents = array_filter($parents);
        krsort($parents);
        $parents = array_slice($parents, 0, $qty);
        return $parents;
    }

    /**
     * 递归查询上级
     *
     * @param App\Users $user 用户模型实例
     * @return array
     */
    public static function getRealParents($user)
    {
        $found_parent_node = [];
        $parents = self::findParent($user, $found_parent_node);
        return $parents;
    }

    /**
     * 递归查询上级(字符串)
     *
     * @param App\Users $user 用户模型实例
     * @return string 返回逗号间隔的path
     */
    public static function getRealParentsPath($user)
    {
        $parents = self::getRealParents($user);
        if (count($parents) > 0) {
            return implode(',', $parents);
        }
        return '';
    }

    private static function findParent($user, &$found_parent_node)
    {
        $parent_id = $user->parent_id;

        if ($parent_id) {
            //检测节点关系是否有死循环
            if (in_array($parent_id, $found_parent_node)) {
                $context = [
                    'user_id' => $user->id,
                    'parent_id' => $parent_id,
                    'found_parent_node' => $found_parent_node,
                ];
                //记录错误日志
                // Log::useDailyFiles(base_path('storage/logs/user/'), 7);
                // Log::critical('id:' . $user->id . '的用户,上级关系存在死循环', $context);
                return [];
            }
            array_unshift($found_parent_node, $parent_id);
            $parent = Users::find($parent_id);
            $result = self::findParent($parent, $found_parent_node);
            unset($parent);
            array_push($result, $parent_id);
            return $result;
        } else {
            return [];
        }
    }

}
