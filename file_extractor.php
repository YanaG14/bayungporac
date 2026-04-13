<?php
require 'vendor/autoload.php';

use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory as WordIO;
use PhpOffice\PhpSpreadsheet\IOFactory as ExcelIO;

function extractText($filepath) {
    $ext = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
    $text = '';

    try {
        // PDF
        if ($ext === 'pdf') {
            $parser = new Parser();
            $pdf = $parser->parseFile($filepath);
            $text = $pdf->getText();
        }

        // DOCX
        elseif ($ext === 'docx') {
            $phpWord = WordIO::load($filepath);
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . ' ';
                    }
                }
            }
        }

        // EXCEL
        elseif (in_array($ext, ['xlsx', 'xls'])) {
            $spreadsheet = ExcelIO::load($filepath);
            foreach ($spreadsheet->getAllSheets() as $sheet) {
                foreach ($sheet->toArray() as $row) {
                    $text .= implode(' ', $row) . ' ';
                }
            }
        }

        // TXT (optional)
        elseif ($ext === 'txt') {
            $text = file_get_contents($filepath);
        }

    } catch (Exception $e) {
        $text = '';
    }

    return $text;
}