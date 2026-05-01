<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Review;
use App\Models\Category;
use App\Models\Property;
use App\Models\Wishlist;
use App\Models\BaseModel;
use App\Traits\Hashidable;
use App\Models\SubCategory;
use App\Models\ProductImage;
use App\Models\ProductPrice;
use App\Models\ProductPropertyValue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends BaseModel
{
    use HasFactory, Hashidable;

    protected $fillable = [
        'primary_property_id',
        'category_ids',
        'sub_category_ids',
        'name',
        'tag_line',
        'slug',
        'sku',
        'hsn',
        'highlights',
        'description',
        'keywords',
        'views_count',
        'reviews_count',
        'average_rating',
        'index',
        'featured',
        'status',
        'gender',
        'video_type',
        'video',
        'youtube_link',
    ];

    protected $appends = [
        'is_wishlisted',
        'is_added_to_cart',
        'primary_price',
        'primary_property_values',
    ];

    protected $casts = [
        'category_ids' => 'array',
        'sub_category_ids' => 'array',
    ];

    public function primaryProperty()
    {
        return $this->belongsTo(Property::class, 'primary_property_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function productPropertyValues()
    {
        return $this->hasMany(ProductPropertyValue::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function productPrices()
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 'ACTIVE');
    }

    public function getIsWishlistedAttribute()
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        return $this->wishlists()->where('user_id', $user->id)->exists();
    }

    public function getIsAddedToCartAttribute()
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        return $this->carts()->where('user_id', $user->id)->exists();
    }

    public function getPrimaryPropertyValuesAttribute()
    {
        return $this->productPropertyValues()->where('is_primary', 'YES')->pluck('property_value_id')->toArray();
    }

    public function getPrimaryPriceAttribute()
    {
        return $this->productPrices()
            ->whereJsonContains('property_values', $this->primary_property_values)
            ->first();
    }

    public function getPrice(array $property_values = [])
    {
        $product_price = null;

        if (!empty($property_values)) {
            $product_price = $this->productPrices()
                ->whereJsonContains('property_values', array_map('intval', $property_values))
                ->first();
        }

        return $product_price ?? $this->primary_price;
    }


    public function getImage(array $property_values = [])
    {
        $product_property_values = $this->productPropertyValues()
            ->where('property_id', $this->primary_property_id)
            ->pluck('property_value_id')
            ->toArray();

        if (!empty($property_values)) {
            foreach ($property_values as $property_value) {
                if (in_array($property_value, $product_property_values)) {
                    $product_image = $this->productImages()
                        ->where('property_value_id', $property_value)
                        ->first();

                    if ($product_image) {
                        return Storage::url($product_image->image);
                    }
                }
            }
        }

        $product_image = $this->productImages()
            ->whereIn('property_value_id', $this->primary_property_values)
            ->first();

        if ($product_image) {
            return Storage::url($product_image->image);
        }
    }

    public function isOutOfStock()
    {
        return $this->productPrices()->sum('stock') <= 0;
    }
}
