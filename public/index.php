<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/Database.php';

$page = $_GET['page'] ?? 'dashboard';
$allowedPages = ['dashboard', 'customers', 'appointments', 'visits', 'services', 'inventory', 'events', 'employees', 'tracking', 'today', 'reports', 'users'];
if (!in_array($page, $allowedPages, true)) {
    $page = 'dashboard';
}

$kpis = [
    'todayBookings' => 18,
    'todayVisits' => 13,
    'todayRevenue' => 3250,
    'inventoryAlerts' => 4,
];

$employeePerformance = [
    ['name' => 'ريم', 'services' => 24, 'revenue' => 5100],
    ['name' => 'سارة', 'services' => 17, 'revenue' => 3700],
    ['name' => 'نورا', 'services' => 14, 'revenue' => 2900],
];

$userPerformance = [
    ['name' => 'موظفة حجوزات', 'bookings' => 32, 'converted' => 25],
    ['name' => 'الاستقبال', 'bookings' => 21, 'converted' => 14],
    ['name' => 'مدير النظام', 'bookings' => 10, 'converted' => 10],
];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SalonPro | نظام إدارة الصالون</title>
  <link rel="manifest" href="manifest.webmanifest">
  <meta name="theme-color" content="#d63384">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="app-shell">
  <aside id="sidebar" class="sidebar">
    <div class="brand">SalonPro</div>
    <nav>
      <?php foreach ($allowedPages as $menuPage): ?>
        <a href="?page=<?= $menuPage ?>" class="menu-item <?= $page === $menuPage ? 'active' : '' ?>"><?= match($menuPage) {
          'dashboard' => 'لوحة التحكم',
          'customers' => 'الزبائن',
          'appointments' => 'الحجوزات',
          'visits' => 'الزيارات',
          'services' => 'الخدمات',
          'inventory' => 'المخزن',
          'events' => 'المناسبات',
          'employees' => 'الموظفات',
          'tracking' => 'المتابعة',
          'today' => 'مواعيد اليوم',
          'reports' => 'التقارير',
          'users' => 'مستخدمي النظام',
        } ?></a>
      <?php endforeach; ?>
    </nav>
  </aside>

  <main class="content">
    <header class="topbar">
      <button id="sidebarToggle" class="icon-button">☰</button>
      <h1><?= match($page) {
          'dashboard' => 'لوحة تحكم الصالون',
          'customers' => 'إدارة الزبائن',
          'appointments' => 'إدارة الحجوزات',
          'visits' => 'الزيارات والفواتير',
          'services' => 'الخدمات والأسعار',
          'inventory' => 'إدارة المخزن',
          'events' => 'مناسبات الزبائن',
          'employees' => 'الموظفات والخدمات',
          'tracking' => 'صفحة المتابعة',
          'today' => 'مواعيد اليوم',
          'reports' => 'تقارير الأداء',
          'users' => 'مستخدمي النظام',
      } ?></h1>
      <button class="btn" data-modal="quickModal">+ إضافة سريعة</button>
    </header>

    <section class="kpi-grid">
      <article class="card"><h3>حجوزات اليوم</h3><strong><?= $kpis['todayBookings'] ?></strong></article>
      <article class="card"><h3>زيارات اليوم</h3><strong><?= $kpis['todayVisits'] ?></strong></article>
      <article class="card"><h3>إيراد اليوم</h3><strong><?= $kpis['todayRevenue'] ?> ر.س</strong></article>
      <article class="card"><h3>تنبيهات المخزن</h3><strong><?= $kpis['inventoryAlerts'] ?></strong></article>
    </section>

    <?php if ($page === 'dashboard' || $page === 'today'): ?>
      <section class="layout-2">
        <article class="card">
          <h2>تقويم الحجوزات</h2>
          <div class="calendar">
            <?php for ($i = 1; $i <= 30; $i++): ?>
              <div class="day <?= in_array($i, [2, 7, 11, 18, 23], true) ? 'busy' : '' ?>"><?= $i ?></div>
            <?php endfor; ?>
          </div>
        </article>

        <article class="card">
          <h2>مواعيد اليوم</h2>
          <ul class="timeline">
            <li><span>10:00</span> قص + سشوار - أمل</li>
            <li><span>11:30</span> صبغة شعر - هدى</li>
            <li><span>14:00</span> عناية أظافر - جود</li>
            <li><span>17:00</span> ميك أب مناسبة - نجلاء</li>
          </ul>
        </article>
      </section>
    <?php endif; ?>

    <?php if ($page === 'reports' || $page === 'employees'): ?>
      <section class="layout-2">
        <article class="card">
          <h2>تقرير الموظفات</h2>
          <table>
            <thead><tr><th>الاسم</th><th>عدد الخدمات</th><th>الإيراد</th></tr></thead>
            <tbody>
              <?php foreach ($employeePerformance as $employee): ?>
                <tr><td><?= $employee['name'] ?></td><td><?= $employee['services'] ?></td><td><?= $employee['revenue'] ?> ر.س</td></tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </article>
        <article class="card">
          <h2>تقرير مستخدمي النظام</h2>
          <table>
            <thead><tr><th>المستخدم</th><th>الحجوزات</th><th>تحولت لزيارة</th></tr></thead>
            <tbody>
              <?php foreach ($userPerformance as $user): ?>
                <tr><td><?= $user['name'] ?></td><td><?= $user['bookings'] ?></td><td><?= $user['converted'] ?></td></tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </article>
      </section>
    <?php endif; ?>

    <?php if ($page === 'visits'): ?>
      <section class="card">
        <h2>فاتورة حرارية</h2>
        <div id="invoice" class="thermal-invoice">
          <p>صالون SalonPro</p>
          <p>العميلة: أمل عبدالله</p>
          <p>الخدمة: صبغة شعر</p>
          <p>المبلغ: 420 ر.س</p>
          <p>شكراً لزيارتك</p>
        </div>
        <button onclick="window.print()" class="btn">طباعة الفاتورة</button>
      </section>
    <?php endif; ?>

    <?php if (!in_array($page, ['dashboard', 'today', 'reports', 'employees', 'visits'], true)): ?>
      <section class="card">
        <h2>وحدة <?= $page ?></h2>
        <p>هذه الوحدة جاهزة للربط بقاعدة البيانات وإضافة CRUD كامل عبر PHP + SQL.</p>
      </section>
    <?php endif; ?>
  </main>
</div>

<div id="quickModal" class="modal">
  <div class="modal-content">
    <h3>إضافة حجز سريع</h3>
    <form>
      <input type="text" placeholder="اسم الزبونة">
      <input type="datetime-local">
      <select><option>اختيار الخدمة</option><option>صبغة</option><option>قص</option></select>
      <button type="button" class="btn" data-close="quickModal">حفظ</button>
    </form>
  </div>
</div>

<script src="assets/js/app.js"></script>
</body>
</html>
