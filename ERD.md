# ERD Hotel Luxury

Diagram ditulis dengan Mermaid, otomatis tampil di GitHub, GitLab, dan editor Markdown yang mendukung Mermaid.

```mermaid
erDiagram
    USERS ||--o{ HOTEL_BOOKINGS : "membuat"
    USERS ||--o{ REVIEWS : "menulis"
    COUNTRIES ||--o{ HOTELS : "memiliki"
    CITIES ||--o{ HOTELS : "memiliki"
    HOTELS ||--o{ HOTEL_PHOTOS : "punya galeri"
    HOTELS ||--o{ HOTEL_ROOMS : "punya tipe kamar"
    HOTELS ||--o{ HOTEL_BOOKINGS : "dipesan di"
    HOTELS ||--o{ REVIEWS : "diulas"
    HOTEL_ROOMS ||--o{ HOTEL_BOOKINGS : "dipesan"
    HOTEL_BOOKINGS ||--o| REVIEWS : "diulas satu kali"
    USERS ||--o{ MODEL_HAS_ROLES : "memiliki"
    ROLES ||--o{ MODEL_HAS_ROLES : "diberikan"
    ROLES ||--o{ ROLE_HAS_PERMISSIONS : "mencakup"
    PERMISSIONS ||--o{ ROLE_HAS_PERMISSIONS : "dicakup"
    USERS ||--o{ MODEL_HAS_PERMISSIONS : "izin langsung"
    PERMISSIONS ||--o{ MODEL_HAS_PERMISSIONS : "diberikan"

    USERS {
        bigint id PK
        string name
        string avatar
        string email UK
        timestamp email_verified_at
        string password
        string remember_token
        timestamp created_at
        timestamp updated_at
    }

    COUNTRIES {
        bigint id PK
        string name UK
        string slug UK
        timestamp deleted_at
        timestamp created_at
        timestamp updated_at
    }

    CITIES {
        bigint id PK
        string name UK
        string slug UK
        timestamp deleted_at
        timestamp created_at
        timestamp updated_at
    }

    HOTELS {
        bigint id PK
        string name
        string slug UK
        string thumbnail
        string address
        text link_gmaps
        bigint star_level
        bigint city_id FK
        bigint country_id FK
        timestamp deleted_at
        timestamp created_at
        timestamp updated_at
    }

    HOTEL_PHOTOS {
        bigint id PK
        string photo
        bigint hotel_id FK
        timestamp deleted_at
        timestamp created_at
        timestamp updated_at
    }

    HOTEL_ROOMS {
        bigint id PK
        string name
        string photo
        bigint price
        bigint total_people
        int total_rooms "default 1, jumlah unit per tipe"
        bigint hotel_id FK
        timestamp deleted_at
        timestamp created_at
        timestamp updated_at
    }

    HOTEL_BOOKINGS {
        bigint id PK
        bigint user_id FK
        bigint hotel_id FK
        bigint hotel_room_id FK
        date checkin_at
        date checkout_at
        bigint total_days
        bigint total_amount
        boolean is_paid
        string status "pending, waiting_verification, paid, rejected, expired, cancelled, checked_in, checked_out, no_show"
        timestamp expires_at "batas waktu bayar"
        string reject_reason
        string proof "nullable, disk privat"
        timestamp paid_at
        timestamp checked_in_at
        timestamp checked_out_at
        timestamp deleted_at
        timestamp created_at
        timestamp updated_at
    }

    REVIEWS {
        bigint id PK
        bigint hotel_booking_id FK, UK
        bigint user_id FK
        bigint hotel_id FK
        tinyint rating "1 sampai 5"
        text comment
        boolean is_hidden "moderasi admin"
        timestamp created_at
        timestamp updated_at
    }

    ROLES {
        bigint id PK
        string name
        string guard_name
    }

    PERMISSIONS {
        bigint id PK
        string name
        string guard_name
    }

    MODEL_HAS_ROLES {
        bigint role_id FK
        string model_type
        bigint model_id FK
    }

    MODEL_HAS_PERMISSIONS {
        bigint permission_id FK
        string model_type
        bigint model_id FK
    }

    ROLE_HAS_PERMISSIONS {
        bigint permission_id FK
        bigint role_id FK
    }
```

## Catatan Desain

| Topik | Penjelasan |
|---|---|
| Soft delete | `countries`, `cities`, `hotels`, `hotel_photos`, `hotel_rooms`, dan `hotel_bookings` memakai `deleted_at`. Booking yang dihapus tamu hanya disembunyikan, review tetap tampil. |
| Ketersediaan kamar | Tidak ada tabel unit kamar. `hotel_rooms.total_rooms` adalah stok per tipe, dan sisa stok dihitung dari booking yang bentrok tanggal. |
| Booking bentrok | Dua rentang tanggal bentrok bila `checkin_lama < checkout_baru` dan `checkout_lama > checkin_baru`. Hari check-out tidak ikut dihitung. |
| Booking yang menahan stok | Status `paid`, `waiting_verification`, `checked_in`, serta `pending` dan `rejected` selama `expires_at` belum lewat. |
| Satu review per booking | Dijamin oleh unique index pada `reviews.hotel_booking_id`. |
| Index performa | `hb_room_status_dates_idx` (room, status, tanggal), `hb_user_created_idx`, `hb_status_expires_idx`, dan `reviews(hotel_id, is_hidden)`. |
| Tabel framework | `password_reset_tokens`, `sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs` tidak digambar karena tidak berelasi dengan domain hotel. `sessions.user_id` hanya index, bukan foreign key. |
| Relasi `hotel_bookings.hotel_id` | Denormalisasi: sebenarnya bisa diturunkan dari `hotel_rooms.hotel_id`, tetapi disimpan agar query laporan dan dashboard lebih cepat. |

## Diagram Status Booking

```mermaid
stateDiagram-v2
    [*] --> pending : tamu membuat booking
    pending --> waiting_verification : upload bukti bayar
    pending --> expired : lewat batas waktu bayar
    waiting_verification --> paid : admin menyetujui
    waiting_verification --> rejected : admin menolak (alasan wajib)
    rejected --> waiting_verification : tamu upload ulang
    rejected --> expired : lewat batas waktu bayar
    paid --> checked_in : admin check-in
    paid --> no_show : admin tandai atau otomatis lewat check-out
    checked_in --> checked_out : admin check-out atau otomatis
    checked_out --> [*]
    expired --> [*]
    no_show --> [*]
```
