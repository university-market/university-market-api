<?php require_once './core/start.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Scripts</title>
    <style>
        body {
            padding: 10px;
            background: #3e3e3e;
            color: #fff;
            font-family: 'Arial';
            line-height: 25px;
        }
        section {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }
        section > div > button {
            height: 100%;
            background: #93b5ff;
            color: #0040c9;
            border: solid 1px #0040c9;
            border-radius: 10px;
            transition: ease-in-out 0.1s;
        }
        section > div > button:hover {
            filter: brightness(1.2);
            cursor: pointer;
        }
        .alert {
            display: block;
            text-align: center;
            border: 1px solid #0040c9;
            border-radius: 10px;
            background: #93b5ff;
            color: #0040c9;
            padding: 10px 25px;
            margin: 15px 0px;
        }
        .output {
            background: #000;
            color: #fff;
            font-family: 'Monospace';
            padding: 10px;
        }
    </style>
</head>
<body>

    <h1>Controle de Scripts</h1>

    <section>

        <div>
            <p>Informações:</p>
        
            <ul>
                <li>Data atual: <?=$today;?></li>
                <li>Última atualização: <?=$last_update ?? 'Sem registro';?></li>
                <li>Ambiente: <?=strtoupper($env);?></li>
            </ul>
        </div>

        <div>
            <button id="onExecute">
                Executar Controle de Scripts
            </button>
        </div>


    </section>
    
    <div class="alert">
        <?=$operation_state; ?>
    </div>

    <?php if ($executed) :?>

        <h2>Output da execução</h2>
        
        <div class="output">
            
            <?php
                // Executa scripts que ainda não foram rodados localmente
                $control->executeScripts();

                // Finaliza a operação atualizando as informações de scripts executados
                $control->finalizeOperation();
            ?>
            
        </div>

    <?php endif; ?>

    <script>

        const btnExec = document.getElementById('onExecute');
        btnExec.onclick = function() {
            
            if (confirm('Deseja realmente executar os scripts pendentes?')) {

                window.location.href = './?run=true';
            }
        }
    </script>
</body>
</html>