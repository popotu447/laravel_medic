# Laravel Medic API

Prosty backend API do zarządzania pacjentami i ich plikami PDF. Zbudowany w Laravel 11, uruchamiany w kontenerze Docker. Obsługuje uwierzytelnianie Basic Auth, upload plików PDF oraz REST API.

---

## 🚀 Jak uruchomić aplikację

### 1. Sklonuj repozytorium

```bash
git clone https://github.com/popotu447/laravel_medic.git
cd laravel_medic
```

### 2. Uruchom aplikację w Dockerze

```bash
docker compose up --build
```

To polecenie:

- zbuduje kontener z PHP, SQLite i Composerem,
- zainstaluje zależności PHP,
- uruchomi serwer aplikacji Laravel na `http://localhost:8000`.

### 3. Wygeneruj klucz aplikacji (raz)

```bash
docker compose exec app php artisan key:generate
```

---

## 🔐 Domyślni użytkownicy (Basic Auth)

| Email                  | Hasło   |
|------------------------|---------|
| `doctor1@example.com`  | `haslo1` |
| `doctor2@example.com`  | `haslo2` |

---

## 📦 API – wybrane endpointy

### Lista pacjentów zalogowanego użytkownika

```http
GET /api/patients
```

### Upload pliku PDF do pacjenta

```http
POST /api/patients/{patient}/files
```

Dane formularza:
- `file` (plik PDF, maks. 10 MB),
- `description` (opcjonalny tekst)

### Lista plików pacjenta

```http
GET /api/patients/{patient}/files
```

### Podgląd pliku PDF

```http
GET /api/files/{file}
```

Zwraca PDF inline (jeśli istnieje) lub błąd JSON.

### Usunięcie pliku

```http
DELETE /api/files/{file}
```

---

## 🗄 Praca z bazą danych SQLite

Plik bazy znajduje się w:

```
database/database.sqlite
```

Jest dołączony do repozytorium, więc dane testowe będą dostępne od razu po uruchomieniu.

---

## 🧪 Testy i dane

### Wykonanie migracji i seeda:

```bash
docker compose exec app php artisan migrate:fresh --seed
```

Seeder tworzy:

- 2 użytkowników (`doctor1`, `doctor2`),
- po 2 pacjentów na użytkownika,
- pliki testowe PDF dla każdego pacjenta.

### Uruchomienie testów PHPUnit:

```bash
docker compose exec app php artisan test
```

---


## 🛠 Wymagania

- Docker + Docker Compose
- Brak potrzeby instalowania PHP, Composera ani SQLite lokalnie

---

## 📃 Licencja

MIT © 2025
