<?php

namespace App\Grid\Users;


use SrkGrid\GridView\BaseGrid;
use SrkGrid\GridView\GridView;

class IPGrid implements BaseGrid
{
    public function render($grid, $data, $parameters = null)
    {
        return $grid->headerColumns([
            ['head' => 'IP'],
            ['head' => 'Type'],
            ['head' => 'Date'],
        ])->addColumns('ip')
            ->addColumns('type')
            ->addColumns(function ($query) {
                if ($query->created_at) {
                    $html = " <span>{$query->created_at}</span>";
                } else {
                    $html = '';
                }
                return $html;
            })->renderGrid();
    }
}
