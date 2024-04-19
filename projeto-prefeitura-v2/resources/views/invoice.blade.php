<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=h1, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    @foreach($protocolos as $protocolo)
        <br><br>
        <h2>Protocolo número {{$protocolo->numero}}</h2>
        <h4>Constribuinte: {{$protocolo->pessoa->nome}}</h4>
        <h3>Descrição: </h3>
        <p>{{ $protocolo->descricao }}</p>
        <?php
    
        ?>
        <p>{{ $protocolo->data_registro }}</p>
        <br><br>
        <hr>
    @endforeach
</body>
</html>