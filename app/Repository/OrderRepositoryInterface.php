<?php

namespace App\Repository;

/**
 * @method find($id)
 * @method support($id)
 * @method amazonTrack($id)
 * @method otherTrack($id)
 */
interface OrderRepositoryInterface
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_CREDIT_PENDING = 'credit_pending';

    public const STATUS_RESERVE = 'reserve';

    public const STATUS_PURCHASE = 'purchase';

    public const STATUS_PARTIAL_SHIP = 'partial_ship';

    public const STATUS_SHIP = 'ship';

    public const STATUS_PARTIAL_DELIVER = 'partial_deliver';

    public const STATUS_WISH_PENDING = 'wish_pending';

    public const STATUS_DELIVER = 'deliver';

    public const STATUS_WISH_CALLBACK = 'wish_callback';

    public const STATUS_CANCEL = 'cancel';

    public const STATUS_COMPLETE = 'complete';

    public const STATUS_WISH_FAIL = 'wish_fail';

    public const STATUS_ISSUE_FOUNDED = 'issue_founded';

    public function getStatus($status);

    public function isNative($id);

    public function withRelations($request);

    public function support($id);

    public function issueMessage($id);

    public function shopper($id);

    public function earner($id);

    public function credits($id);

    public function reservations($id);

    public function allChats($id);

    public function wishes($id);

    public function from($id);

    public function tracking($id);

    public function images($id);

    public function otherTrackItem($id, $origin);

    public function amazonTrack($id);

    public function otherTrack($id);

    public function totalPrice($id, $option = []);

    public function shopperCredit($orderId);

    public function reorder($id, $attributes);

    public function countCondition($condition, $currency = null);

    public function getOrderWithCondition($condition, $currency = null);
}
