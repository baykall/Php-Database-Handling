<?php
class DataBase{
    var $con;
    public function connect(){
        global $dHost,$dUser,$dPassword,$dBase;
        $this->con = mysqli_connect($dHost, $dUser, $dPassword, $dBase);
    }
    
    public function disconnect(){
        mysqli_close($this->con);
    }
    
    public function InsertInto($table,$columns,$values){
        $query_str = "INSERT INTO ".$table." (";
        for($i=0;$i<count($columns);$i++){
            $query_str=$query_str.$columns[$i];
            if($i<count($columns)-1)
                $query_str=$query_str.", ";
            else
                $query_str=$query_str.") ";
        }
        $query_str=$query_str." VALUES(";
        for($i=0;$i<count($values);$i++){
            $query_str=$query_str."'".$values[$i]."'";
            if($i<count($values)-1)
                $query_str=$query_str.", ";
            else
                $query_str=$query_str.") ";
        }
        mysqli_query($this->con,$query_str);
    }
    
    public function InsertIntoSmart($table,$init_col,$val){
        $values = get_array($val);
        $allcolumns = $this->GetColumnNames($table);
        
        $columns = array();
        for($i=0;$i<count($values);$i++)
            array_push($columns,$allcolumns[$init_col+$i]);
        
        return $this->InsertInto($table,$columns,$values);
    }
    public function Update($table,$col,$val,$UpdateColumn,$UpdateValue){
        $columns = get_array($col);
        $values = get_array($val);
        
        $query_str = "UPDATE ".$table." SET ".$UpdateColumn."='".$UpdateValue."' WHERE ";
        for($i=0;$i<count($columns);$i++){
            if($i==0)
                $query_str = $query_str.$columns[$i]."='".$values[$i]."' ";
            else
                $query_str = $query_str."AND ".$columns[$i]."='".$values[$i]."'";
        }
        mysqli_query($this->con,$query_str);
    }
    
    public function UpdateSmart($table,$init_col,$val,$UpdateColumn,$UpdateValue){
        $values = get_array($val);
        $allcolumns = $this->GetColumnNames($table);
        
        $columns = array();
        for($i=0;$i<count($values);$i++)
            array_push($columns,$allcolumns[$init_col+$i]);
        
        return $this->Update($table,$columns,$values,$UpdateColumn,$UpdateValue);
    }
    
    public function Delete($table,$col,$val){
        $values = get_array($val);
        $columns = get_array($col);
        $query_str = "DELETE FROM ".$table." WHERE ";
        for($i=0;$i<count($columns);$i++){
            if($i==0)
                $query_str = $query_str.$columns[$i]."='".$values[$i]."' ";
            else
                $query_str = $query_str."AND ".$columns[$i]."='".$values[$i]."'";
        }
        mysqli_query($this->con,$query_str);
    }
    public function DeleteSmart($table,$init_col,$val){
        $values = get_array($val);
        $allcolumns = $this->GetColumnNames($table);
        
        $columns = array();
        for($i=0;$i<count($values);$i++)
            array_push($columns,$allcolumns[$init_col+$i]);
        
        return $this->Delete($table,$columns,$values);
    }
    public function GetNofRows($table){
        $id = array();
        $query_str = "SELECT * FROM ".$table;
        $result = mysqli_query($this->con,$query_str);
        while($row = mysqli_fetch_array($result))
            array_push($id,$row[$retColumn]);
        return count($id);
    }
    
    public function GetSingle($table,$columns,$values,$retColumn){
        $id = '';
        $query_str = "SELECT * FROM ".$table;
        $result = mysqli_query($this->con,$query_str);
        while($row = mysqli_fetch_array($result)){
            $found = true;
            for($i=0;$i<count($columns);$i++){
                if($row[$columns[$i]]!=$values[$i]){
                    $found = false;
                    break;
                }
            }
            if($found==true){
                $id = $row[$retColumn];
                break;
            }
        }
        return $id;
    }
    
    public function GetAll($table,$columns,$values,$retColumn){
        $id = array();
        $query_str = "SELECT * FROM ".$table;
        $result = mysqli_query($this->con,$query_str);
        while($row = mysqli_fetch_array($result)){
            $found = true;
            for($i=0;$i<count($columns);$i++){
                if($row[$columns[$i]]!=$values[$i]){
                    $found = false;
                    break;
                }
            }
            if($found==true)
                array_push($id,$row[$retColumn]);
        }
        return $id;
    }
    
    public function GetAllAll($table,$retColumn){
        $id = array();
        $query_str = "SELECT * FROM ".$table;
        $result = mysqli_query($this->con,$query_str);
        while($row = mysqli_fetch_array($result))
            array_push($id,$row[$retColumn]);
        return $id;
    }
    
    public function GetFirstAvailable($table,$columns,$values,$retColumns){
        $id = '';
        $query_str = "SELECT * FROM ".$table;
        $result = mysqli_query($this->con,$query_str);
        while($row = mysqli_fetch_array($result)){
            $found = true;
            for($i=0;$i<count($columns);$i++){
                if($row[$columns[$i]]!=$values[$i]){
                    $found = false;
                    break;
                }
            }
            if($found==true){
                for($i=0;$i<count($retColumns);$i++){
                    $id = $row[$retColumns[$i]];
                    if($id!='')
                        break;
                }
            }
        }
        return $id;
    }
    
    public function GetFirstAvailableAll($table,$columns,$values,$retColumns){
        $id = array();
        $query_str = "SELECT * FROM ".$table;
        $result = mysqli_query($this->con,$query_str);
        while($row = mysqli_fetch_array($result)){
            $found = true;
            for($i=0;$i<count($columns);$i++){
                if($row[$columns[$i]]!=$values[$i]){
                    $found = false;
                    break;
                }
            }
            if($found==true){
                for($i=0;$i<count($retColumns);$i++){
                    $val = $row[$retColumns[$i]];
                    if($val!=''){
                        array_push($id,$row[$retColumns[$i]]);
                        break;
                    }
                }
            }
        }
        return $id;
    }
    
    public function Get($table,$col,$val,$retColumn,$type){
        $columns = get_array($col);
        $values = get_array($val);
        if(is_array($retColumn)==true)
            $fa = true;
        else
            $fa = false;
        
        if($type=='Single'&&$fa==false)
            return $this->GetSingle($table,$columns,$values,$retColumn);
        else if($type=='All'&&$fa==false)
            return $this->GetAll($table,$columns,$values,$retColumn);
        else if($type=='AllAll')
            return $this->GetAllAll($table,$retColumn);
        else if($type=='Single'&&$fa==true)
            return $this->GetFirstAvailable($table,$columns,$values,$retColumn);
        else if($type=='All'&&$fa==true)
            return $this->GetFirstAvailableAll($table,$columns,$values,$retColumn);
    }
    
    public function GetSmart($table,$init_col,$val,$retColumn,$type){
        $values = get_array($val);
        $allcolumns = $this->GetColumnNames($table);
        $columns = array();
        for($i=0;$i<count($values);$i++)
            array_push($columns,$allcolumns[$init_col+$i]);
        
        return $this->Get($table,$columns,$values,$retColumn,$type);
    }
    
    public function GetColumnNames($table){
        $query_str = "SELECT * FROM ".$table;
        $result = mysqli_query($this->con,$query_str);
        $i = 0;
        $cols = array();
        
        while ($finfo = mysqli_fetch_field($result))
            array_push($cols,$finfo->name);
        mysqli_free_result($result);
        return $cols;
    }
    
    public function Search($table,$column,$value,$retColumn,$type='LIKE'){
        $id = array();
        $query_str = "SELECT * FROM ".$table." WHERE ".$column." ".$type." '%".$value."%'";
        $result = mysqli_query($this->con,$query_str);
        while($row = mysqli_fetch_array($result))
            array_push($id,$row[$retColumn]);
        return $id;
    }
    
    public function GetAllTables(){
        global $dBase;
        $id = array();
        $query_str = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='".$dBase."'";
        $result = mysqli_query($this->con,$query_str);
        while($row = mysqli_fetch_array($result))
            array_push($id,$row['TABLE_NAME']);
        return $id;
    }   
}
?>


