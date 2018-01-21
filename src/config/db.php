<?php
/**
 * É a classe que permite a conexão com o banco de dados
 */
class DatabaseCon
{
  //Preparando as propriedades de acesso ao DB
  private $dbhost = 'localhost';
  private $dbuser = 'root';
  private $dbpass = '';
  private $dbname = 'cadastro';

  public function connect(){
    $db_string = "mysql:host=$this->dbhost;dbname=$this->dbname";

    //Prepara em PDO a query que será lançada ao banco
    $dbh = new PDO($db_string , $this->dbuser, $this->dbpass);

    //Configura um atributo para que o banco de dados mostre excessões
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Retorna a query para que possa ser executada
    return $dbh;
  }
}
?>
