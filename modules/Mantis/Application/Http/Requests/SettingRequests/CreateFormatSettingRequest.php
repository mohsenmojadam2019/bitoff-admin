<?php

namespace Bitoff\Mantis\Application\Http\Requests\SettingRequests;


trait CreateFormatSettingRequest
{
    
    protected $keysSetting;
    protected $loopIteration = 0;
    protected $disposableData;
    protected $data;

    public function formatSettingRequest()
    {
        $this->setKeysSetting();

        $this->checkType();

        return ['value' => $this->data];
    }


    protected function setKeysSetting()
    {
        $this->keysSetting = collect($this->validated())->keys()->toArray();
    }

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

    protected function singleValue()
    {
        $disposableData = null;

        collect($this->keysSetting)->each(function ($key) use (&$disposableData) {
            $disposableData[] = request()->$key[$this->loopIteration];
        });

        $this->data = (object)collect($this->keysSetting)->combine($disposableData)->toArray();
    }

    protected function forbiddenUsername()
    {
        $this->data = (request('username'));
    }
}
