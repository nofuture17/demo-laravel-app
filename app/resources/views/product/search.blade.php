<?php

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * @var $items LengthAwarePaginator
 */
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
                @if($items->total())
                    <div class="results__items">
                        @foreach($items as $item)
                            <div class="results__item product flex justify-evenly my-2">
                                <button class="product__save bg-blue-500 rounded p-1">Save</button>
                                <div class="product__id_external">{{$item->getExternalID()}}</div>
                                <div class="product__name">{{$item->getName()}}</div>
                                <div class="product__image"><img src="{{$item->getImageUrl()}}" alt=""></div>
                                <div class="product__categories">{{$item->getCategories()}}</div>
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
@endsection
