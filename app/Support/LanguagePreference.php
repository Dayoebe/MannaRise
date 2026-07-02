<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LanguagePreference
{
    public const SESSION_KEY = 'mannarise_locale';

    public const COOKIE_KEY = 'mannarise_locale';

    private const NAV_COPY = [
        'en' => [
            'home' => 'Home',
            'daily' => 'Daily',
            'devotionals' => 'Devotionals',
            'bible' => 'Bible',
            'resources' => 'Resources',
            'prayer' => 'Prayer',
            'journal' => 'Journal',
            'explore' => 'Explore',
            'my_space' => 'My Space',
            'admin' => 'Admin',
            'language' => 'Language',
            'choose_language' => 'Choose language',
            'dashboard' => 'Dashboard',
            'super_admin' => 'Super Admin',
            'log_in' => 'Log in',
            'log_out' => 'Log out',
            'join' => 'Join',
            'more' => 'More',
            'grow_daily' => 'grow daily',
            'guided_prayer' => 'Guided Prayer',
            'prayer_rooms' => 'Prayer Rooms',
            'prayer_wall' => 'Prayer Wall',
            'request_prayer' => 'Request Prayer',
        ],
        'fr' => [
            'home' => 'Accueil',
            'daily' => 'Méditation',
            'devotionals' => 'Lectures',
            'bible' => 'Bible',
            'resources' => 'Ressources',
            'prayer' => 'Prière',
            'journal' => 'Journal',
            'explore' => 'Explorer',
            'my_space' => 'Mon espace',
            'admin' => 'Admin',
            'language' => 'Langue',
            'choose_language' => 'Choisir la langue',
            'dashboard' => 'Tableau',
            'super_admin' => 'Super Admin',
            'log_in' => 'Connexion',
            'log_out' => 'Déconnexion',
            'join' => 'Rejoindre',
            'more' => 'Plus',
            'grow_daily' => 'grandir chaque jour',
            'guided_prayer' => 'Prière guidée',
            'prayer_rooms' => 'Salles de prière',
            'prayer_wall' => 'Mur de prière',
            'request_prayer' => 'Demander la prière',
        ],
        'es' => [
            'home' => 'Inicio',
            'daily' => 'Diario',
            'devotionals' => 'Devocionales',
            'bible' => 'Biblia',
            'resources' => 'Recursos',
            'prayer' => 'Oración',
            'journal' => 'Diario',
            'explore' => 'Explorar',
            'my_space' => 'Mi espacio',
            'admin' => 'Admin',
            'language' => 'Idioma',
            'choose_language' => 'Elegir idioma',
            'dashboard' => 'Panel',
            'super_admin' => 'Super Admin',
            'log_in' => 'Entrar',
            'log_out' => 'Salir',
            'join' => 'Unirse',
            'more' => 'Más',
            'grow_daily' => 'crece cada día',
            'guided_prayer' => 'Oración guiada',
            'prayer_rooms' => 'Salas de oración',
            'prayer_wall' => 'Muro de oración',
            'request_prayer' => 'Pedir oración',
        ],
        'pt' => [
            'home' => 'Início',
            'daily' => 'Diário',
            'devotionals' => 'Devocionais',
            'bible' => 'Bíblia',
            'resources' => 'Recursos',
            'prayer' => 'Oração',
            'journal' => 'Diário',
            'explore' => 'Explorar',
            'my_space' => 'Meu espaço',
            'admin' => 'Admin',
            'language' => 'Idioma',
            'choose_language' => 'Escolher idioma',
            'dashboard' => 'Painel',
            'super_admin' => 'Super Admin',
            'log_in' => 'Entrar',
            'log_out' => 'Sair',
            'join' => 'Entrar',
            'more' => 'Mais',
            'grow_daily' => 'cresça diariamente',
            'guided_prayer' => 'Oração guiada',
            'prayer_rooms' => 'Salas de oração',
            'prayer_wall' => 'Mural de oração',
            'request_prayer' => 'Pedir oração',
        ],
        'sw' => [
            'home' => 'Nyumbani',
            'daily' => 'Ibada',
            'devotionals' => 'Ibada',
            'bible' => 'Biblia',
            'resources' => 'Nyenzo',
            'prayer' => 'Maombi',
            'journal' => 'Jarida',
            'explore' => 'Chunguza',
            'my_space' => 'Nafasi yangu',
            'admin' => 'Admin',
            'language' => 'Lugha',
            'choose_language' => 'Chagua lugha',
            'dashboard' => 'Dashibodi',
            'super_admin' => 'Super Admin',
            'log_in' => 'Ingia',
            'log_out' => 'Toka',
            'join' => 'Jiunge',
            'more' => 'Zaidi',
            'grow_daily' => 'kua kila siku',
            'guided_prayer' => 'Maombi ya mwongozo',
            'prayer_rooms' => 'Vyumba vya maombi',
            'prayer_wall' => 'Ukuta wa maombi',
            'request_prayer' => 'Omba kuombewa',
        ],
        'yo' => [
            'home' => 'Ilé',
            'daily' => 'Ojoojúmọ́',
            'devotionals' => 'Ìfọkànsìn',
            'bible' => 'Bíbélì',
            'resources' => 'Ohun èlò',
            'prayer' => 'Àdúrà',
            'journal' => 'Ìwé-iranti',
            'explore' => 'Ṣawari',
            'my_space' => 'Ààyè mi',
            'admin' => 'Admin',
            'language' => 'Èdè',
            'choose_language' => 'Yan èdè',
            'dashboard' => 'Dasibodu',
            'super_admin' => 'Super Admin',
            'log_in' => 'Wọlé',
            'log_out' => 'Jade',
            'join' => 'Darapọ̀',
            'more' => 'Si i',
            'grow_daily' => 'dagba lojoojúmọ́',
            'guided_prayer' => 'Àdúrà amọ̀nà',
            'prayer_rooms' => 'Yàrá àdúrà',
            'prayer_wall' => 'Odi àdúrà',
            'request_prayer' => 'Bẹ̀rẹ̀ àdúrà',
        ],
        'ha' => [
            'home' => 'Gida',
            'daily' => 'Yau',
            'devotionals' => 'Ibada',
            'bible' => 'Littafi',
            'resources' => 'Albarkatu',
            'prayer' => 'Addu’a',
            'journal' => 'Rubutu',
            'explore' => 'Bincika',
            'my_space' => 'Wurina',
            'admin' => 'Admin',
            'language' => 'Harshe',
            'choose_language' => 'Zaɓi harshe',
            'dashboard' => 'Allo',
            'super_admin' => 'Super Admin',
            'log_in' => 'Shiga',
            'log_out' => 'Fita',
            'join' => 'Shiga',
            'more' => 'Ƙari',
            'grow_daily' => 'girma kullum',
            'guided_prayer' => 'Addu’ar jagora',
            'prayer_rooms' => 'Dakunan addu’a',
            'prayer_wall' => 'Bangon addu’a',
            'request_prayer' => 'Nemi addu’a',
        ],
        'ig' => [
            'home' => 'Ụlọ',
            'daily' => 'Kwa ụbọchị',
            'devotionals' => 'Nsọpụrụ',
            'bible' => 'Baịbụl',
            'resources' => 'Ngwaọrụ',
            'prayer' => 'Ekpere',
            'journal' => 'Akwụkwọ',
            'explore' => 'Chọgharịa',
            'my_space' => 'Ebe m',
            'admin' => 'Admin',
            'language' => 'Asụsụ',
            'choose_language' => 'Họrọ asụsụ',
            'dashboard' => 'Dashboard',
            'super_admin' => 'Super Admin',
            'log_in' => 'Banye',
            'log_out' => 'Pụọ',
            'join' => 'Soro',
            'more' => 'Ọzọ',
            'grow_daily' => 'too kwa ụbọchị',
            'guided_prayer' => 'Ekpere nduzi',
            'prayer_rooms' => 'Ụlọ ekpere',
            'prayer_wall' => 'Mgbidi ekpere',
            'request_prayer' => 'Rịọ ekpere',
        ],
    ];

    public static function current(?Request $request = null): string
    {
        $request ??= request();
        $routeLocale = $request->route()?->parameter('locale');
        $explicitLocale = $request->query('language') ?: $request->query('lang');
        $sessionLocale = $request->hasSession() ? $request->session()->get(self::SESSION_KEY) : null;

        foreach ([$routeLocale, $explicitLocale, $sessionLocale, $request->cookie(self::COOKIE_KEY)] as $locale) {
            $locale = self::normalize($locale);

            if ($locale) {
                return $locale;
            }
        }

        return self::normalize($request->getPreferredLanguage(LanguagePages::codes())) ?: 'en';
    }

    public static function normalize(mixed $locale): ?string
    {
        if (! is_string($locale)) {
            return null;
        }

        $locale = strtolower(Str::before(trim($locale), '-'));

        return LanguagePages::isSupported($locale) ? $locale : null;
    }

    /**
     * @return array<string, string>
     */
    public static function navCopy(?string $locale = null): array
    {
        $locale = self::normalize($locale ?? self::current()) ?: 'en';

        return array_replace(self::NAV_COPY['en'], self::NAV_COPY[$locale] ?? []);
    }

    /**
     * @return array<int, array{code:string,name:string,native_name:string,current:bool,switch_url:string}>
     */
    public static function options(?Request $request = null): array
    {
        $request ??= request();
        $current = self::current($request);

        return array_map(function (string $code) use ($current, $request): array {
            $language = LanguagePages::language($code);

            return [
                'code' => $code,
                'name' => $language['name'],
                'native_name' => $language['native_name'],
                'current' => $code === $current,
                'switch_url' => route('language.switch', [
                    'locale' => $code,
                    'redirect' => self::targetUrlFor($code, $request),
                ]),
            ];
        }, LanguagePages::codes());
    }

    public static function homeUrl(?string $locale = null): string
    {
        $locale = self::normalize($locale ?? self::current()) ?: 'en';

        return route('localized.home', ['locale' => $locale]);
    }

    public static function dailyUrl(?string $locale = null, mixed $date = null): string
    {
        $locale = self::normalize($locale ?? self::current()) ?: 'en';
        $date = $date ?: today()->toDateString();

        return route('daily.localized.show', ['locale' => $locale, 'date' => $date]);
    }

    public static function bibleUrl(?string $locale = null, ?string $book = null, int|string|null $chapter = null): string
    {
        return BibleTranslations::readerUrl($book, $chapter, self::normalize($locale ?? self::current()) ?: 'en');
    }

    public static function targetUrlFor(string $locale, ?Request $request = null): string
    {
        $request ??= request();
        $locale = self::normalize($locale) ?: 'en';
        $route = $request->route();
        $routeName = $route?->getName();

        return match ($routeName) {
            'home', 'localized.home' => self::homeUrl($locale),
            'daily.index' => self::dailyUrl($locale),
            'daily.show', 'daily.localized.show' => self::dailyUrl($locale, (string) $route?->parameter('date')),
            'bible' => self::bibleUrl(
                $locale,
                is_string($route?->parameter('book')) ? $route?->parameter('book') : null,
                $route?->parameter('chapter')
            ),
            'language.switch' => self::homeUrl($locale),
            default => self::safeCurrentUrl($request, $locale),
        };
    }

    public static function safeRedirectUrl(mixed $target, string $locale, ?Request $request = null): string
    {
        $request ??= request();
        $target = is_string($target) ? trim($target) : '';

        if ($target === '') {
            return self::homeUrl($locale);
        }

        if (Str::startsWith($target, '/')) {
            return url($target);
        }

        $targetHost = parse_url($target, PHP_URL_HOST);

        if ($targetHost && strcasecmp($targetHost, $request->getHost()) === 0) {
            return $target;
        }

        return self::homeUrl($locale);
    }

    private static function safeCurrentUrl(Request $request, string $locale): string
    {
        $current = $request->fullUrl();

        if (str_contains($current, '/language/')) {
            return self::homeUrl($locale);
        }

        return $current;
    }
}
