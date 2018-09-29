<?php
  
  $bd = mysqli_connect("localhost","root","","hfw");  

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

  $idsemana  = "";
  $semana  = "";
  $consumosemana  = "";
  
  
  
   
  if ( ! isset($_POST["acao"]) )
     $descr_acao = "Incluir";
  else {
	 
	 $acao = $_POST["acao"];
	 
	 if (strtoupper($acao) == "INCLUIR" || strtoupper($acao) == "SALVAR" ) {
	    $idsemana = mysqli_real_escape_string($bd, $_POST["idsemana"] ) ;
	    $semana  = mysqli_real_escape_string($bd, $_POST["semana"] ) ;
	    $consumosemana = mysqli_real_escape_string($bd, $_POST["consumosemana"] ) ;
	    

     }
     
     if (strtoupper($acao) == "INCLUIR") {
		 
		 $sql = "insert into consumo (idsemana, semana, consumosemana)
		                values ('$idsemana','$semana','$consumosemana')";
		                
		 if ( ! mysqli_query($bd, $sql) ) {
			
			 if ( mysqli_errno($bd) == 1062 )
			    $mensagem = "O numero registrado '$idsemana' já existe, tente outro!";
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
		 
		 $sql = " update consumo
		          set 
		              semana = '$semana',
		              consumosemana = '$consumosemana'
		          where
		              idsemana = '$idsemana' ";
		              
		 if ( ! mysqli_query($bd, $sql) ) {
			 
			 $mensagem = "<h3>Ocorreu um erro ao alterar os dados </h3>
			              <h3>Erro: ".mysqli_error($bd)."</h3>
			              <h3>SQL: $sql </h3>
			              <h3>Código: ".mysqli_errno($bd)."</h3>";
			 
		 }
	 }

     if (strtoupper($acao) == "EXCLUIR") {
        
        $idsemana = $_POST["idsemana"];

     	$descr_acao = "Incluir";

     	$sql = "delete from consumo where idsemana = '$idsemana' ";

     	if ( ! mysqli_query($bd, $sql) ) {
			 
			 $mensagem = "<h3>Ocorreu um erro ao excluir os dados </h3>
			              <h3>Erro: ".mysqli_error($bd)."</h3>
			              <h3>SQL: $sql </h3>
			              <h3>Código: ".mysqli_errno($bd)."</h3>";
			 
		 }

		 $idsemana ="";
     }

     if (strtoupper($acao) == "BUSCAR") {

        $idsemana = $_POST["$idsemana"];
     	
     	$descr_acao = "Salvar";

     	$sql = "select idsemana, semana, consumosemana,  
     		        from consumo
     	        where idsemana = '$idsemana' ";

     	$resultado = mysqli_query($bd, $sql);

     	if (mysqli_num_rows($resultado) == 1) {

             $dados = mysqli_fetch_assoc($resultado);

             $idsemana = $dados["idsemana"];
             $semana = $dados["semana"];
             $consumosemana = $dados["consumosemana"];
            
     	}

     }

   }

   
   $sql_listar = "select idsemana, semana, consumosemana from consumo order by idsemana";
	 
   $lista = mysqli_query($bd, $sql_listar);
	 
   if ( mysqli_num_rows($lista) > 0 ) {
		
		$tabela = "<table border='4'>";
		
		$tabela = $tabela."<tr><th>Numero de Registro</th><th>Seamana Atual</th>
		             <th>Consumo Acumulado</th><th>Alterar</th><th>Excluir</th></tr>";
		 
		while ( $dados = mysqli_fetch_assoc($lista) ) {
		   
		   $vidsemana = $dados["idsemana"];
		   $vidsemana  = $dados["semana"];
		   $vconsumosemana  = $dados["consumosemana"];
		   

		   
		   $alterar = "<form method='post'>
		                  <input type='hidden' name='idsemana' value='$idsemana'>
		                  <input type='hidden' name='acao' value='BUSCAR'>
		                  <input type='image' src='$btnAlterar'> 
		               </form>";
		   
		   $excluir = "<form method='post'>
		                  <input type='hidden' name='idsemana' value='$idsemana'>
		                  <input type='hidden' name='acao' value='EXCLUIR'>
		                  <input type='image' src='$btnExcluir'> 
		               </form>";
		   
		   $tabela = $tabela."<tr><td>$vidsemana</td><td>$semana</td>
		        <td>$consumosemana</td><td>$alterar</td><td>$excluir</td></tr>";
		}
		
		$tabela = $tabela."</table>"; 
   } else 
	    $tabela = "não há dados para listar";
	    

?>

<html>

<head>
	<title>Cadastro de Consumo de Ração</title>
	<meta charset="utf-8" />
</head>

<body>

	<h2>Cadastro de Consumo de Ração</h2>
	
	<?php echo $mensagem; ?>
	
	<fieldset>
		<legend>Dados do Usuário</legend>
		
		<form action="controle_semanal.php" method="post">
		Numero de Registro:  <input type="text" name="idsemana" value="<?php echo $idsemana; ?>"> <br>
		Semana Atual: <input type="text" name="semana" value="<?php echo $semana; ?>" size="40"> <br>
		Consumo Acumulado: <input type="text" name="consumosemana" value="<?php echo $consumosemana; ?>"> <br>
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

