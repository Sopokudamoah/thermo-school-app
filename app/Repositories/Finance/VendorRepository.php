<?php

namespace App\Repositories\Finance;

use App\Interfaces\Finance\VendorInterface;
use App\Models\Finance\Vendor;

class VendorRepository implements VendorInterface
{
    public function getAll()
    {
        return Vendor::orderBy('name')->get();
    }

    public function getActive()
    {
        return Vendor::where('active', true)->orderBy('name')->get();
    }

    public function findById($id)
    {
        return Vendor::findOrFail($id);
    }

    public function create(array $data)
    {
        return Vendor::create($data);
    }

    public function update($id, array $data)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->update($data);
        return $vendor;
    }
}
