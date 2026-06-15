# Instalacja — krok po kroku

Instrukcja uruchomienia aplikacji **System Rezerwacji Wizyt Lekarskich** na Windows (VS Code / Cursor).

---

## Wariant A: Docker (zalecany)

### Wymagania

- Docker Desktop (uruchomiony)
- Git (opcjonalnie)

### Kroki

1. **Otwórz terminal** w VS Code (`Ctrl + `` ` ``) w katalogu projektu:

```powershell
cd Aplikacja-System-Rezerwacji-Wizyt-Lekarskich-web
```

2. **Uruchom kontenery** (pierwsze uruchomienie buduje obraz i instaluje `vendor` w szybkim wolumenie Linux):

```powershell
docker compose up -d --build
```

Pierwszy start może trwać kilka minut (Composer + cache Laravel). Kolejne restarty są szybsze.

3. **Skonfiguruj aplikację** (tylko pierwsze uruchomienie):

```powershell
docker exec medical_app php artisan key:generate
docker exec medical_app php artisan storage:link
docker exec medical_app php artisan migrate --seed --force
docker exec medical_app npm install
docker exec medical_app npm run build
```

> **Wydajność na Windows:** katalogi `vendor/`, `bootstrap/cache/`, `storage/framework/` i `node_modules/` są w wolumenach Docker (Linux), nie na dysku Windows — to znacząco przyspiesza ładowanie stron. Sesje i cache Laravel są w plikach (`SESSION_DRIVER=file`, `CACHE_STORE=file`).

4. **Wejdź w przeglądarce:**

| Usługa | Adres |
|--------|-------|
| Aplikacja | http://localhost:8000 |
| phpMyAdmin | http://localhost:8080 |

### phpMyAdmin — dane logowania

- Serwer: `db`
- Użytkownik: `laravel`
- Hasło: `secret`
- Baza: `medical_booking`

### Przydatne komendy Docker

```powershell
# Zatrzymanie
docker compose down

# Logi aplikacji
docker logs medical_app -f

# Ponowne seedowanie bazy (UWAGA: czyści dane!)
docker exec medical_app php artisan migrate:fresh --seed --force

# Po zmianie .env, routów lub widoków — odśwież cache
docker exec medical_app php artisan optimize:clear
docker exec medical_app php artisan optimize

# Po zmianie kodu PHP — zrestartuj kontener (OPcache)
docker compose restart app

# Wejście do kontenera
docker exec -it medical_app bash
```

---

## Wariant B: Lokalnie (bez Docker)

### Wymagania

- PHP **8.3+** z rozszerzeniami: `pdo_mysql`, `mbstring`, `openssl`, `zip`
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) 18+
- MySQL 8 (lokalnie lub XAMPP)

### Kroki

1. **Sklonuj / otwórz projekt** i przejdź do folderu web:

```powershell
cd Aplikacja-System-Rezerwacji-Wizyt-Lekarskich-web
```

2. **Zależności PHP:**

```powershell
composer install
```

3. **Plik środowiskowy:**

```powershell
copy .env.example .env
```

Edytuj `.env`:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=medical_booking
DB_USERNAME=root
DB_PASSWORD=twoje_haslo
APP_URL=http://127.0.0.1:8000
```

4. **Klucz i baza:**

```powershell
php artisan key:generate
php artisan migrate --seed
```

5. **Frontend:**

```powershell
npm install
npm run build
```

6. **Uruchom serwer:**

```powershell
php artisan serve
```

Aplikacja: http://127.0.0.1:8000

### Tryb deweloperski (serwer + Vite + kolejka)

```powershell
composer run dev
```

---

## VS Code — konfiguracja

### Otwarcie projektu

1. **Plik → Otwórz folder**
2. Wybierz `Aplikacja-System-Rezerwacji-Wizyt-Lekarskich-web` (lub cały repozytorium nadrzędne)

### Zalecane rozszerzenia

| Rozszerzenie | Cel |
|--------------|-----|
| PHP Intelephense | Autouzupełnianie PHP |
| Laravel Blade Snippets | Składnia Blade |
| Tailwind CSS IntelliSense | Klasy Tailwind |
| Docker | Zarządzanie kontenerami |
| EditorConfig | Spójne formatowanie |

### Terminal zintegrowany

W VS Code domyślnie otwiera się PowerShell. Wszystkie komendy z tej instrukcji działają bezpośrednio w terminalu (`Ctrl + `` ` ``).

### Debugowanie

- Logi Laravel: `storage/logs/laravel.log`
- W Dockerze: `docker exec medical_app tail -f storage/logs/laravel.log`
- Laravel Pail (przy `composer run dev`): logi na żywo w terminalu

---

## Rozwiązywanie problemów

| Problem | Rozwiązanie |
|---------|-------------|
| Port 8000 zajęty | Zmień port w `docker-compose.yml` (`"8001:80"`) lub użyj `php artisan serve --port=8001` |
| Błąd połączenia z bazą | Sprawdź czy kontener `medical_db` działa: `docker compose ps` |
| Brak stylów CSS | Uruchom `docker exec medical_app npm run build` |
| Błąd 500 po migracji | `docker exec medical_app php artisan config:clear` |
| Wolne strony po zmianach | `docker compose restart app` oraz `docker exec medical_app php artisan optimize` |
| Sesja / cache | `docker exec medical_app php artisan optimize:clear` |

---

## Konta testowe po seedzie

| Email | Hasło | Rola |
|-------|-------|------|
| admin@test.pl | password | Admin |
| doktor1@test.pl | password | Lekarz |
| pacjent1@test.pl | password | Pacjent |

Po seedzie warto zweryfikować e-maile (wymagane przez niektóre trasy):

```powershell
docker exec medical_app php artisan tinker --execute="App\Models\User::query()->update(['email_verified_at' => now()]);"
```
