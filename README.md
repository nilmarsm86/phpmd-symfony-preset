# phpmd-symfony-preset

Paquete-plugin de Composer para instalar, de forma automática, tus rulesets
de PHPMD por nivel y tu script de navegación en PHPStorm en cualquier
proyecto Symfony.

## 1. Cómo funciona

`Plugin.php` se suscribe a los eventos `post-install-cmd` / `post-update-cmd`
de Composer. Composer activa el plugin en el mismo comando que lo instala
(no necesitas un tercer paso), así que basta con:

```bash
composer require --dev phpmd/phpmd
composer require --dev nilmarsm86/phpmd-symfony-preset
```

El segundo comando dispara `Installer::install()`, que:

1. Copia `phpmd/level1.xml`, `level2.xml`, `level3.xml`, etc al proyecto
   (solo si no existen ya — no pisa tus ediciones locales en updates
   posteriores).
2. Añade a tu `composer.json` los scripts `phpmd`, `phpmd:1`, `phpmd:2`, `phpmd:3`, `phpmd:4`, `phpmd:5`, `phpmd:6`,
   `phpmd:next` (solo si no existen ya).

El script de navegación queda disponible de inmediato como
`vendor/bin/phpmd-pretty` gracias al campo `"bin"` de Composer — no necesita
copiarse al proyecto porque siempre corre desde el paquete instalado.

## 2. ⚠️ Paso obligatorio: autorizar el plugin

Desde Composer 2.2, cualquier paquete `"type": "composer-plugin"` requiere
autorización explícita en el proyecto que lo consume, o Composer preguntará
interactivamente (y fallará en CI si no hay TTY). En cada proyecto que lo use:

```bash
composer config allow-plugins.nilmarsm86/phpmd-symfony-preset true
```

o añade a mano en el `composer.json` del proyecto:

```json
"config": {
    "allow-plugins": {
        "nilmarsm86/phpmd-symfony-preset": true
    }
}
```

## 3. Probarlo en local antes de publicarlo

En un proyecto Symfony de prueba, añade un repositorio `path` apuntando a
esta carpeta:

```bash
composer config repositories.phpmd-preset path /ruta/a/phpmd-symfony-preset
composer require --dev nilmarsm86/phpmd-symfony-preset:@dev
```

Verás en la salida los mensajes `[phpmd-preset] Copiado phpmd/level-*.xml` y
`[phpmd-preset] Scripts "composer phpmd:1|2|3|goto" añadidos...`.

## 4. Publicarlo en tu repositorio personal (Git privado)

```bash
cd phpmd-symfony-preset
git init
git add .
git commit -m "Preset inicial de PHPMD para Symfony"
git remote add origin git@tu-host:tu-usuario/phpmd-symfony-preset.git
git push -u origin main
git tag v1.0.0 && git push --tags   # recomendado: versiones etiquetadas
```

Para no repetir el bloque `repositories` en cada proyecto, regístralo una
sola vez de forma **global** en tu máquina:

```bash
composer config --global repositories.phpmd-preset vcs \
  git@tu-host:tu-usuario/phpmd-symfony-preset.git
```

A partir de ahí, en cualquier proyecto nuevo solo necesitas los dos comandos
del punto 1 (más el `allow-plugins` del punto 2) — sin tocar el
`composer.json` del proyecto para declarar el repositorio.

## 5. Publicarlo como "repositorio oficial" (Packagist)

Si en algún momento quieres que sea instalable sin configurar ningún
repositorio adicional: sube el repo a GitHub (puede seguir siendo un repo
"no listado" si no quieres que se indexe públicamente en tu perfil, pero
Packagist exige que el repo sea públicamente clonable) y date de alta el
paquete en https://packagist.org con la URL del repo. Composer resolverá
`TU-VENDOR/phpmd-symfony-preset` automáticamente sin necesidad de la entrada
`repositories`. En ese caso, quita la entrada `vcs` global para evitar tener
dos fuentes resolviendo el mismo nombre de paquete.

## 6. Uso del día a día una vez instalado

```bash
composer phpmd:1      # nivel básico, salida en texto
composer phpmd:2       # nivel intermedio
composer phpmd:3       # nivel estricto
composer phpmd:goto    # genera XML y lo pasa por phpmd-goto para navegación en PHPStorm
```

`composer phpmd:goto` imprime líneas `archivo:línea: mensaje`, que PHPStorm
detecta automáticamente como enlaces en la consola (igual que con
phpstan/psalm) — no hace falta configurar ninguna "External Tool" aparte.
