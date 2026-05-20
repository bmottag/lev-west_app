<?php
namespace App\Modules\Employee\Controllers;

use App\Controllers\BaseController;
use App\Modules\Employee\Models\EmployeeModel;
use App\Models\GeneralModel;

class Employee extends BaseController
{
    protected $employeeModel;
    protected $generalModel;
    
    public function __construct()
    {
        $this->employeeModel   = new EmployeeModel();
        $this->generalModel   = new GeneralModel();
    }

	/**
	 * photo
	 * @review 06/04/2026 - new CI4 version
	 */
	public function profile()
	{
		$data['UserInfo'] = $this->generalModel->get_user(['idUser' => session()->get('id')]);
		return $this->render('App\Modules\Employee\Views\form_photo', $data);
	}

	public function do_upload()
	{
		$idUser = session()->get('id');
		$file = $this->request->getFile('userfile');

		if (!$file->isValid()) {
			// Error al subir
			$error = $file->getErrorString();
			return redirect()->back()->with('retornoError', $error);
		}

		// ✅ Validar tipo de archivo
		$allowedTypes = ['image/gif', 'image/jpeg', 'image/png'];
		if (!in_array($file->getMimeType(), $allowedTypes)) {
			return redirect()->back()->with('retornoError', 'Only GIF, JPG, PNG files are allowed.');
		}

		// ✅ Validar tamaño máximo en KB
		$maxSizeKB = 3000;
		if ($file->getSizeByUnit('kb') > $maxSizeKB) {
			return redirect()->back()->with('retornoError', "File too large. Maximum size: {$maxSizeKB} KB.");
		}

		// ✅ Validar dimensiones de imagen
		list($width, $height) = getimagesize($file->getTempName());
		if ($width > 2024 || $height > 2008) {
			return redirect()->back()->with('retornoError', "Image dimensions too large. Max: 2024x2008 px.");
		}

		// Configuración de nombre y rutas
		$fileName = $idUser . '.' . $file->getExtension();
		$uploadPath = WRITEPATH . '../public/images/employee/';
		$thumbPath = $uploadPath . 'thumbs/';

		// Mover archivo
		$file->move($uploadPath, $fileName, true);

		// Crear miniatura
		$this->_create_thumbnail($fileName, $uploadPath, $thumbPath);

		// Guardar en DB
		$path = 'images/employee/thumbs/' . $fileName;
		$this->generalModel->updateRecord([
			"table" => "user",
			"primaryKey" => "id_user",
			"id" => $idUser,
			"column" => "photo",
			"value" => $path
		]);

		return redirect()->back()->with('retornoExito', 'Photo updated successfully.');
	}

    private function _create_thumbnail($filename, $sourcePath, $thumbPath)
    {
        $image = \Config\Services::image()
            ->withFile($sourcePath . $filename)
            ->fit(150, 150, 'center')
            ->save($thumbPath . $filename);
    }

	public function save_signature()
	{
		$imageData = $this->request->getPost('image'); // o el hiddenName que uses
		$fileName = 'signature_user_' . session()->get('id') . '.png';
		$filePath = WRITEPATH . '../public/images/employee/signature/' . $fileName;

		if(!$imageData){
			return redirect()->back()->with('error', 'No signature provided.');
		}

		$imageData = str_replace('data:image/png;base64,', '', $imageData);
		$imageData = str_replace(' ', '+', $imageData);

		if(!is_dir(dirname($filePath))) mkdir(dirname($filePath), 0755, true);

		if(file_put_contents($filePath, base64_decode($imageData))){
			$this->generalModel->updateRecord([
				"table" => "user",
				"primaryKey" => "id_user",
				"id" => session()->get('id'),
				"column" => "user_signature",
				"value" => 'images/employee/signature/' . $fileName
			]);
			return redirect()->back()->with('success', 'Signature saved successfully.');
		} else {
			return redirect()->back()->with('error', 'Error saving signature.');
		}
	}


}
