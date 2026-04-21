<?php
namespace Admin\Controllers;

use Core\Auth;
use Core\Database;
use Core\Flash;
use Core\Security;

/**
 * Panel de seguridad del admin: monitoreo + gestion de IPs banned/whitelist
 * + log de eventos. Solo superadmin.
 */
final class SecurityController extends BaseController
{
    public function index(): void
    {
        $this->requireSuperadmin();

        $db = Database::instance();

        // Stats de overview
        $now = time();
        $activeBans = (int)$db->fetchColumn(
            'SELECT COUNT(*) FROM banned_ips
             WHERE expires_at IS NULL OR expires_at > NOW()'
        );
        $whitelistCount = (int)$db->fetchColumn('SELECT COUNT(*) FROM ip_whitelist');
        $failedLogins24h = (int)$db->fetchColumn(
            "SELECT COUNT(*) FROM security_events
             WHERE event_type = 'login_fail' AND created_at >= (NOW() - INTERVAL 24 HOUR)"
        );
        $blockedReq24h = (int)$db->fetchColumn(
            "SELECT COUNT(*) FROM security_events
             WHERE event_type = 'blocked_request' AND created_at >= (NOW() - INTERVAL 24 HOUR)"
        );
        $csrfFails24h = (int)$db->fetchColumn(
            "SELECT COUNT(*) FROM security_events
             WHERE event_type = 'csrf_fail' AND created_at >= (NOW() - INTERVAL 24 HOUR)"
        );

        // IPs baneadas activas
        $bans = $db->fetchAll(
            "SELECT b.*, u.name AS banned_by_name
             FROM banned_ips b
             LEFT JOIN users u ON u.id = b.banned_by
             WHERE b.expires_at IS NULL OR b.expires_at > NOW()
             ORDER BY b.banned_at DESC
             LIMIT 200"
        );

        // Whitelist
        $whitelist = $db->fetchAll(
            "SELECT w.*, u.name AS added_by_name
             FROM ip_whitelist w
             LEFT JOIN users u ON u.id = w.added_by
             ORDER BY w.created_at DESC"
        );

        // Events recientes
        $filter = (string)$this->input('filter', '');
        $validFilters = ['login_fail','login_success','logout','auto_ban','manual_ban','unban','whitelist_add','whitelist_remove','blocked_request','csrf_fail','suspicious',''];
        if (!in_array($filter, $validFilters, true)) { $filter = ''; }

        $eventsSql = "SELECT e.*, u.name AS user_name
                      FROM security_events e
                      LEFT JOIN users u ON u.id = e.user_id";
        $eventsParams = [];
        if ($filter !== '') {
            $eventsSql .= ' WHERE e.event_type = :t';
            $eventsParams['t'] = $filter;
        }
        $eventsSql .= ' ORDER BY e.created_at DESC LIMIT 200';
        $events = $db->fetchAll($eventsSql, $eventsParams);

        // Top IPs atacantes (con fails)
        $topAttackers = $db->fetchAll(
            "SELECT ip_address, COUNT(*) AS fails,
                    MAX(created_at) AS last_seen,
                    GROUP_CONCAT(DISTINCT email ORDER BY email SEPARATOR ', ') AS emails_tried
             FROM security_events
             WHERE event_type = 'login_fail'
               AND created_at >= (NOW() - INTERVAL 7 DAY)
               AND ip_address IS NOT NULL
             GROUP BY ip_address
             ORDER BY fails DESC
             LIMIT 10"
        );

        $this->render('security/index', [
            'stats' => [
                'active_bans'       => $activeBans,
                'whitelist_count'   => $whitelistCount,
                'failed_logins_24h' => $failedLogins24h,
                'blocked_req_24h'   => $blockedReq24h,
                'csrf_fails_24h'    => $csrfFails24h,
            ],
            'bans'          => $bans,
            'whitelist'     => $whitelist,
            'events'        => $events,
            'top_attackers' => $topAttackers,
            'filter'        => $filter,
            'my_ip'         => Security::getClientIp(),
            'page_title'    => 'Seguridad',
        ]);
    }

    public function banIp(): void
    {
        $this->requireCsrf();
        $this->requireSuperadmin();
        $ip = trim((string)$this->input('ip', ''));
        $reason = trim((string)$this->input('reason', 'Manual ban')) ?: 'Manual ban';
        $hours = $this->input('hours', '');
        $duration = ($hours === '' || $hours === '0') ? null : max(1, (int)$hours);

        if (!Security::isValidIp($ip)) {
            Flash::error('IP invalida.');
            $this->redirect('/admin/security');
            return;
        }
        if ($ip === Security::getClientIp()) {
            Flash::error('No podes banear tu propia IP. Agregala a la whitelist primero.');
            $this->redirect('/admin/security');
            return;
        }

        Security::ban($ip, $reason, $duration, (int)$this->user['id'], false);
        Flash::success("IP $ip baneada" . ($duration ? " por $duration h" : ' permanentemente') . '.');
        $this->redirect('/admin/security');
    }

    public function unbanIp(): void
    {
        $this->requireCsrf();
        $this->requireSuperadmin();
        $ip = trim((string)$this->input('ip', ''));
        if (Security::unban($ip, (int)$this->user['id'])) {
            Flash::success("IP $ip desbaneada.");
        } else {
            Flash::error('No se pudo desbanear.');
        }
        $this->redirect('/admin/security');
    }

    public function addWhitelist(): void
    {
        $this->requireCsrf();
        $this->requireSuperadmin();
        $ip = trim((string)$this->input('ip', ''));
        $note = trim((string)$this->input('note', '')) ?: null;
        if (!Security::isValidIp($ip)) {
            Flash::error('IP invalida.');
            $this->redirect('/admin/security');
            return;
        }
        Security::addToWhitelist($ip, $note, (int)$this->user['id']);
        Flash::success("IP $ip agregada a la whitelist.");
        $this->redirect('/admin/security');
    }

    public function removeWhitelist(): void
    {
        $this->requireCsrf();
        $this->requireSuperadmin();
        $ip = trim((string)$this->input('ip', ''));
        if (Security::removeFromWhitelist($ip, (int)$this->user['id'])) {
            Flash::success("IP $ip removida de la whitelist.");
        } else {
            Flash::error('No se pudo remover.');
        }
        $this->redirect('/admin/security');
    }

    public function runGc(): void
    {
        $this->requireCsrf();
        $this->requireSuperadmin();
        Security::gc();
        Flash::success('Cleanup ejecutado (bans vencidos / attempts > 24h / events > 90 días).');
        $this->redirect('/admin/security');
    }

    private function requireSuperadmin(): void
    {
        if (!Auth::isSuperadmin()) {
            Flash::error('Solo superadmin puede ver seguridad.');
            $this->redirect('/admin/dashboard');
        }
    }
}
