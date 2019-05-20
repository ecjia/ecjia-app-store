<?php
/**
 * Created by PhpStorm.
 * User: royalwang
 * Date: 2018/12/12
 * Time: 14:04
 */

namespace Ecjia\App\Store\StoreDuplicateHandlers;

use Ecjia\App\Store\Repositories\MerchantConfigRepository;
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
        try {
            $source_repository = new MerchantConfigRepository($this->source_store_id);

            $count = $source_repository->getCount();

            // 统计数据条数
            return $count;
        } catch (\Royalcms\Component\Repository\Exceptions\RepositoryException $e) {
            return new ecjia_error('duplicate_data_error', $e->getMessage());
        }

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
        $result = $this->startDuplicateProcedure();

        if (is_ecjia_error($result)) {
            return $result;
        }

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
        try {
            $repository = new MerchantConfigRepository($this->store_id);
            $source_repository = new MerchantConfigRepository($this->source_store_id);

            $options = $source_repository->getAllOptions();
            $options->map(function ($item) use ($repository) {
                //构造可用于复制的数据
//            $this->buildDuplicateData($items);

                //更新数据到新店铺
                //RC_DB::table('merchants_config')->insert($items);

                if (in_array($item['code'], [
                    'duplicate_progress_data',
                    'duplicate_source_store_id',
                    'duplicate_store_status',
                ])) {
                    return false;
                }

                return $repository->addOption($item['code'], $item['value'], [
                    'type' => $item['type'],
                    'group' => $item['group'],
                    'store_range' => $item['store_range'],
                    'store_dir' => $item['store_dir'],
                    'sort_order' => $item['sort_order'],
                ]);

            });

            return true;
        } catch (\Royalcms\Component\Repository\Exceptions\RepositoryException $e) {
            return new ecjia_error('duplicate_data_error', $e->getMessage());
        }

        /**
         * setp2
         *
         * 目前只发现了这几种value带图片路径的code，还有的话可以再加
         * shop_thumb_logo
         * shop_nav_background
         * shop_logo
         * shop_banner_pic
         */


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
        \Ecjia\App\Store\Helper::assign_adminlog_content();

        $store_info = RC_Api::api('store', 'store_info', array('store_id' => $this->store_id));

        $merchants_name = !empty($store_info) ? sprintf(__('店铺名是%s', 'goods'), $store_info['merchants_name']) : sprintf(__('店铺ID是%s', 'goods'), $this->store_id);

        ecjia_admin::admin_log($merchants_name, 'duplicate', 'store_goods');
    }


}