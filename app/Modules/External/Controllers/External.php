<?php
namespace App\Modules\External\Controllers;

use App\Controllers\BaseController;
use App\Modules\External\Models\ExternalModel;
use App\Models\GeneralModel;

class External extends BaseController
{
    protected $externalModel;
    protected $generalModel;

    public function __construct()
    {
        $this->externalModel = new ExternalModel();
        $this->generalModel  = new GeneralModel();
    }

    /**
     * Envio de mensaje para firmar FLHA
     * @since 14/4/2021
     * @author BMOTTAG
     * @review 08/05/2026 - new CI4 version
     */
    public function sendSMSFLHAWorker($idSafety, $idSafetySubcontractor = 'x')
    {
        $smsService   = new \App\Libraries\SmsService();

        // 🔹 Safety info
        $infoSafety = $this->generalModel->get_safety(['idSafety' => $idSafety]);

        $workers = $this->generalModel->get_safety_subcontractors_workers([
            'idSafety' => $idSafety,
            'movilNumber' => true,
            'idSafetySubcontractor' => $idSafetySubcontractor
        ]);

        if (empty($workers)) {
            session()->setFlashdata('retornoError', 'No workers found');
            return redirect()->to(base_url('safety/review_flha/' . $idSafety));
        }

        // 🔹 Mensaje
        $mensaje  = "FLHA App - Lev West - " . date('F j, Y', strtotime($infoSafety[0]['date']));
        $mensaje .= "\n" . $infoSafety[0]['job_description'];
        $mensaje .= "\nFollow the link, read the FLHA and sign.";
        $mensaje .= "\n\n" . base_url("safety/review_flha/" . $idSafety);

        // 🔹 Números
        $numbers = array_map(function ($w) {
            return '+1' . $w['worker_movil_number'];
        }, $workers);

        // 🔹 Enviar SMS
        try {
            $smsService->sendBulk($numbers, $mensaje);
            session()->setFlashdata('retornoExito', 'You have send the SMS to Subcontractors.');
        } catch (\Exception $e) {
            session()->setFlashdata('retornoError', '<strong>Error!</strong> SMS could not be sent: ' . $e->getMessage());
        }

		return redirect()->to(base_url('safety/review_flha/' . $idSafety));
    }

    /**
     * Envio de mensaje para firmar - Excavation and Trenching Plan
     * @since 14/4/2021
     * @author BMOTTAG
     * @review 08/05/2026 - new CI4 version
     */
    public function sendSMSExcavationWorker($idExcavation, $idSubcontractor = 'x')
    {
        $smsService   = new \App\Libraries\SmsService();

        $information = $this->generalModel->get_excavation(['idExcavation'  => $idExcavation]);

        $workers = $this->generalModel->get_excavation_subcontractors([
            'idExcavation'  => $idExcavation,
            'movilNumber'   => true,
            'idSubcontractor' => $idSubcontractor,
        ]);

        if (empty($workers)) {
            session()->setFlashdata('retornoError', 'No workers found');
            return redirect()->to(base_url('jobs/review_excavation/' . $idExcavation));
        }

        // 🔹 Mensaje
        $mensaje  = 'Excavation and Trenching Plan App - Lev West - ' . date('F j, Y', strtotime($information[0]['date_excavation']));
        $mensaje .= "\n" . $information[0]['job_description'];
        $mensaje .= "\nFollow the link, read the Excavation and Trenching Plan and sign.";
        $mensaje .= "\n\n" . base_url('jobs/review_excavation/' . $idExcavation);

        // 🔹 Números
        $numbers = array_map(function ($w) {
            return '+1' . $w['worker_movil_number'];
        }, $workers);

        // 🔹 Enviar SMS
        try {
            $smsService->sendBulk($numbers, $mensaje);
            session()->setFlashdata('retornoExito', 'You have send the SMS to Subcontractors.');
        } catch (\Exception $e) {
            session()->setFlashdata('retornoError', '<strong>Error!</strong> SMS could not be sent: ' . $e->getMessage());
        }

		return redirect()->to(base_url('jobs/review_excavation/' . $idExcavation));
    }

    /**
     * Add an employee
     * @since 31/1/2022
     * @author BMOTTAG
     * @review 08/05/2026 - new CI4 version
     */
    public function newEmployee($key)
    {
        $filtro = 'uiAqv828TZr';
        if($filtro != $key) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Access denied.');
        }

        return $this->renderTopOnly('App\Modules\External\Views\form_employee', []);
    }

    /**
     * Save new employee
     * @since 31/01/2022
     * @author BMOTTAG
     * @review 08/05/2026 - new CI4 version
     */
    public function saveEmployee()
    {
        $post  = $this->request->getPost();
        $data  = [];

        $logUser = $post['user'] ?? '';
        $email   = $post['email'] ?? '';

        $resultUser  = $this->generalModel->verifyUser(['column' => 'log_user', 'value' => $logUser]);
        $resultEmail = $this->generalModel->verifyUser(['column' => 'email', 'value' => $email]);

        if ($resultUser || $resultEmail) {
            $data['status'] = 'error';

            if ($resultUser && $resultEmail) {
                $data['message'] = ' Error. The user name and email already exist.';
            } elseif ($resultUser) {
                $data['message'] = ' Error. The user name already exist.';
            } else {
                $data['message'] = ' Error. The email already exist.';
            }
        } else {
            if ($this->externalModel->saveEmployee($post)) {
                $passwd = str_replace(['<', '>', '[', ']', '*', '^', '-', "'", '='], '', $post['inputPassword'] ?? '');
                $firstName = $post['firstName'] ?? '';

                $msj  = 'Thank you for registering, an email was sent with the access data to the system.';
                $msj .= '<br><br><strong>User name: </strong>' . esc($logUser);
                $msj .= '<br><strong>Password: </strong>' . esc($passwd);

                //Email sending
                $emailService = new \App\Libraries\EmailService();

                $result = $emailService->sendTemplate(
                    $email,
                    'Welcome to Lev West',
                    'emails/welcome',
                    [
                        'name' => $firstName,
                        'link' => base_url(),
                        'logUser' => $logUser,
                        'password' => $passwd
                    ]
                );

                if ($result !== true) {
                    log_message('error', $result);
                }

                $data['status'] = 'success';
                $this->session->setFlashdata('retornoExito', $msj);
            } else {
                $data['status'] = 'error';
            }
        }

        return $this->response->setJSON($data);
    }

    /**
     * Form Checkin
     * @since 30/5/2022
     * @author BMOTTAG
     * @review 08/05/2026 - new CI4 version
     */
    public function checkin($idProject, $idCheckin = 'x')
    {
        $data['information'] = false;
        $data['idCheckin']   = false;

        $data['workers'] = $this->generalModel->get_basic_search([
            'table' => 'new_workers',
            'order' => 'worker_name',
            'id'    => 'x',
        ]);

        $data['jobInfo'] = $this->generalModel->get_basic_search([
            'table'  => 'param_jobs',
            'order'  => 'id_job',
            'column' => 'id_job',
            'id'     => $idProject,
        ]);

        $data['checkinList'] = $this->generalModel->get_checkin([
            'today' => date('Y-m-d'),
            'idJob' => $idProject,
        ]);

        if ($idCheckin !== 'x') {
            $data['idCheckin']   = $idCheckin;
            $data['checkinList'] = $this->generalModel->get_checkin(['idCheckin' => $idCheckin]);
        }

        return $this->renderTopOnly('App\Modules\External\Views\form_checkin', $data);
    }

    /**
     * Save Checkin
     * @since 1/6/2022
     * @author BMOTTAG
     * @review 08/05/2026 - new CI4 version
     */
    public function saveCheckin()
    {
        $post        = $this->request->getPost();
        $data        = [];
        $loginBefore = $post['login_before'] ?? '';
        $idWorker    = $post['id_name'] ?? null;
        $idJob       = $post['idProject'] ?? null;

        if ($loginBefore == 1) {
            if ($idCheckin = $this->externalModel->saveCheckin((int) $idWorker, $post)) {
                $data['status']   = 'success';
                $data['idCheckin'] = $idJob . '/' . $idCheckin;
                $this->session->setFlashdata('retornoExito', 'Welcome, work safe!');
            } else {
                $data['status']  = 'error';
                $this->session->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
            }
        } else {
            if ($idWorker = $this->externalModel->saveNewWorker($post)) {
                if ($idCheckin = $this->externalModel->saveCheckin($idWorker, $post)) {
                    $data['status']   = 'success';
                    $data['idCheckin'] = $idJob . '/' . $idCheckin;
                    $this->session->setFlashdata('retornoExito', 'Welcome, work safe!');
                } else {
                    $data['status']  = 'error';
                    $this->session->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
                }
            } else {
                $data['status']  = 'error';
                $this->session->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
            }
        }

        return $this->response->setJSON($data);
    }

    /**
     * Cargo modal - formulario checkout
     * @since 4/06/2022
     * @review 08/05/2026 - new CI4 version
     */
    public function cargarModalCheckout()
    {
        $data['information'] = false;
        $data['idCheckin']   = $this->request->getPost('idCheckin');

        if ($data['idCheckin'] !== 'x') {
            $data['information'] = $this->generalModel->get_checkin(['idCheckin' => $data['idCheckin']]);
        }

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\External\Views\checkout_modal', $data));
    }

    /**
     * Update checkin - checkout
     * @since 4/06/2022
     * @author BMOTTAG
     * @review 08/05/2026 - new CI4 version
     */
    public function saveCheckout()
    {
        $post    = $this->request->getPost();
        $idJob   = $post['idProject'] ?? null;
        $idCheckin = $post['hddId'] ?? null;
        $data    = ['idCheckin' => $idJob . '/' . $idCheckin];

        if ($this->externalModel->saveCheckout($post)) {
            $data['status']   = 'success';
            $this->session->setFlashdata('retornoExito', 'Thanks for coming, have a good day!');
        } else {
            $data['status'] = 'error';
            $this->session->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * List Day Off, for ADMIN
     * @since 27/12/2022
     * @author BMOTTAG
     * @review 08/05/2026 - new CI4 version
     */
    public function aproveDayOff($idDayoff, $idUser)
    {
        $data['idDayoff']    = $idDayoff;
        $data['idUser']      = $idUser;
        $data['dayOffInfo']  = $this->generalModel->get_day_off($data);

        if (!$data['dayOffInfo']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Day off not found.');
        }

        return $this->renderTopOnly('App\Modules\External\Views\dayoff', $data);
    }

    /**
     * Update dayoff status
     * @since 27/12/2022
     * @author BMOTTAG
     * @review 08/05/2026 - new CI4 version
     */
    public function updateDayoffStatus()
    {
        $post   = $this->request->getPost();
        $data   = ['return' => ($post['hddIdDayOff'] ?? '') . '/' . ($post['hddIdUser'] ?? '')];

        if ($this->externalModel->update_dayoff($post)) {
            $data['status'] = 'success';
            $this->session->setFlashdata('retornoExito', 'Information saved successfully!!');
        } else {
            $data['status'] = 'error';
            $this->session->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }
}
