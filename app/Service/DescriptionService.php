<?php

namespace App\Service;

use App\Models\Description;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DescriptionService {
    public function store(Description $description, $params, $product_id = null)
    {
        DB::beginTransaction();
        try {
            if ($product_id != null) {
                $description->product_id = $product_id;
            }
            $description->fill($params);
            $description->save();
            DB::commit();
            return $description;
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

    public function getDescriptionByProductId($product_id)
    {
        return Description::query()
            ->select(['*'])
            ->where("product_id", $product_id)
            ->first();
    }

    public function delete($product_id)
    {
        DB::beginTransaction();
        try {
            $description = $this->getDescriptionByProductId($product_id);
            if($description) {
                $description->delete();
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
}
