<?php

namespace App\Grid\Users;


use App\Models\Credit;
use SrkGrid\GridView\BaseGrid;
use SrkGrid\GridView\GridView;

class TransactionGrid implements BaseGrid
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
            ['head' => 'Type'],
            ['head' => 'Trough'],
            ['head' => 'Currency'],
            ['head' => 'BTC'],
            ['head' => 'USD'],
            ['head' => 'Date'],
        ])
            ->addColumns('type')
            ->addColumns(function ($query) {
                if ($query->currency === 'btc') {
                    return sprintf('<code><a style="color: inherit" target="_blank" href="https://btc.com/%s">%s</a></code>', $query->tx_hash, $query->tx_hash);
                }
                return sprintf('<code><a style="color: inherit" target="_blank" href="https://tronscan.org/#/transaction/%s">%s</a></code>', $query->tx_hash, $query->tx_hash);
            })
            ->addColumns(function ($query) {
                $btcImage = '<img width="15px" height="15px" src="/currency_logo/bitcoin_logo.png">';
                $usdtImage = ' <img width="15px" height="15px" src="/currency_logo/usdt_logo.png">';
                $image = $query->currency == Credit::CURRENCY_USDT ? $usdtImage : $btcImage;
                return  $image. strtoupper($query->currency);
            })
            ->addColumns(function ($query) {
                if ($query->currency === 'btc') {
                    return $query->amount;
                }

                return '';
            })
            ->addColumns(function ($query) {
                if ($query->currency === 'btc') {
                    return number_format($query->amount / $query->rate, 2);
                }
                return $query->amount;
            })
            ->addColumns(function ($query) {
                return $query->created_at->toDateString();
            })
            ->setParentTableAttribute(['class' => 'show-grid table-responsive'])
            ->addParentPaginateAttribute(
                [
                    'data-url' => $parameter['baseRoute'] . '?action=transactions',
                    'data-class-name' => '.show-grid'
                ])
            ->renderGrid();
    }
}
