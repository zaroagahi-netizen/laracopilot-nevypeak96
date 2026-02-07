<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use SoftDeletes, HasTranslations;

    protected $fillable = [
        'name',
        'description',
        'sku',
        'price',
        'compare_at_price',
        'type',
        'stock_quantity',
        'min_age',
        'max_age',
        'digital_file_path',
        'images',
        'is_new',
        'is_customizable',
        'active',
        'sort_order',
    ];

    public $translatable = ['name', 'description'];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'min_age' => 'integer',
        'max_age' => 'integer',
        'images' => 'array',
        'is_new' => 'boolean',
        'is_customizable' => 'boolean',
        'active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function terms()
    {
        return $this->morphToMany(Term::class, 'termable');
    }

    public function ageGroups()
    {
        return $this->terms()->whereHas('taxonomy', function ($query) {
            $query->where('slug', 'age-group');
        });
    }

    public function developmentAreas()
    {
        return $this->terms()->whereHas('taxonomy', function ($query) {
            $query->where('slug', 'development-area');
        });
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopePhysical($query)
    {
        return $query->where('type', 'physical');
    }

    public function scopeDigital($query)
    {
        return $query->where('type', 'digital');
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeForAge($query, $age)
    {
        return $query->where(function ($q) use ($age) {
            $q->whereNull('min_age')
              ->orWhere(function ($q2) use ($age) {
                  $q2->where('min_age', '<=', $age)
                     ->where(function ($q3) use ($age) {
                         $q3->whereNull('max_age')
                            ->orWhere('max_age', '>=', $age);
                     });
              });
        });
    }

    // Accessors & Mutators
    public function getDiscountPercentAttribute()
    {
        if ($this->compare_at_price && $this->compare_at_price > $this->price) {
            return round((($this->compare_at_price - $this->price) / $this->compare_at_price) * 100);
        }
        return 0;
    }

    public function getIsDigitalAttribute()
    {
        return $this->type === 'digital';
    }

    public function getStockStatusAttribute()
    {
        if ($this->is_digital) {
            return 'unlimited';
        }
        
        if ($this->stock_quantity <= 0) {
            return 'out_of_stock';
        } elseif ($this->stock_quantity <= 5) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    public function getStockMessageAttribute()
    {
        $status = $this->stock_status;
        
        return match($status) {
            'unlimited' => __('Digital Product'),
            'out_of_stock' => __('Out of Stock'),
            'low_stock' => __('Only :count left!', ['count' => $this->stock_quantity]),
            'in_stock' => __('In Stock'),
        };
    }

    public function getBadgesAttribute()
    {
        $badges = [];
        
        if ($this->is_new) {
            $badges[] = ['type' => 'new', 'label' => __('New')];
        }
        
        if ($this->is_digital) {
            $badges[] = ['type' => 'digital', 'label' => __('Digital Product')];
        }
        
        if ($this->is_customizable) {
            $badges[] = ['type' => 'customizable', 'label' => __('Customizable')];
        }
        
        if ($this->discount_percent > 0) {
            $badges[] = ['type' => 'discount', 'label' => "-{$this->discount_percent}%"];
        }
        
        return $badges;
    }

    public function getPrimaryImageAttribute()
    {
        if ($this->images && count($this->images) > 0) {
            return $this->images[0];
        }
        return null;
    }

    // Methods
    public function hasAvailableStock($quantity = 1)
    {
        if ($this->is_digital) {
            return true;
        }
        return $this->stock_quantity >= $quantity;
    }

    public function decrementStock($quantity = 1)
    {
        if (!$this->is_digital) {
            $this->decrement('stock_quantity', $quantity);
        }
    }

    public function incrementStock($quantity = 1)
    {
        if (!$this->is_digital) {
            $this->increment('stock_quantity', $quantity);
        }
    }

    public function isInAgeRange($age)
    {
        if (is_null($this->min_age) && is_null($this->max_age)) {
            return true;
        }
        
        if (!is_null($this->min_age) && $age < $this->min_age) {
            return false;
        }
        
        if (!is_null($this->max_age) && $age > $this->max_age) {
            return false;
        }
        
        return true;
    }
}