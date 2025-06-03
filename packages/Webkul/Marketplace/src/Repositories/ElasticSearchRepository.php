<?php

namespace Webkul\Marketplace\Repositories;

use Illuminate\Support\Arr;
use Webkul\Core\Facades\ElasticSearch;
use Webkul\Product\Repositories\ElasticSearchRepository as Repository;

class ElasticSearchRepository extends Repository
{
    /**
     * Returns product ids from Elasticsearch
     */
    public function search(array $params, array $options): array
    {
        $name = $params['query'] ?? null;

        $params = Arr::except($params, ['query']);

        $filters = $this->getFilters($params);

        if ($name) {
            $filters['must'][] = [
                'wildcard' => [
                    'name' => '*'.strtolower($name).'*',
                ],
            ];
        }

        if (! empty($params['seller_id'])) {
            $filters['filter'][] = [
                'term' => [
                    'seller_id' => $params['seller_id'],
                ],
            ];
        }

        $results = Elasticsearch::search([
            'index' => $params['index'] ?? $this->getIndexName(),
            'body'  => [
                'from'          => $options['from'],
                'size'          => $options['limit'],
                'stored_fields' => [],
                'query'         => [
                    'bool' => $filters ?: new \stdClass(),
                ],
                'sort'          => $this->getSortOptions($options),
            ],
        ]);

        return [
            'total' => $results['hits']['total']['value'],
            'ids'   => collect($results['hits']['hits'])->pluck('_id')->toArray(),
        ];
    }
}
