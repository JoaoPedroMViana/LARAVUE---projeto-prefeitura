<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=h1, initial-scale=1.0">
    <title>Protocolo</title>
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

        #situacao {
            margin-top: 10px;
        }
        .acompanhamentos {
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div id="title">
        <h1>Protocolo n° {{$protocolo->numero}}</h1>
        <p>Prefeitura de São Leopoldo</p>
        <p> Data de emissão: 
            <?php
                setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
                $dataAtual = strftime('%d/%m/%Y');
                echo $dataAtual
            ?>
        </p>
    </div>
    <div>
        <br>
        <h2>Protocolo:</h2>
        <br>
        <h4>Constribuinte: {{$protocolo->pessoa->nome}}</h4>
        <h4>Departamento: {{$protocolo->departamento->nome}}</h4>
        <h3>Descrição: </h3>
        <p>{{ $protocolo->descricao }}</p>
        <p id="situacao">Situação: 
            <?php
                if($protocolo->situacao == 'A'){
                    echo 'Aberto';
                }else if($protocolo->situacao == 'E'){
                    echo 'Em andamento';
                }else{
                    echo 'Solucionado';
                }
            ?>

        </p>
        <p class="data"> Data de registro: 
            <?php
                    $dataString = $protocolo->data_registro;
                    list($ano, $mes, $dia) = explode('-', $dataString);
                    $formatada = "$dia/$mes/$ano";
                    echo $formatada;  
            ?>
        </p>
        <p>Prazo: {{$protocolo->prazo}}</p>
        <br><br>
        <h2>Acompanhamentos:</h2>
        @foreach($acompanhamentos as $acompanhamento)
        <div class="acompanhamentos">
            <p>Descrição: <br><br>{{$acompanhamento->descricao}}</p>
            <br>
            <p> Data: 
            <?php
                    $dataString = $acompanhamento->data;
                    list($ano, $mes, $dia) = explode('-', $dataString);
                    $formatada = "$dia/$mes/$ano";
                    echo $formatada;  
            ?>
        </p>
        </div>
        <hr>
        @endforeach
        @if (count($acompanhamentos) == 0)
            <p>O protocolo não possui acompanhamentos.</p>  
        @endif
    </div>

</body>
</html>