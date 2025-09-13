@extends('layouts.app_admin')

@section('content')
    <link href="{{ asset('css/bootstrap-select.min.css') }}" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> {{-- Tambahkan SweetAlert2 --}}

    <div class="orders-page">

        {{-- Tombol Export Apriori --}}
        <div class="mb-3">
            <a href="{{ route('admin.orders.export_apriori') }}" class="btn btn-primary">
                {{ __('Export Apriori') }}
            </a>
        </div>

        <div class="card card-cascade narrower">
            <div class="table-responsive-xs">
                <table class="table">
                    <thead class="blue-grey lighten-4">
                        <tr>
                            <th>#</th>
                            <th>{{ __('admin_pages.time_created') }}</th>
                            <th>{{ __('admin_pages.order_type') }}</th>
                            <th>{{ __('admin_pages.phone') }}</th>
                            <th>{{ __('admin_pages.status') }}</th>
                            <th class="text-right"><i class="fa fa-list" aria-hidden="true"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->order_id }}</td>
                                <td>{{ $order->time_created }}</td>
                                <td>{{ __('admin_pages.ord_' . $order->type) }}</td>
                                <td>{{ $order->phone }}</td>
                                <td>
                                    <select class="selectpicker change-ord-status" data-ord-id="{{ $order->order_id }}"
                                        data-style="btn-secondary">
                                        <option {{ $order->status == 0 ? 'selected' : '' }} value="0">
                                            {{ __('admin_pages.ord_status_new') }}</option>
                                        <option {{ $order->status == 1 ? 'selected' : '' }} value="1">
                                            {{ __('admin_pages.ord_status_processed') }}</option>
                                        <option {{ $order->status == 2 ? 'selected' : '' }} value="2">
                                            {{ __('admin_pages.ord_status_rej') }}</option>
                                        <option {{ $order->status == 3 ? 'selected' : '' }} value="3">
                                            Complete
                                        </option>
                                    </select>
                                </td>
                                <td class="text-right">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-secondary show-more"
                                        data-show-tr="{{ $order->order_id }}">
                                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                        <i class="fa fa-chevron-up" aria-hidden="true" style="display:none;"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr class="tr-more" data-tr="{{ $order->order_id }}" style="display:none;">
                                <td colspan="6">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <ul>
                                                <li><b>{{ __('admin_pages.first_name') }}</b>
                                                    <span>{{ $order->first_name }}</span>
                                                </li>
                                                <li><b>{{ __('admin_pages.last_name') }}</b>
                                                    <span>{{ $order->last_name }}</span>
                                                </li>
                                                <li><b>{{ __('admin_pages.email') }}</b>
                                                    <span>{{ $order->email }}</span>
                                                </li>
                                                <li><b>{{ __('admin_pages.phone') }}</b>
                                                    <span>{{ $order->phone }}</span>
                                                </li>
                                                <li><b>{{ __('admin_pages.address') }}</b>
                                                    <span>{{ $order->address }}</span>
                                                </li>
                                                <li><b>{{ __('admin_pages.city') }}</b>
                                                    <span>{{ $order->city }}</span>
                                                </li>
                                                <li><b>{{ __('admin_pages.post_code') }}</b>
                                                    <span>{{ $order->post_code }}</span>
                                                </li>
                                                <li><b>{{ __('admin_pages.notes') }}</b>
                                                    <span>{{ $order->notes }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-6">
                                            @foreach (unserialize($order->products) as $product)
                                                @php $productInfo = $controller->getProductInfo($product['id']); @endphp
                                                @if ($productInfo)
                                                    <div class="product d-flex mb-2">
                                                        <a href="{{ lang_url($productInfo->url) }}" target="_blank"
                                                            class="d-flex align-items-center">
                                                            <img src="{{ $productInfo->image ? asset('storage/' . $productInfo->image) : asset('images/no-image.png') }}"
                                                                alt=""
                                                                style="width:60px;height:auto;margin-right:10px;">
                                                            <div class="info">
                                                                <span
                                                                    class="name">{{ $productInfo->name ?? 'Unnamed Product' }}</span><br>
                                                                <span class="quantity">
                                                                    <b>{{ __('admin_pages.quantity') }}:</b>
                                                                    {{ $product['quantity'] }}
                                                                </span>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{ $orders->links() }}
    </div>

    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script>
        $('.change-ord-status').change(function() {
            var order_id = $(this).data('ord-id');
            var order_value = $(this).val();
            $.ajax({
                type: "POST",
                url: urls.changeStatus,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    order_id: order_id,
                    order_value: order_value
                }
            }).done(function() {
                // Notifikasi sukses default
                showAlert('success', "{{ __('admin_pages.status_changed') }}");

                // Jika status COMPLETE (value = 3)
                if (parseInt(order_value) === 3) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Completed!',
                        text: 'Order #' + order_id + ' has been successfully completed.',
                        confirmButtonColor: '#3085d6'
                    });
                }
            });
        });

        $('.show-more').click(function() {
            var tr_id = $(this).data('show-tr');
            var tr = $('table').find('[data-tr="' + tr_id + '"]');
            tr.toggle();
            $(this).find('.fa-chevron-down').toggle();
            $(this).find('.fa-chevron-up').toggle();
        });
    </script>
@endsection
