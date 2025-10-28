<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Coupon\Http\Request;

use Basement\AbacatePay\Coupon\Enums\CouponDiscountKindEnum;
use InvalidArgumentException;

final class CreateCouponRequestBuilder
{
    private ?string $code = null;

    private ?string $notes = null;

    private ?int $maxRedeems = null;

    private ?CouponDiscountKindEnum $discountKind = null;

    private ?int $discount = null;

    private ?array $metadata = null;

    public static function make(): self
    {
        return new self();
    }

    public function code(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function notes(string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function maxRedeems(int $maxRedeems): self
    {
        $this->maxRedeems = $maxRedeems;

        return $this;
    }

    public function discountKind(CouponDiscountKindEnum $kind): self
    {
        $this->discountKind = $kind;

        return $this;
    }

    public function discount(int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function metadata(?array $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function percentage(int $value): self
    {
        $this->discountKind = CouponDiscountKindEnum::Percentage;
        $this->discount = $value;

        return $this;
    }

    public function fixed(int $value): self
    {
        $this->discountKind = CouponDiscountKindEnum::Fixed;
        $this->discount = $value;

        return $this;
    }

    public function build(): CreateCouponRequest
    {
        $missing = [];
        if ($this->code === null) {
            $missing[] = 'code';
        }

        if ($this->notes === null) {
            $missing[] = 'notes';
        }

        if ($this->maxRedeems === null) {
            $missing[] = 'maxRedeems';
        }

        if (!$this->discountKind instanceof CouponDiscountKindEnum) {
            $missing[] = 'discountKind';
        }

        if ($this->discount === null) {
            $missing[] = 'discount';
        }

        if ($missing !== []) {
            throw new InvalidArgumentException('Missing required fields: '.implode(', ', $missing));
        }

        return new CreateCouponRequest(
            code: $this->code,
            notes: $this->notes,
            maxRedeems: $this->maxRedeems,
            discountKind: $this->discountKind,
            discount: $this->discount,
            metadata: $this->metadata ?? ['null' => null],
        );
    }
}
