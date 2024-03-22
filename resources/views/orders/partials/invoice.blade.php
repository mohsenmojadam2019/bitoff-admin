<div class="invoice p-3 mb-3">
    <div class="row">
        <div class="col-12">
        </div>
    </div>
    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            To
            <address>
               {{ implode(',',$order->address) }}
            </address>

        </div>

        <div class="col-sm-4 invoice-col">
            <b>Invoice</b><br>

            <b>Order ID :</b> {{ $order->hash }}<br>
            <b>Order Created : </b>{{ $order->created_at->format('d/m/Y') }}<br>

        </div>

    </div>
    <div class="row">
        <div class="col-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Product</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $detail)
                        <tr>
                            <td>{{ $detail->id }}</td>
                            <td>{{ substr($detail->product->title, 0, 50) }}</td>
                            <td>{{'$ '.($detail->price + $detail->shiping + $detail->extra) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">

        <div class="col-6">

        </div>
        <div class="col-6">
            <p class="lead">Amount</p>
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <th style="width:50%">Total Price:</th>
                            <td>{{ '$ '.$invoice->totalPrice() }}</td>
                        </tr>
                        <tr>
                            <th>Tax : </th>
                            <td>{{ $invoice->tax() }}</td>
                        </tr>
                        <tr>
                            <th>Shipping:</th>
                            <td>{{ '$ '.$invoice->shipping() }}</td>
                        </tr>
                        <tr>
                            <th>Net:</th>
                            <td>{{ $invoice->net() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
