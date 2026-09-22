# phpmd-symfony-preset

Paquete-plugin de Composer para instalar, de forma automática, tus rulesets
de PHPMD por nivel y tu script de navegación en PHPStorm en cualquier
proyecto Symfony.

## 1. Cómo funciona

Basta con:

```bash
composer require --dev nilmarsm86/phpmd-symfony-preset
```

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

## 3. Uso del día a día una vez instalado

```bash
composer phpmd      # nivel actual
composer phpmd:0    # nivel básico
composer phpmd:1    # nivel 1
composer phpmd:2    # nivel 2
composer phpmd:3    # nivel 3
composer phpmd:4    # nivel 4
composer phpmd:5    # nivel 5
composer phpmd:6    # nivel 6
composer phpmd:next # pasa al siguiente nivel
```

PHPStorm detecta automáticamente como enlaces en la consola (igual que con
phpstan/psalm) — no hace falta configurar ninguna "External Tool" aparte.
