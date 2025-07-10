<?php
$categories = $db->query("SELECT * FROM categories");
?>


<div class="col-lg-4">
  <!-- Sesrch Section -->
  <div class="card">
    <div class="card-body">
      <p class="fw-bold fs-6">جستجو در وبلاگ</p>
      <form action="search.html">
        <div class="input-group mb-3">
          <input
            type="text"
            class="form-control"
            placeholder="جستجو ..." />
          <button
            class="btn btn-secondary"
            type="submit">
            <i class="bi bi-search"></i>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Categories Section -->
  <div class="card mt-4">
    <div class="fw-bold fs-6 card-header">دسته بندی ها</div>
    <ul class="list-group list-group-flush p-0">
      <?php if (!empty($categories)) : ?>
        <?php foreach ($categories as $category): ?>
          <li class="list-group-item">
            <a
              class="link-body-emphasis text-decoration-none"
              href="index.php?category=<?= $category['id'] ?>"><?= $category['title'] ?></a>
          </li>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12">
          <div class="alert alert-info">دسته بندی برای نمایش وجود ندارد</div>
        </div>
      <?php endif; ?>
    </ul>
  </div>

  <?php
  $invalidInputName = '';
  $invalidInputEmail = '';

  if (isset($_POST['subscribe'])) {
    if (empty(trim($_POST['name']))) {
      $invalidInputName = "فید نام الزامی است";
    } elseif (empty(trim($_POST['email']))) {
      $invalidInputEmail = "فید ایمیل الزامی است";
    } else {
      $name = $_POST['name'];
      $email = $_POST['email'];
      $subscribeInsert = $db->prepare("INSERT INTO subscribers (name,email) VALUES (:name,:email)");
      $subscribeInsert->execute(['name' => $name, 'email' => $email]);

      $successMessage = "عضویت شما با موفقیت انجام شد";
    }
  }
  ?>
  <!-- Subscribue Section -->
  <div class="card mt-4">
    <div class="card-body">
      <p class="fw-bold fs-6">عضویت در خبرنامه</p>
      <form method="post">
        <div class="mb-3">
          <label class="form-label">نام</label>
          <input
            type="text"
            name="name"
            class="form-control" />
          <div class="text-danger form-text"><?= $invalidInputName ?></div>
        </div>
        <div class="mb-3">
          <label class="form-label">ایمیل</label>
          <input
            type="email"
            name="email"
            class="form-control" />
          <div class="text-danger form-text"><?= $invalidInputEmail ?></div>
        </div>
        <div class="d-grid gap-2">
          <button
            name="subscribe"
            type="submit"
            class="btn btn-secondary">
            ارسال
          </button>
          <div class="text-success form-text"><?= $successMessage ?></div>
        </div>
      </form>
    </div>
  </div>

  <!-- About Section -->
  <div class="card mt-4">
    <div class="card-body">
      <p class="fw-bold fs-6">درباره ما</p>
      <p class="text-justify">
        لورم ایپسوم متن ساختگی با تولید سادگی
        نامفهوم از صنعت چاپ و با استفاده از
        طراحان گرافیک است. چاپگرها و متون بلکه
        روزنامه و مجله در ستون و سطرآنچنان که
        لازم است و برای شرایط فعلی تکنولوژی مورد
        نیاز و کاربردهای متنوع با هدف بهبود
        ابزارهای کاربردی می باشد.
      </p>
    </div>
  </div>
</div>