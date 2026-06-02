<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            line-height: 1.5;
            padding: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .title {
            font-size: 28px;
            font-weight: bold;
            color: #4f46e5;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .label {
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .value {
            font-size: 16px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        th {
            text-align: left;
            border-bottom: 1px solid #eee;
            padding: 10px 0;
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
        }

        td {
            padding: 15px 0;
            border-bottom: 1px solid #f9f9f9;
            font-size: 14px;
        }

        .totals-section {
            display: flex;
            justify-content: flex-end;
        }

        .totals-box {
            width: 300px;
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .grand-total {
            border-top: 1px solid #ddd;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 18px;
            font-weight: 900;
            color: #4f46e5;
        }

        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .status-stamp {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 4px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
        }

        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-overdue {
            background: #fee2e2;
            color: #991b1b;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="no-print" style="margin-bottom: 20px; display: flex; justify-content: flex-end;">
    <button onclick="window.print()"
            style="background: #4f46e5; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold;">
        Print Invoice
    </button>
</div>

<div class="header">
    <div>
        <div class="title">{{ $school_setting->school_name ?? config('app.name') }}</div>
        <p style="margin: 5px 0; color: #666;">School Management System</p>
    </div>
    <div style="text-align: right;">
        <div class="label">Invoice Number</div>
        <div class="value">{{ $invoice->invoice_number }}</div>
        <div style="margin-top: 10px;">
                <span
                    class="status-stamp status-{{ $invoice->status == 'paid' ? 'paid' : ($invoice->status == 'overdue' ? 'overdue' : 'pending') }}">
                    {{ ucfirst($invoice->status) }}
                </span>
        </div>
    </div>
</div>

<div class="info-grid">
    <div>
        <div class="label">Student Details</div>
        <div class="value">{{ $invoice->student->first_name }} {{ $invoice->student->last_name }}</div>
        <div style="font-size: 14px; color: #666;">Student ID: {{ $invoice->student_id }}</div>
        <div style="font-size: 14px; color: #666;">{{ $invoice->session->session_name }}
            - {{ $invoice->schoolClass->class_name }}</div>
    </div>
    <div style="text-align: right;">
        <div class="label">Dates</div>
        <div class="value">Issued: {{ $invoice->issue_date->format('F d, Y') }}</div>
        <div class="value" style="color: #ef4444;">Due: {{ $invoice->due_date->format('F d, Y') }}</div>
    </div>
</div>

<table>
    <thead>
    <tr>
        <th>Description</th>
        <th style="text-align: right;">Amount</th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoice->items as $item)
        <tr>
            <td>{{ $item->feeType->name }}</td>
            <td style="text-align: right; font-weight: bold;">{{ $school_setting->currency_symbol ?? '$' }}{{ number_format($item->amount, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="totals-section">
    <div class="totals-box">
        <div class="total-row">
            <span>Subtotal</span>
            <span>{{ $school_setting->currency_symbol ?? '$' }}{{ number_format($invoice->subtotal, 2) }}</span>
        </div>
        @if($invoice->discount_amount > 0)
            <div class="total-row" style="color: #059669;">
                <span>Discounts</span>
                <span>-{{ $school_setting->currency_symbol ?? '$' }}{{ number_format($invoice->discount_amount, 2) }}</span>
            </div>
        @endif
        @if($invoice->scholarship_amount > 0)
            <div class="total-row" style="color: #4f46e5;">
                <span>Scholarships</span>
                <span>-{{ $school_setting->currency_symbol ?? '$' }}{{ number_format($invoice->scholarship_amount, 2) }}</span>
            </div>
        @endif
        <div class="total-row grand-total">
            <span>Total Amount</span>
            <span>{{ $school_setting->currency_symbol ?? '$' }}{{ number_format($invoice->total, 2) }}</span>
        </div>
        <div class="total-row" style="margin-top: 10px; color: #059669; font-weight: bold;">
            <span>Amount Paid</span>
            <span>{{ $school_setting->currency_symbol ?? '$' }}{{ number_format($invoice->paid_amount, 2) }}</span>
        </div>
        <div class="total-row" style="color: #ef4444; font-weight: 900; font-size: 16px;">
            <span>Balance Due</span>
            <span>{{ $school_setting->currency_symbol ?? '$' }}{{ number_format($invoice->balance, 2) }}</span>
        </div>
    </div>
</div>

@if($invoice->notes)
    <div style="margin-top: 40px;">
        <div class="label">Notes</div>
        <div
            style="font-size: 14px; color: #666; border-left: 3px solid #eee; padding-left: 15px;">{{ $invoice->notes }}</div>
    </div>
@endif

<div class="footer">
    <p>Please pay your fees by the due date to avoid any late penalties.</p>
    <p>Payments can be made via Cash, Bank Transfer, or Online Portal.</p>
    <p>&copy; {{ date('Y') }} {{ $school_setting->school_name ?? config('app.name') }}. All rights reserved.</p>
</div>
</body>
</html>
