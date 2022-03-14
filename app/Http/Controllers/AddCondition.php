<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

class AddCondition extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()//Request $request)
    {
        // $path = $request->address;
        $path = "/home/hossein/Desktop/flarumExcel/resources/binicon.csv";
        // $path = "/home/rahim/Insert_Excel_To_Flarum_Form/resources/fatigue.csv";

        $file = fopen($path, 'r');

        if ($file) {


            $importData_arr = array(); // Read through the file and store the contents as an array
            $i = 0;
            //Read the contents of the uploaded file 
            while (($filedata = fgetcsv($file, 10000, ",")) !== FALSE) {
                $num = count($filedata);

                // Skip first row (Remove below comment if you want to skip the first row)
                if ($i == 0) {
                    $i++;
                    continue;
                }
                for ($c = 0; $c < $num; $c++) {
                    $importData_arr[$i][] = $filedata[$c];
                }
                $i++;
            }
            fclose($file); //Close after reading
            
            
            
            $j = 0;
          
            
            foreach ($importData_arr as $importData) {

                // dd($importData[0]);
                
                
                $condition = $importData[0]; 
                // $state = $importData[1]; 
                $number = $importData[2]; 
                
                // $description = $importData[13];
                
                // dd($condition,$number);

                $this->conditionMaker($condition,$number);
            
     

                $j++;
            }
            return response()->json([
                'message' => "$j records successfully uploaded"
            ]);
        } else {
            //no file was uploaded
            throw new \Exception('No file was uploaded', Response::HTTP_BAD_REQUEST);
        }
    }




    public function conditionMaker($condition  ,$number)
    { 
        
        $sqlsearch = "SELECT id FROM treatment_x WHERE (title = '$condition' and mode = 2)";
        $resultsearch = DB::select($sqlsearch,[1])[0];

        // dd($resultsearch);

        // $now = now();
        // if(!$resultsearch){
        //     $sql = "INSERT INTO treatment_x (title,description, mode,created_at) VALUES ('$disease','$description',1,'$now')";
        //     $result = DB::insert($sql,[1]);
        // }
        
        // $sql2 = "SELECT id FROM treatment_x WHERE (title = '$disease' and mode = 1)";
        // $result2 = DB::select($sql2,[1])[0];
        for ($x=0;$x<$number;$x++){

            $disease_id =147;
            $randd=rand(0,20);
            $sqlinsert1 = "INSERT INTO submit_diseases (disease_id,user_id) VALUES ($disease_id,'$randd')";
            $resultinsert1 = DB::insert($sqlinsert1,[1]);

            $sqlsel2 = "SELECT id FROM submit_diseases ORDER BY id DESC";
            $resultsel2 = DB::select($sqlsel2,[1])[0];

            
            $sqlinsert2 = "INSERT INTO submit_disease_symptoms (submit_disease_id,symptom_id) VALUES ('$resultsel2->id','$resultsearch->id')";
            $resultinsert2 = DB::insert($sqlinsert2,[1]);
        }





    }
   
}
