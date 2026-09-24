<?php
http_response_code(404);
$page_title = 'Page Not Found | Triple K Properties';
require __DIR__.'/includes/header.php';
?>
<section class="container section" style="text-align:center;padding:120px 20px">
  <h1 style="font-size:6rem;font-weight:900;color:var(--brand,#e67e22);margin:0">404</h1>
  <h2 style="margin:16px 0 8px">Page Not Found</h2>
  <p style="color:#666;margin-bottom:24px">The page you're looking for doesn't exist or has been moved.</p>
  <a href="/properties/" style="display:inline-block;padding:12px 28px;background:#e67e22;color:#fff;border-radius:8px;text-decoration:none;font-weight:600">
    ← Back to Home
  </a>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>