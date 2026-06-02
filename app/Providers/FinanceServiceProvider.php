<?php

namespace App\Providers;

use App\Interfaces\Finance\AuditLogInterface;
use App\Interfaces\Finance\BudgetInterface;
use App\Interfaces\Finance\DiscountInterface;
use App\Interfaces\Finance\ExpenseInterface;
use App\Interfaces\Finance\FeeStructureInterface;
use App\Interfaces\Finance\FeeTypeInterface;
use App\Interfaces\Finance\InvoiceInterface;
use App\Interfaces\Finance\PaymentInterface;
use App\Interfaces\Finance\ScholarshipInterface;
use App\Interfaces\Finance\VendorInterface;
use App\Repositories\Finance\AuditLogRepository;
use App\Repositories\Finance\BudgetRepository;
use App\Repositories\Finance\DiscountRepository;
use App\Repositories\Finance\ExpenseRepository;
use App\Repositories\Finance\FeeStructureRepository;
use App\Repositories\Finance\FeeTypeRepository;
use App\Repositories\Finance\InvoiceRepository;
use App\Repositories\Finance\PaymentRepository;
use App\Repositories\Finance\ScholarshipRepository;
use App\Repositories\Finance\VendorRepository;
use Illuminate\Support\ServiceProvider;

class FinanceServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(FeeTypeInterface::class, FeeTypeRepository::class);
        $this->app->bind(FeeStructureInterface::class, FeeStructureRepository::class);
        $this->app->bind(InvoiceInterface::class, InvoiceRepository::class);
        $this->app->bind(PaymentInterface::class, PaymentRepository::class);
        $this->app->bind(DiscountInterface::class, DiscountRepository::class);
        $this->app->bind(ScholarshipInterface::class, ScholarshipRepository::class);
        $this->app->bind(ExpenseInterface::class, ExpenseRepository::class);
        $this->app->bind(VendorInterface::class, VendorRepository::class);
        $this->app->bind(BudgetInterface::class, BudgetRepository::class);
        $this->app->bind(AuditLogInterface::class, AuditLogRepository::class);
    }

    public function boot()
    {
        //
    }
}
