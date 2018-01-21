<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

//Usado para permitir multiplas requisições
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            //Permite responder qualquer domínio, pode-se alterar o asterisco pelo domínio do site que o front-end ficará
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});


//Puxar todos os animais
$app->get('/api/animais', function (Request $request, Response $response) {
    //Cria a query que será usada para puxar todos os animais da tabela
    $sql = "SELECT * FROM animais";

    try {
      //Prepara a classe DatabaseCon
      $db = new DatabaseCon();
      //Agora o $db possui a query de conexão ao banco de dados
      $db = $db->connect();
      //Cria um statement para passar a query '$sql'
      $stmt = $db->query($sql);
      //Busca todas as informações na tabela com o fetchAll
      $animais = $stmt->fetchAll(PDO::FETCH_ASSOC);
      //Reseta a variável
      $db = null;
      echo json_encode($animais);


    } catch (PDOException $e) {
      //Como essa é uma API e trabalhará em JSON o erro será emitido do mesmo jeito
      echo '{
              "error":
                {
                  "text" : '.$e->getMessage().'
                }
            }';
    }

});


//Puxar um único animal
$app->get('/api/animal/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    //Cria a query que será usada para puxar apenas um animal da tabela
    $sql = "SELECT * FROM animais WHERE id = $id";

    try {
      //Prepara a classe DatabaseCon
      $db = new DatabaseCon();
      //Agora o $db possui a query de conexão ao banco de dados
      $db = $db->connect();
      //Cria um statement para passar a query '$sql'
      $stmt = $db->query($sql);
      //Busca todas as informações na tabela com o fetchAll
      $animal = $stmt->fetch(PDO::FETCH_ASSOC);
      //Resete a variável
      $db = null;
      echo json_encode($animal);


    } catch (PDOException $e) {
      echo '{
              "error":
                {
                  "text" : '.$e->getMessage().'
                }
            }';
    }

});


//Adicionar um animal
$app->post('/api/animal/add', function (Request $request, Response $response) {
    //Pega os parametros http que serão adicionados
    $nome = $request->getParam('Nome');
    $raca = $request->getParam('Raca');
    $peso = $request->getParam('Peso');

    //Cria a query que será usada para adicionar animais a tabela
    $sql = "INSERT INTO animais (nome , raca , peso) VALUES(:nome , :raca , :peso)";

    try {
      //Prepara a classe DatabaseCon
      $db = new DatabaseCon();
      //Agora o $db possui a query de conexão ao banco de dados
      $db = $db->connect();
      $stmt = $db->prepare($sql);

      $stmt->bindParam(':nome', $nome);
      $stmt->bindParam(':raca', $raca);
      $stmt->bindParam(':peso', $peso);

      $stmt->execute();

      echo '{
              "notice":
                {
                  "text": "Animal adicionado"
                }
            }';


    } catch (PDOException $e) {
      //Como essa é uma API e trabalhará em JSON o erro será emitido do mesmo jeito
      echo '{
              "error":
                {
                  "text" : '.$e->getMessage().'
                }
            }';
    }

});


//Modificar um animal
$app->put('/api/animal/atualizar/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $nome = $request->getParam('Nome');
    $raca = $request->getParam('Raca');
    $peso = $request->getParam('Peso');

    $sql = "UPDATE animais SET nome = :nome, raca = :raca, peso = :peso WHERE id = $id";

    try {
      //Prepara a classe DatabaseCon
      $db = new DatabaseCon();
      //Agora o $db possui a query de conexão ao banco de dados
      $db = $db->connect();
      $stmt = $db->prepare($sql);

      $stmt->bindParam(':nome', $nome);
      $stmt->bindParam(':raca', $raca);
      $stmt->bindParam(':peso', $peso);

      $stmt->execute();

      echo '{
              "notice":
                {
                  "text": "Atributo modificado"
                }
            }';


    } catch (PDOException $e) {
      echo '{
              "error":
                {
                  "text" : '.$e->getMessage().'
                }
            }';
    }

});


//Deletar um animal
$app->delete('/api/animal/deletar/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM animais WHERE id = $id";

    try {
      //Prepara a classe DatabaseCon
      $db = new DatabaseCon();
      //Agora o $db possui a query de conexão ao banco de dados
      $db = $db->connect();
      $stmt = $db->prepare($sql);
      $stmt->execute();

      echo '{
              "notice":
                {
                  "text" : "Animal deletado"
                }
            }';

    } catch (PDOException $e) {
      echo '{
              "error":
                {
                  "text" : '.$e->getMessage().'
                }
            }';
    }

});
