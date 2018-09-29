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

  $iddoenca= "";
  $ocorrenciadoenca= "";
  $medicamento="";
  
  
  
  if ( ! isset($_POST["acao"]) )
     $descr_acao = "Incluir"; //
  else {
	 
	 $acao = $_POST["acao"];
	 
	 if (strtoupper($acao) == "INCLUIR" || strtoupper($acao) == "SALVAR" ) {
	    $iddoenca = mysqli_real_escape_string($bd, $_POST["iddoenca"] ) ;
	    $ocorrenciadoenca  = mysqli_real_escape_string($bd, $_POST["ocorrenciadoenca"] );
	    $medicamento  = mysqli_real_escape_string($bd, $_POST["medicamento"] ) ;
	    
	    

     }
     
     if (strtoupper($acao) == "INCLUIR") {
		 
		 $sql = "insert into doenca (iddoenca, ocorrenciadoenca,medicamento)
		                values ('$iddoenca','$ocorrenciadoenca', '$medicamento')";
		                
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
		              medicamento = '$medicamento'
		              
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

     	$sql = "select iddoenca, ocorrenciadoenca,medicamento
     		        from doenca 
     	        where iddoenca = '$iddoenca' ";

     	$resultado = mysqli_query($bd, $sql);

     	if (mysqli_num_rows($resultado) == 1) {

             $dados = mysqli_fetch_assoc($resultado);

             $iddoenca = $dados["iddoenca"];
             $ocorrencia = $dados["ocorrenciadoenca"];
             $medicamento = $dados["medicamento"];
             
     	}

     }

   }

   
	 
   $sql_listar = "select iddoenca, ocorrenciadoenca, medicamento from doenca order by ocorrenciadoenca";
	 
   $lista = mysqli_query($bd, $sql_listar);
	 
   if ( mysqli_num_rows($lista) > 0 ) {
		
		$tabela = "";
		
		$tabela = $tabela."<div class='container-fluid' size='50px'><tr><th><div class='alert alert-success' role='alert'>Numero de registro</div></th>
		<th><div class='alert alert-success' role='alert'>Data da ocorrencia</div></th>
		<th><div class='alert alert-success' role='alert'>Medicamento Usado</div></th>
		<th><div class='alert alert-danger' role='alert'>Alterar</div></th>
		<th><div class='alert alert-danger' role='alert'>Excluir</div></th></tr>";
		 
		while ( $dados = mysqli_fetch_assoc($lista) ) {
		   
		   $viddoenca = $dados["iddoenca"];
		   $vocorenciadoenca  = $dados["ocorrenciadoenca"];
		   $vmedicamento = $dados["medicamento"];
		   
		   
		   $alterar = "<form method='post'>
		                  <input type='hidden' name='iddoenca' value='$viddoenca'>
		                  <input type='hidden' name='acao' value='BUSCAR'>
		                  <input type='image' src='$btnAlterar'> 
		               </form>";
		   
		   $excluir = "<form method='post'>
		                  <input type='hidden' name='iddoenca' value='$viddoenca'>
		                  <input type='hidden' name='acao' value='EXCLUIR'>
		                  <input type='image' src='$btnExcluir'> 
		               </form>";
		   
		   $tabela = $tabela."<tr><td>$viddoenca</td><td>$vocorenciadoenca</td><td>$vmedicamento</td>
		        <td>$alterar</td><td>$excluir</td></tr></div>";
		}
		
		$tabela = $tabela."</table>"; 
   } else 
	    $tabela = "não há dados para listar";
	    

?>

<html>

<head>
	<title>Cadastro das doenças </title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head>

<body>
	<table>
	<th>
		<div class="alert alert-primary" role="alert">Controle de Doenças</div>
	</th>
	
	
	<tr>
		<td>
<div class="container-fluid" height="50px">

		<form action="controle_doenca.php" method="post">
  <div class="form-group">
    <label for="formGroupExampleInput">Código da doença:</label>
    <input type="text" class="form-control" id="formGroupExampleInput" placeholder="" name="iddoenca" value="<?php echo $iddoenca; ?>" size="50%">
  </div>
  <div class="form-group">
    <label for="formGroupExampleInput2">Data da Ocorrencia</label>
    <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="" name="ocorrenciadoenca" value="<?php echo $ocorrenciadoenca; ?>" size="50%">
  </div>
  <div class="form-group">
    <label for="formGroupExampleInput2">Medicamento</label>
    <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="" name="medicamento" value="<?php echo $medicamento; ?>" size="50%">
  </div>
  <div>
  	<button type="submit" class="btn btn-primary" value="Novo">Novo</button>
  	<button type="submit" class="btn btn-primary" name="acao" value="<?php echo $descr_acao; ?>"><?php echo "$descr_acao"; ?></button>
  </div>
</div>
</form>
	
	</td>

</div>

	<td>
	<table>
		<tr>
			<td><div class="alert alert-warning" role="alert">Codido da Doença</div></td>
			<td><div class="alert alert-warning" role="alert">Codigo do Medicamento a ser usado e Descrição</div></td>
		</tr>
		<tr>
			<td><div class="alert alert-warning" role="alert">Código: 1 - Diaréia</div></td>
			<td><div class="alert alert-warning" role="alert"><div>Código: 1 - Cevamutin</div></td>
		</tr>
		<tr>
			<td><div class="alert alert-warning" role="alert">Código:2 - Diaréia de Sangue</div></td>
			<td><div class="alert alert-warning" role="alert"><div>Código:2 - HertaKá</div></td>
		</tr>
		<tr>
			<td><div class="alert alert-warning" role="alert"> Código: 3 - Pneumunia</div></td>
			<td><div class="alert alert-warning" role="alert"><div>Código: 3 - Iflox</div></td>
		</tr>
		<tr>
			<td><div class="alert alert-warning" role="alert"> Código: 4 - Artrite</div></td>
			<td><div class="alert alert-warning" role="alert"><div>Código: 4 - Vetflogin</div></td>
		</tr>
		<tr>
			<td><div class="alert alert-warning" role="alert">Codigo 5: - Úlcera</div></td>
			<td><div class="alert alert-warning" role="alert"><div>Codigo 5: - Maxican 2%</div></td>
		</tr>
	</table>

	</td>
</tr>	
<td>
	<legend>Cadastrados</legend>
	
	   <?php echo $tabela; ?>
	
	<?php echo $mensagem; ?>

	<br><br>
</td>
	</table>

</body>

</html>

<?php
  
  mysqli_close($bd);

?>
