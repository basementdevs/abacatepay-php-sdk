<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Coupon\Entities;

use ArrayIterator;
use Countable;
use IteratorAggregate;

final class CouponCollection implements Countable, IteratorAggregate
{
    /**
     * @var CouponEntity[]
     */
    private array $coupons = [];

    public function __construct(array $coupons = [])
    {
        foreach ($coupons as $coupon) {
            $this->add($coupon);
        }
    }

    public static function fromArray(array $data): self
    {
        $collection = new self();

        foreach ($data['data'] as $coupon) {
            $collection->add(CouponEntity::fromArray($coupon));
        }

        return $collection;
    }

    public function add(CouponEntity $coupon): void
    {
        $this->coupons[] = $coupon;
    }

    /**
     * @return CouponEntity[]
     */
    public function all(): array
    {
        return $this->coupons;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->coupons);
    }

    public function count(): int
    {
        return count($this->coupons);
    }
}
