<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use App\Models\{Product, ProductImage, ProductReference};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Storage};

class ProductsController extends Controller
{
    /**
     * Create a new product.
     *
     * @param Request $request The request object.
     * @return array The API response array.
     */
    public function createProduct(Request $request): array
    {
        $status = false;
        $result = null;
        $product = null;
        DB::beginTransaction();
        try {
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'status_id' => $this->active_status
            ]);

            foreach ($request->references as $reference) {
                ProductReference::create([
                    'product_id' => $product->id,
                    'reference' => $reference['name'],
                    'price' => $reference['price'],
                    'stock' => $reference['stock'],
                    'status_id' => $this->active_status
                ]);
            }

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollBack();
        }

        if ($status) {
            return $this->responseApi($status, array('type' => 'success', 'content' => 'Done.'), $product);
        } else {
            return $this->responseApi($status, array('type' => 'error', 'content' => 'Error.'), $result);
        }
    }

    /**
     * Edit an existing product.
     *
     * @param Request $request The request object.
     * @return array The API response array.
     */
    public function editProduct(Request $request): array
    {
        $status = false;
        $result = null;
        $product = null;
        DB::beginTransaction();
        try {
            $product = Product::find($request->id);
            $product->name = $request->name;
            $product->description = $request->description;
            $product->save();

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollBack();
        }

        if ($status) {
            return $this->responseApi(true, array('type' => 'success', 'content' => 'Done.'), $product);
        } else {
            return $this->responseApi(false, array('type' => 'error', 'content' => 'Error.'), $result);
        }
    }

    /**
     * Edit an existing product.
     *
     * @param Request $request The request object.
     * @return array The API response array.
     */
    public function createProductImage(Request $request): array
    {
        $status = false;
        $result = null;
        $productImage = null;
        DB::beginTransaction();
        try {
            $filename = $request->file('image')->getClientOriginalName();

            $path = $request->file('image')->storeAs('products', $filename, 'public');
            $url = Storage::url($path);
            $finalUrl = url($url);

            $productImage = new ProductImage();
            $productImage->product_id = $request->product_id;
            $productImage->url = $finalUrl;
            $productImage->file_name = $filename;
            $productImage->status_id = $this->active_status;
            $productImage->save();

            $status = true;
            DB::commit();
        } catch (\Throwable|\Exception $th) {
            $result = $th->getMessage();
            DB::rollBack();
        }

        if ($status) {
            return $this->responseApi(true, array('type' => 'success', 'content' => 'Done.'), $productImage);
        } else {
            return $this->responseApi(false, array('type' => 'error', 'content' => 'Error.'), $result);
        }
    }

    /**
     * Create a new product reference.
     *
     * @param Request $request The request object.
     * @return array The API response array.
     */
    public function createReference(Request $request): array
    {
        $status = false;
        $result = null;
        $productReference = null;
        DB::beginTransaction();
        try {
            $productReference = new ProductReference();
            $productReference->product_id = $request->product_id;
            $productReference->reference = $request->reference;
            $productReference->stock = $request->stock;
            $productReference->price = $request->price;
            $productReference->status_id = $this->active_status;
            $productReference->save();

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollBack();
        }

        if ($status) {
            return $this->responseApi($status, array('type' => 'success', 'content' => 'Done.'), $productReference);
        } else {
            return $this->responseApi($status, array('type' => 'error', 'content' => 'Error.'), $result);
        }
    }

    /**
     * Change the status of a product.
     *
     * @param Request $request The request object.
     * @return array The API response array.
     */
    public function changeStatusProduct(Request $request): array
    {
        $status = false;
        $result = null;
        $product = null;
        DB::beginTransaction();
        try {
            $product = Product::find($request->id);
            $product->status_id = $product->status_id === $this->active_status ? $this->inactive_status : $this->active_status;
            $product->save();

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollBack();
        }

        if ($status) {
            return $this->responseApi(true, array('type' => 'success', 'content' => 'Done.'), $product);
        } else {
            return $this->responseApi(false, array('type' => 'error', 'content' => 'Error.'), $result);
        }
    }

    /**
     * Change the status of a product reference.
     *
     * @param Request $request The request object.
     * @return array The API response array.
     */
    public function changeStatusReference(Request $request): array
    {
        $status = false;
        $result = null;
        $productReference = null;
        DB::beginTransaction();
        try {
            $productReference = ProductReference::find($request->id);
            $productReference->status_id = $productReference->status_id === $this->active_status ? $this->inactive_status : $this->active_status;
            $productReference->save();

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollBack();
        }

        if ($status) {
            return $this->responseApi(true, array('type' => 'success', 'content' => 'Done.'), $productReference);
        } else {
            return $this->responseApi(false, array('type' => 'error', 'content' => 'Error.'), $result);
        }
    }

    /**
     * Change the status of a product image.
     *
     * @param Request $request The request object.
     * @return array The API response array.
     */
    public function changeStatusProductImage(Request $request): array
    {
        $status = false;
        $result = null;
        $productImage = null;
        DB::beginTransaction();
        try {
            $productImage = ProductImage::find($request->id);
            $productImage->status_id = $productImage->status_id === $this->active_status ? $this->inactive_status : $this->active_status;
            $productImage->save();

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollBack();
        }

        if ($status) {
            return $this->responseApi(true, array('type' => 'success', 'content' => 'Done.'), $productImage);
        } else {
            return $this->responseApi(false, array('type' => 'error', 'content' => 'Error.'), $result);
        }
    }

    public function getActiveProducts(): array
    {
        $products = Product::with('references.status', 'images', 'status')
            ->whereStatusId($this->active_status)
            ->whereHas('references', function ($query) {
                $query->whereStatusId($this->active_status);
            })->whereHas('images', function ($query) {
                $query->whereStatusId($this->active_status);
            })->get();

        return $this->responseApi(true, ['type' => 'success', 'content' => 'Done.'], $products);
    }

    public function getAllProducts(): array
    {
        $products = Product::with('references.status', 'images', 'status')->get();
        return $this->responseApi(true, ['type' => 'success', 'content' => 'Done.'], $products);
    }
}
