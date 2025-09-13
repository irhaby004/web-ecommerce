@extends('layouts.app_public')

@section('content')
    <style>
        .recommended-products-section h3 {
            margin-bottom: 20px;
            font-weight: bold;
        }

        .product-card {
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            transition: box-shadow 0.3s ease-in-out;
            background: #ebe9e9;
            margin-bottom: 15px;
        }

        .product-card:hover {
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .recommended-img {
            max-width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .product-name {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 5px;
            min-height: 40px;
        }

        .product-price {
            font-size: 14px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 10px;
        }

        .btn-block {
            width: 100%;
        }
    </style>
    <div class="checkout-page">
        <div class="container">

            {{-- Pesan sukses setelah tambah ke keranjang --}}
            @if (session('success'))
                <div class="alert alert-success" style="margin-top:10px;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="section-title">
                        <h2>{{ __('public_pages.payment_type') }}</h2>
                    </div>
                    <div class="payment-types">
                        <div class="box-type active" data-radio-val="cash_on_delivery">
                            <img src="{{ asset('img/cash_on_deliv.png') }}" alt="econt" class="img-responsive">
                            <span>{{ __('public_pages.cash_on_delivery') }}</span>
                        </div>
                    </div>
                    <div class="section-title">
                        <h2>{{ __('public_pages.delivery_address') }}</h2>
                    </div>
                    <div id="errors" class="alert alert-danger"></div>
                    <form method="POST" action="{{ lang_url('checkout') }}" id="set-order">
                        {{ csrf_field() }}
                        <div class="radios">
                            <input type="radio" checked name="payment_type" value="cash_on_delivery">
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <input class="form-control" name="first_name" type="text"
                                    placeholder="{{ __('public_pages.name') }}">
                            </div>
                            <div class="form-group col-sm-6">
                                <input class="form-control" name="last_name" type="text"
                                    placeholder="{{ __('public_pages.family') }}">
                            </div>
                            <div class="form-group col-sm-6">
                                <input class="form-control" name="email" type="text"
                                    placeholder="{{ __('public_pages.email_address') }}">
                            </div>
                            <div class="form-group col-sm-6">
                                <input class="form-control" name="phone" type="text"
                                    placeholder="{{ __('public_pages.phone') }}">
                            </div>
                            <div class="form-group col-sm-12">
                                <textarea name="address" placeholder="{{ __('public_pages.address') }}" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="form-group col-sm-6">
                                <input class="form-control" name="city" type="text"
                                    placeholder="{{ __('public_pages.city') }}">
                            </div>
                            <div class="form-group col-sm-6">
                                <input class="form-control" name="post_code" type="text"
                                    placeholder="{{ __('public_pages.post_code') }}">
                            </div>
                            <div class="form-group col-sm-12">
                                <textarea class="form-control" placeholder="{{ __('public_pages.notes') }}" name="notes" rows="3"></textarea>
                            </div>
                        </div>

                        @php $sum_total = 0; @endphp
                        @if (!empty($cartProducts))
                            <div class="products-for-checkout">
                                <ul>
                                    @foreach ($cartProducts as $index => $cartProduct)
                                        @php
                                            $subtotal = $cartProduct->num_added * (float) $cartProduct->price;
                                            $sum_total += $subtotal;
                                        @endphp
                                        <li>
                                            <input name="id[]" value="{{ $cartProduct->id }}" type="hidden">
                                            <a href="{{ lang_url($cartProduct->url) }}" class="link">
                                                <img src="{{ asset('storage/images/' . $cartProduct->image) }}"
                                                    alt="">
                                                <div class="info">
                                                    <span class="name">{{ $cartProduct->name }}</span>
                                                    <span class="price">
                                                        <span class="qty-display">{{ $cartProduct->num_added }}</span> x
                                                        {{ $cartProduct->price }} = {{ $subtotal }}
                                                    </span>
                                                </div>
                                            </a>
                                            <div class="controls">
                                                <div class="input-group">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-default"
                                                            onclick="changeQuantity({{ $index }}, -1)">
                                                            <span class="glyphicon glyphicon-minus"></span>
                                                        </button>
                                                    </span>
                                                    <input type="number" min="1" name="quantity[]"
                                                        id="quantity-{{ $index }}" class="form-control"
                                                        value="{{ $cartProduct->num_added }}">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-default"
                                                            onclick="changeQuantity({{ $index }}, 1)">
                                                            <span class="glyphicon glyphicon-plus"></span>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Form untuk hapus produk --}}
                                            <form method="POST" action="{{ route('cart.remove') }}"
                                                style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $cartProduct->id }}">
                                                <button type="submit" class="removeProduct btn btn-link"
                                                    style="padding:0; border:none; background:none;" title="Hapus Produk">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </button>
                                            </form>

                                            <div class="clearfix"></div>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="final-total">{{ __('public_pages.sum_for_pay') }} {{ $sum_total }}</div>
                            </div>

                            {{-- Produk rekomendasi --}}
                            @if (!empty($recommendedProducts) && $recommendedProducts->count() > 0)
                                <div class="recommended-products-section" style="margin-top: 30px;">
                                    <h3>{{ __('public_pages.recommended_products') }}</h3>
                                    <div class="row">
                                        @foreach ($recommendedProducts as $product)
                                            <div class="col-md-3 col-sm-4 col-xs-6">
                                                <div class="product-card">
                                                    <a href="{{ lang_url($product->url) }}">
                                                        <img src="{{ env('STORAGE_URL') . '/' . $product->image }}"
                                                            alt="{{ $product->name }}" class="recommended-img">
                                                        <div class="product-name">{{ $product->name }}</div>
                                                        <div class="product-price">{{ $product->price }}</div>
                                                    </a>
                                                    <form method="POST" action="{{ route('cart.add') }}"
                                                        style="margin-top:8px;">
                                                        @csrf
                                                        <input type="hidden" name="id"
                                                            value="{{ $product->id }}">
                                                        <input type="hidden" name="quantity" value="1">
                                                        <button type="submit" class="btn btn-primary"
                                                            style="width:100%;">{{ __('Tambah ke Keranjang') }}</button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <a href="javascript:void(0);" onclick="completeOrder()"
                                class="green-btn">{{ __('public_pages.complete_order') }}</a>
                        @else
                            <a href="{{ lang_url('products') }}"
                                class="green-btn">{{ __('public_pages.first_need_add_products') }}</a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function changeQuantity(index, delta) {
            const input = document.getElementById('quantity-' + index);
            let current = parseInt(input.value);
            current = isNaN(current) ? 1 : current;
            current += delta;
            if (current < 1) current = 1;
            input.value = current;
        }

        function completeOrder() {
            document.getElementById('set-order').submit();
        }
    </script>
@endsection
