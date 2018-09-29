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
  $btnAlterar = //"http://inf.fw.iffarroupilha.edu.br/~bruno/disciplinas/programacao_web1/alterar.png";


  $mensagem = "";

  $iddoenca= "";
  $ocorrenciadoenca= "";
  
  //Essas variáveis servem para indicar qual dos dois radios estarão marcados
  
  
  if ( ! isset($_POST["acao"]) )
     $descr_acao = "Incluir"; //
  else {
	 
	 $acao = $_POST["acao"];
	 
	 if (strtoupper($acao) == "INCLUIR" || strtoupper($acao) == "SALVAR" ) {
	    $iddoenca = mysqli_real_escape_string($bd, $_POST["iddoenca"] ) ;
	    $ocorrenciadoenca  = mysqli_real_escape_string($bd, $_POST["ocorrenciadoenca"] ) ;
	    
	    

     }
     
     if (strtoupper($acao) == "INCLUIR") {
		 
		 $sql = "insert into doenca (iddoenca, ocorrenciadoenca)
		                values ('$iddoenca','$ocorrenciadoenca')";
		                
		 if ( ! mysqli_query($bd, $sql) ) {
			
			 if ( mysqli_errno($bd) == 1062 )
			    $mensagem = "A nota  informada '$iddoenca' já existe, tente outra!";
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
		 
		 $sql = " update doenca 
		          set 
		              ocorrenciadoenca = '$ocorrenciadoenca',
		              
		          where
		              iddoenca = '$iddoenca' ";
		              
		 if ( ! mysqli_query($bd, $sql) ) {
			 
			 $mensagem = "<h3>Ocorreu um erro ao alterar os dados </h3>
			              <h3>Erro: ".mysqli_error($bd)."</h3>
			              <h3>SQL: $sql </h3>
			              <h3>Código: ".mysqli_errno($bd)."</h3>";
			 
		 }
	 }

     if (strtoupper($acao) == "EXCLUIR") {
        
        $iddoenca = $_POST["iddoenca"];

     	$descr_acao = "Incluir";

     	$sql = "delete from doenca where iddoenca = '$iddoenca' ";

     	if ( ! mysqli_query($bd, $sql) ) {
			 
			 $mensagem = "<h3>Ocorreu um erro ao excluir os dados </h3>
			              <h3>Erro: ".mysqli_error($bd)."</h3>
			              <h3>SQL: $sql </h3>
			              <h3>Código: ".mysqli_errno($bd)."</h3>";
			              
			
			 
			 
		 }

		 $iddoenca = " ";
     }

     if (strtoupper($acao) == "BUSCAR") {

        $iddoenca = $_POST["iddoenca"];
     	
     	$descr_acao = "Salvar";

     	$sql = "select iddoenca, ocorrenciadoenca
     		        from doenca 
     	        where iddoenca = '$iddoenca' ";

     	$resultado = mysqli_query($bd, $sql);

     	if (mysqli_num_rows($resultado) == 1) {

             $dados = mysqli_fetch_assoc($resultado);

             $iddoenca = $dados["iddoenca"];
             $ocorrencia = $dados["ocorrenciadoenca"];
             
     	}

     }

   }

   
	 
   $sql_listar = "select iddoenca, ocorrenciadoenca from doenca order by ocorrenciadoenca";
	 
   $lista = mysqli_query($bd, $sql_listar);
	 
   if ( mysqli_num_rows($lista) > 0 ) {
		
		$tabela = "<table border='4'>";
		
		$tabela = $tabela."<tr><th>Número da doença</th><th>Data da doença</th>
		             <th>Alterar</th><th>Excluir</th></tr>";
		 
		while ( $dados = mysqli_fetch_assoc($lista) ) {
		   
		   $vnum_nf = $dados["iddoenca"];
		   $vcliente  = $dados["ocorrenciadoenca"];
		   
		   
		   $alterar = "<form method='post'>
		                  <input type='hidden' name='iddoenca' value='$vnum_nf'>
		                  <input type='hidden' name='acao' value='BUSCAR'>
		                  <input type='image' src='$btnAlterar'> 
		               </form>";
		   
		   $excluir = "<form method='post'>
		                  <input type='hidden' name='iddoenca' value='$vnum_nf'>
		                  <input type='hidden' name='acao' value='EXCLUIR'>
		                  <input type='image' src='$btnExcluir'> 
		               </form>";
		   
		   $tabela = $tabela."<tr><td>$vnum_nf</td><td>$vcliente</td>
		        <td>$alterar</td><td>$excluir</td></tr>";
		}
		
		$tabela = $tabela."</table>"; 
   } else 
	    $tabela = "não há dados para listar";
	    

?>

<html>

<head>
	<title>Cadastro das doenças </title>
	<meta charset="utf-8" />
</head>

<body>
	<table>
	<h2>Página para cadastro das Doenças </h2>
	
	<?php echo $mensagem; ?>
	
	
		<legend>Dados das doenças:</legend>
		
		<form action="index.php" method="post">
		
		Código da doença :  <input type="text" name="iddoenca" value="<?php echo $iddoenca; ?>"> <br><br>
		Data da doença:     <input type="text" name="ocorrenciadoenca" value="<?php echo $ocorrenciadoenca; ?>" size="40"> <br>
		
		<br><br>
		
		<input type="submit" value="Novo">
		<input type="submit" name="acao" value="<?php echo $descr_acao; ?>">      
		      
		</form>
	
	

	
	<legend>Cadastrados</legend>
	
	   <?php echo $tabela; ?>
	
	

	<br><br>
	<hr>
	</table>
	
</body>

</html>

<?php
  
  mysqli_close($bd);

?>
