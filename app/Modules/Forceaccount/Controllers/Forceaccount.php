<?php
namespace App\Modules\Forceaccount\Controllers;

use App\Controllers\BaseController;
use App\Modules\Forceaccount\Models\ForceaccountModel;
use App\Models\GeneralModel;
use App\Libraries\PdfBuilder;

class Forceaccount extends BaseController
{
    protected $forceaccountModel;
    protected $generalModel;
    protected $helpers = ['form'];

    public function __construct()
    {
        $this->forceaccountModel = new ForceaccountModel();
        $this->generalModel      = new GeneralModel();
    }

    /**
     * Form Forceaccounts
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function index()
    {
        $userRol = $this->session->get('rol');
        $idUser  = $this->session->get('id');

        $arrParam = [];
        if ($userRol == ID_ROL_BASIC || $userRol == ID_ROL_SAFETY) {
            $arrParam['idEmployee'] = $idUser;
        }

        $data['forceAccountInfo'] = $this->forceaccountModel->get_forceaccount_by_idUser($arrParam);

        $this->generalModel->deleteRecord([
            'table'      => 'forceaccount_go_back',
            'primaryKey' => 'fk_id_user',
            'id'         => $idUser,
        ]);

        return $this->render('App\Modules\Forceaccount\Views\forceaccount', $data);
    }

    /**
     * Form Add forceaccount
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function add_forceaccount($id = 'x')
    {
        $data['information']         = false;
        $data['forceaccountPersonal']  = false;
        $data['forceaccountMaterials'] = false;
        $data['deshabilitar']          = '';

        $data['jobs'] = $this->generalModel->get_basic_search([
            'table'  => 'param_jobs',
            'order'  => 'job_description',
            'column' => 'state',
            'id'     => 1,
        ]);

        if ($id !== 'x') {
            $arrParam = ['idForceAccount' => $id];

            $data['forceaccountPersonal']  = $this->forceaccountModel->get_forceaccount_personal($arrParam);
            $data['forceaccountMaterials'] = $this->forceaccountModel->get_forceaccount_materials($arrParam);
            $data['forceaccountReceipt']   = $this->forceaccountModel->get_forceaccount_receipt($arrParam);
            $data['forceaccountEquipment'] = $this->forceaccountModel->get_forceaccount_equipment($arrParam);
            $data['forceaccountOcasional'] = $this->forceaccountModel->get_forceaccount_ocasional($arrParam);
            $data['forceaccountState']     = $this->forceaccountModel->get_forceaccount_state($id);
            $data['information']           = $this->forceaccountModel->get_forceaccount_by_idUser($arrParam);

            $data['employeeTypeList'] = $this->generalModel->get_basic_search([
                'table' => 'param_employee_type',
                'order' => 'employee_type',
                'id'    => 'x',
            ]);

            if (!$data['information']) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('ERROR!!! - You are in the wrong place.');
            }

            $userRol          = $this->session->get('rol');
            $forceaccountState = $data['information'][0]['state'];

            if ($userRol != ID_ROL_SUPER_ADMIN) {
                if ($forceaccountState == 4) {
                    $data['deshabilitar'] = 'disabled';
                } elseif ($forceaccountState != 0 && ($userRol == ID_ROL_SAFETY || $userRol == ID_ROL_SUPERVISOR || $userRol == ID_ROL_BASIC)) {
                    $data['deshabilitar'] = 'disabled';
                } elseif ($forceaccountState == 5 && $userRol != ID_ROL_ACCOUNTING_ASSISTANT) {
                    $data['deshabilitar'] = 'disabled';
                } elseif (($forceaccountState == 2 || $forceaccountState == 3) && $userRol == ID_ROL_WORKORDER) {
                    $data['deshabilitar'] = 'disabled';
                } elseif ($forceaccountState == 3 && $userRol == ID_ROL_ENGINEER) {
                    $data['deshabilitar'] = 'disabled';
                } elseif ($forceaccountState == 1 && ($userRol == ID_ROL_MANAGER || $userRol == ID_ROL_ACCOUNTING)) {
                    $data['deshabilitar'] = 'disabled';
                }
            }
        }

        return $this->render('App\Modules\Forceaccount\Views\form_forceaccount', $data);
    }

    /**
     * Save forceaccount
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function save_forceaccount()
    {
        $post   = $this->request->getPost();
        $idUser = $this->session->get('id');
        $data   = [];

        $idForceaccountInicial = $post['hddIdentificador'] ?? '';
        $msj = $idForceaccountInicial !== ''
            ? 'You have updated the Force Account, continue uploading the information.'
            : 'You have added a new Force Account, continue uploading the information.';

        if ($idForceaccount = $this->forceaccountModel->add_forceaccount($post, $idUser)) {
            $nameForeman = $post['foreman'] ?? '';

            if ($nameForeman !== '') {
                $idJob    = $post['jobName'];
                $infoForeman = $this->generalModel->get_basic_search([
                    'table'  => 'param_company_foreman',
                    'order'  => 'id_company_foreman',
                    'column' => 'fk_id_job',
                    'id'     => $idJob,
                ]);
                $idForeman = $infoForeman ? $infoForeman[0]['id_company_foreman'] : '';
                $this->forceaccountModel->info_foreman($idForeman, $post);
            }

            if ($idForceaccountInicial === '') {
                $this->forceaccountModel->add_forceaccount_state([
                    'idForceaccount' => $idForceaccount,
                    'observation'    => 'New Force Account.',
                    'state'          => 0,
                ], $idUser);
            }

            $data['status']       = 'success';
            $data['mensaje']      = $msj;
            $data['idForceaccount'] = $idForceaccount;
            session()->setFlashdata('retornoExito', $msj);
        } else {
            $data['status']       = 'error';
            $data['mensaje']      = 'Error!!! Ask for help.';
            $data['idForceaccount'] = '';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Update forceaccount state to Revised
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function update_forceaccount()
    {
        $data               = [];
        $data['idForceaccount'] = $this->request->getPost('hddIdentificador');

        if ($this->generalModel->updateRecord([
            'table'      => 'forceaccount',
            'primaryKey' => 'id_forceaccount',
            'id'         => $data['idForceaccount'],
            'column'     => 'state',
            'value'      => 2,
        ])) {
            $data['status']  = 'success';
            $data['mensaje'] = 'You have closed the Force Account.';
            session()->setFlashdata('retornoExito', 'You have closed the Force Account');
        } else {
            $data['status']  = 'error';
            $data['mensaje'] = 'Error!!! Ask for help.';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Cargo modal - formulario de captura personal
     * @since 16/04/2025
     * @review 19/05/2026 - new CI4 version
     */
    public function cargarModalPersonal()
    {
        $data['idForceaccount'] = $this->request->getPost('idForceaccount');

        $data['workersList']      = $this->generalModel->get_user(['state' => 1]);
        $data['employeeTypeList'] = $this->generalModel->get_basic_search([
            'table' => 'param_employee_type',
            'order' => 'employee_type',
            'id'    => 'x',
        ]);

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\Forceaccount\Views\modal_personal', $data));
    }

    /**
     * Save formularios
     * @param string $modalToUse: model method name to call
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function save($modalToUse)
    {
        $post           = $this->request->getPost();
        $idUser         = $this->session->get('id');
        $idForceAccount = $post['hddidForceaccount'] ?? '';
        $data           = ['idRecord' => $idForceAccount];

        if ($this->forceaccountModel->$modalToUse($post, $idUser)) {
            if ($modalToUse === 'saveExpense') {
                $idJob = $post['hddidJob'] ?? '';
                $this->update_wo_expenses_values($idForceAccount, $idJob);

                $this->generalModel->updateRecord([
                    'table'      => 'forceaccount',
                    'primaryKey' => 'id_forceaccount',
                    'id'         => $idForceAccount,
                    'column'     => 'expenses_flag',
                    'value'      => 1,
                ]);
            }

            $data['status'] = 'success';
            session()->setFlashdata('retornoExito', 'You have added a new record!!');
        } else {
            $data['status'] = 'error';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        $userRol             = $this->session->get('rol');
        $data['controlador'] = 'add_forceaccount';
        if ($userRol == ID_ROL_SUPER_ADMIN) {
            $data['controlador'] = 'view_forceaccount';
        }

        return $this->response->setJSON($data);
    }

    /**
     * Delete forceaccount record
     * @param string $tabla
     * @param int    $idValue
     * @param int    $idForceAccount
     * @param string $vista
     * @since 16/04/2025
     * @review 19/05/2026 - new CI4 version
     */
    public function deleteRecord($tabla, $idValue, $idForceAccount, $vista)
    {
        if (empty($tabla) || empty($idValue) || empty($idForceAccount)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ERROR!!! - You are in the wrong place.');
        }

        $table = 'forceaccount_' . $tabla;
        $old   = $this->generalModel->get_basic_search([
            'table'  => $table,
            'order'  => 'id_forceaccount_' . $tabla,
            'column' => 'id_forceaccount_' . $tabla,
            'id'     => $idValue,
        ]);

        $log = ['old' => $old, 'new' => null];

        if ($this->generalModel->deleteRecord([
            'table'      => $table,
            'primaryKey' => 'id_forceaccount_' . $tabla,
            'id'         => $idValue,
        ])) {
            if ($tabla === 'personal' && $old) {
                $idEmployee = $old[0]['fk_id_user'];

                $taskStart = $this->generalModel->get_task([
                    'idWorkOrder' => $idForceAccount,
                    'idEmployee'  => $idEmployee,
                    'column'      => 'wo_start_project',
                ]);
                if ($taskStart) {
                    $this->generalModel->updateRecord([
                        'table'      => 'task',
                        'primaryKey' => 'id_task',
                        'id'         => $taskStart[0]['id_task'],
                        'column'     => 'wo_start_project',
                        'value'      => null,
                    ]);
                }

                $taskEnd = $this->generalModel->get_task([
                    'idWorkOrder' => $idForceAccount,
                    'idEmployee'  => $idEmployee,
                    'column'      => 'wo_end_project',
                ]);
                if ($taskEnd) {
                    $this->generalModel->updateRecord([
                        'table'      => 'task',
                        'primaryKey' => 'id_task',
                        'id'         => $taskEnd[0]['id_task'],
                        'column'     => 'wo_end_project',
                        'value'      => null,
                    ]);
                }
            }

            $this->forceaccountModel->deleteExpenses([
                'fk_id_forceaccount' => $idForceAccount,
                'submodule'          => $tabla,
                'fk_id_submodule'    => $idValue,
            ]);

            $info  = $this->forceaccountModel->get_forceaccount_by_idJob(['idForceAccount' => $idForceAccount]);
            $idJob = $info[0]['fk_id_job'];
            $this->update_wo_expenses_values($idForceAccount, $idJob);

            $forceaccountExpense = $this->forceaccountModel->get_forceaccount_expense(['idForceAccount' => $idForceAccount]);
            if (!$forceaccountExpense) {
                $this->generalModel->updateRecord([
                    'table'      => 'forceaccount',
                    'primaryKey' => 'id_forceaccount',
                    'id'         => $idForceAccount,
                    'column'     => 'expenses_flag',
                    'value'      => 0,
                ]);
            }

            session()->setFlashdata('retornoExito', 'You have deleted one record from <strong>' . $tabla . '</strong> table.');
        } else {
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return redirect()->to(base_url('forceaccount/' . $vista . '/' . $idForceAccount));
    }

    /**
     * Cargo modal - formulario de captura Material
     * @since 16/04/2025
     * @review 19/05/2026 - new CI4 version
     */
    public function cargarModalMaterials()
    {
        $idForceaccount         = $this->request->getPost('idForceaccount');
        $porciones              = explode('-', $idForceaccount);
        $data['idForceaccount'] = $porciones[1];

        $data['materialList'] = $this->generalModel->get_basic_search([
            'table' => 'param_material_type',
            'order' => 'material',
            'id'    => 'x',
        ]);

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\Forceaccount\Views\modal_material', $data));
    }

    /**
     * Cargo modal - formulario de captura Equipment
     * @since 16/04/2025
     * @review 19/05/2026 - new CI4 version
     */
    public function cargarModalEquipment()
    {
        $idForceaccount         = $this->request->getPost('idForceaccount');
        $porciones              = explode('-', $idForceaccount);
        $data['idForceaccount'] = $porciones[1];

        $data['equipmentType'] = $this->generalModel->get_basic_search([
            'table'  => 'param_vehicle_type_2',
            'order'  => 'type_2',
            'column' => 'show_workorder',
            'id'     => 1,
        ]);

        $data['workersList'] = $this->generalModel->get_user(['state' => 1]);

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\Forceaccount\Views\modal_equipment', $data));
    }

    /**
     * Company list
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    public function companyList()
    {
        $companyType = $this->request->getPost('CompanyType');

        $lista = $this->generalModel->get_basic_search([
            'table'  => 'param_company',
            'order'  => 'company_name',
            'column' => 'company_type',
            'id'     => $companyType,
        ]);

        $html = "<option value=''>Select...</option>";
        if ($lista) {
            foreach ($lista as $fila) {
                $html .= "<option value='" . esc($fila['id_company']) . "'>" . esc($fila['company_name']) . "</option>";
            }
        }

        return $this->response->setContentType('text/html')->setBody($html);
    }

    /**
     * Cargo modal - formulario de captura Ocasional
     * @since 16/04/2025
     * @review 19/05/2026 - new CI4 version
     */
    public function cargarModalOcasional()
    {
        $idForceaccount = $this->request->getPost('idForceaccount');
        $porciones      = explode('-', $idForceaccount);

        if (count($porciones) > 1) {
            $data['idForceaccount'] = $porciones[1];

            $data['companyList'] = $this->generalModel->get_basic_search([
                'table'  => 'param_company',
                'order'  => 'company_name',
                'column' => 'company_type',
                'id'     => 2,
            ]);

            return $this->response
                ->setContentType('text/html')
                ->setBody(view('App\Modules\Forceaccount\Views\modal_ocasional', $data));
        }

        return $this->response->setBody('');
    }

    /**
     * Search by JOB CODE
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    public function search($goBack = 'x')
    {
        $idUser = $this->session->get('id');

        $data['jobList'] = $this->generalModel->get_basic_search([
            'table'  => 'param_jobs',
            'order'  => 'job_description',
            'column' => 'state',
            'id'     => 1,
        ]);

        $data['noOnfield']   = $this->forceaccountModel->countForceaccounts(['state' => 0]);
        $data['noProgress']  = $this->forceaccountModel->countForceaccounts(['state' => 1]);
        $data['noRevised']   = $this->forceaccountModel->countForceaccounts(['state' => 2]);
        $data['noSend']      = $this->forceaccountModel->countForceaccounts(['state' => 3]);
        $data['noClosed']    = $this->forceaccountModel->countForceaccounts(['state' => 4]);
        $data['noAccounting'] = $this->forceaccountModel->countForceaccounts(['state' => 5]);

        if ($goBack === 'y') {
            $forceAccountGoBackInfo = $this->forceaccountModel->get_forceaccount_go_back($idUser);

            if (!$forceAccountGoBackInfo) {
                return redirect()->to(base_url('forceaccount'));
            }

            $to = date('Y-m-d', strtotime('+1 day', strtotime(formatear_fecha($forceAccountGoBackInfo['post_to']))));

            $arrParam = [
                'jobId'              => $forceAccountGoBackInfo['post_id_job'],
                'idForceAccount'     => $forceAccountGoBackInfo['post_id_work_order'],
                'idForceAccountFrom' => $forceAccountGoBackInfo['post_id_wo_from'],
                'idForceAccountTo'   => $forceAccountGoBackInfo['post_id_wo_to'],
                'from'               => $forceAccountGoBackInfo['post_from'],
                'to'                 => $to,
                'state'              => $forceAccountGoBackInfo['post_state'],
            ];

            $data['forceAccountInfo'] = $this->forceaccountModel->get_forceaccount_by_idJob($arrParam);

            return $this->renderTopOnly('App\Modules\Forceaccount\Views\asign_rate_list', $data);
        }

        $post = $this->request->getPost();
        if ($post['jobName'] ?? '' || $post['forceAccountNumber'] ?? '' || $post['forceAccountNumberFrom'] ?? '' || $post['from'] ?? '') {
            $data['jobName']              = $post['jobName'] ?? '';
            $data['forceAccountNumber']   = $post['forceAccountNumber'] ?? '';
            $data['forceAccountNumberFrom'] = $post['forceAccountNumberFrom'] ?? '';
            $data['forceAccountNumberTo']   = $post['forceAccountNumberTo'] ?? '';
            $data['from']                 = $post['from'] ?? '';
            $data['to']                   = $post['to'] ?? '';

            $to   = $data['to']   ? formatear_fecha($data['to'])   : '';
            $from = $data['from'] ? formatear_fecha($data['from']) : '';

            $arrParam = [
                'jobId'              => $post['jobName'] ?? '',
                'idForceAccount'     => $post['forceAccountNumber'] ?? '',
                'idForceAccountFrom' => $post['forceAccountNumberFrom'] ?? '',
                'idForceAccountTo'   => $post['forceAccountNumberTo'] ?? '',
                'from'               => $from,
                'to'                 => $to,
            ];

            $this->forceaccountModel->saveInfoGoBack($arrParam, $idUser);
            $data['forceAccountInfo'] = $this->forceaccountModel->get_forceaccount_by_idJob($arrParam);

            return $this->renderTopOnly('App\Modules\Forceaccount\Views\asign_rate_list', $data);
        }

        return $this->render('App\Modules\Forceaccount\Views\asign_rate_form_search', $data);
    }

    /**
     * View info Force Account to assign rate
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function view_forceaccount($id)
    {
        $arrParam = ['idForceAccount' => $id];

        $data['forceaccountExpense']   = $this->forceaccountModel->get_forceaccount_expense($arrParam);
        $data['forceaccountPersonal']  = $this->forceaccountModel->get_forceaccount_personal($arrParam);
        $data['forceaccountMaterials'] = $this->forceaccountModel->get_forceaccount_materials($arrParam);
        $data['forceaccountReceipt']   = $this->forceaccountModel->get_forceaccount_receipt($arrParam);
        $data['forceaccountEquipment'] = $this->forceaccountModel->get_forceaccount_equipment($arrParam);
        $data['forceaccountOcasional'] = $this->forceaccountModel->get_forceaccount_ocasional($arrParam);
        $data['information']           = $this->forceaccountModel->get_forceaccount_by_idJob($arrParam);

        $arrParam2            = ['idForceAccount' => $id, 'idJob' => $data['information'][0]['fk_id_job']];
        $arrParam2['table']   = 'forceaccount_personal';
        $data['incomePersonal'] = $this->forceaccountModel->countIncome($arrParam2);

        $arrParam2['table']       = 'forceaccount_materials';
        $data['incomeMaterial']   = $this->forceaccountModel->countIncome($arrParam2);

        $arrParam2['table']       = 'forceaccount_equipment';
        $data['incomeEquipment']  = $this->forceaccountModel->countIncome($arrParam2);

        $arrParam2['table']           = 'forceaccount_ocasional';
        $data['incomeSubcontractor']  = $this->forceaccountModel->countIncome($arrParam2);

        $arrParam2['table']   = 'forceaccount_receipt';
        $data['incomeReceipt'] = $this->forceaccountModel->countIncome($arrParam2);

        $data['totalWOIncome'] = $data['incomePersonal'] + $data['incomeMaterial'] + $data['incomeEquipment'] + $data['incomeSubcontractor'] + $data['incomeReceipt'];

        $userRol          = $this->session->get('rol');
        $forceaccountState = $data['information'][0]['state'];
        $data['deshabilitar'] = '';

        if ($forceaccountState == 4) {
            $data['deshabilitar'] = 'disabled';
        } elseif ($forceaccountState != 0 && ($userRol == ID_ROL_SAFETY || $userRol == ID_ROL_SUPERVISOR || $userRol == ID_ROL_BASIC)) {
            $data['deshabilitar'] = 'disabled';
        } elseif (($forceaccountState == 2 || $forceaccountState == 3) && $userRol == ID_ROL_WORKORDER) {
            $data['deshabilitar'] = 'disabled';
        } elseif ($forceaccountState == 1 && ($userRol == ID_ROL_MANAGER || $userRol == ID_ROL_ACCOUNTING)) {
            $data['deshabilitar'] = 'disabled';
        }

        return $this->render('App\Modules\Forceaccount\Views\asign_rate_form', $data);
    }

    /**
     * Save rate
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    public function save_rate()
    {
        $post           = $this->request->getPost();
        $idUser         = $this->session->get('id');
        $idForceaccount = $post['hddIdWorkOrder'] ?? '';

        if ($this->forceaccountModel->saveRate($post, $idUser, $this->generalModel)) {
            session()->setFlashdata('retornoExito', 'You have saved the Rate!!');
        } else {
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return redirect()->to(base_url('forceaccount/view_forceaccount/' . $idForceaccount));
    }

    /**
     * Save hour
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function save_hour()
    {
        $post           = $this->request->getPost();
        $idUser         = $this->session->get('id');
        $idForceaccount = $post['hddIdWorkOrder'] ?? '';

        if ($this->forceaccountModel->saveRate($post, $idUser, $this->generalModel)) {
            session()->setFlashdata('retornoExito', 'You have saved the Rate!!');
        } else {
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return redirect()->to(base_url('forceaccount/add_forceaccount/' . $idForceaccount));
    }

    /**
     * Envio de correo a la empresa
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    public function email($id)
    {
        // TODO: implement email sending
        // $arrParam = ['idForceAccount' => $id];
        // ... send emails to contractors and VCI
        session()->setFlashdata('retornoExito', 'Email functionality pending implementation.');

        return redirect()->to(base_url('forceaccount/add_forceaccount/' . $id));
    }

    /**
     * Signature
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
	public function save_signature()
	{
		$imageData = $this->request->getPost('image'); // o el hiddenName que uses
        $idForceAccount = $this->request->getPost('id');
		$fileName = 'forceaccount_' . $idForceAccount . '.png';
		$filePath = WRITEPATH . '../public/images/signature/forceaccount/' . $fileName;

		if(!$imageData){
			return redirect()->back()->with('retornoError', 'No signature provided.');
		}

		$imageData = str_replace('data:image/png;base64,', '', $imageData);
		$imageData = str_replace(' ', '+', $imageData);

		if(!is_dir(dirname($filePath))) mkdir(dirname($filePath), 0755, true);

		if(file_put_contents($filePath, base64_decode($imageData))){
			$this->generalModel->updateRecord([
                'table'      => 'forceaccount',
                'primaryKey' => 'id_forceaccount',
                'id'         => $idForceAccount,
                'column'     => 'signature_wo',
				"value" => 'images/signature/forceaccount/' . $fileName
			]);
			return redirect()->back()->with('retornoExito', 'Signature saved successfully.');
		} else {
			return redirect()->back()->with('retornoError', 'Error saving signature.');
		}
	}

    /**
     * Save forceaccount and send email
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    public function save_forceaccount_and_send_email()
    {
        $post   = $this->request->getPost();
        $idUser = $this->session->get('id');
        $data   = [];

        if ($idForceaccount = $this->forceaccountModel->add_forceaccount($post, $idUser)) {
            // TODO: implement email sending (email_v2 equivalent)
            $data['status']       = 'success';
            $data['mensaje']      = 'You have updated the Force Account and send an email to the contractor, continue uploading the information.';
            $data['idForceaccount'] = $idForceaccount;
            session()->setFlashdata('retornoExito', 'You have updated the Force Account and send an email to the contractor, continue uploading the information.');
        } else {
            $data['status']       = 'error';
            $data['mensaje']      = 'Error!!! Ask for help.';
            $data['idForceaccount'] = '';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Save forceaccount state
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function save_forceaccount_state()
    {
        $post   = $this->request->getPost();
        $idUser = $this->session->get('id');
        $data   = [];

        $data['idForceaccount'] = $post['hddIdWorkOrder'] ?? '';
        $idACS                  = $post['hddIdAcs'] ?? '';
        $status                 = $post['state'] ?? '';
        $msj                    = 'You have added additional information to the Force Account.';

        $arrParam = [
            'idForceaccount' => $data['idForceaccount'],
            'observation'    => $post['information'] ?? '',
            'state'          => $status,
        ];

        if ($this->forceaccountModel->add_forceaccount_state($arrParam, $idUser)) {
            $this->forceaccountModel->update_forceaccount([
                'idForceaccount' => $data['idForceaccount'],
                'state'          => $status,
                'lastMessage'    => $post['information'] ?? '',
            ]);

            if ($status == REVISED && !$idACS) {
                $arrParam2 = ['idForceAccount' => $data['idForceaccount']];
                $info = [
                    'forceaccount'         => $this->forceaccountModel->get_forceaccount_by_idUser($arrParam2),
                    'forceaccountPersonal'  => $this->forceaccountModel->get_forceaccount_personal($arrParam2),
                    'forceaccountMaterials' => $this->forceaccountModel->get_forceaccount_materials($arrParam2),
                    'forceaccountReceipt'   => $this->forceaccountModel->get_forceaccount_receipt($arrParam2),
                    'forceaccountEquipment' => $this->forceaccountModel->get_forceaccount_equipment($arrParam2),
                    'forceaccountOcasional' => $this->forceaccountModel->get_forceaccount_ocasional($arrParam2),
                ];
                $this->forceaccountModel->clone_forceaccount($info);

                $haulingList = $this->generalModel->get_basic_search([
                    'table'  => 'hauling',
                    'order'  => 'id_hauling',
                    'column' => 'fk_id_forceaccount',
                    'id'     => $data['idForceaccount'],
                ]);

                if ($haulingList) {
                    foreach ($haulingList as $hauling) {
                        if ($hauling['state'] != 3) {
                            $this->generalModel->updateRecord([
                                'table'      => 'hauling',
                                'primaryKey' => 'id_hauling',
                                'id'         => $hauling['id_hauling'],
                                'column'     => 'state',
                                'value'      => 2,
                            ]);
                        }
                    }
                }
            }

            $data['status']  = 'success';
            $data['mensaje'] = $msj;
            session()->setFlashdata('retornoExito', $msj);
        } else {
            $data['status']  = 'error';
            $data['mensaje'] = 'Error!!! Ask for help.';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Lista de Forceaccounts filtrado por estado
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    public function wo_by_state($state, $year = 'x')
    {
        $year = ($year === 'x') ? (int) date('Y') : (int) $year;

        $from = date('Y-m-d', mktime(0, 0, 0, 1, 1, $year));
        $to   = date('Y-m-d', mktime(0, 0, 0, 1, 1, $year + 1));

        $arrParam = [
            'from'  => $from,
            'to'    => $to,
            'state' => $state,
        ];

        $idUser = $this->session->get('id');
        $this->forceaccountModel->saveInfoGoBack($arrParam, $idUser);

        $data['forceAccountInfo'] = $this->forceaccountModel->get_forceaccount_by_idJob($arrParam);

        return $this->renderTopOnly('App\Modules\Forceaccount\Views\asign_rate_list', $data);
    }

    /**
     * Generate Force Account Report in PDF
     * @param int $idForceAccount
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function generaForceAccountPDF($idForceAccount)
    {
        $arrParam = ['idForceAccount' => $idForceAccount, 'view_pdf' => true];

        $data['info']                  = $this->forceaccountModel->get_forceaccount_by_idJob($arrParam);
        $data['forceaccountPersonal']  = $this->forceaccountModel->get_forceaccount_personal($arrParam);
        $data['forceaccountMaterials'] = $this->forceaccountModel->get_forceaccount_materials($arrParam);
        $data['forceaccountReceipt']   = $this->forceaccountModel->get_forceaccount_receipt($arrParam);
        $data['forceaccountEquipment'] = $this->forceaccountModel->get_forceaccount_equipment($arrParam);
        $data['forceaccountOcasional'] = $this->forceaccountModel->get_forceaccount_ocasional($arrParam);

        $builder = new PdfBuilder();
        $pdf     = $builder->create('Force Account');

        $html = view('App\Modules\Forceaccount\Views\reporte_force_account', $data);
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->lastPage();

        if (ob_get_length()) {
            ob_end_clean();
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($pdf->Output('force_account_' . $idForceAccount . '.pdf', 'I'));
    }

    /**
     * Generate Force Account Report in XLS
     * @param int    $jobId
     * @param string $from
     * @param string $to
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    public function generaWorkOrderXLS($jobId, $from = '', $to = '')
    {
        // TODO: XLS generation not implemented (generaWorkOrderXLS - uses PhpSpreadsheet)
    }

    /**
     * Search income by JOB CODE
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    public function search_income()
    {
        $data['jobList'] = $this->generalModel->get_basic_search([
            'table'  => 'param_jobs',
            'order'  => 'job_description',
            'column' => 'state',
            'id'     => 1,
        ]);

        $data['noOnfield']    = $this->forceaccountModel->countForceaccounts(['state' => 0]);
        $data['noProgress']   = $this->forceaccountModel->countForceaccounts(['state' => 1]);
        $data['noRevised']    = $this->forceaccountModel->countForceaccounts(['state' => 2]);
        $data['noSend']       = $this->forceaccountModel->countForceaccounts(['state' => 3]);
        $data['noClosed']     = $this->forceaccountModel->countForceaccounts(['state' => 4]);
        $data['noAccounting'] = $this->forceaccountModel->countForceaccounts(['state' => 5]);

        $post = $this->request->getPost();
        if (($post['jobName'] ?? '') && ($post['from'] ?? '') && ($post['to'] ?? '')) {
            $data['idJob'] = $post['jobName'];
            $data['from']  = $post['from'];
            $data['to']    = $post['to'];

            $from = formatear_fecha($data['from']);
            $to   = formatear_fecha($data['to']);

            $data['fromFormat'] = $from;
            $data['toFormat']   = $to;

            $arrParam = ['idJob' => $data['idJob'], 'from' => $from, 'to' => $to];

            $data['noWO']          = $this->forceaccountModel->countForceaccounts($arrParam);
            $data['hoursPersonal'] = $this->forceaccountModel->countHoursPersonal($arrParam);

            $arrParam['table']        = 'forceaccount_personal';
            $data['incomePersonal']   = $this->forceaccountModel->countIncome($arrParam);

            $arrParam['table']       = 'forceaccount_materials';
            $data['incomeMaterial']  = $this->forceaccountModel->countIncome($arrParam);

            $arrParam['table']        = 'forceaccount_equipment';
            $data['incomeEquipment']  = $this->forceaccountModel->countIncome($arrParam);

            $arrParam['table']            = 'forceaccount_ocasional';
            $data['incomeSubcontractor']  = $this->forceaccountModel->countIncome($arrParam);

            $arrParam['table']     = 'forceaccount_receipt';
            $data['incomeReceipt'] = $this->forceaccountModel->countIncome($arrParam);

            $data['total'] = $data['incomePersonal'] + $data['incomeMaterial'] + $data['incomeEquipment'] + $data['incomeSubcontractor'] + $data['incomeReceipt'];

            $data['jobListSearch'] = $this->generalModel->get_basic_search([
                'table'  => 'param_jobs',
                'order'  => 'job_description',
                'column' => 'id_job',
                'id'     => $data['idJob'],
            ]);
        }

        return $this->render('App\Modules\Forceaccount\Views\form_search_income', $data);
    }

    /**
     * Foreman forceaccount view to sign
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    public function foreman_view($id)
    {
        $arrParam = ['idForceAccount' => $id];

        $data['forceaccountPersonal']  = $this->forceaccountModel->get_forceaccount_personal($arrParam);
        $data['forceaccountMaterials'] = $this->forceaccountModel->get_forceaccount_materials($arrParam);
        $data['forceaccountEquipment'] = $this->forceaccountModel->get_forceaccount_equipment($arrParam);
        $data['forceaccountOcasional'] = $this->forceaccountModel->get_forceaccount_ocasional($arrParam);
        $data['information']           = $this->forceaccountModel->get_forceaccount_by_idJob($arrParam);

        return $this->render('App\Modules\Forceaccount\Views\foreman_view', $data);
    }

    /**
     * Foreman info
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    public function foremanInfo()
    {
        $idJob   = $this->request->getPost('idJob');
        $data    = ['result' => true, 'company_id' => '', 'company_name' => '', 'foreman_name' => '', 'foreman_movil' => '', 'foreman_email' => ''];

        $jobInfo = $this->generalModel->get_job(['idJob' => $idJob]);

        $infoForeman = $this->generalModel->get_basic_search([
            'table'  => 'param_company_foreman',
            'order'  => 'id_company_foreman',
            'column' => 'fk_id_job',
            'id'     => $idJob,
        ]);

        $data['company_id']   = $jobInfo[0]['fk_id_company'];
        $data['company_name'] = $jobInfo[0]['company_name'];

        if ($infoForeman) {
            $data['foreman_name']  = $infoForeman[0]['foreman_name'];
            $data['foreman_movil'] = $infoForeman[0]['foreman_movil_number'];
            $data['foreman_email'] = $infoForeman[0]['foreman_email'];
        } elseif ($jobInfo[0]['fk_id_company'] > 0 && $jobInfo[0]['fk_id_company'] !== '') {
            $infoForeman = $this->generalModel->get_basic_search([
                'table'  => 'param_company_foreman',
                'order'  => 'id_company_foreman',
                'column' => 'fk_id_param_company',
                'id'     => $jobInfo[0]['fk_id_company'],
            ]);
            if ($infoForeman) {
                $data['foreman_name']  = $infoForeman[0]['foreman_name'];
                $data['foreman_movil'] = $infoForeman[0]['foreman_movil_number'];
                $data['foreman_email'] = $infoForeman[0]['foreman_email'];
            }
        }

        return $this->response->setJSON($data);
    }

    /**
     * Envio de mensaje SMS al Foreman
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function sendSMSForeman($idForceAccount)
    {
        $smsService  = new \App\Libraries\SmsService();
        $information = $this->forceaccountModel->get_forceaccount_by_idJob(['idForceAccount' => $idForceAccount]);

        if (empty($information) || empty($information[0]['foreman_movil_number_wo'])) {
            session()->setFlashdata('retornoError', '<strong>Error!</strong> Foreman phone number not found.');
            return redirect()->to(base_url('forceaccount/add_forceaccount/' . $idForceAccount));
        }

        $info    = $information[0];
        $mensaje = date('F j, Y', strtotime($info['date']));
        $mensaje .= "\n" . $info['job_description'];
        $mensaje .= "\n" . $info['observation'];
        $mensaje .= "\nClick the following link to review W.O. " . $idForceAccount;
        $mensaje .= "\n\n" . base_url('forceaccount/foreman_view/' . $idForceAccount);

        $to = '+1' . $info['foreman_movil_number_wo'];

        try {
            $smsService->send($to, $mensaje);
            session()->setFlashdata('retornoExito', 'We have sent the SMS to the foreman to sign the Force Account.');
        } catch (\Exception $e) {
            session()->setFlashdata('retornoError', '<strong>Error!</strong> SMS could not be sent: ' . $e->getMessage());
        }

        return redirect()->to(base_url('forceaccount/add_forceaccount/' . $idForceAccount));
    }

    /**
     * Load prices WO
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function load_prices_wo()
    {
        $idWO  = $this->request->getPost('identificador');
        $data  = ['idWO' => $idWO, 'result' => true];

        $info  = $this->forceaccountModel->get_forceaccount_by_idJob(['idForceAccount' => $idWO]);
        $idJob = $info[0]['fk_id_job'];

        $personalRate  = $this->forceaccountModel->get_forceaccount_personal_prices($idWO, $idJob);
        $equipmentRate = $this->forceaccountModel->get_forceaccount_equipment_prices($idWO, $idJob);
        $materialRate  = $this->forceaccountModel->get_forceaccount_material_prices($idWO);

        $data['status']  = 'success';
        if ($personalRate) {
            if ($this->forceaccountModel->update_wo_personal_rate($personalRate)) {
                session()->setFlashdata('retornoExito', 'You have loaded the data.');
            } else {
                $data['status']  = 'error';
                session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
            }
        }

        if ($equipmentRate) {
            if ($this->forceaccountModel->update_wo_equipment_rate($equipmentRate)) {
                session()->setFlashdata('retornoExito', 'You have loaded the data.');
            } else {
                $data['status']  = 'error';
                session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
            }
        }

        if ($materialRate) {
            if ($this->forceaccountModel->update_wo_material_rate($materialRate)) {
                session()->setFlashdata('retornoExito', 'You have loaded the data.');
            } else {
                $data['status']  = 'error';
                session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
            }
        }

        return $this->response->setJSON($data);
    }

    /**
     * Cargo modal - formulario de captura Invoice
     * @since 16/04/2025
     * @review 19/05/2026 - new CI4 version
     */
    public function cargarModalReceipts()
    {
        $idForceaccount         = $this->request->getPost('idForceaccount');
        $porciones              = explode('-', $idForceaccount);
        $data['idForceaccount'] = $porciones[1];

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\Forceaccount\Views\modal_receipt', $data));
    }

    /**
     * Actualizar info de invoice
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function update_receipt()
    {
        $post           = $this->request->getPost();
        $idUser         = $this->session->get('id');
        $idForceaccount = $post['hddidForceaccount'] ?? '';
        $view           = $post['view'] ?? 'add_forceaccount';

        if ($this->forceaccountModel->saveReceipt($post, $idUser)) {
            session()->setFlashdata('retornoExito', 'You have updated the information!!');
        } else {
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return redirect()->to(base_url('forceaccount/' . $view . '/' . $idForceaccount));
    }

    /**
     * Load markup WO
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function load_markup_wo()
    {
        $idWO  = $this->request->getPost('identificador');
        $data  = ['idWO' => $idWO, 'result' => true];

        $arrParam          = ['idForceAccount' => $idWO];
        $infoWO            = $this->forceaccountModel->get_forceaccount_by_idJob($arrParam);
        $forceaccountMaterials  = $this->forceaccountModel->get_forceaccount_materials($arrParam);
        $forceaccountReceipt    = $this->forceaccountModel->get_forceaccount_receipt($arrParam);
        $forceaccountOcasional  = $this->forceaccountModel->get_forceaccount_ocasional($arrParam);
        $markup            = $infoWO[0]['markup'];

        $data['status']  = 'success';
        if ($forceaccountReceipt) {
            if ($this->forceaccountModel->update_wo_invoice_markup($forceaccountReceipt, $markup)) {
                session()->setFlashdata('retornoExito', 'You have loaded the data.');
            } else {
                $data['status']  = 'error';
                session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
            }
        }

        if ($forceaccountMaterials) {
            if ($this->forceaccountModel->update_wo_material_markup($forceaccountMaterials, $markup)) {
                session()->setFlashdata('retornoExito', 'You have loaded the data.');
            } else {
                $data['status']  = 'error';
                session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
            }
        }

        if ($forceaccountOcasional) {
            if ($this->forceaccountModel->update_wo_ocasional_markup($forceaccountOcasional, $markup)) {
                session()->setFlashdata('retornoExito', 'You have loaded the data.');
            } else {
                $data['status']  = 'error';
                session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
            }
        }

        return $this->response->setJSON($data);
    }

    /**
     * Cambio de estado de las WO
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    public function update_wo_state()
    {
        $post  = $this->request->getPost();
        $idUser = $this->session->get('id');
        $data  = [];

        $wo = $post['wo'] ?? '';
        if ($wo) {
            if ($this->forceaccountModel->updateWOState($post, $idUser)) {
                $data['status'] = 'success';
                session()->setFlashdata('retornoExito', 'You have updated the state!!');
            } else {
                $data['status'] = 'error';
                session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
            }
        } else {
            $data['status']  = 'error';
            $data['mensaje'] = 'You have to select a W.O.';
            session()->setFlashdata('retornoError', 'You have to select a W.O.');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Cargo modal - formulario de captura Expense
     * @since 16/04/2025
     * @review 07/05/2026 - new CI4 version
     */
    public function cargarModalExpense()
    {
        $data['idForceaccount'] = $this->request->getPost('idForceaccount');

        $info           = $this->forceaccountModel->get_forceaccount_by_idJob(['idForceAccount' => $data['idForceaccount']]);
        $data['idJob']  = $info[0]['fk_id_job'];
        $arrParam       = ['idJob' => $data['idJob']];

        $data['information']  = $info;
        $data['chapterList']  = $this->generalModel->get_chapter_list($arrParam);
        $data['sumPercentage'] = $this->generalModel->sumPercentageByJob($arrParam);

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\Forceaccount\Views\modal_expense', $data));
    }

    /**
     * Recalculate Expenses
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    public function recalculate_expenses()
    {
        $idForceaccount = $this->request->getPost('identificador');
        $data           = ['idWO' => $idForceaccount];

        $info  = $this->forceaccountModel->get_forceaccount_by_idJob(['idForceAccount' => $idForceaccount]);
        $idJob = $info[0]['fk_id_job'];

        if ($this->update_wo_expenses_values($idForceaccount, $idJob)) {
            $data['status'] = 'success';
            session()->setFlashdata('retornoExito', 'The information was saved successfully!!');
        } else {
            $data['status'] = 'error';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Attachment List
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    public function attachmentList()
    {
        $lista = $this->generalModel->get_attachments_by_equipment([
            'idEquipment' => $this->request->getPost('equipmentId'),
        ]);

        $html = '';
        if ($lista) {
            $html = "<option value=''>Select...</option>";
            foreach ($lista as $fila) {
                $html .= "<option value='" . esc($fila['id_attachment']) . "'>" . esc($fila['attachment_number']) . ' - ' . esc($fila['attachment_description']) . '</option>';
            }
        }

        return $this->response->setContentType('text/html')->setBody($html);
    }

    /**
     * LOG Forceaccounts
     * @since 16/04/2025
     * @author FOROZCO
     * @review 07/05/2026 - new CI4 version
     */
    public function log($goBack = 'x')
    {
        $idUser = $this->session->get('id');

        $data['jobList'] = $this->generalModel->get_basic_search([
            'table'  => 'param_jobs',
            'order'  => 'job_description',
            'column' => 'state',
            'id'     => 1,
        ]);

        $data['user'] = $this->generalModel->get_basic_search([
            'table' => 'user',
            'order' => 'id_user',
            'id'    => 'x',
        ]);

        $data['noOnfield']  = $this->forceaccountModel->countForceaccounts(['state' => 0]);
        $data['noProgress'] = $this->forceaccountModel->countForceaccounts(['state' => 1]);
        $data['noRevised']  = $this->forceaccountModel->countForceaccounts(['state' => 2]);
        $data['noSend']     = $this->forceaccountModel->countForceaccounts(['state' => 3]);
        $data['noClosed']   = $this->forceaccountModel->countForceaccounts(['state' => 4]);

        if ($goBack === 'y') {
            $forceAccountGoBackInfo = $this->forceaccountModel->get_forceaccount_go_back($idUser);

            if (!$forceAccountGoBackInfo) {
                return redirect()->to(base_url('forceaccount'));
            }

            $to = date('Y-m-d', strtotime('+1 day', strtotime(formatear_fecha($forceAccountGoBackInfo['post_to']))));

            $arrParam = [
                'jobId'              => $forceAccountGoBackInfo['post_id_job'],
                'idForceAccount'     => $forceAccountGoBackInfo['post_id_work_order'],
                'idForceAccountFrom' => $forceAccountGoBackInfo['post_id_wo_from'],
                'idForceAccountTo'   => $forceAccountGoBackInfo['post_id_wo_to'],
                'from'               => $forceAccountGoBackInfo['post_from'],
                'to'                 => $to,
                'state'              => $forceAccountGoBackInfo['post_state'],
            ];

            $data['forceAccountInfo'] = $this->forceaccountModel->get_forceaccount_by_idJob($arrParam);

            return $this->renderTopOnly('App\Modules\Forceaccount\Views\asign_rate_list', $data);
        }

        $post = $this->request->getPost();
        if (($post['jobName'] ?? '') || ($post['user'] ?? '') || ($post['from'] ?? '') || ($post['forceAccountNumber'] ?? '')) {
            $data['jobName']          = $post['jobName'] ?? '';
            $data['forceAccountNumber'] = $post['forceAccountNumber'] ?? '';
            $data['user']             = $post['user'] ?? '';
            $data['from']             = $post['from'] ?? '';
            $data['to']               = $post['to'] ?? '';

            $to   = $data['to']   ? date('Y-m-d', strtotime('+1 day', strtotime(formatear_fecha($data['to'])))) : '';
            $from = $data['from'] ? formatear_fecha($data['from']) : '';

            $arrParam = [
                'jobId'          => $post['jobName'] ?? '',
                'idForceAccount' => $post['forceAccountNumber'] ?? '',
                'userId'         => $post['user'] ?? '',
                'from'           => $from,
                'to'             => $to,
            ];

            $data['forceAccountInfo'] = $this->forceaccountModel->get_forceaccount_log($arrParam);

            return $this->renderTopOnly('App\Modules\Forceaccount\Views\log_list', $data);
        }

        return $this->render('App\Modules\Forceaccount\Views\job_search', $data);
    }

    /**
     * View forceaccount_expenses
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function forceaccount_expenses($idForceAccount)
    {
        $arrParam = ['idForceAccount' => $idForceAccount];

        $data['information']         = $this->forceaccountModel->get_forceaccount_by_idJob($arrParam);
        $data['forceaccountPersonal']  = $this->forceaccountModel->get_forceaccount_personal($arrParam);
        $data['forceaccountMaterials'] = $this->forceaccountModel->get_forceaccount_materials($arrParam);
        $data['forceaccountReceipt']   = $this->forceaccountModel->get_forceaccount_receipt($arrParam);
        $data['forceaccountEquipment'] = $this->forceaccountModel->get_forceaccount_equipment($arrParam);
        $data['forceaccountOcasional'] = $this->forceaccountModel->get_forceaccount_ocasional($arrParam);
        $data['forceaccountExpenses']  = $this->forceaccountModel->get_forceaccount_expense($arrParam);

        $data['jobDetails'] = $this->generalModel->get_job_detail([
            'idJob'  => $data['information'][0]['fk_id_job'],
            'status' => 1,
        ]);

        return $this->render('App\Modules\Forceaccount\Views\expenses', $data);
    }

    /**
     * Save WO Expenses
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function save_fa_expenses()
    {
        $post           = $this->request->getPost();
        $idUser         = $this->session->get('id');
        $idForceAccount = $post['hddidForceaccount'] ?? '';
        $idJob          = $post['hddidJob'] ?? '';

        if ($this->forceaccountModel->saveFAExpenses($post, $idUser)) {
            $this->generalModel->updateRecord([
                'table'      => 'param_jobs',
                'primaryKey' => 'id_job',
                'id'         => $idJob,
                'column'     => 'flag_expenses',
                'value'      => 1,
            ]);
            $this->generalModel->updateRecord([
                'table'      => 'forceaccount',
                'primaryKey' => 'id_forceaccount',
                'id'         => $idForceAccount,
                'column'     => 'expenses_flag',
                'value'      => 1,
            ]);
            session()->setFlashdata('retornoExito', 'You have updated the Force Account Expenses!!');
        } else {
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return redirect()->to(base_url('forceaccount/forceaccount_expenses/' . $idForceAccount));
    }

    /**
     * Delete Expenses
     * @param string $subModule
     * @param int    $idForceaccountExpenses
     * @param int    $idSubmodule
     * @param int    $idForceAccount
     * @since 16/04/2025
     * @review 19/05/2026 - new CI4 version
     */
    public function deleteRecordExpenses($subModule, $idForceaccountExpenses, $idSubmodule, $idForceAccount)
    {
        if (empty($subModule) || empty($idForceaccountExpenses) || empty($idForceAccount)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ERROR!!! - You are in the wrong place.');
        }

        if ($this->generalModel->deleteRecord([
            'table'      => 'forceaccount_expense',
            'primaryKey' => 'id_forceaccount_expense',
            'id'         => $idForceaccountExpenses,
        ])) {
            $info  = $this->forceaccountModel->get_forceaccount_by_idJob(['idForceAccount' => $idForceAccount]);
            $idJob = $info[0]['fk_id_job'];
            $this->update_wo_expenses_values($idForceAccount, $idJob);

            $table = 'forceaccount_' . $subModule;
            $this->generalModel->updateRecord([
                'table'      => $table,
                'primaryKey' => 'id_forceaccount_' . $subModule,
                'id'         => $idSubmodule,
                'column'     => 'flag_expenses',
                'value'      => 0,
            ]);

            session()->setFlashdata('retornoExito', 'You have deleted one record from <strong>' . $table . '</strong> table.');
        } else {
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return redirect()->to(base_url('forceaccount/forceaccount_expenses/' . $idForceAccount));
    }

    /**
     * Update WO Expenses Values
     * @since 16/04/2025
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    private function update_wo_expenses_values($idForceaccount, $idJob)
    {
        $arrParam = ['idForceAccount' => $idForceaccount, 'idJob' => $idJob];

        $this->forceaccountModel->get_forceaccount_expense(['idForceAccount' => $idForceaccount]);
        $sumPercentageExpense = $this->forceaccountModel->sumPercentageExpense(['idForceAccount' => $idForceaccount]);

        $arrParam['table'] = 'forceaccount_personal';
        $incomePersonal    = $this->forceaccountModel->countIncome($arrParam);

        $arrParam['table'] = 'forceaccount_materials';
        $incomeMaterial    = $this->forceaccountModel->countIncome($arrParam);

        $arrParam['table'] = 'forceaccount_equipment';
        $incomeEquipment   = $this->forceaccountModel->countIncome($arrParam);

        $arrParam['table']     = 'forceaccount_ocasional';
        $incomeSubcontractor   = $this->forceaccountModel->countIncome($arrParam);

        $arrParam['table'] = 'forceaccount_receipt';
        $incomeReceipt     = $this->forceaccountModel->countIncome($arrParam);

        $totalWOIncome = $incomePersonal + $incomeMaterial + $incomeEquipment + $incomeSubcontractor + $incomeReceipt;

        if ($totalWOIncome > 0 && $sumPercentageExpense) {
            $this->generalModel->updateRecord([
                'table'      => 'param_jobs',
                'primaryKey' => 'id_job',
                'id'         => $idJob,
                'column'     => 'flag_expenses',
                'value'      => 1,
            ]);
        }

        return true;
    }
}
