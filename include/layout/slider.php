<?php
// بارگذاری اطلاعات اسلایدها و پست‌ها در یک کوئری
$sliders = $db->query("SELECT posts_slider.*, posts.title, posts.body, posts.image FROM posts_slider JOIN posts ON posts_slider.post_id = posts.id")->fetchAll(PDO::FETCH_ASSOC);
?>

<section>
  <div id="carousel" class="carousel slide">
    <div class="carousel-indicators">
      <?php foreach ($sliders as $index => $slider): ?>
        <button
          type="button"
          data-bs-target="#carousel"
          data-bs-slide-to="<?= $index ?>"
          class="<?= ($index === 0) ? 'active' : '' ?>"></button>
      <?php endforeach; ?>
    </div>
    <div class="carousel-inner rounded">
      <?php if (!empty($sliders)) : ?>
        <?php foreach ($sliders as $index => $slider): ?>
          <div class="carousel-item carousel-height overlay <?= ($index === 0) ? 'active' : '' ?>">
            <img
              src="./uploads/posts/<?= htmlspecialchars($slider['image']) ?>"
              class="d-block w-100"
              alt="post-image" />
            <div class="carousel-caption d-none d-md-block">
              <h5><?= htmlspecialchars($slider['title']) ?></h5>
              <p>
                <?= htmlspecialchars(substr($slider['body'], 0, 200)) . "..." ?>
              </p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <button
      class="carousel-control-prev"
      type="button"
      data-bs-target="#carousel"
      data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button
      class="carousel-control-next"
      type="button"
      data-bs-target="#carousel"
      data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</section>