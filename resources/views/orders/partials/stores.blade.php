<div class="card-body pb-0">
    @foreach($order->items->chunk(4) as $items)
        <div class="row d-flex align-items-stretch" style="margin-bottom: 20px">
            @foreach($items as $item)
                <div class="col-12 col-sm-6 col-md-3 d-flex align-items-stretch">
                    <div class="card card-widget widget-user">
                        <div class="widget-user-header bg-info">
                            <a target="_blank" href="https://amazon.com/dp/{{ $item->product_id }}?th=1&psc=1">
                                <h3 class="widget-user-username">{{ $item->product_id }}</h3>
                            </a>
                            <h6 class="widget-user-desc">
                                <a class="text-black-50" href="{{ $item->meta['url'] }}">{{ $item->meta['url'] }}</a>
                            </h6>
                        </div>
{{--                        <div class="widget-user-image">--}}
{{--                            <img class="img-circle elevation-2" src="{{  }}"--}}
{{--                                 class="img img-circle img-fluid img-bordered-sm" style="height: 90px">--}}
{{--                        </div>--}}
                        <div class="card-footer">

                            <div class="row">
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">{{ $item->price }}</h5>
                                        <span class="description-text">Price</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">{{ $item->shipping }}</h5>
                                        <span class="description-text">Ship</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header">{{ $item->extra }}</h5>
                                        <span class="description-text">Extra</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <hr>

                            <span class="badge badge-dark dropright">
                                <a type="button" class="text-white dropdown-toggle" data-toggle="dropdown" href="#">#{{ $item->id }}</a>
                                <div class="dropdown-menu">
                                    @if($order->earner_id)
                                        <a data-target="{{ route('orders.items.deliver', [$order->hash, $item->id]) }}" data-id="{{ $item->id }}" class="d-item dropdown-item {{ !$item->isState('ship') ? 'disabled' : '' }}" href="#">Deliver</a>
                                        <a data-target="{{ route('orders.items.tracking', [$order->hash, $item->id]) }}" data-id="{{ $item->id }}" class="t-item dropdown-item {{ !$item->isState('purchase') ? 'disabled' : '' }}" href="#">Add tracking</a>
                                        <a data-target="{{ route('orders.items.cancel', [$order->hash, $item->id]) }}" data-id="{{ $item->id }}" class="c-item dropdown-item {{ $order->isState('cancel') || $item->isState('deliver', 'cancel') ? 'disabled' : '' }}" href="#">Remove</a>
                                    @else
                                        <a class="dropdown-item disabled" href="#">Deliver</a>
                                        <a class="dropdown-item disabled" href="#">Add tracking</a>
                                        <a class="dropdown-item disabled" href="#">Remove</a>
                                    @endif
                                </div>
                            </span>
                            @if($item->amazon_order_id)
                                <span style="cursor: pointer" class="badge badge-primary" title="Order id"
                                      data-toggle="tooltip">
                                    <i data-toggle="modal" data-target="#orderid{{ $item->id }}"
                                       class="fas fa-shopping-bag"></i>
                                </span>
                                <div class="modal" id="orderid{{ $item->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Order Id of <kbd>#{{ $item->id }}</kbd></h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <code>{!! nl2br($item->amazon_order_id) !!}</code>
                                            </div>

                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                    Close
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($item->tracking)
                                <a target="_blank" style="color: inherit" href="{{ $item->tracking }}">
                                    <span style="cursor: pointer; display: inline-block" class="badge badge-success"
                                          title="Tracking link" data-toggle="tooltip">
                                        <i data-toggle="modal" class="fas fa-shipping-fast"></i>
                                    </span>
                                </a>
                            @endif
                            @if($item->status == 'cancel')
                                <span class="badge badge-danger">Removed</span>
                            @endif
                            @if($item->status == 'deliver')
                                <span class="badge badge-primary">Delivered</span>
                            @endif
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
