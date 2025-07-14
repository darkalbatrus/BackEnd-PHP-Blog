<?php
include "../../include/layout/header.php";
$invalidInputTitle = '';

if (!isset($_GET['id']) || empty(trim($_GET['id']))) {
    header("Location: index.php");
    exit;
} else {
    $categoryId = trim($_GET['id']);
    $category = $db->prepare('SELECT * FROM categories WHERE id = :id');
    $category->execute(['id' => $categoryId]);
    $category = $category->fetch();
    if (!$category) {
        header("Location: index.php");
        exit;
    }
}

if (isset($_POST['editCategory'])) {
    $title = trim($_POST['title']);

    if (empty($title)) {
        $invalidInputTitle = 'فیلد عنوان الزامی است';
    } elseif (mb_strlen($title) < 3) {
        $invalidInputTitle = 'حداقل 3 کاراکتر باشد';
    } elseif (mb_strlen($title) > 20) {
        $invalidInputTitle = 'حداکثر 20 کاراکتر باشد';
    } elseif (!preg_match('/^[\p{L}0-9\s]+$/u', $title)) {
        $invalidInputTitle = 'فقط حروف و اعداد مجاز هستند';
    } else {
        $title = htmlspecialchars($title);

        $categoryUpdate = $db->prepare("UPDATE categories SET title = :title WHERE id = :id");
        $categoryUpdate->execute(['title' => $title, 'id' => $categoryId]);

        header("Location: index.php");
        exit();
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Section -->
        <?php include "../../include/layout/sidebar.php"; ?>

        <!-- Main Section -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">ویرایش دسته بندی</h1>
            </div>

            <!-- Posts -->
            <div class="mt-4">
                <form method="post" class="row g-4">
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">عنوان دسته بندی</label>
                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($category['title']) ?>" />
                        <?php if (isset($error)): ?>
                            <div class="form-text text-danger"><?= $error ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-12">
                        <button name="editCategory" type="submit" class="btn btn-dark">ویرایش</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<?php include "../../include/layout/footer.php"; ?>