<?php

namespace App\DTOs\Import;

final class SaleDTO
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
        public bool $is_supply,
        public bool $is_realization,
        public ?float $promo_code_discount,
        public ?string $warehouse_name,
        public ?string $country_name,
        public ?string $oblast_okrug_name,
        public ?string $region_name,
        public int $income_id,
        public ?string $sale_id,
        public ?int $odid,
        public int $spp,
        public float $for_pay,
        public float $finished_price,
        public float $price_with_disc,
        public int $nm_id,
        public ?string $subject,
        public ?string $category,
        public ?string $brand,
        public ?bool $is_storno,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            g_number:          (string) ($data['g_number'] ?? ''),
            date:              (string) ($data['date'] ?? ''),
            last_change_date:  $data['last_change_date'] ?? null,
            supplier_article:  $data['supplier_article'] ?? null,
            tech_size:         $data['tech_size'] ?? null,
            barcode:           (int) ($data['barcode'] ?? 0),
            total_price:       (float) ($data['total_price'] ?? 0),
            discount_percent:  (int) ($data['discount_percent'] ?? 0),
            is_supply:         (bool) ($data['is_supply'] ?? false),
            is_realization:    (bool) ($data['is_realization'] ?? false),
            promo_code_discount: isset($data['promo_code_discount']) ? (float) $data['promo_code_discount'] : null,
            warehouse_name:    $data['warehouse_name'] ?? null,
            country_name:      $data['country_name'] ?? null,
            oblast_okrug_name: $data['oblast_okrug_name'] ?? null,
            region_name:       $data['region_name'] ?? null,
            income_id:         (int) ($data['income_id'] ?? 0),
            sale_id:           $data['sale_id'] ?? null,
            odid:              isset($data['odid']) ? (int) $data['odid'] : null,
            spp:               (int) ($data['spp'] ?? 0),
            for_pay:           (float) ($data['for_pay'] ?? 0),
            finished_price:    (float) ($data['finished_price'] ?? 0),
            price_with_disc:   (float) ($data['price_with_disc'] ?? 0),
            nm_id:             (int) ($data['nm_id'] ?? 0),
            subject:           $data['subject'] ?? null,
            category:          $data['category'] ?? null,
            brand:             $data['brand'] ?? null,
            is_storno:         isset($data['is_storno']) ? (bool) $data['is_storno'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'g_number'            => $this->g_number,
            'date'                => $this->date,
            'last_change_date'    => $this->last_change_date,
            'supplier_article'    => $this->supplier_article,
            'tech_size'           => $this->tech_size,
            'barcode'             => $this->barcode,
            'total_price'         => $this->total_price,
            'discount_percent'    => $this->discount_percent,
            'is_supply'           => $this->is_supply,
            'is_realization'      => $this->is_realization,
            'promo_code_discount' => $this->promo_code_discount,
            'warehouse_name'      => $this->warehouse_name,
            'country_name'        => $this->country_name,
            'oblast_okrug_name'   => $this->oblast_okrug_name,
            'region_name'         => $this->region_name,
            'income_id'           => $this->income_id,
            'sale_id'             => $this->sale_id,
            'odid'                => $this->odid,
            'spp'                 => $this->spp,
            'for_pay'             => $this->for_pay,
            'finished_price'      => $this->finished_price,
            'price_with_disc'     => $this->price_with_disc,
            'nm_id'               => $this->nm_id,
            'subject'             => $this->subject,
            'category'            => $this->category,
            'brand'               => $this->brand,
            'is_storno'           => $this->is_storno,
        ];
    }
}
