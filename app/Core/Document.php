<?php


namespace App\Core;

class Document
{

  /**
   * Name of the uploaded file
   *
   * @var string
   */
  private $filename;

  /**
   * Constructor
   *
   * @param $filePath
   */

  public function __construct($filePath)
  {
    $this->filename = $filePath;
  }

  /**
   * Read doc file.
   *
   * @return string|string[]|null
   */

  private function readDoc()
  {
    $fileHandle = fopen($this->filename, "r");
    $line = @fread($fileHandle, filesize($this->filename));
    $lines = explode(chr(0x0D), $line);
    $outtext = "";
    foreach ($lines as $thisline) {
      $pos = strpos($thisline, chr(0x00));
      if (($pos !== FALSE) || (strlen($thisline) == 0)) {
      } else {
        $outtext .= $thisline . " ";
      }
    }
    $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/", "", $outtext);
    return $outtext;
  }

  /**
   * Read docx file.
   *
   * @return string|string[]|null
   */

  private function readDocx()
  {
    $striped_content = '';
    $content = '';

    $zip = zip_open($this->filename);

    if (!$zip || is_numeric($zip)) return false;

    while ($zip_entry = zip_read($zip)) {

      if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

      if (zip_entry_name($zip_entry) != "word/document.xml") continue;

      $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

      zip_entry_close($zip_entry);
    }// end while

    zip_close($zip);

    $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
    $content = str_replace('</w:r></w:p>', PHP_EOL, $content);
    $content = str_replace('</w:r><w:proofErr w:type="spellEnd"/></w:p>', PHP_EOL, $content);
    $striped_content = strip_tags($content);
    return $striped_content;
  }

  /**
   * Read txt file.
   *
   * @return string|string[]|null
   */

  private function readTxt(){
    $handle  = fopen($this->filename, 'r');
    $contents = fread($handle, filesize($this->filename));
    fclose($handle);
    return $contents;
  }

  /**
   * Convert document to text.
   *
   * @return string|string[]|null
   */

  public function convertToText()
  {

    if (isset($this->filename) && !file_exists($this->filename)) {
      return "File Not exists";
    }

    $fileArray = pathinfo($this->filename);
    $file_ext = $fileArray['extension'];
    if ($file_ext == "doc" || $file_ext == "docx" || $file_ext == "txt") {
      if ($file_ext == "doc") {
        return $this->readDoc();
      } elseif ($file_ext == "docx") {
        return $this->readDocx();
      } elseif ($file_ext == "txt") {
        return $this->readTxt();
      }
    } else {
      return "Invalid File Type";
    }
  }
}