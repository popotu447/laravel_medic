# 📁 Laravel API – Zarządzanie plikami pacjentów

To API umożliwia autoryzowanym użytkownikom przeglądanie, dodawanie, edytowanie i usuwanie plików (PDF) przypisanych do pacjentów.

---

## 🔐 Autoryzacja – Basic Auth

Każde żądanie do API wymaga uwierzytelnienia przy użyciu **Basic Auth**.

### Przykładowi użytkownicy:

| E-mail                | Hasło   |
|------------------------|---------|
| `doctor1@example.com` | `haslo1` |
| `doctor2@example.com` | `haslo2` |

---

## 📄 Endpoints

### 🧑‍⚕️ 1. Lista pacjentów zalogowanego lekarza

```
GET /api/patients
```

Zwraca wszystkich pacjentów przypisanych do aktualnie zalogowanego użytkownika (lekarza). Wymaga Basic Auth.

**Przykład:**

```bash
curl -u doctor1@example.com:haslo1 http://localhost:8000/api/patients
```

---

### 🔍 2. Lista plików danego pacjenta

```
GET /api/patients/{patient}/files
```

Zwraca wszystkie pliki przypisane do pacjenta należącego do zalogowanego użytkownika.

**Przykład:**

```bash
curl -u doctor1@example.com:haslo1 http://localhost:8000/api/patients/1/files
```

---

### 📤 3. Dodanie nowego pliku (PDF)

```
POST /api/patients/{patient}/files
```

**Parametry (multipart/form-data):**

- `file` – plik PDF (wymagany)
- `description` – opis pliku (opcjonalny)

**Przykład:**

```bash
curl -u doctor1@example.com:haslo1 \
  -X POST http://localhost:8000/api/patients/1/files \
  -F "file=@/ścieżka/do/plik.pdf" \
  -F "description=Wynik badania"
```

---

### ✏️ 4. Aktualizacja pliku

```
PUT /api/files/{file}
```

Jeśli wysyłasz plik, użyj metody POST + `_method=PUT`.

**Przykład:**

```bash
curl -u doctor1@example.com:haslo1 \
  -X POST http://localhost:8000/api/files/7 \
  -F "_method=PUT" \
  -F "description=Nowy opis" \
  -F "file=@nowy_plik.pdf"
```

---

### 🗑️ 5. Usuwanie pliku

```
DELETE /api/files/{file}
```

**Przykład:**

```bash
curl -u doctor1@example.com:haslo1 \
  -X DELETE http://localhost:8000/api/files/7
```

---

### 📂 6. Pobranie pliku

```
GET /api/files/{file}
```

Wyświetla PDF w przeglądarce (nagłówek `Content-Disposition: inline`).

---

## ⚙️ Wymagania środowiska

- PHP 8.1+
- Laravel 11
- Kolejki: `php artisan queue:work` (jeśli upload jest asynchroniczny)
- Pliki są przechowywane w: `storage/app/public/uploads`

Uruchom komendę:

```bash
php artisan storage:link
```

aby umożliwić dostęp do plików przez URL (`/storage/...`).

---

## 📌 Uwagi

- Użytkownik może zarządzać **tylko plikami pacjentów, których jest właścicielem**
- Obsługiwane są wyłącznie pliki **PDF**
- Maksymalny rozmiar pliku określa zmienna `.env`:  
  `PATIENT_FILE_MAX_SIZE=10240` (czyli 10 MB)

---

## ✅ Status odpowiedzi

| Kod | Znaczenie             |
|-----|------------------------|
| 200 | OK                     |
| 201 | Plik utworzony         |
| 202 | Plik przyjęty do kolejki |
| 403 | Brak dostępu (unauthorized) |
| 404 | Nie znaleziono         |

---

## 🤝 Kontakt

Projekt prywatny – stworzony na potrzeby dokumentowania i testowania REST API.
