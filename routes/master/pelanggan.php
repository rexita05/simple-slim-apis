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

//get list all pelanggan (Belum dipakai)
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

//insert pelanggan
$app->post('/master/pelanggan/insert', function (Request $request, Response $reponse, array $args) {
    $id = $request->getParam('id');
    $kode = $request->getParam('kode');
    $layanan = $request->getParam('layanan');
    $nama_pelanggan = $request->getParam('nama_pelanggan');
    $tagihan = $request->getParam('tagihan');
    $terbilang = $request->getParam('terbilang');
    $operasional = $request->getParam('operasional');

    try {
        $db = new db();
        //connect
        $pdo = $db->connect();
        // Cek Data digunakan apa belum
        // $sql = "SELECT kode FROM pelanggan WHERE kode=? ";
        // $st = $pdo->prepare($sql);
        // $result = $st->execute(array($kode));
        // $rows = $st->fetchAll(PDO::FETCH_ASSOC); 
        // if (count($rows) > 0) {
        //     throw new Exception('Error kode pelanggan sudah digunakan.');
        // }

        //Insert pelanggan
        $sql = "INSERT INTO pelanggan (id, kode, layanan, nama_pelanggan, tagihan, terbilang, operasional) VALUES (?,?,?,?,?,?,?)";

        $result = $pdo->prepare($sql)->execute([$id, $kode, $layanan, $nama_pelanggan, $tagihan, $terbilang, $operasional]);

        $pdo = null;
        if ($result['message'] == '') {
            $list['error'] = false;
            $list['message'] = 'Penyimpanan Pelanggan berhasil.';
        } else {
            $list['error'] = true;
            $list['message'] = 'Penyimpanan Pelanggan gagal. '.$result['message'];
        }

        echo json_encode(array('status'=>$list));
    }
    catch (\PDOException $e){
        echo '{"error": {"message": ' . $e->getMessage() . '}}';
    }
});

//make a post request
$app->put('/master/pelanggan/update/{id}', function (Request $request, Response $reponse, array $args) {
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
