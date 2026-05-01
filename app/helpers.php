<?php

use App\Models\Product;
use App\Models\Property;
use App\Models\OrderProduct;
use App\Models\ProductImage;
use App\Models\ProductPrice;
use App\Models\PropertyValue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;

function ends_with($s, $t)
{
    return \Illuminate\Support\Str::endsWith($s, $t);
}

function str_singular($s)
{
    return \Illuminate\Support\Str::singular($s);
}

function snake_case($s)
{
    return \Illuminate\Support\Str::snake($s);
}

function str_plural($s)
{
    return \Illuminate\Support\Str::plural($s);
}

function toIndianDateTime($datetime)
{
    return \Carbon\Carbon::parse($datetime)->format('d-m-Y h:i A');
}

function toIndianDate($date)
{
    return \Carbon\Carbon::parse($date)->format('d-m-Y');
}

function toIndianCurrency($number, $type = null)
{
    $number = (string) number_format((float) $number, 2, '.', '');
    $parts = explode('.', $number);

    $integer_part = $parts[0];
    $decimal_part = isset($parts[1]) ? rtrim($parts[1], '00') : '00';

    $last_three = substr($integer_part, -3);
    $remaining = substr($integer_part, 0, -3);

    if ($remaining !== '') {
        $remaining = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $remaining);
        $formatted_integer = $remaining . ',' . $last_three;
    } else {
        $formatted_integer = $last_three;
    }

    $decimal_part = ($decimal_part === '') ? '00' : $decimal_part;

    return $type == 'pdf' ? $formatted_integer . '.' . $decimal_part : '₹' . $formatted_integer . '.' . $decimal_part;
}



/**
 * Format a price (stored in GBP base) into the user's chosen currency.
 * Reads currency from session; defaults to GBP.
 */
/**
 * Format a price (stored in GBP base) into a specific currency.
 * Reads currency from session if $selected is null; defaults to GBP.
 */
function toCurrency($amountGbp, $selected = null): string
{
    if (!$selected) {
        $selected = session('currency', 'GBP');
    }

    // Use cache to avoid frequent DB queries
    $currency = \Illuminate\Support\Facades\Cache::remember('currency_' . $selected, 3600, function () use ($selected) {
        return \App\Models\Currency::where('code', $selected)->where('is_active', true)->first();
    });

    if (!$currency) {
        // Fallback to GBP if selected not found or inactive
        $currency = \Illuminate\Support\Facades\Cache::remember('currency_GBP', 3600, function () {
            return \App\Models\Currency::where('code', 'GBP')->first();
        });
    }

    if (!$currency) {
        return '£' . number_format($amountGbp, 2);
    }

    $converted = (float) $amountGbp * $currency->exchange_rate;

    if ($currency->code === 'INR') {
        return toIndianCurrency($converted);
    }

    return $currency->symbol . number_format($converted, 2);
}

/**
 * Get the current currency symbol.
 */
function getCurrencySymbol(): string
{
    $selected = session('currency', 'GBP');

    $currency = \Illuminate\Support\Facades\Cache::remember('currency_' . $selected, 3600, function () use ($selected) {
        return \App\Models\Currency::where('code', $selected)->where('is_active', true)->first();
    });

    if (!$currency) {
        // Fallback to GBP if selected not found or inactive
        $currency = \Illuminate\Support\Facades\Cache::remember('currency_GBP', 3600, function () {
            return \App\Models\Currency::where('code', 'GBP')->first();
        });
    }

    return $currency ? $currency->symbol : '£';
}

/**
 * Format an amount with a specific currency symbol (no conversion).
 */
function formatCurrency($amount, $currencyCode): string
{
    $currency = \Illuminate\Support\Facades\Cache::remember('currency_' . $currencyCode, 3600, function () use ($currencyCode) {
        return \App\Models\Currency::where('code', $currencyCode)->first();
    });

    if ($currencyCode === 'INR') {
        return toIndianCurrency($amount);
    }

    $symbol = $currency ? $currency->symbol : '£';
    return $symbol . number_format($amount, 2);
}

function getSystemRoles()
{
    $permission_seeder = new \Database\Seeders\PermissionSeeder;
    $roles = $permission_seeder->roles;
    $systemRoles = [];
    foreach ($roles as $role => $permissions) {
        $systemRoles[] = $role;
    }
    return $systemRoles;
}

function getCountingNumber($model, $prefix, $field_name, $year = true)
{
    $modelClass = "\App\Models\\" . $model;
    // Assuming you have an 'number_field' column in your database table

    $latestNumber = $modelClass::max($field_name);
    if ($latestNumber === null) {
        // No records in the database yet, start with 1
        $lastNumberPart = 1;
    } else {
        // Extract the last part of the latest number and increment it
        $parts = explode('-', $latestNumber);
        $lastNumberPart = (int) end($parts);
        $lastNumberPart++; // Increment the last number part
    }
    $currentYear = date('Y');
    $currentMonth = date('n');
    $financialYearStart = ($currentMonth >= 4) ? substr($currentYear, -2) : substr(($currentYear - 1), -2);
    $financialYearEnd = ($currentMonth >= 4) ? substr(($currentYear + 1), -2) : substr($currentYear, -2);
    $number = $prefix . '-' . $financialYearStart . '-' . $financialYearEnd . '-' . str_pad($lastNumberPart, 4, '0', STR_PAD_LEFT);
    if (!$year) {
        $number = $prefix . '-' . str_pad($lastNumberPart, 4, '0', STR_PAD_LEFT);
    }
    return $number;
}


function desktop()
{
    $detect = new Mobile_Detect;
    if ($detect->isMobile()) {
        return false;
    } else {
        return true;
    }
}

function mobile()
{
    $detect = new Mobile_Detect;
    if ($detect->isMobile()) {
        return true;
    } else {
        return false;
    }
}

function getDiscountedPercentage($originalPrice, $discountedPrice)
{
    if ($originalPrice == 0) {
        return '0%';
    }
    $percentage = (($originalPrice - $discountedPrice) / $originalPrice) * 100;
    $formattedPercentage = round($percentage, 0); // Round to 0 decimal places
    return $formattedPercentage . '%';
}

function inRupe($num, $symbol = true, $pdf = false)
{
    $nums = explode('.', $num);
    $num = $nums[0] ?? '0';

    $minus = false;
    if (substr($num, 0, 1) === '-') {
        $minus = true;
        $num = substr($num, strpos($num, "-") + 1);
    }

    $explrestunits = "";
    if (strlen($num) > 3) {

        $lastthree = substr($num, strlen($num) - 3, strlen($num));

        $restunits = substr($num, 0, strlen($num) - 3); // extracts the last three digits
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            // creates each of the 2's group and adds a comma to the end
            if ($i == 0) {
                $explrestunits .= (int) $expunit[$i] . ","; // if is first value , convert into integer
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }

    if ($minus) {
        $thecash = "-" . $thecash;
    }

    if (isset($nums[1]) && $nums[1] > 0) {
        $thecash = $thecash . "." . $nums[1];
    } else {
        $thecash = $thecash;
    }

    if ($symbol) {
        $thecash = '₹ ' . $thecash . '/-';

        return $thecash;
    } elseif ($pdf) {

        // return "Rs. ".$thecash.'/-';
        return $thecash . '/-';
    } else {

        return html_entity_decode('₹ ' . $thecash . '/-');
    }
}



// * Send SMS - START * //
function send_sms($mobile, $otp, $message_id)
{
    if (env('APP_ENV') != 'production') {
        return true;
    }

    $fields = array(
        "sender_id" => "BAMSTR",
        "message" => $message_id,
        "variables_values" => $otp,
        "route" => "dlt",
        "numbers" => $mobile,
        "template_id" => "183016"
    );

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => http_build_query($fields),
        CURLOPT_HTTPHEADER => array(
            "authorization: F9eWoGw0y2YNsi4ILX8QtCMU5pzc37TkrfAJhEPdDjgvqaxlSn6Khc3tIdRxzGkMjuNOCVmXUJPyWgiL",
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded"
        ),
    ));

    $response = curl_exec($curl);

    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return "cURL Error #: " . $err;
    } else {
        return $response;
    }
}

// * Send SMS - END * //

// * Products Helper Functions - START * //

function getProductPropertyValues($product_id, $label)
{
    $product = Product::find($product_id);

    return $product->productPropertyValues->where('property.label', $label);
}

function isOrderedProduct($product_id)
{
    $user = Auth::user();
    $is_ordered_product = false;

    if ($user) {
        $is_ordered_product = $user->orders()->where('payment_status', 'COMPLETED')->whereHas('products', function ($query) use ($product_id) {
            $query->where('product_id', $product_id);
        })->exists();
    }

    return $is_ordered_product;
}

// * Products Helper Functions - END * //

// * Cart Helper Functions - START * //
function getCartData($cart)
{
    $product_image = ProductImage::where('product_id', $cart->product_id)
        ->whereIn('property_value_id', $cart->property_values)
        ->pluck('image')
        ->first();

    $product_price = ProductPrice::where('product_id', $cart->product_id)
        ->whereJsonContains('property_values', array_map('intval', $cart->property_values))
        ->first();

    $property_values = PropertyValue::whereIn('id', $cart->property_values)->pluck('name')->toArray();

    $data = [
        'price' => $product_price ? $product_price->selling_price : 0,
        'image' => Storage::url($product_image),
        'property_values' => implode(' / ', $property_values),
    ];

    return $data;
}
// * Cart Helper Functions - END * //

// * Collections Helper Functions - START * //
function getCollectionProducts($collection)
{
    $products = Product::whereIn('id', $collection->product_ids)->get();

    return $products;
}
// * Collections Helper Functions - END * //


function get_months()
{
    return [
        '1' => 'January',
        '2' => 'February',
        '3' => 'March',
        '4' => 'April',
        '5' => 'May',
        '6' => 'June',
        '7' => 'July',
        '8' => 'August',
        '9' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December',
    ];
}

function get_ordermonths()
{
    return [
        '1' => 'Jan',
        '2' => 'Feb',
        '3' => 'Mar',
        '4' => 'Apr',
        '5' => 'May',
        '6' => 'Jun',
        '7' => 'Jul',
        '8' => 'Aug',
        '9' => 'Sept',
        '10' => 'Oct',
        '11' => 'Nov',
        '12' => 'Dec',
    ];
}


function formatNumber($number)
{
    $formatted_number = number_format($number, 0, '.', ',');

    return $formatted_number;
}


function getYoutubeEmbedUrl($url)
{
    $pattern = '/(?:youtube\.com\/(?:[^\/]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
    preg_match($pattern, $url, $matches);
    return isset($matches[1]) ? $matches[1] : null;
}

function is_mobile()
{
    $agent = new Agent();
    return $agent->isMobile();
}
