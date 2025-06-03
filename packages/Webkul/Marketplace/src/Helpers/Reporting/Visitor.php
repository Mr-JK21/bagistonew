<?php

namespace Webkul\Marketplace\Helpers\Reporting;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\Helpers\Reporting\AbstractReporting;
use Webkul\Core\Repositories\VisitRepository;

class Visitor extends AbstractReporting
{
    /**
     * Create a helper instance.
     *
     * @return void
     */
    public function __construct(protected VisitRepository $visitRepository)
    {
        parent::__construct();
    }

    /**
     * Retrieves total visitors and their progress.
     *
     * @param  object  $seller
     * @param  string  $visitableType
     */
    public function getTotalVisitorsProgress($seller, $visitableType = null): array
    {
        return [
            'previous' => $previous = $this->getTotalVisitors($seller, $this->lastStartDate, $this->lastEndDate, $visitableType),
            'current'  => $current = $this->getTotalVisitors($seller, $this->startDate, $this->endDate, $visitableType),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total visitors and their progress.
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $visitableType
     * @return array
     */
    public function getTotalVisitors($seller, $startDate, $endDate, $visitableType = null): int
    {
        $query = $this->visitRepository
            ->resetModel()
            ->whereIn('channel_id', $this->channelIds)
            ->where('marketplace_seller_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($visitableType) {
            $query->where('visitable_type', $visitableType);
        } else {
            $query->whereNull('visitable_id');
        }

        return $query->count();
    }

    /**
     * Retrieves unique visitors and their progress.
     *
     * @param  object  $seller
     * @param  string  $visitableType
     */
    public function getTotalUniqueVisitorsProgress($seller, $visitableType = null): array
    {
        return [
            'previous' => $previous = $this->getTotalUniqueVisitors($seller, $this->lastStartDate, $this->lastEndDate, $visitableType),
            'current'  => $current = $this->getTotalUniqueVisitors($seller, $this->startDate, $this->endDate, $visitableType),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total unique visitors
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $visitableType
     */
    public function getTotalUniqueVisitors($seller, $startDate, $endDate, $visitableType = null): int
    {
        $query = $this->visitRepository
            ->resetModel()
            ->whereIn('channel_id', $this->channelIds)
            ->where('marketplace_seller_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($visitableType) {
            $query->where('visitable_type', $visitableType)
                ->groupBy(DB::raw('CONCAT(ip, "-", visitor_id, "-", visitable_type)'));
        } else {
            $query->whereNull('visitable_id')
                ->groupBy(DB::raw('CONCAT(ip, "-", visitor_id)'));
        }

        return $query->get()->count();
    }

    /**
     * Returns previous sales over time
     *
     * @param  object  $seller
     * @param  string  $visitableType
     */
    public function getPreviousTotalVisitorsOverTime($seller, $visitableType = null): array
    {
        return $this->getTotalVisitorsOverTime($seller, $this->lastStartDate, $this->lastEndDate, 'auto', $visitableType);
    }

    /**
     * Returns current sales over time
     *
     * @param  object  $seller
     * @param  string  $visitableType
     */
    public function getCurrentTotalVisitorsOverTime($seller, $visitableType = null): array
    {
        return $this->getTotalVisitorsOverTime($seller, $this->startDate, $this->endDate, 'auto', $visitableType);
    }

    /**
     * Returns previous sales over week
     *
     * @param  object  $seller
     * @param  string  $visitableType
     */
    public function getPreviousTotalVisitorsOverWeek($seller, $visitableType = null): array
    {
        return $this->getTotalVisitorsOverWeek($seller, $this->lastStartDate, $this->lastEndDate, $visitableType);
    }

    /**
     * Returns current sales over week
     *
     * @param  object  $seller
     * @param  string  $visitableType
     */
    public function getCurrentTotalVisitorsOverWeek($seller, $visitableType = null): array
    {
        return $this->getTotalVisitorsOverWeek($seller, $this->startDate, $this->endDate, $visitableType);
    }

    /**
     * Gets visitable with most visits.
     *
     * @param  object  $seller
     * @param  string  $visitableType
     * @param  int  $limit
     */
    public function getVisitableWithMostVisits($seller, $visitableType = null, $limit = null): Collection
    {
        $visits = $this->visitRepository
            ->resetModel()
            ->addSelect(
                'id',
                'visitable_type',
                'visitable_id',
                DB::raw('COUNT(*) as visits')
            )
            ->where('visitable_type', $visitableType)
            ->whereIn('channel_id', $this->channelIds)
            ->where('marketplace_seller_id', $seller->id)
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->groupBy('visitable_id')
            ->orderByDesc('visits')
            ->limit($limit)
            ->get();

        $visits->map(function ($visit) {
            $visit->name = $visit->visitable->name;
        });

        return $visits;
    }

    /**
     * Generates visitor graph data.
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $period
     * @param  string  $visitableType
     */
    public function getTotalVisitorsOverTime($seller, $startDate, $endDate, $period = 'auto', $visitableType = null): array
    {
        $config = $this->getTimeInterval($startDate, $endDate, $period);

        $groupColumn = $config['group_column'];

        $results = $this->visitRepository
            ->resetModel()
            ->select(
                DB::raw("$groupColumn AS date"),
                DB::raw('COUNT(*) AS total')
            )
            ->whereNull('visitable_id')
            ->whereIn('channel_id', $this->channelIds)
            ->where('marketplace_seller_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get();

        $stats = [];

        foreach ($config['intervals'] as $interval) {
            $total = $results->where('date', $interval['filter'])->first();

            $stats[] = [
                'label' => $interval['start'],
                'total' => $total?->total ?? 0,
            ];
        }

        return $stats;
    }

    /**
     * Generates visitor over week graph data.
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $visitableType
     */
    public function getTotalVisitorsOverWeek($seller, $startDate, $endDate, $visitableType = null): array
    {
        $stats = [];

        $weekDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $visits = $this->visitRepository
            ->resetModel()
            ->select(
                DB::raw('DAYNAME(created_at) AS day'),
                DB::raw('COUNT(*) AS count')
            )
            ->whereNull('visitable_id')
            ->whereIn('channel_id', $this->channelIds)
            ->where('marketplace_seller_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('day')
            ->get();

        foreach ($weekDays as $day) {
            $total = $visits->where('day', $day)->first();

            $stats['label'][] = $day;
            $stats['total'][] = $total?->count ?? 0;
        }

        return $stats;
    }
}
