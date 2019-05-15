<?php
/**
 * Created by PhpStorm.
 * User: royalwang
 * Date: 2019-05-15
 * Time: 18:50
 */

namespace Ecjia\App\Store\StoreDuplicate;


use Ecjia\App\Store\Models\MerchantConfigModel;

class ProgressDataStorage
{

    protected $store_id;

    protected $duplicate_status;

    const STORAGE_CODE = 'duplicate_store_status';

    public function __construct($store_id, StoreDuplicateStatus $status = null)
    {
        $this->store_id = $store_id;

        $this->duplicate_status = $status;

    }


    public function save()
    {

        $model = MerchantConfigModel::where('store_id', $this->store_id)->where('code', self::STORAGE_CODE)->first();

        if (! empty($model)) {

            $model->value = serialize($this->duplicate_status->toArray());
            $model->save();

        }
        else {

            $data = [
                'store_id' => $this->store_id,
                'code' => self::STORAGE_CODE,
                'value' => serialize($this->duplicate_status->toArray()),
            ];

            $model = MerchantConfigModel::create($data);

        }

        return $model;
    }


    public function getDuplicateStatus()
    {
        if (is_null($this->duplicate_status)) {
            $model = MerchantConfigModel::where('store_id', $this->store_id)->where('code', self::STORAGE_CODE)->first();

            if (! empty($model)) {

                if ($model->value) {
                    $data = unserialize($model->value);

                    if (is_array($data)) {
                        $this->duplicate_status = StoreDuplicateStatus::createStoreDuplicateStatus($data);
                    }

                }


            }

        }

        return $this->duplicate_status;
    }

}