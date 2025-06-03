<?php

namespace Webkul\Marketplace\Helpers\Product;

class ProgressBar
{
    /**
     * Total fields count.
     */
    protected int $totalFields;

    /**
     * Filled fields count.
     */
    protected int $filledFields;

    /**
     * Get product progress details.
     *
     * @param  object  $product
     */
    public function getDetails($product): object
    {
        $this->resetCounters();

        $progress = $this->calculateProgress($product);

        return (object) [
            'progress'         => $progress,
            'total_fields'     => $this->totalFields,
            'filled_fields'    => $this->filledFields,
            'remaining_fields' => $this->totalFields - $this->filledFields,
        ];
    }

    /**
     * Calculate product progress.
     *
     * @param  object  $product
     */
    public function calculateProgress($product): int
    {
        $this->calculateAttributeProgress($product);
        $this->calculateCategoryProgress($product);
        $this->calculateImageProgress($product);
        $this->calculateVideoProgress($product);

        return $this->totalFields > 0
            ? round(($this->filledFields / $this->totalFields) * 100)
            : 0;
    }

    /**
     * Calculate attribute progress.
     *
     * @param  object  $product
     */
    private function calculateAttributeProgress($product): void
    {
        foreach ($product->attribute_family->attribute_groups as $group) {
            $attributes = $product->getEditableAttributes($group);

            $this->incrementFieldCounts($attributes, $product);
        }
    }

    /**
     * Calculate category progress.
     *
     * @param  object  $product
     */
    private function calculateCategoryProgress($product): void
    {
        if (seller()->user()?->category()?->exists()) {
            $this->incrementFieldCount($product->categories ?? null);
        }
    }

    /**
     * Calculate image progress.
     *
     * @param  object  $product
     */
    private function calculateImageProgress($product): void
    {
        $this->incrementFieldCount($product->images ?? null);
    }

    /**
     * Calculate video progress.
     *
     * @param  object  $product
     */
    private function calculateVideoProgress($product): void
    {
        $this->incrementFieldCount($product->videos ?? null);
    }

    /**
     * Increment total and filled field counts based on attributes.
     *
     * @param  object  $attributes
     * @param  object  $product
     */
    private function incrementFieldCounts($attributes, $product): void
    {
        $this->totalFields += count($attributes);

        foreach ($attributes as $attribute) {
            if ($product->{$attribute->code}) {
                $this->filledFields++;
            }
        }
    }

    /**
     * Increment total and filled field counts for a single field.
     *
     * @param  mixed  $field
     */
    private function incrementFieldCount($field): void
    {
        $this->totalFields++;

        if (
            $field
            && $field->isNotEmpty()
        ) {
            $this->filledFields++;
        }
    }

    /**
     * Reset counters.
     */
    private function resetCounters(): void
    {
        $this->totalFields = 0;
        $this->filledFields = 0;
    }

    /**
     * Get the total number of fields.
     */
    public function getTotalFields(): int
    {
        return $this->totalFields;
    }

    /**
     * Get the number of filled fields.
     */
    public function getFilledFields(): int
    {
        return $this->filledFields;
    }
}
