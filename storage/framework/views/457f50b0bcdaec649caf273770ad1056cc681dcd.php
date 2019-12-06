<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$arrayData = ['Имя', 'Фамилия', 'Дата Рождения', 'Телефон', 'создан', 'изменен'];
$spreadsheet->getActiveSheet()
    ->fromArray( $arrayData );
$pupils = \App\Pupils::all();
$i = 2;
foreach($pupils as $pupil) {
    echo $pupil['id'];
    $arrayData = [$pupil['firstName'], $pupil['secondName'], $pupil['birthDate'], $pupil['phone'], $pupil['created_at'], $pupil['updated_at']];
    $spreadsheet->getActiveSheet()
        ->fromArray( $arrayData, NULL,        // Array values with this value will not be set
            'A'.$i );
    $i++;
}
$writer = new Xlsx($spreadsheet);
$name = str_random(10).'.xlsx';
$writer->save($name);
echo 'header';
header("Location: /".$name);
die();