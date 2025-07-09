<?php
include "./include/layout/header.php";

// بارگذاری اطلاعات پست‌ها و دسته‌بندی‌ها در یک کوئری بدون نام مستعار
$posts = $db->query("SELECT posts.*, categories.title AS category_title FROM posts LEFT JOIN categories ON posts.category_id = categories.id ORDER BY posts.id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <!-- Slider Section -->
    <?php include "./include/layout/slider.php"; ?>

    <!-- Content Section -->
    <section class="mt-4">
        <div class="row">
            <!-- Posts Content -->
            <div class="col-lg-8">
                <div class="row g-3">
                    <?php if (!empty($posts)) : ?>
                        <?php foreach ($posts as $post): ?>
                            <div class="col-sm-6">
                                <div class="card">
                                    <img
                                        src="./uploads/posts/<?= htmlspecialchars($post['image']) ?>"
                                        class="card-img-top"
                                        alt="post-image" />
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="card-title fw-bold">
                                                <?= htmlspecialchars($post['title']) ?>
                                            </h5>
                                            <div>
                                                <span class="badge text-bg-secondary">
                                                    <?= htmlspecialchars($post['category_title'] ?? 'متفرقه') ?>
                                                </span>
                                            </div>
                                        </div>
                                        <p class="card-text text-secondary pt-3">
                                            <?= htmlspecialchars(substr($post['body'], 0, 500)) ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="single.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-dark">مشاهده</a>
                                            <p class="fs-7 mb-0">
                                                نویسنده: <?= htmlspecialchars($post['author']) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info">مقاله‌ای برای نمایش وجود ندارد</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar Section -->
            <?php include "./include/layout/sidebar.php"; ?>
        </div>
    </section>
</main>

<?php
include "./include/layout/footer.php";
?>