<?php

namespace App\Http\Controllers;

use App\Realms\Realm;

/**
 * Class SubscriptionController
 * @package App\Http\Controllers
 */
class SubscriptionController extends Controller
{
    /**
     * Minecraft assumes all months are exactly 30 days, meaning a year equals 360 days.
     * This function will normalize days so that 365 days will translate to 360 days.
     *
     * @param int $days
     * @return int
     */
    protected function normalizeDays($days)
    {
        return ceil($days / 1.013888888888889);
    }

    /**
     * View subscription information.
     *
     * @param int $serverId Server ID
     * @return array
     */
    public function view($serverId)
    {
        $server = Realm::findOrFail($serverId);

        return [
            'daysLeft' => $this->normalizeDays($server->days_left),
            'startDate' => strtotime($server->created_at) * 1000,
            'subscriptionType' => 'NORMAL'
        ];
    }
}