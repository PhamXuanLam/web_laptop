<?php

namespace App\Service;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductService {
    public function getProductByBrand(string $brand) {
        return Product::query()
            ->select(['*'])
            ->where("brand", 'LIKE', '%' . $brand . '%')
            ->get();
    }

   public function getDemands(){
    $demands = Product::query()
                ->select('demand')
                ->distinct()
                ->get()
                ->pluck('demand'); // Lấy ra giá trị của cột 'demand' thành một mảng

    return $demands->toArray(); // Chuyển đổi collection thành mảng
    }

    public function getAll()
    {
        return Product::query()
            ->with(['category'])
            ->select(["*"])
            ->get();
    }

    public function getProductByKeyword($keyword)
    {
        return Product::query()
            ->where('name', 'like', "%{$keyword}%")
            ->orWhere('slug', 'like', "%{$keyword}%")
            ->orWhere('demand', 'like', "%{$keyword}%")
            ->orWhere('brand', 'like', "%{$keyword}%")
            ->get();
    }

    public function getProductById($id)
    {
        return Product::query()
            ->with(["images", "category", "description"])
            ->find($id);
    }

    public function getProductBestseller($order)
    {
        return Product::select('products.*')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->selectRaw('SUM(order_items.quantity) as total_quantity')
            ->groupBy('products.id')
            ->orderBy('total_quantity', $order)
            ->get();
    }

    public function store(Product $product, $param)
    {
        DB::beginTransaction();
        try {
            $product->fill($param);
            $product->save();
            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("File: ".$e->getFile().'---Line: '.$e->getLine()."---Message: ".$e->getMessage());
            return [
                'success' => false,
                'message' => "An error occurred!",
                'error' => $e->getMessage()
            ];
        }
    }

    public function evaluate($product_id ,$rate)
    {
        $product = $this->getProductById($product_id);
        if($product->evaluate == null) {
            $product->evaluate = $rate;
        } else {
            $product->evaluate = ($rate + $product->evaluate)/2;
        }
        $product->save();
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $product = $this->getProductById($id);
            app(ImageService::class)->delete($id);
            app(DescriptionService::class)->delete($id);
            app(ProductReviewService::class)->delete($id);
            $product->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("File: ".$e->getFile().'---Line: '.$e->getLine()."---Message: ".$e->getMessage());
            return [
                'success' => false,
                'message' => "An error occurred!",
                'error' => $e->getMessage()
            ];
        }
    }
}
