<?php
/**
 * Created by PhpStorm.
 * User: royalwang
 * Date: 2018/12/12
 * Time: 12:05
 */

namespace Ecjia\App\Store\StoreDuplicate;

use ecjia_admin;
use RC_Api;

abstract class StoreDuplicateAbstract
{
    /**
     * 当前店铺的ID
     * @var int
     */
    protected $store_id;

    /**
     * 复制来源的店铺ID
     * @var int
     */
    protected $source_store_id;

    /**
     * 代号标识
     * @var string
     */
    protected $code;

    /**
     * 名称
     * @var string
     */
    protected $name;

    /**
     * 描述
     * @var string
     */
    protected $description;

    /**
     * 排序
     * @var int
     */
    protected $sort = 0;

    /**
     * 依赖关联code
     * @var array
     */
    protected $dependents = [];

    /**
     * 复制对象关联的数据条目
     * @var int
     */
    protected $count = 0;

    /**
     * 复制过程数据存储对象
     * @var
     */
    protected $progress_data_storage;

    /**
     * StoreDuplicateAbstract constructor.
     * @param $store_id
     * @param $source_store_id
     * @param int $sort
     */

    /**
     * 检测程序是否发生异常
     * @var bool
     */
    protected $exception = false;

    public function __construct($store_id, $source_store_id, $sort = 0)
    {
        $this->store_id = $store_id;
        $this->source_store_id = $source_store_id;
        if ($sort > 0) {
            $this->sort = $sort;
        }
    }

    protected function enableException()
    {
        $this->exception = true;
    }

    protected function disableException()
    {
        $this->exception = false;
    }

    public function setProgressDataStorage()
    {
        if (empty($this->progress_data_storage)) {
            $this->progress_data_storage = new ProgressDataStorage($this->store_id);
        }
        return $this;
    }

    public function getProgressData()
    {
        $this->setProgressDataStorage();
        return $this->progress_data_storage->getDuplicateProgressData();
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getName()
    {
        if (!empty($this->rank)) {
            return $this->name . $this->rank;
        }
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getSort()
    {
        return $this->sort;
    }

    public function setSort($sort)
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * 数据描述及输出显示内容
     */
    abstract public function handlePrintData();

    /**
     * 获取数据统计条数
     *
     * @return mixed
     */
    abstract public function handleCount();

    /**
     * 执行复制操作
     *
     * @return mixed
     */
    abstract public function handleDuplicate();

    /**
     * 返回操作日志编写
     *
     * @return mixed
     */
    //abstract public function handleAdminLog();
    public function handleAdminLog()
    {
        \Ecjia\App\Store\Helper::assign_adminlog_content();

        static $store_merchant_name, $source_store_merchant_name;

        if (empty($store_merchant_name)) {
            $store_info = RC_Api::api('store', 'store_info', ['store_id' => $this->store_id]);
            $store_merchant_name = array_get(empty($store_info) ? [] : $store_info, 'merchants_name');
        }

        if (empty($source_store_merchant_name)) {
            $source_store_info = RC_Api::api('store', 'store_info', ['store_id' => $this->source_store_id]);
            $source_store_merchant_name = array_get(empty($source_store_info) ? [] : $source_store_info, 'merchants_name');
        }

        $content = sprintf(__('录入：将【%s】店铺所有%s复制到【%s】店铺中', 'goods'), $source_store_merchant_name, $this->name, $store_merchant_name);
        ecjia_admin::admin_log($content, 'clear', 'store_goodsww');
    }

    /**
     * 标记操作完成
     */
    public function markDuplicateFinished()
    {
        //$this->setProgressDataStorage();

        $this->getProgressData()->addDuplicateFinishedItem($this->getCode());

        $this->progress_data_storage->save();
    }

    /**
     * 依赖检测，返回依赖未完成的项
     * @return mixed
     */
    public function dependentCheck()
    {
        //$this->setProgressDataStorage();
        $items = $this->getProgressData()->getDuplicateFinishedItems();

        $factory = new StoreDuplicateManager($this->store_id, $this->source_store_id);

        $diff = collect($this->dependents)->diff($items)->filter(function ($item) use ($factory) {
            //判断依赖项是否有数据
            $handle = $factory->handler($item);

            return $handle->handleCount() > 0 ? true : false;
        });

        return $diff->all();
    }

    /**
     * 检查当前复制项是否完成
     * @return bool
     */
    public function isCheckFinished()
    {
        //$this->setProgressDataStorage();
        if (in_array($this->getCode(), $this->getProgressData()->getDuplicateFinishedItems())) {
            return true;
        }
        return false;
    }

    /**
     * @param $code
     * @return array
     */
    public function getReplacementData($code)
    {
        //$this->setProgressDataStorage();
        return $this->getProgressData()->getReplacementData($code);
    }

    /**
     * @param $code
     * @param $replacement_data
     * @return bool
     */
    public function setReplacementData($code, $replacement_data)
    {
        //$this->setProgressDataStorage();
        $this->getProgressData()->setReplacementDataByCode($code, $replacement_data);

        $this->progress_data_storage->save();

        return true;
    }

    public function getException(){
        return $this->exception;
    }
}