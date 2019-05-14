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

class StoreCategoryDuplicate extends StoreDuplicateAbstract
{

    /**
     * 代号标识
     * @var string
     */
    protected $code = 'store_category_duplicate';

    /**
     * 排序
     * @var int
     */
    protected $sort = 2;

    public function __construct($store_id, $source_store_id)
    {
        $this->name = __('商品分类', 'category');

        parent::__construct($store_id, $source_store_id);
    }

    /**
     * 数据描述及输出显示内容
     */
    public function handlePrintData()
    {
        $count     = $this->handleCount();
        $text = sprintf(__('店铺内总共有<span class="ecjiafc-red ecjiaf-fs3">%s</span>大分类', 'category'), $count);

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

        return 12;
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

    }


}