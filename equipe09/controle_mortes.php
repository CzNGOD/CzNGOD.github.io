<?php
  
  $bd = mysqli_connect("localhost","root","","hfw"); //"adelar " é o bd das notas fiscais 

  if ($bd) {
  	mysqli_set_charset($bd, "utf8");
  } else {
	 echo "Não foi possível conectar o BD <br>";
	 echo "Mensagem de erro: ".mysqli_connect_error() ;
	 exit();
  }
  
  $btnExcluir = "http://inf.fw.iffarroupilha.edu.br/~bruno/disciplinas/programacao_web1/excluir.png";
  $btnAlterar = "http://inf.fw.iffarroupilha.edu.br/~bruno/disciplinas/programacao_web1/alterar.png";


  $mensagem = "";

  $idmorte      = "";
  $pesoanimal     = "";
  $datamorte       = "";
  
  
  //Essas variáveis servem para indicar qual dos dois radios estarão marcados
  
   
  if ( ! isset($_POST["acao"]) )
     $descr_acao = "Incluir";
  else {
	 
	 $acao = $_POST["acao"];
	 
	 if (strtoupper($acao) == "INCLUIR" || strtoupper($acao) == "SALVAR" ) {
	    $idmorte = mysqli_real_escape_string($bd, $_POST["idmorte"] ) ;
	    $pesoanimal  = mysqli_real_escape_string($bd, $_POST["pesoanimal"] ) ;
	    $datamorte = mysqli_real_escape_string($bd, $_POST["datamorte"] ) ;
	    

     }
     
     if (strtoupper($acao) == "INCLUIR") {
		 
		 $sql = "insert morte (idmorte, pesoanimal, datamorte)
		                values ('$idmorte','$pesoanimal','$datamorte')";
		                
		 if ( ! mysqli_query($bd, $sql) ) {
			
			 if ( mysqli_errno($bd) == 1062 )
			    $mensagem = "O numero registrado '$idmorte' já existe, tente outro!";
			 else
			    $mensagem = "<h3>Ocorreu um erro ao inserir os dados </h3>
			              <h3>Erro: ".mysqli_error($bd)."</h3>
			              <h3>SQL: $sql </h3>
			              <h3>Código: ".mysqli_errno($bd)."</h3>";
		   	 
		   	 $descr_acao = "Incluir";
		 } else 
		     $descr_acao = "Salvar";
	 }
	 
	 if (strtoupper($acao) == "SALVAR") {
		 
		 $descr_acao = "Salvar";
		 
		 $sql = " update morte
		          set 
		              pesoanimal = '$pesoanimal',
		              datamorte = '$datamorte'
		          where
		              idmorte = '$idmorte' ";
		              
		 if ( ! mysqli_query($bd, $sql) ) {
			 
			 $mensagem = "<h3>Ocorreu um erro ao alterar os dados </h3>
			              <h3>Erro: ".mysqli_error($bd)."</h3>
			              <h3>SQL: $sql </h3>
			              <h3>Código: ".mysqli_errno($bd)."</h3>";
			 
		 }
	 }

     if (strtoupper($acao) == "EXCLUIR") {
        
        $idmorte = $_POST["idmorte"];

     	$descr_acao = "Incluir";

     	$sql = "delete from morte where idmorte = '$idmorte' ";

     	if ( ! mysqli_query($bd, $sql) ) {
			 
			 $mensagem = "<h3>Ocorreu um erro ao excluir os dados </h3>
			              <h3>Erro: ".mysqli_error($bd)."</h3>
			              <h3>SQL: $sql </h3>
			              <h3>Código: ".mysqli_errno($bd)."</h3>";
			 
		 }

		 $idmorte ="";
     }

     if (strtoupper($acao) == "BUSCAR") {

        $idmorte = $_POST["$idmorte"];
     	
     	$descr_acao = "Salvar";

     	$sql = "select idmorte, pesoanimal, datamorte,  
     		        from morte
     	        where idmorte = '$idmorte' ";

     	$resultado = mysqli_query($bd, $sql);

     	if (mysqli_num_rows($resultado) == 1) {

             $dados = mysqli_fetch_assoc($resultado);

             $idmorte = $dados["idmorte"];
             $pesoanimal = $dados["pesoanimal"];
             $datamorte = $dados["datamorte"];
            
     	}

     }

   }

   
   $sql_listar = "select idmorte, pesoanimal, datamorte from morte order by idmorte";
	 
   $lista = mysqli_query($bd, $sql_listar);
	 
   if ( mysqli_num_rows($lista) > 0 ) {
		
		$tabela = "<table border='4'>";
		
		$tabela = $tabela."<tr><th>Numero de Registro</th><th>Peso do Animal</th>
		             <th>Data da Ocorrencia</th><th>Alterar</th><th>Excluir</th></tr>";
		 
		while ( $dados = mysqli_fetch_assoc($lista) ) {
		   
		   $vidmorte = $dados["idmorte"];
		   $vpesoanimal  = $dados["pesoanimal"];
		   $vdatamorte  = $dados["datamorte"];
		   

		   
		   $alterar = "<form method='post'>
		                  <input type='hidden' name='idmorte' value='$idmorte'>
		                  <input type='hidden' name='acao' value='BUSCAR'>
		                  <input type='image' src='$btnAlterar'> 
		               </form>";
		   
		   $excluir = "<form method='post'>
		                  <input type='hidden' name='idmorte' value='$idmorte'>
		                  <input type='hidden' name='acao' value='EXCLUIR'>
		                  <input type='image' src='$btnExcluir'> 
		               </form>";
		   
		   $tabela = $tabela."<tr><td>$vidmorte</td><td>$vpesoanimal</td>
		        <td>$vdatamorte</td><td>$alterar</td><td>$excluir</td></tr>";
		}
		
		$tabela = $tabela."</table>"; 
   } else 
	    $tabela = "não há dados para listar";
	    

?>

<html>

<head>
	<title>Cadastro de daixa de animais</title>
	<meta charset="utf-8" />
</head>

<body>

	<h2>Dados do usuário</h2>
	
	<?php echo $mensagem; ?>
	
	<fieldset>
		<legend>Dados do Usuário</legend>
		
		<form action="controle_mortes.php" method="post">
		Numero de Registro:  <input type="text" name="idmorte" value="<?php echo $idmorte; ?>"> <br>
		Peso do Animal: <input type="text" name="pesoanimal" value="<?php echo $pesoanimal; ?>" size="40"> <br>
		Data da Ocorrencia: <input type="text" name="datamorte" value="<?php echo $datamorte; ?>"> <br>
		<br><br>
		
		<input type="submit" value="Novo">
		<input type="submit" name="acao" value="<?php echo $descr_acao; ?>">      
		      
		</form>
	
	</fieldset>

	<fieldset>
	<legend>Dados Cadastrados</legend>
	
	   <?php echo $tabela; ?>
	
	</fieldset>

	<br><br>
	
</body>

</html>

<?php
  
  mysqli_close($bd);

?>

