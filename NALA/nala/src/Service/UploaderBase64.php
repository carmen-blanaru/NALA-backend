<?php

namespace App\Service;

class UploaderBase64
{
    /**
     * Base64 file decode, save in a folder and return file name
     *
     * @param string $stringBase64 : file in base64
     * @param string $path : folder path to save file
     * @return string : uploded file name 
     */
    public function upload(string $stringBase64, string $path): string
    {
        // Split the string to catch file base64
        $fileParts = explode(";base64,", $stringBase64);
        // Split the string to catch file extension
        $fileTypeAux = preg_split("/.+\//", $fileParts[0], -1, PREG_SPLIT_NO_EMPTY);
        // Stringify file extension
        $fileType = implode("", $fileTypeAux);
        // Decode base64 file
        $fileBase64 = base64_decode($fileParts[1]);
        // Set a file name
        $fileName = uniqid() . "." . $fileType;
        // Save file in a folder
        file_put_contents($path . '/' . $fileName, $fileBase64);

        return $fileName;
    }
}
