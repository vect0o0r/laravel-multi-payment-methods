@php
    $url = $params['url']."/ePay/pg/epay?_v=" .$params['tij_MerchAuthKeyApi'];
@endphp
<form id='pgForm' method='post' action="{{$url}}" enctype='application/x-www-form-urlencoded'>
    @php
        $formKeys = Arr::except($params,['url']);
            foreach ($formKeys as $key => $value) {
        echo "<input type='hidden' name='$key' value='$value'>";
          }
    @endphp

</form>

<div style="position: fixed;top: 50%;left: 50%;transform: translate(-50%, -50%);text-align:center">
    Redirecting to PG ...
    <br>
    <b> DO NOT REFRESH</b>
</div>
<script type="text/javascript">
    document.getElementById('pgForm').submit();
</script>
