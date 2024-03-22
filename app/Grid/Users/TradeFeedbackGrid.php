<?php

namespace App\Grid\Users;


use App\Support\Hash\HashId;
use Illuminate\Support\Str;
use SrkGrid\GridView\BaseGrid;
use SrkGrid\GridView\GridView;

class TradeFeedbackGrid implements BaseGrid
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
            ['head' => 'Trade id'],
            ['head' => 'User'],
            ['head' => 'Comment'],
            ['head' => 'Status'],
            ['head' => 'Role'],
            ['head' => 'Type'],
            ['head' => 'Date']
        ])
            ->addColumns(function ($query) {

                $hash = HashId::encode($query->feedbackable_id);
                $route = route('mantis.trades.show', $hash);
                $html = "<a  target='_blank' href='{$route}'>{$hash}</a>";
                return $html;
            })
            ->addColumns(function ($query) {
                if (request()->route()->parameter('id') == $query->fromUser->id) {
                    $innerHtml = $query->toUser->identifier;
                    $route = route('users.show', $query->toUser->id);
                } else {
                    $innerHtml = $query->fromUser->identifier;
                    $route = route('users.show', $query->fromUser->id);
                }
                return "<a class='text-dark' target='_blank' href='{$route}'><u>{$innerHtml}</u></a>";
            })
            ->addColumns(function ($query) {

                $comment = Str::limit($query->comment,60);

                return "<p>{$comment}</p>";
            })
            ->addColumns(function ($query) {
                if($query->is_positive){
                    $status = "fa fa-thumbs-up";
                    $style = "color: #42bd67;";
                }else{
                    $status =  "fa fa-thumbs-down";
                    $style = "color: #ea398e;";
                }

                return "<i class='$status'  style='$style'></i>";
            })
            ->addColumns(function ($query) {
                return $this->getHtmlForRole($query);
            })
            ->addColumns(function ($query) {
                if ($query->fromUser->id == request()->route()->parameter('id')){
                    return "Given";
                }

                if($query->toUser->id == request()->route()->parameter('id')){
                    return "Received";
                }
            })
            ->addColumns('created_at')
            ->setParentTableAttribute(['class' => 'show-grid'])
            ->addParentPaginateAttribute(
                [
                    'data-url' => $parameter['baseRoute'] . '?action=feedbacks',//
                    'data-class-name' => '.show-grid'
                ])
            ->renderGrid();
    }

    private function getHtmlForRole($query): string
    {
        if ($query->feedbackable->trader_id == $query->from_user_id) {
            return "Trader";
        }

        if($query->feedbackable->offer->offerer_id == $query->from_user_id){
            return "Offerer";
        }

        if($query->feedbackable->offerer_id == $query->from_user_id){
            return "Offerer";
        }

        return "---";
    }
}
