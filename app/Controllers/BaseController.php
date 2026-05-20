<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{

    protected $session;
    protected $menu;

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);

        $this->session = session();

        // 🔥 CARGAR MENÚ UNA SOLA VEZ	
		if ($this->session->get('rol')) {
			$this->menu = $this->prepareMenu();
		}
    }

    /**
     * Prepara los datos del menú
     */
    protected function prepareMenu()
    {
        $userRol = $this->session->get('rol');

        $menuData = [
            'leftMenu' => [],
            'topMenu' => []
        ];

        // Left Menu
        $itemsLeftMenu = $this->generalModel->get_role_menu([
            'idRole' => $userRol,
            'menuType' => 1,
            'menuState' => 1
        ]);

		$menuIndex = [];

		if ($itemsLeftMenu) {
			foreach ($itemsLeftMenu as $item) {

				$menuId = $item['fk_id_menu'];

				// 🔹 Si el menú NO existe aún → lo creamos
				if (!isset($menuIndex[$menuId])) {

					$links = [];

					if (!$item['menu_url']) {
						$links = $this->generalModel->get_role_access([
							'idRole' => $userRol,
							'idMenu' => $menuId,
							'linkState' => 1,
							'menuType' => 1
						]);
					}

					$menuIndex[$menuId] = [
						'name' => $item['menu_name'],
						'icon' => $item['menu_icon'],
						'url'  => $item['menu_url'] ? base_url($item['menu_url']) : null,
						'links'=> $links
					];
				}

			}
		}

		$menuData['leftMenu'] = array_values($menuIndex);

		// Top Menu
		$itemsTopMenu = $this->generalModel->get_role_menu([
			'idRole' => $userRol,
			'menuType' => 2,
			'menuState' => 1
		]);

		$topIndex = [];

		if ($itemsTopMenu) {
			foreach ($itemsTopMenu as $item) {

				$menuId = $item['fk_id_menu'];

				if (!isset($topIndex[$menuId])) {

					$links = [];

					if (!$item['menu_url']) {
						$links = $this->generalModel->get_role_access([
							'idRole' => $userRol,
							'idMenu' => $menuId,
							'linkState' => 1,
							'menuType' => 2
						]);
					}

					$topIndex[$menuId] = [
						'name'  => $item['menu_name'],
						'icon'  => $item['menu_icon'],
						'url'   => $item['menu_url'] ? base_url($item['menu_url']) : null,
						'links' => $links
					];
				}
			}
		}

		$menuData['topMenu'] = array_values($topIndex);

        return $menuData;
    }

    // 🔥 Helper para renderizar vistas
    protected function render($view, $data = [])
    {
		$data['leftMenu'] = $this->menu['leftMenu'] ?? [];
		$data['topMenu'] = $this->menu['topMenu'] ?? [];
        $data['view']     = $view;

        return view('layout', $data);
    }

    protected function renderTopOnly($view, $data = [])
    {
		$data['topMenu'] = $this->menu['topMenu'] ?? [];
        $data['view']     = $view;

        return view('layout_top_only', $data);
    }
}
