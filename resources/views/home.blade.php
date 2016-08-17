<!DOCTYPE html>
<html>
<head>
    <title>Indonesia Maimai Hourly Rating</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>
    <link href="{{ url('/css/app.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ url('/js/app.js') }}"></script>
</head>
<body>
    <br/>
    <div class="container">
        <div class="row col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-12">
            <div class="alert alert-success" role="alert">
                <strong>Last Update:</strong> {{ $last_update_date }}
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-6 col-xs-12">
            <table class="table table-striped">
                <thead class="thead-inverse">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Player Name</th>
                        <th class="text-center">Rating</th>
                        <th class="text-center">Arcade</th>
                    </tr>
                </thead>
                @foreach( $players as $player )
                    <tr>
                        <td class="text-center">{{ $player['rank'] }}</td>
                        <td class="text-center">{{ $player['player']->name }}</td>
                        <td class="text-center">{{ $player['player']->rating }}</td>
                        <td class="text-center">{{ $arcades[$player['player']->arcade_id] }}</td>
                    </tr>
                @endforeach
            </table>
            </div>

            <div class="col-lg-6 col-md-6 col-xs-12">
            <table class="table table-striped">
                <thead class="thead-inverse">
                    <tr>
                        <th class="text-center">Arcade Name</th>
                        <th class="text-center">Rating 13 Count</th>
                    </tr>
                </thead>
                @foreach( $arcades as $arcade_id => $name )
                    <tr>
                        <td class="text-center">{{ $name }}</td>
                        <td class="text-center">{{ $rating_13_data[$arcade_id] }}</td>
                    </tr>
                @endforeach
            </table>
            </div>
        </div>

        <div class="row col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-12">
            <div class="alert alert-warning fade in" role="alert">
                <strong>Notice:</strong> Bila ada saran fitur atau laporan error bisa kontak saya melalui <a href="https://www.facebook.com/whitesnlol">Facebook</a>.
                <br>
                Source Code untuk Maimai Live Rating dapat dilihat di <a href="https://github.com/whitesn/MaimaiLiveRating">sini</a> (feel free to make PR if necessary).
            </div>
        </div>
    </div>
</body>
</html>
