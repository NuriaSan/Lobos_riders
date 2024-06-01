<?php 
    function parametro_plantilla ($variable){
      if (isset ($GLOBALS [$variable])){
        echo $GLOBALS [$variable];
      }else{
        echo "Sin dato cargado: ". $variable;
      }
    }
?>