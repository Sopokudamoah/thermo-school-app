<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $payment->receipt_number }}</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            line-height: 1.5;
            padding: 40px;
        }

        .receipt-header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #eee;
            pb: 20px;
            margin-bottom: 30px;
        }

        .receipt-title {
            font-size: 24px;
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
            w: 100%;
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

        .amount-row {
            display: flex;
            justify-content: flex-end;
        }

        .total-box {
            bg: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            width: 250px;
        }

        .total-label {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }

        .total-value {
            font-size: 24px;
            font-weight: 900;
            color: #059669;
            display: flex;
            justify-content: space-between;
        }

        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
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
        Print Receipt
    </button>
</div>

<div class="receipt-header">
    <div>
        <div class="receipt-title">{{ $school_setting->school_name ?? config('app.name') }}</div>
        <p style="margin: 5px 0; color: #666;">School Financial Management</p>
    </div>
    <div style="text-align: right;">
        <div class="label">Receipt Number</div>
        <div class="value" style="color: #4f46e5;">{{ $payment->receipt_number }}</div>
    </div>
</div>

<div class="info-grid">
    <div>
        <div class="label">Payment From</div>
        <div class="value">{{ $payment->student->first_name }} {{ $payment->student->last_name }}</div>
        <div style="font-size: 14px; color: #666;">Student ID: {{ $payment->student_id }}</div>
    </div>
    <div style="text-align: right;">
        <div class="label">Payment Date</div>
        <div class="value">{{ $payment->payment_date->format('F d, Y') }}</div>
        <div class="label" style="margin-top: 15px;">Method</div>
        <div class="value capitalize">{{ str_replace('_', ' ', $payment->method) }}</div>
    </div>
</div>

<table style="width: 100%;">
    <thead>
    <tr>
        <th>Invoice Details</th>
        <th style="text-align: right;">Amount Applied</th>
    </tr>
    </thead>
    <tbody>
    @foreach($payment->invoices as $invoice)
        <tr>
            <td>
                <div style="font-weight: bold;">Invoice #{{ $invoice->invoice_number }}</div>
                <div style="font-size: 12px; color: #666;">Issued: {{ $invoice->issue_date->format('M d, Y') }}</div>
            </td>
            <td style="text-align: right; font-weight: bold;">{{ $school_setting->currency_symbol ?? '$' }}{{ number_format($invoice->pivot->amount, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="amount-row">
    <div class="total-box">
        <div class="total-label">
            <span>Total Received</span>
        </div>
        <div class="total-value">
            <span>{{ $school_setting->currency_code ?? 'USD' }}</span>
            <span>{{ $school_setting->currency_symbol ?? '$' }}{{ number_format($payment->amount, 2) }}</span>
        </div>
    </div>
</div>

@if($payment->reference)
    <div style="margin-top: 20px;">
        <div class="label">Reference</div>
        <div style="font-size: 14px;">{{ $payment->reference }}</div>
    </div>
@endif

<div class="footer">
    <p>Thank you for your payment. This is a computer generated receipt and does not require a signature.</p>
    <p>&copy; {{ date('Y') }} {{ $school_setting->school_name ?? config('app.name') }}. All rights reserved.</p>
</div>
</body>
</html>
