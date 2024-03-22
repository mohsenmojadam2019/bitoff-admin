@extends('layouts.app')
@section('title', 'Show Product')
@section('content')
    <style>
        .active {
            color: orange
        }

        .active-variation {
            background-color: #1e7e34;
            color: white;
        }

    </style>
    <div class="card card-solid">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3 class="d-inline-block d-sm-none">{{ $product->title }}</h3>
                    <div class="col-12">
                        <img style="height: 50%;width:50%" src="{{ $product->images[0]['large'] }}" class="product-image show-image" alt="Product Image">
                    </div>
                    <div class="col-12 product-image-thumbs">
                        @foreach ($product->images as $image)
                            <div class="product-image-thumb active">
                                <img class="show-loarg-image" data-src="{{ $image['large'] }}"
                                    src="{{ $image['thumbnail'] }}" alt="Product Image">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <h3 class="my-3">{{ $product->title }}</h3>
                    <h5 class="text-info">{{ $product->brand }}</h5>
                    @if ($product->review)
                        @for ($i = 0; $i < 5; $i++)
                            <i class="fa fa-star {{ $i < ((int) $product->review['rate']) ? 'active' : '' }}"></i>
                        @endfor
                        <p class="text-info">people : {{ $product->review['people'] }}</p>
                    @endif
                    @if ($product->features != '')
                        <ul>
                            @foreach ($product->features as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        </ul>
                    @endif
                    @if ($product->variations != '')
                        <hr>

                        @foreach ($product->variations['dimensionsDisplay'] as $variation)
                            <h4>{{ $variation }}</h4>
                            <ul>
                                @foreach ($product->variations['dimensionValuesData'][$loop->index] as $data)
                                    <li style="font-size: 18px"
                                        class="btn btn-app btn-lg
                                                     {{ $product->getIndexSelectedVariation($loop->parent->index) == $loop->index ? 'active-variation' : '' }}">
                                        {{ $data }}
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                    @endif
                    <hr>
                    <div class="mt-4">
                        <a title="amazon link" target="_blank" href="https://www.amazon.com/dp/{{ $product->amazon_id }}">
                            Amazon
                        </a>
                        <span>|</span>
                        <a title="amazon link" target="_blank" href="{{ \App\Support\FrontUrl::product($product->amazon_id) }} }}">
                            Bitoff
                        </a>
                    </div>
                </div>

            </div>
            <br>
            @if ($product->offers)
                <h3>Offers</h4>
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>CONDIITON</th>
                                    <th>SELLER IFORMATION</th>
                                    <th>DELIVER</th>
                                    <th>PRICE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->offers as $offer)
                                    <tr>
                                        <td>{{ $offer->condition }}</td>
                                        <td>
                                            {{ $offer->seller->name }}
                                            @if ($offer->seller->review)
                                                <br>
                                                @for ($i = 0; $i < 5; $i++)
                                                    <i
                                                        class="fa fa-star {{ $i < ((int) $offer->seller->review['rate']) ? 'active' : '' }}"></i>
                                                @endfor
                                                <span
                                                    class="text-info">{{ number_format($offer->seller->review['people']) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($offer->seller->delivery))
                                                {{ implode('.', $offer->seller->delivery) }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ '$ ' . $offer->price }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
            @endif
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).on('click', '.show-loarg-image', function() {
            $('.show-image').attr('src', $(this).attr('data-src'))
        });
        changeProductImage();

        function changeProductImage() {
            if ($('.product-image').attr('src') === "") {
                $(".show-loarg-image").each(function(key, value) {
                    if ($(this).attr('data-src') != "") {
                        $(this).click();
                        return false;
                    }
                })
            }
        }

    </script>
@endsection
