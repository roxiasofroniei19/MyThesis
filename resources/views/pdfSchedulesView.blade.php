<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <title>Programarile zilei</title>
    <style>
        .logo {
            display: block;
            max-width: 75px;
            margin: 1em auto;
        }

        body {
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">

        <img src={{public_path('storage/app/public/img/logo.jpg')}} class="logo">
    </div>
    <div class="p-3 mt-5">
        <h4 class="text-center">Programarile zilei <br> {{$date}}</h4>
        <table class="table table-striped mt-5">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Student</th>
                    <th scope="col">Titlu</th>
                    <th scope="col">Descriere</th>
                    <th scope="col">Ora</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($schedules ?? '' as $schedule)
                <tr>
                    <th scope="row">{{$loop->index}}</th>
                    <td>{{$schedule->ClientName}}</td>
                    <td>{{$schedule->ScheduleName}}</td>
                    <td>{{$schedule->ScheduleDescription}}
                    <td>{{$schedule->ScheduleTime}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>
