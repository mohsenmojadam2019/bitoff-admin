<?php

namespace App\Http\Requests\SettingRequests;


trait CreateFormatSettingRequest
{
    /**
     * Store key of value of setting
     * @var array
     */
    protected $keysSetting;
    /**
     * Increment integer
     * @var int
     */
    protected $loopIteration = 0;
    /**
     * Store momentary data
     * @var array
     */
    protected $disposableData;
    /**
     * Store final data
     * @var array
     */
    protected $data;

    /**
     * @author Reza Sarlak
     * @return array
     */
    public function formatSettingReq()
    {
        $this->setKeysSetting();

        $this->checkType();

        return ['value' => $this->data];
    }


    /**
     * @author Reza Sarlak
     * Set key for setting value
     */
    protected function setKeysSetting()
    {
        $this->keysSetting = collect($this->validated())->keys()->toArray();
    }

    /**
     * @author Reza Sarlak
     * Check type request for multiple value or single value
     */
    protected function checkType()
    {
        $firstKey = $this->keysSetting[0];

        if (request()->has('username')) {
            $this->forbiddenUsername();
        } elseif (is_array((request($firstKey))) && count(request($firstKey)) > 1) {
            $this->multipleValue($firstKey);
        } else {
            $this->singleValue();
        }
    }

    /**
     * @author Reza Sarlak
     * @param $firstKey
     */
    protected function multipleValue($firstKey)
    {
        collect(request()->$firstKey)->each(function () {

            $this->disposableData = null;

            collect($this->keysSetting)->each(function ($key) {
                $this->disposableData[] = request()->$key[$this->loopIteration];
            });

            $this->loopIteration++;

            $this->data [] = (object)collect($this->keysSetting)->combine($this->disposableData)->toArray();
        });
    }

    /**
     * @author Reza Sarlak
     * Fire when request type single value
     */
    protected function singleValue()
    {
        $disposableData = null;

        collect($this->keysSetting)->each(function ($key) use (&$disposableData) {
            $disposableData[] = request()->$key[$this->loopIteration];
        });

        $this->data = (object)collect($this->keysSetting)->combine($disposableData)->toArray();
    }

    /**
     * Just run for forbidden username
     */
    protected function forbiddenUsername()
    {
        $this->data = (request('username'));
    }
}
