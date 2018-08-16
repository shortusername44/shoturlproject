<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>URL Shortener</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 54px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        .shorten-input {
            width: 50%;
            height: 30px;
            font-size: 14px;
            border-radius: 5px;
        }

        .submit-button {
            height: 30px;
        }
    </style>
</head>
<body>

    <div class="content">
        <div class="title m-b-md">
            URL Shortener
        </div>

        <div style="padding-top: 100px">
                <input id="url" class="shorten-input" type="text" name="url" placeholder="Paste your link here">
                <button id="button" class="submit-button" type="button">Click Me!</button>
        </div>
        <div style="padding-top: 30px">
            <p id="error" style="color: red;"></p>
            <p id="newurl"></p>
        </div>
    </div>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">

        $(document).ready(function () {
            $('#button').click(function(){
                document.getElementById("error").innerHTML = "";
                document.getElementById("newurl").innerHTML = "";
                $.ajax({
                    type: "POST",
                    url: "api/short_url_api/post",
                    data: "url="+ $('#url').val(),
                    success: function(response) {
                        console.log(response);
                        var obj = JSON.parse(response);
                        if(obj.error){
                            document.getElementById("error").innerHTML = obj.error;
                        }
                        if(obj.short_url){
                            document.getElementById("newurl").innerHTML = obj.short_url;
                        }
                    }
                });
            });
        });

    </script>

</body>
</html>
