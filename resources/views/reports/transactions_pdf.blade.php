<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>รายงานประวัติการเคลื่อนไหว</title>
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("file://{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url("file://{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
        }

        body {
            font-family: 'THSarabunNew', sans-serif;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .text-green { color: green; }
        .text-red { color: red; }
    </style>
</head>
<body>
    <div class="header">
        <h2>รายงานประวัติการเคลื่อนไหว (Transaction History)</h2>
        <p>ข้อมูล ณ วันที่: {{ $generated_at }} (ล่าสุด 100 รายการ)</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>วันที่/เวลา</th>
                <th>ผู้ทำรายการ</th>
                <th>สินค้า</th>
                <th>ประเภท</th>
                <th>จำนวน</th>
                <th>หมายเหตุ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $transaction->user->name ?? 'N/A' }}</td>
                    <td>{{ $transaction->product->name ?? '(สินค้าถูกลบ)' }}</td>
                    <td>
                        @if($transaction->type == 'in')
                            <span class="text-green">รับเข้า</span>
                        @else
                            <span class="text-red">เบิกออก</span>
                        @endif
                    </td>
                    <td>{{ number_format($transaction->amount) }}</td>
                    <td>{{ $transaction->note }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
