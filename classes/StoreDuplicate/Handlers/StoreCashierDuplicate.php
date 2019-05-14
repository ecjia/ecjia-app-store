<?php
/**
 * Created by PhpStorm.
 * User: royalwang
 * Date: 2018/12/12
 * Time: 14:04
 */

namespace Ecjia\App\Store\StoreDuplicate\Handlers;

use Ecjia\App\Store\StoreDuplicate\StoreDuplicateAbstract;
use RC_Uri;
use RC_DB;
use RC_Api;
use ecjia_admin;

class StoreCashierDuplicate extends StoreDuplicateAbstract
{

    /**
     * 代号标识
     * @var string
     */
    protected $code = 'store_cashier_duplicate';

    /**
     * 排序
     * @var int
     */
    protected $sort = 7;

    public function __construct($store_id, $source_store_id)
    {
        $this->name = __('收银台商品', 'cashier');

        parent::__construct($store_id, $source_store_id);
    }

    /**
     * 数据描述及输出显示内容
     */
    public function handlePrintData()
    {
        $count     = $this->handleCount();
        $text = sprintf(__('店铺内总共有<span class="ecjiafc-red ecjiaf-fs3">%s</span>件收银台商品', 'cashier'), $count);

        return <<<HTML
<span class="controls-info">{$text}</span>
HTML;
    }

    /**
     * 获取数据统计条数
     *
     * @return mixed
     */
    public function handleCount()
    {
        //$count = RC_DB::table('cashier_pendorder')->where('store_id', $this->store_id)->count();
        return 18;
    }


    /**
     * 执行复制操作
     *
     * @return mixed
     */
    public function handleDuplicate()
    {

        return true;
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

        $merchants_name = !empty($store_info) ? sprintf(__('店铺名是%s', 'cashier'), $store_info['merchants_name']) : sprintf(__('店铺ID是%s', 'cashier'), $this->store_id);

        ecjia_admin::admin_log($merchants_name, 'clean', 'store_cashier_pendorder');

    }


}