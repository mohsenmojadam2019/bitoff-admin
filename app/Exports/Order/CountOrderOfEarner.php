<?php

namespace App\Exports\Order;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;

class CountOrderOfEarner implements FromArray
{
    function array(): array
    {
        $result[] = [
            'USERNAME', 'EMAIL', 'COUNT', 'CREATE AT',
        ];
        foreach ($this->result() as $user) {
            $result[] = [
                $user->username,
                $user->email,
                $user->order_count,
                $user->created_at,
            ];
        }

        return $result;
    }

    protected function result()
    {
        return User::query()
            ->with('earns')
            ->withCount('earns as order_count')
            ->whereHas('earns')
            ->orderBy('order_count', 'desc')
            ->get();
    }
}
