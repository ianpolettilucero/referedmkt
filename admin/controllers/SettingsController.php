<?php
namespace Admin\Controllers;

use Core\Flash;
use Core\Settings;

final class SettingsController extends BaseController
{
    /**
     * Keys expuestas al form. value = default si no existe.
     */
    private const KEYS = [
        'newsletter_enabled'           => '0',
        'newsletter_heading'           => 'Suscribite al newsletter',
        'newsletter_description'       => 'Recibí guías nuevas y análisis sin spam.',
        'newsletter_button_text'       => 'Suscribirme',
        'newsletter_action_url'        => '',
        'newsletter_email_field_name'  => 'email',
        'newsletter_hidden_fields_json'=> '',
        'newsletter_success_message'   => 'Listo. Revisá tu email para confirmar.',
    ];

    public function index(): void
    {
        $site = $this->requireSite();
        $values = [];
        foreach (self::KEYS as $k => $default) {
            $values[$k] = Settings::get((int)$site['id'], $k, $default);
        }
        $this->render('settings/form', [
            'values'     => $values,
            'page_title' => 'Settings del sitio',
        ]);
    }

    public function update(): void
    {
        $this->requireCsrf();
        $site = $this->requireSite();

        foreach (self::KEYS as $k => $_) {
            $v = (string)$this->input($k, '');
            $v = trim($v);
            // Validaciones especificas.
            if ($k === 'newsletter_enabled') {
                $v = $this->boolInput('newsletter_enabled') ? '1' : '0';
            }
            if ($k === 'newsletter_hidden_fields_json' && $v !== '') {
                $decoded = json_decode($v, true);
                if (!is_array($decoded)) {
                    Flash::error('newsletter_hidden_fields_json no es JSON valido. Ejemplo: {"form_id":"123"}');
                    $this->redirect('/admin/settings');
                    return;
                }
            }
            if ($k === 'newsletter_action_url' && $v !== '' && !filter_var($v, FILTER_VALIDATE_URL)) {
                Flash::error('newsletter_action_url debe ser una URL valida.');
                $this->redirect('/admin/settings');
                return;
            }
            Settings::set((int)$site['id'], $k, $v);
        }
        Flash::success('Settings guardados.');
        $this->redirect('/admin/settings');
    }
}
