<?php
include "./include/layout/header.php";

if (!isset($_GET['post']) || empty(trim($_GET['post'])) || !is_numeric($_GET['post'])) {
    header("Location: index.php");
    exit;
}

$postId = isset($_GET['post']) && is_numeric($_GET['post']) ? (int)$_GET['post'] : null;
if ($postId) {
    $post = $db->prepare("SELECT * FROM posts WHERE id = :id");
    $post->execute(["id" => $postId]);
    $post = $post->fetch();
} else {
    header("Location: index.php");
    exit;
}


$invalidInputName = "";
$invalidInputText = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['postComment'])) {
    $name = trim($_POST['name']);
    $comment = trim($_POST['comment']);

    if (empty($name)) {
        $invalidInputName = "فید نام الزامی است";
    } elseif (mb_strlen($name) < 3) {
        $invalidInputName = 'حداقل 3 کاراکتر باشد';
    } elseif (mb_strlen($name) > 20) {
        $invalidInputName = 'حداکثر 20 کاراکتر باشد';
    } elseif (!preg_match('/^[\p{L}0-9\s]+$/u', $name)) {
        $invalidInputName = 'فقط حروف و اعداد مجاز هستند';
    }

    if (empty($comment)) {
        $invalidInputText = "متنی وارد نکرده اید ...";
    } elseif (mb_strlen($comment) < 3) {
        $invalidInputText = 'حداقل 3 کاراکتر باشد';
    } elseif (mb_strlen($comment) > 1000) {
        $invalidInputText = 'حداکثر 1000 کاراکتر باشد';
    } elseif (!preg_match('/^[\p{L}0-9\s]+$/u', $comment)) {
        $invalidInputText = 'فقط حروف و اعداد مجاز هستند';
    }

    if (empty($invalidInputName) && empty($invalidInputText)) {
        $commentInsert = $db->prepare("INSERT INTO comments (name, comment, post_id) VALUES (:name, :comment, :postId)");
        $commentInsert->execute(['name' => $name, 'comment' => $comment, 'postId' => $postId]);
        $successMessage = "کامنت شما ثبت و بعد بررسی نمایش داده میشود";
    }
}

$comments = $db->prepare("SELECT * FROM comments WHERE post_id = :id AND status = '1'");
$comments->execute(['id' => $postId]);
?>

<main>
    <section class="mt-4">
        <div class="row">
            <?php if (empty($post)): ?>
                <div class="col-lg-8">
                    <div class="alert alert-danger">
                        مقاله ای یافت نشد ...
                    </div>
                </div>
            <?php else: ?>
                <?php
                $categoryId = (int)$post['category_id'];
                $category = $db->prepare("SELECT title FROM categories WHERE id = :id");
                $category->execute(['id' => $categoryId]);
                $categoryTitle = $category->fetchColumn();

                $imgId = (int)$post['file_id'];
                $imageName = $db->prepare("SELECT name FROM files WHERE id = :id");
                $imageName->execute(['id' => $imgId]);
                $image = $imageName->fetchColumn();
                ?>
                <div class="col-lg-8">
                    <div class="row justify-content-center">
                        <div class="col">
                            <div class="card">
                                <img src="./uploads/posts/<?= htmlspecialchars($image) ?>" class="card-img-top" alt="post-image" />
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title fw-bold"><?= htmlspecialchars($post['title']) ?></h5>
                                        <div>
                                            <span class="badge text-bg-secondary"><?= htmlspecialchars($categoryTitle) ?></span>
                                        </div>
                                    </div>
                                    <p class="card-text text-secondary text-justify pt-3"><?= nl2br(htmlspecialchars($post['body'])) ?></p>
                                    <div>
                                        <p class="fs-6 mt-5 mb-0">نویسنده : <?= htmlspecialchars($post['author']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="mt-4" />
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <p class="fw-bold fs-5">ارسال کامنت</p>
                                    <form method="post">
                                        <div class="mb-3">
                                            <label class="form-label">نام</label>
                                            <input name="name" type="text" class="form-control" />
                                            <div class="text-danger form-text"><?= $invalidInputName ?></div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">متن کامنت</label>
                                            <textarea name="comment" class="form-control" rows="3"></textarea>
                                            <div class="text-danger form-text"><?= $invalidInputText ?></div>
                                        </div>
                                        <button name="postComment" type="submit" class="btn btn-dark">ارسال</button>
                                        <div class="text-success form-text"><?= $successMessage ?></div>
                                    </form>
                                </div>
                            </div>

                            <hr class="mt-4" />
                            <p class="fw-bold fs-6">تعداد کامنت : <?= $comments->rowCount() ?></p>
                            <?php foreach ($comments as $comment): ?>
                                <div class="card bg-light-subtle mb-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <img src="./assets/images/profile.png" width="45" height="45" alt="user-profile" />
                                            <h5 class="card-title me-2 mb-0"><?= htmlspecialchars($comment['name']) ?></h5>
                                        </div>
                                        <p class="card-text pt-3 pr-3"><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Sidebar Section -->
            <?php include "./include/layout/sidebar.php"; ?>
        </div>
    </section>
</main>

<?php
include "./include/layout/footer.php";
?>