<?php

namespace App\Grid\Users;


use SrkGrid\GridView\BaseGrid;
use SrkGrid\GridView\GridView;

class OffersGrid implements BaseGrid
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
            ['head' => 'Type'],
            ['head' => 'Price Limit'],
            ['head' => 'Currency'],
            ['head' => 'Payment Method'],
            ['head' => 'Off'],
            ['head' => 'Status'],
            ['head' => 'Creation'],
        ])
            ->addColumns(function ($query) {
                $route = route('mantis.offers.show', $query->hash);
                return " <a target='_blank' href='{$route}'>{$query->hash}</a>";
            })
            ->addColumns(function ($query) {
                return " <span > $query->type</span>";
            })
            ->addColumns(function($query){
                return " <span >from $query->min to $query->max </span>";
            })
            ->addColumns(function($query){
                return " <span > {$query->currency} </span>";
            })
            ->addColumns(function($query){
                return " <span > {$query->paymentMethod->name} </span>";
            })
            ->addColumns(function($query){
                return " <span > {$query->rate} </span>";
            })
            ->addColumns(function($query){
                return " <span > {$query->status} </span>";
            })

            ->addColumns('created_at')
//            ->setParentTableAttribute(['class' => 'show-grid sdfadsf adfasd f'])
            ->addParentPaginateAttribute(
                [
                    'data-url' => $parameter['baseRoute'] . '?action=order_as_shopper',
                    'data-class-name' => '.show-grid'
                ])
            ->renderGrid();
    }
}
