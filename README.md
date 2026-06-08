# System Rezerwacji Wizyt Lekarskich

Aplikacja Laravel do rezerwacji wizyt prywatnych z panelem lekarza, pacjenta i administratora.

**Pełna dokumentacja:** [docs/DOKUMENTACJA.md](docs/DOKUMENTACJA.md)  
**Instalacja:** [docs/INSTALACJA.md](docs/INSTALACJA.md)

## Szybki start (Docker)

```powershell
docker compose up -d --build
docker exec medical_app php artisan migrate --seed --force
docker exec medical_app npm run build
```

→ http://localhost:8000

## Konta demo

Hasło: `password` | Admin: `admin@test.pl` | Lekarz: `doktor1@test.pl` | Pacjent: `pacjent1@test.pl`
