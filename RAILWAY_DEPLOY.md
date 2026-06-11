# دليل الرفع والتشغيل على منصة Railway (SmartScreen)

هذا الدليل يشرح كيفية إعداد وتشغيل تطبيق SmartScreen (Laravel) على منصة Railway بعد أن قمنا برفع الكود إلى مستودع GitHub الخاص بك.

---

## 1. ربط المشروع بـ Railway
1. افتح لوحة تحكم [منصة Railway](https://railway.app/).
2. اضغط على **New Project** ثم اختر **Deploy from GitHub repo**.
3. اختر المستودع الخاص بك: `SmartScreen`.

---

## 2. إعداد قاعدة البيانات (Database Setup)
تطبيق Laravel يحتاج إلى قاعدة بيانات لحفظ الشاشات والشرائح. يُنصح باستخدام **PostgreSQL** أو **MySQL** على Railway لأن ملفات SQLite تُحذف عند كل عملية بناء (Rebuild) أو إعادة تشغيل للحاوية.

### خطوات إنشاء قاعدة بيانات على Railway:
1. داخل لوحة تحكم المشروع في Railway، اضغط على **+ Add** (أو **New**).
2. اختر **Database** ثم اختر **Add PostgreSQL** (أو MySQL).
3. ستقوم Railway بإنشاء قاعدة البيانات تلقائياً وتوفير متغيرات البيئة (Environment Variables) الخاصة بها.
4. قم بربط قاعدة البيانات بالتطبيق من خلال متغيرات البيئة أدناه.

---

## 3. إعداد متغيرات البيئة (Environment Variables)
في لوحة تحكم تطبيق `SmartScreen` على Railway، انتقل إلى تبويب **Variables** وأضف المتغيرات التالية:

| اسم المتغير (Key) | القيمة المقترحة (Value) / الشرح |
| :--- | :--- |
| `APP_KEY` | مفتاح التطبيق الخاص بك (يمكنك نسخه من ملف `.env` المحلي لديك، أو توليده). *ضروري جداً لتشفير الجلسات.* |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | الرابط الذي توفره لك منصة Railway للتطبيق (مثال: `https://smartscreen.up.railway.app`). |
| `DB_CONNECTION` | `pgsql` (إذا اخترت PostgreSQL) أو `mysql` (إذا اخترت MySQL). |
| `DB_HOST` | `${{Postgres.DATABASE_HOST}}` (الربط التلقائي بمتغيرات قاعدة بيانات Railway). |
| `DB_PORT` | `${{Postgres.DATABASE_PORT}}` |
| `DB_DATABASE` | `${{Postgres.DATABASE_NAME}}` |
| `DB_USERNAME` | `${{Postgres.DATABASE_USER}}` |
| `DB_PASSWORD` | `${{Postgres.DATABASE_PASSWORD}}` |
| `FILESYSTEM_DISK` | `public` |

*ملاحظة: صيغة `${{Postgres.DATABASE_HOST}}` هي ميزة في Railway تقوم بربط الحقول تلقائياً بقاعدة البيانات المنشأة في نفس المشروع.*

---

## 4. إعداد أمر التشغيل والـ Migrations (Start Command)
بشكل افتراضي، تستخدم Railway نظام **Nixpacks** لبناء وتعبئة مشاريع Laravel تلقائياً (تثبيت PHP و Composer و Node.js وبناء ملفات CSS/JS عبر Vite).

لتشغيل الهجرات (Migrations) تلقائياً عند كل رفع وتحديث للكود، قم بتعديل **Start Command** الخاص بالتطبيق في إعدادات الخدمة (**Settings** -> **Deploy** -> **Start Command**) واجعله كالتالي:

```bash
php artisan migrate --force && php artisan optimize && php artisan serve --host 0.0.0.0 --port ${PORT:-8080}
```

---

## 5. تفعيل مجلد التخزين (Storage Link)
الشرائح والصور التي ترفعها يتم تخزينها في مجلد `storage/app/public` ويجب ربطها بمجلد `public/storage`.
إذا لم يتم تفعيل الرابط تلقائياً، يمكنك تشغيل الأمر التالي في سطر الأوامر (أو إضافته قبل أمر التشغيل):

```bash
php artisan storage:link
```
*ملاحظة هامة: منصة Railway تستخدم حاويات مؤقتة (Ephemeral Storage)، مما يعني أن الصور المرفوعة محلياً على الهارد ديسك الخاص بالحاوية ستختفي عند عمل Rebuild للمشروع. يفضل في بيئة الإنتاج ربط التطبيق بـ Amazon S3 أو خدمة تخزين سحابي، أو استخدام Railway Volume وتثبيته على مجلد `/app/storage/app/public` للحفاظ على الصور.*
