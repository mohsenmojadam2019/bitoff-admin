<?php

namespace App\Grid\Users;

use App\Models\Reservation;
use App\Support\Hash\HashId;
use Illuminate\Support\Str;
use SrkGrid\GridView\BaseGrid;
use SrkGrid\GridView\GridView;

class CreditUsdtGrid implements BaseGrid
{
    /**
     * Render method for get html view result
     *
     * @param GridView $grid
     * @param $data
     * @param $parameters
     * @return mixed
     */
    public function render($grid, $data, $parameters = null)
    {
        return $grid->headerColumns([
            ['head' => ' '],
            ['head' => 'ID'],
            ['head' => 'Amount'],
            ['head' => 'Type'],
            ['head' => 'Date'],
        ])
            ->addColumns(function ($query) {
                $class = $query->amount > 0 ? 'fas fa-sort-numeric-up-alt text-success' : 'fas fa-sort-numeric-down-alt text-danger';
                $html = "<span class = '{$class}'></span>";
                return $html;
            })
            ->addColumns(function ($query) {
                $hasId = HashId::encode(optional($query->creditable)->order_id);
                $route = route('orders.show', $hasId);
                $html = is_a($query->creditable, Reservation::class) ?
                    "<a href='{$route}'>" . $hasId . "</a>" : '---';
                return $html;
            })
            ->addColumns('amount')
            ->addColumns(function ($query) {
                return Str::humanize($query->type);
            })
            ->addColumns('created_at')
            ->setParentTableAttribute(['class' => 'show-grid'])
            ->addParentPaginateAttribute(
                [
                    'data-url' => $parameters['baseRoute'] . '?action=credits_usdt',
                    'data-class-name' => '.show-grid'
                ])
            ->renderGrid();
    }
}
