<?php

namespace App\Grid\Users;


use App\Support\Hash\HashId;
use SrkGrid\GridView\BaseGrid;
use SrkGrid\GridView\GridView;

class FeedBackGrid implements BaseGrid
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
            ['head' => 'User'],
            ['head' => 'Role'],
            ['head' => 'Type'],
            ['head' => 'Date']
        ])
            ->addColumns(function ($query) {
                $hash = HashId::encode($query->feedbackable_id );
                $route = route('orders.show', $hash);
                $html = "<a  target='_blank' href='{$route}'>{$hash}</a>";
                return $html;
            })
            ->addColumns(function ($query) {
                if (request()->route()->parameter('id') == $query->fromUser->id) {
                    $innerHtml = $query->toUser->identifier;
                    $route = route('users.show', $query->toUser->id);
                } else {
                    $innerHtml = $query->fromuser->identifier;
                    $route = route('users.show', $query->fromUser->id);
                }
                $html = "<a class='text-dark' target='_blank' href='{$route}'><u>{$innerHtml}</u></a>";
                return $html;
            })

            ->addColumns(function ($query) {
                return $query->role->value;
            })
            ->addColumns(function ($query) {
                if ($query->fromUser->id == request()->route()->parameter('id'))
                    return "Given";
                else
                    return "Received";
            })
            ->addColumns('created_at')
            ->setParentTableAttribute(['class' => 'show-grid'])
            ->addParentPaginateAttribute(
                [
                    'data-url' => $parameter['baseRoute'] . '?action=feedbacks',
                    'data-class-name' => '.show-grid'
                ])
            ->renderGrid();
    }
}
