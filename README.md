# Laravel REST API: megyék és települések

REST API megyék (`counties`) és települések (`cities`, irányítószámmal) kezelésére.
Laravel + Sanctum (token alapú hitelesítés).

## Telepítés

```
composer install
copy .env.example .env
php artisan key:generate
```

Az `.env` fájlban állítsd be az adatbázist (`DB_DATABASE=laravel_rest_api`, `DB_USERNAME=root`, üres jelszó), hozd létre az adatbázist, majd:

```
php artisan migrate --seed
php artisan serve
```

Az API a `http://127.0.0.1:8000/api` címen érhető el.
Teszt felhasználó: `user@example.com` / `12345678`

## Végpontok

| Metódus | Végpont | Leírás | Token |
|---|---|---|---|
| POST | /api/login | Bejelentkezés, token kérése | Nem |
| GET | /api/counties | Megyék listázása (`?search=` a névre) | Nem |
| POST | /api/counties | Új megye felvétele | Igen |
| PUT | /api/counties/{id} | Megye módosítása | Igen |
| DELETE | /api/counties/{id} | Megye törlése (a városaival együtt) | Igen |
| GET | /api/cities | Települések listázása, keresés, szűrés | Nem |
| POST | /api/cities | Új település felvétele | Igen |
| PUT | /api/cities/{id} | Település módosítása | Igen |
| DELETE | /api/cities/{id} | Település törlése | Igen |

## Keresés és szűrés (GET /api/cities)

| Paraméter | Leírás | Példa |
|---|---|---|
| search | Keresés a település nevében vagy irányítószámában | `/api/cities?search=Vác` |
| county_id | Szűrés megye azonosító szerint | `/api/cities?county_id=1` |
| county | Szűrés megye neve szerint | `/api/cities?county=Pest` |

## Hitelesítés

A `POST /api/login` végpont `email` és `password` mezőkkel tokent ad vissza.
A védett végpontoknál a tokent a fejlécben kell küldeni:

```
Authorization: Bearer <token>
Accept: application/json
```

## Tesztelés

A Bruno kérések a projekt `laravel-rest-api bruno` mappájában vannak.

## Dokumentáció

Az API dokumentáció apiDoc-kal készült (a kommentek a controllerekben vannak).
A legenerált dokumentáció a `public/docs` mappában van.

Újragenerálás (Node.js szükséges):

```
npm install -g apidoc
apidoc -i app/Http/Controllers -o public/docs
```

Megnyitás (`php artisan serve` mellett): `http://127.0.0.1:8000/docs/index.html`