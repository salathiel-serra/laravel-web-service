<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrUpdateProductFormRequest;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private $mProduct, $itemsPerPage = 15;

    public function __construct(Product $product)
    {
        $this->mProduct = $product;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data     = $request->all();
        $products = $this->mProduct->getResults($data, $this->itemsPerPage);

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrUpdateProductFormRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $name = Str::kebab($request->name);
            $extension = $request->image->extension();

            $nameFile = "{$name}.{$extension}";
            $data['image'] = $nameFile;
            
            $upload = $request->image->storeAs('products', $nameFile);

            if (!$upload)
                return response()->json(['error' => 'Fail upload'], 500);
        }

        $product = $this->mProduct->create($data);

        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->mProduct->find($id);
        if (!$product)
            return response()->json(['error' => 'Not found'], 404);

        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreOrUpdateProductFormRequest $request, $id)
    {
        $product = $this->mProduct->find($id);
        if (!$product)
            return response()->json(['error' => 'Not found'], 404);

        $data = $request->all();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($product->image) {
                if (Storage::exists("products/{$product->image}"))
                    Storage::delete("products/{$product->image}");
            }

            $name = Str::kebab($request->name);
            $extension = $request->image->extension();

            $nameFile = "{$name}.{$extension}";
            $data['image'] = $nameFile;
            
            $upload = $request->image->storeAs('products', $nameFile);

            if (!$upload)
                return response()->json(['error' => 'Fail upload'], 500);
        }

        $product->update($data);

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = $this->mProduct->find($id);
        if (!$product)
            return response()->json(['error' => 'Not found'], 404);

        if ($product->image) {
            if (Storage::exists("products/{$product->image}"))
                Storage::delete("products/{$product->image}");
        }
        
        $product->delete();
        
        return response()->json(['success' => true], 204);
    }
}
