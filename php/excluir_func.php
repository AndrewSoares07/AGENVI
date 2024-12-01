<?php

$id = (int) $_GET['id'];
try{
    $con = mysqli_connect('localhost','root','','agenvi');
    
    $sql = "DELETE FROM funcionario WHERE idfuncionario = $id;";
    $result = mysqli_query($con, $sql);
    
    if($result){

        include "lista_func.php";
      
    }
    else{
        
    }

}catch(Exception $e){
    echo $e->getMessage();
}

?>