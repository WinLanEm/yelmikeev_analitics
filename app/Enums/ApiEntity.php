<?php

namespace App\Enums;

enum ApiEntity:string
{
    case STOCKS = 'stocks';
    case INCOMES = 'incomes';
    case ORDERS = 'orders';
    case SALES = 'sales';
}
