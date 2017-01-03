<?php

$prevArray = '[{"_id":1,"someKey":"RINGING","meta":{"subKey1":1234,"subKey2":52}}]';
$currArray = '[{"_id":1,"someKey":"HANGUP","meta":{"subKey1":1234}},{"_id":2,"someKey":"RINGING","meta":{"subKey1":5678,"subKey2":207,"subKey3":52}}]';
 

function arrayDiffToHtmlTable( $prevArray, $currArray) {
    
    $currArray_decode= json_decode($currArray,true);
    $prevArray_decode = json_decode($prevArray,true);
    
    $htmlTableString="<table border='1' ><tbody>";
    $htmlTableStringBody='';
    $array_key_heading=array();

    foreach($currArray_decode as $key => $currArray_decode_obj){

        $htmlTableStringBody.="<tr>";   


        $array_to_compare=array();
        // Fetch arrary to compare
        foreach($prevArray_decode as $prevArray_decode_key=>$prevArray_decode_val){ 
            foreach($prevArray_decode_val as $prevArray_decode_val_key=>$prevArray_decode_valmain){
               if($prevArray_decode_val['_id']==$currArray_decode_obj['_id']){
                $array_to_compare=$prevArray_decode_val;
               }
            }   
        }


    
    foreach($currArray_decode_obj as $currArray_decode_obj_key => $currArray_decode_obj_val){
         
         if (is_array ($currArray_decode_obj_val)) {
             foreach($currArray_decode_obj_val as  $currArray_decode_obj_val_key => $currArray_decode_obj_val_subcval){
                     $array_key_heading[$currArray_decode_obj_key.'-'.$currArray_decode_obj_val_key]=$currArray_decode_obj_key.'_'.$currArray_decode_obj_val_key;             
                      $record_modified='';
                     if(@$array_to_compare[$currArray_decode_obj_key][$currArray_decode_obj_val_key]!=$currArray_decode_obj[$currArray_decode_obj_key][$currArray_decode_obj_val_key]){
                          $record_modified="style='font-weight:bold'";
                     }
                    
                     if(isset($array_to_compare[$currArray_decode_obj_key][$currArray_decode_obj_val_key]) and !isset($currArray_decode_obj[$currArray_decode_obj_key][$currArray_decode_obj_val_key])){
                          $htmlTableStringBody .= "<td style='font-weight:bold' >DELETED</td>";
                     }else{                     
                        $htmlTableStringBody .= "<td {$record_modified} >".$currArray_decode_obj_val_subcval."</td>";
                     }
             }
      
             $arrary_diff= @array_diff($array_to_compare[$currArray_decode_obj_key], $currArray_decode_obj[$currArray_decode_obj_key]);                                 $array_deleted_key=  @array_keys($arrary_diff);

             if(!empty($array_deleted_key)){
                foreach($array_deleted_key as $array_deleted_key_val){
                     $htmlTableStringBody .= "<td style='font-weight:bold' >DELETED</td>";
                }
             }
         }else{
             $record_modified='';
             $array_key_heading[$currArray_decode_obj_key]=$currArray_decode_obj_key;
             
             if(@$array_to_compare[$currArray_decode_obj_key]!=$currArray_decode_obj[$currArray_decode_obj_key]){
                 $record_modified="style='font-weight:bold'";
             }
             
             $htmlTableStringBody.="<td {$record_modified} >".$currArray_decode_obj_val."</td>"; 
            }
       }

       $htmlTableStringBody.="</tr>";
   }

   $htmlTableStringheader="<tr><td>".implode('</td><td>',$array_key_heading)."</td></tr>";

   $htmlTableString.=$htmlTableStringheader.$htmlTableStringBody."</tbody></table>";


   return $htmlTableString;
}

echo arrayDiffToHtmlTable($prevArray,$currArray);
