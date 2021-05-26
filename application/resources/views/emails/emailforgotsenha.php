<!DOCTYPE html>
<html lang="br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Email de Recuperação</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;1,100&family=Stint+Ultra+Condensed&display=swap');

        body {
            margin: 0px;
            background: #024bad;
            font-family: 'Roboto', sans-serif;
            font-weight: 300;
            font-size:12pt;
        }

        .container {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            
        }

        .box {
            border: 2px solid #024bad;
            padding:10px 15px;
            border-radius: 8px;
            width: 500px;
            height: 500px;
            background: #efefef;
            
        }

        .titulo {
            font-size: 2.5em;
            text-align: center;
        }

        .corpo {
            line-height: 25px;
            padding-left: 15px;
            padding-top: 20px;
            font-size: 1.2em;
            
        }
        p{
            padding:10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="box">
            <h1 class="titulo">Recuperação De Senha</h1>
            <div class="corpo">
                <p>Olá! recebemos um pedido de recuperação de senha, para processeguir é apenas colocar o código abaixo!
                </p>
                <hr>
                <p>Token:{{$details->token}}</p>
                <hr>
                <p>May the Force be with you<br>U.M Team</p>
            </div>
        </div>
    </div>
</body>

</html>