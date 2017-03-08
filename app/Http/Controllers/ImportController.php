<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use mysqli;

class ImportController extends Controller {

    protected $file;

    public function import() {
        return view('import');
    }

    public function readFile(Request $request) {
        if ($request->file('filename')) {
            $this->file = $request->file('filename');
            $this->destinationPath = public_path() . '\uploads\\';

            $fileName = $this->file->getClientOriginalName();

            $fileType = substr($fileName, -3);
            if ($fileType === 'xml') {
                $extensions = ['CSV', 'SQL'];
            } elseif ($fileType === 'csv') {
                $extensions = ['XML', 'SQL'];
            } elseif ($fileType === 'sql') {
                $extensions = ['XML', 'CSV'];
            }
            $this->file->move($this->destinationPath, $fileName);

            $filePath = public_path("uploads\\" . $fileName);


            return view('read', [
                'filePath' => $filePath,
                'fileType' => $fileType,
                'extensions' => $extensions,
                'fileName' => $fileName
            ]);
        }
    }

    public function storeFileData() {
        $filePath = Input::get('filePath');
        $fileName = Input::get('fileName');
        $extension = substr($fileName,-3);
        $fileName = substr($fileName, 0, -4);
        
        $fileExtensionSelected = Input::get('fileExtension');

        if ($fileExtensionSelected === 'CSV') {
            if (file_exists($filePath)) {
                $xml = simplexml_load_file($filePath);
                $f = fopen($fileName . '.csv', 'w');
                foreach ($xml as $element) {
                    fputcsv($f, get_object_vars($element), ',', '"');
                }
                fclose($f);
                return response()->download($fileName . '.csv');
            }
            return response('Plik nie istnieje.');
        } elseif ($fileExtensionSelected === 'XML') {
            if (file_exists($filePath)) {
                $csv = file($filePath);

                return response()->download($fileName . '.xml');
            }
            return response('Plik nie istnieje.');
        } elseif ($fileExtensionSelected === 'SQL') {
            $connect = new mysqli("localhost", "root", "");
            if ($connect->connect_error) {
                die("Connection failed: " . $connect->connect_error);
            }
            //echo "Connected successfully";
            mysqli_select_db($connect, "importfile");

            $input = file_get_contents($filePath);
            $lines_array = explode("\n", $input);

            foreach ($lines_array as $key => $value) {
                $connect = new mysqli("localhost", "root", "", "importfile");
                if ($connect->connect_error) {
                    die("Connection failed: " . $connect->connect_error);
                }
                $line = explode(",", $value);
                for ($i = 0; $i < count($line); $i++) {
                    mysqli_query($connect, "INSERT INTO import_data (name)
                    VALUES ('$line[$i]')");
                }
            }
            mysqli_close($connect);
            return response('Zapisano rekordy do bazy danych');
        }
        return response('Plik nie istnieje.');
    }

}
