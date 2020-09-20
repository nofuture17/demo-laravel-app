<?php

use App\ViewModel\Product\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * @var $items LengthAwarePaginator|Product[]
 */
$updateButtonText = 'Update'
?>

@extends('layouts.app')
@section('content')
    <div class="products-search">
        <div class="products-search__form form flex justify-center">
            <form action="">
                <label class="form__title text-3xl" for="name">Enter a product name</label>
                <input type="text" class="form__name-input border-2 border-black rounded" id="name" name="name">
                <button class="form__submit bg-green-500 rounded p-1">Search</button>
            </form>
        </div>
        @if($name)
            <div class="products-search__results results">
                <div class="results__title text-center text-2xl">Search result:</div>
                @if($items->isNotEmpty())
                    <div class="results__items">
                        @foreach($items as $item)
                            <div class="results__item product flex justify-evenly my-2">
                                <button
                                    class="product__save bg-blue-500 rounded p-1">{{ $item->id ? $updateButtonText : 'Save' }}</button>
                                <div class="product__external_id">{{$item->external_id}}</div>
                                <div class="product__name">{{$item->name}}</div>
                                <div class="product__image"><img src="{{$item->image_url}}" alt=""></div>
                                <div class="product__categories">{{$item->categories}}</div>
                            </div>
                        @endforeach
                    </div>
                    <div class="results__pagination w-6/12 mx-auto">
                        {{ $items->links() }}
                    </div>
                @else
                    <div class="results__empty-result-label text-center text-xl">
                        Empty result
                    </div>
                @endif
            </div>
        @endif
    </div>
    <style>
        .results__item > div {
            display: inline-block;
        }
    </style>
    <script>
        $(document).ready(function () {
            $(document).on('click', '.product__save', function () {
                let button = $(this)
                let product = button.closest('.product')
                let data = {
                    'external_id': product.find('.product__external_id').text(),
                    'image_url': product.find('.product__image img').attr('src'),
                    'name': product.find('.product__name').text(),
                    'categories': product.find('.product__categories').text(),
                }
                let url = '{{url('/save')}}'
                $.ajax(url, {
                    method: 'POST',
                    data: data,
                    dataType: 'json',
                    success: function (response) {
                        if (response.id && button.text() != '{{$updateButtonText}}') {
                            button.text('{{$updateButtonText}}')
                        }
                    }
                })
            })
        })
    </script>
@endsection
