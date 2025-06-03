<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Webkul\Marketplace\Contracts\Seller as SellerContract;
use Webkul\Marketplace\Database\Factories\SellerFactory;
use Webkul\Marketplace\Mail\ResetPasswordNotification;

class Seller extends Authenticatable implements SellerContract
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'marketplace_sellers';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        '_token',
        'logo',
        'banner',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'allowed_product_types' => 'array',
        'password'              => 'hashed',
    ];

    /**
     * Append to the model attributes
     *
     * @var array
     */
    protected $appends = [
        'seller_id',
        'shop_url',
        'logo_url',
        'banner_url',
        'full_address',
    ];

    /**
     * Get parent id in case of children seller.
     */
    public function getSellerIdAttribute()
    {
        return $this->parent ? $this->parent->id : $this->id;
    }

    /**
     * Get parent url in case of children seller.
     */
    public function getShopUrlAttribute()
    {
        return $this->parent ? $this->parent->url : $this->url;
    }

    /**
     * Get logo image url attribute.
     */
    public function getLogoUrlAttribute()
    {
        if (! $this->logo) {
            return;
        }

        return Storage::url($this->logo);
    }

    /**
     * Get banner image url attribute.
     */
    public function getBannerUrlAttribute()
    {
        if (! $this->banner) {
            return;
        }

        return Storage::url($this->banner);
    }

    /**
     * Get the seller's full address attribute.
     */
    public function getFullAddressAttribute()
    {
        $addressParts = array_filter([
            implode(', ', array_filter(explode(PHP_EOL, $this->address))),
            $this->city,
            $this->state,
            $this->postcode,
            $this->country ? "({$this->country})" : null,
        ]);

        return implode(', ', $addressParts);
    }

    /**
     * Get the sellers's products.
     */
    public function products(): HasMany
    {
        return $this->hasMany(ProductProxy::modelClass(), 'marketplace_seller_id');
    }

    /**
     * Get the seller's reviews.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ReviewProxy::modelClass(), 'marketplace_seller_id');
    }

    /**
     * Get the product's reviews.
     */
    public function productReviews(): HasMany
    {
        return $this->hasMany(ProductReviewProxy::modelClass(), 'marketplace_seller_id');
    }

    /**
     * Get the seller's orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(OrderProxy::modelClass(), 'marketplace_seller_id');
    }

    /**
     * Get the seller's category.
     */
    public function category(): HasOne
    {
        return $this->hasOne(SellerCategoryProxy::modelClass(), 'marketplace_seller_id');
    }

    /**
     * Get the seller's shipping.
     */
    public function flags(): HasMany
    {
        return $this->hasMany(SellerFlagProxy::modelClass(), 'marketplace_seller_id');
    }

    /**
     * Show red flag.
     */
    public function showRedFlag(): bool
    {
        return core()->getConfigData('marketplace.settings.seller.show_red_flag')
            && ($this->flags()->count() > core()->getConfigData('marketplace.settings.seller.red_flag_limit'));
    }

    /*
     * Get the parent seller
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the seller's role.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(RoleProxy::modelClass(), 'marketplace_role_id');
    }

    /**
     * Get the seller's communication.
     */
    public function communication(): HasOne
    {
        return $this->hasOne(CommunicationProxy::modelClass(), 'marketplace_seller_id');
    }

    /**
     * Checks if seller has permission to perform certain action.
     */
    public function hasPermission(string $permission): bool
    {
        if (
            $this->role->permission_type == 'custom'
            && ! $this->role->permissions
        ) {
            return false;
        }

        return in_array($permission, $this->role->permissions);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Get the factory instance.
     */
    protected static function newFactory()
    {
        return SellerFactory::new();
    }

    /**
     * Boot method to add event listener.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($seller) {
            $requiredFields = [
                'name',
                'email',
                'url',
                'phone',
                'address',
                'city',
                'state',
                'postcode',
                'country',
                'shop_title',
                'description',
            ];

            $isComplete = collect($requiredFields)->every(fn ($field) => ! empty($seller->$field));

            $seller->is_profile_completed = $isComplete ? 1 : 0;
        });
    }

    /**
     * Get the seller's profile score.
     */
    public function getProfileScoreAttribute()
    {
        $attributes = $this->getAttributes();

        $skippableAttributes = [
            'id',
            'password',
            'is_approved',
            'marketplace_role_id',
            'parent_id',
            'is_suspended',
            'is_profile_completed',
            'commission_enable',
            'commission_percentage',
            'allowed_product_types',
            'enable_minimum_order_amount',
            'locale',
            'created_at',
            'updated_at',
        ];

        if (! core()->getConfigData('marketplace.settings.seller.enable_minimum_order_amount')) {
            $skippableAttributes[] = 'minimum_order_amount';
        }

        $attributes = Arr::except($attributes, $skippableAttributes);

        $totalFields = count($attributes);
        $filledFields = count(Arr::where($attributes, fn ($value) => ! empty($value)));

        $score = $totalFields > 0 ? ($filledFields / $totalFields) * 100 : 0;

        return round($score);
    }
}
