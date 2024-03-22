<?php

namespace App\Grid\Users;


use App\Models\Reservation;
use App\Support\Hash\HashId;
use Bitoff\Mantis\Application\Models\Trade;
use Illuminate\Support\Str;
use SrkGrid\GridView\BaseGrid;
use SrkGrid\GridView\GridView;

class CreditGrid implements BaseGrid
{

    /**
     * Render method for get html view result
     *
     * @param GridView $grid
     * @param $data
     * @param $parameter
     * @return mixed
     * @author Reza Sarlak
     */
    public function render($grid, $data, $parameter = null)
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
                return $this->getHtmlForID($query);
            })
            ->addColumns('amount')
            ->addColumns(function ($query) {
                return Str::humanize($query->type);
            })
            ->addColumns('created_at')
            ->setParentTableAttribute(['class' => 'show-grid'])
            ->addParentPaginateAttribute(
                [
                    'data-url' => $parameter['baseRoute'] . '?action=credits_btc',
                    'data-class-name' => '.show-grid'
                ])
            ->renderGrid();
    }

    private function getHtmlForID($query)
    {
        $options = [
            Reservation::class => function ($query) {
                $hasId = HashId::encode($query->creditable->order_id);
                $route = route('orders.show', $hasId);
                return "<a href='{$route}'>" . $hasId . "</a>";
            },
            Trade::class => function ($query){
                $hasId = HashId::encode($query->creditable->id);
                $route = route('mantis.trades.show', $hasId);
                return "<a href='{$route}'>" . $hasId . "</a>";
            }
        ];

        if(isset($options[get_class($query->creditable)])){
            return $options[get_class($query->creditable)]($query);
        }else{
            return '---';
        }
    }
}
