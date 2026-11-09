<?php

namespace App\Support;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

/**
 * Nettoie le HTML saisi dans l'administration avant de l'afficher (protection XSS).
 *
 * Seules quelques balises de mise en forme sont conservées ; tout le reste
 * (scripts, attributs on*, styles, liens javascript:, etc.) est supprimé.
 */
class SafeHtml
{
    private static ?HtmlSanitizer $sanitizer = null;

    public static function clean(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        return self::sanitizer()->sanitize($html);
    }

    private static function sanitizer(): HtmlSanitizer
    {
        return self::$sanitizer ??= new HtmlSanitizer(
            (new HtmlSanitizerConfig())
                ->allowElement('p')
                ->allowElement('br')
                ->allowElement('b')
                ->allowElement('strong')
                ->allowElement('i')
                ->allowElement('em')
                ->allowElement('u')
                ->allowElement('ul')
                ->allowElement('ol')
                ->allowElement('li')
                ->allowElement('a', ['href', 'title'])
                // Liens : uniquement http(s) et mailto, jamais javascript:
                ->allowLinkSchemes(['http', 'https', 'mailto'])
                ->allowRelativeLinks()
                // Les liens externes s'ouvrent sans donner accès à la page d'origine
                ->forceAttribute('a', 'rel', 'noopener noreferrer')
        );
    }
}
