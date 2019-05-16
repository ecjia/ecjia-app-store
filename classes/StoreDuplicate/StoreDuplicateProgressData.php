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
    protected $duplicate_items = [];

    /**
     * @return array
     */
    public function getDuplicateItems()
    {
        return $this->duplicate_items;
    }

    /**
     * @param array $duplicate_items
     * @return StoreDuplicateStatus
     */
    public function setDuplicateItems($duplicate_items)
    {
        $this->duplicate_items = $duplicate_items;
        return $this;
    }


    public function addDuplicateItem($code)
    {
        $this->duplicate_items[] = $code;
    }


    public function toArray()
    {
        return [
            //'status' => $this->status,
            'duplicate_items' => $this->duplicate_items,
        ];

    }

    /**
     * 创建对象
     * @param array $data
     */
    public static function createStoreDuplicateStatus(array $data = null)
    {
        $duplicate = new static();

        if (! empty($data)) {

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