<?php
    //TOKEN - TOKEN DE SEGURANÇA PARA VALIDAR E SABER QUE ESTAMOS CHMANDO A API
    //ACAO - OQ VAMOS FAZER?
    //ID - ID DO CLIENTE?
    //VALOR - NOME DO CLIENTE, DADOS, CARTAO OU ATUALIZAÇAO DO CLIENTE

    //CONSTANTE
    define('TOKEN','rtyuindufbdufwb23y2v3237732wadsd');

    if(isset($_GET['token'])){
        $token = $_GET['token'];
        if($token == TOKEN){
            //PODEMOS CONTINUAR NA API
        if(isset($_GET['acao'])){
            $pdo = new PDO('mysql:host=localhost;dbname=clientes', 'root', '');
            $acao = $_GET['acao'];

            if($acao == 'novo_contato'){

                $nome = isset($_GET['nome']) ? $_GET['nome'] : '';
                $sql = $pdo->prepare('INSERT INTO `nomes` VALUES (null,?)');

                if($nome === ""){
                    die(json_encode(array('Sucesso' => false, 'Erro'=>'Nenhum nome foi inserido')));
                }else{
                    if($sql->execute(array($nome))){
                        die(json_encode(array('Sucesso' => true, 'Inserido' => $nome)));
                    }else{
                        die(json_encode(array('Sucesso' => false, 'Erro'=>'Não foi possível inserir seu contato')));
                    }
                }
            }elseif($acao == 'deletar_contato'){
                if(!isset($_GET['id']))
                    die(json_encode(array('Erro'=>'Precisamos de um id')));

                $id = (int)$_GET['id'];
                $pdo->exec("DELETE FROM `nomes` WHERE id = $id");
                die(json_encode(array('Sucesso'=>true, 'Deletado'=> $id)));
                
            }elseif($acao == 'atualizar_contato'){
                if(!isset($_GET['id']))
                    die(json_encode(array('Erro'=>'Precisamos de um id')));

                $id = (int)$_GET['id'];

                if(!isset($_GET['val']))
                    die(json_encode(array('Erro'=>'Precisamos do parâmentro valor.')));

                    $val = $_GET['val'];
            
                $sql = $pdo->prepare("UPDATE clientes SET nomes = ? WHERE id = ?");
                if($sql->execute(array($val,$id))){
                    die(json_encode(array('Resposta'=>'O usuário com ID: '.$id.' teve o nome atualizado para: '. $val )));
                }

            }elseif($acao == 'visualizar_contato'){
                if(!isset($_GET['id']))
                    die(json_encode(array('Erro'=>'Precisamos de um id')));

                $id = (int)$_GET['id'];

                $sql = $pdo->prepare("SELECT * FROM `nomes` WHERE id = ?");
                $sql->execute(array($id));

                if($sql->rowCount() >= 1){
                    $dados = $sql->fetch();
                    die(json_encode($dados));
                }else{
                    die('Não encontramos nenhum usuário com este id.');
                }
            }else{
                die("A ação especificada não é valida em nosso sistema de API.");
            }

        }else{
            die('Você não pode conectar na API sem uma ação definida');
        }

        }else{
            die('Não foi possivel conectar na API. Seu token está errado');
        }
    }else{
        die('Você precisa especificar um token de segurança');
    }
?>