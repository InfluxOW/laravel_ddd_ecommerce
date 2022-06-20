<?php

namespace App\Domains\Cart\Services;

use App\Components\Purchasable\Abstracts\Purchasable;
use App\Domains\Cart\Models\Cart;
use App\Domains\Cart\Models\CartItem;
use App\Domains\Generic\Utils\MathUtils;
use App\Domains\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

final class CartService
{
    public function make(string $currency, ?User $user): Cart
    {
        $cart = Cart::make(['price_items' => 0, 'price_items_discounted' => 0, 'currency' => $currency]);
        $cart->user()->associate($user);
        if ($user === null) {
            do {
                $key = $this->generateCartKey();
                $cacheKey = $this->getCartCacheKey(null, $key);
            } while ((is_string($cacheKey) && Redis::exists($cacheKey)) || Cart::query()->where('key', $key)->exists());

            $cart->key = $key;
        }

        return $cart;
    }

    public function find(?User $user, ?string $key): ?Cart
    {
        if ($user === null && $key === null) {
            return null;
        }

        return $this->getFromCache($user, $key);
    }

    public function add(Cart $cart, Model&Purchasable $purchasable, int $quantity): Cart
    {
        $item = $cart->items->where('purchasable_id', $purchasable->getKey())->where('purchasable_type', $purchasable::class)->first();
        if (isset($item)) {
            return $this->update($cart, $purchasable, $quantity);
        }

        /** @var CartItem $item */
        $item = $cart->items()->make();
        $item->purchasable()->associate($purchasable);
        $item->quantity = (int) MathUtils::clamp($quantity, 0, CartItem::MAX_QUANTITY);
        $item->setRelation('cart', $cart);

        $cart->setRelation('items', $cart->items->push($item));

        $cart = $this->recalculate($cart);
        $this->cache($cart);

        return $cart;
    }

    public function update(Cart $cart, Model&Purchasable $purchasable, int $quantity): Cart
    {
        $item = $cart->items->where('purchasable_id', $purchasable->getKey())->where('purchasable_type', $purchasable::class)->first();
        if ($item === null) {
            return $this->add($cart, $purchasable, $quantity);
        }

        $item->quantity = (int) MathUtils::clamp($quantity, 0, CartItem::MAX_QUANTITY);

        $cart = $this->recalculate($cart);
        $this->cache($cart);

        return $cart;
    }

    public function delete(?User $user, ?string $key): void
    {
        if ($user === null && $key === null) {
            return;
        }

        $cacheKey = $this->getCartCacheKey($user, $key);
        if (isset($cacheKey)) {
            Redis::del($cacheKey);
        }
    }

    public function save(Cart $cart): void
    {
        $this->delete($cart->user, $cart->key);

        $cart->user_id = null;
        $cart->key = null;

        $cart = $this->recalculate($cart);

        $cart->save();
        $cart->items()->saveMany($cart->items);
    }

    protected function cache(Cart $cart): void
    {
        $cacheKey = $this->getCartCacheKey($cart->user, $cart->key);
        if (isset($cacheKey)) {
            Redis::set($cacheKey, serialize($cart));
        }
    }

    protected function getFromCache(?User $user, ?string $key): ?Cart
    {
        $cacheKey = $this->getCartCacheKey($user, $key);
        if ($cacheKey === null) {
            return null;
        }

        $cart = Redis::get($cacheKey);

        return is_string($cart) ? unserialize($cart, [Cart::class]) : null;
    }

    protected function recalculate(Cart $cart): Cart
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

    private function getCartCacheKey(?User $user, ?string $key): ?string
    {
        if ($user === null && $key === null) {
            return null;
        }

        return sprintf('carts.%s', ($user === null) ? $key : sprintf('user_id:%s', $user->id));
    }

    private function generateCartKey(): string
    {
        return bin2hex(random_bytes(24));
    }
}
