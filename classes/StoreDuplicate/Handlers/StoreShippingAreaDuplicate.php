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

class StoreShippingAreaDuplicate extends StoreDuplicateAbstract
{

    /**
     * 代号标识
     * @var string
     */
    protected $code = 'store_shipping_area_duplicate';

    /**
     * 排序
     * @var int
     */
    protected $sort = 32;

    public function __construct($store_id, $source_store_id)
    {
        $this->name = __('店铺配送区域、运费模板', 'shipping');

        parent::__construct($store_id, $source_store_id);
    }

    /**
     * 数据描述及输出显示内容
     */
    public function handlePrintData()
    {
        $count = $this->handleCount();
        $text      = sprintf(__('店铺内运费模板总共<span class="ecjiafc-red ecjiaf-fs3">%s</span>个', 'shipping'), $count);
        return <<<HTML
<span class="controls-info w400">{$text}</span>
HTML;

    }

    /**
     * 获取数据统计条数
     *
     * @return mixed
     */
    public function handleCount()
    {
        $count = RC_DB::table('shipping_area')->where('store_id', $this->store_id)->count();

        return $count;
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

        $result = RC_DB::table('shipping_area')->where('store_id', $this->store_id)->delete();

        if ($result) {
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

        $merchants_name = !empty($store_info) ? sprintf(__('店铺名是%s', 'shipping'), $store_info['merchants_name']) : sprintf(__('店铺ID是%s', 'shipping'), $this->store_id);

        ecjia_admin::admin_log($merchants_name, 'clean', 'store_shipping_area');
    }


}