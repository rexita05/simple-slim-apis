<?php
class PelangganDBO{
    
    function __construct() {
        $pdo = $db->connect();
    }

    function searchPelanggan($p_data){
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
    }
}