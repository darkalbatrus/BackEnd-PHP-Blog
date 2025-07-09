<?php
include "./include/layout/header.php";
$posts = $db->query("SELECT * FROM posts ORDER BY id DESC");
// echo "<pre>";
// print_r($posts->fetchAll())
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
                            <?php
                            $categoryId = $post['category_id'];
                            $category = ($db->query("SELECT title FROM categories WHERE id = $categoryId")->fetch())['title'];
                            ?>
                            <div class="col-sm-6">
                                <div class="card">
                                    <img
                                        src="./uploads/posts/<?= $post['image'] ?>"
                                        class="card-img-top"
                                        alt="post-image" />
                                    <div class="card-body">
                                        <div
                                            class="d-flex justify-content-between">
                                            <h5 class="card-title fw-bold">
                                                <?= $post['title'] ?>
                                            </h5>
                                            <div>
                                                <span
                                                    class="badge text-bg-secondary"><?= $category ?></span>
                                            </div>
                                        </div>
                                        <p
                                            class="card-text text-secondary pt-3">
                                            <?= substr($post['body'], 0, 600) ?>
                                        </p>
                                        <div
                                            class="d-flex justify-content-between align-items-center">
                                            <a
                                                href="single.html"
                                                class="btn btn-sm btn-dark">مشاهده</a>

                                            <p class="fs-7 mb-0">
                                                نویسنده : <?= $post['author'] ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    <?php endif ?>

                </div>
            </div>

            <!-- Sidebar Section -->
            <?php include "./include/layout/sidebar.php" ?>
        </div>

    </section>
</main>

<?php
include "./include/layout/footer.php";
?>