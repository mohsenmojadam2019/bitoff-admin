<?php

namespace App\Grid\Users;


use SrkGrid\GridView\BaseGrid;
use SrkGrid\GridView\GridView;

class AsShopperGrid implements BaseGrid
{

    /**
     * Render method for get html view result
     *
     * @author Reza Sarlak
     * @param GridView $grid
     * @param $data
     * @param $parameter
     * @return mixed
     */
    public function render($grid, $data, $parameter = null)
    {
        return $grid->headerColumns([
            ['head' => 'ID'],
            ['head' => 'Earner'],
            ['head' => 'Status'],
            ['head' => 'Date'],
        ])
            ->addColumns(function ($query) {
                $route = route('orders.show', $query->hash);
                $html = " <a target='_blank' href='{$route}'>{$query->hash}</a>";
                return $html;
            })
            ->addColumns(function ($query) {
                $route = isset($query->earner->id) ? route('users.show', optional($query->earner)->id) : '';
                $innerHtml = isset($query->earner->identifier) ? $query->earner->identifier : '';
                $html = "<a target='_blank' href='{$route}'>" . $innerHtml . "</a>";
                return $html;
            })
            ->addColumns(function($query){
                $localization = trans("order.translate.$query->status");
                $typeClass = trans("order.color.$query->status");
                $html = " <span class='badge badge-{$typeClass }'>{$localization}</span>";
                return $html;
            })
            ->addColumns('created_at')
            ->setParentTableAttribute(['class' => 'show-grid sdfadsf adfasd f'])
            ->addParentPaginateAttribute(
                [
                    'data-url' => $parameter['baseRoute'] . '?action=order_as_shopper',
                    'data-class-name' => '.show-grid'
                ])
            ->renderGrid();
    }
}
