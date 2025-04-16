# Laravel 12 + Docker Boilerplate

Un mediu complet containerizat pentru dezvoltare Laravel 12, cu Nginx, PHP‑FPM, MySQL și phpMyAdmin.  
Singura cerință locală este **Docker** (Engine) și **Docker Compose**.

---

## 📦 Cerințe

- Docker Engine
- Docker Compose (inclus în Docker Desktop)

---

## 🚀 Instalare & Configurare

1. **Clonează repository‑ul**
   ```bash
   git clone https://github.com/utilizator/laravel-docker.git
   cd laravel-docker
   ```

2. **Instalează dependențe front‑end & back‑end**
   > Dacă ai fișiere JS/CSS sau pachete npm în proiect:
   ```bash
   npm install
   ```  
   Apoi, instalează dependențele PHP Laravel:
   ```bash
   composer install
   ```

3. **Copiază fișierul de mediu**
   ```bash
   cp .env.example .env
   ```

4. **Generează cheia aplicației**
   ```bash
   php artisan key:generate
   ```
   Aceasta va popula `APP_KEY` în `.env`.

5. **Configurează variabilele din `.env`**
   ```env
   APP_NAME=TestareaSoft
   APP_ENV=local
   APP_KEY= # (completat de `key:generate`)
   APP_DEBUG=true
   APP_URL=http://localhost:8000

   LOG_CHANNEL=stack
   LOG_LEVEL=debug

   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306 
   DB_DATABASE=testarea_soft
   DB_USERNAME=user
   DB_PASSWORD=secret

   # Alte servicii (Mail, Redis etc.) le configurezi aici
   ```

---

## 🐳 Pornire containere

În directorul rădăcină al proiectului, rulează:

```bash
docker-compose up -d --build
```

- `app` (PHP‑FPM + Composer)
- `webserver` (Nginx)
- `db` (MySQL)
- `phpmyadmin` (phpMyAdmin)

După ce se termină build‑ul, în interiorul containerului `app` execută migrările:

```bash
docker-compose exec app php artisan migrate
```

---

## 🔌 Oprire containere

- **Oprește & șterge containerele** (volum MySQL păstrat):
  ```bash
  docker-compose down
  ```
- **Doar oprește containerele** (le poți porni din nou cu `docker-compose start`):
  ```bash
  docker-compose stop
  ```

---

## 🌐 Acces & URL‑uri

- **Aplicația Laravel**:  
  http://localhost:8000

- **phpMyAdmin**:  
  http://localhost:8080
    - Host: `db`
    - User: `root`
    - Pass: `root` (sau ce ai definit în `.env`)

---

## ⚙️ Schimbarea porturilor

Dacă porturile implicite (3306, 8000, 8080) sunt ocupate:

1. Editează `docker-compose.yml`, secțiunea `ports:` a serviciului:
   ```yaml
   db:
     ports:
       - "3307:3306"
   webserver:
     ports:
       - "8001:80"
   phpmyadmin:
     ports:
       - "8081:80"
   ```
2. Actualizează `DB_PORT` în `.env` (dacă ai schimbat portul MySQL).
3. Reconstruiește & repornește:
   ```bash
   docker-compose down
   docker-compose up -d --build
   ```

---

## ✅ Checklist final

- [x] Docker & Docker Compose instalate
- [x] Repo clonat
- [x] `npm install` (dacă e cazul)
- [x] `composer install`
- [x] `.env` creat din `.env.example`
- [x] `php artisan key:generate`
- [x] Variabile `.env` configurate
- [x] `docker-compose up -d --build`
- [x] `docker-compose exec app php artisan migrate`
- [x] Aplicație accesibilă la `http://localhost:8000`
