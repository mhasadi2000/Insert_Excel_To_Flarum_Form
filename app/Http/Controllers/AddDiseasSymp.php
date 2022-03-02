<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

class AddDiseasSymp extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $path = $request->address;
        // $path = "/home/hossein/Desktop/flarumExcel/resources/fatigue.csv";
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
            $symptomid = null;
            
            foreach ($importData_arr as $importData) {

                // dd($importData[0]);
                if($j==0){
                    $symptomid =$this->symptomMaker($importData[0]);
                }


                $disease = $importData[11]; //Get user names
                $description = $importData[13];

                // dd($disease);

                $this->diseaseMaker($disease,$description,$symptomid);
            
     

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


    public function symptomMaker($symp)
    {
        $sql = "INSERT INTO treatment_x (title, mode) VALUES ('$symp',2)";
        $result = DB::insert($sql,[1]);
        $sql2 = "SELECT id FROM treatment_x WHERE (title = '$symp' and mode = 2)";
        $result2 = DB::select($sql2,[1])[0];
        // dd($result2);
        $sql3 = "INSERT INTO symptoms (id) VALUES ('$result2->id')";
        $result3 = DB::insert($sql3,[1]);

        return $result2->id;

        // dd($result,$result2,$result3,$symp);
    }

    public function diseaseMaker($disease , $description ,$symptomid)
    { 
        
        $sqlsearch = "SELECT id FROM treatment_x WHERE (title = '$disease' and mode = 1)";
        $resultsearch = DB::select($sqlsearch,[1]);

        if(!$resultsearch){
            $sql = "INSERT INTO treatment_x (title,description, mode) VALUES ('$disease','$description',1)";
            $result = DB::insert($sql,[1]);
        }
        
        $sql2 = "SELECT id FROM treatment_x WHERE (title = '$disease' and mode = 1)";
        $result2 = DB::select($sql2,[1])[0];
        // $sql3 = "INSERT INTO symptoms (id) VALUES ('$result2->id')";
        // $result3 = DB::insert($sql3,[1]);

       

        $sql3 = "INSERT INTO diseases (id) VALUES ('$result2->id')";
        $result3 = DB::insert($sql3,[1]);
        
        $sql4 = "INSERT INTO disease_symptoms (disease_id, symptom_id) VALUES ('$result2->id', '$symptomid')";
        $result4 = DB::insert($sql4,[1]);
        
        
        
        // dd($result,$result2,$result3,$disease);




    }
   
}
