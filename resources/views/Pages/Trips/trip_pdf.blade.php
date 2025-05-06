<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تفاصيل الرحلة</title>
    <style>
        @font-face {
            font-family: 'Amiri';
            src: url('{{ storage_path("fonts/Amiri-Regular.ttf") }}') format('truetype');
            font-weight: normal;
        }
        @font-face {
            font-family: 'Amiri';
            src: url('{{ storage_path("fonts/Amiri-Bold.ttf") }}') format('truetype');
            font-weight: bold;
        }
        body {
            font-family: 'Amiri', sans-serif;
            direction: rtl;
            text-align: right;
        }
    </style>
</head>
<body>
<h2>تفاصيل الرحلة</h2>
<p><strong>اسم الرحلة:</strong> {{ $trip->trip->name }}</p>
<p><strong>المندوب:</strong> {{ $trip->agent->name }}</p>
<p><strong>عدد الأشخاص:</strong> {{ optional($trip->detail)->total_people }}</p>
<p><strong>عدد الذكور:</strong> {{ optional($trip->detail)->male_count }}</p>
<p><strong>عدد الإناث:</strong> {{ optional($trip->detail)->female_count }}</p>
<p><strong>السعر:</strong> {{ optional($trip->detail)->price }} جنيه</p>
<p><strong>حالة الرحلة:</strong> {{ $trip->status }}</p>
</body>
</html>
