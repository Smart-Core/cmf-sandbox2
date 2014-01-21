<?php

namespace SmartCore\Module\Menu\Controller;

use SmartCore\Bundle\CMSBundle\Response;

class MenuController extends Controller
{
    public function indexAction()
    {
        $current_folder_path = $this->get('cms.context')->getCurrentFolderPath();

        $cache_key = md5('smart_module_menu' . $current_folder_path . $this->node->getId());

        $cache = $this->getCacheService();

        if (false == $this->view->menu = $cache->get($cache_key)) {
            // Хак для Menu\RequestVoter
            $this->get('request')->attributes->set('__selected_inheritance', $this->selected_inheritance);
            $this->get('request')->attributes->set('__current_folder_path', $current_folder_path);

            $this->view->menu = $this->renderView('MenuModule::menu.html.twig', [
                'css_class'     => $this->css_class,
                'current_class' => '',
                'depth'         => $this->depth,
                'group'         => $this->getDoctrine()->getManager()->find('MenuModule:Group', $this->group_id),
            ]);

            $cache->set($cache_key, $this->view->menu, ['smart_module_menu', 'folder', 'node_'.$this->node->getId()]);

            $this->get('request')->attributes->remove('__selected_inheritance');
            $this->get('request')->attributes->remove('__current_folder_path');
        }

        $response = new Response($this->view);

        if ($this->isEip()) {
            $this->setFrontControls($response);
        }

        return $response;
    }

    protected function setFrontControls(Response $response)
    {
        $response->setFrontControls([
            'edit' => [
                'title' => 'Редактировать',
                'descr' => 'Пункты меню',
                'uri'   => $this->generateUrl('cms_admin_node_w_slug', [
                        'id'   => $this->node->getId(),
                        'slug' => $this->group_id,
                    ]),
                'overlay' => false,
                'default' => false,
            ],
        ]);
    }
}
