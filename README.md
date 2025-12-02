# Funnel Widget for WordPress 

**Wersja:** 1.0.0  
**Autor:** EVILLAGE / Rafał  
**Wymagania:** WordPress 5.0+, PHP 7.0+

## Opis

Plugin do inteligentnego wyświetlania powiązanych treści w blogach WordPress, oparty na modelu lejka marketingowego (Marketing Funnel). Automatycznie proponuje czytelnikom treści dopasowane do ich poziomu wiedzy poprzez system TOFU/MOFU/BOFU.

Plugin służy również do zwiększenia zaangażowania użytkowników, poprawy nawigacji oraz wydłużenia czasu przebywania na stronie (dwell time).
Kluczem jest jednak klasterowanie treści według kategorii i poziomu zaawansowania.

## Koncepcja: Lejek Marketingowy (Marketing Funnel)

### Model TOFU/MOFU/BOFU

Plugin wykorzystuje trzystopniowy model lejka marketingowego:

- **TOFU (Top of Funnel)** - Szczyt lejka
  - Treści podstawowe, wprowadzające
  - Dla osób, które dopiero poznają temat
  - Przykład: "Czym jest SEO?", "Podstawy WordPress"

- **MOFU (Middle of Funnel)** - Środek lejka
  - Treści średnio-zaawansowane
  - Dla osób z podstawową wiedzą
  - Przykład: "Jak optymalizować tytuły pod SEO", "Tworzenie motywów WordPress"

- **BOFU (Bottom of Funnel)** - Dół lejka
  - Treści zaawansowane, specjalistyczne
  - Dla ekspertów i osób gotowych do działania
  - Przykład: "Zaawansowane techniki link buildingu", "Optymalizacja WP pod duży ruch"

## Jak Działa Plugin?

### 1. System Tagów

Plugin identyfikuje poziom zaawansowania wpisu na podstawie tagów:

```
tofu → mofu → bofu
```

**Priorytet tagów:** BOFU > MOFU > TOFU (jeśli wpis ma kilka tagów, wygrywa najgłębszy etap)

### 2. Trzy Dynamiczne Sekcje

Na każdym wpisie wyświetlane są do trzech sekcji:

#### Sekcja 1: "To już powinieneś wiedzieć na temat [kategoria]"
- **Kiedy:** Gdy istnieje poprzedni etap lejka
- **Pokazuje:** 3 najstarsze wpisy z poprzedniego etapu w tej samej kategorii
- **Cel:** Upewnić się, że czytelnik ma fundamenty
- **Sortowanie:** Od najstarszych (ASC)

#### Sekcja 2: "Więcej na ten temat [kategoria]"
- **Kiedy:** Zawsze (jeśli są inne wpisy)
- **Pokazuje:** 4 najnowsze wpisy z tego samego etapu w tej samej kategorii
- **Cel:** Pogłębić wiedzę na aktualnym poziomie
- **Sortowanie:** Od najnowszych (DESC)

#### Sekcja 3: "Z tym się jeszcze zapoznaj na temat [kategoria]"
- **Kiedy:** Gdy istnieje następny etap lejka
- **Pokazuje:** 3 najnowsze wpisy z następnego etapu w tej samej kategorii
- **Cel:** Zachęcić do rozwoju i zgłębiania tematu
- **Sortowanie:** Od najnowszych (DESC)

### 3. Filtrowanie przez Kategorie

Plugin używa kategorii WordPress do grupowania treści. Wszystkie sugerowane wpisy muszą być w tej samej kategorii co aktualny wpis, co zapewnia:
- Kontekstowość rekomendacji
- Tematyczną spójność
- Lepsze doświadczenie użytkownika

### 4. Dynamiczne Nagłówki

Nagłówki automatycznie zawierają nazwę kategorii aktualnego wpisu:
- "To już powinieneś wiedzieć na temat **SEO**"
- "Więcej na ten temat **WordPress**"
- "Z tym się jeszcze zapoznaj na temat **Marketing**"

## Scenariusze Użycia

### Scenariusz 1: Blog Edukacyjny - Kurs SEO

**Struktura treści:**
```
Kategoria: SEO

TOFU:
- "Czym jest SEO?" [tag: tofu]
- "Dlaczego SEO jest ważne?" [tag: tofu]
- "Podstawowe terminy SEO" [tag: tofu]

MOFU:
- "Jak optymalizować meta tagi" [tag: mofu]
- "Link building dla początkujących" [tag: mofu]
- "Analiza słów kluczowych" [tag: mofu]

BOFU:
- "Zaawansowany audyt techniczny SEO" [tag: bofu]
- "Strategia SEO dla enterprise" [tag: bofu]
- "International SEO i hreflang" [tag: bofu]
```

**Co się stanie na wpisie "Analiza słów kluczowych" [MOFU]:**

1. **Sekcja "To już powinieneś wiedzieć na temat SEO"**
   - Czym jest SEO?
   - Dlaczego SEO jest ważne?
   - Podstawowe terminy SEO

2. **Sekcja "Więcej na ten temat SEO"**
   - Link building dla początkujących
   - Jak optymalizować meta tagi

3. **Sekcja "Z tym się jeszcze zapoznaj na temat SEO"**
   - International SEO i hreflang
   - Strategia SEO dla enterprise
   - Zaawansowany audyt techniczny SEO

**Efekt:** Czytelnik widzi naturalną ścieżkę rozwoju - od podstaw przez obecny poziom po zaawansowane techniki.

---

### Scenariusz 2: Blog Kulinarny - Nauka Gotowania

**Struktura treści:**
```
Kategoria: Ciasta

TOFU:
- "Podstawowe narzędzia do pieczenia" [tag: tofu]
- "Jak odmierzać składniki" [tag: tofu]

MOFU:
- "Jak upiec idealną biszkopt" [tag: mofu]
- "Sekrety kruchego ciasta" [tag: mofu]

BOFU:
- "Torty wielopoziomowe krok po kroku" [tag: bofu]
- "Dekoracje cukiernicze na level pro" [tag: bofu]

Kategoria: Dania główne

TOFU:
- "Podstawy obróbki mięsa" [tag: tofu]

MOFU:
- "Jak zrobić perfect steak" [tag: mofu]
```

**Co się stanie na wpisie "Jak upiec idealną biszkopt" [MOFU, Kategoria: Ciasta]:**

1. **Sekcja "To już powinieneś wiedzieć na temat Ciasta"**
   - Podstawowe narzędzia do pieczenia
   - Jak odmierzać składniki

2. **Sekcja "Więcej na ten temat Ciasta"**
   - Sekrety kruchego ciasta

3. **Sekcja "Z tym się jeszcze zapoznaj na temat Ciasta"**
   - Dekoracje cukiernicze na level pro
   - Torty wielopoziomowe krok po kroku

**Uwaga:** Wpisy z kategorii "Dania główne" nie zostaną wyświetlone, mimo że też dotyczą gotowania.

---

### Scenariusz 3: Blog Techniczny - WordPress

**Struktura treści:**
```
Kategoria: WordPress

TOFU:
- "Instalacja WordPress" [tag: tofu]
- "Pierwsze kroki w panelu admin" [tag: tofu]
- "Czym są wtyczki i motywy" [tag: tofu]

MOFU:
- "Tworzenie motywu dziecka" [tag: mofu]
- "Hooks i filtry w praktyce" [tag: mofu]
- "Custom Post Types" [tag: mofu]

BOFU:
- "Optymalizacja WP pod duży ruch" [tag: bofu]
- "Tworzenie własnych Gutenberg bloków" [tag: bofu]

Kategoria: PHP

MOFU:
- "Programowanie obiektowe w PHP" [tag: mofu]

BOFU:
- "Design patterns w PHP" [tag: bofu]
```

**Co się stanie na wpisie "Instalacja WordPress" [TOFU, Kategoria: WordPress]:**

1. **Sekcja "To już powinieneś wiedzieć"** → NIE WYŚWIETLA SIĘ (brak wcześniejszego etapu)

2. **Sekcja "Więcej na ten temat WordPress"**
   - Czym są wtyczki i motywy
   - Pierwsze kroki w panelu admin

3. **Sekcja "Z tym się jeszcze zapoznaj na temat WordPress"**
   - Tworzenie własnych Gutenberg bloków
   - Optymalizacja WP pod duży ruch
   - Custom Post Types

**Co się stanie na wpisie "Tworzenie własnych Gutenberg bloków" [BOFU, Kategoria: WordPress]:**

1. **Sekcja "To już powinieneś wiedzieć na temat WordPress"**
   - Tworzenie motywu dziecka
   - Hooks i filtry w praktyce
   - Custom Post Types

2. **Sekcja "Więcej na ten temat WordPress"**
   - Optymalizacja WP pod duży ruch

3. **Sekcja "Z tym się jeszcze zapoznaj"** → NIE WYŚWIETLA SIĘ (brak dalszego etapu)

---

### Scenariusz 4: Blog Marketingowy - Multi-tematyczny

**Struktura treści:**
```
Kategoria: Facebook Ads

TOFU:
- "Pierwsze kroki w Facebook Ads" [tag: tofu]

MOFU:
- "Targetowanie według zainteresowań" [tag: mofu]
- "Remarketing na Facebooku" [tag: mofu]

BOFU:
- "Zaawansowana analityka kampanii FB" [tag: bofu]

Kategoria: Google Ads

TOFU:
- "Czym jest Google Ads" [tag: tofu]

MOFU:
- "Kampanie Search - best practices" [tag: mofu]

BOFU:
- "Automatyzacja stawek w Google Ads" [tag: bofu]
```

**Co się stanie na wpisie "Targetowanie według zainteresowań" [MOFU, Kategoria: Facebook Ads]:**

1. **Sekcja "To już powinieneś wiedzieć na temat Facebook Ads"**
   - Pierwsze kroki w Facebook Ads

2. **Sekcja "Więcej na ten temat Facebook Ads"**
   - Remarketing na Facebooku

3. **Sekcja "Z tym się jeszcze zapoznaj na temat Facebook Ads"**
   - Zaawansowana analityka kampanii FB

**Kluczowa obserwacja:** Wpisy z kategorii "Google Ads" nie będą mieszać się z "Facebook Ads", mimo że obie są o reklamach.

## Instalacja i Konfiguracja

### Metoda 1: Widget (Najłatwiejsza)

1. Przejdź do **Wygląd → Widgety**
2. Znajdź widget **"Lejek treści (TOFU/MOFU/BOFU)"**
3. Przeciągnij do wybranego obszaru widgetów (np. sidebar, footer)
4. Opcjonalnie ustaw tytuł widgetu
5. Zapisz

Widget automatycznie pojawi się tylko na pojedynczych wpisach z tagami tofu/mofu/bofu.

---

### Metoda 2: Shortcode (Elastyczna)

Użyj shortcode'a w treści wpisu, Page Builder lub Template:

```
[ev_funnel_sections]
```

**Przykłady użycia:**

**W Gutenberg Block:**
1. Dodaj blok "Shortcode"
2. Wpisz: `[ev_funnel_sections]`

**W Blocksy Hooks/Content Blocks:**
1. Stwórz nowy Content Block
2. Użyj shortcode'a `[ev_funnel_sections]`
3. Ustaw warunki wyświetlania: `post_type == post`

**W klasycznym edytorze:**
Wstaw shortcode bezpośrednio w treści wpisu.

---

### Metoda 3: Funkcja PHP (Dla Developerów)

Dodaj w szablonie motywu (np. `single.php`):

```php
<?php
if ( function_exists( 'ev_render_funnel_sections' ) ) {
    ev_render_funnel_sections();
}
?>
```

**Przykład: Dodanie po treści wpisu w motywie dziecka**

Plik: `wp-content/themes/twoj-motyw-child/functions.php`

```php
add_filter( 'the_content', 'add_funnel_sections_to_content' );

function add_funnel_sections_to_content( $content ) {
    if ( is_singular( 'post' ) && function_exists( 'ev_render_funnel_sections' ) ) {
        ob_start();
        ev_render_funnel_sections();
        $funnel_html = ob_get_clean();
        
        return $content . $funnel_html;
    }
    
    return $content;
}
```

## Konfiguracja Treści

### Krok 1: Stwórz Tagi

Utwórz trzy tagi w WordPress:

- `tofu` (Top of Funnel)
- `mofu` (Middle of Funnel)
- `bofu` (Bottom of Funnel)

### Krok 2: Przypisz Tagi do Wpisów

Dla każdego wpisu dodaj **JEDEN** tag określający poziom zaawansowania:

- Wpisy podstawowe → tag `tofu`
- Wpisy średnio-zaawansowane → tag `mofu`
- Wpisy zaawansowane → tag `bofu`

### Krok 3: Uporządkuj Kategorie

Upewnij się, że wpisy są w odpowiednich kategoriach tematycznych:

- Kategoria = temat/dziedzina (np. "SEO", "WordPress", "Marketing")
- Tag = poziom zaawansowania (tofu/mofu/bofu)

### Przykład Dobrej Struktury:

```
Wpis: "Podstawy SEO dla początkujących"
├── Kategoria: SEO
└── Tag: tofu

Wpis: "Link building - strategia krok po kroku"
├── Kategoria: SEO
└── Tag: mofu

Wpis: "Technical SEO dla dużych serwisów"
├── Kategoria: SEO
└── Tag: bofu
```

## Najlepsze Praktyki

### 1. Jeden Tag na Wpis
Nie dodawaj kilku tagów lejka do jednego wpisu. Jeśli wpis ma `tofu` i `mofu`, plugin potraktuje go jako `mofu` (priorytet).

### 2. Spójność Kategorii
Używaj kategorii konsekwentnie:
- ✅ "SEO", "WordPress", "Marketing"
- ❌ "SEO", "seo", "Search Engine Optimization"

### 3. Balansuj Treści
Staraj się mieć zbliżoną liczbę wpisów na każdym etapie lejka w danej kategorii:
- Minimum 3-5 wpisów TOFU
- Minimum 3-5 wpisów MOFU
- Minimum 3-5 wpisów BOFU

### 4. Aktualizuj Regularnie
Plugin pokazuje najnowsze wpisy (MOFU/BOFU) i najstarsze (TOFU). Planuj publikacje tak, by użytkownicy zawsze mieli świeże rekomendacje.

### 5. Testuj Nawigację
Przejdź przez ścieżkę użytkownika:
1. Wejdź na wpis TOFU
2. Kliknij w sekcję "Z tym się jeszcze zapoznaj"
3. Sprawdź, czy logika lejka działa

## Dostosowywanie Stylów

Plugin ładuje plik `evillage-funnel-widget.css`. Możesz go edytować lub nadpisać style w motywie:

```css
/* Zmiana koloru nagłówków */
.entry-content .ev-funnel-heading {
    color: #ff6600;
}

/* Zmiana wyglądu linków */
.entry-content .ev-funnel-link:hover {
    color: #0066cc;
    transform: translateX(5px);
}

/* Ukrycie kropek przy elementach */
.entry-content .ev-funnel-item::before {
    display: none;
}
```

## Klasy CSS

Plugin dodaje następujące klasy CSS:

```
.ev-funnel-sections          # Główny kontener
.ev-funnel-section           # Pojedyncza sekcja
.ev-funnel-prev-stage        # Sekcja "To już powinieneś wiedzieć"
.ev-funnel-same-stage        # Sekcja "Więcej na ten temat"
.ev-funnel-next-stage        # Sekcja "Z tym się jeszcze zapoznaj"
.ev-funnel-heading           # Nagłówek sekcji
.ev-funnel-intro             # Tekst wprowadzający
.ev-funnel-list              # Lista artykułów
.ev-funnel-item              # Pojedynczy artykuł
.ev-funnel-link              # Link do artykułu
.ev-funnel-excerpt           # Excerpt artykułu
```

## Funkcje API

### `ev_get_funnel_stage_for_post( $post_id = null )`

Zwraca etap lejka dla danego wpisu.

```php
$stage = ev_get_funnel_stage_for_post( 123 );
// Zwraca: 'tofu', 'mofu', 'bofu' lub null
```

### `ev_build_tax_query( $cat_ids = [], $tag_slugs = [] )`

Buduje tax_query dla WP_Query.

```php
$query = ev_build_tax_query( [5, 10], ['mofu'] );
// Zwraca tablicę tax_query
```

### `ev_render_funnel_sections( $post_id = null )`

Renderuje wszystkie sekcje lejka.

```php
ev_render_funnel_sections(); // Dla aktualnego wpisu
ev_render_funnel_sections( 123 ); // Dla konkretnego wpisu
```

## Wymagania Techniczne

- **WordPress:** 5.0 lub nowszy
- **PHP:** 7.0 lub nowszy
- **Motywy:** Kompatybilny z każdym motywem WordPress
- **Pluginy:** Brak wymaganych zależności

## Kompatybilność

✅ Blocksy Theme  
✅ GeneratePress  
✅ Astra  
✅ Kadence  
✅ Gutenberg Editor  
✅ Classic Editor  
✅ Elementor  
✅ Beaver Builder  

## FAQ

**Q: Czy mogę mieć wpis z kilkoma tagami TOFU/MOFU/BOFU?**  
A: Technicznie tak, ale nie jest to zalecane. Plugin wybierze najgłębszy etap (BOFU > MOFU > TOFU).

**Q: Co jeśli wpis nie ma tagu tofu/mofu/bofu?**  
A: Widget w ogóle się nie pojawi na takim wpisie.

**Q: Czy sekcje zawsze się wyświetlają?**  
A: Nie. Każda sekcja wyświetla się tylko wtedy, gdy:
- Istnieją odpowiednie wpisy
- Istnieje poprzedni/następny etap lejka

**Q: Ile wpisów pokazuje każda sekcja?**  
A: 
- "To już powinieneś wiedzieć": 3 wpisy
- "Więcej na ten temat": 4 wpisy
- "Z tym się jeszcze zapoznaj": 3 wpisy

**Q: Czy mogę zmienić liczbę wyświetlanych wpisów?**  
A: Tak, edytuj wartości `posts_per_page` w pliku wtyczki (linie 131, 174, 217).

**Q: Czy plugin wpływa na SEO?**  
A: Pozytywnie! Zwiększa czas przebywania na stronie (dwell time) i zmniejsza bounce rate poprzez oferowanie powiązanych treści.

**Q: Czy mogę użyć własnych nazw tagów zamiast tofu/mofu/bofu?**  
A: Nie bez modyfikacji kodu. Funkcja `ev_get_funnel_stage_for_post()` sprawdza konkretne slugi.

## Changelog

### 1.0.0 (2025-12-02)
- Pierwsza wersja pluginu
- System TOFU/MOFU/BOFU
- Trzy sekcje treści
- Widget, shortcode i funkcja PHP
- Dynamiczne nagłówki z nazwą kategorii
- Własny plik CSS
- Responsywny layout (3 kolumny na desktop)

## Wsparcie

W razie problemów lub pytań skontaktuj się z autorem.

## Licencja

MIT
