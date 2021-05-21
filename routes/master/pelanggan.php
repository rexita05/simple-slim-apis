<?php

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

$app = new \Slim\App;

// $config = ['settings' => ['displayErrorDetails' => true]]; 
// $app = new Slim\App($config);

$app->get('/', function (Request $request, Response $reponse) {
    echo 'Welcome My Page!!!';
});

//search pelanggan
$app->post('/master/pelanggan/search[/{criteria}]', function (Request $request, Response $reponse, array $args) {

    $criteria = $request->getAttribute('criteria');
    if($criteria!=null || $criteria!=''){
        $query = "SELECT * FROM pelanggan where kode LIKE '%$criteria%' OR nama_pelanggan LIKE '%$criteria%'";
    }
    else{
        $query = "SELECT * FROM pelanggan";
    }
    $sql = $query;

    try {
        $db = new db();
        $pdo = $db->connect();

        $result = $pdo->query($sql);
        $users = $result->fetchAll(PDO::FETCH_ASSOC);
        $pdo = null;
        
        if(!$result) {
            echo json_encode(array('status'=>'error', 'status_code'=>'300', 'message'=>'Gagal mengambil data Pelanggan'));
        }
        else{
            if($result==null) {
                echo json_encode(array('status'=>'error', 'status_code'=>'300', 'message'=>'Data Pelanggan kosong'));
            }else{
                echo json_encode(array('status'=>'success', 'status_code'=>'200', 'data'=>$users));
            }
        }
    }
    catch (\PDOException $e) {
        echo '{"message": {"response": ' . $e->getMessage() . '}}';
    }
});

//get list all pelanggan
$app->post('/master/pelanggan/list_all', function (Request $request, Response $reponse) {
    $sql = "SELECT * FROM pelanggan";

    try {
        $db = new db();
        $pdo = $db->connect();
        $result = $pdo->query($sql);
        $users = $result->fetchAll(PDO::FETCH_OBJ);
        $pdo = null;
        
        if(!$result) {
            echo json_encode(array('status'=>'error', 'status_code'=>'300', 'message'=>'Gagal mengambil data Pelanggan'));
        }
        else{
            if($result==null) {
                echo json_encode(array('status'=>'error', 'status_code'=>'300', 'message'=>'Data Pelanggan kosong'));
            }else{
                echo json_encode(array('status'=>'success', 'status_code'=>'200', 'data'=>$users));
            }
        }
    }
    catch (\PDOException $e) {
        echo '{"message":{"response":'.$e->getMessage().'}}';
    }
});

//get perkode
$app->get('/master/pelanggan/getPerkode/{id}', function (Request $request, Response $reponse) {
    $id = $request->getAttribute('id');
    $sql = "SELECT * FROM pelanggan where id = '$id'";

    try {
        $db = new db();
        $pdo = $db->connect();
        $result = $pdo->query($sql);
        $users = $result->fetchAll(PDO::FETCH_OBJ);
        $pdo = null;
        
        if(!$result) {
            echo json_encode(array('status'=>'error', 'status_code'=>'300', 'message'=>'Gagal mengambil data Pelanggan'));
        }
        else{
            if($result==null) {
                echo json_encode(array('status'=>'error', 'status_code'=>'300', 'message'=>'Data Pelanggan kosong'));
            }else{
                echo json_encode(array('status'=>'success', 'status_code'=>'200', 'data'=>$users));
            }
        }
    }
    catch (\PDOException $e) {
        echo '{"message":{"response":'.$e->getMessage().'}}';
    }
});

//make a post request
$app->post('/api/users/add', function (Request $request, Response $reponse, array $args) {
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $phone = $request->getParam('phone');
    $email = $request->getParam('email');
    $address = $request->getParam('address');
    $city = $request->getParam('city');
    $state = $request->getParam('state');

    try {
        //get db object
        $db = new db();
        //conncect
        $pdo = $db->connect();


        $sql = "INSERT INTO users (first_name, last_name, phone,email,address,city,state) VALUES (?,?,?,?,?,?,?)";


        $pdo->prepare($sql)->execute([$first_name, $last_name, $phone, $email, $address, $city, $state]);

        echo '{"notice": {"text": "User '. $first_name .' has been just added now"}}';
        $pdo = null;
    } catch (\PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}}';
    }
});

//make a post request
$app->put('/api/users/update/{id}', function (Request $request, Response $reponse, array $args) {
    $id = $request->getAttribute('id');

    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $phone = $request->getParam('phone');

    try {
        //get db object
        $db = new db();
        //conncect
        $pdo = $db->connect();


        $sql = "UPDATE  users SET first_name =?, last_name=?, phone=? WHERE id=?";


        $pdo->prepare($sql)->execute([$first_name, $last_name, $phone, $id]);

        echo '{"notice": {"text": "User '. $first_name .' has been just updated now"}}';
        $pdo = null;
    } catch (\PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}}';
    }
});


//make a post request
$app->delete('/api/users/delete/{id}', function (Request $request, Response $reponse, array $args) {
    $id = $request->getAttribute('id');

    try {
        //get db object
        $db = new db();
        //conncect
        $pdo = $db->connect();

        $sql = "DELETE FROM users WHERE id=?";

        $pdo->prepare($sql)->execute([$id]);
        $pdo = null;

        echo '{"notice": {"text": "User with '. $id .' has been just deleted now"}}';

    } catch (\PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}}';
    }
});

$app->run();
