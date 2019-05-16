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
     * 获取数据统计条数
     *
     * @return mixed
     */
    public function handleCount()
    {

        return 5;
    }


    /**
     * 执行复制操作
     *
     * @return mixed
     */
    public function handleDuplicate()
    {
        //检测当前对象是否已复制完成
        if ($this->isCheckFinished()){
            return true;
        }

        $dependent = false;
        if (!empty($this->dependents)) { //如果设有依赖对象
            //检测依赖
            if (!empty($this->dependentCheck())){
                $dependent = true;
            }
        }

        //如果当前对象复制前仍存在依赖，则需要先复制依赖对象才能继续复制
        if ($dependent){
            return false;
        }

        //@todo 执行具体任务
        $this->startDuplicateProcedure();

        //标记处理完成
        $this->markDuplicateFinished();

        //记录日志
        $this->handleAdminLog();

        return true;
    }

    /**
     * 此方法实现店铺复制操作的具体过程
     */
    protected function startDuplicateProcedure(){

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