<?php

namespace App\Support;

use Carbon\CarbonInterface;

class LanguagePages
{
    private const LANGUAGES = [
        'en' => [
            'name' => 'English',
            'native_name' => 'English',
            'html_locale' => 'en',
            'og_locale' => 'en_US',
            'seo_title' => 'MannaRise in English | Daily Devotionals, Prayer and Bible Growth',
            'seo_description' => 'Read MannaRise in English with daily scripture, affirmation, prayer, journaling prompts, Bible reading, and Christian spiritual growth tools.',
            'daily_seo_title' => 'MannaRise Daily Devotion for :date',
            'daily_seo_description' => 'Read and share the MannaRise daily devotion for :date: scripture, affirmation, prayer, and a journal prompt.',
            'hero_eyebrow' => 'English devotional home',
            'hero_title' => 'MannaRise in English',
            'hero_body' => 'Daily scripture, prayer, journaling, testimonies, and Bible growth tools for a steady walk with Christ.',
            'primary_cta' => 'Open today\'s devotion',
            'secondary_cta' => 'Open Bible',
            'prayer_cta' => 'Request prayer',
            'daily_eyebrow' => 'Today\'s rhythm',
            'daily_title' => 'Daily devotion for :date',
            'daily_intro' => 'A shareable daily path with scripture, affirmation, prayer, and one honest journal prompt.',
            'scripture_label' => 'Scripture',
            'affirmation_label' => 'Affirmation',
            'prayer_label' => 'Prayer',
            'journal_label' => 'Journal prompt',
            'action_label' => 'Practice today',
            'share_title' => 'Share daily card',
            'share_note' => 'Public link. No login needed.',
            'download_image' => 'Download image',
            'whatsapp_share' => 'WhatsApp share',
            'copy_link' => 'Copy link',
            'device_share' => 'Device share',
            'previous_day' => 'Previous day',
            'next_day' => 'Next day',
            'read_chapter' => 'Read full chapter',
            'language_switcher' => 'Language versions',
            'focus_title' => 'Built for daily Christian growth',
            'focus_intro' => 'Each language page has its own public URL, daily devotional focus, and shareable daily card.',
            'focus_cards' => [
                ['title' => 'Morning devotion', 'body' => 'Start with scripture, prayer, and a clear reflection before the day becomes crowded.'],
                ['title' => 'Share-first faith', 'body' => 'Send the daily card to someone who needs encouragement, prayer, or a steady reminder of God\'s word.'],
                ['title' => 'Personal rhythm', 'body' => 'Use the same public page for reading, journaling, and returning to the day\'s focus.'],
            ],
            'affirmation_template' => 'Today I receive God\'s :theme with faith.',
            'prayer_template' => 'Lord, let Your :theme shape my heart, my decisions, and the way I serve today.',
            'journal_template' => 'Where do I need God\'s :theme in my life today?',
            'action_template' => 'Pause, pray, and take one step that reflects God\'s :theme.',
            'card_devotion_label' => 'DAILY DEVOTION',
            'card_growth_label' => 'grow daily',
            'status_downloaded' => 'Daily card downloaded.',
            'status_copy_unavailable' => 'Clipboard copy is not available in this browser.',
            'status_copied' => 'Daily devotion link copied.',
            'status_native_unavailable' => 'Device sharing is not available, so the link was copied.',
            'status_shared' => 'Share sheet opened.',
            'status_not_completed' => 'Sharing was not completed.',
            'status_whatsapp' => 'WhatsApp share opened.',
        ],
        'fr' => [
            'name' => 'French',
            'native_name' => 'Français',
            'html_locale' => 'fr',
            'og_locale' => 'fr_FR',
            'seo_title' => 'MannaRise en français | Méditations, prière et croissance biblique',
            'seo_description' => 'Lisez MannaRise en français avec un verset quotidien, une affirmation, une prière, un journal spirituel et des outils de croissance chrétienne.',
            'daily_seo_title' => 'Méditation quotidienne MannaRise du :date',
            'daily_seo_description' => 'Lisez et partagez la méditation quotidienne MannaRise du :date avec l’Écriture, une affirmation, une prière et une question de journal.',
            'hero_eyebrow' => 'Accueil de méditation en français',
            'hero_title' => 'MannaRise en français',
            'hero_body' => 'Écriture, prière, journal spirituel et encouragement biblique pour grandir chaque jour avec Christ.',
            'primary_cta' => 'Ouvrir la méditation du jour',
            'secondary_cta' => 'Ouvrir la Bible',
            'prayer_cta' => 'Demander la prière',
            'daily_eyebrow' => 'Rythme du jour',
            'daily_title' => 'Méditation du :date',
            'daily_intro' => 'Un chemin quotidien à partager avec l’Écriture, une affirmation, une prière et une question de journal.',
            'scripture_label' => 'Écriture',
            'affirmation_label' => 'Affirmation',
            'prayer_label' => 'Prière',
            'journal_label' => 'Question de journal',
            'action_label' => 'Pratique du jour',
            'share_title' => 'Partager la carte du jour',
            'share_note' => 'Lien public. Aucun compte requis.',
            'download_image' => 'Télécharger l’image',
            'whatsapp_share' => 'Partager sur WhatsApp',
            'copy_link' => 'Copier le lien',
            'device_share' => 'Partager',
            'previous_day' => 'Jour précédent',
            'next_day' => 'Jour suivant',
            'read_chapter' => 'Lire le chapitre',
            'language_switcher' => 'Versions linguistiques',
            'focus_title' => 'Pensé pour une croissance chrétienne quotidienne',
            'focus_intro' => 'Chaque page de langue possède son URL publique, son accent spirituel du jour et une carte facile à partager.',
            'focus_cards' => [
                ['title' => 'Méditation du matin', 'body' => 'Commencez par l’Écriture, la prière et une réflexion claire avant le rythme de la journée.'],
                ['title' => 'Foi à partager', 'body' => 'Envoyez la carte du jour à une personne qui a besoin d’encouragement ou de prière.'],
                ['title' => 'Rythme personnel', 'body' => 'Revenez à la même page pour lire, écrire et garder le cap spirituel du jour.'],
            ],
            'affirmation_template' => 'Aujourd’hui, je reçois la :theme de Dieu avec foi.',
            'prayer_template' => 'Seigneur, que Ta :theme façonne mon cœur, mes choix et mon service aujourd’hui.',
            'journal_template' => 'Où ai-je besoin de la :theme de Dieu aujourd’hui ?',
            'action_template' => 'Arrête-toi, prie, puis fais un pas qui reflète la :theme de Dieu.',
            'card_devotion_label' => 'MÉDITATION DU JOUR',
            'card_growth_label' => 'grandir chaque jour',
            'status_downloaded' => 'Carte quotidienne téléchargée.',
            'status_copy_unavailable' => 'La copie du lien n’est pas disponible dans ce navigateur.',
            'status_copied' => 'Lien de la méditation copié.',
            'status_native_unavailable' => 'Le partage n’est pas disponible, le lien a été copié.',
            'status_shared' => 'Fenêtre de partage ouverte.',
            'status_not_completed' => 'Le partage n’a pas été terminé.',
            'status_whatsapp' => 'Partage WhatsApp ouvert.',
        ],
        'es' => [
            'name' => 'Spanish',
            'native_name' => 'Español',
            'html_locale' => 'es',
            'og_locale' => 'es_ES',
            'seo_title' => 'MannaRise en español | Devocionales, oración y crecimiento bíblico',
            'seo_description' => 'Lee MannaRise en español con escritura diaria, afirmación, oración, preguntas de diario y herramientas para crecer en la fe cristiana.',
            'daily_seo_title' => 'Devocional diario de MannaRise para el :date',
            'daily_seo_description' => 'Lee y comparte el devocional diario de MannaRise para el :date con escritura, afirmación, oración y una pregunta de diario.',
            'hero_eyebrow' => 'Inicio devocional en español',
            'hero_title' => 'MannaRise en español',
            'hero_body' => 'Escritura, oración, diario espiritual y recursos bíblicos para crecer cada día con Cristo.',
            'primary_cta' => 'Abrir el devocional de hoy',
            'secondary_cta' => 'Abrir la Biblia',
            'prayer_cta' => 'Pedir oración',
            'daily_eyebrow' => 'Ritmo de hoy',
            'daily_title' => 'Devocional para el :date',
            'daily_intro' => 'Un camino diario para compartir con escritura, afirmación, oración y una pregunta de diario.',
            'scripture_label' => 'Escritura',
            'affirmation_label' => 'Afirmación',
            'prayer_label' => 'Oración',
            'journal_label' => 'Pregunta de diario',
            'action_label' => 'Práctica de hoy',
            'share_title' => 'Compartir tarjeta diaria',
            'share_note' => 'Enlace público. No requiere cuenta.',
            'download_image' => 'Descargar imagen',
            'whatsapp_share' => 'Compartir por WhatsApp',
            'copy_link' => 'Copiar enlace',
            'device_share' => 'Compartir',
            'previous_day' => 'Día anterior',
            'next_day' => 'Día siguiente',
            'read_chapter' => 'Leer capítulo',
            'language_switcher' => 'Versiones de idioma',
            'focus_title' => 'Diseñado para el crecimiento cristiano diario',
            'focus_intro' => 'Cada página de idioma tiene su URL pública, su enfoque devocional del día y una tarjeta fácil de compartir.',
            'focus_cards' => [
                ['title' => 'Devoción de la mañana', 'body' => 'Comienza con la Escritura, oración y una reflexión clara antes de avanzar en el día.'],
                ['title' => 'Fe para compartir', 'body' => 'Envía la tarjeta diaria a alguien que necesite ánimo, oración o la Palabra de Dios.'],
                ['title' => 'Ritmo personal', 'body' => 'Vuelve a la misma página para leer, escribir y recordar el enfoque espiritual del día.'],
            ],
            'affirmation_template' => 'Hoy recibo la :theme de Dios con fe.',
            'prayer_template' => 'Señor, que Tu :theme forme mi corazón, mis decisiones y mi servicio hoy.',
            'journal_template' => '¿Dónde necesito la :theme de Dios hoy?',
            'action_template' => 'Haz una pausa, ora y da un paso que refleje la :theme de Dios.',
            'card_devotion_label' => 'DEVOCIONAL DIARIO',
            'card_growth_label' => 'crece cada día',
            'status_downloaded' => 'Tarjeta diaria descargada.',
            'status_copy_unavailable' => 'Copiar al portapapeles no está disponible en este navegador.',
            'status_copied' => 'Enlace del devocional copiado.',
            'status_native_unavailable' => 'Compartir no está disponible, así que se copió el enlace.',
            'status_shared' => 'Hoja de compartir abierta.',
            'status_not_completed' => 'No se completó el compartir.',
            'status_whatsapp' => 'Compartir por WhatsApp abierto.',
        ],
        'pt' => [
            'name' => 'Portuguese',
            'native_name' => 'Português',
            'html_locale' => 'pt',
            'og_locale' => 'pt_PT',
            'seo_title' => 'MannaRise em português | Devocionais, oração e crescimento bíblico',
            'seo_description' => 'Leia MannaRise em português com escritura diária, afirmação, oração, perguntas de diário e ferramentas de crescimento cristão.',
            'daily_seo_title' => 'Devocional diário MannaRise para :date',
            'daily_seo_description' => 'Leia e compartilhe o devocional diário MannaRise para :date com escritura, afirmação, oração e uma pergunta de diário.',
            'hero_eyebrow' => 'Página devocional em português',
            'hero_title' => 'MannaRise em português',
            'hero_body' => 'Escritura, oração, diário espiritual e recursos bíblicos para crescer diariamente com Cristo.',
            'primary_cta' => 'Abrir o devocional de hoje',
            'secondary_cta' => 'Abrir a Bíblia',
            'prayer_cta' => 'Pedir oração',
            'daily_eyebrow' => 'Ritmo de hoje',
            'daily_title' => 'Devocional para :date',
            'daily_intro' => 'Um caminho diário para compartilhar com escritura, afirmação, oração e uma pergunta de diário.',
            'scripture_label' => 'Escritura',
            'affirmation_label' => 'Afirmação',
            'prayer_label' => 'Oração',
            'journal_label' => 'Pergunta de diário',
            'action_label' => 'Prática de hoje',
            'share_title' => 'Compartilhar cartão diário',
            'share_note' => 'Link público. Não precisa de conta.',
            'download_image' => 'Baixar imagem',
            'whatsapp_share' => 'Compartilhar no WhatsApp',
            'copy_link' => 'Copiar link',
            'device_share' => 'Compartilhar',
            'previous_day' => 'Dia anterior',
            'next_day' => 'Próximo dia',
            'read_chapter' => 'Ler capítulo',
            'language_switcher' => 'Versões de idioma',
            'focus_title' => 'Criado para crescimento cristão diário',
            'focus_intro' => 'Cada página de idioma tem sua URL pública, seu foco devocional do dia e um cartão fácil de compartilhar.',
            'focus_cards' => [
                ['title' => 'Devocional da manhã', 'body' => 'Comece com a Escritura, oração e uma reflexão clara antes das demandas do dia.'],
                ['title' => 'Fé para compartilhar', 'body' => 'Envie o cartão diário a alguém que precise de encorajamento, oração ou da Palavra de Deus.'],
                ['title' => 'Ritmo pessoal', 'body' => 'Volte à mesma página para ler, escrever e guardar o foco espiritual do dia.'],
            ],
            'affirmation_template' => 'Hoje recebo a :theme de Deus com fé.',
            'prayer_template' => 'Senhor, que a Tua :theme forme meu coração, minhas decisões e meu serviço hoje.',
            'journal_template' => 'Onde preciso da :theme de Deus hoje?',
            'action_template' => 'Pare, ore e dê um passo que reflita a :theme de Deus.',
            'card_devotion_label' => 'DEVOCIONAL DIÁRIO',
            'card_growth_label' => 'cresça diariamente',
            'status_downloaded' => 'Cartão diário baixado.',
            'status_copy_unavailable' => 'Copiar para a área de transferência não está disponível neste navegador.',
            'status_copied' => 'Link do devocional copiado.',
            'status_native_unavailable' => 'Compartilhamento indisponível, então o link foi copiado.',
            'status_shared' => 'Tela de compartilhamento aberta.',
            'status_not_completed' => 'O compartilhamento não foi concluído.',
            'status_whatsapp' => 'Compartilhamento no WhatsApp aberto.',
        ],
        'sw' => [
            'name' => 'Swahili',
            'native_name' => 'Kiswahili',
            'html_locale' => 'sw',
            'og_locale' => 'sw_KE',
            'seo_title' => 'MannaRise kwa Kiswahili | Ibada ya kila siku, maombi na Biblia',
            'seo_description' => 'Soma MannaRise kwa Kiswahili ukiwa na andiko la siku, tamko la imani, maombi, maswali ya jarida na ukuaji wa Kikristo.',
            'daily_seo_title' => 'Ibada ya kila siku ya MannaRise ya :date',
            'daily_seo_description' => 'Soma na shiriki ibada ya kila siku ya MannaRise ya :date yenye andiko, tamko la imani, maombi na swali la jarida.',
            'hero_eyebrow' => 'Nyumbani kwa ibada ya Kiswahili',
            'hero_title' => 'MannaRise kwa Kiswahili',
            'hero_body' => 'Andiko, maombi, jarida la kiroho na nyenzo za Biblia kwa ukuaji wa kila siku katika Kristo.',
            'primary_cta' => 'Fungua ibada ya leo',
            'secondary_cta' => 'Fungua Biblia',
            'prayer_cta' => 'Omba kuombewa',
            'daily_eyebrow' => 'Mpangilio wa leo',
            'daily_title' => 'Ibada ya :date',
            'daily_intro' => 'Njia ya kila siku ya kushiriki andiko, tamko la imani, maombi na swali la jarida.',
            'scripture_label' => 'Andiko',
            'affirmation_label' => 'Tamko la imani',
            'prayer_label' => 'Maombi',
            'journal_label' => 'Swali la jarida',
            'action_label' => 'Tendo la leo',
            'share_title' => 'Shiriki kadi ya siku',
            'share_note' => 'Kiungo cha umma. Hakuna akaunti inayohitajika.',
            'download_image' => 'Pakua picha',
            'whatsapp_share' => 'Shiriki WhatsApp',
            'copy_link' => 'Nakili kiungo',
            'device_share' => 'Shiriki',
            'previous_day' => 'Siku iliyopita',
            'next_day' => 'Siku inayofuata',
            'read_chapter' => 'Soma sura',
            'language_switcher' => 'Matoleo ya lugha',
            'focus_title' => 'Imejengwa kwa ukuaji wa Kikristo wa kila siku',
            'focus_intro' => 'Kila ukurasa wa lugha una URL yake ya umma, ibada ya siku na kadi rahisi kushiriki.',
            'focus_cards' => [
                ['title' => 'Ibada ya asubuhi', 'body' => 'Anza na Andiko, maombi na tafakari iliyo wazi kabla siku haijashika kasi.'],
                ['title' => 'Imani ya kushiriki', 'body' => 'Tuma kadi ya siku kwa mtu anayehitaji faraja, maombi au Neno la Mungu.'],
                ['title' => 'Mpangilio binafsi', 'body' => 'Rudi kwenye ukurasa huo kusoma, kuandika na kukumbuka lengo la kiroho la siku.'],
            ],
            'affirmation_template' => 'Leo ninapokea :theme ya Mungu kwa imani.',
            'prayer_template' => 'Bwana, acha :theme Yako iunde moyo wangu, maamuzi yangu na huduma yangu leo.',
            'journal_template' => 'Ni wapi ninahitaji :theme ya Mungu leo?',
            'action_template' => 'Tulia, omba, kisha chukua hatua moja inayoonyesha :theme ya Mungu.',
            'card_devotion_label' => 'IBADA YA SIKU',
            'card_growth_label' => 'kua kila siku',
            'status_downloaded' => 'Kadi ya siku imepakuliwa.',
            'status_copy_unavailable' => 'Kunakili hakupatikani kwenye kivinjari hiki.',
            'status_copied' => 'Kiungo cha ibada kimenakiliwa.',
            'status_native_unavailable' => 'Kushiriki hakupatikani, kwa hiyo kiungo kimenakiliwa.',
            'status_shared' => 'Dirisha la kushiriki limefunguliwa.',
            'status_not_completed' => 'Kushiriki hakukukamilika.',
            'status_whatsapp' => 'Kushiriki WhatsApp kumefunguliwa.',
        ],
        'yo' => [
            'name' => 'Yoruba',
            'native_name' => 'Yorùbá',
            'html_locale' => 'yo',
            'og_locale' => 'yo_NG',
            'seo_title' => 'MannaRise ní Yorùbá | Ìfọkànsìn ojoojúmọ́, àdúrà àti Bíbélì',
            'seo_description' => 'Ka MannaRise ní Yorùbá pẹlu ẹsẹ Bíbélì ojoojúmọ́, ìjẹ́wọ́ ìgbàgbọ́, àdúrà, ìbéèrè ìwé-iranti ati idagbasoke Kristẹni.',
            'daily_seo_title' => 'Ìfọkànsìn MannaRise fún :date',
            'daily_seo_description' => 'Ka ki o sì pín ìfọkànsìn MannaRise fún :date pẹlu Ìwé Mímọ́, ìjẹ́wọ́, àdúrà ati ìbéèrè ìwé-iranti.',
            'hero_eyebrow' => 'Ilé ìfọkànsìn Yorùbá',
            'hero_title' => 'MannaRise ní Yorùbá',
            'hero_body' => 'Ìwé Mímọ́, àdúrà, ìwé-iranti ẹ̀mí ati ohun èlò Bíbélì fún idagbasoke ojoojúmọ́ ninu Kristi.',
            'primary_cta' => 'Ṣí ìfọkànsìn òní',
            'secondary_cta' => 'Ṣí Bíbélì',
            'prayer_cta' => 'Bẹ̀rẹ̀ àdúrà',
            'daily_eyebrow' => 'Ìlànà òní',
            'daily_title' => 'Ìfọkànsìn fún :date',
            'daily_intro' => 'Ọ̀nà ojoojúmọ́ láti pín Ìwé Mímọ́, ìjẹ́wọ́ ìgbàgbọ́, àdúrà ati ìbéèrè ìwé-iranti.',
            'scripture_label' => 'Ìwé Mímọ́',
            'affirmation_label' => 'Ìjẹ́wọ́ ìgbàgbọ́',
            'prayer_label' => 'Àdúrà',
            'journal_label' => 'Ìbéèrè ìwé-iranti',
            'action_label' => 'Ìṣe òní',
            'share_title' => 'Pín káàdì ojoojúmọ́',
            'share_note' => 'Ọna asopọ gbangba. Ko nilo iwọle.',
            'download_image' => 'Gba àwòrán sílẹ̀',
            'whatsapp_share' => 'Pín sí WhatsApp',
            'copy_link' => 'Da ọna asopọ kọ',
            'device_share' => 'Pín',
            'previous_day' => 'Ọjọ́ ṣáájú',
            'next_day' => 'Ọjọ́ tó tẹ̀lé',
            'read_chapter' => 'Ka orí náà',
            'language_switcher' => 'Àwọn ẹ̀dà èdè',
            'focus_title' => 'A kọ́ ọ fún idagbasoke Kristẹni ojoojúmọ́',
            'focus_intro' => 'Ojúewé èdè kọọkan ní URL tirẹ̀, ìfọkànsìn ọjọ́ ati káàdì tí ó rọrùn láti pín.',
            'focus_cards' => [
                ['title' => 'Ìfọkànsìn owurọ̀', 'body' => 'Bẹrẹ ọjọ́ pẹ̀lú Ìwé Mímọ́, àdúrà ati ìrònú kedere kí iṣẹ́ ọjọ́ tó pọ̀.'],
                ['title' => 'Ìgbàgbọ́ fún pínpín', 'body' => 'Rán káàdì ọjọ́ sí ẹni tí ó nílò ìtùnú, àdúrà tàbí Ọ̀rọ̀ Ọlọ́run.'],
                ['title' => 'Ìlànà ara ẹni', 'body' => 'Pada sí ojúewé kan naa láti ka, kọ ati rántí ìdojúkọ ẹ̀mí ti ọjọ́.'],
            ],
            'affirmation_template' => 'Lónìí, mo gba :theme Ọlọ́run pẹ̀lú ìgbàgbọ́.',
            'prayer_template' => 'Olúwa, jẹ́ kí :theme Rẹ dá ọkàn mi, ìpinnu mi ati iṣẹ́ mi lónìí.',
            'journal_template' => 'Nibo ni mo nílò :theme Ọlọ́run lónìí?',
            'action_template' => 'Dákẹ́, gbàdúrà, kí o sì gbe ìgbésẹ̀ kan tí ó fi :theme Ọlọ́run hàn.',
            'card_devotion_label' => 'ÌFỌKÀNSÌN ỌJỌ́',
            'card_growth_label' => 'dagba lojoojúmọ́',
            'status_downloaded' => 'Káàdì ọjọ́ ti gba sílẹ̀.',
            'status_copy_unavailable' => 'Didakọ ko si ninu aṣàwákiri yìí.',
            'status_copied' => 'Ọna asopọ ìfọkànsìn ti dakọ.',
            'status_native_unavailable' => 'Pínpín ko si, ọna asopọ ti dakọ.',
            'status_shared' => 'Ferese pínpín ti ṣí.',
            'status_not_completed' => 'Pínpín ko pari.',
            'status_whatsapp' => 'Pínpín WhatsApp ti ṣí.',
        ],
        'ha' => [
            'name' => 'Hausa',
            'native_name' => 'Hausa',
            'html_locale' => 'ha',
            'og_locale' => 'ha_NG',
            'seo_title' => 'MannaRise da Hausa | Ibada ta yau, addu’a da Littafi Mai Tsarki',
            'seo_description' => 'Karanta MannaRise da Hausa tare da ayar yau, furucin bangaskiya, addu’a, tambayar rubutu da kayan girma na Kirista.',
            'daily_seo_title' => 'Ibada ta MannaRise ta :date',
            'daily_seo_description' => 'Karanta kuma raba ibadar MannaRise ta :date tare da Nassi, furucin bangaskiya, addu’a da tambayar rubutu.',
            'hero_eyebrow' => 'Shafin ibada da Hausa',
            'hero_title' => 'MannaRise da Hausa',
            'hero_body' => 'Nassi, addu’a, rubutun tunani da kayan Littafi Mai Tsarki domin girma kullum cikin Kristi.',
            'primary_cta' => 'Bude ibadar yau',
            'secondary_cta' => 'Bude Littafi Mai Tsarki',
            'prayer_cta' => 'Nemi addu’a',
            'daily_eyebrow' => 'Tsarin yau',
            'daily_title' => 'Ibada ta :date',
            'daily_intro' => 'Hanyar yau da za a raba Nassi, furucin bangaskiya, addu’a da tambayar rubutu.',
            'scripture_label' => 'Nassi',
            'affirmation_label' => 'Furucin bangaskiya',
            'prayer_label' => 'Addu’a',
            'journal_label' => 'Tambayar rubutu',
            'action_label' => 'Aikin yau',
            'share_title' => 'Raba katin yau',
            'share_note' => 'Hanyar jama’a. Babu bukatar shiga.',
            'download_image' => 'Sauke hoto',
            'whatsapp_share' => 'Raba a WhatsApp',
            'copy_link' => 'Kwafi hanyar',
            'device_share' => 'Raba',
            'previous_day' => 'Ranar baya',
            'next_day' => 'Ranar gaba',
            'read_chapter' => 'Karanta babin',
            'language_switcher' => 'Siffofin harshe',
            'focus_title' => 'An gina shi domin girman Kirista na yau da kullum',
            'focus_intro' => 'Kowane shafin harshe yana da URL nasa, ibadar rana da katin da za a iya rabawa.',
            'focus_cards' => [
                ['title' => 'Ibada ta safe', 'body' => 'Fara da Nassi, addu’a da tunani mai tsabta kafin ayyukan rana su yi yawa.'],
                ['title' => 'Bangaskiya don rabawa', 'body' => 'Aika katin yau ga wanda ke bukatar ƙarfafa, addu’a ko Maganar Allah.'],
                ['title' => 'Tsarin kai', 'body' => 'Koma wannan shafi don karatu, rubutu da tuna abin da Allah ke koyarwa yau.'],
            ],
            'affirmation_template' => 'Yau ina karɓar :theme na Allah da bangaskiya.',
            'prayer_template' => 'Ubangiji, bari :theme naka ya tsara zuciyata, shawarata da hidimata yau.',
            'journal_template' => 'A ina nake bukatar :theme na Allah yau?',
            'action_template' => 'Dakatar, yi addu’a, ka ɗauki mataki guda da ke nuna :theme na Allah.',
            'card_devotion_label' => 'IBADAR YAU',
            'card_growth_label' => 'girma kullum',
            'status_downloaded' => 'An sauke katin yau.',
            'status_copy_unavailable' => 'Kwafi ba ya samuwa a wannan burauzar.',
            'status_copied' => 'An kwafi hanyar ibada.',
            'status_native_unavailable' => 'Rabawa ba ya samuwa, don haka an kwafi hanyar.',
            'status_shared' => 'An bude raba.',
            'status_not_completed' => 'Ba a kammala rabawa ba.',
            'status_whatsapp' => 'An bude rabawa ta WhatsApp.',
        ],
        'ig' => [
            'name' => 'Igbo',
            'native_name' => 'Igbo',
            'html_locale' => 'ig',
            'og_locale' => 'ig_NG',
            'seo_title' => 'MannaRise n’Igbo | Ekpere, Akwụkwọ Nsọ na uto kwa ụbọchị',
            'seo_description' => 'Gụọ MannaRise n’Igbo nwere amaokwu ụbọchị, nkwupụta okwukwe, ekpere, ajụjụ ide ihe na ngwa uto Ndị Kraịst.',
            'daily_seo_title' => 'Ntụgharị uche MannaRise nke :date',
            'daily_seo_description' => 'Gụọ ma kesaa ntụgharị uche MannaRise nke :date nwere Akwụkwọ Nsọ, nkwupụta okwukwe, ekpere na ajụjụ ide ihe.',
            'hero_eyebrow' => 'Ụlọ ntụgharị uche n’Igbo',
            'hero_title' => 'MannaRise n’Igbo',
            'hero_body' => 'Akwụkwọ Nsọ, ekpere, ide echiche mmụọ na ngwa Bible maka ito kwa ụbọchị n’ime Kraịst.',
            'primary_cta' => 'Mepee ntụgharị uche taa',
            'secondary_cta' => 'Mepee Bible',
            'prayer_cta' => 'Rịọ ekpere',
            'daily_eyebrow' => 'Usoro taa',
            'daily_title' => 'Ntụgharị uche maka :date',
            'daily_intro' => 'Ụzọ kwa ụbọchị iji kesaa Akwụkwọ Nsọ, nkwupụta okwukwe, ekpere na ajụjụ ide ihe.',
            'scripture_label' => 'Akwụkwọ Nsọ',
            'affirmation_label' => 'Nkwupụta okwukwe',
            'prayer_label' => 'Ekpere',
            'journal_label' => 'Ajụjụ ide ihe',
            'action_label' => 'Omume taa',
            'share_title' => 'Kesaa kaadị ụbọchị',
            'share_note' => 'Njikọ ọha. Enweghị nbanye achọrọ.',
            'download_image' => 'Budata onyonyo',
            'whatsapp_share' => 'Kesaa na WhatsApp',
            'copy_link' => 'Detuo njikọ',
            'device_share' => 'Kesaa',
            'previous_day' => 'Ụbọchị gara aga',
            'next_day' => 'Ụbọchị ọzọ',
            'read_chapter' => 'Gụọ isi',
            'language_switcher' => 'Ụdị asụsụ',
            'focus_title' => 'E wuru ya maka uto Ndị Kraịst kwa ụbọchị',
            'focus_intro' => 'Otu ibe asụsụ ọ bụla nwere URL nke ya, ntụgharị uche ụbọchị na kaadị dị mfe ikesa.',
            'focus_cards' => [
                ['title' => 'Ntụgharị uche ụtụtụ', 'body' => 'Malite na Akwụkwọ Nsọ, ekpere na echiche doro anya tupu ihe ụbọchị abawanye.'],
                ['title' => 'Okwukwe iji kesaa', 'body' => 'Zipụ kaadị ụbọchị nye onye chọrọ nkasi obi, ekpere ma ọ bụ Okwu Chineke.'],
                ['title' => 'Usoro onwe onye', 'body' => 'Laghachi n’otu ibe ahụ iji gụọ, dee ma cheta isiokwu mmụọ nke ụbọchị.'],
            ],
            'affirmation_template' => 'Taa ana m anabata :theme Chineke n’okwukwe.',
            'prayer_template' => 'Onyenwe anyị, ka :theme Gị kpụzie obi m, mkpebi m na ozi m taa.',
            'journal_template' => 'Ebee ka m chọrọ :theme Chineke taa?',
            'action_template' => 'Kwụsị, kpee ekpere, were otu nzọụkwụ na-egosi :theme Chineke.',
            'card_devotion_label' => 'NTỤGHARỊ UCHE TAA',
            'card_growth_label' => 'too kwa ụbọchị',
            'status_downloaded' => 'Ebudatala kaadị ụbọchị.',
            'status_copy_unavailable' => 'Idetuo adịghị na ihe nchọgharị a.',
            'status_copied' => 'Edetuo njikọ ntụgharị uche.',
            'status_native_unavailable' => 'Ikesa adịghị, ya mere edetuo njikọ ahụ.',
            'status_shared' => 'Emepeela mpio ikesa.',
            'status_not_completed' => 'Ikesa agwụchaghị.',
            'status_whatsapp' => 'Emepeela ikesa WhatsApp.',
        ],
    ];

    private const THEME_LABELS = [
        'en' => [
            'wisdom' => 'wisdom',
            'peace' => 'peace',
            'strength' => 'strength',
            'fruit' => 'fruitfulness',
            'renewal' => 'renewal',
            'anxiety' => 'guarded peace',
            'purpose' => 'purpose',
            'word' => 'God\'s word',
            'steadfast' => 'steadfastness',
            'mercy' => 'mercy',
            'courage' => 'courage',
            'endurance' => 'endurance',
            'growth' => 'spiritual growth',
            'provision' => 'provision',
        ],
        'fr' => [
            'wisdom' => 'sagesse',
            'peace' => 'paix',
            'strength' => 'force',
            'fruit' => 'fécondité',
            'renewal' => 'renouvellement',
            'anxiety' => 'paix gardée',
            'purpose' => 'appel',
            'word' => 'Parole de Dieu',
            'steadfast' => 'persévérance',
            'mercy' => 'miséricorde',
            'courage' => 'courage',
            'endurance' => 'endurance',
            'growth' => 'croissance spirituelle',
            'provision' => 'provision',
        ],
        'es' => [
            'wisdom' => 'sabiduría',
            'peace' => 'paz',
            'strength' => 'fortaleza',
            'fruit' => 'fruto espiritual',
            'renewal' => 'renovación',
            'anxiety' => 'paz guardada',
            'purpose' => 'propósito',
            'word' => 'Palabra de Dios',
            'steadfast' => 'firmeza',
            'mercy' => 'misericordia',
            'courage' => 'valor',
            'endurance' => 'perseverancia',
            'growth' => 'crecimiento espiritual',
            'provision' => 'provisión',
        ],
        'pt' => [
            'wisdom' => 'sabedoria',
            'peace' => 'paz',
            'strength' => 'força',
            'fruit' => 'fruto espiritual',
            'renewal' => 'renovação',
            'anxiety' => 'paz guardada',
            'purpose' => 'propósito',
            'word' => 'Palavra de Deus',
            'steadfast' => 'firmeza',
            'mercy' => 'misericórdia',
            'courage' => 'coragem',
            'endurance' => 'perseverança',
            'growth' => 'crescimento espiritual',
            'provision' => 'provisão',
        ],
        'sw' => [
            'wisdom' => 'hekima',
            'peace' => 'amani',
            'strength' => 'nguvu',
            'fruit' => 'matunda ya Roho',
            'renewal' => 'upya',
            'anxiety' => 'amani iliyolindwa',
            'purpose' => 'kusudi',
            'word' => 'Neno la Mungu',
            'steadfast' => 'uthabiti',
            'mercy' => 'rehema',
            'courage' => 'ujasiri',
            'endurance' => 'uvumilivu',
            'growth' => 'ukuaji wa kiroho',
            'provision' => 'utoaji',
        ],
        'yo' => [
            'wisdom' => 'ọgbọ́n',
            'peace' => 'àlàáfíà',
            'strength' => 'agbára',
            'fruit' => 'eso Ẹ̀mí',
            'renewal' => 'isọdọtun',
            'anxiety' => 'àlàáfíà tó ń ṣọ́ ọkàn',
            'purpose' => 'ìpinnu Ọlọ́run',
            'word' => 'Ọ̀rọ̀ Ọlọ́run',
            'steadfast' => 'iduroṣinṣin',
            'mercy' => 'àánú',
            'courage' => 'ìgboyà',
            'endurance' => 'ìfaradà',
            'growth' => 'idagbasoke ẹ̀mí',
            'provision' => 'ipèsè',
        ],
        'ha' => [
            'wisdom' => 'hikima',
            'peace' => 'salama',
            'strength' => 'ƙarfi',
            'fruit' => '’ya’yan Ruhu',
            'renewal' => 'sabuntawa',
            'anxiety' => 'salamar da ke tsare zuciya',
            'purpose' => 'nufi',
            'word' => 'Maganar Allah',
            'steadfast' => 'tsayawa da ƙarfi',
            'mercy' => 'jinƙai',
            'courage' => 'jarumta',
            'endurance' => 'juriya',
            'growth' => 'girma na ruhaniya',
            'provision' => 'tanadi',
        ],
        'ig' => [
            'wisdom' => 'amamihe',
            'peace' => 'udo',
            'strength' => 'ike',
            'fruit' => 'mkpụrụ nke Mmụọ',
            'renewal' => 'mmelite',
            'anxiety' => 'udo na-eche obi',
            'purpose' => 'nzube Chineke',
            'word' => 'Okwu Chineke',
            'steadfast' => 'iguzosi ike',
            'mercy' => 'ebere',
            'courage' => 'obi ike',
            'endurance' => 'ntachi obi',
            'growth' => 'uto mmụọ',
            'provision' => 'ndokwa',
        ],
    ];

    private const MONTHS = [
        'en' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        'fr' => ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'],
        'es' => ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'],
        'pt' => ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'],
        'sw' => ['Januari', 'Februari', 'Machi', 'Aprili', 'Mei', 'Juni', 'Julai', 'Agosti', 'Septemba', 'Oktoba', 'Novemba', 'Desemba'],
        'yo' => ['Oṣù Kínní', 'Oṣù Kejì', 'Oṣù Kẹta', 'Oṣù Kẹrin', 'Oṣù Karùn-ún', 'Oṣù Kẹfà', 'Oṣù Keje', 'Oṣù Kẹjọ', 'Oṣù Kẹsán', 'Oṣù Kẹwàá', 'Oṣù Kọkànlá', 'Oṣù Kejìlá'],
        'ha' => ['Janairu', 'Faburairu', 'Maris', 'Afrilu', 'Mayu', 'Yuni', 'Yuli', 'Agusta', 'Satumba', 'Oktoba', 'Nuwamba', 'Disamba'],
        'ig' => ['Jenụwarị', 'Febrụwarị', 'Maachị', 'Eprel', 'Mee', 'Juun', 'Julaị', 'Ọgọst', 'Septemba', 'Ọktoba', 'Novemba', 'Disemba'],
    ];

    /**
     * @return array<int, string>
     */
    public static function codes(): array
    {
        return array_keys(self::LANGUAGES);
    }

    public static function routePattern(): string
    {
        return implode('|', self::codes());
    }

    public static function isSupported(?string $locale): bool
    {
        return is_string($locale) && isset(self::LANGUAGES[$locale]);
    }

    /**
     * @return array<string, mixed>
     */
    public static function language(string $locale): array
    {
        return self::LANGUAGES[$locale] ?? self::LANGUAGES['en'];
    }

    /**
     * @return array<string, string>
     */
    public static function homeAlternates(): array
    {
        $alternates = ['x-default' => route('home')];

        foreach (self::codes() as $code) {
            $alternates[$code] = route('localized.home', ['locale' => $code]);
        }

        return $alternates;
    }

    /**
     * @return array<string, string>
     */
    public static function dailyAlternates(CarbonInterface|string $date): array
    {
        $date = $date instanceof CarbonInterface ? $date->toDateString() : $date;
        $alternates = ['x-default' => route('daily.show', ['date' => $date])];

        foreach (self::codes() as $code) {
            $alternates[$code] = route('daily.localized.show', ['locale' => $code, 'date' => $date]);
        }

        return $alternates;
    }

    /**
     * @return array<int, array{code:string,name:string,native_name:string,url:string,current:bool}>
     */
    public static function homeOptions(string $currentLocale): array
    {
        return array_map(fn (string $code): array => [
            'code' => $code,
            'name' => self::LANGUAGES[$code]['name'],
            'native_name' => self::LANGUAGES[$code]['native_name'],
            'url' => route('localized.home', ['locale' => $code]),
            'current' => $code === $currentLocale,
        ], self::codes());
    }

    /**
     * @return array<int, array{code:string,name:string,native_name:string,url:string,current:bool}>
     */
    public static function dailyOptions(string $currentLocale, CarbonInterface|string $date): array
    {
        $date = $date instanceof CarbonInterface ? $date->toDateString() : $date;

        return array_map(fn (string $code): array => [
            'code' => $code,
            'name' => self::LANGUAGES[$code]['name'],
            'native_name' => self::LANGUAGES[$code]['native_name'],
            'url' => route('daily.localized.show', ['locale' => $code, 'date' => $date]),
            'current' => $code === $currentLocale,
        ], self::codes());
    }

    /**
     * @return array<string, mixed>
     */
    public static function homeMeta(string $locale): array
    {
        $language = self::language($locale);
        $canonical = route('localized.home', ['locale' => $locale]);

        return [
            'title' => $language['seo_title'],
            'description' => $language['seo_description'],
            'canonical' => $canonical,
            'language' => $language['html_locale'],
            'locale_code' => $locale,
            'og_locale' => $language['og_locale'],
            'alternates' => self::homeAlternates(),
            'breadcrumbs' => [
                ['label' => 'Home', 'url' => route('home')],
                ['label' => $language['native_name'], 'url' => $canonical],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function dailyMeta(string $locale, CarbonInterface $date, string $canonical): array
    {
        $language = self::language($locale);
        $dateLabel = self::dateLabel($locale, $date);

        return [
            'title' => self::fill($language['daily_seo_title'], [':date' => $dateLabel]),
            'description' => self::fill($language['daily_seo_description'], [':date' => $dateLabel]),
            'canonical' => $canonical,
            'language' => $language['html_locale'],
            'locale_code' => $locale,
            'og_locale' => $language['og_locale'],
            'alternates' => self::dailyAlternates($date),
            'breadcrumbs' => [
                ['label' => 'Home', 'url' => route('home')],
                ['label' => $language['native_name'], 'url' => route('localized.home', ['locale' => $locale])],
                ['label' => $dateLabel, 'url' => $canonical],
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $dailyRhythm
     * @return array<string, mixed>
     */
    public static function landingContent(string $locale, array $dailyRhythm, CarbonInterface $date): array
    {
        $language = self::language($locale);
        $daily = self::dailyCopy($locale, $dailyRhythm, $date);

        return [
            'locale' => $locale,
            'language' => $language,
            'date_label' => $daily['date_label'],
            'hero_eyebrow' => $language['hero_eyebrow'],
            'hero_title' => $language['hero_title'],
            'hero_body' => $language['hero_body'],
            'primary_cta' => $language['primary_cta'],
            'secondary_cta' => $language['secondary_cta'],
            'prayer_cta' => $language['prayer_cta'],
            'daily' => $daily,
            'focus_title' => $language['focus_title'],
            'focus_intro' => $language['focus_intro'],
            'focus_cards' => $language['focus_cards'],
            'language_switcher' => $language['language_switcher'],
            'language_options' => self::homeOptions($locale),
            'daily_url' => route('daily.localized.show', ['locale' => $locale, 'date' => $date->toDateString()]),
            'bible_url' => route('bible'),
            'prayer_url' => route('prayer-requests.submit'),
        ];
    }

    /**
     * @param  array<string, mixed>  $dailyRhythm
     * @return array<string, mixed>
     */
    public static function dailyCopy(string $locale, array $dailyRhythm, CarbonInterface $date): array
    {
        $language = self::language($locale);
        $sourceAffirmation = $dailyRhythm['affirmation'] ?? [];
        $sourceReflection = $dailyRhythm['reflection'] ?? [];
        $theme = (string) ($sourceAffirmation['theme'] ?? 'peace');
        $themeLabel = self::themeLabel($locale, $theme);
        $dateLabel = self::dateLabel($locale, $date);

        $affirmationText = $locale === 'en'
            ? (string) ($sourceAffirmation['text'] ?? self::fill($language['affirmation_template'], [':theme' => $themeLabel]))
            : self::fill($language['affirmation_template'], [':theme' => $themeLabel]);

        $prayer = $locale === 'en'
            ? (string) ($sourceReflection['prayer'] ?? self::fill($language['prayer_template'], [':theme' => $themeLabel]))
            : self::fill($language['prayer_template'], [':theme' => $themeLabel]);

        $journalPrompt = $locale === 'en'
            ? (string) ($sourceReflection['journal_prompt'] ?? self::fill($language['journal_template'], [':theme' => $themeLabel]))
            : self::fill($language['journal_template'], [':theme' => $themeLabel]);

        $action = $locale === 'en'
            ? (string) ($sourceReflection['action'] ?? self::fill($language['action_template'], [':theme' => $themeLabel]))
            : self::fill($language['action_template'], [':theme' => $themeLabel]);

        return [
            'locale' => $locale,
            'language' => $language,
            'date_label' => $dateLabel,
            'theme_label' => $themeLabel,
            'page_eyebrow' => $language['daily_eyebrow'],
            'page_title' => self::fill($language['daily_title'], [':date' => $dateLabel]),
            'page_intro' => $language['daily_intro'],
            'scripture_label' => $language['scripture_label'],
            'affirmation_label' => $language['affirmation_label'],
            'prayer_label' => $language['prayer_label'],
            'journal_label' => $language['journal_label'],
            'action_label' => $language['action_label'],
            'affirmation_text' => $affirmationText,
            'affirmation_reference' => (string) ($sourceAffirmation['reference'] ?? ''),
            'prayer' => $prayer,
            'journal_prompt' => $journalPrompt,
            'action' => $action,
            'share_title' => $language['share_title'],
            'share_note' => $language['share_note'],
            'download_image' => $language['download_image'],
            'whatsapp_share' => $language['whatsapp_share'],
            'copy_link' => $language['copy_link'],
            'device_share' => $language['device_share'],
            'previous_day' => $language['previous_day'],
            'next_day' => $language['next_day'],
            'read_chapter' => $language['read_chapter'],
            'language_switcher' => $language['language_switcher'],
            'card_devotion_label' => $language['card_devotion_label'],
            'card_growth_label' => $language['card_growth_label'],
            'status_downloaded' => $language['status_downloaded'],
            'status_copy_unavailable' => $language['status_copy_unavailable'],
            'status_copied' => $language['status_copied'],
            'status_native_unavailable' => $language['status_native_unavailable'],
            'status_shared' => $language['status_shared'],
            'status_not_completed' => $language['status_not_completed'],
            'status_whatsapp' => $language['status_whatsapp'],
        ];
    }

    public static function themeLabel(string $locale, string $theme): string
    {
        return self::THEME_LABELS[$locale][$theme]
            ?? self::THEME_LABELS['en'][$theme]
            ?? $theme;
    }

    public static function dateLabel(string $locale, CarbonInterface $date): string
    {
        $months = self::MONTHS[$locale] ?? self::MONTHS['en'];
        $month = $months[((int) $date->format('n')) - 1] ?? $date->format('F');
        $day = (int) $date->format('j');
        $year = $date->format('Y');

        return match ($locale) {
            'fr' => $day.' '.$month.' '.$year,
            'es' => $day.' de '.$month.' de '.$year,
            'pt' => $day.' de '.$month.' de '.$year,
            'sw', 'yo', 'ha', 'ig' => $day.' '.$month.' '.$year,
            default => $month.' '.$day.', '.$year,
        };
    }

    /**
     * @param  array<string, string>  $replacements
     */
    private static function fill(string $template, array $replacements): string
    {
        return strtr($template, $replacements);
    }
}
