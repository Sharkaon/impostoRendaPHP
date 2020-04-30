<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles/style.css">
        <title>Calcular Impostos</title>
    </head>
    <body>
        <?php
            if(isset($_POST["calcular"])){
                $nome = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_SPECIAL_CHARS);
                $salarioBruto = filter_input(INPUT_POST, "salario", FILTER_SANITIZE_NUMBER_FLOAT);
                if(filter_var($salarioBruto, FILTER_VALIDATE_FLOAT)){
                    if($salarioBruto>0){
                        if($salarioBruto<=1045){
                            $aliquotaINSS=0.075;
                        }else if($salarioBruto<=2089.60){
                            $aliquotaINSS=0.9;
                        }else if($salarioBruto<=3134.40){
                            $aliquotaINSS=0.12;
                        }else if($salarioBruto<=6101.06){
                            $aliquotaINSS=0.14;
                        }
                        
                        $impostoINSS=$aliquotaINSS*$salarioBruto;
                        $salarioPosINSS=$salarioBruto-$impostoINSS;
                        if($salarioPosINSS<=1903.98){
                            $aliquotaIRPF=0;
                            $desconto=0;
                        }else if($salarioPosINSS<=2826.65){
                            $aliquotaIRPF=0.075;
                            $desconto=142.80;
                        }else if($salarioPosINSS<=3751.05){
                            $aliquotaIRPF=0.15;
                            $desconto=354.80;
                        }else if($salarioPosINSS<=4664.68){
                            $aliquotaIRPF=0.225;
                            $desconto=636.13;
                        }else{
                            $aliquotaIRPF=0.275;
                            $desconto=10432.32;
                        }
                        
                        $impostoIRPF=$salarioPosINSS*$aliquotaIRPF-$desconto;
                        $salarioLíquido=$salarioPosINSS-$impostoIRPF;
                        if($file=fopen("registro.txt", "a")){
                            $registro="$nome|$salarioBruto|$impostoINSS|$impostoIRPF|$salarioLíquido\r\n";
                            if(fwrite($file, $registro)){
                            }
                        }
                    }else{
                        $invalid = true;
                    }                    
                }else{
                    $invalid = true;
                }
            }
        ?>

        <div class="row">
            <img src="imgs/receita.png" alt="Logo Receita Federal" id="logo">
        </div>

        <form action="index.php" method="post">
            <div class="row">
                <input type="text" name="nome" placeholder="NOME" required id="nome"
                pattern="^(?![ ])(?!.*[ ]{2})((?:e|da|do|das|dos|de|d'|D'|la|las|el|los)\s*?|(?:[A-Z][^\s]*\s*?)(?!.*[ ]$))+$"></input>
                <input type="text" name="salario" placeholder="SALÁRIO BRUTO" required id="salario"></input>
            </div>
            <div class="row">
                <button type="submit" name="calcular">CALCULAR</button>
            </div>
        </form>

        <div class="row">
            <?php
                if(isset($invalid)){
                    echo("<p>SALÁRIO INVÁLIDO</p>");
                }
            ?>
        </div>

        <div class="row">
            <table>
                <tr>
                    <th>Nome</th>
                    <th>Salário Bruto</th>
                    <th>INSS</th>
                    <th>IRPF</th>
                    <th>Salário</th>
                </tr>
                <?php
                    if(isset($salarioLíquido)){
                        echo("<tr>
                            <td>$nome</td>
                            <td>$salarioBruto</td>
                            <td>$impostoINSS</td>
                            <td>$impostoIRPF</td>
                            <td>$salarioLíquido</td>
                        </tr>");
                    }
                ?>
            </table>
        </div>
        
    </body>
</html>