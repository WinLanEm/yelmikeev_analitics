<?php

namespace App\DTOs;

final class OrderDTO
{
    public function __construct(
        public string $g_number,
        public string $date,
        public ?string $last_change_date,
        public ?string $supplier_article,
        public ?string $tech_size,
        public int $barcode,
        public float $total_price,
        public int $discount_percent,
        public ?string $warehouse_name,
        public ?string $oblast,
        public int $income_id,
        public int $odid,
        public int $nm_id,
        public ?string $subject,
        public ?string $category,
        public ?string $brand,
        public bool $is_cancel,
        public ?string $cancel_dt,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            g_number:         (string) ($data['g_number'] ?? ''),
            date:             (string) ($data['date'] ?? ''),
            last_change_date: $data['last_change_date'] ?? null,
            supplier_article: $data['supplier_article'] ?? null,
            tech_size:        $data['tech_size'] ?? null,
            barcode:          (int) ($data['barcode'] ?? 0),
            total_price:      (float) ($data['total_price'] ?? 0),
            discount_percent: (int) ($data['discount_percent'] ?? 0),
            warehouse_name:   $data['warehouse_name'] ?? null,
            oblast:           $data['oblast'] ?? null,
            income_id:        (int) ($data['income_id'] ?? 0),
            odid:             (int) ($data['odid'] ?? 0),
            nm_id:            (int) ($data['nm_id'] ?? 0),
            subject:          $data['subject'] ?? null,
            category:         $data['category'] ?? null,
            brand:            $data['brand'] ?? null,
            is_cancel:        (bool) ($data['is_cancel'] ?? false),
            cancel_dt:        $data['cancel_dt'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'g_number'         => $this->g_number,
            'date'             => $this->date,
            'last_change_date' => $this->last_change_date,
            'supplier_article' => $this->supplier_article,
            'tech_size'        => $this->tech_size,
            'barcode'          => $this->barcode,
            'total_price'      => $this->total_price,
            'discount_percent' => $this->discount_percent,
            'warehouse_name'   => $this->warehouse_name,
            'oblast'           => $this->oblast,
            'income_id'        => $this->income_id,
            'odid'             => $this->odid,
            'nm_id'            => $this->nm_id,
            'subject'          => $this->subject,
            'category'         => $this->category,
            'brand'            => $this->brand,
            'is_cancel'        => $this->is_cancel,
            'cancel_dt'        => $this->cancel_dt,
        ];
    }
}
