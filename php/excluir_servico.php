<?php

$id = (int) $_GET['id'];
try{
    $con = mysqli_connect('localhost','root','','agenvi');
    
    $sql = "DELETE FROM servicos WHERE idservico = $id;";
    $result = mysqli_query($con, $sql);
    
    if($result){

        include "lista_servicos.php";
      
    }
    else{
        
    }

}catch(Exception $e){
    echo $e->getMessage();
}

?>