<form method="POST" enctype="multipart/form-data">
	
	<input type="file" name="fileUpload">

	<button type="submit">Send</button>

</form>

<?php 

require_once ("config.php");

if ($_SERVER["REQUEST_METHOD"] === "POST"){ // Valida se é um metodo post

	$file = $_FILES["fileUpload"]; // atribui a variável $file o arquivo setado no input

	if ($file["error"]){ // chega se contem erro no upload

		throw new Exception("Error" . $file["error"]);
		
	}

	$dirUploads = "uploads"; 

	if (!is_dir($dirUploads)){ // verfica se existe o diretorio, se não ele cria

		mkdir($dirUploads);
	}

	if (move_uploaded_file($file["tmp_name"], $dirUploads . DIRECTORY_SEPARATOR . $file["name"])) { // função para adicionar ao diretorio criado o upload realizado

		 $filename = $dirUploads . DIRECTORY_SEPARATOR . $file["name"];

		if (file_exists($filename)){ //verifica se o arquivo existe

	$file = fopen($filename, "r"); //abre o arquivo e inicia a leitura

	$headers = explode(";", fgets($file)); //retira o separador do arquivo csv e salva os dados da linha

	$data = array();

	while ($row = fgets($file)){ // enquanto tiver linha no arquivo ele adiciona o valor a variavel row

		$rowData = explode(';', $row); //retira separador da variavel
		$linha = array(); 
		
		for ($i=0; $i < count($headers) ; $i++) { 
	 		
	 		$linha[$headers[$i]] = $rowData[$i]; // pega a posição do $headers e define como chave, atribuindo a mesma posição da linha(rowdata) no array.

		}
		
		$inserir = new Usuario($linha[$headers[0]],$linha[$headers[1]]);
		$inserir->insert();

	}

	fclose($file);

		echo "inserido com sucesso";
}

	} else {

		throw new Exception("Não foi possível realizar o upload");
		

	}



}

 ?>