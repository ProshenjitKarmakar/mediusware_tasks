<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $searchByProductTitle = $request->title;
        $searchByPriceFrom = $request->price_from;
        $searchByPriceTo = $request->price_to;
        $searchByProductDate = $request->date;

        $product_info = Product::with([
            'productVariantPrice' =>function($query) use ($searchByPriceFrom, $searchByPriceTo){
               $query->with('productVariantOne','productVariantTwo','productVariantThree');
                if($searchByPriceFrom){
                    $query->where('price', '>=', $searchByPriceFrom);
                }
                if($searchByPriceTo){
                    $query->where('price', '<=', $searchByPriceTo);
                }
        }]);

        if($searchByProductDate)
        {
            $product_info = $product_info->whereDate('created_at', '>=', $searchByProductDate);
        }

        if($searchByProductTitle)
        {
            $product_info = $product_info->where('title', 'LIKE', '%'.$searchByProductTitle.'%');
        }

        $product_info = $product_info->paginate(3);

        return view('products.index',compact('product_info'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $data = [
            'title' => $request->product_name ?? null,
            'sku' => $request->product_sku ?? null,
            'description' => $request->product_description ?? null,
        ];

        DB::beginTransaction();
        try{
            DB::table('products')->insert($data);
            DB::commit();
            return redirect()->back();
        }
        catch(\Exception $e){
            DB::rollBack();
            return redirect()->back();
        }

    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $product_info = Product::with([
            'productVariantPrice' => function($query){
                $query->with(['productVariantOne','productVariantTwo','productVariantThree']);
            }])->find($product->id);

        return view('products.edit', compact('product_info'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $data = [
            'title' => $request->product_name ?? null,
            'sku' => $request->product_sku ?? null,
            'description' => $request->product_description ?? null,
            'updated_at' => now(),
        ];

        DB::beginTransaction();
        try{
            DB::table('products')->where('id', $product->id)->update($data);
            DB::commit();
            return view('products.create');
        }
        catch(\Exception $e){
            DB::rollBack();
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
