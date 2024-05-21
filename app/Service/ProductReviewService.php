<?php

namespace App\Service;

use App\Models\ProductReview;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductReviewService {
    public function delete($product_id)
    {
        DB::beginTransaction();
        try {
            $reviews = $this->getReviewByProductId($product_id);
            foreach($reviews as $review) {
                $review->delete();
            }
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

    public function getReviewByProductId($product_id)
    {
        return ProductReview::query()
            ->select(["*"])
            ->where("product_id", $product_id)
            ->get();
    }
}
