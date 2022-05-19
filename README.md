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

## Exception Codes

| Code | Message                                                                                             |
|------|-----------------------------------------------------------------------------------------------------|
| 0	   | Empty api result try again                                                                          |
| 1	   | User not found, check your username and password.                                                   |
 | 2	   | There is no access from your IP address. Please check your IP Address.                              |
 | 3	   | Invalid command, check cmd parameter.                                                               |
 | 4	   | Message field is a mandatory field.                                                                 |
 | 5	   | An error occurred while processing.                                                                 |
 | 6	   | This Account is not authorized to use Turkpin API                                                   |
 | 7	   | This Account is not available for Turkpin API.                                                      |
 | 8	   | Game ID is missing                                                                                  |
 | 9	   | The server list for the game could not be found                                                     |
 | 10	  | Missing or incorrect order format.                                                                  |
 | 11	  | The product was not found.                                                                          |
 | 12	  | The product is out of stock.                                                                        |
 | 13	  | The number of orders must be min% s.                                                                |
 | 14	  | Dealer Balance Insufficient.                                                                        |
 | 15	  | Payment type is invalid                                                                             |
 | 16	  | Missing or incorrect notification format. Please check fields                                       |
 | 17	  | You have entered a missing or incorrect notification number. Please check the notification_id value |
 | 18	  | Number of orders must be max% s.                                                                    |
 | 19	  | System error, error code:% s                                                                        |
 | 20	  | Please enter correct operator and phone number for GSM TL top up                                    |
 | 21	  | The amount to be reported for payment must be greater than 0                                        |
 | 22	  | This product is not purchased by us.                                                                |
 | 23	  | The site is currently closed for an update or maintenance.                                          | 
 | 99   | Order Not Found. Check Order Number                                                                 |
 | 111  | XML Format Error. Please check the XML format you sent.                                             |
| ---  | Custom error codes added to this package                                                            |
| 101  | Failed to get game list                                                                             |
| 102  | Failed to get products list                                                                         |
| 103  | Failed to get balance                                                                               |

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
