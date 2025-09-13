@extends('layouts.app_admin')

@section('content')
    <style>
        /* Gunakan CSS Grid untuk layout agar tidak ada gap aneh */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background: #fff;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        }

        .product-card img {
            object-fit: cover;
            width: 100%;
            height: 220px;
        }

        .product-body {
            padding: 15px;
            text-align: center;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .product-description {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 10px;
            min-height: 60px;
        }

        .product-price {
            font-size: 1rem;
            color: #e67e22;
            font-weight: bold;
            margin: 10px 0;
        }

        .product-footer {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
        }
    </style>

    <div class="product-grid">
        @php
            use Illuminate\Support\Str;
        @endphp
        @if (!$products->isEmpty())
            @foreach ($products as $product)
                @php
                    $imagePath = Str::startsWith($product->image, 'images/')
                        ? $product->image
                        : 'images/' . $product->image;
                @endphp
                <div class="product-card">
                    <img src="{{ env('STORAGE_URL') . '/' . $imagePath }}" alt="{{ __('admin_pages.no_choosed_image') }}">
                    <div class="product-body">
                        <div class="product-title">{{ $product->name }}</div>
                        <div class="product-description">{{ Str::limit(strip_tags($product->description), 60) }}</div>
                        <div class="product-price">{{ number_format($product->price, 0, ',', '.') }}</div>
                        <div class="product-footer">
                            <a href="{{ lang_url('admin/edit/pruduct/' . $product->id) }}" class="btn btn-sm btn-warning">
                                {{ __('admin_pages.edit') }}
                            </a>
                            <a href="{{ lang_url('admin/delete/product/' . $product->id) }}"
                                data-my-message="{{ __('admin_pages.are_u_sure_delete') }}"
                                class="btn btn-sm btn-danger confirm">
                                {{ __('admin_pages.delete') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-success">{{ __('admin_pages.no_product_results') }}</div>
        @endif
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
@endsection
