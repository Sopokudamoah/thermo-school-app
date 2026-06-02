<?php

namespace App\Interfaces\Finance;

interface VendorInterface
{
    public function getAll();

    public function getActive();

    public function findById($id);

    public function create(array $data);

    public function update($id, array $data);
}
