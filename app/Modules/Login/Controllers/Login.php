<?php
namespace App\Modules\Login\Controllers;

use CodeIgniter\Controller;
use App\Modules\Login\Models\LoginModel;
use App\Models\GeneralModel;

class Login extends Controller
{
    protected $helpers = ['cookie'];
    protected $loginModel;
    

    public function __construct()
    {
        $this->loginModel   = new LoginModel();

    }

    public function index($id = 'x', $module = 'x', $idModule = 'x')
    {
        $generalModel = new GeneralModel();

        $data = [
            'idVehicle'     => false,
            'inspectionType'=> false,
            'moduleInfo'    => $module,
            'idModule'      => $idModule
        ];

        // 🔹 Caso 1: vehículo
        if ($id !== 'x') {
            $arrParam = [
                'encryption' => $id
            ];

            $vehicleInfo = $generalModel->get_vehicle_by($arrParam);

            if (!empty($vehicleInfo)) {
                $data['vehicleInfo']   = $vehicleInfo;
                $data['idVehicle']     = $vehicleInfo[0]['id_vehicle'];
                $data['inspectionType']= $vehicleInfo[0]['inspection_type'];
            }
        }

        // 🔹 Caso 2: módulo
        if ($module !== 'x') {
            $moduleDecoded = base64url_decode($module);

            $arrParam = [
                "table"  => "param_module",
                "order"  => "id_module",
                "column" => "module_tag",
                "id"     => $moduleDecoded
            ];
            $moduleInfo = $generalModel->get_basic_search($arrParam);

            if (!empty($moduleInfo)) {
                $data['moduleInfo'] = $moduleInfo[0]['module_url'];
            }
        }

        if (session()->get('auth') == 'OK') {
            session()->set(['moduleURL' => $data['moduleInfo']]);
            session()->set(['moduleId' => $idModule]);

            return $this->redirectUser();
        }

        return view('App\Modules\Login\Views\login', $data);
    }

    public function validateUser()
    {
        // Obtener datos del POST (CI4 ya filtra)
        $login  = $this->request->getPost('inputLogin');
        $passwd = $this->request->getPost('inputPassword');

        $data = [
            'idVehicle'      => $this->request->getPost('hddId'),
            'inspectionType' => $this->request->getPost('hddInpectionType'),
            'moduleURL'     => $this->request->getPost('hddModuleURL'),
            'moduleId'       => $this->request->getPost('hddModuleId'),
            'linkInspection' => false,
            'formInspection' => false
        ];

        // Modelos
        $generalModel = new GeneralModel();

        // 🔹 Buscar info vehículo
        if ($data['idVehicle'] != 'x') {
            $arrParam = ['idVehicle' => $data['idVehicle']];
            $vehicleInfo = $generalModel->get_vehicle_by($arrParam);

            if (!empty($vehicleInfo)) {
                $data['vehicleInfo']   = $vehicleInfo;
                $data['linkInspection']= $vehicleInfo[0]['link_inspection'];
                $data['formInspection']= $vehicleInfo[0]['form'];
            }
        }

        // 🔹 Validar si usuario existe
        $arrParam = [
            "table"  => "user",
            "order"  => "id_user",
            "column" => "log_user",
            "id"     => $login
        ];

        $userExist = $generalModel->get_basic_search($arrParam);

        if ($userExist) {

            // Validar login
            $arrParam = [
                "login"  => $login,
                "passwd" => $passwd
            ];
            $user = $this->loginModel->validateLogin($arrParam);

            if ($user["valid"] == true) {

                $userRol = (int)$user["rol"];

                // Obtener info del rol
                $rolInfo = $generalModel->get_roles(["idRol" => $userRol]);

                // 🔹 Guardar sesión (CI4)
                session()->set([
                    "auth"            => "OK",
                    "id"              => $user["id"],
                    "dashboardURL"    => $rolInfo[0]["dashboard_url"],
                    "firstname"       => $user["firstname"],
                    "lastname"        => $user["lastname"],
                    "name"            => $user["firstname"] . ' ' . $user["lastname"],
                    "logUser"         => $user["logUser"],
                    "state"           => $user["state"],
                    "rol"             => $user["rol"],
                    "bankTime"        => $user["bankTime"],
                    "photo"           => $user["photo"],
                    "idVehicle"       => $data['idVehicle'],
                    "inspectionType"  => $data['inspectionType'],
                    "linkInspection"  => $data['linkInspection'],
                    "formInspection"  => $data['formInspection'],
                    "moduleURL"       => $data['moduleURL'],
                    "moduleId"        => $data['moduleId']
                ]);

                // Cookies
                set_cookie('user', $login, 350000);
                set_cookie('password', $passwd, 350000);

                // Redirección
                return $this->redirectUser();

            } else {
                $data["msj"] = "<strong>{$userExist[0]["first_name"]}</strong> that's not your password.";
                session()->destroy();
                return view('App\Modules\Login\Views\login', $data);
            }

        } else {
            $data["msj"] = "<strong>{$login}</strong> doesn't exist.";
            session()->destroy();
            return view('App\Modules\Login\Views\login', $data);
        }
    }

    /**
     * Redirecciona el usuario al módulo correspondiente dependiendo de los datos almacenados en la session
     * @author BMOTTAG
     * @since  8/11/2016
     * @review  18/12/2016
     */
    private function redirectUser()
    {
        $session = session();

        $idVehicle      = $session->get("idVehicle");
        $inspectionType = $session->get("inspectionType");
        $linkInspection = $session->get("linkInspection");
        $state          = $session->get("state");
        $dashboardURL   = $session->get("dashboardURL");

        $moduleURL = $session->get("moduleURL");
        $moduleId  = $session->get("moduleId");

        if ($moduleURL != "x") {
            $state = 10;
            if ($moduleId != "x") {
                $moduleURL = str_replace('ID_REPLACE', $moduleId, $moduleURL);
            }
        } elseif ($idVehicle != "x") {
            $state = ($inspectionType == 99 || $linkInspection == "NA") ? 99 : 88;
        }

        switch ($state) {
            case 0:
                return redirect()->to("/employee", 301);

            case 1:
                return redirect()->to($dashboardURL, 301);

            case 2:
                $session->destroy();
                return redirect()->to("/login", 301);

            case 10:
                return redirect()->to($moduleURL, 301);

            case 88:
                return redirect()->to($linkInspection, 301);

            case 99:
                $data = [
                    'linkBack' => "dashboard/",
                    'titulo'   => "<i class='fa fa-unlock fa-fw'></i>QR CODE SCAN",
                    'msj'      => "<strong>Error!!!</strong> This QR code doesn't have an inspection form.",
                    'clase'    => "alert-danger",
                    'view'     => "template/answer"
                ];
                return view('layout', $data);

            default:
                $session->destroy();
                return redirect()->to("/login", 301);
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}