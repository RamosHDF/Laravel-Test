<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PdfUploadController extends Controller
{    

    public function uploadPdfFile(Request $request){
    
        $validator = Validator::make($request->all(), 
        ['fileInput' => 'mimes:pdf']);
            if ($validator->fails()) {
                    $response['code']=422;
                    $response['status']='errors';
                    $response['errors']= $validator->errors();
            return response()->json($response, 422);
            }
            else{
                    $fileName = $request->file('fileInput')->getClientOriginalName();
                    $size= $request->file('fileInput')->getSize();
                    $path = $request->file('fileInput')->store('files', 'public');
                    $fileUrl = Storage::url($path);

                    $filesInDatabase = File::where('file_name','like', $fileName,'and','file_size','=',$size)->first();

                    if($filesInDatabase){
                        $this->pdfService->searchFor();
                        $response['code']=404;
                        $response['status']='failed';
                        $response['data']=  $fileName.' already exist';
                        return response()->json($response, 422);
                    }else{
                        
                        $fileContent = file_get_contents($_FILES['fileInput']['tmp_name']);
                        if($this->searchFor('Proposal',$fileContent)){

                            $response['code']=404;
                            $response['status']='failed';
                            $response['data']=  'file must include the word Proposal';
                     
                            return response()->json($response, 422);
                        }
                        else{
                            $file = File::updateOrCreate([
                                'file_name' => $fileName ,
                                'file_size' => $size,
                                'file_url'=> $fileUrl
                                ]);
                                          
                                $file->save();
                            $response['code']=200;
                            $response['status']='success';
                            $response['data']=  $fileName.' added succefully';
                     
                            return response()->json($response, 200);
                        }
     
                    }            
            }   
    }
    public function searchFor($word,$file){
        $fileContent = file_get_contents($file);
        if(preg_match_all('/'.$word.'/',$fileContent)){
            return true;
        }
        else{
            return false;
        }
        
    }
}
