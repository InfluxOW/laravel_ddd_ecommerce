<?php

namespace App\Domains\Cart\Services;

use App\Domains\Cart\Models\Cart;
use App\Domains\Cart\Models\CartItem;
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
            $cart->key = self::generateCartKey();
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
        /** @var CartItem $item */
        $item = $cart->items()->make();
        $item->purchasable()->associate($purchasable);
        $item->quantity = $quantity;
        $item->setRelation('cart', $cart);

        $cart->setRelation('items', $cart->items->push($item));

        $cart = self::recalculate($cart);
        self::cache($cart);

        return $cart;
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
        $cart->items->map(function (CartItem $item) use ($cart): CartItem {
            /** @var Model&Purchasable $purchasable */
            $purchasable = $item->purchasable;

            $item->price_item = $purchasable->getPurchasablePrice($cart->currency);
            $item->price_item_discounted = $purchasable->getPurchasablePriceDiscounted($cart->currency) ?? $item->price_item;
            $item->price_total = (int)($item->price_item->getAmount() * $item->quantity);
            $item->price_total_discounted = (int)($item->price_item_discounted->getAmount() * $item->quantity);

            $item->purchasable_data = $purchasable->getPurchasableData();

            return $item;
        });

        $cart->price_items = $cart->items->sum(fn (CartItem $item): int => (int)($item->price_total->getAmount()));
        $cart->price_items_discounted = $cart->items->sum(fn (CartItem $item): int => (int)($item->price_total_discounted->getAmount()));

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
