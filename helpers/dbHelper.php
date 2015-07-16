<?php


    function getRowCount($db, $tableName) {
        $query = "SELECT count(*) as 'count' FROM cfpb.".$tableName.";";
        if ($result = $db->query($query)) {
            $rows = mysqli_fetch_row($result);
            $result->close();
            return $rows[0];
        }else{
            return FALSE;         
        }  
    }


    function validateIfTableExists($db, $tableName) {
        $query = "show tables like '".$tableName."';";
        if ($result = $db->query($query)) {
            $rows = mysqli_num_rows($result);
            $result->close();
            if($rows > 0)
                return TRUE;
        } else{
            return FALSE;
        }

        return FALSE;
    }




    function validateIfTableIndexExists($db, $tableName, $indexName) {
        $query = "SHOW INDEXES from cfpb.".$tableName." WHERE Key_name LIKE '".$indexName."';";
        if ($result = $db->query($query)) {
            $rows = mysqli_num_rows($result);
            $result->close();
            if($rows > 0)
                return TRUE;
        } else{
            return FALSE;
        }

        return FALSE;
    }



    function printQueryError($db, $query){
        if (!mysqli_query($db,$query)){
          echo("Query Error: " . mysqli_error($db));
        }
    }

?>