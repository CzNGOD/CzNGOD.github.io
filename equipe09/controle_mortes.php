<?php
  
  $bd = mysqli_connect("localhost","root","","hfw"); 

  if ($bd) {
  	mysqli_set_charset($bd, "utf8");
  } else {
	 echo "Não foi possível conectar o BD <br>";
	 echo "Mensagem de erro: ".mysqli_connect_error() ;
	 exit();
  }
  
  $btnExcluir = "fotos/excluir.png";
  $btnAlterar = "fotos/editar.png";


  $mensagem = "";

  $idmorte= "";
  $pesoanimal= "";
  $datamorte="";
 
  
  
  
  if ( ! isset($_POST["acao"]) )
     $descr_acao = "Incluir"; 
  else {
	 
	 $acao = $_POST["acao"];
	 
	 if (strtoupper($acao) == "INCLUIR" || strtoupper($acao) == "SALVAR" ) {
	    $idmorte = mysqli_real_escape_string($bd, $_POST["idmorte"] ) ;
	    $pesoanimal  = mysqli_real_escape_string($bd, $_POST["pesoanimal"] );
	    $datamorte  = mysqli_real_escape_string($bd, $_POST["datamorte"] ) ;
	   
     }
     
     if (strtoupper($acao) == "INCLUIR") {
		 
		 $sql = "insert into morte (idmorte, pesoanimal,datamorte)
		                values ('$idmorte','$pesoanimal', '$datamorte')";
		                
		 if ( ! mysqli_query($bd, $sql) ) {
			
			 if ( mysqli_errno($bd) == 1062 )
			    $mensagem = "O registro informado '$idmorte' já existe, tente outro!";
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

		 $idmorte = " ";
     }

     if (strtoupper($acao) == "BUSCAR") {

        $idmorte = $_POST["idmorte"];
     	
     	$descr_acao = "Salvar";

     	$sql = "select idmorte, pesoanimal, datamorte
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
		
		$tabela = "";
		
		$tabela = $tabela."<div class='container-fluid' size='50px'><tr>
		<th><div class='alert alert-success' role='alert'>Numero de registro</div></th>
		<th><div class='alert alert-success' role='alert'>Peso do Animal</div></th>
		<th><div class='alert alert-success' role='alert'>Data do ocorrido</div></th>
		<th><div class='alert alert-danger' role='alert'>Alterar</div></th>
		<th><div class='alert alert-danger' role='alert'>Excluir</div></th></tr>";
		 
		while ( $dados = mysqli_fetch_assoc($lista) ) {
		   
		   $vidmorte = $dados["idmorte"];
		   $vpesoanimal  = $dados["pesoanimal"];
		   $vdatamorte = $dados["datamorte"];		   
		   
		   $alterar = "<form method='post'>
		                  <input type='hidden' name='idmorte' value='$vidmorte'>
		                  <input type='hidden' name='acao' value='BUSCAR'>
		                  <input type='image' src='$btnAlterar'> 
		               </form>";
		   
		   $excluir = "<form method='post'>
		                  <input type='hidden' name='idmorte' value='$vidmorte'>
		                  <input type='hidden' name='acao' value='EXCLUIR'>
		                  <input type='image' src='$btnExcluir'> 
		               </form>";
		   
		   $tabela = $tabela."<tr><td>$vidmorte</td><td>$vpesoanimal</td><td>$vdatamorte</td>
		        <td>$alterar</td><td>$excluir</td></tr></div>";
		}
		
		$tabela = $tabela."</table>"; 
   } else 
	    $tabela = "não há dados para listar";
	    

?>

<html>

<head>
	<title>Contreole de Baixas dos Animais</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head>

<body >
		<table align="center">

			<td>
	
		<div class="alert alert-primary" role="alert">Controle de Baixa dos Animais </div>
		
<div class="container-fluid" height="50px">

		<form action="controle_mortes.php" method="post">
  <div class="form-group">
    <label for="formGroupExampleInput">Numero de registro:</label>
    <input type="text" class="form-control" id="formGroupExampleInput" placeholder="" name="idmorte" value="<?php echo $idmorte; ?>" size="50%">
  </div>
  <div class="form-group">
    <label for="formGroupExampleInput2">Peso do Animal</label>
    <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="" name="pesoanimal" value="<?php echo $pesoanimal; ?>" size="50%">
  </div>
  <div class="form-group">
    <label for="formGroupExampleInput2">Data do Ocorrido</label>
    <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="" name="datamorte" value="<?php echo $datamorte; ?>" size="50%">
  </div>
  <div>
  	<button type="submit" class="btn btn-primary" value="Novo">Novo</button>
  	<button type="submit" class="btn btn-primary" name="acao" value="<?php echo $descr_acao; ?>"><?php echo "$descr_acao"; ?></button>
  	<br>
</div>
</form>
	
	

</div>

	<legend>Cadastrados</legend>
	
	   <?php echo $tabela; ?>
	
	<?php echo $mensagem; ?>

	<br><br>

	<center>
<a href="index.html" class="badge badge-primary">Voltar a página inicial</a>
	</center>
	</td>
</table>
</body>

</html>

<?php
  
  mysqli_close($bd);

?>
