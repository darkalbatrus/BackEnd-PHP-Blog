<?php
include "./include/layout/header.php";

if (isset($_GET['entity'], $_GET['action'], $_GET['id'])) {
    $entity = $_GET['entity'];
    $action = $_GET['action'];
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($id !== false && $id > 0) {
        if ($action === "delete") {
            switch ($entity) {
                case 'post':
                    $query = $db->prepare('DELETE FROM posts WHERE id = :id');
                    break;
                case 'comment':
                    $query = $db->prepare('DELETE FROM comments WHERE id = :id');
                    break;
                case 'category':
                    $query = $db->prepare('DELETE FROM categories WHERE id = :id');
                    break;
            }
        } elseif ($action === "approve") {
            $query = $db->prepare("UPDATE comments SET status = '1' WHERE id = :id");
        }

        if (isset($query)) {
            $query->execute([':id' => $id]);
        }
    }
}

$posts = $db->query("SELECT * FROM posts ORDER BY id DESC LIMIT 5");
$comments = $db->query("SELECT * FROM comments ORDER BY id DESC LIMIT 5");
$categories = $db->query("SELECT * FROM categories LIMIT 5");
?>

<div class="container-fluid">
    <div class="row">
        <?php include "./include/layout/sidebar.php"; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">داشبورد</h1>
            </div>

            <!-- Posts Table -->
            <div class="mt-4">
                <h4 class="text-secondary fw-bold">مقالات اخیر</h4>
                <div class="table-responsive small">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>عنوان</th>
                                <th>نویسنده</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($posts->rowCount() > 0): ?>
                                <?php foreach ($posts as $post): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($post['id']) ?></td>
                                        <td><?= htmlspecialchars($post['title']) ?></td>
                                        <td><?= htmlspecialchars($post['author']) ?></td>
                                        <td>
                                            <a href="./pages/posts/edit.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-outline-dark">ویرایش</a>
                                            <a href="index.php?entity=post&action=delete&id=<?= $post['id'] ?>" class="btn btn-sm btn-outline-danger">حذف</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <div class="alert alert-danger">مقاله ای یافت نشد ...</div>
                                    </td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Comments Table -->
            <div class="mt-4">
                <h4 class="text-secondary fw-bold">نظرات اخیر</h4>
                <div class="table-responsive small">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>نام</th>
                                <th>متن نظر</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($comments->rowCount() > 0): ?>
                                <?php foreach ($comments as $comment): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($comment['id']) ?></td>
                                        <td><?= htmlspecialchars($comment['name']) ?></td>
                                        <td><?= htmlspecialchars(substr($comment['comment'], 0, 50)) ?></td>
                                        <td>
                                            <?php if ($comment['status']): ?>
                                                <a
                                                    href="#"
                                                    class="btn btn-sm btn-outline-dark disabled">تایید شده</a>
                                            <?php else: ?>
                                                <a
                                                    href="index.php?entity=comment&action=approve&id=<?= $comment['id'] ?>"
                                                    class="btn btn-sm btn-outline-info">تایید کامنت</a>
                                            <?php endif ?>
                                            <a href="index.php?entity=comment&action=delete&id=<?= $comment['id'] ?>" class="btn btn-sm btn-outline-danger">حذف</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <div class="alert alert-danger">نظری یافت نشد ...</div>
                                    </td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Categories Table -->
            <div class="mt-4">
                <h4 class="text-secondary fw-bold">دسته‌بندی‌ها</h4>
                <div class="table-responsive small">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>عنوان</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($categories->rowCount() > 0): ?>
                                <?php foreach ($categories as $category): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($category['id']) ?></td>
                                        <td><?= htmlspecialchars($category['title']) ?></td>
                                        <td>
                                            <a href="./pages/categories/edit.php?id=<?= htmlspecialchars($category['id']) ?>" class="btn btn-sm btn-outline-dark">ویرایش</a>
                                            <a href="index.php?entity=category&action=delete&id=<?= $category['id'] ?>" class="btn btn-sm btn-outline-danger">حذف</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">
                                        <div class="alert alert-danger">دسته بندی ای یافت نشد ...</div>
                                    </td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include "./include/layout/footer.php"; ?>