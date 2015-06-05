<?php

/*
** Usage:
**
** $up = new Upload('html_field');
** // Or directly: $up = new Upload($_FILES['html_field'], true);
**
** if ($up->error() != UPLOAD_ERR_OK)
**    die($up->errorMsg());
** $up->setMaxSize(4000000); // 4 Mo
** $up->allowExtension('jpg');
** $up->allowExtensions(['jpg', 'jpeg', 'png']);
** $transfered = $up->register('UploadDir/'.$up->filename);
**
** if (!$transfered)
**    die($up->errorMsg());
*/

class 				Upload {
  private 			$allowedExtensions = [];
  public	 		$filename;
  public	 		$extension;
  private 			$tmpName;
  private 			$maxSize;
  public	 		$uploaded;
  public	 		$error;
  public 			$size;
  
  public static 	$uploadDir = '/uploads/';
  
  public static function 	job($field, $isData = false, $extensions = []) {
		$u = new Upload($field, $isData);
		if ($u->error() != UPLOAD_ERR_OK)
			return false;
		$u->setMaxSize(80000000);
		$u->allowExtensions($extensions);
		
		$path = ROOT . self::$uploadDir;
		if (!is_dir($path))
			mkdir($path);
		$exists = true;
		while ($exists) {
			$exists = false;
			$newFilename = str_replace('.'.$u->extension, '', $u->filename).'-'.Text::random(6, '0123456789aqwzsxedcrfvtgbyhnujikolpm').'.'.$u->extension;
			if (file_exists($path . $newFilename))
				$exists = true;
		}
		$uploaded = $u->register($path . $newFilename);
		if (!$uploaded)
			return false;
		return $uploadDir . $newFilename;
  }
  
  public function 		__construct($field, $isData = false) {
    if (!$isData)
      $file = $_FILES[$field];
    else
      $file = $field;
    if ((!$isData && !isset($_FILES[$field])) || $file['error'] > 0) {
      $this->uploaded = false;
      $this->error = $file['error'];
    } else {
      $this->uploaded = true;
      $this->error = 0;
      $this->filename = $file['name'];
      // Getting file extension
      $ext = explode('.', $this->filename);
      $this->extension = strtolower($ext[sizeof($ext) - 1]);
      $this->maxSize = Ini::size(Ini::get('upload_max_filesize'));
      $this->tmpName = $file['tmp_name'];
      $this->size = $file['size'];
    }
  }
  
  public function 		allowExtension($ext) {
    $this->allowedExtensions[] = $ext;
    $this->allowedExtensions = array_unique($this->allowedExtensions);
  }
  
  public function 		allowExtensions($arr) {
    $this->allowedExtensions = array_unique(array_merge($this->allowedExtensions, $arr));
  }
  
  public function 		setMaxSize($maxSize) {
    $this->maxSize = $maxSize;
  }
  
  public function 		uploaded() {
    return $this->uploaded;
  }
  
  public function 		error() {
    return $this->error;
  }
  
  public function 		errorMsg() {
    switch ($this->error) {
    case UPLOAD_ERR_OK:
      return '';
      break;
    case UPLOAD_ERR_INI_SIZE:
      return 'The file is too big (ini).';
      break;
    case UPLOAD_ERR_FORM_SIZE:
      return 'The file is too big.';
      break;
    case UPLOAD_ERR_PARTIAL:
      return 'The uploaded file was only partially uploaded.';
      break;
    case UPLOAD_ERR_NO_FILE:
      return 'No file was uploaded.';
      break;
    case UPLOAD_ERR_NO_TMP_DIR:
      return 'Missing a temporary folder.';
      break;
    case UPLOAD_ERR_CANT_WRITE:
      return 'Failed to write the file on the disk.';
      break;
    case UPLOAD_ERR_EXTENSION:
      return 'The extension is not allowed or a PHP extension blocked the transfer.';
      break;
    default:
      return 'Unknown error.';
      break;
    }
    return 'Unknown error.';
  }
  
  public function 		filename() {
    return $this->filename;
  }
  
  public function 		size() {
    return $this->size;
  }
  
  public function 		extension() {
    return $this->extension;
  }
  
  private function		allowed() {
    // Check size
    if ($this->size > $this->maxSize) {
      $this->error = UPLOAD_ERR_FORM_SIZE;
      return false;
    }
    if (sizeof($this->allowedExtensions) > 0 && !in_array($this->extension, $this->allowedExtensions)) {
      $this->error = UPLOAD_ERR_EXTENSION;
      return false;
    }
    return true;
  } 

  public function 		base64() {
    if ($this->allowed())
      return base64_encode(file_get_contents($this->tmpName));
    return false;
  }

  public function 		register($filename = null) {
    if (!$this->allowed())
      return false;
    if ($filename == null)
      return file_get_contents($this->tmpName);
    move_uploaded_file($this->tmpName, $filename);
    $this->tmpName = $filename;
    return $filename;
  }
}

?>