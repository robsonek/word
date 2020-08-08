<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
require_once 'DocxConversion.php';
require_once 'vendor/autoload.php';

use NcJoes\OfficeConverter\OfficeConverter;

function replace_all_text_between($str, $start, $end) {

    $txtOut = '';
    $start = preg_quote($start, '/');
    $end = preg_quote($end, '/');
    $regex = "/({$start})(.*?)({$end})/";

    preg_match_all($regex, $str, $matches);
    foreach ($matches[0] as $id => $match) {
        $txtOut .= $matches[2][$id].': <input type="text" id="'.$matches[2][$id].'" value="'.rand(0,10000).'" name="'.$matches[2][$id].'" /><br />';
    }
    return $txtOut;
}

function docx2text($filename, $search, $replace) {
    return readZippedXML($filename, "word/document.xml", $search, $replace);
}

function readZippedXML($archiveFile, $dataFile, $search, $replace) {
// Create new ZIP archive
    $zip = new ZipArchive;

// Open received archive file
    if (true === $zip->open($archiveFile)) {
        // If done, search for the data file in the archive
        if (($index = $zip->locateName($dataFile)) !== false) {
            // If found, read it to the string
            $data = $zip->getFromIndex($index);
            $data = str_replace('{{'.$search.'}}', $replace, $data);
            $zip->addFromString('word/document.xml', $data);
            // Close archive file
            $zip->close();
            // Load XML from a string
            return "";
        }
        $zip->close();
    }

// In case of failure return empty string
    return "";
}

$fileName = 'umowa.docx';

copy('files/'.$fileName, 'tmp/'.$fileName);

$fileName = 'tmp/'.$fileName;

if (!empty($_POST)) {
    foreach ($_POST as $name => $item) {
        docx2text($fileName, $name, $item); // Save this contents to file
    }
    $file = 'umowa.pdf';

    $converter = new OfficeConverter($fileName);
    $converter->convertTo($file);

    $file = 'tmp/umowa.pdf';

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    die();
}

$docObj = new DocxConversion($fileName);

?>
<html lang="pl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>

        $( document ).ready(function() {

        $('input').on('paste', function(e){
            $this = $(this);

            setTimeout(function(){
                var columns = $this.val().split(/\s+/);

                var i;
                var input =  $this
                for(i=0; i < columns.length; i++){
                    input.val(columns[i]);
                    input = input.next();
                    input = input.next();
                }
            }, 0);
        });

        });
    </script>
    <title>WORD</title>
</head>
<body>
<?php
echo '<form method="post" action="">';
echo replace_all_text_between($docObj->convertToText(), '{{', '}}');
echo '<input type="submit" value="Pobierz PDF"></form>';

?>
</body>
</html>
