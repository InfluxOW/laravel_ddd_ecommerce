<?php

namespace App\Domains\Cart\Services;

use App\Domains\Cart\Models\Cart;
use App\Domains\Cart\Models\CartItem;
use App\Domains\Components\Generic\Utils\MathUtils;
use App\Domains\Components\Purchasable\Abstracts\Purchasable;
use App\Domains\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class CartService
{
    public static function make(string $currency, ?User $user): Cart
    {
        $cart = Cart::make(['price_items' => 0, 'price_items_discounted' => 0, 'currency' => $currency]);
        $cart->user()->associate($user);
        if ($user === null) {
            do {
                $key = self::generateCartKey();
                $cacheKey = self::getCartCacheKey(null, $key);
            } while (is_string($cacheKey) && Redis::exists($cacheKey));

            $cart->key = $key;
        }

        return $cart;
    }

    public static function find(?User $user, ?string $key): ?Cart
    {
        if ($user === null && $key === null) {
            return null;
        }

        return self::getFromCache($user, $key);
    }

    /**
     * @param Cart $cart
     * @param Model&Purchasable $purchasable
     * @param int $quantity
     * @return Cart
     */
    public static function add(Cart $cart, Model&Purchasable $purchasable, int $quantity): Cart
    {
        $item = $cart->items->where('purchasable_id', $purchasable->id)->where('purchasable_type', $purchasable::class)->first();
        if (isset($item)) {
            return self::update($cart, $purchasable, $quantity);
        }

        /** @var CartItem $item */
        $item = $cart->items()->make();
        $item->purchasable()->associate($purchasable);
        $item->quantity = (int) MathUtils::clamp($quantity, 0, CartItem::MAX_QUANTITY);
        $item->setRelation('cart', $cart);

        $cart->setRelation('items', $cart->items->push($item));

        $cart = self::recalculate($cart);
        self::cache($cart);

        return $cart;
    }

    /**
     * @param Cart $cart
     * @param Model&Purchasable $purchasable
     * @param int $quantity
     * @return Cart
     */
    public static function update(Cart $cart, Model&Purchasable $purchasable, int $quantity): Cart
    {
        $item = $cart->items->where('purchasable_id', $purchasable->id)->where('purchasable_type', $purchasable::class)->first();
        if ($item === null) {
            return self::add($cart, $purchasable, $quantity);
        }

        $item->quantity = (int) MathUtils::clamp($quantity, 0, CartItem::MAX_QUANTITY);

        $cart = self::recalculate($cart);
        self::cache($cart);

        return $cart;
    }

    public static function delete(?User $user, ?string $key): void
    {
        if ($user === null && $key === null) {
            return;
        }

        $cacheKey = self::getCartCacheKey($user, $key);
        if (isset($cacheKey)) {
            Redis::del($cacheKey);
        }
    }

    public static function save(Cart $cart): void
    {
        self::delete($cart->user, $cart->key);

        $cart->user_id = null;
        $cart->key = null;

        $cart = self::recalculate($cart);

        $cart->save();
        $cart->items()->saveMany($cart->items);
    }

    protected static function cache(Cart $cart): void
    {
        $cacheKey = self::getCartCacheKey($cart->user, $cart->key);
        if (isset($cacheKey)) {
            Redis::set($cacheKey, serialize($cart));
        }
    }

    protected static function getFromCache(?User $user, ?string $key): ?Cart
    {
        $cacheKey = self::getCartCacheKey($user, $key);
        if ($cacheKey === null) {
            return null;
        }

        $cart = Redis::get($cacheKey);

        return is_string($cart) ? unserialize($cart, [Cart::class]) : null;
    }

    protected static function recalculate(Cart $cart): Cart
    {
        $priceItems = 0;
        $priceItemsDiscounted = 0;
        $cart->items->map(function (CartItem $item) use ($cart, &$priceItems, &$priceItemsDiscounted): CartItem {
            /** @var Model&Purchasable $purchasable */
            $purchasable = $item->purchasable;

            /*
             * There is some strange bug related to casts: castable properties
             * randomly become `null` on call.
             * */

            $priceItem = (int) ($purchasable->getPurchasablePrice($cart->currency)->getAmount());
            $priceItemDiscounted = (int) ($purchasable->getPurchasablePriceDiscounted($cart->currency)?->getAmount() ?? $priceItem);
            $priceTotal = $priceItem * $item->quantity;
            $priceTotalDiscounted = $priceItemDiscounted * $item->quantity;

            $item->price_item = $priceItem;
            $item->price_item_discounted = $priceItemDiscounted;
            $item->price_total = $priceTotal;
            $item->price_total_discounted = $priceTotalDiscounted;

            $item->purchasable_data = $purchasable->getPurchasableData();

            $priceItems += $priceTotal;
            $priceItemsDiscounted += $priceTotalDiscounted;

            return $item;
        });

        $cart->price_items = $priceItems;
        $cart->price_items_discounted = $priceItemsDiscounted;

        return $cart;
    }

    private static function getCartCacheKey(?User $user, ?string $key): ?string
    {
        if ($user === null && $key === null) {
            return null;
        }

        return sprintf('carts.%s', ($user === null) ? $key : sprintf('user_id:%s', $user->id));
    }

    private static function generateCartKey(): string
    {
        return bin2hex(random_bytes(24));
    }
}
