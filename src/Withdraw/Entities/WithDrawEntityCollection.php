<?php

declare(strict_types=1);

namespace AbacatePay\Withdraw\Entities;

use ArrayIterator;
use Countable;
use IteratorAggregate;

class WithDrawEntityCollection implements Countable, IteratorAggregate
{
    /**
     * @var WithdrawEntity[]
     */
    private array $items = [];

    /**
     * @param  WithdrawEntity[]  $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    private function add(WithDrawEntity $entity): void
    {
        $this->items[] = $entity;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @return WithdrawEntity[]
     */
    public function all(): array
    {
        return $this->items;
    }

    public static function fromArray(array $data): self
    {
        $collection = new self;

        foreach ($data['data'] as $item) {
            $collection->add(WithdrawEntity::fromArray($item));
        }

        return $collection;
    }
}
