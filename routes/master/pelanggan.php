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
    $list['message'] = '';

    try {
        $db = new db();
        $pdo = $db->connect();
        $result = $pdo->query($sql);
        $users = $result->fetchAll(PDO::FETCH_OBJ);
        $pdo = null;
        
        if(!$result) {
            echo json_encode(['status'=>'error', 'status_code'=>'300', 'message'=>'Gagal mengambil data Pelanggan']);
        }
        else{
            if($result==null) {
                echo json_encode(['status'=>'error', 'status_code'=>'300', 'message'=>'Data Pelanggan kosong']);
            }else{
                echo json_encode(['status'=>'success', 'status_code'=>'200', 'data'=>$users]);
            }
        }
    }
    catch (\PDOException $e) {
        $list['message'] = $e->getMessage();
    }
});

//insert pelanggan
$app->post('/master/pelanggan/insert', function ($request) {
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
        //Insert pelanggan
        $sql = "INSERT INTO pelanggan (id, kode, layanan, nama_pelanggan, tagihan, terbilang, operasional) VALUES (?,?,?,?,?,?,?)";
        $result = $pdo->prepare($sql)->execute(array($id, $kode, $layanan, $nama_pelanggan, $tagihan, $terbilang, $operasional));
        $pdo = null;
        if ($result) {
            $list['error'] = false;
            $list['message'] = 'Penyimpanan Pelanggan berhasil.';
        } else {
            $list['error'] = true;
            $list['message'] = 'Penyimpanan Pelanggan gagal. '.$result['message'];
        }
        echo json_encode(['status'=>$list]);
    }
    catch (\PDOException $e){
        $list['message'] = $e->getMessage();
    }
});

//update pelanggan
$app->post('/master/pelanggan/update', function ($request) {
    $id = $request->getParam('id');
    $kode = $request->getParam('kode');
    $layanan = $request->getParam('layanan');
    $nama_pelanggan = $request->getParam('nama_pelanggan');
    $tagihan = $request->getParam('tagihan');
    $terbilang = $request->getParam('terbilang');
    $operasional = $request->getParam('operasional');

    try {
        //get db object
        $db = new db();
        //connect
        $pdo = $db->connect();
        $sql = "UPDATE pelanggan SET kode=?, layanan=?, nama_pelanggan=?, tagihan=?, terbilang=?, operasional=? WHERE id=?";
        $result = $pdo->prepare($sql)->execute(array($kode, $layanan, $nama_pelanggan, $tagihan, $terbilang, $operasional, $id));
        $pdo = null;
        if ($result) {
            $list['error'] = false;
            $list['message'] = 'Update Pelanggan berhasil.';
        } else {
            $list['error'] = true;
            $list['message'] = 'Update Pelanggan gagal. '.$result['message'];
        }
        echo json_encode(['status'=>$list]);
    }
    catch (\PDOException $e) {
        $this->conn->rollback();
        $list['message'] = $e->getMessage();
    }
});

//delete pelanggan
$app->post('/master/pelanggan/delete', function ($request) {
    $id = $request->getParam('id');

    try {
        //get db object
        $db = new db();
        //connect
        $pdo = $db->connect();
        $sql = "DELETE FROM pelanggan WHERE id=?";
        $result=$pdo->prepare($sql)->execute([$id]);
        $pdo = null;
        if ($result) {
            $list['error'] = false;
            $list['message'] = 'Hapus Pelanggan berhasil.';
        } else {
            $list['error'] = true;
            $list['message'] = 'Hapus Pelanggan gagal. '.$result['message'];
        }
        echo json_encode(['status'=>$list]);
    }
    catch (\PDOException $e) {
        $list['message'] = $e->getMessage();
    }
});

$app->run();
