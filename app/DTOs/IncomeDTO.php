<?php

namespace App\DTOs;

final class IncomeDTO
{
    public function __construct(
        public int $income_id,
        public ?string $number,
        public string $date,
        public ?string $last_change_date,
        public ?string $supplier_article,
        public ?string $tech_size,
        public int $barcode,
        public int $quantity,
        public float $total_price,
        public ?string $date_close,
        public ?string $warehouse_name,
        public int $nm_id,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            income_id:        (int) ($data['income_id'] ?? 0),
            number:           (string) ($data['number'] ?? ''),
            date:             (string) ($data['date'] ?? ''),
            last_change_date: $data['last_change_date'] ?? null,
            supplier_article: $data['supplier_article'] ?? null,
            tech_size:        $data['tech_size'] ?? null,
            barcode:          (int) ($data['barcode'] ?? 0),
            quantity:         (int) ($data['quantity'] ?? 0),
            total_price:      (float) ($data['total_price'] ?? 0),
            date_close:       $data['date_close'] ?? null,
            warehouse_name:   $data['warehouse_name'] ?? null,
            nm_id:            (int) ($data['nm_id'] ?? 0),
        );
    }

    public function toArray(): array
    {
        return [
            'income_id'        => $this->income_id,
            'number'           => $this->number,
            'date'             => $this->date,
            'last_change_date' => $this->last_change_date,
            'supplier_article' => $this->supplier_article,
            'tech_size'        => $this->tech_size,
            'barcode'          => $this->barcode,
            'quantity'         => $this->quantity,
            'total_price'      => $this->total_price,
            'date_close'       => $this->date_close,
            'warehouse_name'   => $this->warehouse_name,
            'nm_id'            => $this->nm_id,
        ];
    }
}
