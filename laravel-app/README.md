## Projekt zaliczeniowy z laboratorium "Programowanie aplikacji internetowych"

## Tematyka projektu: System Prywatnej Biblioteczki

## Autor: Dominik Długołencki

## Funkcjonalności:
- **System uwierzytelniania**: rejestracja użytkowników z walidacją, logowanie z haszowaniem haseł (bcrypt), zabezpieczenie zasobów middleware
- **Zarządzanie książkami (CRUD)**: dodawanie, edycja, usuwanie i przeglądanie książek
- **Upload okładek**: możliwość dodania własnej okładki
- **Biblioteki użytkowników**: każdy użytkownik ma własną, prywatną bibliotekę książek
- **Statusy czytania**: kategoryzacja książek (Planuję, Czytam, Porzucona, Przeczytana)
- **Ulubione książki**: oznaczanie i wyświetlanie ulubionych książek w osobnej sekcji
- **Integracja z OpenLibrary API**: wyszukiwanie i importowanie książek z zewnętrznego API (aktualnie openLibrary nie działa, przez to nie działa import)
- **Oceny książek**: możliwość przypisania oceny w skali 0-10

## Narzędzia i technologie:
- **Strona serwera**: Laravel 11
- **Baza danych**: SQLite, ORM (Eloquent)
- **Strona klienta**: Laravel Blade, CSS3 (plik globalny style.css)
- **Walidacja**: HTML5 validation (pattern, minlength, maxlength) + Laravel Form Requests
- **Integracja API**: Laravel HTTP Client, OpenLibrary REST API
- **Bezpieczeństwo**: CSRF protection, middleware auth, authorization checks

## Wymagania

Wersje programów wykorzystane do tworzenia aplikacji:
- PHP 8.2+
- Composer 2.x
- Laravel Framework 11.x
- SQLite (baza danych)

## Uruchomienie

### Dla gotowego projektu (wszystko już skonfigurowane):

1. Uruchom serwer deweloperski:
   ```bash
   php artisan serve
   ```
2. Otwórz aplikację w przeglądarce pod adresem: `http://localhost:8000`

### Dla nowej instalacji (od zera):

Jeśli instalujesz projekt po raz pierwszy lub na nowym komputerze:

1. Zainstaluj zależności:
   ```bash
   composer install
   ```
2. Skopiuj plik konfiguracyjny (jeśli nie istnieje `.env`):
   ```bash
   copy .env.example .env
   ```
3. Wygeneruj klucz aplikacji (jeśli pusty w `.env`):
   ```bash
   php artisan key:generate
   ```
4. Wykonaj migracje (jeśli pusta baza danych):
   ```bash
   php artisan migrate
   ```
5. Zlinkuj storage dla okładek:
   ```bash
   php artisan storage:link
   ```
6. Uruchom serwer:
   ```bash
   php artisan serve
   ```

## Uwagi

1. **OpenLibrary API**: Integracja może nie działać w niektórych sieciach ze względu na firewall lub problemy z dostępnością API. Funkcjonalność jest w pełni zaimplementowana z obsługą błędów - komunikaty są wyświetlane użytkownikowi, a system pozwala na ręczne dodawanie książek.

2. **Okładki książek**: Przechowywane w `storage/app/public/book-covers/`. Jeśli obrazy nie wyświetlają się, wykonaj polecenie `php artisan storage:link`.

## Pierwsze uruchomienie - Konto testowe

Po uruchomieniu aplikacji:
1. Przejdź do strony rejestracji
2. Utwórz konto testowe:
   - Nazwa użytkownika: TestUser
   - Email: test@test.com
   - Hasło: test1234 (min. 8 znaków)
3. Zaloguj się i dodaj książki do swojej biblioteki
