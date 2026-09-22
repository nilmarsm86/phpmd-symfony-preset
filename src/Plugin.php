<?php

declare(strict_types=1);

namespace Nilmarsm86\PhpmdPreset;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

/**
 * Se activa en cuanto el paquete queda instalado (mismo `composer require`
 * que lo instala). Composer garantiza que el autoloader del plugin ya está
 * disponible antes de disparar post-install-cmd / post-update-cmd, así que
 * no hace falta un segundo comando aparte de requerir este paquete.
 */
final class Plugin implements PluginInterface, EventSubscriberInterface
{
    public function activate(Composer $composer, IOInterface $io): void
    {
        // Nada que hacer aquí: toda la lógica vive en los eventos.
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ScriptEvents::POST_INSTALL_CMD => 'onPostInstallOrUpdate',
            ScriptEvents::POST_UPDATE_CMD  => 'onPostInstallOrUpdate',
        ];
    }

    public function onPostInstallOrUpdate(Event $event): void
    {
        Installer::install($event->getIO(), getcwd());
    }
}
