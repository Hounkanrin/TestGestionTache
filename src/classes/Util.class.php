<?php

class ApiUtils
{
    private $datafile = './resources/data.json';
    
    const ID = "id";
    const LIBELLE = "libelle";
    const DESC = "desc";
    const DUREE = "duree";
    const TRAITANT = "traitant";
    const ETAT = "etat";
    
    private $data = array();
    private $listeData = array();
    
    public function getData($id = null,$type = null,$delete = false){
        $this->getAllData();
        $temp = array();
        if(!isset($id) && !isset($type)){
            $temp  = $this->listeData;
        }else{
            foreach ($this->listeData as $key => $data) {
                if(isset($id) && strpos($data, '"'.self::ID.'":"'.$id.'"') !== false){
                    if($delete){
                        unset($this->listeData[$key]);
                    }
                    return $data;
                }else if(isset($type) && strpos($data, '"'.self::ETAT.'":"'.$type.'"') !== false){
                    $temp[$key] = $data;
                }
            }
        }
        return '['.implode(',', $temp).']';
    }
    
    private function getAllData(){
        $this->listeData = $this->openDataFile() ? file($this->datafile) : array();
    }
    
    public function generateID(){
        return date('YmdHis');
    }
    
    public function setData($param){
        $this->data[self::ID] = !isset($param[self::ID]) ? $this->generateID() :  $param[self::ID];
        if(isset($param[self::LIBELLE])){
            $this->data[self::LIBELLE] = $param[self::LIBELLE];
            $this->data[self::DESC] = $param[self::DESC];
            $this->data[self::TRAITANT] = $param[self::TRAITANT];
            $this->data[self::DUREE] = $param[self::DUREE];
            $this->data[self::ETAT] = $param[self::ETAT];
        }
    }
    
    public function saveData($new = true,$delete = false){
        $return = json_encode($this->data);
        $dataTosave = array($return);
        if(!$new){
            $this->getData($this->data[self::ID],null,true);
            if(!$delete){
                $this->listeData[] = $return;
            }
            $dataTosave = $this->listeData;
        }
        $handle = $new ? $this->openDataFile() : $this->openDataFile('w');
        if($handle){
            foreach ($dataTosave as $save) {
                $save = str_replace("\n", "", $save);
                fwrite($handle, $save."\n");
            }
            return $return;
        }
        return null;
    }
    
    private function openDataFile($mode = 'a+'){
        // ouvre le fichier en ecriture et en lecture
        return fopen($this->datafile, $mode);
    }
}