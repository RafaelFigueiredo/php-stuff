<?php

//--------------------------- VARIÁVEIS ----------------------------
//variáveis do banco de $dadosDaOs
define("servername", "localhost");
define("dbname", "cariocae_os");
define("username", "cariocae_client");
define("password", "senhaaqui");


//Nossa variável vazia para receber os dados
$dadosDaOs  = array();




//--------------------------- FUNÇÕES ----------------------------
//Função que verifica se há caracteres invalidos, util para envitar ataques como PHP Injection ou SQL Injection
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  //$data = htmlspecialchars($data);
  return $data;
}


function isValidJson($strJson) {
    json_decode($strJson);
    return (json_last_error() === JSON_ERROR_NONE);
}

function gravarNoBancoDeDados($data){
  // Create connection
  $conn = new mysqli(servername, username, password, dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $sql = "INSERT INTO requisicoes (message) VALUES ('".$data."')";

  if ($conn->query($sql) === TRUE) {
      echo "New record created successfully";
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
      throw new Exception("DB error: ".$conn->error);
  }

  mysqli_close($conn);
}


function setup(){
  // Create connection
  $conn= mysqli_connect(servername, username, password, dbname);
  // Check connection
  if (!$conn) {

      die("Connection failed: " . mysqli_connect_error());

  }
  echo "Connected successfully";
  mysqli_close($conn);
}



//--------------------------- MÉTODO PRINCIPAL ----------------------------

function Run() {
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // recebe os dados
    $dadosDaOs = test_input(file_get_contents('php://input'));
    // verifica se o JSON é invalido
    if(isValidJson($dadosDaOs)){
      // grava no banco de dados
      gravarNoBancoDeDados($dadosDaOs);

    }else{
      // retorna erro se o json for inválido
      http_response_code(400);//Bad request
      return;
    }

  }else{
    echo "Estatus do serviço? ";
    setup();
    //A outra opção é exibir a página do manual
    //http_response_code(405);//Method Not Allowed
    return;
  }
}



//--------------------------- TRATAMENTO DE ERROS --------------------------
try {
  Run();
}

//catch exception
catch(Exception $e) {
  // Grava erro no banco de dados? (PROPOSTA)


  // ERRO 500 - PROBLEMA NO SERVIDOR.
  // Pode ser um problema ao gravar o registro no banco de dados, ou algum outro erro interno
  http_response_code(500);
  echo json_encode( array("status"=>"error","Internal Server Error","message"=>$e.getMessage() ) );
}




?>
