<?php

namespace App\DTOs\Import;

final class StockDTO
{
    public function __construct(
        public string $date,
        public ?string $last_change_date,
        public ?string $supplier_article,
        public ?string $tech_size,
        public int $barcode,
        public int $quantity,
        public ?bool $is_supply,
        public ?bool $is_realization,
        public int $quantity_full,
        public ?string $warehouse_name,
        public int $in_way_to_client,
        public int $in_way_from_client,
        public int $nm_id,
        public ?string $subject,
        public ?string $category,
        public ?string $brand,
        public ?string $sc_code,
        public float $price,
        public int $discount,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            date:               (string) ($data['date'] ?? now()->toDateString()),
            last_change_date:   $data['last_change_date'] ?? null,
            supplier_article:   $data['supplier_article'] ?? null,
            tech_size:          $data['tech_size'] ?? null,
            barcode:            (int) ($data['barcode'] ?? 0),
            quantity:           (int) ($data['quantity'] ?? 0),
            is_supply:          isset($data['is_supply']) ? (bool) $data['is_supply'] : null,
            is_realization:     isset($data['is_realization']) ? (bool) $data['is_realization'] : null,
            quantity_full:      (int) ($data['quantity_full'] ?? 0),
            warehouse_name:     $data['warehouse_name'] ?? null,
            in_way_to_client:   (int) ($data['in_way_to_client'] ?? 0),
            in_way_from_client: (int) ($data['in_way_from_client'] ?? 0),
            nm_id:              (int) ($data['nm_id'] ?? 0),
            subject:            $data['subject'] ?? null,
            category:           $data['category'] ?? null,
            brand:              $data['brand'] ?? null,
            sc_code:            (string) ($data['sc_code'] ?? ''),
            price:              (float) ($data['price'] ?? 0),
            discount:           (int) ($data['discount'] ?? 0),
        );
    }

    public function toArray(): array
    {
        return [
            'date'               => $this->date,
            'last_change_date'   => $this->last_change_date,
            'supplier_article'   => $this->supplier_article,
            'tech_size'          => $this->tech_size,
            'barcode'            => $this->barcode,
            'quantity'           => $this->quantity,
            'is_supply'          => $this->is_supply,
            'is_realization'     => $this->is_realization,
            'quantity_full'      => $this->quantity_full,
            'warehouse_name'     => $this->warehouse_name,
            'in_way_to_client'   => $this->in_way_to_client,
            'in_way_from_client' => $this->in_way_from_client,
            'nm_id'              => $this->nm_id,
            'subject'            => $this->subject,
            'category'           => $this->category,
            'brand'              => $this->brand,
            'sc_code'            => $this->sc_code,
            'price'              => $this->price,
            'discount'           => $this->discount,
        ];
    }
}
