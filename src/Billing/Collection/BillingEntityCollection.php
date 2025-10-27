<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Billing\Collection;

use ArrayIterator;
use Basement\AbacatePay\Billing\Entities\BillingEntity;
use Countable;
use IteratorAggregate;

final class BillingEntityCollection implements Countable, IteratorAggregate
{
    /** @var BillingEntity[] */
    private array $items = [];

    /**
     * @param  BillingEntity[]  $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    public function add(BillingEntity $billing): void
    {
        $this->items[] = $billing;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    /** @return BillingEntity[] */
    public function all(): array
    {
        return $this->items;
    }

    public static function fromArray(array $data): self
    {
        $collection = new self();

        foreach ($data as $item) {
            $collection->add(BillingEntity::fromArray($item));
        }

        return $collection;
    }
}
