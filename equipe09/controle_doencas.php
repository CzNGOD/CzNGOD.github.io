<?php

  $bd = mysqli_connect("localhost","root","","hfw");

  if ($bd) {
  	mysqli_set_charset($bd, "utf8");
  } else {
	 echo "Não foi possível conectar o BD <br>";
	 echo "Mensagem de erro: ".mysqli_connect_error() ;
	 exit();
  }

$mensagem = "";

    $iddoenca= "";
    $ocorrenciadoenca= "";
    
    
    
    
    if ( ! isset($_POST["acao"]) )
        $descr_acao = "CADASTRAR";
    else {
        $acao = $_POST["acao"];
        
        if (strtoupper($acao) == "CADASTRAR" || strtoupper($acao) == "CADASTRAR") {
            
            $iddoenca = mysqli_real_escape_string($bd, $_POST["iddoenca"]);
            $ocorrenciadoenca = mysqli_real_escape_string($bd, $_POST["ocorrenciadoenca"]);
                
            
             
        }  
        
        if (strtoupper($acao) == "CADASTRAR") {
            
            $sql = "insert into doenca (iddoenca, ocorrenciadoenca)
                        values ('$iddoenca', '$ocorrenciadoenca')";
            
            if (! mysqli_query($bd, $sql)) {
                if (mysqli_errno($bd) == 1062)
                    $mensagem = "<p style='color: red;'>O numero informado '$iddoenca' já existe, tente outro!</p>";
                else
                    $mensagem = "<h3 style='color: red;'>OCORREU UM ERRO AO INSERIR OS DADOS</h3>
                                <h3>Erro: ".mysqli_error($bd)."</h3>
                                <h3>Código: ".mysqli_errno($bd)."</h3>";
                
                $descr_acao = "CADASTRAR";
            } else{
                $descr_acao = "CADASTRAR";
                $mensagem = "<p style='color: green;'>Cadastro efetuado com sucesso!</p><br>";
            }
        }
        
        if (strtoupper($acao) == "CADASTRAR") {
            
            $descr_acao = "CADASTRAR";
            
            $sql = " update doenca
                     set
                        ocorrenciadoenca = '$ocorrenciadoenca',
                     where
                        iddoenca = '$iddoenca'";
            
            if ( ! mysqli_query($bd, $sql) ){
                $mensagem = "<h3 style='color: red;'>Ocorreu um erro ao alterar os dados</h3>
                <h3>".mysqli_error($bd)."</h3>".$sql."<h4>".mysqli_errno($bd)."</h4>";
            }else{
            	$mensagem = "<p style='color: green;'>Dados cadastrados com sucesso!</p>";
            }
        }
        
        
        if (strtoupper($acao) == "BUSCAR") {
            
            $iddoenca = $_POST["iddoenca"];
            
            $descr_acao = "CADASTRAR";
            
            $sql = "select iddoenca, ocorrenciadoenca,  
                    from doenca
                    where iddoenca = '$iddoenca'"; 
            
            $resultado = mysqli_query($bd, $sql);
            
            if (mysqli_num_rows($resultado) == 1) {
                
                $dados = mysqli_fetch_assoc($resultado);
                
                $iddoenca = $dados["iddoenca"];
                $ocorrenciadoenca = $dados["ocorrenciadoenca"];
                
               
            }
        }
    }


       
?>

<html>

<head>
    <title>Cadastro de Doenças Encontradas</title>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="imagens/icone.jpeg" type="image/x-icon" />
    
    <link rel="stylesheet" type="text/css" href="estilos.css"></link>
</head>

<body>
    <center>
	<h3>Cadastro de Doenças Encontradas</h3>
    
    

    <fieldset class="fieldcad1">
        <legend><h5>Inclusão de dados</h5></legend>
        
        <form action="controle_doencas.php" method="post">
            Numero de registro:<input  type="text" name="iddoenca" value="<?php echo $iddoenca; ?>"><br><br>
            Data de Ocorrencia:<input  type="text" name="ocorrenciadoenca" value="<?php echo $ocorrenciadoenca; ?>"><br><br>
            
            
            <br><br>
            <input type="submit" " value="LIMPAR">
            <input type="submit" name="acao"  value="<?php echo $descr_acao; ?>">
        </form>
    </fieldset>
    
   
	<br><br>
	
    <?php echo $mensagem ?>
</center>
</body>

</html>

<?php
  
  mysqli_close($bd);

?>


 ?>