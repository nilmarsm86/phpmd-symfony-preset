<?php

declare(strict_types=1);

namespace Nilmarsm86\PhpmdPreset;

use Composer\IO\IOInterface;

final class Installer
{
    private const TARGET_DIR = 'phpmd';

    public static function install(IOInterface $io, string $projectRoot): void
    {
        // Evita correr dentro del propio repo del plugin (cuando lo desarrollas/pruebas).
        if (basename($projectRoot) === 'phpmd-symfony-preset' && is_dir($projectRoot . '/resources/rulesets')) {
            return;
        }

        self::copyRulesets($projectRoot, $io);
        self::ensureComposerScripts($projectRoot, $io);
    }

    /**
     * Copia los rulesets SOLO si no existen ya en el proyecto, para no pisar
     * ajustes que hayas hecho localmente en instalaciones/updates posteriores.
     */
    private static function copyRulesets(string $projectRoot, IOInterface $io): void
    {
        $source = __DIR__ . '/../resources/rulesets';
        $target = $projectRoot . '/' . self::TARGET_DIR;

        if (!is_dir($target) && !mkdir($target, 0755, true) && !is_dir($target)) {
            $io->writeError('<error>[phpmd-preset]</error> No se pudo crear ' . $target);
            return;
        }

        foreach (glob($source . '/*.xml') ?: [] as $file) {
            $dest = $target . '/' . basename($file);
            if (file_exists($dest)) {
                continue;
            }
            copy($file, $dest);
            $io->write('<info>[phpmd-preset]</info> Copiado phpmd/' . basename($file));
        }
		
		//Copiar el archivo del level
		$file = $source . '/.level';
		$dest = $target . '/' . basename($file);
		if (!file_exists($dest)) {
		    copy($file, $dest);
            $io->write('<info>[phpmd-preset]</info> Copiado phpmd/' . basename($file));
		}
    }

    /**
     * Añade scripts "phpmd:*" al composer.json del proyecto SOLO si no existen ya,
     * para respetar cualquier personalización previa.
     */
    private static function ensureComposerScripts(string $projectRoot, IOInterface $io): void
    {
        $composerJsonPath = $projectRoot . '/composer.json';
        if (!file_exists($composerJsonPath)) {
            return;
        }

        $raw = file_get_contents($composerJsonPath);
        $data = json_decode($raw, true);
        if (!is_array($data)) {
            $io->writeError('<error>[phpmd-preset]</error> No se pudo leer composer.json del proyecto');
            return;
        }

        $defaults = [
            "phpmd" => "php vendor/bin/phpmd-pretty",
            "phpmd:next" => "php vendor/bin/phpmd-pretty",
            "phpmd:0" => "php vendor/bin/phpmd-pretty 0",
            "phpmd:1" => "php vendor/bin/phpmd-pretty 1",
            "phpmd:2" => "php vendor/bin/phpmd-pretty 2",
            "phpmd:3" => "php vendor/bin/phpmd-pretty 3",
            "phpmd:4" => "php vendor/bin/phpmd-pretty 4",
            "phpmd:5" => "php vendor/bin/phpmd-pretty 5",
            "phpmd:6" => "php vendor/bin/phpmd-pretty 6",
        ];

        $changed = false;
        foreach ($defaults as $key => $value) {
            if (!isset($data['scripts'][$key])) {
                $data['scripts'][$key] = $value;
                $changed = true;
            }
        }

        if (!$changed) {
            return;
        }

        file_put_contents(
            $composerJsonPath,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n"
        );
        $io->write('<info>[phpmd-preset]</info> Scripts "composer phpmd:1|2|3|4|5|6|next" añadidos a composer.json');
    }
}
