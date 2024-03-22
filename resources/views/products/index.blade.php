@extends('layouts.app')
@section('title', 'Products')
@section('content')

    <style>
        .p-i {
            height: 250px;
            max-width: 250px;
        }
        .item-wrap {
            padding: 20px;
        }
        .item-wrap img {
            max-width: 220px;
            height: 150px;
        }
        section {
            padding-top: 30px;
        }
        p.p-title {
            height: 85px;
            overflow: hidden;
            padding: 10px;
        }
        .item {
            margin-bottom: 15px;
        }
    </style>

    <section>
        @include('products.partials.prdocut_filter')
        @foreach($products->chunk(4) as $items)
            <div class="row">
                @foreach($items as $product)
                    <div class="col-md-3 text-center item">
                        <div class="item-wrap bg-white">
                            <p>
                                <b>
                                    <u>
                                        <a class="text-dark" target="_blank" href="{{ route('products.show',$product->amazon_id) }}">{{ $product->amazon_id }}</a>
                                    </u>
                                </b>
                            </p>
                            <img src="{{ @$product->images[0]['medium'] }}">
                            <hr>
                            <p class="p-title text-sm">{{ $product->title }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </section>
    <hr>
    @include('layouts.pagination', ['data' => $products])

@endsection
