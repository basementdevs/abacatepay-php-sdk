<?php

declare(strict_types=1);

namespace AbacatePay\Customer\Entities;

use ArrayIterator;
use Countable;
use IteratorAggregate;

class CustomerEntityCollection implements Countable, IteratorAggregate
{
    /** @var CustomerEntity[] */
    private array $items;

    /**
     * @param  CustomerEntity[]  $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    public function add(CustomerEntity $customer): void
    {
        $this->items[] = $customer;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    /** @return CustomerEntity[] */
    public function all(): array
    {
        return $this->items;
    }

    public static function fromArray(array $data): self
    {
        $collection = new self();

        foreach ($data as $item) {
            $collection->add(CustomerEntity::fromArray($item));
        }

        return $collection;
    }
}
