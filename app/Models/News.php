<?php
/**
 * create by vscode
 * @author lion
 */
namespace App\Models;

class News extends Model
{
    protected $table = 'news';
    //自动时间戳
    protected $dateFormat = 'U';
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    protected static $langList = [
        'zh' => '中文简体',
        'en' => '英文',
        'hk' => '中文繁体',
        'jp' => '日语',
    ];

    public static function getLangeList()
    {
        return self::$langList;
    }

    /**
     * 定义新闻和分类的一对多相对关联
     */

    public function cate()
    {
        return $this->belongsTo(NewsCategory::class, 'c_id');
    }

    /**
     * 定义新闻和评论的一对多关联
     */

    public function discuss()
    {
        return $this->hasMany(NewsDiscuss::class, 'n_id');
    }

    public function getCreateTimeAttribute()
    {
        $value = $this->attributes['create_time'];
        return $value ? date('Y-m-d H:i:s', $value ) : '';
    }

    public function getThumbnailAttribute()
    {
        $thumbnail = $this->attributes['thumbnail'];
        return $thumbnail ? $thumbnail : URL("images/zwtp.png");
    }

    public function getUpdateTimeAttribute()
    {
        $value = $this->attributes['update_time'];
        return $value ? date('Y-m-d H:i:s', $value ) : '';
    }

    /**
     * 获取当前时间
     *
     * @return int
     */

    public function freshTimestamp()
    {
        return time();
    }

    /**
     * 避免转换时间戳为时间字符串
     *
     * @param DateTime|int $value
     * @return DateTime|int
     */
    
    public function fromDateTime($value)
    {
        return $value;
    }

    /**
     * 直接从POST变量批量赋值，忽略不存在的字段和主键
     * @return bool
     */
    public function batchAssign($data)
    {   
        if(is_array($data)) {
            foreach($data as $key => $value) {
                //判定$key是否在模型字段中，如果不在则忽略
                $fields = Schema::getColumnListing($this->table);
                if(in_array($key, $fields) && $key != $this->primaryKey) {
                    $this->$key = $value;
                }
            }
            return true;
        } else {
            return false;
        }
    }
}
