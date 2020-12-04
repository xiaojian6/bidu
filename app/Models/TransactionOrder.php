<?php

namespace App\Models;


use Illuminate\Support\Facades\DB;
use App\Jobs\LeverClose;

class TransactionOrder extends Model
{

    protected $table = 'lever_transaction';
    public $timestamps = false;

    protected $appends = [
        'user_name',
        'agent_level',
        'phone',
        'parent_agent_name',
        'symbol',
        
    ];


    public function getSymbolAttribute()
    {
        $currency_id = $this->getAttribute('currency');
        $legal_id = $this->getAttribute('legal');
        $currency_match = CurrencyMatch::where('currency_id', $currency_id)
            ->where('legal_id', $legal_id)
            ->first();
        return $currency_match ? $currency_match->symbol : '';
    }

    public function getCreateTimeAttribute() {
        $value = $this->attributes['create_time'];

        return date('Y-m-d H:i:s' , $value);
    }


    public function getUpdateTimeAttribute() {
        $value = $this->attributes['update_time'];
        if ($value == 0){
            return '-';
        }else{

            return date('Y-m-d H:i:s' , $value);
        }
    }


    public function getHandleTimeAttribute() {
        $value = $this->attributes['handle_time'];
        if ($value == 0){
            return '-';
        }else{

            return date('Y-m-d H:i:s' , $value);
        }
    }
    public function getCompleteTimeAttribute() {
        $value = $this->attributes['complete_time'];
        if ($value == 0){
            return '-';
        }else{

            return date('Y-m-d H:i:s' , $value);
        }
    }

    public function getPhoneAttribute() {
      
        $user = $this->user()->getResults();
        return $user->phone ?? '';
    }
    public function getUserNameAttribute() {
       $user = $this->user()->getResults();
        return $user->account_number ?? '';
    }

    public function getAgentLevelAttribute() {
        
        $user = $this->user()->getResults();

        if ($user) {
            
            if ($user->agent_id == 0){
                return '普通用户';
            }else{
                
                $agent = DB::table('agent')->where('id' , $user->agent_id)->first();
    
                $agent_name = '';
               
                if(!empty($agent) && $agent->level==0 ){
                    $agent_name = '超管';
                }else if(!empty($agent) && $agent->level > 0){
                    $agent_name =$agent->level.'级代理商';
                }
               
                return $agent_name;
            }
        } else {
            return '无用户';
        }
        
    }

    public function getParentAgentNameAttribute() {
        $user = $this->user()->getResults();
        if ($user) {
            if ($user->agent_note_id == 0){
                return '无';
            }else{
                $agent = DB::table('agent')->where('id' , $user->agent_note_id)->first();
    
                return $agent->username;
            }
        } else {
            return '';
        } 
    }

    public static function get_user($uid){
        return DB::table('users')->where('id' , $uid)->first();
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id')->withDefault();
    }


}