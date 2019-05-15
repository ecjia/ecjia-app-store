<?php
/**
 * Created by PhpStorm.
 * User: royalwang
 * Date: 2018/12/12
 * Time: 14:04
 */

namespace Ecjia\App\Store\StoreDuplicateHandlers;

use Ecjia\App\Store\StoreDuplicate\StoreDuplicateAbstract;
use RC_Uri;
use RC_DB;
use RC_Api;
use ecjia_admin;

class StoreBonusDuplicate extends StoreDuplicateAbstract
{

    /**
     * 代号标识
     * @var string
     */
    protected $code = 'store_bonus_duplicate';

    /**
     * 排序
     * @var int
     */
    protected $sort = 13;

    public function __construct($store_id, $source_store_id)
    {
        $this->name = __('店铺红包', 'bonus');

        parent::__construct($store_id, $source_store_id);
    }

    /**
     * 数据描述及输出显示内容
     */
    public function handlePrintData()
    {
        /*$store_info = RC_Api::api('store', 'store_info', array('store_id' => $this->store_id));

        $type = 'self';
        if ($store_info['manage_mode'] != 'self') {
            $type = 'merchant';
        }

        $url = RC_Uri::url('bonus/admin/init', array('type' => $type, 'merchant_keywords' => $store_info['merchants_name']));
        $text_info = __('查看全部>>>', 'bonus');
        */

        $count     = $this->handleCount();
        $text      = sprintf(__('店铺红包总共<span class="ecjiafc-red ecjiaf-fs3">%s</span>个', 'bonus'), $count);


        return <<<HTML
<span class="controls-info w300">{$text}</span>
HTML;
    }

    /**
     * 获取数据统计条数
     *
     * @return mixed
     */
    public function handleCount()
    {
        $bonus_type_list = RC_DB::table('bonus_type')->where('store_id', $this->source_store_id)->lists('type_id');
        //$bonus_type_list = [1,2];
        if ($bonus_type_list){
            return RC_DB::table('user_bonus')->whereIn('bonus_type_id', $bonus_type_list)->count();
        }
        return 0;

    }


    /**
     * 执行复制操作
     *
     * @return mixed
     */
    public function handleDuplicate()
    {
        return true;
        $count = $this->handleCount();
        if (empty($count)) {
            return true;
        }

        $bonus_type_list = RC_DB::table('bonus_type')->where('store_id', $this->store_id)->lists('type_id');

        $res    = RC_DB::table('user_bonus')->whereIn('bonus_type_id', $bonus_type_list)->delete();
        $result = RC_DB::table('bonus_type')->where('store_id', $this->store_id)->delete();

        if ($result || $res) {
            $this->handleAdminLog();
        }

        return $result;
    }

    /**
     * 返回操作日志编写
     *
     * @return mixed
     */
    public function handleAdminLog()
    {
        \Ecjia\App\Store\Helper::assign_adminlog_content();

        $store_info = RC_Api::api('store', 'store_info', array('store_id' => $this->store_id));

        $merchants_name = !empty($store_info) ? sprintf(__('店铺名是%s', 'bonus'), $store_info['merchants_name']) : sprintf(__('店铺ID是%s', 'bonus'), $this->store_id);

        ecjia_admin::admin_log($merchants_name, 'clean', 'store_bonus');
    }


}