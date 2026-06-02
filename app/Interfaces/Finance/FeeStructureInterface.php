<?php

namespace App\Interfaces\Finance;

interface FeeStructureInterface
{
    public function getAll();

    public function findById($id);

    public function getBySessionAndClass($session_id, $class_id, $section_id = null);

    public function create(array $data);

    public function update($id, array $data);

    public function syncItems($fee_structure_id, array $items);
}
