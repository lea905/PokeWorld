<?php

namespace App\Support;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

/**
 * Nettoie le HTML saisi dans l'administration avant de l'afficher (protection XSS).
 *
 * - Balises de mise en forme autorisées : conservées.
 * - Balises de structure courantes (span, section, table…) : retirées, mais leur texte est gardé.
 * - Tout le reste (script, style, iframe, attributs on*, liens javascript:…) : supprimé.
 */
class SafeHtml
{
    /**
     * Balises conservées. Inclut celles produites par l'éditeur Trix de l'ancien
     * back-office EasyAdmin (div, h1, blockquote, pre, del), encore présentes en base.
     */
    private const ALLOWED = [
        'p', 'div', 'br', 'b', 'strong', 'i', 'em', 'u', 'del', 's',
        'ul', 'ol', 'li', 'h1', 'h2', 'h3', 'blockquote', 'pre', 'code',
    ];

    /** Balises retirées dont le texte est conservé (au lieu d'être effacé avec elles). */
    private const BLOCKED = [
        'span', 'font', 'small', 'big', 'sub', 'sup', 'mark', 'center',
        'section', 'article', 'header', 'footer', 'main', 'aside', 'nav',
        'figure', 'figcaption', 'label',
        'table', 'thead', 'tbody', 'tfoot', 'tr', 'td', 'th', 'caption',
    ];

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
        if (self::$sanitizer !== null) {
            return self::$sanitizer;
        }

        $config = new HtmlSanitizerConfig;

        foreach (self::ALLOWED as $element) {
            $config = $config->allowElement($element);
        }

        foreach (self::BLOCKED as $element) {
            $config = $config->blockElement($element);
        }

        $config = $config
            ->allowElement('a', ['href', 'title'])
            // Liens : uniquement http(s) et mailto, jamais javascript:
            ->allowLinkSchemes(['http', 'https', 'mailto'])
            ->allowRelativeLinks()
            // Les liens externes s'ouvrent sans donner accès à la page d'origine
            ->forceAttribute('a', 'rel', 'noopener noreferrer');

        return self::$sanitizer = new HtmlSanitizer($config);
    }
}
