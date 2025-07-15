<?php
include "./include/layout/header.php";

if (!isset($_GET['search']) || empty(trim($_GET['search']))) {
    header("Location: index.php");
    exit;
}

$keyword = trim($_GET['search']);
$posts = $db->prepare("SELECT * FROM posts WHERE title LIKE :keyword");
$posts->execute(["keyword" => "%$keyword%"]);

?>

<main>
    <!-- Content Section -->
    <section class="mt-4">
        <div class="row">
            <!-- Posts Content -->
            <div class="col-lg-8">
                <div class="row">
                    <div class="col">
                        <div class="alert alert-secondary">
                            پست های مرتبط با کلمه [ <?= htmlspecialchars($keyword) ?> ]
                        </div>
                        <?php if ($posts->rowCount() == 0) : ?>
                            <div class="alert alert-danger">
                                مقاله مورد نظر پیدا نشد !!!!
                            </div>
                        <?php else: ?>
                            <div class="row g-3">
                                <?php if (!empty($posts)) : ?>
                                    <?php foreach ($posts as $post): ?>
                                        <?php
                                        $categoryId = $post['category_id'];
                                        $category = ($db->query("SELECT title FROM categories WHERE id = $categoryId")->fetch())['title'];

                                        $imgId = (int)$post['file_id'];
                                        $imageName = $db->prepare("SELECT name FROM files WHERE id = :id");
                                        $imageName->execute(['id' => $imgId]);
                                        $image = $imageName->fetchColumn();
                                        ?>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <img
                                                    src="./uploads/posts/<?= htmlspecialchars($image) ?>"
                                                    class="card-img-top"
                                                    alt="post-image" />
                                                <div class="card-body">
                                                    <div
                                                        class="d-flex justify-content-between">
                                                        <h5 class="card-title fw-bold">
                                                            <?= htmlspecialchars($post['title']) ?>
                                                        </h5>
                                                        <div>
                                                            <span
                                                                class="badge text-bg-secondary"> <?= htmlspecialchars($category) ?></span>
                                                        </div>
                                                    </div>
                                                    <p
                                                        class="card-text text-secondary pt-3">
                                                        <?= htmlspecialchars(substr($post['body'], 0, 600)) ?>
                                                    </p>
                                                    <div
                                                        class="d-flex justify-content-between align-items-center">
                                                        <a
                                                            href="single.php?post=<?= $post['id'] ?>"
                                                            class="btn btn-sm btn-dark">مشاهده</a>

                                                        <p class="fs-7 mb-0">
                                                            نویسنده : <?= htmlspecialchars($post['author']) ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </div>
                        <?php endif ?>
                    </div>
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