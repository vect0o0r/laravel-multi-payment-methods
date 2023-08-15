<!DOCTYPE html>
<html>
<head>
    <title>Moyasar Payment</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- Moyasar Styles -->
    <link rel="stylesheet" href="https://cdn.moyasar.com/mpf/1.7.3/moyasar.css"/>

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
        amount: {{$transaction_details['price'] * 100}},
        currency: "{{$transaction_details['currency_code']}}",
        description: "{{implode(', ',\Illuminate\Support\Arr::pluck($items,'name'))}}",
        publishable_api_key: "{{$publishable_api_key}}",
        callback_url: "{{$callback_url}}",
        metadata: {
            customer_details: {!!  json_encode($customer_details,true)!!},
            items: {!!json_encode($items,true)!!},
            transaction_details: {!!json_encode($transaction_details,true)!!},
        },
        methods: ['creditcard'],
        on_completed: function (payment) {
            return new Promise(async function (resolve, reject) {
                const payload =
                    await fetch("{{$process_url}}", {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json; charset=utf-8'},
                        body: JSON.stringify({
                            ...payment,
                            customer_details: {!!  json_encode($customer_details,true)!!},
                            items: {!!json_encode($items,true)!!},
                            transaction_details: {!!json_encode($transaction_details,true)!!},
                        }),
                    }).then((response) => {
                        console.log(response.ok);
                        if (response.ok) {
                            resolve(response)
                        } else {
                            reject(response)
                        }
                    })
            });
        }
    })
</script>
</body>
</html>
