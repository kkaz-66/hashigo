<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @foreach($arr["results"]["shop"] as $val)
        <img src='{{$val["logo_image"]}}' alt="">
        {{$val["name"]}}<br/>
        {{$val["id"]}}<br/>
        {{$val["address"]}}<br/>
        {{$val["station_name"]}}<br/>
        {{$val["lat"]}}<br/>
        {{$val["lng"]}}<br/>
        <br/>
    @endforeach
</body>
</html>