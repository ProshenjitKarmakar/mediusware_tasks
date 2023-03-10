@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="{{ route('product.index') }}" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">

                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    @php($sl=1)
                    @foreach ($product_info as $item)
                    <tr>
                        <td>{{ $sl }}</td>
                        <td>{{ $item->title }} <br> Created at : {{ $item->created_at }}</td>
                        <td>{{ $item->description }}</td>
                        <td>
                            <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant{{ $item->id }}">
                                @foreach ($item->productVariantPrice as $varient)
                                <dt class="col-sm-3 pb-0">
                                   {{  optional($varient->productVariantOne)->variant }} /
                                   {{  optional($varient->productVariantTwo)->variant }} /
                                   {{  optional($varient->productVariantThree)->variant }}
                                </dt>
                                <dd class="col-sm-9">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 pb-0">Price : {{ number_format($varient->price, 2) }}</dt>
                                        <dd class="col-sm-8 pb-0">InStock : {{ number_format($varient->stock, 2) }}</dd>
                                    </dl>
                                </dd>
                                @endforeach
                            </dl>
                            <button onclick="$('#variant{{ $item->id }}').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                        </td>

                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('product.edit', $item->id) }}" class="btn btn-success">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @php($sl++)
                    @endforeach
                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p>Showing {{ $product_info->firstItem() }} to {{ $product_info->lastItem() }} out of {{ $product_info->total() }}</p>
                </div>
                <div class="col-md-2">
                    {{ $product_info->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
