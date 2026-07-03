<?php

namespace App\Support;

class LocalizedDailyContent
{
    private const SCRIPTURES = [
        'wisdom' => [
            'reference' => 'James 1:5',
            'book_slug' => 'james',
            'chapter' => 1,
            'text' => [
                'en' => "If any of you lack wisdom, let him ask of God, who gives generously to all.",
                'fr' => "Si quelqu’un manque de sagesse, qu’il la demande à Dieu, qui donne à tous simplement.",
                'es' => "Si alguno necesita sabiduría, pídala a Dios, que da a todos con generosidad.",
                'pt' => "Se alguém precisa de sabedoria, peça a Deus, que dá a todos com generosidade.",
                'sw' => "Mtu akikosa hekima, na amwombe Mungu, naye huwapa wote kwa ukarimu.",
                'yo' => "Bí ẹnikẹ́ni kò bá ní ọgbọ́n, kí ó bẹ Ọlọ́run, Ẹni tí ń fún gbogbo ènìyàn lọ́pọ̀.",
                'ha' => "Idan wani ya rasa hikima, ya roƙi Allah, wanda yake ba kowa cikin yalwa.",
                'ig' => "Ọ bụrụ na onye ọbụla enweghị amamihe, ka ọ rịọ Chineke, Onye na-enye mmadụ niile n’ụba.",
            ],
        ],
        'peace' => [
            'reference' => 'John 14:27',
            'book_slug' => 'john',
            'chapter' => 14,
            'text' => [
                'en' => "My peace I give to you. Do not let your heart be troubled or afraid.",
                'fr' => "Je vous donne ma paix. Que votre cœur ne se trouble point et ne craigne point.",
                'es' => "Mi paz les doy. No se turbe su corazón ni tenga miedo.",
                'pt' => "A minha paz vos dou. Não se turbe o vosso coração, nem tenha medo.",
                'sw' => "Amani yangu nawapa. Mioyo yenu isifadhaike wala isiogope.",
                'yo' => "Àlàáfíà mi ni mo fi fún yín. Kí ọkàn yín má bàjẹ́, kí ẹ má sì bẹ̀rù.",
                'ha' => "Salamata nake ba ku. Kada zuciyarku ta firgita, kada kuma ku ji tsoro.",
                'ig' => "Udo m ka m na-enye unu. Ka obi unu ghara ịma jijiji, ka unu ghara ịtụ egwu.",
            ],
        ],
        'strength' => [
            'reference' => 'Isaiah 41:10',
            'book_slug' => 'isaiah',
            'chapter' => 41,
            'text' => [
                'en' => "Do not fear, for I am with you. I will strengthen you and help you.",
                'fr' => "Ne crains point, car je suis avec toi. Je te fortifie et je viens à ton secours.",
                'es' => "No temas, porque yo estoy contigo. Te fortaleceré y te ayudaré.",
                'pt' => "Não temas, porque eu estou contigo. Eu te fortaleço e te ajudo.",
                'sw' => "Usiogope, kwa maana niko pamoja nawe. Nitakutia nguvu na kukusaidia.",
                'yo' => "Má bẹ̀rù, nítorí mo wà pẹ̀lú rẹ. Èmi yóò fún ọ ní agbára, èmi yóò ràn ọ́ lọ́wọ́.",
                'ha' => "Kada ka ji tsoro, gama ina tare da kai. Zan ƙarfafa ka, zan taimake ka.",
                'ig' => "Atụla egwu, n’ihi na m nọnyere gị. Aga m eme ka ị sie ike ma nyere gị aka.",
            ],
        ],
        'fruit' => [
            'reference' => 'Galatians 5:22-23',
            'book_slug' => 'galatians',
            'chapter' => 5,
            'text' => [
                'en' => "The fruit of the Spirit is love, joy, peace, patience, kindness, goodness, faithfulness, gentleness, and self-control.",
                'fr' => "Le fruit de l’Esprit est amour, joie, paix, patience, bonté, fidélité, douceur et maîtrise de soi.",
                'es' => "El fruto del Espíritu es amor, gozo, paz, paciencia, bondad, fidelidad, mansedumbre y dominio propio.",
                'pt' => "O fruto do Espírito é amor, alegria, paz, paciência, bondade, fidelidade, mansidão e domínio próprio.",
                'sw' => "Tunda la Roho ni upendo, furaha, amani, uvumilivu, wema, uaminifu, upole na kiasi.",
                'yo' => "Eso Ẹ̀mí ni ìfẹ́, ayọ̀, àlàáfíà, sùúrù, inú rere, ìṣòtítọ́, ìwà pẹ̀lẹ́ ati ìkóra-ẹni-níjàánu.",
                'ha' => "’Ya’yan Ruhu su ne ƙauna, farin ciki, salama, haƙuri, alheri, aminci, tawali’u da kame kai.",
                'ig' => "Mkpụrụ nke Mmụọ bụ ịhụnanya, ọṅụ, udo, ndidi, ịdị mma, ntụkwasị obi, ịdị nwayọọ na ijide onwe.",
            ],
        ],
        'renewal' => [
            'reference' => 'Isaiah 40:31',
            'book_slug' => 'isaiah',
            'chapter' => 40,
            'text' => [
                'en' => "Those who wait on the Lord shall renew their strength and walk without growing weary.",
                'fr' => "Ceux qui espèrent en l’Éternel renouvellent leur force et marchent sans se fatiguer.",
                'es' => "Los que esperan en el Señor renovarán sus fuerzas y caminarán sin cansarse.",
                'pt' => "Os que esperam no Senhor renovarão as suas forças e caminharão sem se cansar.",
                'sw' => "Wanaomngojea Bwana watapata nguvu mpya na watatembea bila kuchoka.",
                'yo' => "Àwọn tí ń dúró de Olúwa yóò tún agbára wọn ṣe, wọn yóò sì rìn lai rẹ̀wẹ̀sì.",
                'ha' => "Masu dogara ga Ubangiji za su sabunta ƙarfinsu, su yi tafiya ba tare da gajiya ba.",
                'ig' => "Ndị na-eche Jehova ga-enweta ike ọhụrụ, ha ga-eje ije n’enweghị ike ọgwụgwụ.",
            ],
        ],
        'anxiety' => [
            'reference' => 'Philippians 4:6-7',
            'book_slug' => 'philippians',
            'chapter' => 4,
            'text' => [
                'en' => "Bring every request to God with prayer and thanksgiving, and his peace will guard your heart.",
                'fr' => "Présentez vos demandes à Dieu avec prière et reconnaissance, et sa paix gardera votre cœur.",
                'es' => "Presenta tus peticiones a Dios con oración y gratitud, y su paz guardará tu corazón.",
                'pt' => "Apresente seus pedidos a Deus com oração e gratidão, e a paz dele guardará seu coração.",
                'sw' => "Mletee Mungu kila haja kwa maombi na shukrani, naye amani yake italinda moyo wako.",
                'yo' => "Mú gbogbo ìbéèrè rẹ tọ Ọlọ́run wá pẹ̀lú àdúrà ati ọpẹ́, àlàáfíà Rẹ yóò ṣọ́ ọkàn rẹ.",
                'ha' => "Ka kai kowace bukata ga Allah cikin addu’a da godiya, salamar sa kuma za ta tsare zuciyarka.",
                'ig' => "Weta arịrịọ gị niile n’ihu Chineke n’ekpere na ekele, udo ya ga-eche obi gị.",
            ],
        ],
        'purpose' => [
            'reference' => 'Ephesians 2:10',
            'book_slug' => 'ephesians',
            'chapter' => 2,
            'text' => [
                'en' => "We are God’s workmanship, created in Christ Jesus for the good works he prepared.",
                'fr' => "Nous sommes l’ouvrage de Dieu, créés en Christ pour les bonnes œuvres qu’il a préparées.",
                'es' => "Somos obra de Dios, creados en Cristo para las buenas obras que él preparó.",
                'pt' => "Somos obra de Deus, criados em Cristo para as boas obras que ele preparou.",
                'sw' => "Sisi ni kazi ya Mungu, tumeumbwa katika Kristo kwa matendo mema aliyoyaandaa.",
                'yo' => "Àwa ni iṣẹ́ ọwọ́ Ọlọ́run, a dá wa ninu Kristi fún iṣẹ́ rere tí Ó ti pèsè.",
                'ha' => "Mu aikin hannun Allah ne, an halicce mu cikin Kristi domin ayyuka nagari da ya shirya.",
                'ig' => "Anyị bụ ọrụ aka Chineke, e kere anyị n’ime Kraịst maka ezi ọrụ ọ kwadebere.",
            ],
        ],
        'word' => [
            'reference' => 'Psalm 119:105',
            'book_slug' => 'psalms',
            'chapter' => 119,
            'text' => [
                'en' => "Your word is a lamp to my feet and a light to my path.",
                'fr' => "Ta parole est une lampe à mes pieds et une lumière sur mon sentier.",
                'es' => "Tu palabra es lámpara a mis pies y luz en mi camino.",
                'pt' => "A tua palavra é lâmpada para os meus pés e luz para o meu caminho.",
                'sw' => "Neno lako ni taa ya miguu yangu na mwanga wa njia yangu.",
                'yo' => "Ọ̀rọ̀ Rẹ jẹ́ fitílà fún ẹsẹ̀ mi ati ìmọ́lẹ̀ fún ọ̀nà mi.",
                'ha' => "Maganarka fitila ce ga ƙafafuna, haske kuma ga hanyata.",
                'ig' => "Okwu Gị bụ oriọna nye ụkwụ m na ìhè nye ụzọ m.",
            ],
        ],
        'steadfast' => [
            'reference' => '1 Corinthians 15:58',
            'book_slug' => '1-corinthians',
            'chapter' => 15,
            'text' => [
                'en' => "Be steadfast and immovable, always abounding in the work of the Lord.",
                'fr' => "Soyez fermes, inébranlables, abondant toujours dans l’œuvre du Seigneur.",
                'es' => "Sean firmes e inconmovibles, abundando siempre en la obra del Señor.",
                'pt' => "Sejam firmes e constantes, sempre abundantes na obra do Senhor.",
                'sw' => "Simameni imara, msitikisike, mkizidi daima katika kazi ya Bwana.",
                'yo' => "Ẹ dúró ṣinṣin, ẹ má ṣe yípadà, ẹ máa pọ̀ sí i nigbagbogbo ninu iṣẹ́ Olúwa.",
                'ha' => "Ku tsaya da ƙarfi, kada ku girgiza, ku ƙaru kullum cikin aikin Ubangiji.",
                'ig' => "Guzosie ike, ghara ịma jijiji, na-abawanye mgbe niile n’ọrụ Onyenwe anyị.",
            ],
        ],
        'mercy' => [
            'reference' => 'Lamentations 3:22-23',
            'book_slug' => 'lamentations',
            'chapter' => 3,
            'text' => [
                'en' => "The Lord’s mercies are not consumed. They are new every morning.",
                'fr' => "Les compassions de l’Éternel ne sont pas épuisées. Elles se renouvellent chaque matin.",
                'es' => "Las misericordias del Señor no se acaban. Nuevas son cada mañana.",
                'pt' => "As misericórdias do Senhor não se acabam. Renovam-se a cada manhã.",
                'sw' => "Rehema za Bwana hazikomi. Ni mpya kila asubuhi.",
                'yo' => "Àánú Olúwa kò tán. Wọ́n jẹ́ tuntun ní gbogbo òwúrọ̀.",
                'ha' => "Jinƙan Ubangiji ba ya ƙarewa. Sabbi ne kowace safiya.",
                'ig' => "Ebere Onyenwe anyị anaghị agwụ. Ha dị ọhụrụ n’ụtụtụ ọ bụla.",
            ],
        ],
        'courage' => [
            'reference' => 'Joshua 1:9',
            'book_slug' => 'joshua',
            'chapter' => 1,
            'text' => [
                'en' => "Be strong and courageous. The Lord your God is with you wherever you go.",
                'fr' => "Fortifie-toi et prends courage. L’Éternel ton Dieu est avec toi partout où tu vas.",
                'es' => "Sé fuerte y valiente. El Señor tu Dios está contigo dondequiera que vayas.",
                'pt' => "Seja forte e corajoso. O Senhor seu Deus está contigo por onde fores.",
                'sw' => "Uwe hodari na jasiri. Bwana Mungu wako yuko pamoja nawe popote uendapo.",
                'yo' => "Ní agbára ati ìgboyà. Olúwa Ọlọ́run rẹ wà pẹ̀lú rẹ níbikíbi tí o bá lọ.",
                'ha' => "Ka yi ƙarfi ka kuma yi jarumta. Ubangiji Allahnka yana tare da kai duk inda za ka.",
                'ig' => "Dị ike ma nwee obi ike. Onyenwe anyị Chineke gị nọnyere gị ebe ọbụla ị na-aga.",
            ],
        ],
        'endurance' => [
            'reference' => 'Philippians 4:13',
            'book_slug' => 'philippians',
            'chapter' => 4,
            'text' => [
                'en' => "I can do all things through Christ who strengthens me.",
                'fr' => "Je puis tout par Christ qui me fortifie.",
                'es' => "Todo lo puedo en Cristo que me fortalece.",
                'pt' => "Tudo posso em Cristo que me fortalece.",
                'sw' => "Naweza mambo yote katika Kristo anayenitia nguvu.",
                'yo' => "Mo lè ṣe ohun gbogbo ninu Kristi tí ń fún mi ní agbára.",
                'ha' => "Zan iya yin kome ta wurin Kristi wanda yake ƙarfafa ni.",
                'ig' => "Enwere m ike ime ihe niile site n’aka Kraịst onye na-eme ka m sie ike.",
            ],
        ],
        'growth' => [
            'reference' => 'Colossians 2:7',
            'book_slug' => 'colossians',
            'chapter' => 2,
            'text' => [
                'en' => "Be rooted and built up in Christ, established in the faith.",
                'fr' => "Soyez enracinés et édifiés en Christ, affermis dans la foi.",
                'es' => "Sean arraigados y edificados en Cristo, firmes en la fe.",
                'pt' => "Estejam enraizados e edificados em Cristo, firmes na fé.",
                'sw' => "Mwe na mizizi na kujengwa katika Kristo, mkithibitishwa katika imani.",
                'yo' => "Ẹ jẹ́ kí gbongbo yín wà ninu Kristi, kí a sì kọ́ yín sókè ninu ìgbàgbọ́.",
                'ha' => "Ku kafu ku kuma ginu cikin Kristi, ku tabbata cikin bangaskiya.",
                'ig' => "Gbanyere mgbọrọgwụ ma wulite n’ime Kraịst, guzoro ike n’okwukwe.",
            ],
        ],
        'provision' => [
            'reference' => 'Psalm 23:1',
            'book_slug' => 'psalms',
            'chapter' => 23,
            'text' => [
                'en' => "The Lord is my shepherd. I shall not lack what I need.",
                'fr' => "L’Éternel est mon berger. Je ne manquerai de rien.",
                'es' => "El Señor es mi pastor. Nada me faltará.",
                'pt' => "O Senhor é o meu pastor. Nada me faltará.",
                'sw' => "Bwana ndiye mchungaji wangu. Sitapungukiwa na kitu ninachohitaji.",
                'yo' => "Olúwa ni olùṣọ́-àgùntàn mi. Èmi kì yóò ṣe aláìní.",
                'ha' => "Ubangiji makiyayina ne. Ba zan rasa abin da nake bukata ba.",
                'ig' => "Onyenwe anyị bụ onye ọzụzụ atụrụ m. Agaghị m enwe ụkọ.",
            ],
        ],
    ];

    private const COPY = [
        'en' => [
            'wisdom' => ['affirmation' => "God gives me wisdom for today’s decisions.", 'prayer' => "Father, slow my heart down and teach me to choose with your wisdom, not pressure."],
            'peace' => ['affirmation' => "The peace of Christ rules my heart today.", 'prayer' => "Lord Jesus, settle every troubled place in me and make me a carrier of your peace."],
            'strength' => ['affirmation' => "God strengthens me for the work and weight of today.", 'prayer' => "Faithful God, meet my weakness with your help and make my steps steady."],
            'fruit' => ['affirmation' => "The Holy Spirit forms patient, loving fruit in me.", 'prayer' => "Holy Spirit, shape my words, reactions, and choices so they reveal your character."],
            'renewal' => ['affirmation' => "The Lord renews what is tired in me.", 'prayer' => "Lord, restore my soul and teach me to receive strength while I wait on you."],
            'anxiety' => ['affirmation' => "I turn worry into prayer and receive God’s guarded peace.", 'prayer' => "God of peace, take what is heavy in my mind and guard my heart with trust."],
            'purpose' => ['affirmation' => "I am created in Christ for meaningful good works.", 'prayer' => "Father, align my gifts, time, and obedience with the purpose you prepared for me."],
            'word' => ['affirmation' => "God’s word gives light for my next faithful step.", 'prayer' => "Lord, open your word to me and make it clear enough to obey today."],
            'steadfast' => ['affirmation' => "My labor in the Lord is not wasted.", 'prayer' => "Lord, make me steady when results are slow and faithful when obedience feels hidden."],
            'mercy' => ['affirmation' => "New mercy meets me this morning.", 'prayer' => "Merciful Father, let your compassion heal me and flow through me to others."],
            'courage' => ['affirmation' => "I can move with courage because God is with me.", 'prayer' => "Lord, remove fear from the place where obedience is asking me to step forward."],
            'endurance' => ['affirmation' => "Christ gives me strength to continue with faith.", 'prayer' => "Jesus, strengthen my endurance and keep my heart faithful in the middle of the process."],
            'growth' => ['affirmation' => "I am rooted in Christ and growing in faith.", 'prayer' => "Lord, deepen my roots in you and grow habits that make my faith fruitful."],
            'provision' => ['affirmation' => "The Lord shepherds me and provides what I need.", 'prayer' => "Shepherd of my soul, teach me to trust your care before I chase my own security."],
        ],
        'fr' => [
            'wisdom' => ['affirmation' => "Dieu me donne la sagesse pour les choix d’aujourd’hui.", 'prayer' => "Père, ralentis mon cœur et apprends-moi à choisir avec ta sagesse, non sous pression."],
            'peace' => ['affirmation' => "La paix de Christ gouverne mon cœur aujourd’hui.", 'prayer' => "Seigneur Jésus, apaise ce qui est troublé en moi et fais de moi un porteur de paix."],
            'strength' => ['affirmation' => "Dieu me fortifie pour le poids et le travail de ce jour.", 'prayer' => "Dieu fidèle, rencontre ma faiblesse par ton secours et affermis mes pas."],
            'fruit' => ['affirmation' => "Le Saint-Esprit forme en moi un fruit patient et plein d’amour.", 'prayer' => "Saint-Esprit, façonne mes paroles, mes réactions et mes choix pour révéler ton caractère."],
            'renewal' => ['affirmation' => "Le Seigneur renouvelle ce qui est fatigué en moi.", 'prayer' => "Seigneur, restaure mon âme et apprends-moi à recevoir la force pendant que je t’attends."],
            'anxiety' => ['affirmation' => "Je transforme l’inquiétude en prière et je reçois la paix de Dieu.", 'prayer' => "Dieu de paix, prends ce qui pèse sur mon esprit et garde mon cœur dans la confiance."],
            'purpose' => ['affirmation' => "Je suis créé en Christ pour des œuvres bonnes et utiles.", 'prayer' => "Père, aligne mes dons, mon temps et mon obéissance avec l’appel que tu as préparé."],
            'word' => ['affirmation' => "La Parole de Dieu éclaire mon prochain pas fidèle.", 'prayer' => "Seigneur, ouvre ta Parole devant moi et rends-la assez claire pour que j’obéisse aujourd’hui."],
            'steadfast' => ['affirmation' => "Mon travail dans le Seigneur n’est pas inutile.", 'prayer' => "Seigneur, rends-moi ferme quand les fruits tardent et fidèle quand l’obéissance reste cachée."],
            'mercy' => ['affirmation' => "Une miséricorde nouvelle me rejoint ce matin.", 'prayer' => "Père miséricordieux, que ta compassion me guérisse et se répande vers les autres."],
            'courage' => ['affirmation' => "Je peux avancer avec courage parce que Dieu est avec moi.", 'prayer' => "Seigneur, ôte la peur là où l’obéissance me demande d’avancer."],
            'endurance' => ['affirmation' => "Christ me donne la force de continuer avec foi.", 'prayer' => "Jésus, fortifie mon endurance et garde mon cœur fidèle au milieu du chemin."],
            'growth' => ['affirmation' => "Je suis enraciné en Christ et je grandis dans la foi.", 'prayer' => "Seigneur, approfondis mes racines en toi et fais grandir des habitudes qui rendent ma foi féconde."],
            'provision' => ['affirmation' => "Le Seigneur me conduit et pourvoit à mes besoins.", 'prayer' => "Berger de mon âme, apprends-moi à faire confiance à tes soins avant de chercher ma sécurité ailleurs."],
        ],
        'es' => [
            'wisdom' => ['affirmation' => "Dios me da sabiduría para las decisiones de hoy.", 'prayer' => "Padre, aquieta mi corazón y enséñame a elegir con tu sabiduría, no por presión."],
            'peace' => ['affirmation' => "La paz de Cristo gobierna mi corazón hoy.", 'prayer' => "Señor Jesús, calma lo que está turbado en mí y hazme portador de tu paz."],
            'strength' => ['affirmation' => "Dios me fortalece para el peso y la tarea de este día.", 'prayer' => "Dios fiel, encuentra mi debilidad con tu ayuda y afirma mis pasos."],
            'fruit' => ['affirmation' => "El Espíritu Santo forma en mí fruto paciente y lleno de amor.", 'prayer' => "Espíritu Santo, moldea mis palabras, reacciones y decisiones para revelar tu carácter."],
            'renewal' => ['affirmation' => "El Señor renueva lo que está cansado en mí.", 'prayer' => "Señor, restaura mi alma y enséñame a recibir fuerza mientras espero en ti."],
            'anxiety' => ['affirmation' => "Convierto la preocupación en oración y recibo la paz de Dios.", 'prayer' => "Dios de paz, toma lo que pesa en mi mente y guarda mi corazón en confianza."],
            'purpose' => ['affirmation' => "Fui creado en Cristo para buenas obras con propósito.", 'prayer' => "Padre, alinea mis dones, mi tiempo y mi obediencia con el propósito que preparaste."],
            'word' => ['affirmation' => "La palabra de Dios ilumina mi próximo paso fiel.", 'prayer' => "Señor, abre tu palabra delante de mí y hazla clara para obedecer hoy."],
            'steadfast' => ['affirmation' => "Mi trabajo en el Señor no es en vano.", 'prayer' => "Señor, hazme firme cuando el fruto tarde y fiel cuando la obediencia parezca escondida."],
            'mercy' => ['affirmation' => "Nueva misericordia me encuentra esta mañana.", 'prayer' => "Padre misericordioso, que tu compasión me sane y fluya de mí hacia otros."],
            'courage' => ['affirmation' => "Puedo avanzar con valor porque Dios está conmigo.", 'prayer' => "Señor, quita el temor del lugar donde la obediencia me pide avanzar."],
            'endurance' => ['affirmation' => "Cristo me da fuerza para continuar con fe.", 'prayer' => "Jesús, fortalece mi perseverancia y guarda mi corazón fiel en medio del proceso."],
            'growth' => ['affirmation' => "Estoy arraigado en Cristo y creciendo en fe.", 'prayer' => "Señor, profundiza mis raíces en ti y cultiva hábitos que hagan fructífera mi fe."],
            'provision' => ['affirmation' => "El Señor me pastorea y provee lo que necesito.", 'prayer' => "Pastor de mi alma, enséñame a confiar en tu cuidado antes de buscar seguridad por mi cuenta."],
        ],
        'pt' => [
            'wisdom' => ['affirmation' => "Deus me dá sabedoria para as decisões de hoje.", 'prayer' => "Pai, acalma meu coração e ensina-me a escolher com tua sabedoria, não pela pressão."],
            'peace' => ['affirmation' => "A paz de Cristo governa meu coração hoje.", 'prayer' => "Senhor Jesus, aquieta o que está perturbado em mim e faz de mim portador da tua paz."],
            'strength' => ['affirmation' => "Deus me fortalece para o peso e a tarefa deste dia.", 'prayer' => "Deus fiel, encontra minha fraqueza com teu socorro e firma meus passos."],
            'fruit' => ['affirmation' => "O Espírito Santo forma em mim fruto paciente e cheio de amor.", 'prayer' => "Espírito Santo, molda minhas palavras, reações e escolhas para revelar teu caráter."],
            'renewal' => ['affirmation' => "O Senhor renova o que está cansado em mim.", 'prayer' => "Senhor, restaura minha alma e ensina-me a receber força enquanto espero em ti."],
            'anxiety' => ['affirmation' => "Transformo preocupação em oração e recebo a paz de Deus.", 'prayer' => "Deus de paz, toma o que pesa em minha mente e guarda meu coração em confiança."],
            'purpose' => ['affirmation' => "Fui criado em Cristo para boas obras com propósito.", 'prayer' => "Pai, alinha meus dons, meu tempo e minha obediência com o propósito que preparaste."],
            'word' => ['affirmation' => "A palavra de Deus ilumina meu próximo passo fiel.", 'prayer' => "Senhor, abre tua palavra diante de mim e torna-a clara para obedecer hoje."],
            'steadfast' => ['affirmation' => "Meu trabalho no Senhor não é em vão.", 'prayer' => "Senhor, torna-me firme quando o fruto demora e fiel quando a obediência parece escondida."],
            'mercy' => ['affirmation' => "Nova misericórdia me encontra nesta manhã.", 'prayer' => "Pai misericordioso, que tua compaixão me cure e flua de mim para outras pessoas."],
            'courage' => ['affirmation' => "Posso avançar com coragem porque Deus está comigo.", 'prayer' => "Senhor, remove o medo do lugar onde a obediência me chama a avançar."],
            'endurance' => ['affirmation' => "Cristo me dá força para continuar com fé.", 'prayer' => "Jesus, fortalece minha perseverança e guarda meu coração fiel no meio do processo."],
            'growth' => ['affirmation' => "Estou enraizado em Cristo e crescendo na fé.", 'prayer' => "Senhor, aprofunda minhas raízes em ti e cultiva hábitos que tornem minha fé frutífera."],
            'provision' => ['affirmation' => "O Senhor me pastoreia e provê o que preciso.", 'prayer' => "Pastor da minha alma, ensina-me a confiar no teu cuidado antes de buscar segurança por mim mesmo."],
        ],
        'sw' => [
            'wisdom' => ['affirmation' => "Mungu hunipa hekima kwa maamuzi ya leo.", 'prayer' => "Baba, tuliza moyo wangu na unifundishe kuchagua kwa hekima yako, si kwa msukumo."],
            'peace' => ['affirmation' => "Amani ya Kristo inatawala moyo wangu leo.", 'prayer' => "Bwana Yesu, tuliza kila mahali palipo na wasiwasi ndani yangu na nifanye mbeba amani yako."],
            'strength' => ['affirmation' => "Mungu hunipa nguvu kwa kazi na mzigo wa leo.", 'prayer' => "Mungu mwaminifu, kutana na udhaifu wangu kwa msaada wako na uimarishe hatua zangu."],
            'fruit' => ['affirmation' => "Roho Mtakatifu huzaa ndani yangu tunda la upendo na uvumilivu.", 'prayer' => "Roho Mtakatifu, unda maneno, miitikio na maamuzi yangu ili yaonyeshe tabia yako."],
            'renewal' => ['affirmation' => "Bwana anafanya upya kilichochoka ndani yangu.", 'prayer' => "Bwana, rejesha nafsi yangu na unifundishe kupokea nguvu ninapokungojea."],
            'anxiety' => ['affirmation' => "Ninageuza wasiwasi kuwa maombi na kupokea amani ya Mungu.", 'prayer' => "Mungu wa amani, chukua mzigo ulio akilini mwangu na linda moyo wangu kwa tumaini."],
            'purpose' => ['affirmation' => "Nimeumbwa katika Kristo kwa kazi njema zenye kusudi.", 'prayer' => "Baba, linganisha vipawa vyangu, muda wangu na utii wangu na kusudi uliloandaa."],
            'word' => ['affirmation' => "Neno la Mungu huangaza hatua yangu inayofuata ya uaminifu.", 'prayer' => "Bwana, fungua Neno lako kwangu na ulifanye wazi kiasi cha kulitii leo."],
            'steadfast' => ['affirmation' => "Kazi yangu katika Bwana si bure.", 'prayer' => "Bwana, nifanye imara matokeo yanapochelewa na mwaminifu utii unapofichika."],
            'mercy' => ['affirmation' => "Rehema mpya zinanikuta asubuhi hii.", 'prayer' => "Baba mwenye rehema, huruma yako iniponye na itiririke kupitia mimi kwa wengine."],
            'courage' => ['affirmation' => "Ninaweza kusonga kwa ujasiri kwa sababu Mungu yu pamoja nami.", 'prayer' => "Bwana, ondoa hofu mahali ambapo utii unaniita kusonga mbele."],
            'endurance' => ['affirmation' => "Kristo hunipa nguvu ya kuendelea kwa imani.", 'prayer' => "Yesu, imarisha uvumilivu wangu na uulinde moyo wangu ubaki mwaminifu katikati ya safari."],
            'growth' => ['affirmation' => "Nina mizizi ndani ya Kristo na ninakua katika imani.", 'prayer' => "Bwana, ongeza mizizi yangu ndani yako na kukuza tabia zinazofanya imani yangu izae matunda."],
            'provision' => ['affirmation' => "Bwana hunichunga na kunipa ninachohitaji.", 'prayer' => "Mchungaji wa nafsi yangu, nifundishe kutumaini utunzaji wako kabla sijatafuta usalama wangu mwenyewe."],
        ],
        'yo' => [
            'wisdom' => ['affirmation' => "Ọlọ́run ń fún mi ní ọgbọ́n fún ìpinnu òní.", 'prayer' => "Baba, jẹ́ kí ọkàn mi balẹ̀, kọ́ mi láti yan pẹ̀lú ọgbọ́n Rẹ, kì í ṣe pẹ̀lú ìkánjú."],
            'peace' => ['affirmation' => "Àlàáfíà Kristi ń ṣàkóso ọkàn mi lónìí.", 'prayer' => "Olúwa Jesu, tù gbogbo ibi tí ó dàrú ninu mi, kí o sì ṣe mí ní ẹni tí ń gbé àlàáfíà Rẹ."],
            'strength' => ['affirmation' => "Ọlọ́run ń fún mi ní agbára fún iṣẹ́ ati ẹrù òní.", 'prayer' => "Ọlọ́run olóòtítọ́, pàdé ailera mi pẹ̀lú ìrànlọ́wọ́ Rẹ, kí o sì mú ìgbésẹ̀ mi dúró ṣinṣin."],
            'fruit' => ['affirmation' => "Ẹ̀mí Mímọ́ ń dá eso ìfẹ́ ati sùúrù sí mi.", 'prayer' => "Ẹ̀mí Mímọ́, dá ọ̀rọ̀ mi, ìdáhùn mi ati ìpinnu mi kí wọ́n fi ìwà Rẹ hàn."],
            'renewal' => ['affirmation' => "Olúwa ń tún ohun tí ó rẹ̀ ninu mi ṣe.", 'prayer' => "Olúwa, tún ọkàn mi ṣe, kọ́ mi láti gba agbára nigba tí mo ń dúró de Ọ."],
            'anxiety' => ['affirmation' => "Mo yí àníyàn padà sí àdúrà, mo sì gba àlàáfíà Ọlọ́run.", 'prayer' => "Ọlọ́run àlàáfíà, gba ohun tí ó wuwo ninu èrò mi, kí o sì ṣọ́ ọkàn mi ninu ìgbẹ́kẹ̀lé."],
            'purpose' => ['affirmation' => "A dá mi ninu Kristi fún iṣẹ́ rere tí ó ní ìpinnu.", 'prayer' => "Baba, bá ẹ̀bùn mi, àkókò mi ati ìgbọràn mi mu pẹ̀lú ìpinnu tí O ti pèsè."],
            'word' => ['affirmation' => "Ọ̀rọ̀ Ọlọ́run ń tan ìmọ́lẹ̀ sí ìgbésẹ̀ ìgbàgbọ́ mi tó tẹ̀lé.", 'prayer' => "Olúwa, ṣí Ọ̀rọ̀ Rẹ sí mi, kí ó sì ye mi dáadáa láti gbọràn lónìí."],
            'steadfast' => ['affirmation' => "Iṣẹ́ mi ninu Olúwa kì í ṣe asán.", 'prayer' => "Olúwa, jẹ́ kí n dúró ṣinṣin nígbà tí èso ń pé, kí n sì jẹ́ olóòtítọ́ nígbà tí ìgbọràn farasin."],
            'mercy' => ['affirmation' => "Àánú tuntun ń pàdé mi ní òwúrọ̀ yìí.", 'prayer' => "Baba aláàánú, jẹ́ kí ìyọ́nú Rẹ wò mí sàn, kí ó sì ṣàn láti ọ̀dọ̀ mi sí àwọn míì."],
            'courage' => ['affirmation' => "Mo lè lọ pẹ̀lú ìgboyà nítorí Ọlọ́run wà pẹ̀lú mi.", 'prayer' => "Olúwa, mú ìbẹ̀rù kúrò níbi tí ìgbọràn ti ń pè mí láti lọ síwájú."],
            'endurance' => ['affirmation' => "Kristi ń fún mi ní agbára láti tẹ̀síwájú pẹ̀lú ìgbàgbọ́.", 'prayer' => "Jesu, mú ìfaradà mi lágbára, kí o sì pa ọkàn mi mọ́ ninu òtítọ́ ní àárín irinàjò."],
            'growth' => ['affirmation' => "Mo ní gbongbo ninu Kristi, mo sì ń dagba ninu ìgbàgbọ́.", 'prayer' => "Olúwa, jin gbongbo mi sinu Rẹ, kí o sì dá àṣà tí yóò jẹ́ kí ìgbàgbọ́ mi so eso."],
            'provision' => ['affirmation' => "Olúwa ń ṣọ́ mi, Ó sì ń pèsè ohun tí mo nílò.", 'prayer' => "Olùṣọ́ ọkàn mi, kọ́ mi láti gbẹ́kẹ̀lé ìtọju Rẹ kí n tó wá ààbò mi fúnra mi."],
        ],
        'ha' => [
            'wisdom' => ['affirmation' => "Allah yana ba ni hikima domin shawarwarin yau.", 'prayer' => "Uba, ka kwantar da zuciyata, ka koya mini zaɓi da hikimarka, ba da matsin lamba ba."],
            'peace' => ['affirmation' => "Salamar Kristi tana mulkin zuciyata yau.", 'prayer' => "Ubangiji Yesu, ka kwantar da duk abin da ya rikice a cikina, ka mai da ni mai ɗaukar salamar ka."],
            'strength' => ['affirmation' => "Allah yana ƙarfafa ni domin aikin da nauyin yau.", 'prayer' => "Allah mai aminci, ka sadu da raunin zuciyata da taimakonka, ka tabbatar da matakaina."],
            'fruit' => ['affirmation' => "Ruhu Mai Tsarki yana haifar da ’ya’yan ƙauna da haƙuri a cikina.", 'prayer' => "Ruhu Mai Tsarki, ka tsara kalamaina, martanina da zabina su nuna halinka."],
            'renewal' => ['affirmation' => "Ubangiji yana sabunta abin da ya gaji a cikina.", 'prayer' => "Ubangiji, ka sabunta raina, ka koya mini karɓar ƙarfi yayin da nake jiranka."],
            'anxiety' => ['affirmation' => "Ina juya damuwa zuwa addu’a, ina karɓar salamar Allah.", 'prayer' => "Allah na salama, ka ɗauki nauyin da ke cikin tunanina, ka tsare zuciyata cikin dogara."],
            'purpose' => ['affirmation' => "An halicce ni cikin Kristi domin ayyuka nagari masu nufi.", 'prayer' => "Uba, ka daidaita baiwata, lokacina da biyayyata da nufin da ka shirya."],
            'word' => ['affirmation' => "Maganar Allah tana haskaka mataki na gaba na bangaskiya.", 'prayer' => "Ubangiji, ka buɗe Maganarka gare ni, ka sa ta bayyana har in yi biyayya yau."],
            'steadfast' => ['affirmation' => "Aikina cikin Ubangiji ba ya zama banza.", 'prayer' => "Ubangiji, ka sa ni tsayawa da ƙarfi idan ’ya’ya sun jinkirta, in kasance mai aminci idan biyayya ta ɓoyu."],
            'mercy' => ['affirmation' => "Sabon jinƙai yana saduwa da ni wannan safiya.", 'prayer' => "Uba mai jinƙai, bari tausayi naka ya warkar da ni, ya kuma gudana ta wurina zuwa ga wasu."],
            'courage' => ['affirmation' => "Zan iya tafiya da jarumta domin Allah yana tare da ni.", 'prayer' => "Ubangiji, ka cire tsoro daga wurin da biyayya ke kira na in ci gaba."],
            'endurance' => ['affirmation' => "Kristi yana ba ni ƙarfi in ci gaba cikin bangaskiya.", 'prayer' => "Yesu, ka ƙarfafa juriyata, ka kiyaye zuciyata cikin aminci a tsakiyar tafiya."],
            'growth' => ['affirmation' => "Ina da tushe cikin Kristi, ina kuma girma cikin bangaskiya.", 'prayer' => "Ubangiji, ka zurfafa tushena a cikinka, ka gina halaye da za su sa bangaskiyata ta ba da ’ya’ya."],
            'provision' => ['affirmation' => "Ubangiji yana kiwona, yana kuma tanadar min abin da nake bukata.", 'prayer' => "Makiyayin raina, ka koya mini dogara ga kulawarka kafin in nemi tsaro da kaina."],
        ],
        'ig' => [
            'wisdom' => ['affirmation' => "Chineke na-enye m amamihe maka mkpebi taa.", 'prayer' => "Nna, mee ka obi m daa jụụ, kụziere m ịhọrọ site n’amamihe gị, ọ bụghị site n nrụgide."],
            'peace' => ['affirmation' => "Udo Kraịst na-achị obi m taa.", 'prayer' => "Onyenwe Jizọs, mee ka ebe niile juru ụjọ n’ime m daa jụụ, mee ka m buru udo gị."],
            'strength' => ['affirmation' => "Chineke na-eme ka m sie ike maka ọrụ na ibu nke taa.", 'prayer' => "Chineke kwesiri ntụkwasị obi, zute adịghị ike m na enyemaka gị, mee ka nzọụkwụ m guzosie ike."],
            'fruit' => ['affirmation' => "Mmụọ Nsọ na-amị mkpụrụ ịhụnanya na ndidi n’ime m.", 'prayer' => "Mmụọ Nsọ, kpụzie okwu m, mmeghachi omume m na nhọrọ m ka ha gosi àgwà gị."],
            'renewal' => ['affirmation' => "Onyenwe anyị na-emegharị ihe gwụrụ n’ime m.", 'prayer' => "Onyenwe anyị, weghachi mkpụrụ obi m, kụziere m ịnata ike mgbe m na-eche gị."],
            'anxiety' => ['affirmation' => "Ana m agbanwe nchegbu bụrụ ekpere ma nata udo Chineke.", 'prayer' => "Chineke nke udo, were ihe na-arọ n’uche m, chekwaa obi m n ntụkwasị obi."],
            'purpose' => ['affirmation' => "E kere m n’ime Kraịst maka ezi ọrụ nwere nzube.", 'prayer' => "Nna, mee ka onyinye m, oge m na nrube isi m kwekọọ na nzube ị kwadebere."],
            'word' => ['affirmation' => "Okwu Chineke na-enwu ìhè nye nzọụkwụ okwukwe m na-esote.", 'prayer' => "Onyenwe anyị, mepee Okwu gị nye m, mee ka o doo anya ka m wee rubere taa."],
            'steadfast' => ['affirmation' => "Ọrụ m n’ime Onyenwe anyị abụghị ihe efu.", 'prayer' => "Onyenwe anyị, mee ka m guzosie ike mgbe mkpụrụ na-egbu oge, mee ka m bụrụ onye kwesịrị ntụkwasị obi mgbe nrube isi zoro ezo."],
            'mercy' => ['affirmation' => "Ebere ọhụrụ na-ezute m n'ụtụtụ a.", 'prayer' => "Nna nke ebere, ka ọmịiko gị gwọọ m ma si n’ime m ruo ndị ọzọ."],
            'courage' => ['affirmation' => "Enwere m ike ịga n’ihu n’obi ike n’ihi na Chineke nọnyere m.", 'prayer' => "Onyenwe anyị, wepụ egwu n’ebe nrube isi na-akpọ m ịga n’ihu."],
            'endurance' => ['affirmation' => "Kraịst na-enye m ike ịga n’ihu n’okwukwe.", 'prayer' => "Jizọs, mee ka ntachi obi m sie ike, chekwaa obi m ka ọ bụrụ nke kwesịrị ntụkwasị obi n’etiti njem."],
            'growth' => ['affirmation' => "Enwere m mgbọrọgwụ n’ime Kraịst ma na-eto n’okwukwe.", 'prayer' => "Onyenwe anyị, mee ka mgbọrọgwụ m miri emi n’ime gị, kụọ omume ga-eme ka okwukwe m mịa mkpụrụ."],
            'provision' => ['affirmation' => "Onyenwe anyị na-azụ m ma na-enye m ihe m chọrọ.", 'prayer' => "Onye ọzụzụ mkpụrụ obi m, kụziere m ịtụkwasị nlekọta gị obi tupu m chọọ nchekwa nke m."],
        ],
    ];

    private const SEO_TITLES = [
        'en' => 'MannaRise Daily Devotion for :date | :theme and :reference',
        'fr' => 'Méditation MannaRise du :date | :theme et :reference',
        'es' => 'Devocional MannaRise para :date | :theme y :reference',
        'pt' => 'Devocional MannaRise para :date | :theme e :reference',
        'sw' => 'Ibada ya MannaRise ya :date | :theme na :reference',
        'yo' => 'Ìfọkànsìn MannaRise fún :date | :theme àti :reference',
        'ha' => 'Ibada ta MannaRise ta :date | :theme da :reference',
        'ig' => 'Ntụgharị uche MannaRise maka :date | :theme na :reference',
    ];

    private const SEO_DESCRIPTIONS = [
        'en' => ':affirmation Read :reference, pray today’s prayer, and reflect with the daily journal prompt.',
        'fr' => ':affirmation Lisez :reference, priez la prière du jour et méditez avec la question de journal.',
        'es' => ':affirmation Lee :reference, ora la oración de hoy y reflexiona con la pregunta de diario.',
        'pt' => ':affirmation Leia :reference, ore a oração de hoje e reflita com a pergunta de diário.',
        'sw' => ':affirmation Soma :reference, omba maombi ya leo na tafakari kwa swali la jarida.',
        'yo' => ':affirmation Ka :reference, gbàdúrà àdúrà òní, kí o sì ronú pẹ̀lú ìbéèrè ìwé-iranti.',
        'ha' => ':affirmation Karanta :reference, yi addu’ar yau, ka kuma yi tunani da tambayar rubutu.',
        'ig' => ':affirmation Gụọ :reference, kpee ekpere taa ma tụgharịa uche site n’ajụjụ ide ihe.',
    ];

    /**
     * @return array{affirmation:string,prayer:string}
     */
    public static function themeCopy(string $locale, string $theme): array
    {
        return self::COPY[$locale][$theme]
            ?? self::COPY['en'][$theme]
            ?? self::COPY['en']['peace'];
    }

    /**
     * @return array{reference:string,text:string,book_slug:string|null,chapter:int|null,language:string,version:string}|null
     */
    public static function scriptureForTheme(string $theme, string $locale): ?array
    {
        $scripture = self::SCRIPTURES[$theme] ?? self::SCRIPTURES['peace'] ?? null;

        if (! $scripture) {
            return null;
        }

        $locale = self::supportedLocale($locale);

        return [
            'reference' => $scripture['reference'],
            'text' => $scripture['text'][$locale] ?? $scripture['text']['en'],
            'book_slug' => $scripture['book_slug'] ?? null,
            'chapter' => $scripture['chapter'] ?? null,
            'language' => $locale,
            'version' => 'MannaRise',
        ];
    }

    /**
     * @return array{reference:string,text:string,book_slug:string|null,chapter:int|null,language:string,version:string}|null
     */
    public static function scriptureForReference(string $reference, string $locale): ?array
    {
        $reference = self::normalizeReference($reference);

        foreach (self::SCRIPTURES as $theme => $scripture) {
            if (self::normalizeReference($scripture['reference']) === $reference) {
                return self::scriptureForTheme($theme, $locale);
            }
        }

        return null;
    }

    public static function seoTitle(string $locale, string $dateLabel, string $themeLabel, string $reference): string
    {
        return self::fill(self::SEO_TITLES[$locale] ?? self::SEO_TITLES['en'], [
            ':date' => $dateLabel,
            ':theme' => $themeLabel,
            ':reference' => $reference,
        ]);
    }

    public static function seoDescription(string $locale, string $affirmation, string $reference): string
    {
        return self::fill(self::SEO_DESCRIPTIONS[$locale] ?? self::SEO_DESCRIPTIONS['en'], [
            ':affirmation' => $affirmation,
            ':reference' => $reference,
        ]);
    }

    private static function supportedLocale(string $locale): string
    {
        return isset(self::COPY[$locale]) ? $locale : 'en';
    }

    private static function normalizeReference(string $reference): string
    {
        $reference = trim(preg_replace('/\s+/u', ' ', $reference) ?? '');
        $reference = preg_replace('/\s+(KJV|WEB|BBE|WEBBE|OSTV|RV1909|ALMEIDA|SWA)$/i', '', $reference) ?? $reference;

        return strtolower($reference);
    }

    /**
     * @param  array<string, string>  $replacements
     */
    private static function fill(string $template, array $replacements): string
    {
        return strtr($template, $replacements);
    }
}
