<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=h1, initial-scale=1.0">
    <title>Protocolos</title>
    <style>
     


        body {
            padding: 50px;
        }

        #title {
            width: 100%;
            text-align: center;
            margin-bottom: 40px;
        }

        *{
            margin: 0;
            padding: 0;
    
        }

        h1 {
            margin-bottom: 15px;
        }

        h2 {
            margin-bottom: 20px;
        }

        h3 {
            margin-top: 10px;
        }


        .data {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div id="title">
        <h1>Protocolos</h1>
        <p>Prefeitura de São Leopoldo</p>
        <p> Data de emissão: 
            <?php
                setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
                $dataAtual = strftime('%d/%m/%Y');
                echo $dataAtual
            ?>
        </p>
    </div>
    @foreach($protocolos as $protocolo)
    <div>
        <br><br>
        <h2>Protocolo número {{$protocolo->numero}}:</h2>
        <h4>Constribuinte: {{$protocolo->pessoa->nome}}</h4>
        <h3>Descrição: </h3>
        <p>{{ $protocolo->descricao }}</p>
        <p class="data">
            <?php
                    $dataString = $protocolo->data_registro;
                    list($ano, $mes, $dia) = explode('-', $dataString);
                    $formatada = "$dia/$mes/$ano";
                    echo $formatada;  
            ?>
        </p>
        <br><br>
        <hr>
    </div>
    @endforeach
</body>
</html>