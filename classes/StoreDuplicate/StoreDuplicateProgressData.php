<?php
/**
 * Created by PhpStorm.
 * User: royalwang
 * Date: 2019-05-15
 * Time: 18:33
 */

namespace Ecjia\App\Store\StoreDuplicate;


use Royalcms\Component\Support\Str;

class StoreDuplicateProgressData
{

    /**
     * 存储已经完成的复制项
     *
     * @var array
     */
    protected $duplicate_finished_items = [];

    /**
     * @return array
     */
    public function getDuplicateFinishedItems()
    {
        return $this->duplicate_finished_items;
    }

    /**
     * @param $finished_items
     * @return $this
     */
    public function setDuplicateFinishedItems($finished_items)
    {
        $this->duplicate_finished_items = $finished_items;
        return $this;
    }

    public function addDuplicateFinishedItem($code)
    {
        $this->duplicate_finished_items[] = $code;
    }

    public function toArray()
    {
        return [
            'duplicate_finished_items' => $this->duplicate_finished_items,
        ];

    }

    /**
     * 创建对象
     * @param array $data
     */
    public static function createStoreDuplicateProgressData(array $data = null)
    {
        $duplicate = new static();
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $method = Str::camel('set_' . $key);
                if (method_exists($duplicate, $method)) {
                    $duplicate->{$method}($value);
                }
            }
        }

        return $duplicate;
    }


}