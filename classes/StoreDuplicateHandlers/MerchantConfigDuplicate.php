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
use ecjia_error;

/**
 * 店铺基本信息复制
 *
 * Class MerchantConfigDuplicate
 * @package Ecjia\App\Store\StoreDuplicateHandlers
 */
class MerchantConfigDuplicate extends StoreDuplicateAbstract
{

    /**
     * 代号标识
     * @var string
     */
    protected $code = 'merchant_config_duplicate';

    /**
     * 排序
     * @var int
     */
    protected $sort = 1;

    public function __construct($store_id, $source_store_id)
    {
        $this->name = __('店铺基本信息', 'store');

        parent::__construct($store_id, $source_store_id);

        $this->source_store_data_handler = RC_DB::table('merchants_config')->where('store_id', $this->source_store_id)->select('store_id','code','value');
    }

    /**
     * 数据描述及输出显示内容
     */
    public function handlePrintData()
    {
        $text = __('复制店铺内基本信息（店铺Logo、Banner、营业时间、客服电话、公告、简介等）', 'store');

        return <<<HTML
<span class="controls-info">{$text}</span>
HTML;
    }

    /**
     * 统计数据条数并获取
     *
     * @return mixed
     */
    public function handleCount()
    {
        //如果已经统计过，直接返回统计过的条数
        if ($this->count) {
            return $this->count;
        }
        // 统计数据条数
        if (!empty($this->source_store_data_handler)) {
            $this->count = $this->source_store_data_handler->count();
        }
        return $this->count;
    }


    /**
     * 执行复制操作
     *
     * @return mixed
     */
    public function handleDuplicate()
    {
        //检测当前对象是否已复制完成
        if ($this->isCheckFinished()) {
            return true;
        }

        //如果当前对象复制前仍存在依赖，则需要先复制依赖对象才能继续复制
        if (!empty($this->dependents)) { //如果设有依赖对象
            //检测依赖
            $items = $this->dependentCheck();
            if (!empty($items)) {
                return new ecjia_error('handle_duplicate_error', __('复制依赖检测失败！', 'store'), $items);
            }
        }

        //执行具体任务
        $this->startDuplicateProcedure();

        //标记处理完成
        $this->markDuplicateFinished();

        //记录日志
        $this->handleAdminLog();

        return true;
    }

    /**
     * 店铺复制操作的具体过程
     */
    protected function startDuplicateProcedure()
    {
        $this->source_store_data_handler->chunk(50, function ($items) {
            //构造可用于复制的数据
            $this->buildDuplicateData($items);

            dd($items);
            //更新数据到新店铺
            //RC_DB::table('merchants_config')->insert($items);
        });


    }

    protected function buildDuplicateData(&$items)
    {
        foreach ($items as $k => &$item) {
            unset($item['id']);

            //将源店铺ID设为新店铺的ID
            $item['store_id'] = $this->store_id;

            //解决图片问题数据
            switch ($item['code']) {
                //目前只发现了这几种value带图片路径的code，还有的话可以再加
                case 'shop_thumb_logo' :
                case 'shop_nav_background' :
                case 'shop_logo' :
                case 'shop_banner_pic' :
                    $item['value'] = sprintf('merchant/%s/data/%s/%s.jpg', $this->store_id, $item['code'], '123');

                    //存储图片

                    break;
            }


            //不需要复制的数据
            if (stripos($item['code'], 'duplicate_') !== false) {
                unset($items[$k]);
            }

            //其他数据处理

        }


    }

    /**
     * 返回操作日志编写
     *
     * @return mixed
     */
    public function handleAdminLog()
    {

    }


}