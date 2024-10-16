# Laravel Bkash
A PHP Laravel Package For Bkash Tokenized Payment Gateway

## Installation
You can install the package using composer. Run below command.
```bash
composer require pranaycb/laravel-bkash
```

### Publish Config File
After successfully installing the package you need to publish the config file. Run this command. This will make a copy of the config file in your application's config directory
```bash
php artisan vendor:publish --provider="PranayCb\LaravelBkash\BkashServiceProvider" --tag=config
```

### Set .env Variables
In your .env file, add the necessary variables:
```
BKASH_ENVIRONMENT=sandbox
BKASH_APP_KEY=your_app_key
BKASH_APP_SECRET=your_app_secret
BKASH_USERNAME=your_username
BKASH_PASSWORD=your_password
```

### Autoloading and Testing
Run ```composer dump-autoload ``` to ensure that Composer recognizes your new package.

### Usage

You can inject the BkashService into controllers or other services as needed. In your Laravel application, you can use the Bkash service as follows:

```php

use PranayCb\LaravelBkash\BkashService;

class PaymentController extends Controller
{
    protected $bkash;

    public function __construct(BkashService $bkash)
    {
        $this->bkash = $bkash;
    }
    
    /**
     * Create  payment
     */
    public function createPayment(Request $request)
    {
        $paymentData = $request->all(); // Adjust as necessary
        $response = $this->bkash->createPayment($paymentData);

        return response()->json($response); // You will get the bkashURL when request is successful
    }
    
    /**
     * Execute payment
     */
    public function executePayment(Request $request)
    {
        $paymentID = $request->paymentID;
        $response = $this->bkash->executePayment($paymentID);

        return response()->json($response);
    }

    /**
     * Query payment
     */
    public function queryPayment(Request $request)
    {
        $trxid = $request->trxID;
        $response = $this->bkash->queryPayment($trxid);

        return response()->json($response);
    }
}

```

### Bkash Official Documentation
Follow this link https://developer.bka.sh/docs/tokenized-checkout-process to know details how bkash payment gateway works.

## Need Help?

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

If you have any problem regarding the file please feel free to contact me via email pranaycb.ctg@gmail.com

Happy Coding 🤗🤗

## License

[MIT](https://choosealicense.com/licenses/mit/)





