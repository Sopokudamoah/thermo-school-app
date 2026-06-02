<?php

namespace App\Repositories\Finance;

use App\Interfaces\Finance\ScholarshipInterface;
use App\Models\Finance\Invoice;
use App\Models\Finance\Scholarship;
use App\Models\Finance\StudentScholarship;

class ScholarshipRepository implements ScholarshipInterface
{
    public function getAll()
    {
        return Scholarship::orderBy('name')->get();
    }

    public function getActive()
    {
        return Scholarship::where('active', true)->orderBy('name')->get();
    }

    public function findById($id)
    {
        return Scholarship::findOrFail($id);
    }

    public function create(array $data)
    {
        return Scholarship::create($data);
    }

    public function update($id, array $data)
    {
        $scholarship = Scholarship::findOrFail($id);
        $scholarship->update($data);
        return $scholarship;
    }

    public function assignToStudent(array $data)
    {
        $scholarship = Scholarship::findOrFail($data['scholarship_id']);

        $amount_applied = 0;
        if (!empty($data['invoice_id'])) {
            $invoice = Invoice::with('items')->findOrFail($data['invoice_id']);

            $subtotalMoney = $invoice->items->reduce(function ($carry, $item) {
                return $carry->add($item->amount);
            }, \App\Helpers\MoneyHelper::zero());

            if ($scholarship->type === Scholarship::TYPE_PERCENTAGE) {
                $amount_applied = $subtotalMoney->multiply($scholarship->value / 100);
            } else {
                $amount_applied = new \Money\Money(
                    (string)round($scholarship->value * 100),
                    \App\Helpers\MoneyHelper::getCurrency()
                );
            }
        }

        $data['amount_applied'] = $amount_applied;

        return StudentScholarship::create($data);
    }

    public function getStudentScholarships($student_id)
    {
        return StudentScholarship::with(['scholarship'])
            ->where('student_id', $student_id)
            ->orderByDesc('approval_date')
            ->get();
    }

    public function revokeStudentScholarship($student_scholarship_id)
    {
        $ss = StudentScholarship::findOrFail($student_scholarship_id);
        $ss->update(['status' => 'revoked']);
        return $ss;
    }
}
