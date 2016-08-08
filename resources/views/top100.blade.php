<!DOCTYPE html>
<html>
<head>
    <title>Indonesia Maimai Daily Top 100</title>
    <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>
    <link href="{{ url('/css/app.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ url('/js/app.js') }}"></script>
</head>
<body>
    <br/>

    <div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-12">
        <div class="alert alert-warning fade in" role="alert">
            <strong>Notice:</strong> Bila ada saran fitur (yang bisa saya kerjakan) atau laporan error bisa tolong diemailkan ke : dylan@whitesn.net

            <br><br>

            <strong></strong>Situs ini dihost dengan uang yang keluar dari kantong saya sendiri. Bila ada keikhlasan untuk donasi sesedikitnya 10,000 rupiah saja akan
            sangat membantu (contact me <a href="https://www.facebook.com/whitesnlol">here</a>).

            <br><br>

            <strong>Note:</strong> Big thanks buat yang minjemin accountnya dari Arcade masing-masing :)
        </div>

        <div class="alert alert-success" role="alert">
            <strong>Last Update:</strong> {{ $last_update_date }}
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-xs-12 nopadding">
    <table class="table table-striped">
        <thead class="thead-inverse">
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Player Name</th>
                <th class="text-center">Rating</th>
                <th class="text-center">Arcade</th>
            </tr>
        </thead>
        <?php $i = 1 ?>
        @foreach( $players as $player )
            <tr>
                <td class="text-center">{{ $i++ }}</td>
                <td class="text-center">{{ $player->name }}</td>
                <td class="text-center">{{ $player->rating }}</td>
                <td class="text-center">{{ $arcades[$player->arcade_id] }}</td>
            </tr>
        @endforeach
    </table>
    </div>

    <div class="col-lg-6 col-md-6 col-xs-12 nopadding">
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
</body>
</html>
