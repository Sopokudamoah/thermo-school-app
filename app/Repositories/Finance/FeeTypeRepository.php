<?php

namespace App\Repositories\Finance;

use App\Interfaces\Finance\FeeTypeInterface;
use App\Models\Finance\FeeType;

class FeeTypeRepository implements FeeTypeInterface
{
    public function getAll()
    {
        return FeeType::orderBy('name')->get();
    }

    public function getActive()
    {
        return FeeType::where('active', true)->orderBy('name')->get();
    }

    public function findById($id)
    {
        return FeeType::findOrFail($id);
    }

    public function create(array $data)
    {
        return FeeType::create($data);
    }

    public function update($id, array $data)
    {
        $fee_type = FeeType::findOrFail($id);
        $fee_type->update($data);
        return $fee_type;
    }
}
