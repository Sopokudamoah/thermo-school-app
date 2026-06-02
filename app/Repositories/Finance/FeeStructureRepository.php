<?php

namespace App\Repositories\Finance;

use App\Interfaces\Finance\FeeStructureInterface;
use App\Models\Finance\FeeStructure;
use App\Models\Finance\FeeStructureItem;

class FeeStructureRepository implements FeeStructureInterface
{
    public function getAll()
    {
        return FeeStructure::with(['session', 'school_class', 'section', 'items.fee_type'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function findById($id)
    {
        return FeeStructure::with(['items.fee_type', 'session', 'school_class', 'section'])->findOrFail($id);
    }

    public function getBySessionAndClass($session_id, $class_id, $section_id = null)
    {
        $query = FeeStructure::with(['items.fee_type'])
            ->where('session_id', $session_id)
            ->where('class_id', $class_id)
            ->where('active', true);

        if ($section_id) {
            $query->where(function ($q) use ($section_id) {
                $q->where('section_id', $section_id)->orWhereNull('section_id');
            });
        }

        return $query->first();
    }

    public function create(array $data)
    {
        return FeeStructure::create($data);
    }

    public function update($id, array $data)
    {
        $structure = FeeStructure::findOrFail($id);
        $structure->update($data);
        return $structure;
    }

    public function syncItems($fee_structure_id, array $items)
    {
        FeeStructureItem::where('fee_structure_id', $fee_structure_id)->delete();

        foreach ($items as $item) {
            FeeStructureItem::create([
                'fee_structure_id' => $fee_structure_id,
                'fee_type_id' => $item['fee_type_id'],
                'amount' => $item['amount'],
            ]);
        }
    }
}
