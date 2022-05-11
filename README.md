# Turkpin API Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/anilken/turkpin-api.svg?style=flat-square)](https://packagist.org/packages/anilken/turkpin-api)
[![Total Downloads](https://img.shields.io/packagist/dt/anilken/turkpin-api.svg?style=flat-square)](https://packagist.org/packages/anilken/turkpin-api)

**Turkpin API** With this package, you can create an epin order and deliver it to your customers. **[Official Documentation](https://dev.turkpin.com/view/3051588/TVza8YYA#038621b3-851b-4ac9-82ef-a2ad22f7cd20)**

## Installation

You can install the package via composer:

```bash
composer require anilken/turkpin-api
```

## Usage

```php
use Anilken\Turkpin\Turkpin;

$api = new Turkpin('username', 'password');

$api->epinList();

// Example Output

[
  {
    "id": "5",
    "name": "Echo of Soul (EOS)"
  },
  {
    "id": "633",
    "name": "Google Play kodu"
  }
  ...
]


$api->epinProducts($epin_id);

// Example Output

[
  {
    "name": "Google Play kodu 25 TL",
    "id": "8209",
    "stock": "377",
    "min_order": "1",
    "max_order": "0",
    "price": "24.5000",
    "tax_type": []
  },
  ...
]

$api->epinOrder($epin_id, $product_id, $qty = 1, $character = '');

// Example Output

{
  "status": "Success",
  "order_no": "21061113513501",
  "total_amount": "1",
  "list": [
    {
      "code": "8PFG-XXXX-FL3C-XXXX",
      "desc": "Test Ürün"
    }
  ]
}

$api->checkStatus($order_no);

// Example Output

{
  "status_code": "000",
  "order_no": "21061113513501",
  "order_code": "2",
  "order_status_description": "Siparişiniz Tamamlandı",
  "check_date": "11-06-2021 13:51:53",
  "extra": "İPTAL SEBEBI",
}

$api->checkBalance();

// Example Output

{
  "balance": "996501.2300",
  "credit": "0.0000",
  "bonus": "0.000",
  "spending": "3498.7700"
}

```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [ANILKEN](https://github.com/anilken)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
