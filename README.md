# System Rezerwacji Wizyt Lekarskich

Aplikacja webowa (Laravel) do rezerwacji prywatnych wizyt lekarskich — z panelami pacjenta, lekarza i administratora oraz porównaniem terminów z kolejkami NFZ.

## Dokumentacja projektu

Pełna dokumentacja zgodna z wymaganiami zaliczeniowymi:

**[docs/DOKUMENTACJA.md](docs/DOKUMENTACJA.md)**

Zawiera m.in.:

- autorów i podział ról
- użyte technologie
- przeznaczenie i opis funkcjonalności
- schemat ERD (format dbdiagram.io)
- kierunki dalszego rozwoju
- instrukcję uruchomienia krok po kroku
- reprezentatywny przebieg logiki biznesowej (rezerwacja wizyty)

Instalacja szczegółowa: [docs/INSTALACJA.md](docs/INSTALACJA.md)

## Szybki start (Docker)

```powershell
docker compose up -d --build
docker exec medical_app php artisan key:generate
docker exec medical_app php artisan storage:link
docker exec medical_app php artisan migrate --seed --force
docker exec medical_app npm install
docker exec medical_app npm run build
```

→ http://localhost:8000

## Konta demo

Hasło: `password`

| E-mail | Rola |
|--------|------|
| admin@test.pl | Administrator |
| doktor1@test.pl | Lekarz |
| pacjent1@test.pl | Pacjent |

## Autorzy

Krystian Świąder, Igor Styczeń
