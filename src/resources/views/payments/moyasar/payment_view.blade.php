<!DOCTYPE html>
<html>
<head>
    <title>Moyasar Payment</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- Moyasar Styles -->
    <link rel="stylesheet" href="https://cdn.moyasar.com/mpf/1.7.3/moyasar.css" />

    <!-- Moyasar Scripts -->
    <script src="https://polyfill.io/v3/polyfill.min.js?features=fetch"></script>
    <script src="https://cdn.moyasar.com/mpf/1.7.3/moyasar.js"></script>

    <!-- Download CSS and JS files in case you want to test it locally, but use CDN in production -->
</head>
<body>
<div class="mysr-form"></div>

<script>
    Moyasar.init({
        element: '.mysr-form',
        // Amount in the smallest currency unit.
        // For example:
        // 10 SAR = 10 * 100 Halalas
        // 10 KWD = 10 * 1000 Fils
        // 10 JPY = 10 JPY (Japanese Yen does not have fractions)
        amount: 1000,
        currency: 'SAR',
        description: 'Coffee Order #1',
        publishable_api_key: 'pk_test_AQpxBV31a29qhkhUYFYUFjhwllaDVrxSq5ydVNui',
        callback_url: 'https://moyasar.com/thanks',
        methods: ['creditcard'],
    })
</script>
</body>
</html>
