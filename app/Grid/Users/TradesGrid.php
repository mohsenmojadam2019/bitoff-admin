<?php

namespace App\Grid\Users;


use SrkGrid\GridView\BaseGrid;
use SrkGrid\GridView\GridView;

class TradesGrid implements BaseGrid
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
            ['head' => 'Trader'],
            ['head' => 'Offerer'],
            ['head' => 'Type'],
            ['head' => 'Price'],
            ['head' => 'Currency'],
            ['head' => 'Payment Method'],
            ['head' => 'Status'],
            ['head' => 'Creation'],
        ])
            ->addColumns(function ($query) {
                $route = route('mantis.trades.show', $query->hash);
                return " <a target='_blank' href='{$route}'>{$query->hash}</a>";
            })
            ->addColumns(function ($query) {
                $route = route('users.show', $query->id);
                return " <a target='_blank' href='{$route}'>{$query->trader->username}</a>";
            })
            ->addColumns(function($query){
                $route = route('users.show', $query->id);
                return " <a target='_blank' href='{$route}'>{$query->offer->offerer->username}</a>";
            })
            ->addColumns(function($query){
                return " <span > {$query->offer->type} </span>";
            })
            ->addColumns(function($query){
                return " <span > {$query->amount} </span>";
            })
            ->addColumns(function($query){
                return " <span > {$query->offer->currency} </span>";
            })
            ->addColumns(function($query){
                return " <span > {$query->offer->paymentMethod->name} </span>";
            })
            ->addColumns(function($query){
                return " <span > {$query->status} </span>";
            })
            ->addColumns('created_at')
            ->addParentPaginateAttribute(
                [
                    'data-url' => $parameter['baseRoute'] . '?action=order_as_shopper',
                    'data-class-name' => '.show-grid'
                ])
            ->renderGrid();
    }
}
