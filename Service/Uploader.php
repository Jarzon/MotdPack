<?php
namespace Jarzon\MotdPack\Service;

class Uploader
{
    public $fileIndex = '';
    public $inputName = '';

    public $fileName = '';
    public $fileNameHash = '';
    public $fileTempName = '';

    public $uploadPath = '';
    public $ext = '';

    function __construct($uploadPath, $inputName, $fileIndex) {
        $this->uploadPath = $uploadPath;
        $this->inputName = $inputName;
        $this->fileIndex = $fileIndex;

        if (
            !isset($_FILES[$this->inputName]['error'][$this->fileIndex])
            || is_array($_FILES[$this->inputName]['error'][$this->fileIndex])// Delete that line if you want multi files upload and rewrite the method
        ) {
            throw new RuntimeException('Invalid parameters.');
        }

        switch ($_FILES[$this->inputName]['error'][$this->fileIndex]) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }

//        if ($_FILES[$this->inputName]['size'][$this->fileIndex] > $this->file_upload_max_size()) {
//            throw new RuntimeException('Exceeded filesize limit.');
//        }

//        $finfo = new finfo(FILEINFO_MIME_TYPE);
//        $formats = [
//            'audio/mp3' => 'mp3',
//            'audio/mpeg' => 'mp3'
//        ];
//
//        if(isset($formats[$finfo->file($_FILES[$this->inputName]['tmp_name'][$this->fileIndex])])) {
//            $this->ext = $formats[$finfo->file($_FILES[$this->inputName]['tmp_name'][$this->fileIndex])];
//        } else {
//            throw new RuntimeException('Invalid file format.'.$finfo->file($_FILES[$this->inputName]['tmp_name'][$this->fileIndex]));
//        }

        $this->ext = 'mp3';
    }

    function saveFile() {
        $fileName = explode('.', $_FILES[$this->inputName]['name'][$this->fileIndex]);

        if(count($fileName) !== 2) {
            throw new RuntimeException('Error song a required to contain a dot and only one (ex. song.mp3)');
        }

        $this->fileName = $fileName[0];
        $this->ext = $fileName[1];

        $this->fileTempName = $_FILES[$this->inputName]['tmp_name'][$this->fileIndex];
        $this->fileNameHash = sha1_file($_FILES[$this->inputName]['tmp_name'][$this->fileIndex]); // TODO: is this future proof? should use a date right?

        if (move_uploaded_file($this->fileTempName, sprintf('%s/%s.%s',
            $this->uploadPath,
            $this->fileNameHash,
            $this->ext
        ))) {
            return true;
        } else {
            throw new RuntimeException('Failed to move uploaded file.');
        }
    }

    function getFileNameHash() {
        return $this->fileNameHash;
    }

    function getfileTempName() {
        return $this->fileTempName;
    }

    function getFileName() {
        return $this->fileName;
    }

    function getFileExt() {
        return $this->ext;
    }
}