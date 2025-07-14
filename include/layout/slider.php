<?php
$sliders = $db->query("SELECT * FROM posts_slider");
?>

<section>
  <div id="carousel" class="carousel slide">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carousel" data-bs-slide-to="0" class="active"></button>
      <button type="button" data-bs-target="#carousel" data-bs-slide-to="1"></button>
      <button type="button" data-bs-target="#carousel" data-bs-slide-to="2"></button>
    </div>

    <div class="carousel-inner rounded">
      <?php if ($sliders->rowCount() > 0) : ?>
        <?php foreach ($sliders as $slider) : ?>
          <?php
          $postId = $slider['post_id'];
          $postQuery = $db->prepare("SELECT * FROM posts WHERE id = :id");
          $postQuery->execute(['id' => $postId]);
          $post = $postQuery->fetch();

          $imgId = $post['file_id'];
          $imageQuery = $db->prepare("SELECT name FROM files WHERE id = :id");
          $imageQuery->execute(['id' => $imgId]);
          $imgName = $imageQuery->fetchColumn();
          ?>
          <div class="carousel-item overlay carousel-height <?= $slider['active'] ? 'active' : '' ?>">
            <img src="./uploads/posts/<?= htmlspecialchars($imgName) ?>" class="d-block w-100" alt="post-image" />
            <div class="carousel-caption d-none d-md-block">
              <h5><?= htmlspecialchars($post['title']) ?></h5>
              <p><?= htmlspecialchars(substr($post['body'], 0, 200)) ?>...</p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</section>